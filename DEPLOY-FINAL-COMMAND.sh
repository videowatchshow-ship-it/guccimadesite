#!/bin/bash

# 🚀 최종 배포 명령어
# Hostinger Browser Terminal에서 실행

# VPS 정보
VPS_HOST="76.13.218.129"
VPS_HOSTNAME="srv1636789.hstgr.cloud"

# 배포 스크립트 다운로드 및 실행
curl -O https://raw.githubusercontent.com/videowatchshow-ship-it/guccimadesite/main/scripts/auto-deploy.sh && \
chmod +x auto-deploy.sh && \
bash auto-deploy.sh
