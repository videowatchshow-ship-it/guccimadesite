# Hostinger IP 보호 체크리스트 (20개)

**공식 문서**: https://support.hostinger.com/en/articles/1583227-how-to-point-domain-to-your-vps  
**주의**: Cloudflare 절대 사용 금지 (Hostinger만 사용)  
**생성일**: 2026-06-02

---

## 📋 Hostinger IP 보호 (20개)

### 1. DNS 레벨 보호 (5개)

| # | 항목 | 상태 | 정규식 | 비고 |
|---|------|------|--------|------|
| 1 | Hostinger BIND9 DNS 사용 | ⏳ | `^(enabled\|disabled)$` | Cloudflare 금지 |
| 2 | DNS Zone 파일 설정 | ⏳ | `^(configured\|not_configured)$` | /etc/bind/zones/ |
| 3 | A 레코드 숨김 | ⏳ | `^(hidden\|visible)$` | IP 직접 노출 방지 |
| 4 | AXFR 전송 비활성화 | ⏳ | `^(disabled\|enabled)$` | DNS 존 전송 방지 |
| 5 | DNS 쿼리 로깅 | ⏳ | `^(enabled\|disabled)$` | 의심 활동 감지 |

---

### 2. 웹 서버 레벨 보호 (5개)

| # | 항목 | 상태 | 정규식 | 비고 |
|---|------|------|--------|------|
| 6 | apache2 Server 헤더 숨김 | ⏳ | `^(hidden\|visible)$` | server_tokens off |
| 7 | apache2 버전 정보 제거 | ⏳ | `^(removed\|present)$` | 버전 정보 노출 방지 |
| 8 | X-Powered-By 헤더 제거 | ⏳ | `^(removed\|present)$` | 기술 스택 숨김 |
| 9 | 에러 페이지 커스터마이징 | ⏳ | `^(customized\|default)$` | 서버 정보 노출 방지 |
| 10 | 디렉토리 리스팅 비활성화 | ⏳ | `^(disabled\|enabled)$` | autoindex off |

---

### 3. 방화벽 레벨 보호 (5개)

| # | 항목 | 상태 | 정규식 | 비고 |
|---|------|------|--------|------|
| 11 | UFW 방화벽 활성화 | ⏳ | `^(enabled\|disabled)$` | 기본 설정 |
| 12 | 불필요한 포트 차단 | ⏳ | `^(blocked\|open)$` | 22, 80, 443만 허용 |
| 13 | 포트 스캔 방지 | ⏳ | `^(enabled\|disabled)$` | nmap 스캔 방지 |
| 14 | DDoS 방어 설정 | ⏳ | `^(configured\|not_configured)$` | fail2ban 설정 |
| 15 | 레이트 리미팅 | ⏳ | `^(enabled\|disabled)$` | 요청 제한 |

---

### 4. 애플리케이션 레벨 보호 (5개)

| # | 항목 | 상태 | 정규식 | 비고 |
|---|------|------|--------|------|
| 16 | 백엔드 API 숨김 | ⏳ | `^(hidden\|visible)$` | 직접 접근 방지 |
| 17 | 내부 IP 노출 방지 | ⏳ | `^(prevented\|exposed)$` | 에러 메시지 검사 |
| 18 | 데이터베이스 포트 숨김 | ⏳ | `^(hidden\|visible)$` | 3306 외부 접근 차단 |
| 19 | Redis 포트 숨김 | ⏳ | `^(hidden\|visible)$` | 6379 외부 접근 차단 |
| 20 | 관리자 패널 숨김 | ⏳ | `^(hidden\|visible)$` | /admin 경로 보호 |

---

## 📊 최종 체크 결과

### 상태 요약
- ✅ 체크리스트 생성: 20개 항목
- ⏳ 검증 대기: 모든 항목 (배포 후 검증)

### 카테고리별 항목 수
- DNS 레벨 보호: 5개
- 웹 서버 레벨 보호: 5개
- 방화벽 레벨 보호: 5개
- 애플리케이션 레벨 보호: 5개

### 중요 사항
- ❌ Cloudflare 절대 사용 금지
- ✅ Hostinger BIND9 DNS만 사용
- ✅ 모든 IP 정보 숨김
- ✅ 서버 정보 노출 방지

---

**생성일**: 2026-06-02  
**상태**: ✅ 생성 완료  
**항목 수**: 20개  
**정규식 검증**: 포함됨  
**주의**: Cloudflare 금지, Hostinger만 사용

