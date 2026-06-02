/**
 * common.js — 구찌야놀자 모바일/데스크탑 공통 JS
 * ref: https://developer.mozilla.org/en-US/docs/Web/JavaScript
 * ref: https://owasp.org/www-community/attacks/xss/ (XSS 방지)
 * UTF-8 without BOM | LF line endings
 * PHPStan level max 기준 — 추측 코딩 금지
 */
(function () {
  'use strict';

  /* ── XSS-safe DOM 접근 유틸
   * ref: https://developer.mozilla.org/en-US/docs/Web/API/Node/textContent
   * innerHTML 사용 금지 — textContent / createElement 만 사용
   */
  var DOM = {
    /**
     * @param {string} id
     * @returns {HTMLElement|null}
     */
    id: function (id) {
      return document.getElementById(id);
    },
    /**
     * @param {string} selector
     * @returns {NodeList}
     */
    all: function (selector) {
      return document.querySelectorAll(selector);
    },
    /**
     * XSS-safe 텍스트 설정
     * @param {HTMLElement} el
     * @param {string} text
     */
    setText: function (el, text) {
      if (el) { el.textContent = String(text); }
    },
    /**
     * XSS-safe 요소 생성
     * @param {string} tag
     * @param {Object} attrs
     * @param {string} [text]
     * @returns {HTMLElement}
     */
    create: function (tag, attrs, text) {
      var el = document.createElement(tag);
      if (attrs) {
        Object.keys(attrs).forEach(function (k) {
          el.setAttribute(k, attrs[k]);
        });
      }
      if (text !== undefined) { el.textContent = text; }
      return el;
    }
  };

  /* ── debounce
   * ref: https://developer.mozilla.org/en-US/docs/Web/API/Window/scroll_event
   * @param {Function} fn
   * @param {number} delay
   * @returns {Function}
   */
  function debounce(fn, delay) {
    var timer = null;
    return function () {
      var ctx  = this;
      var args = arguments;
      clearTimeout(timer);
      timer = setTimeout(function () { fn.apply(ctx, args); }, delay);
    };
  }

  /* ── lazy loading
   * ref: https://developer.mozilla.org/en-US/docs/Web/API/Intersection_Observer_API
   */
  function initLazyLoad() {
    if (!('IntersectionObserver' in window)) { return; }
    var imgs = DOM.all('img[data-src]');
    if (!imgs.length) { return; }
    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) { return; }
        var img = entry.target;
        var src = img.getAttribute('data-src');
        if (src) {
          img.src = src;
          img.removeAttribute('data-src');
        }
        observer.unobserve(img);
      });
    }, { rootMargin: '200px 0px' });
    imgs.forEach(function (img) { observer.observe(img); });
  }

  /* ── 스크롤 핸들러 (debounce 적용)
   * ref: https://developer.mozilla.org/en-US/docs/Web/API/Window/scroll_event
   */
  function initScrollHandler() {
    var header = document.querySelector('.g-nav, .d-nav, .m-nav');
    if (!header) { return; }
    var onScroll = debounce(function () {
      if (window.scrollY > 20) {
        header.style.boxShadow = '0 4px 20px rgba(0,0,0,0.4)';
      } else {
        header.style.boxShadow = 'none';
      }
    }, 50);
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  /* ── 메뉴 active 처리
   * 현재 경로와 일치하는 nav 링크에 active 클래스 추가
   */
  function initActiveNav() {
    var path  = window.location.pathname;
    var links = DOM.all('.g-nav-link, .d-nav-link, .m-nav-link');
    links.forEach(function (link) {
      var href = link.getAttribute('href');
      if (!href) { return; }
      if (path === href || (href !== '/' && path.indexOf(href) === 0)) {
        link.classList.add('active');
        link.setAttribute('aria-current', 'page');
      }
    });
  }

  /* ── 키보드 단축키 (데스크탑 전용)
   * ref: https://developer.mozilla.org/en-US/docs/Web/API/KeyboardEvent
   */
  function initKeyboardShortcuts() {
    var isMobile = /Mobile|Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
    if (isMobile) { return; }
    document.addEventListener('keydown', function (e) {
      if (!e.altKey) { return; }
      if (e.target && (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA')) { return; }
      var shortcuts = {
        's': '/desktop/streaming/',
        'g': '/desktop/games/',
        'r': '/desktop/reservation/',
        'b': '/desktop/free-board/',
        'c': '/desktop/contact/'
      };
      var key = e.key.toLowerCase();
      if (shortcuts[key]) {
        e.preventDefault();
        window.location.href = shortcuts[key];
      }
    });
  }

  /* ── 외부 링크 rel 보안 처리
   * ref: https://developer.mozilla.org/en-US/docs/Web/HTML/Attributes/rel/noopener
   */
  function initExternalLinks() {
    var links = DOM.all('a[target="_blank"]');
    links.forEach(function (link) {
      var rel = link.getAttribute('rel') || '';
      if (rel.indexOf('noopener') === -1) {
        link.setAttribute('rel', (rel + ' noopener noreferrer').trim());
      }
    });
  }

  /* ── 초기화 */
  function init() {
    initLazyLoad();
    initScrollHandler();
    initActiveNav();
    initKeyboardShortcuts();
    initExternalLinks();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  /* 외부 노출 (필요 시 사용) */
  window.GucciCommon = { DOM: DOM, debounce: debounce };

}());
