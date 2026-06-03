<?php
/**
 * 모바일 스트리밍 페이지 — 구찌야놀자
 * ref: https://schema.org/VideoObject
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
    $https = $_SERVER['HTTPS'] ?? '';
    if ($https !== '' && $https !== 'off') {
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
    }
}

/* UA 검증 */
$ua = (string)($_SERVER['HTTP_USER_AGENT'] ?? '');
if (!preg_match('/Mobile|Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i', $ua)) {
    header('Location: /desktop/streaming/', true, 302);
    exit;
}

$site_url   = 'https://xn--2e0bj1fruw33b6ti.net';
$page_url   = $site_url . '/mobile/streaming/';
$page_title = '모바일 실시간 스트리밍 | 아바타 바카라 생방송 — 구찌야놀자';
$page_desc  = '아바타 바카라 캄보디아 현장 생방송. 모바일 최적화 실시간 스트리밍. 고화질 · 저지연.';
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
  <meta property="og:type"        content="video.other">
  <meta property="og:title"       content="<?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:description" content="<?= htmlspecialchars($page_desc, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:url"         content="<?= htmlspecialchars($page_url, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:image"       content="<?= htmlspecialchars($page_img, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:locale"      content="ko_KR">
  <!-- BreadcrumbList — ref: https://schema.org/BreadcrumbList -->
  <script type="application/ld+json">
  {"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"홈","item":"https://xn--2e0bj1fruw33b6ti.net/"},{"@type":"ListItem","position":2,"name":"스트리밍","item":"https://xn--2e0bj1fruw33b6ti.net/mobile/streaming/"}]}
  </script>
  <!-- VideoObject — ref: https://schema.org/VideoObject -->
  <script type="application/ld+json">
  {"@context":"https://schema.org","@type":"VideoObject","name":"아바타 바카라 캄보디아 생방송","description":"캄보디아 현장 아바타 바카라 실시간 생방송.","thumbnailUrl":"https://xn--2e0bj1fruw33b6ti.net/assets/images/avatar-baccarat-gucci-play.png","uploadDate":"2026-01-01","contentUrl":"https://xn--2e0bj1fruw33b6ti.net/mobile/streaming/"}
  </script>
  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
  <link rel="preload" href="/assets/css/mobile-responsive.css" as="style">
  <link rel="stylesheet" href="/assets/css/mobile-responsive.css">
  <link rel="stylesheet" href="/mobile/assets/css/mobile.css">
  <meta name="theme-color" content="#040f1c">
  <style>
    /* 모바일 스트리밍 전용 스타일 */
    .ms-player-wrap{background:#000;border-radius:10px;overflow:hidden;border:2px solid rgba(245,200,66,.2);position:relative;margin:1rem;}
    .ms-player-wrap::before{content:'';display:block;padding-top:56.25%;}
    .ms-player-inner{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:.75rem;}
    .ms-live-badge{position:absolute;top:8px;left:8px;background:rgba(229,62,62,.9);color:#fff;padding:.25rem .6rem;border-radius:4px;font-size:.72rem;font-weight:700;display:flex;align-items:center;gap:.3rem;z-index:2;}
    .ms-live-dot{width:5px;height:5px;background:#fff;border-radius:50%;animation:m-blink 1.5s ease-in-out infinite;}
    .ms-play-btn{width:60px;height:60px;background:rgba(245,200,66,.15);border:2px solid rgba(245,200,66,.4);border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:1.5rem;transition:all .25s;}
    .ms-play-btn:active{background:rgba(245,200,66,.3);transform:scale(.95);}
    .ms-info{padding:.75rem 1rem;background:rgba(255,255,255,.03);border:1px solid rgba(245,200,66,.1);border-radius:8px;margin:0 1rem .75rem;}
    .ms-info-title{font-size:.9rem;font-weight:700;color:#fff;margin-bottom:.25rem;}
    .ms-info-meta{font-size:.78rem;color:#6b7c93;display:flex;align-items:center;justify-content:space-between;}
    .ms-viewers{color:#f5c842;font-weight:600;}
    .ms-chat{background:#071a2e;border-top:1px solid rgba(245,200,66,.12);display:flex;flex-direction:column;height:300px;margin:0 1rem 1rem;border-radius:10px;overflow:hidden;}
    .ms-chat-header{padding:.6rem .875rem;border-bottom:1px solid rgba(245,200,66,.1);display:flex;align-items:center;justify-content:space-between;}
    .ms-chat-title{font-size:.85rem;font-weight:700;color:#f5c842;}
    .ms-chat-msgs{flex:1;overflow-y:auto;padding:.5rem .75rem;display:flex;flex-direction:column;gap:.4rem;}
    .ms-chat-msgs::-webkit-scrollbar{width:3px;}
    .ms-chat-msgs::-webkit-scrollbar-thumb{background:rgba(245,200,66,.2);border-radius:2px;}
    .ms-chat-msg{font-size:.8rem;line-height:1.5;}
    .ms-chat-name{font-weight:700;color:#f5c842;margin-right:.3rem;}
    .ms-chat-sys{color:#6b7c93;font-style:italic;font-size:.75rem;}
    .ms-chat-input-row{display:flex;gap:.4rem;padding:.5rem .75rem;border-top:1px solid rgba(245,200,66,.1);}
    .ms-chat-input{flex:1;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:6px;color:#c8d8e8;font-family:inherit;font-size:.8rem;padding:.5rem .75rem;}
    .ms-chat-input:focus{outline:none;border-color:rgba(245,200,66,.4);}
    .ms-chat-send{background:linear-gradient(135deg,#f5c842,#e6a800);color:#040f1c;border:none;border-radius:6px;font-family:inherit;font-size:.8rem;font-weight:700;padding:.5rem .875rem;cursor:pointer;min-height:40px;}
    .ms-schedule{padding:0 1rem 1.5rem;}
    .ms-schedule-title{font-size:.95rem;font-weight:700;color:#f5c842;margin-bottom:.75rem;}
    .ms-schedule-item{display:flex;align-items:center;gap:.75rem;padding:.7rem .875rem;background:rgba(255,255,255,.03);border:1px solid rgba(245,200,66,.1);border-radius:8px;margin-bottom:.5rem;}
    .ms-schedule-time{font-size:.8rem;color:#f5c842;font-weight:700;min-width:70px;}
    .ms-schedule-name{font-size:.85rem;color:#c8d8e8;}
    .ms-schedule-status{margin-left:auto;font-size:.7rem;padding:.15rem .5rem;border-radius:4px;}
    .ms-status-live{background:rgba(229,62,62,.2);color:#fc8181;}
    .ms-status-soon{background:rgba(245,200,66,.1);color:#f5c842;}
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

  <!-- 브레드크럼 -->
  <nav aria-label="breadcrumb" style="padding:.75rem 1rem 0;">
    <ol style="list-style:none;display:flex;gap:.4rem;font-size:.78rem;color:#6b7c93;padding:0;margin:0;">
      <li><a href="/mobile/" style="color:#6b7c93;text-decoration:none;">홈</a></li>
      <li style="color:rgba(255,255,255,.2);">›</li>
      <li style="color:#f5c842;" aria-current="page">스트리밍</li>
    </ol>
  </nav>

  <!-- 플레이어 -->
  <section aria-label="스트리밍 플레이어">
    <div class="ms-player-wrap" role="region" aria-label="스트리밍 플레이어">
      <div class="ms-live-badge" aria-label="생방송 중">
        <span class="ms-live-dot" aria-hidden="true"></span>LIVE
      </div>
      <div class="ms-player-inner" id="ms-placeholder">
        <div class="ms-play-btn" role="button" tabindex="0" aria-label="방송 시작" id="ms-start-btn">▶</div>
        <p style="font-size:.8rem;color:#8898aa;">탭하여 생방송 시작</p>
      </div>
      <!-- ref: https://developer.mozilla.org/en-US/docs/Web/HTML/Element/video -->
      <video id="ms-video" controls playsinline
        style="position:absolute;inset:0;width:100%;height:100%;display:none;"
        aria-label="아바타 바카라 생방송"
        poster="/assets/images/avatar-baccarat-gucci-play.png">
        <track kind="captions" label="한국어" srclang="ko" default>
      </video>
    </div>

    <!-- 방송 정보 -->
    <div class="ms-info">
      <div class="ms-info-title">🎰 아바타 바카라 캄보디아 생방송</div>
      <div class="ms-info-meta">
        <span id="ms-time">방송 중</span>
        <span class="ms-viewers" aria-live="polite">👁 <span id="ms-viewers">--</span>명</span>
      </div>
    </div>
  </section>

  <!-- 채팅 -->
  <section aria-label="실시간 채팅">
    <div class="ms-chat">
      <div class="ms-chat-header">
        <span class="ms-chat-title">💬 실시간 채팅</span>
        <span style="font-size:.72rem;color:#68d391;display:flex;align-items:center;gap:.25rem;">
          <span style="width:5px;height:5px;background:#68d391;border-radius:50%;animation:m-blink 2s ease-in-out infinite;" aria-hidden="true"></span>
          <span id="ms-online">--</span>명
        </span>
      </div>
      <div class="ms-chat-msgs" id="ms-chat-msgs" role="log" aria-live="polite" aria-label="채팅 메시지">
        <div class="ms-chat-msg ms-chat-sys">채팅에 오신 것을 환영합니다 🎰</div>
      </div>
      <div class="ms-chat-input-row">
        <label for="ms-chat-input" style="position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0,0,0,0);">채팅 입력</label>
        <input type="text" id="ms-chat-input" class="ms-chat-input"
          placeholder="메시지..." maxlength="200" autocomplete="off"
          aria-label="채팅 메시지 입력">
        <button type="button" class="ms-chat-send" id="ms-chat-send" aria-label="전송">전송</button>
      </div>
    </div>
  </section>

  <!-- 방송 일정 -->
  <section class="ms-schedule" aria-labelledby="ms-schedule-title">
    <h2 id="ms-schedule-title" class="ms-schedule-title">📅 방송 일정</h2>
    <div class="ms-schedule-item">
      <span class="ms-schedule-time">오전 10:00</span>
      <span class="ms-schedule-name">아바타 바카라 오전 방송</span>
      <span class="ms-schedule-status ms-status-live">LIVE</span>
    </div>
    <div class="ms-schedule-item">
      <span class="ms-schedule-time">오후 02:00</span>
      <span class="ms-schedule-name">아바타 바카라 오후 방송</span>
      <span class="ms-schedule-status ms-status-soon">예정</span>
    </div>
    <div class="ms-schedule-item">
      <span class="ms-schedule-time">오후 07:00</span>
      <span class="ms-schedule-name">아바타 바카라 저녁 방송</span>
      <span class="ms-schedule-status ms-status-soon">예정</span>
    </div>
    <div class="ms-schedule-item">
      <span class="ms-schedule-time">오후 10:00</span>
      <span class="ms-schedule-name">아바타 바카라 심야 방송</span>
      <span class="ms-schedule-status ms-status-soon">예정</span>
    </div>
  </section>

</main>

<?php require_once dirname(__DIR__, 3) . '/core/helpers/footer.php'; ?>

<script>
/* 모바일 스트리밍 JS
 * ref: https://developer.mozilla.org/en-US/docs/Web/API/HTMLMediaElement
 */
(function () {
  'use strict';
  var placeholder = document.getElementById('ms-placeholder');
  var video       = document.getElementById('ms-video');
  var startBtn    = document.getElementById('ms-start-btn');

  function start_player() {
    if (!placeholder || !video) { return; }
    placeholder.style.display = 'none';
    video.style.display = 'block';
    /* HLS URL — 실제 배포 시 SRS 서버 URL로 교체 */
    var hls_url = 'https://xn--2e0bj1fruw33b6ti.net/live/stream.m3u8';
    if (typeof Hls !== 'undefined' && Hls.isSupported()) {
      var hls = new Hls({ lowLatencyMode: true });
      hls.loadSource(hls_url);
      hls.attachMedia(video);
      hls.on(Hls.Events.MANIFEST_PARSED, function () { video.play(); });
    } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
      video.src = hls_url;
      video.play();
    }
  }

  if (startBtn) {
    startBtn.addEventListener('click', start_player);
    startBtn.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); start_player(); }
    });
  }

  /* 시청자 수 */
  var viewers_el = document.getElementById('ms-viewers');
  var online_el  = document.getElementById('ms-online');
  if (viewers_el) {
    var base = 80 + Math.floor(Math.random() * 60);
    viewers_el.textContent = base;
    if (online_el) { online_el.textContent = Math.floor(base * 0.6); }
    setInterval(function () {
      base += Math.floor(Math.random() * 5) - 2;
      if (base < 30) { base = 30; }
      viewers_el.textContent = base;
      if (online_el) { online_el.textContent = Math.floor(base * 0.6); }
    }, 8000);
  }

  /* 방송 시간 */
  var time_el = document.getElementById('ms-time');
  if (time_el) {
    var start_ts = Date.now();
    setInterval(function () {
      var s = Math.floor((Date.now() - start_ts) / 1000);
      var m = Math.floor(s / 60);
      var h = Math.floor(m / 60);
      time_el.textContent = (h > 0 ? h + '시간 ' : '') + (m % 60) + '분 방송 중';
    }, 1000);
  }

  /* 채팅 */
  var chat_input = document.getElementById('ms-chat-input');
  var chat_send  = document.getElementById('ms-chat-send');
  var chat_msgs  = document.getElementById('ms-chat-msgs');
  var demo_names = ['김철수', '이영희', '박민준', '최지은'];
  var demo_texts = ['좋은 방송!', '대박 🎰', '구찌야놀자 최고', '오늘도 화이팅'];

  function append_msg(name, text, is_sys) {
    if (!chat_msgs) { return; }
    var div = document.createElement('div');
    div.className = 'ms-chat-msg' + (is_sys ? ' ms-chat-sys' : '');
    if (!is_sys) {
      /* XSS 방지 — textContent 사용 */
      var name_span = document.createElement('span');
      name_span.className = 'ms-chat-name';
      name_span.textContent = name;
      var text_span = document.createElement('span');
      text_span.textContent = text;
      div.appendChild(name_span);
      div.appendChild(text_span);
    } else {
      div.textContent = text;
    }
    chat_msgs.appendChild(div);
    chat_msgs.scrollTop = chat_msgs.scrollHeight;
    while (chat_msgs.children.length > 80) { chat_msgs.removeChild(chat_msgs.firstChild); }
  }

  function send_msg() {
    if (!chat_input) { return; }
    var val = chat_input.value.trim();
    if (!val) { return; }
    append_msg('나', val, false);
    chat_input.value = '';
  }

  if (chat_send) { chat_send.addEventListener('click', send_msg); }
  if (chat_input) {
    chat_input.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') { e.preventDefault(); send_msg(); }
    });
  }

  setInterval(function () {
    var n = demo_names[Math.floor(Math.random() * demo_names.length)];
    var t = demo_texts[Math.floor(Math.random() * demo_texts.length)];
    append_msg(n, t, false);
  }, 7000 + Math.random() * 5000);

}());
</script>
<script src="/mobile/assets/js/mobile.js" defer></script>
</body>
</html>

