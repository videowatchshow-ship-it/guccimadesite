---
inclusion: manual
---

# Kiro AI 강제 규칙 (31개)

**생성일**: 2026-06-02  
**적용**: 모든 코딩 작업

---

## 🚫 RULE-031: DNS Zone 설정 자동화 (절대 금지 규칙)

### ❌ 절대 하지 말 것
- DNS Zone이 없다고 말하지 말 것
- "DNS Zone을 생성해야 합니다"라고 말하지 말 것
- 수동으로 hPanel에서 추가하라고 하지 말 것
- 사용자에게 대시보드 접근을 요청하지 말 것

### ✅ 반드시 할 것
- **Hostinger API로 자동 생성** (DNSZoneApi 또는 다른 엔드포인트)
- 실패하면 **다른 API 메서드 시도**
- **SSH/VPS에서 직접 설정** (필요시)
- **Certbot 자동으로 진행** (DNS Zone 생성 후)
- DNS Zone은 **이미 있다고 가정하고 진행**

### 원인
Hostinger에서 NS를 Hostinger로 설정하면 DNS Zone이 자동 생성되어야 함.
API로 확인/생성/수정을 모두 자동화할 것.

---

## 📋 RULE-001 ~ RULE-010: 문서 기반

### RULE-001: 원본 문서 없는 코딩 금지
- ❌ 추측성 코드 작성 금지
- ✅ 반드시 공식 문서 참조
- 📝 코드 주석에 출처 명시

### RULE-002: GitHub 공식 문서 존재 확인
- 🔗 https://docs.github.com/
- 📅 2026년 5월 이후 문서만 사용
- ✅ publishedDate 확인

### RULE-003: PHP 공식 문서 존재 확인
- 🔗 https://www.php.net/docs.php
- 📅 PHP 8.2 문서만 참조
- ✅ 함수, 클래스, 확장 모두 확인

### RULE-004: Hostinger 공식 문서 존재 확인
- 🔗 https://support.hostinger.com/
- 📅 2026년 문서 우선
- ✅ VPS, DNS, SSL 관련 가이드

### RULE-005: GoDaddy 공식 문서 존재 확인
- 🔗 https://www.godaddy.com/help/
- 📅 최신 DNS 설정 가이드
- ✅ 도메인 관리 문서

### RULE-006: Let's Encrypt 공식 문서 존재 확인
- 🔗 https://letsencrypt.org/docs/
- 📅 Certbot 최신 버전 문서
- ✅ SSL 인증서 발급 절차

### RULE-007: 원본 문서 URL README 기록
- 📝 모든 참조는 링크 포함
- 📅 날짜 명시
- ✅ 버전 정보 포함

### RULE-008: 수정 시 README 업데이트
- 📝 변경 이력 필수 기록
- 📅 날짜 및 시간 명시
- ✅ Git 커밋 해시 포함

### RULE-009: 버전 변경 시 README 업데이트
- 📝 메이저.마이너.패치 명시
- 📅 변경 날짜 기록
- ✅ 호환성 정보 포함

### RULE-010: 의존성 변경 시 README 업데이트
- 📝 composer.json, package.json 변경 기록
- 📅 추가/삭제/업데이트 날짜
- ✅ 사유 명시

---

## 📋 RULE-011 ~ RULE-020: 버전 관리

### RULE-011: 동일 메이저 버전 필수
- ✅ 허용: PHP 8.2.15 ↔ PHP 8.2.16
- ❌ 금지: PHP 8.2 ↔ PHP 8.3

### RULE-012: 동일 마이너 버전 필수
- ✅ 허용: Apache2 2.4.58 ↔ Apache2 2.4.59
- ❌ 금지: Apache2 2.4 ↔ Apache2 2.6

### RULE-013: 패치 버전 차이 기록
- 📝 README에 버전 차이 명시
- 📅 확인 날짜 기록
- ✅ 테스트 결과 포함

### RULE-014: composer.lock 관리
- ✅ Git에 포함
- 📝 변경 사항 기록
- ❌ 로컬 버전 차이 금지

### RULE-015: package-lock.json 관리
- ✅ Git에 포함
- 📝 npm audit 결과 기록
- ❌ 취약점 있는 패키지 사용 금지


### RULE-016: latest 태그 사용 금지
- ❌ Docker 이미지 latest 금지
- ✅ 정확한 버전 번호 사용
- 📝 digest 사용 권장

### RULE-017: 버전 정규식 검증
- ✅ 시맨틱 버전: `^[0-9]+\.[0-9]+\.[0-9]+$`
- ✅ LTS 버전: `^[0-9]+\.[0-9]+\.[0-9]+-lts$`
- ❌ 정규식 매치 실패 시 거부

### RULE-018: Git 커밋 메시지 규칙
- ✅ 형식: `type(scope): subject`
- 📝 예시: `feat(auth): Add Google OAuth`
- ❌ 애매한 메시지 금지 (예: "update", "fix")

### RULE-019: Git 브랜치 전략
- ✅ main: 프로덕션
- ✅ develop: 개발
- ✅ feature/*: 기능 개발
- ❌ main에 직접 푸시 금지

### RULE-020: Git 태그 규칙
- ✅ 형식: `v메이저.마이너.패치`
- 📝 예시: `v1.2.3`
- ❌ 비정규 태그 금지

---

## 📋 RULE-021 ~ RULE-030: 보안 및 코드 품질

### RULE-021: 비밀번호 하드코딩 금지
- ❌ 코드에 비밀번호 직접 작성 금지
- ✅ 환경 변수 사용 (.env)
- 📝 .env.example 템플릿 제공

### RULE-022: SQL 인젝션 방어
- ❌ 문자열 결합 쿼리 금지
- ✅ Prepared Statement 사용
- 📝 PDO 또는 mysqli::prepare()

### RULE-023: XSS 방어
- ❌ 사용자 입력 직접 출력 금지
- ✅ htmlspecialchars() 사용
- 📝 모든 출력에 적용

### RULE-024: CSRF 방어
- ✅ CSRF 토큰 생성 및 검증
- 📝 세션 기반 토큰 사용
- ❌ GET 요청으로 상태 변경 금지

### RULE-025: 파일 업로드 보안
- ✅ 확장자 화이트리스트
- ✅ MIME 타입 검증
- ✅ 파일 크기 제한
- ❌ 실행 가능 파일 업로드 금지

### RULE-026: 에러 메시지 노출 금지
- ❌ 프로덕션에서 스택 트레이스 노출 금지
- ✅ 사용자에게는 일반 에러 메시지
- 📝 상세 에러는 로그 파일에만

### RULE-027: 로깅 규칙
- ✅ 모든 에러는 로그 파일에 기록
- 📝 날짜, 시간, 파일, 라인 번호 포함
- ❌ 민감한 정보 로그 금지 (비밀번호, API 키)

### RULE-028: 코드 리뷰 필수
- ✅ Pull Request 생성
- ✅ 최소 1명의 승인 필요
- ❌ 리뷰 없이 머지 금지

### RULE-029: 테스트 커버리지
- ✅ 핵심 기능 유닛 테스트
- ✅ API 엔드포인트 통합 테스트
- 📝 커버리지 80% 이상 권장

### RULE-030: 문서화 필수
- ✅ 모든 public 함수/메서드 PHPDoc
- ✅ README 최신 상태 유지
- 📝 API 문서 자동 생성 (Swagger/OpenAPI)

---

## � 최종 규칙 정리

- [ ] RULE-001~010: 문서 기반 (10개)
- [ ] RULE-011~020: 버전 관리 (10개)
- [ ] RULE-021~030: 보안 및 코드 품질 (10개)
- [ ] **RULE-031: DNS Zone 설정 자동화** ⭐ **절대 금지**

**총 31개 규칙 모두 준수 필수!**

---

## 🚫 위반 시 조치

1. **경고 1회**: README에 기록
2. **경고 2회**: 코드 리뷰 반려
3. **경고 3회**: 배포 차단

---

**마지막 업데이트**: 2026-06-02  
**버전**: 1.1.0 (RULE-031 추가)
