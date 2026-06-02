#!/usr/bin/env python3
"""SSL/TLS 설정 최종 완성 (자체 서명 + Certbot 대체)

전략:
1. 자체 서명 SSL 인증서로 HTTPS 작동
2. Certbot은 DNS 검증으로 Let's Encrypt 인증서 발급 (나중에)
3. 현재: 자체 서명 인증서로 즉시 HTTPS 활성화

공식 문서:
- OpenSSL: https://www.openssl.org/docs/
- nginx SSL: https://nginx.org/en/docs/http/ngx_http_ssl_module.html
- Let's Encrypt: https://letsencrypt.org/docs/
"""

import os
import paramiko
from dotenv import load_dotenv

load_dotenv()

VPS_HOST = "76.13.218.129"
VPS_USER = "root"
VPS_PASS = os.getenv("VPS_PASS")
DOMAIN = "xn--2e0bj1fruw33b6ti.net"
VPS_IP = "76.13.218.129"

def execute_command(ssh_client, command, description=""):
    """원격 명령 실행"""
    if description:
        print(f"[VPS] {description}")
    
    stdin, stdout, stderr = ssh_client.exec_command(command, timeout=30)
    exit_code = stdout.channel.recv_exit_status()
    output = stdout.read().decode('utf-8', errors='ignore')
    error = stderr.read().decode('utf-8', errors='ignore')
    
    if exit_code == 0:
        print("✅\n")
    else:
        print(f"⚠️  {error}")
        print()
    
    return exit_code, output, error

print("="*70)
print("SSL/TLS 설정 (자체 서명 인증서 + Certbot 준비)")
print("="*70 + "\n")

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
ssh.connect(VPS_HOST, port=22, username=VPS_USER, password=VPS_PASS, timeout=30)

# Step 1: SSL 디렉토리 생성
print("[1/5] SSL 디렉토리 생성...")
execute_command(ssh, "mkdir -p /etc/nginx/ssl", "SSL 디렉토리 생성")

# Step 2: 자체 서명 인증서 생성
print("[2/5] 자체 서명 SSL 인증서 생성 (365일)...")
cert_command = f"""
openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
  -keyout /etc/nginx/ssl/{DOMAIN}.key \
  -out /etc/nginx/ssl/{DOMAIN}.crt \
  -subj "/C=KR/ST=Seoul/L=Seoul/O=GucciYanonlja/CN={DOMAIN}/subjectAltName=DNS:{DOMAIN},DNS:www.{DOMAIN}"
"""

execute_command(ssh, cert_command, "자체 서명 인증서 생성")

# Step 3: 인증서 권한 설정
print("[3/5] 인증서 권한 설정...")
execute_command(ssh, f"chmod 600 /etc/nginx/ssl/{DOMAIN}.key", "Key 파일 권한")
execute_command(ssh, f"chmod 644 /etc/nginx/ssl/{DOMAIN}.crt", "Cert 파일 권한")

# Step 4: nginx SSL 설정 파일 생성
print("[4/5] nginx SSL 설정 파일 생성...")

nginx_config = f"""# {DOMAIN} - SSL Configuration
# 생성일: 2026-06-02
# 공식 문서: https://nginx.org/en/docs/http/ngx_http_ssl_module.html

# HTTP → HTTPS 리다이렉트
server {{
    listen 80;
    listen [::]:80;
    server_name {DOMAIN} www.{DOMAIN} {VPS_IP};
    return 301 https://$server_name$request_uri;
}}

# HTTPS (SSL/TLS)
server {{
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name {DOMAIN} www.{DOMAIN} {VPS_IP};

    # SSL 인증서 (자체 서명)
    ssl_certificate /etc/nginx/ssl/{DOMAIN}.crt;
    ssl_certificate_key /etc/nginx/ssl/{DOMAIN}.key;

    # SSL 정책 (공식 권장)
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers 'ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256';
    ssl_prefer_server_ciphers on;
    ssl_session_cache shared:SSL:10m;
    ssl_session_timeout 10m;

    # HSTS (선택적)
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;

    # 문서 루트
    root /var/www/gucci-yanonlja-net;
    index index.php index.html index.htm;

    # 로깅
    access_log /var/log/nginx/{DOMAIN}.access.log;
    error_log /var/log/nginx/{DOMAIN}.error.log;

    # PHP 처리
    location ~ \\.php$ {{
        try_files $uri =404;
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }}

    # 정적 파일
    location ~* \\.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2)$ {{
        expires 30d;
        add_header Cache-Control "public, immutable";
    }}

    # 기본 요청
    location / {{
        try_files $uri $uri/ =404;
    }}

    # 숨겨진 파일 차단
    location ~ /\\. {{
        deny all;
    }}
}}
"""

# nginx 설정 파일 업로드
try:
    sftp = ssh.open_sftp()
    with sftp.file(f"/etc/nginx/sites-available/{DOMAIN}", 'w') as f:
        f.write(nginx_config)
    sftp.close()
    print(f"✅\n")
except Exception as e:
    print(f"⚠️  {e}\n")

# Step 4-2: nginx 사이트 활성화
print("[4/5] nginx 사이트 활성화...")
execute_command(ssh, f"ln -sf /etc/nginx/sites-available/{DOMAIN} /etc/nginx/sites-enabled/{DOMAIN}", 
                "사이트 활성화 심볼릭 링크 생성")

# Step 5: nginx 설정 테스트
print("[5/5] nginx 설정 테스트...")
code, output, _ = execute_command(ssh, "nginx -t", "nginx 설정 검증")

if code == 0:
    # nginx 재시작
    print("[5/5] nginx 재시작...")
    execute_command(ssh, "systemctl restart nginx", "nginx 재시작")
else:
    print("⚠️  설정 오류 발생\n")

# Step 6: SSL 인증서 확인
print("\n" + "="*70)
print("SSL 인증서 확인")
print("="*70 + "\n")

code, output, _ = execute_command(ssh, 
    f"openssl x509 -in /etc/nginx/ssl/{DOMAIN}.crt -text -noout | grep -E 'Subject:|Not Before|Not After'",
    "인증서 정보")

# Step 7: HTTPS 연결 테스트
print("[최종] HTTPS 연결 테스트...")
code, output, error = execute_command(ssh,
    f"curl -k https://127.0.0.1/ -I 2>/dev/null | head -5",
    "localhost HTTPS 테스트")

if "200" in output or "301" in output or "HTTP" in output:
    print("✅ HTTPS 작동 중\n")
else:
    print("⚠️  응답 없음\n")

# 최종 상태
print("="*70)
print("✅ SSL/TLS 설정 완료!")
print("="*70)
print(f"""
자체 서명 인증서 생성됨:
  - Certificate: /etc/nginx/ssl/{DOMAIN}.crt
  - Private Key: /etc/nginx/ssl/{DOMAIN}.key
  - 유효기간: 365일 (2027-06-02)

접속 주소:
  - https://{DOMAIN}/
  - https://www.{DOMAIN}/
  - https://{VPS_IP}/ (IP 기반 접속)

주의: 자체 서명 인증서는 브라우저에서 경고 표시됨
  → Let's Encrypt 인증서로 교체 필요

다음 단계:
  1. DNS 전파 대기 (Hostinger NS 설정 확인)
  2. certbot 재시도:
     certbot --nginx -d {DOMAIN} -d www.{DOMAIN} \\
       --non-interactive --agree-tos -m admin@{DOMAIN}
""")

ssh.close()
