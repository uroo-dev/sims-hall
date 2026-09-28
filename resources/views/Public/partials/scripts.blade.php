<script>
    // ---- Mobile Menu Toggle ----
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');

    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // ---- Global Modal Logic ----
    const globalModal = document.getElementById('global-modal');
    const modalContent = document.getElementById('modal-content');
    const modalTitle = document.getElementById('modal-title');
    const modalBody = document.getElementById('modal-body');

    function openModal(title, body) {
        modalTitle.innerText = title;
        modalBody.innerText = body;
        globalModal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeModal() {
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            globalModal.classList.add('hidden');
        }, 200);
    }

    // Tutup modal dengan klik area gelap atau tombol Escape
    if (globalModal) {
        globalModal.addEventListener('click', (e) => {
            if (e.target === globalModal) closeModal();
        });
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && globalModal && !globalModal.classList.contains('hidden')) {
            closeModal();
        }
    });

    // ---- Chatbot Toggle & Interactive Responses ----
    const chatbotWindow = document.getElementById('chatbot-window');
    const chatInput = document.getElementById('chat-input');
    const chatMessages = document.getElementById('chat-messages');

    function toggleChatbot() {
        chatbotWindow.classList.toggle('hidden');
    }

    function sendChatMessage() {
        const text = chatInput.value.trim();
        if (!text) return;

        const userBubble = document.createElement('div');
        userBubble.className = 'bg-brand-blue text-white p-2.5 rounded-xl max-w-[85%] ml-auto text-xs';
        userBubble.innerText = text;
        chatMessages.appendChild(userBubble);

        chatInput.value = '';
        chatMessages.scrollTop = chatMessages.scrollHeight;

        setTimeout(() => {
            const botBubble = document.createElement('div');
            botBubble.className = 'bg-slate-100 p-2.5 rounded-xl max-w-[85%] text-slate-700 text-xs';

            if (text.toLowerCase().includes('ppdb') || text.toLowerCase().includes('daftar')) {
                botBubble.innerText = "Informasi PPDB 2026 dapat dilihat pada bagian menu PPDB di atas atau langsung datang ke kampus SMKN 2 Karanganyar.";
            } else if (text.toLowerCase().includes('aula') || text.toLowerCase().includes('sewa')) {
                botBubble.innerText = "Kami memiliki 2 paket sewa aula: Paket Unggulan (12 Jam - Rp 6 Juta) dan Terjangkau (4 Jam - Rp 1.5 Juta).";
            } else {
                botBubble.innerText = "Terima kasih atas pertanyaan Anda. Layanan Informasi SMKN 2 Karanganyar akan segera merespon.";
            }
            chatMessages.appendChild(botBubble);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }, 600);
    }

    // ---- News Carousel (hanya ada di halaman landing) ----
    const prevNews = document.getElementById('prev-news');
    const nextNews = document.getElementById('next-news');
    const newsContainer = document.getElementById('news-container');

    if (prevNews && nextNews && newsContainer) {
        const flash = () => {
            newsContainer.classList.add('opacity-50');
            setTimeout(() => newsContainer.classList.remove('opacity-50'), 300);
        };
        prevNews.addEventListener('click', flash);
        nextNews.addEventListener('click', flash);
    }
</script>

@stack('scripts')
