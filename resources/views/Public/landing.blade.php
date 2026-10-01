@extends('Public.layout.app')

@section('content')
    <!-- HERO SECTION -->
    <section id="hero" class="relative py-12 md:py-20 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

                <!-- Left Text Content -->
                <div class="lg:col-span-6 space-y-6 z-10">
                    <span class="inline-block text-brand-blue font-bold tracking-wide text-base sm:text-lg">
                        Sekolah Pusat Keunggulan
                    </span>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-slate-900 tracking-tight leading-tight">
                        {{ $sekolah->profil_judul ?? 'SMKN 2' }}<br />
                        <span class="text-slate-800">{{ $sekolah->profil_judul ? '' : 'KARANGANYAR' }}</span>
                    </h1>
                    <p class="text-slate-600 text-base sm:text-lg leading-relaxed max-w-xl">
                        {{ $sekolah->profil_deskripsi ?? 'Sebagai Sekolah Pusat Keunggulan, kami berkomitmen menghadirkan siswa berkualitas dengan standar industri.' }}
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('profil') }}"
                            class="bg-brand-blue hover:bg-brand-darkBlue text-white font-semibold px-8 py-3.5 rounded-full shadow-lg shadow-blue-500/25 transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                            Pelajari Selengkapnya
                        </a>
                    </div>
                </div>

                <!-- Right Visual -->
                <div class="lg:col-span-6 relative flex justify-center items-center">
                    <div class="w-full max-w-xl flex items-center justify-center">
                        {{-- Gambar sekolah hanya dipakai kalau filenya benar-benar ada di
                             disk. Sebelumnya `profil_dokumentasi` menunjuk nama file yang
                             tidak ada (dokumentasi-3d.png), jadi browser merender <img>
                             404 dan video fallback tidak pernah tercapai. --}}
                        @if (! empty($sekolah->profil_dokumentasi) && is_file(public_path('assets/' . $sekolah->profil_dokumentasi)))
                            <img src="{{ asset('assets/' . $sekolah->profil_dokumentasi) }}" alt="Ilustrasi {{ $sekolah->profil_judul }}"
                                class="w-full h-auto object-contain select-none">
                        @else
                            {{-- Video ilustrasi sekolah, diputar terus-menerus.
                                 `muted` wajib ada, tanpa itu browser akan memblokir
                                 autoplay. `playsinline` mencegah iOS membuka video
                                 fullscreen. Logo dipakai sebagai poster supaya tidak
                                 ada ruang kosong selagi video diunduh. Sengaja tanpa
                                 drop-shadow dan tanpa hover: visual menyatu dengan
                                 layout, tidak terlihat seperti kartu yang melayang. --}}
                            <video
                                src="{{ asset('assets/hero.mp4') }}"
                                poster="{{ asset('assets/full-jurusan-logo.png') }}"
                                aria-label="Video ilustrasi SMKN 2 Karanganyar"
                                class="w-full h-auto object-contain select-none"
                                autoplay loop muted playsinline preload="auto">
                                Browser Anda tidak mendukung pemutaran video.
                            </video>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- KOMPETENSI KEAHLIAN & MITRA DUDI -->
    <section id="jurusan" class="py-16 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-4">
                    Kompetensi Keahlian
                </h2>
                <p class="text-slate-600 text-base sm:text-lg">
                    Beragam kompetensi keahlian berbasis teknologi dan industri yang membekali siswa dengan keterampilan
                    profesional sesuai kebutuhan dunia kerja.
                </p>
            </div>

            <!-- 4 Vertical Jurusan Cards Grid (DINAMIS) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 pt-12 mb-20">

                @php
                    // Warna dan konfigurasi per jurusan (bisa disesuaikan berdasarkan nama jurusan)
                    $jurusanConfig = [
                        'Teknik Pemesinan' => ['bg' => 'bg-[#5FB0FF]', 'shadow' => 'shadow-blue-500/20', 'logo' => 'logo_mesin.png'],
                        'Teknik Pembuatan Kain' => ['bg' => 'bg-[#FCE055]', 'shadow' => 'shadow-amber-500/20', 'logo' => 'logo_tekstil.png'],
                        'Teknik Ototronik' => ['bg' => 'bg-[#FF5A5F]', 'shadow' => 'shadow-red-500/20', 'logo' => 'logo_oto.png'],
                        'Rekayasa Perangkat Lunak' => ['bg' => 'bg-[#10C863]', 'shadow' => 'shadow-emerald-500/20', 'logo' => 'logo_rpl.png'],
                    ];
                @endphp

                @forelse($jurusans as $jurusan)
                    @php
                        $config = $jurusanConfig[$jurusan->nama] ?? ['bg' => 'bg-blue-300', 'shadow' => 'shadow-blue-500/20', 'logo' => 'logo_mesin.png'];
                    @endphp
                    <div class="relative group cursor-pointer pt-16">
                        <div class="{{ $config['bg'] }} rounded-b-3xl rounded-t-[40px] p-6 text-slate-900 flex flex-col justify-between shadow-xl {{ $config['shadow'] }} group-hover:-translate-y-3 transition-all duration-300 relative z-10 min-h-[380px]">
                            <div class="absolute -top-16 left-0 right-0 flex justify-center pointer-events-none">
                                @if($jurusan->logo)
                                    <img src="{{ asset('assets/' . $jurusan->logo) }}" alt="{{ $jurusan->nama }}"
                                        class="w-52 h-auto object-contain filter drop-shadow-xl group-hover:scale-110 group-hover:-translate-y-2 transition-all duration-300 transform-gpu">
                                @else
                                    <img src="{{ asset('assets/' . $config['logo']) }}" alt="{{ $jurusan->nama }}"
                                        class="w-52 h-auto object-contain filter drop-shadow-xl group-hover:scale-110 group-hover:-translate-y-2 transition-all duration-300 transform-gpu">
                                @endif
                            </div>
                            <div class="mt-28 space-y-3">
                                <h3 class="text-2xl font-bold tracking-tight text-slate-900 leading-tight">
                                    {{ $jurusan->nama }}
                                </h3>
                                <p class="text-slate-800/90 text-sm font-medium leading-relaxed">
                                    {{ Str::limit($jurusan->deskripsi ?? 'Deskripsi jurusan belum diisi.', 150) }}
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-4 text-center py-10 text-slate-500">Data jurusan belum tersedia.</div>
                @endforelse

            </div>

            <!-- MITRA DUDI Container (DINAMIS) -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-8 md:p-10 shadow-md">
                <div class="mb-8 flex items-center gap-4">
                    <h4 class="text-base md:text-lg font-extrabold text-slate-800 tracking-wider uppercase">
                        MITRA DUDI — Kerjasama Industri
                    </h4>
                    <div class="h-[3px] bg-slate-200 flex-1 rounded-full"></div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6 lg:gap-10 items-center justify-items-center">
                    @forelse($dudis as $dudi)
                        <div class="flex flex-col items-center justify-center w-full h-24 group hover:scale-110 transition-transform duration-300">
                            @if($dudi->logo)
                                <img src="{{ asset('assets/' . $dudi->logo) }}" alt="Logo {{ $dudi->nama }}"
                                    class="h-16 md:h-20 w-auto object-contain filter drop-shadow-md select-none">
                            @else
                                <div class="font-black text-xl md:text-2xl text-blue-800 tracking-tighter border-4 border-blue-800 px-4 py-2 rounded-xl bg-blue-50/50 shadow-sm">
                                    {{ $dudi->nama }}
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="col-span-5 text-center py-6 text-slate-500">Data mitra DUDI belum tersedia.</div>
                    @endforelse
                </div>

                <div class="mt-8 text-center">
                    <a href="{{ route('pkl-bkk') }}"
                        class="inline-flex items-center text-brand-blue font-bold text-sm hover:translate-x-1 transition-transform">
                        Lihat Semua Mitra &amp; Lowongan PKL/BKK
                        <i class="fa-solid fa-chevron-right text-xs ml-1"></i>
                    </a>
                </div>
            </div>

        </div>
    </section>

    <!-- KESISWAAN & PRESTASI TERBARU -->
    <section id="kesiswaan" class="py-16 bg-[#F8FAFC] relative">
        <div class="absolute top-8 left-8 w-24 h-24 dot-pattern opacity-40 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            <div class="mb-12">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-2">
                    Kesiswaan
                </h2>
                <p class="text-slate-600 text-base">
                    Membentuk karakter, kedisiplinan, dan potensi non-akademik.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-16">

                <!-- CARD PRESTASI (DINAMIS) -->
                <div class="lg:col-span-6 bg-white border border-slate-200 rounded-2xl p-6 card-shadow flex flex-col justify-between">
                    <div>
                        <div class="bg-blue-900 text-white font-bold text-center py-2.5 rounded-lg mb-6 tracking-wide text-sm sm:text-base">
                            {{ $prestasis->first()->judul ?? 'PRESTASI TERBARU SISWA' }}
                        </div>

                        <div class="space-y-4 mb-6">
                            @forelse($prestasis as $prestasi)
                                <div class="flex gap-4 items-start bg-slate-50 rounded-lg p-3 border border-slate-100">
                                    @if($prestasi->dokumentasi)
                                        <img src="{{ asset('assets/' . $prestasi->dokumentasi) }}" alt="{{ $prestasi->judul }}" class="w-16 h-16 object-cover rounded-lg">
                                    @else
                                        <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600">
                                            <i class="fa-solid fa-trophy text-xl"></i>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <div class="text-xs font-bold text-slate-800">{{ $prestasi->judul ?? 'Prestasi Siswa' }}</div>
                                        <div class="text-[10px] text-slate-500 mt-1">{{ Str::limit($prestasi->deskripsi ?? '-', 100) }}</div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-slate-500 text-xs text-center">Belum ada data prestasi.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="text-right mt-4">
                        <span class="inline-block bg-brand-blue text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase">
                            AKADEMIK
                        </span>
                    </div>
                </div>

                <!-- CARD EKSTRAKURIKULER (DINAMIS) -->
                <div class="lg:col-span-3 bg-white border border-slate-200 rounded-2xl p-6 card-shadow flex flex-col justify-between hover:border-brand-blue transition-colors">
                    <div>
                        <div class="w-12 h-12 rounded-full bg-blue-50 text-brand-blue flex items-center justify-center text-2xl mb-6">
                            <i class="fa-solid fa-futbol"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-3">Ekstrakurikuler</h3>
                        <ul class="text-slate-600 text-sm leading-relaxed mb-6 space-y-1">
                            @forelse($ekstrakurikulers->take(4) as $eskul)
                                <li class="flex items-center gap-2">
                                    <i class="fa-solid fa-circle text-[6px] text-brand-blue"></i>
                                    <span>{{ $eskul->nama }}</span>
                                </li>
                            @empty
                                <li class="text-xs text-slate-500">Belum ada ekstrakurikuler.</li>
                            @endforelse
                        </ul>
                    </div>
                    <a href="#"
                        class="inline-flex items-center text-brand-blue font-bold text-sm hover:translate-x-1 transition-transform">
                        Lihat Semua Eskul <i class="fa-solid fa-chevron-right text-xs ml-1"></i>
                    </a>
                </div>

                <!-- CARD TATA TERTIB -->
                <div class="lg:col-span-3 bg-brand-blue text-white rounded-2xl p-6 card-shadow flex flex-col justify-between">
                    <div>
                        <div class="text-2xl mb-6">
                            <i class="fa-solid fa-scale-balanced"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-3">Tata Tertib</h3>
                        <p class="text-blue-100 text-sm leading-relaxed mb-6">
                            Pedoman kedisiplinan siswa untuk membentuk etos kerja profesional.
                        </p>
                    </div>
                    <a href="#"
                        class="bg-white/20 hover:bg-white/30 text-white font-semibold text-xs py-2.5 px-4 rounded-lg w-fit transition-colors">
                        Unduh PDF
                    </a>
                </div>

            </div>

            <!-- PRESTASI TERBARU (DINAMIS) -->
            <div class="bg-white rounded-2xl p-6 sm:p-8 card-shadow border border-slate-100 relative">
                <div class="mb-6">
                    <h4 class="text-lg font-bold text-slate-800 border-b-2 border-brand-blue inline-block pb-1">Prestasi Terbaru</h4>
                </div>

                <div class="relative">
                    <div id="news-container" class="grid grid-cols-1 md:grid-cols-2 gap-6 transition-all duration-300">
                        @forelse($prestasis as $index => $prestasi)
                            @if($index == 0)
                                <div class="bg-gradient-to-r from-blue-900 to-indigo-900 rounded-xl overflow-hidden p-6 text-white relative group">
                                    <span class="inline-block bg-blue-600 text-white text-[10px] font-bold px-2.5 py-1 rounded mb-3 uppercase">
                                        BERITA HARI INI
                                    </span>
                                    <h4 class="text-lg sm:text-xl font-bold leading-snug mb-4 group-hover:text-blue-200 transition-colors">
                                        {{ $prestasi->judul ?? 'Prestasi Terbaru Siswa' }}
                                    </h4>
                                    <p class="text-xs text-blue-100">{{ Str::limit($prestasi->deskripsi ?? '', 120) }}</p>
                                </div>
                            @else
                                <div class="bg-gradient-to-r from-emerald-800 to-teal-900 rounded-xl overflow-hidden p-6 text-white relative group">
                                    <div class="text-xs font-semibold text-emerald-300 mb-1">{{ $sekolah->profil_judul ?? 'SMKN 2 KARANGANYAR' }} MENGUCAPKAN</div>
                                    <h4 class="text-2xl sm:text-3xl font-black italic tracking-wide text-amber-300 group-hover:scale-105 transition-transform">
                                        SELAMAT DAN SUKSES !
                                    </h4>
                                    <p class="text-xs text-emerald-100 mt-2">{{ $prestasi->judul ?? '' }} - {{ Str::limit($prestasi->deskripsi ?? '', 80) }}</p>
                                </div>
                            @endif
                        @empty
                            <div class="col-span-2 text-center py-6 text-slate-500">Belum ada prestasi terbaru.</div>
                        @endforelse
                    </div>

                    <button id="prev-news" class="absolute -left-4 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-white shadow-md border border-slate-200 flex items-center justify-center text-slate-700 hover:text-brand-blue transition-colors">
                        <i class="fa-solid fa-chevron-left text-xs"></i>
                    </button>
                    <button id="next-news" class="absolute -right-4 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-white shadow-md border border-slate-200 flex items-center justify-center text-slate-700 hover:text-brand-blue transition-colors">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </button>
                </div>
            </div>

        </div>
    </section>

    <!-- PRODUK UNGGULAN (DINAMIS) -->
    <section id="produk" class="py-16 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-4">
                    Produk Unggulan
                </h2>
                <p class="text-slate-600 text-base sm:text-lg">
                    Beragam produk unggulan berbasis teknologi dan industri yang mencerminkan keterampilan siswa sesuai
                    kebutuhan dunia kerja.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div
                class="bg-gradient-to-r from-blue-300 via-blue-200 to-slate-200 rounded-2xl p-8 flex flex-col justify-between relative overflow-hidden shadow-sm hover:shadow-lg transition-shadow">
                <div class="pr-24 z-10">
                    <h3 class="text-xl sm:text-2xl font-extrabold text-slate-900 mb-3">
                        TEKNIK PERMESINAN
                    </h3>
                    <p
                        class="text-slate-700 text-xs sm:text-sm uppercase tracking-wide leading-relaxed mb-6 font-semibold">
                        DIPROSES MENGGUNAKAN MESIN MODERN YANG MENGHASILKAN PRODUK DENGAN KUALITAS TINGGI, PRESISI,
                        DAN HASIL YANG KONSISTEN.
                    </p>
                    <a href="#"
                    class="bg-slate-900 text-white text-xs font-bold px-5 py-2.5 rounded-lg uppercase tracking-wider hover:bg-slate-800 transition-colors">
                    SEMUA PRODUK
                </a>
                </div>
                <div class="absolute right-5 bottom-10 w-32 h-32 opacity-90">
                    <img src="assets/produk mesin.png" alt="Bolt / Hardware" class="w-full h-full object-contain">
                </div>
            </div>

            <div
                class="bg-gradient-to-r from-amber-200 via-amber-100 to-yellow-50 rounded-2xl p-8 flex flex-col justify-between relative overflow-hidden shadow-sm hover:shadow-lg transition-shadow">
                <div class="pr-28 z-10">
                    <h3 class="text-xl sm:text-2xl font-extrabold text-slate-900 mb-3">
                        TEKNIK PEMBUATAN KAIN
                    </h3>
                    <p
                        class="text-slate-700 text-xs sm:text-sm uppercase tracking-wide leading-relaxed mb-6 font-semibold">
                        DARI KAIN BATIK, TENUN, HINGGA KAIN ECOPRINT SEMUA DIPRODUKSI OLEH SISWA JURUSAN TEKSTIL.
                    </p>
                    <a href="#"
                    class="bg-slate-900 text-white text-xs font-bold px-5 py-2.5 rounded-lg uppercase tracking-wider hover:bg-slate-800 transition-colors">
                    SEMUA PRODUK
                </a>
                  
                </div>
                <div class="absolute right-5 bottom-10 w-32 h-36 flex items-center justify-center opacity-90">
                    <img src="assets/produk tpk.png" alt="Bolt / Hardware" class="w-full h-full object-contain">
                </div>
            </div>

            <div
                class="bg-gradient-to-r from-emerald-200 via-teal-100 to-emerald-50 rounded-2xl p-8 flex flex-col justify-between relative overflow-hidden shadow-sm hover:shadow-lg transition-shadow">
                <div class="pr-32 z-10">
                    <h3 class="text-xl sm:text-2xl font-extrabold text-slate-900 mb-3">
                        REKAYASA PERANGKAT LUNAK
                    </h3>
                    <p
                        class="text-slate-700 text-xs sm:text-sm uppercase tracking-wide leading-relaxed mb-6 font-semibold">
                        DARI COMPANY PROFILE, E-COMMERCE, HINGGA APLIKASI ONLINE
                    </p>
                    <a href="#"
                    class="bg-slate-900 text-white text-xs font-bold px-5 py-2.5 rounded-lg uppercase tracking-wider hover:bg-slate-800 transition-colors">
                    SEMUA PRODUK
                </a>
                </div>
                <div class="absolute right-5 bottom-10 w-32 h-36 flex items-center justify-center opacity-90">
                    <img src="assets/produk rpl.png" alt="Bolt / Hardware" class="w-full h-full object-contain">
                </div>
            </div>

            <div
                class="bg-gradient-to-r from-red-300 via-pink-200 to-rose-100 rounded-2xl p-8 flex flex-col justify-between relative overflow-hidden shadow-sm hover:shadow-lg transition-shadow">
                <div class="pr-28 z-10">
                    <h3 class="text-xl sm:text-2xl font-extrabold text-slate-900 mb-3">
                        TEKNIK OTOTRONIK
                    </h3>
                    <p
                        class="text-slate-700 text-xs sm:text-sm uppercase tracking-wide leading-relaxed mb-6 font-semibold">
                        TEKNOLOGI OTOTRONIK MODERN DALAM PERAWATAN DAN PERBAIKAN KENDARAAN UNTUK MENGHASILKAN
                        PERFORMA YANG OPTIMAL DAN BERKUALITAS.
                    </p>
                    <a href="#"
                        class="bg-slate-900 text-white text-xs font-bold px-5 py-2.5 rounded-lg uppercase tracking-wider hover:bg-slate-800 transition-colors">
                        SEMUA PRODUK
                    </a>
                </div>
                <div class="absolute right-5 bottom-10 w-32 h-36 flex items-center justify-center opacity-90">
                    <img src="assets/produk oto.png" alt="Bolt / Hardware" class="w-full h-full object-contain">
                </div>
            </div>
            </div>

        </div>
    </section>

    <!-- LAYANAN PEMINJAMAN AULA (DINAMIS) -->
    <section id="aula" class="py-16 bg-[#FAFCFF] relative overflow-hidden font-sans">
        <div class="absolute top-8 right-8 w-28 h-28 dot-pattern opacity-40 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            <div class="text-center max-w-3xl mx-auto mb-14">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-3 tracking-tight">
                    Layanan Peminjaman Aula
                </h2>
                <p class="text-slate-500 text-sm sm:text-base leading-relaxed">
                    Fasilitas sekolah dengan kapasitas luas untuk berbagai kebutuhan acara institusi,<br class="hidden sm:block"> perusahaan, dan masyarakat umum.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">

                <!-- PAKET PEMINJAMAN (DINAMIS) -->
                <div class="lg:col-span-4 flex flex-col justify-between gap-6">
                    @forelse($paketPeminjamans as $paket)
                        <div class="bg-white border-2 border-[#0066B2] rounded-2xl p-6 relative shadow-sm">
                            <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-[#0066B2] text-white text-xs font-semibold px-8 py-1 rounded-full">
                                {{ $paket->nama_paket }}
                            </div>

                            <div class="mt-2 mb-5 flex items-baseline justify-center gap-1">
                                <span class="text-xs font-medium text-slate-400">Rp.</span>
                                <span class="text-2xl sm:text-3xl font-bold text-[#0066B2]">{{ number_format($paket->harga, 0, ',', '.') }}</span>
                                <span class="text-xs text-slate-400">/ {{ $paket->durasi ?? '4 Jam' }}</span>
                            </div>

                            <ul class="space-y-2.5 mb-6 text-slate-700 text-xs sm:text-sm">
                                @if($paket->fasilitas)
                                    @foreach(explode(',', $paket->fasilitas) as $fasilitas)
                                        <li class="flex items-center gap-2.5">
                                            <i class="fa-regular fa-circle-check text-[#0066B2] text-base"></i>
                                            <span>{{ trim($fasilitas) }}</span>
                                        </li>
                                    @endforeach
                                @else
                                    <li class="text-xs text-slate-500">Detail fasilitas belum tersedia.</li>
                                @endif
                            </ul>

                            <button onclick="openModal('Pemesanan {{ $paket->nama_paket }}', 'Form reservasi Aula {{ $paket->durasi ?? '' }} (Rp {{ number_format($paket->harga, 0, ',', '.') }}).')"
                                class="w-full bg-[#0066B2] hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold py-2.5 rounded-full transition-all duration-200">
                                Pilih Paket
                            </button>
                        </div>
                    @empty
                        <div class="text-center py-6 text-slate-500 text-xs">Belum ada paket peminjaman.</div>
                    @endforelse
                </div>

                <!-- INFO PEMINJAMAN -->
                <div class="lg:col-span-8 bg-white rounded-3xl p-8 sm:p-10 pb-10 sm:pb-12 border border-slate-100 shadow-xl flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-3 mb-6">
                            <i class="fa-regular fa-circle-info text-2xl text-slate-900"></i>
                            <h3 class="text-xl sm:text-2xl font-bold text-slate-900 border-b-2 border-slate-900 pb-1 inline-block">
                                Informasi Peminjaman Aula
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                            <div class="md:col-span-7 space-y-4">
                                <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                                    Kami menyediakan layanan peminjaman aula sekolah untuk berbagai kebutuhan kegiatan. Mulai dari acara sekolah, organisasi, rapat, seminar, hingga kegiatan instansi luar.
                                </p>

                                <div>
                                    <p class="text-slate-800 font-semibold text-xs sm:text-sm mb-1.5">Layanan kami mencakup:</p>
                                    <ul class="list-disc list-inside text-slate-600 text-xs sm:text-sm space-y-1 pl-1">
                                        <li>Booking Aula Online.</li>
                                        <li>Peminjaman Aula Berkualitas.</li>
                                        <li>Fasilitas Lengkap.</li>
                                        <li>Kebersihan & Kenyamanan.</li>
                                        <li>Parkir & Keamanan.</li>
                                    </ul>
                                </div>

                                <p class="text-slate-600 text-xs sm:text-sm pt-1">
                                    Kami siap membantu menciptakan tempat kegiatan yang nyaman dan berkualitas.
                                </p>

                                <div class="pt-3">
                                    <button onclick="openModal('Mulai Peminjaman Aula', 'Form jadwal peminjaman aula.')"
                                        class="bg-[#0066B2] hover:bg-blue-700 text-white font-semibold text-xs sm:text-sm px-6 py-2.5 rounded-full transition-all duration-200 shadow-md">
                                        Mulai Peminjaman
                                    </button>
                                </div>
                            </div>

                            <div class="md:col-span-5 relative mt-4 md:mt-0 pl-0 sm:pl-2">
                                <div class="relative rounded-2xl overflow-hidden shadow-md h-80 sm:h-96 w-full bg-slate-200">
                                    {{-- Sama seperti hero: nama file di DB dicek ke disk,
                                         kalau tidak ada jatuh ke gambar cadangan. --}}
                                    @if($aulas->first() && $aulas->first()->dokumentasi && is_file(public_path('assets/' . $aulas->first()->dokumentasi)))
                                        <img src="{{ asset('assets/' . $aulas->first()->dokumentasi) }}" alt="{{ $aulas->first()->nama }}" class="w-full h-full object-cover">
                                    @else
                                        <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=800&auto=format&fit=crop&q=80" alt="Gedung Auditorium Aula" class="w-full h-full object-cover">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- INFORMASI PPDB & DAYA TAMPUNG -->
    <section id="ppdb" class="py-16 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center mb-16">
                <div class="lg:col-span-6">
                    <div class="rounded-2xl overflow-hidden shadow-xl border border-slate-100 bg-slate-100">
                        @if($ppdb && $ppdb->dokumentasi)
                            <img src="{{ asset('assets/' . $ppdb->dokumentasi) }}" alt="Banner PPDB" class="w-full h-56 sm:h-64 md:h-80 object-cover">
                        @else
                            <img src="{{ asset('assets/ppdb.png') }}" alt="Banner SPMB" class="w-full h-56 sm:h-64 md:h-80 object-cover">
                        @endif
                    </div>
                </div>

                <div class="lg:col-span-6 space-y-4">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-tight">
                        {{ $ppdb->judul ?? 'INFORMASI PPDB SMKN 2 KARANGANYAR' }}
                    </h2>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        {{ $ppdb->deskripsi ?? 'Calon Murid Baru diharapkan menyiapkan seluruh dokumen persyaratan sebelum melakukan pengajuan akun.' }}
                    </p>
                    @if($ppdb && $ppdb->persyaratan)
                        <p class="text-slate-500 text-xs leading-relaxed underline">
                            Persyaratan: {{ $ppdb->persyaratan }}
                        </p>
                    @endif
                    <div class="pt-2">
                        <button onclick="openModal('Portal Resmi PPDB', 'Mengarahkan ke portal resmi pendaftaran online.')"
                            class="bg-brand-blue hover:bg-brand-darkBlue text-white font-semibold px-6 py-2.5 rounded-xl text-sm transition-colors shadow-md">
                            Lihat Selengkapnya
                        </button>
                    </div>
                </div>
            </div>

            <!-- DAYA TAMPUNG (DINAMIS dari InformasiPpdb) -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 card-shadow">
                <div class="mb-6 flex items-center gap-3">
                    <h4 class="text-base font-bold text-slate-800">Daya Tampung — Kompetensi Keahlian</h4>
                    <div class="h-[2px] bg-slate-200 flex-1"></div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @php
                        $colorConfig = ['bg-[#2B89FF]', 'bg-[#FF982B]', 'bg-[#FF3B3B]', 'bg-[#28C76F]'];
                    @endphp
                    @forelse($informasiPpdbs as $index => $info)
                        <div class="{{ $colorConfig[$index % 4] }} text-white rounded-2xl p-5 hover:scale-105 transition-transform">
                            <div class="text-xs font-extrabold uppercase mb-4 tracking-wider">{{ $info->nama_agenda }}</div>
                            <div class="text-4xl font-black mb-1">{{ $info->daya_tampung ?? '108' }}</div>
                            <div class="text-xs font-medium">Siswa</div>
                        </div>
                    @empty
                        <div class="col-span-4 text-center py-6 text-slate-500">Data daya tampung belum tersedia.</div>
                    @endforelse
                </div>
            </div>

        </div>
    </section>
@endsection