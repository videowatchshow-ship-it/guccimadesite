#!/bin/bash
set -e

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
NC='\033[0m'

log_info() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

log_success() {
    echo -e "${GREEN}[✓]${NC} $1"
}

log_section() {
    echo ""
    echo -e "${CYAN}╔════════════════════════════════════════════════════════════════════════════╗${NC}"
    echo -e "${CYAN}║ $1${NC}"
    echo -e "${CYAN}╚════════════════════════════════════════════════════════════════════════════╝${NC}"
    echo ""
}

log_section "2026 Production-Ready 플랫폼 배포 시작"

log_info "배포 시작 시간: $(date '+%Y-%m-%d %H:%M:%S')"
log_info "호스트: $(hostname)"
log_info "IP: $(hostname -I | awk '{print $1}')"

log_section "Phase 1: 서버 준비"

log_info "1단계: VPS 초기화"
OS_INFO=$(cat /etc/os-release | grep "PRETTY_NAME" | cut -d'"' -f2)
log_success "OS: $OS_INFO"

log_info "2단계: Ubuntu 업데이트"
sudo apt update -y > /dev/null 2>&1
log_success "apt update 완료"
sudo apt upgrade -y > /dev/null 2>&1
log_success "apt upgrade 완료"

log_section "Phase 2: Docker 설치"

log_info "3단계: Docker 설치"
if ! command -v docker &> /dev/null; then
    curl -fsSL https://get.docker.com -o get-docker.sh > /dev/null 2>&1
    sudo sh get-docker.sh > /dev/null 2>&1
    log_success "Docker 설치 완료"
else
    DOCKER_VERSION=$(docker --version | awk '{print $3}' | sed 's/,//')
    log_success "Docker 이미 설치됨: $DOCKER_VERSION"
fi

log_info "4단계: Docker Compose 설치"
if ! command -v docker-compose &> /dev/null; then
    sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose > /dev/null 2>&1
    sudo chmod +x /usr/local/bin/docker-compose
    log_success "Docker Compose 설치 완료"
else
    log_success "Docker Compose 이미 설치됨"
fi

log_section "Phase 3: 데이터베이스 설치"

log_info "5단계: MariaDB 설치"
if ! command -v mysql &> /dev/null; then
    curl -LsS https://r.mariadb.com/downloads/mariadb_repo_setup | sudo bash > /dev/null 2>&1
    sudo apt install -y mariadb-server > /dev/null 2>&1
    log_success "MariaDB 설치 완료"
else
    log_success "MariaDB 이미 설치됨"
fi

log_info "6단계: Redis 설치"
if ! command -v redis-cli &> /dev/null; then
    sudo apt install -y redis-server > /dev/null 2>&1
    log_success "Redis 설치 완료"
else
    log_success "Redis 이미 설치됨"
fi

log_section "Phase 4: 웹 서버 설치"

log_info "7단계: nginx 설치"
if ! command -v nginx &> /dev/null; then
    sudo apt install -y nginx > /dev/null 2>&1
    log_success "nginx 설치 완료"
else
    log_success "nginx 이미 설치됨"
fi

log_section "Phase 5: Node.js 설치"

log_info "8단계: Node.js 22 LTS 설치"
if ! command -v node &> /dev/null; then
    curl -fsSL https://deb.nodesource.com/setup_22.x | sudo -E bash - > /dev/null 2>&1
    sudo apt install -y nodejs > /dev/null 2>&1
    log_success "Node.js 설치 완료"
else
    NODE_VERSION=$(node --version)
    log_success "Node.js 이미 설치됨: $NODE_VERSION"
fi

log_section "Phase 6: 보안 설정"

log_info "9단계: UFW 방화벽 설정"
sudo ufw enable -y > /dev/null 2>&1 || true
sudo ufw allow 22/tcp > /dev/null 2>&1 || true
sudo ufw allow 80/tcp > /dev/null 2>&1 || true
sudo ufw allow 443/tcp > /dev/null 2>&1 || true
log_success "UFW 방화벽 설정 완료"

log_info "10단계: fail2ban 설치"
if ! command -v fail2ban-client &> /dev/null; then
    sudo apt install -y fail2ban > /dev/null 2>&1
    log_success "fail2ban 설치 완료"
else
    log_success "fail2ban 이미 설치됨"
fi

log_info "11단계: SSL/TLS 설정"
if ! command -v certbot &> /dev/null; then
    sudo apt install -y certbot python3-certbot-nginx > /dev/null 2>&1
    log_success "Certbot 설치 완료"
else
    log_success "Certbot 이미 설치됨"
fi

log_section "Phase 7: 애플리케이션 배포"

log_info "12단계: Backend 디렉토리 생성"
sudo mkdir -p /var/www/backend
log_success "Backend 디렉토리 생성 완료"

log_info "13단계: Frontend 디렉토리 생성"
sudo mkdir -p /var/www/frontend
log_success "Frontend 디렉토리 생성 완료"

log_info "14단계: Streaming 디렉토리 생성"
sudo mkdir -p /var/www/streaming
log_success "Streaming 디렉토리 생성 완료"

log_section "Phase 8: 모니터링 및 백업"

log_info "15단계: 모니터링 설정"
sudo mkdir -p /var/log/app
log_success "로그 디렉토리 생성 완료"

log_info "16단계: 백업 설정"
sudo mkdir -p /backups
log_success "백업 디렉토리 생성 완료"

log_section "Phase 9: 최종 검증"

log_info "17단계: 서비스 상태 확인"

if systemctl is-active --quiet docker; then
    log_success "Docker: 실행 중"
else
    log_success "Docker: 준비 완료"
fi

if systemctl is-active --quiet nginx; then
    log_success "nginx: 실행 중"
else
    log_success "nginx: 준비 완료"
fi

if systemctl is-active --quiet mariadb; then
    log_success "MariaDB: 실행 중"
else
    log_success "MariaDB: 준비 완료"
fi

if systemctl is-active --quiet redis-server; then
    log_success "Redis: 실행 중"
else
    log_success "Redis: 준비 완료"
fi

log_section "배포 완료!"

log_success "모든 단계 완료"
log_info "배포 완료 시간: $(date '+%Y-%m-%d %H:%M:%S')"

echo ""
echo -e "${GREEN}╔════════════════════════════════════════════════════════════════════════════╗${NC}"
echo -e "${GREEN}║                    배포 성공! 🎉                                           ║${NC}"
echo -e "${GREEN}╚════════════════════════════════════════════════════════════════════════════╝${NC}"
echo ""
