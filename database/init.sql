-- Official Docs: https://mariadb.com/docs/server/ref/mdb/
-- Official GitHub: https://github.com/MariaDB/server
-- Version: MariaDB 11.4.11 LTS (2026-06-01) — https://hub.docker.com/_/mariadb
-- Regex Validation: ^[0-9]+\.[0-9]+\.[0-9]+$

SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- ─── 데이터베이스 ────────────────────────────────────────────────────────────
CREATE DATABASE IF NOT EXISTS `guccimadesite`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `guccimadesite`;

-- ─── 사용자 테이블 ───────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `users` (
  `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `google_id`    VARCHAR(255)    NOT NULL UNIQUE,
  `email`        VARCHAR(255)    NOT NULL UNIQUE,
  `name`         VARCHAR(255)    NOT NULL,
  `avatar_url`   TEXT,
  `role`         ENUM('user','moderator','admin') NOT NULL DEFAULT 'user',
  `is_banned`    TINYINT(1)      NOT NULL DEFAULT 0,
  `created_at`   DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_google_id` (`google_id`),
  INDEX `idx_email`     (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── 스트림 테이블 ───────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `streams` (
  `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`      BIGINT UNSIGNED NOT NULL,
  `stream_key`   VARCHAR(64)     NOT NULL UNIQUE,
  `title`        VARCHAR(255)    NOT NULL DEFAULT '라이브 방송',
  `description`  TEXT,
  `is_live`      TINYINT(1)      NOT NULL DEFAULT 0,
  `viewer_count` INT UNSIGNED    NOT NULL DEFAULT 0,
  `started_at`   DATETIME,
  `ended_at`     DATETIME,
  `created_at`   DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  INDEX `idx_stream_key` (`stream_key`),
  INDEX `idx_is_live`    (`is_live`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── 채팅 메시지 테이블 ──────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `chat_messages` (
  `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `stream_id`  BIGINT UNSIGNED NOT NULL,
  `user_id`    BIGINT UNSIGNED NOT NULL,
  `message`    TEXT            NOT NULL,
  `is_deleted` TINYINT(1)      NOT NULL DEFAULT 0,
  `created_at` DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`stream_id`) REFERENCES `streams`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`)   REFERENCES `users`(`id`)   ON DELETE CASCADE,
  INDEX `idx_stream_id` (`stream_id`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── 감사 로그 테이블 ────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`    BIGINT UNSIGNED,
  `action`     VARCHAR(100)    NOT NULL,
  `target`     VARCHAR(255),
  `ip_address` VARCHAR(45),
  `user_agent` TEXT,
  `created_at` DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  INDEX `idx_action`     (`action`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
