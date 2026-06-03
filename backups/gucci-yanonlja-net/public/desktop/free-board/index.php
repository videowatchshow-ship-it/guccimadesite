<?php
/**
 * 데스크탑 자유게시판 — 구찌야놀자
 * ref: https://schema.org/DiscussionForumPosting
 * ref: https://owasp.org/www-project-secure-headers/
 */
declare(strict_types=1);

if (!headers_sent()) {
    header_remove('X-Powered-By');
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header("Content-Security-Policy: default-src 'self' https:; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://accounts.google.com; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; img-src 'self' data: https:; font-src 'self' data: https://cdn.jsdelivr.net; connect-src 'self' https: wss:; frame-ancestors 'self';");
    $https = $_SERVER['HTTPS'] ?? '';
    if ($https !== '' && $https !== 'off') {
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
    }
}

$ua = (string)($_SERVER['HTTP_USER_AGENT'] ?? '');
if (preg_match('/Mobile|Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i', $ua)) {
    header('Location: /mobile/free-board/', true, 302);
    exit;
}

$site_url   = 'https://xn--2e0bj1fruw33b6ti.net';
$page_url   = $site_url . '/desktop/free-board/';
$page_title = '자유게시판 | 커뮤니티 — 구찌야놀자';
$page_desc  = '구찌야놀자 자유게시판. 아바타 바카라 후기, 정보 공유, 커뮤니티 소통 공간.';
$page_img   = $site_url . '/assets/images/avatar-baccarat-gucci-play.png';

/* 샘플 게시글 — 실제 배포 시 DB 연동 */
$posts = [
    ['id' => 5, 'title' => '오늘 바카라 대박 후기 🎰', 'author' => '김**', 'date' => '2026-05-22', 'views' => 342, 'comments' => 18, 'tag' => '후기'],
    ['id' => 4, 'title' => '구찌야놀자 처음 이용 후기', 'author' => '이**', 'date' => '2026-05-21', 'views' => 215, 'comments' => 9,  'tag' => '후기'],
    ['id' => 3, 'title' => '바카라 전략 공유합니다',    'author' => '박**', 'date' => '2026-05-20', 'views' => 487, 'comments' => 32, 'tag' => '정보'],
    ['id' => 2, 'title' => '생방송 화질 너무 좋네요',   'author' => '최**', 'date' => '2026-05-19', 'views' => 178, 'comments' => 7,  'tag' => '후기'],
    ['id' => 1, 'title' => '예약 시스템 이용 방법',     'author' => '정**', 'date' => '2026-05-18', 'views' => 523, 'comments' => 14, 'tag' => '안내'],
];
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?></title>
  <meta name="description" content="<?= htmlspecialchars($page_desc, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="robots" content="index,follow,max-snippet:-1,max-image-preview:large,max-video-preview:-1">
  <link rel="canonical" href="<?= htmlspecialchars($page_url, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:type"        content="website">
  <meta property="og:title"       content="<?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:description" content="<?= htmlspecialchars($page_desc, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:url"         content="<?= htmlspecialchars($page_url, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:image"       content="<?= htmlspecialchars($page_img, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:locale"      content="ko_KR">
  <script type="application/ld+json">
  {"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"홈","item":"https://xn--2e0bj1fruw33b6ti.net/"},{"@type":"ListItem","position":2,"name":"자유게시판","item":"<?= htmlspecialchars($page_url, ENT_QUOTES, 'UTF-8') ?>"}]}
  </script>
  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
  <link rel="preload" href="/assets/css/mobile-responsive.css" as="style">
  <link rel="stylesheet" href="/assets/css/mobile-responsive.css">
  <link rel="stylesheet" href="/desktop/assets/css/desktop.css">
  <meta name="theme-color" content="#040f1c">
</head>
<body>
  <!-- 🏠 헤더 (메뉴 가로 비율 균등 분배) -->
  <header role="banner" style="background: linear-gradient(135deg, #071a2e, #0a2540); padding: 1.2rem 2rem; border-bottom: 3px solid rgba(245,200,66,0.3); margin-bottom: 1.5rem;">
    <nav style="max-width: 1400px; margin: 0 auto; display: flex; justify-content: space-around; align-items: center; flex-wrap: wrap; font-family: 'SchoolSafetyTteokbokki', sans-serif; font-size: 1.1rem; font-weight: 600;">
      <a href="/" style="font-size: 1.3rem; font-weight: 800; color: #f5c842; text-decoration: none; white-space: nowrap; transition: all 0.2s; display: inline-block; padding: 0.5rem 0.75rem; border-radius: 6px;">🏠 홈</a>
      <a href="/streaming/" style="color: #c8d8e8; text-decoration: none; white-space: nowrap; transition: all 0.2s; display: inline-block; padding: 0.5rem 0.75rem; border-radius: 6px;">🔴 스트리밍</a>
      <a href="/games/" style="color: #c8d8e8; text-decoration: none; white-space: nowrap; transition: all 0.2s; display: inline-block; padding: 0.5rem 0.75rem; border-radius: 6px;">🃏 게임</a>
      <a href="/free-board/" style="color: #c8d8e8; text-decoration: none; white-space: nowrap; transition: all 0.2s; display: inline-block; padding: 0.5rem 0.75rem; border-radius: 6px;">💬 게시판</a>
      <a href="/reservation/" style="color: #c8d8e8; text-decoration: none; white-space: nowrap; transition: all 0.2s; display: inline-block; padding: 0.5rem 0.75rem; border-radius: 6px;">📅 예약</a>
      <a href="/contact/" style="color: #c8d8e8; text-decoration: none; white-space: nowrap; transition: all 0.2s; display: inline-block; padding: 0.5rem 0.75rem; border-radius: 6px;">📞 연락</a>
    </nav>
  </header>
<a class="skip-to-main" href="#main-content">본문으로 바로가기</a>
<?php require_once dirname(__DIR__, 3) . '/core/helpers/header.php'; ?>

<main id="main-content" role="main">

  <section class="d-page-hero" aria-label="자유게시판 헤더">
    <div class="d-inner">
      <nav aria-label="breadcrumb">
        <ol style="list-style:none;display:flex;gap:.5rem;font-size:.85rem;color:#6b7c93;padding:0;margin:0 0 .75rem;">
          <li><a href="/desktop/" style="color:#6b7c93;text-decoration:none;">홈</a></li>
          <li style="color:rgba(255,255,255,.2);">›</li>
          <li style="color:#f5c842;" aria-current="page">자유게시판</li>
        </ol>
      </nav>
      <h1 style="font-size:clamp(1.5rem,3vw,2.25rem);color:#fff;margin-bottom:.5rem;">
        <span style="color:#f5c842;">자유</span>게시판
      </h1>
      <p style="color:#8898aa;font-size:1rem;">후기, 정보, 소통 — 자유롭게 이야기하세요</p>
    </div>
  </section>

  <div class="d-inner" style="padding-top:2rem;padding-bottom:4rem;">

    <!-- 게시판 헤더 -->
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;flex-wrap:wrap;gap:.75rem;">
      <div style="font-size:.875rem;color:#6b7c93;">총 <strong style="color:#f5c842;"><?= count($posts) ?></strong>개 게시글</div>
      <a href="https://t.me/Fury0079" rel="noopener noreferrer" target="_blank"
        style="display:inline-flex;align-items:center;gap:.4rem;background:linear-gradient(135deg,#f5c842,#e6a800);color:#040f1c;border:none;border-radius:8px;font-family:inherit;font-size:.875rem;font-weight:700;padding:.6rem 1.25rem;text-decoration:none;cursor:pointer;"
        aria-label="글쓰기 (텔레그램으로 이동, 새 탭에서 열림)">
        ✏️ 글쓰기
      </a>
    </div>

    <!-- 게시글 목록 -->
    <div style="border:1px solid rgba(245,200,66,.12);border-radius:12px;overflow:hidden;" role="table" aria-label="게시글 목록">
      <div style="display:grid;grid-template-columns:60px 1fr 100px 80px 80px 80px;padding:.75rem 1rem;background:rgba(245,200,66,.06);border-bottom:1px solid rgba(245,200,66,.12);font-size:.8rem;font-weight:700;color:#6b7c93;" role="row">
        <div role="columnheader">번호</div>
        <div role="columnheader">제목</div>
        <div role="columnheader">작성자</div>
        <div role="columnheader">날짜</div>
        <div role="columnheader">조회</div>
        <div role="columnheader">댓글</div>
      </div>
      <?php foreach ($posts as $post): ?>
      <div style="display:grid;grid-template-columns:60px 1fr 100px 80px 80px 80px;padding:.875rem 1rem;border-bottom:1px solid rgba(255,255,255,.04);transition:background .2s;cursor:pointer;"
        role="row"
        onmouseenter="this.style.background='rgba(245,200,66,.04)'"
        onmouseleave="this.style.background='transparent'">
        <div style="font-size:.82rem;color:#6b7c93;" role="cell"><?= (int)$post['id'] ?></div>
        <div role="cell">
          <span style="display:inline-block;font-size:.7rem;padding:.1rem .45rem;border-radius:4px;background:rgba(245,200,66,.12);color:#f5c842;margin-right:.5rem;"><?= htmlspecialchars($post['tag'], ENT_QUOTES, 'UTF-8') ?></span>
          <span style="font-size:.9rem;color:#c8d8e8;"><?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?></span>
        </div>
        <div style="font-size:.82rem;color:#6b7c93;" role="cell"><?= htmlspecialchars($post['author'], ENT_QUOTES, 'UTF-8') ?></div>
        <div style="font-size:.82rem;color:#6b7c93;" role="cell"><?= htmlspecialchars($post['date'], ENT_QUOTES, 'UTF-8') ?></div>
        <div style="font-size:.82rem;color:#6b7c93;" role="cell"><?= (int)$post['views'] ?></div>
        <div style="font-size:.82rem;color:#f5c842;font-weight:600;" role="cell"><?= (int)$post['comments'] ?></div>
      </div>
      <?php endforeach; ?>
    </div>

  </div>

</main>

<?php require_once dirname(__DIR__, 3) . '/core/helpers/footer.php'; ?>
<script src="/desktop/assets/js/desktop.js" defer></script>
</body>
</html>

