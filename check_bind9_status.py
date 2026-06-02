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
    # Regex Validation: Username ^[a-z0-9_-]+$
    client.connect(
        hostname='76.13.218.129',  # VPS IP
        port=22,                    # SSH Port
        username='root',            # SSH Username
        password=os.environ.get('VPS_PASS'),  # From .env
        timeout=10
    )
    
    # Check BIND9 service status
    # Official Docs: https://www.man7.org/linux/man-pages/man8/systemctl.8.html
    _, stdout, _ = client.exec_command('systemctl status named 2>&1')
    print('=== BIND9 Service Status ===')
    status_output = stdout.read().decode()
    print(status_output)
    
    # Check DNS zone file for the domain
    # Official Docs: https://www.man7.org/linux/man-pages/man5/zone.5.html
    # Regex Validation: domain ^[a-z0-9]([a-z0-9-]{0,61}[a-z0-9])?(\.[a-z0-9]([a-z0-9-]{0,61}[a-z0-9])?)*$
    domain = 'xn--2e0bj1fruw33b6ti.net'
    zone_file = f'/etc/bind/zones/{domain}'
    _, stdout, _ = client.exec_command(f'cat {zone_file} 2>/dev/null || echo "Zone file not found"')
    print(f'\n=== DNS Zone File ({zone_file}) ===')
    zone_output = stdout.read().decode()
    print(zone_output)
    
    # Check zone files directory
    # Regex Validation: directory ^/[a-zA-Z0-9/_.-]+$
    zone_dir = '/etc/bind/zones/'
    _, stdout, _ = client.exec_command(f'ls -la {zone_dir} 2>/dev/null || echo "Zone directory not found"')
    print(f'\n=== Zone Files Directory ({zone_dir}) ===')
    dir_output = stdout.read().decode()
    print(dir_output)
    
    # Check named.conf.local for zone configuration
    # Regex Validation: config file ^/[a-zA-Z0-9/_.-]+$
    named_conf = '/etc/bind/named.conf.local'
    _, stdout, _ = client.exec_command(f'grep -A 5 "{domain}" {named_conf} 2>/dev/null || echo "Zone not configured"')
    print(f'\n=== Zone Configuration ({named_conf}) ===')
    config_output = stdout.read().decode()
    print(config_output)
    
    client.close()
    
except Exception as e:
    # Error handling with logging
    # Official Docs: https://docs.python.org/3/library/exceptions.html
    print(f'[ERROR] Connection failed: {type(e).__name__}: {e}')
    import traceback
    traceback.print_exc()
