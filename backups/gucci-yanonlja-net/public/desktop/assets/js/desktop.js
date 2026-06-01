/**
 * 데스크탑 전용 JS — 구찌야놀자
 * ref: https://developer.mozilla.org/en-US/docs/Web/API/KeyboardEvent
 * ref: https://developer.mozilla.org/en-US/docs/Web/API/Fullscreen_API
 */
(function () {
  'use strict';

  /* ── 키보드 단축키
   * ref: https://developer.mozilla.org/en-US/docs/Web/API/KeyboardEvent/key
   */
  document.addEventListener('keydown', function (e) {
    /* Alt+S: 스트리밍 이동 */
    if (e.altKey && e.key === 's') {
      e.preventDefault();
      window.location.href = '/desktop/streaming/';
    }
    /* Alt+G: 게임 이동 */
    if (e.altKey && e.key === 'g') {
      e.preventDefault();
      window.location.href = '/desktop/games/';
    }
    /* Alt+R: 예약 이동 */
    if (e.altKey && e.key === 'r') {
      e.preventDefault();
      window.location.href = '/desktop/reservation/';
    }
    /* Alt+B: 게시판 이동 */
    if (e.altKey && e.key === 'b') {
      e.preventDefault();
      window.location.href = '/desktop/free-board/';
    }
    /* Escape: 모달/드롭다운 닫기 */
    if (e.key === 'Escape') {
      document.querySelectorAll('[aria-expanded="true"]').forEach(function (el) {
        el.setAttribute('aria-expanded', 'false');
      });
    }
  });

  /* ── 전체화면 API
   * ref: https://developer.mozilla.org/en-US/docs/Web/API/Fullscreen_API
   */
  var fullscreen_btn = document.getElementById('d-fullscreen-btn');
  if (fullscreen_btn) {
    fullscreen_btn.addEventListener('click', function () {
      if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen().catch(function (err) {
          console.error('Fullscreen error:', err.message);
        });
      } else {
        document.exitFullscreen();
      }
    });
    document.addEventListener('fullscreenchange', function () {
      var is_full = !!document.fullscreenElement;
      fullscreen_btn.setAttribute('aria-label', is_full ? '전체화면 종료' : '전체화면');
      fullscreen_btn.setAttribute('aria-pressed', is_full ? 'true' : 'false');
    });
  }

  /* ── 호버 인터랙션 강화 */
  document.querySelectorAll('.d-card').forEach(function (card) {
    card.addEventListener('mouseenter', function () {
      card.style.willChange = 'transform';
    });
    card.addEventListener('mouseleave', function () {
      card.style.willChange = '';
    });
  });

}());
