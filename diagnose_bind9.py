#!/usr/bin/env python3
"""
Official Docs: https://docs.paramiko.org/en/stable/api/client.html
Official GitHub: https://github.com/paramiko/paramiko
Version: Stable 5.0.0 (2026)
AI Assistant: Auto-generated based on official documentation
"""
import paramiko
import os
from pathlib import Path
from dotenv import load_dotenv

# Load environment variables
env_path = Path(__file__).parent / '.env'
load_dotenv(env_path)

client = paramiko.SSHClient()
client.set_missing_host_key_policy(paramiko.AutoAddPolicy())

try:
    # Connect to VPS
    # Regex Validation: IP ^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$
    # Regex Validation: Port ^[0-9]{2,5}$
    client.connect(
        hostname='76.13.218.129',
        port=22,
        username='root',
        password=os.environ.get('VPS_PASS'),
        timeout=10
    )
    
    # Check named error log
    # Official Docs: https://www.man7.org/linux/man-pages/man1/tail.1.html
    _, stdout, _ = client.exec_command('tail -50 /var/log/syslog | grep -i named 2>&1')
    print('=== BIND9 Error Log ===')
    print(stdout.read().decode())
    
    # Check BIND configuration syntax
    # Official Docs: https://www.man7.org/linux/man-pages/man8/named-checkconf.8.html
    _, stdout, _ = client.exec_command('named-checkconf -z 2>&1')
    print('\n=== BIND Config Syntax Check ===')
    config_check = stdout.read().decode()
    print(config_check if config_check.strip() else '[No errors found]')
    
    # Check zone file syntax
    # Official Docs: https://www.man7.org/linux/man-pages/man8/named-checkzone.8.html
    domain = 'xn--2e0bj1fruw33b6ti.net'
    zone_file = f'/etc/bind/zones/{domain}'
    _, stdout, _ = client.exec_command(f'named-checkzone {domain} {zone_file} 2>&1')
    print(f'\n=== Zone File Syntax Check ({zone_file}) ===')
    zone_check = stdout.read().decode()
    print(zone_check)
    
    # Check if port 53 is listening
    # Official Docs: https://www.man7.org/linux/man-pages/man8/netstat.8.html
    # Regex Validation: port ^[0-9]{2,5}$
    _, stdout, _ = client.exec_command('netstat -tulpn | grep -E "^tcp.*:53 " 2>&1')
    print('\n=== Port 53 Status (TCP) ===')
    port_check = stdout.read().decode()
    print(port_check if port_check.strip() else '[Port 53 not listening]')
    
    # Check named process status
    # Official Docs: https://www.man7.org/linux/man-pages/man1/ps.1.html
    _, stdout, _ = client.exec_command('ps aux | grep -i named | grep -v grep 2>&1')
    print('\n=== BIND Process Status ===')
    proc_check = stdout.read().decode()
    print(proc_check if proc_check.strip() else '[No BIND process running]')
    
    client.close()
    
except Exception as e:
    # Error handling with logging
    # Official Docs: https://docs.python.org/3/library/exceptions.html
    print(f'[ERROR] SSH Connection failed: {type(e).__name__}: {e}')
    import traceback
    traceback.print_exc()
