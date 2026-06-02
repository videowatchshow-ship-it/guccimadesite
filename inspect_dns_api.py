#!/usr/bin/env python3
"""
Inspect DNSZoneApi methods and signatures
Official Docs: https://github.com/hostinger/api-python-sdk
"""
import inspect
import hostinger_api

api_class = hostinger_api.DNSZoneApi
methods = ['get_dns_records_v1', 'update_dns_records_v1', 'delete_dns_records_v1', 'reset_dns_records_v1']

print("=" * 80)
print("DNSZoneApi Method Signatures and Documentation")
print("=" * 80)

for method_name in methods:
    method = getattr(api_class, method_name)
    sig = inspect.signature(method)
    print(f"\n### {method_name}()")
    print(f"Signature: {sig}")
    
    # Get docstring
    doc = inspect.getdoc(method)
    if doc:
        lines = doc.split('\n')[:20]  # First 20 lines
        print(f"\nDocumentation:")
        for line in lines:
            print(f"  {line}")
    print("-" * 80)

# Also check model classes
print("\n" + "=" * 80)
print("DNS Model Classes")
print("=" * 80)

model_classes = [x for x in dir(hostinger_api) if 'Zone' in x and x.startswith('DNSV1')]
for cls_name in model_classes[:10]:
    cls = getattr(hostinger_api, cls_name)
    print(f"\n{cls_name}:")
    if hasattr(cls, 'attribute_map'):
        print(f"  Attributes: {cls.attribute_map}")
