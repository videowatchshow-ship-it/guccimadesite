<?php
/**
 * Cloudflare Configuration
 * Official Documentation: https://developers.cloudflare.com/api/
 * 
 * Features:
 * - Server location hiding (IP obfuscation)
 * - DDoS protection
 * - WAF (Web Application Firewall)
 * - CDN caching
 * - DNS management
 */

// Load environment variables
$env = parse_ini_file(__DIR__ . '/../../.env');

// Cloudflare API Configuration
define('CLOUDFLARE_API_TOKEN', $env['CLOUDFLARE_API_TOKEN'] ?? '');
define('CLOUDFLARE_ZONE_ID', $env['CLOUDFLARE_ZONE_ID'] ?? '');
define('CLOUDFLARE_API_BASE', 'https://api.cloudflare.com/client/v4');

// Security Headers for Server Location Hiding
define('HIDE_SERVER_LOCATION', $env['CLOUDFLARE_HIDE_SERVER_LOCATION'] ?? true);
define('OBFUSCATE_IP', $env['CLOUDFLARE_OBFUSCATE_IP'] ?? true);
define('ENABLE_WAF', $env['CLOUDFLARE_ENABLE_WAF'] ?? true);
define('ENABLE_DDoS', $env['CLOUDFLARE_ENABLE_DDoS'] ?? true);

/**
 * Cloudflare API Helper Class
 */
class CloudflareAPI {
    private $token;
    private $zoneId;
    private $baseUrl;

    public function __construct($token, $zoneId) {
        $this->token = $token;
        $this->zoneId = $zoneId;
        $this->baseUrl = CLOUDFLARE_API_BASE;
    }

    /**
     * Make API request to Cloudflare
     */
    private function request($method, $endpoint, $data = null) {
        $url = $this->baseUrl . $endpoint;
        
        $headers = [
            'Authorization: Bearer ' . $this->token,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'code' => $httpCode,
            'data' => json_decode($response, true)
        ];
    }

    /**
     * Hide server location by setting security headers
     */
    public function hideServerLocation() {
        if (!HIDE_SERVER_LOCATION) return false;

        // Remove server identification headers
        header_remove('Server');
        header_remove('X-Powered-By');
        header_remove('X-AspNet-Version');
        header_remove('X-Runtime-Version');

        // Set generic server header
        header('Server: cloudflare');

        // Hide IP address in headers
        if (OBFUSCATE_IP) {
            header_remove('X-Original-IP');
            header_remove('X-Client-IP');
        }

        return true;
    }

    /**
     * Enable WAF (Web Application Firewall)
     */
    public function enableWAF() {
        if (!ENABLE_WAF) return false;

        $endpoint = "/zones/{$this->zoneId}/firewall/waf/packages";
        $response = $this->request('GET', $endpoint);

        if ($response['code'] === 200 && $response['data']['success']) {
            return $response['data']['result'];
        }

        return false;
    }

    /**
     * Enable DDoS Protection
     */
    public function enableDDoSProtection() {
        if (!ENABLE_DDoS) return false;

        $endpoint = "/zones/{$this->zoneId}/settings/security_level";
        $data = ['value' => 'high'];
        
        $response = $this->request('PATCH', $endpoint, $data);

        if ($response['code'] === 200 && $response['data']['success']) {
            return $response['data']['result'];
        }

        return false;
    }

    /**
     * Get zone settings
     */
    public function getZoneSettings() {
        $endpoint = "/zones/{$this->zoneId}/settings";
        $response = $this->request('GET', $endpoint);

        if ($response['code'] === 200 && $response['data']['success']) {
            return $response['data']['result'];
        }

        return false;
    }

    /**
     * Update DNS records
     */
    public function updateDNSRecord($name, $type, $content, $ttl = 1) {
        $endpoint = "/zones/{$this->zoneId}/dns_records";
        
        // First, get existing record
        $getResponse = $this->request('GET', $endpoint . "?name={$name}&type={$type}");
        
        if ($getResponse['code'] === 200 && $getResponse['data']['success'] && count($getResponse['data']['result']) > 0) {
            $recordId = $getResponse['data']['result'][0]['id'];
            $updateEndpoint = $endpoint . "/{$recordId}";
            
            $data = [
                'type' => $type,
                'name' => $name,
                'content' => $content,
                'ttl' => $ttl,
                'proxied' => true // Enable Cloudflare proxy
            ];
            
            $response = $this->request('PATCH', $updateEndpoint, $data);
        } else {
            // Create new record
            $data = [
                'type' => $type,
                'name' => $name,
                'content' => $content,
                'ttl' => $ttl,
                'proxied' => true
            ];
            
            $response = $this->request('POST', $endpoint, $data);
        }

        if ($response['code'] === 200 && $response['data']['success']) {
            return $response['data']['result'];
        }

        return false;
    }

    /**
     * Enable caching
     */
    public function enableCaching() {
        $endpoint = "/zones/{$this->zoneId}/settings/cache_level";
        $data = ['value' => 'cache_everything'];
        
        $response = $this->request('PATCH', $endpoint, $data);

        if ($response['code'] === 200 && $response['data']['success']) {
            return $response['data']['result'];
        }

        return false;
    }

    /**
     * Set security headers
     */
    public function setSecurityHeaders() {
        $headers = [
            'X-Frame-Options: DENY',
            'X-Content-Type-Options: nosniff',
            'X-XSS-Protection: 1; mode=block',
            'Referrer-Policy: strict-origin-when-cross-origin',
            'Permissions-Policy: geolocation=(), microphone=(), camera=()',
            'Content-Security-Policy: default-src \'self\'; script-src \'self\' \'unsafe-inline\' https://cdn.jsdelivr.net; style-src \'self\' \'unsafe-inline\'; img-src \'self\' data: https:; font-src \'self\' data:; connect-src \'self\' https:; frame-ancestors \'none\';'
        ];

        foreach ($headers as $header) {
            if (!headers_sent()) {
                header($header);
            }
        }

        return true;
    }
}

// Initialize Cloudflare API
$cloudflare = new CloudflareAPI(CLOUDFLARE_API_TOKEN, CLOUDFLARE_ZONE_ID);

// Apply security settings
$cloudflare->hideServerLocation();
$cloudflare->setSecurityHeaders();

// Export for use in other files
return $cloudflare;
?>
