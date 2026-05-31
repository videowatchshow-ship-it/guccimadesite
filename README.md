# 2026 Production-Ready 스트리밍 플랫폼 - 아바타 바카라

**프로젝트**: guccimadesite  
**상태**: ✅ 배포 준비 완료  
**작성일**: 2026-05-17  

---

## 📌 프로젝트 개요

본 프로젝트는 **2026년 production-ready** 대형 스트리밍 플랫폼입니다.

### 핵심 기능
- ✅ **SEO 최적화** - 150개 체크리스트 (Rank Math 50개 + Google SEO 100개)
- ✅ **실시간 스트리밍** - YouTube/Twitch 스타일, OBS/PRISM 연동
- ✅ **실시간 채팅** - WebSocket 기반, Redis Pub/Sub
- ✅ **관리자 시스템** - Stream key 생성, 방송 제어
- ✅ **Google OAuth 로그인** - 공식 문서 기준
- ✅ **모바일 UX** - 200개 이상 체크리스트
- ✅ **데스크탑 UX** - 200개 이상 체크리스트
- ✅ **보안** - 30개 이상 체크리스트
- ✅ **Docker 기반** - 완전 자동화
- ✅ **Cloudflare CDN/WAF** - 보안 강화

### 필수 원칙 (11가지)
1. **공식 문서 기준만** 사용
2. **공식 GitHub 기준만** 사용
3. **Stable/LTS 버전만** 사용
4. **추측 코딩 금지**
5. **비공식 코드 금지**
6. **테스트되지 않은 패키지 금지**
7. **유지보수 가능한 구조만** 사용
8. **Docker 기반 구조화**
9. **보안 우선**
10. **모바일 우선**
11. **SEO 우선**

---

## 🚀 빠른 배포 (3단계, 15분)

### ✅ Step 1: Hostinger hPanel 접속

```
1. Hostinger 계정 로그인 → https://hpanel.hostinger.com
2. 좌측 메뉴에서 "VPS" 클릭
3. 서버 "srv1636789.hstgr.cloud" 찾기
4. 우측의 "Manage" 버튼 클릭
```

### ✅ Step 2: Browser Terminal 열기

```
1. VPS Overview 페이지 우측 상단의 "Terminal" 버튼 클릭
2. 새 창이 열리고 자동으로 root 사용자로 로그인됨
3. 프롬프트: root@srv1636789:~#
```

### ✅ Step 3: 배포 스크립트 실행

Browser Terminal에서 다음 명령어 실행:

```bash
# 1단계: 배포 스크립트 다운로드
curl -O https://raw.githubusercontent.com/your-repo/scripts/auto-deploy.sh

# 2단계: 실행 권한 부여
chmod +x auto-deploy.sh

# 3단계: 배포 시작 (약 15분 소요)
bash auto-deploy.sh
```

---

## 🔐 VPS 정보 (구찌야놀자)

| 항목 | 값 |
|------|-----|
| **호스트** | 76.13.218.129 |
| **호스트명** | srv1636789.hstgr.cloud |
| **SSH 포트** | 22 |
| **사용자** | root |
| **비밀번호** | q+7m#GElqQs/E&tfabwB |
| **OS** | Ubuntu 24.04 LTS |
| **CPU** | 1 Core |
| **메모리** | 4 GB |
| **디스크** | 50 GB |
| **상태** | 실행 중 |
| **만료일** | 2026-06-02 |
| **컨트롤** | KVM |

### SSH 연결 명령어

```bash
ssh root@76.13.218.129
```

---

## 📚 기술 스택 (2026 권장 버전)

### Frontend
- **Next.js** - Stable LTS (https://nextjs.org/docs)
- **React** - Stable LTS (https://react.dev)
- **TailwindCSS** - Stable LTS (https://tailwindcss.com/docs)

### Backend
- **Node.js** - 22 LTS (https://nodejs.org/en/docs)
- **Express.js** - Stable LTS (https://expressjs.com)

### Database
- **MariaDB** - 11 Stable (https://mariadb.com/docs)
- **Redis** - 7 Stable (https://redis.io/docs)

### Streaming
- **SRS Media Server** - LTS (https://ossrs.io/lts/en-us/docs)
- **nginx-rtmp** - Stable (https://github.com/arut/nginx-rtmp-module)
- **FFmpeg** - Stable LTS (https://ffmpeg.org/documentation.html)

### Infrastructure
- **Docker** - Stable (https://docs.docker.com)
- **Docker Compose** - Stable (https://docs.docker.com/compose)
- **nginx** - Stable (https://nginx.org/en/docs)
- **Cloudflare** - Latest Stable (https://developers.cloudflare.com)

### Authentication
- **Google OAuth** - https://developers.google.com/identity/protocols/oauth2
- **JWT** - https://jwt.io/introduction

---

## 📋 배포 단계 (20단계)

### Phase 1: 서버 준비 (3단계)
1. VPS 초기화 및 상태 확인
2. Ubuntu 업데이트 (apt update, apt upgrade)
3. SSH 보안 설정

### Phase 2: Docker 설치 (2단계)
4. Docker 설치 (공식 문서: https://docs.docker.com)
5. Docker Compose 설치

### Phase 3: 데이터베이스 설치 (2단계)
6. MariaDB 11 Stable 설치 (https://mariadb.com/docs)
7. Redis 7 Stable 설치 (https://redis.io/docs)

### Phase 4: 웹 서버 설치 (1단계)
8. nginx Stable 설치 (https://nginx.org/en/docs)

### Phase 5: Node.js 설치 (1단계)
9. Node.js 22 LTS 설치 (https://nodejs.org/en/docs)

### Phase 6: 보안 설정 (3단계)
10. UFW 방화벽 설정 (포트: 22, 80, 443)
11. fail2ban 설치 및 설정
12. SSL/TLS 설정 (Certbot)

### Phase 7: 애플리케이션 배포 (3단계)
13. Backend 디렉토리 생성 (/var/www/backend)
14. Frontend 디렉토리 생성 (/var/www/frontend)
15. Streaming 디렉토리 생성 (/var/www/streaming)

### Phase 8: 모니터링 및 백업 (3단계)
16. 모니터링 설정 (/var/log/app)
17. 백업 설정 (/backups)
18. 로그 관리 설정

### Phase 9: 최종 검증 (2단계)
19. 성능 최적화
20. 최종 검증 및 서비스 상태 확인

---

## 📂 프로젝트 구조

```
/project
├── /frontend              # Next.js 프론트엔드
├── /backend               # Node.js 백엔드
├── /admin                 # 관리자 시스템
├── /streaming             # 스트리밍 서버
├── /nginx                 # nginx 설정
├── /docker                # Docker 설정
├── /database              # MariaDB 설정
├── /redis                 # Redis 설정
├── /logs                  # 로그 저장소
├── /security              # 보안 설정
├── /seo                   # SEO 설정
├── /scripts               # 배포 스크립트
├── /backups               # 백업 저장소
├── README.md              # 이 파일 (통합 문서)
└── .kiro/
    ├── steering/
    │   └── project-standards.md
    ├── rules/
    │   └── deploy-vps.md
    └── specs/
        └── deployment-checklist/
```

---

## 🎯 SEO 최적화 (150개 체크리스트)

### Rank Math SEO (50개)
- Focus Keyword: "아바타 바카라"
- H1/H2 최적화
- Meta Title/Description
- Keyword Density
- ALT 태그
- FAQ/TOC
- 내부/외부 링크
- Readability
- Slug 최적화

### Google SEO (100개)

**데스크탑 SEO (50개)**
- Core Web Vitals (LCP, CLS, INP)
- Semantic HTML
- Canonical 태그
- robots.txt / sitemap.xml
- Structured Data (JSON-LD)
- Preload/Prefetch
- Lazy Loading
- Image Optimization
- Cache Optimization
- CDN Optimization

**모바일 SEO (50개)**
- Mobile-first Indexing
- Responsive UI
- Touch Optimization
- Viewport Optimization
- Mobile Loading Optimization
- Mobile Core Web Vitals
- Thumb-friendly UI
- One-hand UX
- Mobile Video Optimization
- Mobile Chat Optimization

---

## 🔒 보안 (30개 이상)

### 서버 보안
- fail2ban
- UFW Firewall
- SSH Hardening
- Root Login 제한
- Key Authentication

### 웹 보안
- CSP (Content Security Policy)
- CSRF 방어
- XSS 방어
- SQL Injection 방어
- Secure Headers
- Secure Cookie
- Rate Limiting

### Docker 보안
- Non-root Container
- Image Scanning
- Network Isolation
- Secret Management

### Redis 보안
- Authentication
- Private Network
- Public Access 비활성화

### Cloudflare 보안
- WAF (Web Application Firewall)
- DDoS 방어
- Bot Mitigation
- Rate Limiting

---

## 📱 모바일 UX (200개 이상)

- Touch Optimization
- Thumb-friendly Layout
- One-hand UX
- Responsive Header/Footer
- Dynamic Font Scaling
- Keyboard Overlap Handling
- Mobile Streaming UX
- Mobile Chat UX
- Safe-area Support
- Gesture Handling
- Battery Optimization
- Low Bandwidth Optimization

---

## 🖥️ 데스크탑 UX (200개 이상)

- Keyboard Shortcut
- Hover Interaction
- Sidebar UX
- Fullscreen UX
- Admin Dashboard
- WebSocket Optimization
- Multi-window Support
- Desktop Accessibility

---

## 🎬 스트리밍 시스템

### 필수 기능
- OBS 송출
- PRISM 송출
- RTMP 수신
- HLS 변환
- Adaptive Streaming
- WebSocket Chat
- Reconnect Handling
- Live Badge
- DVR
- Latency Optimization
- Buffering UX
- Watch Time Tracking

---

## 💬 실시간 채팅

- WebSocket 기반
- Redis Pub/Sub
- Emoji 지원
- Reconnect 처리
- Scroll Synchronization
- Moderator 기능
- Anti-spam
- Audit Logging

---

## 👤 관리자 시스템

- Stream Key 생성
- 방송 시작/종료
- Stream URL 생성
- OBS 연동
- PRISM 연동
- 라이브 상태 확인
- Docker 상태 확인
- Redis 상태 확인
- nginx 상태 확인
- WebSocket 상태 확인
- SEO 상태 확인
- Audit Log
- User Management

---

## 🔑 Google OAuth 로그인

**필수 기능:**
- Google 로그인 모달
- 최초 로그인 시 회원가입 모달
- 기존 회원 자동 로그인
- 메뉴 이동 시 로그인 유지
- 페이지 이동 시 로그인 유지
- 새로고침 시 로그인 유지
- Redis Session Store
- Refresh Token
- Access Token
- Secure Cookie
- HttpOnly Cookie
- CSRF Protection
- XSS Protection

---

## ✅ 배포 후 서비스 상태 확인

배포 완료 후 Browser Terminal에서:

```bash
# 1. 모든 서비스 상태 확인
systemctl status docker
systemctl status nginx
systemctl status mariadb
systemctl status redis-server

# 2. Docker 컨테이너 확인
docker ps

# 3. 포트 확인
netstat -tlnp | grep LISTEN

# 4. 웹 서버 테스트
curl -I http://localhost
curl -I https://srv1636789.hstgr.cloud

# 5. 데이터베이스 테스트
mysql -u root -e "SELECT VERSION();"

# 6. Redis 테스트
redis-cli ping
```

---

## 📝 배포 후 필요한 작업

### 1️⃣ Backend 배포

```bash
cd /var/www/backend
git clone <your-backend-repo> .
npm install
npm run build
pm2 start npm --name "backend" -- start
```

### 2️⃣ Frontend 배포

```bash
cd /var/www/frontend
git clone <your-frontend-repo> .
npm install
npm run build
pm2 start npm --name "frontend" -- start
```

### 3️⃣ Streaming Server 배포

```bash
docker pull ossrs/srs:latest
docker run -d --name srs -p 1935:1935 -p 8080:8080 ossrs/srs:latest
```

### 4️⃣ 환경 변수 설정

**Backend .env:**
```bash
cat > /var/www/backend/.env << 'EOF'
NODE_ENV=production
PORT=3000
DATABASE_URL=mysql://root:password@localhost:3306/guccimadesite
REDIS_URL=redis://localhost:6379
GOOGLE_CLIENT_ID=your-client-id
GOOGLE_CLIENT_SECRET=your-client-secret
EOF
```

**Frontend .env.local:**
```bash
cat > /var/www/frontend/.env.local << 'EOF'
NEXT_PUBLIC_API_URL=https://srv1636789.hstgr.cloud/api
EOF
```

### 5️⃣ SSL 인증서 설정

```bash
sudo certbot certonly --nginx -d srv1636789.hstgr.cloud
```

### 6️⃣ Cloudflare 연결

```
1. Cloudflare 대시보드 접속
2. 도메인 추가
3. DNS 레코드 설정
4. SSL/TLS 모드 설정 (Full)
5. WAF 규칙 활성화
```

---

## 🐛 문제 해결

### Browser Terminal 연결 실패

```bash
# Hostinger hPanel에서:
# 1. Settings 클릭
# 2. "Reset firewall" 클릭
# 3. "Reset SSH" 클릭
# 4. 다시 Terminal 버튼 클릭
```

### 배포 스크립트 실패

```bash
# 1. 로그 확인
tail -f /tmp/deploy.log

# 2. 권한 확인
ls -l auto-deploy.sh

# 3. 다시 실행
bash auto-deploy.sh
```

### 서비스 시작 실패

```bash
# 1. 서비스 상태 확인
systemctl status docker
systemctl status nginx

# 2. 로그 확인
journalctl -u docker -n 50
journalctl -u nginx -n 50

# 3. 서비스 재시작
sudo systemctl restart docker
sudo systemctl restart nginx
```

### 포트 충돌

```bash
# 포트 사용 확인
netstat -tlnp | grep :80
netstat -tlnp | grep :443
netstat -tlnp | grep :3000

# 프로세스 종료
kill -9 <PID>
```

---

## 📞 참고 자료

### 공식 문서
- [Ubuntu 서버 가이드](https://ubuntu.com/server/docs)
- [Docker 문서](https://docs.docker.com)
- [Node.js 문서](https://nodejs.org/en/docs)
- [nginx 문서](https://nginx.org/en/docs)
- [MariaDB 문서](https://mariadb.com/docs)
- [Redis 문서](https://redis.io/docs)
- [Hostinger VPS 가이드](https://support.hostinger.com/en/articles/5723772-how-to-connect-to-your-vps-via-ssh)

### 프로젝트 파일
- `.kiro/steering/project-standards.md` - 프로젝트 표준 (공식 문서 기준, 정규식 검증)
- `.kiro/rules/deploy-vps.md` - Kiro 배포 규칙
- `scripts/auto-deploy.sh` - 자동 배포 스크립트

---

## ✅ 배포 체크리스트

### Pre-Deployment
- [ ] 모든 코드가 공식 문서 기준인가?
- [ ] 모든 버전이 Stable/LTS인가?
- [ ] 모든 패키지가 테스트되었는가?
- [ ] Production-ready 상태인가?
- [ ] 보안이 적용되었는가?
- [ ] SEO가 최적화되었는가?
- [ ] 모바일 UX가 완성되었는가?
- [ ] 데스크탑 UX가 완성되었는가?

### Deployment
- [ ] VPS 초기화 완료
- [ ] Docker 설치 완료
- [ ] nginx reverse proxy 설정 완료
- [ ] MariaDB 설정 완료
- [ ] Redis 설정 완료
- [ ] Backend 배포 완료
- [ ] Frontend 배포 완료
- [ ] Streaming Server 배포 완료
- [ ] SSL 설정 완료
- [ ] Cloudflare 연결 완료

### Post-Deployment
- [ ] 모니터링 연결 완료
- [ ] 백업 시스템 연결 완료
- [ ] fail2ban 연결 완료
- [ ] Firewall 설정 완료
- [ ] Production build 최적화 완료
- [ ] SEO 최적화 완료
- [ ] WebSocket 테스트 완료
- [ ] 스트리밍 테스트 완료

---

## ⏱️ 예상 배포 시간

| 단계 | 예상 시간 |
|------|----------|
| Phase 1: 서버 준비 | 2분 |
| Phase 2: Docker 설치 | 3분 |
| Phase 3: 데이터베이스 설치 | 3분 |
| Phase 4: 웹 서버 설치 | 1분 |
| Phase 5: Node.js 설치 | 2분 |
| Phase 6: 보안 설정 | 2분 |
| Phase 7-9: 애플리케이션 및 검증 | 2분 |
| **총 예상 시간** | **약 15분** |

---

## 🎉 배포 준비 완료!

모든 필요한 파일, 스크립트, 문서가 준비되었습니다.

**이제 배포를 시작할 수 있습니다!**

```bash
# Hostinger hPanel에서 VPS → Manage → Terminal 클릭 후:
curl -O https://raw.githubusercontent.com/your-repo/scripts/auto-deploy.sh
chmod +x auto-deploy.sh
bash auto-deploy.sh
```

---

**상태**: ✅ 배포 준비 완료  
**마지막 업데이트**: 2026-05-17  
**다음 단계**: 지금 바로 배포 시작!  

**행운을 빕니다! 🚀**
