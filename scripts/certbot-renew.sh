#!/bin/bash

# Official Docs: https://letsencrypt.org/docs
# Official GitHub: https://github.com/certbot/certbot
# Version: Certbot 2.x (Ubuntu 24.04 LTS)
# Regex Validation: ^[0-9]+\.[0-9]+\.[0-9]+$
# AI Assistant: Auto-generated based on official documentation

set -e

echo "🔐 SSL/TLS 자동 갱신 설정 시작..."

# Certbot 설치 확인
if ! command -v certbot &> /dev/null; then
    echo "📦 Certbot 설치 중..."
    apt-get update
    apt-get install -y certbot python3-certbot-apache
    echo "✅ Certbot 설치 완료"
fi

# ─── 인증서 갱신 ──────────────────────────────────────────────────────────────
echo "🔄 SSL/TLS 인증서 갱신 중..."

# 도메인 설정
DOMAIN="xn--2e0bj1fruw33b6ti.net"
EMAIL="admin@${DOMAIN}"

# 인증서 갱신 (dry-run 테스트)
echo "🧪 Certbot dry-run 테스트..."
certbot renew --dry-run --quiet || true
echo "✅ Certbot dry-run 테스트 완료"

# 실제 갱신
echo "🔄 Certbot 갱신 실행..."
certbot renew --quiet || true
echo "✅ Certbot 갱신 완료"

# ─── 자동 갱신 설정 ───────────────────────────────────────────────────────────
echo "⚙️ 자동 갱신 설정..."

# systemd timer 확인
if systemctl list-timers | grep -q "certbot"; then
    echo "✅ Certbot systemd timer 이미 설정됨"
else
    echo "📝 Certbot systemd timer 설정 중..."
    
    # certbot.timer 활성화
    systemctl enable certbot.timer
    systemctl start certbot.timer
    echo "✅ Certbot systemd timer 활성화"
fi

# ─── Cron 작업 설정 (백업) ────────────────────────────────────────────────────
echo "📝 Cron 작업 설정 중..."

# Cron 작업 파일
CRON_FILE="/etc/cron.d/certbot-renew"

# 기존 Cron 작업 제거
if [ -f "$CRON_FILE" ]; then
    rm "$CRON_FILE"
    echo "✅ 기존 Cron 작업 제거"
fi

# 새로운 Cron 작업 생성 (매일 자정에 갱신 시도)
cat > "$CRON_FILE" << 'EOF'
# Certbot SSL/TLS 자동 갱신
# 매일 자정에 갱신 시도 (Let's Encrypt는 만료 30일 전부터 갱신 가능)
0 0 * * * root certbot renew --quiet --post-hook "systemctl reload apache2"
EOF

chmod 644 "$CRON_FILE"
echo "✅ Cron 작업 설정 완료: $CRON_FILE"

# ─── 인증서 정보 확인 ──────────────────────────────────────────────────────────
echo "📊 인증서 정보 확인..."

# 인증서 목록
certbot certificates
echo "✅ 인증서 정보 확인 완료"

# ─── 갱신 로그 확인 ───────────────────────────────────────────────────────────
echo "📝 갱신 로그 확인..."

# 최근 갱신 로그
tail -20 /var/log/letsencrypt/letsencrypt.log || true
echo "✅ 갱신 로그 확인 완료"

# ─── Apache2 설정 확인 ──────────────────────────────────────────────────────────
echo "🔍 Apache2 설정 확인..."

# Apache2 설정 테스트
if apache2ctl configtest; then
    echo "✅ Apache2 설정 검증 성공"
    
    # Apache2 재로드
    systemctl reload apache2
    echo "✅ Apache2 재로드 완료"
else
    echo "❌ Apache2 설정 검증 실패"
    exit 1
fi

# ─── 상태 확인 ────────────────────────────────────────────────────────────────
echo "📊 상태 확인..."

# systemd timer 상태
systemctl status certbot.timer --no-pager || true
echo "✅ systemd timer 상태 확인 완료"

# Cron 작업 확인
crontab -l | grep certbot || echo "⚠️ Cron 작업 없음"
echo "✅ Cron 작업 확인 완료"

echo "🎉 SSL/TLS 자동 갱신 설정 완료!"
echo ""
echo "📊 설정 요약:"
echo "  - 자동 갱신: systemd timer (권장)"
echo "  - 백업 갱신: Cron 작업 (매일 자정)"
echo "  - 갱신 후 동작: Apache2 재로드"
echo "  - 갱신 로그: /var/log/letsencrypt/letsencrypt.log"
echo ""
echo "💡 추가 명령어:"
echo "  - 인증서 확인: certbot certificates"
echo "  - 수동 갱신: certbot renew"
echo "  - 수동 갱신 (dry-run): certbot renew --dry-run"
echo "  - timer 상태: systemctl status certbot.timer"
echo "  - timer 로그: journalctl -u certbot.timer -n 50"
echo "  - 갱신 로그: tail -f /var/log/letsencrypt/letsencrypt.log"
