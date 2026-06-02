#!/usr/bin/env python3
"""
Test adding DNS records to Hostinger
Official Docs: https://github.com/hostinger/api-python-sdk
Version: hostinger-api==0.0.19
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
    
    # Official SDK usage
    configuration = hostinger_api.Configuration(
        access_token=api_token
    )
    
    api_client = hostinger_api.ApiClient(configuration)
    dns_api = hostinger_api.DNSZoneApi(api_client)
    
    print('\n=== STEP 1: Check current DNS records ===')
    try:
        current_records = dns_api.get_dns_records_v1(domain)
        print(f'✅ Current records: {len(current_records)} records found')
        for rec in current_records:
            print(f'   - {rec.name or "@"} ({rec.type}): {rec.data}')
    except ApiException as e:
        print(f'❌ GET failed: {e.status} - {e.reason}')
        if hasattr(e, 'body'):
            print(f'   Body: {str(e.body)[:200]}')
    
    print('\n=== STEP 2: Create DNS update request ===')
    
    # Method 1: Try direct zone update
    try:
        # Create zone update request
        update_request = hostinger_api.DNSV1ZoneUpdateRequest()
        
        # Try to understand the structure
        print(f'DNSV1ZoneUpdateRequest created')
        print(f'Dir: {[x for x in dir(update_request) if not x.startswith("_")][:10]}')
        
        # Try setting zone with records
        zone = hostinger_api.DNSV1ZoneUpdateRequestZoneInner()
        print(f'\nDNSV1ZoneUpdateRequestZoneInner created')
        print(f'Dir: {[x for x in dir(zone) if not x.startswith("_")][:10]}')
        
        # Try creating a record
        record = hostinger_api.DNSV1ZoneUpdateRequestZoneInnerRecordsInner()
        print(f'\nDNSV1ZoneUpdateRequestZoneInnerRecordsInner created')
        print(f'Dir (settable): {[x for x in dir(record) if not x.startswith("_") and x.islower()][:15]}')
        
        # Try to set record attributes
        try:
            record.name = "@"
            record.type = "A"
            record.data = "76.13.218.129"
            print(f'✅ Record attributes set: {record.name} ({record.type}) = {record.data}')
        except Exception as e:
            print(f'❌ Failed to set record attributes: {e}')
        
        # Try to add record to zone
        try:
            zone.records = [record]
            print(f'✅ Records added to zone: {len(zone.records)} record(s)')
        except Exception as e:
            print(f'❌ Failed to add records to zone: {e}')
        
        # Try to add zone to update request
        try:
            update_request.zone = [zone]
            print(f'✅ Zone added to update request')
        except Exception as e:
            print(f'❌ Failed to add zone: {e}')
        
        # Try update
        print('\n=== STEP 3: Send DNS update ===')
        try:
            result = dns_api.update_dns_records_v1(domain, update_request)
            print(f'✅ Update successful: {result}')
        except ApiException as e:
            print(f'❌ UPDATE failed: {e.status} - {e.reason}')
            if hasattr(e, 'body'):
                print(f'   Body: {str(e.body)[:300]}')
        
    except Exception as e:
        print(f'❌ Request creation error: {type(e).__name__}: {e}')
        import traceback
        traceback.print_exc()
    
except ImportError as e:
    print(f'❌ Import error: {e}')
except Exception as e:
    print(f'❌ Error: {type(e).__name__}: {e}')
    import traceback
    traceback.print_exc()
