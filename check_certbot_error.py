#!/usr/bin/env python3
"""Certbot 에러 로그 확인 및 SSL 수동 발급"""

import os
import paramiko
from dotenv import load_dotenv

load_dotenv()

VPS_HOST = "76.13.218.129"
VPS_USER = "root"
VPS_PASS = os.getenv("VPS_PASS")
DOMAIN = "xn--2e0bj1fruw33b6ti.net"

def execute_command(ssh_client, command):
    """원격 명령 실행"""
    stdin, stdout, stderr = ssh_client.exec_command(command, timeout=30)
    exit_code = stdout.channel.recv_exit_status()
    output = stdout.read().decode('utf-8', errors='ignore')
    error = stderr.read().decode('utf-8', errors='ignore')
    return exit_code, output, error

print("[1] Certbot 에러 로그 확인...")
ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
ssh.connect(VPS_HOST, port=22, username=VPS_USER, password=VPS_PASS, timeout=30)

# Certbot 로그 확인
print("\n=== Certbot 에러 로그 ===\n")
code, output, error = execute_command(ssh, "tail -50 /var/log/letsencrypt/letsencrypt.log")
print(output)
if error:
    print("오류:", error)

# Certbot 상태 확인
print("\n=== Certbot 인증서 현황 ===\n")
code, output, error = execute_command(ssh, "certbot certificates")
print(output)

# nginx 상태 확인
print("\n=== nginx 상태 ===\n")
code, output, error = execute_command(ssh, "systemctl status nginx --no-pager")
print(output)

# DNS A 레코드 수동 추가 (HTTP 챌린지를 위해 필요)
print("\n[2] DNS A 레코드 수동 추가 (Hostinger 대시보드)...")
print(f"""
1. https://hpanel.hostinger.com 접속
2. 도메인 선택: {DOMAIN}
3. DNS → A 레코드 추가:
   - 이름: @
   - 주소: 76.13.218.129
   - TTL: 3600
   
   - 이름: www
   - 주소: 76.13.218.129
   - TTL: 3600

4. 저장 후 5분 대기

5. 다시 시도:
   certbot --nginx -d {DOMAIN} -d www.{DOMAIN} --non-interactive --agree-tos -m admin@{DOMAIN} --no-eff-email
""")

# IP 기반 SSL 인증서 추가 (임시)
print("\n[3] IP 기반 자체 서명 인증서 생성 (임시)...")
code, output, error = execute_command(ssh, f"""
openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
  -keyout /etc/nginx/ssl/{DOMAIN}.key \
  -out /etc/nginx/ssl/{DOMAIN}.crt \
  -subj "/C=KR/ST=Seoul/L=Seoul/O=Gucci/CN={DOMAIN}"
""")

if code == 0:
    print("✅ 자체 서명 인증서 생성 완료")
else:
    print(f"⚠️  {error}")

# nginx 설정 업데이트
print("\n[4] nginx SSL 설정 업데이트...")
nginx_config = f"""
server {{
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name {DOMAIN} www.{DOMAIN};

    ssl_certificate /etc/nginx/ssl/{DOMAIN}.crt;
    ssl_certificate_key /etc/nginx/ssl/{DOMAIN}.key;
    
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;

    root /var/www/gucci-yanonlja-net;
    index index.php index.html;

    location ~ \\.php$ {{
        try_files $uri =404;
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }}

    location / {{
        try_files $uri $uri/ =404;
    }}
}}

server {{
    listen 80;
    listen [::]:80;
    server_name {DOMAIN} www.{DOMAIN};
    return 301 https://$server_name$request_uri;
}}
"""

code, output, error = execute_command(ssh, 
    f"echo '{nginx_config}' > /etc/nginx/sites-available/{DOMAIN}")

if code == 0:
    print("✅ nginx 설정 업데이트 완료")
else:
    print(f"⚠️  {error}")

# nginx 재시작
print("\n[5] nginx 재시작...")
code, output, error = execute_command(ssh, "systemctl restart nginx")

if code == 0:
    print("✅ nginx 재시작 완료")
else:
    print(f"⚠️  {error}")

# 최종 상태 확인
print("\n[6] 최종 상태 확인...")
code, output, error = execute_command(ssh, f"curl -k https://{DOMAIN}/ -I | head -5")
print(output)

ssh.close()
print("\n✅ 완료!")
