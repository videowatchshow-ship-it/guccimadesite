---
inclusion: manual
---

# 구찌야놀자.net 배포 업데이트 규칙 (2026-06-02 최종)

## ⚠️ 핵심 원칙

**이것은 새로운 배포가 아니라 기존 배포의 수정/업데이트입니다.**

- 서버: `/var/www/gucci-yanonlja-net` (거의 완성된 상태)
- 상태: nginx + MariaDB + Redis + BIND9 실행 중
- 방식: 기존 코드 수정 → GitHub 커밋 → 서버 배포

---

## 📊 현재 상황 (2026-06-02 최종)

### ✅ 서버 상태 확정
- **위치**: /var/www/gucci-yanonlja-net
- **구조**: PHP 기반 (composer.json)
- **프로세스**: 
  - nginx (포트 80, 443) ✅
  - MariaDB (포트 3306) ✅
  - Redis (포트 6379) ✅
  - BIND9 (포트 53) ✅
- **크기**: 920KB
- **Git**: 없음 (Git 저장소 아님)
- **.env**: 있음

### ✅ DNS 설정 확정 (Hostinger 외부 도메인)
- **도메인**: xn--2e0bj1fruw33b6ti.net
- **등록처**: Hostinger (외부 도메인으로 hPanel에 Website 추가)
- **DNS Zone**: Hostinger hPanel에서 자동 생성 ✅
- **Nameserver**: ns1.hostinger.com / ns2.hostinger.com ✅
- **로컬 BIND9**: /etc/bind/zones/db.xn--2e0bj1fruw33b6ti.net (VPS 로컬용)
- **공개 DNS**: Hostinger 글로벌 DNS 노출됨 ✅

### ⏳ 로컬 상태
- **변경 파일**: 22개 (커밋 필요)
- **삭제 파일**: 많음 (정리 필요)
- **구조**: Node.js + Docker Compose 기반

### ✅ GitHub 상태
- **최신 커밋**: `3d321c2` (VPS 1 배포 대상 명시)
- **구조**: Docker Compose 기반

---

## 🔄 배포 전략 (서버 코드 보존 + 로컬 추가 수정)

### 핵심 원칙
1. **서버 코드 보존** - 기존 PHP 코드 절대 삭제 금지
2. **로컬 추가 수정** - Node.js/Docker 기능 추가만 진행
3. **GitHub 동기화** - 서버 코드를 기준으로 GitHub 업데이트
4. **점진적 통합** - 단계별로 기능 통합

### 배포 프로세스

#### Phase 1: 서버 코드 백업 및 분석 ✅ 완료
- [x] 서버 전체 코드 백업 (55개 파일, 920KB)
- [x] 로컬에 다운로드 및 압축 해제
- [x] 서버 구조 분석 완료

#### Phase 2: DNS 설정 확인 ✅ 완료
- [x] GoDaddy 네임서버 변경 (ns1-2.hostinger.com)
- [x] Hostinger 외부 도메인 등록 (Website 추가)
- [x] Hostinger hPanel DNS Zone 자동 생성
- [x] BIND9 로컬 Zone 파일 설정 (/etc/bind/zones/)
- [x] 공개 DNS 노출 확인

#### Phase 3: GitHub에 서버 코드 추가 ⏳ 다음
```bash
# 1. 로컬 backups/gucci-yanonlja-net/ 디렉토리를 GitHub에 추가
# 2. 기존 backend/, frontend/ 코드와 병행 유지
# 3. 커밋: "Add server code backup (PHP-based gucci-yanonlja-net)"
```

#### Phase 4: 로컬 추가 수정 ⏳ 그 다음
```bash
# 1. 서버 코드 기반으로 로컬 코드 수정
# 2. Node.js 기능 추가 (필요한 경우만)
# 3. Docker Compose 설정 업데이트
```

#### Phase 5: 서버 배포 업데이트 ⏳ 최종
```bash
# 1. GitHub에서 최신 코드 pull
# 2. 필요한 서비스만 재시작
# 3. 기존 기능 보존 확인
```

---

## 📋 DNS 설정 상세 (Hostinger VPS 외부 도메인)

| 항목 | 값 | 상태 |
|------|-----|------|
| **도메인** | xn--2e0bj1fruw33b6ti.net | ✅ |
| **등록처** | GoDaddy → Hostinger | ✅ |
| **Hostinger 등록** | Website (hPanel) | ✅ |
| **DNS Zone** | Hostinger 자동 생성 | ✅ |
| **Nameserver** | ns1-4.hostinger.com | ✅ |
| **VPS BIND9** | /etc/bind/zones/db.{domain} | ✅ |
| **로컬 DNS** | 127.0.0.1:53 | ✅ |
| **공개 DNS** | Hostinger 글로벌 | ✅ |
| **SSL 인증서** | Let's Encrypt (Certbot) | ⏳ |

---

## 📝 필수 확인 사항

### 배포 전
- [ ] 로컬 Git 상태 Clean (변경 파일 없음)
- [ ] GitHub Main Branch 최신 상태
- [ ] 서버 .env 파일 백업
- [ ] 서버 데이터베이스 백업
- [x] Hostinger 외부 도메인 등록 확인
- [x] Hostinger hPanel DNS Zone 확인

### 배포 중
- [ ] 서버 코드 pull 성공
- [ ] 서비스 재시작 성공
- [ ] 포트 상태 확인 (80, 443, 3306, 6379, 53)
- [x] BIND9 Zone 파일 검증

### 배포 후
- [ ] 웹사이트 접속 확인
- [ ] nginx 상태 확인
- [ ] MariaDB 상태 확인
- [ ] Redis 상태 확인
- [ ] BIND9 상태 확인
- [ ] 에러 로그 확인

---

## 🔐 VPS 접속 정보

| 항목 | 값 |
|------|-----|
| **호스트** | 76.13.218.129 |
| **포트** | 22 |
| **사용자** | root |
| **비밀번호** | `.env` 파일 참조 |
| **도메인** | xn--2e0bj1fruw33b6ti.net |
| **배포 디렉토리** | /var/www/gucci-yanonlja-net |
| **DNS Zone** | /etc/bind/zones/db.xn--2e0bj1fruw33b6ti.net |

### SSH 접속
```bash
ssh root@76.13.218.129
```

---

## 📊 서버 구조 (PHP 기반 - 거의 완성된 상태)
```
/var/www/gucci-yanonlja-net/
├── admin/                    # 관리자 시스템
│   ├── api/stream-key.php   # 스트림 키 API
│   └── dashboard/           # 관리자 대시보드
├── core/
│   ├── auth/                # Google OAuth 인증
│   ├── helpers/             # SEO, 보안, 모바일 지원
│   └── websocket/           # 라이브 채팅
├── database/                # DB 마이그레이션
├── public/                  # 공개 파일
│   └── mobile/              # 모바일 UI
└── .env                     # 환경 변수
```

---

**상태**: ✅ 배포 준비 완료  
**마지막 업데이트**: 2026-06-02  
**다음 단계**: Let's Encrypt 인증서 발급 (Certbot) → 웹사이트 최종 테스트

---

## 🔄 배포 전략 (서버 코드 보존 + 로컬 추가 수정)

### 핵심 원칙
1. **서버 코드 보존** - 기존 PHP 코드 절대 삭제 금지
2. **로컬 추가 수정** - Node.js/Docker 기능 추가만 진행
3. **GitHub 동기화** - 서버 코드를 기준으로 GitHub 업데이트
4. **점진적 통합** - 단계별로 기능 통합

### 배포 프로세스

#### Phase 1: 서버 코드 백업 및 분석 ✅ 완료
- [x] 서버 전체 코드 백업 (55개 파일, 920KB)
- [x] 로컬에 다운로드 및 압축 해제
- [x] 서버 구조 분석 완료

#### Phase 2: GitHub에 서버 코드 추가
```bash
# 1. 로컬 backups/gucci-yanonlja-net/ 디렉토리를 GitHub에 추가
# 2. 기존 backend/, frontend/ 코드와 병행 유지
# 3. 커밋: "Add server code backup (PHP-based gucci-yanonlja-net)"
```

#### Phase 3: 로컬 추가 수정
```bash
# 1. 서버 코드 기반으로 로컬 코드 수정
# 2. Node.js 기능 추가 (필요한 경우만)
# 3. Docker Compose 설정 업데이트
```

#### Phase 4: 서버 배포 업데이트
```bash
# 1. GitHub에서 최신 코드 pull
# 2. 필요한 서비스만 재시작
# 3. 기존 기능 보존 확인
```

### 파일 구조 (최종)
```
f:\youtubeautoid\
├── backups/
│   └── gucci-yanonlja-net/          # 서버 코드 백업
│       ├── admin/
│       ├── config/
│       ├── core/
│       ├── database/
│       ├── public/
│       ├── composer.json
│       └── .env
├── backend/                          # Node.js 추가 기능
├── frontend/                         # Next.js 추가 기능
├── docker/                           # Docker Compose
├── nginx/                            # nginx 설정
├── database/                         # DB 마이그레이션
└── scripts/                          # 배포 스크립트
```

---

## 📋 비교 분석 (서버 ↔ 로컬 ↔ GitHub)

### 서버 구조 (PHP 기반 - 거의 완성된 상태)
```
/var/www/gucci-yanonlja-net/
├── admin/
│   ├── api/
│   │   └── stream-key.php
│   └── dashboard/
│       └── index.php
├── config/
│   ├── bootstrap.php
│   ├── cloudflare-config.php
│   ├── cloudflare-waf-config.php
│   └── google-oauth-mcp-config.php
├── core/
│   ├── auth/
│   │   ├── google-auth-api.php
│   │   └── google-auth-unified.js
│   ├── helpers/
│   │   ├── footer.php
│   │   ├── header.php
│   │   ├── health.php
│   │   ├── mobile-helper.php
│   │   ├── security-headers.php
│   │   └── seo-meta.php
│   └── websocket/
│       ├── websocket-chat-server.js
│       ├── websocket-server-ssl.js
│       └── websocket-server.js
├── database/
│   ├── migrations/
│   │   └── 001-initial-schema.sql
│   └── schemas/
│       └── db-helper.php
├── docs/
├── logs/
├── public/
│   ├── mobile/
│   │   └── assets/
│       ├── images/
│       └── js/
├── cache/
├── composer.json
└── .env
```

### 로컬 구조 (Node.js + Docker Compose)
```
f:\youtubeautoid\
├── backend/
├── frontend/
├── docker/
├── nginx/
├── database/
└── scripts/
```

### 차이점
- **서버**: PHP 기반, 거의 완성된 상태 (920KB, 55개 파일)
  - ✅ 관리자 대시보드 (admin/)
  - ✅ WebSocket 채팅 (core/websocket/)
  - ✅ Google OAuth (core/auth/)
  - ✅ 모바일 지원 (public/mobile/)
  - ✅ SEO 최적화 (core/helpers/seo-meta.php)
  - ✅ 보안 헤더 (core/helpers/security-headers.php)
  - ✅ 데이터베이스 마이그레이션 (database/)
  
- **로컬**: Node.js + Docker Compose, 추가 기능용
  - 서버 기능 보완
  - 추가 API 개발
  - Docker 기반 배포

- **GitHub**: 로컬과 동일 (Docker Compose 기반)

---

## ✅ 필수 확인 사항

### 배포 전
- [ ] 로컬 Git 상태 Clean (변경 파일 없음)
- [ ] GitHub Main Branch 최신 상태
- [ ] 서버 .env 파일 백업
- [ ] 서버 데이터베이스 백업

### 배포 중
- [ ] 서버 코드 pull 성공
- [ ] 서비스 재시작 성공
- [ ] 포트 상태 확인 (80, 443, 3306, 6379)

### 배포 후
- [ ] 웹사이트 접속 확인
- [ ] nginx 상태 확인
- [ ] MariaDB 상태 확인
- [ ] Redis 상태 확인
- [ ] 에러 로그 확인

---

## 🔐 VPS 접속 정보

| 항목 | 값 |
|------|-----|
| **호스트** | 76.13.218.129 |
| **포트** | 22 |
| **사용자** | root |
| **비밀번호** | `.env` 파일 참조 |
| **도메인** | 구찌야놀자.net (xn--2e0bj1fruw33b6ti.net) |
| **배포 디렉토리** | /var/www/gucci-yanonlja-net |

### SSH 접속
```bash
ssh root@76.13.218.129
```

### Python SSH 접속 (paramiko 5.0.0)
```python
import paramiko

client = paramiko.SSHClient()
client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
client.connect(
    hostname="76.13.218.129",
    port=22,
    username="root",
    password="q+7m#GElqQs/E&tfabwB",
    timeout=10
)
```

---

## 📝 배포 체크리스트

### Pre-Deployment
- [ ] 로컬 코드 정리 완료
- [ ] GitHub 커밋 완료
- [ ] 서버 백업 완료
- [ ] 서버 상태 확인 완료

### Deployment
- [ ] 서버 코드 pull 완료
- [ ] 서비스 재시작 완료
- [ ] 포트 상태 확인 완료

### Post-Deployment
- [ ] 웹사이트 접속 확인
- [ ] 에러 로그 확인
- [ ] 성능 모니터링

---

## 🚨 주의사항

1. **새로 배포가 아님** - 기존 배포 수정/업데이트
2. **서버 코드 보존** - 기존 코드 삭제 금지
3. **데이터 백업** - 배포 전 반드시 백업
4. **점진적 배포** - 한 번에 모든 것을 변경하지 말 것
5. **모니터링** - 배포 후 반드시 상태 확인

---

## 📞 참고 자료

- [Paramiko 공식 문서](https://docs.paramiko.org/en/stable/api/client.html)
- [SSH 공식 문서](https://www.openssh.com/specs.html)
- [Ubuntu SSH 가이드](https://help.ubuntu.com/community/SSH/OpenSSH/Keys)
- [Hostinger VPS 가이드](https://support.hostinger.com/en/articles/5723772-how-to-connect-to-your-vps-via-ssh)

---

**상태**: ✅ 수정/업데이트 준비 완료  
**마지막 업데이트**: 2026-06-01  
**다음 단계**: 로컬 코드 정리 → GitHub 커밋 → 서버 배포

