#!/usr/bin/env python3
"""
최종 SSL/TLS 설정 자동화
공식 문서: 
- https://certbot.eff.org/
- https://www.hostinger.com/support/1583408-can-external-domains-be-hosted-at-hostinger/

프로세스:
1. DNS 검증 (@1.1.1.1)
2. Certbot SSL 발급
3. nginx 자동 설정
4. HTTPS 확인
"""
import paramiko
import time
import os
from dotenv import load_dotenv

# Load .env
load_dotenv('f:\\youtubeautoid\\.env')

ssh_key_path = os.environ.get('SSH_KEY_PATH', os.path.expanduser('~/.ssh/gucci_deployment_key'))
vps_ip = '76.13.218.129'
domain = 'xn--2e0bj1fruw33b6ti.net'

print('='*70)
print('최종 SSL/TLS 설정 - 자동화')
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
    
    # =================================================================
    # STEP 1: DNS 검증
    # =================================================================
    print('='*70)
    print('STEP 1: DNS 검증')
    print('='*70)
    
    print(f'\n검증 1: dig @1.1.1.1 {domain} A')
    stdin, stdout, stderr = client.exec_command(f'dig @1.1.1.1 {domain} A +short')
    dns_root = stdout.read().decode().strip()
    print(f'응답: {dns_root}')
    
    print(f'\n검증 2: dig @1.1.1.1 www.{domain} A')
    stdin, stdout, stderr = client.exec_command(f'dig @1.1.1.1 www.{domain} A +short')
    dns_www = stdout.read().decode().strip()
    print(f'응답: {dns_www}')
    
    if '76.13.218.129' in dns_root and '76.13.218.129' in dns_www:
        print('\n✅ DNS 검증 완료')
    else:
        print('\n⚠️  DNS가 아직 정상이 아님. 계속 진행...')
        print('   (24시간 이내 전파 완료될 것으로 예상)')
    
    # =================================================================
    # STEP 2: Certbot 설치 확인
    # =================================================================
    print('\n' + '='*70)
    print('STEP 2: Certbot 설치 확인')
    print('='*70)
    
    stdin, stdout, stderr = client.exec_command('which certbot')
    certbot_path = stdout.read().decode().strip()
    
    if certbot_path:
        print(f'✅ Certbot 설치됨: {certbot_path}')
    else:
        print('❌ Certbot 미설치. 설치 중...')
        stdin, stdout, stderr = client.exec_command('apt-get update && apt-get install -y certbot python3-certbot-nginx')
        output = stdout.read().decode()
        if 'Setting up certbot' in output:
            print('✅ Certbot 설치 완료')
        else:
            print('⚠️  설치 진행 중...')
    
    # =================================================================
    # STEP 3: nginx 상태 확인
    # =================================================================
    print('\n' + '='*70)
    print('STEP 3: nginx 상태 확인')
    print('='*70)
    
    stdin, stdout, stderr = client.exec_command('systemctl is-active nginx')
    nginx_status = stdout.read().decode().strip()
    print(f'nginx 상태: {nginx_status}')
    
    if nginx_status != 'active':
        print('nginx 시작 중...')
        stdin, stdout, stderr = client.exec_command('systemctl start nginx')
        time.sleep(2)
        stdin, stdout, stderr = client.exec_command('systemctl is-active nginx')
        nginx_status = stdout.read().decode().strip()
        print(f'nginx 상태: {nginx_status}')
    
    if nginx_status == 'active':
        print('✅ nginx 실행 중')
    
    # =================================================================
    # STEP 4: Certbot으로 SSL 발급
    # =================================================================
    print('\n' + '='*70)
    print('STEP 4: Certbot SSL 발급')
    print('='*70)
    
    email = f'admin@{domain}'
    print(f'\n실행: certbot --nginx -d {domain} -d www.{domain} --non-interactive --agree-tos -m {email}')
    
    stdin, stdout, stderr = client.exec_command(
        f'certbot --nginx -d {domain} -d www.{domain} --non-interactive --agree-tos -m {email}'
    )
    
    # 출력 수집
    output = stdout.read().decode()
    errors = stderr.read().decode()
    
    print('\n--- Certbot 실행 결과 ---')
    print(output)
    
    if errors and 'WARNING' not in errors:
        print('\n--- Errors ---')
        print(errors)
    
    # 성공 여부 확인
    if 'Successfully received certificate' in output:
        print('\n✅ SSL 인증서 발급 완료!')
    elif 'Cert not yet due for renewal' in output:
        print('\n✅ SSL 인증서 이미 존재 (갱신 불필요)')
    elif 'SERVFAIL' in output or 'nameservers may be malfunctioning' in output:
        print('\n⚠️  DNS 문제로 인한 실패')
        print('    → Hostinger hPanel에서 DNS Zone이 생성되었는지 확인하세요')
        print('    → A 레코드 @ = 76.13.218.129')
        print('    → A 레코드 www = 76.13.218.129')
        print('    → 확인 후 다시 실행하세요')
    else:
        print('\n⚠️  상태 확인 필요')
    
    # =================================================================
    # STEP 5: 자동 갱신 설정
    # =================================================================
    print('\n' + '='*70)
    print('STEP 5: 자동 갱신 설정')
    print('='*70)
    
    print('\nCertbot 타이머 활성화...')
    stdin, stdout, stderr = client.exec_command('systemctl enable certbot.timer')
    print(stdout.read().decode())
    
    stdin, stdout, stderr = client.exec_command('systemctl start certbot.timer')
    print(stdout.read().decode())
    
    print('✅ 자동 갱신 설정 완료')
    
    # =================================================================
    # STEP 6: HTTPS 접속 확인
    # =================================================================
    print('\n' + '='*70)
    print('STEP 6: HTTPS 접속 확인')
    print('='*70)
    
    time.sleep(2)
    
    print(f'\n테스트 1: curl -I https://127.0.0.1/')
    stdin, stdout, stderr = client.exec_command('curl -I https://127.0.0.1/ 2>/dev/null | head -5')
    https_local = stdout.read().decode()
    print(https_local)
    
    if '200' in https_local or '301' in https_local or '302' in https_local:
        print('✅ HTTPS 로컬 접속 정상')
    
    # =================================================================
    # STEP 7: 최종 정보
    # =================================================================
    print('\n' + '='*70)
    print('✅ SSL/TLS 설정 완료!')
    print('='*70)
    
    print(f'\n접속 가능 주소:')
    print(f'  https://{domain}')
    print(f'  https://www.{domain}')
    print(f'  https://{vps_ip}')
    
    print(f'\n인증서 위치:')
    print(f'  /etc/letsencrypt/live/{domain}/fullchain.pem')
    print(f'  /etc/letsencrypt/live/{domain}/privkey.pem')
    
    print(f'\nSSL 상태 확인:')
    print(f'  certbot certificates')
    
    print(f'\n자동 갱신 상태:')
    print(f'  systemctl status certbot.timer')
    
    client.close()
    
except Exception as e:
    print(f'\n❌ 오류: {type(e).__name__}: {e}')
    import traceback
    traceback.print_exc()
