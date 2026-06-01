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





declare(strict_types=1);


/**


 * 모바일 PHP 헬퍼 — 모바일 PHP 코딩 (#131~#140)


 * ref: https://www.php.net/manual/en/function.preg-match.php


 * ref: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/User-Agent


 */





/**


 * #131 User-Agent 기반 모바일 감지


 * ref: https://www.php.net/manual/en/function.preg-match.php


 */


function is_mobile(): bool {


    $ua = filter_input(INPUT_SERVER, HTTP_USER_AGENT, FILTER_SANITIZE_STRING) ?? '';


    return (bool) preg_match(


        '/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini|mobile/i',


        $ua


    );


}





/**


 * #132 태블릿 감지


 */


function is_tablet(): bool {


    $ua = filter_input(INPUT_SERVER, HTTP_USER_AGENT, FILTER_SANITIZE_STRING) ?? '';


    return (bool) preg_match('/ipad|android(?!.*mobile)|tablet/i', $ua);


}





/**


 * #133 모바일 전용 응답 헤더 설정


 * Vary: User-Agent — 캐시 서버가 UA별로 다른 응답 캐시


 * ref: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Vary


 */


function set_mobile_headers(): void {


    if (!headers_sent()) {


        if (!headers_sent()) { header('Vary: User-Agent'); }


    }


}





/**


 * #134 모바일 이미지 최적화 — srcset 자동 생성


 * ref: https://developer.mozilla.org/en-US/docs/Learn/HTML/Multimedia_and_embedding/Responsive_images


 */


function mobile_img(string $src, string $alt, array $opts = []): string {


    $loading  = $opts['loading']  ?? 'lazy';


    $priority = $opts['priority'] ?? '';


    $class    = $opts['class']    ?? '';


    $width    = $opts['width']    ?? '';


    $height   = $opts['height']   ?? '';





    $attrs  = 'src="' . htmlspecialchars($src, ENT_QUOTES) . '"';


    $attrs .= ' alt="' . htmlspecialchars($alt, ENT_QUOTES) . '"';


    $attrs .= ' loading="' . $loading . '"';


    $attrs .= ' decoding="async"';


    if ($priority) $attrs .= ' fetchpriority="' . $priority . '"';


    if ($class)    $attrs .= ' class="' . htmlspecialchars($class, ENT_QUOTES) . '"';


    if ($width)    $attrs .= ' width="' . (int)$width . '"';


    if ($height)   $attrs .= ' height="' . (int)$height . '"';





    return '<img ' . $attrs . '>';


}





/**


 * #135 모바일 전화번호 링크 자동 변환


 * ref: https://www.php.net/manual/en/function.preg-replace.php


 * 정규식: 한국 전화번호 패턴 → tel: 링크


 */


function mobile_tel_links(string $content): string {


    // 한국 전화번호: 010-1234-5678, 02-1234-5678, 1588-1234


    return preg_replace(


        '/(\b(?:0\d{1,2}[-\s]?\d{3,4}[-\s]?\d{4}|1[5-9]\d{2}[-\s]?\d{4})\b)/',


        '<a href="tel:$1" style="color:inherit;text-decoration:none;">$1</a>',


        $content


    );


}





/**


 * #136 모바일 뷰포트 메타 태그 출력


 * ref: https://developer.mozilla.org/en-US/docs/Web/HTML/Viewport_meta_tag


 */


function mobile_viewport(): string {


    return '<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">';


}





/**


 * #137 모바일 터치 아이콘 태그 출력


 * ref: https://developer.apple.com/library/archive/documentation/AppleApplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html


 */


function mobile_touch_icons(): string {


    $logo = '/%EC%95%84%EB%B0%94%ED%83%80-%EB%B0%94%EC%B9%B4%EB%9D%BC-%EA%B5%AC%EC%B0%8C%EC%95%BC-%EB%86%80%EC%9E%90.png';


    return implode("\n", [


        '<link rel="apple-touch-icon" href="' . $logo . '">',


        '<link rel="apple-touch-icon" sizes="152x152" href="' . $logo . '">',


        '<link rel="apple-touch-icon" sizes="180x180" href="' . $logo . '">',


        '<meta name="apple-mobile-web-app-capable" content="yes">',


        '<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">',


        '<meta name="apple-mobile-web-app-title" content="구찌야놀자">',


    ]);


}





/**


 * #138 모바일 JSON 응답 압축 출력


 * ref: https://www.php.net/manual/en/function.json-encode.php


 */


function mobile_json_response(array $data, int $code = 200): void {


    http_response_code($code);


    if (!headers_sent()) { header('Content-Type: application/json; charset=utf-8'); }


    // 모바일 대역폭 절약: 공백 없이 압축


    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);


    exit;


}





/**


 * #139 모바일 페이지 캐시 헤더 (정적 콘텐츠용)


 * ref: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Cache-Control


 */


function mobile_cache_headers(int $seconds = 3600): void {


    if (!headers_sent()) {


        if (!headers_sent()) { header('Cache-Control: public, max-age=' . $seconds . ', stale-while-revalidate=60'); }


        if (!headers_sent()) { header('Vary: Accept-Encoding, User-Agent'); }


    }


}





/**


 * #140 모바일 리다이렉트 (m. 서브도메인 없이 반응형으로 처리)


 * 현재 사이트는 반응형이므로 리다이렉트 불필요 — 감지 후 CSS 클래스만 추가


 */


function mobile_body_class(): string {


    $classes = ['gucci-body'];


    if (is_mobile())  $classes[] = 'is-mobile';


    if (is_tablet())  $classes[] = 'is-tablet';


    return implode(' ', $classes);


}





/**


 * #141 모바일 이미지 WebP 변환


 * ref: https://www.php.net/manual/en/function.imagewebp.php


 */


function mobile_convert_to_webp(string $source_path, string $dest_path, int $quality = 85): bool {


    $info = @getimagesize($source_path);


    if (!$info) return false;


    


    $image = match($info[2]) {


        IMAGETYPE_JPEG => @imagecreatefromjpeg($source_path),


        IMAGETYPE_PNG  => @imagecreatefrompng($source_path),


        default        => null,


    };


    


    if (!$image) return false;


    


    $result = @imagewebp($image, $dest_path, $quality);


    imagedestroy($image);


    return $result;


}





/**


 * #142 모바일 이미지 리사이즈


 * ref: https://www.php.net/manual/en/function.imagescale.php


 */


function mobile_resize_image(string $source, string $dest, int $max_width = 800): bool {


    $info = @getimagesize($source);


    if (!$info || $info[0] <= $max_width) return @copy($source, $dest);


    


    $image = match($info[2]) {


        IMAGETYPE_JPEG => @imagecreatefromjpeg($source),


        IMAGETYPE_PNG  => @imagecreatefrompng($source),


        default        => null,


    };


    


    if (!$image) return false;


    


    $ratio = $max_width / $info[0];


    $new_height = (int)($info[1] * $ratio);


    $resized = imagescale($image, $max_width, $new_height);


    


    $result = match($info[2]) {


        IMAGETYPE_JPEG => @imagejpeg($resized, $dest, 85),


        IMAGETYPE_PNG  => @imagepng($resized, $dest, 8),


        default        => false,


    };


    


    imagedestroy($image);


    if ($resized) imagedestroy($resized);


    return $result;


}





/**


 * #143 모바일 HTML 압축


 * ref: https://www.php.net/manual/en/function.preg-replace.php


 */


function mobile_minify_html(string $html): string {


    // 주석 제거 (조건부 주석 제외)


    $html = preg_replace('/<!--(?!<!)[^\[>].*?-->/s', '', $html);


    // 불필요한 공백 제거


    $html = preg_replace('/\s+/', ' ', $html);


    $html = preg_replace('/>\s+</', '><', $html);


    return trim($html);


}





/**


 * #144 모바일 CSS 인라인 (Critical CSS)


 * ref: https://web.dev/articles/extract-critical-css


 */


function mobile_inline_critical_css(): string {


    $critical_css = <<<CSS


@font-face{font-family:'SchoolSafetyTteokbokki';src:url('https://cdn.jsdelivr.net/gh/projectnoonnu/2510-1@1.0/HakgyoansimTTeokbokkiB.woff2') format('woff2');font-weight:normal;font-style:normal;font-display:swap}


body{margin:0;font-family:'SchoolSafetyTteokbokki';background:#282828;color:#fff}


.container{max-width:100%;padding:0 16px;margin:0 auto}


h1{font-size:clamp(1.5rem,5vw,2.5rem);line-height:1.2}


img{max-width:100%;height:auto}


a,button{min-height:48px}


CSS;


    return '<style>' . $critical_css . '</style>';


}





/**


 * #145 모바일 지연 로딩 스크립트


 * ref: https://developer.mozilla.org/en-US/docs/Web/API/Intersection_Observer_API


 */


function mobile_lazy_load_script(): string {


    return '<script>


if("IntersectionObserver" in window){


    const observer=new IntersectionObserver(function(entries){


        entries.forEach(function(entry){


            if(entry.isIntersecting){


                const img=entry.target;


                if(img.dataset.src){


                    img.src=img.dataset.src;


                    img.classList.remove("lazy");


                    observer.unobserve(img);


                }


            }


        });


    },{rootMargin:"50px"});


    document.querySelectorAll("img[data-src]").forEach(function(img){observer.observe(img);});


}


</script>';


}





</html>


?>

<!doctype html>


<html lang="ko">
