# Official Docs: https://expressjs.com/en/5x/api.html
# Official GitHub: https://github.com/expressjs/express
# Version: Node.js 22.22.3-alpine3.22 (LTS, 2026-06-01)
# Regex Validation: ^[0-9]+\.[0-9]+\.[0-9]+$
# AI Assistant: Auto-generated based on official documentation

# ─── Build Stage ────────────────────────────────────────────────────────────
FROM node:22.22.3-alpine3.22 AS builder

# 작업 디렉토리 설정
WORKDIR /app

# 패키지 파일 복사
COPY package*.json ./

# 의존성 설치
RUN npm ci && \
    npm cache clean --force

# 소스 코드 복사
COPY . .

# TypeScript 컴파일 (필요시)
RUN npm run build || true

# ─── Runtime Stage ──────────────────────────────────────────────────────────
FROM node:22.22.3-alpine3.22

# 보안: 비루트 사용자 생성
RUN addgroup -g 1001 -S nodejs && \
    adduser -S node -u 1001

# 작업 디렉토리 설정
WORKDIR /app

# 빌드 결과물 복사
COPY --from=builder --chown=node:nodejs /app/node_modules ./node_modules
COPY --from=builder --chown=node:nodejs /app/package*.json ./
COPY --from=builder --chown=node:nodejs /app/dist ./dist || true
COPY --from=builder --chown=node:nodejs /app/src ./src

# 비루트 사용자로 전환
USER node

# 포트 노출
EXPOSE 3001

# 헬스 체크
HEALTHCHECK --interval=30s --timeout=10s --start-period=30s --retries=3 \
    CMD node -e "require('http').get('http://localhost:3001/health', (r) => {if (r.statusCode !== 200) throw new Error(r.statusCode)})"

# 시작 명령어
CMD ["npm", "start"]
