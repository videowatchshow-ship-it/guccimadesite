<?php
/**
 * Cloudflare WAF Configuration
 * Official Documentation: https://developers.cloudflare.com/waf/
 * 
 * Features:
 * - Web Application Firewall (WAF)
 * - DDoS Protection
 * - Bot Management
 * - Rate Limiting
 * - Security Rules
 */

// Load environment variables
$env = parse_ini_file(__DIR__ . '/../../.env');

// Cloudflare WAF Configuration
define('CLOUDFLARE_API_TOKEN', $env['CLOUDFLARE_API_TOKEN'] ?? '');
define('CLOUDFLARE_ZONE_ID', $env['CLOUDFLARE_ZONE_ID'] ?? '');
define('CLOUDFLARE_API_BASE', 'https://api.cloudflare.com/client/v4');

// WAF Settings
define('ENABLE_WAF', $env['CLOUDFLARE_ENABLE_WAF'] ?? true);
define('ENABLE_DDoS', $env['CLOUDFLARE_ENABLE_DDoS'] ?? true);
define('SECURITY_LEVEL', $env['CLOUDFLARE_SECURITY_LEVEL'] ?? 'high');

/**
 * Cloudflare WAF Helper Class
 */
class CloudflareWAF {
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
     * Enable WAF Rules
     */
    public function enableWAFRules() {
        if (!ENABLE_WAF) return false;

        // OWASP ModSecurity Core Rule Set
        $endpoint = "/zones/{$this->zoneId}/firewall/waf/packages";
        $response = $this->request('GET', $endpoint);

        if ($response['code'] === 200 && $response['data']['success']) {
            $packages = $response['data']['result'];
            
            foreach ($packages as $package) {
                // Enable all packages
                $updateEndpoint = "/zones/{$this->zoneId}/firewall/waf/packages/{$package['id']}";
                $updateData = ['sensitivity' => 'high', 'action' => 'block'];
                $this->request('PATCH', $updateEndpoint, $updateData);
            }
            
            return true;
        }

        return false;
    }

    /**
     * Enable DDoS Protection
     */
    public function enableDDoSProtection() {
        if (!ENABLE_DDoS) return false;

        // Set security level to high
        $endpoint = "/zones/{$this->zoneId}/settings/security_level";
        $data = ['value' => SECURITY_LEVEL];
        
        $response = $this->request('PATCH', $endpoint, $data);

        if ($response['code'] === 200 && $response['data']['success']) {
            // Enable DDoS protection
            $ddosEndpoint = "/zones/{$this->zoneId}/settings/advanced_ddos";
            $ddosData = ['value' => 'on'];
            $this->request('PATCH', $ddosEndpoint, $ddosData);
            
            return true;
        }

        return false;
    }

    /**
     * Create Rate Limiting Rule
     */
    public function createRateLimitRule($pattern, $threshold, $period) {
        $endpoint = "/zones/{$this->zoneId}/rate_limit";
        
        $data = [
            'match' => [
                'request' => [
                    'url' => [
                        'path' => [
                            'matches' => $pattern
                        ]
                    ]
                ]
            ],
            'action' => [
                'mode' => 'challenge'
            ],
            'threshold' => $threshold,
            'period' => $period,
            'description' => 'Rate limit rule for ' . $pattern
        ];

        $response = $this->request('POST', $endpoint, $data);

        if ($response['code'] === 200 && $response['data']['success']) {
            return $response['data']['result'];
        }

        return false;
    }

    /**
     * Create Custom WAF Rule
     */
    public function createCustomRule($expression, $action, $description) {
        $endpoint = "/zones/{$this->zoneId}/firewall/rules";
        
        $data = [
            'filter' => [
                'expression' => $expression
            ],
            'action' => $action,
            'description' => $description,
            'priority' => 1
        ];

        $response = $this->request('POST', $endpoint, $data);

        if ($response['code'] === 200 && $response['data']['success']) {
            return $response['data']['result'];
        }

        return false;
    }

    /**
     * Block SQL Injection Attempts
     */
    public function blockSQLInjection() {
        $expression = '(cf.threat_score > 50) or (http.request.uri.query contains "union") or (http.request.uri.query contains "select") or (http.request.uri.query contains "insert") or (http.request.uri.query contains "delete")';
        
        return $this->createCustomRule($expression, 'block', 'Block SQL Injection Attempts');
    }

    /**
     * Block XSS Attempts
     */
    public function blockXSSAttempts() {
        $expression = '(http.request.uri.query contains "<script") or (http.request.uri.query contains "javascript:") or (http.request.body contains "<script") or (http.request.body contains "onerror=")';
        
        return $this->createCustomRule($expression, 'block', 'Block XSS Attempts');
    }

    /**
     * Block Suspicious User Agents
     */
    public function blockSuspiciousUserAgents() {
        $expression = '(http.user_agent contains "bot") or (http.user_agent contains "crawler") or (http.user_agent contains "scanner")';
        
        return $this->createCustomRule($expression, 'challenge', 'Challenge Suspicious User Agents');
    }

    /**
     * Enable Bot Management
     */
    public function enableBotManagement() {
        $endpoint = "/zones/{$this->zoneId}/settings/bot_management";
        $data = ['value' => 'on'];
        
        $response = $this->request('PATCH', $endpoint, $data);

        if ($response['code'] === 200 && $response['data']['success']) {
            return true;
        }

        return false;
    }

    /**
     * Get WAF Status
     */
    public function getWAFStatus() {
        $endpoint = "/zones/{$this->zoneId}/settings/security_level";
        $response = $this->request('GET', $endpoint);

        if ($response['code'] === 200 && $response['data']['success']) {
            return $response['data']['result'];
        }

        return false;
    }

    /**
     * Get DDoS Status
     */
    public function getDDoSStatus() {
        $endpoint = "/zones/{$this->zoneId}/settings/advanced_ddos";
        $response = $this->request('GET', $endpoint);

        if ($response['code'] === 200 && $response['data']['success']) {
            return $response['data']['result'];
        }

        return false;
    }

    /**
     * Initialize WAF and DDoS Protection
     */
    public function initialize() {
        $results = [
            'waf_enabled' => $this->enableWAFRules(),
            'ddos_enabled' => $this->enableDDoSProtection(),
            'sql_injection_blocked' => $this->blockSQLInjection(),
            'xss_blocked' => $this->blockXSSAttempts(),
            'suspicious_ua_blocked' => $this->blockSuspiciousUserAgents(),
            'bot_management_enabled' => $this->enableBotManagement()
        ];

        return $results;
    }
}

// Initialize Cloudflare WAF
$cloudflareWAF = new CloudflareWAF(CLOUDFLARE_API_TOKEN, CLOUDFLARE_ZONE_ID);

// Export for use in other files
return $cloudflareWAF;
?>
