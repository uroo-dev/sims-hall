<!-- SIDEBAR CONTAINER -->
<aside id="sidebar"
    class="fixed top-0 bottom-0 left-0 z-50 w-[270px] bg-white flex flex-col transition-transform duration-300 -translate-x-full lg:translate-x-0 border-r border-gray-100">

    <!-- BRAND / LOGO HEADER (Latar Belakang Putih) -->
    <div class="p-5 bg-white flex items-center gap-3">
        <img src="{{ asset('assets/logosmkk.png') }}" alt="Logo SMKN 2 Karanganyar" class="w-10 h-10 object-contain">
        <div>
            <h1 class="font-bold text-gray-900 leading-tight text-xs tracking-wider uppercase">SMK NEGERI 2</h1>
            <p class="font-semibold text-gray-500 text-[11px] tracking-tight uppercase">KARANGANYAR</p>
        </div>
    </div>

    <!-- MAIN BLUE CONTAINER -->
    <div class="bg-[#0073c6] flex-1 rounded-tr-[40px] flex flex-col overflow-hidden text-white pt-6 pb-6 px-4">

        <!-- MENU NAVIGATION SCROLLABLE AREA -->
        <div class="flex-1 overflow-y-auto sidebar-scroll pr-1 space-y-2">

            <!-- 1. DASHBOARD -->
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('dashboard') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                <i class="fa-solid fa-table-cells-large text-base w-5 text-center"></i>
                <span>Dashboard</span>
            </a>

            <!-- 2. FASILITAS -->
            <a href="{{ route('admin.fasilitas.index') }}"
                class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('admin.fasilitas.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                <i class="fa-solid fa-box text-base w-5 text-center"></i>
                <span>Fasilitas</span>
            </a>

            <!-- 3. PAKET PEMINJAMAN -->
            <a href="#"
                class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('admin.paket-peminjaman.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                <i class="fa-solid fa-boxes-packing text-base w-5 text-center"></i>
                <span>Paket Peminjaman</span>
            </a>

        </div>

        <!-- LOGOUT BUTTON CONTAINER -->
        <div class="pt-4 mt-2">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin keluar dari sistem admin?')"
                    class="w-full bg-white text-[#0073c6] hover:bg-gray-100 transition font-bold py-3 px-4 rounded-full text-sm shadow-sm flex items-center justify-center">
                    <span>Logout</span>
                </button>
            </form>
        </div>

    </div>
</aside>
