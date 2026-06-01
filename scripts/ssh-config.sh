#!/bin/bash

# Official Docs: https://man.openbsd.org/ssh_config
# Official GitHub: https://github.com/openssh/openssh-portable
# Version: OpenSSH 9.x (Ubuntu 24.04 LTS)
# Regex Validation: ^[0-9]+\.[0-9]+[a-z]?$
# AI Assistant: Auto-generated based on official documentation

set -e

echo "🔐 SSH 보안 설정 시작..."

# SSH 설정 파일 경로
SSH_CONFIG="/etc/ssh/sshd_config"
SSH_CONFIG_D="/etc/ssh/sshd_config.d"

# 백업 생성
if [ -f "$SSH_CONFIG" ]; then
    cp "$SSH_CONFIG" "$SSH_CONFIG.backup.$(date +%Y%m%d_%H%M%S)"
    echo "✅ SSH 설정 파일 백업 완료"
fi

# SSH 설정 디렉토리 생성
mkdir -p "$SSH_CONFIG_D"

# SSH 보안 설정 파일 생성
cat > "$SSH_CONFIG_D/99-security.conf" << 'EOF'
# Official Docs: https://man.openbsd.org/ssh_config
# SSH 보안 설정 (2026-06-01)

# ─── 포트 설정 ───────────────────────────────────────────────────────────────
Port 22

# ─── 인증 설정 ───────────────────────────────────────────────────────────────
# Root 로그인 비활성화 (보안)
PermitRootLogin no

# 비밀번호 인증 비활성화 (공개 키 인증만 사용)
PasswordAuthentication no

# 공개 키 인증 활성화
PubkeyAuthentication yes

# 빈 비밀번호 로그인 비활성화
PermitEmptyPasswords no

# ─── 보안 설정 ───────────────────────────────────────────────────────────────
# 프로토콜 버전 (SSH 2만 사용)
Protocol 2

# 호스트 키 알고리즘 (강력한 알고리즘만 사용)
HostKeyAlgorithms ssh-ed25519,rsa-sha2-512,rsa-sha2-256

# 키 교환 알고리즘 (강력한 알고리즘만 사용)
KexAlgorithms curve25519-sha256,curve25519-sha256@libssh.org,diffie-hellman-group-exchange-sha256

# 암호화 알고리즘 (강력한 알고리즘만 사용)
Ciphers chacha20-poly1305@openssh.com,aes256-gcm@openssh.com,aes128-gcm@openssh.com,aes256-ctr,aes192-ctr,aes128-ctr

# MAC 알고리즘 (강력한 알고리즘만 사용)
MACs hmac-sha2-512-etm@openssh.com,hmac-sha2-256-etm@openssh.com,hmac-sha2-512,hmac-sha2-256

# ─── 로그인 시도 제한 ─────────────────────────────────────────────────────────
# 최대 인증 시도 횟수
MaxAuthTries 3

# 최대 세션 수
MaxSessions 10

# 로그인 유휴 시간 제한 (초)
ClientAliveInterval 300
ClientAliveCountMax 2

# ─── 기타 보안 설정 ───────────────────────────────────────────────────────────
# X11 포워딩 비활성화
X11Forwarding no

# TCP 포워딩 비활성화
AllowTcpForwarding no

# 에이전트 포워딩 비활성화
AllowAgentForwarding no

# 환경 변수 전달 비활성화
PermitUserEnvironment no

# 압축 비활성화
Compression no

# 로깅 레벨
SyslogFacility AUTH
LogLevel VERBOSE

# ─── 접근 제어 ───────────────────────────────────────────────────────────────
# 특정 사용자만 허용 (필요시 수정)
# AllowUsers deployment root

# 특정 그룹만 허용 (필요시 수정)
# AllowGroups ssh-users

# ─── 배너 ─────────────────────────────────────────────────────────────────────
# 배너 파일 (선택사항)
# Banner /etc/ssh/banner.txt
EOF

echo "✅ SSH 보안 설정 파일 생성 완료: $SSH_CONFIG_D/99-security.conf"

# SSH 설정 검증
if sshd -t; then
    echo "✅ SSH 설정 검증 성공"
else
    echo "❌ SSH 설정 검증 실패"
    exit 1
fi

# SSH 서비스 재시작
systemctl restart ssh || systemctl restart sshd
echo "✅ SSH 서비스 재시작 완료"

# SSH 상태 확인
if systemctl is-active --quiet ssh || systemctl is-active --quiet sshd; then
    echo "✅ SSH 서비스 실행 중"
else
    echo "❌ SSH 서비스 실행 실패"
    exit 1
fi

echo "🎉 SSH 보안 설정 완료!"
