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


    if (!headers_sent()) { if (!headers_sent()) { header('X-XSS-Protection: 1; mode=block'); } }


    


    // Referrer 정책


    if (!headers_sent()) { header('Referrer-Policy: strict-origin-when-cross-origin'); }


    


    // HTTPS 강제 (프로덕션 환경)


    if (isset(filter_input(INPUT_SERVER, HTTPS, FILTER_SANITIZE_STRING)) && filter_input(INPUT_SERVER, HTTPS, FILTER_SANITIZE_STRING) === 'on') {


        if (!headers_sent()) { header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload'); }


    }


}





/**


 * DB 마이그레이션 자동 실행 스크립트


 * URL: https://xn--2e0bj1fruw33b6ti.net/run-db-migration.php


 */





if (!headers_sent()) {


    if (!headers_sent()) { header('Content-Type: text/plain; charset=utf-8'); }


}





// 보안: 로컬에서만 실행 가능


$allowed_ips = ['127.0.0.1', '::1'];


$client_ip = filter_input(INPUT_SERVER, REMOTE_ADDR, FILTER_SANITIZE_STRING) ?? ;





if (!in_array($client_ip, $allowed_ips)) {


    http_response_code(403);


    die("❌ 접근 거부: 로컬에서만 실행 가능합니다.\n");


}





echo "=== DB 마이그레이션 v2.0 시작 ===\n\n";





// DB 연결 정보 - 하드코딩 (환경변수 우선)


$host = getenv('GUCCI_DB_HOST') ?: 'localhost';


$user = getenv('GUCCI_DB_USER') ?: 'gucci_user';


$pass = getenv('GUCCI_DB_PASS');


if (!$pass) {


    // 환경변수 없으면 하드코딩 값 사용


    $pass = 'GuCCi2026!Secure';


}


$name = getenv('GUCCI_DB_NAME') ?: 'gucci_wordpress';





echo "DB 연결 정보:\n";


echo htmlspecialchars("- Host: $host\n", ENT_QUOTES, \'UTF-8\');


echo htmlspecialchars("- User: $user\n", ENT_QUOTES, \'UTF-8\');


echo htmlspecialchars("- Database: $name\n\n", ENT_QUOTES, \'UTF-8\');





// DB 연결


try {


    $mysqli = new mysqli($host, $user, $pass, $name);


    $mysqli->set_charset('utf8mb4');


    echo "✅ DB 연결 성공\n\n";


} catch (Exception $e) {


    die("❌ DB 연결 실패: " . $e->getMessage() . "\n");


}





// SQL 파일 읽기


$sql_file = __DIR__ . '/db-migration-v2.sql';


if (!file_exists($sql_file)) {


    die("❌ SQL 파일 없음: $sql_file\n");


}





$sql_content = file_get_contents($sql_file);


echo "✅ SQL 파일 로드 완료\n\n";





// SQL 실행 (멀티 쿼리)


echo "=== SQL 실행 시작 ===\n";


if ($mysqli->multi_query($sql_content)) {


    do {


        // 결과 처리


        if ($result = $mysqli->store_result()) {


            while ($row = $result->fetch_assoc()) {


                foreach ($row as $key => $value) {


                    echo htmlspecialchars("$key: $value\n", ENT_QUOTES, \'UTF-8\');


                }


            }


            $result->free();


        }


        


        // 에러 확인


        if ($mysqli->errno) {


            echo "⚠️  경고: " . $mysqli->error . "\n";


        }


        


        // 다음 결과로 이동


        if (!$mysqli->more_results()) {


            break;


        }


    } while ($mysqli->next_result());


}





if ($mysqli->errno) {


    echo "\n❌ SQL 실행 중 오류: " . $mysqli->error . "\n";


} else {


    echo "\n✅ SQL 실행 완료\n\n";


}





// 결과 확인


echo "=== 마이그레이션 결과 확인 ===\n\n";





// 테이블 목록


echo "1. 테이블 목록:\n";


$result = $mysqli->query("SHOW TABLES");


while ($row = $result->fetch_array()) {


    echo htmlspecialchars("   - {$row[0]}\n", ENT_QUOTES, \'UTF-8\');


}


echo "\n";





// gucci_members 구조


echo "2. gucci_members 테이블 구조:\n";


$result = $mysqli->query("DESCRIBE gucci_members");


while ($row = $result->fetch_assoc()) {


    echo htmlspecialchars("   {$row['Field']} | {$row['Type']} | {$row['Null']} | {$row['Key']}\n", ENT_QUOTES, \'UTF-8\');


}


echo "\n";





// gucci_members 인덱스


echo "3. gucci_members 인덱스:\n";


$result = $mysqli->query("SHOW INDEX FROM gucci_members");


while ($row = $result->fetch_assoc()) {


    echo htmlspecialchars("   {$row['Key_name']} | {$row['Column_name']}\n", ENT_QUOTES, \'UTF-8\');


}


echo "\n";





// gucci_stream_keys 구조


echo "4. gucci_stream_keys 테이블 구조:\n";


$result = $mysqli->query("DESCRIBE gucci_stream_keys");


while ($row = $result->fetch_assoc()) {


    echo htmlspecialchars("   {$row['Field']} | {$row['Type']} | {$row['Null']} | {$row['Key']}\n", ENT_QUOTES, \'UTF-8\');


}


echo "\n";





// gucci_stream_keys 인덱스


echo "5. gucci_stream_keys 인덱스:\n";


$result = $mysqli->query("SHOW INDEX FROM gucci_stream_keys");


while ($row = $result->fetch_assoc()) {


    echo htmlspecialchars("   {$row['Key_name']} | {$row['Column_name']}\n", ENT_QUOTES, \'UTF-8\');


}


echo "\n";





// 데이터 개수


echo "6. 데이터 개수:\n";


$result = $mysqli->query("SELECT COUNT(*) as cnt FROM gucci_members");


$row = $result->fetch_assoc();


echo htmlspecialchars("   gucci_members: {$row['cnt']}개\n", ENT_QUOTES, \'UTF-8\');





$result = $mysqli->query("SELECT COUNT(*) as cnt FROM gucci_stream_keys");


$row = $result->fetch_assoc();


echo htmlspecialchars("   gucci_stream_keys: {$row['cnt']}개\n", ENT_QUOTES, \'UTF-8\');





echo "\n=== 마이그레이션 완료 ===\n";


echo "✅ DB 구조 개선이 완료되었습니다.\n";





$mysqli->close();





</html>


?>

<!doctype html>


<html lang="ko">
