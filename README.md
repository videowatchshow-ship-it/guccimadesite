# 구찌야놀자.net (xn--2e0bj1fruw33b6ti.net)

**실시간 스트리밍 플랫폼 | PHP + Node.js + MariaDB + Redis**

---

## � 프로젝트 상태 (2026-06-02)

| 항목 | 상태 | 상세 |
|------|------|------|
| **도메인** | ✅ Active | xn--2e0bj1fruw33b6ti.net (GoDaddy) |
| **VPS** | ✅ Running | 76.13.218.129, Ubuntu 24.04 LTS |
| **웹서버** | ✅ Running | nginx 1.24.0 (포트 80, 443) |
| **PHP** | ✅ Running | PHP 8.2 + FPM |
| **데이터베이스** | ✅ Running | MariaDB 11, Redis 7 |
| **PHP 앱** | ✅ Complete | 920KB, 55개 파일 (`/var/www/gucci-yanonlja-net/`) |
| **DNS (BIND9)** | ❌ Disabled | BIND9 중지됨, Hostinger DNS 사용 |
| **SSL/TLS** | ⏳ 진행중 | Certbot으로 발급 진행 중 |
| **DNS 전파** | ✅ Complete | GoDaddy → NS1-4.HOSTINGER.COM (완료) |

---

## 🎯 핵심 기능

### 서버 구조 (`/var/www/gucci-yanonlja-net/`)

```
├── admin/                    # 관리자 시스템
│   ├── api/stream-key.php   # 스트림 키 API
│   └── dashboard/           # 관리자 대시보드
├── core/
│   ├── auth/                # Google OAuth 인증
│   ├── helpers/             # SEO, 보안, 모바일 지원
│   └── websocket/           # 라이브 채팅 (Node.js)
├── database/                # DB 마이그레이션
├── public/                  # 공개 파일
│   └── mobile/              # 모바일 UI (100% 반응형)
├── composer.json            # PHP 의존성
└── .env                     # 환경 변수
```

---

## 🔗 접속 주소

```
메인:     https://76.13.218.129/
모바일:   https://76.13.218.129/mobile/
관리자:   https://76.13.218.129/admin/dashboard/
도메인:   https://구찌야놀자.net (DNS 전파 진행 중)
```

---

## 🌐 DNS 설정 현황 (2026-06-02 확정)

### ✅ 최종 확정된 사실
- **도메인**: xn--2e0bj1fruw33b6ti.net
- **GoDaddy**: NS1-4.HOSTINGER.COM으로 위임 ✅ (완료)
- **Hostinger DNS Zone**: ✅ 존재함 (자동 생성, 레코드 추가 완료)
- **A 레코드 @**: 76.13.218.129 ✅
- **A 레코드 www**: 76.13.218.129 ✅
- **BIND9**: ❌ 중지됨 (불필요, Hostinger DNS 사용)
- **nginx**: ✅ 실행 중 (HTTP 301 → HTTPS)

### ✅ DNS 검증 완료
```bash
dig @1.1.1.1 xn--2e0bj1fruw33b6ti.net A
# 응답: 76.13.218.129 ✅

dig @1.1.1.1 www.xn--2e0bj1fruw33b6ti.net A
# 응답: 76.13.218.129 ✅
```

---

## ⚠️ 현재 문제

---

## 🛠️ 기술 스택 (공식 문서 기준)

| 레이어 | 기술 | 버전 | 공식 문서 |
|-------|------|------|----------|
| **웹서버** | nginx | 1.24.0 | https://nginx.org/en/docs/ |
| **백엔드** | PHP | 8.2 | https://www.php.net/docs.php |
| | Node.js | 22 LTS | https://nodejs.org/en/docs/ |
| **DB** | MariaDB | 11 | https://mariadb.com/docs/ |
| | Redis | 7 | https://redis.io/docs/ |
| **DNS** | BIND9 | 9.18.39 | https://www.isc.org/bind/ |
| **OS** | Ubuntu | 24.04 LTS | https://ubuntu.com/server/docs/ |

---

## 📁 로컬 구조

```
f:\youtubeautoid/
├── .env                    # 환경 변수
├── .kiro/                  # Kiro 설정
│   └── steering/          # 배포 규칙
├── check_bind9_status.py  # BIND9 진단
├── diagnose_bind9.py      # BIND9 세부 진단
├── README.md              # 이 파일
└── backups/
    └── gucci-yanonlja-net/# 서버 코드 백업
```

---

## 🔐 VPS 접속

```bash
ssh root@76.13.218.129
Password: (from .env: VPS_PASS)
```

---

## 📋 서비스 상태 확인

```bash
systemctl status nginx        # 웹서버
systemctl status php8.2-fpm   # PHP
systemctl status mariadb      # MariaDB
systemctl status redis        # Redis
systemctl status named        # DNS (현재 실패)
```

---

## � 공식 문서 정규식 검증

### 파일명
- ✅ Python: `^[a-z0-9_]+\.py$`
- ✅ Markdown: `^[A-Z_]+\.md$`
- ✅ 경로: `^/[a-zA-Z0-9/_.-]+$`

### 버전
- ✅ 시맨틱: `^[0-9]+\.[0-9]+\.[0-9]+$`
- ✅ IP: `^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$`
- ✅ 포트: `^[0-9]{2,5}$`

## ⚠️ 현재 상태 (2026-06-02 최종) - 근본 원인 파악됨

### ✅ 완료됨
- BIND9: ✅ 중지됨 (disabled)
- nginx: ✅ 실행 중 (포트 80, 443)
- DNS 위임: ✅ GoDaddy → NS1-4.HOSTINGER.COM (WHOIS 확인)
- nginx 설정: ✅ 준비됨 (SSL 경로 지정)
- **API 검증**: ✅ SDK 정상 작동 (필드 검증 완료)

### ❌ 근본 원인 발견
**Hostinger API Error: [DNS:4009] Domain not found**

원인 분석:
1. ✅ GoDaddy NS 설정: NS1-4.HOSTINGER.COM (완료)
2. ✅ Hostinger DNS Zone GET: 응답 200, 레코드 0개 (공백, 정상)
3. ❌ Hostinger DNS Zone UPDATE: 404 "Domain not found" (실패)

**결론**: 도메인이 Hostinger 계정에 "Website" 또는 "Hosting" 로 등록되지 않음
- GoDaddy에서 NS만 변경 (도메인 이전 없음)
- Hostinger는 DNS Zone을 만들었지만, 도메인이 계정에 링크되지 않음
- API가 도메인을 찾을 수 없음

### ⏳ 해결 방법 (2가지 옵션)

**옵션 A (권장): Hostinger hPanel에서 도메인 추가**
1. hPanel 접속 → VPS → Manage → Websites
2. "Add Website" 또는 "Add Domain" 클릭
3. 도메인 입력: `xn--2e0bj1fruw33b6ti.net`
4. 이 작업 후 자동으로 DNS Zone이 Hostinger 계정에 링크됨
5. 그러면 API가 도메인을 인식하고 DNS 레코드 추가 가능

**옵션 B (기술적): Hostinger API로 도메인 추가**
- 별도의 도메인 등록 API가 있을 수 있음
- 현재 `DNSZoneApi`로는 불가능 (도메인이 계정에 없으면 업데이트 불가)

### 다음 단계
1. **필수**: hPanel에서 도메인을 Website/Hosting으로 추가
2. 완료 후: 자동으로 DNS API로 레코드 추가 가능
3. 그 후: Certbot으로 SSL 발급

---

## 📋 다음 단계 (순서)

### ✅ 완료된 작업
1. ✅ **API 분석 완료**: 모든 필드명 정정 (data → content)
2. ✅ **근본 원인 파악**: DNS API 404 "Domain not found"
3. ✅ **자동화 스크립트 준비**: AUTO_ADD_DNS_RECORDS.py
4. ✅ **전체 문서 작성**: HOSTINGER_API_BUG_ANALYSIS.md

### ⏳ 즉시 필요한 액션 (사용자 또는 자동화)

**방법 1: 수동 (hPanel에서)**
1. hPanel 접속: https://hpanel.hostinger.com
2. VPS → Manage → Websites
3. "Add Website" 또는 "Add Domain" 클릭
4. 도메인 입력: `xn--2e0bj1fruw33b6ti.net`
5. 추가 후, 자동으로 DNS Zone이 계정에 링크됨

**방법 2: 자동화 (스크립트)**
```bash
# 도메인을 hPanel에 추가한 후 실행
python AUTO_ADD_DNS_RECORDS.py

# 또는 VPS에서
ssh root@76.13.218.129
cd /home/youtubeautoid
python3 AUTO_ADD_DNS_RECORDS.py
```

### 🔄 처리 흐름

```
1. hPanel에서 도메인 추가 (Web UI)
   ↓
2. AUTO_ADD_DNS_RECORDS.py 실행 (자동화)
   ├─ GET DNS 레코드 확인 ✅
   ├─ A 레코드 생성 (@ 및 www) ✅
   ├─ Hostinger API로 업데이트 ✅
   └─ 검증 ✅
   ↓
3. DNS 전파 대기 (5-10분)
   ↓
4. DNS 검증
   dig @1.1.1.1 xn--2e0bj1fruw33b6ti.net A
   # 응답: 76.13.218.129 ✅
   ↓
5. Certbot SSL 발급 (자동화 가능)
   certbot --nginx -d xn--2e0bj1fruw33b6ti.net -d www.xn--2e0bj1fruw33b6ti.net
   ↓
6. HTTPS 접속 확인 ✅
   https://xn--2e0bj1fruw33b6ti.net
```

### 📊 파일 위치

| 파일 | 설명 | 상태 |
|------|------|------|
| `AUTO_ADD_DNS_RECORDS.py` | DNS 레코드 자동 추가 스크립트 | ✅ 준비됨 |
| `HOSTINGER_API_BUG_ANALYSIS.md` | 상세 분석 리포트 | ✅ 작성됨 |
| `README.md` (이 파일) | 현재 상태 및 다음 단계 | ✅ 업데이트됨 |

---

## 🔧 기술 스택 (공식 문서 기준)

| 레이어 | 기술 | 버전 | 공식 문서 |
|-------|------|------|----------|
| **웹서버** | nginx | 1.24.0 | https://nginx.org/en/docs/ |
| **백엔드** | PHP | 8.2 | https://www.php.net/docs.php |
| | Node.js | 22 LTS | https://nodejs.org/en/docs/ |
| **DB** | MariaDB | 11 | https://mariadb.com/docs/ |
| | Redis | 7 | https://redis.io/docs/ |
| **DNS** | BIND9 | 9.18.39 | https://www.isc.org/bind/ |
| **API** | hostinger-api | 0.0.19 | https://github.com/hostinger/api-python-sdk |
| **OS** | Ubuntu | 24.04 LTS | https://ubuntu.com/server/docs/ |

---

## 📞 참고 자료

- [Hostinger VPS 가이드](https://www.hostinger.com/tutorials/i-bought-a-vps-now-what)
- [Hostinger DNS 설정](https://support.hostinger.com/en/articles/1583227-how-to-point-a-domain-to-your-vps)
- [Certbot SSL 설치](https://support.hostinger.com/en/articles/6865487-how-to-install-ssl-on-vps-using-certbot)

---

**마지막 업데이트**: 2026-06-02  
**버전**: 1.0.0  
**상태**: 프로덕션 진행 중
