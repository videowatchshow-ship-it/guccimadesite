/**
 * Google Authentication Integration Module
 * Official Documentation: https://developers.google.com/identity/gsi/web/reference/html-reference
 * Source: https://developers.google.com/identity/gsi/web
 * Version: Latest (2026-04), Date: 2026-04-22
 * 
 * Features:
 * - Auto-inject login button on all pages (top-right corner)
 * - One Tap prompt support
 * - Auto-display signup modal
 * - SEO friendly
 */

(function() {
  'use strict';

  const CONFIG = {
    CLIENT_ID: '956283750273-do3ebgq60vbi585r62ffpk0cqts8l634.apps.googleusercontent.com',
    API_ENDPOINT: '/site_content/google-auth-api.php',
    BUTTON_POSITION: 'top-right',
    ENABLE_ONE_TAP: true,
    AUTO_PROMPT: true
  };

  let currentUser = null;
  let csrfToken = null;

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  function init() {
    injectStyles();
    createAuthContainer();
    loadGoogleGSI();
    checkLoginStatus();
  }

  function injectStyles() {
    const style = document.createElement('style');
    style.textContent = `
      #gucci-google-auth-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 999999;
        display: flex;
        align-items: center;
        gap: 12px;
        background: rgba(255, 255, 255, 0.98);
        padding: 8px 16px;
        border-radius: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        font-family: 'Roboto', 'Noto Sans KR', sans-serif;
      }
      #gucci-google-signin-button {
        display: inline-block;
      }
      #gucci-user-info {
        display: none;
        align-items: center;
        gap: 10px;
      }
      #gucci-user-info.active {
        display: flex;
      }
      #gucci-user-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
      }
      #gucci-user-name {
        font-size: 14px;
        font-weight: 500;
        color: #202124;
        max-width: 150px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
      }
      #gucci-logout-btn {
        background: #f1f3f4;
        border: none;
        padding: 6px 12px;
        border-radius: 16px;
        font-size: 13px;
        color: #5f6368;
        cursor: pointer;
        transition: background 0.2s;
      }
      #gucci-logout-btn:hover {
        background: #e8eaed;
      }
      #gucci-signup-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000000;
        justify-content: center;
        align-items: center;
      }
      #gucci-signup-modal.active {
        display: flex;
      }
      #gucci-signup-modal-content {
        background: white;
        padding: 32px;
        border-radius: 16px;
        max-width: 500px;
        width: 90%;
        box-shadow: 0 4px 16px rgba(0,0,0,0.2);
        position: relative;
      }
      #gucci-signup-modal h2 {
        margin: 0 0 24px 0;
        font-size: 24px;
        color: #202124;
      }
      #gucci-signup-form {
        display: flex;
        flex-direction: column;
        gap: 16px;
      }
      #gucci-signup-form label {
        display: flex;
        flex-direction: column;
        gap: 6px;
        font-size: 14px;
        color: #5f6368;
      }
      #gucci-signup-form input {
        padding: 12px;
        border: 1px solid #dadce0;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.2s;
      }
      #gucci-signup-form input:focus {
        outline: none;
        border-color: #1a73e8;
      }
      #gucci-signup-form button {
        background: #1a73e8;
        color: white;
        border: none;
        padding: 12px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.2s;
      }
      #gucci-signup-form button:hover {
        background: #1765cc;
      }
      #gucci-signup-close {
        position: absolute;
        top: 16px;
        right: 16px;
        background: none;
        border: none;
        font-size: 24px;
        color: #5f6368;
        cursor: pointer;
        padding: 4px 8px;
      }
      @media (max-width: 768px) {
        #gucci-google-auth-container {
          top: 10px;
          right: 10px;
          padding: 6px 12px;
          border-radius: 20px;
        }
        #gucci-user-name {
          max-width: 100px;
          font-size: 13px;
        }
        #gucci-signup-modal-content {
          padding: 24px;
          width: 95%;
        }
      }
    `;
    document.head.appendChild(style);
  }

  function createAuthContainer() {
    const container = document.createElement('div');
    container.id = 'gucci-google-auth-container';
    container.innerHTML = '<div id="gucci-google-signin-button"></div><div id="gucci-user-info"><img id="gucci-user-avatar" src="" alt="User Avatar"><span id="gucci-user-name"></span><button id="gucci-logout-btn">Logout</button></div>';
    document.body.appendChild(container);

    const modal = document.createElement('div');
    modal.id = 'gucci-signup-modal';
    modal.innerHTML = '<div id="gucci-signup-modal-content"><button id="gucci-signup-close">&times;</button><h2>Member Information</h2><form id="gucci-signup-form"><label>Real Name<input type="text" id="gucci-real-name" required maxlength="50"></label><label>Phone Number<input type="tel" id="gucci-phone" required pattern="[0-9-]+" maxlength="20"></label><label>Bank Name<input type="text" id="gucci-bank-name" required maxlength="30"></label><label>Account Number<input type="text" id="gucci-account-number" required pattern="[0-9-]+" maxlength="50"></label><button type="submit">Save</button></form></div>';
    document.body.appendChild(modal);

    document.getElementById('gucci-logout-btn').addEventListener('click', handleLogout);
    document.getElementById('gucci-signup-close').addEventListener('click', closeSignupModal);
    document.getElementById('gucci-signup-form').addEventListener('submit', handleSignupSubmit);
    
    modal.addEventListener('click', (e) => {
      if (e.target === modal) closeSignupModal();
    });
  }

  function loadGoogleGSI() {
    if (window.google?.accounts) {
      initializeGoogleSignIn();
      return;
    }

    const script = document.createElement('script');
    script.src = 'https://accounts.google.com/gsi/client';
    script.async = true;
    script.defer = true;
    script.onload = initializeGoogleSignIn;
    script.onerror = () => {
      console.error('[GoogleAuth] GSI library load failed');
    };
    document.head.appendChild(script);
  }

  function initializeGoogleSignIn() {
    if (!window.google?.accounts?.id) {
      console.error('[GoogleAuth] Google Identity Services unavailable');
      return;
    }

    google.accounts.id.initialize({
      client_id: CONFIG.CLIENT_ID,
      callback: handleCredentialResponse,
      auto_select: false,
      cancel_on_tap_outside: true,
      context: 'signin',
      ux_mode: 'popup',
      itp_support: true
    });

    google.accounts.id.renderButton(
      document.getElementById('gucci-google-signin-button'),
      {
        theme: 'outline',
        size: 'large',
        type: 'standard',
        shape: 'pill',
        text: 'signin_with',
        logo_alignment: 'left',
        width: 240,
        locale: 'ko'
      }
    );

    if (CONFIG.ENABLE_ONE_TAP && CONFIG.AUTO_PROMPT) {
      google.accounts.id.prompt(() => {});
    }

    console.log('[GoogleAuth] Google Sign-In initialized');
    try {
      const res = await fetch(CONFIG.API_ENDPOINT, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'include',
        body: JSON.stringify({
          action: 'login',
          credential: response.credential
        })
      });

      const data = await res.json();

      if (data.ok) {
        currentUser = data.user;
        csrfToken = data.csrf_token;
        updateUI();

        if (!currentUser.real_name || !currentUser.phone) {
          showSignupModal();
        }
      } else {
        alert('Login failed: ' + (data.msg || 'Unknown error'));
      }
    } catch (err) {
      console.error('[GoogleAuth] Login error:', err);
      alert('Login error occurred');
    }
  }

  async function checkLoginStatus() {
    try {
      const res = await fetch(CONFIG.API_ENDPOINT, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'include',
        body: JSON.stringify({ action: 'me' })
      });

      const data = await res.json();

      if (data.ok) {
        currentUser = data.user;
        csrfToken = data.csrf_token;
        updateUI();
      }
    } catch (err) {
      console.error('[GoogleAuth] Status check error:', err);
    }
  }

  function updateUI() {
    const signInBtn = document.getElementById('gucci-google-signin-button');
    const userInfo = document.getElementById('gucci-user-info');
    const userAvatar = document.getElementById('gucci-user-avatar');
    const userName = document.getElementById('gucci-user-name');

    if (signInBtn && userInfo) {
      if (currentUser) {
        signInBtn.style.display = 'none';
        userInfo.classList.add('active');
        if (userAvatar) userAvatar.src = currentUser.picture || 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="32" height="32"%3E%3Ccircle cx="16" cy="16" r="16" fill="%23ccc"/%3E%3C/svg%3E';
        if (userName) userName.textContent = currentUser.name || currentUser.email;
      } else {
        signInBtn.style.display = 'inline-block';
        userInfo.classList.remove('active');
      }
    }

    if (window.updateCustomButton) {
      window.updateCustomButton(currentUser);
    }
  }

  async function handleLogout() {
    try {
      const res = await fetch(CONFIG.API_ENDPOINT, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'include',
        body: JSON.stringify({ action: 'logout' })
      });

      const data = await res.json();

      if (data.ok) {
        currentUser = null;
        csrfToken = null;
        updateUI();
        
        if (window.google?.accounts?.id) {
          google.accounts.id.disableAutoSelect();
        }
        
        alert('Logged out');
        location.reload();
      }
    } catch (err) {
      console.error('[GoogleAuth] Logout error:', err);
    }
  }

  function showSignupModal() {
    const modal = document.getElementById('gucci-signup-modal');
    modal.classList.add('active');

    if (currentUser) {
      document.getElementById('gucci-real-name').value = currentUser.real_name || '';
      document.getElementById('gucci-phone').value = currentUser.phone || '';
      document.getElementById('gucci-bank-name').value = currentUser.bank_name || '';
      document.getElementById('gucci-account-number').value = currentUser.account_number || '';
    }
  }

  function closeSignupModal() {
    const modal = document.getElementById('gucci-signup-modal');
    modal.classList.remove('active');
  }

  async function handleSignupSubmit(e) {
    e.preventDefault();

    const realName = document.getElementById('gucci-real-name').value.trim();
    const phone = document.getElementById('gucci-phone').value.trim();
    const bankName = document.getElementById('gucci-bank-name').value.trim();
    const accountNumber = document.getElementById('gucci-account-number').value.trim();

    try {
      const res = await fetch(CONFIG.API_ENDPOINT, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'include',
        body: JSON.stringify({
          action: 'save_profile',
          csrf_token: csrfToken,
          real_name: realName,
          phone: phone,
          bank_name: bankName,
          account_number: accountNumber
        })
      });

      const data = await res.json();

      if (data.ok) {
        currentUser = data.user;
        closeSignupModal();
        alert('Profile saved');
      } else {
        alert('Save failed: ' + (data.msg || 'Unknown error'));
      }
    } catch (err) {
      console.error('[GoogleAuth] Profile save error:', err);
      alert('Save error occurred');
    }
  }

  window.GucciGoogleAuth = {
    getCurrentUser: () => currentUser,
    showSignupModal: showSignupModal,
    logout: handleLogout
  };

  window.handleGoogleLogin = function() {
    if (window.google?.accounts?.id) {
      google.accounts.id.prompt();
    } else {
      alert('Google Sign-In이 로드되지 않았습니다.');
    }
  };

  window.handleLogout = handleLogout;

})();
