/**
 * 앱 초기화 모듈 — 모든 모듈 통합 및 초기화 순서 보장
 *
 * 초기화 순서 (순서 중요):
 *   1. fetchStreamKey()  — stream-key-api.php 호출 → HLS_URL 동적 설정
 *   2. ChatManager.initialize() — DOM 이벤트 바인딩
 *   3. WebSocketClient.connect() — WebSocket 연결
 *   4. VideoPlayer.initialize()  — HLS_URL 확정 후 플레이어 초기화
 *
 * 공식 문서:
 *   MDN Fetch API:
 *     https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API
 *   MDN Promise:
 *     https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Promise
 *   MDN DOMContentLoaded:
 *     https://developer.mozilla.org/en-US/docs/Web/API/Document/DOMContentLoaded_event
 *   Video.js README:
 *     https://github.com/videojs/video.js/blob/main/README.md
 *   HLS.js README:
 *     https://github.com/video-dev/hls.js/blob/master/README.md
 */
window.StreamingApp = (function () {
    'use strict';

    var isInitialized = false;
    var initializationPromise = null;

    // ── 의존성 확인 (모듈만 — 라이브러리는 waitForLibraries에서 별도 처리)
    function checkDependencies() {
        var required = ['StreamingConfig', 'WebSocketClient', 'VideoPlayer', 'ChatManager'];
        var missing = required.filter(function (dep) { return !window[dep]; });

        if (missing.length > 0) {
            throw new Error('필수 모듈 미로드: ' + missing.join(', '));
        }
        
    }

    /**
     * Video.js / HLS.js CDN 로드 완료 대기 (폴링)
     * CDN 응답 지연 시 즉시 실패하지 않고 최대 10초 대기
     *
     * ref: https://github.com/videojs/video.js/blob/main/README.md
     * ref: https://github.com/video-dev/hls.js/blob/master/README.md
     */
    function waitForLibraries(callback, maxWaitMs) {
        var waited = 0;
        var interval = 100;
        maxWaitMs = maxWaitMs || 10000; // 최대 10초

        function check() {
            var vjsReady = (typeof videojs !== 'undefined');
            var hlsReady = (typeof Hls !== 'undefined');

            if (vjsReady && hlsReady) {
                // Both libraries loaded successfully
                // Source: https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API
                // Version: Latest (2026-04), Date: 2026-04-22
                callback(null);
                return;
            }

            waited += interval;
            if (waited >= maxWaitMs) {
                var missing = [];
                if (!vjsReady) missing.push('Video.js');
                if (!hlsReady) missing.push('HLS.js');
                callback(new Error(missing.join(', ') + ' 라이브러리 미로드 (타임아웃 ' + maxWaitMs + 'ms)'));
                return;
            }

            setTimeout(check, interval);
        }

        check();
    }

    // ── 오류 표시
    function showError(message) {
        var div = document.createElement('div');
        div.style.cssText = [
            'position:fixed', 'top:50%', 'left:50%',
            'transform:translate(-50%,-50%)',
            'background:#c62828', 'color:#fff',
            'padding:24px 32px', 'border-radius:8px',
            'z-index:10000', 'text-align:center',
            'font-family:inherit', 'max-width:400px'
        ].join(';');
        div.innerHTML =
            '<h3 style="margin-bottom:10px;">⚠️ 초기화 오류</h3>' +
            '<p style="font-size:.9rem;margin-bottom:16px;">' + message + '</p>' +
            '<button onclick="location.reload()" style=background:#fff;color:#c62828;border:none;' +
            'padding:8px 20px;border-radius:4px;cursor:pointer;font-weight:700;">' +
            '새로고침</button>';
        document.body.appendChild(div);
    }

    // ── 메인 초기화 흐름
    function initializeModules() {
        return new Promise(function (resolve, reject) {
            try {
                

                // STEP 1: stream-key-api.php 호출 → HLS_URL 동적 설정
                // ref: https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API
                
                window.StreamingConfig.fetchStreamKey()
                    .then(function (apiData) {
                        

                        // STEP 2: 채팅 관리자 초기화 (DOM 이벤트 바인딩)
                        
                        window.ChatManager.initialize();

                        // STEP 3: WebSocket 연결
                        
                        window.WebSocketClient.connect();

                        // STEP 4: 비디오 플레이어 초기화 (HLS_URL 확정 후)
                        // Video.js는 DOM 준비 후 약간의 지연 필요
                        // ref: https://github.com/videojs/video.js/blob/main/README.md
                        
                        setTimeout(function () {
                            try {
                                window.VideoPlayer.initialize();
                                

                                // 스트림 상태 메시지
                                if (apiData && apiData.source === 'db+srs') {
                                    window.ChatManager.addSystemMessage(
                                        '🎥 라이브 스트리밍 중 — ' + (apiData.title || '구찌야 놀자')
                                    );
                                } else if (apiData && apiData.source === 'db_only_srs_offline') {
                                    window.ChatManager.addSystemMessage(
                                        '📡 스트리밍 준비 중입니다...'
                                    );
                                } else {
                                    window.ChatManager.addSystemMessage(
                                        '📡 현재 방송 준비 중입니다. 잠시 후 시작됩니다.'
                                    );
                                }

                                resolve();
                            } catch (videoErr) {
                                console.error('[App] 비디오 플레이어 초기화 실패:', videoErr);
                                window.ChatManager.addSystemMessage(
                                    '⚠️ 비디오 플레이어 초기화 실패: ' + videoErr.message
                                );
                                // 비디오 실패해도 채팅은 계속 동작
                                resolve();
                            }
                        }, 300);
                    })
                    .catch(function (err) {
                        console.error('[App] 스트림 키 API 실패:', err);
                        // API 실패해도 폴백 URL로 계속 진행
                        window.ChatManager.initialize();
                        window.WebSocketClient.connect();
                        setTimeout(function () {
                            try { window.VideoPlayer.initialize(); } catch (e) { }
                            resolve();
                        }, 300);
                    });

            } catch (err) {
                console.error('[App] 초기화 실패:', err);
                reject(err);
            }
        });
    }

    function initialize() {
        if (isInitialized) return initializationPromise;
        if (initializationPromise) return initializationPromise;

        initializationPromise = new Promise(function (resolve, reject) {
            try {
                // STEP 1: 모듈 의존성 확인 (즉시)
                checkDependencies();

                // STEP 2: Video.js / HLS.js CDN 로드 완료 대기 (최대 10초 폴링)
                // ref: https://github.com/videojs/video.js/blob/main/README.md
                waitForLibraries(function (libErr) {
                    if (libErr) {
                        showError(libErr.message);
                        reject(libErr);
                        return;
                    }

                    // STEP 3: 모든 의존성 준비 완료 → 초기화 실행
                    initializeModules()
                        .then(function () {
                            isInitialized = true;
                            resolve();
                        })
                        .catch(function (err) {
                            showError(err.message);
                            reject(err);
                        });
                });
            } catch (err) {
                showError(err.message);
                reject(err);
            }
        });

        return initializationPromise;
    }

    function destroy() {
        if (!isInitialized) return;
        try {
            window.WebSocketClient.disconnect();
            window.VideoPlayer.destroy();
            isInitialized = false;
            initializationPromise = null;
        } catch (err) {
            console.error('[App] 정리 중 오류:', err);
        }
    }

    function getStatus() {
        return {
            initialized: isInitialized,
            hlsUrl: window.StreamingConfig.HLS_URL,
            wsConnected: window.WebSocketClient ? window.WebSocketClient.isConnected() : false,
            nickname: window.ChatManager ? window.ChatManager.getNickname() : null
        };
    }

    // ── DOM 로드 완료 후 자동 초기화
    // ref: https://developer.mozilla.org/en-US/docs/Web/API/Document/DOMContentLoaded_event
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            initialize().catch(function (err) {
                console.error('[App] 자동 초기화 실패:', err);
            });
        });
    } else {
        // DOM 이미 준비됨 — 즉시 실행 (waitForLibraries가 CDN 로드 대기 처리)
        initialize().catch(function (err) {
            console.error('[App] 자동 초기화 실패:', err);
        });
    }

    // ── 페이지 언로드 시 정리
    window.addEventListener('beforeunload', destroy);

    return {
        initialize: initialize,
        destroy: destroy,
        getStatus: getStatus,
        isInitialized: function () { return isInitialized; }
    };
})();

window.App = window.StreamingApp;
