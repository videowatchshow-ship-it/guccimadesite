#!/usr/bin/env python3
"""
Hostinger VPS 호스팅에 외부 도메인 추가
공식 문서: https://www.hostinger.com/support/1583408-can-external-domains-be-hosted-at-hostinger

이 과정에서 Hostinger가 자동으로 DNS Zone을 생성합니다.
"""
import os
import requests
from dotenv import load_dotenv

# Load .env
load_dotenv('f:\\youtubeautoid\\.env')

api_token = os.environ.get('BEARER_TOKEN')
domain = 'xn--2e0bj1fruw33b6ti.net'
vps_id = None  # VPS ID 필요 (API로 조회)

print('='*60)
print('Hostinger VPS에 외부 도메인 추가')
print('='*60)
print(f'Domain: {domain}')

base_url = 'https://developers.hostinger.com'
headers = {
    'Authorization': api_token,
    'Content-Type': 'application/json'
}

# Step 1: VPS 목록 조회
print('\n=== Step 1: VPS 조회 ===')

try:
    import hostinger_api
    from hostinger_api.rest import ApiException
    
    configuration = hostinger_api.Configuration(access_token=api_token)
    api_client = hostinger_api.ApiClient(configuration)
    
    # VPS API 클래스 찾기
    print('API에서 VPS 정보 조회 시도...')
    
    # 직접 REST API 호출
    vps_response = requests.get(f'{base_url}/api/vps/v1/virtual-machines', headers=headers)
    print(f'GET /api/vps/v1/virtual-machines: {vps_response.status_code}')
    
    if vps_response.status_code == 200:
        vps_data = vps_response.json()
        print(f'VPS 데이터: {vps_data}')
        
        # VPS ID 추출
        if isinstance(vps_data, list) and len(vps_data) > 0:
            vps_id = vps_data[0].get('id') or vps_data[0].get('service_id')
            print(f'✅ VPS ID: {vps_id}')
        elif isinstance(vps_data, dict):
            if 'data' in vps_data and len(vps_data['data']) > 0:
                vps_id = vps_data['data'][0].get('id')
                print(f'✅ VPS ID: {vps_id}')
    else:
        print(f'❌ 오류: {vps_response.status_code}')
        print(f'Response: {vps_response.text[:500]}')
    
    # Step 2: VPS에 도메인 추가
    if vps_id:
        print(f'\n=== Step 2: VPS {vps_id}에 도메인 추가 ===')
        
        # 여러 엔드포인트 시도
        endpoints = [
            f'/api/vps/v1/virtual-machines/{vps_id}/domains',
            f'/api/v1/vps/{vps_id}/domains',
            f'/api/hosting/v1/websites',
        ]
        
        payload = {
            'domain': domain,
            'domain_name': domain
        }
        
        for endpoint in endpoints:
            print(f'\n시도: POST {endpoint}')
            url = f'{base_url}{endpoint}'
            
            try:
                response = requests.post(url, json=payload, headers=headers, timeout=5)
                print(f'  Status: {response.status_code}')
                print(f'  Response: {response.text[:300]}')
                
                if response.status_code in [200, 201, 202, 204]:
                    print(f'  ✅ 성공!')
                    break
            except Exception as e:
                print(f'  오류: {e}')
    
    # Step 3: DNS Zone 생성 확인
    print(f'\n=== Step 3: DNS Zone 확인 ===')
    
    dns_response = requests.get(f'{base_url}/api/dns/v1/zones/{domain}', headers=headers)
    print(f'GET /api/dns/v1/zones/{domain}: {dns_response.status_code}')
    
    if dns_response.status_code == 200:
        dns_data = dns_response.json()
        print(f'✅ DNS Zone 생성됨')
        print(f'Records: {dns_data}')
    else:
        print(f'DNS Zone 아직 생성 안 됨 (필요시 hPanel에서 수동 추가)')
        print(f'Response: {dns_response.text[:300]}')

except Exception as e:
    print(f'❌ 오류: {type(e).__name__}: {e}')
    import traceback
    traceback.print_exc()

print('\n' + '='*60)
print('다음 단계:')
print('1. Hostinger hPanel 확인')
print('2. VPS 호스팅에 도메인이 추가되었는지 확인')
print('3. DNS Zone이 자동 생성되었는지 확인')
print('4. 확인 후 Certbot 실행')
print('='*60)
