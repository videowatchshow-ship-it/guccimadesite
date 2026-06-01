# 해킹 대비 보안 체크리스트 (100개)

**공식 문서**: https://owasp.org/www-project-top-ten/  
**참고**: https://cheatsheetseries.owasp.org/  
**생성일**: 2026-06-02

---

## 📋 해킹 대비 보안 (100개)

### 1. 인증 및 세션 보안 (15개)

| # | 항목 | 상태 | 정규식 | 비고 |
|---|------|------|--------|------|
| 1 | 비밀번호 해싱 알고리즘 | ⏳ | `^(bcrypt\|argon2\|scrypt)$` | bcrypt 권장 |
| 2 | 비밀번호 최소 길이 | ⏳ | `^[0-9]{2}$` | 12자 이상 |
| 3 | 비밀번호 복잡도 요구 | ⏳ | `^(required\|optional)$` | 대문자, 숫자, 특수문자 |
| 4 | 비밀번호 만료 정책 | ⏳ | `^[0-9]{2,3}d$` | 90일 권장 |
| 5 | 비밀번호 재사용 방지 | ⏳ | `^(enabled\|disabled)$` | 최근 5개 방지 |
| 6 | 계정 잠금 정책 | ⏳ | `^(enabled\|disabled)$` | 5회 실패 후 잠금 |
| 7 | 계정 잠금 시간 | ⏳ | `^[0-9]{2,4}s$` | 15분 권장 |
| 8 | 세션 타임아웃 | ⏳ | `^[0-9]{2,4}m$` | 30분 권장 |
| 9 | 세션 고정 방지 | ⏳ | `^(enabled\|disabled)$` | 로그인 후 세션 ID 변경 |
| 10 | 동시 세션 제한 | ⏳ | `^[0-9]{1,2}$` | 최대 3개 권장 |
| 11 | 2FA 활성화 | ⏳ | `^(enabled\|disabled)$` | 2단계 인증 필수 |
| 12 | 2FA 방법 | ⏳ | `^(totp\|sms\|email)$` | TOTP 권장 |
| 13 | 백업 코드 생성 | ⏳ | `^(generated\|not_generated)$` | 복구 코드 제공 |
| 14 | 로그인 시도 로깅 | ⏳ | `^(enabled\|disabled)$` | 모든 시도 기록 |
| 15 | 의심 활동 감지 | ⏳ | `^(enabled\|disabled)$` | 비정상 로그인 감지 |

---

### 2. 입력 검증 및 출력 인코딩 (15개)

| # | 항목 | 상태 | 정규식 | 비고 |
|---|------|------|--------|------|
| 16 | 입력 길이 제한 | ⏳ | `^(enforced\|not_enforced)$` | 최대 길이 설정 |
| 17 | 입력 타입 검증 | ⏳ | `^(strict\|loose)$` | 엄격한 검증 |
| 18 | 화이트리스트 검증 | ⏳ | `^(enabled\|disabled)$` | 허용된 문자만 |
| 19 | SQL Injection 방지 | ⏳ | `^(parameterized\|concatenated)$` | Prepared Statement 사용 |
| 20 | NoSQL Injection 방지 | ⏳ | `^(protected\|vulnerable)$` | 쿼리 검증 |
| 21 | XSS 방지 | ⏳ | `^(protected\|vulnerable)$` | 출력 인코딩 |
| 22 | HTML 인코딩 | ⏳ | `^(enabled\|disabled)$` | HTML 특수문자 인코딩 |
| 23 | JavaScript 인코딩 | ⏳ | `^(enabled\|disabled)$` | JS 특수문자 인코딩 |
| 24 | URL 인코딩 | ⏳ | `^(enabled\|disabled)$` | URL 특수문자 인코딩 |
| 25 | CSS 인코딩 | ⏳ | `^(enabled\|disabled)$` | CSS 특수문자 인코딩 |
| 26 | 파일 업로드 검증 | ⏳ | `^(strict\|loose)$` | 파일 타입 검증 |
| 27 | 파일 크기 제한 | ⏳ | `^[0-9]{1,4}MB$` | 최대 크기 설정 |
| 28 | 파일 확장자 화이트리스트 | ⏳ | `^(enabled\|disabled)$` | 허용된 확장자만 |
| 29 | 파일 MIME 타입 검증 | ⏳ | `^(enabled\|disabled)$` | MIME 타입 확인 |
| 30 | 파일 저장 위치 | ⏳ | `^(outside_webroot\|inside_webroot)$` | 웹루트 외부 저장 |

---

### 3. CSRF 및 CORS 보안 (10개)

| # | 항목 | 상태 | 정규식 | 비고 |
|---|------|------|--------|------|
| 31 | CSRF 토큰 생성 | ⏳ | `^(generated\|not_generated)$` | 모든 폼에 포함 |
| 32 | CSRF 토큰 검증 | ⏳ | `^(validated\|not_validated)$` | 모든 POST 요청 검증 |
| 33 | CSRF 토큰 만료 | ⏳ | `^[0-9]{2,4}m$` | 30분 권장 |
| 34 | SameSite 쿠키 설정 | ⏳ | `^(Strict\|Lax\|None)$` | Strict 권장 |
| 35 | CORS 정책 설정 | ⏳ | `^(restrictive\|permissive)$` | 필요한 도메인만 |
| 36 | CORS 프리플라이트 | ⏳ | `^(enabled\|disabled)$` | OPTIONS 요청 처리 |
| 37 | CORS 자격증명 | ⏳ | `^(allowed\|denied)$` | 필요시만 허용 |
| 38 | CORS 헤더 검증 | ⏳ | `^(validated\|not_validated)$` | Origin 헤더 검증 |
| 39 | 리다이렉트 검증 | ⏳ | `^(validated\|not_validated)$` | 화이트리스트 확인 |
| 40 | 오픈 리다이렉트 방지 | ⏳ | `^(protected\|vulnerable)$` | 외부 URL 차단 |

---

### 4. 암호화 및 데이터 보호 (15개)

| # | 항목 | 상태 | 정규식 | 비고 |
|---|------|------|--------|------|
| 41 | HTTPS 사용 | ⏳ | `^(enabled\|disabled)$` | 모든 통신 암호화 |
| 42 | TLS 버전 | ⏳ | `^(1\.3\|1\.2)$` | TLS 1.3 권장 |
| 43 | SSL 인증서 유효성 | ⏳ | `^(valid\|expired)$` | 유효한 인증서 |
| 44 | 암호화 스위트 | ⏳ | `^(strong\|weak)$` | 강력한 암호화 |
| 45 | HSTS 헤더 | ⏳ | `^(enabled\|disabled)$` | max-age 설정 |
| 46 | 민감한 데이터 암호화 | ⏳ | `^(encrypted\|plaintext)$` | AES-256 권장 |
| 47 | 데이터베이스 암호화 | ⏳ | `^(enabled\|disabled)$` | 저장 데이터 암호화 |
| 48 | 백업 암호화 | ⏳ | `^(encrypted\|plaintext)$` | 백업 파일 암호화 |
| 49 | 키 관리 | ⏳ | `^(secure\|insecure)$` | 안전한 키 저장 |
| 50 | 키 로테이션 | ⏳ | `^(enabled\|disabled)$` | 정기적 키 변경 |
| 51 | 암호화 알고리즘 | ⏳ | `^(modern\|deprecated)$` | 최신 알고리즘 사용 |
| 52 | 해시 알고리즘 | ⏳ | `^(SHA256\|MD5)$` | SHA-256 이상 |
| 53 | 난수 생성 | ⏳ | `^(cryptographic\|weak)$` | 암호학적 난수 |
| 54 | 토큰 암호화 | ⏳ | `^(encrypted\|plaintext)$` | 토큰 암호화 저장 |
| 55 | 쿠키 암호화 | ⏳ | `^(encrypted\|plaintext)$` | 민감한 쿠키 암호화 |

---

### 5. 접근 제어 및 권한 관리 (15개)

| # | 항목 | 상태 | 정규식 | 비고 |
|---|------|------|--------|------|
| 56 | 최소 권한 원칙 | ⏳ | `^(enforced\|not_enforced)$` | 필요한 권한만 |
| 57 | 역할 기반 접근 제어 (RBAC) | ⏳ | `^(implemented\|not_implemented)$` | 역할별 권한 |
| 58 | 속성 기반 접근 제어 (ABAC) | ⏳ | `^(implemented\|not_implemented)$` | 속성별 권한 |
| 59 | 관리자 권한 분리 | ⏳ | `^(separated\|combined)$` | 관리자 계정 분리 |
| 60 | 권한 상승 방지 | ⏳ | `^(protected\|vulnerable)$` | 권한 상승 차단 |
| 61 | 수평 권한 상승 방지 | ⏳ | `^(protected\|vulnerable)$` | 다른 사용자 접근 차단 |
| 62 | 수직 권한 상승 방지 | ⏳ | `^(protected\|vulnerable)$` | 상위 권한 접근 차단 |
| 63 | 접근 제어 목록 (ACL) | ⏳ | `^(configured\|not_configured)$` | ACL 설정 |
| 64 | 파일 권한 설정 | ⏳ | `^(restrictive\|permissive)$` | 최소 권한 설정 |
| 65 | 디렉토리 권한 설정 | ⏳ | `^(restrictive\|permissive)$` | 최소 권한 설정 |
| 66 | 소유자 확인 | ⏳ | `^(verified\|not_verified)$` | 리소스 소유자 확인 |
| 67 | 권한 캐싱 | ⏳ | `^(cached\|not_cached)$` | 권한 캐시 설정 |
| 68 | 권한 캐시 만료 | ⏳ | `^[0-9]{2,4}s$` | 5분 권장 |
| 69 | 권한 감사 로그 | ⏳ | `^(enabled\|disabled)$` | 권한 변경 기록 |
| 70 | 권한 검토 | ⏳ | `^(regular\|never)$` | 정기적 검토 |

---

### 6. 로깅 및 모니터링 (15개)

| # | 항목 | 상태 | 정규식 | 비고 |
|---|------|------|--------|------|
| 71 | 보안 이벤트 로깅 | ⏳ | `^(enabled\|disabled)$` | 모든 보안 이벤트 기록 |
| 72 | 로그 레벨 설정 | ⏳ | `^(DEBUG\|INFO\|WARN\|ERROR)$` | INFO 이상 |
| 73 | 로그 보관 기간 | ⏳ | `^[0-9]{2,3}d$` | 90일 이상 |
| 74 | 로그 암호화 | ⏳ | `^(encrypted\|plaintext)$` | 로그 파일 암호화 |
| 75 | 로그 무결성 검증 | ⏳ | `^(enabled\|disabled)$` | 로그 변조 감지 |
| 76 | 로그 중앙화 | ⏳ | `^(centralized\|distributed)$` | 중앙 로그 서버 |
| 77 | 실시간 모니터링 | ⏳ | `^(enabled\|disabled)$` | 실시간 알림 |
| 78 | 이상 탐지 | ⏳ | `^(enabled\|disabled)$` | 비정상 활동 감지 |
| 79 | 알림 설정 | ⏳ | `^(configured\|not_configured)$` | 알림 규칙 설정 |
| 80 | 알림 채널 | ⏳ | `^(email\|sms\|slack)$` | 다중 채널 |
| 81 | 로그 분석 | ⏳ | `^(automated\|manual)$` | 자동 분석 |
| 82 | 보안 정보 및 이벤트 관리 (SIEM) | ⏳ | `^(implemented\|not_implemented)$` | SIEM 도구 |
| 83 | 침입 탐지 시스템 (IDS) | ⏳ | `^(enabled\|disabled)$` | IDS 활성화 |
| 84 | 침입 방지 시스템 (IPS) | ⏳ | `^(enabled\|disabled)$` | IPS 활성화 |
| 85 | 정기적 로그 검토 | ⏳ | `^(weekly\|monthly)$` | 주간 검토 |

---

### 7. 취약점 관리 (10개)

| # | 항목 | 상태 | 정규식 | 비고 |
|---|------|------|--------|------|
| 86 | 정기적 보안 업데이트 | ⏳ | `^(enabled\|disabled)$` | 자동 업데이트 |
| 87 | 패치 관리 정책 | ⏳ | `^(defined\|not_defined)$` | 패치 정책 수립 |
| 88 | 취약점 스캔 | ⏳ | `^(regular\|never)$` | 정기적 스캔 |
| 89 | 침투 테스트 | ⏳ | `^(regular\|never)$` | 정기적 테스트 |
| 90 | 코드 검토 | ⏳ | `^(mandatory\|optional)$` | 필수 코드 검토 |
| 91 | 정적 분석 (SAST) | ⏳ | `^(enabled\|disabled)$` | 정적 분석 도구 |
| 92 | 동적 분석 (DAST) | ⏳ | `^(enabled\|disabled)$` | 동적 분석 도구 |
| 93 | 의존성 검사 | ⏳ | `^(enabled\|disabled)$` | 라이브러리 취약점 검사 |
| 94 | 보안 교육 | ⏳ | `^(regular\|never)$` | 정기적 교육 |
| 95 | 사고 대응 계획 | ⏳ | `^(documented\|not_documented)$` | 대응 계획 수립 |

---

### 8. 기타 보안 (5개)

| # | 항목 | 상태 | 정규식 | 비고 |
|---|------|------|--------|------|
| 96 | 보안 헤더 설정 | ⏳ | `^(complete\|incomplete)$` | 모든 헤더 설정 |
| 97 | 보안 정책 문서화 | ⏳ | `^(documented\|not_documented)$` | 정책 문서 |
| 98 | 보안 감사 | ⏳ | `^(regular\|never)$` | 정기적 감사 |
| 99 | 컴플라이언스 검증 | ⏳ | `^(compliant\|non_compliant)$` | 규정 준수 |
| 100 | 보안 체크리스트 검토 | ⏳ | `^(regular\|never)$` | 정기적 검토 |

---

## 📊 최종 체크 결과

### 상태 요약
- ✅ 체크리스트 생성: 100개 항목
- ⏳ 검증 대기: 모든 항목 (배포 후 검증)

### 카테고리별 항목 수
- 인증 및 세션 보안: 15개
- 입력 검증 및 출력 인코딩: 15개
- CSRF 및 CORS 보안: 10개
- 암호화 및 데이터 보호: 15개
- 접근 제어 및 권한 관리: 15개
- 로깅 및 모니터링: 15개
- 취약점 관리: 10개
- 기타 보안: 5개

### 해킹 대비 전략
- ✅ OWASP Top 10 기준
- ✅ 다층 방어 (Defense in Depth)
- ✅ 정기적 모니터링
- ✅ 사고 대응 계획

---

**생성일**: 2026-06-02  
**상태**: ✅ 생성 완료  
**항목 수**: 100개  
**정규식 검증**: 포함됨  
**공식 문서**: OWASP Top 10 기준

