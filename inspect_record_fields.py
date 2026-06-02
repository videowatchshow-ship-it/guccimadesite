#!/usr/bin/env python3
"""
Inspect exact fields of DNS record class
"""
import hostinger_api

cls = hostinger_api.DNSV1ZoneUpdateRequestZoneInnerRecordsInner

print("=" * 70)
print("DNSV1ZoneUpdateRequestZoneInnerRecordsInner - Field Analysis")
print("=" * 70)

# Method 1: Pydantic V2 __pydantic_fields__
if hasattr(cls, '__pydantic_fields__'):
    print("\n✅ Pydantic V2 detected:")
    print("\nFields:")
    for name, field_info in cls.__pydantic_fields__.items():
        required = field_info.is_required()
        annotation = field_info.annotation
        print(f"  {name}: {annotation} (required={required})")

# Method 2: Try to create instance and see what's needed
print("\n\nExample object creation (trying different combinations):")

# Try with different field names
test_cases = [
    {"name": "@", "type": "A", "content": "76.13.218.129"},
    {"name": "@", "type": "A", "data": "76.13.218.129"},
    {"type": "A", "content": "76.13.218.129"},
]

for i, params in enumerate(test_cases, 1):
    try:
        obj = hostinger_api.DNSV1ZoneUpdateRequestZoneInnerRecordsInner(**params)
        print(f"\n✅ Test {i} SUCCESS:")
        print(f"   Parameters: {params}")
        print(f"   Created: {obj}")
        break
    except Exception as e:
        error_msg = str(e).split('\n')[0]
        print(f"\n❌ Test {i} FAILED:")
        print(f"   Parameters: {params}")
        print(f"   Error: {error_msg[:100]}")
