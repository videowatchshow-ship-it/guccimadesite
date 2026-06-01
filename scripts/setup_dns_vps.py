# Official GitHub: https://github.com/hostinger/api-python-sdk
# Official Docs: https://developers.hostinger.com
# Official PyPI paramiko: https://pypi.org/project/paramiko/5.0.0/
# Official PyPI hostinger_api: https://pypi.org/project/hostinger-api/0.0.19/
# VPS SSH 직접 접속 → BIND9 DNS Zone 설정
# Version: 2026-06-01 (공식 문서 기준)
# pip install paramiko==5.0.0 hostinger-api==0.0.19

import os
import paramiko
import time
from pathlib import Path

# .env 파일에서 환경변수 로드
env_path = Path(__file__).parent.parent / ".env"
for line in env_path.read_text(encoding="utf-8").splitlines():
    if "=" in line and not line.startswith("#"):
        k, v = line.split("=", 1)
        os.environ[k.strip()] = v.strip()

VPS_HOST = "76.13.218.129"
VPS_PORT = 22
VPS_USER = "root"
VPS_PASS = os.environ.get("VPS_PASS", "")
DOMAIN = "xn--2e0bj1fruw33b6ti.net"
VPS_IP = "76.13.218.129"

def ssh_exec(client, cmd, timeout=30):
    print(f"\n$ {cmd}")
    stdin, stdout, stderr = client.exec_command(cmd, timeout=timeout)
    out = stdout.read().decode(errors="replace").strip()
    err = stderr.read().decode(errors="replace").strip()
    if out:
        print(out)
    if err:
        print(f"[stderr] {err}")
    return out, err

client = paramiko.SSHClient()
client.set_missing_host_key_policy(paramiko.AutoAddPolicy())

print(f"SSH 접속: {VPS_HOST}")
client.connect(VPS_HOST, port=VPS_PORT, username=VPS_USER, password=VPS_PASS, timeout=15)
print("✅ SSH 연결 성공")

# 1. BIND9 설치 확인
ssh_exec(client, "named -v 2>/dev/null || echo 'BIND9 not installed'")

# 2. BIND9 설치 (없으면)
ssh_exec(client, "apt-get install -y bind9 bind9utils bind9-doc 2>&1 | tail -5", timeout=120)

# 3. DNS Zone 파일 생성
zone_content = f"""$TTL 3600
@   IN  SOA ns1.{DOMAIN}. admin.{DOMAIN}. (
            2026060101  ; Serial
            3600        ; Refresh
            1800        ; Retry
            604800      ; Expire
            300 )       ; Minimum TTL

; Name servers
@       IN  NS  ns1.{DOMAIN}.
@       IN  NS  ns2.{DOMAIN}.

; A records
@       IN  A   {VPS_IP}
www     IN  A   {VPS_IP}
ns1     IN  A   {VPS_IP}
ns2     IN  A   {VPS_IP}
"""

# Zone 파일 디렉토리 생성
ssh_exec(client, "mkdir -p /etc/bind/zones")

# Zone 파일 작성
zone_file = f"/etc/bind/zones/{DOMAIN}"
ssh_exec(client, f"cat > {zone_file} << 'ZONEOF'\n{zone_content}\nZONEOF")

# 4. named.conf.local에 zone 추가
named_local = f"""
zone "{DOMAIN}" {{
    type master;
    file "{zone_file}";
    allow-query {{ any; }};
}};
"""
ssh_exec(client, f"grep -q '{DOMAIN}' /etc/bind/named.conf.local || cat >> /etc/bind/named.conf.local << 'NAMEDEOF'\n{named_local}\nNAMEDEOF")

# 5. named.conf.options 설정 (외부 쿼리 허용)
options_check, _ = ssh_exec(client, "grep -c 'allow-query' /etc/bind/named.conf.options 2>/dev/null || echo 0")
if options_check.strip() == "0":
    ssh_exec(client, """sed -i 's/allow-query { localhost; };/allow-query { any; };/' /etc/bind/named.conf.options""")

# 6. Zone 파일 문법 검사
ssh_exec(client, f"named-checkzone {DOMAIN} {zone_file}")

# 7. 설정 파일 검사
ssh_exec(client, "named-checkconf")

# 8. BIND9 재시작
ssh_exec(client, "systemctl restart bind9")
time.sleep(2)
ssh_exec(client, "systemctl status bind9 --no-pager | head -20")

# 9. UFW 포트 53 허용
ssh_exec(client, "ufw allow 53/tcp")
ssh_exec(client, "ufw allow 53/udp")
ssh_exec(client, "ufw status | grep 53")

# 10. DNS 동작 확인
ssh_exec(client, f"dig @{VPS_IP} {DOMAIN} A +short")
ssh_exec(client, f"dig @{VPS_IP} www.{DOMAIN} A +short")

print("\n✅ DNS Zone 설정 완료")
client.close()
