<?php
/**
 * 자유게시판 — 구찌야놀자
 * ref: https://schema.org/DiscussionForumPosting
 * ref: https://developer.mozilla.org/en-US/docs/Web/HTML/Element/article
 * ref: https://owasp.org/www-project-secure-headers/
 */
declare(strict_types=1);

if (!headers_sent()) {
    header_remove('X-Powered-By');
    header('X-Frame-Options: DENY');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header("Content-Security-Policy: default-src 'self' https:; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://accounts.google.com; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; img-src 'self' data: https:; font-src 'self' data: https://cdn.jsdelivr.net; connect-src 'self' https: wss:; frame-ancestors 'none';");
}

$site_url   = 'https://xn--2e0bj1fruw33b6ti.net';
$page_url   = $site_url . '/free-board/';
$page_title = '자유게시판 | 커뮤니티 — 구찌야놀자';
$page_desc  = '구찌야놀자 자유게시판. 아바타 바카라 정보 공유, 후기, 질문 등 자유롭게 소통하세요.';
$page_img   = $site_url . '/아바타-바카라-구찌야-놀자.png';

/* 데모 게시글 데이터 (실제 배포 시 DB에서 조회) */
$demo_posts = [
    ['id'=>10,'cat'=>'후기','title'=>'오늘 바카라 대박났어요 🎰','author'=>'행운의김씨','date'=>'2026-05-22','views'=>342,'comments'=>18,'hot'=>true],
    ['id'=>9,'cat'=>'정보','title'=>'아바타 바카라 베팅 전략 공유합니다','author'=>'전략가박씨','date'=>'2026-05-22','views'=>521,'comments'=>34,'hot'=>true],
    ['id'=>8,'cat'=>'질문','title'=>'처음 이용하는데 어떻게 시작하나요?','author'=>'초보자이씨','date'=>'2026-05-21','views'=>189,'comments'=>12,'hot'=>false],
    ['id'=>7,'cat'=>'후기','title'=>'구찌야놀자 스트리밍 화질 최고입니다','author'=>'만족한최씨','date'=>'2026-05-21','views'=>267,'comments'=>8,'hot'=>false],
    ['id'=>6,'cat'=>'정보','title'=>'캄보디아 바카라 규칙 정리','author'=>'정보왕정씨','date'=>'2026-05-20','views'=>445,'comments'=>22,'hot'=>false],
    ['id'=>5,'cat'=>'후기','title'=>'VIP 서비스 정말 좋네요','author'=>'VIP회원강씨','date'=>'2026-05-20','views'=>198,'comments'=>6,'hot'=>false],
    ['id'=>4,'cat'=>'질문','title'=>'모바일에서도 잘 되나요?','author'=>'모바일유저조씨','date'=>'2026-05-19','views'=>156,'comments'=>9,'hot'=>false],
    ['id'=>3,'cat'=>'정보','title'=>'드래곤 타이거 승률 분석','author'=>'분석가윤씨','date'=>'2026-05-19','views'=>389,'comments'=>15,'hot'=>false],
    ['id'=>2,'cat'=>'후기','title'=>'예약 서비스 이용 후기','author'=>'예약왕장씨','date'=>'2026-05-18','views'=>234,'comments'=>11,'hot'=>false],
    ['id'=>1,'cat'=>'공지','title'=>'구찌야놀자 자유게시판 이용 안내','author'=>'운영팀','date'=>'2026-05-01','views'=>1024,'comments'=>0,'hot'=>false],
];
$cat_colors = ['공지'=>'#fc8181','후기'=>'#68d391','정보'=>'#63b3ed','질문'=>'#f6e05e'];
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?></title>
  <meta name="description" content="<?= htmlspecialchars($page_desc, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="robots" content="index,follow">
  <link rel="canonical" href="<?= htmlspecialchars($page_url, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:type"        content="website">
  <meta property="og:title"       content="<?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:description" content="<?= htmlspecialchars($page_desc, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:url"         content="<?= htmlspecialchars($page_url, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:image"       content="<?= htmlspecialchars($page_img, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:locale"      content="ko_KR">
  <script type="application/ld+json">
  {"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"홈","item":"https://xn--2e0bj1fruw33b6ti.net/"},{"@type":"ListItem","position":2,"name":"자유게시판","item":"https://xn--2e0bj1fruw33b6ti.net/free-board/"}]}
  </script>
  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
  <link rel="preload" href="/assets/css/mobile-responsive.css" as="style">
  <link rel="stylesheet" href="/assets/css/mobile-responsive.css">
  <style>
    @font-face{font-family:'SchoolSafetyTteokbokki';src:url('https://cdn.jsdelivr.net/gh/projectnoonnu/2510-1@1.0/HakgyoansimTTeokbokkiB.woff2') format('woff2');font-weight:normal;font-style:normal;font-display:swap;}
    body{font-family:'SchoolSafetyTteokbokki',sans-serif;background:#040f1c;color:#c8d8e8;margin:0;padding:0;min-height:100vh;display:flex;flex-direction:column;}
    .g-inner{max-width:1200px;margin:0 auto;padding:0 1.5rem;}
    .page-hero{background:linear-gradient(180deg,#071a2e 0%,#040f1c 100%);padding:2.5rem 0 2rem;border-bottom:1px solid rgba(245,200,66,.15);}
    .board-wrap{padding:2rem 0 3rem;}
    .board-toolbar{display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.25rem;flex-wrap:wrap;}
    .board-filter{display:flex;gap:.5rem;flex-wrap:wrap;}
    .filter-btn{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.1);border-radius:6px;color:#8898aa;cursor:pointer;font-family:inherit;font-size:.82rem;padding:.35rem .875rem;transition:all .2s;min-height:36px;}
    .filter-btn.active,.filter-btn:hover{background:rgba(245,200,66,.1);border-color:rgba(245,200,66,.3);color:#f5c842;}
    .board-write-btn{display:inline-flex;align-items:center;gap:.4rem;background:linear-gradient(135deg,#f5c842,#e6a800);color:#040f1c;border:none;border-radius:8px;cursor:pointer;font-family:inherit;font-size:.875rem;font-weight:700;padding:.5rem 1.25rem;text-decoration:none;transition:all .25s;min-height:40px;}
    .board-write-btn:hover{transform:translateY(-2px);box-shadow:0 6px 16px rgba(245,200,66,.4);}
    .board-search{display:flex;gap:.5rem;}
    .board-search-input{background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:8px;color:#c8d8e8;font-family:inherit;font-size:.875rem;padding:.5rem .875rem;width:200px;transition:border-color .2s;}
    .board-search-input:focus{outline:none;border-color:rgba(245,200,66,.4);}
    .board-search-btn{background:rgba(245,200,66,.15);border:1px solid rgba(245,200,66,.25);border-radius:8px;color:#f5c842;cursor:pointer;font-family:inherit;font-size:.875rem;padding:.5rem .875rem;transition:all .2s;min-height:40px;}
    .board-search-btn:hover{background:rgba(245,200,66,.25);}
    .board-table{width:100%;border-collapse:collapse;}
    .board-table th{background:#071a2e;border-bottom:2px solid rgba(245,200,66,.2);color:#f5c842;font-size:.82rem;font-weight:700;padding:.75rem 1rem;text-align:left;}
    .board-table td{border-bottom:1px solid rgba(255,255,255,.05);font-size:.875rem;padding:.875rem 1rem;vertical-align:middle;}
    .board-table tr:hover td{background:rgba(245,200,66,.03);}
    .post-cat{border-radius:4px;font-size:.72rem;font-weight:700;padding:.2rem .5rem;}
    .post-title-link{color:#c8d8e8;text-decoration:none;transition:color .2s;}
    .post-title-link:hover{color:#f5c842;}
    .post-hot{background:rgba(229,62,62,.15);border-radius:4px;color:#fc8181;font-size:.7rem;font-weight:700;margin-left:.4rem;padding:.15rem .4rem;}
    .post-author{color:#6b7c93;font-size:.82rem;}
    .post-date{color:#4a5568;font-size:.8rem;}
    .post-views,.post-comments{color:#6b7c93;font-size:.82rem;text-align:center;}
    .board-pagination{display:flex;align-items:center;justify-content:center;gap:.4rem;margin-top:1.5rem;}
    .page-btn{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:6px;color:#8898aa;cursor:pointer;font-family:inherit;font-size:.875rem;min-height:36px;min-width:36px;padding:.35rem .75rem;transition:all .2s;}
    .page-btn.active,.page-btn:hover{background:rgba(245,200,66,.1);border-color:rgba(245,200,66,.3);color:#f5c842;}
    @media(max-width:768px){.board-table th:nth-child(3),.board-table td:nth-child(3),.board-table th:nth-child(5),.board-table td:nth-child(5){display:none;}.board-search-input{width:140px;}.board-toolbar{flex-direction:column;align-items:flex-start;}}
  </style>
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
<?php require_once dirname(__DIR__, 2) . '/core/helpers/header.php'; ?>

<main id="main-content" role="main">

  <section class="page-hero" aria-label="자유게시판 헤더">
    <div class="g-inner"><h1 style="font-size:clamp(1.5rem,4vw,2.25rem);color:#fff;margin-bottom:.5rem;">
        <span style="color:#f5c842;">자유</span>게시판
      </h1>
      <p style="color:#8898aa;font-size:clamp(.9rem,2vw,1rem);">아바타 바카라 정보 공유 · 후기 · 커뮤니티</p>
    </div>
  </section>

  <div class="g-inner">
    <div class="board-wrap">

      <!-- 툴바 -->
      <div class="board-toolbar">
        <div style="display:flex;gap:.75rem;flex-wrap:wrap;align-items:center;">
          <div class="board-filter" role="group" aria-label="카테고리 필터">
            <button class="filter-btn active" data-cat="all" aria-pressed="true">전체</button>
            <button class="filter-btn" data-cat="공지" aria-pressed="false">공지</button>
            <button class="filter-btn" data-cat="후기" aria-pressed="false">후기</button>
            <button class="filter-btn" data-cat="정보" aria-pressed="false">정보</button>
            <button class="filter-btn" data-cat="질문" aria-pressed="false">질문</button>
          </div>
          <div class="board-search" role="search">
            <label for="board-search-input" style="position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0,0,0,0);">게시글 검색</label>
            <input type="search" id="board-search-input" class="board-search-input"
              placeholder="검색어 입력..." maxlength="50" aria-label="게시글 검색">
            <button type="button" class="board-search-btn" aria-label="검색">🔍</button>
          </div>
        </div>
        <a href="#write-form" class="board-write-btn" aria-label="글쓰기">✏️ 글쓰기</a>
      </div>

      <!-- 게시글 목록 -->
      <div role="region" aria-label="게시글 목록">
        <table class="board-table" aria-label="자유게시판 게시글 목록">
          <thead>
            <tr>
              <th scope="col" style="width:70px;">분류</th>
              <th scope="col">제목</th>
              <th scope="col" style="width:100px;">작성자</th>
              <th scope="col" style="width:90px;">날짜</th>
              <th scope="col" style="width:60px;text-align:center;">조회</th>
              <th scope="col" style="width:60px;text-align:center;">댓글</th>
            </tr>
          </thead>
          <tbody id="board-tbody">
<?php foreach ($demo_posts as $post):
    $cat_color = $cat_colors[$post['cat']] ?? '#8898aa';
    $safe_title = htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8');
    $safe_author = htmlspecialchars($post['author'], ENT_QUOTES, 'UTF-8');
    $safe_date = htmlspecialchars($post['date'], ENT_QUOTES, 'UTF-8');
    $safe_cat = htmlspecialchars($post['cat'], ENT_QUOTES, 'UTF-8');
?>
            <tr data-cat="<?= $safe_cat ?>">
              <td><span class="post-cat" style="background:<?= $cat_color ?>22;color:<?= $cat_color ?>;"><?= $safe_cat ?></span></td>
              <td>
                <a href="/free-board/<?= (int)$post['id'] ?>/" class="post-title-link"><?= $safe_title ?></a>
                <?php if ($post['hot']): ?><span class="post-hot" aria-label="인기글">HOT</span><?php endif; ?>
              </td>
              <td class="post-author"><?= $safe_author ?></td>
              <td class="post-date"><?= $safe_date ?></td>
              <td class="post-views"><?= number_format((int)$post['views']) ?></td>
              <td class="post-comments"><?= (int)$post['comments'] ?></td>
            </tr>
<?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- 페이지네이션 -->
      <nav class="board-pagination" aria-label="게시판 페이지 이동">
        <button class="page-btn" aria-label="이전 페이지">‹</button>
        <button class="page-btn active" aria-label="1페이지" aria-current="page">1</button>
        <button class="page-btn" aria-label="2페이지">2</button>
        <button class="page-btn" aria-label="3페이지">3</button>
        <button class="page-btn" aria-label="다음 페이지">›</button>
      </nav>

      <!-- 글쓰기 폼 -->
      <section id="write-form" style="margin-top:2.5rem;background:#071a2e;border:1px solid rgba(245,200,66,.15);border-radius:16px;padding:2rem;" aria-labelledby="write-form-title">
        <h2 id="write-form-title" style="font-size:1.1rem;font-weight:700;color:#f5c842;margin-bottom:1.25rem;padding-bottom:.75rem;border-bottom:1px solid rgba(245,200,66,.12);">✏️ 글쓰기</h2>
        <form id="post-form" novalidate aria-label="게시글 작성 폼">
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
            <div>
              <label style="display:block;font-size:.875rem;font-weight:600;color:#c8d8e8;margin-bottom:.35rem;" for="post-cat-select">분류 <span style="color:#fc8181;">*</span></label>
              <select id="post-cat-select" style="background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:8px;color:#c8d8e8;font-family:inherit;font-size:.875rem;padding:.6rem .875rem;width:100%;" required>
                <option value="" disabled selected>분류 선택</option>
                <option value="후기">후기</option>
                <option value="정보">정보</option>
                <option value="질문">질문</option>
              </select>
            </div>
            <div>
              <label style="display:block;font-size:.875rem;font-weight:600;color:#c8d8e8;margin-bottom:.35rem;" for="post-nickname">닉네임 <span style="color:#fc8181;">*</span></label>
              <input type="text" id="post-nickname" style="background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:8px;color:#c8d8e8;font-family:inherit;font-size:.875rem;padding:.6rem .875rem;width:100%;" placeholder="닉네임" maxlength="20" required>
            </div>
          </div>
          <div style="margin-bottom:1rem;">
            <label style="display:block;font-size:.875rem;font-weight:600;color:#c8d8e8;margin-bottom:.35rem;" for="post-title-input">제목 <span style="color:#fc8181;">*</span></label>
            <input type="text" id="post-title-input" style="background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:8px;color:#c8d8e8;font-family:inherit;font-size:.875rem;padding:.6rem .875rem;width:100%;" placeholder="제목을 입력하세요" maxlength="100" required>
          </div>
          <div style="margin-bottom:1.25rem;">
            <label style="display:block;font-size:.875rem;font-weight:600;color:#c8d8e8;margin-bottom:.35rem;" for="post-content">내용 <span style="color:#fc8181;">*</span></label>
            <textarea id="post-content" style="background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:8px;color:#c8d8e8;font-family:inherit;font-size:.875rem;padding:.75rem 1rem;width:100%;resize:vertical;min-height:150px;" placeholder="내용을 입력하세요 (최대 2000자)" maxlength="2000" required></textarea>
          </div>
          <button type="submit" style="background:linear-gradient(135deg,#f5c842,#e6a800);border:none;border-radius:8px;color:#040f1c;cursor:pointer;font-family:inherit;font-size:.95rem;font-weight:700;min-height:48px;padding:.75rem 2rem;transition:all .25s;" aria-label="게시글 등록">등록하기</button>
        </form>
      </section>

    </div>
  </div>

</main>

<?php require_once dirname(__DIR__, 2) . '/core/helpers/footer.php'; ?>

<script>
(function () {
  'use strict';
  /* 카테고리 필터 */
  var filterBtns = document.querySelectorAll('.filter-btn');
  var rows = document.querySelectorAll('#board-tbody tr');
  filterBtns.forEach(function (btn) {
    btn.addEventListener('click', function () {
      filterBtns.forEach(function (b) { b.classList.remove('active'); b.setAttribute('aria-pressed', 'false'); });
      btn.classList.add('active');
      btn.setAttribute('aria-pressed', 'true');
      var cat = btn.getAttribute('data-cat');
      rows.forEach(function (row) {
        row.style.display = (cat === 'all' || row.getAttribute('data-cat') === cat) ? '' : 'none';
      });
    });
  });

  /* 검색 */
  var searchInput = document.getElementById('board-search-input');
  var searchBtn = document.querySelector('.board-search-btn');
  function doSearch() {
    var q = searchInput ? searchInput.value.trim().toLowerCase() : '';
    rows.forEach(function (row) {
      var title = row.querySelector('.post-title-link');
      row.style.display = (!q || (title && title.textContent.toLowerCase().includes(q))) ? '' : 'none';
    });
  }
  if (searchBtn) searchBtn.addEventListener('click', doSearch);
  if (searchInput) searchInput.addEventListener('keydown', function (e) { if (e.key === 'Enter') doSearch(); });

  /* 글쓰기 폼 */
  var postForm = document.getElementById('post-form');
  if (postForm) {
    postForm.addEventListener('submit', function (e) {
      e.preventDefault();
      var cat = document.getElementById('post-cat-select').value;
      var nick = document.getElementById('post-nickname').value.trim();
      var title = document.getElementById('post-title-input').value.trim();
      var content = document.getElementById('post-content').value.trim();
      if (!cat || !nick || !title || !content) { alert('모든 필수 항목을 입력해 주세요.'); return; }
      alert('게시글이 등록되었습니다. (로그인 후 이용 가능)');
      postForm.reset();
    });
  }
}());
</script>
</body>
</html>

