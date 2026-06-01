/**
 * 채팅 관리자 모듈
 * DOM 조작 및 사용자 인터페이스 관리
 */
window.ChatManager = (function () {
    'use strict';

    let nickname = localStorage.getItem(window.StreamingConfig.NICKNAME_STORAGE_KEY) || '익명';
    let isScrolledToBottom = true;

    const DOM = {
        messagesContainer: document.getElementById('messages'),
        messageInput: document.getElementById('message-input'),
        sendButton: document.getElementById('send-button'),
        statusIndicator: document.getElementById('status'),
        nicknameModal: document.getElementById('nickname-modal'),
        nicknameInput: document.getElementById('nickname-input'),
        modalConfirm: document.getElementById('modal-confirm'),
        modalCancel: document.getElementById('modal-cancel'),
        videoContainer: document.getElementById('video-container')
    };

    function getCurrentTime() {
        return new Date().toLocaleTimeString('ko-KR', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
    }

    function validateMessage(text) {
        if (!text || typeof text !== 'string') return false;
        const trimmed = text.trim();
        return trimmed.length > 0 && trimmed.length <= window.StreamingConfig.MESSAGE_MAX_LENGTH;
    }

    function validateNickname(name) {
        if (!name || typeof name !== 'string') return false;
        const trimmed = name.trim();
        return trimmed.length > 0 && trimmed.length <= window.StreamingConfig.NICKNAME_MAX_LENGTH;
    }

    function checkScrollPosition() {
        const container = DOM.messagesContainer;
        return container.scrollHeight - container.scrollTop - container.clientHeight < window.StreamingConfig.SCROLL_THRESHOLD;
    }

    function scrollToBottom() {
        DOM.messagesContainer.scrollTop = DOM.messagesContainer.scrollHeight;
    }

    function updateStatus(connected) {
        if (connected) {
            DOM.statusIndicator.textContent = '✓ 연결됨';
            DOM.statusIndicator.className = 'connected';
            DOM.sendButton.disabled = false;
        } else {
            DOM.statusIndicator.textContent = '✗ 연결 끊김';
            DOM.statusIndicator.className = 'disconnected';
            DOM.sendButton.disabled = true;
        }
    }

    function addMessage(user, text, isSystem) {
        const messageDiv = document.createElement('div');
        messageDiv.className = isSystem ? 'message message-system' : 'message';

        const userDiv = document.createElement('div');
        userDiv.className = 'message-user';
        userDiv.textContent = user;

        const textDiv = document.createElement('div');
        textDiv.className = 'message-text';
        textDiv.textContent = text;

        const timeDiv = document.createElement('div');
        timeDiv.className = 'message-time';
        timeDiv.textContent = getCurrentTime();

        messageDiv.appendChild(userDiv);
        messageDiv.appendChild(textDiv);
        messageDiv.appendChild(timeDiv);

        DOM.messagesContainer.appendChild(messageDiv);

        if (isScrolledToBottom) {
            scrollToBottom();
        }
    }

    function addSystemMessage(text) {
        addMessage('시스템', text, true);
    }

    function sendMessage() {
        const text = DOM.messageInput.value.trim();
        if (!validateMessage(text)) return false;

        const success = window.WebSocketClient.sendMessage(nickname, text);
        if (success) {
            addMessage(nickname, text, false);
            DOM.messageInput.value = ;
            DOM.messageInput.focus();
        }
        return success;
    }

    function showNicknameModal() {
        DOM.nicknameModal.classList.add('active');
        DOM.nicknameInput.value = nickname;
        DOM.nicknameInput.focus();
        DOM.nicknameInput.select();
    }

    function hideNicknameModal() {
        DOM.nicknameModal.classList.remove('active');
    }

    function saveNickname() {
        const newNickname = DOM.nicknameInput.value.trim();
        if (!validateNickname(newNickname)) {
            alert('닉네임은 1-20자 사이여야 합니다.');
            return;
        }

        nickname = newNickname;
        localStorage.setItem(window.StreamingConfig.NICKNAME_STORAGE_KEY, nickname);
        hideNicknameModal();
        addSystemMessage('닉네임이 "' + nickname + 으로 변경되었습니다.');
    }

    function bindEvents() {
        // 메시지 전송
        DOM.sendButton.addEventListener('click', sendMessage);
        DOM.messageInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });

        // 스크롤 감지
        DOM.messagesContainer.addEventListener('scroll', function () {
            isScrolledToBottom = checkScrollPosition();
        });

        // 닉네임 모달
        DOM.modalConfirm.addEventListener('click', saveNickname);
        DOM.modalCancel.addEventListener('click', hideNicknameModal);
        DOM.nicknameInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') saveNickname();
        });

        // 모달 외부 클릭으로 닫기
        DOM.nicknameModal.addEventListener('click', function (e) {
            if (e.target === DOM.nicknameModal) hideNicknameModal();
        });

        // 비디오 더블클릭으로 닉네임 변경
        DOM.videoContainer.addEventListener('dblclick', showNicknameModal);

        // 페이지 언로드 시 WebSocket 정리
        window.addEventListener('beforeunload', function () {
            window.WebSocketClient.disconnect();
        });

        // 온라인/오프라인 감지
        window.addEventListener('online', function () {
            if (!window.WebSocketClient.isConnected()) {
                window.WebSocketClient.connect();
            }
        });

        window.addEventListener('offline', function () {
            updateStatus(false);
        });
    }

    function initialize() {
        bindEvents();

        // WebSocket 콜백 설정
        window.WebSocketClient.setStatusCallback(updateStatus);
        window.WebSocketClient.setMessageCallback(addMessage);

        // 환영 메시지
        addSystemMessage('환영합니다! 닉네임: ' + nickname);
        addSystemMessage('비디오 영역 더블클릭으로 닉네임 변경 가능합니다.');

        // 입력 포커스
        DOM.messageInput.focus();
    }

    // 공개 API
    return {
        initialize: initialize,
        addMessage: addMessage,
        addSystemMessage: addSystemMessage,
        updateStatus: updateStatus,
        getNickname: function () { return nickname; }
    };
})();
