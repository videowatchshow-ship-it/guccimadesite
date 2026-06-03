# 관리자 권한으로 실행하세요
# 방법: 이 파일 우클릭 → "관리자 권한으로 PowerShell에서 실행"

$hostsPath = "C:\Windows\System32\drivers\etc\hosts"

# 기존 항목 제거
$content = Get-Content $hostsPath | Where-Object { $_ -notmatch "xn--2e0bj1fruw33b6ti" }
$content | Set-Content $hostsPath

# 새 항목 추가
Add-Content $hostsPath "76.13.218.129 xn--2e0bj1fruw33b6ti.net"
Add-Content $hostsPath "76.13.218.129 www.xn--2e0bj1fruw33b6ti.net"

# DNS 캐시 플러시
ipconfig /flushdns

# DNS 서버도 8.8.8.8로 변경
netsh interface ip set dns "Ethernet" static 8.8.8.8 primary
netsh interface ip add dns "Ethernet" 8.8.4.4 index=2
netsh interface ip set dns "Wi-Fi 3" static 8.8.8.8 primary
netsh interface ip add dns "Wi-Fi 3" 8.8.4.4 index=2

ipconfig /flushdns

Write-Host "완료 - 브라우저에서 https://xn--2e0bj1fruw33b6ti.net 접속하세요"
nslookup xn--2e0bj1fruw33b6ti.net
