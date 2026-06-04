<?php
declare(strict_types=1);
/**
 * 개인정보처리방침 — 구찌야놀자
 */
$site_url = 'https://xn--2e0bj1fruw33b6ti.net';
$page_title = '개인정보처리방침 | 구찌야놀자';
$page_desc = '구찌야놀자 개인정보처리방침. 수집 항목, 이용 목적, 보관 기간 및 이용자 권리 안내.';
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?></title>
  <meta name="description" content="<?= htmlspecialchars($page_desc, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="robots" content="index,follow">
  <link rel="canonical" href="<?= htmlspecialchars($site_url, ENT_QUOTES, 'UTF-8') ?>/privacy-policy.php">
  <link rel="stylesheet" href="/assets/css/common.css">
  <style>
    body { background:#071a2e; color:#c8d8e8; font-family:sans-serif; line-height:1.8; margin:0; }
    main { max-width:800px; margin:0 auto; padding:2rem 1.5rem 4rem; }
    h1 { color:#f5c842; font-size:1.75rem; margin-bottom:1.5rem; }
    h2 { color:#f5c842; font-size:1.15rem; margin-top:2rem; }
    a { color:#f5c842; }
    p, li { color:#8898aa; }
    .back { display:inline-block; margin-bottom:1.5rem; text-decoration:none; }
  </style>
</head>
<body>
<a class="skip-to-main" href="#main-content">본문으로 바로가기</a>
<main id="main-content" role="main">
  <a href="/" class="back">← 홈으로</a>
  <h1>개인정보처리방침</h1>
  <p>구찌야놀자(이하 "회사")는 이용자의 개인정보를 중요하게 생각하며, 관련 법령을 준수합니다.</p>

  <h2>1. 수집하는 개인정보 항목</h2>
  <ul>
    <li>문의 시: 이름(닉네임), 연락처, 문의 내용</li>
    <li>예약 시: 이름, 연락처, 예약 일시</li>
    <li>자동 수집: IP 주소, 접속 로그, 쿠키</li>
  </ul>

  <h2>2. 개인정보의 이용 목적</h2>
  <ul>
    <li>서비스 제공 및 고객 상담</li>
    <li>예약 확인 및 운영 안내</li>
    <li>서비스 개선 및 보안</li>
  </ul>

  <h2>3. 보관 기간</h2>
  <p>목적 달성 후 지체 없이 파기하며, 관련 법령에 따라 일정 기간 보관할 수 있습니다.</p>

  <h2>4. 이용자 권리</h2>
  <p>이용자는 개인정보 열람, 정정, 삭제, 처리 정지를 요청할 수 있습니다. 문의: <a href="/contact/">고객센터</a></p>

  <h2>5. 시행일</h2>
  <p>본 방침은 2026년 6월 4일부터 시행됩니다.</p>
</main>
<?php
$_footer = __DIR__ . '/../core/helpers/footer.php';
if (file_exists($_footer)) {
    include $_footer;
} elseif (file_exists(__DIR__ . '/footer-loader.php')) {
    include __DIR__ . '/footer-loader.php';
}
?>
</body>
</html>
