#!/bin/bash

################################################################################
# VPS 서버 상태 조사 스크립트
# 공식 문서 기준: Ubuntu 24.04 LTS, Hostinger VPS
# 작성일: 2026-05-14
# 목표: 서버의 정확한 상태를 파악하고 파일로 저장
################################################################################

set -e

# 색상 정의
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# 로그 함수
log_section() {
    echo ""
    echo -e "${BLUE}=== $1 ===${NC}"
    echo ""
}

# 출력 파일 설정
OUTPUT_FILE="/tmp/vps-server-status-$(date +%Y%m%d-%H%M%S).txt"

# 출력 함수 (파일과 화면 동시)
output() {
    echo "$1" | tee -a "$OUTPUT_FILE"
}

################################################################################
# 시작
################################################################################

output "╔════════════════════════════════════════════════════════════════════════════╗"
output "║                    VPS 서버 상태 조사 시작                                ║"
output "║                    작성일: $(date '+%Y-%m-%d %H:%M:%S')                      ║"
output "╚════════════════════════════════════════════════════════════════════════════╝"
output ""

################################################################################
# 1. 현재 디렉토리
################################################################################

output "=== 1. 현재 디렉토리 ==="
output "$(pwd)"
output ""

################################################################################
# 2. 시스템 정보
################################################################################

output "=== 2. 시스템 정보 ==="
output "$(uname -a)"
output ""
output "OS 정보:"
output "$(cat /etc/os-release)"
output ""

################################################################################
# 3. 디스크 상태
################################################################################

output "=== 3. 디스크 상태 ==="
output "$(df -h)"
output ""

################################################################################
# 4. 메모리 상태
################################################################################

output "=== 4. 메모리 상태 ==="
output "$(free -h)"
output ""

################################################################################
# 5. CPU 정보
################################################################################

output "=== 5. CPU 정보 ==="
output "CPU 코어 수: $(nproc)"
output "CPU 모델:"
output "$(cat /proc/cpuinfo | grep "model name" | head -1)"
output ""

################################################################################
# 6. 설치된 소프트웨어
################################################################################

output "=== 6. 설치된 소프트웨어 ==="

# Docker
if command -v docker &> /dev/null; then
    output "✅ Docker: $(docker --version)"
else
    output "❌ Docker: NOT INSTALLED"
fi

# Node.js
if command -v node &> /dev/null; then
    output "✅ Node.js: $(node --version)"
else
    output "❌ Node.js: NOT INSTALLED"
fi

# npm
if command -v npm &> /dev/null; then
    output "✅ npm: $(npm --version)"
else
    output "❌ npm: NOT INSTALLED"
fi

# nginx
if command -v nginx &> /dev/null; then
    output "✅ nginx: $(nginx -v 2>&1)"
else
    output "❌ nginx: NOT INSTALLED"
fi

# MariaDB
if command -v mysql &> /dev/null; then
    output "✅ MariaDB: $(mysql --version)"
else
    output "❌ MariaDB: NOT INSTALLED"
fi

# Redis
if command -v redis-cli &> /dev/null; then
    output "✅ Redis: $(redis-cli --version)"
else
    output "❌ Redis: NOT INSTALLED"
fi

# Git
if command -v git &> /dev/null; then
    output "✅ Git: $(git --version)"
else
    output "❌ Git: NOT INSTALLED"
fi

# Python
if command -v python3 &> /dev/null; then
    output "✅ Python3: $(python3 --version)"
else
    output "❌ Python3: NOT INSTALLED"
fi

output ""

################################################################################
# 7. 실행 중인 프로세스
################################################################################

output "=== 7. 실행 중인 프로세스 (상위 20개) ==="
output "$(ps aux | head -20)"
output ""

################################################################################
# 8. 열린 포트
################################################################################

output "=== 8. 열린 포트 ==="
if command -v netstat &> /dev/null; then
    output "$(netstat -tlnp 2>/dev/null || echo 'netstat 실행 불가')"
elif command -v ss &> /dev/null; then
    output "$(ss -tlnp)"
else
    output "포트 확인 도구 없음"
fi
output ""

################################################################################
# 9. 루트 디렉토리
################################################################################

output "=== 9. 루트 디렉토리 (/root) ==="
output "$(ls -la /root 2>/dev/null || echo '접근 불가')"
output ""

################################################################################
# 10. 홈 디렉토리
################################################################################

output "=== 10. 홈 디렉토리 (~) ==="
output "$(ls -la ~ 2>/dev/null || echo '접근 불가')"
output ""

################################################################################
# 11. 현재 사용자
################################################################################

output "=== 11. 현재 사용자 ==="
output "사용자명: $(whoami)"
output "사용자 정보:"
output "$(id)"
output ""

################################################################################
# 12. 환경 변수
################################################################################

output "=== 12. 환경 변수 ==="
output "$(env | grep -E "PATH|HOME|USER|SHELL" || echo '환경 변수 없음')"
output ""

################################################################################
# 13. 마운트된 디렉토리
################################################################################

output "=== 13. 마운트된 디렉토리 ==="
output "$(mount | grep -E "^/dev" || echo '마운트 정보 없음')"
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
# 16. 서비스 상태
################################################################################

output "=== 16. 주요 서비스 상태 ==="

# Docker
if command -v docker &> /dev/null; then
    output "Docker: $(systemctl is-active docker 2>/dev/null || echo 'systemctl 불가')"
fi

# nginx
if command -v nginx &> /dev/null; then
    output "nginx: $(systemctl is-active nginx 2>/dev/null || echo 'systemctl 불가')"
fi

# MariaDB
if command -v mysql &> /dev/null; then
    output "MariaDB: $(systemctl is-active mariadb 2>/dev/null || echo 'systemctl 불가')"
fi

# Redis
if command -v redis-cli &> /dev/null; then
    output "Redis: $(systemctl is-active redis-server 2>/dev/null || echo 'systemctl 불가')"
fi

output ""

################################################################################
# 17. Docker 컨테이너 상태
################################################################################

output "=== 17. Docker 컨테이너 상태 ==="
if command -v docker &> /dev/null; then
    output "$(docker ps -a 2>/dev/null || echo 'Docker 컨테이너 없음')"
else
    output "Docker가 설치되지 않았습니다"
fi
output ""

################################################################################
# 18. Docker Compose 상태
################################################################################

output "=== 18. Docker Compose 상태 ==="
if command -v docker-compose &> /dev/null; then
    output "Docker Compose 버전: $(docker-compose --version)"
    if [ -f "docker-compose.yml" ]; then
        output "docker-compose.yml 파일 존재"
        output "$(docker-compose ps 2>/dev/null || echo 'docker-compose 실행 불가')"
    else
        output "docker-compose.yml 파일 없음"
    fi
else
    output "Docker Compose: NOT INSTALLED"
fi
output ""

################################################################################
# 19. 디렉토리 구조
################################################################################

output "=== 19. 현재 디렉토리 구조 ==="
output "$(pwd)"
output "$(ls -la)"
output ""

################################################################################
# 20. 시스템 로그 (최근 20줄)
################################################################################

output "=== 20. 시스템 로그 (최근 20줄) ==="
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
    echo -e "${GREEN}✅ 서버 상태 조사 완료!${NC}"
    echo -e "${GREEN}📁 저장 파일: $OUTPUT_FILE${NC}"
    echo ""
    echo "파일 내용 확인:"
    echo "cat $OUTPUT_FILE"
else
    echo -e "${RED}❌ 파일 저장 실패${NC}"
fi

exit 0
