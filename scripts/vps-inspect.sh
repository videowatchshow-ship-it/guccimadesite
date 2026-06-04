#!/bin/bash

################################################################################
# VPS 서버 상태 조사 스크립트
# 공식 문서: https://github.com/hostinger/api-cli
# 공식 API: https://developers.hostinger.com
# 버전: v1.0.0 (2026-06-01)
# 정규식 검증: ^[0-9]+\.[0-9]+\.[0-9]+$
################################################################################

set -e

# 색상 정의
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# 정규식 정의
VERSION_REGEX="^[0-9]+\.[0-9]+\.[0-9]+$"
PORT_REGEX="^[0-9]{4,5}$"
PATH_REGEX="^/[a-zA-Z0-9/_.-]+$"
HOSTNAME_REGEX="^[a-zA-Z0-9.-]+$"
IP_REGEX="^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$"

# 출력 파일
OUTPUT_FILE="vps-server-status-$(date +%Y%m%d-%H%M%S).txt"

# 출력 함수
output() {
    echo "$1" | tee -a "$OUTPUT_FILE"
}

################################################################################
# 1. VPS 기본 정보
################################################################################

output "╔════════════════════════════════════════════════════════════════════════════╗"
output "║                    VPS 서버 상태 조사                                     ║"
output "║                    공식 문서: https://github.com/hostinger/api-cli        ║"
output "║                    작성일: $(date '+%Y-%m-%d %H:%M:%S')                      ║"
output "╚════════════════════════════════════════════════════════════════════════════╝"
output ""

output "=== 1. VPS 기본 정보 ==="
output "호스트명: srv1636789.hstgr.cloud"
output "IP 주소: 76.13.218.129"
output "SSH 포트: 22"
output "사용자명: root"
output "OS: Ubuntu 24.04 LTS"
output "CPU: 1 Core"
output "메모리: 4 GB"
output "디스크: 50 GB"
output "위치: Malaysia - Kuala Lumpur"
output "만료일: 2026-06-02"
output ""

################################################################################
# 2. 현재 디렉토리
################################################################################

output "=== 2. 현재 디렉토리 ==="
CURRENT_DIR=$(pwd)
if [[ $CURRENT_DIR =~ $PATH_REGEX ]]; then
    output "✅ 경로 검증 통과: $CURRENT_DIR"
else
    output "❌ 경로 검증 실패: $CURRENT_DIR"
fi
output ""

################################################################################
# 3. 시스템 정보
################################################################################

output "=== 3. 시스템 정보 ==="
output "$(uname -a)"
output ""
output "OS 정보:"
output "$(cat /etc/os-release 2>/dev/null || echo 'OS 정보 없음')"
output ""

################################################################################
# 4. 디스크 상태
################################################################################

output "=== 4. 디스크 상태 ==="
output "$(df -h)"
output ""

################################################################################
# 5. 메모리 상태
################################################################################

output "=== 5. 메모리 상태 ==="
output "$(free -h)"
output ""

################################################################################
# 6. CPU 정보
################################################################################

output "=== 6. CPU 정보 ==="
CPU_CORES=$(nproc)
output "CPU 코어 수: $CPU_CORES"
output "CPU 모델:"
output "$(cat /proc/cpuinfo | grep "model name" | head -1)"
output ""

################################################################################
# 7. 설치된 소프트웨어 (공식 문서 기준)
################################################################################

output "=== 7. 설치된 소프트웨어 ==="

# Docker (공식 문서: https://docs.docker.com/engine/install/ubuntu/)
if command -v docker &> /dev/null; then
    DOCKER_VERSION=$(docker --version | grep -oP '[0-9]+\.[0-9]+\.[0-9]+' | head -1)
    if [[ $DOCKER_VERSION =~ $VERSION_REGEX ]]; then
        output "✅ Docker: $DOCKER_VERSION (검증 통과)"
    else
        output "⚠️  Docker: $DOCKER_VERSION (버전 형식 확인 필요)"
    fi
else
    output "❌ Docker: NOT INSTALLED"
fi

# Node.js (공식 문서: https://nodejs.org/en/docs)
if command -v node &> /dev/null; then
    NODE_VERSION=$(node --version | sed 's/v//')
    if [[ $NODE_VERSION =~ $VERSION_REGEX ]]; then
        output "✅ Node.js: $NODE_VERSION (검증 통과)"
    else
        output "⚠️  Node.js: $NODE_VERSION (버전 형식 확인 필요)"
    fi
else
    output "❌ Node.js: NOT INSTALLED"
fi

# npm (공식 문서: https://docs.npmjs.com/)
if command -v npm &> /dev/null; then
    NPM_VERSION=$(npm --version)
    if [[ $NPM_VERSION =~ $VERSION_REGEX ]]; then
        output "✅ npm: $NPM_VERSION (검증 통과)"
    else
        output "⚠️  npm: $NPM_VERSION (버전 형식 확인 필요)"
    fi
else
    output "❌ npm: NOT INSTALLED"
fi

# apache2 (공식 문서: https://apache2.org/en/docs/)
if command -v apache2 &> /dev/null; then
    Apache2_VERSION=$(apache2 -v 2>&1 | grep -oP '[0-9]+\.[0-9]+\.[0-9]+' | head -1)
    if [[ $Apache2_VERSION =~ $VERSION_REGEX ]]; then
        output "✅ apache2: $Apache2_VERSION (검증 통과)"
    else
        output "⚠️  apache2: $Apache2_VERSION (버전 형식 확인 필요)"
    fi
else
    output "❌ apache2: NOT INSTALLED"
fi

# MariaDB (공식 문서: https://mariadb.com/docs/)
if command -v mysql &> /dev/null; then
    MARIADB_VERSION=$(mysql --version | grep -oP '[0-9]+\.[0-9]+\.[0-9]+' | head -1)
    if [[ $MARIADB_VERSION =~ $VERSION_REGEX ]]; then
        output "✅ MariaDB: $MARIADB_VERSION (검증 통과)"
    else
        output "⚠️  MariaDB: $MARIADB_VERSION (버전 형식 확인 필요)"
    fi
else
    output "❌ MariaDB: NOT INSTALLED"
fi

# Redis (공식 문서: https://redis.io/docs/)
if command -v redis-cli &> /dev/null; then
    REDIS_VERSION=$(redis-cli --version | grep -oP '[0-9]+\.[0-9]+\.[0-9]+' | head -1)
    if [[ $REDIS_VERSION =~ $VERSION_REGEX ]]; then
        output "✅ Redis: $REDIS_VERSION (검증 통과)"
    else
        output "⚠️  Redis: $REDIS_VERSION (버전 형식 확인 필요)"
    fi
else
    output "❌ Redis: NOT INSTALLED"
fi

# Git (공식 문서: https://git-scm.com/doc)
if command -v git &> /dev/null; then
    GIT_VERSION=$(git --version | grep -oP '[0-9]+\.[0-9]+\.[0-9]+' | head -1)
    if [[ $GIT_VERSION =~ $VERSION_REGEX ]]; then
        output "✅ Git: $GIT_VERSION (검증 통과)"
    else
        output "⚠️  Git: $GIT_VERSION (버전 형식 확인 필요)"
    fi
else
    output "❌ Git: NOT INSTALLED"
fi

# Python3 (공식 문서: https://www.python.org/doc/)
if command -v python3 &> /dev/null; then
    PYTHON_VERSION=$(python3 --version | grep -oP '[0-9]+\.[0-9]+\.[0-9]+' | head -1)
    if [[ $PYTHON_VERSION =~ $VERSION_REGEX ]]; then
        output "✅ Python3: $PYTHON_VERSION (검증 통과)"
    else
        output "⚠️  Python3: $PYTHON_VERSION (버전 형식 확인 필요)"
    fi
else
    output "❌ Python3: NOT INSTALLED"
fi

output ""

################################################################################
# 8. 실행 중인 프로세스
################################################################################

output "=== 8. 실행 중인 프로세스 (상위 20개) ==="
output "$(ps aux | head -20)"
output ""

################################################################################
# 9. 열린 포트
################################################################################

output "=== 9. 열린 포트 ==="
if command -v netstat &> /dev/null; then
    output "$(netstat -tlnp 2>/dev/null || echo 'netstat 실행 불가')"
elif command -v ss &> /dev/null; then
    output "$(ss -tlnp)"
else
    output "포트 확인 도구 없음"
fi
output ""

################################################################################
# 10. 루트 디렉토리
################################################################################

output "=== 10. 루트 디렉토리 (/root) ==="
output "$(ls -la /root 2>/dev/null || echo '접근 불가')"
output ""

################################################################################
# 11. 홈 디렉토리
################################################################################

output "=== 11. 홈 디렉토리 (~) ==="
output "$(ls -la ~ 2>/dev/null || echo '접근 불가')"
output ""

################################################################################
# 12. 현재 사용자
################################################################################

output "=== 12. 현재 사용자 ==="
CURRENT_USER=$(whoami)
output "사용자명: $CURRENT_USER"
output "사용자 정보:"
output "$(id)"
output ""

################################################################################
# 13. 환경 변수
################################################################################

output "=== 13. 환경 변수 ==="
output "$(env | grep -E "PATH|HOME|USER|SHELL" || echo '환경 변수 없음')"
output ""

################################################################################
# 14. 네트워크 정보
################################################################################

output "=== 14. 네트워크 정보 ==="
if command -v ip &> /dev/null; then
    output "$(ip addr show)"
elif command -v ifconfig &> /dev/null; then
    output "$(ifconfig)"
else
    output "네트워크 정보 도구 없음"
fi
output ""

################################################################################
# 15. 방화벽 상태
################################################################################

output "=== 15. 방화벽 상태 ==="
if command -v ufw &> /dev/null; then
    output "$(sudo ufw status 2>/dev/null || echo 'UFW 상태 확인 불가')"
else
    output "UFW: NOT INSTALLED"
fi
output ""

################################################################################
# 16. Docker 컨테이너
################################################################################

output "=== 16. Docker 컨테이너 ==="
if command -v docker &> /dev/null; then
    output "$(docker ps -a 2>/dev/null || echo 'Docker 컨테이너 없음')"
else
    output "Docker가 설치되지 않았습니다"
fi
output ""

################################################################################
# 17. Docker Compose
################################################################################

output "=== 17. Docker Compose ==="
if docker compose version &> /dev/null; then
    DOCKER_COMPOSE_VERSION=$(docker compose version 2>/dev/null | grep -oP '[0-9]+\.[0-9]+\.[0-9]+' | head -1)
    if [[ $DOCKER_COMPOSE_VERSION =~ $VERSION_REGEX ]]; then
        output "✅ Docker Compose: $DOCKER_COMPOSE_VERSION (검증 통과)"
    else
        output "⚠️  Docker Compose: $DOCKER_COMPOSE_VERSION (버전 형식 확인 필요)"
    fi
    
    if [ -f "docker-compose.yml" ]; then
        output "docker-compose.yml 파일 존재"
        output "$(docker compose ps 2>/dev/null || echo 'docker compose 실행 불가')"
    else
        output "docker-compose.yml 파일 없음"
    fi
else
    output "Docker Compose: NOT INSTALLED"
fi
output ""

################################################################################
# 18. 현재 디렉토리 구조
################################################################################

output "=== 18. 현재 디렉토리 구조 ==="
output "$(pwd)"
output "$(ls -la)"
output ""

################################################################################
# 19. 시스템 로그
################################################################################

output "=== 19. 시스템 로그 (최근 20줄) ==="
output "$(tail -20 /var/log/syslog 2>/dev/null || echo '로그 파일 없음')"
output ""

################################################################################
# 완료
################################################################################

output "╔════════════════════════════════════════════════════════════════════════════╗"
output "║                    VPS 서버 상태 조사 완료                                ║"
output "║                    완료 시간: $(date '+%Y-%m-%d %H:%M:%S')                      ║"
output "║                    저장 파일: $OUTPUT_FILE                    ║"
output "╚════════════════════════════════════════════════════════════════════════════╝"
output ""

# 파일 저장 확인
if [ -f "$OUTPUT_FILE" ]; then
    echo ""
    echo -e "${GREEN}✅ VPS 서버 상태 조사 완료!${NC}"
    echo -e "${GREEN}📁 저장 파일: $OUTPUT_FILE${NC}"
    echo ""
    echo "파일 내용 확인:"
    echo "cat $OUTPUT_FILE"
else
    echo -e "${RED}❌ 파일 저장 실패${NC}"
fi

exit 0
