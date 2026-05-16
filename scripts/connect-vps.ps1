# VPS 서버 접속 및 상태 확인 스크립트
# PowerShell에서 실행

# VPS 정보
$VPS_HOST = "76.13.218.129"
$VPS_USER = "root"
$VPS_PASSWORD = "1EMokhN03j9?G)8h7,pX"
$VPS_HOSTNAME = "srv1636789.hstgr.cloud"

Write-Host "=== VPS 서버 접속 정보 ===" -ForegroundColor Cyan
Write-Host "호스트: $VPS_HOST" -ForegroundColor Green
Write-Host "사용자: $VPS_USER" -ForegroundColor Green
Write-Host "호스트명: $VPS_HOSTNAME" -ForegroundColor Green
Write-Host ""

# 방법 1: SSH 키 기반 인증 (권장)
Write-Host "방법 1: SSH 키 기반 인증 (권장)" -ForegroundColor Yellow
Write-Host "ssh -i ~/.ssh/id_rsa root@${VPS_HOST}" -ForegroundColor White
Write-Host ""

# 방법 2: 비밀번호 기반 인증 (sshpass 사용)
Write-Host "방법 2: 비밀번호 기반 인증 (Linux/Mac에서)" -ForegroundColor Yellow
Write-Host "sshpass -p '${VPS_PASSWORD}' ssh root@${VPS_HOST}" -ForegroundColor White
Write-Host ""

# 방법 3: PuTTY 사용 (Windows)
Write-Host "방법 3: PuTTY 사용 (Windows)" -ForegroundColor Yellow
Write-Host "putty.exe -ssh root@${VPS_HOST} -pw '${VPS_PASSWORD}'" -ForegroundColor White
Write-Host ""

# 방법 4: WSL 사용 (Windows Subsystem for Linux)
Write-Host "방법 4: WSL 사용 (Windows Subsystem for Linux)" -ForegroundColor Yellow
Write-Host "wsl ssh root@${VPS_HOST}" -ForegroundColor White
Write-Host ""

# 방법 5: 배포 스크립트 업로드 및 실행
Write-Host "방법 5: 배포 스크립트 업로드 및 실행" -ForegroundColor Yellow
Write-Host ""
Write-Host "Step 1: 스크립트 업로드" -ForegroundColor Cyan
Write-Host "scp scripts/quick-server-check.sh root@${VPS_HOST}:/tmp/" -ForegroundColor White
Write-Host ""
Write-Host "Step 2: 서버 접속" -ForegroundColor Cyan
Write-Host "ssh root@${VPS_HOST}" -ForegroundColor White
Write-Host ""
Write-Host "Step 3: 스크립트 실행" -ForegroundColor Cyan
Write-Host "bash /tmp/quick-server-check.sh" -ForegroundColor White
Write-Host ""

# 방법 6: Hostinger API CLI 사용
Write-Host "방법 6: Hostinger API CLI 사용" -ForegroundColor Yellow
Write-Host ""
Write-Host "Step 1: API CLI 설치 (서버에서)" -ForegroundColor Cyan
Write-Host "wget https://github.com/hostinger/api-cli/releases/download/v1.x.x/hapi-v1.x.x-linux-amd64.tar.gz" -ForegroundColor White
Write-Host "tar -xf hapi-v1.x.x-linux-amd64.tar.gz" -ForegroundColor White
Write-Host "sudo mv hapi /usr/local/bin" -ForegroundColor White
Write-Host ""
Write-Host "Step 2: API 토큰 설정" -ForegroundColor Cyan
Write-Host "export HAPI_API_TOKEN=kiro2" -ForegroundColor White
Write-Host ""
Write-Host "Step 3: VPS 목록 조회" -ForegroundColor Cyan
Write-Host "hapi vps vm list" -ForegroundColor White
Write-Host ""

Write-Host "=== 권장 방법 ===" -ForegroundColor Cyan
Write-Host "1. SSH 키 기반 인증 설정 (가장 안전)" -ForegroundColor Green
Write-Host "2. WSL 또는 Git Bash 사용 (Windows에서 편함)" -ForegroundColor Green
Write-Host "3. PuTTY 사용 (GUI 기반)" -ForegroundColor Green
Write-Host ""
