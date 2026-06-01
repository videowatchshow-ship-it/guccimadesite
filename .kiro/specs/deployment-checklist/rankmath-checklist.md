# Rankmath 플러그인 체크리스트 (메인 프론트엔드 - 100개)

## 📋 Rankmath SEO 플러그인 설정 (메인 페이지)

### 1. 기본 설정 (10개)

| # | 항목 | 상태 | 정규식 | 비고 |
|---|------|------|--------|------|
| 1 | Rankmath 플러그인 설치 | ⏳ | `^(installed\|not_installed)$` | WordPress 플러그인 설치 필수 |
| 2 | Rankmath 활성화 | ⏳ | `^(active\|inactive)$` | 플러그인 활성화 상태 |
| 3 | Rankmath 라이선스 | ⏳ | `^(free\|pro\|business)$` | 라이선스 타입 |
| 4 | Rankmath 버전 | ⏳ | `^[0-9]+\.[0-9]+\.[0-9]+$` | 최신 버전 확인 |
| 5 | Rankmath 설정 완료 | ⏳ | `^(complete\|incomplete)$` | 초기 설정 완료 여부 |
| 6 | Rankmath 대시보드 접근 | ⏳ | `^(accessible\|not_accessible)$` | 관리자 대시보드 접근 가능 |
| 7 | Rankmath 분석 활성화 | ⏳ | `^(enabled\|disabled)$` | 분석 기능 활성화 |
| 8 | Rankmath 추적 코드 | ⏳ | `^(configured\|not_configured)$` | Google Analytics 연동 |
| 9 | Rankmath 사이트맵 | ⏳ | `^(generated\|not_generated)$` | XML 사이트맵 생성 |
| 10 | Rankmath 로봇 파일 | ⏳ | `^(configured\|not_configured)$` | robots.txt 설정 |

---

### 2. 메인 페이지 메타 태그 (15개)

| # | 항목 | 상태 | 정규식 | 비고 |
|---|------|------|--------|------|
| 11 | 페이지 제목 | ⏳ | `^.{30,60}$` | 30-60자 권장 |
| 12 | 메타 설명 | ⏳ | `^.{120,160}$` | 120-160자 권장 |
| 13 | 포커스 키워드 | ⏳ | `^[a-zA-Z0-9가-힣 ]+$` | 주요 키워드 설정 |
| 14 | 포커스 키워드 밀도 | ⏳ | `^[0-9]+(\.[0-9]{1,2})?%$` | 1-3% 권장 |
| 15 | 메타 로봇 | ⏳ | `^(index,follow\|noindex,nofollow)$` | 인덱싱 설정 |
| 16 | 캐노니컬 URL | ⏳ | `^https?://[a-z0-9.-]+\.[a-z]{2,}(/.*)?$` | 정규 URL 설정 |
| 17 | Open Graph 제목 | ⏳ | `^.{30,60}$` | SNS 공유용 제목 |
| 18 | Open Graph 설명 | ⏳ | `^.{120,160}$` | SNS 공유용 설명 |
| 19 | Open Graph 이미지 | ⏳ | `^https?://[a-z0-9.-]+\.[a-z]{2,}/.*\.(jpg\|png\|gif)$` | 1200x630px 권장 |
| 20 | Twitter 카드 | ⏳ | `^(summary\|summary_large_image)$` | Twitter 카드 타입 |
| 21 | Twitter 제목 | ⏳ | `^.{30,60}$` | Twitter 공유용 제목 |
| 22 | Twitter 설명 | ⏳ | `^.{120,160}$` | Twitter 공유용 설명 |
| 23 | Twitter 이미지 | ⏳ | `^https?://[a-z0-9.-]+\.[a-z]{2,}/.*\.(jpg\|png\|gif)$` | Twitter 이미지 |
| 24 | Twitter 계정 | ⏳ | `^@[a-zA-Z0-9_]{1,15}$` | Twitter 계정명 |
| 25 | 언어 설정 | ⏳ | `^(ko\|en\|ja\|zh)$` | 페이지 언어 |

---

### 3. 구조화된 데이터 (Schema Markup) (15개)

| # | 항목 | 상태 | 정규식 | 비고 |
|---|------|------|--------|------|
| 26 | Schema.org 마크업 | ⏳ | `^(implemented\|not_implemented)$` | 구조화된 데이터 구현 |
| 27 | Organization 스키마 | ⏳ | `^(present\|absent)$` | 조직 정보 스키마 |
| 28 | Organization 이름 | ⏳ | `^[a-zA-Z0-9가-힣 ]+$` | 조직명 |
| 29 | Organization 로고 | ⏳ | `^https?://[a-z0-9.-]+\.[a-z]{2,}/.*\.(jpg\|png\|svg)$` | 로고 URL |
| 30 | Organization 연락처 | ⏳ | `^[0-9]{2,3}-[0-9]{3,4}-[0-9]{4}$` | 전화번호 형식 |
| 31 | Organization 주소 | ⏳ | `^[a-zA-Z0-9가-힣 ,.-]+$` | 주소 정보 |
| 32 | WebSite 스키마 | ⏳ | `^(present\|absent)$` | 웹사이트 스키마 |
| 33 | WebSite URL | ⏳ | `^https?://[a-z0-9.-]+\.[a-z]{2,}$` | 웹사이트 URL |
| 34 | WebSite 검색 액션 | ⏳ | `^(enabled\|disabled)$` | 검색 기능 스키마 |
| 35 | BreadcrumbList 스키마 | ⏳ | `^(present\|absent)$` | 브레드크럼 스키마 |
| 36 | BreadcrumbList 항목 | ⏳ | `^[0-9]+$` | 브레드크럼 항목 수 |
| 37 | LocalBusiness 스키마 | ⏳ | `^(present\|absent)$` | 지역 비즈니스 스키마 |
| 38 | LocalBusiness 타입 | ⏳ | `^(LocalBusiness\|Restaurant\|Store)$` | 비즈니스 타입 |
| 39 | LocalBusiness 위도 | ⏳ | `^-?[0-9]{1,3}\.[0-9]{1,10}$` | 위도 좌표 |
| 40 | LocalBusiness 경도 | ⏳ | `^-?[0-9]{1,3}\.[0-9]{1,10}$` | 경도 좌표 |

---

### 4. 콘텐츠 최적화 (15개)

| # | 항목 | 상태 | 정규식 | 비고 |
|---|------|------|--------|------|
| 41 | 제목 길이 | ⏳ | `^[0-9]{2,3}$` | 30-60자 권장 |
| 42 | 제목 포커스 키워드 포함 | ⏳ | `^(yes\|no)$` | 제목에 키워드 포함 |
| 43 | 메타 설명 길이 | ⏳ | `^[0-9]{3}$` | 120-160자 권장 |
| 44 | 메타 설명 포커스 키워드 포함 | ⏳ | `^(yes\|no)$` | 설명에 키워드 포함 |
| 45 | 본문 길이 | ⏳ | `^[0-9]{4,}$` | 최소 1,000자 권장 |
| 46 | 본문 포커스 키워드 포함 | ⏳ | `^(yes\|no)$` | 본문에 키워드 포함 |
| 47 | 제목(H1) 개수 | ⏳ | `^1$` | H1 태그 1개만 |
| 48 | 제목(H1) 포커스 키워드 포함 | ⏳ | `^(yes\|no)$` | H1에 키워드 포함 |
| 49 | 부제목(H2) 개수 | ⏳ | `^[0-9]{1,2}$` | H2 태그 개수 |
| 50 | 부제목(H2) 포커스 키워드 포함 | ⏳ | `^(yes\|no)$` | H2에 키워드 포함 |
| 51 | 이미지 ALT 텍스트 | ⏳ | `^(all_present\|some_missing\|all_missing)$` | 모든 이미지에 ALT 텍스트 |
| 52 | 이미지 ALT 포커스 키워드 포함 | ⏳ | `^(yes\|no)$` | ALT 텍스트에 키워드 포함 |
| 53 | 내부 링크 개수 | ⏳ | `^[0-9]{1,2}$` | 최소 3개 권장 |
| 54 | 외부 링크 개수 | ⏳ | `^[0-9]{1,2}$` | 최소 2개 권장 |
| 55 | 링크 앵커 텍스트 최적화 | ⏳ | `^(optimized\|not_optimized)$` | 앵커 텍스트 최적화 |

---

### 5. 기술적 SEO (15개)

| # | 항목 | 상태 | 정규식 | 비고 |
|---|------|------|--------|------|
| 56 | 페이지 로딩 속도 | ⏳ | `^[0-9]{1,3}(\.[0-9]{1,2})?s$` | 3초 이하 권장 |
| 57 | 모바일 반응성 | ⏳ | `^(responsive\|not_responsive)$` | 모바일 최적화 |
| 58 | SSL 인증서 | ⏳ | `^(https\|http)$` | HTTPS 필수 |
| 59 | 사이트맵 제출 | ⏳ | `^(submitted\|not_submitted)$` | Google Search Console 제출 |
| 60 | robots.txt 설정 | ⏳ | `^(configured\|not_configured)$` | robots.txt 설정 |
| 61 | 캐시 설정 | ⏳ | `^(enabled\|disabled)$` | 브라우저 캐시 설정 |
| 62 | GZIP 압축 | ⏳ | `^(enabled\|disabled)$` | GZIP 압축 활성화 |
| 63 | CDN 사용 | ⏳ | `^(yes\|no)$` | CDN 사용 여부 |
| 64 | 이미지 최적화 | ⏳ | `^(optimized\|not_optimized)$` | 이미지 압축 및 최적화 |
| 65 | JavaScript 최소화 | ⏳ | `^(minified\|not_minified)$` | JS 파일 최소화 |
| 66 | CSS 최소화 | ⏳ | `^(minified\|not_minified)$` | CSS 파일 최소화 |
| 67 | 렌더링 차단 리소스 | ⏳ | `^(none\|some\|many)$` | 렌더링 차단 리소스 제거 |
| 68 | 레이아웃 시프트 | ⏳ | `^[0-9]+(\.[0-9]{1,2})?$` | CLS 점수 0.1 이하 |
| 69 | 첫 입력 지연 | ⏳ | `^[0-9]{1,3}ms$` | FID 100ms 이하 |
| 70 | 최대 콘텐츠풀 페인트 | ⏳ | `^[0-9]{1,4}ms$` | LCP 2.5초 이하 |

---

### 6. 사용자 경험 (UX) (15개)

| # | 항목 | 상태 | 정규식 | 비고 |
|---|------|------|--------|------|
| 71 | 페이지 제목 명확성 | ⏳ | `^(clear\|unclear)$` | 페이지 제목이 명확한가 |
| 72 | 메타 설명 명확성 | ⏳ | `^(clear\|unclear)$` | 메타 설명이 명확한가 |
| 73 | 콜투액션(CTA) 명확성 | ⏳ | `^(clear\|unclear)$` | CTA 버튼이 명확한가 |
| 74 | 네비게이션 명확성 | ⏳ | `^(clear\|unclear)$` | 네비게이션이 명확한가 |
| 75 | 페이지 구조 명확성 | ⏳ | `^(clear\|unclear)$` | 페이지 구조가 명확한가 |
| 76 | 콘텐츠 가독성 | ⏳ | `^(good\|fair\|poor)$` | 콘텐츠 가독성 평가 |
| 77 | 폰트 크기 | ⏳ | `^[0-9]{2}px$` | 16px 이상 권장 |
| 78 | 줄 높이 | ⏳ | `^[0-9]+(\.[0-9]{1,2})?$` | 1.5 이상 권장 |
| 79 | 문단 간격 | ⏳ | `^[0-9]{2,3}px$` | 충분한 간격 |
| 80 | 색상 대비 | ⏳ | `^(good\|fair\|poor)$` | WCAG 기준 충족 |
| 81 | 버튼 크기 | ⏳ | `^[0-9]{2,3}px$` | 44px 이상 권장 |
| 82 | 버튼 간격 | ⏳ | `^[0-9]{2,3}px$` | 충분한 간격 |
| 83 | 폼 필드 명확성 | ⏳ | `^(clear\|unclear)$` | 폼 필드가 명확한가 |
| 84 | 폼 검증 메시지 | ⏳ | `^(present\|absent)$` | 검증 메시지 표시 |
| 85 | 에러 메시지 명확성 | ⏳ | `^(clear\|unclear)$` | 에러 메시지가 명확한가 |

---

### 7. 모바일 최적화 (10개)

| # | 항목 | 상태 | 정규식 | 비고 |
|---|------|------|--------|------|
| 86 | 모바일 반응성 | ⏳ | `^(responsive\|not_responsive)$` | 모바일 최적화 |
| 87 | 모바일 뷰포트 | ⏳ | `^(configured\|not_configured)$` | viewport 메타 태그 |
| 88 | 모바일 터치 타겟 | ⏳ | `^(optimized\|not_optimized)$` | 터치 타겟 크기 44px 이상 |
| 89 | 모바일 폰트 크기 | ⏳ | `^(optimized\|not_optimized)$` | 모바일 폰트 크기 최적화 |
| 90 | 모바일 이미지 | ⏳ | `^(optimized\|not_optimized)$` | 모바일 이미지 최적화 |
| 91 | 모바일 로딩 속도 | ⏳ | `^[0-9]{1,3}(\.[0-9]{1,2})?s$` | 3초 이하 권장 |
| 92 | 모바일 메뉴 | ⏳ | `^(optimized\|not_optimized)$` | 모바일 메뉴 최적화 |
| 93 | 모바일 폼 | ⏳ | `^(optimized\|not_optimized)$` | 모바일 폼 최적화 |
| 94 | 모바일 비디오 | ⏳ | `^(optimized\|not_optimized)$` | 모바일 비디오 최적화 |
| 95 | 모바일 테스트 | ⏳ | `^(passed\|failed)$` | Google Mobile-Friendly Test 통과 |

---

### 8. 분석 및 모니터링 (5개)

| # | 항목 | 상태 | 정규식 | 비고 |
|---|------|------|--------|------|
| 96 | Google Analytics 설정 | ⏳ | `^(configured\|not_configured)$` | GA 추적 코드 설정 |
| 97 | Google Search Console 설정 | ⏳ | `^(configured\|not_configured)$` | GSC 연동 |
| 98 | Rankmath 분석 대시보드 | ⏳ | `^(accessible\|not_accessible)$` | Rankmath 분석 접근 가능 |
| 99 | 순위 추적 | ⏳ | `^(enabled\|disabled)$` | 키워드 순위 추적 |
| 100 | 경쟁사 분석 | ⏳ | `^(enabled\|disabled)$` | 경쟁사 분석 기능 |

---

## 📊 최종 체크 결과

### 상태 요약
- ✅ 체크리스트 생성: 100개 항목
- ⏳ 검증 대기: 모든 항목 (배포 후 검증)

### 다음 단계
1. 마스터 체크리스트에 통합
2. Git 커밋 및 푸시
3. 배포 후 검증 시작

---

**생성일**: 2026-06-02  
**상태**: ✅ 생성 완료  
**항목 수**: 100개  
**정규식 검증**: 포함됨

