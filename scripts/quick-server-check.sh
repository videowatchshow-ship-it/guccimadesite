#!/bin/bash

################################################################################
# 빠른 서버 상태 확인 스크립트
# 공식 문서: https://ubuntu.com/server/docs
# 버전: v1.0.0 (2026-05-14)
################################################################################

set -e

# 색상 정의
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
NC='\033[0m'

# 로그 함수
log_info() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

log_success() {
    echo -e "${GREEN}[✓]${NC} $1"
}

log_warning() {
    echo -e "${YELLOW}[!]${NC} $1"
}

log_error() {
    echo -e "${RED}[✗]${NC} $1"
}

log_section() {
    echo ""
    echo -e "${CYAN}╔════════════════════════════════════════════════════════════════════════════╗${NC}"
    echo -e "${CYAN}║ $1${NC}"
    echo -e "${CYAN}╚════════════════════════════════════════════════════════════════════════════╝${NC}"
    echo ""
}

################################################################################
# 1. 시스템 정보
################################################################################

log_section "1. 시스템 정보"

OS_INFO=$(cat /etc/os-release | grep "PRETTY_NAME" | cut -d'"' -f2)
log_success "OS: $OS_INFO"

KERNEL=$(uname -r)
log_success "Kernel: $KERNEL"

HOSTNAME=$(hostname)
log_success "Hostname: $HOSTNAME"

IP_ADDR=$(hostname -I | awk '{print $1}')
log_success "IP Address: $IP_ADDR"

################################################################################
# 2. 리소스 상태
################################################################################

log_section "2. 리소스 상태"

# CPU
CPU_CORES=$(nproc)
log_success "CPU Cores: $CPU_CORES"

# 메모리
MEM_TOTAL=$(free -h | awk 'NR==2 {print $2}')
MEM_USED=$(free -h | awk 'NR==2 {print $3}')
MEM_PERCENT=$(free | awk 'NR==2 {printf "%.1f", ($3/$2)*100}')
log_success "Memory: $MEM_USED / $MEM_TOTAL ($MEM_PERCENT%)"

# 디스크
DISK_TOTAL=$(df -h / | awk 'NR==2 {print $2}')
DISK_USED=$(df -h / | awk 'NR==2 {print $3}')
DISK_PERCENT=$(df / | awk 'NR==2 {print $5}')
log_success "Disk: $DISK_USED / $DISK_TOTAL ($DISK_PERCENT)"

################################################################################
# 3. 설치된 소프트웨어
################################################################################

log_section "3. 설치된 소프트웨어"

# Docker
if command -v docker &> /dev/null; then
    DOCKER_VERSION=$(docker --version | awk '{print $3}' | sed 's/,//')
    log_success "Docker: $DOCKER_VERSION"
else
    log_warning "Docker: 미설치"
fi

# Docker Compose
if command -v docker-compose &> /dev/null; then
    COMPOSE_VERSION=$(docker-compose --version | awk '{print $3}' | sed 's/,//')
    log_success "Docker Compose: $COMPOSE_VERSION"
else
    log_warning "Docker Compose: 미설치"
fi

# Node.js
if command -v node &> /dev/null; then
    NODE_VERSION=$(node --version)
    log_success "Node.js: $NODE_VERSION"
else
    log_warning "Node.js: 미설치"
fi

# npm
if command -v npm &> /dev/null; then
    NPM_VERSION=$(npm --version)
    log_success "npm: $NPM_VERSION"
else
    log_warning "npm: 미설치"
fi

# nginx
if command -v nginx &> /dev/null; then
    NGINX_VERSION=$(nginx -v 2>&1 | awk '{print $3}')
    log_success "nginx: $NGINX_VERSION"
else
    log_warning "nginx: 미설치"
fi

# MariaDB
if command -v mysql &> /dev/null; then
    MYSQL_VERSION=$(mysql --version | awk '{print $5}' | sed 's/,//')
    log_success "MariaDB: $MYSQL_VERSION"
else
    log_warning "MariaDB: 미설치"
fi

# Redis
if command -v redis-cli &> /dev/null; then
    REDIS_VERSION=$(redis-cli --version | awk '{print $2}')
    log_success "Redis: $REDIS_VERSION"
else
    log_warning "Redis: 미설치"
fi

################################################################################
# 4. 서비스 상태
################################################################################

log_section "4. 서비스 상태"

# Docker
if systemctl is-active --quiet docker; then
    log_success "Docker: 실행 중"
else
    log_warning "Docker: 중지됨"
fi

# nginx
if systemctl is-active --quiet nginx; then
    log_success "nginx: 실행 중"
else
    log_warning "nginx: 중지됨"
fi

# MariaDB
if systemctl is-active --quiet mariadb; then
    log_success "MariaDB: 실행 중"
else
    log_warning "MariaDB: 중지됨"
fi

# Redis
if systemctl is-active --quiet redis-server; then
    log_success "Redis: 실행 중"
else
    log_warning "Redis: 중지됨"
fi

################################################################################
# 5. 포트 상태
################################################################################

log_section "5. 포트 상태"

# SSH (22)
if netstat -tlnp 2>/dev/null | grep -q ":22 "; then
    log_success "SSH (22): 열림"
else
    log_warning "SSH (22): 닫힘"
fi

# HTTP (80)
if netstat -tlnp 2>/dev/null | grep -q ":80 "; then
    log_success "HTTP (80): 열림"
else
    log_warning "HTTP (80): 닫힘"
fi

# HTTPS (443)
if netstat -tlnp 2>/dev/null | grep -q ":443 "; then
    log_success "HTTPS (443): 열림"
else
    log_warning "HTTPS (443): 닫힘"
fi

# Backend (3000)
if netstat -tlnp 2>/dev/null | grep -q ":3000 "; then
    log_success "Backend (3000): 열림"
else
    log_warning "Backend (3000): 닫힘"
fi

################################################################################
# 6. 보안 상태
################################################################################

log_section "6. 보안 상태"

# UFW
if ufw status | grep -q "Status: active"; then
    log_success "UFW: 활성화"
else
    log_warning "UFW: 비활성화"
fi

# fail2ban
if systemctl is-active --quiet fail2ban; then
    log_success "fail2ban: 실행 중"
else
    log_warning "fail2ban: 중지됨"
fi

# SSL 인증서
if [ -f /etc/letsencrypt/live/srv1636789.hstgr.cloud/cert.pem ]; then
    CERT_EXPIRY=$(openssl x509 -in /etc/letsencrypt/live/srv1636789.hstgr.cloud/cert.pem -noout -dates | grep notAfter | cut -d= -f2)
    log_success "SSL 인증서: 설치됨 (만료: $CERT_EXPIRY)"
else
    log_warning "SSL 인증서: 미설치"
fi

################################################################################
# 7. 데이터베이스 연결
################################################################################

log_section "7. 데이터베이스 연결"

# MariaDB
if mysql -u root -e "SELECT 1" &> /dev/null; then
    log_success "MariaDB: 연결 성공"
else
    log_warning "MariaDB: 연결 실패"
fi

# Redis
if redis-cli ping &> /dev/null; then
    log_success "Redis: 연결 성공"
else
    log_warning "Redis: 연결 실패"
fi

################################################################################
# 8. 최종 요약
################################################################################

log_section "8. 최종 요약"

log_success "서버 상태 확인 완료!"
log_info "시간: $(date '+%Y-%m-%d %H:%M:%S')"
log_info "호스트: $HOSTNAME ($IP_ADDR)"
log_info "OS: $OS_INFO"

echo ""
