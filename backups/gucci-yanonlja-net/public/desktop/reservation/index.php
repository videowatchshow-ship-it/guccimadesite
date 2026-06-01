<?php
/**
 * 데스크탑 예약 페이지 — 구찌야놀자
 * ref: https://schema.org/ReservationPackage
 * ref: https://owasp.org/www-project-secure-headers/
 * ref: https://developer.mozilla.org/en-US/docs/Web/HTML/Element/form
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
    header('Location: /mobile/reservation/', true, 302);
    exit;
}

$site_url   = 'https://xn--2e0bj1fruw33b6ti.net';
$page_url   = $site_url . '/desktop/reservation/';
$page_title = '테이블 예약 | 아바타 바카라 예약 — 구찌야놀자';
$page_desc  = '아바타 바카라 테이블 예약. 원하는 시간에 전용 테이블을 예약하고 현장감 있는 생방송을 즐기세요.';
$page_img   = $site_url . '/assets/images/avatar-baccarat-gucci-play.png';

$packages = [
    ['name' => '베이직',    'price' => '문의',  'features' => ['전용 테이블 1시간', '실시간 채팅 지원', '기본 고객 서비스']],
    ['name' => '프리미엄',  'price' => '문의',  'features' => ['전용 테이블 3시간', '전담 매니저 배정', '우선 입장 보장', 'VIP 채팅 채널'], 'highlight' => true],
    ['name' => 'VIP',       'price' => '문의',  'features' => ['무제한 전용 테이블', '24/7 전담 매니저', '최우선 입장', 'VIP 전용 채널', '특별 혜택']],
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
  {"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"홈","item":"https://xn--2e0bj1fruw33b6ti.net/"},{"@type":"ListItem","position":2,"name":"예약","item":"<?= htmlspecialchars($page_url, ENT_QUOTES, 'UTF-8') ?>"}]}
  </script>
  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
  <link rel="preload" href="/assets/css/mobile-responsive.css" as="style">
  <link rel="stylesheet" href="/assets/css/mobile-responsive.css">
  <link rel="stylesheet" href="/desktop/assets/css/desktop.css">
  <meta name="theme-color" content="#040f1c">
</head>
<body>
<a class="skip-to-main" href="#main-content">본문으로 바로가기</a>
<?php require_once dirname(__DIR__, 3) . '/core/helpers/header.php'; ?>

<main id="main-content" role="main">

  <section class="d-page-hero" aria-label="예약 페이지 헤더">
    <div class="d-inner">
      <nav aria-label="breadcrumb">
        <ol style="list-style:none;display:flex;gap:.5rem;font-size:.85rem;color:#6b7c93;padding:0;margin:0 0 .75rem;">
          <li><a href="/desktop/" style="color:#6b7c93;text-decoration:none;">홈</a></li>
          <li style="color:rgba(255,255,255,.2);">›</li>
          <li style="color:#f5c842;" aria-current="page">예약</li>
        </ol>
      </nav>
      <h1 style="font-size:clamp(1.5rem,3vw,2.25rem);color:#fff;margin-bottom:.5rem;">
        <span style="color:#f5c842;">테이블</span> 예약
      </h1>
      <p style="color:#8898aa;font-size:1rem;">원하는 패키지를 선택하고 전용 테이블을 예약하세요</p>
    </div>
  </section>

  <div class="d-inner" style="padding-top:2.5rem;padding-bottom:4rem;">

    <!-- 패키지 선택 -->
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;margin-bottom:3rem;" role="list" aria-label="예약 패키지">
      <?php foreach ($packages as $pkg): ?>
      <div class="d-card" role="listitem" style="<?= isset($pkg['highlight']) ? 'border-color:rgba(245,200,66,.5);background:rgba(245,200,66,.04);' : '' ?>position:relative;">
        <?php if (isset($pkg['highlight'])): ?>
        <div style="position:absolute;top:-12px;left:50%;transform:translateX(-50%);background:linear-gradient(135deg,#f5c842,#e6a800);color:#040f1c;font-size:.72rem;font-weight:700;padding:.25rem .875rem;border-radius:50px;white-space:nowrap;">추천</div>
        <?php endif; ?>
        <h2 style="font-size:1.25rem;font-weight:700;color:#f5c842;margin-bottom:.5rem;"><?= htmlspecialchars($pkg['name'], ENT_QUOTES, 'UTF-8') ?></h2>
        <div style="font-size:1.5rem;font-weight:700;color:#fff;margin-bottom:1.25rem;"><?= htmlspecialchars($pkg['price'], ENT_QUOTES, 'UTF-8') ?></div>
        <ul style="list-style:none;padding:0;margin:0 0 1.5rem;display:flex;flex-direction:column;gap:.6rem;" aria-label="<?= htmlspecialchars($pkg['name'], ENT_QUOTES, 'UTF-8') ?> 패키지 혜택">
          <?php foreach ($pkg['features'] as $f): ?>
          <li style="display:flex;align-items:center;gap:.6rem;font-size:.875rem;color:#8898aa;">
            <span style="color:#f5c842;font-weight:700;" aria-hidden="true">✓</span>
            <?= htmlspecialchars($f, ENT_QUOTES, 'UTF-8') ?>
          </li>
          <?php endforeach; ?>
        </ul>
        <a href="https://t.me/Fury0079" rel="noopener noreferrer" target="_blank"
          style="display:block;text-align:center;padding:.75rem;border-radius:8px;font-weight:700;font-size:.9rem;text-decoration:none;transition:all .2s;<?= isset($pkg['highlight']) ? 'background:linear-gradient(135deg,#f5c842,#e6a800);color:#040f1c;' : 'background:rgba(245,200,66,.1);color:#f5c842;border:1px solid rgba(245,200,66,.3);' ?>"
          aria-label="<?= htmlspecialchars($pkg['name'], ENT_QUOTES, 'UTF-8') ?> 패키지 텔레그램 문의 (새 탭에서 열림)">
          텔레그램 문의
        </a>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- 예약 안내 -->
    <div style="background:rgba(255,255,255,.03);border:1px solid rgba(245,200,66,.12);border-radius:12px;padding:2rem;" role="region" aria-labelledby="d-res-guide-title">
      <h2 id="d-res-guide-title" style="font-size:1.1rem;font-weight:700;color:#f5c842;margin-bottom:1.25rem;">📋 예약 안내</h2>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
        <div>
          <h3 style="font-size:.95rem;font-weight:700;color:#c8d8e8;margin-bottom:.75rem;">예약 방법</h3>
          <ol style="padding-left:1.25rem;margin:0;display:flex;flex-direction:column;gap:.5rem;">
            <li style="font-size:.875rem;color:#8898aa;line-height:1.6;">패키지 선택 후 텔레그램 문의</li>
            <li style="font-size:.875rem;color:#8898aa;line-height:1.6;">원하는 날짜/시간 협의</li>
            <li style="font-size:.875rem;color:#8898aa;line-height:1.6;">예약 확정 및 안내 수령</li>
            <li style="font-size:.875rem;color:#8898aa;line-height:1.6;">예약 시간에 접속</li>
          </ol>
        </div>
        <div>
          <h3 style="font-size:.95rem;font-weight:700;color:#c8d8e8;margin-bottom:.75rem;">유의사항</h3>
          <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:.5rem;">
            <li style="font-size:.875rem;color:#8898aa;line-height:1.6;display:flex;gap:.5rem;"><span style="color:#f5c842;" aria-hidden="true">•</span>예약 취소는 24시간 전까지 가능</li>
            <li style="font-size:.875rem;color:#8898aa;line-height:1.6;display:flex;gap:.5rem;"><span style="color:#f5c842;" aria-hidden="true">•</span>노쇼 시 다음 예약 제한</li>
            <li style="font-size:.875rem;color:#8898aa;line-height:1.6;display:flex;gap:.5rem;"><span style="color:#f5c842;" aria-hidden="true">•</span>문의는 텔레그램으로만 가능</li>
          </ul>
        </div>
      </div>
    </div>

  </div>

</main>

<?php require_once dirname(__DIR__, 3) . '/core/helpers/footer.php'; ?>
<script src="/desktop/assets/js/desktop.js" defer></script>
</body>
</html>
