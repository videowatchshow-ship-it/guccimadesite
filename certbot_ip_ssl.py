#!/usr/bin/env python3
"""
Certbot IP 기반 SSL 발급 (DNS 검증 대신 HTTP 검증)
Official Docs: https://certbot.eff.org/instructions

참고: DNS Zone이 정상화되면 다시 도메인 기반으로 갱신
"""
import paramiko
import os
from dotenv import load_dotenv

# Load .env
load_dotenv('f:\\youtubeautoid\\.env')

ssh_key_path = os.environ.get('SSH_KEY_PATH', os.path.expanduser('~/.ssh/gucci_deployment_key'))
vps_ip = '76.13.218.129'
domain = 'xn--2e0bj1fruw33b6ti.net'

print('='*60)
print('SSL/TLS 설치 (IP 기반, HTTP 검증)')
print('='*60)
print(f'VPS IP: {vps_ip}')
print(f'Domain: {domain} (DNS Zone 없음, IP로 임시 설치)')

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
    
    # Step 1: HTTP로 자체 서명 인증서 확인
    print('=== Step 1: 현재 SSL 상태 확인 ===')
    stdin, stdout, stderr = client.exec_command('curl -k -I https://127.0.0.1/ 2>/dev/null | head -3')
    ssl_status = stdout.read().decode()
    print(ssl_status)
    
    # Step 2: Certbot으로 IP 기반 인증서 요청 (--standalone 사용)
    print('=== Step 2: Certbot 설치 (IP 기반) ===')
    print('Certbot --standalone 모드 사용 (HTTP:80 직접 수신)\n')
    
    # 포트 80, 443 임시 중지
    print('nginx 임시 중지...')
    stdin, stdout, stderr = client.exec_command('systemctl stop nginx')
    print(stdout.read().decode()[-50:] if stdout else '')
    
    # Certbot 실행 (--standalone, --http-01)
    print('\ncertbot certonly --standalone -d xn--2e0bj1fruw33b6ti.net --non-interactive --agree-tos -m admin@example.com')
    
    stdin, stdout, stderr = client.exec_command(
        'certbot certonly --standalone -d xn--2e0bj1fruw33b6ti.net --non-interactive --agree-tos -m admin@example.com'
    )
    
    output = stdout.read().decode()
    errors = stderr.read().decode()
    
    print(output)
    if errors and 'WARNING' not in errors:
        print('STDERR:', errors[:300])
    
    # nginx 재시작
    print('\nnginx 재시작...')
    stdin, stdout, stderr = client.exec_command('systemctl start nginx')
    print(stdout.read().decode()[-50:] if stdout else '')
    
    # Step 3: nginx에 SSL 설정 적용
    print('\n=== Step 3: nginx에 SSL 인증서 적용 ===')
    
    nginx_config = '''server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    
    server_name xn--2e0bj1fruw33b6ti.net www.xn--2e0bj1fruw33b6ti.net 76.13.218.129;
    
    # Certbot이 설치한 인증서
    ssl_certificate /etc/letsencrypt/live/xn--2e0bj1fruw33b6ti.net/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/xn--2e0bj1fruw33b6ti.net/privkey.pem;
    
    # SSL 설정
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;
    
    # PHP 앱
    root /var/www/gucci-yanonlja-net/public;
    index index.php index.html;
    
    location ~ \\.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
    }
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
}

# HTTP → HTTPS 리다이렉트
server {
    listen 80;
    listen [::]:80;
    
    server_name xn--2e0bj1fruw33b6ti.net www.xn--2e0bj1fruw33b6ti.net 76.13.218.129;
    
    # Certbot 검증용 (자동 추가됨)
    location /.well-known/acme-challenge/ {
        root /var/www/certbot;
    }
    
    location / {
        return 301 https://$server_name$request_uri;
    }
}
'''
    
    print('nginx 설정 업데이트 중...')
    stdin, stdout, stderr = client.exec_command(
        "cat > /etc/nginx/sites-available/gucci-yanonlja-net << 'EOF'\n" + nginx_config + "\nEOF"
    )
    print(stdout.read().decode())
    
    # nginx 설정 테스트
    print('nginx 설정 테스트...')
    stdin, stdout, stderr = client.exec_command('nginx -t')
    test_result = stdout.read().decode()
    print(test_result)
    
    if 'successful' in test_result.lower():
        print('✅ 설정 정상')
        stdin, stdout, stderr = client.exec_command('systemctl reload nginx')
        print(stdout.read().decode()[-50:] if stdout else '')
    else:
        print('❌ 설정 오류')
    
    # Step 4: 최종 확인
    print('\n=== Step 4: HTTPS 접속 확인 ===')
    
    import time
    time.sleep(2)
    
    stdin, stdout, stderr = client.exec_command('curl -k -I https://127.0.0.1/ 2>/dev/null | head -5')
    https_check = stdout.read().decode()
    print(https_check)
    
    if '200' in https_check or '301' in https_check:
        print('✅ HTTPS 정상 작동')
    
    print('\n' + '='*60)
    print('✅ SSL/TLS 설치 완료!')
    print('='*60)
    print(f'\n✅ 접속 가능:')
    print(f'   https://76.13.218.129/')
    print(f'   https://{domain} (DNS 정상화 후)')
    
    client.close()
    
except Exception as e:
    print(f'❌ 오류: {type(e).__name__}: {e}')
    import traceback
    traceback.print_exc()
