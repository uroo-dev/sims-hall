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

    <!-- GSAP & ScrollTrigger Animation Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>

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

    <!-- Floating Toast Alert Container (Public / Landing) -->
    <div id="unified-alert-floating-wrapper" class="fixed top-24 right-4 sm:right-8 z-[110] max-w-md w-[calc(100%-2rem)] sm:w-full pointer-events-none transition-all duration-300">
        <div class="pointer-events-auto">
            @include('partials.alerts')
        </div>
    </div>

    @yield('content')
    
    @include('Public.layout.footer')
   

    <!-- FLOATING CHATBOT WIDGET - Nanya AI -->
    {{-- Widget chatbot asli (backend Gemini + fallback database) menggantikan
         widget statis yang sebelumnya tertanam langsung di layout ini. Versi lama
         hanya mencocokkan keyword di JavaScript tanpa pernah menghubungi server. --}}
    @include('Public.partials.chatbot')

    <!-- GLOBAL MODAL DIALOG -->
    <div id="global-modal"
        class="fixed inset-0 !m-0 z-[100] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 md:p-7 shadow-2xl border border-slate-100 relative transform transition-all scale-95 opacity-0 duration-200"
            id="modal-content">
            <div class="flex items-center justify-between mb-4">
                <h3 id="modal-title" class="text-base md:text-lg font-extrabold text-slate-900 tracking-tight">Informasi</h3>
                <button onclick="closeModal()" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition cursor-pointer">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
            <p id="modal-body" class="text-slate-600 text-xs md:text-sm leading-relaxed mb-6">Memuat...</p>
            <div class="text-right">
                <button onclick="closeModal()"
                    class="px-5 py-2.5 rounded-xl bg-[#0060ac] hover:bg-[#004f8f] active:scale-95 text-white font-bold text-xs md:text-sm shadow-md shadow-blue-500/20 transition cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    @include('partials.modals')
    @stack('modals')

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

        // Global Modal Function Proxies
        function openModal(arg1, arg2) {
            if (window.openModal) {
                window.openModal.apply(window, arguments);
            }
        }

        function closeModal(modalId) {
            if (window.closeModal) {
                window.closeModal(modalId);
            }
        }

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

    @include('Public.partials.animations')
    @stack('scripts')
</body>

</html>
