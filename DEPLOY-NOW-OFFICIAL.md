# 🚀 지금 바로 배포 (공식 Hostinger 문서 기준)

**공식 문서**: https://support.hostinger.com/en/articles/5723772-how-to-connect-to-your-vps-via-ssh

**배포 방법**: Hostinger Browser Terminal (권장 - 공식 문서)

---

## 📌 배포 정보

| 항목 | 값 |
|------|-----|
| **VPS 호스트** | 76.13.218.129 |
| **호스트명** | srv1636789.hstgr.cloud |
| **OS** | Ubuntu 24.04 LTS |
| **사용자** | root |
| **배포 시간** | 약 15분 |

---

## 🎯 배포 3단계

### ✅ Step 1: Hostinger hPanel 접속

```
1. https://hpanel.hostinger.com 접속
2. 좌측 메뉴에서 "VPS" 클릭
3. 서버 목록에서 "srv1636789.hstgr.cloud" 찾기
4. 우측의 "Manage" 버튼 클릭
```

### ✅ Step 2: Browser Terminal 열기

```
1. VPS Overview 페이지 우측 상단의 "Terminal" 버튼 클릭
2. 새 창이 열리고 자동으로 root 사용자로 로그인됨
3. 프롬프트가 나타남: root@srv1636789:~#
```

### ✅ Step 3: 배포 스크립트 실행

Browser Terminal에서 다음 명령어를 **복사하여 붙여넣기** 하세요:

```bash
# 1단계: 배포 스크립트 다운로드
curl -O https://raw.githubusercontent.com/your-repo/scripts/auto-deploy.sh

# 2단계: 실행 권한 부여
chmod +x auto-deploy.sh

# 3단계: 배포 시작 (약 15분 소요)
bash auto-deploy.sh
```

---

## 📋 배포 스크립트 실행 내용

### Phase 1: 서버 준비 (3단계)
- VPS 초기화 및 상태 확인
- Ubuntu 업데이트 (apt update, apt upgrade)
- SSH 보안 설정

### Phase 2: Docker 설치 (2단계)
- Docker 설치 (공식 문서: https://docs.docker.com)
- Docker Compose 설치

### Phase 3: 데이터베이스 설치 (2단계)
- MariaDB 11 Stable 설치 (https://mariadb.com/docs)
- Redis 7 Stable 설치 (https://redis.io/docs)

### Phase 4: 웹 서버 설치 (1단계)
- nginx Stable 설치 (https://nginx.org/en/docs)

### Phase 5: Node.js 설치 (1단계)
- Node.js 22 LTS 설치 (https://nodejs.org/en/docs)

### Phase 6: 보안 설정 (3단계)
- UFW 방화벽 설정 (포트: 22, 80, 443)
- fail2ban 설치 및 설정
- SSL/TLS 설정 (Certbot)

### Phase 7: 애플리케이션 배포 (3단계)
- Backend 디렉토리 생성 (/var/www/backend)
- Frontend 디렉토리 생성 (/var/www/frontend)
- Streaming 디렉토리 생성 (/var/www/streaming)

### Phase 8: 모니터링 및 백업 (3단계)
- 모니터링 설정 (/var/log/app)
- 백업 설정 (/backups)
- 로그 관리 설정

### Phase 9: 최종 검증 (2단계)
- 성능 최적화
- 최종 검증 및 서비스 상태 확인

---

## ✅ 배포 진행 상황 확인

배포 중에 다음과 같은 메시지가 나타납니다:

```
╔════════════════════════════════════════════════════════════════════════════╗
║ 2026 Production-Ready 플랫폼 자동 배포                                     ║
╚════════════════════════════════════════════════════════════════════════════╝

[INFO] 배포 시작 시간: 2026-05-17 10:30:45
[INFO] 호스트: srv1636789
[INFO] IP: 76.13.218.129

╔════════════════════════════════════════════════════════════════════════════╗
║ Phase 1: 서버 준비                                                         ║
╚════════════════════════════════════════════════════════════════════════════╝

[INFO] 1단계: VPS 초기화
[✓] OS: Ubuntu 24.04.1 LTS
[✓] 디스크 사용률: 15%
[✓] 메모리 사용량: 512M/4G

[INFO] 2단계: Ubuntu 업데이트
[✓] apt update 완료
[✓] apt upgrade 완료

[INFO] 3단계: SSH 보안 설정
[✓] SSH 설정 백업 완료

╔════════════════════════════════════════════════════════════════════════════╗
║ Phase 2: Docker 설치                                                       ║
╚════════════════════════════════════════════════════════════════════════════╝

[INFO] 4단계: Docker 설치
[✓] Docker 설치 완료

[INFO] 5단계: Docker Compose 설치
[✓] Docker Compose 설치 완료

... (계속)
```

---

## 🎉 배포 완료 후

배포가 완료되면 다음 메시지가 나타납니다:

```
╔════════════════════════════════════════════════════════════════════════════╗
║                    배포 성공! 🎉                                           ║
╚════════════════════════════════════════════════════════════════════════════╝

[INFO] 배포 완료 시간: 2026-05-17 10:35:20

[INFO] 다음 단계:
[INFO] 1. Backend 배포: cd /var/www/backend && git clone <repo>
[INFO] 2. Frontend 배포: cd /var/www/frontend && git clone <repo>
[INFO] 3. Streaming 배포: cd /var/www/streaming && git clone <repo>
[INFO] 4. 서비스 시작: docker-compose up -d
[INFO] 5. 상태 확인: docker ps
```

---

## 🔍 배포 후 서비스 상태 확인

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

## 📞 참고 자료

### 공식 문서
- [Hostinger VPS SSH 연결 (공식)](https://support.hostinger.com/en/articles/5723772-how-to-connect-to-your-vps-via-ssh)
- [Ubuntu 서버 가이드](https://ubuntu.com/server/docs)
- [Docker 문서](https://docs.docker.com)
- [Node.js 문서](https://nodejs.org/en/docs)
- [nginx 문서](https://nginx.org/en/docs)
- [MariaDB 문서](https://mariadb.com/docs)
- [Redis 문서](https://redis.io/docs)

---

**상태**: ✅ 배포 준비 완료  
**배포 방법**: Hostinger Browser Terminal (공식 권장)  
**다음 단계**: 위의 배포 3단계를 따라 실행  

**행운을 빕니다! 🚀**
