#!/usr/bin/env python3
"""
Official Docs: https://github.com/hostinger/api-python-sdk
Version: hostinger-api==0.0.19 (2026-06-02)

목표: Hostinger DNS Zone에 A 레코드 2개 추가
- @ → 76.13.218.129
- www → 76.13.218.129
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
    
    # DNS Zone API
    dns_zone_api = hostinger_api.DNSZoneApi(api_client)
    
    print('\n=== Step 1: 기존 레코드 확인 ===')
    try:
        records = dns_zone_api.get_dns_records_v1(domain)
        print(f'기존 레코드: {len(records)}개')
        for record in records:
            print(f"  {record}")
    except ApiException as e:
        print(f'조회 오류: {e.reason}')
    
    print('\n=== Step 2: A 레코드 추가 ===')
    
    # DNS 레코드 업데이트
    try:
        # Zone 구조 설명:
        # - name: @ 또는 www (DNS 레코드 이름)
        # - type: A (DNS 레코드 타입)
        # - records: content 배열
        # - ttl: 3600
        
        zone_updates = [
            hostinger_api.DNSV1ZoneUpdateRequestZoneInner(
                name='@',
                type='A',
                ttl=3600,
                records=[
                    hostinger_api.DNSV1ZoneUpdateRequestZoneInnerRecordsInner(
                        content='76.13.218.129'
                    )
                ]
            ),
            hostinger_api.DNSV1ZoneUpdateRequestZoneInner(
                name='www',
                type='A',
                ttl=3600,
                records=[
                    hostinger_api.DNSV1ZoneUpdateRequestZoneInnerRecordsInner(
                        content='76.13.218.129'
                    )
                ]
            )
        ]
        
        # request body 구성
        update_request = hostinger_api.DNSV1ZoneUpdateRequest(
            zone=zone_updates,
            overwrite=True
        )
        
        result = dns_zone_api.update_dns_records_v1(domain, update_request)
        print(f'✅ A 레코드 추가 성공')
        print(f'Result: {result}')
        
        print('\n=== Step 3: 추가된 레코드 확인 ===')
        records = dns_zone_api.get_dns_records_v1(domain)
        print(f'현재 레코드: {len(records)}개')
        for record in records:
            print(f"  {record}")
            
    except ApiException as e:
        print(f'❌ 레코드 추가 실패')
        print(f'Status: {e.status}')
        print(f'Reason: {e.reason}')
        print(f'Body: {str(e.body)[:500]}')
    
except Exception as e:
    print(f'❌ 오류: {type(e).__name__}: {e}')
    import traceback
    traceback.print_exc()
