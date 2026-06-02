#!/usr/bin/env python3
"""
완전 진단: 설정된 모든 것을 하나하나 연결해서 확인
- Hostinger DNS Zone이 정말 있는지
- A 레코드가 정말 설정되어 있는지
- VPS가 정말 응답하는지
- nginx가 정말 응답하는지
- 왜 Certbot이 SERVFAIL을 받는지
"""
import paramiko
import os
from dotenv import load_dotenv

# Load .env
load_dotenv('f:\\youtubeautoid\\.env')

ssh_key_path = os.environ.get('SSH_KEY_PATH', os.path.expanduser('~/.ssh/gucci_deployment_key'))
vps_ip = '76.13.218.129'
domain = 'xn--2e0bj1fruw33b6ti.net'

print('='*80)
print('완전 진단: 모든 설정 연결 확인')
print('='*80)

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
    
    # 1. VPS에서 DNS 조회 (VPS 자신의 DNS 설정)
    print('='*80)
    print('1단계: VPS 내부에서 DNS 조회')
    print('='*80)
    
    print(f'\n명령: dig {domain} A')
    stdin, stdout, stderr = client.exec_command(f'dig {domain} A +short')
    vps_dns_result = stdout.read().decode().strip()
    print(f'응답: {vps_dns_result if vps_dns_result else "(빈 응답)"}')
    
    print(f'\n명령: dig www.{domain} A')
    stdin, stdout, stderr = client.exec_command(f'dig www.{domain} A +short')
    vps_dns_www = stdout.read().decode().strip()
    print(f'응답: {vps_dns_www if vps_dns_www else "(빈 응답)"}')
    
    # 2. VPS에서 다른 DNS 서버로 조회
    print('\n' + '='*80)
    print('2단계: VPS에서 Google DNS (8.8.8.8) 통해 조회')
    print('='*80)
    
    print(f'\n명령: dig @8.8.8.8 {domain} A +short')
    stdin, stdout, stderr = client.exec_command(f'dig @8.8.8.8 {domain} A +short')
    google_dns_result = stdout.read().decode().strip()
    print(f'응답: {google_dns_result if google_dns_result else "(빈 응답)"}')
    
    # 3. VPS에서 Cloudflare DNS로 조회
    print('\n' + '='*80)
    print('3단계: VPS에서 Cloudflare DNS (1.1.1.1) 통해 조회')
    print('='*80)
    
    print(f'\n명령: dig @1.1.1.1 {domain} A +short')
    stdin, stdout, stderr = client.exec_command(f'dig @1.1.1.1 {domain} A +short')
    cloudflare_result = stdout.read().decode().strip()
    print(f'응답: {cloudflare_result if cloudflare_result else "(빈 응답)"}')
    
    # 4. VPS의 resolv.conf 확인
    print('\n' + '='*80)
    print('4단계: VPS DNS 설정 (/etc/resolv.conf)')
    print('='*80)
    
    stdin, stdout, stderr = client.exec_command('cat /etc/resolv.conf')
    resolv_conf = stdout.read().decode()
    print(f'\n/etc/resolv.conf:')
    print(resolv_conf[:300])
    
    # 5. nameserver 상태 확인
    print('\n' + '='*80)
    print('5단계: Nameserver 설정 확인')
    print('='*80)
    
    print(f'\n명령: dig NS {domain} +short')
    stdin, stdout, stderr = client.exec_command(f'dig NS {domain} +short')
    ns_result = stdout.read().decode().strip()
    print(f'권한 네임서버:')
    print(ns_result if ns_result else "(없음)")
    
    # 6. WHOIS 조회
    print('\n' + '='*80)
    print('6단계: WHOIS 정보')
    print('='*80)
    
    print(f'\n명령: whois {domain} 2>/dev/null | grep -i "name server" | head -5')
    stdin, stdout, stderr = client.exec_command(f'whois {domain} 2>/dev/null | grep -i "name server" | head -5')
    whois_ns = stdout.read().decode().strip()
    print(f'WHOIS Nameservers:')
    print(whois_ns if whois_ns else "(조회 불가)")
    
    # 7. Hostinger DNS API 상태 확인
    print('\n' + '='*80)
    print('7단계: Hostinger 연결 테스트')
    print('='*80)
    
    print(f'\n명령: curl -I https://developers.hostinger.com/ 2>/dev/null | head -2')
    stdin, stdout, stderr = client.exec_command('curl -I https://developers.hostinger.com/ 2>/dev/null | head -2')
    hostinger_api = stdout.read().decode().strip()
    print(f'Hostinger API:')
    print(hostinger_api if hostinger_api else "(연결 불가)")
    
    # 8. nginx HTTP 응답 확인
    print('\n' + '='*80)
    print('8단계: nginx HTTP 응답')
    print('='*80)
    
    print(f'\n명령: curl -I http://127.0.0.1/ 2>/dev/null | head -3')
    stdin, stdout, stderr = client.exec_command('curl -I http://127.0.0.1/ 2>/dev/null | head -3')
    http_response = stdout.read().decode().strip()
    print(f'HTTP 응답:')
    print(http_response if http_response else "(응답 없음)")
    
    # 9. Certbot 설정 파일 확인
    print('\n' + '='*80)
    print('9단계: Certbot 설정')
    print('='*80)
    
    print(f'\n명령: ls -la /etc/letsencrypt/renewal/{domain}* 2>/dev/null | head -5')
    stdin, stdout, stderr = client.exec_command(f'ls -la /etc/letsencrypt/renewal/{domain}* 2>/dev/null | head -5')
    certbot_conf = stdout.read().decode().strip()
    print(f'Certbot 설정:')
    print(certbot_conf if certbot_conf else "(설정 없음)")
    
    # 10. nginx 설정에서 acme-challenge 확인
    print('\n' + '='*80)
    print('10단계: nginx ACME Challenge 설정')
    print('='*80)
    
    print(f'\n명령: grep -n "acme-challenge" /etc/nginx/nginx.conf /etc/nginx/sites-available/* /etc/nginx/conf.d/* 2>/dev/null | head -5')
    stdin, stdout, stderr = client.exec_command('grep -n "acme-challenge" /etc/nginx/nginx.conf /etc/nginx/sites-available/* /etc/nginx/conf.d/* 2>/dev/null | head -5')
    acme_result = stdout.read().decode().strip()
    print(f'ACME Challenge 설정:')
    print(acme_result if acme_result else "(설정 없음)")
    
    # 11. 최종 진단
    print('\n' + '='*80)
    print('최종 진단')
    print('='*80)
    
    success_count = 0
    
    if '76.13.218.129' in vps_dns_result or '76.13.218.129' in google_dns_result or '76.13.218.129' in cloudflare_result:
        print('✅ DNS가 76.13.218.129를 반환함')
        success_count += 1
    else:
        print('❌ DNS가 76.13.218.129를 반환하지 않음')
    
    if 'HOSTINGER' in ns_result or 'HOSTINGER' in whois_ns:
        print('✅ Hostinger nameserver 설정됨')
        success_count += 1
    else:
        print('❌ Hostinger nameserver 설정 확인 불가')
    
    if http_response:
        print('✅ nginx HTTP 응답 정상')
        success_count += 1
    else:
        print('❌ nginx HTTP 응답 없음')
    
    print(f'\n진행률: {success_count}/3 항목 정상')
    
    client.close()
    
except Exception as e:
    print(f'\n❌ 오류: {type(e).__name__}: {e}')
    import traceback
    traceback.print_exc()
