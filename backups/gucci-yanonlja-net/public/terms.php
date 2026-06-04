<?php
declare(strict_types=1);
/**
 * 이용약관 — 구찌야놀자
 */
$site_url = 'https://xn--2e0bj1fruw33b6ti.net';
$page_title = '이용약관 | 구찌야놀자';
$page_desc = '구찌야놀자 서비스 이용약관. 이용 조건, 금지 행위, 면책 및 분쟁 해결 안내.';
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?></title>
  <meta name="description" content="<?= htmlspecialchars($page_desc, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="robots" content="index,follow">
  <link rel="canonical" href="<?= htmlspecialchars($site_url, ENT_QUOTES, 'UTF-8') ?>/terms.php">
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
  <h1>이용약관</h1>
  <p>본 약관은 구찌야놀자(이하 "회사")가 제공하는 서비스 이용과 관련하여 회사와 이용자 간 권리·의무를 규정합니다.</p>

  <h2>1. 서비스 내용</h2>
  <p>회사는 캄보디아 현지 아바타 바카라 생방송 중계 및 관련 정보 제공 서비스를 운영합니다.</p>

  <h2>2. 이용 자격</h2>
  <p>만 19세 이상 성인만 이용할 수 있습니다. 미성년자의 이용은 금지됩니다.</p>

  <h2>3. 이용자 의무</h2>
  <ul>
    <li>타인의 정보를 도용하거나 허위 정보를 제공하지 않습니다.</li>
    <li>서비스 운영을 방해하는 행위를 하지 않습니다.</li>
    <li>관련 법령 및 본 약관을 준수합니다.</li>
  </ul>

  <h2>4. 면책</h2>
  <p>회사는 천재지변, 네트워크 장애 등 불가항력으로 인한 서비스 중단에 대해 책임을 지지 않습니다.</p>

  <h2>5. 문의</h2>
  <p>약관 관련 문의: <a href="/contact/">고객센터</a> · 텔레그램 <a href="https://t.me/Fury0079" rel="noopener noreferrer" target="_blank">@Fury0079</a></p>

  <h2>6. 시행일</h2>
  <p>본 약관은 2026년 6월 4일부터 시행됩니다.</p>
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
