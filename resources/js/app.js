// Global helper untuk menu sidebar demo dashboard.
if (typeof window.setActiveMenu !== 'function') {
    window.setActiveMenu = function (menuName) {
        const breadcrumb = document.getElementById('breadcrumb-page');

        if (breadcrumb && menuName) {
            breadcrumb.textContent = menuName;
        }
    };
}

// ---------------------------------------------------------------------------
// Landing page publik: menu mobile, modal detail produk, dan chatbot.
// ---------------------------------------------------------------------------

function initMobileMenu() {
    const button = document.getElementById('mobile-menu-btn');
    const menu = document.getElementById('mobile-menu');

    if (!button || !menu) return;

    button.addEventListener('click', () => {
        const terbuka = menu.classList.toggle('hidden') === false;
        button.setAttribute('aria-expanded', String(terbuka));
    });
}

function initModal() {
    const modal = document.getElementById('global-modal');
    const content = document.getElementById('modal-content');

    if (!modal || !content) return;

    const title = document.getElementById('modal-title');
    const meta = document.getElementById('modal-meta');
    const body = document.getElementById('modal-body');
    const image = document.getElementById('modal-image');

    function setOptionalText(element, value) {
        if (!element) return;

        element.textContent = value || '';
        element.classList.toggle('hidden', !value);
    }

    function openModal({ title: judul, meta: info, body: isi, image: gambar }) {
        if (title) title.textContent = judul || 'Detail';
        setOptionalText(meta, info);
        if (body) body.textContent = isi || '';

        if (image) {
            image.classList.toggle('hidden', !gambar);
            image.src = gambar || '';
            image.alt = gambar ? judul || '' : '';
        }

        modal.classList.remove('hidden');
        requestAnimationFrame(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        });
    }

    function closeModal() {
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 200);
    }

    document.addEventListener('click', (event) => {
        const trigger = event.target.closest('[data-modal-open]');

        if (trigger) {
            openModal({
                title: trigger.dataset.modalTitle,
                meta: trigger.dataset.modalMeta,
                body: trigger.dataset.modalBody,
                image: trigger.dataset.modalImage,
            });
            return;
        }

        if (event.target.closest('[data-modal-close]') || event.target === modal) {
            closeModal();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });

    window.openModal = (judul, isi) => openModal({ title: judul, body: isi });
    window.closeModal = closeModal;
}

function initChatbot() {
    const button = document.getElementById('chatbot-btn');
    const chatWindow = document.getElementById('chatbot-window');

    if (!button || !chatWindow) return;

    const form = document.getElementById('chat-form');
    const input = document.getElementById('chat-input');
    const messages = document.getElementById('chat-messages');

    function toggleChatbot() {
        const terbuka = chatWindow.classList.toggle('hidden') === false;
        button.setAttribute('aria-expanded', String(terbuka));

        if (terbuka && input) {
            input.focus();
        }
    }

    function appendMessage(text, dariPengguna) {
        if (!messages) return;

        const bubble = document.createElement('div');
        bubble.className = dariPengguna
            ? 'bg-brand-blue text-white p-2.5 rounded-xl max-w-[85%] ml-auto text-xs'
            : 'bg-slate-100 p-2.5 rounded-xl max-w-[85%] text-slate-700 text-xs';
        bubble.textContent = text;

        messages.appendChild(bubble);
        messages.scrollTop = messages.scrollHeight;
    }

    function balasan(pertanyaan) {
        const teks = pertanyaan.toLowerCase();

        if (teks.includes('produk') || teks.includes('karya')) {
            return "Produk unggulan kami dikelompokkan per jurusan. Silakan lihat bagian 'Semua Produk Unggulan' di halaman ini.";
        }

        if (teks.includes('mitra') || teks.includes('dudi') || teks.includes('industri')) {
            return 'Mitra DUDI kami tercantum pada bagian "Mitra DUDI — Kerjasama Industri".';
        }

        if (teks.includes('alamat') || teks.includes('lokasi') || teks.includes('peta')) {
            return 'Alamat sekolah dan peta lokasi tersedia di bagian footer halaman ini.';
        }

        return 'Terima kasih atas pertanyaan Anda. Layanan Informasi sekolah akan segera merespon.';
    }

    button.addEventListener('click', toggleChatbot);

    chatWindow.addEventListener('click', (event) => {
        if (event.target.closest('[data-chatbot-close]')) {
            toggleChatbot();
        }
    });

    if (form && input) {
        form.addEventListener('submit', (event) => {
            event.preventDefault();

            const text = input.value.trim();
            if (!text) return;

            appendMessage(text, true);
            input.value = '';

            setTimeout(() => appendMessage(balasan(text), false), 600);
        });
    }

    window.toggleChatbot = toggleChatbot;
}

initMobileMenu();
initModal();
initChatbot();
