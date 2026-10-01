{{--
    SIDEBAR CONTAINER

    Tampilan mengikuti desain branch `dapin` (biru #0073c6, kategori dengan
    garis pemisah, logout pill). Data menu & pembatasan role mengikuti
    config/menu.php + App\Support\Menu supaya modul PKL & BKK milik branch
    `uroo` tetap muncul hanya untuk role yang berhak.

    Item dengan route null (modul belum punya halaman) dirender sebagai
    `href="#"` dengan teks redup — sama seperti desain aslinya, tapi jelas
    terbaca sebagai "belum tersedia".
--}}
<aside id="sidebar"
    class="fixed top-0 bottom-0 left-0 z-50 w-[270px] bg-white flex flex-col transition-transform duration-300 -translate-x-full lg:translate-x-0 border-r border-gray-100">

    @php
        $role = auth()->user()?->role;
        $modules = App\Support\Menu::forRole($role);
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


            @if((auth()->user() && auth()->user()->role === 'pelanggan') || request()->routeIs('customer.*'))
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
            @elseif(auth()->user() && auth()->user()->role === 'kepala_sekolah')
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
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black {{ request()->routeIs('kepala-sekolah.peminjaman.*') ? 'bg-amber-400 text-slate-900' : 'bg-amber-400 text-slate-900' }}">
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
            @elseif(auth()->user()?->role === 'bkk')
                @foreach ($modules as $module)
                @php
                    $moduleRoute = $module['route'] ?? null;
                    $moduleUrl = App\Support\Menu::url($moduleRoute, $role);
                    $moduleActive = App\Support\Menu::isActive($moduleRoute, $role);
                @endphp

                {{-- Modul tanpa kategori anak, mis. Dashboard --}}
                @if (empty($module['children']))
                    <a href="{{ $moduleUrl ?? '#' }}"
                        @if ($moduleUrl === null) aria-disabled="true" @endif
                        @class([
                            'flex items-center gap-3 px-5 py-3.5 rounded-full font-bold text-sm shadow-sm transition transform active:scale-95',
                            'bg-white text-[#0073c6]' => $moduleActive,
                            'text-white hover:bg-white/10' => ! $moduleActive && $moduleUrl !== null,
                            'text-white/40 cursor-default' => $moduleUrl === null,
                        ])>
                        <i class="fa-solid {{ $module['icon'] }} text-base"></i>
                        <span>{{ $module['label'] }}</span>
                    </a>
                @else
                    {{-- Modul berkategori --}}
                    <div class="space-y-3">
                        <div
                            class="flex items-center justify-between text-[12px] font-medium text-blue-100/90 tracking-wide">
                            <span>{{ $module['label'] }}</span>
                            <span class="w-12 h-[1px] bg-white/30"></span>
                        </div>
                        <div class="space-y-2.5 pl-1">
                            @foreach ($module['children'] as $child)
                                @php
                                    $childUrl = App\Support\Menu::url($child['route'] ?? null, $role);
                                    $childActive = App\Support\Menu::isActive($child['route'] ?? null, $role);
                                @endphp
                                <a href="{{ $childUrl ?? '#' }}"
                                    @if ($childUrl === null) aria-disabled="true" @endif
                                    @class([
                                        'flex items-center gap-3 py-1.5 font-medium text-sm transition',
                                        // Aktif: putih solid + tebal
                                        'text-white font-semibold' => $childActive,
                                        // Tersedia tapi tidak aktif
                                        'text-white/90 hover:text-blue-100' => ! $childActive && $childUrl !== null,
                                        // Belum ada halamannya
                                        'text-white/40 cursor-default' => $childUrl === null,
                                    ])>
                                    <i class="fa-solid {{ $child['icon'] }} w-5 text-center text-base"></i>
                                    <span>{{ $child['label'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
            @else
                <!-- 1. DASHBOARD ADMIN -->
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
                <a href="{{ route('admin.paket.index') }}"
                    class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('admin.paket.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                    <i class="fa-solid fa-boxes-packing text-base w-5 text-center"></i>
                    <span>Paket Peminjaman</span>
                </a>

                <!-- 4. DAFTAR PEMINJAMAN -->
                <a href="{{ route('admin.peminjaman.index') }}"
                    class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('admin.peminjaman.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                    <i class="fa-solid fa-clipboard-list text-base w-5 text-center"></i>
                    <span>Daftar Peminjaman</span>
                </a>

                <!-- 5. LAPORAN PEMASUKAN AULA (ADMIN AULA) -->
                <a href="{{ route('admin.laporan.index') }}"
                    class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('admin.laporan.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                    <i class="fa-solid fa-file-invoice-dollar text-base w-5 text-center"></i>
                    <span>Laporan Pemasukan</span>
                </a>

                @if(in_array(auth()->user()?->role, ['super_admin', 'super_duper_admin']))
                    <!-- 6. KONFIGURASI PEMINJAMAN (SUPER ADMIN) -->
                     <a href="{{ route('datamaster.index') }}"
                        class="flex items-center gap-3 py-1.5 px-3 rounded-full text-sm transition 
                        {{ request()->routeIs('datamaster.index') ? 'bg-white/20 text-white font-bold' : 'text-white hover:text-blue-100 font-medium' }}">
                        <i class="fa-solid fa-graduation-cap w-5 text-center text-base"></i>
                        <span>Dashboard Master</span>
                    </a>
                    
                    <!-- Data Sekolah -->
                    <a href="{{ route('datamaster.sekolah.edit') }}"
                        class="flex items-center gap-3 py-1.5 px-3 rounded-full text-sm transition 
                        {{ request()->routeIs('datamaster.sekolah.*') ? 'bg-white/20 text-white font-bold' : 'text-white hover:text-blue-100 font-medium' }}">
                        <i class="fa-regular fa-comment-dots w-5 text-center text-base"></i>
                        <span>Data Sekolah</span>
                    </a>
                    
                    <!-- Users -->
                    <a href="{{ route('datamaster.users') }}"
                        class="flex items-center gap-3 py-1.5 px-3 rounded-full text-sm transition 
                        {{ request()->routeIs('datamaster.users*') ? 'bg-white/20 text-white font-bold' : 'text-white hover:text-blue-100 font-medium' }}">
                        <i class="fa-solid fa-users-gear w-5 text-center text-base"></i>
                        <span>Users</span>
                    </a>
                    <a href="{{ route('admin.payment-configuration.index') }}"
                        class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('admin.payment-configuration.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
                        <i class="fa-solid fa-sliders text-base w-5 text-center"></i>
                        <span>Konfigurasi Peminjaman</span>
                    </a>
                @endif
            @endif
        </div>

        <!-- LOGOUT BUTTON CONTAINER -->
        <div class="pt-4 mt-2">
         <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin keluar dari portal admin?')">
             @csrf
            <button name="logout"
                class="w-full bg-white text-[#0073c6] hover:bg-gray-100 transition font-bold py-3 px-4 rounded-full text-sm shadow-sm flex items-center justify-center">
                <span>Logout</span>
            </button>
         </form>
        </div>

    </div>
</aside>
