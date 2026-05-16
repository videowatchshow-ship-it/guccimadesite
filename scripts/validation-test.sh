#!/bin/bash

################################################################################
# 10회 검증 테스트 스크립트
# 공식 문서: https://docs.docker.com/
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
HTTP_REGEX="^HTTP/[0-9]\.[0-9] [0-9]{3}$"

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

log_test() {
    echo -e "${CYAN}[TEST $1/10]${NC} $2"
}

# 출력 파일
OUTPUT_FILE="validation-test-$(date +%Y%m%d-%H%M%S).txt"

# 출력 함수
output() {
    echo "$1" | tee -a "$OUTPUT_FILE"
}

# 테스트 카운터
TEST_COUNT=0
PASS_COUNT=0
FAIL_COUNT=0

################################################################################
# 테스트 1: 시스템 정보 검증
################################################################################

test_1_system_info() {
    TEST_COUNT=$((TEST_COUNT + 1))
    log_test $TEST_COUNT "시스템 정보 검증"
    
    output "=== 테스트 1: 시스템 정보 검증 ==="
    
    # OS 확인
    OS=$(cat /etc/os-release | grep PRETTY_NAME | cut -d'"' -f2)
    if [[ $OS == *"Ubuntu 24.04"* ]]; then
        output "✅ OS: $OS"
        PASS_COUNT=$((PASS_COUNT + 1))
    else
        output "❌ OS: $OS (Ubuntu 24.04 LTS 필요)"
        FAIL_COUNT=$((FAIL_COUNT + 1))
    fi
    
    # 디스크 확인
    DISK_USAGE=$(df -h / | awk 'NR==2 {print $5}' | sed 's/%//')
    if [ "$DISK_USAGE" -lt 80 ]; then
        output "✅ 디스크 사용률: ${DISK_USAGE}% (정상)"
        PASS_COUNT=$((PASS_COUNT + 1))
    else
        output "❌ 디스크 사용률: ${DISK_USAGE}% (80% 이상)"
        FAIL_COUNT=$((FAIL_COUNT + 1))
    fi
    
    output ""
}

################################################################################
# 테스트 2: Docker 검증
################################################################################

test_2_docker() {
    TEST_COUNT=$((TEST_COUNT + 1))
    log_test $TEST_COUNT "Docker 검증"
    
    output "=== 테스트 2: Docker 검증 ==="
    
    if command -v docker &> /dev/null; then
        DOCKER_VERSION=$(docker --version | grep -oP '[0-9]+\.[0-9]+\.[0-9]+' | head -1)
        if [[ $DOCKER_VERSION =~ $VERSION_REGEX ]]; then
            output "✅ Docker 설치: $DOCKER_VERSION"
            PASS_COUNT=$((PASS_COUNT + 1))
        else
            output "❌ Docker 버전 형식 오류: $DOCKER_VERSION"
            FAIL_COUNT=$((FAIL_COUNT + 1))
        fi
    else
        output "❌ Docker: 설치되지 않음"
        FAIL_COUNT=$((FAIL_COUNT + 1))
    fi
    
    if systemctl is-active --quiet docker; then
        output "✅ Docker 서비스: 실행 중"
        PASS_COUNT=$((PASS_COUNT + 1))
    else
        output "❌ Docker 서비스: 중지됨"
        FAIL_COUNT=$((FAIL_COUNT + 1))
    fi
    
    output ""
}

################################################################################
# 테스트 3: Node.js 검증
################################################################################

test_3_nodejs() {
    TEST_COUNT=$((TEST_COUNT + 1))
    log_test $TEST_COUNT "Node.js 검증"
    
    output "=== 테스트 3: Node.js 검증 ==="
    
    if command -v node &> /dev/null; then
        NODE_VERSION=$(node --version | sed 's/v//')
        if [[ $NODE_VERSION =~ $VERSION_REGEX ]]; then
            output "✅ Node.js 설치: $NODE_VERSION"
            PASS_COUNT=$((PASS_COUNT + 1))
        else
            output "❌ Node.js 버전 형식 오류: $NODE_VERSION"
            FAIL_COUNT=$((FAIL_COUNT + 1))
        fi
    else
        output "❌ Node.js: 설치되지 않음"
        FAIL_COUNT=$((FAIL_COUNT + 1))
    fi
    
    if command -v npm &> /dev/null; then
        NPM_VERSION=$(npm --version)
        if [[ $NPM_VERSION =~ $VERSION_REGEX ]]; then
            output "✅ npm 설치: $NPM_VERSION"
            PASS_COUNT=$((PASS_COUNT + 1))
        else
            output "❌ npm 버전 형식 오류: $NPM_VERSION"
            FAIL_COUNT=$((FAIL_COUNT + 1))
        fi
    else
        output "❌ npm: 설치되지 않음"
        FAIL_COUNT=$((FAIL_COUNT + 1))
    fi
    
    output ""
}

################################################################################
# 테스트 4: nginx 검증
################################################################################

test_4_nginx() {
    TEST_COUNT=$((TEST_COUNT + 1))
    log_test $TEST_COUNT "nginx 검증"
    
    output "=== 테스트 4: nginx 검증 ==="
    
    if command -v nginx &> /dev/null; then
        NGINX_VERSION=$(nginx -v 2>&1 | grep -oP '[0-9]+\.[0-9]+\.[0-9]+' | head -1)
        if [[ $NGINX_VERSION =~ $VERSION_REGEX ]]; then
            output "✅ nginx 설치: $NGINX_VERSION"
            PASS_COUNT=$((PASS_COUNT + 1))
        else
            output "❌ nginx 버전 형식 오류: $NGINX_VERSION"
            FAIL_COUNT=$((FAIL_COUNT + 1))
        fi
    else
        output "❌ nginx: 설치되지 않음"
        FAIL_COUNT=$((FAIL_COUNT + 1))
    fi
    
    if systemctl is-active --quiet nginx; then
        output "✅ nginx 서비스: 실행 중"
        PASS_COUNT=$((PASS_COUNT + 1))
    else
        output "❌ nginx 서비스: 중지됨"
        FAIL_COUNT=$((FAIL_COUNT + 1))
    fi
    
    output ""
}

################################################################################
# 테스트 5: MariaDB 검증
################################################################################

test_5_mariadb() {
    TEST_COUNT=$((TEST_COUNT + 1))
    log_test $TEST_COUNT "MariaDB 검증"
    
    output "=== 테스트 5: MariaDB 검증 ==="
    
    if command -v mysql &> /dev/null; then
        MARIADB_VERSION=$(mysql --version | grep -oP '[0-9]+\.[0-9]+\.[0-9]+' | head -1)
        if [[ $MARIADB_VERSION =~ $VERSION_REGEX ]]; then
            output "✅ MariaDB 설치: $MARIADB_VERSION"
            PASS_COUNT=$((PASS_COUNT + 1))
        else
            output "❌ MariaDB 버전 형식 오류: $MARIADB_VERSION"
            FAIL_COUNT=$((FAIL_COUNT + 1))
        fi
    else
        output "❌ MariaDB: 설치되지 않음"
        FAIL_COUNT=$((FAIL_COUNT + 1))
    fi
    
    if systemctl is-active --quiet mariadb; then
        output "✅ MariaDB 서비스: 실행 중"
        PASS_COUNT=$((PASS_COUNT + 1))
    else
        output "❌ MariaDB 서비스: 중지됨"
        FAIL_COUNT=$((FAIL_COUNT + 1))
    fi
    
    output ""
}

################################################################################
# 테스트 6: Redis 검증
################################################################################

test_6_redis() {
    TEST_COUNT=$((TEST_COUNT + 1))
    log_test $TEST_COUNT "Redis 검증"
    
    output "=== 테스트 6: Redis 검증 ==="
    
    if command -v redis-cli &> /dev/null; then
        REDIS_VERSION=$(redis-cli --version | grep -oP '[0-9]+\.[0-9]+\.[0-9]+' | head -1)
        if [[ $REDIS_VERSION =~ $VERSION_REGEX ]]; then
            output "✅ Redis 설치: $REDIS_VERSION"
            PASS_COUNT=$((PASS_COUNT + 1))
        else
            output "❌ Redis 버전 형식 오류: $REDIS_VERSION"
            FAIL_COUNT=$((FAIL_COUNT + 1))
        fi
    else
        output "❌ Redis: 설치되지 않음"
        FAIL_COUNT=$((FAIL_COUNT + 1))
    fi
    
    if systemctl is-active --quiet redis-server; then
        output "✅ Redis 서비스: 실행 중"
        PASS_COUNT=$((PASS_COUNT + 1))
    else
        output "❌ Redis 서비스: 중지됨"
        FAIL_COUNT=$((FAIL_COUNT + 1))
    fi
    
    output ""
}

################################################################################
# 테스트 7: 포트 검증
################################################################################

test_7_ports() {
    TEST_COUNT=$((TEST_COUNT + 1))
    log_test $TEST_COUNT "포트 검증"
    
    output "=== 테스트 7: 포트 검증 ==="
    
    # SSH (22)
    if netstat -tlnp 2>/dev/null | grep -q ":22 " || ss -tlnp 2>/dev/null | grep -q ":22 "; then
        output "✅ SSH (22): 열림"
        PASS_COUNT=$((PASS_COUNT + 1))
    else
        output "❌ SSH (22): 닫힘"
        FAIL_COUNT=$((FAIL_COUNT + 1))
    fi
    
    # HTTP (80)
    if netstat -tlnp 2>/dev/null | grep -q ":80 " || ss -tlnp 2>/dev/null | grep -q ":80 "; then
        output "✅ HTTP (80): 열림"
        PASS_COUNT=$((PASS_COUNT + 1))
    else
        output "❌ HTTP (80): 닫힘"
        FAIL_COUNT=$((FAIL_COUNT + 1))
    fi
    
    # HTTPS (443)
    if netstat -tlnp 2>/dev/null | grep -q ":443 " || ss -tlnp 2>/dev/null | grep -q ":443 "; then
        output "✅ HTTPS (443): 열림"
        PASS_COUNT=$((PASS_COUNT + 1))
    else
        output "❌ HTTPS (443): 닫힘"
        FAIL_COUNT=$((FAIL_COUNT + 1))
    fi
    
    output ""
}

################################################################################
# 테스트 8: 보안 검증
################################################################################

test_8_security() {
    TEST_COUNT=$((TEST_COUNT + 1))
    log_test $TEST_COUNT "보안 검증"
    
    output "=== 테스트 8: 보안 검증 ==="
    
    # UFW
    if sudo ufw status 2>/dev/null | grep -q "Status: active"; then
        output "✅ UFW: 활성화"
        PASS_COUNT=$((PASS_COUNT + 1))
    else
        output "❌ UFW: 비활성화"
        FAIL_COUNT=$((FAIL_COUNT + 1))
    fi
    
    # fail2ban
    if systemctl is-active --quiet fail2ban; then
        output "✅ fail2ban: 실행 중"
        PASS_COUNT=$((PASS_COUNT + 1))
    else
        output "❌ fail2ban: 중지됨"
        FAIL_COUNT=$((FAIL_COUNT + 1))
    fi
    
    output ""
}

################################################################################
# 테스트 9: HTTP 응답 검증
################################################################################

test_9_http_response() {
    TEST_COUNT=$((TEST_COUNT + 1))
    log_test $TEST_COUNT "HTTP 응답 검증"
    
    output "=== 테스트 9: HTTP 응답 검증 ==="
    
    # localhost HTTP
    HTTP_RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost 2>/dev/null || echo "000")
    if [ "$HTTP_RESPONSE" == "200" ] || [ "$HTTP_RESPONSE" == "301" ] || [ "$HTTP_RESPONSE" == "302" ]; then
        output "✅ HTTP localhost: $HTTP_RESPONSE"
        PASS_COUNT=$((PASS_COUNT + 1))
    else
        output "⚠️  HTTP localhost: $HTTP_RESPONSE"
        FAIL_COUNT=$((FAIL_COUNT + 1))
    fi
    
    # localhost HTTPS
    HTTPS_RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" -k https://localhost 2>/dev/null || echo "000")
    if [ "$HTTPS_RESPONSE" == "200" ] || [ "$HTTPS_RESPONSE" == "301" ] || [ "$HTTPS_RESPONSE" == "302" ]; then
        output "✅ HTTPS localhost: $HTTPS_RESPONSE"
        PASS_COUNT=$((PASS_COUNT + 1))
    else
        output "⚠️  HTTPS localhost: $HTTPS_RESPONSE"
        FAIL_COUNT=$((FAIL_COUNT + 1))
    fi
    
    output ""
}

################################################################################
# 테스트 10: 데이터베이스 연결 검증
################################################################################

test_10_database_connection() {
    TEST_COUNT=$((TEST_COUNT + 1))
    log_test $TEST_COUNT "데이터베이스 연결 검증"
    
    output "=== 테스트 10: 데이터베이스 연결 검증 ==="
    
    # MariaDB 연결
    if mysql -u root -e "SELECT 1;" &>/dev/null; then
        output "✅ MariaDB 연결: 성공"
        PASS_COUNT=$((PASS_COUNT + 1))
    else
        output "❌ MariaDB 연결: 실패"
        FAIL_COUNT=$((FAIL_COUNT + 1))
    fi
    
    # Redis 연결
    if redis-cli ping &>/dev/null | grep -q "PONG"; then
        output "✅ Redis 연결: 성공"
        PASS_COUNT=$((PASS_COUNT + 1))
    else
        output "❌ Redis 연결: 실패"
        FAIL_COUNT=$((FAIL_COUNT + 1))
    fi
    
    output ""
}

################################################################################
# 최종 결과 요약
################################################################################

final_summary() {
    output "╔════════════════════════════════════════════════════════════════════════════╗"
    output "║                    10회 검증 테스트 완료                                   ║"
    output "╚════════════════════════════════════════════════════════════════════════════╝"
    output ""
    
    output "=== 테스트 결과 ==="
    output "총 테스트: $TEST_COUNT회"
    output "통과: $PASS_COUNT회"
    output "실패: $FAIL_COUNT회"
    output "성공률: $(( (PASS_COUNT * 100) / TEST_COUNT ))%"
    output ""
    
    if [ $FAIL_COUNT -eq 0 ]; then
        output "✅ 모든 테스트 통과! 배포 준비 완료"
        output ""
        output "다음 단계:"
        output "1. 배포 스크립트 실행"
        output "2. 애플리케이션 배포"
        output "3. 최종 검증"
    else
        output "⚠️  일부 테스트 실패. 배포 전 확인 필요"
        output ""
        output "실패 항목:"
        output "- 위의 ❌ 표시된 항목 확인"
    fi
    
    output ""
    output "저장 파일: $OUTPUT_FILE"
}

################################################################################
# 메인 함수
################################################################################

main() {
    output "╔════════════════════════════════════════════════════════════════════════════╗"
    output "║                    10회 검증 테스트 시작                                   ║"
    output "║                    작성일: $(date '+%Y-%m-%d %H:%M:%S')                      ║"
    output "╚════════════════════════════════════════════════════════════════════════════╝"
    output ""
    
    # 각 테스트 실행
    test_1_system_info
    test_2_docker
    test_3_nodejs
    test_4_nginx
    test_5_mariadb
    test_6_redis
    test_7_ports
    test_8_security
    test_9_http_response
    test_10_database_connection
    
    # 최종 결과
    final_summary
}

# 스크립트 실행
main "$@"

exit 0
