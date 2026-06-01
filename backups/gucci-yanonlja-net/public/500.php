<?php
/**
 * 커스텀 500 에러 페이지 — 구찌야놀자
 * ref: https://httpd.apache.org/docs/2.4/mod/core.html#errordocument
 * ref: https://www.php.net/manual/en/function.http-response-code.php
 * ref: https://owasp.org/www-project-secure-headers/
 */
declare(strict_types=1);

/* 보안 헤더 — 직접 설정 (security-headers.php require 전 오류 방지) */
if (!headers_sent()) {
    header_remove('X-Powered-By');
    header('X-Frame-Options: DENY');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('X-Robots-Tag: noindex, nofollow');
    header('Cache-Control: no-store, no-cache, must-revalidate');
}

http_response_code(500);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>서버 오류 (500) — 구찌야놀자</title>
  <meta name="robots" content="noindex, nofollow">
  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
  <link rel="stylesheet" href="/assets/css/mobile-responsive.css">
  <style>
    @font-face{font-family:'SchoolSafetyTteokbokki';src:url('https://cdn.jsdelivr.net/gh/projectnoonnu/2510-1@1.0/HakgyoansimTTeokbokkiB.woff2') format('woff2');font-weight:normal;font-style:normal;font-display:swap;}
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
    body{font-family:'SchoolSafetyTteokbokki',sans-serif;background:#040f1c;color:#c8d8e8;min-height:100vh;display:flex;flex-direction:column;}
    .err-wrap{flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:3rem 1.5rem;text-align:center;}
    .err-code{font-size:clamp(5rem,15vw,8rem);font-weight:900;color:#fc8181;line-height:1;margin-bottom:1rem;}
    .err-title{font-size:clamp(1.25rem,4vw,1.75rem);color:#fff;margin-bottom:.75rem;}
    .err-desc{font-size:clamp(.875rem,2vw,1rem);color:#8898aa;margin-bottom:2.5rem;max-width:480px;line-height:1.7;}
    .err-btn{display:inline-flex;align-items:center;gap:.4rem;padding:.875rem 2rem;border-radius:50px;font-family:inherit;font-size:.95rem;font-weight:700;text-decoration:none;background:linear-gradient(135deg,#f5c842,#e6a800);color:#040f1c;transition:all .2s;min-height:48px;}
    .err-btn:hover{transform:translateY(-2px);box-shadow:0 6px 20px rgba(245,200,66,.4);}
    .err-btn:focus-visible{outline:3px solid #f5c842;outline-offset:3px;}
    footer{padding:1.25rem;text-align:center;font-size:.8rem;color:#4a5568;border-top:1px solid rgba(255,255,255,.06);}
  </style>
</head>
<body>

<main class="err-wrap" id="main-content" role="main">
  <div class="err-code" aria-label="500 에러">500</div>
  <h1 class="err-title">서버 오류가 발생했습니다</h1>
  <p class="err-desc">일시적인 서버 오류입니다. 잠시 후 다시 시도해 주세요.<br>문제가 지속되면 텔레그램으로 문의해 주세요.</p>
  <a href="/" class="err-btn" aria-label="홈으로 돌아가기">🏠 홈으로 돌아가기</a>
</main>

<footer role="contentinfo">
  © 2026 구찌야놀자. All rights reserved.
</footer>
</body>
</html>
