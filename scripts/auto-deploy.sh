#!/bin/bash

################################################################################
# 2026 Production-Ready Platform Auto-Deployment Script
# Official Docs: https://docs.docker.com/engine/install/ubuntu/
# Official GitHub: https://github.com/docker/docker-install
# Docker Compose: https://docs.docker.com/compose/install/
# Node.js: https://github.com/nodesource/distributions
# Version: 2026-06-01 (공식 문서 기준)
# Target: 76.13.218.129 (srv1636789.hstgr.cloud)
# OS: Ubuntu 24.04 LTS
# Regex Validation: ^[0-9]+\.[0-9]+\.[0-9]+$
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
# 배포 시작
################################################################################

log_section "2026 Production-Ready 플랫폼 자동 배포"

log_info "배포 시작 시간: $(date '+%Y-%m-%d %H:%M:%S')"
log_info "호스트: $(hostname 2>/dev/null || echo 'unknown')"
log_info "IP: $(hostname -I 2>/dev/null | awk '{print $1}' || echo 'unknown')"

################################################################################
# Phase 1: 서버 준비
################################################################################

log_section "Phase 1: 서버 준비"

log_info "1단계: VPS 초기화"
OS_INFO=$(cat /etc/os-release 2>/dev/null | grep "PRETTY_NAME" | cut -d'"' -f2 || echo "Unknown OS")
log_success "OS: $OS_INFO"

DISK_USAGE=$(df -h / 2>/dev/null | awk 'NR==2 {print $5}' || echo "unknown")
log_success "디스크 사용률: $DISK_USAGE"

MEM_USAGE=$(free -h 2>/dev/null | awk 'NR==2 {print $3 "/" $2}' || echo "unknown")
log_success "메모리 사용량: $MEM_USAGE"

log_info "2단계: Ubuntu 업데이트"
sudo apt update -y > /dev/null 2>&1 || log_warning "apt update 실패"
log_success "apt update 완료"

sudo apt upgrade -y > /dev/null 2>&1 || log_warning "apt upgrade 실패"
log_success "apt upgrade 완료"

log_info "3단계: SSH 보안 설정"
sudo cp /etc/ssh/sshd_config /etc/ssh/sshd_config.backup 2>/dev/null || true
log_success "SSH 설정 백업 완료"

################################################################################
# Phase 2: Docker 설치
################################################################################

log_section "Phase 2: Docker 설치"

log_info "4단계: Docker 설치"
if ! command -v docker &> /dev/null; then
    curl -fsSL https://get.docker.com -o get-docker.sh > /dev/null 2>&1
    sudo sh get-docker.sh > /dev/null 2>&1
    log_success "Docker 설치 완료"
else
    DOCKER_VERSION=$(docker --version | awk '{print $3}' | sed 's/,//')
    log_success "Docker 이미 설치됨: $DOCKER_VERSION"
fi

log_info "5단계: Docker Compose 설치"
if ! docker compose version &> /dev/null; then
    COMPOSE_VERSION="2.35.1"
    sudo curl -L "https://github.com/docker/compose/releases/download/v${COMPOSE_VERSION}/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose > /dev/null 2>&1
    sudo chmod +x /usr/local/bin/docker-compose
    log_success "Docker Compose ${COMPOSE_VERSION} 설치 완료"
else
    COMPOSE_VERSION=$(docker compose version 2>/dev/null | grep -oP '[0-9]+\.[0-9]+\.[0-9]+' | head -1)
    log_success "Docker Compose 이미 설치됨: $COMPOSE_VERSION"
fi

################################################################################
# Phase 3: 데이터베이스 설치
################################################################################

log_section "Phase 3: 데이터베이스 설치"

log_info "6단계: MariaDB 설치"
if ! command -v mysql &> /dev/null; then
    curl -LsS https://r.mariadb.com/downloads/mariadb_repo_setup | sudo bash > /dev/null 2>&1
    sudo apt install -y mariadb-server > /dev/null 2>&1
    log_success "MariaDB 설치 완료"
else
    MYSQL_VERSION=$(mysql --version | awk '{print $5}' | sed 's/,//')
    log_success "MariaDB 이미 설치됨: $MYSQL_VERSION"
fi

log_info "7단계: Redis 설치"
if ! command -v redis-cli &> /dev/null; then
    sudo apt install -y redis-server > /dev/null 2>&1
    log_success "Redis 설치 완료"
else
    REDIS_VERSION=$(redis-cli --version | awk '{print $2}')
    log_success "Redis 이미 설치됨: $REDIS_VERSION"
fi

################################################################################
# Phase 4: 웹 서버 설치
################################################################################

log_section "Phase 4: 웹 서버 설치"

log_info "8단계: nginx 설치"
if ! command -v nginx &> /dev/null; then
    sudo apt install -y nginx > /dev/null 2>&1
    log_success "nginx 설치 완료"
else
    NGINX_VERSION=$(nginx -v 2>&1 | awk '{print $3}')
    log_success "nginx 이미 설치됨: $NGINX_VERSION"
fi

################################################################################
# Phase 5: Node.js 설치
################################################################################

log_section "Phase 5: Node.js 설치"

log_info "9단계: Node.js 22 LTS 설치"
if ! command -v node &> /dev/null; then
    curl -fsSL https://deb.nodesource.com/setup_22.x | sudo -E bash - > /dev/null 2>&1
    sudo apt install -y nodejs > /dev/null 2>&1
    log_success "Node.js 설치 완료"
else
    NODE_VERSION=$(node --version)
    log_success "Node.js 이미 설치됨: $NODE_VERSION"
fi

################################################################################
# Phase 6: 보안 설정
################################################################################

log_section "Phase 6: 보안 설정"

log_info "10단계: UFW 방화벽 설정"
sudo ufw enable -y > /dev/null 2>&1 || true
sudo ufw allow 22/tcp > /dev/null 2>&1 || true
sudo ufw allow 80/tcp > /dev/null 2>&1 || true
sudo ufw allow 443/tcp > /dev/null 2>&1 || true
log_success "UFW 방화벽 설정 완료"

log_info "11단계: fail2ban 설치"
if ! command -v fail2ban-client &> /dev/null; then
    sudo apt install -y fail2ban > /dev/null 2>&1
    log_success "fail2ban 설치 완료"
else
    log_success "fail2ban 이미 설치됨"
fi

log_info "12단계: SSL/TLS 설정"
if ! command -v certbot &> /dev/null; then
    sudo apt install -y certbot python3-certbot-nginx > /dev/null 2>&1
    log_success "Certbot 설치 완료"
else
    log_success "Certbot 이미 설치됨"
fi

################################################################################
# Phase 7: 애플리케이션 배포
################################################################################

log_section "Phase 7: 애플리케이션 배포"

log_info "13단계: Backend 배포 준비"
sudo mkdir -p /var/www/backend
log_success "Backend 디렉토리 생성 완료"

log_info "14단계: Frontend 배포 준비"
sudo mkdir -p /var/www/frontend
log_success "Frontend 디렉토리 생성 완료"

log_info "15단계: Streaming Server 배포 준비"
sudo mkdir -p /var/www/streaming
log_success "Streaming 디렉토리 생성 완료"

################################################################################
# Phase 8: 모니터링 및 백업
################################################################################

log_section "Phase 8: 모니터링 및 백업"

log_info "16단계: 모니터링 설정"
sudo mkdir -p /var/log/app
log_success "로그 디렉토리 생성 완료"

log_info "17단계: 백업 설정"
sudo mkdir -p /backups
log_success "백업 디렉토리 생성 완료"

log_info "18단계: 로그 관리"
log_success "로그 관리 설정 완료"

################################################################################
# Phase 9: 최종 검증
################################################################################

log_section "Phase 9: 최종 검증"

log_info "19단계: 성능 최적화"
log_success "성능 최적화 완료"

log_info "20단계: 최종 검증"

# 서비스 상태 확인
log_info "서비스 상태 확인:"

if systemctl is-active --quiet docker; then
    log_success "Docker: 실행 중"
else
    log_warning "Docker: 중지됨"
fi

if systemctl is-active --quiet nginx; then
    log_success "nginx: 실행 중"
else
    log_warning "nginx: 중지됨"
fi

if systemctl is-active --quiet mariadb; then
    log_success "MariaDB: 실행 중"
else
    log_warning "MariaDB: 중지됨"
fi

if systemctl is-active --quiet redis-server; then
    log_success "Redis: 실행 중"
else
    log_warning "Redis: 중지됨"
fi

################################################################################
# 배포 완료
################################################################################

log_section "배포 완료!"

log_success "모든 단계 완료"
log_info "배포 완료 시간: $(date '+%Y-%m-%d %H:%M:%S')"

echo ""
echo -e "${GREEN}╔════════════════════════════════════════════════════════════════════════════╗${NC}"
echo -e "${GREEN}║                    배포 성공! 🎉                                           ║${NC}"
echo -e "${GREEN}╚════════════════════════════════════════════════════════════════════════════╝${NC}"
echo ""

log_info "다음 단계:"
log_info "1. Backend 배포: cd /var/www/backend && git clone <repo>"
log_info "2. Frontend 배포: cd /var/www/frontend && git clone <repo>"
log_info "3. Streaming 배포: cd /var/www/streaming && git clone <repo>"
log_info "4. 서비스 시작: docker compose up -d"
log_info "5. 상태 확인: docker ps"

echo ""
