<?php
/**
 * 예약 페이지 — 구찌야놀자
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
}

$site_url   = 'https://xn--2e0bj1fruw33b6ti.net';
$page_url   = $site_url . '/reservation/';
$page_title = '테이블 예약 | 아바타 바카라 사전 예약 — 구찌야놀자';
$page_desc  = '구찌야놀자 테이블 예약. 아바타 바카라 테이블을 사전 예약하고 우선 입장 혜택을 받으세요.';
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
  <meta name="robots" content="index,follow">
  <link rel="canonical" href="<?= htmlspecialchars($page_url, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:type"        content="website">
  <meta property="og:title"       content="<?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:description" content="<?= htmlspecialchars($page_desc, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:url"         content="<?= htmlspecialchars($page_url, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:image"       content="<?= htmlspecialchars($page_img, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:locale"      content="ko_KR">
  <script type="application/ld+json">
  {"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"홈","item":"https://xn--2e0bj1fruw33b6ti.net/"},{"@type":"ListItem","position":2,"name":"예약","item":"https://xn--2e0bj1fruw33b6ti.net/reservation/"}]}
  </script>
  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
  <link rel="preload" href="/assets/css/mobile-responsive.css" as="style">
  <link rel="stylesheet" href="/assets/css/mobile-responsive.css">
  <style>
    @font-face{font-family:'SchoolSafetyTteokbokki';src:url('https://cdn.jsdelivr.net/gh/projectnoonnu/2510-1@1.0/HakgyoansimTTeokbokkiB.woff2') format('woff2');font-weight:normal;font-style:normal;font-display:swap;}
    body{font-family:'SchoolSafetyTteokbokki',sans-serif;background:#040f1c;color:#c8d8e8;margin:0;padding:0;min-height:100vh;display:flex;flex-direction:column;}
    .g-inner{max-width:1200px;margin:0 auto;padding:0 1.5rem;}
    .page-hero{background:linear-gradient(180deg,#071a2e 0%,#040f1c 100%);padding:2.5rem 0 2rem;border-bottom:1px solid rgba(245,200,66,.15);}
    .res-layout{display:grid;grid-template-columns:1fr 400px;gap:2rem;padding:2.5rem 0 3rem;}
    .res-form-wrap{background:#071a2e;border:1px solid rgba(245,200,66,.15);border-radius:16px;padding:2rem;}
    .res-form-title{font-size:1.2rem;font-weight:700;color:#f5c842;margin-bottom:1.5rem;padding-bottom:.75rem;border-bottom:1px solid rgba(245,200,66,.15);}
    .form-group{display:flex;flex-direction:column;gap:.4rem;margin-bottom:1.25rem;}
    .form-label{font-size:.9rem;font-weight:600;color:#c8d8e8;}
    .form-label .req{color:#fc8181;margin-left:.2rem;}
    .form-input,.form-select,.form-textarea{background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:8px;color:#c8d8e8;font-family:inherit;font-size:.9rem;padding:.7rem 1rem;transition:border-color .2s;width:100%;}
    .form-input:focus,.form-select:focus,.form-textarea:focus{outline:none;border-color:rgba(245,200,66,.5);box-shadow:0 0 0 3px rgba(245,200,66,.1);}
    .form-select option{background:#071a2e;color:#c8d8e8;}
    .form-textarea{resize:vertical;min-height:100px;}
    .form-submit{width:100%;padding:.875rem;background:linear-gradient(135deg,#f5c842,#e6a800);color:#040f1c;border:none;border-radius:8px;font-family:inherit;font-size:1rem;font-weight:700;cursor:pointer;transition:all .25s ease;min-height:52px;margin-top:.5rem;}
    .form-submit:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(245,200,66,.4);}
    .form-submit:focus-visible{outline:3px solid #f5c842;outline-offset:3px;}
    .form-note{font-size:.8rem;color:#6b7c93;margin-top:.4rem;}
    .res-sidebar{display:flex;flex-direction:column;gap:1.25rem;}
    .res-info-card{background:#071a2e;border:1px solid rgba(245,200,66,.12);border-radius:12px;padding:1.5rem;}
    .res-info-card h3{font-size:1rem;font-weight:700;color:#f5c842;margin-bottom:1rem;padding-bottom:.5rem;border-bottom:1px solid rgba(245,200,66,.12);}
    .res-info-list{list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:.6rem;}
    .res-info-list li{font-size:.875rem;color:#8898aa;display:flex;align-items:flex-start;gap:.5rem;line-height:1.6;}
    .res-info-list li::before{content:'✓';color:#f5c842;flex-shrink:0;font-weight:700;}
    .res-contact-btn{display:flex;align-items:center;gap:.75rem;padding:.875rem 1rem;border-radius:10px;text-decoration:none;font-size:.9rem;font-weight:600;transition:all .25s ease;min-height:52px;}
    .res-contact-btn:focus-visible{outline:3px solid #f5c842;outline-offset:3px;}
    .res-contact-telegram{background:linear-gradient(135deg,rgba(0,136,204,.2),rgba(0,136,204,.1));border:1px solid rgba(0,136,204,.3);color:#63b3ed;}
    .res-contact-telegram:hover{background:linear-gradient(135deg,rgba(0,136,204,.3),rgba(0,136,204,.2));border-color:rgba(0,136,204,.5);}
    .res-contact-kakao{background:linear-gradient(135deg,rgba(254,229,0,.15),rgba(254,229,0,.08));border:1px solid rgba(254,229,0,.25);color:#f6e05e;}
    .res-contact-kakao:hover{background:linear-gradient(135deg,rgba(254,229,0,.25),rgba(254,229,0,.15));border-color:rgba(254,229,0,.4);}
    .form-success{display:none;background:rgba(72,187,120,.1);border:1px solid rgba(72,187,120,.25);border-radius:8px;padding:1rem;color:#68d391;font-size:.9rem;text-align:center;margin-top:1rem;}
    @media(max-width:900px){.res-layout{grid-template-columns:1fr;}}
  </style>
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
<?php require_once dirname(__DIR__, 2) . '/core/helpers/header.php'; ?>

<main id="main-content" role="main">

  <section class="page-hero" aria-label="예약 페이지 헤더">
    <div class="g-inner">
      <nav aria-label="breadcrumb">
        <ol style="list-style:none;display:flex;gap:.5rem;font-size:.85rem;color:#6b7c93;padding:0;margin:0 0 .75rem;">
          <li><a href="/" style="color:#6b7c93;text-decoration:none;">홈</a></li>
          <li style="color:rgba(255,255,255,.2);">›</li>
          <li style="color:#f5c842;" aria-current="page">예약</li>
        </ol>
      </nav>
      <h1 style="font-size:clamp(1.5rem,4vw,2.25rem);color:#fff;margin-bottom:.5rem;">
        테이블 <span style="color:#f5c842;">예약</span>
      </h1>
      <p style="color:#8898aa;font-size:clamp(.9rem,2vw,1rem);">아바타 바카라 테이블 사전 예약 · 우선 입장 혜택</p>
    </div>
  </section>

  <div class="g-inner">
    <div class="res-layout">

      <!-- 예약 폼 -->
      <div>
        <div class="res-form-wrap">
          <h2 class="res-form-title">📅 예약 신청</h2>
          <form id="reservation-form" novalidate aria-label="테이블 예약 신청 폼">
            <input type="hidden" name="csrf_token" id="csrf_token" value="">

            <div class="form-group">
              <label class="form-label" for="res-name">이름 <span class="req" aria-label="필수">*</span></label>
              <input type="text" id="res-name" name="name" class="form-input"
                placeholder="홍길동" maxlength="50" required
                autocomplete="name" aria-required="true">
            </div>

            <div class="form-group">
              <label class="form-label" for="res-phone">연락처 <span class="req" aria-label="필수">*</span></label>
              <input type="tel" id="res-phone" name="phone" class="form-input"
                placeholder="010-1234-5678" maxlength="20" required
                autocomplete="tel" aria-required="true"
                pattern="[0-9\-]{10,15}">
              <span class="form-note">텔레그램 또는 카카오톡 ID도 가능합니다.</span>
            </div>

            <div class="form-group">
              <label class="form-label" for="res-game">게임 종류 <span class="req" aria-label="필수">*</span></label>
              <select id="res-game" name="game" class="form-select" required aria-required="true">
                <option value="" disabled selected>게임을 선택하세요</option>
                <option value="avatar-baccarat">아바타 바카라</option>
                <option value="speed-baccarat">스피드 바카라</option>
                <option value="roulette">유러피안 룰렛</option>
                <option value="blackjack">블랙잭</option>
                <option value="dragon-tiger">드래곤 타이거</option>
                <option value="sic-bo">식보</option>
              </select>
            </div>

            <div class="form-group">
              <label class="form-label" for="res-date">희망 날짜 <span class="req" aria-label="필수">*</span></label>
              <input type="date" id="res-date" name="date" class="form-input"
                required aria-required="true">
            </div>

            <div class="form-group">
              <label class="form-label" for="res-time">희망 시간</label>
              <select id="res-time" name="time" class="form-select">
                <option value="">시간 선택 (선택사항)</option>
                <option value="10:00">오전 10:00</option>
                <option value="14:00">오후 02:00</option>
                <option value="19:00">오후 07:00</option>
                <option value="22:00">오후 10:00</option>
              </select>
            </div>

            <div class="form-group">
              <label class="form-label" for="res-budget">예산 (선택사항)</label>
              <select id="res-budget" name="budget" class="form-select">
                <option value="">예산 선택</option>
                <option value="100k">10만원 이하</option>
                <option value="500k">10만원 ~ 50만원</option>
                <option value="1m">50만원 ~ 100만원</option>
                <option value="1m+">100만원 이상</option>
              </select>
            </div>

            <div class="form-group">
              <label class="form-label" for="res-memo">요청 사항</label>
              <textarea id="res-memo" name="memo" class="form-textarea"
                placeholder="특별 요청 사항이 있으시면 입력해 주세요."
                maxlength="500" aria-describedby="memo-count"></textarea>
              <span class="form-note" id="memo-count" aria-live="polite">0 / 500자</span>
            </div>

            <button type="submit" class="form-submit" aria-label="예약 신청하기">📅 예약 신청하기</button>
            <div class="form-success" id="form-success" role="alert" aria-live="assertive">
              ✅ 예약 신청이 완료되었습니다. 담당자가 빠르게 연락드리겠습니다.
            </div>
          </form>
        </div>
      </div>

      <!-- 사이드바 -->
      <aside aria-label="예약 안내">
        <div class="res-sidebar">

          <div class="res-info-card">
            <h3>예약 혜택</h3>
            <ul class="res-info-list">
              <li>우선 입장 및 테이블 배정</li>
              <li>전담 매니저 1:1 서비스</li>
              <li>첫 예약 보너스 제공</li>
              <li>VIP 전용 채팅방 초대</li>
              <li>방송 일정 사전 안내</li>
            </ul>
          </div>

          <div class="res-info-card">
            <h3>운영 시간</h3>
            <ul class="res-info-list">
              <li>오전 10:00 ~ 오전 02:00</li>
              <li>연중무휴 365일 운영</li>
              <li>캄보디아 현지 시간 기준</li>
              <li>한국 시간 +0시간 (동일)</li>
            </ul>
          </div>

          <div class="res-info-card">
            <h3>빠른 문의</h3>
            <div style="display:flex;flex-direction:column;gap:.75rem;margin-top:.25rem;">
              <a href="https://t.me/Fury0079"
                class="res-contact-btn res-contact-telegram"
                rel="noopener noreferrer" target="_blank"
                aria-label="텔레그램으로 문의하기 (새 탭에서 열림)">
                📱 <span><strong>텔레그램</strong><br><small style="font-size:.78rem;opacity:.8;">@Fury0079</small></span>
              </a>
              <a href="https://open.kakao.com/o/gucciyanolja"
                class="res-contact-btn res-contact-kakao"
                rel="noopener noreferrer" target="_blank"
                aria-label="카카오톡으로 문의하기 (새 탭에서 열림)">
                💬 <span><strong>카카오톡</strong><br><small style="font-size:.78rem;opacity:.8;">오픈채팅</small></span>
              </a>
            </div>
          </div>

        </div>
      </aside>

    </div>
  </div>

</main>

<?php require_once dirname(__DIR__, 2) . '/core/helpers/footer.php'; ?>

<script>
(function () {
  'use strict';
  /* 날짜 최솟값 — 오늘 이후만 선택 가능 */
  var dateInput = document.getElementById('res-date');
  if (dateInput) {
    var today = new Date();
    var yyyy = today.getFullYear();
    var mm = String(today.getMonth() + 1).padStart(2, '0');
    var dd = String(today.getDate()).padStart(2, '0');
    dateInput.min = yyyy + '-' + mm + '-' + dd;
  }

  /* 메모 글자 수 카운터 */
  var memo = document.getElementById('res-memo');
  var memoCount = document.getElementById('memo-count');
  if (memo && memoCount) {
    memo.addEventListener('input', function () {
      memoCount.textContent = memo.value.length + ' / 500자';
    });
  }

  /* 폼 제출 처리 */
  var form = document.getElementById('reservation-form');
  var successMsg = document.getElementById('form-success');
  if (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var name = document.getElementById('res-name').value.trim();
      var phone = document.getElementById('res-phone').value.trim();
      var game = document.getElementById('res-game').value;
      var date = document.getElementById('res-date').value;
      if (!name || !phone || !game || !date) {
        alert('필수 항목을 모두 입력해 주세요.');
        return;
      }
      /* 실제 배포 시 API 엔드포인트로 전송 */
      if (successMsg) {
        successMsg.style.display = 'block';
        form.reset();
        if (memoCount) memoCount.textContent = '0 / 500자';
        successMsg.focus();
      }
    });
  }
}());
</script>
</body>
</html>

