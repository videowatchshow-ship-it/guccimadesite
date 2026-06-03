<?php
/**
 * 공통 헤더 컴포넌트 — 구찌야놀자
 * ref: https://www.w3.org/WAI/ARIA/apg/patterns/disclosure/examples/disclosure-navigation/
 * ref: https://developer.mozilla.org/en-US/docs/Web/Accessibility/ARIA/Roles/banner_role
 * ref: https://developers.google.com/identity/gsi/web/reference/html-reference
 * ref: https://developer.mozilla.org/en-US/docs/Web/CSS/animation
 * 원본 JS: https://github.com/w3c/aria-practices/blob/main/content/patterns/disclosure/examples/js/disclosureMenu.js
 * 라이선스: https://www.w3.org/Consortium/Legal/2015/copyright-software-and-document
 */
?>
<style>
  @font-face {
    font-family: 'SchoolSafetyTteokbokki';
    src: url('https://cdn.jsdelivr.net/gh/projectnoonnu/2510-1@1.0/HakgyoansimTTeokbokkiB.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: swap;
  }

  .gucci-header, .gucci-header * {
    font-family: 'SchoolSafetyTteokbokki', sans-serif;
  }

  /* 접근성: 본문 바로가기
   * ref: https://www.w3.org/WAI/WCAG21/Techniques/general/G1
   */
  .skip-link {
    position: absolute;
    top: -100%;
    left: 1rem;
    background: #f5c842;
    color: #040f1c;
    padding: .5rem 1rem;
    border-radius: 6px;
    font-size: .875rem;
    font-weight: 700;
    z-index: 9999;
    text-decoration: none;
    transition: top .2s ease;
  }
  .skip-link:focus { top: 1rem; }

  /* 헤더 레이아웃
   * ref: https://developer.mozilla.org/en-US/docs/Web/CSS/position
   */
  .gucci-header {
    background:
      linear-gradient(135deg, rgba(245,200,66,0.05) 0%, transparent 50%),
      linear-gradient(225deg, rgba(99,91,255,0.08) 0%, transparent 50%),
      #0a2540;
    border-bottom: 2px solid rgba(245,200,66,0.2);
    position: sticky;
    top: 0;
    z-index: 1000;
    height: 72px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    transition: all 0.3s ease;
  }

  /* CSS content 빈 문자열 — 정상 문법
   * ref: https://developer.mozilla.org/en-US/docs/Web/CSS/content
   */
  .gucci-header::before {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, #f5c842, transparent);
    animation: headerShine 4s ease-in-out infinite;
  }
  @keyframes headerShine {
    0%, 100% { opacity: 0.3; transform: translateX(-100%); }
    50%       { opacity: 1;   transform: translateX(100%); }
  }

  .gucci-header-inner {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 2rem;
    height: 100%;
    display: flex;
    align-items: center;
    position: relative;
  }

  .gucci-logo-container {
    display: flex;
    align-items: center;
    position: absolute;
    left: 2rem;
  }
  .gucci-logo-container a {
    display: flex;
    align-items: center;
    text-decoration: none;
    color: inherit;
  }

  /* 로고 애니메이션
   * ref: https://developer.mozilla.org/en-US/docs/Web/CSS/animation
   */
  .gucci-logo-img {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid rgba(245,200,66,0.3);
    box-shadow: 0 0 20px rgba(245,200,66,0.4), 0 4px 12px rgba(0,0,0,0.3);
    transition: all 0.3s ease;
    animation: logoPulse 3s ease-in-out infinite;
  }
  @keyframes logoPulse {
    0%, 100% { box-shadow: 0 0 20px rgba(245,200,66,0.4), 0 4px 12px rgba(0,0,0,0.3); transform: scale(1); }
    50%       { box-shadow: 0 0 30px rgba(245,200,66,0.6), 0 6px 16px rgba(0,0,0,0.4); transform: scale(1.05); }
  }
  .gucci-logo-img:hover {
    transform: scale(1.1) rotate(5deg);
    box-shadow: 0 0 40px rgba(245,200,66,0.8), 0 8px 20px rgba(0,0,0,0.5);
  }

  /* 네비게이션
   * ref: https://developer.mozilla.org/en-US/docs/Web/HTML/Element/nav
   */
  .gucci-nav {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1.5rem;
    flex: 1;
    width: 100%;
    max-width: 1400px;
    margin: 0 auto;
  }

  .nav-item {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    flex: 1;
    max-width: 180px;
  }

  /* 네비게이션 버튼
   * ref: https://github.com/w3c/aria-practices/blob/main/content/patterns/disclosure/examples/css/disclosure-navigation.css
   */
  .nav-btn {
    align-items: center;
    background: linear-gradient(135deg, rgba(99,91,255,0.1), rgba(99,91,255,0.05));
    border: 2px solid rgba(245,200,66,0.3);
    border-radius: 8px;
    color: #f5c842;
    cursor: pointer;
    display: flex;
    font-family: inherit;
    font-size: 1.125rem;
    font-weight: 600;
    gap: .3rem;
    padding: .75em 1em;
    text-decoration: none;
    white-space: nowrap;
    transition: all 0.3s ease;
    width: 100%;
    height: 48px;
    justify-content: center;
    position: relative;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
  }
  .nav-btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(245,200,66,0.2);
    transform: translate(-50%, -50%);
    transition: width 0.4s, height 0.4s;
  }
  .nav-btn:hover::before { width: 200%; height: 200%; }
  .nav-btn:hover {
    background: linear-gradient(135deg, rgba(99,91,255,0.2), rgba(99,91,255,0.1));
    border-color: rgba(245,200,66,0.6);
    color: #ffd700;
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(245,200,66,0.3), 0 0 20px rgba(245,200,66,0.2);
  }
  .nav-btn:focus-visible { outline: 3px solid #f5c842; outline-offset: 3px; }
  .nav-btn .nav-arrow {
    width: 14px;
    height: 14px;
    transition: transform .3s ease;
    flex-shrink: 0;
    position: relative;
    z-index: 1;
  }
  .nav-btn[aria-expanded="true"] {
    background: linear-gradient(135deg, rgba(245,200,66,0.2), rgba(245,200,66,0.1));
    border-color: rgba(245,200,66,0.8);
    color: #ffd700;
    box-shadow: 0 4px 16px rgba(245,200,66,0.4), 0 0 24px rgba(245,200,66,0.3);
  }
  .nav-btn[aria-expanded="true"] .nav-arrow { transform: rotate(180deg); }

  /* 드롭다운 패널
   * ref: https://github.com/w3c/aria-practices/blob/main/content/patterns/disclosure/examples/css/disclosure-navigation.css
   */
  .nav-dropdown {
    position: absolute;
    top: calc(100% + 8px);
    left: 0;
    min-width: 260px;
    background: linear-gradient(145deg, #0e2d5a, #0a2137);
    border: 2px solid rgba(245,200,66,0.3);
    border-radius: 12px;
    padding: .75rem;
    box-shadow: 0 12px 40px rgba(0,0,0,0.6), 0 0 20px rgba(245,200,66,0.2), inset 0 1px 0 rgba(255,255,255,0.1);
    z-index: 200;
    list-style: none;
    margin: 0;
    animation: dropdownSlide 0.3s ease-out;
  }
  @keyframes dropdownSlide {
    from { opacity: 0; transform: translateY(-10px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  .nav-dd-item {
    display: flex;
    align-items: flex-start;
    gap: .75rem;
    padding: .75rem .875rem;
    border-radius: 8px;
    text-decoration: none;
    color: #c8d8e8;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
  }
  .nav-dd-item::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    width: 3px;
    height: 0;
    background: linear-gradient(180deg, #f5c842, #ffd700);
    transition: height 0.3s ease;
  }
  .nav-dd-item:hover::before { height: 100%; }
  .nav-dd-item:hover {
    background: linear-gradient(90deg, rgba(245,200,66,0.15), rgba(245,200,66,0.05));
    color: #fff;
    transform: translateX(5px);
    box-shadow: 0 2px 8px rgba(245,200,66,0.2);
  }
  .nav-dd-item:focus-visible { outline: 2px solid #f5c842; outline-offset: 2px; }
  .nav-dd-icon {
    width: 44px;
    height: 44px;
    background: linear-gradient(135deg, rgba(245,200,66,0.2), rgba(99,91,255,0.2));
    border: 2px solid rgba(245,200,66,0.3);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 1.3rem;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
  }
  .nav-dd-item:hover .nav-dd-icon {
    background: linear-gradient(135deg, rgba(245,200,66,0.4), rgba(99,91,255,0.3));
    border-color: rgba(245,200,66,0.6);
    transform: scale(1.1) rotate(5deg);
    box-shadow: 0 4px 12px rgba(245,200,66,0.3);
  }
  .nav-dd-label { font-size: 1.05rem; font-weight: 600; color: #fff; display: block; }
  .nav-dd-desc  { font-size: 0.9rem; color: #8898aa; margin-top: 1px; display: block; }

  /* 모바일 햄버거
   * ref: https://developer.mozilla.org/en-US/docs/Web/Accessibility/ARIA/Attributes/aria-expanded
   */
  .nav-mobile-btn {
    display: none;
    background: none;
    border: 1px solid rgba(255,255,255,0.25);
    color: #fff;
    cursor: pointer;
    padding: .5rem;
    min-height: 44px;
    min-width: 44px;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border-radius: 6px;
  }

  /* 반응형
   * ref: https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_media_queries
   */
  @media (max-width: 768px) {
    .gucci-header-inner { display: flex; flex-wrap: wrap; gap: 1rem; }
    .gucci-logo-container { flex: 0 0 auto; }
    .gucci-nav { display: none; }
    .nav-mobile-btn { display: flex; margin-left: auto; }
    .gucci-nav.open {
      display: flex;
      flex-direction: column;
      position: fixed;
      top: 72px;
      left: 0;
      right: 0;
      bottom: 0;
      background: #0a2540;
      padding: 1rem;
      overflow-y: auto;
      z-index: 999;
      gap: .5rem;
    }
    .nav-dropdown { position: static; box-shadow: none; border: none; background: rgba(255,255,255,.04); margin-top: .25rem; }
    .nav-item { height: auto; justify-content: flex-start; width: 100%; max-width: none; flex: none; }
    .nav-btn { width: 100%; justify-content: space-between; }
  }
</style>

<!-- Kick.com 폰트 크기 시스템 (2026-06-03) -->
<link rel="stylesheet" href="/assets/css/kick-typography.css" />

<!-- Kick.com 레이아웃 시스템 (2026-06-03) -->
<link rel="stylesheet" href="/assets/css/kick_layout_system.css" />

<a class="skip-link" href="#main-content">본문으로 바로가기</a>

<!-- header role="banner"
     ref: https://developer.mozilla.org/en-US/docs/Web/Accessibility/ARIA/Roles/banner_role
-->
<header class="gucci-header" role="banner">
  <div class="gucci-header-inner">

    <div class="gucci-logo-container">
      <a href="https://xn--2e0bj1fruw33b6ti.net/" title="구찌야놀자 홈" aria-label="구찌야놀자 홈으로 이동">
        <img
          src="/assets/images/avatar-baccarat-gucci-play.png"
          alt="구찌야놀자 아바타 바카라 로고"
          class="gucci-logo-img"
          width="60" height="60"
          loading="eager"
          fetchpriority="high"
          onerror="this.style.display='none'">
      </a>
    </div>

    <button class="nav-mobile-btn" id="nav-mobile-btn"
      aria-label="메뉴 열기" aria-expanded="false" aria-controls="main-nav">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
        <line x1="3" y1="6" x2="21" y2="6"/>
        <line x1="3" y1="12" x2="21" y2="12"/>
        <line x1="3" y1="18" x2="21" y2="18"/>
      </svg>
    </button>

    <!-- nav aria-label
         ref: https://www.w3.org/WAI/ARIA/apg/patterns/disclosure/examples/disclosure-navigation/
    -->
    <nav class="gucci-nav" id="main-nav" role="navigation" aria-label="메인 내비게이션">

      <!-- 스트리밍 드롭다운 -->
      <div class="nav-item">
        <button class="nav-btn" aria-haspopup="true" aria-expanded="false"
          aria-controls="dd-streaming" id="btn-streaming">
          스트리밍
          <svg class="nav-arrow" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <polyline points="4 6 8 10 12 6"/>
          </svg>
        </button>
        <div class="nav-dropdown" id="dd-streaming" role="region" aria-labelledby="btn-streaming">
          <a class="nav-dd-item" href="/desktop/streaming/">
            <span class="nav-dd-icon" aria-hidden="true">🔴</span>
            <span>
              <span class="nav-dd-label">실시간 LIVE</span>
              <span class="nav-dd-desc">바카라 · 카지노 생중계</span>
            </span>
          </a>
          <a class="nav-dd-item" href="/streaming/">
            <span class="nav-dd-icon" aria-hidden="true">🎬</span>
            <span>
              <span class="nav-dd-label">지난 방송 보기</span>
              <span class="nav-dd-desc">전체화면 · 고화질 재생</span>
            </span>
          </a>
        </div>
      </div>

      <!-- 게임 드롭다운 -->
      <div class="nav-item">
        <button class="nav-btn" aria-haspopup="true" aria-expanded="false"
          aria-controls="dd-games" id="btn-games">
          게임
          <svg class="nav-arrow" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <polyline points="4 6 8 10 12 6"/>
          </svg>
        </button>
        <div class="nav-dropdown" id="dd-games" role="region" aria-labelledby="btn-games">
          <a class="nav-dd-item" href="/games/">
            <span class="nav-dd-icon" aria-hidden="true">🃏</span>
            <span>
              <span class="nav-dd-label">게임 안내</span>
              <span class="nav-dd-desc">바카라 · 룰렛 · 블랙잭</span>
            </span>
          </a>
          <a class="nav-dd-item" href="/reservation/">
            <span class="nav-dd-icon" aria-hidden="true">📅</span>
            <span>
              <span class="nav-dd-label">게임 예약</span>
              <span class="nav-dd-desc">테이블 사전 예약</span>
            </span>
          </a>
        </div>
      </div>

      <!-- 커뮤니티 드롭다운 -->
      <div class="nav-item">
        <button class="nav-btn" aria-haspopup="true" aria-expanded="false"
          aria-controls="dd-community" id="btn-community">
          커뮤니티
          <svg class="nav-arrow" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <polyline points="4 6 8 10 12 6"/>
          </svg>
        </button>
        <div class="nav-dropdown" id="dd-community" role="region" aria-labelledby="btn-community">
          <a class="nav-dd-item" href="/free-board/">
            <span class="nav-dd-icon" aria-hidden="true">💬</span>
            <span>
              <span class="nav-dd-label">자유게시판</span>
              <span class="nav-dd-desc">정보 공유 · 커뮤니티</span>
            </span>
          </a>
          <a class="nav-dd-item" href="/contact/">
            <span class="nav-dd-icon" aria-hidden="true">📞</span>
            <span>
              <span class="nav-dd-label">문의하기</span>
              <span class="nav-dd-desc">텔레그램 · 카카오톡</span>
            </span>
          </a>
        </div>
      </div>

    </nav>
  </div>
</header>

<script>
/**
 * W3C ARIA Practices — Disclosure Navigation Menu
 * 공식 문서: https://www.w3.org/WAI/ARIA/apg/patterns/disclosure/examples/disclosure-navigation/
 * 원본 JS: https://github.com/w3c/aria-practices/blob/main/content/patterns/disclosure/examples/js/disclosureMenu.js
 * 라이선스: https://www.w3.org/Consortium/Legal/2015/copyright-software-and-document
 */
(function () {
  'use strict';

  function openMenu(button, menu) {
    button.setAttribute('aria-expanded', 'true');
    menu.style.display = 'block';
  }

  function closeMenu(button, menu) {
    button.setAttribute('aria-expanded', 'false');
    menu.style.display = 'none';
  }

  function closeAllMenus() {
    document.querySelectorAll('.nav-btn[aria-haspopup]').forEach(function (btn) {
      var menuId = btn.getAttribute('aria-controls');
      var menu   = document.getElementById(menuId);
      if (menu) { closeMenu(btn, menu); }
    });
  }

  var buttons = document.querySelectorAll('.nav-btn[aria-haspopup]');
  buttons.forEach(function (button) {
    var menuId = button.getAttribute('aria-controls');
    var menu   = document.getElementById(menuId);
    if (!menu) { return; }

    closeMenu(button, menu);

    button.addEventListener('click', function (e) {
      var isExpanded = button.getAttribute('aria-expanded') === 'true';
      closeAllMenus();
      if (!isExpanded) { openMenu(button, menu); }
      e.stopPropagation();
    });

    button.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') { closeAllMenus(); button.focus(); }
    });
  });

  document.addEventListener('click', function (e) {
    if (!e.target.closest('.nav-item')) { closeAllMenus(); }
  });

  /* 모바일 햄버거
   * ref: https://developer.mozilla.org/en-US/docs/Web/API/Element/classList
   */
  var mobileBtn = document.getElementById('nav-mobile-btn');
  var mainNav   = document.getElementById('main-nav');
  if (mobileBtn && mainNav) {
    mobileBtn.addEventListener('click', function () {
      var open = mainNav.classList.toggle('open');
      mobileBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
      mobileBtn.setAttribute('aria-label', open ? '메뉴 닫기' : '메뉴 열기');
    });
  }
}());
</script>
