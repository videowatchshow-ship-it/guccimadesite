#!/usr/bin/env node
/**
 * 구찌야놀자 WebSocket 채팅 서버 v2.1.0 (SSL 지원)
 * 정규식 검증 + 명령어 처리 + XSS 방지 + HTTPS 지원
 *
 * 공식 문서 출처:
 *   ref: https://github.com/websockets/ws/blob/master/README.md
 *   ref: https://nodejs.org/api/esm.html (Node.js v25.9.0)
 *   ref: https://www.rfc-editor.org/rfc/rfc6455 (WebSocket Protocol)
 *
 * Node.js: v22.22.2
 * ws: 8.20.0
 */

import { WebSocketServer, WebSocket } from 'ws';
import { createServer as createHttpsServer } from 'https';
import { createServer as createHttpServer } from 'http';
import { readFileSync } from 'fs';

// ── 환경 변수
const PORT = parseInt(process.env.WS_PORT || '8080', 10);
const MAX_CLIENTS = parseInt(process.env.MAX_CLIENTS || '500', 10);
const PING_INTERVAL_MS = parseInt(process.env.PING_MS || '30000', 10);
const MAX_MSG_LEN = parseInt(process.env.MAX_MSG_LEN || '500', 10);
const ALLOWED_ORIGINS = (process.env.ALLOWED_ORIGINS || [
    'https://xn--2e0bj1fruw33b6ti.net',
    'http://localhost:3000',
    'http://127.0.0.1:3000'
].join(',')).split(',');

// ── SSL 인증서 경로
const SSL_CERT_PATH = '/etc/letsencrypt/live/xn--2e0bj1fruw33b6ti.net/fullchain.pem';
const SSL_KEY_PATH = '/etc/letsencrypt/live/xn--2e0bj1fruw33b6ti.net/privkey.pem';

// ── 정규식 패턴 (공식 문서 기반)
// ref: https://www.rfc-editor.org/rfc/rfc5322 (이메일)
// ref: https://www.rfc-editor.org/rfc/rfc3986 (URL)
const REGEX = {
    // RFC 5322 간소화 이메일 검증
    email: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,

    // 닉네임: 한글, 영문, 숫자, 언더스코어, 하이픈 (2-20자)
    nickname: /^[\w\-가-힣]{2,20}$/u,

    // URL 검증 (http, https, ftp)
    url: /^(https?|ftp):\/\/[^\s]+$/i,

    // 이모지 검증 (기본 이모지 범위)
    emoji: /^[\u{1F300}-\u{1F9FF}]$/u,

    // HTML 태그 감지 (XSS 방지)
    htmlTag: /<[^>]*>/g,

    // 명령어 패턴 (/help, /nick, /users 등)
    command: /^\/(\w+)(?:\s+(.*))?$/,

    // 스크립트 태그 감지
    scriptTag: /<script[^>]*>.*?<\/script>/gi,

    // 이벤트 핸들러 감지 (on* 속성)
    eventHandler: /\s+on\w+\s*=/gi,
};

// ── 메시지 sanitization (XSS 방지)
function sanitizeMessage(text) {
    if (typeof text !== 'string') return '';

    // 스크립트 태그 제거
    let sanitized = text.replace(REGEX.scriptTag, '');

    // 이벤트 핸들러 제거
    sanitized = sanitized.replace(REGEX.eventHandler, ' ');

    // HTML 엔티티 인코딩 (< > & " ')
    sanitized = sanitized
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#x27;');

    return sanitized.trim();
}

// ── 닉네임 검증
function validateNickname(nickname) {
    if (typeof nickname !== 'string') return false;
    return REGEX.nickname.test(nickname.trim());
}

// ── 이모지 검증
function validateEmoji(emoji) {
    if (typeof emoji !== 'string') return false;
    return REGEX.emoji.test(emoji);
}

// ── 명령어 파싱
function parseCommand(text) {
    const match = text.match(REGEX.command);
    if (!match) return null;

    return {
        command: match[1].toLowerCase(),
        args: match[2] ? match[2].trim() : ''
    };
}

// ── 명령어 처리
function handleCommand(command, args, clientInfo, ws, wss) {
    switch (command) {
        case 'help':
            ws.send(JSON.stringify({
                type: 'system',
                message: '📋 사용 가능한 명령어:\n' +
                    '  /help — 도움말 표시\n' +
                    '  /nick <닉네임> — 닉네임 변경\n' +
                    '  /users — 접속자 목록\n' +
                    '  /emoji <이모지> — 이모지 반응',
                timestamp: Date.now(),
            }));
            break;

        case 'nick':
            if (!args) {
                ws.send(JSON.stringify({
                    type: 'error',
                    message: '❌ 사용법: /nick <닉네임>',
                    timestamp: Date.now(),
                }));
                break;
            }

            if (!validateNickname(args)) {
                ws.send(JSON.stringify({
                    type: 'error',
                    message: '❌ 닉네임은 2-20자 (한글, 영문, 숫자, _, -)만 가능합니다.',
                    timestamp: Date.now(),
                }));
                break;
            }

            const oldName = clientInfo.userName;
            clientInfo.userName = args;

            ws.send(JSON.stringify({
                type: 'system',
                message: '✅ 닉네임이 "' + args + '"으로 변경되었습니다.',
                timestamp: Date.now(),
            }));

            // 모든 클라이언트에 알림
            wss.clients.forEach(function (client) {
                if (client.readyState === WebSocket.OPEN) {
                    client.send(JSON.stringify({
                        type: 'system',
                        message: oldName + '님이 ' + args + '(으)로 이름을 변경했습니다.',
                        timestamp: Date.now(),
                    }));
                }
            });
            break;

        case 'users':
            const userList = [];
            wss.clients.forEach(function (client) {
                if (client.readyState === WebSocket.OPEN) {
                    const info = clientInfo._map ? clientInfo._map.get(client) : null;
                    if (info) {
                        userList.push(info.userName + ' (' + info.role + ')');
                    }
                }
            });

            ws.send(JSON.stringify({
                type: 'system',
                message: '👥 접속자 (' + wss.clients.size + '명):\n  ' + userList.join('\n  '),
                timestamp: Date.now(),
            }));
            break;

        case 'emoji':
            if (!args || !validateEmoji(args)) {
                ws.send(JSON.stringify({
                    type: 'error',
                    message: '❌ 유효한 이모지를 입력하세요.',
                    timestamp: Date.now(),
                }));
                break;
            }

            wss.clients.forEach(function (client) {
                if (client.readyState === WebSocket.OPEN) {
                    client.send(JSON.stringify({
                        type: 'reaction',
                        author: clientInfo.userName,
                        emoji: args,
                        timestamp: Date.now(),
                    }));
                }
            });
            break;

        default:
            ws.send(JSON.stringify({
                type: 'error',
                message: '❌ 알 수 없는 명령어: /' + command + '\n/help로 도움말을 확인하세요.',
                timestamp: Date.now(),
            }));
    }
}

// ── 닉네임 색상 팔레트
const NICK_COLORS = [
    '#FF4500', '#FF7F50', '#9ACD32', '#00FF7F', '#2E8B57',
    '#DAA520', '#D2691E', '#5F9EA0', '#1E90FF', '#FF69B4',
    '#8A2BE2', '#00CED1', '#FF6347', '#7FFF00', '#DC143C',
];

function assignColor(id) {
    return NICK_COLORS[id % NICK_COLORS.length];
}

// ── HTTPS 서버 생성 (공식 문서 기준)
// ref: https://github.com/websockets/ws/blob/master/README.md#external-https-server
// ref: https://nodejs.org/api/esm.html
let server;
let isSSL = false;

try {
    server = createHttpsServer({
        cert: readFileSync(SSL_CERT_PATH),
        key: readFileSync(SSL_KEY_PATH)
    });
    isSSL = true;
    
} catch (error) {
    console.error('❌ SSL 인증서 로드 실패:', error.message);
    
    // HTTP 모드로 폴백
    server = createHttpServer();
    isSSL = false;
}

// ── WebSocketServer 생성 (공식 문서 기준)
const wss = new WebSocketServer({
    server: server,
    perMessageDeflate: {
        zlibDeflateOptions: { chunkSize: 1024, memLevel: 7, level: 3 },
        zlibInflateOptions: { chunkSize: 10 * 1024 },
        clientNoContextTakeover: true,
        serverNoContextTakeover: true,
        serverMaxWindowBits: 10,
        concurrencyLimit: 10,
        threshold: 1024,
    },
    maxPayload: 64 * 1024,
});

const protocol = isSSL ? 'wss' : 'ws';

// ── 클라이언트 저장소
const clients = new Map();
let clientIdCounter = 0;

// ── broadcast
function broadcast(data, excludeWs) {
    if (!excludeWs) excludeWs = null;
    wss.clients.forEach(function (client) {
        if (client !== excludeWs && client.readyState === WebSocket.OPEN) {
            client.send(data);
        }
    });
}

// ── 접속자 수 브로드캐스트
function broadcastOnlineCount() {
    broadcast(JSON.stringify({
        type: 'online_count',
        count: wss.clients.size,
        timestamp: Date.now(),
    }));
}

// ── Heartbeat
function heartbeat() {
    this.isAlive = true;
}

// ── connection 이벤트
wss.on('connection', function connection(ws, request) {

    if (wss.clients.size > MAX_CLIENTS) {
        ws.close(1013, 'Server full');
        return;
    }

    const origin = request.headers.origin;
    if (origin && !ALLOWED_ORIGINS.includes(origin)) {
        
        ws.close(1008, 'Origin not allowed');
        return;
    }

    const clientId = ++clientIdCounter;
    const clientInfo = {
        id: clientId,
        ip: request.headers['x-forwarded-for'] || request.socket.remoteAddress,
        connectedAt: Date.now(),
        userName: '사용자' + clientId,
        color: assignColor(clientId),
        role: 'viewer',
        isMobile: /Mobile|Android|iPhone|iPad/i.test(request.headers['user-agent'] || ''),
    };
    clients.set(ws, clientInfo);

    console.log('✅ 클라이언트 연결 [' + clientInfo.userName + ', mobile=' + clientInfo.isMobile + ']');

    ws.isAlive = true;
    ws.on('pong', heartbeat);

    ws.on('error', function (err) {
        console.error('❌ 클라이언트 오류 [' + clientInfo.userName + ']: ' + err.message);
    });

    ws.send(JSON.stringify({
        type: 'welcome',
        message: '구찌야 놀자 채팅방에 오신 것을 환영합니다!',
        userName: clientInfo.userName,
        color: clientInfo.color,
        role: clientInfo.role,
        timestamp: Date.now(),
    }));

    broadcastOnlineCount();

    broadcast(JSON.stringify({
        type: 'system',
        message: clientInfo.userName + '님이 입장했습니다',
        timestamp: Date.now(),
    }), ws);

    // ── message 이벤트
    ws.on('message', function message(data, isBinary) {
        if (isBinary) {
            ws.send(JSON.stringify({ type: 'error', message: 'Binary not supported' }));
            return;
        }

        let parsed;
        try {
            parsed = JSON.parse(data.toString());
        } catch (e) {
            ws.send(JSON.stringify({ type: 'error', message: 'Invalid JSON' }));
            return;
        }

        switch (parsed.type) {

            case 'chat': {
                let content = String(parsed.content || '').trim().slice(0, MAX_MSG_LEN);
                if (!content) break;

                // 명령어 확인
                const cmd = parseCommand(content);
                if (cmd) {
                    handleCommand(cmd.command, cmd.args, clientInfo, ws, wss);
                    break;
                }

                // 메시지 sanitization
                content = sanitizeMessage(content);

                const msg = JSON.stringify({
                    type: 'chat',
                    author: clientInfo.userName,
                    color: clientInfo.color,
                    role: clientInfo.role,
                    content: content,
                    timestamp: Date.now(),
                });

                wss.clients.forEach(function (client) {
                    if (client.readyState === WebSocket.OPEN) {
                        client.send(msg);
                    }
                });
                break;
            }

            case 'set_username': {
                const newName = String(parsed.userName || '').trim().slice(0, 20);
                if (!validateNickname(newName)) {
                    ws.send(JSON.stringify({
                        type: 'error',
                        message: '❌ 닉네임은 2-20자 (한글, 영문, 숫자, _, -)만 가능합니다.',
                        timestamp: Date.now(),
                    }));
                    break;
                }

                const oldName = clientInfo.userName;
                clientInfo.userName = newName;

                ws.send(JSON.stringify({
                    type: 'username_changed',
                    userName: clientInfo.userName,
                    color: clientInfo.color,
                    timestamp: Date.now(),
                }));

                broadcast(JSON.stringify({
                    type: 'system',
                    message: oldName + '님이 ' + clientInfo.userName + '(으)로 이름을 변경했습니다',
                    timestamp: Date.now(),
                }));
                break;
            }

            case 'set_role': {
                const role = parsed.role === 'broadcaster' ? 'broadcaster' : 'viewer';
                clientInfo.role = role;
                ws.send(JSON.stringify({
                    type: 'role_changed',
                    role: role,
                    timestamp: Date.now(),
                }));
                broadcast(JSON.stringify({
                    type: 'system',
                    message: role === 'broadcaster'
                        ? clientInfo.userName + '님이 방송을 시작했습니다 🔴'
                        : clientInfo.userName + '님이 방송을 종료했습니다',
                    timestamp: Date.now(),
                }));
                break;
            }

            case 'reaction': {
                const emoji = String(parsed.emoji || '👍').slice(0, 4);
                if (!validateEmoji(emoji)) {
                    ws.send(JSON.stringify({
                        type: 'error',
                        message: '❌ 유효한 이모지를 입력하세요.',
                        timestamp: Date.now(),
                    }));
                    break;
                }

                broadcast(JSON.stringify({
                    type: 'reaction',
                    author: clientInfo.userName,
                    color: clientInfo.color,
                    emoji: emoji,
                    timestamp: Date.now(),
                }));
                break;
            }

            case 'ping': {
                ws.send(JSON.stringify({ type: 'pong', timestamp: Date.now() }));
                break;
            }

            default:
                
        }
    });

    // ── close 이벤트
    ws.on('close', function close() {
        
        clients.delete(ws);

        broadcast(JSON.stringify({
            type: 'system',
            message: clientInfo.userName + '님이 퇴장했습니다',
            timestamp: Date.now(),
        }));

        broadcastOnlineCount();
    });
});

// ── Ping/Pong Heartbeat
const interval = setInterval(function ping() {
    wss.clients.forEach(function (ws) {
        if (ws.isAlive === false) return ws.terminate();
        ws.isAlive = false;
        ws.ping();
    });
}, PING_INTERVAL_MS);

wss.on('close', function close() {
    clearInterval(interval);
    
});

wss.on('error', function error(err) {
    console.error('❌ 서버 오류: ' + err);
});

// ── Graceful Shutdown
function shutdown(signal) {
    
    clearInterval(interval);
    wss.clients.forEach(function (ws) {
        ws.close(1001, 'Server shutting down');
    });
    wss.close(function () {
        server.close(function () {
            
            process.exit(0);
        });
    });
    setTimeout(function () {
        process.exit(1);
    }, 5000);
}

process.on('SIGTERM', function () {
    shutdown('SIGTERM');
});

process.on('SIGINT', function () {
    shutdown('SIGINT');
});

// ── 서버 시작
server.listen(PORT, function () {
    // Production: console.log 제거 (공식 문서: https://nodejs.org/en/docs/guides/nodejs-logging/)
    // 에러는 console.error로 로깅됨
});
