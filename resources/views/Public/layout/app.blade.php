<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SMK Negeri 2 Karanganyar - Sekolah Pusat Keunggulan')</title>

    <!-- Meta SEO Dasar -->
    <meta name="description" content="@yield('meta_description', 'Official Website SMK Negeri 2 Karanganyar - Sekolah Pusat Keunggulan. Portal informasi resmi, PPDB, Teaching Factory, PKL, Career Center (BKK), dan Layanan Sewa Aula.')">
    <meta name="keywords" content="@yield('meta_keywords', 'SMK Negeri 2 Karanganyar, SMKN 2 Kra, Skandakra, PPDB Karanganyar, PKL SMK, BKK Karanganyar, Teaching Factory, Sewa Aula Karanganyar')">
    <meta name="author" content="SMK Negeri 2 Karanganyar">
    <meta name="robots" content="@yield('meta_robots', 'index, follow')">

    <!-- Canonical URL -->
    <link rel="canonical" href="@yield('canonical_url', url()->current())">

    <!-- Open Graph / Facebook / WhatsApp -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="@yield('canonical_url', url()->current())">
    <meta property="og:title" content="@yield('og_title', View::hasSection('title') ? View::getSection('title') : 'SMK Negeri 2 Karanganyar - Sekolah Pusat Keunggulan')">
    <meta property="og:description" content="@yield('meta_description', 'Official Website SMK Negeri 2 Karanganyar - Sekolah Pusat Keunggulan. Portal informasi resmi, PPDB, Teaching Factory, PKL, Career Center (BKK), dan Layanan Sewa Aula.')">
    <meta property="og:image" content="@yield('og_image', asset('assets/logosmkk.png'))">
    <meta property="og:site_name" content="SMK Negeri 2 Karanganyar">
    <meta property="og:locale" content="id_ID">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="@yield('twitter_card', 'summary_large_image')">
    <meta name="twitter:url" content="@yield('canonical_url', url()->current())">
    <meta name="twitter:title" content="@yield('og_title', View::hasSection('title') ? View::getSection('title') : 'SMK Negeri 2 Karanganyar - Sekolah Pusat Keunggulan')">
    <meta name="twitter:description" content="@yield('meta_description', 'Official Website SMK Negeri 2 Karanganyar - Sekolah Pusat Keunggulan. Portal informasi resmi, PPDB, Teaching Factory, PKL, Career Center (BKK), dan Layanan Sewa Aula.')">
    <meta name="twitter:image" content="@yield('og_image', asset('assets/logosmkk.png'))">

    <!-- Favicon & Icons -->
    <link rel="icon" type="image/png" href="{{ asset('assets/logosmkk.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/logosmkk.png') }}">

    <!-- Schema.org JSON-LD Structured Data for Google Search -->
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@type": "EducationalOrganization",
        "name": "SMK Negeri 2 Karanganyar",
        "alternateName": ["SMKN 2 Karanganyar", "Skandakra"],
        "url": "{{ url('/') }}",
        "logo": "{{ asset('assets/logosmkk.png') }}",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "Karanganyar",
            "addressRegion": "Jawa Tengah",
            "addressCountry": "ID"
        }
    }
    </script>
    @yield('structured_data')

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome Icons CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts Inter & Plus Jakarta Sans -->
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            blue: '#0066C4',
                            darkBlue: '#0052A3',
                            lightBlue: '#E6F0FA',
                            red: '#FF4D4D',
                            yellow: '#FFC107',
                            green: '#28A745',
                            accent: '#0284C7'
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        /* Custom decorative dot pattern background */
        .dot-pattern {
            background-image: radial-gradient(#0066C4 1.5px, transparent 1.5px);
            background-size: 12px 12px;
        }

        /* Smooth shadow presets */
        .card-shadow {
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.08);
        }

        .floating-button-shadow {
            box-shadow: 0 8px 25px rgba(0, 102, 196, 0.3);
        }

        /* Dropdown visibility transitions */
        .dropdown-menu {
            transition: all 0.2s ease-in-out;
        }
    </style>
    @stack('styles')
</head>

<body
    class="bg-[#F8FAFC] text-slate-800 font-sans antialiased overflow-x-hidden selection:bg-brand-blue selection:text-white">

    <!-- HEADER -->
    @include('Public.layout.header')

    @yield('content')
    
    @include('Public.layout.footer')
   

    <!-- FLOATING CHATBOT WIDGET - Nanya AI -->
    {{-- Widget chatbot asli (backend Gemini + fallback database) menggantikan
         widget statis yang sebelumnya tertanam langsung di layout ini. Versi lama
         hanya mencocokkan keyword di JavaScript tanpa pernah menghubungi server. --}}
    @include('Public.partials.chatbot')

    <!-- GLOBAL MODAL DIALOG -->
    <div id="global-modal"
        class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl relative transform transition-all scale-95 opacity-0"
            id="modal-content">
            <div class="flex items-center justify-between mb-4">
                <h3 id="modal-title" class="text-lg font-bold text-slate-900">Modal Title</h3>
                <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 text-xl"><i
                        class="fa-solid fa-xmark"></i></button>
            </div>
            <p id="modal-body" class="text-slate-600 text-sm leading-relaxed mb-6">Modal body text...</p>
            <div class="text-right">
                <button onclick="closeModal()"
                    class="bg-brand-blue text-white font-semibold text-xs px-5 py-2.5 rounded-lg hover:bg-brand-darkBlue">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT INTERACTIVE LOGIC -->
    <script>
        // ============================================================
        // DROPDOWN NAVBAR CLICK LOGIC (PERBAIKAN)
        // ============================================================
        document.addEventListener('DOMContentLoaded', function () {
            const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

            dropdownToggles.forEach(toggle => {
                toggle.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation(); // Mencegah klik menyebar ke document

                    const menu = this.nextElementSibling;
                    const isHidden = menu.classList.contains('hidden');

                    // Tutup semua dropdown lain
                    document.querySelectorAll('.dropdown-menu').forEach(otherMenu => {
                        otherMenu.classList.add('hidden');
                    });

                    // Toggle dropdown yang diklik
                    if (isHidden) {
                        menu.classList.remove('hidden');
                        // Rotasi panah ke atas
                        this.querySelector('i').classList.add('rotate-180');
                    } else {
                        menu.classList.add('hidden');
                        // Rotasi panah ke bawah
                        this.querySelector('i').classList.remove('rotate-180');
                    }
                });
            });

            // Tutup dropdown jika klik di luar area
            document.addEventListener('click', function (e) {
                if (!e.target.closest('.dropdown')) {
                    document.querySelectorAll('.dropdown-menu').forEach(menu => {
                        menu.classList.add('hidden');
                    });
                    document.querySelectorAll('.dropdown-toggle i').forEach(icon => {
                        icon.classList.remove('rotate-180');
                    });
                }
            });
        });

        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Global Modal Logic
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

        // Tutup modal dengan klik area gelap atau tombol Escape.
        // Diambil dari Public/partials/scripts.blade.php versi lama.
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

        // Chatbot: TIDAK handled di layout ini. Widget Nanya AI berdiri sendiri
        // di resources/views/components/chatbot.blade.php (backend Laravel +
        // Gemini). Fungsi toggleChatbot()/sendChatMessage() versi lama dihapus
        // karena hanya mencocokkan keyword tanpa menyentuh server.

        // News Carousel Interactions
        const prevNews = document.getElementById('prev-news');
        const nextNews = document.getElementById('next-news');
        const newsContainer = document.getElementById('news-container');

        if (prevNews && nextNews && newsContainer) {
            prevNews.addEventListener('click', () => {
                newsContainer.classList.add('opacity-50');
                setTimeout(() => newsContainer.classList.remove('opacity-50'), 300);
            });

            nextNews.addEventListener('click', () => {
                newsContainer.classList.add('opacity-50');
                setTimeout(() => newsContainer.classList.remove('opacity-50'), 300);
            });
        }
    </script>

    {{-- Layout ini sudah punya sendiri seluruh logika yang biasanya ada di
         Public/partials/scripts.blade.php (mobile menu, global modal, news
         carousel). Partial itu sengaja TIDAK di-include: kedua file
         mendeklarasikan const global dengan nama sama (globalModal, modalContent,
         modalTitle, modalBody, prevNews, nextNews, newsContainer), sehingga
         keduanya digabung akan melempar SyntaxError dan mematikan seluruh JS. --}}
    @stack('scripts')
</body>

</html>
