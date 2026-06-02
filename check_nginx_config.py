#!/usr/bin/env python3
"""
VPS nginx 설정 상태 확인
- nginx 설정 검증
- Certbot 인증서 상태
- SSL 설정 확인
"""
import paramiko
import os
from dotenv import load_dotenv

# Load .env
load_dotenv('f:\\youtubeautoid\\.env')

ssh_key_path = os.environ.get('SSH_KEY_PATH', os.path.expanduser('~/.ssh/gucci_deployment_key'))
vps_ip = '76.13.218.129'
domain = 'xn--2e0bj1fruw33b6ti.net'

print('='*70)
print('VPS nginx 설정 상태 확인')
print('='*70)

# SSH 연결
client = paramiko.SSHClient()
client.set_missing_host_key_policy(paramiko.AutoAddPolicy())

try:
    client.connect(
        hostname=vps_ip,
        port=22,
        username='root',
        key_filename=ssh_key_path,
        timeout=10
    )
    print('✅ SSH 연결 성공\n')
    
    # 1. nginx 설정 파일 확인
    print('='*70)
    print('1. nginx 설정 파일 확인')
    print('='*70)
    
    stdin, stdout, stderr = client.exec_command('cat /etc/nginx/sites-enabled/default 2>/dev/null || echo "파일 없음"')
    nginx_config = stdout.read().decode()
    print(f'\n/etc/nginx/sites-enabled/default:')
    print(nginx_config[:500])
    
    # 2. nginx 테스트
    print('\n' + '='*70)
    print('2. nginx 설정 테스트')
    print('='*70)
    
    stdin, stdout, stderr = client.exec_command('nginx -t')
    test_output = stdout.read().decode()
    test_error = stderr.read().decode()
    print(f'\nnginx -t:')
    print(test_output)
    if test_error:
        print(f'Error: {test_error}')
    
    # 3. Certbot 인증서 상태
    print('\n' + '='*70)
    print('3. Certbot 인증서 상태')
    print('='*70)
    
    stdin, stdout, stderr = client.exec_command('certbot certificates')
    certs_output = stdout.read().decode()
    print(f'\ncertbot certificates:')
    print(certs_output)
    
    # 4. SSL 인증서 파일 확인
    print('\n' + '='*70)
    print('4. SSL 인증서 파일 확인')
    print('='*70)
    
    stdin, stdout, stderr = client.exec_command(f'ls -la /etc/letsencrypt/live/{domain}/ 2>/dev/null || echo "없음"')
    cert_files = stdout.read().decode()
    print(f'\n/etc/letsencrypt/live/{domain}/:')
    print(cert_files)
    
    # 5. 자체 서명 인증서 확인
    print('\n' + '='*70)
    print('5. 자체 서명 인증서 확인')
    print('='*70)
    
    stdin, stdout, stderr = client.exec_command('ls -la /etc/ssl/certs/nginx/ 2>/dev/null || echo "없음"')
    self_signed = stdout.read().decode()
    print(f'\n/etc/ssl/certs/nginx/:')
    print(self_signed)
    
    # 6. nginx 프로세스 확인
    print('\n' + '='*70)
    print('6. nginx 프로세스 확인')
    print('='*70)
    
    stdin, stdout, stderr = client.exec_command('ps aux | grep nginx | grep -v grep')
    processes = stdout.read().decode()
    print(f'\nnginx processes:')
    print(processes)
    
    # 7. 포트 확인
    print('\n' + '='*70)
    print('7. 포트 확인')
    print('='*70)
    
    stdin, stdout, stderr = client.exec_command('netstat -tlnp | grep -E "(80|443)" || ss -tlnp | grep -E "(80|443)"')
    ports = stdout.read().decode()
    print(f'\n포트 80, 443:')
    print(ports)
    
    # 8. Certbot 로그 확인
    print('\n' + '='*70)
    print('8. Certbot 로그 (마지막 20줄)')
    print('='*70)
    
    stdin, stdout, stderr = client.exec_command('tail -20 /var/log/letsencrypt/letsencrypt.log 2>/dev/null || echo "로그 없음"')
    certbot_log = stdout.read().decode()
    print(f'\n/var/log/letsencrypt/letsencrypt.log:')
    print(certbot_log)
    
    client.close()
    
except Exception as e:
    print(f'\n❌ 오류: {type(e).__name__}: {e}')
    import traceback
    traceback.print_exc()
