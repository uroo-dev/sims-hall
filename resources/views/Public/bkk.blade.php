@extends('Public.layout.app')

@section('title', 'Bursa Kerja Khusus (BKK) — SMK Negeri 2 Karanganyar')

@section('content')
    {{-- ============================================================
         HERO SECTION: BURSA KERJA KHUSUS (BKK)
         ============================================================ --}}
    <section id="hero" class="relative py-16 md:py-24 overflow-hidden bg-white">
        <!-- Abstract Background Shapes -->
        <div class="absolute top-0 right-0 w-1/3 h-2/3 bg-blue-50 rounded-bl-[10rem] -z-10 opacity-70"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-100 rounded-full -z-10 opacity-50 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

                <!-- LEFT: Text Content -->
                <div class="lg:col-span-6 space-y-6 z-10">
                    <span class="inline-block text-brand-blue font-bold tracking-wide text-xs sm:text-sm uppercase bg-blue-50 px-3.5 py-1.5 rounded-full border border-blue-100">
                        BKK SMKN 2 Karanganyar – Pusat Karir &amp; Alumni
                    </span>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-slate-900 tracking-tight leading-[1.1]">
                        Bursa Kerja Khusus<br />
                        <span class="text-brand-blue">SMKN 2 Karanganyar</span>
                    </h1>

                    <p class="text-slate-600 text-base sm:text-lg leading-relaxed max-w-xl">
                        Menjembatani lulusan dan alumni dengan peluang kerja profesional di berbagai industri unggulan, rekrutmen langsung, dan bimbingan karir masa depan.
                    </p>

                    <div class="flex flex-wrap gap-4 pt-4">
                        <a href="#lowongan"
                            class="bg-brand-blue hover:bg-brand-darkBlue text-white font-bold px-8 py-3.5 rounded-xl text-sm transition-all shadow-lg shadow-blue-500/30 flex items-center gap-2 transform hover:-translate-y-0.5 active:translate-y-0">
                            Cari Lowongan Kerja <i class="fa-solid fa-arrow-down text-xs"></i>
                        </a>
                        <a href="{{ route('pkl') }}"
                            class="bg-white border-2 border-slate-200 hover:border-brand-blue text-slate-700 hover:text-brand-blue font-bold px-8 py-3.5 rounded-xl text-sm transition-all shadow-sm flex items-center gap-2">
                            Informasi PKL <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>

                <!-- RIGHT: Visual Composition (Hiring Posters from prototype) -->
                <div class="lg:col-span-6 relative mt-12 lg:mt-0 flex justify-center lg:justify-end">
                    <div class="relative w-full max-w-[500px] aspect-[4/3]">

                        <!-- Main Poster 1 -->
                        <div class="absolute top-0 right-0 w-[72%] bg-gradient-to-br from-blue-900 to-indigo-900 rounded-2xl overflow-hidden shadow-2xl z-10 p-5 text-white border-4 border-white flex flex-col justify-center h-64">
                            <span class="inline-block bg-blue-600 text-white text-[10px] font-bold px-2.5 py-1 rounded mb-3 uppercase tracking-wider w-fit">
                                WE ARE HIRING
                            </span>
                            <h4 class="text-xl sm:text-2xl font-black leading-tight mb-2">
                                PT INDACO WARNA DUNIA
                            </h4>
                            <p class="text-xs text-blue-200 mb-4">Operator Produksi, Operator Gudang Bahan Baku, Maintenance Teknik</p>
                            <div class="mt-auto flex items-center justify-between">
                                <span class="text-[10px] bg-white/20 px-2.5 py-1 rounded font-semibold">Tersedia Berbagai Posisi</span>
                                <i class="fa-solid fa-briefcase text-blue-300"></i>
                            </div>
                        </div>

                        <!-- Main Poster 2 -->
                        <div class="absolute bottom-0 left-0 w-[64%] bg-gradient-to-br from-emerald-800 to-teal-900 rounded-2xl overflow-hidden shadow-2xl z-20 p-4 text-white border-4 border-white h-48 flex flex-col justify-center">
                            <div class="text-[10px] font-semibold text-emerald-300 mb-1">PT. SCA (Agung Tex Group)</div>
                            <h4 class="text-lg font-black italic tracking-wide text-amber-300 mb-2">
                                MEMBUTUHKAN SEGERA
                            </h4>
                            <p class="text-[10px] text-emerald-100">Staff Bagian Produksi, Staff Bagian Pemasaran, Operator Tenun</p>
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
         KONTEN: LOWONGAN KERJA
         ============================================================ --}}
    <section id="lowongan" class="py-16 bg-[#F8FAFC] relative overflow-hidden">
        <!-- Dot Pattern Bottom Right -->
        <div class="absolute bottom-10 right-10 w-32 h-32 dot-pattern opacity-40 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            <!-- Header -->
            <div class="text-center max-w-3xl mx-auto mb-10">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-3 tracking-tight">
                    Lowongan Kerja
                </h2>
                <p class="text-slate-500 text-sm sm:text-base leading-relaxed">
                    Kesempatan karir eksklusif bagi alumni dan siswa tingkat akhir SMKN 2 Karanganyar dari mitra industri terpercaya.
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

            <!-- Grid Lowongan -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="lowongan-grid">
                @forelse ($lowongans as $lowongan)
                    @php
                        // Deteksi kategori jurusan untuk filter
                        $jurusanText = strtolower($lowongan->jurusan_sesuai . ' ' . $lowongan->posisi . ' ' . ($lowongan->dudi?->nama_dudi ?? $lowongan->nama_perusahaan));
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

                        // Icon styling per kategori
                        $iconClass = 'fa-briefcase';
                        $iconBg = 'bg-blue-100 border-blue-200 text-brand-blue';
                        $badgeBg = 'bg-blue-600 text-white';

                        if ($category === 'mesin') {
                            $iconClass = 'fa-wrench';
                            $iconBg = 'bg-blue-100 border-blue-200 text-brand-blue';
                            $badgeBg = 'bg-blue-600 text-white';
                        } elseif ($category === 'kain') {
                            $iconClass = 'fa-user-tie';
                            $iconBg = 'bg-amber-100 border-amber-200 text-amber-700';
                            $badgeBg = 'bg-amber-600 text-white';
                        } elseif ($category === 'ototronik') {
                            $iconClass = 'fa-car';
                            $iconBg = 'bg-red-100 border-red-200 text-red-600';
                            $badgeBg = 'bg-red-600 text-white';
                        } elseif ($category === 'rpl') {
                            $iconClass = 'fa-code';
                            $iconBg = 'bg-emerald-100 border-emerald-200 text-emerald-600';
                            $badgeBg = 'bg-emerald-600 text-white';
                        }
                    @endphp

                    <div class="lowongan-card bg-white border border-slate-200 rounded-3xl p-6 flex flex-col justify-between shadow-sm hover:shadow-lg transition-all"
                        data-category="{{ $category }}">
                        <div>
                            <div class="flex items-start gap-4 mb-4">
                                <div class="w-14 h-14 rounded-2xl {{ $iconBg }} flex items-center justify-center shrink-0 border overflow-hidden p-2 shadow-sm bg-white">
                                    @if ($lowongan->logo_url)
                                        <img src="{{ $lowongan->logo_url }}" alt="Logo {{ $lowongan->nama_perusahaan }}" class="w-full h-full object-contain">
                                    @else
                                        <i class="fa-solid {{ $iconClass }} text-2xl"></i>
                                    @endif
                                </div>
                                <div class="overflow-hidden">
                                    <span class="inline-block {{ $badgeBg }} text-[10px] font-bold px-2.5 py-0.5 rounded mb-1 uppercase tracking-wider">
                                        {{ $lowongan->jurusan_sesuai }}
                                    </span>
                                    <h3 class="text-base font-extrabold text-slate-900 truncate" title="{{ $lowongan->posisi }}">
                                        {{ $lowongan->posisi }}
                                    </h3>
                                    <p class="text-xs font-semibold text-slate-500 truncate">
                                        {{ $lowongan->dudi?->nama_dudi ?? $lowongan->nama_perusahaan }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 text-xs text-slate-500 mb-4">
                                <i class="fa-solid fa-location-dot text-brand-blue"></i>
                                <span>{{ $lowongan->dudi?->kota ?? 'Karanganyar, Jawa Tengah' }}</span>
                            </div>

                            <div class="grid grid-cols-3 gap-2 mb-6 text-center">
                                <div class="bg-slate-50 rounded-xl p-2 border border-slate-100">
                                    <span class="block text-[9px] text-slate-400 font-bold uppercase">Pendidikan</span>
                                    <span class="block text-[10px] font-bold text-slate-700 truncate">
                                        {{ $lowongan->jurusan_sesuai === \App\Models\Lowongan::SEMUA_JURUSAN ? 'Semua Jurusan' : 'SMK / ' . $lowongan->jurusan_sesuai }}
                                    </span>
                                </div>
                                <div class="bg-slate-50 rounded-xl p-2 border border-slate-100">
                                    <span class="block text-[9px] text-slate-400 font-bold uppercase">Tipe</span>
                                    <span class="block text-[10px] font-bold text-slate-700 truncate">
                                        {{ $lowongan->tipe }}
                                    </span>
                                </div>
                                <div class="bg-slate-50 rounded-xl p-2 border border-slate-100">
                                    <span class="block text-[9px] text-slate-400 font-bold uppercase">Batas</span>
                                    <span class="block text-[10px] font-bold text-slate-700 truncate">
                                        {{ $lowongan->deadline->locale('id')->translatedFormat('d M Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('bkk.detail', $lowongan->id) }}"
                            class="w-full text-center block bg-white border border-blue-200 text-brand-blue hover:bg-brand-blue hover:text-white font-bold text-xs py-2.5 rounded-lg transition-colors shadow-sm">
                            Lihat Detail
                        </a>
                    </div>
                @empty
                    <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-12 bg-white rounded-3xl border border-slate-200">
                        <i class="fa-solid fa-briefcase text-slate-300 text-4xl mb-3"></i>
                        <p class="text-slate-500 font-medium">Belum ada lowongan yang aktif saat ini.</p>
                    </div>
                @endforelse
            </div>

            <!-- Empty filter alert -->
            <div id="no-filter-match" class="hidden text-center py-12 bg-white rounded-3xl border border-slate-200 mt-6">
                <i class="fa-solid fa-filter text-slate-300 text-3xl mb-3"></i>
                <p class="text-slate-500 font-medium">Tidak ada lowongan kerja untuk kategori keahlian ini.</p>
            </div>

        </div>
    </section>

    {{-- Interactive Category Filter Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const buttons = document.querySelectorAll('.filter-btn');
            const cards = document.querySelectorAll('.lowongan-card');
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
