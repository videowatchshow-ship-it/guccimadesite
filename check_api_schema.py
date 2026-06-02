#!/usr/bin/env python3
import hostinger_api
import json

# 모델 정보 확인
schema = hostinger_api.DNSV1ZoneUpdateRequest.model_json_schema()
print("=== DNSV1ZoneUpdateRequest Schema ===")
print(json.dumps(schema, indent=2))
