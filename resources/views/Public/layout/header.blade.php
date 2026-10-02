@php
    $navJurusan = $navJurusan ?? collect();
@endphp

<!-- HEADER & NAVIGATION -->
<header class="sticky top-0 z-40 bg-white/90 backdrop-blur-md transition-all duration-300 border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
        <!-- Logo Section -->
        <a href="{{ url('/') }}" class="flex items-center gap-3 group">
            <div class="w-12 h-12 flex items-center justify-center rounded-lg p-1 group-hover:scale-105 transition-transform">
                <div class="relative w-full h-full flex items-center justify-center transform-gpu translate-z-10 transition-transform duration-300 group-hover:scale-105">
                    @if (file_exists(public_path('assets/logosmkk.png')))
                        <img src="{{ asset('assets/logosmkk.png') }}" alt="Logo SMK Negeri 2 Karanganyar"
                            class="w-full h-full object-contain filter drop-shadow-xl select-none">
                    @else
                        <span class="w-full h-full rounded-full bg-brand-blue text-white flex items-center justify-center font-black text-sm">N2</span>
                    @endif
                </div>
            </div>
            <div>
                <span class="block font-extrabold text-slate-800 text-base sm:text-lg leading-tight tracking-tight">SMK NEGERI 2</span>
                <span class="block font-bold text-brand-blue text-xs sm:text-sm tracking-wider">KARANGANYAR</span>
            </div>
        </a>

        <!-- Desktop Nav Pill -->
        <nav class="hidden lg:flex items-center bg-brand-blue text-white rounded-full px-6 py-2.5 shadow-lg shadow-blue-500/20">
            
            {{-- LINK: PROFIL (ACTIVE STATE) --}}
            <a href="{{ route('profil') }}"
                class="px-3 py-1 text-sm transition-colors
                {{ request()->routeIs('profil') 
                    ? 'font-semibold bg-white/20 rounded-full' 
                    : 'font-medium hover:text-blue-200' }}">
                Profile
            </a>

            <!-- Dropdown Kesiswaan -->
            <div class="relative dropdown">
                <button class="dropdown-toggle px-3 py-1 text-sm font-medium hover:text-blue-200 transition-colors inline-flex items-center gap-1">
                    Kesiswaan <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200"></i>
                </button>
                <div class="dropdown-menu hidden absolute left-0 mt-2 w-48 bg-white text-slate-800 rounded-xl shadow-xl py-2 border border-slate-100 z-50">
                    <a href="#" class="block px-4 py-2 text-xs font-semibold hover:bg-slate-50 hover:text-brand-blue">Prestasi Siswa</a>
                    <a href="#" class="block px-4 py-2 text-xs font-semibold hover:bg-slate-50 hover:text-brand-blue">Ekstrakurikuler</a>
                    <a href="#" class="block px-4 py-2 text-xs font-semibold hover:bg-slate-50 hover:text-brand-blue">Tata Tertib</a>
                </div>
            </div>

            <!-- Dropdown Produk Unggulan -->
            <div class="relative dropdown">
                <button class="dropdown-toggle px-3 py-1 text-sm transition-colors inline-flex items-center gap-1
                    {{ request()->routeIs('produk-unggulan*') 
                        ? 'font-semibold bg-white/20 rounded-full' 
                        : 'font-medium hover:text-blue-200' }}">
                    Produk Unggulan <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200"></i>
                </button>
                <div class="dropdown-menu hidden absolute left-0 mt-2 w-56 bg-white text-slate-800 rounded-xl shadow-xl py-2 border border-slate-100 z-50">
                    <a href="{{ route('produk-unggulan') }}" class="block px-4 py-2 text-xs font-bold text-brand-blue border-b border-slate-100 hover:bg-slate-50">
                        Semua Produk Unggulan
                    </a>
                    @forelse ($navJurusan as $jurusan)
                        <a href="{{ route('produk-unggulan') }}#jurusan-{{ $jurusan->jurusanID }}"
                            class="block px-4 py-2 text-xs font-semibold hover:bg-slate-50 hover:text-brand-blue">
                            {{ $jurusan->nama }}
                        </a>
                    @empty
                        <a href="{{ route('produk-unggulan') }}" class="block px-4 py-2 text-xs font-semibold hover:bg-slate-50 hover:text-brand-blue">Teknik Permesinan</a>
                        <a href="{{ route('produk-unggulan') }}" class="block px-4 py-2 text-xs font-semibold hover:bg-slate-50 hover:text-brand-blue">Teknik Pembuatan Kain</a>
                        <a href="{{ route('produk-unggulan') }}" class="block px-4 py-2 text-xs font-semibold hover:bg-slate-50 hover:text-brand-blue">Teknik Ototronik</a>
                        <a href="{{ route('produk-unggulan') }}" class="block px-4 py-2 text-xs font-semibold hover:bg-slate-50 hover:text-brand-blue">Rekayasa Perangkat Lunak</a>
                    @endforelse
                </div>
            </div>

            <!-- Dropdown Peminjaman Aula -->
            <div class="relative dropdown">
                <button class="dropdown-toggle px-3 py-1 text-sm transition-colors inline-flex items-center gap-1
                    {{ request()->routeIs('layanan-peminjaman') 
                        ? 'font-semibold bg-white/20 rounded-full' 
                        : 'font-medium hover:text-blue-200' }}">
                    Peminjaman Aula <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200"></i>
                </button>
                <div class="dropdown-menu hidden absolute left-0 mt-2 w-52 bg-white text-slate-800 rounded-xl shadow-xl py-2 border border-slate-100 z-50">
                    <a href="{{ route('layanan-peminjaman') }}#informasi" class="block px-4 py-2 text-xs font-semibold hover:bg-slate-50 hover:text-brand-blue">Info Peminjaman</a>
                    <a href="{{ route('layanan-peminjaman') }}#paket" class="block px-4 py-2 text-xs font-semibold hover:bg-slate-50 hover:text-brand-blue">Paket & Tarif</a>
                    <a href="{{ route('layanan-peminjaman') }}#fasilitas" class="block px-4 py-2 text-xs font-semibold hover:bg-slate-50 hover:text-brand-blue">Fasilitas Unggulan</a>
                    <a href="{{ route('layanan-peminjaman') }}#jadwal" class="block px-4 py-2 text-xs font-semibold hover:bg-slate-50 hover:text-brand-blue">Cek Ketersediaan Aula</a>
                </div>
            </div>

            <!-- Dropdown PKL & BKK -->
            <div class="relative dropdown">
                <button class="dropdown-toggle px-3 py-1 text-sm font-medium hover:text-blue-200 transition-colors inline-flex items-center gap-1">
                    PKL & BKK <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200"></i>
                </button>
                <div class="dropdown-menu hidden absolute left-0 mt-2 w-48 bg-white text-slate-800 rounded-xl shadow-xl py-2 border border-slate-100 z-50">
                    <a href="{{ route('pkl-bkk') }}#mitra" class="block px-4 py-2 text-xs font-semibold hover:bg-slate-50 hover:text-brand-blue">Mitra DUDI</a>
                    <a href="{{ route('pkl-bkk') }}#lowongan" class="block px-4 py-2 text-xs font-semibold hover:bg-slate-50 hover:text-brand-blue">Lowongan Pekerjaan</a>
                </div>
            </div>

            {{-- LINK: INFORMASI --}}
            <a href="{{ route('informasi') }}"
                class="px-3 py-1 text-sm transition-colors
                {{ request()->routeIs('informasi*') 
                    ? 'font-semibold bg-white/20 rounded-full' 
                    : 'font-medium hover:text-blue-200' }}">
                Informasi
            </a>

            <a href="{{ url('/#ppdb') }}" class="px-3 py-1 text-sm font-medium hover:text-blue-200 transition-colors">PPDB</a>
        </nav>

        <!-- Mobile Hamburger Menu Button -->
        <button id="mobile-menu-btn" type="button" aria-controls="mobile-menu" aria-expanded="false"
            class="lg:hidden text-slate-700 hover:text-brand-blue text-2xl p-2 focus:outline-none">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>

    <!-- Mobile Navigation Menu -->
    <div id="mobile-menu" class="hidden lg:hidden bg-white border-b border-slate-200 px-6 py-4 transition-all">
        <div class="flex flex-col gap-3 font-semibold text-slate-700">
            
            {{-- LINK: PROFIL (ACTIVE STATE MOBILE) --}}
            <a href="{{ route('profil') }}" 
                class="py-1 transition-colors
                {{ request()->routeIs('profil') 
                    ? 'text-brand-blue font-bold' 
                    : 'hover:text-brand-blue' }}">
                Profile
            </a>

            <a href="#" class="hover:text-brand-blue py-1">Kesiswaan</a>
            <a href="{{ route('produk-unggulan') }}" 
                class="py-1 transition-colors
                {{ request()->routeIs('produk-unggulan*') 
                    ? 'text-brand-blue font-bold' 
                    : 'hover:text-brand-blue' }}">
                Produk Unggulan
            </a>
            @if ($navJurusan->isNotEmpty())
                <div class="pl-4 flex flex-col gap-1 text-sm font-normal text-slate-600">
                    @foreach ($navJurusan as $jurusan)
                        <a href="{{ route('produk-unggulan') }}#jurusan-{{ $jurusan->jurusanID }}" class="hover:text-brand-blue py-0.5">{{ $jurusan->nama }}</a>
                    @endforeach
                </div>
            @endif
            <a href="{{ route('layanan-peminjaman') }}" 
                class="py-1 transition-colors
                {{ request()->routeIs('layanan-peminjaman') 
                    ? 'text-brand-blue font-bold' 
                    : 'hover:text-brand-blue' }}">
                Peminjaman Aula
            </a>
            <a href="{{ route('pkl-bkk') }}" class="hover:text-brand-blue py-1">PKL & BKK</a>
            <a href="{{ route('informasi') }}" 
                class="py-1 transition-colors
                {{ request()->routeIs('informasi*') 
                    ? 'text-brand-blue font-bold' 
                    : 'hover:text-brand-blue' }}">
                Informasi
            </a>
            <a href="{{ url('/#ppdb') }}" class="hover:text-brand-blue py-1">PPDB 2026</a>
        </div>
    </div>
</header>
