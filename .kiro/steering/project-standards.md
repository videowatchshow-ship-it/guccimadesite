---
inclusion: manual
---

# 2026 프로덕션 레디 플랫폼 프로젝트 표준

## 🗓️ 문서 사용 기준 (최우선 규칙)

**현재 날짜: 2026년 6월**

### AI 어시스턴트 필수 검색 규칙
- **반드시 2026년 문서만 사용** — 2025년 이전 문서 사용 금지
- **웹 검색 시 연도 명시** — 검색 쿼리에 항상 `2026` 포함
- **publishedDate 확인 필수** — 검색 결과의 날짜가 2025년 이후인 것만 사용
- **버전 확인 필수** — PyPI, npm, GitHub Releases에서 최신 stable 버전 직접 확인
- **공식 소스 우선순위**: 공식 문서 사이트 > 공식 GitHub > PyPI/npm > 기타

### 버전 확인 방법 (2026 기준)
- Python 패키지: `https://pypi.org/project/<패키지명>/` 에서 최신 버전 확인
- Node.js 패키지: `https://www.npmjs.com/package/<패키지명>` 에서 최신 버전 확인
- Docker 이미지: Docker Hub 공식 태그 페이지에서 확인
- 검색 쿼리 예시: `paramiko 2026 latest version`, `Node.js 24 LTS 2026`

---

## ⚠️ Hostinger API 필수 규칙 (2026-06-01 확인)

### API Base URL — 절대 틀리지 말 것

| 항목 | 값 |
|------|-----|
| **공식 Python SDK base_url** | `https://developers.hostinger.com` |
| **GitHub 공식 SDK** | https://github.com/hostinger/api-python-sdk |
| **Python SDK 버전** | `hostinger-api==0.0.19` (PyPI 최신, 2026-06-01 확인) |
| **설치 명령** | `pip install hostinger-api==0.0.19` |
| **MCP 서버 버전** | `hostinger-api-mcp@0.2.3` (npm 최신, 2026-06-01 확인) |
| **MCP 실행 방법** | `npx -y hostinger-api-mcp@0.2.3` (Node.js 20+ 필요) |
| **paramiko 버전** | `paramiko==5.0.0` (PyPI 최신, 2026-06-01 확인) |
| **환경변수** | `BEARER_TOKEN` (Python SDK), `API_TOKEN` / `HOSTINGER_API_TOKEN` (MCP) |
| **❌ 절대 사용 금지** | `api.hostinger.com` — Cloudflare 530/1016 에러 발생 |
| **✅ 올바른 URL** | `https://developers.hostinger.com` |

### Python SDK 사용법 (공식 GitHub 기준)

```python
# Official GitHub: https://github.com/hostinger/api-python-sdk
# pip install hostinger-api==0.0.19
import os
import hostinger_api
from hostinger_api.rest import ApiException

configuration = hostinger_api.Configuration(
    access_token=os.environ["BEARER_TOKEN"]
)
# base_url = https://developers.hostinger.com (자동 설정)
```

### MCP 설정 (공식 GitHub 기준)

```json
{
  "mcpServers": {
    "hostinger-api": {
      "command": "npx",
      "args": ["-y", "hostinger-api-mcp@0.2.3"],
      "env": {
        "API_TOKEN": "YOUR_TOKEN",
        "HOSTINGER_API_TOKEN": "YOUR_TOKEN",
        "DEBUG": "false"
      },
      "disabled": false,
      "autoApprove": []
    }
  }
}
```

### DNS Zone API

```
PUT https://developers.hostinger.com/api/dns/v1/zones/{domain}
GET https://developers.hostinger.com/api/dns/v1/zones/{domain}
```

---

## 핵심 원칙

**모든 코드는 프로덕션 레디, 공식 문서 기반, 보안 우선이어야 합니다.**

### AI 어시스턴트를 위한 필수 요구사항
- **공식 문서 기반**: 모든 기술 결정은 공식 문서에서 검증해야 함
- **LTS 버전 고정**: `latest`, `^`, `~` 사용 금지 - 정확한 버전 번호 사용
- **Docker 인프라**: 모든 서비스는 Docker/Docker Compose로 배포
- **보안 우선**: 비밀번호, API 키는 환경 변수 사용
- **모바일 우선**: 반응형 디자인, touch 이벤트, safe-area 지원
- **SEO 우선**: Next.js metadata API, semantic HTML 사용
- **프로젝트 구조**: 아래 정의된 구조를 정확히 따를 것

### 절대 금지사항 (AI 어시스턴트)
- 사용자에게 명령어 실행 요청 금지
- 추측적인 코딩이나 문서화되지 않은 패턴 사용 금지
- 비공식 GitHub 코드나 테스트되지 않은 패키지 사용 금지
- `latest` 태그나 unstable 버전 사용 금지
- 플랫폼 정책 위반 또는 인공적인 엔게이지먼트 조작 금지
- 하드코딩된 비밀번호나 자격증명 금지
- 프로덕션 코드에 console.log 문장 금지
- 이슈 트래킹 없이 TODO 주석 금지

## 기술 스택 및 버전 (2026 기준)

| 레이어 | 기술 | 문서 | GitHub | 버전 | 필수 |
|-------|------|------|--------|------|------|
| **프론트엔드** | Next.js | https://nextjs.org/docs | https://github.com/vercel/next.js | Stable LTS | 필수 |
| | React | https://react.dev | https://github.com/facebook/react | Stable LTS | 필수 |
| | TailwindCSS | https://tailwindcss.com/docs | https://github.com/tailwindlabs/tailwindcss | Stable LTS | 필수 |
| **백엔드** | Node.js | https://nodejs.org/en/docs | https://github.com/nodejs/node | 22 LTS | 필수 |
| | Express.js | https://expressjs.com | https://github.com/expressjs/express | Stable LTS | 선택 |
| | NestJS | https://docs.nestjs.com | https://github.com/nestjs/nest | Stable LTS | 선택 |
| **데이터베이스** | MariaDB | https://mariadb.com/docs | https://github.com/MariaDB/server | 11 Stable | 필수 |
| | Redis | https://redis.io/docs | https://github.com/redis/redis | 7 Stable | 필수 |
| **스트리밍** | SRS | https://ossrs.io/lts/en-us/docs | https://github.com/ossrs/srs | LTS | 필수 |
| | FFmpeg | https://ffmpeg.org/documentation.html | https://github.com/FFmpeg/FFmpeg | Stable LTS | 필수 |
| **인증** | Google OAuth | https://developers.google.com/identity/protocols/oauth2 | — | Latest | 필수 |
| | JWT | https://jwt.io/introduction | — | RFC 7519 | 필수 |
| **인프라** | Docker | https://docs.docker.com | https://github.com/docker | Stable | 필수 |
| | Docker Compose | https://docs.docker.com/compose | — | Stable | 필수 |
| | nginx | https://nginx.org/en/docs | https://github.com/nginx/nginx | Stable | 필수 |
| **OS** | Ubuntu | https://ubuntu.com/server/docs | — | 24.04 LTS | 필수 |

### 버전 관리 규칙
- **Docker 이미지**: digest 사용 권장 (예: `node@sha256:abc123`)
- **Node.js 패키지**: `package-lock.json` 유지, 정확한 버전 핀
- **의존성 소스**: 공식 문서 또는 공식 GitHub만 사용

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

### SSH 연결 (VPS)

```bash
ssh root@76.13.218.129
```

## 버전 관리

- 모든 버전은 명시적으로 고정 (`latest` 태그나 `^`, `~` 범위 사용 금지)
- 모든 Node.js 프로젝트는 `package-lock.json` 유지
- Docker 이미지 태그 대신 digest 사용
- 모든 의존성은 공식적이고 안정적인 소스에서만 가져올 것

## 프로젝트 구조

```
/project
├── /frontend          # Next.js 애플리케이션 (pages/, components/, lib/, styles/, public/)
├── /backend           # Node.js/Express 백엔드 (src/controllers/, services/, models/, routes/, middleware/, config/, utils/)
├── /admin             # 관리자 대시보드
├── /streaming         # 스트리밍 서버 (SRS)
├── /nginx             # nginx 역방향 프록시 설정
├── /docker            # Docker Compose 파일
├── /database          # MariaDB 초기화 스크립트
├── /redis             # Redis 설정
├── /security          # 보안 설정 (fail2ban, UFW)
├── /scripts           # 배포 자동화 스크립트
├── /logs              # 애플리케이션 로그
├── /backups           # 데이터베이스 백업
└── README.md          # 프로젝트 문서
```

### 프론트엔드 구조 (Next.js)
- `pages/` - Next.js pages (route-based)
- `components/` - 재사용 가능한 React 컴포넌트
- `lib/` - 유틸리티 함수 (API 클라이언트, 유틸리티)
- `styles/` - 전역 스타일 및 Tailwind 설정
- `public/` - 정적 파일 (이미지, favicon)

### 백엔드 구조 (Node.js/Express)
- `src/controllers/` - 요청 핸들러
- `src/services/` - 비즈니스 로직
- `src/models/` - 데이터 모델
- `src/routes/` - 라우트 정의
- `src/middleware/` - 미들웨어 (인증, 로깅, 에러 핸들링)
- `src/config/` - 설정 파일
- `src/utils/` - 유틸리티 함수

## 핵심 기능 (우선순위 기반)

### 1. 실시간 스트리밍 (우선순위: 높음)
- YouTube/Twitch 스타일 인터페이스
- OBS/PRISM 지원
- 적응형 비트레이트 (ABR)
- 저지연 스트리밍 (< 5초)

### 2. 라이브 채팅 (우선순위: 높음)
- WebSocket 기반 (Redis Pub/Sub)
- 이모지 및 스티커 지원
- 모더레이터 컨트롤 (차단, 삭제, 관리자 권한)

### 3. Google OAuth (우선순위: 높음)
- 로그인 모달 (모바일/데스크톱 최적화)
- 자동 로그인 (세션 지속성)
- CSRF/XSS 보호

### 4. SEO 최적화 (우선순위: 높음)
- Next.js metadata API
- 올바른 meta 태그 (Open Graph, Twitter Cards)
- semantic HTML
- 150+ 항목 체크리스트

### 5. 모바일 UX (우선순위: 높음)
- 반응형 디자인
- touch 이벤트 처리
- 엄지 사용 편의 레이아웃
- safe-area 지원
- 200+ 항목 체크리스트

### 6. 데스크톱 UX (우선순위: 중간)
- 키보드 단축키
- 전체화면 모드
- 관리자 대시보드
- 멀티윈도우 지원

### 7. 보안 (우선순위: 높음)
- DDoS 보호
- SQL 인젝션 방지 (parameterized query)
- XSS/CSRF 방어
- fail2ban 설치
- UFW 방화벽 설정 (포트: 22, 80, 443)
- 30+ 항목 체크리스트

### 8. 관리자 시스템 (우선순위: 중간)
- 스트림 키 생성
- 방송 제어
- 모니터링 대시보드
- 감사 로그

## 코드 표준

### AI 어시스턴트를 위한 필수 프로세스

1. **공식 문서 검색** — 최신 2026 버전을 위해 웹 검색 사용
2. **공식 GitHub 검증** — stable/LTS 브랜치만 확인
3. **정규표현식 패턴으로 검증** — 버전 번호, 경로, 명령어가 공식 사양과 일치하는지 확인
4. **프로덕션 레디 코드 작성** — 모든 코드는 완전하고 배포 가능해야 함
5. **문서 링크 포함** — 모든 코드 블록은 공식 문서를 참조해야 함

### 코드 작성 규칙

**필수:**
- 모든 코드는 공식 문서 패턴을 따라야 함
- 프로덕션 레디 코드만 사용 (실험적 또는 베타 기능 금지)
- 유지보수 가능하고, 잘 구조화된 코드
- 보안 우선 구현
- 에러 핸들링 포함 (try-catch)

**금지:**
- 추측적이거나 문서화되지 않은 구현
- 비공식적이거나 검증되지 않은 코드
- 테스트되지 않은 패키지나 의존성
- 플랫폼 정책 위반
- 사용자에게 명령어 실행 요청
- 사용자의 입력을 기다리지 말고 자동으로 진행

### 코드 템플릿

```bash
#!/bin/bash
# Official Docs: https://docs.example.com/install
# Official GitHub: https://github.com/example/repo
# Version: Stable LTS (2026)
# Regex Validation: ^[0-9]+\.[0-9]+\.[0-9]+$
# AI Assistant: Auto-generated based on official documentation

# Implementation based on official documentation
# All commands and configurations verified against official sources
```

### 정규표현식 검증 패턴

| 패턴 | 정규표현식 | 설명 |
|------|-----------|------|
| 시맨틱 버전 | `^[0-9]+\.[0-9]+\.[0-9]+$` | 1.2.3 형식 |
| LTS 버전 | `^[0-9]+\.[0-9]+\.[0-9]+-lts$` | 22.14.0-lts 형식 |
| 절대 경로 | `^/[a-zA-Z0-9/_.-]+$` | /path/to/file |
| 포트 번호 | `^[0-9]{4,5}$` | 1024-65535 |
| 명령어 | `^[a-z0-9-]+$` | docker, npm, ssh |
| 플래그 | `^--?[a-z0-9-]+$` | --help, -v |

## 배포 순서 (VPS 초기화부터 프로덕션까지)

### Phase 1: 서버 준비
1. VPS에 SSH로 직접 접속
2. Ubuntu 업데이트 실행 (`apt update && apt upgrade -y`)
3. SSH 보안 설정 적용

### Phase 2: Docker 설치
1. Docker 설치 (공식 스크립트 사용)
2. Docker Compose 설치

### Phase 3: 데이터베이스 설치
1. MariaDB 11 Stable 설치
2. Redis 7 Stable 설치

### Phase 4: 웹 서버 설치
1. nginx Stable 설치

### Phase 5: Node.js 설치
1. Node.js 22 LTS 설치

### Phase 6: 보안 설정
1. UFW 방화벽 설정 (포트: 22, 80, 443)
2. fail2ban 설치
3. SSL/TLS 설정 (Certbot)

### Phase 7: 애플리케이션 배포
1. Backend 디렉토리 생성 및 배포
2. Frontend 디렉토리 생성 및 배포
3. Streaming 디렉토리 생성 및 배포

### Phase 8: 모니터링 및 백업
1. 모니터링 설정
2. 백업 설정
3. 로그 관리 설정

### Phase 9: 최종 검증
1. 성능 최적화
2. 최종 검증 및 서비스 상태 확인

### Phase 10: SEO 최적화
1. Next.js metadata 설정
2. Open Graph 태그 설정
3. sitemap.xml 생성

## 검증 체크리스트 (AI 어시스턴트용)

### 코드 품질
- [ ] 모든 코드는 공식 문서 패턴을 따름
- [ ] 모든 버전은 Stable/LTS (`latest` 태그 없음)
- [ ] 모든 패키지는 테스트되고 검증됨
- [ ] 코드는 프로덕션 레디하고 배포 가능

### 보안
- [ ] 보안 모범 사례 적용
- [ ] 비밀번호는 환경 변수 사용
- [ ] SQL 쿼리는 parameterized query 사용
- [ ] XSS/CSRF 방어 코드 포함

### UX
- [ ] SEO 최적화 구현 (150+ 항목)
- [ ] 모바일 UX 완료 (200+ 항목)
- [ ] 데스크톱 UX 완료 (200+ 항목)

### 인프라
- [ ] Docker Compose 설정 완료
- [ ] SSL/TLS 설정 완료
- [ ] fail2ban 설치 완료
- [ ] UFW 방화벽 설정 완료

### 테스트
- [ ] 유닛 테스트: 모든 핵심 로직
- [ ] 통합 테스트: API 엔드포인트
- [ ] E2E 테스트: 주요 사용자 플로우
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
# AI Assistant: Auto-generated based on official documentation

# Implementation based on official documentation
# All commands and configurations verified against official sources
```

### 정규표현식 검증 패턴

| 패턴 | 정규표현식 | 설명 |
|------|-----------|------|
| 시맨틱 버전 | `^[0-9]+\.[0-9]+\.[0-9]+$` | 1.2.3 형식 |
| LTS 버전 | `^[0-9]+\.[0-9]+\.[0-9]+-lts$` | 22.14.0-lts 형식 |
| 절대 경로 | `^/[a-zA-Z0-9/_.-]+$` | /path/to/file |
| 포트 번호 | `^[0-9]{4,5}$` | 1024-65535 |
| 명령어 | `^[a-z0-9-]+$` | docker, npm, ssh |
| 플래그 | `^--?[a-z0-9-]+$` | --help, -v |

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

### 프론트엔드 구조 (Next.js)

```typescript
// pages/ - Next.js pages (route-based)
// components/ - 재사용 가능한 React 컴포넌트
// lib/ - 유틸리티 함수 (API 클라이언트, 유틸리티)
// styles/ - 전역 스타일 및 Tailwind 설정
// public/ - 정적 파일 (이미지, favicon)
```

### 백엔드 구조 (Node.js/Express)

```typescript
// src/
// ├── controllers/ - 요청 핸들러
// ├── services/ - 비즈니스 로직
// ├── models/ - 데이터 모델
// ├── routes/ - 라우트 정의
// ├── middleware/ - 미들웨어 (인증, 로깅, 에러 핸들링)
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

## VPS 정보

| 항목 | 값 |
|------|-----|
| **호스트** | 76.13.218.129 |
| **호스트명** | srv1636789.hstgr.cloud |
| **SSH 포트** | 22 |
| **사용자** | root |
| **비밀번호** | `.env` 파일 참조 |
| **OS** | Ubuntu 24.04 LTS |
| **CPU** | 1 Core |
| **메모리** | 4 GB |
| **디스크** | 50 GB |
| **상태** | 실행 중 |
| **만료일** | 2026-06-02 |
| **컨트롤** | KVM |
| **Hostinger API 토큰** | `.env` 파일 참조 |
| **토큰 발급처** | https://hpanel.hostinger.com/profile/api |
| **배포 대상** | ✅ VPS 1 (76.13.218.129) |

### SSH 연결 명령어

```bash
ssh root@76.13.218.129
```

## 도메인 DNS 설정 현황

> **공식 문서**: https://support.hostinger.com/en/articles/1583227-how-to-point-domain-to-your-vps  
> **상태**: ✅ VPS BIND9 DNS Zone 완료 (2026-06-01)

| 항목 | 값 | 상태 |
|------|-----|------|
| **도메인** | xn--2e0bj1fruw33b6ti.net | ✅ |
| **VPS IP** | 76.13.218.129 | ✅ |
| **BIND9** | 9.18.39 (Ubuntu 24.04) | ✅ |
| **DNS Zone 파일** | /etc/bind/zones/xn--2e0bj1fruw33b6ti.net | ✅ |
| **A 레코드 @** | 76.13.218.129 | ✅ |
| **A 레코드 www** | 76.13.218.129 | ✅ |
| **UFW 포트 53** | TCP/UDP 허용 | ✅ |
| **고대디 NS** | ns1.hostinger.com / ns2.hostinger.com | ✅ 변경 완료 (사용자 확인) |

### 네임서버 설정 완료 (2026-06-02 사용자 직접 확인)

- **NS1**: `ns1.hostinger.com` ✅ GoDaddy에서 변경 완료
- **NS2**: `ns2.hostinger.com` ✅ GoDaddy에서 변경 완료
- **Hostinger 도메인 등록**: ✅ `is_accessible: true` 확인됨
- **WHOIS 전파**: 진행 중 (GoDaddy UI 반영 완료, WHOIS 서버 전파 중)

---

**핵심 원칙:** 공식 문서 → 안정적인 LTS 버전 → 프로덕션 레디 코드 → 보안 우선 → 모바일 우선 → SEO 우선

