/*

 * 공통 헤더 동적 로딩 — DOM lifecycle 안전 버전

 * ref: https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API/Using_Fetch

 *

 * 사용법: 각 페이지 <body> 최상단

 *   <div id="site-header"></div>

 *   <script src="/_header_fetch.js"></script>

 *

 * 핵심 변경:

 *   - <style> → <head> 삽입

 *   - HTML → #site-header 삽입 (DOM 먼저)

 *   - script는 HTML 삽입 완료 후 순서대로 실행 (타이밍 보장)

 *   - event delegation으로 DOM 재생성에도 안전

 */

(function () {

    'use strict';



    fetch('/header.php?v=' + Date.now(, {
        credentials: 'same-origin'
    }) + '_' + Math.random().toString(36).substr(2, 9), { cache: 'no-store' })

        .then(function (r) {

            if (!r.ok) throw new Error('header fetch ' + r.status);

            return r.text();

        })

        .then(function (html) {

            var tmp = document.createElement('div');

            tmp.innerHTML = html;



            /* 1. <style> → <head> 삽입 */

            tmp.querySelectorAll('style').forEach(function (s) {

                document.head.appendChild(s.cloneNode(true));

            });

            tmp.querySelectorAll('style').forEach(function (s) { s.remove(); });



            /* 2. <script> 수집 후 제거 */

            var scripts = [];

            tmp.querySelectorAll('script').forEach(function (s) {

                scripts.push({ src: s.src, text: s.textContent });

                s.remove();

            });



            /* 3. HTML → #site-header 삽입 (DOM 먼저 완성) */

            var container = document.getElementById('site-header');

            if (container) {

                container.innerHTML = tmp.innerHTML;

            }



            /* 4. DOM 삽입 완료 후 script 순서대로 실행 */

            function runNext(i) {

                if (i >= scripts.length) {

                    /* 5. 모든 스크립트 완료 후 로그인 상태 확인 */

                    checkLoginAfterLoad();

                    return;

                }

                var s = scripts[i];

                var ns = document.createElement('script');

                if (s.src) {

                    ns.src = s.src;

                    ns.async = false;

                    ns.onload = function () { runNext(i + 1); };

                    ns.onerror = function () { runNext(i + 1); };

                    document.head.appendChild(ns);

                } else {

                    /* inline script — body에 append해야 DOM 접근 가능 */

                    ns.textContent = s.text;

                    document.body.appendChild(ns);

                    runNext(i + 1);

                }

            }

            runNext(0);

        })

        .catch(function (e) {

            console.error('header fetch 실패:', e);

        });



    /* 세션 기반 로그인 상태 확인 */

    function checkLoginAfterLoad() {

        // window.checkSessionOnLoad가 정의되어 있으면 호출

        if (typeof window.checkSessionOnLoad === 'function') {

            window.checkSessionOnLoad();

        } else {

            // 정의되지 않은 경우 직접 확인

            fetch('/auth/google-callback.php', {

                method: 'POST',

                credentials: 'include',

                headers: { 'Content-Type': 'application/json' },

                body: JSON.stringify({ action: 'me' })

            })

                .then(function (r) { return r.json(); })

                .then(function (data) {

                    if (data.ok && data.user) {

                        

                        if (typeof window.showUserInfo === 'function') {

                            window.showUserInfo(data.user);

                        }

                    } else {

                        

                        if (typeof window.hideUserInfo === 'function') {

                            window.hideUserInfo();

                        }

                    }

                })

                .catch(function (e) {

                    console.error('로그인 상태 확인 실패:', e);

                });

        }

    }



    /* event delegation — DOM 재생성에도 안전 */

    document.addEventListener('click', function (e) {

        /* 모바일 햄버거 */

        var mobileBtn = e.target.closest('#nav-mobile-btn');

        if (mobileBtn) {

            var nav = document.getElementById('main-nav');

            if (nav) {

                var open = nav.classList.toggle('open');

                mobileBtn.setAttribute('aria-expanded', open ? 'true' : 'false');

                mobileBtn.setAttribute('aria-label', open ? '메뉴 닫기' : '메뉴 열기');

            }

            e.stopPropagation();

            return;

        }



        /* 드롭다운 버튼 */

        var navBtn = e.target.closest('.nav-btn[aria-haspopup]');

        if (navBtn) {

            var item = navBtn.closest('.nav-item');

            if (item) {

                var isOpen = item.classList.contains('is-open');

                /* 다른 드롭다운 전부 닫기 */

                document.querySelectorAll('.nav-item.is-open').forEach(function (other) {

                    other.classList.remove('is-open');

                    var ob = other.querySelector('.nav-btn[aria-haspopup]');

                    if (ob) ob.setAttribute('aria-expanded', 'false');

                });

                if (!isOpen) {

                    item.classList.add('is-open');

                    navBtn.setAttribute('aria-expanded', 'true');

                }

            }

            e.stopPropagation();

            return;

        }



        /* 외부 클릭 시 드롭다운 닫기 */

        document.querySelectorAll('.nav-item.is-open').forEach(function (item) {

            item.classList.remove('is-open');

            var btn = item.querySelector('.nav-btn[aria-haspopup]');

            if (btn) btn.setAttribute('aria-expanded', 'false');

        });

    });



    /* ESC 키로 드롭다운 닫기 */

    document.addEventListener('keydown', function (e) {

        if (e.key === 'Escape') {

            document.querySelectorAll('.nav-item.is-open').forEach(function (item) {

                item.classList.remove('is-open');

                var btn = item.querySelector('.nav-btn[aria-haspopup]');

                if (btn) btn.setAttribute('aria-expanded', 'false');

            });

        }

    });



}());

