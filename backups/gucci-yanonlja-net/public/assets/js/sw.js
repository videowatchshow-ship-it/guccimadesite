// Service Worker for PWA
// ref: https://developer.mozilla.org/en-US/docs/Web/API/Service_Worker_API
// 전략: Network-First (캐시 우선 → 서버 응답 우선으로 변경)
// 이유: Cache-First 시 구버전 index.php 서빙 + POST 요청 간섭 문제

const CACHE_NAME = 'gucci-yanolja-v20260502b';
const urlsToCache = [
    '/mobile-responsive.css',
    '/manifest.json'
];

// Install event
self.addEventListener('install', function (event) {
    // 즉시 활성화 (구버전 SW 대기 없이 바로 교체)
    // ref: https://developer.mozilla.org/en-US/docs/Web/API/ServiceWorkerGlobalScope/skipWaiting
    self.skipWaiting();
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(function (cache) {
                return cache.addAll(urlsToCache);
            })
    );
});

// Fetch event — Network-First + POST 완전 우회
self.addEventListener('fetch', function (event) {
    // POST 요청은 SW 완전 우회 (google-callback.php 등 API 요청 보호)
    // ref: https://developer.mozilla.org/en-US/docs/Web/API/Request/method
    if (event.request.method !== 'GET') {
        return;
    }

    // API 경로 우회 (/auth/, /admin/ 등)
    var url = new URL(event.request.url);
    if (url.pathname.startsWith('/auth/') || url.pathname.startsWith('/admin/')) {
        return;
    }

    // 정적 자산만 Cache-First, 나머지는 Network-First
    var isStatic = /\.(css|woff2?|png|jpg|jpeg|webp|svg|gif|ico)$/.test(url.pathname);

    if (isStatic) {
        // 정적 자산: Cache-First
        event.respondWith(
            caches.match(event.request).then(function (response) {
                return response || fetch(event.request, {
        credentials: 'same-origin'
    }).then(function (networkResponse) {
                    var clone = networkResponse.clone();
                    caches.open(CACHE_NAME).then(function (cache) {
                        cache.put(event.request, clone);
                    });
                    return networkResponse;
                });
            })
        );
    } else {
        // HTML/PHP: Network-First (항상 서버에서 최신 버전)
        event.respondWith(
            fetch(event.request, {
        credentials: 'same-origin'
    }).catch(function () {
                return caches.match(event.request);
            })
        );
    }
});

// Activate event — 구버전 캐시 전부 삭제
self.addEventListener('activate', function (event) {
    // 즉시 모든 클라이언트 제어권 획득
    // ref: https://developer.mozilla.org/en-US/docs/Web/API/Clients/claim
    event.waitUntil(
        Promise.all([
            self.clients.claim(),
            caches.keys().then(function (cacheNames) {
                return Promise.all(
                    cacheNames.map(function (cacheName) {
                        if (cacheName !== CACHE_NAME) {
                            return caches.delete(cacheName);
                        }
                    })
                );
            })
        ])
    );
});
