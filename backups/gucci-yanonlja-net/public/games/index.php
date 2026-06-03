<?php



/**



 * 게임 안내 페이지 — 구찌야놀자



 * ref: https://schema.org/Game



 * ref: https://developers.google.com/search/docs/appearance/structured-data



 * ref: https://owasp.org/www-project-secure-headers/



 */



declare(strict_types=1);







if (!headers_sent()) {



    header_remove('X-Powered-By');



    header('X-Frame-Options: DENY');



    header('X-Content-Type-Options: nosniff');



    header('Referrer-Policy: strict-origin-when-cross-origin');



    header("Content-Security-Policy: default-src 'self' https:; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://accounts.google.com; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; img-src 'self' data: https:; font-src 'self' data: https://cdn.jsdelivr.net; connect-src 'self' https: wss:; frame-ancestors 'none';");



}







$site_url   = 'https://xn--2e0bj1fruw33b6ti.net';



$page_url   = $site_url . '/games/';



$page_title = '게임 안내 | 바카라 · 룰렛 · 블랙잭 — 구찌야놀자';



$page_desc  = '구찌야놀자 게임 안내. 아바타 바카라, 룰렛, 블랙잭 등 다양한 카지노 게임을 소개합니다. 캄보디아 현장 생방송으로 즐기세요.';



$page_img   = $site_url . '/아바타-바카라-구찌야-놀자.png';



?>



<!DOCTYPE html>



<html lang="ko">



<head>



  <meta charset="UTF-8">



  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">



  <meta http-equiv="X-UA-Compatible" content="IE=edge">



  <title><?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?></title>



  <meta name="description" content="<?= htmlspecialchars($page_desc, ENT_QUOTES, 'UTF-8') ?>">



  <meta name="robots" content="index,follow,max-snippet:-1,max-image-preview:large">



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



  <script type="application/ld+json">



  {"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"홈","item":"https://xn--2e0bj1fruw33b6ti.net/"},{"@type":"ListItem","position":2,"name":"게임","item":"https://xn--2e0bj1fruw33b6ti.net/games/"}]}



  </script>



  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>



  <link rel="preload" href="/assets/css/mobile-responsive.css" as="style">



  <link rel="stylesheet" href="/assets/css/mobile-responsive.css">



  <style>



    @font-face{font-family:'SchoolSafetyTteokbokki';src:url('https://cdn.jsdelivr.net/gh/projectnoonnu/2510-1@1.0/HakgyoansimTTeokbokkiB.woff2') format('woff2');font-weight:normal;font-style:normal;font-display:swap;}



    body{font-family:'SchoolSafetyTteokbokki',sans-serif;background:#040f1c;color:#c8d8e8;margin:0;padding:0;min-height:100vh;display:flex;flex-direction:column;}



    .g-inner{max-width:1200px;margin:0 auto;padding:0 1.5rem;}



    .page-hero{background:linear-gradient(180deg,#071a2e 0%,#040f1c 100%);padding:2.5rem 0 2rem;border-bottom:1px solid rgba(245,200,66,.15);}



    .games-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.5rem;padding:2.5rem 0;}



    .game-card{background:linear-gradient(145deg,rgba(14,45,90,.6),rgba(10,33,64,.4));border:1px solid rgba(245,200,66,.12);border-radius:16px;overflow:hidden;transition:all .35s ease;position:relative;}



    .game-card:hover{border-color:rgba(245,200,66,.35);transform:translateY(-6px);box-shadow:0 16px 40px rgba(0,0,0,.4),0 0 20px rgba(245,200,66,.1);}



    .game-card-thumb{aspect-ratio:16/9;background:linear-gradient(135deg,#0e2d5a,#071a2e);display:flex;align-items:center;justify-content:center;font-size:4rem;position:relative;overflow:hidden;}



    .game-card-thumb::after{content:'';position:absolute;inset:0;background:linear-gradient(180deg,transparent 50%,rgba(4,15,28,.8));pointer-events:none;}



    .game-badge{position:absolute;top:10px;right:10px;background:rgba(245,200,66,.9);color:#040f1c;font-size:.72rem;font-weight:700;padding:.25rem .6rem;border-radius:4px;z-index:1;}



    .game-badge.hot{background:rgba(229,62,62,.9);color:#fff;}



    .game-card-body{padding:1.25rem;}



    .game-card-title{font-size:1.1rem;font-weight:700;color:#fff;margin-bottom:.4rem;}



    .game-card-desc{font-size:.875rem;color:#8898aa;line-height:1.6;margin-bottom:1rem;}



    .game-card-meta{display:flex;align-items:center;justify-content:space-between;font-size:.8rem;color:#6b7c93;margin-bottom:1rem;}



    .game-card-rtp{color:#f5c842;font-weight:600;}



    .game-card-btn{display:flex;align-items:center;justify-content:center;gap:.4rem;width:100%;padding:.7rem;background:linear-gradient(135deg,#f5c842,#e6a800);color:#040f1c;border:none;border-radius:8px;font-family:inherit;font-size:.9rem;font-weight:700;cursor:pointer;text-decoration:none;transition:all .25s ease;min-height:44px;}



    .game-card-btn:hover{transform:translateY(-2px);box-shadow:0 6px 20px rgba(245,200,66,.4);}



    .game-card-btn:focus-visible{outline:3px solid #f5c842;outline-offset:3px;}



    .games-cta{background:#071a2e;border:1px solid rgba(245,200,66,.15);border-radius:16px;padding:2.5rem;text-align:center;margin:1rem 0 3rem;}



    .games-cta h2{font-size:clamp(1.25rem,3vw,1.75rem);color:#f5c842;margin-bottom:.75rem;}



    .games-cta p{font-size:.95rem;color:#8898aa;line-height:1.7;margin-bottom:1.5rem;}



    .cta-btns{display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;}



    .cta-btn-primary{display:inline-flex;align-items:center;gap:.5rem;padding:.8rem 2rem;background:linear-gradient(135deg,#f5c842,#e6a800);color:#040f1c;border-radius:50px;font-family:inherit;font-size:.95rem;font-weight:700;text-decoration:none;transition:all .25s ease;min-height:48px;}



    .cta-btn-primary:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(245,200,66,.4);}



    .cta-btn-outline{display:inline-flex;align-items:center;gap:.5rem;padding:.8rem 2rem;background:transparent;color:#f5c842;border:2px solid rgba(245,200,66,.4);border-radius:50px;font-family:inherit;font-size:.95rem;font-weight:700;text-decoration:none;transition:all .25s ease;min-height:48px;}



    .cta-btn-outline:hover{background:rgba(245,200,66,.08);border-color:#f5c842;transform:translateY(-2px);}



    @media(max-width:600px){.games-grid{grid-template-columns:1fr;}.cta-btns{flex-direction:column;align-items:center;}}



  </style>



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



<?php require_once dirname(__DIR__, 2) . '/core/helpers/header.php'; ?>







<main id="main-content" role="main">







  <div class="g-inner">



    <!-- 게임 그리드 -->



    <section aria-labelledby="games-list-title">



      <h2 id="games-list-title" style="position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0,0,0,0);">게임 목록</h2>



      <div class="games-grid">







        <!-- 아바타 바카라 -->



        <article class="game-card" aria-label="아바타 바카라">



          <div class="game-card-thumb" aria-hidden="true">🎰



            <span class="game-badge hot">인기 1위</span>



          </div>



          <div class="game-card-body">



            <h2 class="game-card-title">아바타 바카라</h2>



            <p class="game-card-desc">캄보디아 현장 딜러가 진행하는 실시간 아바타 바카라. 현장감 있는 생방송으로 즐기세요.</p>



            <div class="game-card-meta">



              <span>RTP: <span class="game-card-rtp">98.94%</span></span>



              <span>최소 베팅: 10,000원</span>



            </div>



            <a href="/streaming/" class="game-card-btn" aria-label="아바타 바카라 생방송 보기">🔴 생방송 보기</a>



          </div>



        </article>







        <!-- 스피드 바카라 -->



        <article class="game-card" aria-label="스피드 바카라">



          <div class="game-card-thumb" aria-hidden="true">⚡



            <span class="game-badge">NEW</span>



          </div>



          <div class="game-card-body">



            <h2 class="game-card-title">스피드 바카라</h2>



            <p class="game-card-desc">빠른 진행의 스피드 바카라. 한 라운드가 27초 이내로 진행되는 고속 게임.</p>



            <div class="game-card-meta">



              <span>RTP: <span class="game-card-rtp">98.76%</span></span>



              <span>최소 베팅: 5,000원</span>



            </div>



            <a href="/reservation/" class="game-card-btn" aria-label="스피드 바카라 예약하기">📅 예약하기</a>



          </div>



        </article>







        <!-- 룰렛 -->



        <article class="game-card" aria-label="유러피안 룰렛">



          <div class="game-card-thumb" aria-hidden="true">🎡</div>



          <div class="game-card-body">



            <h2 class="game-card-title">유러피안 룰렛</h2>



            <p class="game-card-desc">싱글 제로 유러피안 룰렛. 37개 숫자로 진행되는 클래식 룰렛 게임.</p>



            <div class="game-card-meta">



              <span>RTP: <span class="game-card-rtp">97.30%</span></span>



              <span>최소 베팅: 1,000원</span>



            </div>



            <a href="/reservation/" class="game-card-btn" aria-label="룰렛 예약하기">📅 예약하기</a>



          </div>



        </article>







        <!-- 블랙잭 -->



        <article class="game-card" aria-label="블랙잭">



          <div class="game-card-thumb" aria-hidden="true">🃏</div>



          <div class="game-card-body">



            <h2 class="game-card-title">블랙잭</h2>



            <p class="game-card-desc">전략적인 카드 게임 블랙잭. 딜러와 1:1로 대결하는 클래식 카지노 게임.</p>



            <div class="game-card-meta">



              <span>RTP: <span class="game-card-rtp">99.50%</span></span>



              <span>최소 베팅: 5,000원</span>



            </div>



            <a href="/reservation/" class="game-card-btn" aria-label="블랙잭 예약하기">📅 예약하기</a>



          </div>



        </article>







        <!-- 드래곤 타이거 -->



        <article class="game-card" aria-label="드래곤 타이거">



          <div class="game-card-thumb" aria-hidden="true">🐉</div>



          <div class="game-card-body">



            <h2 class="game-card-title">드래곤 타이거</h2>



            <p class="game-card-desc">드래곤과 타이거 중 높은 카드를 맞추는 심플하고 빠른 카드 게임.</p>



            <div class="game-card-meta">



              <span>RTP: <span class="game-card-rtp">96.72%</span></span>



              <span>최소 베팅: 1,000원</span>



            </div>



            <a href="/reservation/" class="game-card-btn" aria-label="드래곤 타이거 예약하기">📅 예약하기</a>



          </div>



        </article>







        <!-- 식보 -->



        <article class="game-card" aria-label="식보">



          <div class="game-card-thumb" aria-hidden="true">🎲</div>



          <div class="game-card-body">



            <h2 class="game-card-title">식보 (Sic Bo)</h2>



            <p class="game-card-desc">3개의 주사위로 진행되는 아시아 전통 카지노 게임. 다양한 베팅 옵션 제공.</p>



            <div class="game-card-meta">



              <span>RTP: <span class="game-card-rtp">97.22%</span></span>



              <span>최소 베팅: 1,000원</span>



            </div>



            <a href="/reservation/" class="game-card-btn" aria-label="식보 예약하기">📅 예약하기</a>



          </div>



        </article>







      </div>



    </section>







    <!-- CTA 섹션 -->



    <section class="games-cta" aria-labelledby="games-cta-title">



      <h2 id="games-cta-title">지금 바로 시작하세요</h2>



      <p>구찌야놀자에서 캄보디아 현장 생방송으로 다양한 게임을 즐기세요.<br>텔레그램 또는 카카오톡으로 문의하시면 빠르게 안내해 드립니다.</p>



      <div class="cta-btns">



        <a href="/streaming/" class="cta-btn-primary" aria-label="실시간 생방송 보기">🔴 실시간 생방송 보기</a>



        <a href="/reservation/" class="cta-btn-outline" aria-label="테이블 예약하기">📅 테이블 예약</a>



      </div>



    </section>



  </div>







</main>







<?php require_once dirname(__DIR__, 2) . '/core/helpers/footer.php'; ?>



</body>



</html>







