{{-- Header & navigasi publik.
     Link PPDB ditandai aktif otomatis saat sedang di route 'ppdb'. --}}
@php($activePpdb = request()->routeIs('ppdb'))

<!-- HEADER & NAVIGATION -->
<header
    class="sticky top-0 z-40 bg-white/90 backdrop-blur-md transition-all duration-300 border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
        <!-- Logo Section -->
        <a href="{{ route('landing') }}" class="flex items-center gap-3 group">
            <div
                class="w-12 h-12 flex items-center justify-center rounded-lg p-1 group-hover:scale-105 transition-transform">
                <div
                    class="relative w-full h-full flex items-center justify-center transform-gpu translate-z-10 transition-transform duration-300 group-hover:scale-105">
                    <img src="{{ asset('assets/logosmkk.png') }}" alt="Logo SMK Negeri 2 Karanganyar"
                        class="w-full h-full object-contain filter drop-shadow-xl select-none">
                </div>
            </div>
            <div>
                <span
                    class="block font-extrabold text-slate-800 text-base sm:text-lg leading-tight tracking-tight">SMK
                    NEGERI 2</span>
                <span class="block font-bold text-brand-blue text-xs sm:text-sm tracking-wider">KARANGANYAR</span>
            </div>
        </a>

        <!-- Desktop Nav Pill -->
        <nav
            class="hidden lg:flex items-center bg-brand-blue text-white rounded-full px-6 py-2.5 shadow-lg shadow-blue-500/20">
            <a href="{{ route('profil') }}"
                class="px-3 py-1 text-sm font-medium hover:text-blue-200 transition-colors">Profile</a>

            <!-- Dropdown Peminjaman Aula -->
            <div class="relative group">
                <button
                    class="px-3 py-1 text-sm font-medium hover:text-blue-200 transition-colors inline-flex items-center gap-1">
                    Peminjaman Aula <i
                        class="fa-solid fa-chevron-down text-xs transition-transform group-hover:rotate-180"></i>
                </button>
                <div
                    class="absolute left-0 mt-2 w-48 bg-white text-slate-800 rounded-xl shadow-xl py-2 opacity-0 group-hover:opacity-100 pointer-events-none group-hover:pointer-events-auto transition-all duration-200 border border-slate-100">
                    <a href="{{ route('layanan-peminjaman') }}#paket"
                        class="block px-4 py-2 text-xs font-semibold hover:bg-slate-50 hover:text-brand-blue">
                        Info Peminjaman</a>
                    <a href="{{ route('layanan-peminjaman') }}#paket"
                        class="block px-4 py-2 text-xs font-semibold hover:bg-slate-50 hover:text-brand-blue">
                        Paket & Tarif</a>
                </div>
            </div>

            <!-- Dropdown Kesiswaan -->
            <div class="relative group">
                <button
                    class="px-3 py-1 text-sm font-medium hover:text-blue-200 transition-colors inline-flex items-center gap-1">
                    Kesiswaan <i
                        class="fa-solid fa-chevron-down text-xs transition-transform group-hover:rotate-180"></i>
                </button>
                <div
                    class="absolute left-0 mt-2 w-48 bg-white text-slate-800 rounded-xl shadow-xl py-2 opacity-0 group-hover:opacity-100 pointer-events-none group-hover:pointer-events-auto transition-all duration-200 border border-slate-100">
                    <a href="{{ route('kesiswaan') }}#prestasi"
                        class="block px-4 py-2 text-xs font-semibold hover:bg-slate-50 hover:text-brand-blue">
                        Prestasi SNBT</a>
                    <a href="{{ route('kesiswaan') }}#ekstrakurikuler"
                        class="block px-4 py-2 text-xs font-semibold hover:bg-slate-50 hover:text-brand-blue">
                        Ekstrakurikuler</a>
                    <a href="{{ route('kesiswaan') }}#tata-tertib"
                        class="block px-4 py-2 text-xs font-semibold hover:bg-slate-50 hover:text-brand-blue">
                        Tata Tertib</a>
                </div>
            </div>

            <!-- Dropdown Produk Unggulan -->
            <div class="relative group">
                <button
                    class="px-3 py-1 text-sm font-medium hover:text-blue-200 transition-colors inline-flex items-center gap-1">
                    Produk Unggulan <i
                        class="fa-solid fa-chevron-down text-xs transition-transform group-hover:rotate-180"></i>
                </button>
                <div
                    class="absolute left-0 mt-2 w-52 bg-white text-slate-800 rounded-xl shadow-xl py-2 opacity-0 group-hover:opacity-100 pointer-events-none group-hover:pointer-events-auto transition-all duration-200 border border-slate-100">
                    <a href="{{ route('produk-unggulan') }}#permesinan"
                        class="block px-4 py-2 text-xs font-semibold hover:bg-slate-50 hover:text-brand-blue">
                        Teknik Permesinan</a>
                    <a href="{{ route('produk-unggulan') }}#tekstil"
                        class="block px-4 py-2 text-xs font-semibold hover:bg-slate-50 hover:text-brand-blue">
                        Teknik Pembuatan Kain</a>
                    <a href="{{ route('produk-unggulan') }}#rpl"
                        class="block px-4 py-2 text-xs font-semibold hover:bg-slate-50 hover:text-brand-blue">
                        RPL Digital</a>
                    <a href="{{ route('produk-unggulan') }}#ototronik"
                        class="block px-4 py-2 text-xs font-semibold hover:bg-slate-50 hover:text-brand-blue">
                        Teknik Ototronik</a>
                </div>
            </div>

            <!-- Dropdown PKL & BKK -->
            <div class="relative group">
                <button
                    class="px-3 py-1 text-sm font-medium hover:text-blue-200 transition-colors inline-flex items-center gap-1">
                    PKL & BKK <i
                        class="fa-solid fa-chevron-down text-xs transition-transform group-hover:rotate-180"></i>
                </button>
                <div
                    class="absolute left-0 mt-2 w-48 bg-white text-slate-800 rounded-xl shadow-xl py-2 opacity-0 group-hover:opacity-100 pointer-events-none group-hover:pointer-events-auto transition-all duration-200 border border-slate-100">
                    <a href="{{ route('pkl-bkk') }}#mitra-dudi"
                        class="block px-4 py-2 text-xs font-semibold hover:bg-slate-50 hover:text-brand-blue">
                        Mitra DUDI</a>
                    <a href="#" onclick="openModal('Informasi Karir & BKK', 'Fitur portal Bursa Kerja Khusus (BKK) dan informasi magang siswa.')"
                        class="block px-4 py-2 text-xs font-semibold hover:bg-slate-50 hover:text-brand-blue">Layanan
                        Magang</a>
                </div>
            </div>

            <a href="{{ route('ppdb') }}"
                class="px-3 py-1 text-sm transition-colors {{ $activePpdb ? 'font-semibold bg-white/20 rounded-full' : 'font-medium hover:text-blue-200' }}">PPDB</a>
        </nav>

        <!-- Mobile Hamburger Menu Button -->
        <button id="mobile-menu-btn"
            class="lg:hidden text-slate-700 hover:text-brand-blue text-2xl p-2 focus:outline-none">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>

    <!-- Mobile Navigation Menu -->
    <div id="mobile-menu" class="hidden lg:hidden bg-white border-b border-slate-200 px-6 py-4 transition-all">
        <div class="flex flex-col gap-3 font-semibold text-slate-700">
            <a href="{{ route('profil') }}" class="hover:text-brand-blue py-1">Profile</a>
            <a href="{{ route('pkl-bkk') }}" class="hover:text-brand-blue py-1">Kompetensi Keahlian</a>
            <a href="{{ route('kesiswaan') }}" class="hover:text-brand-blue py-1">Kesiswaan</a>
            <a href="{{ route('produk-unggulan') }}" class="hover:text-brand-blue py-1">Produk Unggulan</a>
            <a href="{{ route('layanan-peminjaman') }}" class="hover:text-brand-blue py-1">Peminjaman Aula</a>
            <a href="{{ route('ppdb') }}"
                class="hover:text-brand-blue py-1 {{ $activePpdb ? 'text-brand-blue font-bold' : '' }}">PPDB 2026</a>
        </div>
    </div>
</header>
