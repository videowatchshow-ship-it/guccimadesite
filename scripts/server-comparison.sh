#!/bin/bash

################################################################################
# 로컬 파일 vs 실제 서버 비교 스크립트
# 공식 문서: https://github.com/hostinger/api-cli
# 버전: v1.0.0 (2026-05-14)
# 정규식 검증: ^[0-9]+\.[0-9]+\.[0-9]+$
################################################################################

set -e

# 색상 정의
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
NC='\033[0m'

# 정규식 정의
VERSION_REGEX="^[0-9]+\.[0-9]+\.[0-9]+$"
PORT_REGEX="^[0-9]{4,5}$"

# 로그 함수
log_info() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

log_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

log_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

log_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

log_section() {
    echo ""
    echo -e "${CYAN}╔════════════════════════════════════════════════════════════════════════════╗${NC}"
    echo -e "${CYAN}║ $1${NC}"
    echo -e "${CYAN}╚════════════════════════════════════════════════════════════════════════════╝${NC}"
    echo ""
}

# 출력 파일
OUTPUT_FILE="server-comparison-$(date +%Y%m%d-%H%M%S).txt"

# 출력 함수
output() {
    echo "$1" | tee -a "$OUTPUT_FILE"
}

################################################################################
# 1. 시스템 정보 비교
################################################################################

compare_system_info() {
    log_section "1. 시스템 정보 비교"
    
    output "=== 시스템 정보 ==="
    output "호스트명: $(hostname)"
    output "OS: $(cat /etc/os-release | grep PRETTY_NAME | cut -d'"' -f2)"
    output "커널: $(uname -r)"
    output "업타임: $(uptime -p)"
    output ""
}

################################################################################
# 2. 설치된 소프트웨어 비교
################################################################################

compare_software() {
    log_section "2. 설치된 소프트웨어 비교"
    
    output "=== 소프트웨어 버전 ==="
    
    # Docker
    if command -v docker &> /dev/null; then
        DOCKER_VERSION=$(docker --version | grep -oP '[0-9]+\.[0-9]+\.[0-9]+' | head -1)
        if [[ $DOCKER_VERSION =~ $VERSION_REGEX ]]; then
            output "✅ Docker: $DOCKER_VERSION"
        else
            output "⚠️  Docker: $DOCKER_VERSION (버전 형식 확인 필요)"
        fi
    else
        output "❌ Docker: NOT INSTALLED"
    fi
    
    # Node.js
    if command -v node &> /dev/null; then
        NODE_VERSION=$(node --version | sed 's/v//')
        if [[ $NODE_VERSION =~ $VERSION_REGEX ]]; then
            output "✅ Node.js: $NODE_VERSION"
        else
            output "⚠️  Node.js: $NODE_VERSION (버전 형식 확인 필요)"
        fi
    else
        output "❌ Node.js: NOT INSTALLED"
    fi
    
    # npm
    if command -v npm &> /dev/null; then
        NPM_VERSION=$(npm --version)
        if [[ $NPM_VERSION =~ $VERSION_REGEX ]]; then
            output "✅ npm: $NPM_VERSION"
        else
            output "⚠️  npm: $NPM_VERSION (버전 형식 확인 필요)"
        fi
    else
        output "❌ npm: NOT INSTALLED"
    fi
    
    # nginx
    if command -v nginx &> /dev/null; then
        NGINX_VERSION=$(nginx -v 2>&1 | grep -oP '[0-9]+\.[0-9]+\.[0-9]+' | head -1)
        if [[ $NGINX_VERSION =~ $VERSION_REGEX ]]; then
            output "✅ nginx: $NGINX_VERSION"
        else
            output "⚠️  nginx: $NGINX_VERSION (버전 형식 확인 필요)"
        fi
    else
        output "❌ nginx: NOT INSTALLED"
    fi
    
    # MariaDB
    if command -v mysql &> /dev/null; then
        MARIADB_VERSION=$(mysql --version | grep -oP '[0-9]+\.[0-9]+\.[0-9]+' | head -1)
        if [[ $MARIADB_VERSION =~ $VERSION_REGEX ]]; then
            output "✅ MariaDB: $MARIADB_VERSION"
        else
            output "⚠️  MariaDB: $MARIADB_VERSION (버전 형식 확인 필요)"
        fi
    else
        output "❌ MariaDB: NOT INSTALLED"
    fi
    
    # Redis
    if command -v redis-cli &> /dev/null; then
        REDIS_VERSION=$(redis-cli --version | grep -oP '[0-9]+\.[0-9]+\.[0-9]+' | head -1)
        if [[ $REDIS_VERSION =~ $VERSION_REGEX ]]; then
            output "✅ Redis: $REDIS_VERSION"
        else
            output "⚠️  Redis: $REDIS_VERSION (버전 형식 확인 필요)"
        fi
    else
        output "❌ Redis: NOT INSTALLED"
    fi
    
    output ""
}

################################################################################
# 3. 서비스 상태 비교
################################################################################

compare_services() {
    log_section "3. 서비스 상태 비교"
    
    output "=== 서비스 상태 ==="
    
    # Docker
    if systemctl is-active --quiet docker; then
        output "✅ Docker: 실행 중"
    else
        output "❌ Docker: 중지됨"
    fi
    
    # nginx
    if systemctl is-active --quiet nginx; then
        output "✅ nginx: 실행 중"
    else
        output "❌ nginx: 중지됨"
    fi
    
    # MariaDB
    if systemctl is-active --quiet mariadb; then
        output "✅ MariaDB: 실행 중"
    else
        output "❌ MariaDB: 중지됨"
    fi
    
    # Redis
    if systemctl is-active --quiet redis-server; then
        output "✅ Redis: 실행 중"
    else
        output "❌ Redis: 중지됨"
    fi
    
    output ""
}

################################################################################
# 4. 포트 상태 비교
################################################################################

compare_ports() {
    log_section "4. 포트 상태 비교"
    
    output "=== 열린 포트 ==="
    
    # SSH (22)
    if netstat -tlnp 2>/dev/null | grep -q ":22 "; then
        output "✅ SSH (22): 열림"
    else
        output "❌ SSH (22): 닫힘"
    fi
    
    # HTTP (80)
    if netstat -tlnp 2>/dev/null | grep -q ":80 "; then
        output "✅ HTTP (80): 열림"
    else
        output "❌ HTTP (80): 닫힘"
    fi
    
    # HTTPS (443)
    if netstat -tlnp 2>/dev/null | grep -q ":443 "; then
        output "✅ HTTPS (443): 열림"
    else
        output "❌ HTTPS (443): 닫힘"
    fi
    
    # Backend (3000)
    if netstat -tlnp 2>/dev/null | grep -q ":3000 "; then
        output "✅ Backend (3000): 열림"
    else
        output "⚠️  Backend (3000): 닫힘"
    fi
    
    output ""
}

################################################################################
# 5. 디렉토리 구조 비교
################################################################################

compare_directories() {
    log_section "5. 디렉토리 구조 비교"
    
    output "=== 주요 디렉토리 ==="
    
    # /var/www
    if [ -d /var/www ]; then
        output "✅ /var/www: 존재"
        output "   내용: $(ls -la /var/www 2>/dev/null | tail -n +4 | wc -l)개 항목"
    else
        output "❌ /var/www: 없음"
    fi
    
    # /var/www/backend
    if [ -d /var/www/backend ]; then
        output "✅ /var/www/backend: 존재"
    else
        output "⚠️  /var/www/backend: 없음"
    fi
    
    # /var/www/frontend
    if [ -d /var/www/frontend ]; then
        output "✅ /var/www/frontend: 존재"
    else
        output "⚠️  /var/www/frontend: 없음"
    fi
    
    # /backups
    if [ -d /backups ]; then
        output "✅ /backups: 존재"
    else
        output "⚠️  /backups: 없음"
    fi
    
    output ""
}

################################################################################
# 6. 리소스 상태 비교
################################################################################

compare_resources() {
    log_section "6. 리소스 상태 비교"
    
    output "=== 리소스 사용량 ==="
    
    # 디스크
    DISK_USAGE=$(df -h / | awk 'NR==2 {print $5}')
    output "디스크 사용률: $DISK_USAGE"
    
    # 메모리
    MEM_USAGE=$(free -h | awk 'NR==2 {print $3 "/" $2}')
    output "메모리 사용량: $MEM_USAGE"
    
    # CPU
    CPU_CORES=$(nproc)
    output "CPU 코어: $CPU_CORES"
    
    output ""
}

################################################################################
# 7. 보안 상태 비교
################################################################################

compare_security() {
    log_section "7. 보안 상태 비교"
    
    output "=== 보안 설정 ==="
    
    # UFW
    if sudo ufw status | grep -q "Status: active"; then
        output "✅ UFW: 활성화"
    else
        output "❌ UFW: 비활성화"
    fi
    
    # fail2ban
    if systemctl is-active --quiet fail2ban; then
        output "✅ fail2ban: 실행 중"
    else
        output "❌ fail2ban: 중지됨"
    fi
    
    # SSL 인증서
    if [ -f /etc/letsencrypt/live/srv1636789.hstgr.cloud/cert.pem ]; then
        CERT_EXPIRY=$(sudo openssl x509 -in /etc/letsencrypt/live/srv1636789.hstgr.cloud/cert.pem -noout -dates 2>/dev/null | grep notAfter | cut -d'=' -f2)
        output "✅ SSL 인증서: 설치됨 (만료: $CERT_EXPIRY)"
    else
        output "❌ SSL 인증서: 없음"
    fi
    
    output ""
}

################################################################################
# 8. 데이터베이스 상태 비교
################################################################################

compare_database() {
    log_section "8. 데이터베이스 상태 비교"
    
    output "=== 데이터베이스 ==="
    
    if command -v mysql &> /dev/null; then
        # 데이터베이스 목록
        DB_COUNT=$(mysql -u root -e "SHOW DATABASES;" 2>/dev/null | wc -l)
        output "✅ MariaDB 데이터베이스: $((DB_COUNT - 1))개"
        
        # 테이블 목록
        TABLE_COUNT=$(mysql -u root -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema NOT IN ('information_schema', 'mysql', 'performance_schema');" 2>/dev/null | tail -1)
        output "✅ 테이블: $TABLE_COUNT개"
    else
        output "❌ MariaDB: 설치되지 않음"
    fi
    
    output ""
}

################################################################################
# 9. 애플리케이션 상태 비교
################################################################################

compare_applications() {
    log_section "9. 애플리케이션 상태 비교"
    
    output "=== 애플리케이션 ==="
    
    # PM2 프로세스
    if command -v pm2 &> /dev/null; then
        PM2_COUNT=$(pm2 list 2>/dev/null | grep -c "online\|stopped" || echo "0")
        output "✅ PM2 프로세스: $PM2_COUNT개"
    else
        output "⚠️  PM2: 설치되지 않음"
    fi
    
    # Docker 컨테이너
    if command -v docker &> /dev/null; then
        CONTAINER_COUNT=$(docker ps -a 2>/dev/null | wc -l)
        output "✅ Docker 컨테이너: $((CONTAINER_COUNT - 1))개"
    else
        output "❌ Docker: 설치되지 않음"
    fi
    
    output ""
}

################################################################################
# 10. 최종 검증 요약
################################################################################

final_validation() {
    log_section "10. 최종 검증 요약"
    
    output "=== 검증 결과 ==="
    
    # 필수 소프트웨어 확인
    REQUIRED_COUNT=0
    INSTALLED_COUNT=0
    
    for cmd in docker node npm nginx mysql redis-cli; do
        REQUIRED_COUNT=$((REQUIRED_COUNT + 1))
        if command -v $cmd &> /dev/null; then
            INSTALLED_COUNT=$((INSTALLED_COUNT + 1))
        fi
    done
    
    output "필수 소프트웨어: $INSTALLED_COUNT/$REQUIRED_COUNT 설치됨"
    
    # 필수 서비스 확인
    SERVICE_COUNT=0
    RUNNING_COUNT=0
    
    for service in docker nginx mariadb redis-server; do
        SERVICE_COUNT=$((SERVICE_COUNT + 1))
        if systemctl is-active --quiet $service; then
            RUNNING_COUNT=$((RUNNING_COUNT + 1))
        fi
    done
    
    output "필수 서비스: $RUNNING_COUNT/$SERVICE_COUNT 실행 중"
    
    # 필수 포트 확인
    PORT_COUNT=0
    OPEN_COUNT=0
    
    for port in 22 80 443; do
        PORT_COUNT=$((PORT_COUNT + 1))
        if netstat -tlnp 2>/dev/null | grep -q ":$port "; then
            OPEN_COUNT=$((OPEN_COUNT + 1))
        fi
    done
    
    output "필수 포트: $OPEN_COUNT/$PORT_COUNT 열림"
    
    # 최종 상태
    if [ $INSTALLED_COUNT -eq $REQUIRED_COUNT ] && [ $RUNNING_COUNT -eq $SERVICE_COUNT ] && [ $OPEN_COUNT -eq $PORT_COUNT ]; then
        output ""
        output "✅ 모든 검증 통과! 배포 준비 완료"
    else
        output ""
        output "⚠️  일부 항목 미완성. 배포 전 확인 필요"
    fi
    
    output ""
}

################################################################################
# 메인 함수
################################################################################

main() {
    log_section "로컬 파일 vs 실제 서버 비교"
    
    output "╔════════════════════════════════════════════════════════════════════════════╗"
    output "║                    로컬 파일 vs 실제 서버 비교                             ║"
    output "║                    작성일: $(date '+%Y-%m-%d %H:%M:%S')                      ║"
    output "╚════════════════════════════════════════════════════════════════════════════╝"
    output ""
    
    # 각 섹션 실행
    compare_system_info
    compare_software
    compare_services
    compare_ports
    compare_directories
    compare_resources
    compare_security
    compare_database
    compare_applications
    final_validation
    
    output "╔════════════════════════════════════════════════════════════════════════════╗"
    output "║                    비교 완료                                              ║"
    output "║                    저장 파일: $OUTPUT_FILE                    ║"
    output "╚════════════════════════════════════════════════════════════════════════════╝"
    output ""
}

# 스크립트 실행
main "$@"

exit 0
