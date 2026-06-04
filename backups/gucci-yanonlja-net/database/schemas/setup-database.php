<?php
/**
 * 데이터베이스 초기 설정 스크립트 — 구찌야놀자
 * ref: https://mariadb.com/docs/server/ref/mdb/sql-statements/CREATE_TABLE/
 * ref: https://www.php.net/manual/en/book.mysqli.php
 * ref: https://owasp.org/www-project-secure-headers/
 *
 * 접속: https://xn--2e0bj1fruw33b6ti.net/database/schemas/setup-database.php
 * 주의: 배포 후 이 파일은 웹에서 접근 불가하도록 apache2/htaccess 설정 필요
 */
declare(strict_types=1);

/* ── 보안 헤더
 * ref: https://owasp.org/www-project-secure-headers/
 */
if (!headers_sent()) {
    header_remove('X-Powered-By');
    header('X-Frame-Options: DENY');
    header('X-Content-Type-Options: nosniff');
    header('X-Robots-Tag: noindex, nofollow');
    header('Content-Type: text/html; charset=utf-8');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    $https = $_SERVER['HTTPS'] ?? '';
    if ($https !== '' && $https !== 'off') {
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
    }
}

error_reporting(0);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

/* ── DB 연결 정보 (환경변수 우선)
 * ref: https://www.php.net/manual/en/function.getenv.php
 */
$db_host = (string)(getenv('GUCCI_DB_HOST') ?: 'localhost');
$db_user = (string)(getenv('GUCCI_DB_USER') ?: 'gucci_user');
$db_pass = (string)(getenv('GUCCI_DB_PASS') ?: 'GuCCi2026Secure');
$db_name = (string)(getenv('GUCCI_DB_NAME') ?: 'gucci_wordpress');

$results        = [];
$overall_status = 'success';

try {
    /* ── DB 연결
     * ref: https://www.php.net/manual/en/class.mysqli.php
     */
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
    $mysqli->set_charset('utf8mb4');
    $results[] = ['step' => 'DB 연결', 'status' => 'success', 'message' => "연결 성공: {$db_name}@{$db_host}"];

    /* ── 1. gucci_members 테이블
     * ref: https://mariadb.com/docs/server/ref/mdb/sql-statements/CREATE_TABLE/
     */
    $mysqli->query("CREATE TABLE IF NOT EXISTS gucci_members (
        id                  INT AUTO_INCREMENT PRIMARY KEY,
        google_id           VARCHAR(100) UNIQUE NOT NULL COMMENT 'Google OAuth sub',
        email               VARCHAR(200) NOT NULL COMMENT '이메일 주소',
        name                VARCHAR(100) COMMENT 'Google 프로필 이름',
        profile_picture_url VARCHAR(500) COMMENT 'Google 프로필 사진 URL',
        account_holder_name VARCHAR(255) COMMENT '예금주명',
        phone_number        VARCHAR(20) COMMENT '전화번호',
        bank_name           VARCHAR(100) COMMENT '은행명',
        account_number      VARCHAR(50) COMMENT '계좌번호',
        is_admin            TINYINT(1) DEFAULT 0 COMMENT '관리자 여부',
        created_at          DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '가입일',
        updated_at          DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '수정일',
        INDEX idx_google_id (google_id),
        INDEX idx_email     (email),
        INDEX idx_is_admin  (is_admin)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='구찌야놀자 회원 테이블'");
    $results[] = ['step' => 'gucci_members 테이블', 'status' => 'success', 'message' => '생성 완료'];

    /* ── 2. gucci_stream_keys 테이블 */
    $mysqli->query("CREATE TABLE IF NOT EXISTS gucci_stream_keys (
        id          INT AUTO_INCREMENT PRIMARY KEY,
        user_id     INT NOT NULL COMMENT '회원 ID (FK)',
        stream_key  VARCHAR(100) NOT NULL UNIQUE COMMENT '스트림 키',
        title       VARCHAR(200) COMMENT '스트림 제목',
        description TEXT COMMENT '스트림 설명',
        is_active   TINYINT(1) DEFAULT 1 COMMENT '활성 여부',
        created_at  DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '생성일',
        updated_at  DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '수정일',
        FOREIGN KEY (user_id) REFERENCES gucci_members(id) ON DELETE CASCADE,
        INDEX idx_user_id   (user_id),
        INDEX idx_stream_key(stream_key),
        INDEX idx_is_active (is_active)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='스트림 키 관리 테이블'");
    $results[] = ['step' => 'gucci_stream_keys 테이블', 'status' => 'success', 'message' => '생성 완료'];

    /* ── 3. gucci_audit_log 테이블 (감사 로그)
     * ref: https://owasp.org/www-project-top-ten/
     */
    $mysqli->query("CREATE TABLE IF NOT EXISTS gucci_audit_log (
        id         INT AUTO_INCREMENT PRIMARY KEY,
        user_id    INT COMMENT '회원 ID',
        action     VARCHAR(100) NOT NULL COMMENT '액션',
        detail     TEXT COMMENT '상세 내용',
        ip_address VARCHAR(45) COMMENT 'IP 주소',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '발생일',
        INDEX idx_user_id  (user_id),
        INDEX idx_action   (action),
        INDEX idx_created  (created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='감사 로그 테이블'");
    $results[] = ['step' => 'gucci_audit_log 테이블', 'status' => 'success', 'message' => '생성 완료'];

    /* ── 4. gucci_chat_messages 테이블 (채팅 로그) */
    $mysqli->query("CREATE TABLE IF NOT EXISTS gucci_chat_messages (
        id         INT AUTO_INCREMENT PRIMARY KEY,
        user_id    INT COMMENT '회원 ID',
        nickname   VARCHAR(100) COMMENT '닉네임',
        message    TEXT NOT NULL COMMENT '메시지',
        room_id    VARCHAR(50) DEFAULT 'main' COMMENT '채팅방 ID',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '발송일',
        INDEX idx_room_id  (room_id),
        INDEX idx_created  (created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='채팅 메시지 테이블'");
    $results[] = ['step' => 'gucci_chat_messages 테이블', 'status' => 'success', 'message' => '생성 완료'];

    /* ── 5. 테이블 수 확인 */
    $check = $mysqli->query("SHOW TABLES LIKE 'gucci_%'");
    $results[] = ['step' => '테이블 확인', 'status' => 'success', 'message' => "총 {$check->num_rows}개 테이블 존재"];

    /* ── 6. 관리자 계정 확인 */
    $admin_check = $mysqli->query("SELECT COUNT(*) AS cnt FROM gucci_members WHERE is_admin=1");
    $admin_row   = $admin_check->fetch_assoc();
    $admin_count = (int)($admin_row['cnt'] ?? 0);
    if ($admin_count > 0) {
        $results[] = ['step' => '관리자 계정', 'status' => 'success', 'message' => "{$admin_count}명의 관리자 존재"];
    } else {
        $results[] = ['step' => '관리자 계정', 'status' => 'warning', 'message' => '관리자 없음 (첫 Google 로그인 시 자동 생성)'];
    }

    /* ── 7. 환경변수 확인 */
    $env_ok = (getenv('GUCCI_DB_HOST') && getenv('GUCCI_DB_USER') && getenv('GUCCI_DB_PASS') && getenv('GUCCI_DB_NAME'));
    $results[] = [
        'step'    => '환경변수',
        'status'  => $env_ok ? 'success' : 'warning',
        'message' => $env_ok ? '모든 환경변수 설정됨' : '일부 환경변수 누락 (기본값 사용 중)',
    ];

    $mysqli->close();

} catch (\mysqli_sql_exception $e) {
    $overall_status = 'error';
    $results[] = ['step' => 'DB 오류', 'status' => 'error', 'message' => $e->getMessage()];
} catch (\Exception $e) {
    $overall_status = 'error';
    $results[] = ['step' => '오류 발생', 'status' => 'error', 'message' => $e->getMessage()];
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>데이터베이스 설정 — 구찌야놀자</title>
  <meta name="robots" content="noindex, nofollow">
  <style>
    @font-face{font-family:'SchoolSafetyTteokbokki';src:url('https://cdn.jsdelivr.net/gh/projectnoonnu/2510-1@1.0/HakgyoansimTTeokbokkiB.woff2') format('woff2');font-weight:normal;font-style:normal;font-display:swap;}
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
    body{font-family:'SchoolSafetyTteokbokki',sans-serif;background:#040f1c;color:#c8d8e8;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:1.5rem;}
    .wrap{background:#071a2e;border:1px solid rgba(245,200,66,.2);border-radius:16px;max-width:700px;width:100%;padding:2.5rem;}
    h1{font-size:1.5rem;color:#f5c842;margin-bottom:.5rem;text-align:center;}
    .sub{text-align:center;color:#6b7c93;font-size:.875rem;margin-bottom:2rem;}
    .badge{display:inline-flex;align-items:center;gap:.4rem;padding:.4rem 1rem;border-radius:50px;font-size:.85rem;font-weight:700;margin-bottom:1.5rem;}
    .badge-ok{background:rgba(72,187,120,.15);color:#68d391;border:1px solid rgba(72,187,120,.25);}
    .badge-err{background:rgba(229,62,62,.15);color:#fc8181;border:1px solid rgba(229,62,62,.25);}
    .result{padding:.875rem 1rem;border-radius:8px;margin-bottom:.6rem;border-left:4px solid;}
    .result.success{background:rgba(72,187,120,.08);border-color:#68d391;}
    .result.warning{background:rgba(245,200,66,.08);border-color:#f5c842;}
    .result.error{background:rgba(229,62,62,.08);border-color:#fc8181;}
    .result-step{font-size:.875rem;font-weight:700;color:#c8d8e8;margin-bottom:.2rem;}
    .result-msg{font-size:.8rem;color:#8898aa;}
    .actions{margin-top:2rem;display:flex;gap:.75rem;justify-content:center;flex-wrap:wrap;}
    .btn{display:inline-flex;align-items:center;padding:.75rem 1.5rem;border-radius:8px;font-family:inherit;font-size:.875rem;font-weight:700;text-decoration:none;transition:all .2s;}
    .btn-primary{background:linear-gradient(135deg,#f5c842,#e6a800);color:#040f1c;}
    .btn-secondary{background:rgba(255,255,255,.06);color:#c8d8e8;border:1px solid rgba(255,255,255,.1);}
    .btn:hover{transform:translateY(-2px);}
    .info{background:rgba(99,91,255,.08);border:1px solid rgba(99,91,255,.2);border-radius:10px;padding:1.25rem;margin-top:1.5rem;}
    .info-title{font-size:.875rem;font-weight:700;color:#a5b4fc;margin-bottom:.75rem;}
    .info-list{list-style:none;padding:0;display:flex;flex-direction:column;gap:.4rem;}
    .info-list li{font-size:.8rem;color:#8898aa;display:flex;gap:.4rem;}
    .info-list li::before{content:'✓';color:#68d391;font-weight:700;flex-shrink:0;}
  </style>
</head>
<body>
<div class="wrap">
  <h1>🗄️ 데이터베이스 설정</h1>
  <p class="sub">구찌야놀자 — 초기 테이블 생성</p>
  <div style="text-align:center;">
    <span class="badge <?= $overall_status === 'success' ? 'badge-ok' : 'badge-err' ?>">
      <?= $overall_status === 'success' ? '✅ 설정 완료' : '❌ 오류 발생' ?>
    </span>
  </div>
  <?php foreach ($results as $r): ?>
  <div class="result <?= htmlspecialchars($r['status'], ENT_QUOTES, 'UTF-8') ?>">
    <div class="result-step"><?= htmlspecialchars($r['step'], ENT_QUOTES, 'UTF-8') ?></div>
    <div class="result-msg"><?= htmlspecialchars($r['message'], ENT_QUOTES, 'UTF-8') ?></div>
  </div>
  <?php endforeach; ?>
  <?php if ($overall_status === 'success'): ?>
  <div class="info">
    <div class="info-title">다음 단계</div>
    <ul class="info-list">
      <li>메인 페이지에서 Google 로그인 테스트</li>
      <li>관리자 계정으로 로그인 (centkim177@gmail.com 등)</li>
      <li>관리자 대시보드 접속 (/admin/dashboard/)</li>
      <li>스트림 키 생성 테스트</li>
    </ul>
  </div>
  <?php endif; ?>
  <div class="actions">
    <a href="/desktop/" class="btn btn-primary">메인 페이지로</a>
    <?php if ($overall_status === 'success'): ?>
    <a href="/admin/dashboard/" class="btn btn-secondary">관리자 대시보드</a>
    <?php else: ?>
    <a href="<?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? '/database/schemas/setup-database.php', ENT_QUOTES, 'UTF-8') ?>" class="btn btn-secondary">다시 시도</a>
    <?php endif; ?>
  </div>
</div>
</body>
</html>
