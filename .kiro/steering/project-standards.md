---
inclusion: always
---

# 2026 프로덕션 레디 플랫폼 프로젝트 표준

## 핵심 원칙

**모든 코드는 프로덕션 레디, 공식 문서 기반, 보안 우선이어야 합니다.**

### 필수 요구사항
- 공식 문서와 안정적인 LTS 버전만 사용
- 모든 코드는 유지보수 가능하고, 보안적이며, 테스트되어야 함
- Docker 기반 인프라 구조 필수
- 모바일 우선, SEO 우선 접근 방식
- 아래 정의된 정확한 프로젝트 구조 따를 것
- 모든 설정은 공식 문서에 따라 검증되어야 함

### 절대 금지사항
- 추측적인 코딩이나 문서화되지 않은 패턴 사용 금지
- 비공식 GitHub 코드나 테스트되지 않은 패키지 사용 금지
- `latest` 태그나 unstable 버전 사용 금지 (정확한 버전 핀 사용)
- 플랫폼 정책 위반 또는 인공적인 엔게이지먼트 조작 금지
- 하드코딩된 비밀번호나 자격증명 금지
- 프로덕션 코드에 console.log 문장 금지
- 이슈 트래킹 없이 TODO 주석 금지

## 기술 스택 및 버전

| 레이어 | 기술 | 문서 | GitHub | 버전 |
|-------|------|------|--------|------|
| **프론트엔드** | Next.js | https://nextjs.org/docs | https://github.com/vercel/next.js | Stable LTS |
| | React | https://react.dev | https://github.com/facebook/react | Stable LTS |
| | TailwindCSS | https://tailwindcss.com/docs | https://github.com/tailwindlabs/tailwindcss | Stable LTS |
| **백엔드** | Node.js | https://nodejs.org/en/docs | https://github.com/nodejs/node | 22 LTS |
| | Express.js | https://expressjs.com | https://github.com/expressjs/express | Stable LTS |
| | NestJS (선택) | https://docs.nestjs.com | https://github.com/nestjs/nest | Stable LTS |
| **데이터베이스** | MariaDB | https://mariadb.com/docs | https://github.com/MariaDB/server | 11 Stable |
| | Redis | https://redis.io/docs | https://github.com/redis/redis | 7 Stable |
| **스트리밍** | SRS | https://ossrs.io/lts/en-us/docs | https://github.com/ossrs/srs | LTS |
| | nginx-rtmp | — | https://github.com/arut/nginx-rtmp-module | Stable |
| | FFmpeg | https://ffmpeg.org/documentation.html | https://github.com/FFmpeg/FFmpeg | Stable LTS |
| **인증** | Google OAuth | https://developers.google.com/identity/protocols/oauth2 | — | Latest |
| | JWT | https://jwt.io/introduction | — | RFC 7519 |
| **인프라** | Docker | https://docs.docker.com | https://github.com/docker | Stable |
| | Docker Compose | https://docs.docker.com/compose | — | Stable |
| | nginx | https://nginx.org/en/docs | https://github.com/nginx/nginx | Stable |
| **OS** | Ubuntu | https://ubuntu.com/server/docs | — | 24.04 LTS |

## SSH 키 설정

### SSH 키 정보 (deployment@gucci-2026)

| 항목 | 값 |
|------|-----|
| **키 이름** | deployment@gucci-2026 |
| **알고리즘** | ed25519 |
| **공개키** | ssh-ed25519 AAAAC3NzaC1lZDI1NTE5AAAAIEHZRkPWLF5K0uzCmcgP37jso7LBr6nrUStUf3YsK5h9 deployment@gucci-2026 |

### SSH 키 생성 명령어

```bash
ssh-keygen -t ed25519 -C "deployment@gucci-2026" -f ~/.ssh/gucci_deployment_key
```

### Hostinger hPanel에 SSH 키 추가

1. hPanel → VPS → Manage → Settings → SSH Keys
2. Add SSH key 클릭
3. 키 이름: `deployment@gucci-2026`
4. 공개키 내용 붙여넣기

## 버전 관리

- 모든 버전은 명시적으로 고정 (`latest` 태그나 `^`, `~` 범위 사용 금지)
- 모든 Node.js 프로젝트는 `package-lock.json` 유지
- Docker 이미지 태그 대신 digest 사용
- 모든 의존성은 공식적이고 안정적인 소스에서만 가져올 것

## 프로젝트 구조

```
/project
├── /frontend          # Next.js 애플리케이션
├── /backend           # Node.js/Express 백엔드
├── /admin             # 관리자 대시보드
├── /streaming         # 스트리밍 서버 (SRS)
├── /nginx             # nginx 역방향 프록시 설정
├── /docker            # Docker Compose 파일
├── /database          # MariaDB 초기화
├── /redis             # Redis 설정
├── /security          # 보안 설정 (fail2ban, UFW)
├── /scripts           # 배포 자동화 스크립트
├── /logs              # 애플리케이션 로그
├── /backups           # 데이터베이스 백업
└── README.md          # 프로젝트 문서
```

## 핵심 기능

1. **SEO 최적화** — 150+ 체크리스트 항목 (Rank Math + Google SEO)
2. **실시간 스트리밍** — YouTube/Twitch 스타일, OBS/PRISM 지원, 적응형 비트레이트, 저지연
3. **라이브 채팅** — WebSocket 기반, Redis Pub/Sub, 이모지 지원, 모더레이터 컨트롤
4. **관리자 시스템** — 스트림 키 생성, 방송 제어, 모니터링, 감사 로그
5. **Google OAuth** — 로그인 모달, 자동 로그인, 세션 지속성, CSRF/XSS 보호
6. **모바일 UX** — 200+ 항목: 터치 최적화, 엄지 사용 편의 레이아웃, 한손 UX, safe-area 지원
7. **데스크톱 UX** — 200+ 항목: 키보드 단축키, 전체화면 모드, 관리자 대시보드, 멀티윈도우 지원
8. **보안** — 30+ 항목: DDoS 보호, SQL 인젝션 방지, XSS/CSRF 방어, fail2ban, UFW 방화벽

## 코드 표준

**필수:**
- 모든 코드는 공식 문서 패턴을 따라야 함
- 프로덕션 레디 코드만 사용 (실험적 또는 베타 기능 금지)
- 유지보수 가능하고, 잘 구조화된 코드
- 보안 우선 구현

**금지:**
- 추측적이거나 문서화되지 않은 구현
- 비공식적이거나 검증되지 않은 코드
- 테스트되지 않은 패키지나 의존성
- 플랫폼 정책 위반

## 배포 순서

1. VPS 초기화
2. Ubuntu 업데이트
3. Docker & Docker Compose 설치
4. nginx 역방향 프록시 설정
5. MariaDB 설정
6. Redis 설정
7. 백엔드 배포
8. 프론트엔드 배포
9. 스트리밍 서버 배포
10. SSL/TLS 설정
11. 모니터링 설정
12. 백업 시스템 설정
13. fail2ban & UFW 방화벽 설정
14. 프로덕션 빌드 최적화
15. SEO 최적화
16. WebSocket 테스트
17. 스트리밍 기능 테스트

## 검증 체크리스트

- [ ] 모든 코드는 공식 문서 패턴을 따름
- [ ] 모든 버전은 Stable/LTS (`latest` 태그 없음)
- [ ] 모든 패키지는 테스트되고 검증됨
- [ ] 코드는 프로덕션 레디하고 배포 가능
- [ ] 보안 모범 사례 적용
- [ ] SEO 최적화 구현
- [ ] 모바일 UX 완료
- [ ] 데스크톱 UX 완료
- [ ] 배포 테스트 완료

## AI 어시스턴트 코딩 규칙

### 필수 프로세스
1. **공식 문서 검색** — 최신 2026 버전을 위해 웹 검색 사용
2. **공식 GitHub 검증** — stable/LTS 브랜치만 확인
3. **정규표현식 패턴으로 검증** — 버전 번호, 경로, 명령어가 공식 사양과 일치하는지 확인
4. **프로덕션 레디 코드 작성** — 모든 코드는 완전하고 배포 가능해야 함
5. **문서 링크 포함** — 모든 코드 블록은 공식 문서를 참조해야 함

### 절대 금지사항
- 사용자에게 명령어 실행이나 작업 수행을 요청하지 말 것
- 추측적인 구현 사용하지 말 것
- 공식 문서 없이 코딩하지 말 것
- 비공식 GitHub 코드 사용하지 말 것
- 테스트되지 않은 패키지 사용하지 말 것
- 사용자의 입력을 기다리지 말고 자동으로 진행

### 코드 템플릿

```bash
#!/bin/bash
# Official Docs: https://docs.example.com/install
# Official GitHub: https://github.com/example/repo
# Version: Stable LTS (2026)
# Regex Validation: ^[0-9]+\.[0-9]+\.[0-9]+$

# Implementation based on official documentation
# All commands and configurations verified against official sources
```

### 정규표현식 검증 패턴

**시맨틱 버전:** `^[0-9]+\.[0-9]+\.[0-9]+$`  
**LTS 버전:** `^[0-9]+\.[0-9]+\.[0-9]+-lts$`  
**절대 경로:** `^/[a-zA-Z0-9/_.-]+$`  
**포트 번호:** `^[0-9]{4,5}$`  
**명령어:** `^[a-z0-9-]+$`  
**플래그:** `^--?[a-z0-9-]+$`

---

## AI 어시스턴트 아키텍처 가이드

### 코드 작성 시 필수 사항

1. **공식 문서 기반**
   - 모든 기술은 공식 문서에서 정보를 가져올 것
   - 코드 블록 위에 `Official Docs:` 주석으로 문서 링크 포함

2. **버전 고정**
   - `latest` 태그 사용 금지
   - 정확한 버전 번호 사용 (예: `node:22.14.0-alpine`)
   - Docker 이미지는 digest 사용 권장

3. **보안 우선**
   - 모든 비밀번호는 환경 변수 사용
   - SQL 쿼리는 parameterized query 사용
   - XSS/CSRF 방어 코드 포함

4. **모바일 우선**
   - 반응형 디자인 필수
   - touch 이벤트 처리
   - safe-area 인식

5. **SEO 최적화**
   - Next.js의 metadata API 사용
   - 올바른 meta 태그 설정
   - semantic HTML 사용

### 프론트엔드 구조

```typescript
// pages/ - Next.js pages
// components/ - 재사용 가능한 컴포넌트
// lib/ - 유틸리티 함수
// styles/ - 전역 스타일
// public/ - 정적 파일
```

### 백엔드 구조

```typescript
// src/
// ├── controllers/ - 요청 핸들러
// ├── services/ - 비즈니스 로직
// ├── models/ - 데이터 모델
// ├── routes/ - 라우트 정의
// ├── middleware/ - 미들웨어
// ├── config/ - 설정 파일
// └── utils/ - 유틸리티 함수
```

### Docker Compose 패턴

```yaml
version: '3.8'
services:
  app:
    build: .
    environment:
      - NODE_ENV=production
    ports:
      - "3000:3000"
    depends_on:
      - db
      - redis
```

### 에러 핸들링

- 모든 비동기 함수는 try-catch로 감쌈
- 에러 로그는 중앙 집중식 로깅 사용
- 사용자에게 민감한 정보 노출 금지

### 테스트 요구사항

- 유닛 테스트: 모든 핵심 로직
- 통합 테스트: API 엔드포인트
- E2E 테스트: 주요 사용자 플로우

---

## VPS 정보 (구찌야놀자)

| 항목 | 값 |
|------|-----|
| **호스트** | 76.13.218.129 |
| **호스트명** | srv1636789.hstgr.cloud |
| **SSH 포트** | 22 |
| **사용자** | root |
| **비밀번호** | q+7m#GElqQs/E&tfabwB |
| **OS** | Ubuntu 24.04 LTS |
| **CPU** | 1 Core |
| **메모리** | 4 GB |
| **디스크** | 50 GB |
| **상태** | 실행 중 |
| **만료일** | 2026-06-02 |
| **컨트롤** | KVM |

### SSH 연결 명령어

```bash
ssh root@76.13.218.129
```

---

**핵심 원칙:** 공식 문서 → 안정적인 LTS 버전 → 프로덕션 레디 코드 → 보안 우선 → 모바일 우선 → SEO 우선
