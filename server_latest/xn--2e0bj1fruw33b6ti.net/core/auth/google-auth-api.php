<?php
declare(strict_types=1);
/**
 * Google OAuth 2.0 API — 구찌야놀자
 * ref: https://developers.google.com/identity/gsi/web/guides/verify-google-id-token
 * ref: https://www.php.net/manual/en/function.apcu-fetch.php
 * ref: https://owasp.org/www-project-secure-headers/
 * ref: https://www.php.net/manual/en/filter.filters.sanitize.php
 */

/* ── 세션 시작 (헤더 전송 전) */
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => true,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

/* ── 보안 헤더
 * ref: https://owasp.org/www-project-secure-headers/
 */
if (!headers_sent()) {
    header_remove('X-Powered-By');
    header('Content-Type: application/json; charset=utf-8');
    header('X-Frame-Options: DENY');
    header('X-Content-Type-Options: nosniff');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header("Content-Security-Policy: default-src 'none';");
    header('Access-Control-Allow-Origin: https://xn--2e0bj1fruw33b6ti.net');
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Access-Control-Allow-Credentials: true');
    header('X-Robots-Tag: noindex, nofollow');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Pragma: no-cache');
    $https = $_SERVER['HTTPS'] ?? '';
    if ($https !== '' && $https !== 'off') {
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
    }
}

error_reporting(0);
ini_set('display_errors', '0');
ini_set('log_errors', '1');
mysqli_report(MYSQLI_REPORT_OFF);

/* ── OPTIONS preflight
 * ref: https://developer.mozilla.org/en-US/docs/Web/HTTP/Methods/OPTIONS
 */
$request_method = $_SERVER['REQUEST_METHOD'] ?? '';
if ($request_method === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($request_method !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'msg' => 'Method not allowed'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

/* ── 상수 정의 */
define('GOOGLE_CLIENT_ID', '956283750273-do3ebgq60vbi585r62ffpk0cqts8l634.apps.googleusercontent.com');
define('ADMIN_EMAIL_1', 'centkim177@gmail.com');
define('ADMIN_EMAIL_2', 'videowatch.show@gmail.com');
define('ADMIN_EMAIL_3', 'fury00000007@gmail.com');

/* ── Rate Limit
 * ref: https://www.php.net/manual/en/function.apcu-fetch.php
 * ref: https://www.php.net/manual/en/function.apcu-store.php
 */
function check_rate_limit(string $ip): void
{
    if (!function_exists('apcu_fetch')) {
        return;
    }
    $key      = 'rl_login_' . md5($ip);
    $lock_key = 'rl_lock_'  . md5($ip);
    if (apcu_exists($lock_key)) {
        http_response_code(429);
        echo json_encode(['ok' => false, 'msg' => 'Too many attempts. Try again in 15 minutes.'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }
    /* apcu_fetch 두 번째 인자: bool $success (ref: php.net/manual/en/function.apcu-fetch.php) */
    $success = false;
    $count   = (int) apcu_fetch($key, $success);
    $count++;
    apcu_store($key, $count, 300);
    if ($count >= 5) {
        apcu_store($lock_key, 1, 900);
        apcu_delete($key);
        http_response_code(429);
        echo json_encode(['ok' => false, 'msg' => 'Login attempts exceeded. Try again in 15 minutes.'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }
}

/* ── JSON 입력 파싱
 * ref: https://www.php.net/manual/en/function.json-decode.php
 */
function safe_json_input(): array
{
    $raw = (string) file_get_contents('php://input');
    if ($raw === '' || $raw === 'null') {
        return [];
    }
    if (strlen($raw) > 65536) {
        http_response_code(413);
        echo json_encode(['ok' => false, 'msg' => 'Request too large'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }
    $data = json_decode($raw, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'msg' => 'Invalid JSON'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }
    return is_array($data) ? $data : [];
}

/* ── DB 연결
 * ref: https://www.php.net/manual/en/class.mysqli.php
 */
function get_db(): mysqli
{
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $host = (string)(getenv('GUCCI_DB_HOST') ?: 'localhost');
    $user = (string)(getenv('GUCCI_DB_USER') ?: 'gucci_user');
    $pass = (string)(getenv('GUCCI_DB_PASS') ?: 'GuCCi2026Secure');
    $name = (string)(getenv('GUCCI_DB_NAME') ?: 'gucci_wordpress');
    $db   = new mysqli($host, $user, $pass, $name);
    if ($db->connect_error) {
        throw new RuntimeException('DB connection failed: ' . $db->connect_error);
    }
    $db->set_charset('utf8mb4');
    return $db;
}

/* ── DB 초기화 */
try {
    $mysqli = get_db();
    $mysqli->query("CREATE TABLE IF NOT EXISTS gucci_members (
        id                  INT AUTO_INCREMENT PRIMARY KEY,
        google_id           VARCHAR(100) UNIQUE NOT NULL,
        email               VARCHAR(200) NOT NULL,
        name                VARCHAR(100),
        profile_picture_url VARCHAR(500),
        account_holder_name VARCHAR(255),
        phone_number        VARCHAR(20),
        bank_name           VARCHAR(100),
        account_number      VARCHAR(50),
        is_admin            TINYINT(1) DEFAULT 0,
        created_at          DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at          DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_google_id (google_id),
        INDEX idx_email     (email),
        INDEX idx_is_admin  (is_admin)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
} catch (RuntimeException $e) {
    error_log('gucci-auth DB: ' . $e->getMessage());
    echo json_encode(['ok' => false, 'msg' => 'Database error'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

/* ── Google ID Token 검증 (서버사이드)
 * ref: https://developers.google.com/identity/gsi/web/guides/verify-google-id-token#using-a-google-api-client-library
 * 주의: 프로덕션에서는 google/apiclient 라이브러리 사용 권장
 * 현재: JWT payload 디코딩 후 aud/iss/exp/email_verified 검증
 */
function verify_google_token(string $id_token): ?array
{
    $parts = explode('.', $id_token);
    if (count($parts) !== 3) {
        return null;
    }
    $payload_b64 = strtr($parts[1], '-_', '+/');
    $padding     = strlen($payload_b64) % 4;
    if ($padding > 0) {
        $payload_b64 .= str_repeat('=', 4 - $padding);
    }
    $payload = json_decode((string) base64_decode($payload_b64), true);
    if (!is_array($payload)) {
        return null;
    }
    if (($payload['aud'] ?? '') !== GOOGLE_CLIENT_ID) {
        return null;
    }
    $iss = $payload['iss'] ?? '';
    if ($iss !== 'accounts.google.com' && $iss !== 'https://accounts.google.com') {
        return null;
    }
    if ((int)($payload['exp'] ?? 0) < time()) {
        return null;
    }
    if (empty($payload['email_verified'])) {
        return null;
    }
    if (empty($payload['sub'])) {
        return null;
    }
    return $payload;
}

/* ── 입력 처리 */
if (ob_get_level() > 0) {
    ob_clean();
}

$input  = safe_json_input();
$action = (string)($input['action'] ?? '');

if ($action === '') {
    http_response_code(400);
    echo json_encode(['ok' => false, 'msg' => 'Action required'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

error_log('gucci-auth action=' . $action . ' sid=' . session_id());

/* ── 액션 라우팅 */
switch ($action) {

    case 'login':
        /* IP 추출 — FILTER_DEFAULT (PHP 8.1+ 권장)
         * ref: https://www.php.net/manual/en/filter.filters.sanitize.php
         */
        $forwarded = (string)($_SERVER['HTTP_X_FORWARDED_FOR'] ?? '');
        $remote    = (string)($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
        $client_ip = trim(explode(',', $forwarded !== '' ? $forwarded : $remote)[0]);
        /* 정규식: IPv4/IPv6 기본 검증 */
        if (!preg_match('/^[0-9a-fA-F.:]{3,45}$/', $client_ip)) {
            $client_ip = '0.0.0.0';
        }
        check_rate_limit($client_ip);

        $id_token = (string)($input['credential'] ?? '');
        if ($id_token === '') {
            http_response_code(400);
            echo json_encode(['ok' => false, 'msg' => 'Google credential missing'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            exit;
        }
        $payload = verify_google_token($id_token);
        if ($payload === null) {
            http_response_code(401);
            echo json_encode(['ok' => false, 'msg' => 'Invalid Google token'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            exit;
        }

        session_regenerate_id(true);
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        $google_id   = (string)($payload['sub'] ?? '');
        $email       = (string)($payload['email'] ?? '');
        $name        = mb_substr((string)($payload['name'] ?? ''), 0, 100);
        $picture_raw = (string)($payload['picture'] ?? '');
        /* 정규식: https:// 시작 URL만 허용 */
        $picture  = (filter_var($picture_raw, FILTER_VALIDATE_URL) && preg_match('/^https:\/\//', $picture_raw))
                    ? $picture_raw : '';
        $is_admin = ($email === ADMIN_EMAIL_1 || $email === ADMIN_EMAIL_2 || $email === ADMIN_EMAIL_3) ? 1 : 0;

        $stmt = $mysqli->prepare(
            'INSERT INTO gucci_members (google_id, email, name, profile_picture_url, is_admin)
             VALUES (?, ?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE
                 email=VALUES(email), name=VALUES(name),
                 profile_picture_url=VALUES(profile_picture_url),
                 is_admin=VALUES(is_admin)'
        );
        $stmt->bind_param('ssssi', $google_id, $email, $name, $picture, $is_admin);
        $stmt->execute();
        $stmt->close();

        $stmt = $mysqli->prepare('SELECT * FROM gucci_members WHERE google_id=?');
        $stmt->bind_param('s', $google_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (function_exists('apcu_delete')) {
            apcu_delete('rl_login_' . md5($client_ip));
        }

        $_SESSION['gucci_user'] = $row;
        $safe = array_map(
            static fn($v) => is_string($v) ? htmlspecialchars($v, ENT_QUOTES, 'UTF-8') : $v,
            (array)$row
        );
        echo json_encode(['ok' => true, 'user' => $safe, 'csrf_token' => $_SESSION['csrf_token']], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        break;

    case 'save_profile':
        if (empty($_SESSION['gucci_user'])) {
            http_response_code(401);
            echo json_encode(['ok' => false, 'msg' => 'Not logged in'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            exit;
        }
        $csrf = (string)($input['csrf_token'] ?? '');
        if (!empty($_SESSION['csrf_token']) && !hash_equals((string)$_SESSION['csrf_token'], $csrf)) {
            http_response_code(403);
            echo json_encode(['ok' => false, 'msg' => 'CSRF mismatch'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            exit;
        }

        $gid            = (string)$_SESSION['gucci_user']['google_id'];
        $holder         = mb_substr(trim((string)($input['real_name'] ?? '')), 0, 255);
        /* 정규식: 전화번호 — 숫자와 하이픈만 허용 */
        $phone          = preg_replace('/[^0-9\-]/', '', (string)($input['phone'] ?? ''));
        $bank           = mb_substr(trim((string)($input['bank_name'] ?? '')), 0, 100);
        $account_number = preg_replace('/[^0-9\-]/', '', (string)($input['account_number'] ?? ''));

        /* 정규식: 한국 전화번호 검증 (ref: https://www.regular-expressions.info/) */
        $phone_clean = preg_replace('/[\s\-]/', '', $phone);
        if ($phone !== '' && !preg_match('/^0[0-9]{8,10}$/', $phone_clean)) {
            http_response_code(422);
            echo json_encode(['ok' => false, 'msg' => 'Invalid phone format (e.g. 010-1234-5678)'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            exit;
        }
        if ($holder === '' || $phone === '') {
            http_response_code(422);
            echo json_encode(['ok' => false, 'msg' => 'Name and phone required'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            exit;
        }

        $stmt = $mysqli->prepare(
            'UPDATE gucci_members SET
             account_holder_name=?, phone_number=?, bank_name=?, account_number=?
             WHERE google_id=?'
        );
        $stmt->bind_param('sssss', $holder, $phone, $bank, $account_number, $gid);
        $stmt->execute();
        $stmt->close();

        $stmt = $mysqli->prepare('SELECT * FROM gucci_members WHERE google_id=?');
        $stmt->bind_param('s', $gid);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        $_SESSION['gucci_user'] = $row;
        $safe = array_map(
            static fn($v) => is_string($v) ? htmlspecialchars($v, ENT_QUOTES, 'UTF-8') : $v,
            (array)$row
        );
        echo json_encode(['ok' => true, 'user' => $safe], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        break;

    case 'me':
        if (empty($_SESSION['gucci_user'])) {
            http_response_code(401);
            echo json_encode(['ok' => false, 'msg' => 'Not logged in'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            exit;
        }
        $safe = array_map(
            static fn($v) => is_string($v) ? htmlspecialchars($v, ENT_QUOTES, 'UTF-8') : $v,
            (array)$_SESSION['gucci_user']
        );
        echo json_encode(['ok' => true, 'user' => $safe, 'csrf_token' => $_SESSION['csrf_token'] ?? ''], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        break;

    case 'logout':
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
        echo json_encode(['ok' => true, 'msg' => 'Logged out'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        break;

    default:
        http_response_code(400);
        echo json_encode(
            ['ok' => false, 'msg' => 'Unknown action: ' . htmlspecialchars($action, ENT_QUOTES, 'UTF-8')],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
}

if (ob_get_level() > 0) {
    ob_end_flush();
}
