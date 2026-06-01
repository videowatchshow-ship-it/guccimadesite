<?php
/**
 * 모바일 게임 페이지 — 구찌야놀자
 * ref: https://schema.org/Game
 * ref: https://owasp.org/www-project-secure-headers/
 */
declare(strict_types=1);

if (!headers_sent()) {
    header_remove('X-Powered-By');
    header('X-Frame-Options: DENY');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header("Content-Security-Policy: default-src 'self' https:; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://accounts.google.com; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; img-src 'self' data: https:; font-src 'self' data: https://cdn.jsdelivr.net; connect-src 'self' https: wss:; frame-ancestors 'none';");
    $https = $_SERVER['HTTPS'] ?? '';
    if ($https !== '' && $https !== 'off') {
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
    }
}

$ua = (string)($_SERVER['HTTP_USER_AGENT'] ?? '');
if (!preg_match('/Mobile|Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i', $ua)) {
    header('Location: /desktop/games/', true, 302);
    exit;
}

$site_url   = 'https://xn--2e0bj1fruw33b6ti.net';
$page_url   = $site_url . '/mobile/games/';
$page_title = '모바일 게임 안내 | 바카라 · 룰렛 — 구찌야놀자';
$page_desc  = '구찌야놀자 모바일 게임 안내. 아바타 바카라, 룰렛, 블랙잭 등 다양한 게임을 모바일에서 즐기세요.';
$page_img   = $site_url . '/assets/images/avatar-baccarat-gucci-play.png';

$games = [
    ['icon'=>'🎰','title'=>'아바타 바카라','desc'=>'캄보디아 현장 딜러 실시간 생방송','rtp'=>'98.94%','badge'=>'인기 1위','link'=>'/mobile/streaming/'],
    ['icon'=>'⚡','title'=>'스피드 바카라','desc'=>'27초 이내 빠른 진행 고속 게임','rtp'=>'98.76%','badge'=>'NEW','link'=>'/mobile/reservation/'],
    ['icon'=>'🎡','title'=>'유러피안 룰렛','desc'=>'싱글 제로 37개 숫자 클래식 룰렛','rtp'=>'97.30%','badge'=>'','link'=>'/mobile/reservation/'],
    ['icon'=>'🃏','title'=>'블랙잭','desc'=>'딜러와 1:1 전략 카드 게임','rtp'=>'99.50%','badge'=>'','link'=>'/mobile/reservation/'],
    ['icon'=>'🐉','title'=>'드래곤 타이거','desc'=>'높은 카드 맞추기 심플 게임','rtp'=>'96.72%','badge'=>'','link'=>'/mobile/reservation/'],
    ['icon'=>'🎲','title'=>'식보','desc'=>'3개 주사위 아시아 전통 게임','rtp'=>'97.22%','badge'=>'','link'=>'/mobile/reservation/'],
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
  <meta name="robots" content="index,follow">
  <link rel="canonical" href="<?= htmlspecialchars($page_url, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:type"        content="website">
  <meta property="og:title"       content="<?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:description" content="<?= htmlspecialchars($page_desc, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:url"         content="<?= htmlspecialchars($page_url, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:image"       content="<?= htmlspecialchars($page_img, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:locale"      content="ko_KR">
  <script type="application/ld+json">
  {"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"홈","item":"https://xn--2e0bj1fruw33b6ti.net/"},{"@type":"ListItem","position":2,"name":"게임","item":"https://xn--2e0bj1fruw33b6ti.net/mobile/games/"}]}
  </script>
  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
  <link rel="preload" href="/assets/css/mobile-responsive.css" as="style">
  <link rel="stylesheet" href="/assets/css/mobile-responsive.css">
  <link rel="stylesheet" href="/mobile/assets/css/mobile.css">
  <meta name="theme-color" content="#040f1c">
  <style>
    .mg-hero{background:linear-gradient(180deg,#071a2e 0%,#040f1c 100%);padding:1.5rem 1rem 1rem;border-bottom:1px solid rgba(245,200,66,.15);}
    .mg-grid{display:flex;flex-direction:column;gap:.75rem;padding:1rem;}
    .mg-card{background:linear-gradient(145deg,rgba(14,45,90,.6),rgba(10,33,64,.4));border:1px solid rgba(245,200,66,.12);border-radius:12px;overflow:hidden;display:flex;align-items:center;gap:1rem;padding:1rem;text-decoration:none;color:#c8d8e8;transition:all .25s ease;position:relative;}
    .mg-card:active{border-color:rgba(245,200,66,.35);background:rgba(245,200,66,.05);}
    .mg-card:focus-visible{outline:3px solid #f5c842;outline-offset:3px;}
    .mg-icon{width:52px;height:52px;background:linear-gradient(135deg,rgba(245,200,66,.2),rgba(99,91,255,.15));border:1px solid rgba(245,200,66,.25);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;flex-shrink:0;}
    .mg-info{flex:1;}
    .mg-title{font-size:.95rem;font-weight:700;color:#fff;margin-bottom:.2rem;}
    .mg-desc{font-size:.78rem;color:#8898aa;line-height:1.5;margin-bottom:.3rem;}
    .mg-rtp{font-size:.72rem;color:#f5c842;font-weight:600;}
    .mg-badge{position:absolute;top:8px;right:8px;font-size:.65rem;font-weight:700;padding:.15rem .45rem;border-radius:4px;}
    .mg-badge-hot{background:rgba(229,62,62,.2);color:#fc8181;}
    .mg-badge-new{background:rgba(245,200,66,.15);color:#f5c842;}
    .mg-arrow{color:#f5c842;opacity:.5;font-size:.875rem;flex-shrink:0;}
    .mg-cta{padding:1rem;margin:.5rem 1rem 2rem;background:#071a2e;border:1px solid rgba(245,200,66,.15);border-radius:12px;text-align:center;}
    .mg-cta h2{font-size:1rem;color:#f5c842;margin-bottom:.5rem;}
    .mg-cta p{font-size:.82rem;color:#8898aa;line-height:1.6;margin-bottom:1rem;}
  </style>
</head>
<body>
<a class="skip-to-main" href="#main-content">본문으로 바로가기</a>
<?php require_once dirname(__DIR__, 3) . '/core/helpers/header.php'; ?>

<main id="main-content" role="main">

  <section class="mg-hero" aria-label="게임 페이지 헤더">
    <nav aria-label="breadcrumb">
      <ol style="list-style:none;display:flex;gap:.4rem;font-size:.78rem;color:#6b7c93;padding:0;margin:0 0 .5rem;">
        <li><a href="/mobile/" style="color:#6b7c93;text-decoration:none;">홈</a></li>
        <li style="color:rgba(255,255,255,.2);">›</li>
        <li style="color:#f5c842;" aria-current="page">게임</li>
      </ol>
    </nav>
    <h1 style="font-size:clamp(1.25rem,6vw,1.75rem);color:#fff;margin-bottom:.35rem;">
      <span style="color:#f5c842;">게임</span> 안내
    </h1>
    <p style="font-size:.85rem;color:#8898aa;">아바타 바카라 · 룰렛 · 블랙잭 · 슬롯</p>
  </section>

  <section aria-labelledby="mg-list-title">
    <h2 id="mg-list-title" style="position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0,0,0,0);">게임 목록</h2>
    <div class="mg-grid">
      <?php foreach ($games as $g): ?>
      <a href="<?= htmlspecialchars($g['link'], ENT_QUOTES, 'UTF-8') ?>"
         class="mg-card"
         aria-label="<?= htmlspecialchars($g['title'], ENT_QUOTES, 'UTF-8') ?>">
        <div class="mg-icon" aria-hidden="true"><?= htmlspecialchars($g['icon'], ENT_QUOTES, 'UTF-8') ?></div>
        <div class="mg-info">
          <div class="mg-title"><?= htmlspecialchars($g['title'], ENT_QUOTES, 'UTF-8') ?></div>
          <div class="mg-desc"><?= htmlspecialchars($g['desc'], ENT_QUOTES, 'UTF-8') ?></div>
          <div class="mg-rtp">RTP <?= htmlspecialchars($g['rtp'], ENT_QUOTES, 'UTF-8') ?></div>
        </div>
        <span class="mg-arrow" aria-hidden="true">›</span>
        <?php if ($g['badge'] !== ''): ?>
        <span class="mg-badge <?= $g['badge'] === '인기 1위' ? 'mg-badge-hot' : 'mg-badge-new' ?>">
          <?= htmlspecialchars($g['badge'], ENT_QUOTES, 'UTF-8') ?>
        </span>
        <?php endif; ?>
      </a>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="mg-cta" aria-labelledby="mg-cta-title">
    <h2 id="mg-cta-title">지금 시작하세요</h2>
    <p>텔레그램 또는 카카오톡으로 문의하시면 빠르게 안내해 드립니다.</p>
    <div style="display:flex;flex-direction:column;gap:.6rem;">
      <a href="/mobile/streaming/" class="m-btn m-btn-primary" aria-label="생방송 보기">🔴 생방송 보기</a>
      <a href="/mobile/reservation/" class="m-btn m-btn-outline" aria-label="테이블 예약">📅 테이블 예약</a>
    </div>
  </section>

</main>

<?php require_once dirname(__DIR__, 3) . '/core/helpers/footer.php'; ?>
<script src="/mobile/assets/js/mobile.js" defer></script>
</body>
</html>
