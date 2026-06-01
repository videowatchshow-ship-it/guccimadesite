<?php
/**
 * Bootstrap Configuration
 * Initializes all core configurations and security settings
 * 
 * Official Documentation:
 * - PHP: https://www.php.net/
 * - Cloudflare: https://developers.cloudflare.com/
 */

// Error handling
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../../logs/php-error.log');

// Session configuration
ini_set('session.use_strict_mode', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.gc_maxlifetime', 3600);

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load environment variables
if (file_exists(__DIR__ . '/../../.env')) {
    $env = parse_ini_file(__DIR__ . '/../../.env');
    foreach ($env as $key => $value) {
        putenv("{$key}={$value}");
    }
}

// Load Cloudflare configuration
require_once __DIR__ . '/cloudflare-config.php';

// Set default timezone
date_default_timezone_set('UTC');

// Define base paths
define('BASE_PATH', dirname(__DIR__));
define('CONFIG_PATH', __DIR__);
define('LOGS_PATH', BASE_PATH . '/logs');
define('CACHE_PATH', BASE_PATH . '/cache');

// Create necessary directories
$dirs = [LOGS_PATH, CACHE_PATH];
foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Security headers
if (!headers_sent()) {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
}

// CSRF token generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Logging function
function logEvent($level, $message, $context = []) {
    $timestamp = date('Y-m-d H:i:s');
    $logFile = LOGS_PATH . '/app.log';
    
    $logMessage = "[{$timestamp}] [{$level}] {$message}";
    if (!empty($context)) {
        $logMessage .= ' | ' . json_encode($context);
    }
    $logMessage .= "\n";
    
    error_log($logMessage, 3, $logFile);
}

// Error handler
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    logEvent('ERROR', $errstr, [
        'file' => $errfile,
        'line' => $errline,
        'errno' => $errno
    ]);
    return false;
});

// Exception handler
set_exception_handler(function($exception) {
    logEvent('EXCEPTION', $exception->getMessage(), [
        'file' => $exception->getFile(),
        'line' => $exception->getLine(),
        'code' => $exception->getCode()
    ]);
    
    if (!headers_sent()) {
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json');
    }
    
    echo json_encode([
        'ok' => false,
        'msg' => 'Internal server error'
    ]);
    exit;
});

// Shutdown handler
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        logEvent('FATAL', $error['message'], [
            'file' => $error['file'],
            'line' => $error['line'],
            'type' => $error['type']
        ]);
    }
});

// Database connection helper
function getDB() {
    static $db = null;
    
    if ($db !== null) {
        return $db;
    }
    
    $host = getenv('GUCCI_DB_HOST') ?: 'localhost';
    $user = getenv('GUCCI_DB_USER') ?: 'gucci_user';
    $pass = getenv('GUCCI_DB_PASS') ?: 'GuCCi2026Secure';
    $name = getenv('GUCCI_DB_NAME') ?: 'gucci_wordpress';
    
    try {
        $db = new mysqli($host, $user, $pass, $name);
        
        if ($db->connect_error) {
            throw new Exception('Database connection failed: ' . $db->connect_error);
        }
        
        $db->set_charset('utf8mb4');
        
        logEvent('INFO', 'Database connected', [
            'host' => $host,
            'database' => $name
        ]);
        
        return $db;
    } catch (Exception $e) {
        logEvent('ERROR', 'Database connection error', [
            'error' => $e->getMessage()
        ]);
        
        if (!headers_sent()) {
            header('HTTP/1.1 500 Internal Server Error');
            header('Content-Type: application/json');
        }
        
        echo json_encode([
            'ok' => false,
            'msg' => 'Database connection error'
        ]);
        exit;
    }
}

// Cache helper
function getCache($key) {
    $file = CACHE_PATH . '/' . md5($key) . '.cache';
    
    if (file_exists($file)) {
        $data = unserialize(file_get_contents($file));
        if ($data['expires'] > time()) {
            return $data['value'];
        }
        unlink($file);
    }
    
    return null;
}

function setCache($key, $value, $ttl = 3600) {
    $file = CACHE_PATH . '/' . md5($key) . '.cache';
    $data = [
        'value' => $value,
        'expires' => time() + $ttl
    ];
    file_put_contents($file, serialize($data));
}

// Utility functions
function sanitizeInput($input) {
    if (is_array($input)) {
        return array_map('sanitizeInput', $input);
    }
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validateURL($url) {
    return filter_var($url, FILTER_VALIDATE_URL) !== false;
}

// Initialize logging
logEvent('INFO', 'Bootstrap initialized', [
    'timestamp' => date('Y-m-d H:i:s'),
    'php_version' => PHP_VERSION,
    'environment' => getenv('APP_ENV') ?: 'production'
]);

?>
