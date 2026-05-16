#!/bin/bash

################################################################################
# VPS 초기 설정 및 배포 스크립트
# 공식 문서 기준: Hostinger API CLI v0.1.12
# 작성일: 2026-05-14
# 목표: Ubuntu 24.04 LTS 완전 업데이트 및 배포 준비
################################################################################

set -e  # 에러 발생 시 즉시 종료

# 색상 정의
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

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

################################################################################
# 1단계: 시스템 정보 확인
################################################################################
log_info "=== VPS 시스템 정보 확인 ==="
echo "OS: $(lsb_release -d | cut -f2)"
echo "Kernel: $(uname -r)"
echo "CPU: $(nproc) cores"
echo "Memory: $(free -h | grep Mem | awk '{print $2}')"
echo "Disk: $(df -h / | tail -1 | awk '{print $2}')"
echo ""

################################################################################
# 2단계: 시스템 업데이트
################################################################################
log_info "=== 시스템 업데이트 시작 ==="
sudo apt update
log_success "apt 캐시 업데이트 완료"

sudo apt upgrade -y
log_success "시스템 패키지 업그레이드 완료"

sudo apt autoremove -y
log_success "불필요한 패키지 제거 완료"

sudo apt autoclean -y
log_success "패키지 캐시 정리 완료"

################################################################################
# 3단계: 필수 도구 설치
################################################################################
log_info "=== 필수 도구 설치 ==="

# 기본 도구
sudo apt install -y \
    curl \
    wget \
    git \
    build-essential \
    software-properties-common \
    apt-transport-https \
    ca-certificates \
    gnupg \
    lsb-release \
    jq \
    htop \
    net-tools \
    vim \
    nano \
    unzip \
    zip

log_success "필수 도구 설치 완료"

################################################################################
# 4단계: Hostinger API CLI 설치
################################################################################
log_info "=== Hostinger API CLI 설치 ==="

# 최신 버전 확인 (v0.1.12)
HAPI_VERSION="0.1.12"
HAPI_URL="https://github.com/hostinger/api-cli/releases/download/v${HAPI_VERSION}/hapi-${HAPI_VERSION}-linux-amd64.tar.gz"

log_info "Hostinger API CLI v${HAPI_VERSION} 다운로드 중..."
cd /tmp
wget -q "${HAPI_URL}" -O "hapi-${HAPI_VERSION}-linux-amd64.tar.gz"
log_success "다운로드 완료"

log_info "압축 해제 중..."
tar -xf "hapi-${HAPI_VERSION}-linux-amd64.tar.gz"
log_success "압축 해제 완료"

log_info "설치 중..."
sudo mv hapi /usr/local/bin/
sudo chmod +x /usr/local/bin/hapi
log_success "Hostinger API CLI 설치 완료"

# 설치 확인
log_info "설치 확인 중..."
hapi --version
log_success "Hostinger API CLI 설치 확인 완료"

################################################################################
# 5단계: Hostinger API CLI 설정
################################################################################
log_info "=== Hostinger API CLI 설정 ==="

# 설정 파일 다운로드
log_info "설정 파일 다운로드 중..."
wget -q https://raw.githubusercontent.com/hostinger/api-cli/main/hapi.yaml -O ~/.hapi.yaml
log_success "설정 파일 다운로드 완료"

# API 토큰 설정 (환경 변수 사용)
if [ -z "$HAPI_API_TOKEN" ]; then
    log_warning "HAPI_API_TOKEN 환경 변수가 설정되지 않았습니다"
    log_info "다음 명령어로 설정하세요:"
    echo "export HAPI_API_TOKEN=your_token_here"
else
    log_success "HAPI_API_TOKEN 환경 변수 설정됨"
fi

################################################################################
# 6단계: Node.js 22 LTS 설치
################################################################################
log_info "=== Node.js 22 LTS 설치 ==="

# NodeSource 저장소 추가
curl -fsSL https://deb.nodesource.com/setup_22.x | sudo -E bash -
log_success "NodeSource 저장소 추가 완료"

# Node.js 설치
sudo apt install -y nodejs
log_success "Node.js 설치 완료"

# 버전 확인
log_info "Node.js 버전: $(node --version)"
log_info "npm 버전: $(npm --version)"

# npm 업데이트
sudo npm install -g npm@latest
log_success "npm 업데이트 완료"

################################################################################
# 7단계: Docker 설치
################################################################################
log_info "=== Docker 설치 ==="

# Docker 저장소 설정
sudo install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
sudo chmod a+r /etc/apt/keyrings/docker.gpg

echo \
  "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu \
  $(. /etc/os-release && echo "$VERSION_CODENAME") stable" | \
  sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

sudo apt update
log_success "Docker 저장소 설정 완료"

# Docker 설치
sudo apt install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
log_success "Docker 설치 완료"

# Docker 버전 확인
log_info "Docker 버전: $(docker --version)"

# Docker 권한 설정
sudo usermod -aG docker $USER
log_warning "Docker 권한 설정 완료 (로그아웃 후 적용됨)"

################################################################################
# 8단계: Docker Compose 설치
################################################################################
log_info "=== Docker Compose 설치 ==="

# 최신 버전 확인
DOCKER_COMPOSE_VERSION=$(curl -s https://api.github.com/repos/docker/compose/releases/latest | grep 'tag_name' | cut -d'"' -f4)
log_info "Docker Compose 최신 버전: ${DOCKER_COMPOSE_VERSION}"

# Docker Compose 설치
sudo curl -L "https://github.com/docker/compose/releases/download/${DOCKER_COMPOSE_VERSION}/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose
log_success "Docker Compose 설치 완료"

# 버전 확인
log_info "Docker Compose 버전: $(docker-compose --version)"

################################################################################
# 9단계: MariaDB 클라이언트 설치
################################################################################
log_info "=== MariaDB 클라이언트 설치 ==="

sudo apt install -y mariadb-client
log_success "MariaDB 클라이언트 설치 완료"

log_info "MariaDB 버전: $(mysql --version)"

################################################################################
# 10단계: Redis 클라이언트 설치
################################################################################
log_info "=== Redis 클라이언트 설치 ==="

sudo apt install -y redis-tools
log_success "Redis 클라이언트 설치 완료"

log_info "Redis 버전: $(redis-cli --version)"

################################################################################
# 11단계: nginx 설치
################################################################################
log_info "=== nginx 설치 ==="

sudo apt install -y nginx
log_success "nginx 설치 완료"

log_info "nginx 버전: $(nginx -v 2>&1)"

# nginx 시작
sudo systemctl start nginx
sudo systemctl enable nginx
log_success "nginx 시작 및 자동 시작 설정 완료"

################################################################################
# 12단계: SSL 도구 설치
################################################################################
log_info "=== SSL 도구 설치 ==="

sudo apt install -y certbot python3-certbot-nginx
log_success "Certbot 설치 완료"

################################################################################
# 13단계: 보안 도구 설치
################################################################################
log_info "=== 보안 도구 설치 ==="

# fail2ban
sudo apt install -y fail2ban
sudo systemctl start fail2ban
sudo systemctl enable fail2ban
log_success "fail2ban 설치 및 시작 완료"

# UFW (Uncomplicated Firewall)
sudo apt install -y ufw
log_success "UFW 설치 완료"

# UFW 기본 규칙 설정
sudo ufw default deny incoming
sudo ufw default allow outgoing
sudo ufw allow 22/tcp    # SSH
sudo ufw allow 80/tcp    # HTTP
sudo ufw allow 443/tcp   # HTTPS
sudo ufw allow 1935/tcp  # RTMP
sudo ufw allow 1935/udp  # RTMP UDP
sudo ufw allow 8080/tcp  # 대체 포트
sudo ufw --force enable
log_success "UFW 규칙 설정 완료"

################################################################################
# 14단계: 모니터링 도구 설치
################################################################################
log_info "=== 모니터링 도구 설치 ==="

# htop은 이미 설치됨
# 추가 모니터링 도구
sudo apt install -y sysstat
log_success "모니터링 도구 설치 완료"

################################################################################
# 15단계: 개발 도구 설치
################################################################################
log_info "=== 개발 도구 설치 ==="

# Git 설정
git config --global user.name "VPS Deploy"
git config --global user.email "deploy@vps.local"
log_success "Git 설정 완료"

# Python 3
sudo apt install -y python3 python3-pip python3-venv
log_success "Python 3 설치 완료"

################################################################################
# 16단계: 환경 변수 설정
################################################################################
log_info "=== 환경 변수 설정 ==="

# .bashrc에 환경 변수 추가
if ! grep -q "HAPI_API_TOKEN" ~/.bashrc; then
    echo "" >> ~/.bashrc
    echo "# Hostinger API CLI" >> ~/.bashrc
    echo "export HAPI_API_TOKEN=${HAPI_API_TOKEN:-your_token_here}" >> ~/.bashrc
    log_success "환경 변수 설정 완료"
else
    log_warning "환경 변수가 이미 설정되어 있습니다"
fi

################################################################################
# 17단계: 시스템 정보 저장
################################################################################
log_info "=== 시스템 정보 저장 ==="

cat > /tmp/vps-info.txt << EOF
VPS 배포 정보
생성일: $(date)

시스템 정보:
- OS: $(lsb_release -d | cut -f2)
- Kernel: $(uname -r)
- CPU: $(nproc) cores
- Memory: $(free -h | grep Mem | awk '{print $2}')
- Disk: $(df -h / | tail -1 | awk '{print $2}')

설치된 소프트웨어:
- Node.js: $(node --version)
- npm: $(npm --version)
- Docker: $(docker --version)
- Docker Compose: $(docker-compose --version)
- nginx: $(nginx -v 2>&1)
- MariaDB Client: $(mysql --version)
- Redis Client: $(redis-cli --version)
- Hostinger API CLI: v${HAPI_VERSION}

보안 설정:
- fail2ban: 활성화
- UFW: 활성화
- SSH: 포트 22

다음 단계:
1. SSH 키 설정
2. 도메인 설정
3. SSL 인증서 발급
4. 애플리케이션 배포
5. 모니터링 설정
EOF

log_success "시스템 정보 저장 완료: /tmp/vps-info.txt"

################################################################################
# 18단계: 최종 확인
################################################################################
log_info "=== 최종 확인 ==="

echo ""
echo "=== 설치된 버전 ==="
echo "Node.js: $(node --version)"
echo "npm: $(npm --version)"
echo "Docker: $(docker --version)"
echo "Docker Compose: $(docker-compose --version)"
echo "nginx: $(nginx -v 2>&1)"
echo "Hostinger API CLI: v${HAPI_VERSION}"
echo ""

echo "=== 서비스 상태 ==="
sudo systemctl status nginx --no-pager | grep Active
sudo systemctl status fail2ban --no-pager | grep Active
echo ""

echo "=== 방화벽 규칙 ==="
sudo ufw status
echo ""

################################################################################
# 완료
################################################################################
log_success "=== VPS 초기 설정 완료 ==="
log_info "다음 단계:"
echo "1. SSH 키 설정: ssh-keygen -t ed25519"
echo "2. Hostinger API 토큰 설정: export HAPI_API_TOKEN=your_token"
echo "3. VPS 상태 확인: hapi vps vm list"
echo "4. 애플리케이션 배포 시작"
echo ""
log_warning "로그아웃 후 다시 로그인하여 Docker 권한 적용"
echo ""

# 시스템 정보 출력
cat /tmp/vps-info.txt

exit 0
