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
 * ref: https://www.php.net/manual/en/function.include.php (header/footer)
 */
declare(strict_types=1);

// ── 헤더 포함 (공통 헤더, 푸터 포함 - SEO, 보안, 메뉴 포함)
$doc_root = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS);
if ($doc_root && file_exists($doc_root . '/../core/helpers/header.php')) {
    include $doc_root . '/../core/helpers/header.php';
}
if ($doc_root && file_exists($doc_root . '/../core/helpers/footer.php')) {
    include $doc_root . '/../core/helpers/footer.php';
}

/* ════════════════════════════════════════════════════
   DB 필수 코딩 20가지
   ════════════════════════════════════════════════════ */
