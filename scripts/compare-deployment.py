#!/usr/bin/env python3
# Official Docs: https://docs.paramiko.org/en/stable/api/client.html
# Version: paramiko 5.0.0 (2026-05-10)
# Purpose: 서버 ↔ 로컬 ↔ GitHub 비교

import paramiko
import subprocess
import sys
import json

def run_ssh_command(client, command):
    """SSH 명령어 실행"""
    stdin, stdout, stderr = client.exec_command(command)
    return stdout.read().decode("utf-8").strip()

def run_local_command(command):
    """로컬 명령어 실행"""
    try:
        result = subprocess.run(command, shell=True, capture_output=True, text=True, cwd="f:\\youtubeautoid")
        return result.stdout.strip()
    except:
        return "[오류]"

def compare_deployment():
    """서버 ↔ 로컬 ↔ GitHub 비교"""
    hostname = "76.13.218.129"
    username = "root"
    password = "q+7m#GElqQs/E&tfabwB"
    
    client = paramiko.SSHClient()
    client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    
    try:
        print("=" * 80)
        print("🔍 서버 ↔ 로컬 ↔ GitHub 비교 분석")
        print("=" * 80)
        
        client.connect(hostname, 22, username, password, timeout=10, allow_agent=False, look_for_keys=False)
        print("[✓] VPS 접속 성공\n")
        
        # 1. 서버 구조 확인
        print("=" * 80)
        print("1️⃣  서버 디렉토리 구조 (/var/www/gucci-yanonlja-net)")
        print("=" * 80)
        server_structure = run_ssh_command(client, "find /var/www/gucci-yanonlja-net -maxdepth 3 -type f -name '*.js' -o -name '*.json' -o -name 'Dockerfile' -o -name 'docker-compose*' 2>/dev/null | head -20")
        print(server_structure if server_structure else "[!] 파일 없음")
        
        # 2. 로컬 구조 확인
        print("\n" + "=" * 80)
        print("2️⃣  로컬 디렉토리 구조 (f:\\youtubeautoid)")
        print("=" * 80)
        local_structure = run_local_command("dir /s /b backend\\*.js frontend\\*.js docker\\*.yml 2>nul | findstr /v node_modules")
        print(local_structure if local_structure else "[!] 파일 없음")
        
        # 3. GitHub 커밋 확인
        print("\n" + "=" * 80)
        print("3️⃣  GitHub 최신 커밋")
        print("=" * 80)
        github_commits = run_local_command("git log --oneline -5")
        print(github_commits if github_commits else "[!] Git 저장소 없음")
        
        # 4. 서버 Git 상태
        print("\n" + "=" * 80)
        print("4️⃣  서버 Git 상태 (/var/www/gucci-yanonlja-net)")
        print("=" * 80)
        server_git = run_ssh_command(client, "cd /var/www/gucci-yanonlja-net && git log --oneline -5 2>&1")
        print(server_git if server_git else "[!] Git 저장소 없음")
        
        # 5. 서버 package.json 확인
        print("\n" + "=" * 80)
        print("5️⃣  서버 package.json 위치")
        print("=" * 80)
        server_package = run_ssh_command(client, "find /var/www/gucci-yanonlja-net -name 'package.json' -type f 2>/dev/null")
        print(server_package if server_package else "[!] package.json 없음")
        
        # 6. 서버 Dockerfile 확인
        print("\n" + "=" * 80)
        print("6️⃣  서버 Dockerfile 위치")
        print("=" * 80)
        server_dockerfile = run_ssh_command(client, "find /var/www/gucci-yanonlja-net -name 'Dockerfile' -type f 2>/dev/null")
        print(server_dockerfile if server_dockerfile else "[!] Dockerfile 없음")
        
        # 7. 서버 docker-compose.yml 확인
        print("\n" + "=" * 80)
        print("7️⃣  서버 docker-compose.yml 위치")
        print("=" * 80)
        server_compose = run_ssh_command(client, "find /var/www/gucci-yanonlja-net -name 'docker-compose.yml' -type f 2>/dev/null")
        print(server_compose if server_compose else "[!] docker-compose.yml 없음")
        
        # 8. 서버 nginx 설정
        print("\n" + "=" * 80)
        print("8️⃣  서버 nginx 설정 (/etc/nginx/sites-enabled)")
        print("=" * 80)
        server_nginx = run_ssh_command(client, "ls -la /etc/nginx/sites-enabled/ 2>/dev/null")
        print(server_nginx if server_nginx else "[!] nginx 설정 없음")
        
        # 9. 서버 환경 변수
        print("\n" + "=" * 80)
        print("9️⃣  서버 .env 파일 위치")
        print("=" * 80)
        server_env = run_ssh_command(client, "find /var/www/gucci-yanonlja-net -name '.env' -type f 2>/dev/null")
        print(server_env if server_env else "[!] .env 없음")
        
        # 10. 서버 프로세스 확인
        print("\n" + "=" * 80)
        print("🔟 서버 실행 중인 프로세스")
        print("=" * 80)
        server_processes = run_ssh_command(client, "ps aux | grep -E 'node|npm|python|gucci' | grep -v grep")
        print(server_processes if server_processes else "[!] 프로세스 없음")
        
        # 11. 서버 포트 상태
        print("\n" + "=" * 80)
        print("1️⃣1️⃣  서버 포트 상태")
        print("=" * 80)
        server_ports = run_ssh_command(client, "netstat -tlnp 2>/dev/null | grep -E ':(80|443|3000|3001|3306|6379|8080)'")
        print(server_ports if server_ports else "[!] 포트 사용 중 없음")
        
        # 12. 서버 디렉토리 크기
        print("\n" + "=" * 80)
        print("1️⃣2️⃣  서버 디렉토리 크기")
        print("=" * 80)
        server_size = run_ssh_command(client, "du -sh /var/www/gucci-yanonlja-net 2>/dev/null")
        print(server_size if server_size else "[!] 디렉토리 없음")
        
        # 13. 로컬 Git 상태
        print("\n" + "=" * 80)
        print("1️⃣3️⃣  로컬 Git 상태")
        print("=" * 80)
        local_git_status = run_local_command("git status --porcelain")
        print(local_git_status if local_git_status else "[✓] Clean (변경 없음)")
        
        # 14. 로컬 vs GitHub 비교
        print("\n" + "=" * 80)
        print("1️⃣4️⃣  로컬 vs GitHub 비교")
        print("=" * 80)
        local_vs_github = run_local_command("git diff origin/main --stat")
        print(local_vs_github if local_vs_github else "[✓] 동일 (차이 없음)")
        
        # 15. 최종 요약
        print("\n" + "=" * 80)
        print("📊 최종 요약")
        print("=" * 80)
        print("""
✅ 확인 사항:
1. 서버 /var/www/gucci-yanonlja-net 디렉토리 구조
2. 서버 Git 저장소 상태 (커밋 히스토리)
3. 서버 package.json, Dockerfile, docker-compose.yml 위치
4. 서버 nginx 설정 파일
5. 서버 .env 파일 위치
6. 서버 실행 중인 프로세스
7. 로컬 Git 상태 (변경 파일)
8. 로컬 vs GitHub 차이점

📌 다음 단계:
- 서버 코드 구조 파악
- 로컬 코드와 비교
- GitHub와 동기화 여부 확인
- 수정 필요 사항 파악
        """)
        
        client.close()
        
    except Exception as e:
        print(f"[✗] 오류: {e}")
        sys.exit(1)

if __name__ == "__main__":
    compare_deployment()
