# Hostinger API DNS Zone Bug Analysis (2026-06-02)

## 🔍 문제 분석 결과

### API 호출 체인
```
1. get_dns_records_v1(domain)
   ✅ 성공: 200 OK
   응답: [] (0개 레코드 - 공백)
   
2. update_dns_records_v1(domain, update_request)
   ❌ 실패: 404 Not Found
   오류: [DNS:4009] Domain not found
```

### 근본 원인
Hostinger DNS API의 `update_dns_records_v1` 메서드는 다음 조건에서만 작동:
- 도메인이 Hostinger 계정의 Websites/Hosting 리스트에 **등록되어 있어야 함**
- 단순히 NS를 Hostinger로 변경하는 것만으로는 **부족**
- hPanel에서 "Add Website" / "Add Domain" 작업이 **필요**

### 현재 상황
```
GoDaddy DNS
  ↓ (NS 변경)
NS1-4.HOSTINGER.COM (Hostinger nameservers)
  ↓ (위임)
Hostinger DNS Zone (자동 생성됨)
  ├─ GET 가능 (200 OK) ✅
  ├─ 도메인: xn--2e0bj1fruw33b6ti.net
  ├─ 레코드: 0개 (공백)
  └─ UPDATE 불가 (404 NOT FOUND) ❌
       └─ 원인: 도메인이 계정에 등록되지 않음
```

---

## 📋 API 호출 세부 분석

### 1. GET DNS Records (성공)
```python
dns_api.get_dns_records_v1('xn--2e0bj1fruw33b6ti.net')
# Response: 200 OK
# Body: []  (0 records)
```

**Hostinger API 응답 헤더:**
```
HTTP/1.1 200 OK
Content-Type: application/json
X-RateLimit-Limit: 90
X-RateLimit-Remaining: 89
```

**해석**: DNS Zone이 존재하고 접근 가능, 레코드는 비어있음

### 2. UPDATE DNS Records (실패)
```python
update_request = hostinger_api.DNSV1ZoneUpdateRequest(
    zone=[
        hostinger_api.DNSV1ZoneUpdateRequestZoneInner(
            name="@",
            type="A",
            records=[
                hostinger_api.DNSV1ZoneUpdateRequestZoneInnerRecordsInner(
                    name="@",
                    type="A",
                    content="76.13.218.129"
                )
            ]
        ),
        hostinger_api.DNSV1ZoneUpdateRequestZoneInner(
            name="www",
            type="A",
            records=[
                hostinger_api.DNSV1ZoneUpdateRequestZoneInnerRecordsInner(
                    name="www",
                    type="A",
                    content="76.13.218.129"
                )
            ]
        )
    ]
)

dns_api.update_dns_records_v1('xn--2e0bj1fruw33b6ti.net', update_request)
```

**Hostinger API 응답 헤더:**
```
HTTP/1.1 404 Not Found
Content-Type: application/json
X-RateLimit-Limit: 90
X-RateLimit-Remaining: 88
```

**Hostinger API 응답 바디:**
```json
{
    "message": "[DNS:4009] Domain not found",
    "correlation_id": "a1ecfe76-14f7-4e84-82c3-e9fdca87eaa0"
}
```

**해석**: 
- 도메인이 Hostinger 계정에 등록되지 않음
- DNS API가 도메인을 찾을 수 없음
- hPanel에서 도메인을 Website/Hosting으로 추가해야 함

---

## 🔧 API 필드 분석 (SDK v0.0.19)

### DNSV1ZoneUpdateRequestZoneInnerRecordsInner

**필수 필드 (Required):**
- `content: str` (DNS 레코드 값, e.g., "76.13.218.129")

**선택 필드:**
- `name: str` (레코드 이름, e.g., "@", "www")
- `type: str` (enum: A, AAAA, CNAME, MX, TXT, NS, SOA, SRV, CAA)

**정정 사항:**
- ❌ `data` 필드 (존재하지 않음)
- ✅ `content` 필드 (올바른 이름)

### DNSV1ZoneUpdateRequestZoneInner

**필수 필드:**
- `name: str` (레코드 이름, "@" 또는 "www")
- `type: str` (enum: A, AAAA, CNAME, MX, TXT, NS, SOA, SRV, CAA)
- `records: List[DNSV1ZoneUpdateRequestZoneInnerRecordsInner]`

**선택 필드:**
- `ttl: int` (Time To Live)

### DNSV1ZoneUpdateRequest

**필수 필드:**
- `zone: List[DNSV1ZoneUpdateRequestZoneInner]`

---

## ✅ 올바른 코드 예제

```python
import os
from dotenv import load_dotenv
import hostinger_api

# 설정 로드
load_dotenv('.env')
api_token = os.environ.get('BEARER_TOKEN')
domain = 'xn--2e0bj1fruw33b6ti.net'
vps_ip = '76.13.218.129'

# API 클라이언트 초기화
config = hostinger_api.Configuration(access_token=api_token)
api_client = hostinger_api.ApiClient(config)
dns_api = hostinger_api.DNSZoneApi(api_client)

# Step 1: GET 현재 레코드 확인
try:
    records = dns_api.get_dns_records_v1(domain)
    print(f"✅ Current records: {len(records)}")
except Exception as e:
    print(f"❌ GET failed: {e}")

# Step 2: UPDATE DNS 레코드 추가 (도메인이 계정에 등록된 후)
try:
    # A @ 레코드
    record_at = hostinger_api.DNSV1ZoneUpdateRequestZoneInnerRecordsInner(
        name="@",
        type="A",
        content=vps_ip
    )
    
    # A www 레코드
    record_www = hostinger_api.DNSV1ZoneUpdateRequestZoneInnerRecordsInner(
        name="www",
        type="A",
        content=vps_ip
    )
    
    # Zone 1: @ A 레코드 업데이트
    zone1 = hostinger_api.DNSV1ZoneUpdateRequestZoneInner(
        name="@",
        type="A",
        records=[record_at]
    )
    
    # Zone 2: www A 레코드 업데이트
    zone2 = hostinger_api.DNSV1ZoneUpdateRequestZoneInner(
        name="www",
        type="A",
        records=[record_www]
    )
    
    # 업데이트 요청
    update_request = hostinger_api.DNSV1ZoneUpdateRequest(zone=[zone1, zone2])
    
    # 실행
    result = dns_api.update_dns_records_v1(domain, update_request)
    print(f"✅ Update successful: {result}")
    
except Exception as e:
    print(f"❌ UPDATE failed: {e}")
```

---

## 🚀 다음 단계

### 1. Hostinger hPanel에서 도메인 등록 (필수)
```
hPanel URL: https://hpanel.hostinger.com
경로: VPS → Manage → Websites (또는 Hosting)
액션: "Add Website" / "Add Domain" 클릭
입력: xn--2e0bj1fruw33b6ti.net
결과: 도메인이 계정에 등록됨
```

### 2. DNS 레코드 추가 (자동화 가능)
위의 올바른 코드 예제를 사용하여 자동으로 진행 가능

### 3. DNS 전파 확인
```bash
dig @1.1.1.1 xn--2e0bj1fruw33b6ti.net A
dig @1.1.1.1 www.xn--2e0bj1fruw33b6ti.net A
# 응답: 76.13.218.129 (예상값)
```

### 4. SSL 인증서 발급
```bash
certbot --nginx -d xn--2e0bj1fruw33b6ti.net -d www.xn--2e0bj1fruw33b6ti.net
```

---

## 📊 Hostinger API 호출 흐름

```
┌─────────────────┐
│   Python Code   │
└────────┬────────┘
         │
         ↓
┌─────────────────────────────────────┐
│   hostinger_api.Configuration       │
│   + access_token (Bearer Token)     │
└────────┬────────────────────────────┘
         │
         ↓
┌─────────────────────────────────────┐
│   hostinger_api.ApiClient           │
│   + base_url                        │
│   = https://developers.hostinger.com│
└────────┬────────────────────────────┘
         │
         ↓
┌─────────────────────────────────────┐
│   hostinger_api.DNSZoneApi          │
│   ├─ get_dns_records_v1() ✅        │
│   └─ update_dns_records_v1() ❌*    │
└────────┬────────────────────────────┘
         │
         ↓
┌─────────────────────────────────────┐
│   HTTP Request                      │
│   PUT /api/dns/v1/zones/{domain}    │
└────────┬────────────────────────────┘
         │
         ↓
┌─────────────────────────────────────┐
│   Hostinger API Server              │
│   ├─ Domain lookup in account       │
│   ├─ IF found: Process update ✅    │
│   └─ IF NOT found: [DNS:4009] ❌    │
└─────────────────────────────────────┘

* UPDATE fails if domain not linked to account
```

---

## 🔐 API 토큰 정보

**위치**: `.env` 파일
```
BEARER_TOKEN=beueTMXzcJ3Jpa4ghsYRwu2oMUtirrh2h6xSayUa2eee5a82
HOSTINGER_API_TOKEN=beueTMXzcJ3Jpa4ghsYRwu2oMUtirrh2h6xSayUa2eee5a82
```

**토큰 발급처**: https://hpanel.hostinger.com/profile/api

**주의**: 토큰은 API 요청용이며, hPanel 대시보드와는 별개

---

## 📝 결론

**문제**: Hostinger API `update_dns_records_v1` 메서드가 `[DNS:4009] Domain not found` 오류 반환

**원인**: 도메인이 Hostinger 계정의 Website/Hosting 리스트에 **등록되지 않음**

**해결책**: hPanel에서 도메인을 Website 또는 Hosting으로 추가

**파일명 정정**:
- ❌ `data` 
- ✅ `content`

**상태**: ✅ 모든 API 필드 정정 완료, 다음 단계는 hPanel 도메인 등록

---

**생성일**: 2026-06-02  
**분석자**: Kiro AI  
**대상 SDK**: hostinger-api==0.0.19  
**테스트된 엔드포인트**: `/api/dns/v1/zones/{domain}` (PUT, GET)

