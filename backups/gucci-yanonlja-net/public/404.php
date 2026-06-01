<?php
/**
 * 커스텀 404 에러 페이지 — 구찌야놀자
 * ref: https://httpd.apache.org/docs/2.4/mod/core.html#errordocument
 * ref: https://www.php.net/manual/en/function.http-response-code.php
 * ref: https://owasp.org/www-project-secure-headers/
 */
declare(strict_types=1);

require_once dirname(__DIR__) . '/core/helpers/security-headers.php';

http_response_code(404);

if (!headers_sent()) {
    header('X-Robots-Tag: noindex, nofollow');
}

/* 요청 경로 — XSS 방지
 * ref: https://www.php.net/manual/en/function.htmlspecialchars.php
 */
$requested_url = esc((string)($_SERVER['REQUEST_URI'] ?? '/'));
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>페이지를 찾을 수 없습니다 (404) — 구찌야놀자</title>
  <meta name="robots" content="noindex, nofollow">
  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
  <link rel="preload" href="https://cdn.jsdelivr.net/gh/projectnoonnu/2510-1@1.0/HakgyoansimTTeokbokkiB.woff2" as="font" type="font/woff2" crossorigin>
  <link rel="stylesheet" href="/assets/css/mobile-responsive.css">
  <style>
    @font-face{font-family:'SchoolSafetyTteokbokki';src:url('https://cdn.jsdelivr.net/gh/projectnoonnu/2510-1@1.0/HakgyoansimTTeokbokkiB.woff2') format('woff2');font-weight:normal;font-style:normal;font-display:swap;}
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
    body{font-family:'SchoolSafetyTteokbokki',sans-serif;background:#040f1c;color:#c8d8e8;min-height:100vh;display:flex;flex-direction:column;}
    .err-wrap{flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:3rem 1.5rem;text-align:center;}
    .err-code{font-size:clamp(5rem,15vw,8rem);font-weight:900;color:#f5c842;line-height:1;margin-bottom:1rem;}
    .err-title{font-size:clamp(1.25rem,4vw,1.75rem);color:#fff;margin-bottom:.75rem;}
    .err-desc{font-size:clamp(.875rem,2vw,1rem);color:#8898aa;margin-bottom:.5rem;max-width:480px;line-height:1.7;}
    .err-url{font-size:.82rem;color:#4a5568;margin-bottom:2.5rem;word-break:break-all;max-width:480px;}
    .err-actions{display:flex;gap:.875rem;flex-wrap:wrap;justify-content:center;margin-bottom:2.5rem;}
    .err-btn{display:inline-flex;align-items:center;gap:.4rem;padding:.75rem 1.5rem;border-radius:50px;font-family:inherit;font-size:.9rem;font-weight:700;text-decoration:none;transition:all .2s;min-height:48px;}
    .err-btn-primary{background:linear-gradient(135deg,#f5c842,#e6a800);color:#040f1c;}
    .err-btn-primary:hover{transform:translateY(-2px);box-shadow:0 6px 20px rgba(245,200,66,.4);}
    .err-btn-secondary{background:rgba(255,255,255,.06);color:#c8d8e8;border:1px solid rgba(255,255,255,.1);}
    .err-btn-secondary:hover{background:rgba(255,255,255,.1);transform:translateY(-2px);}
    .err-btn:focus-visible{outline:3px solid #f5c842;outline-offset:3px;}
    .err-links{background:#071a2e;border:1px solid rgba(245,200,66,.12);border-radius:12px;padding:1.5rem;max-width:420px;width:100%;}
    .err-links h2{font-size:.9rem;font-weight:700;color:#f5c842;margin-bottom:1rem;}
    .err-links ul{list-style:none;padding:0;display:flex;flex-direction:column;gap:.5rem;}
    .err-links a{display:flex;align-items:center;gap:.5rem;color:#8898aa;text-decoration:none;font-size:.875rem;padding:.4rem .5rem;border-radius:6px;transition:all .2s;}
    .err-links a:hover{background:rgba(245,200,66,.06);color:#f5c842;}
    .err-links a:focus-visible{outline:2px solid #f5c842;outline-offset:2px;}
    footer{padding:1.25rem;text-align:center;font-size:.8rem;color:#4a5568;border-top:1px solid rgba(255,255,255,.06);}
    @media(max-width:480px){.err-actions{flex-direction:column;align-items:center;}.err-btn{width:100%;max-width:280px;justify-content:center;}}
  </style>
</head>
<body>
<?php require_once dirname(__DIR__) . '/core/helpers/header.php'; ?>

<main class="err-wrap" id="main-content" role="main">
  <div class="err-code" aria-label="404 에러">404</div>
  <h1 class="err-title">페이지를 찾을 수 없습니다</h1>
  <p class="err-desc">요청하신 페이지가 존재하지 않거나 이동되었을 수 있습니다.</p>
  <p class="err-url">요청 경로: <?= $requested_url ?></p>

  <div class="err-actions">
    <a href="/" class="err-btn err-btn-primary" aria-label="홈으로 돌아가기">🏠 홈으로 돌아가기</a>
    <a href="javascript:history.back()" class="err-btn err-btn-secondary" aria-label="이전 페이지로">← 이전 페이지</a>
  </div>

  <nav class="err-links" aria-label="자주 찾는 페이지">
    <h2>📌 자주 찾는 페이지</h2>
    <ul>
      <li><a href="/">🎰 메인 페이지</a></li>
      <li><a href="/streaming/">🎬 실시간 스트리밍</a></li>
      <li><a href="/games/">🃏 게임 안내</a></li>
      <li><a href="/reservation/">📅 게임 예약</a></li>
      <li><a href="/free-board/">💬 자유게시판</a></li>
      <li><a href="https://t.me/Fury0079" target="_blank" rel="noopener noreferrer" aria-label="텔레그램 문의 (새 탭에서 열림)">📱 텔레그램 문의</a></li>
    </ul>
  </nav>
</main>

<?php require_once dirname(__DIR__) . '/core/helpers/footer.php'; ?>
</body>
</html>
