<?php
/**
 * 스트리밍 페이지 — 구찌야놀자
 * ref: https://schema.org/VideoObject
 * ref: https://developers.google.com/search/docs/appearance/structured-data/video
 * ref: https://developer.mozilla.org/en-US/docs/Web/HTML/Element/video
 * ref: https://owasp.org/www-project-secure-headers/
 */
declare(strict_types=1);

if (!headers_sent()) {
    header_remove('X-Powered-By');
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header("Content-Security-Policy: default-src 'self' https:; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://accounts.google.com; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; img-src 'self' data: https:; font-src 'self' data: https://cdn.jsdelivr.net; connect-src 'self' https: wss:; media-src 'self' https: blob:; frame-ancestors 'self';");
}

$site_url  = 'https://xn--2e0bj1fruw33b6ti.net';
$page_url  = $site_url . '/streaming/';
$page_title = '실시간 스트리밍 | 아바타 바카라 생방송 — 구찌야놀자';
$page_desc  = '아바타 바카라 캄보디아 현장 생방송. 실시간 스트리밍으로 현장감 있는 바카라를 즐기세요. 고화질 · 저지연 · 안정적 연결.';
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
  <meta name="robots" content="index,follow,max-snippet:-1,max-image-preview:large,max-video-preview:-1">
  <link rel="canonical" href="<?= htmlspecialchars($page_url, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:type"        content="video.other">
  <meta property="og:title"       content="<?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:description" content="<?= htmlspecialchars($page_desc, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:url"         content="<?= htmlspecialchars($page_url, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:image"       content="<?= htmlspecialchars($page_img, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:locale"      content="ko_KR">
  <meta name="twitter:card"       content="summary_large_image">
  <meta name="twitter:title"      content="<?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="twitter:image"      content="<?= htmlspecialchars($page_img, ENT_QUOTES, 'UTF-8') ?>">
  <!-- BreadcrumbList — https://schema.org/BreadcrumbList -->
  <script type="application/ld+json">
  {"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"홈","item":"https://xn--2e0bj1fruw33b6ti.net/"},{"@type":"ListItem","position":2,"name":"스트리밍","item":"https://xn--2e0bj1fruw33b6ti.net/streaming/"}]}
  </script>
  <!-- VideoObject — https://schema.org/VideoObject -->
  <script type="application/ld+json">
  {"@context":"https://schema.org","@type":"VideoObject","name":"아바타 바카라 캄보디아 생방송 — 구찌야놀자","description":"캄보디아 현장에서 진행되는 아바타 바카라 실시간 생방송.","thumbnailUrl":"https://xn--2e0bj1fruw33b6ti.net/아바타-바카라-구찌야-놀자.png","uploadDate":"2026-01-01","contentUrl":"https://xn--2e0bj1fruw33b6ti.net/streaming/","embedUrl":"https://xn--2e0bj1fruw33b6ti.net/streaming/"}
  </script>
  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
  <link rel="dns-prefetch" href="//cdn.jsdelivr.net">
  <link rel="preload" href="/assets/css/mobile-responsive.css" as="style">
  <link rel="stylesheet" href="/assets/css/mobile-responsive.css">
  <style>
    @font-face{font-family:'SchoolSafetyTteokbokki';src:url('https://cdn.jsdelivr.net/gh/projectnoonnu/2510-1@1.0/HakgyoansimTTeokbokkiB.woff2') format('woff2');font-weight:normal;font-style:normal;font-display:swap;}
    body{font-family:'SchoolSafetyTteokbokki',sans-serif;background:#040f1c;color:#c8d8e8;margin:0;padding:0;min-height:100vh;display:flex;flex-direction:column;}
    .stream-hero{background:linear-gradient(180deg,#071a2e 0%,#040f1c 100%);padding:2rem 0 1.5rem;border-bottom:1px solid rgba(245,200,66,0.15);}
    .stream-inner{max-width:1200px;margin:0 auto;padding:0 1.5rem;}
    .stream-layout{display:grid;grid-template-columns:1fr 360px;gap:1.5rem;margin-top:1.5rem;}
    .stream-player-wrap{background:#000;border-radius:12px;overflow:hidden;border:2px solid rgba(245,200,66,0.2);position:relative;}
    .stream-player-wrap::before{content:'';display:block;padding-top:56.25%;}
    .stream-player-inner{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:1rem;}
    .stream-live-badge{position:absolute;top:12px;left:12px;background:rgba(229,62,62,0.9);color:#fff;padding:.3rem .7rem;border-radius:4px;font-size:.78rem;font-weight:700;display:flex;align-items:center;gap:.35rem;z-index:2;}
    .stream-live-dot{width:6px;height:6px;background:#fff;border-radius:50%;animation:blink 1.5s ease-in-out infinite;}
    @keyframes blink{0%,100%{opacity:1}50%{opacity:.3}}
    .stream-play-btn{width:72px;height:72px;background:rgba(245,200,66,0.15);border:2px solid rgba(245,200,66,0.4);border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all .3s ease;font-size:1.75rem;}
    .stream-play-btn:hover{background:rgba(245,200,66,0.3);transform:scale(1.1);}
    .stream-play-label{font-size:.9rem;color:#8898aa;}
    .stream-info-bar{display:flex;align-items:center;justify-content:space-between;padding:.875rem 1rem;background:rgba(255,255,255,.03);border:1px solid rgba(245,200,66,.12);border-radius:8px;margin-top:.75rem;flex-wrap:wrap;gap:.5rem;}
    .stream-info-title{font-size:1rem;font-weight:700;color:#fff;}
    .stream-info-meta{font-size:.82rem;color:#6b7c93;display:flex;align-items:center;gap:.75rem;}
    .stream-viewers{display:flex;align-items:center;gap:.3rem;color:#f5c842;font-size:.85rem;font-weight:600;}
    .chat-panel{background:#071a2e;border:1px solid rgba(245,200,66,.15);border-radius:12px;display:flex;flex-direction:column;height:600px;}
    .chat-header{padding:.875rem 1rem;border-bottom:1px solid rgba(245,200,66,.12);display:flex;align-items:center;justify-content:space-between;}
    .chat-title{font-size:.95rem;font-weight:700;color:#f5c842;}
    .chat-online{font-size:.78rem;color:#68d391;display:flex;align-items:center;gap:.3rem;}
    .chat-online-dot{width:6px;height:6px;background:#68d391;border-radius:50%;animation:blink 2s ease-in-out infinite;}
    .chat-messages{flex:1;overflow-y:auto;padding:.75rem;display:flex;flex-direction:column;gap:.5rem;scroll-behavior:smooth;}
    .chat-messages::-webkit-scrollbar{width:4px;}
    .chat-messages::-webkit-scrollbar-track{background:transparent;}
    .chat-messages::-webkit-scrollbar-thumb{background:rgba(245,200,66,.2);border-radius:2px;}
    .chat-msg{font-size:.85rem;line-height:1.5;}
    .chat-msg-name{font-weight:700;color:#f5c842;margin-right:.35rem;}
    .chat-msg-text{color:#c8d8e8;}
    .chat-msg-system{color:#6b7c93;font-style:italic;font-size:.8rem;}
    .chat-input-area{padding:.75rem;border-top:1px solid rgba(245,200,66,.12);}
    .chat-input-row{display:flex;gap:.5rem;}
    .chat-input{flex:1;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:6px;color:#c8d8e8;font-family:inherit;font-size:.875rem;padding:.6rem .875rem;transition:border-color .2s;}
    .chat-input:focus{outline:none;border-color:rgba(245,200,66,.5);}
    .chat-send-btn{background:linear-gradient(135deg,#f5c842,#e6a800);color:#040f1c;border:none;border-radius:6px;padding:.6rem 1rem;font-family:inherit;font-size:.875rem;font-weight:700;cursor:pointer;transition:all .2s;min-width:60px;}
    .chat-send-btn:hover{transform:translateY(-1px);box-shadow:0 4px 12px rgba(245,200,66,.4);}
    .stream-tabs{display:flex;gap:.5rem;margin-top:1.5rem;border-bottom:1px solid rgba(255,255,255,.07);padding-bottom:0;}
    .stream-tab{background:none;border:none;border-bottom:2px solid transparent;color:#6b7c93;cursor:pointer;font-family:inherit;font-size:.9rem;font-weight:600;padding:.6rem 1rem;transition:all .2s;margin-bottom:-1px;}
    .stream-tab.active{border-bottom-color:#f5c842;color:#f5c842;}
    .stream-tab:hover{color:#c8d8e8;}
    .stream-tab-content{padding:1.5rem 0;display:none;}
    .stream-tab-content.active{display:block;}
    .stream-schedule-list{display:flex;flex-direction:column;gap:.75rem;}
    .schedule-item{display:flex;align-items:center;gap:1rem;padding:.875rem 1rem;background:rgba(255,255,255,.03);border:1px solid rgba(245,200,66,.1);border-radius:8px;}
    .schedule-time{font-size:.85rem;color:#f5c842;font-weight:700;min-width:80px;}
    .schedule-title{font-size:.9rem;color:#c8d8e8;}
    .schedule-status{margin-left:auto;font-size:.75rem;padding:.2rem .6rem;border-radius:4px;}
    .schedule-status.live{background:rgba(229,62,62,.2);color:#fc8181;}
    .schedule-status.upcoming{background:rgba(245,200,66,.1);color:#f5c842;}
    @media(max-width:900px){.stream-layout{grid-template-columns:1fr;}.chat-panel{height:400px;}}
    @media(max-width:600px){.stream-info-bar{flex-direction:column;align-items:flex-start;}.stream-tabs{overflow-x:auto;}}
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

  <!-- 페이지 헤더 -->
  <section class="stream-hero" aria-label="스트리밍 페이지 헤더">
    <div class="stream-inner">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="list-style:none;display:flex;gap:.5rem;font-size:.85rem;color:#6b7c93;padding:0;margin:0 0 .75rem;">
          <li><a href="/" style="color:#6b7c93;text-decoration:none;">홈</a></li>
          <li style="color:rgba(255,255,255,.2);">›</li>
          <li style="color:#f5c842;" aria-current="page">스트리밍</li>
        </ol>
      </nav>
      <h1 style="font-size:clamp(1.5rem,4vw,2.25rem);color:#fff;margin-bottom:.5rem;">
        <span style="color:#f5c842;">실시간</span> 스트리밍
      </h1>
      <p style="color:#8898aa;font-size:clamp(.9rem,2vw,1rem);">아바타 바카라 캄보디아 현장 생방송 · 고화질 · 저지연</p>
    </div>
  </section>

  <!-- 스트리밍 메인 레이아웃 -->
  <div class="stream-inner" style="padding-top:1.5rem;padding-bottom:3rem;">
    <div class="stream-layout">

      <!-- 플레이어 영역 -->
      <div>
        <div class="stream-player-wrap" role="region" aria-label="스트리밍 플레이어">
          <div class="stream-live-badge" aria-label="현재 생방송 중">
            <span class="stream-live-dot" aria-hidden="true"></span>LIVE
          </div>
          <div class="stream-player-inner" id="player-placeholder">
            <div class="stream-play-btn" role="button" tabindex="0" aria-label="방송 시작하기" id="start-stream-btn">▶</div>
            <p class="stream-play-label">클릭하여 생방송 시작</p>
          </div>
          <!-- HLS 플레이어 컨테이너 -->
          <video id="hls-player" controls playsinline
            style="position:absolute;inset:0;width:100%;height:100%;display:none;"
            aria-label="아바타 바카라 생방송 플레이어"
            poster="/아바타-바카라-구찌야-놀자.png">
            <track kind="captions" label="한국어" srclang="ko" default>
          </video>
        </div>

        <!-- 방송 정보 바 -->
        <div class="stream-info-bar">
          <div>
            <div class="stream-info-title">🎰 아바타 바카라 캄보디아 생방송</div>
            <div class="stream-info-meta">
              <span>구찌야놀자</span>
              <span>·</span>
              <span id="stream-time">방송 중</span>
            </div>
          </div>
          <div class="stream-viewers" aria-live="polite" aria-label="현재 시청자 수">
            👁 <span id="viewer-count">--</span>명 시청 중
          </div>
        </div>

        <!-- 탭 메뉴 -->
        <div class="stream-tabs" role="tablist" aria-label="스트리밍 탭">
          <button class="stream-tab active" role="tab" aria-selected="true" aria-controls="tab-schedule" id="tab-btn-schedule">방송 일정</button>
          <button class="stream-tab" role="tab" aria-selected="false" aria-controls="tab-info" id="tab-btn-info">방송 안내</button>
          <button class="stream-tab" role="tab" aria-selected="false" aria-controls="tab-guide" id="tab-btn-guide">이용 방법</button>
        </div>

        <!-- 방송 일정 탭 -->
        <div class="stream-tab-content active" id="tab-schedule" role="tabpanel" aria-labelledby="tab-btn-schedule">
          <div class="stream-schedule-list">
            <div class="schedule-item">
              <span class="schedule-time">오전 10:00</span>
              <span class="schedule-title">🎰 아바타 바카라 오전 생방송</span>
              <span class="schedule-status live">LIVE</span>
            </div>
            <div class="schedule-item">
              <span class="schedule-time">오후 02:00</span>
              <span class="schedule-title">🎰 아바타 바카라 오후 생방송</span>
              <span class="schedule-status upcoming">예정</span>
            </div>
            <div class="schedule-item">
              <span class="schedule-time">오후 07:00</span>
              <span class="schedule-title">🎰 아바타 바카라 저녁 생방송</span>
              <span class="schedule-status upcoming">예정</span>
            </div>
            <div class="schedule-item">
              <span class="schedule-time">오후 10:00</span>
              <span class="schedule-title">🎰 아바타 바카라 심야 생방송</span>
              <span class="schedule-status upcoming">예정</span>
            </div>
          </div>
        </div>

        <!-- 방송 안내 탭 -->
        <div class="stream-tab-content" id="tab-info" role="tabpanel" aria-labelledby="tab-btn-info">
          <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;">
            <div style="background:rgba(255,255,255,.03);border:1px solid rgba(245,200,66,.1);border-radius:10px;padding:1.25rem;">
              <div style="font-size:1.5rem;margin-bottom:.5rem;">🎬</div>
              <div style="font-size:.95rem;font-weight:700;color:#f5c842;margin-bottom:.35rem;">고화질 스트리밍</div>
              <div style="font-size:.85rem;color:#8898aa;line-height:1.6;">1080p 고화질로 캄보디아 현장을 생생하게 전달합니다.</div>
            </div>
            <div style="background:rgba(255,255,255,.03);border:1px solid rgba(245,200,66,.1);border-radius:10px;padding:1.25rem;">
              <div style="font-size:1.5rem;margin-bottom:.5rem;">⚡</div>
              <div style="font-size:.95rem;font-weight:700;color:#f5c842;margin-bottom:.35rem;">저지연 연결</div>
              <div style="font-size:.85rem;color:#8898aa;line-height:1.6;">3초 이내 저지연으로 실시간 현장감을 제공합니다.</div>
            </div>
            <div style="background:rgba(255,255,255,.03);border:1px solid rgba(245,200,66,.1);border-radius:10px;padding:1.25rem;">
              <div style="font-size:1.5rem;margin-bottom:.5rem;">📱</div>
              <div style="font-size:.95rem;font-weight:700;color:#f5c842;margin-bottom:.35rem;">모바일 최적화</div>
              <div style="font-size:.85rem;color:#8898aa;line-height:1.6;">스마트폰에서도 끊김 없이 시청할 수 있습니다.</div>
            </div>
          </div>
        </div>

        <!-- 이용 방법 탭 -->
        <div class="stream-tab-content" id="tab-guide" role="tabpanel" aria-labelledby="tab-btn-guide">
          <ol style="padding-left:1.25rem;display:flex;flex-direction:column;gap:.75rem;">
            <li style="color:#8898aa;font-size:.9rem;line-height:1.7;"><strong style="color:#c8d8e8;">Google 로그인</strong> — 상단 로그인 버튼으로 Google 계정 연동</li>
            <li style="color:#8898aa;font-size:.9rem;line-height:1.7;"><strong style="color:#c8d8e8;">방송 시작</strong> — 플레이어 중앙 버튼 클릭으로 생방송 시작</li>
            <li style="color:#8898aa;font-size:.9rem;line-height:1.7;"><strong style="color:#c8d8e8;">채팅 참여</strong> — 우측 채팅창에서 실시간 소통</li>
            <li style="color:#8898aa;font-size:.9rem;line-height:1.7;"><strong style="color:#c8d8e8;">예약 문의</strong> — <a href="/reservation/" style="color:#f5c842;">예약 페이지</a>에서 테이블 사전 예약</li>
          </ol>
        </div>
      </div>

      <!-- 채팅 패널 -->
      <aside aria-label="실시간 채팅">
        <div class="chat-panel">
          <div class="chat-header">
            <span class="chat-title">💬 실시간 채팅</span>
            <span class="chat-online">
              <span class="chat-online-dot" aria-hidden="true"></span>
              <span id="chat-online-count">--</span>명 접속 중
            </span>
          </div>
          <div class="chat-messages" id="chat-messages" role="log" aria-live="polite" aria-label="채팅 메시지">
            <div class="chat-msg chat-msg-system">채팅에 오신 것을 환영합니다 🎰</div>
            <div class="chat-msg chat-msg-system">로그인 후 채팅에 참여하세요.</div>
          </div>
          <div class="chat-input-area">
            <div class="chat-input-row">
              <label for="chat-input" class="sr-only" style="position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0,0,0,0);">채팅 메시지 입력</label>
              <input type="text" id="chat-input" class="chat-input"
                placeholder="메시지를 입력하세요..."
                maxlength="200"
                aria-label="채팅 메시지 입력"
                autocomplete="off">
              <button type="button" class="chat-send-btn" id="chat-send-btn" aria-label="메시지 전송">전송</button>
            </div>
          </div>
        </div>
      </aside>

    </div><!-- /.stream-layout -->
  </div>

</main>

<?php require_once dirname(__DIR__, 2) . '/core/helpers/footer.php'; ?>

<script>
/* 스트리밍 페이지 JS
 * ref: https://developer.mozilla.org/en-US/docs/Web/API/WebSocket
 * ref: https://developer.mozilla.org/en-US/docs/Web/HTML/Element/video
 */
(function () {
  'use strict';

  /* ── 탭 전환 */
  var tabs = document.querySelectorAll('.stream-tab');
  tabs.forEach(function (tab) {
    tab.addEventListener('click', function () {
      tabs.forEach(function (t) { t.classList.remove('active'); t.setAttribute('aria-selected', 'false'); });
      document.querySelectorAll('.stream-tab-content').forEach(function (c) { c.classList.remove('active'); });
      tab.classList.add('active');
      tab.setAttribute('aria-selected', 'true');
      var target = document.getElementById(tab.getAttribute('aria-controls'));
      if (target) target.classList.add('active');
    });
  });

  /* ── 플레이어 시작 버튼 */
  var startBtn = document.getElementById('start-stream-btn');
  var placeholder = document.getElementById('player-placeholder');
  var videoEl = document.getElementById('hls-player');
  if (startBtn && placeholder && videoEl) {
    function startPlayer() {
      placeholder.style.display = 'none';
      videoEl.style.display = 'block';
      /* HLS 스트림 URL — 실제 배포 시 SRS 서버 URL로 교체 */
      var hlsUrl = 'https://xn--2e0bj1fruw33b6ti.net/live/stream.m3u8';
      if (typeof Hls !== 'undefined' && Hls.isSupported()) {
        var hls = new Hls({ lowLatencyMode: true });
        hls.loadSource(hlsUrl);
        hls.attachMedia(videoEl);
        hls.on(Hls.Events.MANIFEST_PARSED, function () { videoEl.play(); });
      } else if (videoEl.canPlayType('application/vnd.apple.mpegurl')) {
        videoEl.src = hlsUrl;
        videoEl.play();
      }
    }
    startBtn.addEventListener('click', startPlayer);
    startBtn.addEventListener('keydown', function (e) { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); startPlayer(); } });
  }

  /* ── 시청자 수 시뮬레이션 (실제 배포 시 WebSocket으로 교체) */
  var viewerEl = document.getElementById('viewer-count');
  var onlineEl = document.getElementById('chat-online-count');
  if (viewerEl) {
    var base = 120 + Math.floor(Math.random() * 80);
    viewerEl.textContent = base;
    if (onlineEl) onlineEl.textContent = Math.floor(base * 0.6);
    setInterval(function () {
      base += Math.floor(Math.random() * 5) - 2;
      if (base < 50) base = 50;
      viewerEl.textContent = base;
      if (onlineEl) onlineEl.textContent = Math.floor(base * 0.6);
    }, 8000);
  }

  /* ── 방송 시간 표시 */
  var timeEl = document.getElementById('stream-time');
  if (timeEl) {
    var start = Date.now();
    setInterval(function () {
      var s = Math.floor((Date.now() - start) / 1000);
      var h = Math.floor(s / 3600), m = Math.floor((s % 3600) / 60), sec = s % 60;
      timeEl.textContent = (h > 0 ? h + '시간 ' : '') + m + '분 ' + sec + '초 방송 중';
    }, 1000);
  }

  /* ── 채팅 전송 (WebSocket 연결 전 로컬 처리) */
  var chatInput = document.getElementById('chat-input');
  var chatSend = document.getElementById('chat-send-btn');
  var chatMessages = document.getElementById('chat-messages');
  var demoNames = ['김철수', '이영희', '박민준', '최지은', '정우성'];
  var demoMsgs = ['좋은 방송 감사합니다!', '오늘도 대박 나세요 🎰', '바카라 최고!', '구찌야놀자 최고', '실시간 너무 좋아요'];

  function appendMsg(name, text, isSystem) {
    if (!chatMessages) return;
    var div = document.createElement('div');
    div.className = 'chat-msg' + (isSystem ? ' chat-msg-system' : '');
    if (!isSystem) {
      div.innerHTML = '<span class="chat-msg-name">' + name + '</span><span class="chat-msg-text">' + text + '</span>';
    } else {
      div.textContent = text;
    }
    chatMessages.appendChild(div);
    chatMessages.scrollTop = chatMessages.scrollHeight;
    /* 최대 100개 메시지 유지 */
    while (chatMessages.children.length > 100) chatMessages.removeChild(chatMessages.firstChild);
  }

  function sendMsg() {
    if (!chatInput) return;
    var val = chatInput.value.trim();
    if (!val) return;
    /* XSS 방지 */
    var safe = val.replace(/[<>&"']/g, function (c) { return {'<':'&lt;','>':'&gt;','&':'&amp;','"':'&quot;',"'":'&#39;'}[c]; });
    appendMsg('나', safe, false);
    chatInput.value = '';
    chatInput.focus();
  }

  if (chatSend) chatSend.addEventListener('click', sendMsg);
  if (chatInput) chatInput.addEventListener('keydown', function (e) { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMsg(); } });

  /* 데모 채팅 메시지 */
  setInterval(function () {
    var name = demoNames[Math.floor(Math.random() * demoNames.length)];
    var msg = demoMsgs[Math.floor(Math.random() * demoMsgs.length)];
    appendMsg(name, msg, false);
  }, 6000 + Math.random() * 4000);

}());
</script>
</body>
</html>

