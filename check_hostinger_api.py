#!/usr/bin/env python3
"""
Official Docs: https://github.com/hostinger/api-python-sdk
Version: hostinger-api==0.0.19 (2026-06-02)
"""
import os
from dotenv import load_dotenv

# Load .env
load_dotenv('f:\\youtubeautoid\\.env')

api_token = os.environ.get('BEARER_TOKEN')
domain = 'xn--2e0bj1fruw33b6ti.net'

print(f'Token: {api_token[:20]}...' if api_token else 'NOT FOUND')
print(f'Domain: {domain}')

try:
    import hostinger_api
    from hostinger_api.rest import ApiException
    
    # SDK 공식 사용법
    configuration = hostinger_api.Configuration(
        access_token=api_token
    )
    
    # API 클라이언트 생성
    api_client = hostinger_api.ApiClient(configuration)
    
    # DNS Zone API
    dns_zone_api = hostinger_api.DNSZoneApi(api_client)
    
    print('\n=== Hostinger DNS Zone API 테스트 ===')
    
    # DNS 레코드 조회
    try:
        print(f'조회 대상: {domain}')
        records = dns_zone_api.get_dns_records_v1(domain)
        print(f'✅ 레코드 조회 성공')
        print(f'Response: {records}')
    except ApiException as e:
        print(f'❌ 레코드 조회 실패')
        print(f'Status: {e.status}')
        print(f'Reason: {e.reason}')
        if hasattr(e, 'body'):
            print(f'Body: {str(e.body)[:300]}')
    
except ImportError as e:
    print(f'❌ Import 오류: {e}')
except Exception as e:
    print(f'❌ 오류: {type(e).__name__}: {e}')
