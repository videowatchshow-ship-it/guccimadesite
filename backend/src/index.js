// Official Docs: https://expressjs.com/en/5x/api.html
// Official GitHub: https://github.com/expressjs/express
// Version: Express 5.2.1 (2026-06-01)
// Official Socket.io: https://socket.io/docs/v4/
// Version: Socket.io 4.8.3 (2026-06-01)
// Official node-redis: https://redis.io/docs/latest/develop/clients/nodejs/
// Version: redis 5.6.1 (node-redis, 2026-06-01)

'use strict';

const express     = require('express');
const http        = require('http');
const { Server }  = require('socket.io');
const cors        = require('cors');
const helmet      = require('helmet');
const compression = require('compression');
const morgan      = require('morgan');
const session     = require('express-session');
const passport    = require('passport');
const rateLimit   = require('express-rate-limit');
const RedisStore  = require('connect-redis').default;

require('dotenv').config();

const { redisClient, connectRedis } = require('./config/redis');
const { testDbConnection }          = require('./config/database');
const authRouter                    = require('./routes/auth');
const streamRouter                  = require('./routes/stream');
const chatRouter                    = require('./routes/chat');
const adminRouter                   = require('./routes/admin');
const healthRouter                  = require('./routes/health');
const { setupSocketIO }             = require('./socket/index');
require('./config/passport');

const app    = express();
const server = http.createServer(app);
const io     = new Server(server, {
  // Official: https://socket.io/docs/v4/server-options/
  cors: {
    origin: [
      `https://${process.env.DOMAIN}`,
      `https://www.${process.env.DOMAIN}`,
      'http://localhost:3000',
    ],
    methods: ['GET', 'POST'],
    credentials: true,
  },
  transports: ['websocket', 'polling'],
});

const PORT = parseInt(process.env.PORT || '3001', 10);

// ─── 미들웨어 ────────────────────────────────────────────────────────────────
// Official: https://expressjs.com/en/guide/using-middleware.html

// Helmet: 보안 헤더 설정
// Official: https://helmetjs.github.io/
app.use(helmet({
  contentSecurityPolicy: false, // Apache2에서 처리
  crossOriginEmbedderPolicy: false,
}));

// Compression
app.use(compression());

// CORS
// Official: https://expressjs.com/en/resources/middleware/cors.html
app.use(cors({
  origin: [
    `https://${process.env.DOMAIN}`,
    `https://www.${process.env.DOMAIN}`,
    'http://localhost:3000',
  ],
  credentials: true,
  methods: ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
  allowedHeaders: ['Content-Type', 'Authorization'],
}));

// Body Parser
// Official: https://expressjs.com/en/5x/api.html#express.json
app.use(express.json({ limit: '10kb' }));
app.use(express.urlencoded({ extended: true, limit: '10kb' }));

// Morgan 로깅
// Official: https://github.com/expressjs/morgan
if (process.env.NODE_ENV !== 'test') {
  app.use(morgan('combined'));
}

// Rate Limiting
// Official: https://github.com/express-rate-limit/express-rate-limit
const limiter = rateLimit({
  windowMs: 15 * 60 * 1000, // 15분
  max: 100,
  standardHeaders: true,
  legacyHeaders: false,
  message: { error: '요청이 너무 많습니다. 잠시 후 다시 시도하세요.' },
});
app.use('/api/', limiter);

// Session + Redis Store
// Official connect-redis v9: https://github.com/tj/connect-redis
// Official express-session: https://github.com/expressjs/session
// connect-redis v9는 node-redis v5와 함께 사용 (ioredis deprecated)
app.use(session({
  store: new RedisStore({ client: redisClient }),
  secret: process.env.SESSION_SECRET,
  resave: false,
  saveUninitialized: false,
  cookie: {
    secure: process.env.NODE_ENV === 'production',
    httpOnly: true,
    maxAge: 7 * 24 * 60 * 60 * 1000, // 7일
    sameSite: 'lax',
  },
}));

// Passport
// Official: https://www.passportjs.org/docs/
app.use(passport.initialize());
app.use(passport.session());

// ─── 라우터 ──────────────────────────────────────────────────────────────────
app.use('/health',     healthRouter);
app.use('/api/auth',   authRouter);
app.use('/api/stream', streamRouter);
app.use('/api/chat',   chatRouter);
app.use('/api/admin',  adminRouter);

// ─── 404 핸들러 ──────────────────────────────────────────────────────────────
app.use((_req, res) => {
  res.status(404).json({ error: '요청한 리소스를 찾을 수 없습니다.' });
});

// ─── 에러 핸들러 (Express 5 공식 패턴) ──────────────────────────────────────
// Official: https://expressjs.com/en/guide/error-handling.html
// eslint-disable-next-line no-unused-vars
app.use((err, _req, res, _next) => {
  const status = err.status || err.statusCode || 500;
  const message = process.env.NODE_ENV === 'production'
    ? '서버 오류가 발생했습니다.'
    : err.message;
  res.status(status).json({ error: message });
});

// ─── Socket.IO 설정 ──────────────────────────────────────────────────────────
setupSocketIO(io);

// ─── 서버 시작 ───────────────────────────────────────────────────────────────
async function start() {
  try {
    // node-redis v5: connect()를 명시적으로 호출해야 함
    // Official: https://redis.io/docs/latest/develop/clients/nodejs/
    await connectRedis();
    await testDbConnection();

    server.listen(PORT, '0.0.0.0', () => {
      process.stdout.write(`[INFO] 서버 시작: http://0.0.0.0:${PORT}\n`);
      process.stdout.write(`[INFO] 환경: ${process.env.NODE_ENV}\n`);
    });
  } catch (err) {
    process.stderr.write(`[ERROR] 서버 시작 실패: ${err.message}\n`);
    process.exit(1);
  }
}

start();

module.exports = { app, server, io };
