<?php
/**
 * 데이터베이스 헬퍼 — 구찌야놀자
 * DB 필수 코딩 20가지 구현
 *
 * 공식 문서:
 * ref: https://www.php.net/manual/en/book.mysqli.php
 * ref: https://mariadb.com/docs/server/ref/mdb/sql-statements/
 * ref: https://www.php.net/manual/en/mysqli.quickstart.prepared-statements.php
 * ref: https://owasp.org/www-project-top-ten/ (A03:2021 Injection)
 * ref: https://mariadb.com/docs/server/ref/mdb/sql-statements/SHOW_TABLES/
 */
declare(strict_types=1);

/* ════════════════════════════════════════════════════
   DB 필수 코딩 20가지
   ════════════════════════════════════════════════════ */

/**
 * [DB 01] 싱글톤 DB 연결 — 연결 재사용
 * ref: https://www.php.net/manual/en/class.mysqli.php
 * ref: https://www.php.net/manual/en/mysqli.construct.php
 */
function db_connect(): \mysqli
{
    static $db = null;
    if ($db instanceof \mysqli && $db->ping()) {
        return $db;
    }
    /* [DB 02] 환경변수로 자격증명 분리
     * ref: https://www.php.net/manual/en/function.getenv.php
     */
    $host = (string)(getenv('GUCCI_DB_HOST') ?: 'localhost');
    $user = (string)(getenv('GUCCI_DB_USER') ?: 'gucci_user');
    $pass = (string)(getenv('GUCCI_DB_PASS') ?: 'GuCCi2026Secure');
    $name = (string)(getenv('GUCCI_DB_NAME') ?: 'gucci_wordpress');

    /* [DB 03] 엄격 오류 보고 모드
     * ref: https://www.php.net/manual/en/mysqli-driver.report-mode.php
     */
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try {
        $db = new \mysqli($host, $user, $pass, $name);

        /* [DB 04] 문자셋 utf8mb4 강제 설정
         * ref: https://www.php.net/manual/en/mysqli.set-charset.php
         * ref: https://mariadb.com/docs/server/ref/mdb/sql-statements/SET_NAMES/
         */
        $db->set_charset('utf8mb4');

        /* [DB 05] SQL 모드 설정 — 엄격 모드
         * ref: https://mariadb.com/docs/server/ref/mdb/system-variables/sql_mode/
         */
        $db->query("SET sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");

        /* [DB 06] 타임존 설정
         * ref: https://mariadb.com/docs/server/ref/mdb/system-variables/time_zone/
         */
        $db->query("SET time_zone = '+09:00'");

    } catch (\mysqli_sql_exception $e) {
        error_log('DB 연결 실패: ' . $e->getMessage());
        throw new \RuntimeException('Database connection failed');
    }

    return $db;
}

/**
 * [DB 07] Prepared Statement — SQL Injection 방지
 * ref: https://www.php.net/manual/en/mysqli.quickstart.prepared-statements.php
 * ref: https://owasp.org/www-project-top-ten/ (A03:2021 Injection)
 */
function db_query(\mysqli $db, string $sql, string $types = '', array $params = []): \mysqli_result|bool
{
    if (empty($params)) {
        return $db->query($sql);
    }
    $stmt = $db->prepare($sql);
    if (!$stmt) {
        throw new \RuntimeException('Prepare failed: ' . $db->error);
    }
    if ($types && $params) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result !== false ? $result : true;
}

/**
 * [DB 08] 단일 행 조회
 * ref: https://www.php.net/manual/en/mysqli-result.fetch-assoc.php
 */
function db_fetch_one(\mysqli $db, string $sql, string $types = '', array $params = []): ?array
{
    $result = db_query($db, $sql, $types, $params);
    if (!($result instanceof \mysqli_result)) {
        return null;
    }
    $row = $result->fetch_assoc();
    $result->free();
    return $row ?: null;
}

/**
 * [DB 09] 다중 행 조회
 * ref: https://www.php.net/manual/en/mysqli-result.fetch-all.php
 */
function db_fetch_all(\mysqli $db, string $sql, string $types = '', array $params = []): array
{
    $result = db_query($db, $sql, $types, $params);
    if (!($result instanceof \mysqli_result)) {
        return [];
    }
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    $result->free();
    return $rows;
}

/**
 * [DB 10] INSERT — 마지막 삽입 ID 반환
 * ref: https://www.php.net/manual/en/mysqli.insert-id.php
 */
function db_insert(\mysqli $db, string $table, array $data): int
{
    /* 테이블명 정규식 검증: ^[a-z0-9_]+$
     * ref: https://www.regular-expressions.info/
     */
    if (!preg_match('/^[a-z0-9_]+$/', $table)) {
        throw new \InvalidArgumentException('Invalid table name');
    }
    $cols   = array_keys($data);
    $types  = '';
    $vals   = [];
    foreach ($data as $col => $val) {
        /* 컬럼명 정규식 검증 */
        if (!preg_match('/^[a-z0-9_]+$/', $col)) {
            throw new \InvalidArgumentException('Invalid column name: ' . $col);
        }
        if (is_int($val))    { $types .= 'i'; }
        elseif (is_float($val)) { $types .= 'd'; }
        else                 { $types .= 's'; }
        $vals[] = $val;
    }
    $placeholders = implode(', ', array_fill(0, count($cols), '?'));
    $col_list     = implode(', ', $cols);
    $sql          = "INSERT INTO `{$table}` ({$col_list}) VALUES ({$placeholders})";
    $stmt = $db->prepare($sql);
    $stmt->bind_param($types, ...$vals);
    $stmt->execute();
    $id = (int)$db->insert_id;
    $stmt->close();
    return $id;
}

/**
 * [DB 11] UPDATE — 영향받은 행 수 반환
 * ref: https://www.php.net/manual/en/mysqli.affected-rows.php
 */
function db_update(\mysqli $db, string $table, array $data, string $where_sql, string $where_types, array $where_params): int
{
    if (!preg_match('/^[a-z0-9_]+$/', $table)) {
        throw new \InvalidArgumentException('Invalid table name');
    }
    $set_parts = [];
    $types     = '';
    $vals      = [];
    foreach ($data as $col => $val) {
        if (!preg_match('/^[a-z0-9_]+$/', $col)) {
            throw new \InvalidArgumentException('Invalid column name: ' . $col);
        }
        $set_parts[] = "`{$col}` = ?";
        if (is_int($val))    { $types .= 'i'; }
        elseif (is_float($val)) { $types .= 'd'; }
        else                 { $types .= 's'; }
        $vals[] = $val;
    }
    $sql   = "UPDATE `{$table}` SET " . implode(', ', $set_parts) . " WHERE {$where_sql}";
    $types .= $where_types;
    $vals   = array_merge($vals, $where_params);
    $stmt  = $db->prepare($sql);
    $stmt->bind_param($types, ...$vals);
    $stmt->execute();
    $affected = (int)$db->affected_rows;
    $stmt->close();
    return $affected;
}

/**
 * [DB 12] 트랜잭션 처리
 * ref: https://www.php.net/manual/en/mysqli.begin-transaction.php
 * ref: https://mariadb.com/docs/server/ref/mdb/sql-statements/START_TRANSACTION/
 */
function db_transaction(\mysqli $db, callable $callback): mixed
{
    $db->begin_transaction();
    try {
        $result = $callback($db);
        $db->commit();
        return $result;
    } catch (\Throwable $e) {
        $db->rollback();
        error_log('DB 트랜잭션 롤백: ' . $e->getMessage());
        throw $e;
    }
}

/**
 * [DB 13] 페이지네이션 쿼리
 * ref: https://mariadb.com/docs/server/ref/mdb/sql-statements/SELECT/ (LIMIT/OFFSET)
 */
function db_paginate(\mysqli $db, string $sql, string $types, array $params, int $page, int $per_page = 20): array
{
    $page     = max(1, $page);
    $per_page = min(100, max(1, $per_page));
    $offset   = ($page - 1) * $per_page;

    /* 전체 수 조회 */
    $count_sql = "SELECT COUNT(*) AS total FROM ({$sql}) AS _count_wrap";
    $count_row = db_fetch_one($db, $count_sql, $types, $params);
    $total     = (int)($count_row['total'] ?? 0);

    /* 페이지 데이터 조회 */
    $paged_sql = $sql . " LIMIT ? OFFSET ?";
    $paged_types  = $types . 'ii';
    $paged_params = array_merge($params, [$per_page, $offset]);
    $rows = db_fetch_all($db, $paged_sql, $paged_types, $paged_params);

    return [
        'data'        => $rows,
        'total'       => $total,
        'page'        => $page,
        'per_page'    => $per_page,
        'total_pages' => (int)ceil($total / $per_page),
    ];
}

/**
 * [DB 14] 감사 로그 기록
 * ref: https://owasp.org/www-project-top-ten/ (A09:2021 Logging)
 * ref: https://mariadb.com/docs/server/ref/mdb/sql-statements/INSERT/
 */
function db_audit_log(\mysqli $db, ?int $user_id, string $action, string $detail = '', string $ip = ''): void
{
    try {
        $stmt = $db->prepare(
            "INSERT INTO gucci_audit_log (user_id, action, detail, ip_address, created_at)
             VALUES (?, ?, ?, ?, NOW())"
        );
        $stmt->bind_param('isss', $user_id, $action, $detail, $ip);
        $stmt->execute();
        $stmt->close();
    } catch (\Throwable $e) {
        error_log('감사 로그 기록 실패: ' . $e->getMessage());
    }
}

/**
 * [DB 15] 소프트 삭제 (is_deleted 플래그)
 * ref: https://mariadb.com/docs/server/ref/mdb/sql-statements/UPDATE/
 */
function db_soft_delete(\mysqli $db, string $table, int $id): bool
{
    if (!preg_match('/^[a-z0-9_]+$/', $table)) {
        throw new \InvalidArgumentException('Invalid table name');
    }
    $stmt = $db->prepare("UPDATE `{$table}` SET is_deleted = 1, updated_at = NOW() WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $affected = (int)$db->affected_rows;
    $stmt->close();
    return $affected > 0;
}

/**
 * [DB 16] 존재 여부 확인
 * ref: https://www.php.net/manual/en/mysqli.quickstart.prepared-statements.php
 */
function db_exists(\mysqli $db, string $table, string $where_sql, string $types, array $params): bool
{
    if (!preg_match('/^[a-z0-9_]+$/', $table)) {
        throw new \InvalidArgumentException('Invalid table name');
    }
    $sql = "SELECT 1 FROM `{$table}` WHERE {$where_sql} LIMIT 1";
    $row = db_fetch_one($db, $sql, $types, $params);
    return $row !== null;
}

/**
 * [DB 17] 연결 상태 확인 (헬스체크용)
 * ref: https://www.php.net/manual/en/mysqli.ping.php
 */
function db_health_check(\mysqli $db): array
{
    try {
        $ping = $db->ping();
        $row  = db_fetch_one($db, "SELECT VERSION() AS ver, NOW() AS now");
        return [
            'ok'      => $ping,
            'version' => $row['ver'] ?? 'unknown',
            'time'    => $row['now'] ?? 'unknown',
        ];
    } catch (\Throwable $e) {
        return ['ok' => false, 'error' => $e->getMessage()];
    }
}

/**
 * [DB 18] 검색 쿼리 — LIKE 이스케이프
 * ref: https://www.php.net/manual/en/mysqli.real-escape-string.php
 * ref: https://mariadb.com/docs/server/ref/mdb/sql-statements/SELECT/ (LIKE)
 */
function db_search(\mysqli $db, string $table, string $col, string $keyword, int $limit = 20): array
{
    if (!preg_match('/^[a-z0-9_]+$/', $table) || !preg_match('/^[a-z0-9_]+$/', $col)) {
        throw new \InvalidArgumentException('Invalid table or column name');
    }
    /* LIKE 와일드카드 이스케이프 */
    $escaped = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $keyword);
    $pattern = '%' . $escaped . '%';
    $sql     = "SELECT * FROM `{$table}` WHERE `{$col}` LIKE ? AND is_deleted = 0 ORDER BY created_at DESC LIMIT ?";
    return db_fetch_all($db, $sql, 'si', [$pattern, $limit]);
}

/**
 * [DB 19] 배치 INSERT (다중 행 한 번에 삽입)
 * ref: https://mariadb.com/docs/server/ref/mdb/sql-statements/INSERT/ (multi-row)
 */
function db_batch_insert(\mysqli $db, string $table, array $cols, array $rows): int
{
    if (!preg_match('/^[a-z0-9_]+$/', $table)) {
        throw new \InvalidArgumentException('Invalid table name');
    }
    if (empty($rows)) {
        return 0;
    }
    $col_list     = implode(', ', $cols);
    $placeholders = '(' . implode(', ', array_fill(0, count($cols), '?')) . ')';
    $all_placeholders = implode(', ', array_fill(0, count($rows), $placeholders));
    $sql   = "INSERT INTO `{$table}` ({$col_list}) VALUES {$all_placeholders}";
    $types = '';
    $vals  = [];
    foreach ($rows as $row) {
        foreach ($row as $val) {
            if (is_int($val))    { $types .= 'i'; }
            elseif (is_float($val)) { $types .= 'd'; }
            else                 { $types .= 's'; }
            $vals[] = $val;
        }
    }
    $stmt = $db->prepare($sql);
    $stmt->bind_param($types, ...$vals);
    $stmt->execute();
    $affected = (int)$db->affected_rows;
    $stmt->close();
    return $affected;
}

/**
 * [DB 20] 연결 종료
 * ref: https://www.php.net/manual/en/mysqli.close.php
 */
function db_close(\mysqli $db): void
{
    $db->close();
}
