<?php
/**
 * 모바일 홈페이지 — 구찌야놀자
 * ref: https://developers.google.com/search/docs/fundamentals/seo-starter-guide
 * ref: https://developer.mozilla.org/en-US/docs/Web/HTML/Viewport_meta_tag
 * ref: https://owasp.org/www-project-secure-headers/
 * ref: https://schema.org/WebSite
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

/* UA 검증 — 데스크탑이면 /desktop/ 으로 리다이렉트
 * ref: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/User-Agent
 */
$ua = (string)($_SERVER['HTTP_USER_AGENT'] ?? '');
if (!preg_match('/Mobile|Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i', $ua)) {
    header('Location: /desktop/', true, 302);
    exit;
}

$site_url   = 'https://xn--2e0bj1fruw33b6ti.net';
$page_url   = $site_url . '/mobile/';
$page_title = '아바타 바카라 1위 에이전시 | 구찌야놀자 모바일';
$page_desc  = '아바타 바카라 1위 에이전시 구찌야놀자. 캄보디아 현장 생방송, 실시간 스트리밍. 모바일 최적화.';
$page_img   = $site_url . '/assets/images/avatar-baccarat-gucci-play.png';
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <!-- ref: https://developer.mozilla.org/en-US/docs/Web/HTML/Viewport_meta_tag -->
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?></title>
  <meta name="description" content="<?= htmlspecialchars($page_desc, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="robots" content="index,follow,max-snippet:-1,max-image-preview:large,max-video-preview:-1">
  <link rel="canonical" href="<?= htmlspecialchars($page_url, ENT_QUOTES, 'UTF-8') ?>">
  <!-- Open Graph — ref: https://ogp.me/ -->
  <meta property="og:type"        content="website">
  <meta property="og:title"       content="<?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:description" content="<?= htmlspecialchars($page_desc, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:url"         content="<?= htmlspecialchars($page_url, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:image"       content="<?= htmlspecialchars($page_img, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:locale"      content="ko_KR">
  <!-- Twitter Card — ref: https://developer.twitter.com/en/docs/twitter-for-websites/cards/overview/abouts-cards -->
  <meta name="twitter:card"  content="summary_large_image">
  <meta name="twitter:title" content="<?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="twitter:image" content="<?= htmlspecialchars($page_img, ENT_QUOTES, 'UTF-8') ?>">
  <!-- hreflang — ref: https://developers.google.com/search/docs/specialty/international/localization -->
  <link rel="alternate" hreflang="ko"        href="<?= htmlspecialchars($page_url, ENT_QUOTES, 'UTF-8') ?>">
  <link rel="alternate" hreflang="x-default" href="<?= htmlspecialchars($page_url, ENT_QUOTES, 'UTF-8') ?>">
  <!-- JSON-LD WebSite — ref: https://schema.org/WebSite -->
  <script type="application/ld+json">
  {"@context":"https://schema.org","@type":"WebSite","name":"구찌야놀자","url":"https://xn--2e0bj1fruw33b6ti.net","description":"아바타 바카라 1위 에이전시. 캄보디아 현장 생방송.","inLanguage":"ko-KR"}
  </script>
  <!-- Preconnect — ref: https://developer.mozilla.org/en-US/docs/Web/HTML/Attributes/rel/preconnect -->
  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
  <link rel="dns-prefetch" href="//cdn.jsdelivr.net">
  <link rel="preload" href="/assets/css/mobile-responsive.css" as="style">
    <!-- common.css: 폰트, 여백, 색상 기본 스타일 -->
  <link rel="stylesheet" href="/assets/css/common.css">
<link rel="stylesheet" href="/assets/css/mobile-responsive.css">
  <link rel="stylesheet" href="/mobile/assets/css/mobile.css">
  <meta name="theme-color" content="#040f1c">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-title" content="구찌야놀자">
  <meta name="mobile-web-app-capable" content="yes">
</head>
<body>
<a class="skip-to-main" href="#main-content">본문으로 바로가기</a>
<?php require_once dirname(__DIR__, 2) . '/core/helpers/header.php'; ?>

<main id="main-content" role="main">

  <!-- 히어로 섹션 -->
  <section class="m-hero" aria-labelledby="m-hero-title">
    <div class="m-hero-inner">
      <div class="m-live-badge" aria-label="현재 생방송 중">
        <span class="m-live-dot" aria-hidden="true"></span>LIVE
      </div>
      <!-- SEO H1 — ref: https://developers.google.com/search/docs/fundamentals/seo-starter-guide -->
      <h1 id="m-hero-title" class="m-hero-title" style="font-size: 1.875rem;">
        <span class="m-gold">아바타 바카라</span><br>1위 에이전시
      </h1>
      <p class="m-hero-desc">캄보디아 현장 생방송 · 실시간 스트리밍<br>안정적인 연결 · 현장감 있는 진행</p>
      <div class="m-hero-cta">
        <a href="/" class="m-menu-item" aria-label="홈">
    🏠 홈
  </a>

  <a href="/mobile/streaming/" class="m-btn m-btn-primary" aria-label="실시간 생방송 보기">🔴 생방송 보기</a>
        <a href="/mobile/reservation/" class="m-btn m-btn-outline" aria-label="테이블 예약하기">📅 예약하기</a>
      </div>
      <div class="m-stats" aria-label="플랫폼 통계">
        <div class="m-stat"><span class="m-stat-num">1위</span><span class="m-stat-label">에이전시</span></div>
        <div class="m-stat"><span class="m-stat-num">24/7</span><span class="m-stat-label">생방송</span></div>
        <div class="m-stat"><span class="m-stat-num">HD</span><span class="m-stat-label">고화질</span></div>
      </div>
    </div>
  </section>

  <!-- 메뉴 그리드 -->
  <nav class="m-menu-grid" aria-label="주요 메뉴">
    <a href="/" class="m-menu-item" aria-label="홈">
    🏠 홈
  </a>

  <a href="/mobile/streaming/" class="m-menu-item" aria-label="스트리밍">
      <span class="m-menu-icon" aria-hidden="true">🎬</span>
      <span class="m-menu-label">스트리밍</span>
    </a>
    <a href="/mobile/games/" class="m-menu-item" aria-label="게임">
      <span class="m-menu-icon" aria-hidden="true">🎰</span>
      <span class="m-menu-label">게임</span>
    </a>
    <a href="/mobile/reservation/" class="m-menu-item" aria-label="예약">
      <span class="m-menu-icon" aria-hidden="true">📅</span>
      <span class="m-menu-label">예약</span>
    </a>
    <a href="/mobile/free-board/" class="m-menu-item" aria-label="자유게시판">
      <span class="m-menu-icon" aria-hidden="true">💬</span>
      <span class="m-menu-label">게시판</span>
    </a>
    <a href="/mobile/contact/" class="m-menu-item" aria-label="연락처">
      <span class="m-menu-icon" aria-hidden="true">📞</span>
      <span class="m-menu-label">연락처</span>
    </a>
    <a href="https://t.me/Fury0079" class="m-menu-item" rel="noopener noreferrer" target="_blank" aria-label="텔레그램 (새 탭에서 열림)">
      <span class="m-menu-icon" aria-hidden="true">📱</span>
      <span class="m-menu-label">텔레그램</span>
    </a>
  </nav>

  <!-- 특징 섹션 -->
  <section class="m-features" aria-labelledby="m-features-title">
    <h2 id="m-features-title" class="m-section-title" style="font-size: 1.5rem;">왜 구찌야놀자인가?</h2>
    <div class="m-feature-list">
      <div class="m-feature-item">
        <span class="m-feature-icon" aria-hidden="true">⚡</span>
        <div><strong class="m-gold">저지연 스트리밍</strong><p>3초 이내 저지연으로 현장감 있는 생방송</p></div>
      </div>
      <div class="m-feature-item">
        <span class="m-feature-icon" aria-hidden="true">🔒</span>
        <div><strong class="m-gold">안전한 플랫폼</strong><p>Cloudflare WAF + DDoS 보호 + SSL 암호화</p></div>
      </div>
      <div class="m-feature-item">
        <span class="m-feature-icon" aria-hidden="true">📱</span>
        <div><strong class="m-gold">모바일 최적화</strong><p>스마트폰에서 끊김 없는 최적화된 경험</p></div>
      </div>
    </div>
  </section>

  <!-- 빠른 연락 -->
  <section class="m-contact-quick" aria-labelledby="m-contact-title">
    <h2 id="m-contact-title" class="m-section-title" style="font-size: 1.5rem;">빠른 문의</h2>
    <div class="m-contact-btns">
      <a href="https://t.me/Fury0079" class="m-contact-btn m-telegram"
         rel="noopener noreferrer" target="_blank"
         aria-label="텔레그램으로 문의하기 (새 탭에서 열림)">
        📱 텔레그램 @Fury0079
      </a>
      <a href="https://open.kakao.com/o/gucciyanolja" class="m-contact-btn m-kakao"
         rel="noopener noreferrer" target="_blank"
         aria-label="카카오톡으로 문의하기 (새 탭에서 열림)">
        💬 카카오톡 오픈채팅
      </a>
    </div>
  </section>

</main>

<?php require_once dirname(__DIR__, 2) . '/core/helpers/footer.php'; ?>
<script src="/mobile/assets/js/mobile.js" defer></script>
</body>
</html>
