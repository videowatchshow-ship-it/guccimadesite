// Official Docs: https://sidorares.github.io/node-mysql2/docs
// Official GitHub: https://github.com/sidorares/node-mysql2
// Version: mysql2 3.15.0 (2026-06-01)

'use strict';

const mysql = require('mysql2/promise');

// ─── 커넥션 풀 생성 ──────────────────────────────────────────────────────────
// Official: https://sidorares.github.io/node-mysql2/docs#using-connection-pools
const pool = mysql.createPool({
  host:               process.env.DB_HOST     || 'localhost',
  port:               parseInt(process.env.DB_PORT || '3306', 10),
  database:           process.env.DB_NAME     || 'guccimadesite',
  user:               process.env.DB_USER     || 'gucci',
  password:           process.env.DB_PASS,
  waitForConnections: true,
  connectionLimit:    10,
  queueLimit:         0,
  charset:            'utf8mb4',
  timezone:           '+00:00',
  // Parameterized query 강제 (SQL Injection 방지)
  // Official: https://sidorares.github.io/node-mysql2/docs#prepared-statements
  namedPlaceholders:  true,
});

/**
 * DB 연결 테스트
 * @returns {Promise<void>}
 */
async function testDbConnection() {
  const conn = await pool.getConnection();
  try {
    await conn.query('SELECT 1');
    process.stdout.write('[INFO] MariaDB 연결 성공\n');
  } finally {
    conn.release();
  }
}

/**
 * Parameterized query 실행
 * Official: https://sidorares.github.io/node-mysql2/docs#prepared-statements
 * @param {string} sql
 * @param {Array} params
 * @returns {Promise<Array>}
 */
async function query(sql, params = []) {
  const [rows] = await pool.execute(sql, params);
  return rows;
}

module.exports = { pool, query, testDbConnection };
