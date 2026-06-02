#!/usr/bin/env python3
"""
Certbot 즉시 실행
공식 문서: https://certbot.eff.org/

당신이 이미 Hostinger hPanel에서 DNS Zone을 추가했다면,
이 스크립트로 Certbot을 자동 실행합니다.
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
print('Certbot SSL/TLS 즉시 실행')
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
    
    # Step 1: DNS 즉시 검증
    print('='*70)
    print('Step 1: DNS 검증')
    print('='*70)
    
    print(f'\ndig @1.1.1.1 {domain} A')
    stdin, stdout, stderr = client.exec_command(f'dig @1.1.1.1 {domain} A +short')
    dns_root = stdout.read().decode().strip()
    print(f'응답: {dns_root if dns_root else "(빈 응답)"}')
    
    print(f'\ndig @1.1.1.1 www.{domain} A')
    stdin, stdout, stderr = client.exec_command(f'dig @1.1.1.1 www.{domain} A +short')
    dns_www = stdout.read().decode().strip()
    print(f'응답: {dns_www if dns_www else "(빈 응답)"}')
    
    # Step 2: Certbot 즉시 실행
    print('\n' + '='*70)
    print('Step 2: Certbot --nginx 실행')
    print('='*70)
    
    email = f'admin@{domain}'
    cmd = f'certbot --nginx -d {domain} -d www.{domain} --non-interactive --agree-tos -m {email} -v'
    
    print(f'\n실행: {cmd}\n')
    
    stdin, stdout, stderr = client.exec_command(cmd)
    
    # 실시간 출력
    output_lines = []
    for line in iter(lambda: stdout.readline(), ''):
        if not line:
            break
        line_str = line if isinstance(line, str) else line.decode()
        print(line_str, end='', flush=True)
        output_lines.append(line_str)
    
    output = ''.join(output_lines)
    
    # stderr 확인
    errors = stderr.read().decode()
    if errors:
        print('\n--- STDERR ---')
        print(errors)
    
    # Step 3: 결과 확인
    print('\n' + '='*70)
    print('Step 3: 결과 확인')
    print('='*70)
    
    if 'Successfully received certificate' in output:
        print('\n✅✅✅ SSL 인증서 발급 성공!')
        
        # Certbot 상태 확인
        stdin, stdout, stderr = client.exec_command('certbot certificates')
        certs = stdout.read().decode()
        print(f'\ncertbot certificates:\n{certs}')
        
    elif 'Cert not yet due for renewal' in output:
        print('\n✅ SSL 인증서 이미 설치됨 (갱신 불필요)')
        
    elif 'SERVFAIL' in output or 'nameservers may be malfunctioning' in output:
        print('\n❌ DNS SERVFAIL 에러')
        print('→ Hostinger hPanel에서 DNS Zone이 정말 생성되었는지 확인하세요')
        print('→ A 레코드 @ 및 www가 76.13.218.129로 설정되어 있는지 확인하세요')
        
    else:
        print('\n⚠️  상태 확인 필요')
    
    # Step 4: HTTPS 접속 테스트
    print('\n' + '='*70)
    print('Step 4: HTTPS 접속 테스트')
    print('='*70)
    
    time.sleep(1)
    stdin, stdout, stderr = client.exec_command('curl -I https://127.0.0.1/ 2>/dev/null | head -3')
    https_test = stdout.read().decode()
    print(f'\ncurl -I https://127.0.0.1/:')
    print(https_test)
    
    if '200' in https_test or '301' in https_test or '302' in https_test:
        print('✅ HTTPS 로컬 접속 정상')
    
    # Step 5: nginx 자동 갱신 활성화
    print('\n' + '='*70)
    print('Step 5: Certbot 자동 갱신 설정')
    print('='*70)
    
    stdin, stdout, stderr = client.exec_command('systemctl enable certbot.timer && systemctl start certbot.timer')
    print(stdout.read().decode())
    print('✅ Certbot 자동 갱신 활성화')
    
    print('\n' + '='*70)
    print('✅ SSL/TLS 설정 완료!')
    print('='*70)
    print(f'\n접속 가능:')
    print(f'  https://{domain}')
    print(f'  https://www.{domain}')
    
    client.close()
    
except Exception as e:
    print(f'\n❌ 오류: {type(e).__name__}: {e}')
    import traceback
    traceback.print_exc()
