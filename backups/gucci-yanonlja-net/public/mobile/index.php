<?php
/**
 * 모바일 → 루트 301 리다이렉트
 * 이유: Google Mobile-First Indexing 기준, 반응형 단일 URL이 SEO 최적
 * ref: https://developers.google.com/search/docs/crawling-indexing/mobile/mobile-sites-mobile-first-indexing
 * ref: https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/301
 *
 * 대기업 방식: /mobile/ URL을 유지하면 duplicate content 위험
 * → 301로 canonical(/) 에 합치기
 */
declare(strict_types=1);
header('Location: /', true, 301);
exit;
