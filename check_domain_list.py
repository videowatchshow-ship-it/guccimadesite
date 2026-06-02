#!/usr/bin/env python3
"""
Hostinger에 등록된 도메인 목록 확인
"""
import os
from dotenv import load_dotenv

# Load .env
load_dotenv('f:\\youtubeautoid\\.env')

api_token = os.environ.get('BEARER_TOKEN')
domain_target = 'xn--2e0bj1fruw33b6ti.net'

print(f'API Token: {api_token[:20]}...')

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
    
    print('\n=== Hostinger 도메인 목록 ===')
    
    try:
        # 도메인 목록 조회
        domain_list = domains_api.get_domain_list_v1()
        print(f'Response type: {type(domain_list)}')
        print(f'Response: {domain_list}')
        
        # List인 경우 처리
        if isinstance(domain_list, list):
            print(f'등록된 도메인: {len(domain_list)}개')
            for domain_obj in domain_list:
                print(f'\n  도메인: {domain_obj.domain if hasattr(domain_obj, "domain") else domain_obj}')
                if hasattr(domain_obj, 'domain'):
                    if domain_obj.domain == domain_target:
                        print(f'    ✅ 찾는 도메인!')
                
    except ApiException as e:
        print(f'❌ 조회 실패')
        print(f'Status: {e.status}')
        print(f'Reason: {e.reason}')
        print(f'Body: {str(e.body)[:500]}')
    
except Exception as e:
    print(f'❌ 오류: {type(e).__name__}: {e}')
    import traceback
    traceback.print_exc()
