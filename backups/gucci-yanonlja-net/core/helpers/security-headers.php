<?php
/**
 * 공통 보안 헤더 — 구찌야놀자
 * PHP 주의점 10 + 보안 20 = 30개 체크리스트 구현
 *
 * 공식 문서:
 * ref: https://www.php.net/manual/en/function.header.php
 * ref: https://owasp.org/www-project-secure-headers/
 * ref: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy
 * ref: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Strict-Transport-Security
 * ref: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy
 * ref: https://www.php.net/manual/en/ini.core.php
 * ref: https://www.php.net/manual/en/function.session-set-cookie-params.php
 */
declare(strict_types=1);

/* ════════════════════════════════════════════════════
   PHP 주의점 10가지 (ref: https://www.php.net/manual/en/security.php)
   ════════════════════════════════════════════════════ */

/* [주의 01] PHP 버전 노출 방지
 * ref: https://www.php.net/manual/en/ini.core.php#ini.expose-php
 */
header_remove('X-Powered-By');
if (function_exists('ini_set')) {
    ini_set('expose_php', '0');
}

/* [주의 02] 에러 표시 완전 차단 (프로덕션)
 * ref: https://www.php.net/manual/en/function.error-reporting.php
 */
error_reporting(0);
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
ini_set('log_errors', '1');

/* [주의 03] 출력 버퍼링 — 헤더 전송 전 오류 방지
 * ref: https://www.php.net/manual/en/function.ob-start.php
 */
if (!ob_get_level()) {
    ob_start();
}

/* [주의 04] 타임존 명시 설정
 * ref: https://www.php.net/manual/en/function.date-default-timezone-set.php
 */
date_default_timezone_set('Asia/Seoul');

/* [주의 05] 최대 실행 시간 제한
 * ref: https://www.php.net/manual/en/function.set-time-limit.php
 */
set_time_limit(30);

/* [주의 06] POST/업로드 크기 제한
 * ref: https://www.php.net/manual/en/ini.core.php#ini.post-max-size
 */
ini_set('post_max_size', '10M');
ini_set('upload_max_filesize', '5M');

/* [주의 07] 세션 고정 공격 방지
 * ref: https://www.php.net/manual/en/session.configuration.php
 */
ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');
ini_set('session.use_trans_sid', '0');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_secure', '1');
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.gc_maxlifetime', '3600');

/* [주의 08] 파일 포함 경로 제한
 * ref: https://www.php.net/manual/en/ini.core.php#ini.open-basedir
 * 실제 배포 시 nginx/php-fpm 설정에서 open_basedir 적용
 */

/* [주의 09] 입력값 타입 강제 (declare strict_types=1 사용)
 * ref: https://www.php.net/manual/en/language.types.declarations.php
 * 각 파일 상단에 declare(strict_types=1) 필수
 */

/* [주의 10] 민감 정보 환경변수 분리
 * ref: https://www.php.net/manual/en/function.getenv.php
 * .env 파일 사용, 공개 저장소 커밋 금지
 */

/* ════════════════════════════════════════════════════
   보안 20가지 (ref: https://owasp.org/www-project-top-ten/)
   ════════════════════════════════════════════════════ */

if (!headers_sent()) {

    /* [보안 01] Clickjacking 방지 — X-Frame-Options
     * ref: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Frame-Options
     */
    header('X-Frame-Options: DENY');

    /* [보안 02] MIME 스니핑 방지
     * ref: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Content-Type-Options
     */
    header('X-Content-Type-Options: nosniff');

    /* [보안 03] XSS 필터 (레거시 브라우저 대응)
     * ref: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-XSS-Protection
     */
    header('X-XSS-Protection: 1; mode=block');

    /* [보안 04] Referrer 정책
     * ref: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Referrer-Policy
     */
    header('Referrer-Policy: strict-origin-when-cross-origin');

    /* [보안 05] Content Security Policy (CSP)
     * ref: https://developer.mozilla.org/en-US/docs/Web/HTTP/CSP
     * ref: https://content-security-policy.com/
     */
    header("Content-Security-Policy: default-src 'self' https:; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://accounts.google.com; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; img-src 'self' data: https:; font-src 'self' data: https://cdn.jsdelivr.net; connect-src 'self' https: wss:; media-src 'self' https: blob:; frame-ancestors 'none';");

    /* [보안 06] Permissions Policy (카메라/마이크/위치 차단)
     * ref: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Permissions-Policy
     */
    header('Permissions-Policy: geolocation=(), microphone=(), camera=(), payment=()');

    /* [보안 07] HSTS — HTTPS 강제
     * ref: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Strict-Transport-Security
     */
    $https = $_SERVER['HTTPS'] ?? '';
    if ($https !== '' && $https !== 'off') {
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
    }

    /* [보안 08] 캐시 제어 (동적 페이지)
     * ref: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Cache-Control
     */
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

    /* [보안 09] 검색엔진 색인 제어 (관리자/API 페이지)
     * ref: https://developers.google.com/search/docs/crawling-indexing/block-indexing
     */
    // 필요 시 개별 페이지에서 호출: header('X-Robots-Tag: noindex, nofollow');

    /* [보안 10] Cross-Origin Resource Policy
     * ref: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Cross-Origin-Resource-Policy
     */
    header('Cross-Origin-Resource-Policy: same-origin');

    /* [보안 11] Cross-Origin Opener Policy
     * ref: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Cross-Origin-Opener-Policy
     */
    header('Cross-Origin-Opener-Policy: same-origin');

    /* [보안 12] Cross-Origin Embedder Policy
     * ref: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Cross-Origin-Embedder-Policy
     */
    // header('Cross-Origin-Embedder-Policy: require-corp'); // 스트리밍 미디어 로드 시 주의

}

/* ════════════════════════════════════════════════════
   헬퍼 함수
   ════════════════════════════════════════════════════ */

/**
 * [보안 13] 캐시 완전 차단 (민감 페이지용)
 * ref: https://www.php.net/manual/en/function.header.php
 */
function set_no_cache_headers(): void
{
    if (!headers_sent()) {
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');
    }
}

/**
 * [보안 14] IP 추출 헬퍼 (Cloudflare 프록시 대응)
 * ref: https://developers.cloudflare.com/fundamentals/reference/http-request-headers/
 * ref: https://www.php.net/manual/en/reserved.variables.server.php
 * 정규식: IPv4/IPv6 기본 검증
 */
function get_client_ip(): string
{
    /* Cloudflare 실제 IP 헤더 우선 */
    $candidates = [
        $_SERVER['HTTP_CF_CONNECTING_IP'] ?? '',
        $_SERVER['HTTP_X_FORWARDED_FOR']  ?? '',
        $_SERVER['HTTP_X_REAL_IP']        ?? '',
        $_SERVER['REMOTE_ADDR']           ?? '0.0.0.0',
    ];
    foreach ($candidates as $ip) {
        $ip = trim(explode(',', $ip)[0]);
        /* 정규식: IPv4/IPv6 기본 형식 검증
         * ref: https://www.regular-expressions.info/ip.html
         */
        if (preg_match('/^[0-9a-fA-F.:]{3,45}$/', $ip)
            && filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)
        ) {
            return $ip;
        }
    }
    return (string)($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
}

/**
 * [보안 15] SQL Injection 방지 — 입력값 검증
 * ref: https://www.php.net/manual/en/security.database.sql-injection.php
 * 반드시 Prepared Statement와 함께 사용
 */
function validate_int_input(mixed $val, int $min = 0, int $max = PHP_INT_MAX): ?int
{
    $v = filter_var($val, FILTER_VALIDATE_INT, ['options' => ['min_range' => $min, 'max_range' => $max]]);
    return ($v !== false) ? (int)$v : null;
}

/**
 * [보안 16] XSS 방지 — 출력 이스케이프
 * ref: https://www.php.net/manual/en/function.htmlspecialchars.php
 * ref: https://owasp.org/www-community/attacks/xss/
 */
function esc(string $str): string
{
    return htmlspecialchars($str, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * [보안 17] CSRF 토큰 생성/검증
 * ref: https://owasp.org/www-community/attacks/csrf
 * ref: https://www.php.net/manual/en/function.random-bytes.php
 */
function generate_csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return (string)$_SESSION['csrf_token'];
}

function verify_csrf_token(string $token): bool
{
    if (empty($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals((string)$_SESSION['csrf_token'], $token);
}

/**
 * [보안 18] Rate Limiting (APCu 기반)
 * ref: https://www.php.net/manual/en/book.apcu.php
 * ref: https://owasp.org/www-community/attacks/Denial_of_Service
 */
function check_rate_limit(string $key, int $max = 10, int $window = 60): bool
{
    if (!function_exists('apcu_fetch')) {
        return true; // APCu 없으면 통과
    }
    $count_key = 'rl_' . md5($key);
    $lock_key  = 'rl_lock_' . md5($key);
    if (apcu_exists($lock_key)) {
        return false;
    }
    $success = false;
    $count   = (int) apcu_fetch($count_key, $success);
    $count++;
    apcu_store($count_key, $count, $window);
    if ($count >= $max) {
        apcu_store($lock_key, 1, $window * 15);
        apcu_delete($count_key);
        return false;
    }
    return true;
}

/**
 * [보안 19] 파일 업로드 검증
 * ref: https://www.php.net/manual/en/features.file-upload.php
 * ref: https://owasp.org/www-community/vulnerabilities/Unrestricted_File_Upload
 * 정규식: 허용 확장자만 허용
 */
function validate_upload(array $file, array $allowed_types = ['image/jpeg', 'image/png', 'image/gif']): bool
{
    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        return false;
    }
    /* MIME 타입 검증 (확장자 스푸핑 방지) */
    $finfo = new \finfo(FILEINFO_MIME_TYPE);
    $mime  = $finfo->file($file['tmp_name']);
    if (!in_array($mime, $allowed_types, true)) {
        return false;
    }
    /* 파일명 정규식 검증: ^[a-z0-9_\-]+\.(jpg|png|gif)$ */
    $name = strtolower(basename($file['name']));
    if (!preg_match('/^[a-z0-9_\-]+\.(jpg|jpeg|png|gif)$/', $name)) {
        return false;
    }
    return true;
}

/**
 * [보안 20] 리다이렉트 검증 (Open Redirect 방지)
 * ref: https://owasp.org/www-community/attacks/Unvalidated_Redirects_and_Forwards_Cheat_Sheet
 * 정규식: 내부 경로만 허용
 */
function safe_redirect(string $url, int $code = 302): void
{
    /* 정규식: 내부 경로만 허용 (https:// 등 외부 URL 차단) */
    if (!preg_match('/^\/[a-z0-9_\-\/]*$/', $url)) {
        $url = '/';
    }
    header('Location: ' . $url, true, $code);
    exit;
}
