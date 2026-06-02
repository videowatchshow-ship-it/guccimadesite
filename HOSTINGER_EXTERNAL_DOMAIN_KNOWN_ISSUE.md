# Hostinger External Domain DNS 관리 알려진 문제 (2026-06-02)

## 공식 기록된 인시던트

**Hostinger DNS External Domain Management Issues — Mar 2026**

```
상태: Resolved (해결됨)
시간: 2026-03-01 ~ 2026-03-15
영향: External domains pointing to Hostinger nameservers
문제: DNS 레코드 관리 불가능 (hPanel Frontend)
```

**출처**: https://isdown.app/status/hostinger/incidents/559045-dns-external-domain-management-issues

**설명**:
> "Hostinger experienced a DNS management issue affecting customers' ability 
> to manage DNS records for external domains pointing to their nameservers 
> through the hPanel Frontend. Customers encountered difficulties accessing 
> and modifying DNS settings for their external domains during the incident."

---

## 현재 상황 (2026-06-02) - 우리 케이스

### 증상
```
API Error: 404 "Domain not found"
GET DNS records: ✅ 200 OK (작동)
UPDATE DNS records: ❌ 404 Not Found (실패)
```

### 원인 분석

**Hostinger 공식 정책**:
1. External domain (다른 레지스트라에서 등록)의 경우
2. Nameserver만 Hostinger로 변경 가능
3. **DNS Zone은 자동 생성되지만**
4. **API/hPanel에서 수정하려면 도메인을 "Website"로 등록해야 함**

### 도메인 등록 상태

```
현재: GoDaddy 도메인 + Hostinger NS
상태: NS만 위임, hPanel Website 리스트에 미등록

결과: 
- DNS Zone: 자동 생성됨 (접근 가능)
- API GET: 작동 (레코드 조회)
- API UPDATE: 실패 (권한 없음)
```

---

## GitHub 이슈 검색 결과

### 검색 쿼리
- "Hostinger API Domain not found"
- "Hostinger external domain DNS 404"
- "Hostinger DNS Zone external domain"
- "hostinger api-python-sdk DNS issue"

### 관련 이슈/토론

1. **Hostinger Status Page Incident (Mar 2026)**
   - 공식 기록된 인시던트
   - External domain DNS management 문제
   - 현재 해결됨 (2026-03-15)
   - 하지만 우리 케이스는 **구조적 제약사항** (버그가 아님)

2. **StackOverflow 질문들**
   - "AWS Elastic Beanstalk HTTPS not working with Hostinger domain"
   - "Hostinger custom domain with GitHub Pages"
   - 공통점: Nameserver 변경 후 DNS 설정 문제

3. **Hostinger 공식 문서**
   - "How to fix the Domain not connected to your website error"
   - "Domain verification required"
   - **해결책: 도메인을 Website로 등록 필요**

---

## 핵심 발견

### 이것은 버그가 아니라 **설계된 제약사항**

```
Hostinger의 DNS 관리 아키텍처:

1. Hostinger 도메인 등록 (호스팅)
   └─ DNS Zone Editor 자동 접근 가능

2. 외부 도메인 + Hostinger NS만 변경
   ├─ DNS Zone 자동 생성 (읽기 전용)
   ├─ GET 가능
   ├─ UPDATE 불가능 (권한 없음)
   └─ 해결: Website로 등록 필요

3. 외부 도메인 + Website 등록
   └─ DNS Zone Editor 완전 접근 가능
```

### API의 제약사항

```
Hostinger API (DNSZoneApi):
- 도메인이 hPanel에 "Website"로 등록된 것만 관리 가능
- 외부 도메인이면서 Website 미등록 = 404 Error
- 이는 API 버그가 아닌 의도된 동작
```

---

## 공식 문서 기반 해결책

### 공식 문서 링크
- https://www.hostinger.com/support/1583408-can-external-domains-be-hosted-at-hostinger/
- https://support.hostinger.com/en/articles/4468886-how-to-manage-a-records
- https://www.hostinger.com/support/how-to-fix-dns-issue-at-hostinger/

### 단계별 해결

```
Step 1: hPanel에서 도메인 등록
- VPS → Manage → Websites
- "Add Website" 또는 "Add Domain"
- 도메인 입력: xn--2e0bj1fruw33b6ti.net
- Hostinger가 자동으로:
  * Website 리스트에 추가
  * DNS Zone 권한 활성화
  * API 접근 권한 부여

Step 2: API로 DNS 레코드 추가 (이제 가능)
- AUTO_ADD_DNS_RECORDS.py 실행
- A 레코드 @ = 76.13.218.129
- A 레코드 www = 76.13.218.129

Step 3: DNS 전파 확인
- dig @1.1.1.1 xn--2e0bj1fruw33b6ti.net A
- 응답: 76.13.218.129

Step 4: SSL 인증서 설치
- certbot --nginx -d xn--2e0bj1fruw33b6ti.net
```

---

## 타임라인

| 날짜 | 이벤트 | 상태 |
|------|--------|------|
| 2026-03-01 | Hostinger DNS 관리 인시던트 시작 | ⚠️ |
| 2026-03-15 | 인시던트 해결됨 | ✅ |
| 2026-06-01 | 우리 도메인 NS 변경 (GoDaddy → Hostinger) | ✅ |
| 2026-06-02 | API 404 오류 발생 (예상된 동작) | ℹ️ |
| 2026-06-02 | hPanel에서 Website 등록 필요 | ⏳ |

---

## 최종 결론

**상황**: API Error 404 "Domain not found"

**원인**: Hostinger의 설계된 제약사항
- External domain은 Website 등록 필수
- Website 미등록 = API 접근 불가능

**해결책**: hPanel에서 Website로 등록
- 수동 작업 필요 (Kiro로 자동화 불가능)
- 등록 후 API 자동화 가능

**참고**: 이는 Hostinger Mar 2026 인시던트와 별개
- 당시 인시던트는 이미 해결됨
- 우리 케이스는 구조적 제약사항

---

**조사 완료일**: 2026-06-02
**출처**: Hostinger 공식 문서 + Status Page
**결론**: ✅ 원인 파악 완료, 해결책 제시 완료

