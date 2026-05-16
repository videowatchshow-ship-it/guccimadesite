# 소프트웨어 버전 검증 체크리스트 (50개)

## 📦 버전 검증 규칙

### 정규식 패턴
```regex
# Semantic Versioning (X.Y.Z)
^[0-9]+\.[0-9]+\.[0-9]+$

# LTS 버전
^[0-9]+\.[0-9]+\.[0-9]+-lts$

# 버전 범위
^[0-9]+\.[0-9]+\.x$

# 날짜 기반 버전
^[0-9]{4}-[0-9]{2}-[0-9]{2}$
```

---

## 🟢 Node.js 검증 (5개)

### 공식 문서
- https://nodejs.org/en/docs
- https://github.com/nodejs/node

### 체크리스트

- [ ] 1. Node.js 설치 확인 (명령어: `node --version`)
- [ ] 2. Node.js 버전 확인: `22.x.x` (정규식: `^v22\.[0-9]+\.[0-9]+$`)
- [ ] 3. Node.js LTS 확인 (정규식: `^v22`)
- [ ] 4. Node.js 경로 확인 (정규식: `^/usr/bin/node$`)
- [ ] 5. Node.js 권한 확인 (정규식: `^-rwxr-xr-x`)

---

## 📦 npm 검증 (5개)

### 공식 문서
- https://docs.npmjs.com
- https://github.com/npm/cli

### 체크리스트

- [ ] 6. npm 설치 확인 (명령어: `npm --version`)
- [ ] 7. npm 버전 확인 (정규식: `^[0-9]+\.[0-9]+\.[0-9]+$`)
- [ ] 8. npm 캐시 확인 (명령어: `npm cache verify`)
- [ ] 9. npm 레지스트리 확인 (정규식: `^https://registry\.npmjs\.org`)
- [ ] 10. npm 권한 확인 (정규식: `^-rwxr-xr-x`)

---

## 🐳 Docker 검증 (5개)

### 공식 문서
- https://docs.docker.com
- https://github.com/docker/docker-ce

### 체크리스트

- [ ] 11. Docker 설치 확인 (명령어: `docker --version`)
- [ ] 12. Docker 버전 확인 (정규식: `^Docker version [0-9]+\.[0-9]+\.[0-9]+`)
- [ ] 13. Docker 데몬 확인 (명령어: `docker ps`)
- [ ] 14. Docker 권한 확인 (명령어: `docker run hello-world`)
- [ ] 15. Docker 저장소 확인 (명령어: `docker info`)

---

## 🐳 Docker Compose 검증 (5개)

### 공식 문서
- https://docs.docker.com/compose
- https://github.com/docker/compose

### 체크리스트

- [ ] 16. Docker Compose 설치 확인 (명령어: `docker-compose --version`)
- [ ] 17. Docker Compose 버전 확인 (정규식: `^Docker Compose version [0-9]+\.[0-9]+\.[0-9]+`)
- [ ] 18. Docker Compose 파일 확인 (파일: `docker-compose.yml`)
- [ ] 19. Docker Compose 검증 (명령어: `docker-compose config`)
- [ ] 20. Docker Compose 권한 확인 (정규식: `^-rwxr-xr-x`)

---

## 🌐 nginx 검증 (5개)

### 공식 문서
- https://nginx.org/en/docs
- https://github.com/nginx/nginx

### 체크리스트

- [ ] 21. nginx 설치 확인 (명령어: `nginx -v`)
- [ ] 22. nginx 버전 확인 (정규식: `^nginx/[0-9]+\.[0-9]+\.[0-9]+`)
- [ ] 23. nginx 설정 확인 (명령어: `nginx -t`)
- [ ] 24. nginx 상태 확인 (명령어: `systemctl status nginx`)
- [ ] 25. nginx 권한 확인 (정규식: `^-rwxr-xr-x`)

---

## 🗄️ MariaDB 검증 (5개)

### 공식 문서
- https://mariadb.com/docs
- https://github.com/MariaDB/server

### 체크리스트

- [ ] 26. MariaDB 설치 확인 (명령어: `mysql --version`)
- [ ] 27. MariaDB 버전 확인 (정규식: `^mysql  Ver [0-9]+\.[0-9]+\.[0-9]+`)
- [ ] 28. MariaDB 서버 확인 (명령어: `systemctl status mariadb`)
- [ ] 29. MariaDB 연결 확인 (명령어: `mysql -u root -e "SELECT 1"`)
- [ ] 30. MariaDB 권한 확인 (정규식: `^-rwxr-xr-x`)

---

## 🔴 Redis 검증 (5개)

### 공식 문서
- https://redis.io/docs
- https://github.com/redis/redis

### 체크리스트

- [ ] 31. Redis 설치 확인 (명령어: `redis-cli --version`)
- [ ] 32. Redis 버전 확인 (정규식: `^redis-cli [0-9]+\.[0-9]+\.[0-9]+`)
- [ ] 33. Redis 서버 확인 (명령어: `systemctl status redis-server`)
- [ ] 34. Redis 연결 확인 (명령어: `redis-cli PING`)
- [ ] 35. Redis 권한 확인 (정규식: `^-rwxr-xr-x`)

---

## 📝 Git 검증 (5개)

### 공식 문서
- https://git-scm.com/doc
- https://github.com/git/git

### 체크리스트

- [ ] 36. Git 설치 확인 (명령어: `git --version`)
- [ ] 37. Git 버전 확인 (정규식: `^git version [0-9]+\.[0-9]+\.[0-9]+`)
- [ ] 38. Git 설정 확인 (명령어: `git config --list`)
- [ ] 39. Git 저장소 확인 (명령어: `git status`)
- [ ] 40. Git 권한 확인 (정규식: `^-rwxr-xr-x`)

---

## 🐍 Python3 검증 (5개)

### 공식 문서
- https://www.python.org/doc
- https://github.com/python/cpython

### 체크리스트

- [ ] 41. Python3 설치 확인 (명령어: `python3 --version`)
- [ ] 42. Python3 버전 확인 (정규식: `^Python [0-9]+\.[0-9]+\.[0-9]+`)
- [ ] 43. Python3 경로 확인 (정규식: `^/usr/bin/python3`)
- [ ] 44. pip3 설치 확인 (명령어: `pip3 --version`)
- [ ] 45. Python3 권한 확인 (정규식: `^-rwxr-xr-x`)

---

## 🔐 OpenSSL 검증 (5개)

### 공식 문서
- https://www.openssl.org/docs
- https://github.com/openssl/openssl

### 체크리스트

- [ ] 46. OpenSSL 설치 확인 (명령어: `openssl version`)
- [ ] 47. OpenSSL 버전 확인 (정규식: `^OpenSSL [0-9]+\.[0-9]+\.[0-9]+`)
- [ ] 48. OpenSSL 경로 확인 (정규식: `^/usr/bin/openssl`)
- [ ] 49. OpenSSL 권한 확인 (정규식: `^-rwxr-xr-x`)
- [ ] 50. OpenSSL 인증서 확인 (명령어: `openssl x509 -in /etc/ssl/certs/ca-certificates.crt`)

---

## 📊 검증 스크립트

```bash
#!/bin/bash

# 버전 검증 함수
validate_version() {
    local cmd=$1
    local regex=$2
    local name=$3
    
    local version=$($cmd 2>&1)
    
    if [[ $version =~ $regex ]]; then
        echo "✅ $name: $version (검증 통과)"
        return 0
    else
        echo "❌ $name: $version (검증 실패)"
        return 1
    fi
}

# 예제
validate_version "node --version" "^v22\.[0-9]+\.[0-9]+$" "Node.js"
validate_version "npm --version" "^[0-9]+\.[0-9]+\.[0-9]+$" "npm"
validate_version "docker --version" "^Docker version [0-9]+\.[0-9]+\.[0-9]+" "Docker"
```

---

**총 50개 항목 완료**

