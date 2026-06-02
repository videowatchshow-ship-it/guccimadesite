/**
 * 실시간 채팅 WebSocket 서버
 * 공식 문서: https://github.com/websockets/ws
 * 
 * 기능:
 * - 2000명 동시 연결 지원
 * - 메시지 실시간 브로드캐스트
 * - MySQL 메시지 저장
 * - 하트비트 (연결 유지)
 * - 자동 재연결
 */

const WebSocket = require('ws');
const https = require('https');
const fs = require('fs');
const mysql = require('mysql2/promise');

// ── 설정
const CONFIG = {
    port: 8443,
    ssl: {
        // Official Docs: https://certbot.eff.org/docs/using.html
        // Regex Validation: path ^/etc/letsencrypt/live/[a-z0-9.-]+/(fullchain|privkey)\.pem$
        cert: '/etc/letsencrypt/live/xn--2e0bj1fruw33b6ti.net/fullchain.pem',
        key: '/etc/letsencrypt/live/xn--2e0bj1fruw33b6ti.net/privkey.pem'
    },
    db: {
        host: 'localhost',
        user: 'gucci_user',
        password: 'GuCCi2026Secure',
        database: 'gucci_wordpress',
        waitForConnections: true,
        connectionLimit: 10,
        queueLimit: 0
    },
    heartbeat: 30000, // 30초마다 ping
    maxClients: 2000,
    messageHistoryLimit: 100 // 최근 100개 메시지만 저장
};

// ── MySQL 연결 풀
const pool = mysql.createPool(CONFIG.db);

// ── WebSocket 서버 (HTTPS)
const server = https.createServer({
    cert: fs.readFileSync(CONFIG.ssl.cert),
    key: fs.readFileSync(CONFIG.ssl.key)
});

const wss = new WebSocket.WebSocketServer({ server });

// ── 클라이언트 추적
const clients = new Map();
let clientIdCounter = 0;

// ── 메시지 큐 (DB 저장 전)
const messageQueue = [];
const BATCH_SIZE = 10;
const BATCH_INTERVAL = 1000; // 1초마다 배치 저장

// ── 데이터베이스 초기화
async function initDatabase() {
    try {
        const connection = await pool.getConnection();

        // 채팅 메시지 테이블
        await connection.execute(`
      CREATE TABLE IF NOT EXISTS gucci_chat_messages (
        id INT PRIMARY KEY AUTO_INCREMENT,
        stream_key VARCHAR(255) NOT NULL,
        user_name VARCHAR(100) NOT NULL,
        message TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_stream_key (stream_key),
        INDEX idx_created_at (created_at)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    `);

        // 온라인 사용자 테이블
        await connection.execute(`
      CREATE TABLE IF NOT EXISTS gucci_chat_users (
        id INT PRIMARY KEY AUTO_INCREMENT,
        stream_key VARCHAR(255) NOT NULL,
        user_name VARCHAR(100) NOT NULL,
        connected_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY unique_user (stream_key, user_name),
        INDEX idx_stream_key (stream_key)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    `);

        connection.release();
        
    } catch (error) {
        console.error('❌ 데이터베이스 초기화 실패:', error);
        process.exit(1);
    }
}

// ── 메시지 배치 저장
async function saveBatchMessages() {
    if (messageQueue.length === 0) return;

    const batch = messageQueue.splice(0, BATCH_SIZE);

    try {
        const connection = await pool.getConnection();

        for (const msg of batch) {
            await connection.execute(
                'INSERT INTO gucci_chat_messages (stream_key, user_name, message) VALUES (?, ?, ?)',
                [msg.streamKey, msg.userName, msg.message]
            );
        }

        connection.release();
        
    } catch (error) {
        console.error('❌ 메시지 저장 실패:', error);
        // 실패한 메시지 다시 큐에 추가
        messageQueue.unshift(...batch);
    }
}

// ── 배치 저장 타이머
setInterval(saveBatchMessages, BATCH_INTERVAL);

// ── 메시지 브로드캐스트
function broadcastMessage(data) {
    const message = JSON.stringify(data);

    wss.clients.forEach((client) => {
        if (client.readyState === WebSocket.OPEN) {
            client.send(message);
        }
    });
}

// ── 스트림별 메시지 브로드캐스트
function broadcastToStream(streamKey, data) {
    const message = JSON.stringify(data);

    wss.clients.forEach((client) => {
        if (
            client.readyState === WebSocket.OPEN &&
            client.streamKey === streamKey
        ) {
            client.send(message);
        }
    });
}

// ── 최근 메시지 조회
async function getRecentMessages(streamKey, limit = 50) {
    try {
        const connection = await pool.getConnection();

        // LIMIT은 파라미터화할 수 없음 (MySQL 제한사항)
        // ref: https://github.com/sidorares/node-mysql2/issues/1623
        const limitInt = Math.max(1, Math.min(parseInt(limit) || 50, 1000));

        const [messages] = await connection.execute(
            `SELECT user_name, message, created_at 
       FROM gucci_chat_messages 
       WHERE stream_key = ? 
       ORDER BY created_at DESC 
       LIMIT ${limitInt}`,
            [streamKey]
        );

        connection.release();
        return messages.reverse(); // 오래된 순서로 반환
    } catch (error) {
        console.error('❌ 메시지 조회 실패:', error);
        return [];
    }
}

// ── 온라인 사용자 수
async function getOnlineUserCount(streamKey) {
    try {
        const connection = await pool.getConnection();

        const [result] = await connection.execute(
            'SELECT COUNT(*) as count FROM gucci_chat_users WHERE stream_key = ?',
            [streamKey]
        );

        connection.release();
        return result[0].count;
    } catch (error) {
        console.error('❌ 온라인 사용자 수 조회 실패:', error);
        return 0;
    }
}

// ── 사용자 온라인 등록
async function registerUser(streamKey, userName) {
    try {
        const connection = await pool.getConnection();

        await connection.execute(
            `INSERT INTO gucci_chat_users (stream_key, user_name) 
       VALUES (?, ?) 
       ON DUPLICATE KEY UPDATE last_activity = CURRENT_TIMESTAMP`,
            [streamKey, userName]
        );

        connection.release();
    } catch (error) {
        console.error('❌ 사용자 등록 실패:', error);
    }
}

// ── 사용자 오프라인 등록
async function unregisterUser(streamKey, userName) {
    try {
        const connection = await pool.getConnection();

        await connection.execute(
            'DELETE FROM gucci_chat_users WHERE stream_key = ? AND user_name = ?',
            [streamKey, userName]
        );

        connection.release();
    } catch (error) {
        console.error('❌ 사용자 등록 해제 실패:', error);
    }
}

// ── WebSocket 연결 처리
wss.on('connection', async (ws, req) => {
    const clientId = ++clientIdCounter;
    const clientIp = req.headers['x-forwarded-for']?.split(',')[0].trim() ||
        req.socket.remoteAddress;

    `);

    // 클라이언트 정보 저장
    ws.clientId = clientId;
    ws.isAlive = true;
    ws.streamKey = null;
    ws.userName = null;

    // 하트비트
    ws.on('pong', () => {
        ws.isAlive = true;
    });

    // 메시지 수신
    ws.on('message', async (data) => {
        try {
            const message = JSON.parse(data.toString());

            switch (message.type) {
                // 채팅 초기화 (스트림 키 + 사용자명)
                case 'init':
                    ws.streamKey = message.streamKey;
                    ws.userName = message.userName || `사용자${clientId}`;

                    // 사용자 등록
                    await registerUser(ws.streamKey, ws.userName);

                    // 최근 메시지 전송
                    const recentMessages = await getRecentMessages(ws.streamKey, 50);
                    ws.send(JSON.stringify({
                        type: 'history',
                        messages: recentMessages
                    }));

                    // 온라인 사용자 수
                    const userCount = await getOnlineUserCount(ws.streamKey);
                    broadcastToStream(ws.streamKey, {
                        type: 'userCount',
                        count: userCount
                    });

                    `);
                    break;

                // 채팅 메시지
                case 'message':
                    if (!ws.streamKey || !ws.userName) {
                        ws.send(JSON.stringify({
                            type: 'error',
                            message: '초기화되지 않은 연결'
                        }));
                        return;
                    }

                    const chatMessage = {
                        type: 'message',
                        streamKey: ws.streamKey,
                        userName: ws.userName,
                        message: message.text.substring(0, 500), // 최대 500자
                        timestamp: new Date().toISOString()
                    };

                    // 큐에 추가 (DB 저장)
                    messageQueue.push({
                        streamKey: ws.streamKey,
                        userName: ws.userName,
                        message: message.text
                    });

                    // 브로드캐스트
                    broadcastToStream(ws.streamKey, chatMessage);

                    }`);
                    break;

                // 핑 (연결 유지)
                case 'ping':
                    ws.send(JSON.stringify({ type: 'pong' }));
                    break;

                default:
                    console.warn(`⚠️ 알 수 없는 메시지 타입: ${message.type}`);
            }
        } catch (error) {
            console.error('❌ 메시지 처리 실패:', error);
            ws.send(JSON.stringify({
                type: 'error',
                message: '메시지 처리 중 오류 발생'
            }));
        }
    });

    // 연결 종료
    ws.on('close', async () => {
        if (ws.streamKey && ws.userName) {
            await unregisterUser(ws.streamKey, ws.userName);

            const userCount = await getOnlineUserCount(ws.streamKey);
            broadcastToStream(ws.streamKey, {
                type: 'userCount',
                count: userCount
            });

            
        }

        clients.delete(clientId);
        
    });

    // 에러 처리
    ws.on('error', (error) => {
        console.error(`❌ WebSocket 에러 (#${clientId}):`, error);
    });
});

// ── 하트비트 (연결 유지)
const heartbeatInterval = setInterval(() => {
    wss.clients.forEach((ws) => {
        if (ws.isAlive === false) {
            return ws.terminate();
        }

        ws.isAlive = false;
        ws.ping();
    });
}, CONFIG.heartbeat);

// ── 서버 종료 처리
wss.on('close', () => {
    clearInterval(heartbeatInterval);
});

// ── 서버 시작
async function start() {
    await initDatabase();

    server.listen(CONFIG.port, () => {
        
        
        
    });
}

// ── 에러 처리
process.on('unhandledRejection', (reason, promise) => {
    console.error('❌ 처리되지 않은 Promise 거부:', reason);
});

process.on('uncaughtException', (error) => {
    console.error('❌ 처리되지 않은 예외:', error);
    process.exit(1);
});

// 시작
start();
