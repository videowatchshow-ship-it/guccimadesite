#!/bin/bash

################################################################################
# 실제 배포 실행 스크립트
# 공식 문서: https://support.hostinger.com/en/articles/5723772-how-to-connect-to-your-vps-via-ssh
# 배포 방법: Hostinger Browser Terminal (권장)
# 버전: v1.0.0 (2026-05-17)
################################################################################

# VPS 정보
VPS_HOST="76.13.218.129"
VPS_HOSTNAME="srv1636789.hstgr.cloud"
VPS_USER="root"

# 배포 스크립트 URL
DEPLOY_SCRIPT_URL="https://raw.githubusercontent.com/your-repo/scripts/auto-deploy.sh"

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

log_section "2026 Production-Ready 플랫폼 배포 시작"

log_info "배포 시작 시간: $(date '+%Y-%m-%d %H:%M:%S')"
log_info "VPS 호스트: $VPS_HOST"
log_info "호스트명: $VPS_HOSTNAME"
log_info "사용자: $VPS_USER"

################################################################################
# Step 1: 배포 스크립트 다운로드
################################################################################

log_section "Step 1: 배포 스크립트 다운로드"

log_info "배포 스크립트 다운로드 중..."
curl -O "$DEPLOY_SCRIPT_URL"

if [ -f "auto-deploy.sh" ]; then
    log_success "배포 스크립트 다운로드 완료"
else
    log_error "배포 스크립트 다운로드 실패"
    exit 1
fi

################################################################################
# Step 2: 실행 권한 부여
################################################################################

log_section "Step 2: 실행 권한 부여"

chmod +x auto-deploy.sh
log_success "실행 권한 부여 완료"

################################################################################
# Step 3: 배포 스크립트 실행
################################################################################

log_section "Step 3: 배포 스크립트 실행"

log_info "배포 시작 (약 15분 소요)..."
bash auto-deploy.sh

if [ $? -eq 0 ]; then
    log_success "배포 완료!"
else
    log_error "배포 실패"
    exit 1
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
log_info "1. 서비스 상태 확인: docker ps"
log_info "2. Backend 배포: cd /var/www/backend && git clone <repo>"
log_info "3. Frontend 배포: cd /var/www/frontend && git clone <repo>"
log_info "4. Streaming 배포: cd /var/www/streaming && git clone <repo>"

echo ""
