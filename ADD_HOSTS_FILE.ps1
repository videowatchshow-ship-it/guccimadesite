# Windows hosts 파일에 도메인 추가 (관리자 권한 필요)
# 참조: https://docs.microsoft.com/en-us/troubleshoot/windows-server/networking/modify-hosts-file

$hostsPath = "C:\Windows\System32\drivers\etc\hosts"
$domain = "xn--2e0bj1fruw33b6ti.net"
$domainKR = "구찌야놀자.net"
$ip = "76.13.218.129"

Write-Host "================================================================================"
Write-Host "Windows hosts 파일에 도메인 추가"
Write-Host "================================================================================"
Write-Host ""
Write-Host "참조 문서: https://docs.microsoft.com/en-us/troubleshoot/windows-server/networking/modify-hosts-file"
Write-Host ""

# 관리자 권한 확인
$isAdmin = ([Security.Principal.WindowsPrincipal][Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)

if (-not $isAdmin) {
    Write-Host "❌ 관리자 권한 필요"
    Write-Host ""
    Write-Host "이 스크립트를 관리자 권한으로 실행하세요:"
    Write-Host "1. PowerShell을 관리자 권한으로 실행"
    Write-Host "2. cd f:\youtubeautoid"
    Write-Host "3. .\ADD_HOSTS_FILE.ps1"
    exit 1
}

Write-Host "[1] 현재 hosts 파일 확인"
Write-Host "--------------------------------------------------------------------------------"

if (Test-Path $hostsPath) {
    $content = Get-Content $hostsPath
    Write-Host "✅ hosts 파일 존재: $hostsPath"
    Write-Host "현재 줄 수: $($content.Count)"
} else {
    Write-Host "❌ hosts 파일 없음"
    exit 1
}

Write-Host ""
Write-Host "[2] 도메인 추가"
Write-Host "--------------------------------------------------------------------------------"

# 백업
$backupPath = "$hostsPath.backup"
Copy-Item $hostsPath $backupPath -Force
Write-Host "✅ 백업 생성: $backupPath"

# 기존 항목 확인
$domainExists = $content | Select-String -Pattern $domain -Quiet
$domainKRExists = $content | Select-String -Pattern $domainKR -Quiet

if ($domainExists) {
    Write-Host "⚠️ $domain 이미 존재 - 업데이트"
    # 기존 줄 제거
    $content = $content | Where-Object { $_ -notmatch $domain }
} else {
    Write-Host "✅ $domain 추가 중"
}

if ($domainKRExists) {
    Write-Host "⚠️ $domainKR 이미 존재 - 업데이트"
    $content = $content | Where-Object { $_ -notmatch [regex]::Escape($domainKR) }
} else {
    Write-Host "✅ $domainKR 추가 중"
}

# 새 항목 추가
$newEntries = @"

# 구찌야놀자.net (추가: $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss'))
$ip $domain
$ip www.$domain
$ip $domainKR
$ip www.$domainKR
"@

$content += $newEntries

# 파일 저장
$content | Set-Content $hostsPath -Force
Write-Host "✅ hosts 파일 업데이트 완료"

Write-Host ""
Write-Host "[3] DNS 캐시 초기화"
Write-Host "--------------------------------------------------------------------------------"

try {
    ipconfig /flushdns | Out-Null
    Write-Host "✅ DNS 캐시 초기화 완료"
} catch {
    Write-Host "⚠️ DNS 캐시 초기화 실패: $_"
}

Write-Host ""
Write-Host "[4] 접속 테스트"
Write-Host "--------------------------------------------------------------------------------"

Write-Host "도메인으로 접속 가능:"
Write-Host "  ✅ https://$domain/"
Write-Host "  ✅ https://www.$domain/"
Write-Host "  ✅ https://$domainKR/"
Write-Host "  ✅ https://www.$domainKR/"

Write-Host ""
Write-Host "================================================================================"
Write-Host "완료"
Write-Host "================================================================================"
Write-Host ""
Write-Host "✅ 이제 Chrome에서 https://구찌야놀자.net/ 접속하세요!"
Write-Host "⚠️ Chrome 경고: '고급' → '이동' 클릭"
