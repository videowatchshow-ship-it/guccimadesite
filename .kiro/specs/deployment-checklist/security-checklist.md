# 보안 설정 체크리스트 (100개)

## 🔐 SSH 보안 (10개)

### 공식 문서
- https://man.openbsd.org/ssh_config
- https://github.com/openssh/openssh-portable

### 체크리스트

- [ ] 1. SSH 포트 변경 확인 (기본값: 22, 정규식: `^Port [0-9]{4,5}$`)
- [ ] 2. Root 로그인 비활성화 (정규식: `^PermitRootLogin no$`)
- [ ] 3. 비밀번호 인증 비활성화 (정규식: `^PasswordAuthentication no$`)
- [ ] 4. 공개 키 인증 활성화 (정규식: `^PubkeyAuthentication yes$`)
- [ ] 5. SSH 키 권한 확인 (정규식: `^-rw-------`)
- [ ] 6. SSH 키 소유자 확인 (정규식: `^root root$`)
- [ ] 7. SSH 설정 파일 권한 (정규식: `^-rw-r--r--`)
- [ ] 8. SSH 데몬 상태 확인 (명령어: `systemctl status ssh`)
- [ ] 9. SSH 로그 확인 (파일: `/var/log/auth.log`)
- [ ] 10. SSH 연결 테스트 (명령어: `ssh -v localhost`)

---

## 🔥 UFW 방화벽 (10개)

### 공식 문서
- https://wiki.ubuntu.com/UncomplicatedFirewall
- https://github.com/ubuntu/ufw

### 체크리스트

- [ ] 11. UFW 활성화 확인 (명령어: `ufw status`)
- [ ] 12. SSH 포트 허용 (정규식: `^22/tcp`)
- [ ] 13. HTTP 포트 허용 (정규식: `^80/tcp`)
- [ ] 14. HTTPS 포트 허용 (정규식: `^443/tcp`)
- [ ] 15. RTMP 포트 허용 (정규식: `^1935/tcp`)
- [ ] 16. 기본 정책 설정 (정규식: `^Default: deny incoming`)
- [ ] 17. 아웃바운드 정책 설정 (정규식: `^Default: allow outgoing`)
- [ ] 18. 로깅 활성화 (명령어: `ufw logging on`)
- [ ] 19. 로그 레벨 확인 (정규식: `^Logging level: low`)
- [ ] 20. 방화벽 규칙 확인 (명령어: `ufw show added`)

---

## 🛡️ fail2ban 설정 (10개)

### 공식 문서
- https://www.fail2ban.org/wiki/index.php/Main_Page
- https://github.com/fail2ban/fail2ban

### 체크리스트

- [ ] 21. fail2ban 설치 확인 (명령어: `fail2ban-client --version`)
- [ ] 22. fail2ban 서비스 확인 (명령어: `systemctl status fail2ban`)
- [ ] 23. SSH 필터 활성화 (정규식: `^enabled = true`)
- [ ] 24. 최대 재시도 횟수 설정 (정규식: `^maxretry = [0-9]+$`)
- [ ] 25. 차단 시간 설정 (정규식: `^findtime = [0-9]+$`)
- [ ] 26. 밴 시간 설정 (정규식: `^bantime = [0-9]+$`)
- [ ] 27. fail2ban 로그 확인 (파일: `/var/log/fail2ban.log`)
- [ ] 28. 차단된 IP 확인 (명령어: `fail2ban-client status sshd`)
- [ ] 29. 필터 규칙 확인 (파일: `/etc/fail2ban/filter.d/sshd.conf`)
- [ ] 30. 액션 규칙 확인 (파일: `/etc/fail2ban/action.d/iptables-multiport.conf`)

---

## 🔒 SSL/TLS 설정 (10개)

### 공식 문서
- https://letsencrypt.org/docs
- https://certbot.eff.org/docs

### 체크리스트

- [ ] 31. SSL 인증서 설치 확인 (파일: `/etc/ssl/certs/`)
- [ ] 32. SSL 인증서 유효성 확인 (명령어: `openssl x509 -in cert.pem -noout -dates`)
- [ ] 33. SSL 인증서 만료일 확인 (정규식: `^notAfter=[0-9]{2} [A-Z]{3} [0-9]{4}`)
- [ ] 34. SSL 프로토콜 버전 확인 (정규식: `^TLSv1\.[23]$`)
- [ ] 35. SSL 암호화 스위트 확인 (정규식: `^ECDHE-RSA-AES`)
- [ ] 36. SSL 인증서 체인 확인 (명령어: `openssl s_client -connect localhost:443`)
- [ ] 37. HSTS 헤더 설정 (정규식: `^Strict-Transport-Security`)
- [ ] 38. 자동 갱신 설정 (명령어: `certbot renew --dry-run`)
- [ ] 39. SSL 로그 확인 (파일: `/var/log/letsencrypt/`)
- [ ] 40. SSL 테스트 (명령어: `curl -I https://localhost`)

---

## 🗄️ 데이터베이스 보안 (10개)

### 공식 문서
- https://mariadb.com/docs/security
- https://dev.mysql.com/doc/refman/8.0/en/security.html

### 체크리스트

- [ ] 41. 데이터베이스 루트 비밀번호 설정 (명령어: `mysql -u root -p`)
- [ ] 42. 익명 사용자 제거 (명령어: `SELECT user FROM mysql.user WHERE user=''`)
- [ ] 43. 원격 루트 로그인 비활성화 (명령어: `SELECT user, host FROM mysql.user WHERE user='root'`)
- [ ] 44. 테스트 데이터베이스 제거 (명령어: `DROP DATABASE test`)
- [ ] 45. 데이터베이스 사용자 권한 설정 (정규식: `^GRANT [A-Z]+ ON`)
- [ ] 46. 데이터베이스 백업 설정 (명령어: `mysqldump --all-databases`)
- [ ] 47. 데이터베이스 로그 활성화 (정규식: `^log_error = `)
- [ ] 48. 데이터베이스 쿼리 로그 (정규식: `^general_log = ON`)
- [ ] 49. 데이터베이스 슬로우 쿼리 로그 (정규식: `^slow_query_log = ON`)
- [ ] 50. 데이터베이스 연결 테스트 (명령어: `mysql -u root -p -e "SELECT 1"`)

---

## 🔴 Redis 보안 (10개)

### 공식 문서
- https://redis.io/docs/management/security
- https://github.com/redis/redis

### 체크리스트

- [ ] 51. Redis 인증 설정 (정규식: `^requirepass [a-zA-Z0-9]+$`)
- [ ] 52. Redis 바인드 주소 설정 (정규식: `^bind 127.0.0.1$`)
- [ ] 53. Redis 포트 설정 (정규식: `^port 6379$`)
- [ ] 54. Redis 보호 모드 활성화 (정규식: `^protected-mode yes$`)
- [ ] 55. Redis 위험한 명령어 비활성화 (정규식: `^rename-command FLUSHDB ""`)
- [ ] 56. Redis 백업 설정 (정규식: `^save [0-9]+ [0-9]+$`)
- [ ] 57. Redis 영속성 설정 (정규식: `^appendonly yes$`)
- [ ] 58. Redis 로그 레벨 설정 (정규식: `^loglevel notice$`)
- [ ] 59. Redis 연결 테스트 (명령어: `redis-cli PING`)
- [ ] 60. Redis 정보 확인 (명령어: `redis-cli INFO`)

---

## 🐳 Docker 보안 (10개)

### 공식 문서
- https://docs.docker.com/engine/security
- https://github.com/docker/docker-ce

### 체크리스트

- [ ] 61. Docker 데몬 보안 (정규식: `^-H unix:///var/run/docker.sock$`)
- [ ] 62. Docker 사용자 그룹 설정 (명령어: `groups $USER`)
- [ ] 63. Docker 이미지 스캔 (명령어: `docker scan image-name`)
- [ ] 64. Docker 컨테이너 권한 제한 (정규식: `^--cap-drop=ALL$`)
- [ ] 65. Docker 컨테이너 읽기 전용 파일시스템 (정규식: `^--read-only$`)
- [ ] 66. Docker 컨테이너 메모리 제한 (정규식: `^-m [0-9]+m$`)
- [ ] 67. Docker 컨테이너 CPU 제한 (정규식: `^--cpus=[0-9]+$`)
- [ ] 68. Docker 네트워크 격리 (명령어: `docker network ls`)
- [ ] 69. Docker 볼륨 권한 설정 (정규식: `^-v /path:/path:ro$`)
- [ ] 70. Docker 로깅 설정 (정규식: `^--log-driver json-file$`)

---

## 🌐 웹 보안 헤더 (10개)

### 공식 문서
- https://owasp.org/www-project-secure-headers
- https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers

### 체크리스트

- [ ] 71. Content-Security-Policy 헤더 (정규식: `^Content-Security-Policy:`)
- [ ] 72. X-Frame-Options 헤더 (정규식: `^X-Frame-Options: DENY$`)
- [ ] 73. X-Content-Type-Options 헤더 (정규식: `^X-Content-Type-Options: nosniff$`)
- [ ] 74. X-XSS-Protection 헤더 (정규식: `^X-XSS-Protection: 1; mode=block$`)
- [ ] 75. Referrer-Policy 헤더 (정규식: `^Referrer-Policy: strict-origin-when-cross-origin$`)
- [ ] 76. Permissions-Policy 헤더 (정규식: `^Permissions-Policy:`)
- [ ] 77. Strict-Transport-Security 헤더 (정규식: `^Strict-Transport-Security:`)
- [ ] 78. 보안 쿠키 설정 (정규식: `^Secure; HttpOnly; SameSite=Strict$`)
- [ ] 79. CORS 설정 (정규식: `^Access-Control-Allow-Origin:`)
- [ ] 80. 보안 헤더 테스트 (명령어: `curl -I https://localhost`)

---

## 🛡️ CSRF/XSS 방어 (10개)

### 공식 문서
- https://owasp.org/www-community/attacks/csrf
- https://owasp.org/www-community/attacks/xss

### 체크리스트

- [ ] 81. CSRF 토큰 생성 (정규식: `^[a-f0-9]{32,}$`)
- [ ] 82. CSRF 토큰 검증 (명령어: `grep -r "csrf_token"`)
- [ ] 83. SameSite 쿠키 설정 (정규식: `^SameSite=(Strict|Lax)$`)
- [ ] 84. XSS 필터 활성화 (정규식: `^X-XSS-Protection: 1`)
- [ ] 85. 입력 검증 (정규식: `^[a-zA-Z0-9_-]+$`)
- [ ] 86. 출력 인코딩 (명령어: `grep -r "htmlspecialchars"`)
- [ ] 87. Content-Security-Policy 설정 (정규식: `^default-src 'self'`)
- [ ] 88. 스크립트 인라인 비활성화 (정규식: `^'unsafe-inline'` 없음)
- [ ] 89. 외부 스크립트 화이트리스트 (정규식: `^script-src 'self'`)
- [ ] 90. XSS 테스트 (명령어: `curl -X POST -d "<script>alert('xss')</script>"`)

---

## 🔐 기타 보안 (10개)

### 공식 문서
- https://owasp.org/www-project-top-ten
- https://cwe.mitre.org/top25

### 체크리스트

- [ ] 91. SQL Injection 방어 (정규식: `^Prepared Statement$`)
- [ ] 92. 입력 길이 제한 (정규식: `^maxlength=[0-9]+$`)
- [ ] 93. 파일 업로드 검증 (정규식: `^(jpg|png|pdf)$`)
- [ ] 94. 에러 메시지 숨김 (정규식: `^500 Internal Server Error$`)
- [ ] 95. 로깅 및 모니터링 (파일: `/var/log/`)
- [ ] 96. 정기적인 보안 업데이트 (명령어: `apt update && apt upgrade`)
- [ ] 97. 보안 감사 (명령어: `lynis audit system`)
- [ ] 98. 침입 탐지 시스템 (명령어: `systemctl status aide`)
- [ ] 99. 정기적인 백업 (명령어: `crontab -l`)
- [ ] 100. 보안 정책 문서화 (파일: `/root/SECURITY.md`)

---

**총 100개 항목 완료**

