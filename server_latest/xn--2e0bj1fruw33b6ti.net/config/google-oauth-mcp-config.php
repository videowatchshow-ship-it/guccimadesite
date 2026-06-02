<?php
/**
 * Google OAuth MCP Configuration
 * Official Documentation: https://developers.google.com/identity/protocols/oauth2
 * MCP Documentation: https://github.com/modelcontextprotocol
 * 
 * Features:
 * - Google OAuth 2.0 authentication
 * - MCP (Model Context Protocol) integration
 * - User profile management
 * - Session management
 * - Admin email verification
 */

// Load environment variables
$env = parse_ini_file(__DIR__ . '/../../.env');

// Google OAuth Configuration
define('GOOGLE_CLIENT_ID', $env['GOOGLE_CLIENT_ID'] ?? '');
define('GOOGLE_ADMIN_EMAIL', $env['GOOGLE_ADMIN_EMAIL'] ?? '');
define('GOOGLE_OAUTH_SCOPE', $env['GOOGLE_OAUTH_SCOPE'] ?? 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile');
define('GOOGLE_MCP_ENDPOINT', $env['GOOGLE_MCP_ENDPOINT'] ?? 'https://mcp.googleapis.com/oauth');
define('GOOGLE_MCP_ENABLED', $env['GOOGLE_MCP_ENABLED'] ?? true);

// OAuth Configuration
define('OAUTH_REDIRECT_URI', 'https://xn--2e0bj1fruw33b6ti.net/auth/google-callback.php');
define('OAUTH_TOKEN_ENDPOINT', 'https://oauth2.googleapis.com/token');
define('OAUTH_USERINFO_ENDPOINT', 'https://www.googleapis.com/oauth2/v2/userinfo');

/**
 * Google OAuth MCP Helper Class
 */
class GoogleOAuthMCP {
    private $clientId;
    private $adminEmail;
    private $scope;
    private $redirectUri;

    public function __construct($clientId, $adminEmail, $scope, $redirectUri) {
        $this->clientId = $clientId;
        $this->adminEmail = $adminEmail;
        $this->scope = $scope;
        $this->redirectUri = $redirectUri;
    }

    /**
     * Generate OAuth authorization URL
     */
    public function getAuthorizationUrl($state = null) {
        if (!$state) {
            $state = bin2hex(random_bytes(16));
            $_SESSION['oauth_state'] = $state;
        }

        $params = [
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'response_type' => 'code',
            'scope' => $this->scope,
            'state' => $state,
            'access_type' => 'offline',
            'prompt' => 'consent'
        ];

        return 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);
    }

    /**
     * Exchange authorization code for access token
     */
    public function exchangeCodeForToken($code) {
        $data = [
            'client_id' => $this->clientId,
            'code' => $code,
            'redirect_uri' => $this->redirectUri,
            'grant_type' => 'authorization_code'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, OAUTH_TOKEN_ENDPOINT);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            return json_decode($response, true);
        }

        return false;
    }

    /**
     * Get user info from access token
     */
    public function getUserInfo($accessToken) {
        $headers = [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, OAUTH_USERINFO_ENDPOINT);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            return json_decode($response, true);
        }

        return false;
    }

    /**
     * Verify if user is admin
     */
    public function isAdmin($email) {
        return strtolower($email) === strtolower($this->adminEmail);
    }

    /**
     * Validate OAuth state
     */
    public function validateState($state) {
        if (empty($_SESSION['oauth_state']) || $_SESSION['oauth_state'] !== $state) {
            return false;
        }
        unset($_SESSION['oauth_state']);
        return true;
    }

    /**
     * Get MCP configuration
     */
    public function getMCPConfig() {
        return [
            'endpoint' => GOOGLE_MCP_ENDPOINT,
            'client_id' => $this->clientId,
            'admin_email' => $this->adminEmail,
            'scope' => $this->scope,
            'enabled' => GOOGLE_MCP_ENABLED,
            'version' => '2026-05-22'
        ];
    }

    /**
     * Verify MCP token
     */
    public function verifyMCPToken($token) {
        if (empty($token)) {
            return false;
        }

        // Token format: Bearer <token>
        if (strpos($token, 'Bearer ') === 0) {
            $token = substr($token, 7);
        }

        // Verify token structure (JWT format)
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return false;
        }

        // Decode and verify
        $payload = json_decode(base64_decode(strtr($parts[1], '-_', '+/')), true);
        
        if (!$payload) {
            return false;
        }

        // Check expiration
        if (isset($payload['exp']) && $payload['exp'] < time()) {
            return false;
        }

        return $payload;
    }

    /**
     * Create MCP session
     */
    public function createMCPSession($userInfo) {
        $_SESSION['mcp_user'] = [
            'id' => $userInfo['id'] ?? '',
            'email' => $userInfo['email'] ?? '',
            'name' => $userInfo['name'] ?? '',
            'picture' => $userInfo['picture'] ?? '',
            'is_admin' => $this->isAdmin($userInfo['email'] ?? ''),
            'created_at' => time(),
            'mcp_enabled' => GOOGLE_MCP_ENABLED
        ];

        return $_SESSION['mcp_user'];
    }

    /**
     * Get MCP session
     */
    public function getMCPSession() {
        return $_SESSION['mcp_user'] ?? null;
    }

    /**
     * Destroy MCP session
     */
    public function destroyMCPSession() {
        unset($_SESSION['mcp_user']);
        return true;
    }
}

// Initialize Google OAuth MCP
$googleOAuthMCP = new GoogleOAuthMCP(
    GOOGLE_CLIENT_ID,
    GOOGLE_ADMIN_EMAIL,
    GOOGLE_OAUTH_SCOPE,
    OAUTH_REDIRECT_URI
);

// Export for use in other files
return $googleOAuthMCP;
?>
