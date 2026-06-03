<?php
/**
 * 데스크탑 게임 페이지 — 구찌야놀자
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
if (preg_match('/Mobile|Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i', $ua)) {
    header('Location: /mobile/games/', true, 302);
    exit;
}

$site_url   = 'https://xn--2e0bj1fruw33b6ti.net';
$page_url   = $site_url . '/desktop/games/';
$page_title = '게임 안내 | 바카라 · 룰렛 · 블랙잭 — 구찌야놀자';
$page_desc  = '구찌야놀자 게임 안내. 아바타 바카라, 룰렛, 블랙잭 등 다양한 카지노 게임을 소개합니다.';
$page_img   = $site_url . '/assets/images/avatar-baccarat-gucci-play.png';

$games = [
    ['icon'=>'🎰','title'=>'아바타 바카라','desc'=>'캄보디아 현장 딜러가 진행하는 실시간 아바타 바카라. 현장감 있는 생방송으로 즐기세요.','rtp'=>'98.94%','min'=>'10,000원','badge'=>'인기 1위','badge_type'=>'hot','link'=>'/desktop/streaming/'],
    ['icon'=>'⚡','title'=>'스피드 바카라','desc'=>'빠른 진행의 스피드 바카라. 한 라운드가 27초 이내로 진행되는 고속 게임.','rtp'=>'98.76%','min'=>'5,000원','badge'=>'NEW','badge_type'=>'new','link'=>'/desktop/reservation/'],
    ['icon'=>'🎡','title'=>'유러피안 룰렛','desc'=>'싱글 제로 유러피안 룰렛. 37개 숫자로 진행되는 클래식 룰렛 게임.','rtp'=>'97.30%','min'=>'1,000원','badge'=>'','badge_type'=>'','link'=>'/desktop/reservation/'],
    ['icon'=>'🃏','title'=>'블랙잭','desc'=>'전략적인 카드 게임 블랙잭. 딜러와 1:1로 대결하는 클래식 카지노 게임.','rtp'=>'99.50%','min'=>'5,000원','badge'=>'','badge_type'=>'','link'=>'/desktop/reservation/'],
    ['icon'=>'🐉','title'=>'드래곤 타이거','desc'=>'드래곤과 타이거 중 높은 카드를 맞추는 심플하고 빠른 카드 게임.','rtp'=>'96.72%','min'=>'1,000원','badge'=>'','badge_type'=>'','link'=>'/desktop/reservation/'],
    ['icon'=>'🎲','title'=>'식보 (Sic Bo)','desc'=>'3개의 주사위로 진행되는 아시아 전통 카지노 게임. 다양한 베팅 옵션 제공.','rtp'=>'97.22%','min'=>'1,000원','badge'=>'','badge_type'=>'','link'=>'/desktop/reservation/'],
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
  {"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"홈","item":"https://xn--2e0bj1fruw33b6ti.net/"},{"@type":"ListItem","position":2,"name":"게임","item":"https://xn--2e0bj1fruw33b6ti.net/desktop/games/"}]}
  </script>
  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
  <link rel="preload" href="/assets/css/mobile-responsive.css" as="style">
  <link rel="stylesheet" href="/assets/css/mobile-responsive.css">
  <link rel="stylesheet" href="/desktop/assets/css/desktop.css">
  <meta name="theme-color" content="#040f1c">
</head>
<body>
  <!-- 🏠 헤더 (메뉴 가로 비율 균등 분배) -->
  <header role="banner" style="background: linear-gradient(135deg, #071a2e, #0a2540); padding: 1.2rem 2rem; border-bottom: 3px solid rgba(245,200,66,0.3); margin-bottom: 1.5rem;">
    <nav style="max-width: 1400px; margin: 0 auto; display: flex; justify-content: space-around; align-items: center; flex-wrap: wrap; font-family: 'SchoolSafetyTteokbokki', sans-serif; font-size: 1.1rem; font-weight: 600;">
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

  <section class="d-page-hero" aria-label="게임 페이지 헤더">
    <div class="d-inner">
      <nav aria-label="breadcrumb">
        <ol style="list-style:none;display:flex;gap:.5rem;font-size:.85rem;color:#6b7c93;padding:0;margin:0 0 .75rem;">
          <li><a href="/desktop/" style="color:#6b7c93;text-decoration:none;">홈</a></li>
          <li style="color:rgba(255,255,255,.2);">›</li>
          <li style="color:#f5c842;" aria-current="page">게임</li>
        </ol>
      </nav>
      <h1 style="font-size:clamp(1.5rem,3vw,2.25rem);color:#fff;margin-bottom:.5rem;">
        <span style="color:#f5c842;">게임</span> 안내
      </h1>
      <p style="color:#8898aa;font-size:1rem;">아바타 바카라 · 룰렛 · 블랙잭 · 슬롯 — 캄보디아 현장 생방송</p>
      <p style="font-size:.8rem;color:#4a5568;margin-top:.5rem;">단축키: <kbd class="d-kbd">Alt+G</kbd> 게임 · <kbd class="d-kbd">Alt+R</kbd> 예약</p>
    </div>
  </section>

  <div class="d-inner" style="padding:2.5rem 0 3rem;">
    <section aria-labelledby="dg-list-title">
      <h2 id="dg-list-title" style="position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0,0,0,0);">게임 목록</h2>
      <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:1.5rem;">
        <?php foreach ($games as $g): ?>
        <article class="d-card" style="position:relative;overflow:hidden;" aria-label="<?= htmlspecialchars($g['title'], ENT_QUOTES, 'UTF-8') ?>">
          <div style="aspect-ratio:16/9;background:linear-gradient(135deg,#0e2d5a,#071a2e);display:flex;align-items:center;justify-content:center;font-size:4rem;border-radius:10px;margin-bottom:1.25rem;position:relative;" aria-hidden="true">
            <?= htmlspecialchars($g['icon'], ENT_QUOTES, 'UTF-8') ?>
            <?php if ($g['badge'] !== ''): ?>
            <span style="position:absolute;top:8px;right:8px;font-size:.72rem;font-weight:700;padding:.2rem .55rem;border-radius:4px;<?= $g['badge_type'] === 'hot' ? 'background:rgba(229,62,62,.9);color:#fff;' : 'background:rgba(245,200,66,.9);color:#040f1c;' ?>">
              <?= htmlspecialchars($g['badge'], ENT_QUOTES, 'UTF-8') ?>
            </span>
            <?php endif; ?>
          </div>
          <h2 style="font-size:1.1rem;font-weight:700;color:#fff;margin-bottom:.4rem;"><?= htmlspecialchars($g['title'], ENT_QUOTES, 'UTF-8') ?></h2>
          <p style="font-size:.875rem;color:#8898aa;line-height:1.6;margin-bottom:1rem;"><?= htmlspecialchars($g['desc'], ENT_QUOTES, 'UTF-8') ?></p>
          <div style="display:flex;align-items:center;justify-content:space-between;font-size:.8rem;color:#6b7c93;margin-bottom:1rem;">
            <span>RTP: <strong style="color:#f5c842;"><?= htmlspecialchars($g['rtp'], ENT_QUOTES, 'UTF-8') ?></strong></span>
            <span>최소: <?= htmlspecialchars($g['min'], ENT_QUOTES, 'UTF-8') ?></span>
          </div>
          <a href="<?= htmlspecialchars($g['link'], ENT_QUOTES, 'UTF-8') ?>"
             class="d-btn d-btn-primary"
             style="width:100%;justify-content:center;"
             aria-label="<?= htmlspecialchars($g['title'], ENT_QUOTES, 'UTF-8') ?> 시작하기">
            <?= $g['link'] === '/desktop/streaming/' ? '🔴 생방송 보기' : '📅 예약하기' ?>
          </a>
        </article>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- CTA -->
    <section style="background:#071a2e;border:1px solid rgba(245,200,66,.15);border-radius:16px;padding:2.5rem;text-align:center;margin-top:2rem;" aria-labelledby="dg-cta-title">
      <h2 id="dg-cta-title" style="font-size:clamp(1.25rem,2.5vw,1.75rem);color:#f5c842;margin-bottom:.75rem;">지금 바로 시작하세요</h2>
      <p style="font-size:.95rem;color:#8898aa;line-height:1.7;margin-bottom:1.5rem;">텔레그램 또는 카카오톡으로 문의하시면 빠르게 안내해 드립니다.</p>
      <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
        <a href="/desktop/streaming/" class="d-btn d-btn-primary" aria-label="실시간 생방송 보기">🔴 실시간 생방송 보기</a>
        <a href="/desktop/reservation/" class="d-btn d-btn-outline" aria-label="테이블 예약">📅 테이블 예약</a>
      </div>
    </section>
  </div>

</main>

<?php require_once dirname(__DIR__, 3) . '/core/helpers/footer.php'; ?>
<script src="/desktop/assets/js/desktop.js" defer></script>
</body>
</html>

