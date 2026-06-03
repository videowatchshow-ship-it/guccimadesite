<?php
/**
 * 모바일 자유게시판 — 구찌야놀자
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
    $https = $_SERVER['HTTPS'] ?? '';
    if ($https !== '' && $https !== 'off') {
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
    }
}

$ua = (string)($_SERVER['HTTP_USER_AGENT'] ?? '');
if (!preg_match('/Mobile|Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i', $ua)) {
    header('Location: /desktop/free-board/', true, 302);
    exit;
}

$site_url   = 'https://xn--2e0bj1fruw33b6ti.net';
$page_url   = $site_url . '/mobile/free-board/';
$page_title = '모바일 자유게시판 | 커뮤니티 — 구찌야놀자';
$page_desc  = '구찌야놀자 모바일 자유게시판. 아바타 바카라 정보 공유, 후기, 질문 등 자유롭게 소통하세요.';
$page_img   = $site_url . '/assets/images/avatar-baccarat-gucci-play.png';

/* 데모 게시글 (실제 배포 시 DB 조회) */
$posts = [
    ['id'=>10,'cat'=>'후기','title'=>'오늘 바카라 대박났어요 🎰','author'=>'행운의김씨','date'=>'05-22','views'=>342,'hot'=>true],
    ['id'=>9,'cat'=>'정보','title'=>'아바타 바카라 베팅 전략 공유','author'=>'전략가박씨','date'=>'05-22','views'=>521,'hot'=>true],
    ['id'=>8,'cat'=>'질문','title'=>'처음 이용하는데 어떻게 시작하나요?','author'=>'초보자이씨','date'=>'05-21','views'=>189,'hot'=>false],
    ['id'=>7,'cat'=>'후기','title'=>'스트리밍 화질 최고입니다','author'=>'만족한최씨','date'=>'05-21','views'=>267,'hot'=>false],
    ['id'=>6,'cat'=>'정보','title'=>'캄보디아 바카라 규칙 정리','author'=>'정보왕정씨','date'=>'05-20','views'=>445,'hot'=>false],
    ['id'=>5,'cat'=>'후기','title'=>'VIP 서비스 정말 좋네요','author'=>'VIP강씨','date'=>'05-20','views'=>198,'hot'=>false],
    ['id'=>4,'cat'=>'질문','title'=>'모바일에서도 잘 되나요?','author'=>'모바일유저조씨','date'=>'05-19','views'=>156,'hot'=>false],
    ['id'=>1,'cat'=>'공지','title'=>'자유게시판 이용 안내','author'=>'운영팀','date'=>'05-01','views'=>1024,'hot'=>false],
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
  {"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"홈","item":"https://xn--2e0bj1fruw33b6ti.net/"},{"@type":"ListItem","position":2,"name":"자유게시판","item":"https://xn--2e0bj1fruw33b6ti.net/mobile/free-board/"}]}
  </script>
  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
  <link rel="preload" href="/assets/css/mobile-responsive.css" as="style">
  <link rel="stylesheet" href="/assets/css/mobile-responsive.css">
  <link rel="stylesheet" href="/mobile/assets/css/mobile.css">
  <meta name="theme-color" content="#040f1c">
  <style>
    .mb-hero{background:linear-gradient(180deg,#071a2e 0%,#040f1c 100%);padding:1.5rem 1rem 1rem;border-bottom:1px solid rgba(245,200,66,.15);}
    .mb-toolbar{display:flex;align-items:center;justify-content:space-between;gap:.5rem;padding:.75rem 1rem;flex-wrap:wrap;}
    .mb-filter{display:flex;gap:.4rem;overflow-x:auto;-webkit-overflow-scrolling:touch;padding-bottom:.25rem;}
    .mb-filter::-webkit-scrollbar{display:none;}
    .mb-filter-btn{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.1);border-radius:6px;color:#8898aa;cursor:pointer;font-family:inherit;font-size:.75rem;padding:.3rem .7rem;white-space:nowrap;min-height:32px;transition:all .2s;}
    .mb-filter-btn.active,.mb-filter-btn:active{background:rgba(245,200,66,.1);border-color:rgba(245,200,66,.3);color:#f5c842;}
    .mb-write-btn{display:inline-flex;align-items:center;gap:.3rem;background:linear-gradient(135deg,#f5c842,#e6a800);color:#040f1c;border:none;border-radius:6px;cursor:pointer;font-family:inherit;font-size:.78rem;font-weight:700;padding:.4rem .875rem;text-decoration:none;min-height:36px;white-space:nowrap;}
    .mb-list{padding:0 1rem;}
    .mb-post{display:flex;flex-direction:column;gap:.3rem;padding:.875rem 0;border-bottom:1px solid rgba(255,255,255,.05);}
    .mb-post:last-child{border-bottom:none;}
    .mb-post-top{display:flex;align-items:center;gap:.4rem;}
    .mb-post-cat{border-radius:4px;font-size:.65rem;font-weight:700;padding:.15rem .4rem;}
    .mb-post-title{font-size:.875rem;color:#c8d8e8;text-decoration:none;line-height:1.4;flex:1;}
    .mb-post-title:active{color:#f5c842;}
    .mb-post-hot{background:rgba(229,62,62,.15);border-radius:4px;color:#fc8181;font-size:.62rem;font-weight:700;padding:.1rem .35rem;}
    .mb-post-meta{display:flex;align-items:center;gap:.5rem;font-size:.72rem;color:#4a5568;}
    .mb-post-author{color:#6b7c93;}
    .mb-post-views{color:#4a5568;}
    .mb-write-form{padding:1rem;margin:.5rem 1rem 2rem;background:#071a2e;border:1px solid rgba(245,200,66,.15);border-radius:12px;}
    .mb-write-title{font-size:.9rem;font-weight:700;color:#f5c842;margin-bottom:.875rem;}
    .mb-input,.mb-select,.mb-textarea{background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:8px;color:#c8d8e8;font-family:inherit;font-size:.82rem;padding:.6rem .75rem;width:100%;margin-bottom:.75rem;-webkit-appearance:none;}
    .mb-input:focus,.mb-select:focus,.mb-textarea:focus{outline:none;border-color:rgba(245,200,66,.4);}
    .mb-textarea{resize:vertical;min-height:100px;}
    .mb-submit{width:100%;padding:.75rem;background:linear-gradient(135deg,#f5c842,#e6a800);color:#040f1c;border:none;border-radius:8px;font-family:inherit;font-size:.875rem;font-weight:700;cursor:pointer;min-height:48px;}
    .mb-submit:active{transform:scale(.98);}
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
<?php require_once dirname(__DIR__, 3) . '/core/helpers/header.php'; ?>

<main id="main-content" role="main">

  <section class="mb-hero" aria-label="자유게시판 헤더"><h1 style="font-size:clamp(1.25rem,6vw,1.75rem);color:#fff;margin-bottom:.35rem;">
      <span style="color:#f5c842;">자유</span>게시판
    </h1>
    <p style="font-size:.85rem;color:#8898aa;">정보 공유 · 후기 · 커뮤니티</p>
  </section>

  <!-- 툴바 -->
  <div class="mb-toolbar">
    <div class="mb-filter" role="group" aria-label="카테고리 필터">
      <button class="mb-filter-btn active" data-cat="all" aria-pressed="true">전체</button>
      <button class="mb-filter-btn" data-cat="공지" aria-pressed="false">공지</button>
      <button class="mb-filter-btn" data-cat="후기" aria-pressed="false">후기</button>
      <button class="mb-filter-btn" data-cat="정보" aria-pressed="false">정보</button>
      <button class="mb-filter-btn" data-cat="질문" aria-pressed="false">질문</button>
    </div>
    <a href="#mb-write" class="mb-write-btn" aria-label="글쓰기">✏️ 글쓰기</a>
  </div>

  <!-- 게시글 목록 -->
  <section aria-label="게시글 목록">
    <div class="mb-list" id="mb-list">
      <?php foreach ($posts as $p):
          $cat_color = $cat_colors[$p['cat']] ?? '#8898aa';
          $safe_title  = htmlspecialchars($p['title'],  ENT_QUOTES, 'UTF-8');
          $safe_author = htmlspecialchars($p['author'], ENT_QUOTES, 'UTF-8');
          $safe_date   = htmlspecialchars($p['date'],   ENT_QUOTES, 'UTF-8');
          $safe_cat    = htmlspecialchars($p['cat'],    ENT_QUOTES, 'UTF-8');
      ?>
      <article class="mb-post" data-cat="<?= $safe_cat ?>">
        <div class="mb-post-top">
          <span class="mb-post-cat" style="background:<?= $cat_color ?>22;color:<?= $cat_color ?>;"><?= $safe_cat ?></span>
          <a href="/mobile/free-board/<?= (int)$p['id'] ?>/" class="mb-post-title"><?= $safe_title ?></a>
          <?php if ($p['hot']): ?><span class="mb-post-hot" aria-label="인기글">HOT</span><?php endif; ?>
        </div>
        <div class="mb-post-meta">
          <span class="mb-post-author"><?= $safe_author ?></span>
          <span>·</span>
          <span><?= $safe_date ?></span>
          <span>·</span>
          <span class="mb-post-views">👁 <?= number_format((int)$p['views']) ?></span>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- 글쓰기 폼 -->
  <section id="mb-write" class="mb-write-form" aria-labelledby="mb-write-title">
    <h2 id="mb-write-title" class="mb-write-title">✏️ 글쓰기</h2>
    <form id="mb-form" novalidate aria-label="게시글 작성">
      <select class="mb-select" id="mb-cat" required aria-required="true">
        <option value="" disabled selected>분류 선택</option>
        <option value="후기">후기</option>
        <option value="정보">정보</option>
        <option value="질문">질문</option>
      </select>
      <input type="text" class="mb-input" id="mb-nick" placeholder="닉네임" maxlength="20" required aria-required="true" aria-label="닉네임">
      <input type="text" class="mb-input" id="mb-title-input" placeholder="제목" maxlength="100" required aria-required="true" aria-label="제목">
      <textarea class="mb-textarea" id="mb-content" placeholder="내용 (최대 2000자)" maxlength="2000" required aria-required="true" aria-label="내용"></textarea>
      <button type="submit" class="mb-submit" aria-label="등록하기">등록하기</button>
    </form>
  </section>

</main>

<?php require_once dirname(__DIR__, 3) . '/core/helpers/footer.php'; ?>

<script>
(function () {
  'use strict';
  /* 카테고리 필터 */
  var filter_btns = document.querySelectorAll('.mb-filter-btn');
  var posts       = document.querySelectorAll('#mb-list .mb-post');
  filter_btns.forEach(function (btn) {
    btn.addEventListener('click', function () {
      filter_btns.forEach(function (b) { b.classList.remove('active'); b.setAttribute('aria-pressed', 'false'); });
      btn.classList.add('active');
      btn.setAttribute('aria-pressed', 'true');
      var cat = btn.getAttribute('data-cat');
      posts.forEach(function (p) {
        p.style.display = (cat === 'all' || p.getAttribute('data-cat') === cat) ? '' : 'none';
      });
    });
  });
  /* 글쓰기 폼 */
  var form = document.getElementById('mb-form');
  if (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var cat     = document.getElementById('mb-cat').value;
      var nick    = document.getElementById('mb-nick').value.trim();
      var title   = document.getElementById('mb-title-input').value.trim();
      var content = document.getElementById('mb-content').value.trim();
      if (!cat || !nick || !title || !content) { alert('모든 필수 항목을 입력해 주세요.'); return; }
      alert('게시글이 등록되었습니다. (로그인 후 이용 가능)');
      form.reset();
    });
  }
}());
</script>
<script src="/mobile/assets/js/mobile.js" defer></script>
</body>
</html>

