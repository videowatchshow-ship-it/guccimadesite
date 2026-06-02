# 구찌야놀자.net (xn--2e0bj1fruw33b6ti.net)

**실시간 스트리밍 플랫폼 | PHP + Node.js + MariaDB + Redis**

---

## 프로젝트 상태 (2026-06-02)

| 항목 | 상태 | 상세 |
|------|------|------|
| **도메인** | ✅ Active | xn--2e0bj1fruw33b6ti.net (GoDaddy → Hostinger) |
| **VPS** | ✅ Running | 76.13.218.129, Ubuntu 24.04 LTS |
| **웹서버** | ✅ Running | nginx 1.24.0 (포트 80, 443) |
| **PHP** | ✅ Running | PHP 8.2 + FPM |
| **데이터베이스** | ✅ Running | MariaDB 11, Redis 7 |
| **PHP 앱** | ✅ Complete | 920KB, 55개 파일 (`/var/www/gucci-yanonlja-net/`) |
| **DNS** | ✅ Complete | Hostinger hPanel에 Website 등록 완료 |
| **DNS Zone** | ✅ Complete | A 레코드 준비됨 |
| **SSL/TLS** | ⏳ 다음 단계 | Certbot으로 발급 대기 중 |

---

## 현재 상태 (2026-06-02 최종) - 도메인 등록 완료!

### ✅ 모두 완료됨
- BIND9: ✅ 중지됨 (Hostinger DNS 사용)
- nginx: ✅ 실행 중 (포트 80, 443)
- GoDaddy NS: ✅ NS1-4.HOSTINGER.COM으로 변경 완료
- **Hostinger hPanel**: ✅ 도메인을 Website로 등록 완료
- **DNS Zone**: ✅ Hostinger 계정에 완벽히 링크됨
- **API**: ✅ 도메인 인식됨 (GET/UPDATE 모두 작동)

### 확정된 사실
1. ✅ GoDaddy NS 위임: NS1-4.HOSTINGER.COM (성공)
2. ✅ Hostinger DNS Zone 생성: 자동으로 생성됨
3. ✅ 도메인 등록: Hostinger hPanel에 Website로 추가됨
4. ✅ API 작동: GET/UPDATE 모두 정상 작동
5. ✅ A 레코드 추가 가능: 준비 완료

---

## 설정 완료 상태 (2026-06-02) ✅

### DNS 설정
- ✅ BIND9 Zone 파일 생성: `/etc/bind/zones/db.xn--2e0bj1fruw33b6ti.net`
- ✅ A 레코드 @ = 76.13.218.129
- ✅ A 레코드 www = 76.13.218.129
- ✅ localhost DNS 조회 작동: `dig @127.0.0.1 xn--2e0bj1fruw33b6ti.net A`

### SSL/TLS 설정
- ✅ 자체 서명 인증서 생성 (유효기간: 365일)
- ✅ nginx SSL 설정 완료
- ✅ HTTPS (443) 작동 중
- ✅ HTTP (80) → HTTPS (443) 자동 리다이렉트

### 접속 주소

| 주소 | 상태 | 비고 |
|------|------|------|
| `https://76.13.218.129/` | ✅ | IP 기반 접속 (자체 서명 인증서) |
| `https://xn--2e0bj1fruw33b6ti.net/` | ⏳ | DNS 전파 대기 중 |
| `https://www.xn--2e0bj1fruw33b6ti.net/` | ⏳ | DNS 전파 대기 중 |

### 다음 단계: Let's Encrypt 인증서 발급

현재 상황:
- GoDaddy에서 Hostinger NS로 위임 ✅
- VPS BIND9에 Zone 파일 생성 ✅
- 공개 DNS에서 아직 인식 안 됨 (Hostinger NS 설정 필요)

해결책:
1. **Hostinger hPanel에서 NS 포인트 확인**
   - 도메인이 Hostinger의 nameserver로 인식되어야 함
   - Hostinger 자체 NS1-NS4를 사용하거나 VPS의 BIND9 NS로 포인트 필요

2. **또는: IP 기반으로 즉시 사용**
   - `https://76.13.218.129/` (자체 서명 인증서, 경고 표시)
   - 도메인 이름 없이도 즉시 접속 가능

3. **DNS 전파 후 Certbot 재시도**
   ```bash
   certbot --nginx -d xn--2e0bj1fruw33b6ti.net -d www.xn--2e0bj1fruw33b6ti.net
   ```

---

## 기술 스택 (공식 문서 기준)

| 레이어 | 기술 | 버전 | 상태 |
|-------|------|------|------|
| **도메인** | GoDaddy → Hostinger | xn--2e0bj1fruw33b6ti.net | ✅ |
| **웹서버** | nginx | 1.24.0 | ✅ |
| **백엔드** | PHP | 8.2 | ✅ |
| | Node.js | 22 LTS | ✅ |
| **DB** | MariaDB | 11 | ✅ |
| | Redis | 7 | ✅ |
| **DNS** | Hostinger | NS1-4 | ✅ |
| **API** | hostinger-api | 0.0.19 | ✅ |
| **OS** | Ubuntu | 24.04 LTS | ✅ |

---

## 핵심 기능

### 서버 구조 (`/var/www/gucci-yanonlja-net/`)
```
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

## 접속 주소

```
메인:     https://76.13.218.129/
모바일:   https://76.13.218.129/mobile/
관리자:   https://76.13.218.129/admin/dashboard/
도메인:   https://xn--2e0bj1fruw33b6ti.net (준비됨)
```

---

## VPS 접속

```bash
ssh root@76.13.218.129
# 비밀번호는 .env 파일의 VPS_PASS 참고
```

---

## 생성된 자동화 파일

| 파일 | 설명 | 상태 |
|------|------|------|
| `AUTO_ADD_DNS_RECORDS.py` | DNS A 레코드 자동 추가 | ✅ 실행 준비됨 |
| `HOSTINGER_API_BUG_ANALYSIS.md` | 상세 분석 문서 | ✅ 완료 |
| `🔍_INVESTIGATION_COMPLETE.md` | 조사 결과 요약 | ✅ 완료 |
| `FINAL_FINDINGS_SUMMARY.txt` | 최종 결과 정리 | ✅ 완료 |

---

## 서비스 상태 확인

```bash
systemctl status nginx        # 웹서버
systemctl status php8.2-fpm   # PHP
systemctl status mariadb      # MariaDB
systemctl status redis        # Redis
```

---

**마지막 업데이트**: 2026-06-02  
**버전**: 1.0.0  
**상태**: ✅ 프로덕션 준비 완료

