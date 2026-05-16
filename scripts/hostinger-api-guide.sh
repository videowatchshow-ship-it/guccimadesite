#!/bin/bash

################################################################################
# Hostinger API CLI 사용 가이드
# 공식 문서: https://support.hostinger.com/en/articles/11679133-how-to-use-hostinger-api-cli
# 공식 GitHub: https://github.com/hostinger/api-cli
# 버전: v0.1.12 (2025-11-07)
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
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

log_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

log_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

log_command() {
    echo -e "${CYAN}[COMMAND]${NC} $1"
}

################################################################################
# 1. Hostinger API CLI 설치 확인
################################################################################
check_installation() {
    log_info "=== Hostinger API CLI 설치 확인 ==="
    
    if ! command -v hapi &> /dev/null; then
        log_error "Hostinger API CLI가 설치되지 않았습니다"
        log_info "설치 명령어:"
        echo "cd /tmp"
        echo "wget https://github.com/hostinger/api-cli/releases/download/v0.1.12/hapi-0.1.12-linux-amd64.tar.gz"
        echo "tar -xf hapi-0.1.12-linux-amd64.tar.gz"
        echo "sudo mv hapi /usr/local/bin/"
        exit 1
    fi
    
    log_success "Hostinger API CLI 설치됨"
    log_command "hapi --version"
    hapi --version
    echo ""
}

################################################################################
# 2. API 토큰 설정 확인
################################################################################
check_api_token() {
    log_info "=== API 토큰 설정 확인 ==="
    
    if [ -z "$HAPI_API_TOKEN" ]; then
        log_warning "HAPI_API_TOKEN 환경 변수가 설정되지 않았습니다"
        log_info "설정 방법:"
        echo "export HAPI_API_TOKEN=your_token_here"
        echo ""
        log_info "또는 ~/.hapi.yaml 파일에 설정:"
        echo "api_token: your_token_here"
        return 1
    fi
    
    log_success "HAPI_API_TOKEN 환경 변수 설정됨"
    echo ""
}

################################################################################
# 3. VPS 목록 조회
################################################################################
list_vps() {
    log_info "=== VPS 목록 조회 ==="
    log_command "hapi vps vm list"
    hapi vps vm list --format json | jq '.' 2>/dev/null || hapi vps vm list
    echo ""
}

################################################################################
# 4. VPS 상세 정보 조회
################################################################################
get_vps_info() {
    local vm_id=$1
    
    if [ -z "$vm_id" ]; then
        log_error "VPS ID를 입력하세요"
        return 1
    fi
    
    log_info "=== VPS 상세 정보 조회 (ID: $vm_id) ==="
    log_command "hapi vps vm get $vm_id"
    hapi vps vm get "$vm_id" --format json | jq '.' 2>/dev/null || hapi vps vm get "$vm_id"
    echo ""
}

################################################################################
# 5. VPS 전원 제어
################################################################################
control_vps_power() {
    local action=$1
    local vm_id=$2
    
    if [ -z "$action" ] || [ -z "$vm_id" ]; then
        log_error "사용법: control_vps_power [start|stop|restart] <vm_id>"
        return 1
    fi
    
    case $action in
        start)
            log_info "=== VPS 시작 (ID: $vm_id) ==="
            log_command "hapi vps vm start $vm_id"
            hapi vps vm start "$vm_id"
            ;;
        stop)
            log_info "=== VPS 중지 (ID: $vm_id) ==="
            log_command "hapi vps vm stop $vm_id"
            hapi vps vm stop "$vm_id"
            ;;
        restart)
            log_info "=== VPS 재시작 (ID: $vm_id) ==="
            log_command "hapi vps vm stop $vm_id"
            hapi vps vm stop "$vm_id"
            sleep 5
            log_command "hapi vps vm start $vm_id"
            hapi vps vm start "$vm_id"
            ;;
        *)
            log_error "알 수 없는 작업: $action"
            return 1
            ;;
    esac
    
    log_success "작업 완료"
    echo ""
}

################################################################################
# 6. VPS 모니터링
################################################################################
monitor_vps() {
    local vm_id=$1
    
    if [ -z "$vm_id" ]; then
        log_error "VPS ID를 입력하세요"
        return 1
    fi
    
    log_info "=== VPS 모니터링 (ID: $vm_id) ==="
    log_info "5초마다 상태 업데이트 (Ctrl+C로 종료)"
    echo ""
    
    while true; do
        clear
        echo -e "${BLUE}=== VPS 모니터링 (ID: $vm_id) ===${NC}"
        echo "업데이트 시간: $(date '+%Y-%m-%d %H:%M:%S')"
        echo ""
        
        hapi vps vm get "$vm_id" --format json | jq '.data | {
            id: .id,
            hostname: .hostname,
            status: .status,
            cpu_cores: .cpu_cores,
            memory_mb: .memory_mb,
            disk_gb: .disk_gb,
            os: .os,
            ip_address: .ip_address
        }' 2>/dev/null || hapi vps vm get "$vm_id"
        
        echo ""
        echo "5초 후 새로고침... (Ctrl+C로 종료)"
        sleep 5
    done
}

################################################################################
# 7. 도움말 표시
################################################################################
show_help() {
    cat << EOF
${BLUE}=== Hostinger API CLI 사용 가이드 ===${NC}

${CYAN}설치 및 설정:${NC}
  check_installation    - Hostinger API CLI 설치 확인
  check_api_token       - API 토큰 설정 확인

${CYAN}VPS 관리:${NC}
  list_vps              - VPS 목록 조회
  get_vps_info <id>     - VPS 상세 정보 조회
  control_vps_power <action> <id>
                        - VPS 전원 제어 (start|stop|restart)
  monitor_vps <id>      - VPS 모니터링 (실시간)

${CYAN}공식 문서:${NC}
  - Hostinger API CLI: https://support.hostinger.com/en/articles/11679133-how-to-use-hostinger-api-cli
  - GitHub: https://github.com/hostinger/api-cli
  - API 레퍼런스: https://developers.hostinger.com

${CYAN}예제:${NC}
  # VPS 목록 조회
  hapi vps vm list

  # VPS 상세 정보 조회
  hapi vps vm get <vm_id>

  # VPS 시작
  hapi vps vm start <vm_id>

  # VPS 중지
  hapi vps vm stop <vm_id>

  # JSON 형식으로 출력
  hapi vps vm list --format json | jq '.'

${CYAN}환경 변수:${NC}
  HAPI_API_TOKEN        - Hostinger API 토큰 (필수)

${CYAN}설정 파일:${NC}
  ~/.hapi.yaml          - Hostinger API CLI 설정 파일

EOF
}

################################################################################
# 메인 함수
################################################################################
main() {
    local command=$1
    shift || true
    
    case $command in
        check-install)
            check_installation
            ;;
        check-token)
            check_api_token
            ;;
        list)
            check_installation
            check_api_token
            list_vps
            ;;
        info)
            check_installation
            check_api_token
            get_vps_info "$@"
            ;;
        power)
            check_installation
            check_api_token
            control_vps_power "$@"
            ;;
        monitor)
            check_installation
            check_api_token
            monitor_vps "$@"
            ;;
        help|--help|-h)
            show_help
            ;;
        *)
            show_help
            ;;
    esac
}

# 스크립트 실행
if [ $# -eq 0 ]; then
    show_help
else
    main "$@"
fi

exit 0
