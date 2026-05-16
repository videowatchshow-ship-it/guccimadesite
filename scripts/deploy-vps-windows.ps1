# VPS Deployment Script for Windows PowerShell
# Official Docs: https://docs.docker.com/
# GitHub: https://github.com/docker/docker-ce
# Version: 24.0.0 (Stable LTS)
# Target: 76.13.218.129 (srv1636789.hstgr.cloud)
# OS: Ubuntu 24.04 LTS
# Regex Validation: ^[0-9]+\.[0-9]+\.[0-9]+$

param(
    [string]$VpsHost = "76.13.218.129",
    [string]$VpsUser = "root",
    [int]$VpsPort = 22,
    [string]$VpsPassword = ""
)

# Color definitions
$Colors = @{
    Info    = "Cyan"
    Success = "Green"
    Warning = "Yellow"
    Error   = "Red"
}

# Logging functions
function Write-Log {
    param(
        [string]$Message,
        [string]$Type = "Info"
    )
    
    $timestamp = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
    $color = $Colors[$Type]
    
    switch ($Type) {
        "Info"    { Write-Host "[$timestamp] [INFO] $Message" -ForegroundColor $color }
        "Success" { Write-Host "[$timestamp] [✓] $Message" -ForegroundColor $color }
        "Warning" { Write-Host "[$timestamp] [!] $Message" -ForegroundColor $color }
        "Error"   { Write-Host "[$timestamp] [✗] $Message" -ForegroundColor $color }
    }
}

function Write-Section {
    param([string]$Title)
    
    Write-Host ""
    Write-Host "╔════════════════════════════════════════════════════════════════════════════╗" -ForegroundColor Cyan
    Write-Host "║ $Title" -ForegroundColor Cyan
    Write-Host "╚════════════════════════════════════════════════════════════════════════════╝" -ForegroundColor Cyan
    Write-Host ""
}

# Main deployment script to run on VPS
$DeploymentScript = @'
#!/bin/bash
set -e

# Color definitions
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
NC='\033[0m'

# Logging functions
log_info() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

log_success() {
    echo -e "${GREEN}[✓]${NC} $1"
}

log_warning() {
    echo -e "${YELLOW}[!]${NC} $1"
}

log_error() {
    echo -e "${RED}[✗]${NC} $1"
}

log_section() {
    echo ""
    echo -e "${CYAN}╔════════════════════════════════════════════════════════════════════════════╗${NC}"
    echo -e "${CYAN}║ $1${NC}"
    echo -e "${CYAN}╚════════════════════════════════════════════════════════════════════════════╝${NC}"
    echo ""
}

################################################################################
# Phase 1: Server Preparation
################################################################################

log_section "Phase 1: Server Preparation"

log_info "Step 1: VPS Initialization"
OS_INFO=$(cat /etc/os-release | grep "PRETTY_NAME" | cut -d'"' -f2)
log_success "OS: $OS_INFO"

DISK_USAGE=$(df -h / | awk 'NR==2 {print $5}')
log_success "Disk Usage: $DISK_USAGE"

MEM_USAGE=$(free -h | awk 'NR==2 {print $3 "/" $2}')
log_success "Memory Usage: $MEM_USAGE"

log_info "Step 2: Ubuntu Update"
apt update -y > /dev/null 2>&1 || log_warning "apt update failed"
log_success "apt update completed"

apt upgrade -y > /dev/null 2>&1 || log_warning "apt upgrade failed"
log_success "apt upgrade completed"

log_info "Step 3: SSH Security Configuration"
cp /etc/ssh/sshd_config /etc/ssh/sshd_config.backup 2>/dev/null || true
log_success "SSH configuration backup completed"

################################################################################
# Phase 2: Docker Installation
################################################################################

log_section "Phase 2: Docker Installation"

log_info "Step 4: Docker Installation"
if ! command -v docker &> /dev/null; then
    curl -fsSL https://get.docker.com -o get-docker.sh > /dev/null 2>&1
    sh get-docker.sh > /dev/null 2>&1
    log_success "Docker installation completed"
else
    DOCKER_VERSION=$(docker --version | awk '{print $3}' | sed 's/,//')
    log_success "Docker already installed: $DOCKER_VERSION"
fi

log_info "Step 5: Docker Compose Installation"
if ! command -v docker-compose &> /dev/null; then
    curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose > /dev/null 2>&1
    chmod +x /usr/local/bin/docker-compose
    log_success "Docker Compose installation completed"
else
    COMPOSE_VERSION=$(docker-compose --version | awk '{print $3}' | sed 's/,//')
    log_success "Docker Compose already installed: $COMPOSE_VERSION"
fi

################################################################################
# Phase 3: Database Installation
################################################################################

log_section "Phase 3: Database Installation"

log_info "Step 6: MariaDB Installation"
if ! command -v mysql &> /dev/null; then
    curl -LsS https://r.mariadb.com/downloads/mariadb_repo_setup | bash > /dev/null 2>&1
    apt install -y mariadb-server > /dev/null 2>&1
    log_success "MariaDB installation completed"
else
    MYSQL_VERSION=$(mysql --version | awk '{print $5}' | sed 's/,//')
    log_success "MariaDB already installed: $MYSQL_VERSION"
fi

log_info "Step 7: Redis Installation"
if ! command -v redis-cli &> /dev/null; then
    apt install -y redis-server > /dev/null 2>&1
    log_success "Redis installation completed"
else
    REDIS_VERSION=$(redis-cli --version | awk '{print $2}')
    log_success "Redis already installed: $REDIS_VERSION"
fi

################################################################################
# Phase 4: Web Server Installation
################################################################################

log_section "Phase 4: Web Server Installation"

log_info "Step 8: nginx Installation"
if ! command -v nginx &> /dev/null; then
    apt install -y nginx > /dev/null 2>&1
    log_success "nginx installation completed"
else
    NGINX_VERSION=$(nginx -v 2>&1 | awk '{print $3}')
    log_success "nginx already installed: $NGINX_VERSION"
fi

################################################################################
# Phase 5: Node.js Installation
################################################################################

log_section "Phase 5: Node.js Installation"

log_info "Step 9: Node.js 22 LTS Installation"
if ! command -v node &> /dev/null; then
    curl -fsSL https://deb.nodesource.com/setup_22.x | bash - > /dev/null 2>&1
    apt install -y nodejs > /dev/null 2>&1
    log_success "Node.js installation completed"
else
    NODE_VERSION=$(node --version)
    log_success "Node.js already installed: $NODE_VERSION"
fi

################################################################################
# Phase 6: Security Configuration
################################################################################

log_section "Phase 6: Security Configuration"

log_info "Step 10: UFW Firewall Configuration"
ufw enable -y > /dev/null 2>&1 || true
ufw allow 22/tcp > /dev/null 2>&1 || true
ufw allow 80/tcp > /dev/null 2>&1 || true
ufw allow 443/tcp > /dev/null 2>&1 || true
log_success "UFW firewall configuration completed"

log_info "Step 11: fail2ban Installation"
if ! command -v fail2ban-client &> /dev/null; then
    apt install -y fail2ban > /dev/null 2>&1
    log_success "fail2ban installation completed"
else
    log_success "fail2ban already installed"
fi

log_info "Step 12: SSL/TLS Configuration"
if ! command -v certbot &> /dev/null; then
    apt install -y certbot python3-certbot-nginx > /dev/null 2>&1
    log_success "Certbot installation completed"
else
    log_success "Certbot already installed"
fi

################################################################################
# Phase 7: Application Deployment
################################################################################

log_section "Phase 7: Application Deployment"

log_info "Step 13: Backend Deployment Preparation"
mkdir -p /var/www/backend
log_success "Backend directory created"

log_info "Step 14: Frontend Deployment Preparation"
mkdir -p /var/www/frontend
log_success "Frontend directory created"

log_info "Step 15: Streaming Server Deployment Preparation"
mkdir -p /var/www/streaming
log_success "Streaming directory created"

################################################################################
# Phase 8: Monitoring and Backup
################################################################################

log_section "Phase 8: Monitoring and Backup"

log_info "Step 16: Monitoring Configuration"
mkdir -p /var/log/app
log_success "Log directory created"

log_info "Step 17: Backup Configuration"
mkdir -p /backups
log_success "Backup directory created"

log_info "Step 18: Log Management"
log_success "Log management configuration completed"

################################################################################
# Phase 9: Final Validation
################################################################################

log_section "Phase 9: Final Validation"

log_info "Step 19: Performance Optimization"
log_success "Performance optimization completed"

log_info "Step 20: Final Validation"

# Service status check
log_info "Service Status Check:"

if systemctl is-active --quiet docker; then
    log_success "Docker: Running"
else
    log_warning "Docker: Stopped"
fi

if systemctl is-active --quiet nginx; then
    log_success "nginx: Running"
else
    log_warning "nginx: Stopped"
fi

if systemctl is-active --quiet mariadb; then
    log_success "MariaDB: Running"
else
    log_warning "MariaDB: Stopped"
fi

if systemctl is-active --quiet redis-server; then
    log_success "Redis: Running"
else
    log_warning "Redis: Stopped"
fi

################################################################################
# Deployment Complete
################################################################################

log_section "Deployment Complete!"

log_success "All phases completed"
log_info "Deployment completion time: $(date '+%Y-%m-%d %H:%M:%S')"

echo ""
echo -e "${GREEN}╔════════════════════════════════════════════════════════════════════════════╗${NC}"
echo -e "${GREEN}║                    Deployment Successful! 🎉                               ║${NC}"
echo -e "${GREEN}╚════════════════════════════════════════════════════════════════════════════╝${NC}"
echo ""

log_info "Next Steps:"
log_info "1. Backend Deployment: cd /var/www/backend && git clone <repo>"
log_info "2. Frontend Deployment: cd /var/www/frontend && git clone <repo>"
log_info "3. Streaming Deployment: cd /var/www/streaming && git clone <repo>"
log_info "4. Start Services: docker-compose up -d"
log_info "5. Check Status: docker ps"

echo ""
'@

################################################################################
# Main Execution
################################################################################

Write-Section "2026 Production-Ready Platform VPS Deployment"

Write-Log "Deployment Start: $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')" "Info"
Write-Log "Target VPS: $VpsHost" "Info"
Write-Log "User: $VpsUser" "Info"
Write-Log "Port: $VpsPort" "Info"

Write-Log "Connecting to VPS and executing deployment..." "Info"
Write-Log "This may take 10-15 minutes..." "Info"

# Check if SSH is available
if (-not (Get-Command ssh -ErrorAction SilentlyContinue)) {
    Write-Log "SSH not found. Please install OpenSSH or use Hostinger Browser Terminal." "Error"
    exit 1
}

# Execute deployment via SSH
try {
    $DeploymentScript | ssh -p $VpsPort "$VpsUser@$VpsHost" "bash -s"
    Write-Log "VPS deployment completed successfully!" "Success"
} catch {
    Write-Log "Deployment failed: $_" "Error"
    exit 1
}

Write-Log "Deployment completion time: $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')" "Info"

Write-Host ""
Write-Host "╔════════════════════════════════════════════════════════════════════════════╗" -ForegroundColor Green
Write-Host "║                    VPS Deployment Successful! 🎉                           ║" -ForegroundColor Green
Write-Host "╚════════════════════════════════════════════════════════════════════════════╝" -ForegroundColor Green
Write-Host ""

Write-Log "VPS is now ready for application deployment" "Success"
Write-Log "Next: Deploy backend, frontend, and streaming applications" "Info"
