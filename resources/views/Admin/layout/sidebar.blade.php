{{--
SIDEBAR CONTAINER

Tampilan mengikuti desain brand (biru #0073c6, rounded-tr-[40px], pill rounded-full).
Navigasi diatur menggunakan Blade if-else berdasarkan role user autentikasi:
- Pelanggan (customer panel)
- Kepala Sekolah
- BKK & PKL
- Admin Aula & Super Admin
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
                <!-- 1. DASHBOARD CUSTOMER -->
                <a href="{{ route('customer.dashboard') }}"
                    class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('customer.dashboard') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                    <i class="fa-solid fa-table-cells-large text-base w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>

                <!-- 2. PAKET PEMINJAMAN -->
                <a href="{{ route('customer.paket') }}"
                    class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('customer.paket') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                    <i class="fa-solid fa-cube text-base w-5 text-center"></i>
                    <span>Paket Peminjaman</span>
                </a>

                <!-- 3. PEMINJAMAN -->
                <a href="{{ route('customer.peminjaman.create') }}"
                    class="flex items-center gap-3 px-5 py-3.5 {{ request()->is('customer/peminjaman*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                    <i class="fa-regular fa-comment-dots text-base w-5 text-center"></i>
                    <span>Peminjaman</span>
                </a>

                <!-- 4. CEK PEMINJAMAN -->
                <a href="{{ route('customer.cek-peminjaman') }}"
                    class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('customer.cek-peminjaman') || request()->routeIs('customer.riwayat') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                    <i class="fa-solid fa-desktop text-base w-5 text-center"></i>
                    <span>Cek Peminjaman</span>
                </a>

                <!-- 5. PROFIL -->
                <a href="{{ route('customer.profil') }}"
                    class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('customer.profil') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                    <i class="fa-solid fa-user-group text-base w-5 text-center"></i>
                    <span>Profil</span>
                </a>

            @elseif($userRole === 'kepala_sekolah')
                <!-- 1. DASHBOARD KEPALA SEKOLAH -->
                <a href="{{ route('kepala-sekolah.dashboard') }}"
                    class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('kepala-sekolah.dashboard') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                    <i class="fa-solid fa-table-cells-large text-base w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>

                <!-- 2. DAFTAR PEMINJAMAN KEPALA SEKOLAH -->
                @php
                    $pendingFinalCount = \App\Models\Peminjaman::where('status', 'approved_1')->count();
                @endphp
                <a href="{{ route('kepala-sekolah.peminjaman.index') }}"
                    class="flex items-center justify-between px-5 py-3.5 {{ request()->routeIs('kepala-sekolah.peminjaman.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-clipboard-list text-base w-5 text-center"></i>
                        <span>Daftar Peminjaman</span>
                    </div>
                    @if ($pendingFinalCount > 0)
                        <span
                            class="px-2 py-0.5 rounded-full text-[10px] font-black {{ request()->routeIs('kepala-sekolah.peminjaman.*') ? 'bg-amber-400 text-slate-900' : 'bg-amber-400 text-slate-900' }}">
                            {{ $pendingFinalCount }}
                        </span>
                    @endif
                </a>

                <!-- 3. LAPORAN PEMASUKAN AULA (KEPALA SEKOLAH) -->
                <a href="{{ route('kepala-sekolah.laporan.index') }}"
                    class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('kepala-sekolah.laporan.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                    <i class="fa-solid fa-file-invoice-dollar text-base w-5 text-center"></i>
                    <span>Laporan Pemasukan</span>
                </a>

            @elseif($userRole === 'bkk' || $userRole === 'admin_pklbkk')
                <!-- 1. DASHBOARD BKK -->
                <a href="{{ route('pkl.dashboard') }}"
                    class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('pkl.dashboard') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                    <i class="fa-solid fa-chart-pie text-base w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>

                <!-- 2. DATA DUDI -->
                <a href="{{ route('pkl.dudi.index') }}"
                    class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('pkl.dudi.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                    <i class="fa-solid fa-building text-base w-5 text-center"></i>
                    <span>Data DUDI</span>
                </a>

                <!-- 3. LOWONGAN KERJA -->
                <a href="{{ route('pkl.lowongan.index') }}"
                    class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('pkl.lowongan.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                    <i class="fa-solid fa-laptop-code text-base w-5 text-center"></i>
                    <span>Lowongan Kerja</span>
                </a>

                <!-- 4. DATA SISWA PKL -->
                <a href="{{ route('pkl.siswa.index') }}"
                    class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('pkl.siswa.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                    <i class="fa-solid fa-user-graduate text-base w-5 text-center"></i>
                    <span>Data Siswa PKL</span>
                </a>

                <!-- 5. PENEMPATAN PKL -->
                <a href="{{ route('pkl.index') }}"
                    class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('pkl.index') || request()->routeIs('pkl.penempatan.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                    <i class="fa-solid fa-address-card text-base w-5 text-center"></i>
                    <span>Penempatan PKL</span>
                </a>

                <!-- 6. BUAT PENGAJUAN -->
                <a href="{{ route('pkl.create') }}"
                    class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('pkl.create') || request()->routeIs('pkl.store') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                    <i class="fa-solid fa-file-signature text-base w-5 text-center"></i>
                    <span>Buat Pengajuan</span>
                </a>

            @elseif(in_array($userRole, ['admin_produk', 'admin_produk_unggulan']))
                <!-- 1. DASHBOARD PRODUK -->
                <a href="{{ route('produk-unggulan.index') }}"
                    class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('produk-unggulan.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                    <i class="fa-solid fa-store text-base w-5 text-center"></i>
                    <span>Dashboard Produk</span>
                </a>

                <!-- 2. DATA PRODUK -->
                <a href="{{ route('produk.index') }}"
                    class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('produk.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                    <i class="fa-solid fa-basket-shopping text-base w-5 text-center"></i>
                    <span>Data Produk</span>
                </a>

            @elseif($userRole === 'admin_ppdb')
                <!-- 1. DASHBOARD PPDB -->
                <a href="{{ route('index.dashboard.ppdb') }}"
                    class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('index.dashboard.ppdb') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                    <i class="fa-solid fa-table-cells-large text-base w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>

                <!-- 2. INFORMASI & PERSYARATAN -->
                <a href="{{ route('index.informasi.ppdb') }}"
                    class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('index.informasi.ppdb') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                    <i class="fa-solid fa-file-lines text-base w-5 text-center"></i>
                    <span>Informasi & Persyaratan</span>
                </a>
            @elseif($userRole === 'admin_kesiswaan')

                    <!-- 1. DASHBOARD KESISWAAN -->
                    <a href="{{ route('admin.kesiswaan.index') }}"
                        class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('admin.kesiswaan.index') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                        <i class="fa-solid fa-users text-base w-5 text-center"></i>
                        <span>Dashboard</span>
                    </a>

                    <!-- 3. DATA EKSTRAKURIKULER -->
                    <a href="{{ route('admin.kesiswaan.ekstrakurikuler.index') }}"
                        class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('admin.kesiswaan.ekstrakurikuler.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                        <i class="fa-solid fa-icons text-base w-5 text-center"></i>
                        <span>Data Ekstrakulikuler</span>
                    </a>

                    <!-- 4. DATA TATA TERTIB -->
                    <a href="{{ route('admin.kesiswaan.tata-tertib.index') }}"
                        class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('admin.kesiswaan.tata-tertib.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                        <i class="fa-solid fa-book-bookmark text-base w-5 text-center"></i>
                        <span>Data Tata Tertib</span>
                    </a>

                @else
                    @if(in_array($userRole, ['admin', 'admin_aula', 'super_admin', 'super_duper_admin']))
                        <!-- 1. DASHBOARD ADMIN (AULA) -->
                        <a href="{{ route('admin.peminjaman.dashboard') }}"
                            class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('admin.peminjaman.dashboard') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                            <i class="fa-solid fa-table-cells-large text-base w-5 text-center"></i>
                            <span>Dashboard</span>
                        </a>
                    @endif

                    @if(in_array($userRole, ['admin_aula', 'super_admin', 'super_duper_admin']))
                        <!-- SECTION: PEMINJAMAN AULA -->
                        @if(in_array($userRole, ['super_admin', 'super_duper_admin']))
                            <div class="pt-3 pb-1">
                                <div
                                    class="flex items-center justify-between text-[11px] font-bold text-blue-100/80 tracking-wider uppercase px-2 mb-2">
                                    <span>Peminjaman Aula</span>
                                    <span class="w-12 h-[1px] bg-white/30"></span>
                                </div>
                            </div>
                        @endif

                        <!-- 2. KONFIGURASI AULA -->
                        <a href="{{ route('admin.aula.index') }}"
                            class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('admin.aula.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                            <i class="fa-solid fa-hotel text-base w-5 text-center"></i>
                            <span>Konfigurasi Aula</span>
                        </a>

                        @if(in_array($userRole, ['super_admin', 'super_duper_admin']))
                            <!-- KONFIGURASI PEMINJAMAN (SUPER ADMIN) -->
                            <a href="{{ route('admin.payment-configuration.index') }}"
                                class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('admin.payment-configuration.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                                <i class="fa-solid fa-sliders text-base w-5 text-center"></i>
                                <span>Konfigurasi Peminjaman</span>
                            </a>
                        @endif

                        <!-- 3. FASILITAS -->
                        <a href="{{ route('admin.fasilitas.index') }}"
                            class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('admin.fasilitas.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                            <i class="fa-solid fa-box text-base w-5 text-center"></i>
                            <span>Fasilitas</span>
                        </a>

                        <!-- 3. PAKET PEMINJAMAN -->
                        <a href="{{ route('admin.paket.index') }}"
                            class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('admin.paket.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                            <i class="fa-solid fa-boxes-packing text-base w-5 text-center"></i>
                            <span>Paket Peminjaman</span>
                        </a>

                        <!-- 4. DAFTAR PEMINJAMAN -->
                        <a href="{{ route('admin.peminjaman.index') }}"
                            class="flex items-center gap-3 px-5 py-3.5 {{ (request()->routeIs('admin.peminjaman.*') && !request()->routeIs('admin.peminjaman.dashboard')) ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                            <i class="fa-solid fa-clipboard-list text-base w-5 text-center"></i>
                            <span>Daftar Peminjaman</span>
                        </a>

                        <!-- 5. LAPORAN PEMASUKAN AULA (ADMIN AULA) -->
                        <a href="{{ route('admin.laporan.index') }}"
                            class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('admin.laporan.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                            <i class="fa-solid fa-file-invoice-dollar text-base w-5 text-center"></i>
                            <span>Laporan Pemasukan</span>
                        </a>
                    @endif

                    @if(in_array($userRole, ['admin_sekolah', 'super_admin', 'super_duper_admin']))
                        <!-- SECTION: ARTIKEL SEKOLAH -->
                        <div class="pt-3 pb-1">
                            <div
                                class="flex items-center justify-between text-[11px] font-bold text-blue-100/80 tracking-wider uppercase px-2 mb-2">
                                <span>Artikel Sekolah</span>
                                <span class="w-12 h-[1px] bg-white/30"></span>
                            </div>
                        </div>

                        <!-- KATEGORI ARTIKEL -->
                        <a href="{{ route('admin.kategori-artikel.index') }}"
                            class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('admin.kategori-artikel.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                            <i class="fa-solid fa-tags text-base w-5 text-center"></i>
                            <span>Kategori Artikel</span>
                        </a>

                        <!-- DAFTAR ARTIKEL -->
                        <a href="{{ route('admin.artikel.index') }}"
                            class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('admin.artikel.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                            <i class="fa-solid fa-newspaper text-base w-5 text-center"></i>
                            <span>Daftar Artikel</span>
                        </a>
                    @endif

                    @if(in_array($userRole, ['admin_master', 'admin_sekolah', 'super_admin', 'super_duper_admin']))
                        <!-- SECTION: DATA MASTER SEKOLAH -->
                        <div class="pt-3 pb-1">
                            <div
                                class="flex items-center justify-between text-[11px] font-bold text-blue-100/80 tracking-wider uppercase px-2 mb-2">
                                <span>Data Master Sekolah</span>
                                <span class="w-12 h-[1px] bg-white/30"></span>
                            </div>
                        </div>

                        @if(in_array($userRole, ['admin_master', 'super_admin', 'super_duper_admin']))
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

                    @if(in_array($userRole, ['super_admin', 'super_duper_admin']))
                        <!-- SECTION: PKL & BKK -->
                        <div class="pt-3 pb-1">
                            <div
                                class="flex items-center justify-between text-[11px] font-bold text-blue-100/80 tracking-wider uppercase px-2 mb-2">
                                <span>{{ 'PKL & BKK' }}</span>
                                <span class="w-12 h-[1px] bg-white/30"></span>
                            </div>
                        </div>

                        <a href="{{ route('pkl.dashboard') }}"
                            class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('pkl.dashboard') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                            <i class="fa-solid fa-chart-pie text-base w-5 text-center"></i>
                            <span>Dashboard BKK</span>
                        </a>

                        <a href="{{ route('pkl.dudi.index') }}"
                            class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('pkl.dudi.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                            <i class="fa-solid fa-building text-base w-5 text-center"></i>
                            <span>Data DUDI</span>
                        </a>

                        <a href="{{ route('pkl.lowongan.index') }}"
                            class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('pkl.lowongan.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                            <i class="fa-solid fa-laptop-code text-base w-5 text-center"></i>
                            <span>Lowongan Kerja</span>
                        </a>

                        <a href="{{ route('pkl.siswa.index') }}"
                            class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('pkl.siswa.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                            <i class="fa-solid fa-user-graduate text-base w-5 text-center"></i>
                            <span>Data Siswa PKL</span>
                        </a>

                        <a href="{{ route('pkl.index') }}"
                            class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('pkl.index') || request()->routeIs('pkl.penempatan.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                            <i class="fa-solid fa-address-card text-base w-5 text-center"></i>
                            <span>Penempatan PKL</span>
                        </a>

                        <a href="{{ route('pkl.create') }}"
                            class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('pkl.create') || request()->routeIs('pkl.store') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                            <i class="fa-solid fa-file-signature text-base w-5 text-center"></i>
                            <span>Buat Pengajuan</span>
                        </a>
                    @endif

                    @if(in_array($userRole, ['super_admin', 'super_duper_admin']))
                        <!-- SECTION: PPDB -->
                        <div class="pt-3 pb-1">
                            <div
                                class="flex items-center justify-between text-[11px] font-bold text-blue-100/80 tracking-wider uppercase px-2 mb-2">
                                <span>PPDB</span>
                                <span class="w-12 h-[1px] bg-white/30"></span>
                            </div>
                        </div>

                        <a href="{{ route('index.dashboard.ppdb') }}"
                            class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('index.dashboard.ppdb') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                            <i class="fa-solid fa-user-plus text-base w-5 text-center"></i>
                            <span>Dashboard PPDB</span>
                        </a>

                        <a href="{{ route('index.informasi.ppdb') }}"
                            class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('index.informasi.ppdb') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                            <i class="fa-solid fa-file-lines text-base w-5 text-center"></i>
                            <span>Informasi & Persyaratan</span>
                        </a>
                    @endif

                    @if(in_array($userRole, ['super_admin', 'super_duper_admin']))
                        <!-- SECTION: PRODUK UNGGULAN -->
                        <div class="pt-3 pb-1">
                            <div
                                class="flex items-center justify-between text-[11px] font-bold text-blue-100/80 tracking-wider uppercase px-2 mb-2">
                                <span>Produk Unggulan</span>
                                <span class="w-12 h-[1px] bg-white/30"></span>
                            </div>
                        </div>

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
                    @endif

                    @if(in_array($userRole, ['super_admin', 'super_duper_admin']))
                        <!-- SECTION: KESISWAAN -->
                        <div class="pt-3 pb-1">
                            <div
                                class="flex items-center justify-between text-[11px] font-bold text-blue-100/80 tracking-wider uppercase px-2 mb-2">
                                <span>Kesiswaan</span>
                                <span class="w-12 h-[1px] bg-white/30"></span>
                            </div>
                        </div>

                        <a href="{{ route('admin.kesiswaan.index') }}"
                            class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('admin.kesiswaan.index') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                            <i class="fa-solid fa-users text-base w-5 text-center"></i>
                            <span>Dashboard Kesiswaan</span>
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