#!/usr/bin/env python3
"""
Hostinger에 외부 도메인의 DNS Zone 추가
(도메인 이전 없이, NS만 Hostinger로 지정된 상태)

Official Docs: https://github.com/hostinger/api-python-sdk
"""
import os
import requests
from dotenv import load_dotenv

# Load .env
load_dotenv('f:\\youtubeautoid\\.env')

api_token = os.environ.get('BEARER_TOKEN')
domain = 'xn--2e0bj1fruw33b6ti.net'

print(f'Domain: {domain}')
print(f'Token: {api_token[:20]}...')

# 직접 REST API 시도 (SDK에 없을 가능성)
base_url = 'https://developers.hostinger.com'

print('\n=== DNS Zone 생성 시도 (직접 API) ===')

# 여러 엔드포인트 시도
endpoints = [
    f'/api/dns/v1/zones',
    f'/api/dns/v1/zones/{domain}',
    f'/api/v1/domains/dns-zones',
    f'/api/v1/dns-zones',
]

for endpoint in endpoints:
    print(f'\n시도: POST {endpoint}')
    
    url = f'{base_url}{endpoint}'
    headers = {
        'Authorization': api_token,
        'Content-Type': 'application/json'
    }
    
    # Payload
    payload = {
        'domain': domain,
        'zone': domain,
        'name': domain
    }
    
    try:
        response = requests.post(url, json=payload, headers=headers, timeout=5)
        print(f'  Status: {response.status_code}')
        print(f'  Response: {response.text[:300]}')
        
        if response.status_code in [200, 201, 204]:
            print(f'  ✅ 성공!')
            break
    except Exception as e:
        print(f'  오류: {e}')

print('\n=== DNS Zone 목록 조회 ===')

# GET /api/dns/v1/zones 로 생성된 존 확인
url = f'{base_url}/api/dns/v1'
headers = {
    'Authorization': api_token,
    'Content-Type': 'application/json'
}

try:
    response = requests.get(url, headers=headers, timeout=5)
    print(f'Status: {response.status_code}')
    print(f'Response: {response.text[:500]}')
except Exception as e:
    print(f'오류: {e}')
