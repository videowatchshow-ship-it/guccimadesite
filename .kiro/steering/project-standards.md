---
inclusion: always
---

# 2026 Production-Ready 플랫폼 프로젝트 표준

## Core Principles

**All code must be production-ready, officially documented, and security-first.**

### Mandatory Requirements
- Use only official documentation and stable/LTS versions
- All code must be maintainable, secure, and tested
- Docker-based infrastructure required
- Mobile-first and SEO-first approach

### Absolute Prohibitions
- No speculative coding or undocumented patterns
- No unofficial GitHub code or untested packages
- No `latest` tags or unstable versions
- No platform policy violations or artificial engagement manipulation

## Technology Stack & Versions

| Layer | Technology | Docs | GitHub | Version |
|-------|-----------|------|--------|---------|
| **Frontend** | Next.js | https://nextjs.org/docs | https://github.com/vercel/next.js | Stable LTS |
| | React | https://react.dev | https://github.com/facebook/react | Stable LTS |
| | TailwindCSS | https://tailwindcss.com/docs | https://github.com/tailwindlabs/tailwindcss | Stable LTS |
| **Backend** | Node.js | https://nodejs.org/en/docs | https://github.com/nodejs/node | 22 LTS |
| | Express.js | https://expressjs.com | https://github.com/expressjs/express | Stable LTS |
| | NestJS (opt) | https://docs.nestjs.com | https://github.com/nestjs/nest | Stable LTS |
| **Data** | MariaDB | https://mariadb.com/docs | https://github.com/MariaDB/server | 11 Stable |
| | Redis | https://redis.io/docs | https://github.com/redis/redis | 7 Stable |
| **Streaming** | SRS | https://ossrs.io/lts/en-us/docs | https://github.com/ossrs/srs | LTS |
| | nginx-rtmp | — | https://github.com/arut/nginx-rtmp-module | Stable |
| | FFmpeg | https://ffmpeg.org/documentation.html | https://github.com/FFmpeg/FFmpeg | Stable LTS |
| **Auth** | Google OAuth | https://developers.google.com/identity/protocols/oauth2 | — | Latest |
| | JWT | https://jwt.io/introduction | — | RFC 7519 |
| **Infrastructure** | Docker | https://docs.docker.com | https://github.com/docker | Stable |
| | Docker Compose | https://docs.docker.com/compose | — | Stable |
| | nginx | https://nginx.org/en/docs | https://github.com/nginx/nginx | Stable |
| | Cloudflare | https://developers.cloudflare.com | — | Latest |
| **OS** | Ubuntu | https://ubuntu.com/server/docs | — | 24.04 LTS |

## Version Management

- Pin all versions explicitly (no `latest` tags or ranges like `^` or `~`)
- Maintain `package-lock.json` for all Node.js projects
- Use Docker image digests instead of tags
- All dependencies must be from official, stable sources

## Project Structure

```
/project
├── /frontend          # Next.js application
├── /backend           # Node.js/Express backend
├── /admin             # Admin dashboard
├── /streaming         # Streaming server (SRS)
├── /nginx             # nginx reverse proxy config
├── /docker            # Docker Compose files
├── /database          # MariaDB initialization
├── /redis             # Redis configuration
├── /security          # Security configs (fail2ban, UFW)
├── /scripts           # Deployment automation
├── /logs              # Application logs
├── /backups           # Database backups
└── README.md          # Project documentation
```

## Core Features

1. **SEO Optimization** — 150+ checklist items (Rank Math + Google SEO)
2. **Real-time Streaming** — YouTube/Twitch-style with OBS/PRISM support, adaptive bitrate, low latency
3. **Live Chat** — WebSocket-based with Redis Pub/Sub, emoji support, moderator controls
4. **Admin System** — Stream key generation, broadcast control, monitoring, audit logs
5. **Google OAuth** — Sign-up modal, auto-login, session persistence, CSRF/XSS protection
6. **Mobile UX** — 200+ items: touch optimization, thumb-friendly layout, one-hand UX, safe-area support
7. **Desktop UX** — 200+ items: keyboard shortcuts, fullscreen mode, admin dashboard, multi-window support
8. **Security** — 30+ items: DDoS protection, SQL injection prevention, XSS/CSRF defense, fail2ban, UFW firewall

## Code Standards

**Required:**
- All code must follow official documentation patterns
- Production-ready code only (no experimental or beta features)
- Maintainable, well-structured code
- Security-first implementation

**Prohibited:**
- Speculative or undocumented implementations
- Unofficial or unverified code
- Untested packages or dependencies
- Platform policy violations

## Deployment Sequence

1. VPS initialization
2. Ubuntu updates
3. Docker & Docker Compose installation
4. nginx reverse proxy setup
5. MariaDB configuration
6. Redis configuration
7. Backend deployment
8. Frontend deployment
9. Streaming server deployment
10. SSL/TLS setup
11. Cloudflare integration
12. Monitoring setup
13. Backup system configuration
14. fail2ban & UFW firewall setup
15. Production build optimization
16. SEO optimization
17. WebSocket testing
18. Streaming functionality testing

## Validation Checklist

- [ ] All code follows official documentation patterns
- [ ] All versions are Stable/LTS (no `latest` tags)
- [ ] All packages are tested and verified
- [ ] Code is production-ready and deployable
- [ ] Security best practices applied
- [ ] SEO optimization implemented
- [ ] Mobile UX complete
- [ ] Desktop UX complete
- [ ] Deployment testing complete

## AI Assistant Coding Rules

### Mandatory Process
1. **Search official documentation** — Use web search for latest 2026 versions
2. **Verify with official GitHub** — Check stable/LTS branches only
3. **Validate with regex patterns** — Ensure version numbers, paths, and commands match official specs
4. **Write production-ready code** — All code must be complete and deployable
5. **Include documentation links** — Every code block must reference official docs

### Absolute Prohibitions
- Never ask user to run commands or perform tasks
- Never use speculative implementations
- Never code without official documentation
- Never use unofficial GitHub code
- Never use untested packages
- Never wait for user input to proceed

### Code Template

```bash
#!/bin/bash
# Official Docs: https://docs.example.com/install
# Official GitHub: https://github.com/example/repo
# Version: Stable LTS (2026)
# Regex Validation: ^[0-9]+\.[0-9]+\.[0-9]+$

# Implementation based on official documentation
# All commands and configurations verified against official sources
```

### Regex Validation Patterns

**Semantic Versioning:** `^[0-9]+\.[0-9]+\.[0-9]+$`  
**LTS Version:** `^[0-9]+\.[0-9]+\.[0-9]+-lts$`  
**Absolute Path:** `^/[a-zA-Z0-9/_.-]+$`  
**Port Number:** `^[0-9]{4,5}$`  
**Command:** `^[a-z0-9-]+$`  
**Flag:** `^--?[a-z0-9-]+$`
---

## VPS Information (구찌야놀자)

| 항목 | 값 |
|------|-----|
| **호스트** | 76.13.218.129 |
| **호스트명** | srv1636789.hstgr.cloud |
| **SSH 포트** | 22 |
| **사용자** | root |
| **비밀번호** | q+7m#GElqQs/E&tfabwB |
| **OS** | Ubuntu 24.04 LTS |
| **CPU** | 1 Core |
| **메모리** | 4 GB |
| **디스크** | 50 GB |
| **상태** | 실행 중 |
| **만료일** | 2026-06-02 |
| **컨트롤** | KVM |

### SSH 연결 명령어

```bash
ssh root@76.13.218.129
```

---

**Core Principle:** Official documentation → Stable/LTS versions → Production-ready code → Security-first → Mobile-first → SEO-first
