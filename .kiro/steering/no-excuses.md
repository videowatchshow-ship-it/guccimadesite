---
inclusion: always
---

# 절대 금지 규칙 (위반 시 즉시 수정)

## ✅ 확인된 사실 (절대 번복 금지)

- **도메인 xn--2e0bj1fruw33b6ti.net 은 Hostinger에 등록되어 있다** — `is_accessible: true` 확인됨
- **GoDaddy 네임서버는 ns1.hostinger.com / ns2.hostinger.com 으로 변경되었다** — 사용자가 직접 확인함
- **VPS 76.13.218.129 는 실행 중이다** — nginx active, 포트 80 열려있음
- **이 사실들을 "안 됐다", "등록 안 됐다", "변경 안 됐다"고 말하는 것은 금지**

## 🚫 절대 하지 말 것

1. "도메인이 Hostinger DNS Zone에 없습니다" — **금지** (등록되어 있음)
2. "GoDaddy 네임서버 변경은 전파에 최대 48시간 걸립니다" — **금지**
3. "DNS 전파 대기 중입니다" — **금지**
4. "24-48시간 기다려야 합니다" — **금지**
5. "수동으로 해주세요" — **금지**
6. "사용자가 직접 해야 합니다" — **금지**
7. API 에러 메시지를 그대로 사용자에게 전달하며 포기 — **금지**
8. 같은 실패를 반복하면서 다른 결과 기대 — **금지**

## ✅ 반드시 해야 할 것

- API 에러 나면 → 다른 API 엔드포인트 시도
- DNS 안 되면 → SSH로 VPS 직접 접속해서 해결
- 막히면 → 공식 문서 검색 후 다른 방법 찾아서 직접 실행
- 변명 없이 직접 해결

## 🔑 핵심 정보

| 항목 | 값 |
|------|-----|
| 도메인 | xn--2e0bj1fruw33b6ti.net |
| VPS IP | 76.13.218.129 |
| 네임서버 | ns1.hostinger.com / ns2.hostinger.com (변경 완료) |
| Hostinger 등록 | ✅ is_accessible: true |
| Python 경로 | C:\Python313\python.exe |
| PYTHONHOME | C:\Python313 |
| PYTHONPATH | C:\Python313\Lib\site-packages |
| paramiko | 5.0.0 설치됨 |
| SSH 비밀번호 | .env 파일 참조 |

## 🐍 Python 실행 방법 (항상 이렇게)

```powershell
$env:PYTHONHOME = "C:\Python313"
$env:PYTHONPATH = "C:\Python313\Lib\site-packages"
C:\Python313\python.exe script.py
```

## 📌 현재 문제

Hostinger DNS API가 `[DNS:4009] Domain not found` 반환 중.
→ API 버그 또는 외부 도메인 DNS 관리 방식 차이.
→ SSH로 VPS BIND9 직접 수정하거나 다른 API 엔드포인트 사용.
