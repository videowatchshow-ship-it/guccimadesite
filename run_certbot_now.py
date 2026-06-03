#!/usr/bin/env python3
# Official Docs: https://certbot.eff.org/instructions?ws=apache&os=ubuntufocal
# Purpose: Let's Encrypt SSL 인증서 발급 (Apache)

import paramiko
import os
import time

VPS_HOST = "76.13.218.129"
VPS_PORT = 22
VPS_USER = "root"
VPS_PASS = os.environ.get("VPS_PASS", "q+7m#GElqQs/E&tfabwB")
DOMAIN = "xn--2e0bj1fruw33b6ti.net"

def ssh(client, cmd, desc="", timeout=60):
    if desc:
        print(f"\n>>> {desc}")
    stdin, stdout, stderr = client.exec_command(cmd, timeout=timeout)
    out = stdout.read().decode().strip()
    err = stderr.read().decode().strip()
    if out:
        print(out)
    if err:
        print(f"[ERR] {err}")
    return out, err

def main():
    print("=" * 60)
    print("Let's Encrypt SSL 발급 (Certbot + Apache)")
    print("=" * 60)

    client = paramiko.SSHClient()
    client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    client.connect(hostname=VPS_HOST, port=VPS_PORT,
                   username=VPS_USER, password=VPS_PASS, timeout=15)
    print(f"✅ SSH: {VPS_HOST}")

    # 1. DNS 전파 확인 (VPS에서)
    print("\n>>> DNS 전파 확인 (VPS 서버에서)")
    out, _ = ssh(client, f"dig @8.8.8.8 {DOMAIN} A +short")
    if "76.13.218.129" not in out:
        print(f"❌ DNS 아직 전파 안 됨: {out}")
        print("잠시 후 다시 시도하세요")
        client.close()
        return
    print(f"✅ DNS 전파 확인: {out}")

    # 2. Apache 상태 확인
    print("\n>>> Apache 상태 확인")
    ssh(client, "systemctl is-active apache2")

    # 3. 기존 자체서명 인증서 설정 확인
    print("\n>>> 기존 SSL 설정 확인")
    ssh(client, f"ls /etc/apache2/sites-enabled/ | grep {DOMAIN}")

    # 4. Certbot 설치 확인
    print("\n>>> Certbot 설치 확인")
    out, _ = ssh(client, "certbot --version 2>&1")
    if "certbot" not in out.lower():
        print("Certbot 설치 중...")
        ssh(client, "apt-get install -y certbot python3-certbot-apache", 
            "Certbot 설치", timeout=120)

    # 5. 기존 자체서명 SSL 비활성화 (있으면)
    print("\n>>> 기존 자체서명 SSL 비활성화")
    ssh(client, f"a2dissite {DOMAIN}-ssl.conf 2>/dev/null || true")
    ssh(client, "systemctl reload apache2 2>/dev/null || true")

    # 6. Certbot 실행
    print("\n>>> Certbot SSL 발급")
    print(f"도메인: {DOMAIN}, www.{DOMAIN}")
    
    certbot_cmd = (
        f"certbot --apache "
        f"-d {DOMAIN} -d www.{DOMAIN} "
        f"--non-interactive "
        f"--agree-tos "
        f"--email admin@{DOMAIN} "
        f"--redirect"
    )
    
    out, err = ssh(client, certbot_cmd, "Certbot 실행", timeout=120)
    
    if "Congratulations" in out or "Successfully" in out:
        print("\n✅ SSL 인증서 발급 성공!")
    elif "error" in (out+err).lower() or "failed" in (out+err).lower():
        print("\n❌ 발급 실패 - 상세 내용:")
        print(out)
        print(err)
        
        # dry-run으로 원인 파악
        print("\n>>> dry-run 테스트")
        ssh(client, certbot_cmd + " --dry-run", "dry-run", timeout=60)
        client.close()
        return

    # 7. 인증서 확인
    print("\n>>> 인증서 확인")
    ssh(client, "certbot certificates")

    # 8. Apache 상태 확인
    print("\n>>> Apache 최종 상태")
    ssh(client, "systemctl status apache2 --no-pager | head -5")
    ssh(client, f"ls /etc/apache2/sites-enabled/")

    # 9. HTTPS 접속 테스트
    print("\n>>> HTTPS 접속 테스트")
    ssh(client, f"curl -sk https://{DOMAIN} -o /dev/null -w '%{{http_code}}' 2>&1")

    # 10. 자동 갱신 설정 확인
    print("\n>>> 자동 갱신 설정")
    ssh(client, "systemctl is-active certbot.timer 2>/dev/null || certbot renew --dry-run 2>&1 | tail -5")

    print(f"""
{"=" * 60}
🎉 완료!
{"=" * 60}
🔒 https://{DOMAIN}
🔒 https://www.{DOMAIN}
{"=" * 60}
""")
    client.close()

if __name__ == "__main__":
    main()
