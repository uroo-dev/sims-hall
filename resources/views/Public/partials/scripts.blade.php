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
