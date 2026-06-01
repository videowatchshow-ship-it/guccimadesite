<?php
/**
 * 데스크탑 홈페이지 — 구찌야놀자
 * ref: https://developers.google.com/search/docs/fundamentals/seo-starter-guide
 * ref: https://schema.org/WebSite
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

/* UA 검증 — 모바일이면 /mobile/ 으로 리다이렉트 */
$ua = (string)($_SERVER['HTTP_USER_AGENT'] ?? '');
if (preg_match('/Mobile|Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i', $ua)) {
    header('Location: /mobile/', true, 302);
    exit;
}

$site_url   = 'https://xn--2e0bj1fruw33b6ti.net';
$page_url   = $site_url . '/desktop/';
$page_title = '아바타 바카라 1위 에이전시 | 구찌야놀자 캄보디아 생방송';
$page_desc  = '아바타 바카라 1위 에이전시 구찌야놀자. 캄보디아 현장 생방송, 실시간 스트리밍, 안정적인 연결 환경.';
$page_img   = $site_url . '/assets/images/avatar-baccarat-gucci-play.png';
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
  <meta name="twitter:card"       content="summary_large_image">
  <meta name="twitter:title"      content="<?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="twitter:image"      content="<?= htmlspecialchars($page_img, ENT_QUOTES, 'UTF-8') ?>">
  <link rel="alternate" hreflang="ko"        href="<?= htmlspecialchars($page_url, ENT_QUOTES, 'UTF-8') ?>">
  <link rel="alternate" hreflang="x-default" href="<?= htmlspecialchars($page_url, ENT_QUOTES, 'UTF-8') ?>">
  <!-- JSON-LD — ref: https://schema.org/WebSite -->
  <script type="application/ld+json">
  {"@context":"https://schema.org","@type":"WebSite","name":"구찌야놀자","url":"https://xn--2e0bj1fruw33b6ti.net","description":"아바타 바카라 1위 에이전시. 캄보디아 현장 생방송.","inLanguage":"ko-KR","potentialAction":{"@type":"SearchAction","target":{"@type":"EntryPoint","urlTemplate":"https://xn--2e0bj1fruw33b6ti.net/desktop/free-board/?q={search_term_string}"},"query-input":"required name=search_term_string"}}
  </script>
  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
  <link rel="dns-prefetch" href="//cdn.jsdelivr.net">
  <link rel="preload" href="/assets/css/mobile-responsive.css" as="style">
  <link rel="preload" href="https://cdn.jsdelivr.net/gh/projectnoonnu/2510-1@1.0/HakgyoansimTTeokbokkiB.woff2" as="font" type="font/woff2" crossorigin>
  <link rel="stylesheet" href="/assets/css/mobile-responsive.css">
  <link rel="stylesheet" href="/desktop/assets/css/desktop.css">
  <meta name="theme-color" content="#040f1c">
  <meta name="google-site-verification" content="VCbm_iM-IQ4cCfnMxW_Eh3-fsi0IeuM175IRVLPlXtQ">
</head>
<body>
<a class="skip-to-main" href="#main-content">본문으로 바로가기</a>
<?php require_once dirname(__DIR__, 2) . '/core/helpers/header.php'; ?>

<main id="main-content" role="main">

  <!-- 히어로 섹션 -->
  <section style="position:relative;min-height:90vh;display:flex;align-items:center;overflow:hidden;background:radial-gradient(ellipse at 20% 50%,rgba(245,200,66,.08) 0%,transparent 60%),radial-gradient(ellipse at 80% 20%,rgba(99,91,255,.1) 0%,transparent 60%),linear-gradient(180deg,#071a2e 0%,#040f1c 100%);" aria-labelledby="d-hero-title">
    <div class="d-inner" style="display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:center;padding-top:4rem;padding-bottom:4rem;">
      <div>
        <div style="display:inline-flex;align-items:center;gap:.5rem;background:rgba(245,200,66,.12);border:1px solid rgba(245,200,66,.3);border-radius:50px;padding:.4rem 1rem;font-size:.85rem;color:#f5c842;margin-bottom:1.5rem;">
          <span style="width:8px;height:8px;background:#f5c842;border-radius:50%;animation:d-blink 1.5s ease-in-out infinite;" aria-hidden="true"></span>
          실시간 생방송 중
        </div>
        <!-- SEO H1 — ref: https://developers.google.com/search/docs/fundamentals/seo-starter-guide -->
        <h1 id="d-hero-title" style="font-size:clamp(2rem,4vw,3.5rem);line-height:1.15;color:#fff;margin-bottom:1.25rem;">
          <span style="color:#f5c842;">아바타 바카라</span><br>1위 에이전시
        </h1>
        <p style="font-size:clamp(1rem,2vw,1.2rem);color:#8898aa;line-height:1.8;margin-bottom:2.5rem;">
          캄보디아 현장 생방송 · 실시간 스트리밍<br>안정적인 연결 · 현장감 있는 진행
        </p>
        <div style="display:flex;gap:1rem;flex-wrap:wrap;">
          <a href="/desktop/streaming/" class="d-btn d-btn-primary" aria-label="실시간 생방송 보기">🔴 생방송 보기</a>
          <a href="/desktop/reservation/" class="d-btn d-btn-outline" aria-label="테이블 예약하기">📅 예약하기</a>
        </div>
        <div style="display:flex;gap:2rem;margin-top:3rem;padding-top:2rem;border-top:1px solid rgba(255,255,255,.08);">
          <div style="text-align:center;"><span style="font-size:1.75rem;font-weight:700;color:#f5c842;display:block;">1위</span><span style="font-size:.82rem;color:#6b7c93;">에이전시</span></div>
          <div style="text-align:center;"><span style="font-size:1.75rem;font-weight:700;color:#f5c842;display:block;">24/7</span><span style="font-size:.82rem;color:#6b7c93;">생방송</span></div>
          <div style="text-align:center;"><span style="font-size:1.75rem;font-weight:700;color:#f5c842;display:block;">HD</span><span style="font-size:.82rem;color:#6b7c93;">고화질</span></div>
          <div style="text-align:center;"><span style="font-size:1.75rem;font-weight:700;color:#f5c842;display:block;">3초</span><span style="font-size:.82rem;color:#6b7c93;">저지연</span></div>
        </div>
      </div>
      <div style="display:flex;align-items:center;justify-content:center;">
        <div style="position:relative;width:380px;height:380px;">
          <div style="position:absolute;inset:-20px;background:radial-gradient(circle,rgba(245,200,66,.2) 0%,transparent 70%);border-radius:50%;animation:d-glow 3s ease-in-out infinite;" aria-hidden="true"></div>
          <img src="/assets/images/avatar-baccarat-gucci-play.png"
               alt="구찌야놀자 아바타 바카라 로고"
               width="380" height="380"
               style="width:100%;height:100%;object-fit:cover;border-radius:50%;border:4px solid rgba(245,200,66,.4);box-shadow:0 0 60px rgba(245,200,66,.3),0 20px 60px rgba(0,0,0,.5);position:relative;z-index:1;"
               loading="eager" fetchpriority="high"
               onerror="this.style.display='none'">
          <div style="position:absolute;top:20px;right:20px;background:#e53e3e;color:#fff;padding:.35rem .75rem;border-radius:50px;font-size:.8rem;font-weight:700;display:flex;align-items:center;gap:.4rem;z-index:2;" aria-label="생방송 중">
            <span style="width:6px;height:6px;background:#fff;border-radius:50%;animation:d-blink 1.5s ease-in-out infinite;" aria-hidden="true"></span>LIVE
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 특징 섹션 -->
  <section style="background:#071a2e;padding:5rem 0;" aria-labelledby="d-features-title">
    <div class="d-inner">
      <h2 id="d-features-title" style="font-size:clamp(1.5rem,3vw,2rem);color:#f5c842;margin-bottom:.5rem;">왜 구찌야놀자인가?</h2>
      <p style="color:#8898aa;margin-bottom:3rem;">엔터프라이즈급 스트리밍 플랫폼의 핵심 특징</p>
      <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;">
        <?php
        $features = [
            ['icon'=>'⚡','title'=>'저지연 스트리밍','desc'=>'3초 이내 저지연으로 캄보디아 현장을 생생하게 전달합니다.'],
            ['icon'=>'🔒','title'=>'엔터프라이즈 보안','desc'=>'Cloudflare WAF + DDoS 보호 + SSL 암호화로 안전한 플랫폼.'],
            ['icon'=>'🎬','title'=>'고화질 방송','desc'=>'1080p 고화질로 현장감 있는 아바타 바카라 생방송.'],
            ['icon'=>'💬','title'=>'실시간 채팅','desc'=>'WebSocket 기반 실시간 채팅으로 현장과 소통.'],
            ['icon'=>'📱','title'=>'모바일 최적화','desc'=>'스마트폰에서도 끊김 없는 최적화된 경험.'],
            ['icon'=>'🎰','title'=>'다양한 게임','desc'=>'바카라, 룰렛, 블랙잭 등 다양한 카지노 게임.'],
        ];
        foreach ($features as $f): ?>
        <div class="d-card">
          <div style="width:56px;height:56px;background:linear-gradient(135deg,rgba(245,200,66,.2),rgba(99,91,255,.15));border:1px solid rgba(245,200,66,.25);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;margin-bottom:1.25rem;" aria-hidden="true"><?= htmlspecialchars($f['icon'], ENT_QUOTES, 'UTF-8') ?></div>
          <h3 style="font-size:1.05rem;font-weight:700;color:#fff;margin-bottom:.5rem;"><?= htmlspecialchars($f['title'], ENT_QUOTES, 'UTF-8') ?></h3>
          <p style="font-size:.875rem;color:#6b7c93;line-height:1.7;"><?= htmlspecialchars($f['desc'], ENT_QUOTES, 'UTF-8') ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- 키보드 단축키 안내 -->
  <section style="background:#040f1c;padding:3rem 0;" aria-labelledby="d-shortcuts-title">
    <div class="d-inner" style="text-align:center;">
      <h2 id="d-shortcuts-title" style="font-size:1.1rem;color:#f5c842;margin-bottom:1rem;">키보드 단축키</h2>
      <div style="display:flex;gap:1.5rem;justify-content:center;flex-wrap:wrap;">
        <span style="font-size:.85rem;color:#8898aa;"><kbd class="d-kbd">Alt+S</kbd> 스트리밍</span>
        <span style="font-size:.85rem;color:#8898aa;"><kbd class="d-kbd">Alt+G</kbd> 게임</span>
        <span style="font-size:.85rem;color:#8898aa;"><kbd class="d-kbd">Alt+R</kbd> 예약</span>
        <span style="font-size:.85rem;color:#8898aa;"><kbd class="d-kbd">Alt+B</kbd> 게시판</span>
        <span style="font-size:.85rem;color:#8898aa;"><kbd class="d-kbd">Esc</kbd> 닫기</span>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section style="background:#071a2e;padding:5rem 0;text-align:center;" aria-labelledby="d-cta-title">
    <div class="d-inner">
      <h2 id="d-cta-title" style="font-size:clamp(1.5rem,3vw,2rem);color:#fff;margin-bottom:.75rem;">지금 바로 시작하세요</h2>
      <p style="color:#8898aa;margin-bottom:2.5rem;font-size:1rem;">텔레그램 또는 카카오톡으로 문의하시면 빠르게 안내해 드립니다.</p>
      <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
        <a href="/desktop/streaming/" class="d-btn d-btn-primary" aria-label="실시간 생방송 보기">🔴 실시간 생방송 보기</a>
        <a href="https://t.me/Fury0079" class="d-btn d-btn-outline" rel="noopener noreferrer" target="_blank" aria-label="텔레그램 문의 (새 탭에서 열림)">📱 텔레그램 문의</a>
      </div>
    </div>
  </section>

</main>

<?php require_once dirname(__DIR__, 2) . '/core/helpers/footer.php'; ?>

<style>
@keyframes d-blink{0%,100%{opacity:1}50%{opacity:.3}}
@keyframes d-glow{0%,100%{transform:scale(1);opacity:.6}50%{transform:scale(1.1);opacity:1}}
</style>
<script src="/desktop/assets/js/desktop.js" defer></script>
</body>
</html>
