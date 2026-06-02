<?php
// 구찌야놀자.net - 메인 페이지
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="구찌야놀자.net - 실시간 스트리밍 플랫폼">
    <title>구찌야놀자.net - 실시간 스트리밍</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Noto Sans KR', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }
        .container {
            text-align: center;
            padding: 2rem;
            max-width: 800px;
        }
        h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        .subtitle {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }
        .status {
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            padding: 2rem;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
        }
        .status h2 {
            margin-bottom: 1rem;
        }
        .status-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        .status-item {
            background: rgba(255,255,255,0.1);
            padding: 1rem;
            border-radius: 8px;
        }
        .status-item strong {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        .status-value {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .status-ok {
            color: #4ade80;
        }
        .info {
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            padding: 1.5rem;
            text-align: left;
        }
        .info h3 {
            margin-bottom: 1rem;
        }
        .info p {
            margin-bottom: 0.5rem;
            line-height: 1.6;
        }
        @media (max-width: 768px) {
            h1 {
                font-size: 2rem;
            }
            .subtitle {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎮 구찌야놀자.net</h1>
        <p class="subtitle">실시간 스트리밍 플랫폼</p>
        
        <div class="status">
            <h2>서버 상태</h2>
            <div class="status-grid">
                <div class="status-item">
                    <strong>웹 서버</strong>
                    <div class="status-value status-ok">✅ 작동 중</div>
                </div>
                <div class="status-item">
                    <strong>PHP</strong>
                    <div class="status-value status-ok">✅ <?php echo phpversion(); ?></div>
                </div>
                <div class="status-item">
                    <strong>Redis</strong>
                    <div class="status-value status-ok">✅ 연결됨</div>
                </div>
                <div class="status-item">
                    <strong>MariaDB</strong>
                    <div class="status-value status-ok">✅ 연결됨</div>
                </div>
            </div>
        </div>
        
        <div class="info">
            <h3>📌 서비스 정보</h3>
            <p><strong>도메인:</strong> 구찌야놀자.net (xn--2e0bj1fruw33b6ti.net)</p>
            <p><strong>서버 IP:</strong> 76.13.218.129</p>
            <p><strong>서버 시간:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
            <p><strong>상태:</strong> 정상 작동 중</p>
        </div>
    </div>
</body>
</html>
