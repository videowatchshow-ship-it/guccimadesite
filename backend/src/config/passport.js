// Official Docs: https://www.passportjs.org/packages/passport-google-oauth20/
// Official GitHub: https://github.com/jaredhanson/passport-google-oauth2
// Version: passport-google-oauth20 2.0.0 (2026-06-01)
// Google OAuth: https://developers.google.com/identity/protocols/oauth2

'use strict';

const passport = require('passport');
const { Strategy: GoogleStrategy } = require('passport-google-oauth20');
const { query } = require('./database');

// Official: https://www.passportjs.org/concepts/authentication/strategies/
passport.use(new GoogleStrategy(
  {
    clientID:     process.env.GOOGLE_CLIENT_ID,
    clientSecret: process.env.GOOGLE_CLIENT_SECRET,
    callbackURL:  `https://${process.env.DOMAIN}/api/auth/google/callback`,
    scope:        ['profile', 'email'],
  },
  async (_accessToken, _refreshToken, profile, done) => {
    try {
      const googleId = profile.id;
      const email    = profile.emails?.[0]?.value || '';
      const name     = profile.displayName || '';
      const avatar   = profile.photos?.[0]?.value || null;

      // 기존 사용자 조회 (Parameterized query)
      const existing = await query(
        'SELECT * FROM users WHERE google_id = ? LIMIT 1',
        [googleId],
      );

      if (existing.length > 0) {
        // 정보 업데이트
        await query(
          'UPDATE users SET name = ?, avatar_url = ?, updated_at = NOW() WHERE google_id = ?',
          [name, avatar, googleId],
        );
        return done(null, existing[0]);
      }

      // 신규 사용자 생성
      const result = await query(
        'INSERT INTO users (google_id, email, name, avatar_url) VALUES (?, ?, ?, ?)',
        [googleId, email, name, avatar],
      );

      const newUser = await query(
        'SELECT * FROM users WHERE id = ? LIMIT 1',
        [result.insertId],
      );

      return done(null, newUser[0]);
    } catch (err) {
      return done(err, null);
    }
  },
));

// Official: https://www.passportjs.org/concepts/authentication/sessions/
passport.serializeUser((user, done) => {
  done(null, user.id);
});

passport.deserializeUser(async (id, done) => {
  try {
    const users = await query('SELECT * FROM users WHERE id = ? LIMIT 1', [id]);
    done(null, users[0] || null);
  } catch (err) {
    done(err, null);
  }
});
