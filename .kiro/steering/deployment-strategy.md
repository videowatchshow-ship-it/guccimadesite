---
inclusion: manual
---

# 구찌야놀자.net 배포 전략 (2026-06-01 최종)

## 📌 상황 요약

### 서버 상태 ✅
- **위치**: `/var/www/gucci-yanonlja-net`
- **상태**: 거의 완성된 상태 (PHP 기반)
- **파일**: 55개, 920KB
- **프로세스**: nginx + MariaDB + Redis 실행 중
- **기능**:
  - ✅ 관리자 대시보드 (admin/)
  - ✅ WebSocket 채팅 (core/websocket/)
  - ✅ Google OAuth (core/auth/)
  - ✅ 모바일 지원 (public/mobile/)
  - ✅ SEO 최적화 (core/helpers/seo-meta.php)
  - ✅ 보안 헤더 (core/helpers/security-headers.php)
  - ✅ 데이터베이스 마이그레이션 (database/)

### 로컬 상태 ✅
- **구조**: Node.js + Docker Compose
- **용도**: 서버 기능 보완 및 추가 개발
- **상태**: 커밋 완료 (f512725)

### GitHub 상태 ✅
- **최신 커밋**: `f512725` (Update deployment configuration and add server code backup analysis)
- **구조**: 로컬과 동일 (Docker Compose 기반)

---

## 🎯 배포 전략 (3단계)

### Step 1️⃣: 서버 코드 보존 ✅ 완료
- [x] 서버 전체 코드 백업 (55개 파일)
- [x] 로컬에 다운로드 및 압축 해제
- [x] 서버 구조 분석 완료
- [x] 백업 위치: `f:\youtubeautoid\backups\gucci-yanonlja-net\`

### Step 2️⃣: GitHub에 서버 코드 추가 (다음)
```bash
# 1. 로컬 backups/gucci-yanonlja-net/ 디렉토리를 GitHub에 추가
# 2. 커밋: "Add server code backup (PHP-based gucci-yanonlja-net)"
# 3. 기존 backend/, frontend/ 코드와 병행 유지
```

### Step 3️⃣: 로컬 추가 수정 (그 다음)
```bash
# 1. 서버 코드 기반으로 로컬 코드 수정
# 2. Node.js 기능 추가 (필요한 경우만)
# 3. Docker Compose 설정 업데이트
# 4. 커밋 및 푸시
```

---

## 📂 최종 파일 구조

```
f:\youtubeautoid/
├── backups/
│   └── gucci-yanonlja-net/              # 서버 코드 백업 (PHP 기반)
│       ├── admin/                       # 관리자 대시보드
│       ├── config/                      # 설정 파일
│       ├── core/                        # 핵심 기능
│       │   ├── auth/                    # Google OAuth
│       │   ├── helpers/                 # SEO, 보안, 모바일
│       │   └── websocket/               # WebSocket 채팅
│       ├── database/                    # DB 마이그레이션
│       ├── public/                      # 공개 파일
│       │   └── mobile/                  # 모바일 UI
│       ├── composer.json
│       └── .env
├── backend/                             # Node.js 추가 기능
├── frontend/                            # Next.js 추가 기능
├── docker/                              # Docker Compose
├── nginx/                               # nginx 설정
├── database/                            # DB 마이그레이션
└── scripts/                             # 배포 스크립트
```

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
| **nginx 설정** | /etc/nginx/sites-available/gucci-yanonlja-net |

---

## 📊 서버 구조 상세

### admin/ - 관리자 시스템
```
admin/
├── api/
│   └── stream-key.php          # 스트림 키 생성 API
└── dashboard/
    └── index.php               # 관리자 대시보드
```

### core/ - 핵심 기능
```
core/
├── auth/
│   ├── google-auth-api.php     # Google OAuth API
│   └── google-auth-unified.js  # 통합 인증
├── helpers/
│   ├── footer.php              # 푸터
│   ├── header.php              # 헤더
│   ├── health.php              # 헬스 체크
│   ├── mobile-helper.php       # 모바일 지원
│   ├── security-headers.php    # 보안 헤더
│   └── seo-meta.php            # SEO 메타 태그
└── websocket/
    ├── websocket-chat-server.js    # 채팅 서버
    ├── websocket-server-ssl.js     # SSL 지원
    └── websocket-server.js         # 기본 서버
```

### database/ - 데이터베이스
```
database/
├── migrations/
│   └── 001-initial-schema.sql  # 초기 스키마
└── schemas/
    └── db-helper.php           # DB 헬퍼
```

### public/ - 공개 파일
```
public/
├── mobile/
│   └── assets/
│       ├── images/             # 이미지
│       └── js/                 # JavaScript
└── index.php                   # 메인 페이지
```

---

## 🚀 다음 단계

### 즉시 실행
1. ✅ 서버 코드 백업 및 분석 완료
2. ✅ 로컬 Git 커밋 완료
3. ✅ GitHub 푸시 완료

### 다음 실행
1. [ ] 로컬 backups/gucci-yanonlja-net/ 디렉토리를 GitHub에 추가
2. [ ] 커밋: "Add server code backup (PHP-based gucci-yanonlja-net)"
3. [ ] 로컬 추가 수정 시작

### 최종 배포
1. [ ] 서버에서 GitHub pull
2. [ ] 서비스 재시작
3. [ ] 상태 확인

---

## 📞 참고 자료

- [Paramiko 공식 문서](https://docs.paramiko.org/en/stable/api/client.html)
- [SSH 공식 문서](https://www.openssh.com/specs.html)
- [Ubuntu SSH 가이드](https://help.ubuntu.com/community/SSH/OpenSSH/Keys)
- [Hostinger VPS 가이드](https://support.hostinger.com/en/articles/5723772-how-to-connect-to-your-vps-via-ssh)

---

## 🎉 배포 준비 완료!

**상태**: ✅ Step 1 완료, Step 2 준비 중  
**마지막 업데이트**: 2026-06-01  
**다음 단계**: 서버 코드를 GitHub에 추가

---

**핵심 원칙:**
1. 서버 코드 보존 (절대 삭제 금지)
2. 로컬 추가 수정만 진행
3. GitHub 동기화 유지
4. 점진적 통합

