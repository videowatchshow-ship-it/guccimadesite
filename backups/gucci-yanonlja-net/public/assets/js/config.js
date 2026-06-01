/**
 * 설정 모듈 — 실시간 채팅 스트리밍
 * 모든 설정값 중앙 집중 관리 + 스트림 키 API 동적 조회
 *
 * 공식 문서:
 *   MDN Fetch API:
 *     https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API
 *   MDN Response.json():
 *     https://developer.mozilla.org/en-US/docs/Web/API/Response/json
 *   Video.js 설정 옵션:
 *     https://github.com/videojs/video.js/blob/main/README.md
 *   HLS.js 버전: 1.6.16
 *     https://github.com/video-dev/hls.js
 */
window.StreamingConfig = {

    // ── WebSocket 설정
    WS_URL: 'wss://xn--2e0bj1fruw33b6ti.net:8080',
    RECONNECT_DELAY: 1000,
    MAX_RECONNECT_ATTEMPTS: 10,

    // ── HLS 스트리밍 설정
    // 초기값: null — app-initializer.js에서 stream-key-api.php 조회 후 동적 설정
    // ref: https://ossrs.net/lts/en-us/docs/v6/doc/http-api
    HLS_URL: null,

    // ── 스트림 키 API 엔드포인트
    // stream-key-api.php: DB is_active=1 AND SRS publish.active=true 교차 조회
    STREAM_KEY_API_URL: '/streaming/stream-key-api.php',

    // ── HLS URL 폴백 (SRS 오프라인 + DB 키 있을 때)
    // ref: https://ossrs.net/lts/en-us/docs/v6/doc/hls
    HLS_BASE_URL: 'https://xn--2e0bj1fruw33b6ti.net/live/',

    // ── 채팅 설정
    MESSAGE_MAX_LENGTH: 500,
    NICKNAME_MAX_LENGTH: 20,
    NICKNAME_STORAGE_KEY: 'chat_nickname',
    SCROLL_THRESHOLD: 100,

    // ── Video.js 플레이어 옵션
    // ref: https://github.com/videojs/video.js/blob/main/README.md
    VIDEO_OPTIONS: {
        fluid: true,
        responsive: true,
        liveui: true,
        html5: {
            vhs: { overrideNative: true }
        }
    }
};

/**
 * 스트림 키 API 조회 — HLS URL 동적 설정
 *
 * 응답 구조 (stream-key-api.php):
 *   { stream_key, hls_url, title, source, viewers, kbps, message }
 *
 * source 값:
 *   "db+srs"              — DB 등록 + SRS 실시간 송출 중 (최우선)
 *   "db_only_srs_offline" — SRS 오프라인, DB 기반 폴백
 *   "srs_no_active"       — SRS 온라인이지만 송출 없음
 *   "none"                — 등록된 키 없음
 *
 * ref: https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API
 * ref: https://developer.mozilla.org/en-US/docs/Web/API/Response/json
 */
window.StreamingConfig.fetchStreamKey = function () {
    // ref: https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API
    return fetch(window.StreamingConfig.STREAM_KEY_API_URL, {
        method: 'GET',
        headers: { 'Accept': 'application/json' ,
        credentials: 'same-origin'},
        cache: 'no-store'   // 항상 최신 스트림 키 조회
    })
        .then(function (response) {
            if (!response.ok) {
                throw new Error('stream-key-api 응답 오류: HTTP ' + response.status);
            }
            // ref: https://developer.mozilla.org/en-US/docs/Web/API/Response/json
            return response.json();
        })
        .then(function (data) {
            if (data.hls_url) {
                // DB + SRS 교차 확인된 HLS URL 사용
                window.StreamingConfig.HLS_URL = data.hls_url;
                
            } else if (data.stream_key) {
                // stream_key는 있지만 hls_url이 null인 경우 직접 조합
                window.StreamingConfig.HLS_URL =
                    window.StreamingConfig.HLS_BASE_URL + data.stream_key + '.m3u8';
                
            } else {
                // 활성 스트림 없음 — 폴백 URL 사용
                window.StreamingConfig.HLS_URL =
                    window.StreamingConfig.HLS_BASE_URL + 'live.m3u8';
                console.warn('[Config] 활성 스트림 없음, 폴백 URL 사용. message:', data.message);
            }
            return data;
        })
        .catch(function (err) {
            // API 호출 실패 시 폴백
            console.error('[Config] stream-key-api 호출 실패:', err);
            window.StreamingConfig.HLS_URL =
                window.StreamingConfig.HLS_BASE_URL + 'live.m3u8';
            return null;
        });
};
