#!/usr/bin/env python3
"""
Certbot 자동 SSL 발급
Official Docs: https://certbot.eff.org/instructions
Version: 2.0.0+ (2026-06-02)

목표: HTTPS 자동 설치 및 설정
- 도메인: xn--2e0bj1fruw33b6ti.net, www.xn--2e0bj1fruw33b6ti.net
- 웹서버: nginx
- 자동 갱신: enabled
"""
import paramiko
import os
import time
from dotenv import load_dotenv

# Load .env
load_dotenv('f:\\youtubeautoid\\.env')

ssh_key_path = os.environ.get('SSH_KEY_PATH', os.path.expanduser('~/.ssh/gucci_deployment_key'))
domain = 'xn--2e0bj1fruw33b6ti.net'

print('='*60)
print('SSL/TLS 자동 설치 (Certbot)')
print('='*60)
print(f'Domain: {domain}')
print(f'Subdomain: www.{domain}')

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
    print('✅ SSH 연결 성공\n')
    
    # Step 1: Certbot 설치 확인
    print('=== Step 1: Certbot 설치 확인 ===')
    stdin, stdout, stderr = client.exec_command('which certbot')
    certbot_path = stdout.read().decode().strip()
    if certbot_path:
        print(f'✅ Certbot 이미 설치됨: {certbot_path}')
    else:
        print('❌ Certbot 설치 필요')
        print('설치 중...')
        stdin, stdout, stderr = client.exec_command('apt-get update && apt-get install -y certbot python3-certbot-nginx')
        print(stdout.read().decode()[-200:])
    
    # Step 2: DNS 확인
    print('\n=== Step 2: DNS 확인 ===')
    stdin, stdout, stderr = client.exec_command(f'dig @1.1.1.1 {domain} A +short')
    dns_result = stdout.read().decode().strip()
    print(f'dig @1.1.1.1 {domain} A')
    print(f'응답: {dns_result}')
    
    if '76.13.218.129' in dns_result:
        print('✅ DNS 정상')
    else:
        print('⚠️  DNS가 아직 정상이 아님. 계속 진행...')
    
    # Step 3: nginx 상태 확인
    print('\n=== Step 3: nginx 상태 확인 ===')
    stdin, stdout, stderr = client.exec_command('systemctl is-active nginx')
    nginx_status = stdout.read().decode().strip()
    print(f'nginx: {nginx_status}')
    
    if nginx_status != 'active':
        print('❌ nginx가 실행 중이 아님. 시작 중...')
        stdin, stdout, stderr = client.exec_command('systemctl start nginx')
        print(stdout.read().decode())
    
    # Step 4: Certbot SSL 발급
    print('\n=== Step 4: Certbot SSL 발급 ===')
    print(f'certbot --nginx -d {domain} -d www.{domain} --non-interactive --agree-tos -m admin@{domain}')
    
    # 이메일은 임시로 사용
    email = f'admin@{domain}'
    
    stdin, stdout, stderr = client.exec_command(
        f'certbot --nginx -d {domain} -d www.{domain} --non-interactive --agree-tos -m {email}'
    )
    
    # 출력 읽기 (증분으로)
    output = stdout.read().decode()
    errors = stderr.read().decode()
    
    print(output)
    if errors:
        print('STDERR:')
        print(errors)
    
    if 'Successfully received certificate' in output or 'Cert not yet due for renewal' in output:
        print('✅ SSL 인증서 발급/갱신 완료')
    else:
        print('⚠️  상태 확인 필요')
    
    # Step 5: 자동 갱신 설정
    print('\n=== Step 5: 자동 갱신 설정 ===')
    stdin, stdout, stderr = client.exec_command('systemctl enable certbot.timer')
    print(stdout.read().decode())
    
    stdin, stdout, stderr = client.exec_command('systemctl start certbot.timer')
    print(stdout.read().decode())
    
    # Step 6: 최종 확인
    print('\n=== Step 6: HTTPS 접속 확인 ===')
    
    # curl로 HTTPS 확인
    stdin, stdout, stderr = client.exec_command(f'curl -I https://{domain}/ 2>/dev/null | head -5')
    https_response = stdout.read().decode()
    print(f'curl -I https://{domain}/')
    print(https_response)
    
    if '200' in https_response or '301' in https_response or '302' in https_response:
        print('✅ HTTPS 정상 작동')
    else:
        print('⚠️  HTTPS 확인 필요')
    
    print('\n' + '='*60)
    print('✅ SSL/TLS 설치 완료!')
    print('='*60)
    print(f'\n✅ 접속 가능:')
    print(f'   https://{domain}')
    print(f'   https://www.{domain}')
    
    client.close()
    
except Exception as e:
    print(f'❌ 오류: {type(e).__name__}: {e}')
    import traceback
    traceback.print_exc()
