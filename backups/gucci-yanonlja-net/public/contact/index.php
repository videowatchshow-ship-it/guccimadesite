<?php



/**



 * 연락처 페이지 — 구찌야놀자



 * ref: https://schema.org/ContactPage



 * ref: https://developer.mozilla.org/en-US/docs/Web/HTML/Element/address



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



$page_url   = $site_url . '/contact/';



$page_title = '문의하기 | 연락처 — 구찌야놀자';



$page_desc  = '구찌야놀자 문의하기. 텔레그램, 카카오톡으로 빠르게 연락하세요. 24시간 상담 가능.';



$page_img   = $site_url . '/아바타-바카라-구찌야-놀자.png';



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



  {"@context":"https://schema.org","@type":"ContactPage","name":"구찌야놀자 문의하기","url":"https://xn--2e0bj1fruw33b6ti.net/contact/","description":"텔레그램, 카카오톡으로 빠르게 연락하세요."}



  </script>



  <script type="application/ld+json">



  {"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"홈","item":"https://xn--2e0bj1fruw33b6ti.net/"},{"@type":"ListItem","position":2,"name":"연락처","item":"https://xn--2e0bj1fruw33b6ti.net/contact/"}]}



  </script>



  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>



  <link rel="preload" href="/assets/css/mobile-responsive.css" as="style">



  <link rel="stylesheet" href="/assets/css/mobile-responsive.css">



  <style>



    @font-face{font-family:'SchoolSafetyTteokbokki';src:url('https://cdn.jsdelivr.net/gh/projectnoonnu/2510-1@1.0/HakgyoansimTTeokbokkiB.woff2') format('woff2');font-weight:normal;font-style:normal;font-display:swap;}



    body{font-family:'SchoolSafetyTteokbokki',sans-serif;background:#040f1c;color:#c8d8e8;margin:0;padding:0;min-height:100vh;display:flex;flex-direction:column;}



    .g-inner{max-width:1200px;margin:0 auto;padding:0 1.5rem;}



    .page-hero{background:linear-gradient(180deg,#071a2e 0%,#040f1c 100%);padding:2.5rem 0 2rem;border-bottom:1px solid rgba(245,200,66,.15);}



    .contact-layout{display:grid;grid-template-columns:1fr 1fr;gap:2rem;padding:2.5rem 0 3rem;}



    .contact-card{background:#071a2e;border:1px solid rgba(245,200,66,.15);border-radius:16px;padding:2rem;display:flex;flex-direction:column;gap:1.5rem;}



    .contact-channel{display:flex;align-items:center;gap:1.25rem;padding:1.25rem;border-radius:12px;text-decoration:none;transition:all .3s ease;min-height:80px;}



    .contact-channel:focus-visible{outline:3px solid #f5c842;outline-offset:3px;}



    .contact-telegram{background:linear-gradient(135deg,rgba(0,136,204,.15),rgba(0,136,204,.08));border:1px solid rgba(0,136,204,.25);color:#63b3ed;}



    .contact-telegram:hover{background:linear-gradient(135deg,rgba(0,136,204,.25),rgba(0,136,204,.15));border-color:rgba(0,136,204,.5);transform:translateY(-3px);box-shadow:0 8px 24px rgba(0,136,204,.2);}



    .contact-kakao{background:linear-gradient(135deg,rgba(254,229,0,.12),rgba(254,229,0,.06));border:1px solid rgba(254,229,0,.2);color:#f6e05e;}



    .contact-kakao:hover{background:linear-gradient(135deg,rgba(254,229,0,.22),rgba(254,229,0,.12));border-color:rgba(254,229,0,.4);transform:translateY(-3px);box-shadow:0 8px 24px rgba(254,229,0,.15);}



    .contact-icon{width:56px;height:56px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.75rem;flex-shrink:0;}



    .contact-telegram .contact-icon{background:rgba(0,136,204,.2);}



    .contact-kakao .contact-icon{background:rgba(254,229,0,.15);}



    .contact-info-title{font-size:1.05rem;font-weight:700;display:block;margin-bottom:.2rem;}



    .contact-info-id{font-size:.875rem;opacity:.8;display:block;margin-bottom:.2rem;}



    .contact-info-desc{font-size:.8rem;opacity:.6;}



    .contact-form-wrap{background:#071a2e;border:1px solid rgba(245,200,66,.15);border-radius:16px;padding:2rem;}



    .contact-form-title{font-size:1.1rem;font-weight:700;color:#f5c842;margin-bottom:1.25rem;padding-bottom:.75rem;border-bottom:1px solid rgba(245,200,66,.12);}



    .form-group{display:flex;flex-direction:column;gap:.35rem;margin-bottom:1.1rem;}



    .form-label{font-size:.875rem;font-weight:600;color:#c8d8e8;}



    .form-input,.form-select,.form-textarea{background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:8px;color:#c8d8e8;font-family:inherit;font-size:.875rem;padding:.65rem .875rem;transition:border-color .2s;width:100%;}



    .form-input:focus,.form-select:focus,.form-textarea:focus{outline:none;border-color:rgba(245,200,66,.5);box-shadow:0 0 0 3px rgba(245,200,66,.1);}



    .form-textarea{resize:vertical;min-height:120px;}



    .form-submit{width:100%;padding:.8rem;background:linear-gradient(135deg,#f5c842,#e6a800);color:#040f1c;border:none;border-radius:8px;font-family:inherit;font-size:.95rem;font-weight:700;cursor:pointer;transition:all .25s;min-height:48px;margin-top:.25rem;}



    .form-submit:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(245,200,66,.4);}



    .form-submit:focus-visible{outline:3px solid #f5c842;outline-offset:3px;}



    .faq-section{padding:0 0 3rem;}



    .faq-title{font-size:1.3rem;font-weight:700;color:#f5c842;margin-bottom:1.25rem;}



    .faq-list{display:flex;flex-direction:column;gap:.75rem;}



    .faq-item{background:rgba(255,255,255,.03);border:1px solid rgba(245,200,66,.1);border-radius:10px;overflow:hidden;}



    .faq-q{width:100%;background:none;border:none;color:#c8d8e8;cursor:pointer;display:flex;align-items:center;justify-content:space-between;font-family:inherit;font-size:.9rem;font-weight:600;gap:1rem;min-height:52px;padding:.875rem 1.25rem;text-align:left;transition:color .2s;}



    .faq-q:hover,.faq-q[aria-expanded="true"]{color:#f5c842;}



    .faq-q:focus-visible{outline:2px solid #f5c842;outline-offset:-2px;}



    .faq-arrow{flex-shrink:0;transition:transform .3s;font-size:.75rem;}



    .faq-q[aria-expanded="true"] .faq-arrow{transform:rotate(180deg);}



    .faq-a{display:none;font-size:.875rem;color:#8898aa;line-height:1.75;padding:.25rem 1.25rem 1rem;}



    .faq-a.open{display:block;}



    @media(max-width:768px){.contact-layout{grid-template-columns:1fr;}}



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







  <div class="g-inner">



    <div class="contact-layout">







      <!-- 연락처 채널 -->



      <div>



        <div class="contact-card">



          <h2 style="font-size:1.1rem;font-weight:700;color:#f5c842;margin:0;padding-bottom:.75rem;border-bottom:1px solid rgba(245,200,66,.12);">빠른 연락</h2>







          <address style="font-style:normal;display:flex;flex-direction:column;gap:1rem;">



            <a href="https://t.me/Fury0079"



              class="contact-channel contact-telegram"



              rel="noopener noreferrer" target="_blank"



              aria-label="텔레그램 퓨리 실장에게 연락하기 (새 탭에서 열림)">



              <div class="contact-icon" aria-hidden="true">📱</div>



              <div>



                <span class="contact-info-title">텔레그램</span>



                <span class="contact-info-id">@Fury0079</span>



                <span class="contact-info-desc">가장 빠른 응답 · 24시간</span>



              </div>



            </a>







            <a href="https://open.kakao.com/o/gucciyanolja"



              class="contact-channel contact-kakao"



              rel="noopener noreferrer" target="_blank"



              aria-label="카카오톡 오픈채팅으로 연락하기 (새 탭에서 열림)">



              <div class="contact-icon" aria-hidden="true">💬</div>



              <div>



                <span class="contact-info-title">카카오톡 오픈채팅</span>



                <span class="contact-info-id">구찌야놀자</span>



                <span class="contact-info-desc">오픈채팅 · 실시간 상담</span>



              </div>



            </a>



          </address>







          <div style="background:rgba(245,200,66,.06);border:1px solid rgba(245,200,66,.12);border-radius:10px;padding:1.25rem;">



            <h3 style="font-size:.9rem;font-weight:700;color:#f5c842;margin-bottom:.75rem;">운영 시간</h3>



            <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:.5rem;">



              <li style="font-size:.875rem;color:#8898aa;display:flex;justify-content:space-between;"><span>평일</span><span style="color:#c8d8e8;">오전 10:00 ~ 오전 02:00</span></li>



              <li style="font-size:.875rem;color:#8898aa;display:flex;justify-content:space-between;"><span>주말</span><span style="color:#c8d8e8;">오전 10:00 ~ 오전 02:00</span></li>



              <li style="font-size:.875rem;color:#8898aa;display:flex;justify-content:space-between;"><span>공휴일</span><span style="color:#68d391;">정상 운영</span></li>



            </ul>



          </div>



        </div>



      </div>







      <!-- 문의 폼 -->



      <div class="contact-form-wrap">



        <h2 class="contact-form-title">📝 문의 남기기</h2>



        <form id="contact-form" novalidate aria-label="문의 폼">



          <div class="form-group">



            <label class="form-label" for="contact-name">이름 <span style="color:#fc8181;">*</span></label>



            <input type="text" id="contact-name" name="name" class="form-input"



              placeholder="홍길동" maxlength="50" required autocomplete="name" aria-required="true">



          </div>



          <div class="form-group">



            <label class="form-label" for="contact-contact">연락처 <span style="color:#fc8181;">*</span></label>



            <input type="text" id="contact-contact" name="contact" class="form-input"



              placeholder="텔레그램 ID 또는 전화번호" maxlength="50" required aria-required="true">



          </div>



          <div class="form-group">



            <label class="form-label" for="contact-type">문의 유형</label>



            <select id="contact-type" name="type" class="form-select">



              <option value="" disabled selected>문의 유형 선택</option>



              <option value="game">게임 문의</option>



              <option value="reservation">예약 문의</option>



              <option value="streaming">스트리밍 문의</option>



              <option value="account">계정 문의</option>



              <option value="other">기타</option>



            </select>



          </div>



          <div class="form-group">



            <label class="form-label" for="contact-message">문의 내용 <span style="color:#fc8181;">*</span></label>



            <textarea id="contact-message" name="message" class="form-textarea"



              placeholder="문의 내용을 입력해 주세요." maxlength="1000" required aria-required="true"></textarea>



          </div>



          <button type="submit" class="form-submit" aria-label="문의 전송">전송하기</button>



          <div id="contact-success" style="display:none;background:rgba(72,187,120,.1);border:1px solid rgba(72,187,120,.25);border-radius:8px;padding:.875rem;color:#68d391;font-size:.875rem;text-align:center;margin-top:.75rem;" role="alert" aria-live="assertive">



            ✅ 문의가 접수되었습니다. 빠르게 연락드리겠습니다.



          </div>



        </form>



      </div>







    </div>







    <!-- FAQ -->



    <section class="faq-section" aria-labelledby="faq-title">



      <h2 id="faq-title" class="faq-title">자주 묻는 질문</h2>



      <div class="faq-list">



        <?php



        $faqs = [



            ['q'=>'처음 이용하는데 어떻게 시작하나요?','a'=>'텔레그램(@Fury0079) 또는 카카오톡 오픈채팅으로 연락주시면 담당자가 자세히 안내해 드립니다. Google 계정으로 로그인 후 스트리밍 페이지에서 바로 시청 가능합니다.'],



            ['q'=>'모바일에서도 이용 가능한가요?','a'=>'네, 구찌야놀자는 모바일 최적화 반응형 구조로 설계되어 스마트폰과 태블릿에서도 편안하게 이용할 수 있습니다.'],



            ['q'=>'최소 베팅 금액은 얼마인가요?','a'=>'게임 종류에 따라 다르며, 아바타 바카라는 최소 10,000원부터 시작합니다. 자세한 내용은 게임 안내 페이지를 참고하세요.'],



            ['q'=>'입출금은 어떻게 하나요?','a'=>'담당자를 통해 안전하게 처리됩니다. 텔레그램 또는 카카오톡으로 문의해 주시면 자세히 안내해 드립니다.'],



            ['q'=>'스트리밍이 끊기면 어떻게 하나요?','a'=>'페이지를 새로고침하거나 다른 브라우저로 접속해 보세요. 문제가 지속되면 텔레그램으로 즉시 연락주시면 기술 지원을 받으실 수 있습니다.'],



        ];



        foreach ($faqs as $i => $faq):



            $id = 'faq-' . $i;



        ?>



        <div class="faq-item">



          <button class="faq-q" aria-expanded="false" aria-controls="<?= $id ?>" id="btn-<?= $id ?>">



            <?= htmlspecialchars($faq['q'], ENT_QUOTES, 'UTF-8') ?>



            <span class="faq-arrow" aria-hidden="true">▼</span>



          </button>



          <div class="faq-a" id="<?= $id ?>" role="region" aria-labelledby="btn-<?= $id ?>">



            <?= htmlspecialchars($faq['a'], ENT_QUOTES, 'UTF-8') ?>



          </div>



        </div>



        <?php endforeach; ?>



      </div>



    </section>







  </div>







</main>







<?php require_once dirname(__DIR__, 2) . '/core/helpers/footer.php'; ?>







<script>



(function () {



  'use strict';



  /* FAQ 아코디언 */



  document.querySelectorAll('.faq-q').forEach(function (btn) {



    btn.addEventListener('click', function () {



      var expanded = btn.getAttribute('aria-expanded') === 'true';



      var answer = document.getElementById(btn.getAttribute('aria-controls'));



      btn.setAttribute('aria-expanded', expanded ? 'false' : 'true');



      if (answer) answer.classList.toggle('open', !expanded);



    });



  });







  /* 문의 폼 */



  var form = document.getElementById('contact-form');



  var success = document.getElementById('contact-success');



  if (form) {



    form.addEventListener('submit', function (e) {



      e.preventDefault();



      var name = document.getElementById('contact-name').value.trim();



      var contact = document.getElementById('contact-contact').value.trim();



      var msg = document.getElementById('contact-message').value.trim();



      if (!name || !contact || !msg) { alert('필수 항목을 모두 입력해 주세요.'); return; }



      if (success) { success.style.display = 'block'; form.reset(); success.focus(); }



    });



  }



}());



</script>



</body>



</html>







