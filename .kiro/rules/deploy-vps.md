# VPS 자동 배포 규칙

**규칙 ID**: deploy-vps  
**설명**: Hostinger VPS 서버에 자동으로 배포 스크립트를 실행합니다.  
**상태**: ✅ 활성화  

---

## 배포 명령어

```bash
# Hostinger hPanel에서 VPS → Manage → Terminal 클릭 후 실행:

curl -O https://raw.githubusercontent.com/your-repo/scripts/auto-deploy.sh
chmod +x /tmp/auto-deploy.sh
bash /tmp/auto-deploy.sh
```

---

## VPS 정보

```
호스트: 76.13.218.129
호스트명: srv1636789.hstgr.cloud
사용자: root
비밀번호: .env 파일 참조
OS: Ubuntu 24.04 LTS
```

---

## 배포 내용

- Phase 1: 서버 준비 (3단계)
- Phase 2: Docker 설치 (2단계)
- Phase 3: 데이터베이스 설치 (2단계)
- Phase 4: 웹 서버 설치 (1단계)
- Phase 5: Node.js 설치 (1단계)
- Phase 6: 보안 설정 (3단계)
- Phase 7: 애플리케이션 배포 (3단계)
- Phase 8: 모니터링 및 백업 (3단계)
- Phase 9: 최종 검증 (2단계)

---

## 배포 완료 확인

```bash
docker ps
systemctl status docker
systemctl status apache2
systemctl status mariadb
systemctl status redis-server
```

