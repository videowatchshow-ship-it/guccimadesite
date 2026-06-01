#!/usr/bin/env python3
# Official Docs: https://docs.paramiko.org/en/stable/api/client.html
# Version: paramiko 5.0.0 (2026-05-10)
# Purpose: 서버 코드 백업 및 로컬 동기화

import paramiko
import os
import sys

def backup_server_code():
    """서버 /var/www/gucci-yanonlja-net 전체 코드 백업"""
    hostname = "76.13.218.129"
    username = "root"
    password = "q+7m#GElqQs/E&tfabwB"
    
    client = paramiko.SSHClient()
    client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    
    try:
        print("=" * 80)
        print("📦 서버 코드 백업 및 로컬 동기화")
        print("=" * 80)
        
        client.connect(hostname, 22, username, password, timeout=10, allow_agent=False, look_for_keys=False)
        print("[✓] VPS 접속 성공\n")
        
        # Step 1: 서버 전체 파일 목록 확인
        print("=" * 80)
        print("Step 1️⃣  서버 전체 파일 목록 확인")
        print("=" * 80)
        
        stdin, stdout, stderr = client.exec_command("find /var/www/gucci-yanonlja-net -type f -not -path '*/\.*' | sort")
        files = stdout.read().decode("utf-8").strip().split('\n')
        
        print(f"[*] 총 {len(files)}개 파일 발견\n")
        for f in files[:20]:
            print(f"    {f}")
        if len(files) > 20:
            print(f"    ... 외 {len(files) - 20}개 파일")
        
        # Step 2: 서버 디렉토리 구조 확인
        print("\n" + "=" * 80)
        print("Step 2️⃣  서버 디렉토리 구조")
        print("=" * 80)
        
        stdin, stdout, stderr = client.exec_command("tree -L 3 /var/www/gucci-yanonlja-net 2>/dev/null || find /var/www/gucci-yanonlja-net -type d | head -20")
        output = stdout.read().decode("utf-8").strip()
        print(output)
        
        # Step 3: 주요 파일 내용 확인
        print("\n" + "=" * 80)
        print("Step 3️⃣  주요 파일 내용 확인")
        print("=" * 80)
        
        # composer.json
        print("\n[*] composer.json:")
        stdin, stdout, stderr = client.exec_command("cat /var/www/gucci-yanonlja-net/composer.json 2>/dev/null | head -30")
        output = stdout.read().decode("utf-8").strip()
        print(output if output else "[!] 파일 없음")
        
        # .env
        print("\n[*] .env (민감 정보 제외):")
        stdin, stdout, stderr = client.exec_command("head -5 /var/www/gucci-yanonlja-net/.env 2>/dev/null")
        output = stdout.read().decode("utf-8").strip()
        print(output if output else "[!] 파일 없음")
        
        # nginx 설정
        print("\n[*] nginx 설정 (/etc/nginx/sites-available/gucci-yanonlja-net):")
        stdin, stdout, stderr = client.exec_command("cat /etc/nginx/sites-available/gucci-yanonlja-net 2>/dev/null | head -30")
        output = stdout.read().decode("utf-8").strip()
        print(output if output else "[!] 파일 없음")
        
        # Step 4: 서버 코드 압축
        print("\n" + "=" * 80)
        print("Step 4️⃣  서버 코드 압축")
        print("=" * 80)
        
        stdin, stdout, stderr = client.exec_command("cd /var/www && tar -czf gucci-yanonlja-net-backup.tar.gz gucci-yanonlja-net/ && ls -lh gucci-yanonlja-net-backup.tar.gz")
        output = stdout.read().decode("utf-8").strip()
        print(output)
        
        # Step 5: 로컬에 다운로드 (SFTP)
        print("\n" + "=" * 80)
        print("Step 5️⃣  로컬에 다운로드 (SFTP)")
        print("=" * 80)
        
        sftp = client.open_sftp()
        remote_file = "/var/www/gucci-yanonlja-net-backup.tar.gz"
        local_file = "f:\\youtubeautoid\\backups\\gucci-yanonlja-net-backup.tar.gz"
        
        # 로컬 백업 디렉토리 생성
        os.makedirs("f:\\youtubeautoid\\backups", exist_ok=True)
        
        print(f"[*] 다운로드 중: {remote_file} → {local_file}")
        sftp.get(remote_file, local_file)
        print(f"[✓] 다운로드 완료: {os.path.getsize(local_file)} bytes")
        
        sftp.close()
        
        # Step 6: 최종 요약
        print("\n" + "=" * 80)
        print("✅ 백업 완료")
        print("=" * 80)
        print(f"""
📌 백업 정보:
   - 원본: /var/www/gucci-yanonlja-net
   - 압축: /var/www/gucci-yanonlja-net-backup.tar.gz
   - 로컬: {local_file}
   - 크기: {os.path.getsize(local_file) / 1024 / 1024:.2f} MB

📌 다음 단계:
   1. 로컬에서 압축 해제
   2. 서버 코드 구조 분석
   3. GitHub에 동기화
   4. 로컬 추가 수정
        """)
        
        client.close()
        
    except Exception as e:
        print(f"[✗] 오류: {e}")
        sys.exit(1)

if __name__ == "__main__":
    backup_server_code()
