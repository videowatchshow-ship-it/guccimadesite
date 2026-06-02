<?php
/**
 * 관리자 대시보드 — 구찌야놀자
 * ref: https://www.php.net/manual/en/function.session-start.php
 * ref: https://owasp.org/www-project-top-ten/
 * ref: https://developer.mozilla.org/en-US/docs/Web/HTML/Element/main
 */
declare(strict_types=1);

/* ── 세션 시작 (헤더 전송 전)
 * ref: https://www.php.net/manual/en/function.session-start.php
 */
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/admin/',
        'domain'   => '',
        'secure'   => true,
        'httponly' => true,
        'samesite' => 'Strict',
    ]);
    session_start();
}

/* ── 보안 헤더
 * ref: https://owasp.org/www-project-secure-headers/
 */
if (!headers_sent()) {
    header_remove('X-Powered-By');
    header('X-Frame-Options: DENY');
    header('X-Content-Type-Options: nosniff');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; font-src 'self' data: https://cdn.jsdelivr.net; img-src 'self' data:; connect-src 'self'; frame-ancestors 'none';");
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Pragma: no-cache');
    $https = $_SERVER['HTTPS'] ?? '';
    if ($https !== '' && $https !== 'off') {
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
    }
}

/* ── 관리자 인증 확인
 * 미인증 시 홈으로 리다이렉트 (302)
 * ref: https://www.php.net/manual/en/function.header.php
 */
$is_admin = !empty($_SESSION['gucci_user'])
    && !empty($_SESSION['gucci_user']['is_admin'])
    && (int)$_SESSION['gucci_user']['is_admin'] === 1;

if (!$is_admin) {
    header('Location: /', true, 302);
    exit;
}

/* ── CSRF 토큰 생성
 * ref: https://owasp.org/www-community/attacks/csrf
 */
if (empty($_SESSION['admin_csrf'])) {
    $_SESSION['admin_csrf'] = bin2hex(random_bytes(32));
}

$admin_name = htmlspecialchars(
    (string)($_SESSION['gucci_user']['name'] ?? '관리자'),
    ENT_QUOTES,
    'UTF-8'
);
$admin_email = htmlspecialchars(
    (string)($_SESSION['gucci_user']['email'] ?? ''),
    ENT_QUOTES,
    'UTF-8'
);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>관리자 대시보드 — 구찌야놀자</title>
  <!-- 관리자 페이지 색인 제외 — ref: https://developers.google.com/search/docs/crawling-indexing/block-indexing -->
  <meta name="robots" content="noindex, nofollow">
  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
  <link rel="preload" href="/assets/css/mobile-responsive.css" as="style">
  <link rel="stylesheet" href="/assets/css/mobile-responsive.css">
  <style>
    @font-face{font-family:'SchoolSafetyTteokbokki';src:url('https://cdn.jsdelivr.net/gh/projectnoonnu/2510-1@1.0/HakgyoansimTTeokbokkiB.woff2') format('woff2');font-weight:normal;font-style:normal;font-display:swap;}
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
    body{font-family:'SchoolSafetyTteokbokki',sans-serif;background:#040f1c;color:#c8d8e8;min-height:100vh;display:flex;flex-direction:column;}
    /* ── 레이아웃 */
    .adm-wrap{display:flex;min-height:100vh;}
    /* ── 사이드바 */
    .adm-sidebar{width:240px;background:#071a2e;border-right:1px solid rgba(245,200,66,.15);display:flex;flex-direction:column;flex-shrink:0;}
    .adm-logo{padding:1.5rem 1.25rem;border-bottom:1px solid rgba(245,200,66,.12);display:flex;align-items:center;gap:.75rem;}
    .adm-logo-text{font-size:1rem;font-weight:700;color:#f5c842;}
    .adm-logo-badge{font-size:.65rem;background:rgba(229,62,62,.2);color:#fc8181;padding:.15rem .45rem;border-radius:4px;font-weight:700;}
    .adm-nav{padding:.75rem 0;flex:1;}
    .adm-nav-section{padding:.5rem 1.25rem .25rem;font-size:.7rem;font-weight:700;color:#4a5568;letter-spacing:.08em;text-transform:uppercase;}
    .adm-nav-item{display:flex;align-items:center;gap:.75rem;padding:.65rem 1.25rem;color:#8898aa;text-decoration:none;font-size:.875rem;transition:all .2s;border-left:3px solid transparent;}
    .adm-nav-item:hover{background:rgba(245,200,66,.05);color:#c8d8e8;border-left-color:rgba(245,200,66,.3);}
    .adm-nav-item.active{background:rgba(245,200,66,.08);color:#f5c842;border-left-color:#f5c842;}
    .adm-nav-item:focus-visible{outline:2px solid #f5c842;outline-offset:-2px;}
    .adm-nav-icon{font-size:1rem;width:20px;text-align:center;flex-shrink:0;}
    .adm-user{padding:1rem 1.25rem;border-top:1px solid rgba(245,200,66,.12);}
    .adm-user-name{font-size:.875rem;font-weight:700;color:#c8d8e8;margin-bottom:.15rem;}
    .adm-user-email{font-size:.75rem;color:#6b7c93;}
    .adm-logout{display:block;margin-top:.75rem;padding:.5rem .875rem;background:rgba(229,62,62,.1);border:1px solid rgba(229,62,62,.2);border-radius:6px;color:#fc8181;font-family:inherit;font-size:.8rem;font-weight:600;cursor:pointer;text-align:center;text-decoration:none;transition:all .2s;}
    .adm-logout:hover{background:rgba(229,62,62,.2);}
    /* ── 메인 콘텐츠 */
    .adm-main{flex:1;display:flex;flex-direction:column;overflow:hidden;}
    .adm-topbar{background:#071a2e;border-bottom:1px solid rgba(245,200,66,.12);padding:.875rem 1.5rem;display:flex;align-items:center;justify-content:space-between;}
    .adm-topbar-title{font-size:1rem;font-weight:700;color:#fff;}
    .adm-topbar-time{font-size:.8rem;color:#6b7c93;}
    .adm-content{padding:1.5rem;flex:1;overflow-y:auto;}
    /* ── 통계 카드 */
    .adm-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem;}
    .adm-stat-card{background:#071a2e;border:1px solid rgba(245,200,66,.12);border-radius:12px;padding:1.25rem;}
    .adm-stat-label{font-size:.78rem;color:#6b7c93;margin-bottom:.4rem;}
    .adm-stat-value{font-size:1.5rem;font-weight:700;color:#f5c842;}
    .adm-stat-sub{font-size:.72rem;color:#4a5568;margin-top:.2rem;}
    /* ── 섹션 카드 */
    .adm-card{background:#071a2e;border:1px solid rgba(245,200,66,.12);border-radius:12px;padding:1.25rem;margin-bottom:1rem;}
    .adm-card-title{font-size:.9rem;font-weight:700;color:#f5c842;margin-bottom:1rem;padding-bottom:.6rem;border-bottom:1px solid rgba(245,200,66,.1);}
    /* ── 상태 뱃지 */
    .adm-badge{display:inline-flex;align-items:center;gap:.3rem;font-size:.72rem;font-weight:700;padding:.2rem .55rem;border-radius:4px;}
    .adm-badge-ok{background:rgba(72,187,120,.15);color:#68d391;}
    .adm-badge-warn{background:rgba(245,200,66,.12);color:#f5c842;}
    .adm-badge-err{background:rgba(229,62,62,.15);color:#fc8181;}
    .adm-badge-dot{width:5px;height:5px;border-radius:50%;background:currentColor;}
    /* ── 시스템 상태 목록 */
    .adm-sys-list{display:flex;flex-direction:column;gap:.6rem;}
    .adm-sys-item{display:flex;align-items:center;justify-content:space-between;padding:.6rem .875rem;background:rgba(255,255,255,.02);border-radius:8px;}
    .adm-sys-name{font-size:.85rem;color:#c8d8e8;}
    /* ── 스트림 키 */
    .adm-key-wrap{display:flex;gap:.5rem;align-items:center;}
    .adm-key-input{flex:1;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:6px;color:#c8d8e8;font-family:monospace;font-size:.82rem;padding:.55rem .875rem;}
    .adm-key-btn{background:rgba(245,200,66,.12);border:1px solid rgba(245,200,66,.25);border-radius:6px;color:#f5c842;cursor:pointer;font-family:inherit;font-size:.78rem;font-weight:700;padding:.55rem .875rem;transition:all .2s;white-space:nowrap;}
    .adm-key-btn:hover{background:rgba(245,200,66,.22);}
    /* ── 로그 */
    .adm-log{background:rgba(0,0,0,.3);border:1px solid rgba(255,255,255,.06);border-radius:8px;font-family:monospace;font-size:.78rem;color:#68d391;padding:.875rem;max-height:200px;overflow-y:auto;line-height:1.7;}
    /* ── 반응형 */
    @media(max-width:900px){.adm-sidebar{display:none;}.adm-stats{grid-template-columns:repeat(2,1fr);}}
  </style>
</head>
<body>
<a style="position:absolute;top:-100%;left:1rem;background:#f5c842;color:#040f1c;padding:.5rem 1rem;border-radius:6px;font-size:.875rem;font-weight:700;z-index:9999;text-decoration:none;transition:top .2s;" href="#main-content" onfocus="this.style.top='1rem'" onblur="this.style.top='-100%'">본문으로 바로가기</a>

<div class="adm-wrap">

  <!-- 사이드바 -->
  <aside class="adm-sidebar" aria-label="관리자 사이드바">
    <div class="adm-logo">
      <span style="font-size:1.5rem;" aria-hidden="true">🎰</span>
      <div>
        <div class="adm-logo-text">구찌야놀자</div>
        <span class="adm-badge-err adm-badge" style="font-size:.62rem;margin-top:.15rem;">ADMIN</span>
      </div>
    </div>

    <nav class="adm-nav" aria-label="관리자 메뉴">
      <div class="adm-nav-section">대시보드</div>
      <a href="/admin/dashboard/" class="adm-nav-item active" aria-current="page">
        <span class="adm-nav-icon" aria-hidden="true">📊</span>개요
      </a>

      <div class="adm-nav-section">스트리밍</div>
      <a href="#stream-key" class="adm-nav-item">
        <span class="adm-nav-icon" aria-hidden="true">🔑</span>스트림 키
      </a>
      <a href="#stream-status" class="adm-nav-item">
        <span class="adm-nav-icon" aria-hidden="true">📡</span>방송 상태
      </a>

      <div class="adm-nav-section">시스템</div>
      <a href="#system-status" class="adm-nav-item">
        <span class="adm-nav-icon" aria-hidden="true">🖥️</span>서버 상태
      </a>
      <a href="#audit-log" class="adm-nav-item">
        <span class="adm-nav-icon" aria-hidden="true">📋</span>감사 로그
      </a>

      <div class="adm-nav-section">사이트</div>
      <a href="/desktop/" class="adm-nav-item" target="_blank" rel="noopener" aria-label="사이트 보기 (새 탭에서 열림)">
        <span class="adm-nav-icon" aria-hidden="true">🌐</span>사이트 보기
      </a>
    </nav>

    <div class="adm-user">
      <div class="adm-user-name"><?= $admin_name ?></div>
      <div class="adm-user-email"><?= $admin_email ?></div>
      <a href="/core/auth/google-auth-api.php" id="adm-logout-btn" class="adm-logout"
         aria-label="로그아웃">로그아웃</a>
    </div>
  </aside>

  <!-- 메인 -->
  <div class="adm-main">
    <div class="adm-topbar">
      <h1 class="adm-topbar-title">관리자 대시보드</h1>
      <span class="adm-topbar-time" id="adm-clock" aria-live="polite" aria-label="현재 시간"></span>
    </div>

    <main id="main-content" class="adm-content" role="main">

      <!-- 통계 카드 -->
      <section aria-labelledby="adm-stats-title">
        <h2 id="adm-stats-title" style="position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0,0,0,0);">통계 요약</h2>
        <div class="adm-stats">
          <div class="adm-stat-card">
            <div class="adm-stat-label">현재 시청자</div>
            <div class="adm-stat-value" id="adm-viewers">--</div>
            <div class="adm-stat-sub">실시간 업데이트</div>
          </div>
          <div class="adm-stat-card">
            <div class="adm-stat-label">방송 상태</div>
            <div class="adm-stat-value" style="font-size:1rem;margin-top:.25rem;">
              <span class="adm-badge adm-badge-ok" id="adm-stream-status">
                <span class="adm-badge-dot" aria-hidden="true"></span>LIVE
              </span>
            </div>
            <div class="adm-stat-sub" id="adm-stream-time">방송 중</div>
          </div>
          <div class="adm-stat-card">
            <div class="adm-stat-label">오늘 접속자</div>
            <div class="adm-stat-value" id="adm-today-visitors">--</div>
            <div class="adm-stat-sub">UV 기준</div>
          </div>
          <div class="adm-stat-card">
            <div class="adm-stat-label">서버 상태</div>
            <div class="adm-stat-value" style="font-size:1rem;margin-top:.25rem;">
              <span class="adm-badge adm-badge-ok">
                <span class="adm-badge-dot" aria-hidden="true"></span>정상
              </span>
            </div>
            <div class="adm-stat-sub">VPS 76.13.218.129</div>
          </div>
        </div>
      </section>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">

        <!-- 스트림 키 관리 -->
        <section id="stream-key" class="adm-card" aria-labelledby="adm-key-title">
          <h2 id="adm-key-title" class="adm-card-title">🔑 스트림 키 관리</h2>
          <div style="margin-bottom:1rem;">
            <div style="font-size:.78rem;color:#6b7c93;margin-bottom:.4rem;">OBS / PRISM 스트림 키</div>
            <div class="adm-key-wrap">
              <input type="password" id="adm-stream-key" class="adm-key-input"
                value="sk_live_gucci2026_<?= substr(bin2hex(random_bytes(8)), 0, 16) ?>"
                readonly aria-label="스트림 키">
              <button type="button" class="adm-key-btn" id="adm-key-toggle" aria-label="스트림 키 표시/숨김">표시</button>
              <button type="button" class="adm-key-btn" id="adm-key-copy" aria-label="스트림 키 복사">복사</button>
            </div>
          </div>
          <div style="font-size:.78rem;color:#6b7c93;margin-bottom:.4rem;">RTMP 서버 URL</div>
          <div class="adm-key-wrap">
            <input type="text" class="adm-key-input"
              value="rtmp://xn--2e0bj1fruw33b6ti.net/live"
              readonly aria-label="RTMP 서버 URL">
            <button type="button" class="adm-key-btn" id="adm-rtmp-copy" aria-label="RTMP URL 복사">복사</button>
          </div>
          <div style="margin-top:1rem;display:flex;gap:.5rem;">
            <button type="button" class="adm-key-btn" style="background:rgba(72,187,120,.12);border-color:rgba(72,187,120,.25);color:#68d391;" id="adm-stream-start" aria-label="방송 시작">▶ 방송 시작</button>
            <button type="button" class="adm-key-btn" style="background:rgba(229,62,62,.12);border-color:rgba(229,62,62,.25);color:#fc8181;" id="adm-stream-stop" aria-label="방송 종료">■ 방송 종료</button>
            <button type="button" class="adm-key-btn" id="adm-key-regen" aria-label="스트림 키 재생성">🔄 키 재생성</button>
          </div>
        </section>

        <!-- 시스템 상태 -->
        <section id="system-status" class="adm-card" aria-labelledby="adm-sys-title">
          <h2 id="adm-sys-title" class="adm-card-title">🖥️ 시스템 상태</h2>
          <div class="adm-sys-list">
            <div class="adm-sys-item">
              <span class="adm-sys-name">nginx</span>
              <span class="adm-badge adm-badge-ok"><span class="adm-badge-dot" aria-hidden="true"></span>정상</span>
            </div>
            <div class="adm-sys-item">
              <span class="adm-sys-name">MariaDB</span>
              <span class="adm-badge adm-badge-ok"><span class="adm-badge-dot" aria-hidden="true"></span>정상</span>
            </div>
            <div class="adm-sys-item">
              <span class="adm-sys-name">Redis</span>
              <span class="adm-badge adm-badge-ok"><span class="adm-badge-dot" aria-hidden="true"></span>정상</span>
            </div>
            <div class="adm-sys-item">
              <span class="adm-sys-name">WebSocket</span>
              <span class="adm-badge adm-badge-ok"><span class="adm-badge-dot" aria-hidden="true"></span>정상</span>
            </div>
            <div class="adm-sys-item">
              <span class="adm-sys-name">SRS 스트리밍</span>
              <span class="adm-badge adm-badge-ok"><span class="adm-badge-dot" aria-hidden="true"></span>정상</span>
            </div>
            <div class="adm-sys-item">
              <span class="adm-sys-name">Cloudflare</span>
              <span class="adm-badge adm-badge-ok"><span class="adm-badge-dot" aria-hidden="true"></span>활성화</span>
            </div>
          </div>
        </section>

      </div>

      <!-- 감사 로그 -->
      <section id="audit-log" class="adm-card" aria-labelledby="adm-log-title">
        <h2 id="adm-log-title" class="adm-card-title">📋 감사 로그</h2>
        <div class="adm-log" id="adm-log-output" role="log" aria-live="polite" aria-label="감사 로그">
          <div>[<?= date('Y-m-d H:i:s') ?>] 관리자 로그인: <?= $admin_email ?></div>
          <div>[<?= date('Y-m-d H:i:s') ?>] 대시보드 접근</div>
        </div>
      </section>

    </main>
  </div>

</div>

<script>
/* 관리자 대시보드 JS
 * ref: https://developer.mozilla.org/en-US/docs/Web/API/Clipboard/writeText
 * ref: https://developer.mozilla.org/en-US/docs/Web/API/setInterval
 */
(function () {
  'use strict';

  /* ── 시계 */
  var clock = document.getElementById('adm-clock');
  function update_clock() {
    if (!clock) { return; }
    clock.textContent = new Date().toLocaleString('ko-KR', {
      year: 'numeric', month: '2-digit', day: '2-digit',
      hour: '2-digit', minute: '2-digit', second: '2-digit'
    });
  }
  update_clock();
  setInterval(update_clock, 1000);

  /* ── 시청자 수 시뮬레이션 */
  var viewers_el = document.getElementById('adm-viewers');
  var today_el   = document.getElementById('adm-today-visitors');
  if (viewers_el) {
    var v = 120 + Math.floor(Math.random() * 80);
    viewers_el.textContent = v;
    if (today_el) { today_el.textContent = 1240 + Math.floor(Math.random() * 200); }
    setInterval(function () {
      v += Math.floor(Math.random() * 5) - 2;
      if (v < 30) { v = 30; }
      viewers_el.textContent = v;
    }, 8000);
  }

  /* ── 방송 시간 */
  var stream_time_el = document.getElementById('adm-stream-time');
  if (stream_time_el) {
    var start_ts = Date.now();
    setInterval(function () {
      var s = Math.floor((Date.now() - start_ts) / 1000);
      var h = Math.floor(s / 3600);
      var m = Math.floor((s % 3600) / 60);
      stream_time_el.textContent = (h > 0 ? h + '시간 ' : '') + m + '분 방송 중';
    }, 1000);
  }

  /* ── 스트림 키 표시/숨김 */
  var key_input  = document.getElementById('adm-stream-key');
  var key_toggle = document.getElementById('adm-key-toggle');
  if (key_input && key_toggle) {
    key_toggle.addEventListener('click', function () {
      var is_hidden = key_input.type === 'password';
      key_input.type = is_hidden ? 'text' : 'password';
      key_toggle.textContent = is_hidden ? '숨김' : '표시';
    });
  }

  /* ── 클립보드 복사
   * ref: https://developer.mozilla.org/en-US/docs/Web/API/Clipboard/writeText
   */
  function copy_to_clipboard(text, btn_el) {
    if (!navigator.clipboard) {
      /* fallback */
      var ta = document.createElement('textarea');
      ta.value = text;
      ta.style.cssText = 'position:fixed;top:-9999px;';
      document.body.appendChild(ta);
      ta.select();
      document.execCommand('copy');
      document.body.removeChild(ta);
    } else {
      navigator.clipboard.writeText(text).catch(function () {});
    }
    if (btn_el) {
      var orig = btn_el.textContent;
      btn_el.textContent = '✓ 복사됨';
      setTimeout(function () { btn_el.textContent = orig; }, 1500);
    }
  }

  var key_copy  = document.getElementById('adm-key-copy');
  var rtmp_copy = document.getElementById('adm-rtmp-copy');
  if (key_copy && key_input) {
    key_copy.addEventListener('click', function () { copy_to_clipboard(key_input.value, key_copy); });
  }
  var rtmp_input = document.querySelector('input[aria-label="RTMP 서버 URL"]');
  if (rtmp_copy && rtmp_input) {
    rtmp_copy.addEventListener('click', function () { copy_to_clipboard(rtmp_input.value, rtmp_copy); });
  }

  /* ── 방송 시작/종료 */
  var log_el     = document.getElementById('adm-log-output');
  var status_el  = document.getElementById('adm-stream-status');
  var start_btn  = document.getElementById('adm-stream-start');
  var stop_btn   = document.getElementById('adm-stream-stop');

  function append_log(msg) {
    if (!log_el) { return; }
    var div = document.createElement('div');
    div.textContent = '[' + new Date().toLocaleString('ko-KR') + '] ' + msg;
    log_el.appendChild(div);
    log_el.scrollTop = log_el.scrollHeight;
  }

  if (start_btn) {
    start_btn.addEventListener('click', function () {
      if (status_el) {
        status_el.className = 'adm-badge adm-badge-ok';
        status_el.innerHTML = '<span class="adm-badge-dot" aria-hidden="true"></span>LIVE';
      }
      append_log('방송 시작 명령 전송');
    });
  }
  if (stop_btn) {
    stop_btn.addEventListener('click', function () {
      if (status_el) {
        status_el.className = 'adm-badge adm-badge-err';
        status_el.innerHTML = '<span class="adm-badge-dot" aria-hidden="true"></span>오프라인';
      }
      append_log('방송 종료 명령 전송');
    });
  }

  /* ── 스트림 키 재생성 */
  var regen_btn = document.getElementById('adm-key-regen');
  if (regen_btn && key_input) {
    regen_btn.addEventListener('click', function () {
      if (!confirm('스트림 키를 재생성하면 기존 OBS/PRISM 설정을 업데이트해야 합니다. 계속하시겠습니까?')) { return; }
      /* 실제 배포 시 API 호출로 교체 */
      var chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
      var new_key = 'sk_live_gucci2026_';
      for (var i = 0; i < 16; i++) {
        new_key += chars[Math.floor(Math.random() * chars.length)];
      }
      key_input.value = new_key;
      append_log('스트림 키 재생성 완료');
    });
  }

  /* ── 로그아웃 */
  var logout_btn = document.getElementById('adm-logout-btn');
  if (logout_btn) {
    logout_btn.addEventListener('click', function (e) {
      e.preventDefault();
      fetch('/core/auth/google-auth-api.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'same-origin',
        body: JSON.stringify({ action: 'logout' })
      }).then(function () {
        window.location.href = '/';
      }).catch(function () {
        window.location.href = '/';
      });
    });
  }

  /* ── 사이드바 nav 활성화 */
  document.querySelectorAll('.adm-nav-item[href^="#"]').forEach(function (link) {
    link.addEventListener('click', function () {
      document.querySelectorAll('.adm-nav-item').forEach(function (l) { l.classList.remove('active'); l.removeAttribute('aria-current'); });
      link.classList.add('active');
      link.setAttribute('aria-current', 'page');
    });
  });

}());
</script>
</body>
</html>
