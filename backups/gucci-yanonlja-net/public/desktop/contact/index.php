<?php
/**
 * 데스크탑 연락처 페이지 — 구찌야놀자
 * ref: https://schema.org/ContactPage
 * ref: https://owasp.org/www-project-secure-headers/
 */
declare(strict_types=1);

if (!headers_sent()) {
    header_remove('X-Powered-By');
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header("Content-Security-Policy: default-src 'self' https:; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://accounts.google.com; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; img-src 'self' data: https:; font-src 'self' data: https://cdn.jsdelivr.net; connect-src 'self' https: wss:; frame-ancestors 'self';");
    $https = $_SERVER['HTTPS'] ?? '';
    if ($https !== '' && $https !== 'off') {
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
    }
}

$ua = (string)($_SERVER['HTTP_USER_AGENT'] ?? '');
if (preg_match('/Mobile|Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i', $ua)) {
    header('Location: /mobile/contact/', true, 302);
    exit;
}

$site_url   = 'https://xn--2e0bj1fruw33b6ti.net';
$page_url   = $site_url . '/desktop/contact/';
$page_title = '연락처 | 문의하기 — 구찌야놀자';
$page_desc  = '구찌야놀자 문의 및 연락처. 텔레그램, 카카오톡으로 빠르게 문의하세요.';
$page_img   = $site_url . '/assets/images/avatar-baccarat-gucci-play.png';

$contacts = [
    ['icon' => '📱', 'name' => '텔레그램', 'handle' => '@Fury0079',    'desc' => '가장 빠른 응답. 24시간 운영.',          'url' => 'https://t.me/Fury0079',    'color' => '#0088cc'],
    ['icon' => '💬', 'name' => '카카오톡', 'handle' => '문의 시 안내', 'desc' => '카카오톡 ID는 텔레그램 문의 후 안내.', 'url' => 'https://t.me/Fury0079',    'color' => '#fee500'],
    ['icon' => '✉️', 'name' => '이메일',   'handle' => '문의 시 안내', 'desc' => '이메일 주소는 텔레그램 문의 후 안내.', 'url' => 'https://t.me/Fury0079',    'color' => '#68d391'],
];
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?></title>
  <meta name="description" content="<?= htmlspecialchars($page_desc, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="robots" content="index,follow,max-snippet:-1,max-image-preview:large,max-video-preview:-1">
  <link rel="canonical" href="<?= htmlspecialchars($page_url, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:type"        content="website">
  <meta property="og:title"       content="<?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:description" content="<?= htmlspecialchars($page_desc, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:url"         content="<?= htmlspecialchars($page_url, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:image"       content="<?= htmlspecialchars($page_img, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:locale"      content="ko_KR">
  <script type="application/ld+json">
  {"@context":"https://schema.org","@type":"ContactPage","name":"구찌야놀자 연락처","url":"<?= htmlspecialchars($page_url, ENT_QUOTES, 'UTF-8') ?>","description":"<?= htmlspecialchars($page_desc, ENT_QUOTES, 'UTF-8') ?>"}
  </script>
  <script type="application/ld+json">
  {"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"홈","item":"https://xn--2e0bj1fruw33b6ti.net/"},{"@type":"ListItem","position":2,"name":"연락처","item":"<?= htmlspecialchars($page_url, ENT_QUOTES, 'UTF-8') ?>"}]}
  </script>
  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
  <link rel="preload" href="/assets/css/mobile-responsive.css" as="style">
  <link rel="stylesheet" href="/assets/css/mobile-responsive.css">
  <link rel="stylesheet" href="/desktop/assets/css/desktop.css">
  <meta name="theme-color" content="#040f1c">
</head>
<body>
  <!-- 🏠 헤더 (메뉴 폰트: 1.1rem/18px, 간격 균형감 있음) -->
  <header role="banner" style="background: linear-gradient(135deg, #071a2e, #0a2540); padding: 1.2rem 2rem; border-bottom: 3px solid rgba(245,200,66,0.3); margin-bottom: 1.5rem;">
    <nav style="max-width: 1400px; margin: 0 auto; display: flex; gap: 1.5rem; align-items: center; flex-wrap: wrap; font-family: 'SchoolSafetyTteokbokki', sans-serif; font-size: 1.1rem; font-weight: 600;">
      <a href="/" style="font-size: 1.3rem; font-weight: 800; color: #f5c842; text-decoration: none; white-space: nowrap; transition: all 0.2s; display: inline-block; padding: 0.5rem 0.75rem; border-radius: 6px;">🏠 홈</a>
      <a href="/streaming/" style="color: #c8d8e8; text-decoration: none; white-space: nowrap; transition: all 0.2s; display: inline-block; padding: 0.5rem 0.75rem; border-radius: 6px;">🔴 스트리밍</a>
      <a href="/games/" style="color: #c8d8e8; text-decoration: none; white-space: nowrap; transition: all 0.2s; display: inline-block; padding: 0.5rem 0.75rem; border-radius: 6px;">🃏 게임</a>
      <a href="/free-board/" style="color: #c8d8e8; text-decoration: none; white-space: nowrap; transition: all 0.2s; display: inline-block; padding: 0.5rem 0.75rem; border-radius: 6px;">💬 게시판</a>
      <a href="/reservation/" style="color: #c8d8e8; text-decoration: none; white-space: nowrap; transition: all 0.2s; display: inline-block; padding: 0.5rem 0.75rem; border-radius: 6px;">📅 예약</a>
      <a href="/contact/" style="color: #c8d8e8; text-decoration: none; white-space: nowrap; transition: all 0.2s; display: inline-block; padding: 0.5rem 0.75rem; border-radius: 6px;">📞 연락</a>
    </nav>
  </header>
<a class="skip-to-main" href="#main-content">본문으로 바로가기</a>
<?php require_once dirname(__DIR__, 3) . '/core/helpers/header.php'; ?>

<main id="main-content" role="main">

  <section class="d-page-hero" aria-label="연락처 헤더">
    <div class="d-inner">
      <nav aria-label="breadcrumb">
        <ol style="list-style:none;display:flex;gap:.5rem;font-size:.85rem;color:#6b7c93;padding:0;margin:0 0 .75rem;">
          <li><a href="/desktop/" style="color:#6b7c93;text-decoration:none;">홈</a></li>
          <li style="color:rgba(255,255,255,.2);">›</li>
          <li style="color:#f5c842;" aria-current="page">연락처</li>
        </ol>
      </nav>
      <h1 style="font-size:clamp(1.5rem,3vw,2.25rem);color:#fff;margin-bottom:.5rem;">
        <span style="color:#f5c842;">문의</span>하기
      </h1>
      <p style="color:#8898aa;font-size:1rem;">텔레그램으로 빠르게 문의하세요 — 24시간 운영</p>
    </div>
  </section>

  <div class="d-inner" style="padding-top:2.5rem;padding-bottom:4rem;">

    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;margin-bottom:3rem;" role="list" aria-label="연락처 목록">
      <?php foreach ($contacts as $c): ?>
      <a href="<?= htmlspecialchars($c['url'], ENT_QUOTES, 'UTF-8') ?>"
        rel="noopener noreferrer" target="_blank"
        class="d-card"
        role="listitem"
        style="text-decoration:none;display:block;transition:all .3s;"
        aria-label="<?= htmlspecialchars($c['name'], ENT_QUOTES, 'UTF-8') ?> 문의 (새 탭에서 열림)">
        <div style="font-size:2.5rem;margin-bottom:1rem;" aria-hidden="true"><?= htmlspecialchars($c['icon'], ENT_QUOTES, 'UTF-8') ?></div>
        <h2 style="font-size:1.1rem;font-weight:700;color:#fff;margin-bottom:.35rem;"><?= htmlspecialchars($c['name'], ENT_QUOTES, 'UTF-8') ?></h2>
        <div style="font-size:.9rem;font-weight:600;color:<?= htmlspecialchars($c['color'], ENT_QUOTES, 'UTF-8') ?>;margin-bottom:.5rem;"><?= htmlspecialchars($c['handle'], ENT_QUOTES, 'UTF-8') ?></div>
        <p style="font-size:.875rem;color:#6b7c93;line-height:1.6;margin:0;"><?= htmlspecialchars($c['desc'], ENT_QUOTES, 'UTF-8') ?></p>
      </a>
      <?php endforeach; ?>
    </div>

    <!-- 운영 시간 -->
    <div style="background:rgba(255,255,255,.03);border:1px solid rgba(245,200,66,.12);border-radius:12px;padding:2rem;text-align:center;" role="region" aria-labelledby="d-hours-title">
      <h2 id="d-hours-title" style="font-size:1.1rem;font-weight:700;color:#f5c842;margin-bottom:1rem;">⏰ 운영 시간</h2>
      <div style="display:flex;gap:3rem;justify-content:center;flex-wrap:wrap;">
        <div>
          <div style="font-size:.85rem;color:#6b7c93;margin-bottom:.25rem;">텔레그램 문의</div>
          <div style="font-size:1rem;font-weight:700;color:#fff;">24시간 / 365일</div>
        </div>
        <div>
          <div style="font-size:.85rem;color:#6b7c93;margin-bottom:.25rem;">생방송 운영</div>
          <div style="font-size:1rem;font-weight:700;color:#fff;">오전 10:00 ~ 익일 02:00</div>
        </div>
        <div>
          <div style="font-size:.85rem;color:#6b7c93;margin-bottom:.25rem;">평균 응답 시간</div>
          <div style="font-size:1rem;font-weight:700;color:#f5c842;">5분 이내</div>
        </div>
      </div>
    </div>

  </div>

</main>

<?php require_once dirname(__DIR__, 3) . '/core/helpers/footer.php'; ?>
<script src="/desktop/assets/js/desktop.js" defer></script>
</body>
</html>

