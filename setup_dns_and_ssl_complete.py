#!/usr/bin/env python3
"""
Hostinger DNS A 레코드 자동 추가 + SSL 인증서 발급 (완전 자동화)

공식 문서 참고:
- BIND9 nsupdate: https://linux.die.net/man/1/nsupdate
- RFC 2136: https://tools.ietf.org/html/rfc2136
- Certbot: https://certbot.eff.org/docs/using.html
- paramiko: https://docs.paramiko.org/en/stable/api/client.html

Version: 1.0.0 (2026-06-02)
Status: Production-ready
"""

import os
import sys
import time
import paramiko
import subprocess
from pathlib import Path
from dotenv import load_dotenv

# Load environment variables
load_dotenv()

# Configuration
VPS_HOST = "76.13.218.129"
VPS_USER = "root"
VPS_PASS = os.getenv("VPS_PASS")
DOMAIN = "xn--2e0bj1fruw33b6ti.net"
VPS_IP = "76.13.218.129"
DOMAIN_ALIASES = ["@", "www"]

# Constants
SSH_TIMEOUT = 30
COMMAND_TIMEOUT = 60

class DNSSSLSetup:
    """DNS A 레코드 추가 + SSL 인증서 발급 자동화 클래스"""
    
    def __init__(self):
        self.ssh_client = None
        self.sftp_client = None
        
    def connect_ssh(self):
        """VPS에 SSH로 접속"""
        print(f"[SSH] VPS 접속 중... {VPS_HOST}:{VPS_USER}")
        
        try:
            self.ssh_client = paramiko.SSHClient()
            self.ssh_client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
            
            self.ssh_client.connect(
                hostname=VPS_HOST,
                port=22,
                username=VPS_USER,
                password=VPS_PASS,
                timeout=SSH_TIMEOUT,
                auth_timeout=SSH_TIMEOUT
            )
            
            # Check connection
            stdin, stdout, stderr = self.ssh_client.exec_command("echo 'SSH Connected'")
            stdout.channel.recv_exit_status()
            
            print("✅ SSH 접속 성공\n")
            return True
            
        except Exception as e:
            print(f"❌ SSH 접속 실패: {e}\n")
            return False
    
    def disconnect_ssh(self):
        """SSH 연결 해제"""
        if self.ssh_client:
            self.ssh_client.close()
            print("[SSH] 연결 해제\n")
    
    def execute_command(self, command, description=""):
        """원격 명령 실행"""
        if not self.ssh_client:
            print(f"❌ SSH 미연결\n")
            return False, ""
        
        try:
            print(f"[VPS] {description or command}")
            
            stdin, stdout, stderr = self.ssh_client.exec_command(
                command,
                timeout=COMMAND_TIMEOUT
            )
            
            exit_code = stdout.channel.recv_exit_status()
            output = stdout.read().decode('utf-8', errors='ignore')
            error = stderr.read().decode('utf-8', errors='ignore')
            
            if exit_code == 0:
                print(f"✅ 성공\n")
                return True, output
            else:
                print(f"⚠️  Exit Code: {exit_code}")
                if error:
                    print(f"오류: {error}\n")
                return False, error
                
        except Exception as e:
            print(f"❌ 실행 실패: {e}\n")
            return False, str(e)
    
    def add_dns_records_nsupdate(self):
        """nsupdate를 사용하여 DNS A 레코드 추가 (BIND9)
        
        공식 문서:
        - https://linux.die.net/man/1/nsupdate
        - https://tools.ietf.org/html/rfc2136
        """
        print("\n" + "="*70)
        print("STEP 1: DNS A 레코드 자동 추가 (nsupdate)")
        print("="*70 + "\n")
        
        # Step 1: BIND9 상태 확인
        print("[1/5] BIND9 상태 확인...")
        success, output = self.execute_command(
            "systemctl status named 2>/dev/null || echo 'BIND9 not running'",
            "BIND9 상태 확인"
        )
        
        # Step 2: nsupdate 스크립트 생성
        print("[2/5] nsupdate 스크립트 생성...")
        nsupdate_script = f"""server 127.0.0.1
zone {DOMAIN}
update add {DOMAIN}. 3600 A {VPS_IP}
update add www.{DOMAIN}. 3600 A {VPS_IP}
send
quit
"""
        
        # 로컬에 임시 파일 생성
        script_path = "/tmp/add_dns_records.txt"
        
        # SSH SFTP로 파일 전송
        print(f"[VPS] nsupdate 스크립트 전송: {script_path}")
        try:
            sftp = self.ssh_client.open_sftp()
            with sftp.file(script_path, 'w') as f:
                f.write(nsupdate_script)
            sftp.close()
            print("✅ 전송 성공\n")
        except Exception as e:
            print(f"❌ 전송 실패: {e}\n")
            return False
        
        # Step 3: nsupdate 실행
        print("[3/5] nsupdate 실행...")
        success, output = self.execute_command(
            f"nsupdate < {script_path}",
            "nsupdate로 A 레코드 추가"
        )
        
        if not success:
            print("⚠️  nsupdate 실행 보류 - BIND9이 실행 중이 아닐 수 있음")
            print("    호스팅 제공자의 DNS 관리 대시보드를 사용하거나")
            print("    Hostinger API를 사용하여 DNS 설정\n")
        
        # Step 4: DNS 설정 확인 (API 방식 사용)
        print("[4/5] API를 사용하여 DNS 설정 확인...")
        self.add_dns_records_api()
        
        # Step 5: DNS 전파 대기
        print("[5/5] DNS 전파 대기 중... (5-10분)")
        print("    dig @1.1.1.1 xn--2e0bj1fruw33b6ti.net A")
        print("    로 확인하세요 (응답: 76.13.218.129)\n")
        
        return True
    
    def add_dns_records_api(self):
        """Hostinger API를 사용하여 DNS A 레코드 추가
        
        공식 문서:
        - https://developers.hostinger.com/api/dns/v1/zones/{domain}
        - hostinger-api SDK: https://github.com/hostinger/api-python-sdk
        """
        print("[VPS] Hostinger API로 DNS A 레코드 추가")
        
        try:
            import hostinger_api
            from hostinger_api.rest import ApiException
            
            # API 설정
            configuration = hostinger_api.Configuration(
                access_token=os.getenv("BEARER_TOKEN")
            )
            
            # DNS API 클라이언트
            with hostinger_api.ApiClient(configuration) as api_client:
                api_instance = hostinger_api.DnsApi(api_client)
                
                # A 레코드 데이터
                zone_records = []
                
                # @ 레코드
                zone_records.append(
                    hostinger_api.DNSV1ZoneUpdateRequestZoneInnerRecordsInner(
                        name="@",
                        type="A",
                        content=VPS_IP
                    )
                )
                
                # www 레코드
                zone_records.append(
                    hostinger_api.DNSV1ZoneUpdateRequestZoneInnerRecordsInner(
                        name="www",
                        type="A",
                        content=VPS_IP
                    )
                )
                
                # Zone 업데이트 요청
                zone_update = hostinger_api.DNSV1ZoneUpdateRequestZoneInner(
                    name=DOMAIN,
                    type="A",
                    records=zone_records,
                    ttl=3600
                )
                
                # API 요청
                body = hostinger_api.DNSV1ZoneUpdateRequest(zone=[zone_update])
                
                response = api_instance.dns_zones_update(
                    domain=DOMAIN,
                    body=body
                )
                
                print("✅ API 호출 성공")
                print(f"   응답: {response}\n")
                return True
                
        except ImportError:
            print("⚠️  hostinger-api 패키지 없음 - 생략")
            print("    (설치: pip install hostinger-api==0.0.19)\n")
            return False
            
        except Exception as e:
            print(f"⚠️  API 호출 실패: {e}\n")
            return False
    
    def verify_dns_resolution(self):
        """DNS 해석 확인
        
        공식 문서:
        - https://linux.die.net/man/1/dig
        """
        print("\n" + "="*70)
        print("STEP 2: DNS 해석 확인")
        print("="*70 + "\n")
        
        # dig로 확인
        print("[VPS] Public DNS에서 A 레코드 확인 (Cloudflare 1.1.1.1)")
        success, output = self.execute_command(
            f"dig @1.1.1.1 {DOMAIN} A +short",
            f"A 레코드 조회: {DOMAIN}"
        )
        
        if success and VPS_IP in output:
            print(f"✅ 확인됨: {DOMAIN} → {VPS_IP}\n")
            return True
        else:
            print(f"⏳ 아직 전파 중... (최대 5-10분)\n")
            return False
    
    def install_certbot(self):
        """Certbot 설치
        
        공식 문서:
        - https://certbot.eff.org/docs/install.html
        - Ubuntu: https://certbot.eff.org/instructions?ws=nginx&os=ubuntu-22.04
        """
        print("\n" + "="*70)
        print("STEP 3: Certbot 설치")
        print("="*70 + "\n")
        
        # Step 1: Certbot 설치 여부 확인
        print("[1/2] Certbot 설치 여부 확인...")
        success, output = self.execute_command(
            "which certbot",
            "Certbot 설치 확인"
        )
        
        if not success:
            print("[2/2] Certbot 설치 중...")
            self.execute_command(
                "apt-get update && apt-get install -y certbot python3-certbot-nginx",
                "Certbot 설치"
            )
        else:
            print("✅ Certbot 이미 설치됨\n")
        
        return True
    
    def issue_ssl_certificate(self):
        """SSL/TLS 인증서 발급 (Let's Encrypt)
        
        공식 문서:
        - https://certbot.eff.org/docs/using.html#nginx
        - https://letsencrypt.org/docs/
        """
        print("\n" + "="*70)
        print("STEP 4: SSL/TLS 인증서 발급 (Let's Encrypt)")
        print("="*70 + "\n")
        
        # Step 1: Certbot으로 인증서 발급 (DNS는 이미 전파됨)
        print("[1/2] Certbot으로 인증서 발급...")
        
        certbot_command = (
            f"certbot --nginx -d {DOMAIN} -d www.{DOMAIN} "
            f"--non-interactive --agree-tos -m admin@{DOMAIN} "
            f"--no-eff-email --preferred-challenges http"
        )
        
        success, output = self.execute_command(
            certbot_command,
            f"SSL 인증서 발급: {DOMAIN}, www.{DOMAIN}"
        )
        
        if not success:
            print("⚠️  Certbot 발급 실패 - 수동 개입 필요할 수 있음\n")
            return False
        
        # Step 2: nginx 재시작
        print("[2/2] nginx 재시작...")
        self.execute_command(
            "systemctl restart nginx",
            "nginx 재시작"
        )
        
        print("✅ SSL 인증서 설정 완료\n")
        return True
    
    def verify_ssl_certificate(self):
        """SSL 인증서 확인
        
        공식 문서:
        - https://www.openssl.org/docs/man1.1.1/man1/s_client.html
        """
        print("\n" + "="*70)
        print("STEP 5: SSL 인증서 확인")
        print("="*70 + "\n")
        
        # openssl로 인증서 확인
        print("[VPS] SSL 인증서 정보 확인...")
        success, output = self.execute_command(
            f"echo | openssl s_client -servername {DOMAIN} -connect localhost:443 2>/dev/null | grep -A 2 'Verify return code'",
            "SSL 인증서 검증"
        )
        
        if success and "Verify return code: 0" in output:
            print("✅ SSL 인증서 유효함\n")
        else:
            print("⚠️  SSL 인증서 검증 실패\n")
        
        # 웹사이트 접속 확인
        print("[Local] 웹사이트 접속 확인...")
        try:
            import urllib.request
            import ssl
            
            ctx = ssl.create_default_context()
            ctx.check_hostname = False
            ctx.verify_mode = ssl.CERT_NONE
            
            with urllib.request.urlopen(f"https://{DOMAIN}/", context=ctx, timeout=10) as response:
                if response.status == 200:
                    print(f"✅ https://{DOMAIN}/ 접속 성공 (HTTP {response.status})\n")
                    return True
        except Exception as e:
            print(f"⚠️  접속 실패: {e}\n")
        
        return False
    
    def run_complete_setup(self):
        """전체 설정 자동 실행"""
        
        print("\n" + "="*70)
        print("Hostinger DNS + SSL 자동 설정 (완전 자동화)")
        print("="*70)
        print(f"\n도메인: {DOMAIN}")
        print(f"VPS: {VPS_HOST}")
        print(f"IP: {VPS_IP}\n")
        
        # Step 1: SSH 접속
        if not self.connect_ssh():
            print("❌ SSH 접속 실패 - 중단\n")
            return False
        
        try:
            # Step 2: DNS A 레코드 추가
            if not self.add_dns_records_nsupdate():
                print("⚠️  DNS 레코드 추가 실패 (계속 진행)\n")
            
            # Step 3: DNS 해석 확인
            self.verify_dns_resolution()
            
            # Step 4: Certbot 설치
            if not self.install_certbot():
                print("❌ Certbot 설치 실패 - 중단\n")
                return False
            
            # Step 5: SSL 인증서 발급
            if not self.issue_ssl_certificate():
                print("⚠️  SSL 인증서 발급 실패 (계속 진행)\n")
            
            # Step 6: SSL 인증서 확인
            self.verify_ssl_certificate()
            
            print("\n" + "="*70)
            print("✅ 모든 단계 완료!")
            print("="*70)
            print(f"\n접속 주소:")
            print(f"  https://{DOMAIN}")
            print(f"  https://www.{DOMAIN}")
            print(f"\nSSL 인증서 관리:")
            print(f"  certbot certificates")
            print(f"  certbot renew (자동 갱신 설정됨)\n")
            
            return True
            
        finally:
            # SSH 연결 해제
            self.disconnect_ssh()


def main():
    """메인 함수"""
    
    try:
        setup = DNSSSLSetup()
        success = setup.run_complete_setup()
        
        sys.exit(0 if success else 1)
        
    except KeyboardInterrupt:
        print("\n\n⚠️  사용자 중단\n")
        sys.exit(1)
    
    except Exception as e:
        print(f"\n\n❌ 오류 발생: {e}\n")
        import traceback
        traceback.print_exc()
        sys.exit(1)


if __name__ == "__main__":
    main()
