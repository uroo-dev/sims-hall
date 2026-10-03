<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN - Dashboard SMKN 2 Karanganyar</title>
    <link rel="icon" type="image/x-icon" href="assets/logosmkk.png">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0f7ff',
                            100: '#e0effe',
                            500: '#0284c7',
                            600: '#0060ac', // primary dark blue from screenshot
                            700: '#004f8f',
                            800: '#003e73',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }

        /* Custom smooth scrollbar for sidebar */
        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-scroll::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.4);
        }

        /* Custom card shadow matching reference */
        .card-shadow {
            box-shadow: 0 4px 15px -2px rgba(0, 0, 0, 0.05), 0 2px 6px -2px rgba(0, 0, 0, 0.03);
        }
    </style>

    @stack('styles')
</head>

<body class="text-gray-800 antialiased min-h-screen bg-[#f1f3f6]">

    <div class="flex min-h-screen relative">

        <!-- MOBILE BACKDROP -->
        <div id="sidebarBackdrop" onclick="toggleSidebar()"
            class="fixed inset-0 bg-black/40 z-40 hidden lg:hidden backdrop-blur-sm transition-opacity"></div>

        <!-- SIDEBAR (menu role-aware dari config/menu.php) -->
        @include('Admin.layout.sidebar')

        <!-- MAIN CONTENT AREA -->
        <main class="flex-1 lg:ml-[270px] p-3 md:p-6 space-y-5 max-w-[1600px]">
            @include('Admin.layout.header')
            
            @include('partials.alerts')

            @yield('content')

            @include('Admin.layout.footer')
        </main>
    </div>

    <!-- MODAL STACK -->
    @stack('modals')

    <!-- MODAL: KONFIRMASI LOGOUT -->
    <div id="modalLogout" class="fixed inset-0 !m-0 z-[100] hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity" role="dialog" aria-modal="true">
        <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl border border-slate-100 overflow-hidden transform transition-all scale-95 duration-200" id="modalLogoutBox">
            <div class="p-6 text-center space-y-4">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-red-50 text-red-600 border border-red-100/80 flex items-center justify-center text-2xl shadow-xs">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-lg tracking-tight">Konfirmasi Keluar</h3>
                    <p class="text-xs text-slate-500 mt-1">
                        Apakah Anda yakin ingin keluar dari portal admin? Sesi aktif Anda akan segera diakhiri.
                    </p>
                </div>

                <form id="logoutFormModal" action="{{ route('logout') }}" method="POST" class="pt-2 flex items-center justify-center gap-3">
                    @csrf
                    <button type="button" onclick="closeLogoutModal()"
                        class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs md:text-sm font-semibold transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 active:scale-95 text-white text-xs md:text-sm font-bold shadow-md shadow-red-500/20 transition flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                        <span>Ya, Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    @include('partials.modals')

    @vite(['resources/js/app.js'])

    <script>
        // Sidebar drawer toggle for mobile devices
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');

            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }

        // Modal Logout Handlers
        function openLogoutModal() {
            window.openModal('modalLogout');
        }

        function closeLogoutModal() {
            window.closeModal('modalLogout');
        }
    </script>

    @stack('scripts')
</body>

</html>