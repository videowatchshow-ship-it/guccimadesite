# 애플리케이션 설정 체크리스트 (200개)

## 🗄️ 데이터베이스 설정 (50개)

### 공식 문서
- https://mariadb.com/docs
- https://github.com/MariaDB/server

### 설치 및 초기화 (10개)

- [ ] 1. MariaDB 설치 확인 (명령어: `mysql --version`)
- [ ] 2. MariaDB 서비스 시작 (명령어: `systemctl start mariadb`)
- [ ] 3. MariaDB 자동 시작 설정 (명령어: `systemctl enable mariadb`)
- [ ] 4. MariaDB 루트 비밀번호 설정 (명령어: `mysql_secure_installation`)
- [ ] 5. 데이터베이스 생성 (명령어: `CREATE DATABASE streaming_db`)
- [ ] 6. 데이터베이스 사용자 생성 (명령어: `CREATE USER 'app'@'localhost'`)
- [ ] 7. 사용자 권한 설정 (명령어: `GRANT ALL ON streaming_db.*`)
- [ ] 8. 권한 적용 (명령어: `FLUSH PRIVILEGES`)
- [ ] 9. 데이터베이스 연결 테스트 (명령어: `mysql -u app -p streaming_db`)
- [ ] 10. 데이터베이스 정보 확인 (명령어: `SHOW DATABASES`)

### 테이블 설계 (10개)

- [ ] 11. users 테이블 생성 (정규식: `^CREATE TABLE users`)
- [ ] 12. streams 테이블 생성 (정규식: `^CREATE TABLE streams`)
- [ ] 13. chat_messages 테이블 생성 (정규식: `^CREATE TABLE chat_messages`)
- [ ] 14. watch_history 테이블 생성 (정규식: `^CREATE TABLE watch_history`)
- [ ] 15. audit_logs 테이블 생성 (정규식: `^CREATE TABLE audit_logs`)
- [ ] 16. 기본 키 설정 (정규식: `^PRIMARY KEY`)
- [ ] 17. 외래 키 설정 (정규식: `^FOREIGN KEY`)
- [ ] 18. 인덱스 생성 (정규식: `^CREATE INDEX`)
- [ ] 19. 유니크 제약 설정 (정규식: `^UNIQUE`)
- [ ] 20. 기본값 설정 (정규식: `^DEFAULT`)

### 정규화 및 최적화 (10개)

- [ ] 21. 1NF 정규화 확인 (정규식: `^ATOMIC VALUES$`)
- [ ] 22. 2NF 정규화 확인 (정규식: `^NO PARTIAL DEPENDENCIES$`)
- [ ] 23. 3NF 정규화 확인 (정규식: `^NO TRANSITIVE DEPENDENCIES$`)
- [ ] 24. 쿼리 성능 분석 (명령어: `EXPLAIN SELECT`)
- [ ] 25. 느린 쿼리 로그 활성화 (정규식: `^slow_query_log = ON`)
- [ ] 26. 쿼리 캐시 설정 (정규식: `^query_cache_size = [0-9]+`)
- [ ] 27. 연결 풀 설정 (정규식: `^max_connections = [0-9]+`)
- [ ] 28. 버퍼 풀 설정 (정규식: `^innodb_buffer_pool_size = [0-9]+`)
- [ ] 29. 로그 파일 크기 설정 (정규식: `^max_binlog_size = [0-9]+`)
- [ ] 30. 자동 증가 설정 (정규식: `^AUTO_INCREMENT`)

### 백업 및 복구 (10개)

- [ ] 31. 전체 데이터베이스 백업 (명령어: `mysqldump --all-databases`)
- [ ] 32. 특정 데이터베이스 백업 (명령어: `mysqldump streaming_db`)
- [ ] 33. 증분 백업 설정 (정규식: `^binlog_format = ROW`)
- [ ] 34. 백업 스케줄 설정 (명령어: `crontab -e`)
- [ ] 35. 백업 파일 압축 (명령어: `gzip backup.sql`)
- [ ] 36. 백업 파일 암호화 (명령어: `openssl enc -aes-256-cbc`)
- [ ] 37. 백업 파일 검증 (명령어: `mysql < backup.sql`)
- [ ] 38. 복구 절차 테스트 (명령어: `mysql < backup.sql`)
- [ ] 39. 복구 시간 측정 (명령어: `time mysql < backup.sql`)
- [ ] 40. 백업 저장소 설정 (정규식: `^/backups/database/`)

### 모니터링 및 로깅 (10개)

- [ ] 41. 에러 로그 활성화 (정규식: `^log_error = `)
- [ ] 42. 일반 로그 활성화 (정규식: `^general_log = ON`)
- [ ] 43. 슬로우 쿼리 로그 활성화 (정규식: `^slow_query_log = ON`)
- [ ] 44. 바이너리 로그 활성화 (정규식: `^log_bin = `)
- [ ] 45. 로그 파일 로테이션 설정 (명령어: `logrotate`)
- [ ] 46. 데이터베이스 상태 모니터링 (명령어: `SHOW STATUS`)
- [ ] 47. 테이블 상태 확인 (명령어: `CHECK TABLE`)
- [ ] 48. 테이블 최적화 (명령어: `OPTIMIZE TABLE`)
- [ ] 49. 테이블 복구 (명령어: `REPAIR TABLE`)
- [ ] 50. 데이터베이스 통계 업데이트 (명령어: `ANALYZE TABLE`)

---

## 🔴 Redis 설정 (50개)

### 공식 문서
- https://redis.io/docs
- https://github.com/redis/redis

### 설치 및 초기화 (10개)

- [ ] 51. Redis 설치 확인 (명령어: `redis-cli --version`)
- [ ] 52. Redis 서비스 시작 (명령어: `systemctl start redis-server`)
- [ ] 53. Redis 자동 시작 설정 (명령어: `systemctl enable redis-server`)
- [ ] 54. Redis 설정 파일 확인 (파일: `/etc/redis/redis.conf`)
- [ ] 55. Redis 포트 설정 (정규식: `^port 6379$`)
- [ ] 56. Redis 바인드 주소 설정 (정규식: `^bind 127.0.0.1$`)
- [ ] 57. Redis 인증 설정 (정규식: `^requirepass [a-zA-Z0-9]+$`)
- [ ] 58. Redis 보호 모드 활성화 (정규식: `^protected-mode yes$`)
- [ ] 59. Redis 연결 테스트 (명령어: `redis-cli PING`)
- [ ] 60. Redis 정보 확인 (명령어: `redis-cli INFO`)

### 데이터 구조 설정 (10개)

- [ ] 61. 세션 저장소 설정 (명령어: `redis-cli SET session:key value`)
- [ ] 62. 캐시 설정 (명령어: `redis-cli SET cache:key value EX 3600`)
- [ ] 63. 큐 설정 (명령어: `redis-cli LPUSH queue item`)
- [ ] 64. Pub/Sub 설정 (명령어: `redis-cli SUBSCRIBE channel`)
- [ ] 65. 정렬된 집합 설정 (명령어: `redis-cli ZADD zset 1 member`)
- [ ] 66. 해시 설정 (명령어: `redis-cli HSET hash field value`)
- [ ] 67. 비트맵 설정 (명령어: `redis-cli SETBIT bitmap 0 1`)
- [ ] 68. HyperLogLog 설정 (명령어: `redis-cli PFADD hll element`)
- [ ] 69. 스트림 설정 (명령어: `redis-cli XADD stream * field value`)
- [ ] 70. 지오스페이셜 설정 (명령어: `redis-cli GEOADD geo 13.361389 38.115556 Palermo`)

### 영속성 설정 (10개)

- [ ] 71. RDB 스냅샷 설정 (정규식: `^save [0-9]+ [0-9]+$`)
- [ ] 72. AOF 활성화 (정규식: `^appendonly yes$`)
- [ ] 73. AOF 재쓰기 설정 (정규식: `^auto-aof-rewrite-percentage [0-9]+$`)
- [ ] 74. AOF 동기화 설정 (정규식: `^appendfsync (always|everysec|no)$`)
- [ ] 75. 스냅샷 저장 경로 설정 (정규식: `^dir /var/lib/redis$`)
- [ ] 76. 스냅샷 파일명 설정 (정규식: `^dbfilename dump.rdb$`)
- [ ] 77. 스냅샷 압축 설정 (정규식: `^rdbcompression yes$`)
- [ ] 78. 스냅샷 체크섬 설정 (정규식: `^rdbchecksum yes$`)
- [ ] 79. 백업 스케줄 설정 (명령어: `crontab -e`)
- [ ] 80. 복구 테스트 (명령어: `redis-cli BGSAVE`)

### 성능 최적화 (10개)

- [ ] 81. 메모리 제한 설정 (정규식: `^maxmemory [0-9]+mb$`)
- [ ] 82. 메모리 정책 설정 (정규식: `^maxmemory-policy (allkeys-lru|volatile-lru)$`)
- [ ] 83. 샘플 크기 설정 (정규식: `^maxmemory-samples [0-9]+$`)
- [ ] 84. 느린 로그 활성화 (정규식: `^slowlog-log-slower-than [0-9]+$`)
- [ ] 85. 느린 로그 길이 설정 (정규식: `^slowlog-max-len [0-9]+$`)
- [ ] 86. 클라이언트 출력 버퍼 제한 (정규식: `^client-output-buffer-limit`)
- [ ] 87. TCP 백로그 설정 (정규식: `^tcp-backlog [0-9]+$`)
- [ ] 88. TCP 킵얼라이브 설정 (정규식: `^tcp-keepalive [0-9]+$`)
- [ ] 89. 타임아웃 설정 (정규식: `^timeout [0-9]+$`)
- [ ] 90. 데이터베이스 수 설정 (정규식: `^databases [0-9]+$`)

### 모니터링 및 로깅 (10개)

- [ ] 91. 로그 레벨 설정 (정규식: `^loglevel (debug|verbose|notice|warning)$`)
- [ ] 92. 로그 파일 설정 (정규식: `^logfile /var/log/redis`)
- [ ] 93. 시스로그 활성화 (정규식: `^syslog-enabled yes$`)
- [ ] 94. 시스로그 식별자 설정 (정규식: `^syslog-ident redis$`)
- [ ] 95. 시스로그 시설 설정 (정규식: `^syslog-facility local0$`)
- [ ] 96. 명령어 이름 변경 (정규식: `^rename-command FLUSHDB ""`)
- [ ] 97. 위험한 명령어 비활성화 (정규식: `^rename-command FLUSHALL ""`)
- [ ] 98. 모니터링 도구 설정 (명령어: `redis-cli MONITOR`)
- [ ] 99. 성능 분석 (명령어: `redis-cli --latency`)
- [ ] 100. 메모리 분석 (명령어: `redis-cli --memkeys`)

---

## 🐳 Docker 설정 (50개)

### 공식 문서
- https://docs.docker.com
- https://github.com/docker/docker-ce

### 이미지 빌드 (10개)

- [ ] 101. Dockerfile 작성 (파일: `Dockerfile`)
- [ ] 102. 베이스 이미지 선택 (정규식: `^FROM ubuntu:24.04$`)
- [ ] 103. 레이어 최적화 (정규식: `^RUN apt-get update && apt-get install`)
- [ ] 104. 멀티 스테이지 빌드 (정규식: `^FROM.*AS builder$`)
- [ ] 105. 이미지 태그 설정 (정규식: `^[a-z0-9-]+:[0-9]+\.[0-9]+\.[0-9]+$`)
- [ ] 106. 이미지 빌드 (명령어: `docker build -t image:tag .`)
- [ ] 107. 이미지 검사 (명령어: `docker inspect image:tag`)
- [ ] 108. 이미지 스캔 (명령어: `docker scan image:tag`)
- [ ] 109. 이미지 크기 확인 (명령어: `docker images`)
- [ ] 110. 이미지 레이어 확인 (명령어: `docker history image:tag`)

### 컨테이너 실행 (10개)

- [ ] 111. 컨테이너 생성 (명령어: `docker run -d image:tag`)
- [ ] 112. 포트 매핑 설정 (정규식: `^-p [0-9]+:[0-9]+$`)
- [ ] 113. 볼륨 마운트 설정 (정규식: `^-v /host:/container$`)
- [ ] 114. 환경 변수 설정 (정규식: `^-e KEY=VALUE$`)
- [ ] 115. 네트워크 설정 (정규식: `^--network [a-z0-9-]+$`)
- [ ] 116. 리소스 제한 설정 (정규식: `^-m [0-9]+m$`)
- [ ] 117. CPU 제한 설정 (정규식: `^--cpus=[0-9]+$`)
- [ ] 118. 재시작 정책 설정 (정규식: `^--restart (always|on-failure)$`)
- [ ] 119. 헬스 체크 설정 (정규식: `^--health-cmd`)
- [ ] 120. 컨테이너 상태 확인 (명령어: `docker ps`)

### Docker Compose 설정 (10개)

- [ ] 121. docker-compose.yml 작성 (파일: `docker-compose.yml`)
- [ ] 122. 서비스 정의 (정규식: `^services:$`)
- [ ] 123. 이미지 지정 (정규식: `^  image: [a-z0-9-]+:[0-9]+\.[0-9]+\.[0-9]+$`)
- [ ] 124. 포트 설정 (정규식: `^  ports:$`)
- [ ] 125. 볼륨 설정 (정규식: `^  volumes:$`)
- [ ] 126. 환경 변수 설정 (정규식: `^  environment:$`)
- [ ] 127. 네트워크 설정 (정규식: `^networks:$`)
- [ ] 128. 의존성 설정 (정규식: `^  depends_on:$`)
- [ ] 129. 헬스 체크 설정 (정규식: `^  healthcheck:$`)
- [ ] 130. 컴포즈 검증 (명령어: `docker-compose config`)

### 네트워크 및 스토리지 (10개)

- [ ] 131. 브리지 네트워크 생성 (명령어: `docker network create`)
- [ ] 132. 네트워크 연결 (명령어: `docker network connect`)
- [ ] 133. 볼륨 생성 (명령어: `docker volume create`)
- [ ] 134. 볼륨 마운트 (명령어: `docker run -v`)
- [ ] 135. 바인드 마운트 (명령어: `docker run -v /host:/container`)
- [ ] 136. tmpfs 마운트 (명령어: `docker run --tmpfs`)
- [ ] 137. 볼륨 백업 (명령어: `docker run --volumes-from`)
- [ ] 138. 볼륨 복구 (명령어: `docker cp`)
- [ ] 139. 스토리지 드라이버 설정 (정규식: `^storage-driver: overlay2$`)
- [ ] 140. 로깅 드라이버 설정 (정규식: `^log-driver: json-file$`)

### 모니터링 및 로깅 (10개)

- [ ] 141. 컨테이너 로그 확인 (명령어: `docker logs`)
- [ ] 142. 로그 드라이버 설정 (정규식: `^--log-driver json-file$`)
- [ ] 143. 로그 옵션 설정 (정규식: `^--log-opt max-size=10m$`)
- [ ] 144. 컨테이너 통계 확인 (명령어: `docker stats`)
- [ ] 145. 컨테이너 프로세스 확인 (명령어: `docker top`)
- [ ] 146. 컨테이너 이벤트 확인 (명령어: `docker events`)
- [ ] 147. 컨테이너 검사 (명령어: `docker inspect`)
- [ ] 148. 컨테이너 diff 확인 (명령어: `docker diff`)
- [ ] 149. 컨테이너 포트 확인 (명령어: `docker port`)
- [ ] 150. 컨테이너 네트워크 확인 (명령어: `docker network inspect`)

---

## 🎨 Frontend 설정 (50개)

### 공식 문서
- https://nextjs.org/docs
- https://react.dev
- https://tailwindcss.com/docs

### 프로젝트 초기화 (10개)

- [ ] 151. Next.js 프로젝트 생성 (명령어: `npx create-next-app@latest`)
- [ ] 152. package.json 확인 (파일: `package.json`)
- [ ] 153. Node.js 버전 확인 (정규식: `^"node": "22.x"$`)
- [ ] 154. npm 버전 확인 (정규식: `^"npm": "10.x"$`)
- [ ] 155. 의존성 설치 (명령어: `npm install`)
- [ ] 156. package-lock.json 생성 (파일: `package-lock.json`)
- [ ] 157. .gitignore 설정 (파일: `.gitignore`)
- [ ] 158. 환경 변수 설정 (파일: `.env.local`)
- [ ] 159. 빌드 설정 (파일: `next.config.js`)
- [ ] 160. TypeScript 설정 (파일: `tsconfig.json`)

### 개발 환경 설정 (10개)

- [ ] 161. 개발 서버 시작 (명령어: `npm run dev`)
- [ ] 162. 포트 설정 (정규식: `^PORT=3000$`)
- [ ] 163. 핫 리로드 확인 (명령어: `curl http://localhost:3000`)
- [ ] 164. 소스맵 생성 (정규식: `^sourceMap: true$`)
- [ ] 165. ESLint 설정 (파일: `.eslintrc.json`)
- [ ] 166. Prettier 설정 (파일: `.prettierrc`)
- [ ] 167. 코드 포맷팅 (명령어: `npm run format`)
- [ ] 168. 린팅 (명령어: `npm run lint`)
- [ ] 169. 타입 체크 (명령어: `npm run type-check`)
- [ ] 170. 테스트 설정 (파일: `jest.config.js`)

### 빌드 및 최적화 (10개)

- [ ] 171. 프로덕션 빌드 (명령어: `npm run build`)
- [ ] 172. 빌드 결과 확인 (파일: `.next/`)
- [ ] 173. 번들 분석 (명령어: `npm run analyze`)
- [ ] 174. 이미지 최적화 (정규식: `^next/image$`)
- [ ] 175. 폰트 최적화 (정규식: `^next/font$`)
- [ ] 176. 스크립트 최적화 (정규식: `^next/script$`)
- [ ] 177. 동적 임포트 (정규식: `^dynamic import$`)
- [ ] 178. 코드 분할 (정규식: `^Code Splitting$`)
- [ ] 179. 트리 쉐이킹 (정규식: `^Tree Shaking$`)
- [ ] 180. 압축 설정 (정규식: `^compress: true$`)

### SEO 및 성능 (10개)

- [ ] 181. Meta 태그 설정 (정규식: `^<meta name="description"`)
- [ ] 182. Open Graph 설정 (정규식: `^<meta property="og:`)
- [ ] 183. Twitter Card 설정 (정규식: `^<meta name="twitter:`)
- [ ] 184. Canonical 태그 설정 (정규식: `^<link rel="canonical"`)
- [ ] 185. robots.txt 설정 (파일: `public/robots.txt`)
- [ ] 186. sitemap.xml 설정 (파일: `public/sitemap.xml`)
- [ ] 187. Core Web Vitals 측정 (명령어: `npm run lighthouse`)
- [ ] 188. 성능 모니터링 (정규식: `^web-vitals$`)
- [ ] 189. 접근성 검사 (명령어: `npm run a11y`)
- [ ] 190. PWA 설정 (파일: `public/manifest.json`)

### 배포 설정 (10개)

- [ ] 191. Dockerfile 작성 (파일: `Dockerfile`)
- [ ] 192. .dockerignore 설정 (파일: `.dockerignore`)
- [ ] 193. 이미지 빌드 (명령어: `docker build -t frontend:latest .`)
- [ ] 194. 컨테이너 실행 (명령어: `docker run -p 3000:3000 frontend:latest`)
- [ ] 195. 환경 변수 설정 (정규식: `^NEXT_PUBLIC_`)
- [ ] 196. 헬스 체크 설정 (명령어: `curl http://localhost:3000/health`)
- [ ] 197. 로깅 설정 (정규식: `^console.log$`)
- [ ] 198. 에러 처리 (파일: `pages/_error.tsx`)
- [ ] 199. 404 페이지 (파일: `pages/404.tsx`)
- [ ] 200. 500 페이지 (파일: `pages/500.tsx`)

---

**총 200개 항목 완료**

