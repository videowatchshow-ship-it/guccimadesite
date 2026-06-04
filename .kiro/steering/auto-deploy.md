---
inclusion: always
---

# 자동 배포 규칙 (Auto-Deploy)

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
| **API 토큰** | `.env` 파일 참조 |

### DNS Zone API 엔드포인트

```
PUT  https://developers.hostinger.com/api/dns/v1/zones/{domain}
GET  https://developers.hostinger.com/api/dns/v1/zones/{domain}
```

## 절대 금지 사항

- **스크립트 실행 금지** — 절대 shell 명령어를 실행하지 말 것
- **사용자 요청 금지** — 사용자에게 어떤 명령어도 실행시키지 말 것
- **모든 작업은 Kiro가 직접 수행** — Kiro가 직접 모든 작업을 완료할 것

## 배포 프로세스

### Phase 1: 서버 준비
1. VPS에 SSH로 직접 접속
2. Ubuntu 업데이트 실행
3. SSH 보안 설정 적용

### Phase 2: Docker 설치
1. Docker 설치
2. Docker Compose 설치

### Phase 3: 데이터베이스 설치
1. MariaDB 11 Stable 설치
2. Redis 7 Stable 설치

### Phase 4: 웹 서버 설치
1. apache2 Stable 설치

### Phase 5: Node.js 설치
1. Node.js 22 LTS 설치

### Phase 6: 보안 설정
1. UFW 방화벽 설정 (포트: 22, 80, 443)
2. fail2ban 설치
3. SSL/TLS 설정 (Certbot)

### Phase 7: 애플리케이션 배포
1. Backend 디렉토리 생성
2. Frontend 디렉토리 생성
3. Streaming 디렉토리 생성

### Phase 8: 모니터링 및 백업
1. 모니터링 설정
2. 백업 설정
3. 로그 관리 설정

### Phase 9: 최종 검증
1. 성능 최적화
2. 최종 검증 및 서비스 상태 확인

## MCP 사용 규칙

- 모든 작업은 MCP를 사용해서 자동화
- 사용자 인터벤션 없이 완전 자동화
- 모든 단계는 Kiro가 직접 수행

## VPS 정보

| 항목 | 값 |
|------|-----|
| **호스트** | 76.13.218.129 |
| **호스트명** | srv1636789.hstgr.cloud |
| **SSH 포트** | 22 |
| **사용자** | root |
| **비밀번호** | `.env` 파일 참조 |
| **OS** | Ubuntu 24.04 LTS |
| **Hostinger API 토큰** | `.env` 파일 참조 |
| **토큰 발급처** | https://hpanel.hostinger.com/profile/api |

## DNS 설정 현황 (2026-06-01 완료)

- BIND9 설치: ✅ `/etc/bind/zones/xn--2e0bj1fruw33b6ti.net`
- A 레코드 @ → 76.13.218.129 ✅
- A 레코드 www → 76.13.218.129 ✅
- UFW 포트 53 TCP/UDP 허용 ✅

---

**Core Principle:** MCP 자동화 → 사용자 인터벤션 없음 → Kiro 직접 실행
