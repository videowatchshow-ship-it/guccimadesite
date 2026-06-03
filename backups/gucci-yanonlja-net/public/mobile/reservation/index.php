<?php
/**
 * 모바일 예약 페이지 — 구찌야놀자
 * ref: https://schema.org/ReservationPackage
 * ref: https://developer.mozilla.org/en-US/docs/Web/HTML/Element/form
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
    header('Location: /desktop/reservation/', true, 302);
    exit;
}

$site_url   = 'https://xn--2e0bj1fruw33b6ti.net';
$page_url   = $site_url . '/mobile/reservation/';
$page_title = '모바일 테이블 예약 | 아바타 바카라 — 구찌야놀자';
$page_desc  = '구찌야놀자 모바일 테이블 예약. 아바타 바카라 테이블을 사전 예약하고 우선 입장 혜택을 받으세요.';
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
  <meta name="robots" content="index,follow">
  <link rel="canonical" href="<?= htmlspecialchars($page_url, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:type"        content="website">
  <meta property="og:title"       content="<?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:description" content="<?= htmlspecialchars($page_desc, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:url"         content="<?= htmlspecialchars($page_url, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:image"       content="<?= htmlspecialchars($page_img, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:locale"      content="ko_KR">
  <script type="application/ld+json">
  {"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"홈","item":"https://xn--2e0bj1fruw33b6ti.net/"},{"@type":"ListItem","position":2,"name":"예약","item":"https://xn--2e0bj1fruw33b6ti.net/mobile/reservation/"}]}
  </script>
  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
  <link rel="preload" href="/assets/css/mobile-responsive.css" as="style">
  <link rel="stylesheet" href="/assets/css/mobile-responsive.css">
  <link rel="stylesheet" href="/mobile/assets/css/mobile.css">
  <meta name="theme-color" content="#040f1c">
  <style>
    .mr-hero{background:linear-gradient(180deg,#071a2e 0%,#040f1c 100%);padding:1.5rem 1rem 1rem;border-bottom:1px solid rgba(245,200,66,.15);}
    .mr-form-wrap{padding:1rem;}
    .mr-form{background:#071a2e;border:1px solid rgba(245,200,66,.15);border-radius:12px;padding:1.25rem;}
    .mr-form-title{font-size:.95rem;font-weight:700;color:#f5c842;margin-bottom:1rem;padding-bottom:.6rem;border-bottom:1px solid rgba(245,200,66,.12);}
    .mr-group{display:flex;flex-direction:column;gap:.3rem;margin-bottom:1rem;}
    .mr-label{font-size:.82rem;font-weight:600;color:#c8d8e8;}
    .mr-req{color:#fc8181;margin-left:.15rem;}
    .mr-input,.mr-select,.mr-textarea{background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:8px;color:#c8d8e8;font-family:inherit;font-size:.875rem;padding:.65rem .875rem;width:100%;transition:border-color .2s;-webkit-appearance:none;}
    .mr-input:focus,.mr-select:focus,.mr-textarea:focus{outline:none;border-color:rgba(245,200,66,.5);box-shadow:0 0 0 3px rgba(245,200,66,.1);}
    .mr-select option{background:#071a2e;}
    .mr-textarea{resize:vertical;min-height:80px;}
    .mr-submit{width:100%;padding:.875rem;background:linear-gradient(135deg,#f5c842,#e6a800);color:#040f1c;border:none;border-radius:8px;font-family:inherit;font-size:.95rem;font-weight:700;cursor:pointer;min-height:52px;margin-top:.25rem;transition:all .25s;}
    .mr-submit:active{transform:scale(.98);}
    .mr-submit:focus-visible{outline:3px solid #f5c842;outline-offset:3px;}
    .mr-benefits{padding:0 1rem 1rem;}
    .mr-benefits-card{background:#071a2e;border:1px solid rgba(245,200,66,.12);border-radius:12px;padding:1rem;}
    .mr-benefits-title{font-size:.875rem;font-weight:700;color:#f5c842;margin-bottom:.75rem;}
    .mr-benefits-list{list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:.5rem;}
    .mr-benefits-list li{font-size:.8rem;color:#8898aa;display:flex;align-items:flex-start;gap:.4rem;line-height:1.5;}
    .mr-benefits-list li::before{content:'✓';color:#f5c842;font-weight:700;flex-shrink:0;}
    .mr-contact{padding:0 1rem 2rem;display:flex;flex-direction:column;gap:.6rem;}
    .mr-contact-btn{display:flex;align-items:center;gap:.75rem;padding:.875rem 1rem;border-radius:10px;text-decoration:none;font-size:.875rem;font-weight:600;transition:all .25s;min-height:52px;}
    .mr-telegram{background:linear-gradient(135deg,rgba(0,136,204,.2),rgba(0,136,204,.1));border:1px solid rgba(0,136,204,.3);color:#63b3ed;}
    .mr-kakao{background:linear-gradient(135deg,rgba(254,229,0,.15),rgba(254,229,0,.08));border:1px solid rgba(254,229,0,.2);color:#f6e05e;}
    .mr-contact-btn:focus-visible{outline:3px solid #f5c842;outline-offset:3px;}
    .mr-success{display:none;background:rgba(72,187,120,.1);border:1px solid rgba(72,187,120,.25);border-radius:8px;padding:.875rem;color:#68d391;font-size:.875rem;text-align:center;margin-top:.75rem;}
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
<?php require_once dirname(__DIR__, 3) . '/core/helpers/header.php'; ?>

<main id="main-content" role="main">

  <section class="mr-hero" aria-label="예약 페이지 헤더"><h1 style="font-size:clamp(1.25rem,6vw,1.75rem);color:#fff;margin-bottom:.35rem;">
      테이블 <span style="color:#f5c842;">예약</span>
    </h1>
    <p style="font-size:.85rem;color:#8898aa;">사전 예약 · 우선 입장 혜택</p>
  </section>

  <!-- 예약 폼 -->
  <div class="mr-form-wrap">
    <div class="mr-form">
      <h2 class="mr-form-title">📅 예약 신청</h2>
      <form id="mr-form" novalidate aria-label="테이블 예약 신청">

        <div class="mr-group">
          <label class="mr-label" for="mr-name">이름 <span class="mr-req" aria-label="필수">*</span></label>
          <input type="text" id="mr-name" name="name" class="mr-input"
            placeholder="홍길동" maxlength="50" required
            autocomplete="name" aria-required="true" inputmode="text">
        </div>

        <div class="mr-group">
          <label class="mr-label" for="mr-phone">연락처 <span class="mr-req" aria-label="필수">*</span></label>
          <input type="tel" id="mr-phone" name="phone" class="mr-input"
            placeholder="010-1234-5678" maxlength="20" required
            autocomplete="tel" aria-required="true" inputmode="tel">
        </div>

        <div class="mr-group">
          <label class="mr-label" for="mr-game">게임 종류 <span class="mr-req" aria-label="필수">*</span></label>
          <select id="mr-game" name="game" class="mr-select" required aria-required="true">
            <option value="" disabled selected>게임 선택</option>
            <option value="avatar-baccarat">아바타 바카라</option>
            <option value="speed-baccarat">스피드 바카라</option>
            <option value="roulette">유러피안 룰렛</option>
            <option value="blackjack">블랙잭</option>
            <option value="dragon-tiger">드래곤 타이거</option>
            <option value="sic-bo">식보</option>
          </select>
        </div>

        <div class="mr-group">
          <label class="mr-label" for="mr-date">희망 날짜 <span class="mr-req" aria-label="필수">*</span></label>
          <input type="date" id="mr-date" name="date" class="mr-input"
            required aria-required="true">
        </div>

        <div class="mr-group">
          <label class="mr-label" for="mr-memo">요청 사항</label>
          <textarea id="mr-memo" name="memo" class="mr-textarea"
            placeholder="특별 요청 사항" maxlength="300"></textarea>
        </div>

        <button type="submit" class="mr-submit" aria-label="예약 신청하기">📅 예약 신청하기</button>
        <div class="mr-success" id="mr-success" role="alert" aria-live="assertive">
          ✅ 예약 신청 완료. 담당자가 빠르게 연락드리겠습니다.
        </div>
      </form>
    </div>
  </div>

  <!-- 혜택 -->
  <div class="mr-benefits">
    <div class="mr-benefits-card">
      <h2 class="mr-benefits-title">예약 혜택</h2>
      <ul class="mr-benefits-list">
        <li>우선 입장 및 테이블 배정</li>
        <li>전담 매니저 1:1 서비스</li>
        <li>첫 예약 보너스 제공</li>
        <li>VIP 전용 채팅방 초대</li>
        <li>방송 일정 사전 안내</li>
      </ul>
    </div>
  </div>

  <!-- 빠른 연락 -->
  <div class="mr-contact">
    <a href="https://t.me/Fury0079" class="mr-contact-btn mr-telegram"
       rel="noopener noreferrer" target="_blank"
       aria-label="텔레그램으로 예약 문의 (새 탭에서 열림)">
      📱 텔레그램 @Fury0079
    </a>
    <a href="https://open.kakao.com/o/gucciyanolja" class="mr-contact-btn mr-kakao"
       rel="noopener noreferrer" target="_blank"
       aria-label="카카오톡으로 예약 문의 (새 탭에서 열림)">
      💬 카카오톡 오픈채팅
    </a>
  </div>

</main>

<?php require_once dirname(__DIR__, 3) . '/core/helpers/footer.php'; ?>

<script>
(function () {
  'use strict';
  /* 날짜 최솟값 */
  var date_input = document.getElementById('mr-date');
  if (date_input) {
    var today = new Date();
    var y = today.getFullYear();
    var m = String(today.getMonth() + 1).padStart(2, '0');
    var d = String(today.getDate()).padStart(2, '0');
    date_input.min = y + '-' + m + '-' + d;
  }
  /* 폼 제출 */
  var form    = document.getElementById('mr-form');
  var success = document.getElementById('mr-success');
  if (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var name  = document.getElementById('mr-name').value.trim();
      var phone = document.getElementById('mr-phone').value.trim();
      var game  = document.getElementById('mr-game').value;
      var date  = document.getElementById('mr-date').value;
      /* 정규식: 전화번호 검증 */
      var phone_clean = phone.replace(/[\s\-]/g, '');
      if (!name || !phone || !game || !date) { alert('필수 항목을 모두 입력해 주세요.'); return; }
      if (!/^0[0-9]{8,10}$/.test(phone_clean)) { alert('올바른 연락처를 입력해 주세요.'); return; }
      if (success) { success.style.display = 'block'; form.reset(); success.focus(); }
    });
  }
}());
</script>
<script src="/mobile/assets/js/mobile.js" defer></script>
</body>
</html>

