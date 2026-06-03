<?php



/**



 * 데스크탑 스트리밍 페이지 — 구찌야놀자



 * ref: https://schema.org/VideoObject



 * ref: https://developer.mozilla.org/en-US/docs/Web/API/Fullscreen_API



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







$ua = (string)($_SERVER['HTTP_USER_AGENT'] ?? '');



if (preg_match('/Mobile|Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i', $ua)) {



    header('Location: /mobile/streaming/', true, 302);



    exit;



}







$site_url   = 'https://xn--2e0bj1fruw33b6ti.net';



$page_url   = $site_url . '/desktop/streaming/';



$page_title = '실시간 스트리밍 | 아바타 바카라 생방송 — 구찌야놀자';



$page_desc  = '아바타 바카라 캄보디아 현장 생방송. 데스크탑 최적화 실시간 스트리밍. 고화질 · 저지연 · 전체화면 지원.';



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



  <script type="application/ld+json">



  {"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"홈","item":"https://xn--2e0bj1fruw33b6ti.net/"},{"@type":"ListItem","position":2,"name":"스트리밍","item":"https://xn--2e0bj1fruw33b6ti.net/desktop/streaming/"}]}



  </script>



  <script type="application/ld+json">



  {"@context":"https://schema.org","@type":"VideoObject","name":"아바타 바카라 캄보디아 생방송","description":"캄보디아 현장 아바타 바카라 실시간 생방송.","thumbnailUrl":"https://xn--2e0bj1fruw33b6ti.net/assets/images/avatar-baccarat-gucci-play.png","uploadDate":"2026-01-01","contentUrl":"https://xn--2e0bj1fruw33b6ti.net/desktop/streaming/"}



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







  <!-- 페이지 헤더 -->



  <!-- 스트리밍 레이아웃 -->



  <div class="d-inner" style="padding-top:1.5rem;padding-bottom:3rem;">



    <div style="display:grid;grid-template-columns:1fr 360px;gap:1.5rem;">







      <!-- 플레이어 -->



      <div>



        <div style="background:#000;border-radius:12px;overflow:hidden;border:2px solid rgba(245,200,66,.2);position:relative;" role="region" aria-label="스트리밍 플레이어">



          <div style="padding-top:56.25%;"></div>



          <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:1rem;" id="ds-placeholder">



            <div style="position:absolute;top:12px;left:12px;background:rgba(229,62,62,.9);color:#fff;padding:.3rem .7rem;border-radius:4px;font-size:.78rem;font-weight:700;display:flex;align-items:center;gap:.35rem;" aria-label="생방송 중">



              <span style="width:6px;height:6px;background:#fff;border-radius:50%;animation:ds-blink 1.5s ease-in-out infinite;" aria-hidden="true"></span>LIVE



            </div>



            <div id="ds-start-btn" role="button" tabindex="0" aria-label="방송 시작하기"



              style="width:72px;height:72px;background:rgba(245,200,66,.15);border:2px solid rgba(245,200,66,.4);border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:1.75rem;transition:all .3s;">▶</div>



            <p style="font-size:.9rem;color:#8898aa;">클릭하여 생방송 시작</p>



          </div>



          <!-- ref: https://developer.mozilla.org/en-US/docs/Web/HTML/Element/video -->



          <video id="ds-video" controls playsinline



            style="position:absolute;inset:0;width:100%;height:100%;display:none;"



            aria-label="아바타 바카라 생방송 플레이어"



            poster="/assets/images/avatar-baccarat-gucci-play.png">



            <track kind="captions" label="한국어" srclang="ko" default>



          </video>



          <!-- 전체화면 버튼 — ref: https://developer.mozilla.org/en-US/docs/Web/API/Fullscreen_API -->



          <button id="d-fullscreen-btn" aria-label="전체화면" aria-pressed="false"



            style="position:absolute;bottom:12px;right:12px;background:rgba(0,0,0,.6);border:1px solid rgba(255,255,255,.2);border-radius:6px;color:#fff;cursor:pointer;font-size:.875rem;padding:.4rem .75rem;z-index:2;transition:all .2s;">⛶ 전체화면</button>



        </div>







        <!-- 방송 정보 -->



        <div style="display:flex;align-items:center;justify-content:space-between;padding:.875rem 1rem;background:rgba(255,255,255,.03);border:1px solid rgba(245,200,66,.12);border-radius:8px;margin-top:.75rem;flex-wrap:wrap;gap:.5rem;">



          <div>



            <div style="font-size:1rem;font-weight:700;color:#fff;">🎰 아바타 바카라 캄보디아 생방송</div>



            <div style="font-size:.82rem;color:#6b7c93;display:flex;gap:.75rem;margin-top:.2rem;">



              <span>구찌야놀자</span>



              <span id="ds-time">방송 중</span>



            </div>



          </div>



          <div style="color:#f5c842;font-size:.875rem;font-weight:600;" aria-live="polite" aria-label="시청자 수">



            👁 <span id="ds-viewers">--</span>명 시청 중



          </div>



        </div>







        <!-- 탭 -->



        <div style="display:flex;gap:.5rem;margin-top:1.5rem;border-bottom:1px solid rgba(255,255,255,.07);" role="tablist" aria-label="스트리밍 탭">



          <button class="ds-tab active" role="tab" aria-selected="true" aria-controls="ds-tab-schedule" id="ds-btn-schedule" style="background:none;border:none;border-bottom:2px solid #f5c842;color:#f5c842;cursor:pointer;font-family:inherit;font-size:.9rem;font-weight:600;padding:.6rem 1rem;margin-bottom:-1px;transition:all .2s;">방송 일정</button>



          <button class="ds-tab" role="tab" aria-selected="false" aria-controls="ds-tab-info" id="ds-btn-info" style="background:none;border:none;border-bottom:2px solid transparent;color:#6b7c93;cursor:pointer;font-family:inherit;font-size:.9rem;font-weight:600;padding:.6rem 1rem;margin-bottom:-1px;transition:all .2s;">방송 안내</button>



        </div>







        <div id="ds-tab-schedule" role="tabpanel" aria-labelledby="ds-btn-schedule" style="padding:1.25rem 0;display:flex;flex-direction:column;gap:.6rem;">



          <?php



          $schedules = [



              ['time'=>'오전 10:00','name'=>'아바타 바카라 오전 생방송','status'=>'live'],



              ['time'=>'오후 02:00','name'=>'아바타 바카라 오후 생방송','status'=>'soon'],



              ['time'=>'오후 07:00','name'=>'아바타 바카라 저녁 생방송','status'=>'soon'],



              ['time'=>'오후 10:00','name'=>'아바타 바카라 심야 생방송','status'=>'soon'],



          ];



          foreach ($schedules as $s): ?>



          <div style="display:flex;align-items:center;gap:1rem;padding:.75rem 1rem;background:rgba(255,255,255,.03);border:1px solid rgba(245,200,66,.1);border-radius:8px;">



            <span style="font-size:.85rem;color:#f5c842;font-weight:700;min-width:80px;"><?= htmlspecialchars($s['time'], ENT_QUOTES, 'UTF-8') ?></span>



            <span style="font-size:.875rem;color:#c8d8e8;"><?= htmlspecialchars($s['name'], ENT_QUOTES, 'UTF-8') ?></span>



            <span style="margin-left:auto;font-size:.75rem;padding:.2rem .6rem;border-radius:4px;<?= $s['status'] === 'live' ? 'background:rgba(229,62,62,.2);color:#fc8181;' : 'background:rgba(245,200,66,.1);color:#f5c842;' ?>"><?= $s['status'] === 'live' ? 'LIVE' : '예정' ?></span>



          </div>



          <?php endforeach; ?>



        </div>







        <div id="ds-tab-info" role="tabpanel" aria-labelledby="ds-btn-info" style="padding:1.25rem 0;display:none;">



          <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;">



            <?php



            $infos = [



                ['icon'=>'🎬','title'=>'고화질 스트리밍','desc'=>'1080p 고화질로 캄보디아 현장을 생생하게 전달합니다.'],



                ['icon'=>'⚡','title'=>'저지연 연결','desc'=>'3초 이내 저지연으로 실시간 현장감을 제공합니다.'],



                ['icon'=>'⛶','title'=>'전체화면 지원','desc'=>'F키 또는 버튼으로 전체화면 시청이 가능합니다.'],



            ];



            foreach ($infos as $info): ?>



            <div style="background:rgba(255,255,255,.03);border:1px solid rgba(245,200,66,.1);border-radius:10px;padding:1.25rem;">



              <div style="font-size:1.5rem;margin-bottom:.5rem;" aria-hidden="true"><?= htmlspecialchars($info['icon'], ENT_QUOTES, 'UTF-8') ?></div>



              <div style="font-size:.9rem;font-weight:700;color:#f5c842;margin-bottom:.35rem;"><?= htmlspecialchars($info['title'], ENT_QUOTES, 'UTF-8') ?></div>



              <div style="font-size:.82rem;color:#8898aa;line-height:1.6;"><?= htmlspecialchars($info['desc'], ENT_QUOTES, 'UTF-8') ?></div>



            </div>



            <?php endforeach; ?>



          </div>



        </div>



      </div>







      <!-- 채팅 패널 -->



      <aside aria-label="실시간 채팅">



        <div style="background:#071a2e;border:1px solid rgba(245,200,66,.15);border-radius:12px;display:flex;flex-direction:column;height:600px;">



          <div style="padding:.875rem 1rem;border-bottom:1px solid rgba(245,200,66,.12);display:flex;align-items:center;justify-content:space-between;">



            <span style="font-size:.95rem;font-weight:700;color:#f5c842;">💬 실시간 채팅</span>



            <span style="font-size:.78rem;color:#68d391;display:flex;align-items:center;gap:.3rem;">



              <span style="width:6px;height:6px;background:#68d391;border-radius:50%;animation:ds-blink 2s ease-in-out infinite;" aria-hidden="true"></span>



              <span id="ds-online">--</span>명



            </span>



          </div>



          <div id="ds-chat-msgs" style="flex:1;overflow-y:auto;padding:.75rem;display:flex;flex-direction:column;gap:.5rem;scroll-behavior:smooth;" role="log" aria-live="polite" aria-label="채팅 메시지">



            <div style="font-size:.82rem;color:#6b7c93;font-style:italic;">채팅에 오신 것을 환영합니다 🎰</div>



          </div>



          <div style="padding:.75rem;border-top:1px solid rgba(245,200,66,.12);">



            <div style="display:flex;gap:.5rem;">



              <label for="ds-chat-input" style="position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0,0,0,0);">채팅 입력</label>



              <input type="text" id="ds-chat-input"



                style="flex:1;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:6px;color:#c8d8e8;font-family:inherit;font-size:.875rem;padding:.6rem .875rem;transition:border-color .2s;"



                placeholder="메시지를 입력하세요..." maxlength="200" autocomplete="off"



                aria-label="채팅 메시지 입력">



              <button type="button" id="ds-chat-send"



                style="background:linear-gradient(135deg,#f5c842,#e6a800);color:#040f1c;border:none;border-radius:6px;font-family:inherit;font-size:.875rem;font-weight:700;padding:.6rem 1rem;cursor:pointer;min-width:60px;transition:all .2s;"



                aria-label="메시지 전송">전송</button>



            </div>



          </div>



        </div>



      </aside>







    </div>



  </div>







</main>







<?php require_once dirname(__DIR__, 3) . '/core/helpers/footer.php'; ?>







<style>



@keyframes ds-blink{0%,100%{opacity:1}50%{opacity:.3}}



.ds-tab:hover{color:#c8d8e8!important;}



</style>







<script>



/* 데스크탑 스트리밍 JS



 * ref: https://developer.mozilla.org/en-US/docs/Web/API/HTMLMediaElement



 * ref: https://developer.mozilla.org/en-US/docs/Web/API/Fullscreen_API



 */



(function () {



  'use strict';







  /* 탭 전환 */



  var tabs = document.querySelectorAll('.ds-tab');



  tabs.forEach(function (tab) {



    tab.addEventListener('click', function () {



      tabs.forEach(function (t) {



        t.classList.remove('active');



        t.setAttribute('aria-selected', 'false');



        t.style.borderBottomColor = 'transparent';



        t.style.color = '#6b7c93';



      });



      document.querySelectorAll('[id^="ds-tab-"]').forEach(function (c) { c.style.display = 'none'; });



      tab.classList.add('active');



      tab.setAttribute('aria-selected', 'true');



      tab.style.borderBottomColor = '#f5c842';



      tab.style.color = '#f5c842';



      var target = document.getElementById(tab.getAttribute('aria-controls'));



      if (target) { target.style.display = 'flex'; target.style.flexDirection = 'column'; }



    });



  });







  /* 플레이어 시작 */



  var placeholder = document.getElementById('ds-placeholder');



  var video       = document.getElementById('ds-video');



  var start_btn   = document.getElementById('ds-start-btn');







  function start_player() {



    if (!placeholder || !video) { return; }



    placeholder.style.display = 'none';



    video.style.display = 'block';



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







  if (start_btn) {



    start_btn.addEventListener('click', start_player);



    start_btn.addEventListener('keydown', function (e) {



      if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); start_player(); }



    });



    start_btn.addEventListener('mouseenter', function () { start_btn.style.background = 'rgba(245,200,66,.3)'; });



    start_btn.addEventListener('mouseleave', function () { start_btn.style.background = 'rgba(245,200,66,.15)'; });



  }







  /* F키 전체화면 단축키



   * ref: https://developer.mozilla.org/en-US/docs/Web/API/Fullscreen_API



   */



  document.addEventListener('keydown', function (e) {



    if (e.key === 'f' || e.key === 'F') {



      if (document.activeElement && document.activeElement.tagName === 'INPUT') { return; }



      if (!document.fullscreenElement) {



        document.documentElement.requestFullscreen().catch(function () {});



      } else {



        document.exitFullscreen();



      }



    }



  });







  /* 시청자 수 */



  var viewers_el = document.getElementById('ds-viewers');



  var online_el  = document.getElementById('ds-online');



  if (viewers_el) {



    var base = 120 + Math.floor(Math.random() * 80);



    viewers_el.textContent = base;



    if (online_el) { online_el.textContent = Math.floor(base * 0.6); }



    setInterval(function () {



      base += Math.floor(Math.random() * 5) - 2;



      if (base < 50) { base = 50; }



      viewers_el.textContent = base;



      if (online_el) { online_el.textContent = Math.floor(base * 0.6); }



    }, 8000);



  }







  /* 방송 시간 */



  var time_el = document.getElementById('ds-time');



  if (time_el) {



    var start_ts = Date.now();



    setInterval(function () {



      var s = Math.floor((Date.now() - start_ts) / 1000);



      var m = Math.floor(s / 60);



      var h = Math.floor(m / 60);



      time_el.textContent = (h > 0 ? h + '시간 ' : '') + (m % 60) + '분 ' + (s % 60) + '초 방송 중';



    }, 1000);



  }







  /* 채팅 */



  var chat_input = document.getElementById('ds-chat-input');



  var chat_send  = document.getElementById('ds-chat-send');



  var chat_msgs  = document.getElementById('ds-chat-msgs');



  var demo_names = ['김철수', '이영희', '박민준', '최지은', '정우성'];



  var demo_texts = ['좋은 방송 감사합니다!', '오늘도 대박 나세요 🎰', '바카라 최고!', '구찌야놀자 최고', '실시간 너무 좋아요'];







  function append_msg(name, text, is_sys) {



    if (!chat_msgs) { return; }



    var div = document.createElement('div');



    div.style.cssText = 'font-size:.85rem;line-height:1.5;';



    if (!is_sys) {



      var name_span = document.createElement('span');



      name_span.style.cssText = 'font-weight:700;color:#f5c842;margin-right:.35rem;';



      name_span.textContent = name;



      var text_span = document.createElement('span');



      text_span.style.color = '#c8d8e8';



      text_span.textContent = text;



      div.appendChild(name_span);



      div.appendChild(text_span);



    } else {



      div.style.cssText += 'color:#6b7c93;font-style:italic;font-size:.8rem;';



      div.textContent = text;



    }



    chat_msgs.appendChild(div);



    chat_msgs.scrollTop = chat_msgs.scrollHeight;



    while (chat_msgs.children.length > 100) { chat_msgs.removeChild(chat_msgs.firstChild); }



  }







  function send_msg() {



    if (!chat_input) { return; }



    var val = chat_input.value.trim();



    if (!val) { return; }



    append_msg('나', val, false);



    chat_input.value = '';



    chat_input.focus();



  }







  if (chat_send) { chat_send.addEventListener('click', send_msg); }



  if (chat_input) {



    chat_input.addEventListener('keydown', function (e) {



      if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); send_msg(); }



    });



    chat_input.addEventListener('focus', function () { chat_input.style.borderColor = 'rgba(245,200,66,.5)'; });



    chat_input.addEventListener('blur',  function () { chat_input.style.borderColor = 'rgba(255,255,255,.1)'; });



  }







  setInterval(function () {



    var n = demo_names[Math.floor(Math.random() * demo_names.length)];



    var t = demo_texts[Math.floor(Math.random() * demo_texts.length)];



    append_msg(n, t, false);



  }, 6000 + Math.random() * 4000);







}());



</script>



<script src="/desktop/assets/js/desktop.js" defer></script>



</body>



</html>







