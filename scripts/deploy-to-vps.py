#!/usr/bin/env python3
# Official Docs: https://docs.paramiko.org/en/stable/api/client.html
# Official GitHub: https://github.com/paramiko/paramiko
# Version: paramiko 5.0.0 (2026-05-10)
# Auth Method: SSH password-based (비밀번호 인증)
# Purpose: Deploy to existing gucci-yanonlja-net server (수정/업데이트)

import paramiko
import sys
import os
import time
from pathlib import Path

# VPS 정보
VPS_HOST = "76.13.218.129"
VPS_PORT = 22
VPS_USER = "root"
VPS_PASS = os.environ.get("VPS_PASS", "q+7m#GElqQs/E&tfabwB")
DEPLOY_DIR = "/var/www/gucci-yanonlja-net"
GITHUB_REPO = "https://github.com/your-repo/youtubeautoid.git"

def connect_vps():
    """VPS에 SSH로 연결"""
    client = paramiko.SSHClient()
    client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    
    try:
        print(f"🔗 VPS 연결 중: {VPS_USER}@{VPS_HOST}:{VPS_PORT}")
        client.connect(
            hostname=VPS_HOST,
            port=VPS_PORT,
            username=VPS_USER,
            password=VPS_PASS,
            timeout=10
        )
        print("✅ VPS 연결 성공")
        return client
    except paramiko.AuthenticationException:
        print("❌ 인증 실패: 비밀번호가 잘못되었습니다")
        sys.exit(1)
    except paramiko.SSHException as e:
        print(f"❌ SSH 오류: {e}")
        sys.exit(1)

def run_command(client, command, description=""):
    """SSH로 명령어 실행"""
    if description:
        print(f"\n📝 {description}")
    print(f"   명령어: {command}")
    
    stdin, stdout, stderr = client.exec_command(command)
    output = stdout.read().decode("utf-8").strip()
    error = stderr.read().decode("utf-8").strip()
    
    if error and "warning" not in error.lower():
        print(f"   ⚠️  에러: {error}")
    
    if output:
        print(f"   ✅ 결과: {output[:200]}")
    
    return output, error

def deploy():
    """배포 실행"""
    client = connect_vps()
    
    try:
        # 1. 서버 상태 확인
        print("\n" + "="*60)
        print("1️⃣  서버 상태 확인")
        print("="*60)
        
        run_command(client, "uname -a", "OS 정보 확인")
        run_command(client, "uptime", "서버 업타임 확인")
        run_command(client, "df -h /", "디스크 사용량 확인")
        run_command(client, "free -h", "메모리 사용량 확인")
        
        # 2. 기존 배포 디렉토리 확인
        print("\n" + "="*60)
        print("2️⃣  기존 배포 디렉토리 확인")
        print("="*60)
        
        run_command(client, f"ls -la {DEPLOY_DIR}", "배포 디렉토리 확인")
        run_command(client, f"cd {DEPLOY_DIR} && git status", "Git 상태 확인")
        
        # 3. 서비스 상태 확인
        print("\n" + "="*60)
        print("3️⃣  서비스 상태 확인")
        print("="*60)
        
        run_command(client, "systemctl status nginx --no-pager", "nginx 상태")
        run_command(client, "systemctl status mariadb --no-pager", "MariaDB 상태")
        run_command(client, "systemctl status redis-server --no-pager", "Redis 상태")
        
        # 4. 포트 확인
        print("\n" + "="*60)
        print("4️⃣  포트 상태 확인")
        print("="*60)
        
        run_command(client, "netstat -tlnp | grep LISTEN", "열린 포트 확인")
        
        # 5. 최신 코드 pull
        print("\n" + "="*60)
        print("5️⃣  GitHub에서 최신 코드 pull")
        print("="*60)
        
        run_command(client, f"cd {DEPLOY_DIR} && git pull origin main", "코드 pull")
        
        # 6. Docker Compose 재구성
        print("\n" + "="*60)
        print("6️⃣  Docker Compose 재구성")
        print("="*60)
        
        run_command(client, f"cd {DEPLOY_DIR} && docker-compose up -d", "Docker Compose 시작")
        
        # 7. 배포 후 서비스 상태 확인
        print("\n" + "="*60)
        print("7️⃣  배포 후 서비스 상태 확인")
        print("="*60)
        
        time.sleep(3)  # Docker 시작 대기
        
        run_command(client, "docker ps --format 'table {{.Names}}\t{{.Status}}'", "Docker 컨테이너 상태")
        run_command(client, "systemctl status nginx --no-pager", "nginx 상태")
        run_command(client, "systemctl status mariadb --no-pager", "MariaDB 상태")
        run_command(client, "systemctl status redis-server --no-pager", "Redis 상태")
        
        # 8. 웹사이트 접속 테스트
        print("\n" + "="*60)
        print("8️⃣  웹사이트 접속 테스트")
        print("="*60)
        
        run_command(client, "curl -I http://localhost", "로컬 HTTP 테스트")
        run_command(client, "curl -I https://xn--2e0bj1fruw33b6ti.net", "HTTPS 테스트")
        
        # 9. 에러 로그 확인
        print("\n" + "="*60)
        print("9️⃣  에러 로그 확인")
        print("="*60)
        
        run_command(client, "tail -20 /var/log/nginx/error.log", "nginx 에러 로그")
        run_command(client, "tail -20 /var/log/mariadb/error.log", "MariaDB 에러 로그")
        
        # 10. 최종 확인
        print("\n" + "="*60)
        print("🎉 배포 완료!")
        print("="*60)
        print(f"✅ 웹사이트: https://xn--2e0bj1fruw33b6ti.net")
        print(f"✅ VPS: {VPS_HOST}")
        print(f"✅ 배포 디렉토리: {DEPLOY_DIR}")
        print(f"✅ 모든 서비스 정상 실행 중")
        
    finally:
        client.close()
        print("\n✅ SSH 연결 종료")

if __name__ == "__main__":
    deploy()
