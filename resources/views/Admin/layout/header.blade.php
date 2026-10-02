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
            <span class="text-gray-900 font-bold uppercase">{{ (auth()->user() && auth()->user()->role === 'pelanggan') || request()->routeIs('customer.*') ? 'PEMINJAMAN' : (auth()->user() ? strtoupper(str_replace('_', ' ', auth()->user()->role)) : 'ADMIN') }}</span>

            <span class="mx-1 text-gray-400">&gt;</span>
            <span class="text-gray-600">@yield('title', 'Dashboard')</span>
        </div>
    </div>

    <!-- USER PROFILE RIGHT -->
    <div class="flex items-center gap-3">
        @hasSection('role-switcher')
            @yield('role-switcher')
        @endif

        <a href="{{ (auth()->user() && auth()->user()->role === 'pelanggan') ? route('customer.profil') : '#' }}"
            class="w-8 h-8 rounded-lg bg-blue-50 text-brand-600 flex items-center justify-center hover:bg-blue-100 transition"
            title="Pengaturan">
            <i class="fa-solid fa-gear text-sm"></i>
        </a>

        <div class="relative group">
            <button
                class="flex items-center gap-2.5 bg-gray-50 border border-gray-200 rounded-full py-1 px-3 hover:bg-gray-100 transition">
                <div class="w-6 h-6 rounded-full overflow-hidden bg-blue-100 border border-gray-200 flex items-center justify-center flex-shrink-0">
                    <img src="{{ asset('assets/default-avatar.png') }}"
                        alt="{{ auth()->user()->name ?? 'User' }}"
                        class="w-full h-full object-cover"
                        onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'User') }}&background=0073c6&color=fff&size=64';">
                </div>
                <div class="text-left text-xs leading-none">
                    <div class="font-bold text-gray-800">{{ auth()->user()->name ?? 'Admin' }}</div>
                    <div class="text-[10px] text-gray-500 mt-0.5">{{ (auth()->user() && auth()->user()->role === 'pelanggan') ? 'Peminjaman' : (auth()->user() ? ucwords(str_replace('_', ' ', auth()->user()->role)) : 'admin') }}</div>
                </div>
                <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 ml-1"></i>
            </button>

            <!-- DROPDOWN MENU -->
            <div
                class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 hidden group-hover:block z-50 py-1">
                <a href="{{ (auth()->user() && auth()->user()->role === 'pelanggan') ? route('customer.profil') : '#' }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-50">
                    <i class="fa-regular fa-user mr-2"></i> Profil Saya
                </a>
                <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-50">
                    <i class="fa-solid fa-sliders mr-2"></i> Pengaturan
                </a>
                <hr class="my-1 border-gray-100">
                <!-- Logout di Dropdown: Memicu Modal Konfirmasi Logout -->
                <button type="button" onclick="openLogoutModal()"
                    class="w-full text-left px-4 py-2 text-xs text-red-600 hover:bg-red-50 flex items-center transition cursor-pointer">
                    <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i> Keluar
                </button>
            </div>
        </div>
    </div>
</header>