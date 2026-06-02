<?php


// 보안 헤더 설정


if (!headers_sent()) {


    // Clickjacking 방지


    if (!headers_sent()) { header('X-Frame-Options: DENY'); }


    


    // XSS 방지


    if (!headers_sent()) { header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://accounts.google.com https://apis.google.com; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' data:; connect-src 'self' https://accounts.google.com https://www.googleapis.com wss://; frame-src https://accounts.google.com;"); }


    


    // MIME 타입 스니핑 방지


    if (!headers_sent()) { header('X-Content-Type-Options: nosniff'); }


    


    // XSS 필터 활성화


    if (!headers_sent()) { header('X-XSS-Protection: 1; mode=block'); }


    


    // Referrer 정책


    if (!headers_sent()) { if (!headers_sent()) { header('Referrer-Policy: strict-origin-when-cross-origin'); } }


    


    // HTTPS 강제 (프로덕션 환경)


    if (isset(filter_input(INPUT_SERVER, HTTPS, FILTER_SANITIZE_STRING)) && filter_input(INPUT_SERVER, HTTPS, FILTER_SANITIZE_STRING) === 'on') {


        if (!headers_sent()) { header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload'); }


    }


}





/**


 * 구찌야놀자 헬스체크 엔드포인트


 * URL: https://xn--2e0bj1fruw33b6ti.net/health.php


 * 


 * 확인 항목:


 * - 서버 기본 정보


 * - PHP 환경 및 확장 모듈


 * - 데이터베이스 연결


 * - 파일 시스템 권한


 * - Redis 연결


 * - 디스크 사용량


 */





// 보안 헤더 설정


if (!headers_sent()) {


    if (!headers_sent()) { header('Content-Type: application/json; charset=utf-8'); }


}


if (!headers_sent()) {


    if (!headers_sent()) { header('Cache-Control: no-cache, no-store, must-revalidate'); }


}


if (!headers_sent()) {


    if (!headers_sent()) { header('Pragma: no-cache'); }


}


if (!headers_sent()) {


    if (!headers_sent()) { header('Expires: 0'); }


}





// SITE_ID 검증


$SITE_ID = getenv('SITE_ID');


if (!$SITE_ID || $SITE_ID !== 'xn--2e0bj1fruw33b6ti.net') {


    http_response_code(403);


    echo json_encode(['status' => 'error', 'message' => 'Invalid SITE_ID']);


    exit;


}





/**


 * 헬스체크 결과 구조체


 */


$health = [


    'status' => 'ok',


    'timestamp' => date('Y-m-d H:i:s T'),


    'site_id' => $SITE_ID,


    'checks' => []


];





/**


 * 개별 체크 함수


 */


function addCheck($name, $status, $message = , $data = null) {


    global $health;


    $health['checks'][$name] = [


        'status' => $status,


        'message' => $message,


        'data' => $data


    ];


    


    if ($status === 'error') {


        $health['status'] = 'error';


    } elseif ($status === 'warning' && $health['status'] === 'ok') {


        $health['status'] = 'warning';


    }


}





/**


 * 1. 서버 기본 정보


 */


try {


    $server_info = [


        'php_version' => phpversion(),


        'server_software' => filter_input(INPUT_SERVER, SERVER_SOFTWARE, FILTER_SANITIZE_STRING) ?? 'Unknown',


        'server_name' => filter_input(INPUT_SERVER, SERVER_NAME, FILTER_SANITIZE_STRING) ?? 'Unknown',


        'server_addr' => filter_input(INPUT_SERVER, SERVER_ADDR, FILTER_SANITIZE_STRING) ?? 'Unknown',


        'document_root' => filter_input(INPUT_SERVER, DOCUMENT_ROOT, FILTER_SANITIZE_STRING) ?? 'Unknown',


        'memory_limit' => ini_get('memory_limit'),


        'max_execution_time' => ini_get('max_execution_time'),


        'upload_max_filesize' => ini_get('upload_max_filesize'),


        'post_max_size' => ini_get('post_max_size')


    ];


    


    addCheck('server_info', 'ok', 'Server information collected', $server_info);


} catch (Exception $e) {


    addCheck('server_info', 'error', 'Failed to collect server info: ' . $e->getMessage());


}





/**


 * 2. PHP 확장 모듈 확인


 */


try {


    $required_extensions = [


        'mysqli' => 'MySQL 연결',


        'curl' => 'HTTP 요청',


        'gd' => '이미지 처리',


        'xml' => 'XML 파싱',


        'mbstring' => '멀티바이트 문자열',


        'zip' => 'ZIP 압축',


        'intl' => '국제화',


        'bcmath' => '정밀 수학',


        'soap' => 'SOAP 웹서비스',


        'redis' => 'Redis 캐시',


        'imagick' => '고급 이미지 처리'


    ];


    


    $extension_status = [];


    $missing_extensions = [];


    


    foreach ($required_extensions as $ext => $desc) {


        $loaded = extension_loaded($ext);


        $extension_status[$ext] = [


            'loaded' => $loaded,


            'description' => $desc


        ];


        


        if (!$loaded) {


            $missing_extensions[] = $ext;


        }


    }


    


    if (empty($missing_extensions)) {


        addCheck('php_extensions', 'ok', 'All required PHP extensions loaded', $extension_status);


    } else {


        addCheck('php_extensions', 'warning', 'Some extensions missing: ' . implode(', ', $missing_extensions), $extension_status);


    }


} catch (Exception $e) {


    addCheck('php_extensions', 'error', 'Failed to check PHP extensions: ' . $e->getMessage());


}





/**


 * 3. 환경변수 확인


 */


try {


    $env_vars = [


        'SITE_ID' => getenv('SITE_ID'),


        'SITE_URL' => getenv('SITE_URL'),


        'DB_HOST' => getenv('DB_HOST'),


        'DB_NAME' => getenv('DB_NAME'),


        'DB_USER' => getenv('DB_USER'),


        'DB_PASS' => getenv('DB_PASS') ? '***SET***' : null


    ];


    


    $missing_env = [];


    foreach ($env_vars as $key => $value) {


        if (!$value) {


            $missing_env[] = $key;


        }


    }


    


    if (empty($missing_env)) {


        addCheck('environment_variables', 'ok', 'All environment variables set', $env_vars);


    } else {


        addCheck('environment_variables', 'warning', 'Missing env vars: ' . implode(', ', $missing_env), $env_vars);


    }


} catch (Exception $e) {


    addCheck('environment_variables', 'error', 'Failed to check environment variables: ' . $e->getMessage());


}





/**


 * 4. 데이터베이스 연결 확인


 */


try {


    $db_host = getenv('DB_HOST') ?: 'localhost';


    $db_user = getenv('DB_USER');


    $db_pass = getenv('DB_PASS');


    $db_name = getenv('DB_NAME');


    


    if ($db_user && $db_pass && $db_name) {


        $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);


        


        if ($mysqli->connect_error) {


            addCheck('database', 'error', 'Database connection failed: ' . $mysqli->connect_error);


        } else {


            $db_info = [


                'server_info' => $mysqli->server_info,


                'server_version' => $mysqli->server_version,


                'client_info' => $mysqli->client_info,


                'charset' => $mysqli->character_set_name(),


                'host_info' => $mysqli->host_info


            ];


            


            // 테이블 존재 확인


            $tables = [];


            $result = $mysqli->query("SHOW TABLES");


            if ($result) {


                while ($row = $result->fetch_array()) {


                    $tables[] = $row[0];


                }


            }


            $db_info['tables'] = $tables;


            


            addCheck('database', 'ok', 'Database connection successful', $db_info);


            $mysqli->close();


        }


    } else {


        addCheck('database', 'error', 'Database credentials not configured');


    }


} catch (Exception $e) {


    addCheck('database', 'error', 'Database check failed: ' . $e->getMessage());


}





/**


 * 5. Redis 연결 확인


 */


try {


    if (extension_loaded('redis')) {


        $redis = new Redis();


        if ($redis->connect('127.0.0.1', 6379)) {


            $redis_info = [


                'version' => $redis->info('server')['redis_version'] ?? 'Unknown',


                'connected_clients' => $redis->info('clients')['connected_clients'] ?? 'Unknown',


                'used_memory_human' => $redis->info('memory')['used_memory_human'] ?? 'Unknown',


                'uptime_in_seconds' => $redis->info('server')['uptime_in_seconds'] ?? 'Unknown'


            ];


            


            // 간단한 읽기/쓰기 테스트


            $test_key = 'health_check_' . time();


            $redis->set($test_key, 'test_value', 10);


            $test_value = $redis->get($test_key);


            $redis->del($test_key);


            


            if ($test_value === 'test_value') {


                addCheck('redis', 'ok', 'Redis connection and operations successful', $redis_info);


            } else {


                addCheck('redis', 'warning', 'Redis connected but operations failed', $redis_info);


            }


            


            $redis->close();


        } else {


            addCheck('redis', 'warning', 'Redis connection failed');


        }


    } else {


        addCheck('redis', 'warning', 'Redis extension not loaded');


    }


} catch (Exception $e) {


    addCheck('redis', 'warning', 'Redis check failed: ' . $e->getMessage());


}





/**


 * 6. 파일 시스템 권한 확인


 */


try {


    $paths_to_check = [


        '/var/www/html/xn--2e0bj1fruw33b6ti.net/',


        '/var/www/html/xn--2e0bj1fruw33b6ti.net/site_content/',


        '/tmp/',


        sys_get_temp_dir()


    ];


    


    $filesystem_status = [];


    $permission_issues = [];


    


    foreach ($paths_to_check as $path) {


        $readable = is_readable($path);


        $writable = is_writable($path);


        $exists = file_exists($path);


        


        $filesystem_status[$path] = [


            'exists' => $exists,


            'readable' => $readable,


            'writable' => $writable


        ];


        


        if (!$exists || !$readable) {


            $permission_issues[] = $path;


        }


    }


    


    if (empty($permission_issues)) {


        addCheck('filesystem', 'ok', 'File system permissions OK', $filesystem_status);


    } else {


        addCheck('filesystem', 'warning', 'Permission issues: ' . implode(', ', $permission_issues), $filesystem_status);


    }


} catch (Exception $e) {


    addCheck('filesystem', 'error', 'Filesystem check failed: ' . $e->getMessage());


}





/**


 * 7. 디스크 사용량 확인


 */


try {


    $disk_total = disk_total_space('/');


    $disk_free = disk_free_space('/');


    $disk_used = $disk_total - $disk_free;


    $disk_usage_percent = round(($disk_used / $disk_total) * 100, 2);


    


    $disk_info = [


        'total_bytes' => $disk_total,


        'free_bytes' => $disk_free,


        'used_bytes' => $disk_used,


        'usage_percent' => $disk_usage_percent,


        'total_human' => formatBytes($disk_total),


        'free_human' => formatBytes($disk_free),


        'used_human' => formatBytes($disk_used)


    ];


    


    if ($disk_usage_percent > 90) {


        addCheck('disk_usage', 'error', "Disk usage critical: {$disk_usage_percent}%", $disk_info);


    } elseif ($disk_usage_percent > 80) {


        addCheck('disk_usage', 'warning', "Disk usage high: {$disk_usage_percent}%", $disk_info);


    } else {


        addCheck('disk_usage', 'ok', "Disk usage normal: {$disk_usage_percent}%", $disk_info);


    }


} catch (Exception $e) {


    addCheck('disk_usage', 'error', 'Disk usage check failed: ' . $e->getMessage());


}





/**


 * 8. 메모리 사용량 확인


 */


try {


    $memory_info = [


        'current_usage_bytes' => memory_get_usage(true),


        'peak_usage_bytes' => memory_get_peak_usage(true),


        'current_usage_human' => formatBytes(memory_get_usage(true)),


        'peak_usage_human' => formatBytes(memory_get_peak_usage(true)),


        'memory_limit' => ini_get('memory_limit')


    ];


    


    addCheck('memory_usage', 'ok', 'Memory usage information collected', $memory_info);


} catch (Exception $e) {


    addCheck('memory_usage', 'error', 'Memory usage check failed: ' . $e->getMessage());


}





/**


 * 9. 중요 파일 존재 확인


 */


try {


    $important_files = [


        '/var/www/html/xn--2e0bj1fruw33b6ti.net/site_content/index.php',


        '/var/www/html/xn--2e0bj1fruw33b6ti.net/site_content/header.php',


        '/var/www/html/xn--2e0bj1fruw33b6ti.net/site_content/auth/google-callback.php',


        '/var/www/html/xn--2e0bj1fruw33b6ti.net/site_content/admin/index.php',


        '/etc/apache2/sites-available/xn--2e0bj1fruw33b6ti.net.conf'


    ];


    


    $file_status = [];


    $missing_files = [];


    


    foreach ($important_files as $file) {


        $exists = file_exists($file);


        $file_status[basename($file)] = [


            'path' => $file,


            'exists' => $exists,


            'size' => $exists ? filesize($file) : 0,


            'modified' => $exists ? date('Y-m-d H:i:s', filemtime($file)) : null


        ];


        


        if (!$exists) {


            $missing_files[] = basename($file);


        }


    }


    


    if (empty($missing_files)) {


        addCheck('important_files', 'ok', 'All important files exist', $file_status);


    } else {


        addCheck('important_files', 'warning', 'Missing files: ' . implode(', ', $missing_files), $file_status);


    }


} catch (Exception $e) {


    addCheck('important_files', 'error', 'File existence check failed: ' . $e->getMessage());


}





/**


 * 바이트를 사람이 읽기 쉬운 형태로 변환


 */


function formatBytes($bytes, $precision = 2) {


    $units = array('B', 'KB', 'MB', 'GB', 'TB');


    


    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {


        $bytes /= 1024;


    }


    


    return round($bytes, $precision) . ' ' . $units[$i];


}





/**


 * HTTP 상태 코드 설정


 */


if ($health['status'] === 'error') {


    http_response_code(503); // Service Unavailable


} elseif ($health['status'] === 'warning') {


    http_response_code(200); // OK but with warnings


} else {


    http_response_code(200); // OK


}





/**


 * 결과 출력


 */


echo json_encode($health, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);


?>


<!doctype html>


<html lang="ko">


</html>
