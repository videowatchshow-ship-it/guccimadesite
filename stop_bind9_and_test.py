#!/usr/bin/env python3
"""
경로 B 진행:
1. VPS에서 BIND9 중지
2. nginx로 직접 접근 테스트
3. DNS 수정 후 Certbot 실행
"""
import paramiko
import os
from dotenv import load_dotenv

# Load .env
load_dotenv('f:\\youtubeautoid\\.env')

ssh_key_path = os.environ.get('SSH_KEY_PATH', os.path.expanduser('~/.ssh/gucci_deployment_key'))

print('=== Step 1: BIND9 중지 ===')

# SSH 연결
client = paramiko.SSHClient()
client.set_missing_host_key_policy(paramiko.AutoAddPolicy())

try:
    client.connect(
        hostname='76.13.218.129',
        port=22,
        username='root',
        key_filename=ssh_key_path,
        timeout=10
    )
    print('✅ SSH 연결 성공')
    
    # BIND9 상태 확인
    print('\n--- 현재 named 상태 ---')
    stdin, stdout, stderr = client.exec_command('systemctl status named')
    print(stdout.read().decode()[:300])
    
    # BIND9 중지
    print('\n--- named 중지 중 ---')
    stdin, stdout, stderr = client.exec_command('systemctl stop named')
    print(stdout.read().decode())
    print(stderr.read().decode())
    
    # BIND9 비활성화
    print('\n--- named 비활성화 중 ---')
    stdin, stdout, stderr = client.exec_command('systemctl disable named')
    print(stdout.read().decode())
    print(stderr.read().decode())
    
    # 확인
    print('\n--- named 상태 확인 ---')
    stdin, stdout, stderr = client.exec_command('systemctl status named')
    output = stdout.read().decode()
    if 'inactive' in output.lower() or 'disabled' in output.lower():
        print('✅ BIND9 중지 완료')
    print(output[:300])
    
    print('\n=== Step 2: nginx 재시작 ===')
    stdin, stdout, stderr = client.exec_command('systemctl restart nginx')
    print(stdout.read().decode())
    
    print('\n=== Step 3: 연결 테스트 ===')
    # VPS IP로 직접 curl
    stdin, stdout, stderr = client.exec_command('curl -I http://76.13.218.129/')
    print('curl http://76.13.218.129/:')
    print(stdout.read().decode()[:500])
    
    client.close()
    print('\n✅ 모든 작업 완료')
    
except Exception as e:
    print(f'❌ 오류: {type(e).__name__}: {e}')
