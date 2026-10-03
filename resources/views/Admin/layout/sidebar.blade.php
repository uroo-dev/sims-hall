{{--
SIDEBAR CONTAINER

Tampilan mengikuti desain brand (biru #0073c6, rounded-tr-[40px], pill rounded-full).
Navigasi diatur menggunakan Blade if-elseif berdasarkan role user autentikasi:
- Pelanggan (customer panel)
- Kepala Sekolah
- BKK & PKL
- Admin Produk Unggulan
- Admin PPDB
- Admin Kesiswaan
- Admin Aula, Admin, Super Admin (panel utama + section per-modul)
--}}
<aside id="sidebar"
    class="fixed top-0 bottom-0 left-0 z-50 w-[270px] bg-white flex flex-col transition-transform duration-300 -translate-x-full lg:translate-x-0 border-r border-gray-100">

    @php
        $userRole = auth()->user()?->role;
    @endphp

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

            @if(($userRole === 'pelanggan') || request()->routeIs('customer.*'))
                {{-- =================== PANEL: PELANGGAN =================== --}}
                <a href="{{ route('customer.dashboard') }}"
                    class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('customer.dashboard') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                    <i class="fa-solid fa-table-cells-large text-base w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('customer.paket') }}"
                    class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('customer.paket') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                    <i class="fa-solid fa-cube text-base w-5 text-center"></i>
                    <span>Paket Peminjaman</span>
                </a>

                <a href="{{ route('customer.peminjaman.create') }}"
                    class="flex items-center gap-3 px-5 py-3.5 {{ request()->is('customer/peminjaman*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                    <i class="fa-regular fa-comment-dots text-base w-5 text-center"></i>
                    <span>Peminjaman</span>
                </a>

                <a href="{{ route('customer.cek-peminjaman') }}"
                    class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('customer.cek-peminjaman') || request()->routeIs('customer.riwayat') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                    <i class="fa-solid fa-desktop text-base w-5 text-center"></i>
                    <span>Cek Peminjaman</span>
                </a>

                <a href="{{ route('customer.profil') }}"
                    class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('customer.profil') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                    <i class="fa-solid fa-user-group text-base w-5 text-center"></i>
                    <span>Profil</span>
                </a>

            @elseif($userRole === 'kepala_sekolah')
                {{-- =================== PANEL: KEPALA SEKOLAH =================== --}}
                <a href="{{ route('kepala-sekolah.dashboard') }}"
                    class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('kepala-sekolah.dashboard') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                    <i class="fa-solid fa-table-cells-large text-base w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>

                @php $pendingFinalCount = \App\Models\Peminjaman::where('status', 'approved_1')->count(); @endphp
                <a href="{{ route('kepala-sekolah.peminjaman.index') }}"
                    class="flex items-center justify-between px-5 py-3.5 {{ request()->routeIs('kepala-sekolah.peminjaman.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-clipboard-list text-base w-5 text-center"></i>
                        <span>Daftar Peminjaman</span>
                    </div>
                    @if ($pendingFinalCount > 0)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-amber-400 text-slate-900">
                            {{ $pendingFinalCount }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('kepala-sekolah.laporan.index') }}"
                    class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('kepala-sekolah.laporan.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                    <i class="fa-solid fa-file-invoice-dollar text-base w-5 text-center"></i>
                    <span>Laporan Pemasukan</span>
                </a>

            @elseif($userRole === 'bkk' || $userRole === 'admin_pklbkk')
                {{-- =================== PANEL: BKK & PKL =================== --}}
                @include('Admin.layout.partials.menu-pkl-bkk')

            @elseif(in_array($userRole, ['admin_produk', 'admin_produk_unggulan']))
                {{-- =================== PANEL: PRODUK UNGGULAN =================== --}}
                <a href="{{ route('produk-unggulan.index') }}"
                    class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('produk-unggulan.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                    <i class="fa-solid fa-store text-base w-5 text-center"></i>
                    <span>Dashboard Produk</span>
                </a>

                <a href="{{ route('produk.index') }}"
                    class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('produk.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                    <i class="fa-solid fa-basket-shopping text-base w-5 text-center"></i>
                    <span>Data Produk</span>
                </a>

            @elseif($userRole === 'admin_ppdb')
                {{-- =================== PANEL: PPDB =================== --}}
                <a href="{{ route('index.dashboard.ppdb') }}"
                    class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('index.dashboard.ppdb') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                    <i class="fa-solid fa-table-cells-large text-base w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('index.informasi.ppdb') }}"
                    class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('index.informasi.ppdb') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                    <i class="fa-solid fa-file-lines text-base w-5 text-center"></i>
                    <span>Informasi & Persyaratan</span>
                </a>

            @elseif($userRole === 'admin_kesiswaan')
                {{-- =================== PANEL: KESISWAAN =================== --}}
                <a href="{{ route('admin.kesiswaan.index') }}"
                    class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('admin.kesiswaan.index') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                    <i class="fa-solid fa-users text-base w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.kesiswaan.ekstrakurikuler.index') }}"
                    class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('admin.kesiswaan.ekstrakurikuler.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                    <i class="fa-solid fa-icons text-base w-5 text-center"></i>
                    <span>Data Ekstrakulikuler</span>
                </a>

                <a href="{{ route('admin.kesiswaan.tata-tertib.index') }}"
                    class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('admin.kesiswaan.tata-tertib.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                    <i class="fa-solid fa-book-bookmark text-base w-5 text-center"></i>
                    <span>Data Tata Tertib</span>
                </a>

            @elseif(in_array($userRole, ['super_admin', 'super_duper_admin']))
                {{-- =================== PANEL: SUPER ADMIN (ACCORDION DROPDOWN) =================== --}}
                
                <!-- DASHBOARD UTAMA -->
                <a href="{{ route('admin.peminjaman.dashboard') }}"
                    class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('admin.peminjaman.dashboard') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                    <i class="fa-solid fa-table-cells-large text-base w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>

                <!-- 1. SUBMENU: PEMINJAMAN AULA -->
                @php
                    $isAulaActive = request()->routeIs('admin.aula.*') || request()->routeIs('admin.payment-configuration.*') || request()->routeIs('admin.fasilitas.*') || request()->routeIs('admin.paket.*') || (request()->routeIs('admin.peminjaman.*') && !request()->routeIs('admin.peminjaman.dashboard')) || request()->routeIs('admin.laporan.*');
                @endphp
                <div class="space-y-1">
                    <button type="button"
                        onclick="toggleSidebarDropdown('submenu-aula')"
                        class="w-full flex items-center justify-between px-5 py-3.5 rounded-full font-bold text-sm transition transform active:scale-95 cursor-pointer {{ $isAulaActive ? 'bg-white/15 text-white shadow-xs' : 'text-white hover:bg-white/10' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-hotel text-base w-5 text-center"></i>
                            <span>Peminjaman Aula</span>
                        </div>
                        <i id="chevron-submenu-aula" class="fa-solid fa-chevron-down text-xs transition-transform duration-200 {{ $isAulaActive ? 'rotate-180' : '' }}"></i>
                    </button>
                    <div id="submenu-aula" class="{{ $isAulaActive ? '' : 'hidden' }} pl-4 pr-1 py-1 space-y-1">
                        <a href="{{ route('admin.aula.index') }}"
                            class="flex items-center gap-3 px-4 py-2.5 rounded-full text-xs font-semibold transition transform active:scale-95 {{ request()->routeIs('admin.aula.*') ? 'bg-white text-[#0073c6] font-bold shadow-sm' : 'text-blue-100 hover:text-white hover:bg-white/10' }}">
                            <i class="fa-solid fa-hotel text-xs w-4 text-center"></i>
                            <span>Konfigurasi Aula</span>
                        </a>
                        <a href="{{ route('admin.payment-configuration.index') }}"
                            class="flex items-center gap-3 px-4 py-2.5 rounded-full text-xs font-semibold transition transform active:scale-95 {{ request()->routeIs('admin.payment-configuration.*') ? 'bg-white text-[#0073c6] font-bold shadow-sm' : 'text-blue-100 hover:text-white hover:bg-white/10' }}">
                            <i class="fa-solid fa-sliders text-xs w-4 text-center"></i>
                            <span>Konfigurasi Peminjaman</span>
                        </a>
                        <a href="{{ route('admin.fasilitas.index') }}"
                            class="flex items-center gap-3 px-4 py-2.5 rounded-full text-xs font-semibold transition transform active:scale-95 {{ request()->routeIs('admin.fasilitas.*') ? 'bg-white text-[#0073c6] font-bold shadow-sm' : 'text-blue-100 hover:text-white hover:bg-white/10' }}">
                            <i class="fa-solid fa-box text-xs w-4 text-center"></i>
                            <span>Fasilitas</span>
                        </a>
                        <a href="{{ route('admin.paket.index') }}"
                            class="flex items-center gap-3 px-4 py-2.5 rounded-full text-xs font-semibold transition transform active:scale-95 {{ request()->routeIs('admin.paket.*') ? 'bg-white text-[#0073c6] font-bold shadow-sm' : 'text-blue-100 hover:text-white hover:bg-white/10' }}">
                            <i class="fa-solid fa-boxes-packing text-xs w-4 text-center"></i>
                            <span>Paket Peminjaman</span>
                        </a>
                        <a href="{{ route('admin.peminjaman.index') }}"
                            class="flex items-center gap-3 px-4 py-2.5 rounded-full text-xs font-semibold transition transform active:scale-95 {{ (request()->routeIs('admin.peminjaman.*') && !request()->routeIs('admin.peminjaman.dashboard')) ? 'bg-white text-[#0073c6] font-bold shadow-sm' : 'text-blue-100 hover:text-white hover:bg-white/10' }}">
                            <i class="fa-solid fa-clipboard-list text-xs w-4 text-center"></i>
                            <span>Daftar Peminjaman</span>
                        </a>
                        <a href="{{ route('admin.laporan.index') }}"
                            class="flex items-center gap-3 px-4 py-2.5 rounded-full text-xs font-semibold transition transform active:scale-95 {{ request()->routeIs('admin.laporan.*') ? 'bg-white text-[#0073c6] font-bold shadow-sm' : 'text-blue-100 hover:text-white hover:bg-white/10' }}">
                            <i class="fa-solid fa-file-invoice-dollar text-xs w-4 text-center"></i>
                            <span>Laporan Pemasukan</span>
                        </a>
                    </div>
                </div>

                <!-- 2. SUBMENU: ARTIKEL SEKOLAH -->
                @php
                    $isArtikelActive = request()->routeIs('admin.kategori-artikel.*') || request()->routeIs('admin.artikel.*');
                @endphp
                <div class="space-y-1">
                    <button type="button"
                        onclick="toggleSidebarDropdown('submenu-artikel')"
                        class="w-full flex items-center justify-between px-5 py-3.5 rounded-full font-bold text-sm transition transform active:scale-95 cursor-pointer {{ $isArtikelActive ? 'bg-white/15 text-white shadow-xs' : 'text-white hover:bg-white/10' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-newspaper text-base w-5 text-center"></i>
                            <span>Artikel Sekolah</span>
                        </div>
                        <i id="chevron-submenu-artikel" class="fa-solid fa-chevron-down text-xs transition-transform duration-200 {{ $isArtikelActive ? 'rotate-180' : '' }}"></i>
                    </button>
                    <div id="submenu-artikel" class="{{ $isArtikelActive ? '' : 'hidden' }} pl-4 pr-1 py-1 space-y-1">
                        <a href="{{ route('admin.kategori-artikel.index') }}"
                            class="flex items-center gap-3 px-4 py-2.5 rounded-full text-xs font-semibold transition transform active:scale-95 {{ request()->routeIs('admin.kategori-artikel.*') ? 'bg-white text-[#0073c6] font-bold shadow-sm' : 'text-blue-100 hover:text-white hover:bg-white/10' }}">
                            <i class="fa-solid fa-tags text-xs w-4 text-center"></i>
                            <span>Kategori Artikel</span>
                        </a>
                        <a href="{{ route('admin.artikel.index') }}"
                            class="flex items-center gap-3 px-4 py-2.5 rounded-full text-xs font-semibold transition transform active:scale-95 {{ request()->routeIs('admin.artikel.*') ? 'bg-white text-[#0073c6] font-bold shadow-sm' : 'text-blue-100 hover:text-white hover:bg-white/10' }}">
                            <i class="fa-solid fa-newspaper text-xs w-4 text-center"></i>
                            <span>Daftar Artikel</span>
                        </a>
                    </div>
                </div>

                <!-- 3. SUBMENU: DATA MASTER SEKOLAH -->
                @php
                    $isMasterActive = request()->routeIs('datamaster.*');
                @endphp
                <div class="space-y-1">
                    <button type="button"
                        onclick="toggleSidebarDropdown('submenu-master')"
                        class="w-full flex items-center justify-between px-5 py-3.5 rounded-full font-bold text-sm transition transform active:scale-95 cursor-pointer {{ $isMasterActive ? 'bg-white/15 text-white shadow-xs' : 'text-white hover:bg-white/10' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-graduation-cap text-base w-5 text-center"></i>
                            <span>Data Master Sekolah</span>
                        </div>
                        <i id="chevron-submenu-master" class="fa-solid fa-chevron-down text-xs transition-transform duration-200 {{ $isMasterActive ? 'rotate-180' : '' }}"></i>
                    </button>
                    <div id="submenu-master" class="{{ $isMasterActive ? '' : 'hidden' }} pl-4 pr-1 py-1 space-y-1">
                        <a href="{{ route('datamaster.index') }}"
                            class="flex items-center gap-3 px-4 py-2.5 rounded-full text-xs font-semibold transition transform active:scale-95 {{ request()->routeIs('datamaster.index') ? 'bg-white text-[#0073c6] font-bold shadow-sm' : 'text-blue-100 hover:text-white hover:bg-white/10' }}">
                            <i class="fa-solid fa-graduation-cap text-xs w-4 text-center"></i>
                            <span>Dashboard Master</span>
                        </a>
                        <a href="{{ route('datamaster.sekolah.edit') }}"
                            class="flex items-center gap-3 px-4 py-2.5 rounded-full text-xs font-semibold transition transform active:scale-95 {{ request()->routeIs('datamaster.sekolah.*') ? 'bg-white text-[#0073c6] font-bold shadow-sm' : 'text-blue-100 hover:text-white hover:bg-white/10' }}">
                            <i class="fa-solid fa-school text-xs w-4 text-center"></i>
                            <span>Data Sekolah</span>
                        </a>
                        <a href="{{ route('datamaster.users') }}"
                            class="flex items-center gap-3 px-4 py-2.5 rounded-full text-xs font-semibold transition transform active:scale-95 {{ request()->routeIs('datamaster.users*') ? 'bg-white text-[#0073c6] font-bold shadow-sm' : 'text-blue-100 hover:text-white hover:bg-white/10' }}">
                            <i class="fa-solid fa-users-gear text-xs w-4 text-center"></i>
                            <span>Data Users</span>
                        </a>
                        <a href="{{ route('datamaster.guru.index') }}"
                            class="flex items-center gap-3 px-4 py-2.5 rounded-full text-xs font-semibold transition transform active:scale-95 {{ request()->routeIs('datamaster.guru.*') ? 'bg-white text-[#0073c6] font-bold shadow-sm' : 'text-blue-100 hover:text-white hover:bg-white/10' }}">
                            <i class="fa-solid fa-chalkboard-user text-xs w-4 text-center"></i>
                            <span>Data Guru</span>
                        </a>
                        <a href="{{ route('datamaster.siswa.index') }}"
                            class="flex items-center gap-3 px-4 py-2.5 rounded-full text-xs font-semibold transition transform active:scale-95 {{ request()->routeIs('datamaster.siswa.*') ? 'bg-white text-[#0073c6] font-bold shadow-sm' : 'text-blue-100 hover:text-white hover:bg-white/10' }}">
                            <i class="fa-solid fa-user-graduate text-xs w-4 text-center"></i>
                            <span>Data Siswa</span>
                        </a>
                    </div>
                </div>

                <!-- 4. SUBMENU: PKL & BKK -->
                @php
                    $isPklActive = request()->routeIs('pkl.*');
                @endphp
                <div class="space-y-1">
                    <button type="button"
                        onclick="toggleSidebarDropdown('submenu-pkl')"
                        class="w-full flex items-center justify-between px-5 py-3.5 rounded-full font-bold text-sm transition transform active:scale-95 cursor-pointer {{ $isPklActive ? 'bg-white/15 text-white shadow-xs' : 'text-white hover:bg-white/10' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-briefcase text-base w-5 text-center"></i>
                            <span>PKL & BKK</span>
                        </div>
                        <i id="chevron-submenu-pkl" class="fa-solid fa-chevron-down text-xs transition-transform duration-200 {{ $isPklActive ? 'rotate-180' : '' }}"></i>
                    </button>
                    <div id="submenu-pkl" class="{{ $isPklActive ? '' : 'hidden' }} pl-4 pr-1 py-1 space-y-1">
                        <a href="{{ route('pkl.dashboard') }}"
                            class="flex items-center gap-3 px-4 py-2.5 rounded-full text-xs font-semibold transition transform active:scale-95 {{ request()->routeIs('pkl.dashboard') ? 'bg-white text-[#0073c6] font-bold shadow-sm' : 'text-blue-100 hover:text-white hover:bg-white/10' }}">
                            <i class="fa-solid fa-chart-pie text-xs w-4 text-center"></i>
                            <span>Dashboard PKL</span>
                        </a>
                        <a href="{{ route('pkl.dudi.index') }}"
                            class="flex items-center gap-3 px-4 py-2.5 rounded-full text-xs font-semibold transition transform active:scale-95 {{ request()->routeIs('pkl.dudi.*') ? 'bg-white text-[#0073c6] font-bold shadow-sm' : 'text-blue-100 hover:text-white hover:bg-white/10' }}">
                            <i class="fa-solid fa-building text-xs w-4 text-center"></i>
                            <span>Data DUDI</span>
                        </a>
                        <a href="{{ route('pkl.lowongan.index') }}"
                            class="flex items-center gap-3 px-4 py-2.5 rounded-full text-xs font-semibold transition transform active:scale-95 {{ request()->routeIs('pkl.lowongan.*') ? 'bg-white text-[#0073c6] font-bold shadow-sm' : 'text-blue-100 hover:text-white hover:bg-white/10' }}">
                            <i class="fa-solid fa-laptop-code text-xs w-4 text-center"></i>
                            <span>Lowongan Kerja</span>
                        </a>
                        <a href="{{ route('pkl.siswa.index') }}"
                            class="flex items-center gap-3 px-4 py-2.5 rounded-full text-xs font-semibold transition transform active:scale-95 {{ request()->routeIs('pkl.siswa.*') ? 'bg-white text-[#0073c6] font-bold shadow-sm' : 'text-blue-100 hover:text-white hover:bg-white/10' }}">
                            <i class="fa-solid fa-user-graduate text-xs w-4 text-center"></i>
                            <span>Data Siswa PKL</span>
                        </a>
                        <a href="{{ route('pkl.index') }}"
                            class="flex items-center gap-3 px-4 py-2.5 rounded-full text-xs font-semibold transition transform active:scale-95 {{ request()->routeIs('pkl.index') || request()->routeIs('pkl.penempatan.*') ? 'bg-white text-[#0073c6] font-bold shadow-sm' : 'text-blue-100 hover:text-white hover:bg-white/10' }}">
                            <i class="fa-solid fa-address-card text-xs w-4 text-center"></i>
                            <span>Penempatan PKL</span>
                        </a>
                        <a href="{{ route('pkl.create') }}"
                            class="flex items-center gap-3 px-4 py-2.5 rounded-full text-xs font-semibold transition transform active:scale-95 {{ request()->routeIs('pkl.create') || request()->routeIs('pkl.store') ? 'bg-white text-[#0073c6] font-bold shadow-sm' : 'text-blue-100 hover:text-white hover:bg-white/10' }}">
                            <i class="fa-solid fa-file-signature text-xs w-4 text-center"></i>
                            <span>Buat Pengajuan</span>
                        </a>
                    </div>
                </div>

                <!-- 5. SUBMENU: PPDB -->
                @php
                    $isPpdbActive = request()->routeIs('index.dashboard.ppdb') || request()->routeIs('index.informasi.ppdb');
                @endphp
                <div class="space-y-1">
                    <button type="button"
                        onclick="toggleSidebarDropdown('submenu-ppdb')"
                        class="w-full flex items-center justify-between px-5 py-3.5 rounded-full font-bold text-sm transition transform active:scale-95 cursor-pointer {{ $isPpdbActive ? 'bg-white/15 text-white shadow-xs' : 'text-white hover:bg-white/10' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-user-plus text-base w-5 text-center"></i>
                            <span>PPDB</span>
                        </div>
                        <i id="chevron-submenu-ppdb" class="fa-solid fa-chevron-down text-xs transition-transform duration-200 {{ $isPpdbActive ? 'rotate-180' : '' }}"></i>
                    </button>
                    <div id="submenu-ppdb" class="{{ $isPpdbActive ? '' : 'hidden' }} pl-4 pr-1 py-1 space-y-1">
                        <a href="{{ route('index.dashboard.ppdb') }}"
                            class="flex items-center gap-3 px-4 py-2.5 rounded-full text-xs font-semibold transition transform active:scale-95 {{ request()->routeIs('index.dashboard.ppdb') ? 'bg-white text-[#0073c6] font-bold shadow-sm' : 'text-blue-100 hover:text-white hover:bg-white/10' }}">
                            <i class="fa-solid fa-table-cells-large text-xs w-4 text-center"></i>
                            <span>Dashboard PPDB</span>
                        </a>
                        <a href="{{ route('index.informasi.ppdb') }}"
                            class="flex items-center gap-3 px-4 py-2.5 rounded-full text-xs font-semibold transition transform active:scale-95 {{ request()->routeIs('index.informasi.ppdb') ? 'bg-white text-[#0073c6] font-bold shadow-sm' : 'text-blue-100 hover:text-white hover:bg-white/10' }}">
                            <i class="fa-solid fa-file-lines text-xs w-4 text-center"></i>
                            <span>Informasi & Persyaratan</span>
                        </a>
                    </div>
                </div>

                <!-- 6. SUBMENU: PRODUK UNGGULAN -->
                @php
                    $isProdukActive = request()->routeIs('produk-unggulan.*') || request()->routeIs('produk.*');
                @endphp
                <div class="space-y-1">
                    <button type="button"
                        onclick="toggleSidebarDropdown('submenu-produk')"
                        class="w-full flex items-center justify-between px-5 py-3.5 rounded-full font-bold text-sm transition transform active:scale-95 cursor-pointer {{ $isProdukActive ? 'bg-white/15 text-white shadow-xs' : 'text-white hover:bg-white/10' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-store text-base w-5 text-center"></i>
                            <span>Produk Unggulan</span>
                        </div>
                        <i id="chevron-submenu-produk" class="fa-solid fa-chevron-down text-xs transition-transform duration-200 {{ $isProdukActive ? 'rotate-180' : '' }}"></i>
                    </button>
                    <div id="submenu-produk" class="{{ $isProdukActive ? '' : 'hidden' }} pl-4 pr-1 py-1 space-y-1">
                        <a href="{{ route('produk-unggulan.index') }}"
                            class="flex items-center gap-3 px-4 py-2.5 rounded-full text-xs font-semibold transition transform active:scale-95 {{ request()->routeIs('produk-unggulan.*') ? 'bg-white text-[#0073c6] font-bold shadow-sm' : 'text-blue-100 hover:text-white hover:bg-white/10' }}">
                            <i class="fa-solid fa-store text-xs w-4 text-center"></i>
                            <span>Dashboard Produk</span>
                        </a>
                        <a href="{{ route('produk.index') }}"
                            class="flex items-center gap-3 px-4 py-2.5 rounded-full text-xs font-semibold transition transform active:scale-95 {{ request()->routeIs('produk.*') ? 'bg-white text-[#0073c6] font-bold shadow-sm' : 'text-blue-100 hover:text-white hover:bg-white/10' }}">
                            <i class="fa-solid fa-basket-shopping text-xs w-4 text-center"></i>
                            <span>Data Produk</span>
                        </a>
                    </div>
                </div>

                <!-- 7. SUBMENU: KESISWAAN -->
                @php
                    $isKesiswaanActive = request()->routeIs('admin.kesiswaan.*');
                @endphp
                <div class="space-y-1">
                    <button type="button"
                        onclick="toggleSidebarDropdown('submenu-kesiswaan')"
                        class="w-full flex items-center justify-between px-5 py-3.5 rounded-full font-bold text-sm transition transform active:scale-95 cursor-pointer {{ $isKesiswaanActive ? 'bg-white/15 text-white shadow-xs' : 'text-white hover:bg-white/10' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-users text-base w-5 text-center"></i>
                            <span>Kesiswaan</span>
                        </div>
                        <i id="chevron-submenu-kesiswaan" class="fa-solid fa-chevron-down text-xs transition-transform duration-200 {{ $isKesiswaanActive ? 'rotate-180' : '' }}"></i>
                    </button>
                    <div id="submenu-kesiswaan" class="{{ $isKesiswaanActive ? '' : 'hidden' }} pl-4 pr-1 py-1 space-y-1">
                        <a href="{{ route('admin.kesiswaan.index') }}"
                            class="flex items-center gap-3 px-4 py-2.5 rounded-full text-xs font-semibold transition transform active:scale-95 {{ request()->routeIs('admin.kesiswaan.index') ? 'bg-white text-[#0073c6] font-bold shadow-sm' : 'text-blue-100 hover:text-white hover:bg-white/10' }}">
                            <i class="fa-solid fa-users text-xs w-4 text-center"></i>
                            <span>Dashboard Kesiswaan</span>
                        </a>
                        <a href="{{ route('admin.kesiswaan.ekstrakurikuler.index') }}"
                            class="flex items-center gap-3 px-4 py-2.5 rounded-full text-xs font-semibold transition transform active:scale-95 {{ request()->routeIs('admin.kesiswaan.ekstrakurikuler.*') ? 'bg-white text-[#0073c6] font-bold shadow-sm' : 'text-blue-100 hover:text-white hover:bg-white/10' }}">
                            <i class="fa-solid fa-icons text-xs w-4 text-center"></i>
                            <span>Data Ekstrakulikuler</span>
                        </a>
                        <a href="{{ route('admin.kesiswaan.tata-tertib.index') }}"
                            class="flex items-center gap-3 px-4 py-2.5 rounded-full text-xs font-semibold transition transform active:scale-95 {{ request()->routeIs('admin.kesiswaan.tata-tertib.*') ? 'bg-white text-[#0073c6] font-bold shadow-sm' : 'text-blue-100 hover:text-white hover:bg-white/10' }}">
                            <i class="fa-solid fa-book-bookmark text-xs w-4 text-center"></i>
                            <span>Data Tata Tertib</span>
                        </a>
                    </div>
                </div>

            @else
                {{-- =================== PANEL: ROLE SPESIFIK LAINNYA (ADMIN AULA / ADMIN MASTER / ADMIN SEKOLAH) =================== --}}

                @if(in_array($userRole, ['admin', 'admin_aula']))
                    <!-- DASHBOARD ADMIN AULA -->
                    <a href="{{ route('admin.peminjaman.dashboard') }}"
                        class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('admin.peminjaman.dashboard') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                        <i class="fa-solid fa-table-cells-large text-base w-5 text-center"></i>
                        <span>Dashboard</span>
                    </a>
                @endif

                @if($userRole === 'admin_aula')
                    <!-- SECTION: PEMINJAMAN AULA (ADMIN AULA) -->
                    <a href="{{ route('admin.aula.index') }}"
                        class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('admin.aula.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                        <i class="fa-solid fa-hotel text-base w-5 text-center"></i>
                        <span>Konfigurasi Aula</span>
                    </a>

                    <a href="{{ route('admin.fasilitas.index') }}"
                        class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('admin.fasilitas.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                        <i class="fa-solid fa-box text-base w-5 text-center"></i>
                        <span>Fasilitas</span>
                    </a>

                    <a href="{{ route('admin.paket.index') }}"
                        class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('admin.paket.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                        <i class="fa-solid fa-boxes-packing text-base w-5 text-center"></i>
                        <span>Paket Peminjaman</span>
                    </a>

                    <a href="{{ route('admin.peminjaman.index') }}"
                        class="flex items-center gap-3 px-5 py-3.5 {{ (request()->routeIs('admin.peminjaman.*') && !request()->routeIs('admin.peminjaman.dashboard')) ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                        <i class="fa-solid fa-clipboard-list text-base w-5 text-center"></i>
                        <span>Daftar Peminjaman</span>
                    </a>

                    <a href="{{ route('admin.laporan.index') }}"
                        class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('admin.laporan.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                        <i class="fa-solid fa-file-invoice-dollar text-base w-5 text-center"></i>
                        <span>Laporan Pemasukan</span>
                    </a>
                @endif

                @if($userRole === 'admin_sekolah')
                    <!-- SECTION: ARTIKEL SEKOLAH -->
                    <div class="pt-3 pb-1">
                        <div class="flex items-center justify-between text-[11px] font-bold text-blue-100/80 tracking-wider uppercase px-2 mb-2">
                            <span>Artikel Sekolah</span>
                            <span class="w-12 h-[1px] bg-white/30"></span>
                        </div>
                    </div>

                    <a href="{{ route('admin.kategori-artikel.index') }}"
                        class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('admin.kategori-artikel.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                        <i class="fa-solid fa-tags text-base w-5 text-center"></i>
                        <span>Kategori Artikel</span>
                    </a>

                    <a href="{{ route('admin.artikel.index') }}"
                        class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('admin.artikel.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                        <i class="fa-solid fa-newspaper text-base w-5 text-center"></i>
                        <span>Daftar Artikel</span>
                    </a>
                @endif

                @if(in_array($userRole, ['admin_master', 'admin_sekolah']))
                    <!-- SECTION: DATA MASTER SEKOLAH -->
                    <div class="pt-3 pb-1">
                        <div class="flex items-center justify-between text-[11px] font-bold text-blue-100/80 tracking-wider uppercase px-2 mb-2">
                            <span>Data Master Sekolah</span>
                            <span class="w-12 h-[1px] bg-white/30"></span>
                        </div>
                    </div>

                    @if($userRole === 'admin_master')
                        <a href="{{ route('datamaster.index') }}"
                            class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('datamaster.index') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                            <i class="fa-solid fa-graduation-cap text-base w-5 text-center"></i>
                            <span>Dashboard Master</span>
                        </a>

                        <a href="{{ route('datamaster.sekolah.edit') }}"
                            class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('datamaster.sekolah.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                            <i class="fa-solid fa-school text-base w-5 text-center"></i>
                            <span>Data Sekolah</span>
                        </a>

                        <a href="{{ route('datamaster.users') }}"
                            class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('datamaster.users*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                            <i class="fa-solid fa-users-gear text-base w-5 text-center"></i>
                            <span>Data Users</span>
                        </a>
                    @endif

                    <a href="{{ route('datamaster.guru.index') }}"
                        class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('datamaster.guru.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                        <i class="fa-solid fa-chalkboard-user text-base w-5 text-center"></i>
                        <span>Data Guru</span>
                    </a>

                    <a href="{{ route('datamaster.siswa.index') }}"
                        class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('datamaster.siswa.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                        <i class="fa-solid fa-user-graduate text-base w-5 text-center"></i>
                        <span>Data Siswa</span>
                    </a>
                @endif
            @endif
        </div>

        <!-- LOGOUT BUTTON CONTAINER -->
        <div class="pt-4 mt-2">
            <button type="button" onclick="openLogoutModal()"
                class="w-full bg-white text-[#0073c6] hover:bg-gray-100 active:scale-95 transition font-bold py-3 px-4 rounded-full text-sm shadow-sm flex items-center justify-center gap-2 cursor-pointer">
                <i class="fa-solid fa-arrow-right-from-bracket text-sm"></i>
                <span>Logout</span>
            </button>
        </div>

    </div>
</aside>

<script>
    function toggleSidebarDropdown(id) {
        const el = document.getElementById(id);
        const chevron = document.getElementById('chevron-' + id);
        if (!el) return;
        el.classList.toggle('hidden');
        if (chevron) {
            chevron.classList.toggle('rotate-180');
        }
    }
</script>