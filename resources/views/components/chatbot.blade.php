<div class="chatbot-widget" id="chatbot-widget">

    {{-- Tombol chat floating (lingkaran, kanan bawah) --}}
    <button type="button" id="chatbot-toggle" class="chatbot-fab" aria-label="Buka chatbot Nanya AI" aria-expanded="false"
        aria-controls="chatbot-panel">
        <svg class="chatbot-fab-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">

            <!-- Antena -->
            <path d="M12 3v3" />
            <circle cx="12" cy="2.5" r="0.5" fill="currentColor" />

            <!-- Kepala robot -->
            <rect x="4" y="6" width="16" height="14" rx="3" />

            <!-- Mata -->
            <circle cx="9" cy="12" r="1" fill="currentColor" />
            <circle cx="15" cy="12" r="1" fill="currentColor" />

            <!-- Mulut -->
            <path d="M9 16h6" />

            <!-- Sisi telinga -->
            <path d="M4 11H2.5v4H4" />
            <path d="M20 11h1.5v4H20" />

        </svg>
        <span class="chatbot-fab-badge" aria-hidden="true"></span>
    </button>

    {{-- Panel chatbot --}}
    <section id="chatbot-panel" class="chatbot-panel" role="dialog" aria-label="Panel chatbot Nanya AI"
        aria-hidden="true">

        {{-- Header --}}
        <header class="chatbot-header">
            <div class="chatbot-header-info">
                <div class="chatbot-avatar" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <rect x="4" y="8" width="16" height="12" rx="2" />
                        <path d="M12 8V4" />
                        <circle cx="12" cy="3" r="1" />
                        <circle cx="9" cy="13" r="1" fill="currentColor" />
                        <circle cx="15" cy="13" r="1" fill="currentColor" />
                        <path d="M9 17h6" />
                    </svg>
                </div>
                <div>
                    <h2 class="chatbot-title">Nanya AI</h2>
                    <p class="chatbot-subtitle">
                        <span class="chatbot-online-dot" aria-hidden="true"></span>
                        Asisten Informasi Sekolah
                    </p>
                </div>
            </div>
            <div class="chatbot-header-actions">
                <button type="button" id="chatbot-clear" class="chatbot-icon-btn" aria-label="Hapus Percakapan"
                    title="Hapus Percakapan">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 6h18" />
                        <path d="M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2" />
                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                        <path d="M10 11v6" />
                        <path d="M14 11v6" />
                    </svg>
                </button>
                <button type="button" id="chatbot-close" class="chatbot-icon-btn" aria-label="Tutup chatbot">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M18 6L6 18" />
                        <path d="M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </header>

        {{-- Area percakapan --}}
        <div id="chatbot-messages" class="chatbot-messages" role="log" aria-live="polite" aria-relevant="additions">
            {{-- Pesan dirender oleh JavaScript memakai textContent (aman dari XSS) --}}
        </div>

        {{-- Indikator sedang mengetik (ikon robot + titik berdenyut) --}}
        <div id="chatbot-typing" class="chatbot-typing" aria-live="polite" hidden>
            <span class="chatbot-typing-avatar" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <rect x="4" y="8" width="16" height="12" rx="2" />
                    <path d="M12 8V4" />
                    <circle cx="12" cy="3" r="1" />
                    <circle cx="9" cy="13" r="1" fill="currentColor" />
                    <circle cx="15" cy="13" r="1" fill="currentColor" />
                    <path d="M9 17h6" />
                </svg>
            </span>
            <span class="chatbot-typing-dots" aria-hidden="true"><i></i><i></i><i></i></span>
            <span class="chatbot-typing-label">Nanya AI sedang mengetik...</span>
        </div>

        {{-- Footer: input + kirim --}}
        <footer class="chatbot-footer">
            <label for="chatbot-input" class="chatbot-sr-only">Tulis pertanyaan Anda</label>
            <textarea id="chatbot-input" class="chatbot-input" rows="1" maxlength="800"
                placeholder="Tulis pertanyaan Anda..." aria-label="Tulis pertanyaan Anda"></textarea>
            <button type="button" id="chatbot-send" class="chatbot-send" aria-label="Kirim pesan">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M22 2L11 13" />
                    <path d="M22 2l-7 20-4-9-9-4 20-7z" />
                </svg>
            </button>
        </footer>
    </section>
</div>

<style>
    .chatbot-widget {
        --chatbot-primary: #0066C4;
        --chatbot-primary-dark: #0052A3;
        --chatbot-on-primary: #FFFFFF;
        --chatbot-bot-bg: #F1F5F9;
        --chatbot-bot-text: #1E293B;
        --chatbot-user-text: #FFFFFF;
        --chatbot-panel-bg: #FFFFFF;
        --chatbot-border: #E2E8F0;
        --chatbot-muted: #64748B;
        --chatbot-online: #22C55E;
        --chatbot-radius: 16px;

        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
        font-family: 'Plus Jakarta Sans', 'Inter', system-ui, sans-serif;
    }

    /* Atribut `hidden` harus menang atas display: flex/grid di bawah.
       Tanpa aturan ini, `.chatbot-typing { display: flex }` menimpa
       `[hidden] { display: none }` dari UA stylesheet sehingga indikator
       "sedang mengetik" selalu terlihat. */
    .chatbot-widget [hidden] {
        display: none !important;
    }

    .chatbot-sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }

    /* --- Tombol floating --- */
    .chatbot-fab {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 56px;
        height: 56px;
        border-radius: 9999px;
        border: none;
        cursor: pointer;
        background: var(--chatbot-primary);
        color: var(--chatbot-on-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 25px rgba(0, 102, 196, .35);
        transition: transform .25s ease, background-color .25s ease, box-shadow .25s ease;
    }

    .chatbot-fab:hover {
        background: var(--chatbot-primary-dark);
        transform: scale(1.06);
    }

    .chatbot-fab:focus-visible {
        outline: 3px solid var(--chatbot-primary);
        outline-offset: 3px;
    }

    .chatbot-fab-icon {
        width: 26px;
        height: 26px;
    }

    .chatbot-widget.is-open .chatbot-fab {
        transform: scale(0);
        opacity: 0;
        pointer-events: none;
    }

    /* --- Panel --- */
    .chatbot-panel {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 384px;
        height: min(540px, calc(100vh - 48px));
        background: var(--chatbot-panel-bg);
        border: 1px solid var(--chatbot-border);
        border-radius: var(--chatbot-radius);
        box-shadow: 0 20px 50px -10px rgba(15, 23, 42, .3);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        opacity: 0;
        visibility: hidden;
        transform: translateY(16px) scale(.97);
        transform-origin: bottom right;
        transition: opacity .25s ease, transform .25s ease, visibility .25s;
    }

    .chatbot-widget.is-open .chatbot-panel {
        opacity: 1;
        visibility: visible;
        transform: translateY(0) scale(1);
    }

    /* --- Header --- */
    .chatbot-header {
        background: var(--chatbot-primary);
        color: var(--chatbot-on-primary);
        padding: 14px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        flex-shrink: 0;
    }

    .chatbot-header-info {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
    }

    .chatbot-avatar {
        width: 38px;
        height: 38px;
        border-radius: 9999px;
        background: rgba(255, 255, 255, .18);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .chatbot-avatar svg {
        width: 22px;
        height: 22px;
    }

    .chatbot-title {
        font-size: 15px;
        font-weight: 800;
        margin: 0;
        line-height: 1.2;
        color: inherit;
    }

    .chatbot-subtitle {
        font-size: 11.5px;
        margin: 2px 0 0;
        display: flex;
        align-items: center;
        gap: 5px;
        opacity: .92;
        font-weight: 500;
    }

    .chatbot-online-dot {
        width: 8px;
        height: 8px;
        border-radius: 9999px;
        background: var(--chatbot-online);
        display: inline-block;
        box-shadow: 0 0 0 2px rgba(34, 197, 94, .3);
    }

    .chatbot-header-actions {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .chatbot-icon-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: none;
        background: transparent;
        color: var(--chatbot-on-primary);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color .2s ease;
    }

    .chatbot-icon-btn:hover {
        background: rgba(255, 255, 255, .18);
    }

    .chatbot-icon-btn:focus-visible {
        outline: 2px solid var(--chatbot-on-primary);
        outline-offset: 1px;
    }

    .chatbot-icon-btn svg {
        width: 17px;
        height: 17px;
    }

    /* --- Area pesan --- */
    .chatbot-messages {
        flex: 1;
        overflow-y: auto;
        padding: 16px 14px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        background: #F8FAFC;
        scroll-behavior: smooth;
    }

    .chatbot-bubble-row {
        display: flex;
        flex-direction: column;
        max-width: 85%;
    }

    .chatbot-bubble-row.chatbot-row-user {
        align-self: flex-end;
        align-items: flex-end;
    }

    .chatbot-bubble-row.chatbot-row-model {
        align-self: flex-start;
        align-items: flex-start;
    }

    .chatbot-bubble {
        padding: 10px 13px;
        border-radius: 14px;
        font-size: 13px;
        line-height: 1.55;
        white-space: pre-wrap;
        word-break: break-word;
        overflow-wrap: anywhere;
    }

    .chatbot-row-user .chatbot-bubble {
        background: var(--chatbot-primary);
        color: var(--chatbot-user-text);
        border-bottom-right-radius: 4px;
    }

    .chatbot-row-model .chatbot-bubble {
        background: var(--chatbot-bot-bg);
        color: var(--chatbot-bot-text);
        border-bottom-left-radius: 4px;
    }

    .chatbot-row-model .chatbot-bubble.chatbot-bubble-error {
        background: #FEF2F2;
        color: #B91C1C;
    }

    .chatbot-time {
        font-size: 10px;
        color: var(--chatbot-muted);
        margin-top: 3px;
        padding: 0 4px;
    }

    /* --- Tombol pertanyaan cepat --- */
    .chatbot-quick {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 4px;
    }

    .chatbot-quick-btn {
        border: 1px solid var(--chatbot-primary);
        color: var(--chatbot-primary);
        background: #FFFFFF;
        border-radius: 9999px;
        padding: 6px 12px;
        font-size: 11.5px;
        font-weight: 600;
        cursor: pointer;
        font-family: inherit;
        transition: background-color .2s ease, color .2s ease;
    }

    .chatbot-quick-btn:hover {
        background: var(--chatbot-primary);
        color: var(--chatbot-on-primary);
    }

    .chatbot-quick-btn:focus-visible {
        outline: 2px solid var(--chatbot-primary);
        outline-offset: 2px;
    }

    /* --- Indikator mengetik --- */
    .chatbot-typing {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        font-size: 11.5px;
        color: var(--chatbot-muted);
        background: #F8FAFC;
        border-top: 1px solid var(--chatbot-border);
        flex-shrink: 0;
    }

    .chatbot-typing-avatar {
        width: 16px;
        height: 16px;
        flex-shrink: 0;
        display: inline-flex;
        color: var(--chatbot-primary);
        animation: chatbot-typing-pulse 1.2s infinite cubic-bezier(.32, .72, 0, 1);
    }

    .chatbot-typing-avatar svg {
        width: 16px;
        height: 16px;
    }

    .chatbot-typing-label {
        min-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .chatbot-typing-dots {
        display: inline-flex;
        gap: 3px;
    }

    .chatbot-typing-dots i {
        width: 5px;
        height: 5px;
        border-radius: 9999px;
        background: var(--chatbot-primary);
        animation: chatbot-typing-blink 1.2s infinite ease-in-out;
    }

    .chatbot-typing-dots i:nth-child(2) {
        animation-delay: .2s;
    }

    .chatbot-typing-dots i:nth-child(3) {
        animation-delay: .4s;
    }

    @keyframes chatbot-typing-blink {

        0%,
        80%,
        100% {
            opacity: .25;
            transform: translateY(0);
        }

        40% {
            opacity: 1;
            transform: translateY(-2px);
        }
    }

    @keyframes chatbot-typing-pulse {

        0%,
        80%,
        100% {
            opacity: .45;
            transform: translateY(0);
        }

        40% {
            opacity: 1;
            transform: translateY(-1.5px);
        }
    }

    /* --- Footer input --- */
    .chatbot-footer {
        display: flex;
        align-items: flex-end;
        gap: 8px;
        padding: 10px 12px;
        border-top: 1px solid var(--chatbot-border);
        background: var(--chatbot-panel-bg);
        flex-shrink: 0;
    }

    .chatbot-input {
        flex: 1;
        resize: none;
        border: 1px solid var(--chatbot-border);
        border-radius: 12px;
        padding: 9px 12px;
        font-size: 13px;
        font-family: inherit;
        line-height: 1.45;
        color: #1E293B;
        background: #FFFFFF;
        max-height: 84px;
        overflow-y: auto;
        transition: border-color .2s ease, box-shadow .2s ease;
    }

    .chatbot-input::placeholder {
        color: #94A3B8;
    }

    .chatbot-input:focus {
        outline: none;
        border-color: var(--chatbot-primary);
        box-shadow: 0 0 0 3px rgba(0, 102, 196, .15);
    }

    .chatbot-input:disabled,
    .chatbot-send:disabled {
        opacity: .55;
        cursor: not-allowed;
    }

    .chatbot-send {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        border: none;
        background: var(--chatbot-primary);
        color: var(--chatbot-on-primary);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: background-color .2s ease, transform .15s ease;
    }

    .chatbot-send:hover:not(:disabled) {
        background: var(--chatbot-primary-dark);
        transform: scale(1.05);
    }

    .chatbot-send:focus-visible {
        outline: 2px solid var(--chatbot-primary);
        outline-offset: 2px;
    }

    .chatbot-send svg {
        width: 17px;
        height: 17px;
    }

    /* --- Mobile: panel hampir selebar layar --- */
    @media (max-width: 480px) {
        .chatbot-widget {
            bottom: 12px;
            right: 12px;
            left: 12px;
        }

        .chatbot-fab {
            right: 0;
            bottom: 0;
        }

        .chatbot-panel {
            width: calc(100vw - 24px);
            height: calc(100vh - 110px);
            right: 0;
            bottom: 0;
        }
    }

    @media (prefers-reduced-motion: reduce) {

        .chatbot-panel,
        .chatbot-fab,
        .chatbot-typing-dots i,
        .chatbot-typing-avatar {
            transition: none;
            animation: none;
        }
    }
</style>

<script>
    (function() {
        'use strict';

        // ===== Konfigurasi =====
        var ENDPOINT = @json(route('chatbot.send'));
        var STORAGE_KEY = 'nanya_ai_chat_v1';
        var STORAGE_KEY_LAMA = 'sapasekolah_ai_chat_v1'; // dibersihkan sekali saat halaman dimuat
        var MAX_LOCAL_MESSAGES = 20; // maksimal riwayat di localStorage
        var MAX_SERVER_HISTORY = 10; // maksimal riwayat yang dikirim ke Gemini

        var WELCOME_TEXT =
            'Halo! Saya Nanya AI, asisten informasi sekolah. Saya dapat membantu Anda mencari informasi tentang PPDB, jurusan, prestasi, PKL, BKK, kerja sama industri, dan produk unggulan sekolah. Ada yang ingin ditanyakan?';

        var QUICK_QUESTIONS = [
            'Informasi PPDB',
            'Jurusan yang tersedia',
            'Prestasi sekolah',
            'Informasi PKL',
            'BKK dan lowongan kerja',
            'Produk unggulan sekolah',
        ];

        var ERROR_GENERIC = 'Maaf, terjadi gangguan pada layanan chatbot. Silakan coba lagi beberapa saat.';
        var ERROR_RATE_LIMIT = 'Anda terlalu sering mengirim pesan. Mohon tunggu sebentar lalu coba lagi.';

        // ===== Elemen DOM =====
        var widget = document.getElementById('chatbot-widget');
        var toggleBtn = document.getElementById('chatbot-toggle');
        var panel = document.getElementById('chatbot-panel');
        var closeBtn = document.getElementById('chatbot-close');
        var clearBtn = document.getElementById('chatbot-clear');
        var messagesEl = document.getElementById('chatbot-messages');
        var typingEl = document.getElementById('chatbot-typing');
        var inputEl = document.getElementById('chatbot-input');
        var sendBtn = document.getElementById('chatbot-send');

        var messages = loadMessages();
        var isProcessing = false;

        // ===== Penyimpanan riwayat (localStorage) =====
        function loadMessages() {
            try {
                var raw = window.localStorage.getItem(STORAGE_KEY);
                if (!raw) return [];
                var parsed = JSON.parse(raw);
                if (!Array.isArray(parsed)) return [];
                return parsed
                    .filter(function(m) {
                        return m && (m.role === 'user' || m.role === 'model') && typeof m.text === 'string';
                    })
                    .slice(-MAX_LOCAL_MESSAGES);
            } catch (e) {
                return [];
            }
        }

        function saveMessages() {
            try {
                window.localStorage.setItem(STORAGE_KEY, JSON.stringify(messages.slice(-MAX_LOCAL_MESSAGES)));
            } catch (e) {
                // localStorage penuh/diblokir: abaikan, chat tetap berjalan.
            }
        }

        // Buang sisa riwayat dari key versi lama (sebelum nama diganti).
        function bersihkanKeyLama() {
            try {
                window.localStorage.removeItem(STORAGE_KEY_LAMA);
            } catch (e) {
                // localStorage diblokir: abaikan.
            }
        }

        // ===== Rendering (textContent, aman dari XSS) =====
        function formatTime(ts) {
            var date = new Date(ts);
            if (isNaN(date.getTime())) date = new Date();
            var h = String(date.getHours()).padStart(2, '0');
            var m = String(date.getMinutes()).padStart(2, '0');
            return h + '.' + m;
        }

        function renderBubble(message) {
            var row = document.createElement('div');
            row.className = 'chatbot-bubble-row chatbot-row-' + (message.role === 'user' ? 'user' : 'model');

            var bubble = document.createElement('div');
            bubble.className = 'chatbot-bubble' + (message.isError ? ' chatbot-bubble-error' : '');
            bubble.textContent = message.text; // textContent, bukan innerHTML

            var time = document.createElement('span');
            time.className = 'chatbot-time';
            time.textContent = formatTime(message.time || Date.now());

            row.appendChild(bubble);
            row.appendChild(time);
            return row;
        }

        function renderQuickQuestions() {
            var wrap = document.createElement('div');
            wrap.className = 'chatbot-quick';

            QUICK_QUESTIONS.forEach(function(label) {
                var btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'chatbot-quick-btn';
                btn.textContent = label;
                btn.setAttribute('aria-label', 'Tanyakan: ' + label);
                btn.addEventListener('click', function() {
                    sendMessage(label);
                });
                wrap.appendChild(btn);
            });

            messagesEl.appendChild(wrap);
        }

        function renderAll() {
            messagesEl.textContent = '';

            if (messages.length === 0) {
                // PERBAIKAN: tambahkan bubble sambutan ke messagesEl secara eksplisit
                messagesEl.appendChild(renderBubble({
                    role: 'model',
                    text: WELCOME_TEXT,
                    time: Date.now()
                }));
                renderQuickQuestions();
            } else {
                messages.forEach(function(message) {
                    messagesEl.appendChild(renderBubble(message));
                });
            }

            scrollToBottom();
        }

        function appendMessage(message) {
            messages.push(message);
            saveMessages();

            var quick = messagesEl.querySelector('.chatbot-quick');
            if (quick) quick.remove();

            messagesEl.appendChild(renderBubble(message));
            scrollToBottom();
        }

        function scrollToBottom() {
            messagesEl.scrollTop = messagesEl.scrollHeight;
        }

        // ===== Buka / tutup panel =====
        function openPanel() {
            widget.classList.add('is-open');
            toggleBtn.setAttribute('aria-expanded', 'true');
            panel.setAttribute('aria-hidden', 'false');
            window.setTimeout(function() {
                inputEl.focus();
            }, 260);
        }

        function closePanel() {
            widget.classList.remove('is-open');
            toggleBtn.setAttribute('aria-expanded', 'false');
            panel.setAttribute('aria-hidden', 'true');
            toggleBtn.focus();
        }

        toggleBtn.addEventListener('click', openPanel);
        closeBtn.addEventListener('click', closePanel);

        // Escape menutup panel chatbot
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && widget.classList.contains('is-open')) {
                closePanel();
            }
        });

        // ===== Hapus percakapan (dengan konfirmasi) =====
        clearBtn.addEventListener('click', function() {
            if (!window.confirm('Hapus seluruh riwayat percakapan di browser ini?')) {
                return;
            }
            try {
                window.localStorage.removeItem(STORAGE_KEY);
            } catch (e) {
                /* abaikan */
            }
            messages = [];
            renderAll();
            inputEl.focus();
        });

        // ===== Pengiriman pesan =====
        function setProcessing(state) {
            isProcessing = state;
            inputEl.disabled = state;
            sendBtn.disabled = state;
            typingEl.hidden = !state;
            if (state) {
                scrollToBottom();
            } else {
                inputEl.focus();
                autoGrow();
            }
        }

        function buildServerHistory() {
            return messages
                .filter(function(m) {
                    return !m.isError;
                })
                .slice(-MAX_SERVER_HISTORY)
                .map(function(m) {
                    return {
                        role: m.role,
                        parts: [{
                            text: String(m.text).slice(0, 800)
                        }],
                    };
                });
        }

        function csrfToken() {
            var meta = document.querySelector('meta[name="csrf-token"]');
            return meta ? meta.getAttribute('content') : '';
        }

        function sendMessage(text) {
            var question = String(text || '').trim().slice(0, 800);
            if (question === '' || isProcessing) return;

            // Riwayat diambil SEBELUM pesan baru ditambahkan, agar pertanyaan
            // saat ini tidak terkirim dua kali (di history dan di message).
            var serverHistory = buildServerHistory();

            appendMessage({
                role: 'user',
                text: question,
                time: Date.now()
            });
            inputEl.value = '';
            setProcessing(true);

            window.fetch(ENDPOINT, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken(),
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({
                        message: question,
                        history: serverHistory,
                    }),
                })
                .then(function(response) {
                    return response.json()
                        .catch(function() {
                            return null;
                        })
                        .then(function(data) {
                            return {
                                status: response.status,
                                data: data
                            };
                        });
                })
                .then(function(result) {
                    var answer = null;
                    var isError = false;

                    if (result.status === 200 && result.data && typeof result.data.answer === 'string') {
                        answer = result.data.answer;
                    } else if (result.status === 429) {
                        answer = ERROR_RATE_LIMIT;
                        isError = true;
                    } else if (result.status === 422 && result.data && result.data.message) {
                        answer = result.data.message;
                        isError = true;
                    } else {
                        answer = ERROR_GENERIC;
                        isError = true;
                    }

                    appendMessage({
                        role: 'model',
                        text: answer,
                        time: Date.now(),
                        isError: isError
                    });
                })
                .catch(function() {
                    appendMessage({
                        role: 'model',
                        text: ERROR_GENERIC,
                        time: Date.now(),
                        isError: true
                    });
                })
                .finally(function() {
                    setProcessing(false);
                });
        }

        sendBtn.addEventListener('click', function() {
            sendMessage(inputEl.value);
        });

        // Enter = kirim, Shift+Enter = baris baru
        inputEl.addEventListener('keydown', function(event) {
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
                sendMessage(inputEl.value);
            }
        });

        // Textarea tumbuh otomatis (maks ~4 baris)
        function autoGrow() {
            inputEl.style.height = 'auto';
            inputEl.style.height = Math.min(inputEl.scrollHeight, 84) + 'px';
        }
        inputEl.addEventListener('input', autoGrow);

        // ===== Inisialisasi =====
        bersihkanKeyLama();
        renderAll();
    })();
</script>
