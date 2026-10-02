@php
    $navJurusan = $navJurusan ?? collect();
@endphp

<!-- HEADER & NAVIGATION -->
<header class="sticky top-0 z-40 bg-white/90 backdrop-blur-md transition-all duration-300 border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
        <!-- Logo Section -->
        <a href="{{ route('home') }}" class="flex items-center gap-3 group">
            <div class="w-12 h-12 flex items-center justify-center rounded-lg p-1 group-hover:scale-105 transition-transform">
                <div class="relative w-full h-full flex items-center justify-center transition-transform duration-300 group-hover:scale-105">
                    @if (file_exists(public_path('assets/logosmkk.png')))
                        <img src="{{ asset('assets/logosmkk.png') }}" alt="Logo SMK Negeri 2 Karanganyar"
                            class="w-full h-full object-contain drop-shadow-xl select-none">
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
            <a href="{{ route('home') }}#produk" class="px-3 py-1 text-sm font-medium hover:text-blue-200 transition-colors">Profile</a>

            <!-- Dropdown Peminjaman Aula -->
            <div class="relative group">
                <button class="px-3 py-1 text-sm font-medium hover:text-blue-200 transition-colors inline-flex items-center gap-1">
                    Peminjaman Aula <i class="fa-solid fa-chevron-down text-xs transition-transform group-hover:rotate-180"></i>
                </button>
                <div class="absolute left-0 mt-2 w-48 bg-white text-slate-800 rounded-xl shadow-xl py-2 opacity-0 group-hover:opacity-100 pointer-events-none group-hover:pointer-events-auto transition-all duration-200 border border-slate-100">
                    <span class="block px-4 py-2 text-xs font-semibold text-slate-400">Segera tersedia</span>
                </div>
            </div>

            <!-- Dropdown Kesiswaan -->
            <div class="relative group">
                <button class="px-3 py-1 text-sm font-medium hover:text-blue-200 transition-colors inline-flex items-center gap-1">
                    Kesiswaan <i class="fa-solid fa-chevron-down text-xs transition-transform group-hover:rotate-180"></i>
                </button>
                <div class="absolute left-0 mt-2 w-48 bg-white text-slate-800 rounded-xl shadow-xl py-2 opacity-0 group-hover:opacity-100 pointer-events-none group-hover:pointer-events-auto transition-all duration-200 border border-slate-100">
                    <span class="block px-4 py-2 text-xs font-semibold text-slate-400">Segera tersedia</span>
                </div>
            </div>

            <!-- Dropdown Produk Unggulan -->
            <div class="relative group">
                <button class="px-3 py-1 text-sm font-semibold bg-white/20 rounded-full transition-colors inline-flex items-center gap-1">
                    Produk Unggulan <i class="fa-solid fa-chevron-down text-xs transition-transform group-hover:rotate-180"></i>
                </button>
                <div class="absolute left-0 mt-2 w-56 bg-white text-slate-800 rounded-xl shadow-xl py-2 opacity-0 group-hover:opacity-100 pointer-events-none group-hover:pointer-events-auto transition-all duration-200 border border-slate-100">
                    @forelse ($navJurusan as $jurusan)
                        <a href="{{ route('home') }}#jurusan-{{ $jurusan->jurusanID }}"
                            class="block px-4 py-2 text-xs font-semibold hover:bg-slate-50 hover:text-brand-blue">
                            {{ $jurusan->nama }}
                        </a>
                    @empty
                        <span class="block px-4 py-2 text-xs font-semibold text-slate-400">Belum ada jurusan</span>
                    @endforelse
                </div>
            </div>

            <!-- Dropdown PKL & BKK -->
            <div class="relative group">
                <button class="px-3 py-1 text-sm font-medium hover:text-blue-200 transition-colors inline-flex items-center gap-1">
                    PKL &amp; BKK <i class="fa-solid fa-chevron-down text-xs transition-transform group-hover:rotate-180"></i>
                </button>
                <div class="absolute left-0 mt-2 w-48 bg-white text-slate-800 rounded-xl shadow-xl py-2 opacity-0 group-hover:opacity-100 pointer-events-none group-hover:pointer-events-auto transition-all duration-200 border border-slate-100">
                    <a href="{{ route('home') }}#mitra" class="block px-4 py-2 text-xs font-semibold hover:bg-slate-50 hover:text-brand-blue">Mitra DUDI</a>
                </div>
            </div>

            <a href="{{ route('home') }}#ppdb" class="px-3 py-1 text-sm font-medium hover:text-blue-200 transition-colors">PPDB</a>
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
            <a href="{{ route('home') }}#produk" class="hover:text-brand-blue py-1 text-brand-blue">Produk Unggulan</a>
            <a href="{{ route('home') }}#mitra" class="hover:text-brand-blue py-1">Mitra DUDI</a>
            @foreach ($navJurusan as $jurusan)
                <a href="{{ route('home') }}#jurusan-{{ $jurusan->jurusanID }}" class="hover:text-brand-blue py-1">{{ $jurusan->nama }}</a>
            @endforeach
        </div>
    </div>
</header>
