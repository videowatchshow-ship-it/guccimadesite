-- 구찌야놀자 초기 스키마 마이그레이션
-- 버전: 001
-- 날짜: 2026-05-23
-- ref: https://mariadb.com/docs/server/ref/mdb/sql-statements/CREATE_TABLE/
-- ref: https://mariadb.com/docs/server/ref/mdb/sql-statements/CREATE_INDEX/

-- ── 문자셋 설정
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- ── 1. gucci_members (회원 테이블)
CREATE TABLE IF NOT EXISTS gucci_members (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    google_id           VARCHAR(100) UNIQUE NOT NULL COMMENT 'Google OAuth sub',
    email               VARCHAR(200) NOT NULL COMMENT '이메일 주소',
    name                VARCHAR(100) COMMENT 'Google 프로필 이름',
    profile_picture_url VARCHAR(500) COMMENT 'Google 프로필 사진 URL',
    account_holder_name VARCHAR(255) COMMENT '예금주명',
    phone_number        VARCHAR(20) COMMENT '전화번호',
    bank_name           VARCHAR(100) COMMENT '은행명',
    account_number      VARCHAR(50) COMMENT '계좌번호',
    is_admin            TINYINT(1) DEFAULT 0 COMMENT '관리자 여부',
    created_at          DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '가입일',
    updated_at          DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '수정일',
    INDEX idx_google_id (google_id),
    INDEX idx_email     (email),
    INDEX idx_is_admin  (is_admin)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='구찌야놀자 회원 테이블';

-- ── 2. gucci_stream_keys (스트림 키 테이블)
CREATE TABLE IF NOT EXISTS gucci_stream_keys (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT NOT NULL COMMENT '회원 ID (FK)',
    stream_key  VARCHAR(100) NOT NULL UNIQUE COMMENT '스트림 키',
    title       VARCHAR(200) COMMENT '스트림 제목',
    description TEXT COMMENT '스트림 설명',
    is_active   TINYINT(1) DEFAULT 1 COMMENT '활성 여부',
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '생성일',
    updated_at  DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '수정일',
    FOREIGN KEY (user_id) REFERENCES gucci_members(id) ON DELETE CASCADE,
    INDEX idx_user_id    (user_id),
    INDEX idx_stream_key (stream_key),
    INDEX idx_is_active  (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='스트림 키 관리 테이블';

-- ── 3. gucci_audit_log (감사 로그 테이블)
-- ref: https://owasp.org/www-project-top-ten/
CREATE TABLE IF NOT EXISTS gucci_audit_log (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT COMMENT '회원 ID',
    action     VARCHAR(100) NOT NULL COMMENT '액션',
    detail     TEXT COMMENT '상세 내용',
    ip_address VARCHAR(45) COMMENT 'IP 주소',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '발생일',
    INDEX idx_user_id (user_id),
    INDEX idx_action  (action),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='감사 로그 테이블';

-- ── 4. gucci_chat_messages (채팅 메시지 테이블)
-- ref: https://developer.mozilla.org/en-US/docs/Web/API/WebSocket
CREATE TABLE IF NOT EXISTS gucci_chat_messages (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT COMMENT '회원 ID',
    nickname   VARCHAR(100) COMMENT '닉네임',
    message    TEXT NOT NULL COMMENT '메시지',
    room_id    VARCHAR(50) DEFAULT 'main' COMMENT '채팅방 ID',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '발송일',
    INDEX idx_room_id (room_id),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='채팅 메시지 테이블';

-- ── 5. gucci_reservations (예약 테이블)
-- ref: https://schema.org/ReservationPackage
CREATE TABLE IF NOT EXISTS gucci_reservations (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    user_id      INT COMMENT '회원 ID',
    name         VARCHAR(100) NOT NULL COMMENT '예약자명',
    phone        VARCHAR(20) NOT NULL COMMENT '연락처',
    game_type    VARCHAR(50) NOT NULL COMMENT '게임 종류',
    reserve_date DATE NOT NULL COMMENT '예약 날짜',
    reserve_time VARCHAR(10) COMMENT '예약 시간',
    budget       VARCHAR(20) COMMENT '예산',
    memo         TEXT COMMENT '요청 사항',
    status       ENUM('pending','confirmed','cancelled') DEFAULT 'pending' COMMENT '예약 상태',
    created_at   DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '신청일',
    updated_at   DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '수정일',
    INDEX idx_user_id     (user_id),
    INDEX idx_reserve_date(reserve_date),
    INDEX idx_status      (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='예약 테이블';

-- ── 6. gucci_board_posts (자유게시판 테이블)
-- ref: https://schema.org/DiscussionForumPosting
CREATE TABLE IF NOT EXISTS gucci_board_posts (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT COMMENT '회원 ID',
    nickname   VARCHAR(100) NOT NULL COMMENT '닉네임',
    category   ENUM('공지','후기','정보','질문') NOT NULL DEFAULT '정보' COMMENT '카테고리',
    title      VARCHAR(200) NOT NULL COMMENT '제목',
    content    TEXT NOT NULL COMMENT '내용',
    views      INT DEFAULT 0 COMMENT '조회수',
    is_deleted TINYINT(1) DEFAULT 0 COMMENT '삭제 여부',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT '작성일',
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '수정일',
    INDEX idx_user_id  (user_id),
    INDEX idx_category (category),
    INDEX idx_created  (created_at),
    INDEX idx_deleted  (is_deleted)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='자유게시판 테이블';
