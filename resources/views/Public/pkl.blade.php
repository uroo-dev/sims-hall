@extends('Public.layout.app')

@section('title', 'Praktik Kerja Lapangan (PKL) — SMK Negeri 2 Karanganyar')
@section('meta_description', 'Informasi Praktik Kerja Lapangan (PKL) dan daftar kemitraan Dunia Usaha & Dunia Industri (DUDI) SMK Negeri 2 Karanganyar.')
@section('meta_keywords', 'PKL SMKN 2 Karanganyar, Praktik Kerja Lapangan SMK, Mitra DUDI, Magang SMK Karanganyar')

@section('content')
    {{-- ============================================================
         HERO SECTION: PRAKTIK KERJA LAPANGAN (PKL)
         ============================================================ --}}
    <section id="hero" class="relative py-16 md:py-24 overflow-hidden bg-white">
        <!-- Abstract Background Shapes -->
        <div class="absolute top-0 right-0 w-1/3 h-2/3 bg-blue-50 rounded-bl-[10rem] -z-10 opacity-70"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-100 rounded-full -z-10 opacity-50 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

                <!-- LEFT: Text Content -->
                <div class="lg:col-span-7 space-y-6 z-10">
                    <span class="inline-block text-brand-blue font-bold tracking-wide text-xs sm:text-sm uppercase bg-blue-50 px-3.5 py-1.5 rounded-full border border-blue-100">
                        SMK 2 Karanganyar – Sekolah Pusat Keunggulan
                    </span>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-slate-900 tracking-tight leading-[1.1]">
                        Praktik Kerja Lapangan<br />
                        <span class="text-brand-blue">SMKN 2 Karanganyar</span>
                    </h1>

                    <p class="text-slate-600 text-base sm:text-lg leading-relaxed max-w-xl">
                        Mempersiapkan tenaga kerja profesional masa depan melalui kemitraan strategis dengan industri global dan program magang bersertifikasi serta berkualitas tinggi.
                    </p>

                    <div class="flex flex-wrap gap-4 pt-4">
                        <a href="#mitra"
                            class="bg-brand-blue hover:bg-brand-darkBlue text-white font-bold px-8 py-3.5 rounded-xl text-sm transition-all shadow-lg shadow-blue-500/30 flex items-center gap-2 transform hover:-translate-y-0.5 active:translate-y-0">
                            Jelajahi Mitra DUDI <i class="fa-solid fa-arrow-down text-xs"></i>
                        </a>
                        <a href="#jurusan"
                            class="bg-white border-2 border-slate-200 hover:border-brand-blue text-slate-700 hover:text-brand-blue font-bold px-8 py-3.5 rounded-xl text-sm transition-all shadow-sm flex items-center gap-2">
                            Kompetensi Keahlian <i class="fa-solid fa-graduation-cap text-xs"></i>
                        </a>
                        <a href="{{ route('bkk') }}"
                            class="inline-flex items-center text-brand-blue font-bold text-sm px-4 py-3.5 hover:text-brand-darkBlue transition-colors gap-1.5">
                            Bursa Kerja Khusus (BKK) <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>

                <!-- RIGHT: Visual Composition -->
                <div class="lg:col-span-5 relative mt-8 lg:mt-0 flex justify-center lg:justify-end">
                    <div class="relative w-full max-w-[460px] aspect-[4/3]">
                        <!-- Main Card 1 -->
                        <div class="absolute top-0 right-0 w-[78%] bg-gradient-to-br from-blue-900 to-indigo-900 rounded-2xl overflow-hidden shadow-2xl z-10 p-6 text-white border-4 border-white flex flex-col justify-between h-64">
                            <div>
                                <span class="inline-block bg-blue-600 text-white text-[10px] font-bold px-2.5 py-1 rounded mb-3 uppercase tracking-wider">
                                    MITRA RESMI INDUSTRI
                                </span>
                                <h4 class="text-xl sm:text-2xl font-black leading-tight mb-2">
                                    {{ $rekap['total_dudi_resmi'] }} Mitra Resmi
                                </h4>
                                <p class="text-xs text-blue-200">Terhubung langsung dengan kurikulum industri dan penempatan magang siswa.</p>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-blue-100 pt-2 border-t border-white/10">
                                <i class="fa-solid fa-shield-check text-emerald-400"></i>
                                <span>Kurikulum Berbasis Industri</span>
                            </div>
                        </div>

                        <!-- Card 2: Siswa Magang -->
                        <div class="absolute bottom-0 left-0 w-[65%] bg-gradient-to-br from-emerald-800 to-teal-900 rounded-2xl overflow-hidden shadow-2xl z-20 p-5 text-white border-4 border-white h-48 flex flex-col justify-between">
                            <div>
                                <div class="text-[10px] font-semibold text-emerald-300 mb-1">Status Penempatan Siswa</div>
                                <h4 class="text-lg font-black tracking-wide text-amber-300">
                                    {{ $rekap['siswa_fix'] }} Siswa FIX
                                </h4>
                                <p class="text-[11px] text-emerald-100 mt-1">
                                    @if ($rekap['siswa_fix'] > 0)
                                        Siswa telah ditempatkan pada mitra industri yang tampil di halaman ini.
                                    @else
                                        Belum ada siswa yang berstatus FIX pada periode berjalan.
                                    @endif
                                </p>
                            </div>
                            <div class="text-[10px] text-emerald-200 flex items-center gap-1.5">
                                @if ($rekap['siswa_fix'] > 0)
                                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Penempatan Berjalan
                                @else
                                    Periode Belum Berjalan
                                @endif
                            </div>
                        </div>

                        <!-- Abstract decorative shapes -->
                        <div class="absolute -top-6 -left-6 w-24 h-24 dot-pattern opacity-50 z-0"></div>
                        <div class="absolute -bottom-8 -right-8 w-32 h-32 border-[16px] border-brand-blue rounded-full opacity-20 z-0"></div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ============================================================
         KONTEN: KOMPETENSI KEAHLIAN (Diambil dari prototype pkl_bkk.html)
         ============================================================ --}}
    <section id="jurusan" class="py-16 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-4 tracking-tight">
                    Kompetensi Keahlian
                </h2>
                <p class="text-slate-600 text-base sm:text-lg">
                    Beragam kompetensi keahlian berbasis teknologi dan industri yang membekali siswa dengan keterampilan
                    profesional sesuai kebutuhan dunia kerja.
                </p>
            </div>

            <!-- 4 Vertical Jurusan Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 pt-12 mb-8">

                <!-- Card 1: Teknik Pemesinan (Blue) -->
                <div class="relative group cursor-pointer pt-16">
                    <div
                        class="bg-[#5FB0FF] rounded-b-3xl rounded-t-[40px] p-6 text-slate-900 flex flex-col justify-between shadow-xl shadow-blue-500/20 group-hover:-translate-y-3 transition-all duration-300 relative z-10 min-h-[380px]">
                        <div class="absolute -top-16 left-0 right-0 flex justify-center pointer-events-none">
                            <img src="{{ asset('assets/logo_mesin.png') }}" alt="Teknik Pemesinan"
                                class="w-52 h-auto object-contain filter drop-shadow-xl group-hover:scale-110 group-hover:-translate-y-2 transition-all duration-300 transform-gpu">
                        </div>
                        <div class="mt-28 space-y-3">
                            <h3 class="text-2xl font-bold tracking-tight text-slate-900 leading-tight">
                                Teknik<br />Pemesinan
                            </h3>
                            <p class="text-slate-800/90 text-sm font-medium leading-relaxed">
                                Mempelajari tentang cara memproduksi barang teknik dan menggunakan mesin perkakas presisi.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Teknik Pembuatan Kain (Yellow) -->
                <div class="relative group cursor-pointer pt-16">
                    <div
                        class="bg-[#FCE055] rounded-b-3xl rounded-t-[40px] p-6 text-slate-900 flex flex-col justify-between shadow-xl shadow-amber-500/20 group-hover:-translate-y-3 transition-all duration-300 relative z-10 min-h-[380px]">
                        <div class="absolute -top-16 left-0 right-0 flex justify-center pointer-events-none">
                            <img src="{{ asset('assets/logo_tekstil.png') }}" alt="Teknik Pembuatan Kain"
                                class="w-52 h-auto object-contain filter drop-shadow-xl group-hover:scale-110 group-hover:-translate-y-2 transition-all duration-300 transform-gpu">
                        </div>
                        <div class="mt-28 space-y-3">
                            <h3 class="text-2xl font-bold tracking-tight text-slate-900 leading-tight">
                                Teknik<br />Pembuatan Kain
                            </h3>
                            <p class="text-slate-800/90 text-sm font-medium leading-relaxed">
                                Mempelajari tentang desain tenun, mesin pembuatan kain, perawatan, dan pengendalian mutunya.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Teknik Ototronik (Red/Coral) -->
                <div class="relative group cursor-pointer pt-16">
                    <div
                        class="bg-[#FF5A5F] rounded-b-3xl rounded-t-[40px] p-6 text-slate-900 flex flex-col justify-between shadow-xl shadow-red-500/20 group-hover:-translate-y-3 transition-all duration-300 relative z-10 min-h-[380px]">
                        <div class="absolute -top-16 left-0 right-0 flex justify-center pointer-events-none">
                            <img src="{{ asset('assets/logo_oto.png') }}" alt="Teknik Ototronik"
                                class="w-52 h-auto object-contain filter drop-shadow-xl group-hover:scale-110 group-hover:-translate-y-2 transition-all duration-300 transform-gpu">
                        </div>
                        <div class="mt-28 space-y-3">
                            <h3 class="text-2xl font-bold tracking-tight text-slate-900 leading-tight">
                                Teknik<br />Ototronik
                            </h3>
                            <p class="text-slate-900/90 text-sm font-medium leading-relaxed">
                                Mempelajari otomotif modern dalam penguasaan teknologi kontrol elektronik kendaraan bermotor.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Rekayasa Perangkat Lunak (Green) -->
                <div class="relative group cursor-pointer pt-16">
                    <div
                        class="bg-[#10C863] rounded-b-3xl rounded-t-[40px] p-6 text-slate-900 flex flex-col justify-between shadow-xl shadow-emerald-500/20 group-hover:-translate-y-3 transition-all duration-300 relative z-10 min-h-[380px]">
                        <div class="absolute -top-16 left-0 right-0 flex justify-center pointer-events-none">
                            <img src="{{ asset('assets/logo_rpl.png') }}" alt="Rekayasa Perangkat Lunak"
                                class="w-52 h-auto object-contain filter drop-shadow-xl group-hover:scale-110 group-hover:-translate-y-2 transition-all duration-300 transform-gpu">
                        </div>
                        <div class="mt-28 space-y-3">
                            <h3 class="text-2xl font-bold tracking-tight text-slate-900 leading-tight">
                                Rekayasa<br />Perangkat Lunak
                            </h3>
                            <p class="text-slate-900/90 text-sm font-medium leading-relaxed">
                                Mempelajari tentang pengembangan aplikasi, pemrograman web, mobile, basis data, dan cloud.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ============================================================
         KONTEN: MITRA DUDI & PKL
         ============================================================ --}}
    <section id="mitra" class="py-16 bg-[#F8FAFC] relative">
        <!-- Dot Pattern Top Left -->
        <div class="absolute top-8 left-8 w-24 h-24 dot-pattern opacity-40 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            <!-- Header -->
            <div class="text-center max-w-3xl mx-auto mb-10">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-3 tracking-tight">
                    Mitra Dudi &amp; PKL
                </h2>
                <p class="text-slate-500 text-sm sm:text-base leading-relaxed">
                    Kami bekerja sama dengan perusahaan nasional dan multinasional untuk memastikan siswa mendapatkan pengalaman kerja nyata yang relevan.
                </p>
            </div>

            <!-- Filter Buttons -->
            <div class="flex flex-wrap justify-center gap-2 sm:gap-3 mb-10" id="filter-buttons">
                <button type="button" data-filter="all"
                    class="filter-btn active-filter bg-brand-blue text-white font-semibold text-xs px-5 py-2.5 rounded-lg transition-colors shadow-sm">
                    Semua Jurusan
                </button>
                <button type="button" data-filter="mesin"
                    class="filter-btn bg-white border border-blue-200 text-brand-blue hover:bg-blue-50 font-semibold text-xs px-5 py-2.5 rounded-lg transition-colors shadow-sm">
                    Teknik Permesinan
                </button>
                <button type="button" data-filter="kain"
                    class="filter-btn bg-white border border-blue-200 text-brand-blue hover:bg-blue-50 font-semibold text-xs px-5 py-2.5 rounded-lg transition-colors shadow-sm">
                    Teknik Pembuatan Kain
                </button>
                <button type="button" data-filter="ototronik"
                    class="filter-btn bg-white border border-blue-200 text-brand-blue hover:bg-blue-50 font-semibold text-xs px-5 py-2.5 rounded-lg transition-colors shadow-sm">
                    Teknik Ototronik
                </button>
                <button type="button" data-filter="rpl"
                    class="filter-btn bg-white border border-blue-200 text-brand-blue hover:bg-blue-50 font-semibold text-xs px-5 py-2.5 rounded-lg transition-colors shadow-sm">
                    Rekayasa Perangkat Lunak
                </button>
            </div>

            <!-- Grid Mitra DUDI -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="dudi-grid">
                @forelse ($dudis as $dudi)
                    @php
                        // Deteksi kategori jurusan untuk filter
                        $jurusanText = strtolower(($dudi->jurusan?->nama ?? '') . ' ' . $dudi->bidang_usaha . ' ' . $dudi->nama_dudi);
                        $category = 'all';
                        if (str_contains($jurusanText, 'mesin')) {
                            $category = 'mesin';
                        } elseif (str_contains($jurusanText, 'kain') || str_contains($jurusanText, 'tekstil') || str_contains($jurusanText, 'textile')) {
                            $category = 'kain';
                        } elseif (str_contains($jurusanText, 'ototronik') || str_contains($jurusanText, 'otomotif') || str_contains($jurusanText, 'motor') || str_contains($jurusanText, 'toyota')) {
                            $category = 'ototronik';
                        } elseif (str_contains($jurusanText, 'perangkat lunak') || str_contains($jurusanText, 'rpl') || str_contains($jurusanText, 'web') || str_contains($jurusanText, 'it') || str_contains($jurusanText, 'software')) {
                            $category = 'rpl';
                        }

                    @endphp

                    <div class="dudi-card bg-white border border-slate-200 rounded-3xl p-6 flex flex-col justify-between shadow-sm hover:shadow-lg transition-all"
                        data-category="{{ $category }}">
                        <div>
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-16 h-16 rounded-full bg-blue-50 flex items-center justify-center shrink-0 border-2 border-blue-200 overflow-hidden p-1 shadow-sm">
                                    @if ($dudi->logo_url)
                                        <img src="{{ $dudi->logo_url }}" alt="Logo {{ $dudi->nama_dudi }}" class="w-full h-full object-contain">
                                    @else
                                        <span class="font-black text-brand-blue text-base">
                                            {{ strtoupper(substr($dudi->nama_dudi, 0, 3)) }}
                                        </span>
                                    @endif
                                </div>
                                <div class="overflow-hidden">
                                    <h3 class="text-lg font-extrabold text-slate-900 truncate" title="{{ $dudi->nama_dudi }}">
                                        {{ $dudi->nama_dudi }}
                                    </h3>
                                    <p class="text-xs font-semibold text-slate-500 truncate">
                                        {{ collect([$dudi->kota, $dudi->bidang_usaha])->filter()->implode(' • ') }}
                                    </p>
                                </div>
                            </div>

                            @if ($dudi->jurusan?->nama || $dudi->bidang_usaha)
                                <p class="text-slate-600 text-sm leading-relaxed mb-4">
                                    Mitra DUDI Jurusan
                                    <span class="font-bold text-brand-blue">
                                        {{ $dudi->jurusan?->nama ?? $dudi->bidang_usaha }}
                                    </span>
                                </p>
                            @endif

                            <div class="flex items-center justify-between text-xs text-slate-500 mb-6 bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                <span><i class="fa-solid fa-users text-brand-blue mr-1"></i> Kuota: <strong>{{ $dudi->kuota_maksimal }}</strong></span>
                                <span><i class="fa-solid fa-user-check text-emerald-600 mr-1"></i> Terisi: <strong>{{ $dudi->penempatan_fix_count ?? $dudi->penempatanFix()->count() }}</strong></span>
                            </div>
                        </div>

                        <a href="{{ route('pkl.detail', $dudi->id) }}"
                            class="w-full text-center block bg-white border border-blue-200 text-brand-blue hover:bg-brand-blue hover:text-white font-bold text-xs py-2.5 rounded-lg transition-colors shadow-sm">
                            Lihat Detail
                        </a>
                    </div>
                @empty
                    <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-12 bg-white rounded-3xl border border-slate-200">
                        <i class="fa-solid fa-building text-slate-300 text-4xl mb-3"></i>
                        <p class="text-slate-500 font-medium">Belum ada mitra DUDI yang ditampilkan.</p>
                    </div>
                @endforelse
            </div>

            <!-- Empty filter alert -->
            <div id="no-filter-match" class="hidden text-center py-12 bg-white rounded-3xl border border-slate-200 mt-6">
                <i class="fa-solid fa-filter text-slate-300 text-3xl mb-3"></i>
                <p class="text-slate-500 font-medium">Tidak ada mitra DUDI untuk kategori keahlian ini.</p>
            </div>

        </div>
    </section>

    {{-- Interactive Category Filter Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const buttons = document.querySelectorAll('.filter-btn');
            const cards = document.querySelectorAll('.dudi-card');
            const noMatch = document.getElementById('no-filter-match');

            buttons.forEach(button => {
                button.addEventListener('click', function () {
                    const filter = this.getAttribute('data-filter');

                    // Reset button styles
                    buttons.forEach(btn => {
                        btn.classList.remove('bg-brand-blue', 'text-white', 'active-filter');
                        btn.classList.add('bg-white', 'text-brand-blue', 'border', 'border-blue-200');
                    });

                    // Activate selected button
                    this.classList.remove('bg-white', 'text-brand-blue', 'border', 'border-blue-200');
                    this.classList.add('bg-brand-blue', 'text-white', 'active-filter');

                    let visibleCount = 0;
                    cards.forEach(card => {
                        const cat = card.getAttribute('data-category');
                        if (filter === 'all' || cat === filter) {
                            card.classList.remove('hidden');
                            visibleCount++;
                        } else {
                            card.classList.add('hidden');
                        }
                    });

                    if (noMatch) {
                        if (visibleCount === 0 && cards.length > 0) {
                            noMatch.classList.remove('hidden');
                        } else {
                            noMatch.classList.add('hidden');
                        }
                    }
                });
            });
        });
    </script>
@endsection
