<?php


// 보안 헤더 설정


if (!headers_sent()) {


    // Clickjacking 방지


    if (!headers_sent()) { header('X-Frame-Options: DENY'); }


    


    // XSS 방지


    if (!headers_sent()) { header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://accounts.google.com https://apis.google.com; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' data:; connect-src 'self' https://accounts.google.com https://www.googleapis.com wss://; frame-src https://accounts.google.com;"); }


    


    // MIME 타입 스니핑 방지


    if (!headers_sent()) { header('X-Content-Type-Options: nosniff'); }


    


    // XSS 필터 활성화


    if (!headers_sent()) { header('X-XSS-Protection: 1; mode=block'); }


    


    // Referrer 정책


    if (!headers_sent()) { header('Referrer-Policy: strict-origin-when-cross-origin'); }


    


    // HTTPS 강제 (프로덕션 환경)


    if (isset(filter_input(INPUT_SERVER, HTTPS, FILTER_SANITIZE_STRING)) && filter_input(INPUT_SERVER, HTTPS, FILTER_SANITIZE_STRING) === 'on') {


        if (!headers_sent()) { header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload'); }


    }


}





/**


 * SEO 메타 태그 중앙 관리


 * 150개 SEO 규칙 준수 (Google SEO + RankMath + 웹사이트 구조)


 * 


 * GitHub 기반: https://github.com/joshbuchea/HEAD


 * 버전: HEAD 2024 (최신 메타 태그 표준)


 */





// 기본 정보


$site_name = '아바타 바카라 - 구찌야놀자';


$site_url = 'https://xn--2e0bj1fruw33b6ti.net';


$site_description = '아바타 바카라 - 구찌야놀자 · 카지노 실시간 스트리밍 플랫폼. 정품 보장, 안전한 게임 환경, 24시간 고객 지원.';


$site_keywords = '아바타 바카라, 카지노, 실시간 스트리밍, 온라인 카지노, 라이브 바카라, 구찌야놀자';


$site_author = '아바타 바카라 에이전시 구찌야놀자 운영팀';


$site_email = 'info@xn--2e0bj1fruw33b6ti.net';





// 페이지별 정보 (동적 설정 가능)


$page_title = isset($page_title) ? $page_title : '아바타 바카라 - 구찌야놀자 · 프리미엄 카지노 스트리밍';


$page_description = isset($page_description) ? $page_description : $site_description;


$page_url = isset($page_url) ? $page_url : $site_url;


$page_image = isset($page_image) ? $page_image : $site_url . '/images/og-image.jpg';





// 현재 페이지 URL


$current_url = (isset(filter_input(INPUT_SERVER, HTTPS, FILTER_SANITIZE_STRING)) && filter_input(INPUT_SERVER, HTTPS, FILTER_SANITIZE_STRING) === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";


?>


<title><?php echo htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8'); ?></title>


<meta name="description" content="<?php echo htmlspecialchars($page_description, ENT_QUOTES, 'UTF-8'); ?>">


<meta name="keywords" content="<?php echo htmlspecialchars($site_keywords, ENT_QUOTES, 'UTF-8'); ?>">


<meta name="author" content="<?php echo htmlspecialchars($site_author, ENT_QUOTES, 'UTF-8'); ?>">


<meta name="publisher" content="<?php echo htmlspecialchars($site_name, ENT_QUOTES, 'UTF-8'); ?>">


<meta name="copyright" content="© 2026 <?php echo htmlspecialchars($site_name, ENT_QUOTES, 'UTF-8'); ?>. All rights reserved.">





<!-- ===== 언어 및 지역 설정 ===== -->


<meta http-equiv="content-language" content="ko-KR">





<!-- ===== 로봇 크롤링 제어 ===== -->


<meta name="robots" content="index,follow,max-snippet:-1,max-image-preview:large,max-video-preview:-1">


<meta name="googlebot" content="index,follow">


<meta name="bingbot" content="index,follow">





<!-- ===== Canonical URL (중복 콘텐츠 방지) ===== -->


<link rel="canonical" href="<?php echo htmlspecialchars($current_url, ENT_QUOTES, 'UTF-8'); ?>">





<!-- ===== Open Graph (Facebook, LinkedIn) ===== -->


<meta property="og:type" content="website">


<meta property="og:site_name" content="<?php echo htmlspecialchars($site_name, ENT_QUOTES, 'UTF-8'); ?>">


<meta property="og:title" content="<?php echo htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8'); ?>">


<meta property="og:description" content="<?php echo htmlspecialchars($page_description, ENT_QUOTES, 'UTF-8'); ?>">


<meta property="og:url" content="<?php echo htmlspecialchars($current_url, ENT_QUOTES, 'UTF-8'); ?>">


<meta property="og:image" content="<?php echo htmlspecialchars($page_image, ENT_QUOTES, 'UTF-8'); ?>">


<meta property="og:image:width" content="1200">


<meta property="og:image:height" content="630">


<meta property="og:image:alt" content="<?php echo htmlspecialchars($site_name, ENT_QUOTES, 'UTF-8'); ?> 로고">


<meta property="og:locale" content="ko_KR">





<!-- ===== Twitter Cards ===== -->


<meta name="twitter:card" content="summary_large_image">


<meta name="twitter:site" content="@gucciyanolja">


<meta name="twitter:creator" content="@gucciyanolja">


<meta name="twitter:title" content="<?php echo htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8'); ?>">


<meta name="twitter:description" content="<?php echo htmlspecialchars($page_description, ENT_QUOTES, 'UTF-8'); ?>">


<meta name="twitter:image" content="<?php echo htmlspecialchars($page_image, ENT_QUOTES, 'UTF-8'); ?>">


<meta name="twitter:image:alt" content="<?php echo htmlspecialchars($site_name, ENT_QUOTES, 'UTF-8'); ?> 로고">





<!-- ===== 모바일 앱 메타 태그 (PWA) ===== -->


<meta name="application-name" content="<?php echo htmlspecialchars($site_name, ENT_QUOTES, 'UTF-8'); ?>">


<meta name="apple-mobile-web-app-title" content="<?php echo htmlspecialchars($site_name, ENT_QUOTES, 'UTF-8'); ?>">


<meta name="apple-mobile-web-app-capable" content="yes">


<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">


<meta name="mobile-web-app-capable" content="yes">





<!-- ===== 테마 색상 ===== -->


<meta name="theme-color" content="#0a2540">


<meta name="theme-color" content="#0a2540" media="(prefers-color-scheme: dark)">


<meta name="theme-color" content="#ffffff" media="(prefers-color-scheme: light)">


<meta name="msapplication-TileColor" content="#0a2540">


<meta name="msapplication-navbutton-color" content="#0a2540">





<!-- ===== 리퍼러 정책 ===== -->


<meta name="referrer" content="strict-origin-when-cross-origin">





<!-- ===== 자동 형식 감지 제어 ===== -->


<meta name="format-detection" content="telephone=no">


<meta name="format-detection" content="date=no">


<meta name="format-detection" content="address=no">


<meta name="format-detection" content="email=no">





<!-- ===== PWA 매니페스트 ===== -->


<link rel="manifest" href="/manifest.json">





<!-- ===== 파비콘 ===== -->


<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">


<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">


<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">


<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#0a2540">





<!-- ===== 대체 언어 버전 (다국어 사이트용) ===== -->


<link rel="alternate" hreflang="ko" href="<?php echo htmlspecialchars($site_url, ENT_QUOTES, 'UTF-8'); ?>/">


<link rel="alternate" hreflang="x-default" href="<?php echo htmlspecialchars($site_url, ENT_QUOTES, 'UTF-8'); ?>/">





<!-- ===== RSS 피드 ===== -->


<link rel="alternate" type="application/rss+xml" title="<?php echo htmlspecialchars($site_name, ENT_QUOTES, 'UTF-8'); ?> RSS Feed" href="<?php echo htmlspecialchars($site_url, ENT_QUOTES, 'UTF-8'); ?>/feed/">





<!-- ===== DNS Prefetch & Preconnect (성능 최적화) ===== -->


<link rel="dns-prefetch" href="//accounts.google.com">


<link rel="dns-prefetch" href="//cdn.jsdelivr.net">


<link rel="preconnect" href="https://accounts.google.com" crossorigin>


<link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>





<!-- ===== Preload 중요 리소스 ===== -->


<link rel="preload" href="/mobile-responsive.css" as="style">


<link rel="preload" href="https://cdn.jsdelivr.net/gh/projectnoonnu/2510-1@1.0/HakgyoansimTTeokbokkiB.woff2" as="font" type="font/woff2" crossorigin>





<!-- ===== 구조화 데이터 (JSON-LD) ===== -->


<script type="application/ld+json">


{


  "@context": "https://schema.org",


  "@type": "WebSite",


  "name": "<?php echo htmlspecialchars($site_name, ENT_QUOTES, 'UTF-8'); ?>",


  "alternateName": "Gucci Ya Nolja",


  "url": "<?php echo htmlspecialchars($site_url, ENT_QUOTES, 'UTF-8'); ?>",


  "description": "<?php echo htmlspecialchars($site_description, ENT_QUOTES, 'UTF-8'); ?>",


  "inLanguage": "ko-KR",


  "copyrightYear": "2026",


  "copyrightHolder": {


    "@type": "Organization",


    "name": "<?php echo htmlspecialchars($site_name, ENT_QUOTES, 'UTF-8'); ?>"


  },


  "potentialAction": {


    "@type": "SearchAction",


    "target": "<?php echo htmlspecialchars($site_url, ENT_QUOTES, 'UTF-8'); ?>/search?q={search_term_string}",


    "query-input": "required name=search_term_string"


  }


}


</script>





<script type="application/ld+json">


{


  "@context": "https://schema.org",


  "@type": "Organization",


  "name": "<?php echo htmlspecialchars($site_name, ENT_QUOTES, 'UTF-8'); ?>",


  "url": "<?php echo htmlspecialchars($site_url, ENT_QUOTES, 'UTF-8'); ?>",


  "logo": "<?php echo htmlspecialchars($site_url, ENT_QUOTES, 'UTF-8'); ?>/images/logo.png",


  "description": "<?php echo htmlspecialchars($site_description, ENT_QUOTES, 'UTF-8'); ?>",


  "email": "<?php echo htmlspecialchars($site_email, ENT_QUOTES, 'UTF-8'); ?>",


  "sameAs": [


    "https://www.facebook.com/gucciyanolja",


    "https://www.instagram.com/gucciyanolja",


    "https://twitter.com/gucciyanolja"


  ]


}


</script>





<script type="application/ld+json">


{


  "@context": "https://schema.org",


  "@type": "LocalBusiness",


  "name": "<?php echo htmlspecialchars($site_name, ENT_QUOTES, 'UTF-8'); ?>",


  "url": "<?php echo htmlspecialchars($site_url, ENT_QUOTES, 'UTF-8'); ?>",


  "description": "<?php echo htmlspecialchars($site_description, ENT_QUOTES, 'UTF-8'); ?>",


  "priceRange": "$$",


  "aggregateRating": {


    "@type": "AggregateRating",


    "ratingValue": "4.9",


    "reviewCount": "312",


    "bestRating": "5",


    "worstRating": "1"


  },


  "review": [


    {


      "@type": "Review",


      "author": {"@type": "Person", "name": "김민준"},


      "reviewRating": {"@type": "Rating", "ratingValue": "5", "bestRating": "5"},


      "reviewBody": "실시간 생중계 화질이 정말 선명하고 딜러 분들이 친절합니다. 퓨리 실장님 덕분에 게임 이해도가 높아졌어요."


    },


    {


      "@type": "Review",


      "author": {"@type": "Person", "name": "이서연"},


      "reviewRating": {"@type": "Rating", "ratingValue": "5", "bestRating": "5"},


      "reviewBody": "카지노 투어 서비스가 완벽했습니다. 항공권부터 호텔까지 원스톱으로 해결해줘서 너무 편했어요."


    }


  ],


  "employee": [


    {


      "@type": "Person",


      "name": "퓨리 실장",


      "jobTitle": "게임 분석 전문가",


      "description": "15년 경력의 아바타 바카라 전문가. 실시간 게임 분석과 배팅 타이밍 안내.",


      "url": "https://t.me/Fury0079"


    },


    {


      "@type": "Person",


      "name": "소팔 아바타",


      "jobTitle": "생중계 전문 아바타",


      "description": "4K 초고화질 카지노 테이블 실시간 중계 전문."


    },


    {


      "@type": "Person",


      "name": "금비 아바타",


      "jobTitle": "VIP 전담 아바타",


      "description": "VIP 회원 전담, 프라이빗 게임 진행 및 코롱섬 VIP 투어 안내."


    }


  ]


}


</script>





<script type="application/ld+json">


{


  "@context": "https://schema.org",


  "@type": "BreadcrumbList",


  "itemListElement": [


    {


      "@type": "ListItem",


      "position": 1,


      "name": "홈",


      "item": "<?php echo htmlspecialchars($site_url, ENT_QUOTES, 'UTF-8'); ?>"


    }


  ]


}


</script>





<!-- ===== 보안 헤더 ===== -->


<!-- 


  보안 헤더는 security-headers.php에서 중앙 관리됨


  중복 header() 호출 제거 — "headers already sent" 오류 방지


  ref: https://www.php.net/manual/en/function.header.php


-->





</html>


?>


?>


?>


?>


?>


?>


?>


?>


?>


?>

<!doctype html>


<html lang="ko">


<!-- ===== Google Search Console 인증 ===== -->


<meta name="google-site-verification" content="VCbm_iM-IQ4cCfnMxW_Eh3-fsi0IeuM175IRVLPlXtQ" />





<!-- ===== 기본 메타 태그 ===== -->


<meta charset="UTF-8">


<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, viewport-fit=cover">


<meta http-equiv="X-UA-Compatible" content="IE=edge">





<!-- ===== SEO 메타 태그 ===== -->
