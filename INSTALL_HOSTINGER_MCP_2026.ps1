# Hostinger MCP 최신 버전 설치 (2026-06-02)
# 공식: https://github.com/hostinger/api-mcp-server v0.2.3

Write-Host "="*60
Write-Host "Hostinger MCP v0.2.3 설치"
Write-Host "="*60

# 1. 기존 버전 제거
Write-Host "`n[1/4] 기존 버전 제거..."
npm uninstall -g hostinger-api-mcp 2>$null
npm uninstall -g hostinger-dns-mcp 2>$null

# 2. 최신 버전 설치
Write-Host "`n[2/4] 최신 버전 설치..."
npm install -g hostinger-api-mcp@0.2.3

# 3. DNS MCP 설치
Write-Host "`n[3/4] DNS MCP 설치..."
npm install -g hostinger-dns-mcp@0.2.3

# 4. 설치 확인
Write-Host "`n[4/4] 설치 확인..."
Write-Host "`nhostinger-api-mcp:"
hostinger-api-mcp --help
Write-Host "`nhostinger-dns-mcp:"
hostinger-dns-mcp --help

Write-Host "`n"
Write-Host "="*60
Write-Host "✅ 설치 완료!"
Write-Host "="*60
Write-Host "`n사용 가능한 명령어:"
Write-Host "  - hostinger-api-mcp"
Write-Host "  - hostinger-dns-mcp"
Write-Host "  - hostinger-billing-mcp"
Write-Host "  - hostinger-domains-mcp"
Write-Host "  - hostinger-hosting-mcp"
Write-Host "  - hostinger-reach-mcp"
Write-Host "  - hostinger-vps-mcp"
