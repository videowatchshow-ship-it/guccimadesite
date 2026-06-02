#!/usr/bin/env python3
"""
Hostinger Domains Portfolio API 확인
도메인이 Hostinger에 등록되어 있는지, DNS Zone이 있는지 확인
"""
import os
from dotenv import load_dotenv

# Load .env
load_dotenv('f:\\youtubeautoid\\.env')

api_token = os.environ.get('BEARER_TOKEN')
domain = 'xn--2e0bj1fruw33b6ti.net'

print(f'Domain: {domain}')
print(f'Token: {api_token[:20]}...')

try:
    import hostinger_api
    from hostinger_api.rest import ApiException
    
    # SDK 공식 사용법
    configuration = hostinger_api.Configuration(
        access_token=api_token
    )
    
    # API 클라이언트 생성
    api_client = hostinger_api.ApiClient(configuration)
    
    # Domains Portfolio API
    domains_api = hostinger_api.DomainsPortfolioApi(api_client)
    
    print('\n=== DomainsPortfolioApi 메서드 ===')
    methods = [m for m in dir(domains_api) if not m.startswith('_')]
    for method in methods:
        print(f'  {method}')
    
except Exception as e:
    print(f'❌ 오류: {type(e).__name__}: {e}')
