#!/bin/bash

# Official Docs: https://wiki.ubuntu.com/UncomplicatedFirewall
# Official GitHub: https://github.com/ubuntu/ufw
# Version: UFW 0.36+ (Ubuntu 24.04 LTS)
# Regex Validation: ^[0-9]+\.[0-9]+$
# AI Assistant: Auto-generated based on official documentation

set -e

echo "🔥 UFW 방화벽 설정 시작..."

# UFW 설치 확인
if ! command -v ufw &> /dev/null; then
    echo "📦 UFW 설치 중..."
    apt-get update
    apt-get install -y ufw
    echo "✅ UFW 설치 완료"
fi

# UFW 초기화 (기존 규칙 제거)
echo "🔄 UFW 초기화 중..."
ufw --force reset
echo "✅ UFW 초기화 완료"

# ─── 기본 정책 설정 ───────────────────────────────────────────────────────────
echo "📋 기본 정책 설정..."

# 들어오는 트래픽 거부 (기본값)
ufw default deny incoming
echo "✅ 들어오는 트래픽 기본 거부"

# 나가는 트래픽 허용 (기본값)
ufw default allow outgoing
echo "✅ 나가는 트래픽 기본 허용"

# ─── 필수 포트 허용 ───────────────────────────────────────────────────────────
echo "🔓 필수 포트 허용..."

# SSH (포트 22)
ufw allow 22/tcp
echo "✅ SSH (포트 22) 허용"

# HTTP (포트 80)
ufw allow 80/tcp
echo "✅ HTTP (포트 80) 허용"

# HTTPS (포트 443)
ufw allow 443/tcp
echo "✅ HTTPS (포트 443) 허용"

# RTMP (포트 1935) - 스트리밍용
ufw allow 1935/tcp
echo "✅ RTMP (포트 1935) 허용"

# DNS (포트 53) - 로컬 DNS 쿼리용
ufw allow 53/tcp
ufw allow 53/udp
echo "✅ DNS (포트 53) 허용"

# ─── 선택적 포트 ──────────────────────────────────────────────────────────────
# NTP (포트 123) - 시간 동기화
ufw allow 123/udp
echo "✅ NTP (포트 123) 허용"

# ─── 로깅 설정 ────────────────────────────────────────────────────────────────
echo "📝 로깅 설정..."

# 로깅 활성화
ufw logging on
echo "✅ UFW 로깅 활성화"

# 로깅 레벨 설정 (low, medium, high)
ufw logging medium
echo "✅ UFW 로깅 레벨: medium"

# ─── UFW 활성화 ───────────────────────────────────────────────────────────────
echo "🚀 UFW 활성화..."

# UFW 활성화 (yes 자동 응답)
echo "y" | ufw enable
echo "✅ UFW 활성화 완료"

# ─── 상태 확인 ────────────────────────────────────────────────────────────────
echo "📊 UFW 상태 확인..."

# UFW 상태 출력
ufw status verbose
echo "✅ UFW 상태 확인 완료"

# ─── 규칙 확인 ────────────────────────────────────────────────────────────────
echo "📋 UFW 규칙 확인..."

# 규칙 번호 포함 출력
ufw status numbered
echo "✅ UFW 규칙 확인 완료"

# ─── 자동 시작 설정 ───────────────────────────────────────────────────────────
echo "⚙️ 자동 시작 설정..."

# systemd 활성화
systemctl enable ufw
echo "✅ UFW 자동 시작 활성화"

echo "🎉 UFW 방화벽 설정 완료!"
echo ""
echo "📊 설정 요약:"
echo "  - 기본 정책: 들어오는 트래픽 거부, 나가는 트래픽 허용"
echo "  - 허용 포트: 22 (SSH), 80 (HTTP), 443 (HTTPS), 1935 (RTMP), 53 (DNS), 123 (NTP)"
echo "  - 로깅: 활성화 (medium 레벨)"
echo "  - 상태: 활성화"
echo ""
echo "💡 추가 명령어:"
echo "  - 상태 확인: ufw status"
echo "  - 규칙 추가: ufw allow <port>"
echo "  - 규칙 삭제: ufw delete allow <port>"
echo "  - 로그 확인: tail -f /var/log/ufw.log"
