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

        @php
            $currentUser = auth()->user();
            $isSuper = $currentUser?->isSuperAdmin() ?? false;
            $fiturName = $currentUser?->fitur?->nama_fitur;
            $userRole = $currentUser?->role;
        @endphp

        <!-- MENU NAVIGATION SCROLLABLE AREA -->
        <div class="flex-1 overflow-y-auto sidebar-scroll pr-1 space-y-6">

            <!-- DASHBOARD ITEM -->
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('dashboard') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                <i class="fa-solid fa-table-cells-large text-base"></i>
                <span>Dashboard</span>
            </a>

            @if ($isSuper || ($userRole === 'admin' && $fiturName === 'master'))
            <!-- CATEGORY: DATA MASTER SEKOLAH -->
            <div class="space-y-3">
                <div class="flex items-center justify-between text-[12px] font-medium text-blue-100/90 tracking-wide">
                    <span>Data Master Sekolah</span>
                    <span class="w-12 h-[1px] bg-white/30"></span>
                </div>
                <div class="space-y-2.5 pl-1">
                    <a href="#"
                        class="flex items-center gap-3 py-1.5 text-white hover:text-blue-100 font-medium text-sm transition">
                        <i class="fa-solid fa-graduation-cap w-5 text-center text-base"></i>
                        <span>Dashboard Sekolah</span>
                    </a>
                    <a href="#"
                        class="flex items-center gap-3 py-1.5 text-white hover:text-blue-100 font-medium text-sm transition">
                        <i class="fa-regular fa-comment-dots w-5 text-center text-base"></i>
                        <span>Data Sekolah</span>
                    </a>
                    <a href="#"
                        class="flex items-center gap-3 py-1.5 text-white hover:text-blue-100 font-medium text-sm transition">
                        <i class="fa-solid fa-users-gear w-5 text-center text-base"></i>
                        <span>Users</span>
                    </a>
                </div>
            </div>
            @endif

            @if ($isSuper || ($userRole === 'admin' && $fiturName === 'aula'))
            <!-- CATEGORY: PEMINJAMAN AULA -->
            <div class="space-y-3">
                <div class="flex items-center justify-between text-[12px] font-medium text-blue-100/90 tracking-wide">
                    <span>Peminjaman Aula</span>
                    <span class="w-12 h-[1px] bg-white/30"></span>
                </div>
                <div class="space-y-2.5 pl-1">
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center gap-3 py-1.5 text-white hover:text-blue-100 font-medium text-sm transition">
                        <i class="fa-solid fa-building-columns w-5 text-center text-base"></i>
                        <span>Dashboard Aula</span>
                    </a>
                    <a href="{{ route('admin.fasilitas.index') }}"
                        class="flex items-center gap-3 py-2 px-3 {{ request()->routeIs('admin.fasilitas.*') ? 'bg-white text-[#0073c6] font-bold rounded-xl shadow-sm' : 'text-white hover:text-blue-100 hover:bg-white/10 rounded-xl font-medium' }} text-sm transition">
                        <i class="fa-solid fa-box w-5 text-center text-base"></i>
                        <span>Fasilitas</span>
                    </a>
                    <a href="#"
                        class="flex items-center gap-3 py-1.5 text-white hover:text-blue-100 font-medium text-sm transition">
                        <i class="fa-solid fa-boxes-packing w-5 text-center text-base"></i>
                        <span>Paket Peminjaman</span>
                    </a>
                    <a href="#"
                        class="flex items-center gap-3 py-1.5 text-white hover:text-blue-100 font-medium text-sm transition">
                        <i class="fa-solid fa-cart-shopping w-5 text-center text-base"></i>
                        <span>Persetujuan 1</span>
                    </a>
                    <a href="#"
                        class="flex items-center gap-3 py-1.5 text-white hover:text-blue-100 font-medium text-sm transition">
                        <i class="fa-solid fa-chart-simple w-5 text-center text-base"></i>
                        <span>Laporan Operasional</span>
                    </a>
                </div>
            </div>
            @endif

            @if ($isSuper || $userRole === 'kepala_sekolah')
            <!-- CATEGORY: KEPALA SEKOLAH -->
            <div class="space-y-3">
                <div class="flex items-center justify-between text-[12px] font-medium text-blue-100/90 tracking-wide">
                    <span>Kepala Sekolah</span>
                    <span class="w-12 h-[1px] bg-white/30"></span>
                </div>
                <div class="space-y-2.5 pl-1">
                    <a href="#"
                        class="flex items-center gap-3 py-1.5 text-white hover:text-blue-100 font-medium text-sm transition">
                        <i class="fa-solid fa-user-tie w-5 text-center text-base"></i>
                        <span>Dashboard KS</span>
                    </a>
                    <a href="#"
                        class="flex items-center gap-3 py-1.5 text-white hover:text-blue-100 font-medium text-sm transition">
                        <i class="fa-solid fa-cart-shopping w-5 text-center text-base"></i>
                        <span>Persetujuan 2</span>
                    </a>
                    <a href="#"
                        class="flex items-center gap-3 py-1.5 text-white hover:text-blue-100 font-medium text-sm transition">
                        <i class="fa-solid fa-chart-simple w-5 text-center text-base"></i>
                        <span>Laporan Operasional</span>
                    </a>
                </div>
            </div>
            @endif

            @if ($isSuper || ($userRole === 'admin' && $fiturName === 'kesiswaan'))
            <!-- CATEGORY: KESISWAAN -->
            <div class="space-y-3">
                <div class="flex items-center justify-between text-[12px] font-medium text-blue-100/90 tracking-wide">
                    <span>Kesiswaan</span>
                    <span class="w-12 h-[1px] bg-white/30"></span>
                </div>
                <div class="space-y-2.5 pl-1">
                    <a href="#"
                        class="flex items-center gap-3 py-1.5 text-white hover:text-blue-100 font-medium text-sm transition">
                        <i class="fa-solid fa-users w-5 text-center text-base"></i>
                        <span>Dashboard K</span>
                    </a>
                    <a href="#"
                        class="flex items-center gap-3 py-1.5 text-white hover:text-blue-100 font-medium text-sm transition">
                        <i class="fa-solid fa-trophy w-5 text-center text-base"></i>
                        <span>Data Prestasi</span>
                    </a>
                    <a href="#"
                        class="flex items-center gap-3 py-1.5 text-white hover:text-blue-100 font-medium text-sm transition">
                        <i class="fa-solid fa-icons w-5 text-center text-base"></i>
                        <span>Data Ekstrakulikuler</span>
                    </a>
                    <a href="#"
                        class="flex items-center gap-3 py-1.5 text-white hover:text-blue-100 font-medium text-sm transition">
                        <i class="fa-solid fa-book-bookmark w-5 text-center text-base"></i>
                        <span>Data Tata Tertib</span>
                    </a>
                </div>
            </div>
            @endif

            @if ($isSuper || ($userRole === 'admin' && $fiturName === 'produk_unggulan'))
            <!-- CATEGORY: PRODUK UNGGULAN -->
            <div class="space-y-3">
                <div class="flex items-center justify-between text-[12px] font-medium text-blue-100/90 tracking-wide">
                    <span>Produk Unggulan</span>
                    <span class="w-12 h-[1px] bg-white/30"></span>
                </div>
                <div class="space-y-2.5 pl-1">
                    <a href="#"
                        class="flex items-center gap-3 py-1.5 text-white hover:text-blue-100 font-medium text-sm transition">
                        <i class="fa-solid fa-store w-5 text-center text-base"></i>
                        <span>Dashboard PU</span>
                    </a>
                    <a href="#"
                        class="flex items-center gap-3 py-1.5 text-white hover:text-blue-100 font-medium text-sm transition">
                        <i class="fa-solid fa-basket-shopping w-5 text-center text-base"></i>
                        <span>Data Produk</span>
                    </a>
                </div>
            </div>
            @endif

            @if ($isSuper || ($userRole === 'admin' && $fiturName === 'pklbkk'))
            <!-- CATEGORY: PKL & BKK -->
            <div class="space-y-3">
                <div class="flex items-center justify-between text-[12px] font-medium text-blue-100/90 tracking-wide">
                    <span>PKL & BKK</span>
                    <span class="w-12 h-[1px] bg-white/30"></span>
                </div>
                <div class="space-y-2.5 pl-1">
                    <a href="#"
                        class="flex items-center gap-3 py-1.5 text-white hover:text-blue-100 font-medium text-sm transition">
                        <i class="fa-solid fa-briefcase w-5 text-center text-base"></i>
                        <span>Dashboard PB</span>
                    </a>
                    <a href="#"
                        class="flex items-center gap-3 py-1.5 text-white hover:text-blue-100 font-medium text-sm transition">
                        <i class="fa-solid fa-bars-staggered w-5 text-center text-base"></i>
                        <span>Data Dudi</span>
                    </a>
                    <a href="#"
                        class="flex items-center gap-3 py-1.5 text-white hover:text-blue-100 font-medium text-sm transition">
                        <i class="fa-solid fa-laptop-code w-5 text-center text-base"></i>
                        <span>Lowongan Kerja</span>
                    </a>
                    <a href="#"
                        class="flex items-center gap-3 py-1.5 text-white hover:text-blue-100 font-medium text-sm transition">
                        <i class="fa-solid fa-address-card w-5 text-center text-base"></i>
                        <span>Data PKL</span>
                    </a>
                    <a href="#"
                        class="flex items-center gap-3 py-1.5 text-white hover:text-blue-100 font-medium text-sm transition">
                        <i class="fa-solid fa-comments w-5 text-center text-base"></i>
                        <span>Data Jurusan</span>
                    </a>
                </div>
            </div>
            @endif

            @if ($isSuper || ($userRole === 'admin' && in_array($fiturName, ['master', 'kesiswaan'], true)))
            <!-- CATEGORY: PPDB -->
            <div class="space-y-3">
                <div class="flex items-center justify-between text-[12px] font-medium text-blue-100/90 tracking-wide">
                    <span>PPDB</span>
                    <span class="w-12 h-[1px] bg-white/30"></span>
                </div>
                <div class="space-y-2.5 pl-1">
                    <a href="#"
                        class="flex items-center gap-3 py-1.5 text-white hover:text-blue-100 font-medium text-sm transition">
                        <i class="fa-solid fa-user-plus w-5 text-center text-base"></i>
                        <span>Dashboard PPDB</span>
                    </a>
                    <a href="#"
                        class="flex items-center gap-3 py-1.5 text-white hover:text-blue-100 font-medium text-sm transition">
                        <i class="fa-solid fa-file-lines w-5 text-center text-base"></i>
                        <span>Informasi & Persyaratan</span>
                    </a>
                </div>
            </div>
            @endif

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
