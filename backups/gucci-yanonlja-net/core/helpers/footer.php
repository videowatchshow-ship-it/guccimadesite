<?php
/**
 * 공통 푸터 컴포넌트 — 구찌야놀자
 * ref: https://developer.mozilla.org/en-US/docs/Web/HTML/Element/footer
 * ref: https://schema.org/Organization
 * ref: https://www.w3.org/WAI/ARIA/apg/
 *
 * SEO 기준:
 *  - contentinfo landmark role
 *  - 인터널 링크 (사이트맵 구조)
 *  - 아웃바운드 링크 (rel="noopener noreferrer")
 *  - Schema.org Organization JSON-LD
 *  - 접근성 ARIA 레이블
 */
?>
<style>
  /* ===== 공통 푸터 스타일 ===== */
  @font-face {
    font-family: 'SchoolSafetyTteokbokki';
    src: url('https://cdn.jsdelivr.net/gh/projectnoonnu/2510-1@1.0/HakgyoansimTTeokbokkiB.woff2') format('woff2');
    font-weight: normal;
    font-style: normal;
    font-display: swap;
  }

  .gucci-footer,
  .gucci-footer * {
    font-family: 'SchoolSafetyTteokbokki', sans-serif;
  }

  .gucci-footer {
    background: linear-gradient(180deg, #071a2e 0%, #040f1c 100%);
    border-top: 2px solid rgba(245, 200, 66, 0.25);
    color: #8898aa;
    padding: 3rem 0 1.5rem;
    margin-top: auto;
  }

  .gucci-footer-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
  }

  /* ── 상단 그리드 */
  .gucci-footer-content {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    gap: 2.5rem;
    padding-bottom: 2.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
  }

  @media (max-width: 1024px) {
    .gucci-footer-content {
      grid-template-columns: 1fr 1fr;
      gap: 2rem;
    }
  }

  @media (max-width: 600px) {
    .gucci-footer-content {
      grid-template-columns: 1fr;
      gap: 1.5rem;
    }
  }

  /* ── 브랜드 컬럼 */
  .footer-brand-logo {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    text-decoration: none;
    margin-bottom: 1rem;
  }

  .footer-brand-img {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid rgba(245, 200, 66, 0.4);
  }

  .footer-brand-name {
    font-size: 1.2rem;
    font-weight: 700;
    color: #f5c842;
  }

  .footer-brand-desc {
    font-size: 0.9rem;
    line-height: 1.7;
    color: #6b7c93;
    margin-bottom: 1.25rem;
  }

  /* ── 소셜 링크 */
  .footer-social-links {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
  }

  .footer-social-link {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.45rem 0.9rem;
    background: rgba(245, 200, 66, 0.08);
    border: 1px solid rgba(245, 200, 66, 0.2);
    border-radius: 20px;
    color: #c8d8e8;
    text-decoration: none;
    font-size: 0.85rem;
    transition: all 0.25s ease;
    min-height: 36px;
  }

  .footer-social-link:hover {
    background: rgba(245, 200, 66, 0.18);
    border-color: rgba(245, 200, 66, 0.5);
    color: #f5c842;
    transform: translateY(-2px);
  }

  .footer-social-link:focus-visible {
    outline: 2px solid #f5c842;
    outline-offset: 2px;
  }

  /* ── 링크 컬럼 */
  .footer-section-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: #f5c842;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid rgba(245, 200, 66, 0.15);
    letter-spacing: 0.03em;
  }

  .footer-links {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
  }

  .footer-links li a {
    color: #6b7c93;
    text-decoration: none;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.25rem 0;
    transition: color 0.2s ease, transform 0.2s ease;
    min-height: 32px;
  }

  .footer-links li a:hover {
    color: #f5c842;
    transform: translateX(4px);
  }

  .footer-links li a:focus-visible {
    outline: 2px solid #f5c842;
    outline-offset: 2px;
    border-radius: 3px;
  }

  .footer-links li a .link-arrow {
    font-size: 0.7rem;
    opacity: 0.5;
  }

  /* ── 하단 바 */
  .gucci-footer-bottom {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
    padding-top: 1.5rem;
  }

  .footer-copyright {
    font-size: 0.82rem;
    color: #4a5568;
    line-height: 1.6;
  }

  .footer-copyright strong {
    color: #6b7c93;
  }

  .footer-legal-links {
    display: flex;
    gap: 1.25rem;
    flex-wrap: wrap;
  }

  .footer-legal-links a {
    color: #4a5568;
    text-decoration: none;
    font-size: 0.82rem;
    transition: color 0.2s ease;
    min-height: 32px;
    display: inline-flex;
    align-items: center;
  }

  .footer-legal-links a:hover {
    color: #f5c842;
  }

  .footer-legal-links a:focus-visible {
    outline: 2px solid #f5c842;
    outline-offset: 2px;
    border-radius: 3px;
  }

  /* ── 책임 고지 */
  .footer-disclaimer {
    margin-top: 1.25rem;
    padding: 0.875rem 1rem;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 8px;
    font-size: 0.78rem;
    color: #3d4f61;
    line-height: 1.6;
    text-align: center;
  }

  @media (max-width: 600px) {
    .gucci-footer-inner {
      padding: 0 1rem;
    }

    .gucci-footer-bottom {
      flex-direction: column;
      align-items: flex-start;
    }

    .footer-legal-links {
      gap: 0.75rem;
    }
  }
</style>

<!-- footer role="contentinfo" — https://developer.mozilla.org/en-US/docs/Web/Accessibility/ARIA/Roles/contentinfo_role -->
<footer class="gucci-footer" role="contentinfo" aria-label="사이트 푸터">
  <div class="gucci-footer-inner">

    <div class="gucci-footer-content">

      <!-- ── 브랜드 컬럼 -->
      <div class="footer-brand-col">
        <a href="https://xn--2e0bj1fruw33b6ti.net/" class="footer-brand-logo"
           title="구찌야놀자 홈으로 이동" aria-label="구찌야놀자 홈">
          <img
            src="/assets/images/avatar-baccarat-gucci-play.png"
            alt="구찌야놀자 아바타 바카라 로고"
            class="footer-brand-img"
            width="48" height="48"
            loading="lazy"
            onerror="this.style.display='none'">
          <span class="footer-brand-name">구찌야놀자</span>
        </a>
        <p class="footer-brand-desc">
          아바타 바카라 1위 에이전시.<br>
          캄보디아 현장 생방송 · 실시간 스트리밍 플랫폼.<br>
          안정적인 연결 · 현장감 있는 진행.
        </p>
        <!-- 아웃바운드 소셜 링크 — rel="noopener noreferrer" 필수 -->
        <nav class="footer-social-links" aria-label="소셜 미디어 링크">
          <a href="https://t.me/Fury0079"
             class="footer-social-link"
             rel="noopener noreferrer"
             target="_blank"
             aria-label="텔레그램 퓨리 실장 (새 탭에서 열림)">
            📱 텔레그램
          </a>
          <a href="https://open.kakao.com/o/gucciyanolja"
             class="footer-social-link"
             rel="noopener noreferrer"
             target="_blank"
             aria-label="카카오톡 오픈채팅 (새 탭에서 열림)">
            💬 카카오톡
          </a>
        </nav>
      </div>

      <!-- ── 서비스 인터널 링크 -->
      <nav aria-label="서비스 메뉴">
        <h2 class="footer-section-title">서비스</h2>
        <ul class="footer-links">
          <li>
            <a href="https://xn--2e0bj1fruw33b6ti.net/#streaming">
              <span class="link-arrow">▶</span>실시간 LIVE 스트리밍
            </a>
          </li>
          <li>
            <a href="https://xn--2e0bj1fruw33b6ti.net/streaming/">
              <span class="link-arrow">▶</span>지난 방송 보기
            </a>
          </li>
          <li>
            <a href="https://xn--2e0bj1fruw33b6ti.net/streaming/mobile-chat.html">
              <span class="link-arrow">▶</span>모바일 채팅 방송
            </a>
          </li>
          <li>
            <a href="https://xn--2e0bj1fruw33b6ti.net/games/">
              <span class="link-arrow">▶</span>게임 안내
            </a>
          </li>
          <li>
            <a href="https://xn--2e0bj1fruw33b6ti.net/reservation/">
              <span class="link-arrow">▶</span>테이블 예약
            </a>
          </li>
        </ul>
      </nav>

      <!-- ── 커뮤니티 인터널 링크 -->
      <nav aria-label="커뮤니티 메뉴">
        <h2 class="footer-section-title">커뮤니티</h2>
        <ul class="footer-links">
          <li>
            <a href="https://xn--2e0bj1fruw33b6ti.net/free-board/">
              <span class="link-arrow">▶</span>자유게시판
            </a>
          </li>
          <li>
            <a href="https://xn--2e0bj1fruw33b6ti.net/contact/">
              <span class="link-arrow">▶</span>문의하기
            </a>
          </li>
          <li>
            <a href="https://xn--2e0bj1fruw33b6ti.net/#about">
              <span class="link-arrow">▶</span>플랫폼 소개
            </a>
          </li>
          <li>
            <a href="https://xn--2e0bj1fruw33b6ti.net/#features">
              <span class="link-arrow">▶</span>서비스 특징
            </a>
          </li>
          <li>
            <a href="https://xn--2e0bj1fruw33b6ti.net/sitemap.xml"
               rel="nofollow">
              <span class="link-arrow">▶</span>사이트맵
            </a>
          </li>
        </ul>
      </nav>

      <!-- ── 아웃바운드 참고 링크 -->
      <nav aria-label="외부 참고 링크">
        <h2 class="footer-section-title">바카라 정보</h2>
        <ul class="footer-links">
          <li>
            <!-- 아웃링크: 바카라 공식 규칙 참고 -->
            <a href="https://www.casinoguide.com/baccarat/"
               rel="noopener noreferrer"
               target="_blank"
               aria-label="바카라 게임 규칙 (새 탭에서 열림)">
              <span class="link-arrow">↗</span>바카라 게임 규칙
            </a>
          </li>
          <li>
            <!-- 아웃링크: 캄보디아 카지노 정보 -->
            <a href="https://www.cambodia-casino.com/"
               rel="noopener noreferrer"
               target="_blank"
               aria-label="캄보디아 카지노 정보 (새 탭에서 열림)">
              <span class="link-arrow">↗</span>캄보디아 카지노
            </a>
          </li>
          <li>
            <!-- 아웃링크: 책임감 있는 게임 -->
            <a href="https://www.gamcare.org.uk/"
               rel="noopener noreferrer"
               target="_blank"
               aria-label="책임감 있는 게임 안내 (새 탭에서 열림)">
              <span class="link-arrow">↗</span>책임감 있는 게임
            </a>
          </li>
          <li>
            <a href="https://xn--2e0bj1fruw33b6ti.net/#faq">
              <span class="link-arrow">▶</span>자주 묻는 질문
            </a>
          </li>
          <li>
            <a href="https://xn--2e0bj1fruw33b6ti.net/#contact-quick">
              <span class="link-arrow">▶</span>빠른 문의
            </a>
          </li>
        </ul>
      </nav>

    </div><!-- /.gucci-footer-content -->

    <!-- ── 하단 바 -->
    <div class="gucci-footer-bottom">
      <p class="footer-copyright">
        © 2026 <strong>구찌야놀자 (xn--2e0bj1fruw33b6ti.net)</strong>. All rights reserved.<br>
        아바타 바카라 1위 에이전시 · 캄보디아 현장 생방송
      </p>
      <nav class="footer-legal-links" aria-label="법적 고지 링크">
        <a href="https://xn--2e0bj1fruw33b6ti.net/privacy-policy.php">개인정보처리방침</a>
        <a href="https://xn--2e0bj1fruw33b6ti.net/terms.php">이용약관</a>
        <a href="https://xn--2e0bj1fruw33b6ti.net/contact/">고객센터</a>
      </nav>
    </div>

    <!-- ── 책임 고지 -->
    <p class="footer-disclaimer" role="note">
      본 사이트는 성인(만 19세 이상)을 대상으로 운영됩니다.
      도박은 중독성이 있을 수 있으며, 책임감 있는 게임을 권장합니다.
      캄보디아 현지 법령에 따라 합법적으로 운영됩니다.
    </p>

  </div><!-- /.gucci-footer-inner -->
</footer>

<!-- Schema.org Organization — https://schema.org/Organization -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "구찌야놀자",
  "alternateName": "아바타 바카라 구찌야놀자",
  "url": "https://xn--2e0bj1fruw33b6ti.net",
  "logo": "https://xn--2e0bj1fruw33b6ti.net/assets/images/avatar-baccarat-gucci-play.png",
  "description": "아바타 바카라 1위 에이전시. 캄보디아 현장 생방송 실시간 스트리밍 플랫폼.",
  "contactPoint": {
    "@type": "ContactPoint",
    "contactType": "customer service",
    "availableLanguage": "Korean",
    "url": "https://t.me/Fury0079"
  },
  "sameAs": [
    "https://t.me/Fury0079"
  ]
}
</script>
