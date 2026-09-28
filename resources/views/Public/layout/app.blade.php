<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMK Negeri 2 Karanganyar - Sekolah Pusat Keunggulan</title>
    <link rel="icon" type="image/x-icon" href="assets/logosmkk.png">

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
</head>

<body
    class="bg-[#F8FAFC] text-slate-800 font-sans antialiased overflow-x-hidden selection:bg-brand-blue selection:text-white">

    <!-- HEADER -->
    @include('Public.layout.header')

    @yield('content')
    
    @include('Public.layout.footer')
   

    <!-- FLOATING CHATBOT WIDGET -->
    <div class="fixed bottom-6 right-6 z-50">
        <button id="chatbot-btn" onclick="toggleChatbot()"
            class="bg-white border-2 border-brand-blue text-brand-blue hover:bg-brand-blue hover:text-white transition-all duration-300 rounded-full px-4 py-2.5 shadow-2xl flex items-center gap-2 group floating-button-shadow">
            <div
                class="w-8 h-8 rounded-full bg-blue-100 group-hover:bg-white text-brand-blue flex items-center justify-center">
                <i class="fa-solid fa-robot text-base"></i>
            </div>
            <span class="font-bold text-sm">ChatBot</span>
        </button>

        <div id="chatbot-window"
            class="hidden absolute bottom-16 right-0 w-80 bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden text-slate-800">
            <div class="bg-brand-blue text-white p-4 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-robot"></i>
                    <span class="font-bold text-sm">Asisten Virtual SMKN 2</span>
                </div>
                <button onclick="toggleChatbot()" class="text-white hover:text-slate-200"><i
                        class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="p-4 h-60 overflow-y-auto space-y-3 text-xs" id="chat-messages">
                <div class="bg-slate-100 p-2.5 rounded-xl max-w-[85%] text-slate-700">
                    Halo! Ada yang bisa saya bantu mengenai informasi SMKN 2 Karanganyar atau PPDB 2026?
                </div>
            </div>
            <div class="p-2 border-t border-slate-100 flex gap-2">
                <input id="chat-input" type="text" placeholder="Tulis pertanyaan..."
                    class="flex-1 text-xs border rounded-lg px-3 py-2 focus:outline-none focus:border-brand-blue">
                <button onclick="sendChatMessage()"
                    class="bg-brand-blue text-white px-3 py-2 rounded-lg text-xs font-bold hover:bg-brand-darkBlue">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>

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

        // Chatbot Toggle & Interactive Responses
        const chatbotWindow = document.getElementById('chatbot-window');
        const chatInput = document.getElementById('chat-input');
        const chatMessages = document.getElementById('chat-messages');

        function toggleChatbot() {
            chatbotWindow.classList.toggle('hidden');
        }

        function sendChatMessage() {
            const text = chatInput.value.trim();
            if (!text) return;

            // Append User Message
            const userBubble = document.createElement('div');
            userBubble.className = 'bg-brand-blue text-white p-2.5 rounded-xl max-w-[85%] ml-auto text-xs';
            userBubble.innerText = text;
            chatMessages.appendChild(userBubble);

            chatInput.value = '';
            chatMessages.scrollTop = chatMessages.scrollHeight;

            // Generate Bot Answer
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
</body>

</html>
