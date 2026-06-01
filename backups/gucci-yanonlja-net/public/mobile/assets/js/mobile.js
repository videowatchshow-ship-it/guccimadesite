/**
 * 모바일 전용 JS — 구찌야놀자
 * ref: https://developer.mozilla.org/en-US/docs/Web/API/Touch_events
 * ref: https://developer.mozilla.org/en-US/docs/Web/API/VisibilityChange_event
 * ref: https://developer.mozilla.org/en-US/docs/Web/API/Navigator/onLine
 */
(function () {
  'use strict';

  /* ── 오프라인 감지
   * ref: https://developer.mozilla.org/en-US/docs/Web/API/Navigator/onLine
   */
  function on_offline() {
    var banner = document.getElementById('offline-banner');
    if (!banner) {
      banner = document.createElement('div');
      banner.id = 'offline-banner';
      banner.setAttribute('role', 'alert');
      banner.setAttribute('aria-live', 'assertive');
      banner.style.cssText = 'position:fixed;top:0;left:0;right:0;background:#e53e3e;color:#fff;text-align:center;padding:.5rem;font-size:.875rem;z-index:9999;';
      banner.textContent = '⚠️ 인터넷 연결이 끊겼습니다.';
      document.body.prepend(banner);
    }
    banner.style.display = 'block';
  }

  function on_online() {
    var banner = document.getElementById('offline-banner');
    if (banner) { banner.style.display = 'none'; }
  }

  window.addEventListener('offline', on_offline);
  window.addEventListener('online', on_online);
  if (!navigator.onLine) { on_offline(); }

  /* ── 터치 피드백 (active 상태 강화)
   * ref: https://developer.mozilla.org/en-US/docs/Web/API/Touch_events
   */
  document.querySelectorAll('.m-menu-item, .m-btn, .m-contact-btn').forEach(function (el) {
    el.addEventListener('touchstart', function () {
      el.style.opacity = '0.75';
    }, { passive: true });
    el.addEventListener('touchend', function () {
      el.style.opacity = '';
    }, { passive: true });
  });

  /* ── 페이지 가시성 변경 시 재연결 준비
   * ref: https://developer.mozilla.org/en-US/docs/Web/API/Document/visibilitychange_event
   */
  document.addEventListener('visibilitychange', function () {
    if (document.visibilityState === 'visible' && !navigator.onLine) {
      on_offline();
    }
  });

}());
