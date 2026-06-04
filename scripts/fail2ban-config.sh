#!/bin/bash

# Official Docs: https://www.fail2ban.org/wiki/index.php/Main_Page
# Official GitHub: https://github.com/fail2ban/fail2ban
# Version: fail2ban 1.0+ (Ubuntu 24.04 LTS)
# Regex Validation: ^[0-9]+\.[0-9]+\.[0-9]+$
# AI Assistant: Auto-generated based on official documentation

set -e

echo "🛡️ fail2ban 설정 시작..."

# fail2ban 설치 확인
if ! command -v fail2ban-client &> /dev/null; then
    echo "📦 fail2ban 설치 중..."
    apt-get update
    apt-get install -y fail2ban
    echo "✅ fail2ban 설치 완료"
fi

# fail2ban 설정 디렉토리
FAIL2BAN_DIR="/etc/fail2ban"
JAIL_LOCAL="$FAIL2BAN_DIR/jail.local"
FILTER_DIR="$FAIL2BAN_DIR/filter.d"
ACTION_DIR="$FAIL2BAN_DIR/action.d"

# 백업 생성
if [ -f "$JAIL_LOCAL" ]; then
    cp "$JAIL_LOCAL" "$JAIL_LOCAL.backup.$(date +%Y%m%d_%H%M%S)"
    echo "✅ fail2ban 설정 파일 백업 완료"
fi

# ─── jail.local 설정 ──────────────────────────────────────────────────────────
echo "📝 jail.local 설정 중..."

cat > "$JAIL_LOCAL" << 'EOF'
# Official Docs: https://www.fail2ban.org/wiki/index.php/Main_Page
# fail2ban 설정 (2026-06-01)

[DEFAULT]
# ─── 기본 설정 ───────────────────────────────────────────────────────────────
# 무시할 IP 주소 (로컬호스트)
ignoreip = 127.0.0.1/8 ::1

# 차단 시간 (초) - 1시간
bantime = 3600

# 찾기 시간 (초) - 10분
findtime = 600

# 최대 시도 횟수
maxretry = 5

# 이메일 알림 (선택사항)
destemail = root@localhost
sendername = Fail2Ban
action = %(action_mwl)s

# ─── SSH 필터 ─────────────────────────────────────────────────────────────────
[sshd]
enabled = true
port = ssh
filter = sshd
logpath = /var/log/auth.log
maxretry = 3
bantime = 3600
findtime = 600

# ─── SSH 공격 필터 ────────────────────────────────────────────────────────────
[sshd-ddos]
enabled = true
port = ssh
filter = sshd-ddos
logpath = /var/log/auth.log
maxretry = 10
bantime = 600
findtime = 60

# ─── Apache2 필터 ───────────────────────────────────────────────────────────────
[apache2-http-auth]
enabled = true
port = http,https
filter = apache2-http-auth
logpath = /var/log/apache2/error.log
maxretry = 5
bantime = 3600

# ─── apache2 404 필터 ───────────────────────────────────────────────────────────
[apache2-noscript]
enabled = true
port = http,https
filter = apache2-noscript
logpath = /var/log/apache2/access.log
maxretry = 6
bantime = 3600

# ─── apache2 봇 필터 ────────────────────────────────────────────────────────────
[apache2-badbots]
enabled = true
port = http,https
filter = apache2-badbots
logpath = /var/log/apache2/access.log
maxretry = 2
bantime = 3600

# ─── apache2 요청 제한 ──────────────────────────────────────────────────────────
[apache2-overflows]
enabled = true
port = http,https
filter = apache2-overflows
logpath = /var/log/apache2/error.log
maxretry = 5
bantime = 3600

# ─── MariaDB 필터 ─────────────────────────────────────────────────────────────
[mysqld-auth]
enabled = true
port = 3306
filter = mysqld-auth
logpath = /var/log/mysql/error.log
maxretry = 5
bantime = 3600

# ─── Redis 필터 ───────────────────────────────────────────────────────────────
[redis-auth]
enabled = true
port = 6379
filter = redis-auth
logpath = /var/log/redis/redis-server.log
maxretry = 5
bantime = 3600
EOF

echo "✅ jail.local 설정 완료"

# ─── SSH 필터 설정 ────────────────────────────────────────────────────────────
echo "📝 SSH 필터 설정 중..."

cat > "$FILTER_DIR/sshd-ddos.conf" << 'EOF'
# fail2ban SSH DDoS 필터
[Definition]
failregex = ^<HOST> \S+ \S+ \[\S+\] ".*" 401
ignoreregex =
EOF

echo "✅ SSH 필터 설정 완료"

# ─── fail2ban 서비스 시작 ──────────────────────────────────────────────────────
echo "🚀 fail2ban 서비스 시작..."

# fail2ban 서비스 재시작
systemctl restart fail2ban
echo "✅ fail2ban 서비스 재시작 완료"

# fail2ban 상태 확인
if systemctl is-active --quiet fail2ban; then
    echo "✅ fail2ban 서비스 실행 중"
else
    echo "❌ fail2ban 서비스 실행 실패"
    exit 1
fi

# ─── 자동 시작 설정 ───────────────────────────────────────────────────────────
echo "⚙️ 자동 시작 설정..."

systemctl enable fail2ban
echo "✅ fail2ban 자동 시작 활성화"

# ─── 상태 확인 ────────────────────────────────────────────────────────────────
echo "📊 fail2ban 상태 확인..."

# fail2ban 상태 출력
fail2ban-client status
echo "✅ fail2ban 상태 확인 완료"

# ─── 필터 확인 ────────────────────────────────────────────────────────────────
echo "📋 fail2ban 필터 확인..."

# SSH 필터 상태
fail2ban-client status sshd
echo "✅ SSH 필터 상태 확인 완료"

echo "🎉 fail2ban 설정 완료!"
echo ""
echo "📊 설정 요약:"
echo "  - SSH 필터: 활성화 (최대 시도 3회, 차단 시간 1시간)"
echo "  - Apache2 필터: 활성화 (여러 필터)"
echo "  - MariaDB 필터: 활성화"
echo "  - Redis 필터: 활성화"
echo ""
echo "💡 추가 명령어:"
echo "  - 상태 확인: fail2ban-client status"
echo "  - 필터 상태: fail2ban-client status <filter>"
echo "  - 차단 IP 확인: fail2ban-client get <filter> banip"
echo "  - 차단 해제: fail2ban-client set <filter> unbanip <ip>"
echo "  - 로그 확인: tail -f /var/log/fail2ban.log"
