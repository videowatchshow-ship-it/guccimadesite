# 2026 Production-Ready Platform - Official VPS Deployment Guide

**Official Docs:** https://docs.docker.com/  
**GitHub:** https://github.com/docker/docker-ce  
**Version:** 24.0.0 (Stable LTS)  
**Target:** 76.13.218.129 (srv1636789.hstgr.cloud)  
**OS:** Ubuntu 24.04 LTS  

---

## ⚠️ CRITICAL: Deployment Method

**DO NOT run the deployment script on your local Windows machine.**

The deployment script must run **ON THE VPS SERVER**, not on your local computer.

### Why?
- Local Windows PowerShell doesn't have Ubuntu commands (`hostname -I`, `free`, `apt`, etc.)
- The script needs to execute on Ubuntu 24.04 LTS running on the VPS
- Running it locally causes errors like: `hostname: unknown option`, `/etc/os-release: No such file`

---

## ✅ CORRECT Deployment Methods

### Method 1: Hostinger Browser Terminal (RECOMMENDED - No SSH Client Needed)

**Official Hostinger Docs:** https://support.hostinger.com/en/articles/4291307-how-to-access-the-vps-terminal

1. **Access Hostinger hPanel:**
   - Go to https://hpanel.hostinger.com
   - Login with your credentials

2. **Navigate to VPS:**
   - Click "VPS" in the left sidebar
   - Select "srv1636789.hstgr.cloud"
   - Click "Manage"

3. **Open Browser Terminal:**
   - Click the "Terminal" button (top right)
   - A terminal window opens in your browser

4. **Execute Deployment:**
   ```bash
   curl -O https://raw.githubusercontent.com/videowatchshow-ship-it/guccimadesite/main/scripts/auto-deploy.sh && chmod +x auto-deploy.sh && bash auto-deploy.sh
   ```

5. **Monitor Deployment:**
   - Watch the terminal for real-time logs
   - Deployment takes 10-15 minutes
   - All 20 phases will display with status indicators

---

### Method 2: SSH from Windows PowerShell (Requires OpenSSH)

**Official OpenSSH Docs:** https://learn.microsoft.com/en-us/windows-server/administration/openssh/openssh_overview

#### Step 1: Install OpenSSH (if not already installed)

```powershell
# Check if SSH is installed
ssh -V

# If not installed, install via Windows Package Manager
winget install OpenSSH.Client

# Or install via Chocolatey
choco install openssh
```

#### Step 2: Connect to VPS via SSH

```powershell
ssh -p 22 root@76.13.218.129
```

**VPS Credentials:**
- Host: `76.13.218.129`
- User: `root`
- Password: `1EMokhN03j9?G)8h7,pX`

#### Step 3: Execute Deployment Script

Once connected to the VPS via SSH:

```bash
curl -O https://raw.githubusercontent.com/videowatchshow-ship-it/guccimadesite/main/scripts/auto-deploy.sh && chmod +x auto-deploy.sh && bash auto-deploy.sh
```

---

### Method 3: PowerShell Script (Automated SSH)

**Official PowerShell Docs:** https://learn.microsoft.com/en-us/powershell/

If you have OpenSSH installed, use the PowerShell deployment script:

```powershell
# Navigate to project directory
cd e:\guccimadesite

# Run deployment script
.\scripts\deploy-vps-windows.ps1 -VpsHost "76.13.218.129" -VpsUser "root" -VpsPort 22
```

---

## 📊 Deployment Phases (20 Total)

The deployment script executes 20 phases:

### Phase 1: Server Preparation (Steps 1-3)
- VPS Initialization
- Ubuntu Update
- SSH Security Configuration

### Phase 2: Docker Installation (Steps 4-5)
- Docker Installation
- Docker Compose Installation

### Phase 3: Database Installation (Steps 6-7)
- MariaDB Installation
- Redis Installation

### Phase 4: Web Server Installation (Step 8)
- nginx Installation

### Phase 5: Node.js Installation (Step 9)
- Node.js 22 LTS Installation

### Phase 6: Security Configuration (Steps 10-12)
- UFW Firewall Configuration
- fail2ban Installation
- SSL/TLS Configuration (Certbot)

### Phase 7: Application Deployment (Steps 13-15)
- Backend Directory Creation
- Frontend Directory Creation
- Streaming Directory Creation

### Phase 8: Monitoring and Backup (Steps 16-18)
- Monitoring Configuration
- Backup Configuration
- Log Management

### Phase 9: Final Validation (Steps 19-20)
- Performance Optimization
- Final Validation & Service Status Check

---

## 🔍 Expected Output

When deployment runs successfully, you'll see:

```
╔════════════════════════════════════════════════════════════════════════════╗
║ 2026 Production-Ready Platform Auto-Deployment                            ║
╚════════════════════════════════════════════════════════════════════════════╝

[INFO] Deployment Start: 2026-05-17 04:00:00
[INFO] Host: srv1636789.hstgr.cloud
[INFO] IP: 76.13.218.129

╔════════════════════════════════════════════════════════════════════════════╗
║ Phase 1: Server Preparation                                               ║
╚════════════════════════════════════════════════════════════════════════════╝

[INFO] Step 1: VPS Initialization
[✓] OS: Ubuntu 24.04.1 LTS
[✓] Disk Usage: 15%
[✓] Memory Usage: 1.2G / 4G

[INFO] Step 2: Ubuntu Update
[✓] apt update completed
[✓] apt upgrade completed

... (continues through all 20 phases)

╔════════════════════════════════════════════════════════════════════════════╗
║                    Deployment Successful! 🎉                              ║
╚════════════════════════════════════════════════════════════════════════════╝

[✓] All phases completed
[INFO] Deployment completion time: 2026-05-17 04:15:30
```

---

## ✅ Verification After Deployment

After deployment completes, verify services are running:

```bash
# Check Docker
docker --version
docker ps

# Check nginx
nginx -v
systemctl status nginx

# Check MariaDB
mysql --version
systemctl status mariadb

# Check Redis
redis-cli --version
systemctl status redis-server

# Check Node.js
node --version
npm --version
```

---

## 🚀 Next Steps After Deployment

1. **Deploy Backend Application:**
   ```bash
   cd /var/www/backend
   git clone https://github.com/videowatchshow-ship-it/guccimadesite.git
   cd guccimadesite
   npm install
   npm run build
   ```

2. **Deploy Frontend Application:**
   ```bash
   cd /var/www/frontend
   git clone https://github.com/videowatchshow-ship-it/guccimadesite.git
   cd guccimadesite
   npm install
   npm run build
   ```

3. **Deploy Streaming Server:**
   ```bash
   cd /var/www/streaming
   # Configure SRS or nginx-rtmp
   ```

4. **Start Services:**
   ```bash
   docker-compose up -d
   ```

5. **Check Status:**
   ```bash
   docker ps
   ```

---

## 🔐 Security Notes

- **SSH Password:** `1EMokhN03j9?G)8h7,pX` (Change after first login)
- **Firewall:** UFW enabled with ports 22, 80, 443 open
- **fail2ban:** Installed for DDoS protection
- **SSL/TLS:** Certbot installed for Let's Encrypt certificates

---

## 📞 Troubleshooting

### Issue: "Connection refused"
- Verify VPS is running in Hostinger hPanel
- Check firewall rules: `sudo ufw status`
- Verify SSH port 22 is open

### Issue: "Permission denied"
- Ensure you're using `root` user
- Check SSH key permissions: `chmod 600 ~/.ssh/id_rsa`

### Issue: "apt update failed"
- Run: `sudo apt update --fix-missing`
- Check internet connection on VPS

### Issue: "Docker not found"
- Verify Docker installation: `docker --version`
- Restart Docker: `sudo systemctl restart docker`

---

## 📚 Official Documentation References

- **Docker:** https://docs.docker.com/
- **Docker Compose:** https://docs.docker.com/compose/
- **Ubuntu:** https://ubuntu.com/server/docs
- **nginx:** https://nginx.org/en/docs/
- **MariaDB:** https://mariadb.com/docs/
- **Redis:** https://redis.io/docs/
- **Node.js:** https://nodejs.org/en/docs/
- **Hostinger VPS:** https://support.hostinger.com/en/categories/4291307-vps

---

**Last Updated:** 2026-05-17  
**Version:** 1.0.0 (Stable LTS)  
**Status:** Production-Ready ✅
