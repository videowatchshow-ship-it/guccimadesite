#!/usr/bin/env python3
"""
Automated DNS Records Addition to Hostinger
This script will run automatically AFTER domain is added to hPanel

Official Docs: https://github.com/hostinger/api-python-sdk
Version: hostinger-api==0.0.19
Status: Ready for deployment

Prerequisites:
1. ✅ Domain added to Hostinger hPanel (Website/Hosting)
2. ✅ GoDaddy NS changed to Hostinger (NS1-4.HOSTINGER.COM)
3. ✅ Hostinger API token in .env (BEARER_TOKEN)

Usage:
  python AUTO_ADD_DNS_RECORDS.py
"""
import os
import sys
import time
from dotenv import load_dotenv

# Configuration
load_dotenv('f:\\youtubeautoid\\.env')

API_TOKEN = os.environ.get('BEARER_TOKEN')
DOMAIN = 'xn--2e0bj1fruw33b6ti.net'
VPS_IP = '76.13.218.129'
MAX_RETRIES = 3
RETRY_DELAY = 5  # seconds

print("=" * 70)
print("HOSTINGER DNS RECORDS AUTOMATION")
print("=" * 70)
print(f"\nConfiguration:")
print(f"  Domain: {DOMAIN}")
print(f"  VPS IP: {VPS_IP}")
print(f"  API Token: {API_TOKEN[:20]}..." if API_TOKEN else "  API Token: NOT FOUND!")

if not API_TOKEN:
    print("\n❌ FATAL: API token not found in .env")
    print("   Add BEARER_TOKEN to .env and retry")
    sys.exit(1)

try:
    import hostinger_api
    from hostinger_api.rest import ApiException
    
    # Initialize API
    config = hostinger_api.Configuration(access_token=API_TOKEN)
    api_client = hostinger_api.ApiClient(config)
    dns_api = hostinger_api.DNSZoneApi(api_client)
    
    print("\n✅ Hostinger API client initialized")
    
    # ========== STEP 1: Check current DNS records ==========
    print("\n" + "=" * 70)
    print("STEP 1: Checking current DNS records")
    print("=" * 70)
    
    try:
        current_records = dns_api.get_dns_records_v1(DOMAIN)
        print(f"\n✅ GET DNS records successful")
        print(f"   Current records: {len(current_records)}")
        
        if len(current_records) > 0:
            print("\n   Existing records:")
            for rec in current_records:
                name = rec.name if rec.name else "@"
                print(f"     - {name} ({rec.type}): {rec.data}")
        else:
            print("\n   ⚠️  Zone is empty (no records)")
            
    except ApiException as e:
        if e.status == 404:
            print(f"\n❌ CRITICAL: Domain not found in Hostinger account")
            print(f"   Error: [DNS:4009] Domain not found")
            print(f"\n   Action required: Add domain to Hostinger hPanel")
            print(f"   Path: VPS → Manage → Websites → Add Website/Domain")
            print(f"   Domain: {DOMAIN}")
            print(f"\n   After adding, run this script again.")
            sys.exit(1)
        else:
            print(f"\n❌ GET failed: {e.status} - {e.reason}")
            raise
    
    # ========== STEP 2: Prepare DNS records ==========
    print("\n" + "=" * 70)
    print("STEP 2: Preparing DNS records")
    print("=" * 70)
    
    # Create A record for @ (root domain)
    record_at = hostinger_api.DNSV1ZoneUpdateRequestZoneInnerRecordsInner(
        name="@",
        type="A",
        content=VPS_IP
    )
    print(f"\n✅ Record 1: @ (A) = {VPS_IP}")
    
    # Create A record for www
    record_www = hostinger_api.DNSV1ZoneUpdateRequestZoneInnerRecordsInner(
        name="www",
        type="A",
        content=VPS_IP
    )
    print(f"✅ Record 2: www (A) = {VPS_IP}")
    
    # ========== STEP 3: Create zone update requests ==========
    print("\n" + "=" * 70)
    print("STEP 3: Creating zone update requests")
    print("=" * 70)
    
    # Zone for @ record
    zone_at = hostinger_api.DNSV1ZoneUpdateRequestZoneInner(
        name="@",
        type="A",
        records=[record_at]
    )
    
    # Zone for www record
    zone_www = hostinger_api.DNSV1ZoneUpdateRequestZoneInner(
        name="www",
        type="A",
        records=[record_www]
    )
    
    # Create update request
    update_request = hostinger_api.DNSV1ZoneUpdateRequest(zone=[zone_at, zone_www])
    
    print(f"\n✅ Update request prepared with 2 zones")
    
    # ========== STEP 4: Send DNS update ==========
    print("\n" + "=" * 70)
    print("STEP 4: Sending DNS records update to Hostinger")
    print("=" * 70)
    
    retry_count = 0
    success = False
    
    while retry_count < MAX_RETRIES and not success:
        try:
            result = dns_api.update_dns_records_v1(DOMAIN, update_request)
            print(f"\n✅ DNS Update SUCCESSFUL!")
            print(f"   Response: {result}")
            success = True
            
        except ApiException as e:
            retry_count += 1
            if e.status == 404:
                print(f"\n❌ RETRY {retry_count}/{MAX_RETRIES}: Domain not found")
                print(f"   Error: {e.reason}")
                if retry_count < MAX_RETRIES:
                    print(f"   Waiting {RETRY_DELAY} seconds before retry...")
                    time.sleep(RETRY_DELAY)
                else:
                    print(f"\n❌ Max retries exceeded. Domain may not be linked to account yet.")
                    print(f"   Please ensure domain is added in Hostinger hPanel:")
                    print(f"   Path: VPS → Manage → Websites → Add Website/Domain")
                    sys.exit(1)
            else:
                print(f"\n❌ UPDATE failed: {e.status} - {e.reason}")
                if hasattr(e, 'body'):
                    print(f"   Body: {str(e.body)[:300]}")
                raise
    
    if not success:
        sys.exit(1)
    
    # ========== STEP 5: Verify records ==========
    print("\n" + "=" * 70)
    print("STEP 5: Verifying records were added")
    print("=" * 70)
    
    try:
        updated_records = dns_api.get_dns_records_v1(DOMAIN)
        print(f"\n✅ Verification GET successful")
        print(f"   Records after update: {len(updated_records)}")
        
        if len(updated_records) > 0:
            print("\n   New records:")
            for rec in updated_records:
                name = rec.name if rec.name else "@"
                print(f"     - {name} ({rec.type}): {rec.data}")
        else:
            print("\n   ⚠️  Still empty - records may take time to appear")
            
    except ApiException as e:
        print(f"\n❌ Verification failed: {e.status} - {e.reason}")
        raise
    
    # ========== SUCCESS ==========
    print("\n" + "=" * 70)
    print("SUCCESS: DNS records addition complete!")
    print("=" * 70)
    
    print(f"""
Next steps:

1. Wait 5-10 minutes for DNS propagation

2. Verify DNS resolution:
   dig @1.1.1.1 {DOMAIN} A
   dig @1.1.1.1 www.{DOMAIN} A
   
   Expected response: {VPS_IP}

3. After DNS propagates, run Certbot:
   certbot --nginx -d {DOMAIN} -d www.{DOMAIN}

4. Verify HTTPS:
   curl -I https://{DOMAIN}
   curl -I https://www.{DOMAIN}

Status: ✅ Ready for SSL certificate installation
""")
    
except ImportError as e:
    print(f"\n❌ Import error: {e}")
    print("   Fix: pip install hostinger-api==0.0.19")
    sys.exit(1)
    
except Exception as e:
    print(f"\n❌ FATAL ERROR: {type(e).__name__}")
    print(f"   {e}")
    import traceback
    traceback.print_exc()
    sys.exit(1)
