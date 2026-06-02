#!/usr/bin/env python3
import paramiko
import os
from dotenv import load_dotenv

# Load .env
load_dotenv('f:\\youtubeautoid\\.env')

ssh_key_path = os.environ.get('SSH_KEY_PATH', os.path.expanduser('~/.ssh/gucci_deployment_key'))
print('SSH 키:', ssh_key_path)

# SSH 연결
client = paramiko.SSHClient()
client.set_missing_host_key_policy(paramiko.AutoAddPolicy())

try:
    # 공개키 인증
    client.connect(
        hostname='76.13.218.129',
        port=22,
        username='root',
        key_filename=ssh_key_path,
        timeout=10
    )
    print('SSH 연결 성공 (공개키 인증)')
    
    # dig NS 실행
    stdin, stdout, stderr = client.exec_command('dig NS xn--2e0bj1fruw33b6ti.net @8.8.8.8')
    print('\n=== dig NS xn--2e0bj1fruw33b6ti.net @8.8.8.8 ===')
    output = stdout.read().decode()
    errors = stderr.read().decode()
    print(output)
    if errors:
        print('STDERR:', errors)
    
    client.close()
    
except Exception as e:
    print(f'오류: {type(e).__name__}: {e}')
