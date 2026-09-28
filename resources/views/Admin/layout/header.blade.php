<!-- TOP NAVBAR / HEADER CONTAINER -->
<header
    class="sticky top-3 z-40 bg-white/95 backdrop-blur-md rounded-2xl px-5 py-3 shadow-sm border border-gray-100 flex items-center justify-between transition-all">
    
    <div class="flex items-center gap-3">
        <button onclick="toggleSidebar()"
            class="lg:hidden text-gray-600 hover:text-brand-600 focus:outline-none p-1">
            <i class="fa-solid fa-bars text-lg"></i>
        </button>
        <!-- BREADCRUMB -->
        <div class="text-xs md:text-sm font-semibold tracking-wide text-gray-700">
            <span class="text-gray-900 font-bold uppercase">ADMIN</span>
            <span class="mx-1 text-gray-400">&gt;</span>
            <span class="text-gray-600">@yield('title', 'Dashboard')</span>
        </div>
    </div>

    <!-- USER PROFILE RIGHT -->
    <div class="flex items-center gap-3">
        <button
            class="w-8 h-8 rounded-lg bg-blue-50 text-brand-600 flex items-center justify-center hover:bg-blue-100 transition">
            <i class="fa-solid fa-gear text-sm"></i>
        </button>

        <div class="relative group">
            <button
                class="flex items-center gap-2.5 bg-gray-50 border border-gray-200 rounded-full py-1 px-3 hover:bg-gray-100 transition">
                <div
                    class="w-6 h-6 rounded-full bg-blue-100 text-[#0073c6] flex items-center justify-center text-xs font-bold">
                    <i class="fa-regular fa-user"></i>
                </div>
                <div class="text-left text-xs leading-none">
                    <!-- Mengambil nama user yang login -->
                    <div class="font-bold text-gray-800">{{ Auth::user()->name ?? 'User' }}</div>
                    <!-- Mengambil role user yang login -->
                    <div class="text-[10px] text-gray-500 mt-0.5">{{ ucwords(str_replace('_', ' ', Auth::user()->role ?? 'User')) }}</div>
                </div>
                <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 ml-1"></i>
            </button>

            <!-- DROPDOWN MENU -->
            <div
                class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 hidden group-hover:block z-50 py-1">
                <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-50"><i
                        class="fa-regular fa-user mr-2"></i> Profil Saya</a>
                <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-50"><i
                        class="fa-solid fa-sliders mr-2"></i> Pengaturan</a>
                <hr class="my-1 border-gray-100">
                <!-- Logout di Dropdown: Mengirim form logout yang ada di sidebar secara otomatis -->
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();"
                    class="block px-4 py-2 text-xs text-red-600 hover:bg-red-50">
                    <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i> Keluar
                </a>
            </div>
        </div>
    </div>
</header>