@extends('Public.layout.app')

@section('title', 'Artikel & Informasi SKANDAKRA - SMK Negeri 2 Karanganyar')

@section('content')

    <!-- MAIN HEADER SECTION / HERO ARTIKEL -->
    <section class="relative pt-10 sm:pt-14 pb-8 overflow-hidden">
        <div class="absolute top-4 left-6 w-28 h-28 dot-pattern opacity-40 pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-10">
                <span class="inline-block bg-blue-100 text-brand-blue text-xs font-extrabold px-3.5 py-1.5 rounded-full uppercase tracking-wider mb-3">
                    Portal Berita & Edukasi
                </span>
                <h1 class="text-4xl sm:text-5xl font-black text-slate-900 tracking-tight leading-tight">
                    Artikel <span class="text-brand-blue">SKANDAKRA</span>
                </h1>
                <p class="text-slate-500 text-xs sm:text-sm mt-2">Dapatkan warta kegiatan, kabar prestasi, dan wawasan edukatif terkini dari SMK Negeri 2 Karanganyar.</p>
            </div>

            <!-- Banner Card Utama -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 card-shadow border border-slate-100 relative overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    
                    <!-- Gambar Sekolah -->
                    <div class="lg:col-span-7">
                        <div class="relative rounded-2xl overflow-hidden shadow-lg border border-slate-200 group">
                            <img src="{{ asset('assets/Sejarah.jpg') }}" alt="Gerbang SMK Negeri 2 Karanganyar"
                                class="w-full h-64 sm:h-80 md:h-96 object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent"></div>
                            <span class="absolute bottom-4 left-4 bg-brand-blue text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow">
                                SMKN 2 KARANGANYAR
                            </span>
                        </div>
                    </div>

                    <!-- Teks Deskripsi Pembuka -->
                    <div class="lg:col-span-5 space-y-5">
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-snug">
                            Membangun Karakter Unggul & Berteknologi
                        </h2>
                        <p class="text-slate-600 text-sm sm:text-base leading-relaxed">
                            Membangun karakter unggul melalui integrasi nilai moral dan penguasaan teknologi. Kami berdedikasi untuk membina potensi setiap siswa dalam lingkungan yang inklusif, inovatif, dan disiplin.
                        </p>
                        <div class="pt-2 flex items-center gap-4">
                            <a href="#daftar-artikel"
                                class="bg-brand-blue hover:bg-brand-darkBlue text-white text-xs sm:text-sm font-semibold px-6 py-3 rounded-full shadow-md shadow-blue-500/20 transition-all inline-flex items-center gap-2">
                                Jelajahi Artikel <i class="fa-solid fa-arrow-down text-xs"></i>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- DAFTAR ARTIKEL TERBARU -->
    <section id="daftar-artikel" class="py-12 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Section Header & Filter -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        Artikel Terbaru
                    </h3>
                    <p class="text-slate-500 text-xs sm:text-sm">Informasi, prestasi, dan wawasan seputar kegiatan SKANDAKRA.</p>
                </div>

                <!-- Category Pills Filter & Search -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                    <!-- Search Input -->
                    <form action="{{ route('informasi') }}#daftar-artikel" method="GET" class="relative w-full sm:w-64">
                        @if($currentKategori)
                            <input type="hidden" name="kategori" value="{{ $currentKategori }}">
                        @endif
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari informasi..."
                            class="w-full bg-slate-50 border border-slate-200 rounded-full pl-9 pr-4 py-2 text-xs text-slate-700 focus:outline-none focus:border-brand-blue transition">
                    </form>

                    <!-- Category Pills -->
                    <div class="flex items-center gap-2 overflow-x-auto max-w-full pb-2 sm:pb-0">
                        <a href="{{ route('informasi') }}#daftar-artikel"
                            class="{{ !$currentKategori ? 'bg-brand-blue text-white shadow-sm' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }} text-xs font-bold px-4 py-2 rounded-full whitespace-nowrap transition-colors">
                            Semua
                        </a>
                        @foreach($kategoris as $kat)
                            <a href="{{ route('informasi', ['kategori' => $kat->slug, 'search' => $search]) }}#daftar-artikel"
                                class="{{ $currentKategori === $kat->slug ? 'bg-brand-blue text-white shadow-sm' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }} text-xs font-semibold px-4 py-2 rounded-full whitespace-nowrap transition-colors">
                                {{ $kat->nama }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Grid Artikel -->
            @if($artikels->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($artikels as $artikel)
                        <article class="bg-white rounded-2xl overflow-hidden border border-slate-200/90 card-shadow hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between group">
                            <div>
                                <div class="relative h-48 overflow-hidden bg-slate-100">
                                    <a href="{{ route('informasi.show', $artikel->slug) }}" class="block w-full h-full">
                                        <img src="{{ $artikel->gambar ? asset('storage/' . $artikel->gambar) : asset('assets/Sejarah.jpg') }}"
                                            alt="{{ $artikel->judul }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    </a>
                                    <span class="absolute top-3 left-3 bg-brand-blue text-white text-[10px] font-extrabold px-2.5 py-1 rounded-md uppercase tracking-wider shadow-xs">
                                        {{ $artikel->kategori->nama ?? 'Informasi' }}
                                    </span>
                                </div>
                                <div class="p-5 space-y-3">
                                    <div class="flex items-center gap-2 text-slate-400 text-xs font-medium">
                                        <i class="fa-regular fa-calendar"></i>
                                        <span>{{ $artikel->published_at ? $artikel->published_at->translatedFormat('d F Y') : $artikel->created_at->translatedFormat('d F Y') }}</span>
                                    </div>
                                    <h4 class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-brand-blue transition-colors line-clamp-2 leading-snug">
                                        <a href="{{ route('informasi.show', $artikel->slug) }}">
                                            {{ $artikel->judul }}
                                        </a>
                                    </h4>
                                    <p class="text-slate-600 text-xs leading-relaxed line-clamp-3">
                                        {{ $artikel->ringkasan ?: Str::limit(strip_tags($artikel->konten), 120) }}
                                    </p>
                                </div>
                            </div>
                            <div class="px-5 pb-5 pt-2">
                                <a href="{{ route('informasi.show', $artikel->slug) }}"
                                    class="inline-flex items-center text-brand-blue text-xs font-bold hover:translate-x-1 transition-transform">
                                    Baca Selengkapnya <i class="fa-solid fa-chevron-right text-[10px] ml-1.5"></i>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-12 flex justify-center">
                    {{ $artikels->links() }}
                </div>
            @else
                <div class="text-center py-16 bg-slate-50/70 rounded-3xl border border-dashed border-slate-200">
                    <div class="w-16 h-16 rounded-2xl bg-blue-50 text-brand-blue flex items-center justify-center mx-auto mb-3 text-2xl shadow-xs">
                        <i class="fa-regular fa-newspaper"></i>
                    </div>
                    <h4 class="font-extrabold text-slate-800 text-base">Belum Ada Informasi</h4>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1">
                        @if($search || $currentKategori)
                            Tidak ditemukan artikel yang sesuai dengan kriteria filter atau pencarian Anda.
                            <div class="mt-3">
                                <a href="{{ route('informasi') }}#daftar-artikel" class="text-xs font-bold text-brand-blue hover:underline">
                                    Reset Filter
                                </a>
                            </div>
                        @else
                            Belum ada artikel atau informasi yang dipublikasikan saat ini.
                        @endif
                    </p>
                </div>
            @endif

        </div>
    </section>

@endsection
