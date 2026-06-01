// Official Docs: https://redis.io/docs/latest/develop/clients/nodejs/
// Official GitHub: https://github.com/redis/node-redis
// Version: redis 5.6.1 (node-redis, 2026-06-01)
// Note: ioredis is deprecated — Redis officially recommends node-redis
// Migration guide: https://redis.io/docs/latest/develop/clients/nodejs/migration/

'use strict';

const { createClient } = require('redis');

// Official: https://redis.io/docs/latest/develop/clients/nodejs/
const redisClient = createClient({
  socket: {
    host:    process.env.REDIS_HOST || 'localhost',
    port:    parseInt(process.env.REDIS_PORT || '6379', 10),
    reconnectStrategy(retries) {
      if (retries > 10) {
        return new Error('Redis 재연결 한도 초과');
      }
      return Math.min(retries * 50, 2000);
    },
  },
  password: process.env.REDIS_PASS || undefined,
});

redisClient.on('connect', () => {
  process.stdout.write('[INFO] Redis 연결 성공\n');
});

redisClient.on('error', (err) => {
  process.stderr.write(`[ERROR] Redis 오류: ${err.message}\n`);
});

// Pub/Sub용 별도 클라이언트
// Official: https://redis.io/docs/latest/develop/clients/nodejs/pubsub/
const redisSub = createClient({
  socket: {
    host:     process.env.REDIS_HOST || 'localhost',
    port:     parseInt(process.env.REDIS_PORT || '6379', 10),
  },
  password: process.env.REDIS_PASS || undefined,
});

const redisPub = createClient({
  socket: {
    host:     process.env.REDIS_HOST || 'localhost',
    port:     parseInt(process.env.REDIS_PORT || '6379', 10),
  },
  password: process.env.REDIS_PASS || undefined,
});

/**
 * Redis 클라이언트 연결 초기화
 * node-redis v5는 connect()를 명시적으로 호출해야 함
 * Official: https://redis.io/docs/latest/develop/clients/nodejs/
 * @returns {Promise<void>}
 */
async function connectRedis() {
  await redisClient.connect();
  await redisSub.connect();
  await redisPub.connect();
}

module.exports = { redisClient, redisSub, redisPub, connectRedis };
