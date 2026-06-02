#!/usr/bin/env python3
"""
Inspect DNS model classes structure
Official Docs: https://github.com/hostinger/api-python-sdk
"""
import hostinger_api
import json

# Check DNSV1ZoneUpdateRequest
print("=" * 80)
print("DNSV1ZoneUpdateRequest (for update_dns_records_v1)")
print("=" * 80)

try:
    cls = hostinger_api.DNSV1ZoneUpdateRequest
    if hasattr(cls, 'attribute_map'):
        print("\nAttribute Map:")
        for key, val in cls.attribute_map.items():
            print(f"  {key}: {val}")
    
    if hasattr(cls, 'openapi_types'):
        print("\nOpenAPI Types:")
        for key, val in cls.openapi_types.items():
            print(f"  {key}: {val}")
except Exception as e:
    print(f"Error: {e}")

# Check inner zone structure
print("\n" + "=" * 80)
print("DNSV1ZoneUpdateRequestZoneInner (zone configuration)")
print("=" * 80)

try:
    cls = hostinger_api.DNSV1ZoneUpdateRequestZoneInner
    if hasattr(cls, 'attribute_map'):
        print("\nAttribute Map:")
        for key, val in cls.attribute_map.items():
            print(f"  {key}: {val}")
    
    if hasattr(cls, 'openapi_types'):
        print("\nOpenAPI Types:")
        for key, val in cls.openapi_types.items():
            print(f"  {key}: {val}")
except Exception as e:
    print(f"Error: {e}")

# Check records structure
print("\n" + "=" * 80)
print("DNSV1ZoneUpdateRequestZoneInnerRecordsInner (DNS record)")
print("=" * 80)

try:
    cls = hostinger_api.DNSV1ZoneUpdateRequestZoneInnerRecordsInner
    if hasattr(cls, 'attribute_map'):
        print("\nAttribute Map:")
        for key, val in cls.attribute_map.items():
            print(f"  {key}: {val}")
    
    if hasattr(cls, 'openapi_types'):
        print("\nOpenAPI Types:")
        for key, val in cls.openapi_types.items():
            print(f"  {key}: {val}")
except Exception as e:
    print(f"Error: {e}")

# Check record resource
print("\n" + "=" * 80)
print("DNSV1ZoneRecordResource (record response)")
print("=" * 80)

try:
    cls = hostinger_api.DNSV1ZoneRecordResource
    if hasattr(cls, 'attribute_map'):
        print("\nAttribute Map:")
        for key, val in cls.attribute_map.items():
            print(f"  {key}: {val}")
    
    if hasattr(cls, 'openapi_types'):
        print("\nOpenAPI Types:")
        for key, val in cls.openapi_types.items():
            print(f"  {key}: {val}")
except Exception as e:
    print(f"Error: {e}")
