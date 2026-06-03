<?php
/**
 * 모바일 연락처 페이지 — 구찌야놀자
 * ref: https://schema.org/ContactPage
 * ref: https://developer.mozilla.org/en-US/docs/Web/HTML/Element/address
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
    header('Location: /desktop/contact/', true, 302);
    exit;
}

$site_url   = 'https://xn--2e0bj1fruw33b6ti.net';
$page_url   = $site_url . '/mobile/contact/';
$page_title = '모바일 문의하기 | 연락처 — 구찌야놀자';
$page_desc  = '구찌야놀자 모바일 문의하기. 텔레그램, 카카오톡으로 빠르게 연락하세요. 24시간 상담 가능.';
$page_img   = $site_url . '/assets/images/avatar-baccarat-gucci-play.png';

$faqs = [
    ['q'=>'처음 이용하는데 어떻게 시작하나요?','a'=>'텔레그램(@Fury0079) 또는 카카오톡으로 연락주시면 담당자가 자세히 안내해 드립니다.'],
    ['q'=>'모바일에서도 이용 가능한가요?','a'=>'네, 모바일 최적화 반응형 구조로 스마트폰에서도 편안하게 이용할 수 있습니다.'],
    ['q'=>'최소 베팅 금액은 얼마인가요?','a'=>'아바타 바카라는 최소 10,000원부터 시작합니다. 자세한 내용은 게임 안내 페이지를 참고하세요.'],
    ['q'=>'스트리밍이 끊기면 어떻게 하나요?','a'=>'페이지를 새로고침하거나 텔레그램으로 즉시 연락주시면 기술 지원을 받으실 수 있습니다.'],
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
  {"@context":"https://schema.org","@type":"ContactPage","name":"구찌야놀자 모바일 문의","url":"https://xn--2e0bj1fruw33b6ti.net/mobile/contact/"}
  </script>
  <script type="application/ld+json">
  {"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"홈","item":"https://xn--2e0bj1fruw33b6ti.net/"},{"@type":"ListItem","position":2,"name":"연락처","item":"https://xn--2e0bj1fruw33b6ti.net/mobile/contact/"}]}
  </script>
  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
  <link rel="preload" href="/assets/css/mobile-responsive.css" as="style">
  <link rel="stylesheet" href="/assets/css/mobile-responsive.css">
  <link rel="stylesheet" href="/mobile/assets/css/mobile.css">
  <meta name="theme-color" content="#040f1c">
  <style>
    .mc-hero{background:linear-gradient(180deg,#071a2e 0%,#040f1c 100%);padding:1.5rem 1rem 1rem;border-bottom:1px solid rgba(245,200,66,.15);}
    .mc-channels{padding:1rem;display:flex;flex-direction:column;gap:.75rem;}
    .mc-channel{display:flex;align-items:center;gap:1rem;padding:1rem;border-radius:12px;text-decoration:none;transition:all .25s;min-height:72px;}
    .mc-channel:active{transform:scale(.98);}
    .mc-channel:focus-visible{outline:3px solid #f5c842;outline-offset:3px;}
    .mc-telegram{background:linear-gradient(135deg,rgba(0,136,204,.2),rgba(0,136,204,.1));border:1px solid rgba(0,136,204,.3);color:#63b3ed;}
    .mc-kakao{background:linear-gradient(135deg,rgba(254,229,0,.15),rgba(254,229,0,.08));border:1px solid rgba(254,229,0,.2);color:#f6e05e;}
    .mc-icon{width:48px;height:48px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;flex-shrink:0;}
    .mc-telegram .mc-icon{background:rgba(0,136,204,.2);}
    .mc-kakao .mc-icon{background:rgba(254,229,0,.15);}
    .mc-info-title{font-size:.95rem;font-weight:700;display:block;margin-bottom:.15rem;}
    .mc-info-id{font-size:.8rem;opacity:.8;display:block;margin-bottom:.1rem;}
    .mc-info-desc{font-size:.72rem;opacity:.6;}
    .mc-hours{padding:0 1rem 1rem;}
    .mc-hours-card{background:#071a2e;border:1px solid rgba(245,200,66,.12);border-radius:12px;padding:1rem;}
    .mc-hours-title{font-size:.875rem;font-weight:700;color:#f5c842;margin-bottom:.75rem;}
    .mc-hours-row{display:flex;justify-content:space-between;font-size:.8rem;color:#8898aa;padding:.3rem 0;border-bottom:1px solid rgba(255,255,255,.04);}
    .mc-hours-row:last-child{border-bottom:none;}
    .mc-hours-val{color:#c8d8e8;}
    .mc-faq{padding:0 1rem 2rem;}
    .mc-faq-title{font-size:.95rem;font-weight:700;color:#f5c842;margin-bottom:.75rem;}
    .mc-faq-item{background:rgba(255,255,255,.03);border:1px solid rgba(245,200,66,.1);border-radius:10px;overflow:hidden;margin-bottom:.5rem;}
    .mc-faq-q{width:100%;background:none;border:none;color:#c8d8e8;cursor:pointer;display:flex;align-items:center;justify-content:space-between;font-family:inherit;font-size:.82rem;font-weight:600;gap:.75rem;min-height:48px;padding:.75rem 1rem;text-align:left;transition:color .2s;}
    .mc-faq-q:active,.mc-faq-q[aria-expanded="true"]{color:#f5c842;}
    .mc-faq-q:focus-visible{outline:2px solid #f5c842;outline-offset:-2px;}
    .mc-faq-arrow{flex-shrink:0;transition:transform .3s;font-size:.7rem;}
    .mc-faq-q[aria-expanded="true"] .mc-faq-arrow{transform:rotate(180deg);}
    .mc-faq-a{display:none;font-size:.8rem;color:#8898aa;line-height:1.7;padding:.25rem 1rem .875rem;}
    .mc-faq-a.open{display:block;}
  </style>
</head>
<body>  <!-- 🏠 헤더 (메뉴 폰트: 1.1rem/18px, 간격 균형감 있음) -->
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

  <section class="mc-hero" aria-label="연락처 헤더">
    <nav aria-label="breadcrumb">
      <ol style="list-style:none;display:flex;gap:.4rem;font-size:.78rem;color:#6b7c93;padding:0;margin:0 0 .5rem;">
        <li><a href="/mobile/" style="color:#6b7c93;text-decoration:none;">홈</a></li>
        <li style="color:rgba(255,255,255,.2);">›</li>
        <li style="color:#f5c842;" aria-current="page">연락처</li>
      </ol>
    </nav>
    <h1 style="font-size:clamp(1.25rem,6vw,1.75rem);color:#fff;margin-bottom:.35rem;">
      <span style="color:#f5c842;">문의</span>하기
    </h1>
    <p style="font-size:.85rem;color:#8898aa;">텔레그램 · 카카오톡 · 24시간 상담</p>
  </section>

  <!-- 연락 채널 -->
  <section aria-label="연락 채널">
    <address class="mc-channels" style="font-style:normal;">
      <a href="https://t.me/Fury0079" class="mc-channel mc-telegram"
         rel="noopener noreferrer" target="_blank"
         aria-label="텔레그램 퓨리 실장에게 연락하기 (새 탭에서 열림)">
        <div class="mc-icon" aria-hidden="true">📱</div>
        <div>
          <span class="mc-info-title">텔레그램</span>
          <span class="mc-info-id">@Fury0079</span>
          <span class="mc-info-desc">가장 빠른 응답 · 24시간</span>
        </div>
      </a>
      <a href="https://open.kakao.com/o/gucciyanolja" class="mc-channel mc-kakao"
         rel="noopener noreferrer" target="_blank"
         aria-label="카카오톡 오픈채팅으로 연락하기 (새 탭에서 열림)">
        <div class="mc-icon" aria-hidden="true">💬</div>
        <div>
          <span class="mc-info-title">카카오톡 오픈채팅</span>
          <span class="mc-info-id">구찌야놀자</span>
          <span class="mc-info-desc">오픈채팅 · 실시간 상담</span>
        </div>
      </a>
    </address>
  </section>

  <!-- 운영 시간 -->
  <div class="mc-hours">
    <div class="mc-hours-card">
      <h2 class="mc-hours-title">운영 시간</h2>
      <div class="mc-hours-row"><span>평일</span><span class="mc-hours-val">오전 10:00 ~ 오전 02:00</span></div>
      <div class="mc-hours-row"><span>주말</span><span class="mc-hours-val">오전 10:00 ~ 오전 02:00</span></div>
      <div class="mc-hours-row"><span>공휴일</span><span style="color:#68d391;">정상 운영</span></div>
    </div>
  </div>

  <!-- FAQ -->
  <section class="mc-faq" aria-labelledby="mc-faq-title">
    <h2 id="mc-faq-title" class="mc-faq-title" style="font-size: 1.5rem;">자주 묻는 질문</h2>
    <?php foreach ($faqs as $i => $faq):
        $faq_id = 'mc-faq-' . $i;
    ?>
    <div class="mc-faq-item">
      <button class="mc-faq-q" aria-expanded="false" aria-controls="<?= $faq_id ? style="font-size: 1rem;">" id="btn-<?= $faq_id ?>">
        <?= htmlspecialchars($faq['q'], ENT_QUOTES, 'UTF-8') ?>
        <span class="mc-faq-arrow" aria-hidden="true">▼</span>
      </button>
      <div class="mc-faq-a" id="<?= $faq_id ?>" role="region" aria-labelledby="btn-<?= $faq_id ?>">
        <?= htmlspecialchars($faq['a'], ENT_QUOTES, 'UTF-8') ?>
      </div>
    </div>
    <?php endforeach; ?>
  </section>

</main>

<?php require_once dirname(__DIR__, 3) . '/core/helpers/footer.php'; ?>

<script>
(function () {
  'use strict';
  /* FAQ 아코디언 */
  document.querySelectorAll('.mc-faq-q').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var expanded = btn.getAttribute('aria-expanded') === 'true';
      var answer   = document.getElementById(btn.getAttribute('aria-controls'));
      btn.setAttribute('aria-expanded', expanded ? 'false' : 'true');
      if (answer) { answer.classList.toggle('open', !expanded); }
    });
  });
}());
</script>
<script src="/mobile/assets/js/mobile.js" defer></script>
</body>
</html>
