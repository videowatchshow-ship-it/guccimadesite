#!/usr/bin/env python3
"""
Add A records to Hostinger DNS Zone (v3 - WITH CORRECT STRUCTURE)
Official Docs: https://github.com/hostinger/api-python-sdk

Structure Analysis:
- DNSVZoneUpdateRequest contains: zone (list of DNSVZoneUpdateRequestZoneInner)
- Each DNSVZoneUpdateRequestZoneInner represents ONE RECORD TYPE update:
  - name: The record name (@, www, etc.) OR the record domain  
  - type: The DNS record type (A, AAAA, CNAME, MX, TXT, NS, SOA, SRV, CAA)
  - records: List of actual records for this name/type combination
  - ttl: Time to live (optional)
"""
import os
from dotenv import load_dotenv

# Load .env
load_dotenv('f:\\youtubeautoid\\.env')

api_token = os.environ.get('BEARER_TOKEN')
domain = 'xn--2e0bj1fruw33b6ti.net'
vps_ip = '76.13.218.129'

print(f'API Token: {api_token[:20]}...')
print(f'Domain: {domain}')
print(f'VPS IP: {vps_ip}')

try:
    import hostinger_api
    from hostinger_api.rest import ApiException
    
    # Official SDK usage
    configuration = hostinger_api.Configuration(
        access_token=api_token
    )
    
    api_client = hostinger_api.ApiClient(configuration)
    dns_api = hostinger_api.DNSZoneApi(api_client)
    
    print('\n=== STEP 1: Check current DNS records ===')
    try:
        current_records = dns_api.get_dns_records_v1(domain)
        print(f'✅ Current records found: {len(current_records)}')
        for rec in current_records:
            name = rec.name if rec.name else "@"
            print(f'   - {name} ({rec.type}): {rec.data}')
        
        if len(current_records) == 0:
            print('   ⚠️  DNS Zone is EMPTY - Need to add A records')
    except ApiException as e:
        print(f'❌ GET failed: {e.status} - {e.reason}')
        if hasattr(e, 'body'):
            print(f'   Body: {str(e.body)[:300]}')
        raise
    
    print('\n=== STEP 2: Create DNS update request ===')
    print('Theory: Each DNSV1ZoneUpdateRequestZoneInner might be for ONE specific name/type')
    print('Trying: one zone per record (A @ and A www)')
    
    # Method 1: Overwrite all records (overwrite=true)
    # Create record for @ (root)
    record_at = hostinger_api.DNSV1ZoneUpdateRequestZoneInnerRecordsInner(
        name="@",
        type="A",
        content=vps_ip
    )
    
    # Create record for www
    record_www = hostinger_api.DNSV1ZoneUpdateRequestZoneInnerRecordsInner(
        name="www",
        type="A",
        content=vps_ip
    )
    
    # Create TWO zones, one per record name
    # Zone 1: Update A record @ 
    zone1 = hostinger_api.DNSV1ZoneUpdateRequestZoneInner(
        name="@",
        type="A",
        records=[record_at]
    )
    
    # Zone 2: Update A record www
    zone2 = hostinger_api.DNSV1ZoneUpdateRequestZoneInner(
        name="www",
        type="A",
        records=[record_www]
    )
    
    print(f'✅ Created 2 zone updates:')
    print(f'   Zone 1: @ (A) = {vps_ip}')
    print(f'   Zone 2: www (A) = {vps_ip}')
    
    # Create update request
    update_request = hostinger_api.DNSV1ZoneUpdateRequest(
        zone=[zone1, zone2]
    )
    print(f'✅ Update request prepared with 2 zones')
    
    print('\n=== STEP 3: Send DNS update to Hostinger ===')
    
    try:
        result = dns_api.update_dns_records_v1(domain, update_request)
        print(f'✅ DNS Update SUCCESSFUL!')
        print(f'   Response: {result}')
    except ApiException as e:
        print(f'❌ UPDATE FAILED: {e.status} - {e.reason}')
        if hasattr(e, 'body'):
            body_str = str(e.body)
            print(f'   Body: {body_str[:500]}')
        raise
    
    print('\n=== STEP 4: Verify records were added ===')
    try:
        updated_records = dns_api.get_dns_records_v1(domain)
        print(f'✅ Records after update: {len(updated_records)}')
        if len(updated_records) > 0:
            for rec in updated_records:
                name = rec.name if rec.name else "@"
                print(f'   - {name} ({rec.type}): {rec.data}')
        else:
            print('   ⚠️  Still empty - records might not have been added')
    except ApiException as e:
        print(f'❌ Verification failed: {e.status} - {e.reason}')
        raise
    
    if len(updated_records) >= 2:
        print('\n' + '=' * 70)
        print('SUCCESS: DNS A records added to Hostinger')
        print('=' * 70)
        print('Next steps:')
        print('1. Wait 5-10 minutes for DNS propagation')
        print('2. Test: dig @1.1.1.1 xn--2e0bj1fruw33b6ti.net A')
        print('3. Expected: 76.13.218.129')
        print('4. Then run: certbot --nginx -d xn--2e0bj1fruw33b6ti.net -d www.xn--2e0bj1fruw33b6ti.net')
    
except ImportError as e:
    print(f'❌ Import error: {e}')
    print('   Fix: pip install hostinger-api==0.0.19')
except Exception as e:
    print(f'\n❌ FATAL ERROR: {type(e).__name__}')
    print(f'   {e}')
    import traceback
    traceback.print_exc()
