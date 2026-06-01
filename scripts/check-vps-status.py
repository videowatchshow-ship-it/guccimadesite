#!/usr/bin/env python3
# Official Docs: https://docs.paramiko.org/en/stable/api/client.html
# Version: paramiko 5.0.0 (2026-05-10)
# Auth Method: Password-based SSH (비밀번호 인증)

import paramiko
import sys
import os

def check_vps_status(hostname="76.13.218.129", port=22, username="root", password="q+7m#GElqQs/E&tfabwB"):
    """VPS 배포 상태 확인"""
    client = paramiko.SSHClient()
    client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    
    try:
        print(f"[*] VPS 접속 시도: {username}@{hostname}:{port}")
        client.connect(
            hostname=hostname,
            port=port,
            username=username,
            password=password,
            timeout=10,
            allow_agent=False,
            look_for_keys=False
        )
        print(f"[✓] SSH 접속 성공\n")
        
        # 1. Docker 컨테이너 상태
        print("=" * 60)
        print("1️⃣  Docker 컨테이너 상태")
        print("=" * 60)
        stdin, stdout, stderr = client.exec_command("docker ps -a --format 'table {{.Names}}\t{{.Status}}\t{{.Ports}}'")
        output = stdout.read().decode("utf-8").strip()
        print(output if output else "[!] Docker 컨테이너 없음")
        
        # 2. 포트 상태
        print("\n" + "=" * 60)
        print("2️⃣  포트 상태 (80, 443, 3000, 3001)")
        print("=" * 60)
        stdin, stdout, stderr = client.exec_command("netstat -tlnp 2>/dev/null | grep -E ':(80|443|3000|3001)' || echo '[!] 포트 사용 중 없음'")
        output = stdout.read().decode("utf-8").strip()
        print(output)
        
        # 3. nginx 상태
        print("\n" + "=" * 60)
        print("3️⃣  nginx 상태")
        print("=" * 60)
        stdin, stdout, stderr = client.exec_command("systemctl status nginx 2>&1 | head -5")
        output = stdout.read().decode("utf-8").strip()
        print(output)
        
        # 4. 웹 서버 응답 테스트
        print("\n" + "=" * 60)
        print("4️⃣  웹 서버 응답 테스트")
        print("=" * 60)
        stdin, stdout, stderr = client.exec_command("curl -s -I http://localhost 2>&1 | head -3")
        output = stdout.read().decode("utf-8").strip()
        print(output if output else "[!] 응답 없음")
        
        # 5. 디렉토리 구조
        print("\n" + "=" * 60)
        print("5️⃣  배포 디렉토리 구조")
        print("=" * 60)
        stdin, stdout, stderr = client.exec_command("ls -la /var/www/ 2>/dev/null || ls -la /root/ 2>/dev/null | head -10")
        output = stdout.read().decode("utf-8").strip()
        print(output)
        
        # 6. Git 상태
        print("\n" + "=" * 60)
        print("6️⃣  Git 저장소 상태")
        print("=" * 60)
        stdin, stdout, stderr = client.exec_command("cd /var/www/guccimadesite 2>/dev/null && git status 2>&1 | head -5 || echo '[!] Git 저장소 없음'")
        output = stdout.read().decode("utf-8").strip()
        print(output)
        
        client.close()
        print("\n[✓] VPS 상태 확인 완료")
        
    except paramiko.AuthenticationException:
        print(f"[✗] 인증 실패: 비밀번호 오류")
        sys.exit(1)
    except paramiko.SSHException as e:
        print(f"[✗] SSH 오류: {e}")
        sys.exit(1)
    except Exception as e:
        print(f"[✗] 오류: {e}")
        sys.exit(1)

if __name__ == "__main__":
    check_vps_status()
