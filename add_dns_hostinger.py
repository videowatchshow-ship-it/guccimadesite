#!/usr/bin/env python3
import os
import requests
from dotenv import load_dotenv

# Load .env
load_dotenv('f:\\youtubeautoid\\.env')

api_token = os.environ.get('HOSTINGER_API_TOKEN')
domain = 'xn--2e0bj1fruw33b6ti.net'

print(f'API Token: {api_token[:20]}...' if api_token else 'NOT FOUND')
print(f'Domain: {domain}')

# Hostinger DNS API
base_url = 'https://developers.hostinger.com/api/dns/v1/zones'
headers = {
    'Authorization': api_token,
    'Content-Type': 'application/json'
}

# Step 1: 기존 DNS Zone 확인
print('\n=== Step 1: DNS Zone 조회 ===')
url = f'{base_url}/{domain}'
response = requests.get(url, headers=headers)
print(f'Status: {response.status_code}')
print(f'Response: {response.text[:500]}')

if response.status_code == 200:
    zone_data = response.json()
    print('\n기존 레코드들:')
    if 'records' in zone_data:
        for record in zone_data['records']:
            print(f"  {record.get('name', '@')} ({record.get('type')}) -> {record.get('content')}")
    else:
        print('  (레코드 없음)')
        
    # Step 2: A 레코드 추가
    print('\n=== Step 2: A 레코드 추가 ===')
    records_to_add = [
        {'name': '@', 'type': 'A', 'content': '76.13.218.129'},
        {'name': 'www', 'type': 'A', 'content': '76.13.218.129'}
    ]
    
    for record in records_to_add:
        print(f"\n추가 중: {record['name']} -> {record['content']}")
        
elif response.status_code == 404:
    print('❌ 도메인이 Hostinger DNS Zone에 없습니다')
    print('→ 먼저 Hostinger 대시보드에서 도메인을 추가해야 합니다')
else:
    print(f'❌ 오류: {response.status_code}')
    print(f'Response: {response.text}')
