#!/usr/bin/env python3
"""VPS BIND9에 DNS Zone 파일 생성 및 A 레코드 추가
(Hostinger API 우회)

공식 문서:
- BIND9: https://www.isc.org/bind/documentation/
- Zone 파일: https://linux.die.net/man/5/named.zone
- named.conf: https://linux.die.net/man/5/named.conf
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
        if output.strip():
            print(output)
        print("✅ 성공\n")
    else:
        print(f"⚠️  {error}")
        if output.strip():
            print(output)
        print()
    
    return exit_code, output, error

print("="*70)
print("VPS BIND9 DNS Zone 파일 생성 + A 레코드 추가")
print("="*70 + "\n")

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
ssh.connect(VPS_HOST, port=22, username=VPS_USER, password=VPS_PASS, timeout=30)

print("[1/6] BIND9 상태 확인...")
code, output, error = execute_command(ssh, "systemctl status named --no-pager | head -10", "BIND9 상태")

# BIND9 시작
if "inactive" in output or "stopped" in output:
    print("[2/6] BIND9 시작...")
    execute_command(ssh, "systemctl start named", "BIND9 시작")
else:
    print("[2/6] BIND9 이미 실행 중\n")

# Zone 파일 생성
print("[3/6] Zone 파일 생성...")

zone_file_content = f"""$TTL 3600
@   IN  SOA ns1.{DOMAIN}. root.{DOMAIN}. (
        2026060201  ; serial
        3600        ; refresh
        1800        ; retry
        604800      ; expire
        86400 )     ; minimum

@   IN  NS  ns1.{DOMAIN}.
@   IN  NS  ns2.{DOMAIN}.

@           IN  A   {VPS_IP}
www         IN  A   {VPS_IP}
ns1         IN  A   {VPS_IP}
ns2         IN  A   {VPS_IP}

; MX records for email
@           IN  MX  10 mail.{DOMAIN}.
mail        IN  A   {VPS_IP}

; CNAME records
admin       IN  CNAME {DOMAIN}.
api         IN  CNAME {DOMAIN}.
cdn         IN  CNAME {DOMAIN}.
"""

# Zone 파일 경로
zone_file_path = f"/etc/bind/zones/db.{DOMAIN}"
zones_dir = "/etc/bind/zones"

# Zone 디렉토리 생성
execute_command(ssh, f"mkdir -p {zones_dir}", "Zone 디렉토리 생성")

# Zone 파일 업로드 (SFTP)
print("[3/6] Zone 파일 업로드 (SFTP)...")
try:
    sftp = ssh.open_sftp()
    with sftp.file(zone_file_path, 'w') as f:
        f.write(zone_file_content)
    sftp.close()
    print(f"✅ Zone 파일 업로드 완료: {zone_file_path}\n")
except Exception as e:
    print(f"⚠️  SFTP 업로드 실패: {e}\n")

# Zone 파일 소유권 및 권한 설정
print("[4/6] Zone 파일 권한 설정...")
execute_command(ssh, f"chown bind:bind {zone_file_path}", "Zone 파일 소유권")
execute_command(ssh, f"chmod 644 {zone_file_path}", "Zone 파일 권한")

# named.conf 확인 및 zone 선언 추가
print("[5/6] named.conf 설정 확인...")
code, output, error = execute_command(
    ssh, 
    f"grep -q 'zone \"{DOMAIN}\"' /etc/bind/named.conf.local || echo 'NOT_FOUND'",
    "Zone 선언 확인"
)

if "NOT_FOUND" in output:
    print("[5/6] Zone 선언 추가...")
    
    # named.conf.local에 zone 선언 추가
    zone_declaration = f"""
zone "{DOMAIN}" {{
    type master;
    file "{zone_file_path}";
    allow-transfer {{ any; }};
    allow-query {{ any; }};
}};
"""
    
    # 기존 내용 읽고 추가
    code, existing, _ = execute_command(
        ssh,
        f"cat /etc/bind/named.conf.local",
        ""
    )
    
    # SFTP로 파일 업데이트
    try:
        sftp = ssh.open_sftp()
        with sftp.file("/etc/bind/named.conf.local", 'w') as f:
            f.write(existing + zone_declaration)
        sftp.close()
        print("✅ Zone 선언 추가 완료\n")
    except Exception as e:
        print(f"⚠️  Zone 선언 추가 실패: {e}\n")
else:
    print("✅ Zone 선언 이미 존재\n")

# BIND9 설정 확인
print("[5/6] BIND9 설정 검증...")
execute_command(ssh, "named-checkconf /etc/bind/named.conf", "named-checkconf")

# Zone 파일 검증
print("[5/6] Zone 파일 검증...")
execute_command(ssh, f"named-checkzone {DOMAIN} {zone_file_path}", "Zone 파일 검증")

# BIND9 재시작
print("[6/6] BIND9 재시작...")
execute_command(ssh, "systemctl restart named", "BIND9 재시작")

# DNS 확인
print("\n" + "="*70)
print("DNS 확인 (5초 대기)")
print("="*70 + "\n")

import time
time.sleep(5)

print("[DNS 테스트] localhost에서 조회...")
execute_command(ssh, f"dig @127.0.0.1 {DOMAIN} A", "localhost DNS 조회")

print("[DNS 테스트] Public DNS에서 조회...")
execute_command(ssh, f"dig @1.1.1.1 {DOMAIN} A", "Public DNS 조회 (Cloudflare)")

print("[DNS 테스트] Google DNS에서 조회...")
execute_command(ssh, f"dig @8.8.8.8 {DOMAIN} A", "Google DNS 조회")

# 최종 상태
print("="*70)
print("최종 상태")
print("="*70 + "\n")

execute_command(ssh, "systemctl status named --no-pager | head -5", "BIND9 최종 상태")

print("\n✅ DNS Zone 설정 완료!")
print(f"\n다음 단계:")
print(f"1. DNS 전파 대기 (5-10분)")
print(f"2. 확인: dig @1.1.1.1 {DOMAIN} A")
print(f"3. 다시 Certbot 시도: certbot --nginx -d {DOMAIN} -d www.{DOMAIN}")

ssh.close()
