#!/bin/bash

################################################################################
# 프로덕션 배포 스크립트
# 공식 문서: https://docs.docker.com/engine/install/ubuntu/
# 공식 GitHub: https://github.com/docker/docker-install
# Docker Compose: https://docs.docker.com/compose/install/
# Node.js: https://github.com/nodesource/distributions
# 버전: v2.0.0 (2026-06-01)
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
PATH_REGEX="^/[a-zA-Z0-9/_.-]+$"
HOSTNAME_REGEX="^[a-zA-Z0-9.-]+$"
IP_REGEX="^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$"

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

# 에러 처리
error_exit() {
    log_error "$1"
    exit 1
}

# 버전 검증
validate_version() {
    local version=$1
    local name=$2
    
    if [[ $version =~ $VERSION_REGEX ]]; then
        log_success "$name: $version (검증 통과)"
        return 0
    else
        log_warning "$name: $version (버전 형식 확인 필요)"
        return 1
    fi
}

################################################################################
# Phase 1: 서버 준비
################################################################################

phase_1_server_preparation() {
    log_section "Phase 1: 서버 준비"
    
    # 1단계: VPS 초기화
    log_info "1단계: VPS 초기화"
    
    log_info "시스템 정보 확인"
    OS_INFO=$(cat /etc/os-release | grep "PRETTY_NAME" | cut -d'"' -f2)
    log_success "OS: $OS_INFO"
    
    log_info "디스크 상태 확인"
    DISK_USAGE=$(df -h / | awk 'NR==2 {print $5}')
    log_success "디스크 사용률: $DISK_USAGE"
    
    log_info "메모리 상태 확인"
    MEM_USAGE=$(free -h | awk 'NR==2 {print $3 "/" $2}')
    log_success "메모리 사용량: $MEM_USAGE"
    
    # 2단계: Ubuntu 업데이트
    log_info "2단계: Ubuntu 업데이트"
    
    log_info "패키지 목록 업데이트"
    sudo apt update -y || error_exit "apt update 실패"
    log_success "apt update 완료"
    
    log_info "시스템 업그레이드"
    sudo apt upgrade -y || error_exit "apt upgrade 실패"
    log_success "apt upgrade 완료"
    
    log_info "필수 도구 설치"
    sudo apt install -y \
        curl \
        wget \
        git \
        build-essential \
        libssl-dev \
        libffi-dev \
        python3-dev \
        python3-pip \
        || error_exit "필수 도구 설치 실패"
    log_success "필수 도구 설치 완료"
    
    # 버전 확인
    CURL_VERSION=$(curl --version | head -1 | awk '{print $2}')
    validate_version "$CURL_VERSION" "curl"
    
    GIT_VERSION=$(git --version | awk '{print $3}')
    validate_version "$GIT_VERSION" "git"
    
    PYTHON_VERSION=$(python3 --version | awk '{print $2}')
    validate_version "$PYTHON_VERSION" "python3"
    
    # 3단계: SSH 보안 설정
    log_info "3단계: SSH 보안 설정"
    
    if [ -f /etc/ssh/sshd_config ]; then
        log_info "SSH 설정 파일 백업"
        sudo cp /etc/ssh/sshd_config /etc/ssh/sshd_config.backup
        log_success "SSH 설정 파일 백업 완료"
        
        log_info "SSH 설정 검증"
        sudo sshd -t && log_success "SSH 설정 검증 완료" || error_exit "SSH 설정 검증 실패"
    fi
    
    log_success "Phase 1 완료"
}

################################################################################
# Phase 2: Docker 설치
################################################################################

phase_2_docker_installation() {
    log_section "Phase 2: Docker 설치"
    
    # 4단계: Docker 설치
    log_info "4단계: Docker 설치"
    
    if command -v docker &> /dev/null; then
        log_warning "Docker가 이미 설치되어 있습니다"
        DOCKER_VERSION=$(docker --version | grep -oP '[0-9]+\.[0-9]+\.[0-9]+' | head -1)
        validate_version "$DOCKER_VERSION" "Docker"
    else
        log_info "Docker 설치 중..."
        curl -fsSL https://get.docker.com -o get-docker.sh || error_exit "Docker 설치 스크립트 다운로드 실패"
        sudo sh get-docker.sh || error_exit "Docker 설치 실패"
        rm get-docker.sh
        log_success "Docker 설치 완료"
        
        DOCKER_VERSION=$(docker --version | grep -oP '[0-9]+\.[0-9]+\.[0-9]+' | head -1)
        validate_version "$DOCKER_VERSION" "Docker"
    fi
    
    log_info "Docker 서비스 상태 확인"
    sudo systemctl status docker || error_exit "Docker 서비스 실패"
    log_success "Docker 서비스 실행 중"
    
    # 5단계: Docker Compose 설치
    log_info "5단계: Docker Compose 설치"
    
    if command -v docker &> /dev/null && docker compose version &> /dev/null; then
        log_warning "Docker Compose가 이미 설치되어 있습니다"
        DOCKER_COMPOSE_VERSION=$(docker compose version 2>/dev/null | grep -oP '[0-9]+\.[0-9]+\.[0-9]+' | head -1)
        validate_version "$DOCKER_COMPOSE_VERSION" "Docker Compose"
    else
        log_info "Docker Compose 설치 중..."
        COMPOSE_VERSION="2.35.1"
        DOCKER_COMPOSE_URL="https://github.com/docker/compose/releases/download/v${COMPOSE_VERSION}/docker-compose-$(uname -s)-$(uname -m)"
        sudo curl -L "$DOCKER_COMPOSE_URL" -o /usr/local/bin/docker-compose || error_exit "Docker Compose 다운로드 실패"
        sudo chmod +x /usr/local/bin/docker-compose || error_exit "Docker Compose 권한 설정 실패"
        log_success "Docker Compose ${COMPOSE_VERSION} 설치 완료"
        
        DOCKER_COMPOSE_VERSION=$(docker compose version 2>/dev/null | grep -oP '[0-9]+\.[0-9]+\.[0-9]+' | head -1)
        validate_version "$DOCKER_COMPOSE_VERSION" "Docker Compose"
    fi
    
    log_success "Phase 2 완료"
}

################################################################################
# Phase 3: 데이터베이스 설치
################################################################################

phase_3_database_installation() {
    log_section "Phase 3: 데이터베이스 설치"
    
    # 6단계: MariaDB 설치
    log_info "6단계: MariaDB 설치"
    
    if command -v mysql &> /dev/null; then
        log_warning "MariaDB가 이미 설치되어 있습니다"
        MARIADB_VERSION=$(mysql --version | grep -oP '[0-9]+\.[0-9]+\.[0-9]+' | head -1)
        validate_version "$MARIADB_VERSION" "MariaDB"
    else
        log_info "MariaDB 저장소 추가"
        curl -LsS https://r.mariadb.com/downloads/mariadb_repo_setup | sudo bash || error_exit "MariaDB 저장소 추가 실패"
        
        log_info "MariaDB 설치 중..."
        sudo apt install -y mariadb-server || error_exit "MariaDB 설치 실패"
        log_success "MariaDB 설치 완료"
        
        MARIADB_VERSION=$(mysql --version | grep -oP '[0-9]+\.[0-9]+\.[0-9]+' | head -1)
        validate_version "$MARIADB_VERSION" "MariaDB"
    fi
    
    log_info "MariaDB 서비스 상태 확인"
    sudo systemctl status mariadb || error_exit "MariaDB 서비스 실패"
    log_success "MariaDB 서비스 실행 중"
    
    # 7단계: Redis 설치
    log_info "7단계: Redis 설치"
    
    if command -v redis-cli &> /dev/null; then
        log_warning "Redis가 이미 설치되어 있습니다"
        REDIS_VERSION=$(redis-cli --version | grep -oP '[0-9]+\.[0-9]+\.[0-9]+' | head -1)
        validate_version "$REDIS_VERSION" "Redis"
    else
        log_info "Redis 설치 중..."
        sudo apt install -y redis-server || error_exit "Redis 설치 실패"
        log_success "Redis 설치 완료"
        
        REDIS_VERSION=$(redis-cli --version | grep -oP '[0-9]+\.[0-9]+\.[0-9]+' | head -1)
        validate_version "$REDIS_VERSION" "Redis"
    fi
    
    log_info "Redis 서비스 상태 확인"
    sudo systemctl status redis-server || error_exit "Redis 서비스 실패"
    log_success "Redis 서비스 실행 중"
    
    log_success "Phase 3 완료"
}

################################################################################
# Phase 4: 웹 서버 설치
################################################################################

phase_4_webserver_installation() {
    log_section "Phase 4: 웹 서버 설치"
    
    # 8단계: nginx 설치
    log_info "8단계: nginx 설치"
    
    if command -v nginx &> /dev/null; then
        log_warning "nginx가 이미 설치되어 있습니다"
        NGINX_VERSION=$(nginx -v 2>&1 | grep -oP '[0-9]+\.[0-9]+\.[0-9]+' | head -1)
        validate_version "$NGINX_VERSION" "nginx"
    else
        log_info "nginx 설치 중..."
        sudo apt install -y nginx || error_exit "nginx 설치 실패"
        log_success "nginx 설치 완료"
        
        NGINX_VERSION=$(nginx -v 2>&1 | grep -oP '[0-9]+\.[0-9]+\.[0-9]+' | head -1)
        validate_version "$NGINX_VERSION" "nginx"
    fi
    
    log_info "nginx 설정 검증"
    sudo nginx -t || error_exit "nginx 설정 검증 실패"
    log_success "nginx 설정 검증 완료"
    
    log_info "nginx 서비스 시작"
    sudo systemctl start nginx || error_exit "nginx 서비스 시작 실패"
    sudo systemctl enable nginx || error_exit "nginx 자동 시작 설정 실패"
    log_success "nginx 서비스 시작 완료"
    
    log_success "Phase 4 완료"
}

################################################################################
# Phase 5: Node.js 설치
################################################################################

phase_5_nodejs_installation() {
    log_section "Phase 5: Node.js 설치"
    
    # 9단계: Node.js 설치
    log_info "9단계: Node.js 설치"
    
    if command -v node &> /dev/null; then
        log_warning "Node.js가 이미 설치되어 있습니다"
        NODE_VERSION=$(node --version | sed 's/v//')
        validate_version "$NODE_VERSION" "Node.js"
    else
        log_info "Node.js 저장소 추가"
        curl -fsSL https://deb.nodesource.com/setup_22.x | sudo -E bash - || error_exit "Node.js 저장소 추가 실패"
        
        log_info "Node.js 설치 중..."
        sudo apt install -y nodejs || error_exit "Node.js 설치 실패"
        log_success "Node.js 설치 완료"
        
        NODE_VERSION=$(node --version | sed 's/v//')
        validate_version "$NODE_VERSION" "Node.js"
    fi
    
    log_info "npm 업그레이드"
    sudo npm install -g npm@10.9.2 || error_exit "npm 업그레이드 실패"
    NPM_VERSION=$(npm --version)
    validate_version "$NPM_VERSION" "npm"
    
    log_success "Phase 5 완료"
}

################################################################################
# Phase 6: 보안 설정
################################################################################

phase_6_security_setup() {
    log_section "Phase 6: 보안 설정"
    
    # 10단계: UFW 방화벽 설정
    log_info "10단계: UFW 방화벽 설정"
    
    if sudo ufw status | grep -q "Status: active"; then
        log_warning "UFW가 이미 활성화되어 있습니다"
    else
        log_info "UFW 활성화"
        sudo ufw --force enable || error_exit "UFW 활성화 실패"
        log_success "UFW 활성화 완료"
    fi
    
    log_info "필수 포트 개방"
    sudo ufw allow 22/tcp || log_warning "SSH 포트 개방 실패"
    sudo ufw allow 80/tcp || log_warning "HTTP 포트 개방 실패"
    sudo ufw allow 443/tcp || log_warning "HTTPS 포트 개방 실패"
    sudo ufw allow 3000/tcp || log_warning "Backend 포트 개방 실패"
    log_success "필수 포트 개방 완료"
    
    log_info "방화벽 상태 확인"
    sudo ufw status verbose
    
    # 11단계: fail2ban 설치
    log_info "11단계: fail2ban 설치"
    
    if command -v fail2ban-client &> /dev/null; then
        log_warning "fail2ban이 이미 설치되어 있습니다"
    else
        log_info "fail2ban 설치 중..."
        sudo apt install -y fail2ban || error_exit "fail2ban 설치 실패"
        log_success "fail2ban 설치 완료"
    fi
    
    log_info "fail2ban 서비스 시작"
    sudo systemctl start fail2ban || error_exit "fail2ban 서비스 시작 실패"
    sudo systemctl enable fail2ban || error_exit "fail2ban 자동 시작 설정 실패"
    log_success "fail2ban 서비스 시작 완료"
    
    # 12단계: SSL/TLS 설정
    log_info "12단계: SSL/TLS 설정"
    
    if command -v certbot &> /dev/null; then
        log_warning "Certbot이 이미 설치되어 있습니다"
    else
        log_info "Certbot 설치 중..."
        sudo apt install -y certbot python3-certbot-nginx || error_exit "Certbot 설치 실패"
        log_success "Certbot 설치 완료"
    fi
    
    log_success "Phase 6 완료"
}

################################################################################
# Phase 7: 애플리케이션 배포
################################################################################

phase_7_application_deployment() {
    log_section "Phase 7: 애플리케이션 배포"
    
    log_info "애플리케이션 배포 준비"
    log_info "Backend, Frontend, Streaming 서버 배포 필요"
    log_info "Docker Compose 또는 PM2를 사용하여 배포"
    
    log_success "Phase 7 준비 완료"
}

################################################################################
# Phase 8: 모니터링 및 백업
################################################################################

phase_8_monitoring_backup() {
    log_section "Phase 8: 모니터링 및 백업"
    
    # 16단계: 모니터링 설정
    log_info "16단계: 모니터링 설정"
    log_info "모니터링 도구 설치 필요 (Prometheus, Grafana 등)"
    
    # 17단계: 백업 설정
    log_info "17단계: 백업 설정"
    
    log_info "백업 디렉토리 생성"
    sudo mkdir -p /backups || error_exit "백업 디렉토리 생성 실패"
    log_success "백업 디렉토리 생성 완료"
    
    # 18단계: 로그 관리
    log_info "18단계: 로그 관리"
    log_info "logrotate 설정 확인"
    sudo cat /etc/logrotate.conf > /dev/null && log_success "logrotate 설정 확인 완료"
    
    log_success "Phase 8 완료"
}

################################################################################
# Phase 9: 최종 검증
################################################################################

phase_9_final_validation() {
    log_section "Phase 9: 최종 검증"
    
    # 19단계: 성능 최적화
    log_info "19단계: 성능 최적화"
    log_info "nginx gzip 압축 설정 필요"
    log_info "캐시 설정 필요"
    
    # 20단계: 최종 검증
    log_info "20단계: 최종 검증"
    
    log_info "서비스 상태 확인"
    echo ""
    
    echo -n "Docker: "
    sudo systemctl is-active docker && log_success "실행 중" || log_error "중지됨"
    
    echo -n "nginx: "
    sudo systemctl is-active nginx && log_success "실행 중" || log_error "중지됨"
    
    echo -n "MariaDB: "
    sudo systemctl is-active mariadb && log_success "실행 중" || log_error "중지됨"
    
    echo -n "Redis: "
    sudo systemctl is-active redis-server && log_success "실행 중" || log_error "중지됨"
    
    echo ""
    log_info "포트 확인"
    sudo netstat -tlnp 2>/dev/null | grep LISTEN || sudo ss -tlnp | grep LISTEN
    
    log_success "Phase 9 완료"
}

################################################################################
# 메인 함수
################################################################################

main() {
    log_section "프로덕션 배포 시작"
    
    log_info "배포 시작 시간: $(date '+%Y-%m-%d %H:%M:%S')"
    log_info "호스트명: $(hostname)"
    log_info "IP 주소: $(hostname -I)"
    
    # Phase 1-9 실행
    phase_1_server_preparation
    phase_2_docker_installation
    phase_3_database_installation
    phase_4_webserver_installation
    phase_5_nodejs_installation
    phase_6_security_setup
    phase_7_application_deployment
    phase_8_monitoring_backup
    phase_9_final_validation
    
    log_section "프로덕션 배포 완료"
    
    log_info "배포 완료 시간: $(date '+%Y-%m-%d %H:%M:%S')"
    log_success "모든 Phase 완료!"
    log_info "다음 단계: 애플리케이션 배포 및 테스트"
    
    echo ""
    echo -e "${GREEN}╔════════════════════════════════════════════════════════════════════════════╗${NC}"
    echo -e "${GREEN}║                    배포 완료!                                             ║${NC}"
    echo -e "${GREEN}║                    서버 상태를 확인하세요.                                ║${NC}"
    echo -e "${GREEN}╚════════════════════════════════════════════════════════════════════════════╝${NC}"
}

# 스크립트 실행
main "$@"

exit 0
