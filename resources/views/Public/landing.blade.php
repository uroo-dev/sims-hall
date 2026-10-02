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
                    // Warna kartu per jurusan (konfigurasi tampilan, bukan data).
                    // Kalau `$jurusan->warna` sudah diisi di dashboard, warna itu
                    // yang dipakai supaya kartu ikut berubah otomatis.
                    $jurusanConfig = [
                        'Teknik Pemesinan' => ['bg' => 'bg-[#5FB0FF]', 'shadow' => 'shadow-blue-500/20'],
                        'Teknik Pembuatan Kain' => ['bg' => 'bg-[#FCE055]', 'shadow' => 'shadow-amber-500/20'],
                        'Teknik Ototronik' => ['bg' => 'bg-[#FF5A5F]', 'shadow' => 'shadow-red-500/20'],
                        'Rekayasa Perangkat Lunak' => ['bg' => 'bg-[#10C863]', 'shadow' => 'shadow-emerald-500/20'],
                    ];
                @endphp

                @forelse($jurusans as $jurusan)
                    @php
                        $config = $jurusanConfig[$jurusan->nama] ?? ['bg' => 'bg-blue-300', 'shadow' => 'shadow-blue-500/20'];
                    @endphp
                    <div class="relative group cursor-pointer pt-16">
                        <div class="{{ $config['bg'] }} rounded-b-3xl rounded-t-[40px] p-6 text-slate-900 flex flex-col justify-between shadow-xl {{ $config['shadow'] }} group-hover:-translate-y-3 transition-all duration-300 relative z-10 min-h-[380px]">
                            <div class="absolute -top-16 left-0 right-0 flex justify-center pointer-events-none">
                                @if ($jurusan->logo)
                                    <img src="{{ asset('assets/' . $jurusan->logo) }}" alt="{{ $jurusan->nama }}"
                                        class="w-52 h-auto object-contain filter drop-shadow-xl group-hover:scale-110 group-hover:-translate-y-2 transition-all duration-300 transform-gpu">
                                @elseif ($jurusan->dokumentasiUrl())
                                    <img src="{{ $jurusan->dokumentasiUrl() }}" alt="{{ $jurusan->nama }}"
                                        class="w-52 h-auto object-contain filter drop-shadow-xl group-hover:scale-110 group-hover:-translate-y-2 transition-all duration-300 transform-gpu">
                                @else
                                    {{-- Logo belum diisi: tampilkan inisial, bukan logo
                                         jurusan lain yang menyesatkan. --}}
                                    <span class="w-32 h-32 rounded-2xl bg-white/60 text-slate-500 flex items-center justify-center font-black text-4xl shadow-inner">
                                        {{ strtoupper(substr($jurusan->nama, 0, 2)) }}
                                    </span>
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

            <div class="mb-12 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                <div>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-2">
                        Kesiswaan
                    </h2>
                    <p class="text-slate-600 text-base">
                        Membentuk karakter, kedisiplinan, dan potensi non-akademik.
                    </p>
                </div>
                <a href="{{ route('kesiswaan') }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-brand-blue hover:text-brand-darkBlue transition">
                    <span>Halaman Kesiswaan Lengkap</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-16">

                <!-- CARD PRESTASI (DINAMIS) -->
                <div class="lg:col-span-6 bg-white border border-slate-200 rounded-2xl p-6 card-shadow flex flex-col justify-between">
                    <div>
                        <div class="bg-blue-900 text-white font-bold text-center py-2.5 rounded-lg mb-6 tracking-wide text-sm sm:text-base">
                            PRESTASI TERBARU SISWA
                        </div>

                        <div class="space-y-4 mb-6">
                            @forelse($prestasies->take(4) as $prestasi)
                                <div class="flex gap-4 items-start bg-slate-50 rounded-lg p-3 border border-slate-100">
                                    @if($prestasi->gambarUrl())
                                        <img src="{{ $prestasi->gambarUrl() }}" alt="{{ $prestasi->judul }}" class="w-16 h-16 object-cover rounded-lg">
                                    @else
                                        <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600">
                                            <i class="fa-solid fa-trophy text-xl"></i>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <div class="text-xs font-bold text-slate-800">{{ $prestasi->judul }}</div>
                                        <div class="text-[10px] text-slate-500 mt-1">{{ Str::limit($prestasi->ringkasan ?: strip_tags($prestasi->konten), 100) }}</div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-slate-500 text-xs text-center">Belum ada data prestasi.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="text-right mt-4">
                        <a href="{{ route('informasi') }}?kategori={{ \App\Models\Artikel::KATEGORI_PRESTASI }}#daftar-artikel"
                            class="inline-block bg-brand-blue text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase hover:bg-brand-darkBlue transition-colors">
                            LIHAT SEMUA PRESTASI
                        </a>
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
                    <a href="{{ route('kesiswaan') }}#ekstrakurikuler"
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
                    <a href="{{ route('kesiswaan') }}#tata-tertib"
                        class="bg-white/20 hover:bg-white/30 text-white font-semibold text-xs py-2.5 px-4 rounded-lg w-fit transition-colors">
                        Lihat Tata Tertib
                    </a>
                </div>

            </div>

            <!-- PRESTASI TERBARU (DINAMIS) -->
            <div class="bg-white rounded-2xl p-6 sm:p-8 card-shadow border border-slate-100 relative">
                <div class="mb-6 flex items-center justify-between gap-4">
                    <h4 class="text-lg font-bold text-slate-800 border-b-2 border-brand-blue inline-block pb-1">Prestasi Terbaru</h4>
                    <a href="{{ route('informasi') }}?kategori={{ \App\Models\Artikel::KATEGORI_PRESTASI }}#daftar-artikel"
                        class="inline-flex items-center text-brand-blue font-bold text-xs hover:translate-x-1 transition-transform">
                        Semua Prestasi <i class="fa-solid fa-chevron-right text-[10px] ml-1"></i>
                    </a>
                </div>

                <div id="news-container" class="grid grid-cols-1 md:grid-cols-2 gap-6 transition-all duration-300">
                    @forelse($prestasies as $index => $prestasi)
                        @php
                            // Kartu pertama dibedakan supaya hierarki visual tetap sama
                            // seperti desain asli, selebihnya seragam agar tidak
                            // memakai warna random per indeks.
                            $gradien = $index === 0
                                ? 'from-blue-900 to-indigo-900'
                                : 'from-slate-800 to-slate-700';
                            $label = $index === 0
                                ? 'PRESTASI TERBARU'
                                : ($index === 1 ? 'PRESTASI TERLAMBANG' : 'PRESTASI SISWA');
                        @endphp
                        <a href="{{ route('informasi.show', $prestasi->slug) }}"
                            class="bg-gradient-to-r {{ $gradien }} rounded-xl overflow-hidden p-6 text-white relative group">
                            <span class="inline-block bg-white/15 text-white text-[10px] font-bold px-2.5 py-1 rounded mb-3 uppercase">
                                {{ $label }}
                            </span>
                            <h4 class="text-lg sm:text-xl font-bold leading-snug mb-2 group-hover:text-blue-200 transition-colors">
                                {{ $prestasi->judul }}
                            </h4>
                            <p class="text-xs text-white/80 leading-relaxed">
                                {{ Str::limit($prestasi->ringkasan ?: strip_tags($prestasi->konten), 120) }}
                            </p>
                            <span class="mt-4 inline-flex items-center text-[10px] font-bold uppercase tracking-wider text-white/70 group-hover:text-white transition-colors">
                                Baca Selengkapnya <i class="fa-solid fa-arrow-right text-[9px] ml-1"></i>
                            </span>
                        </a>
                    @empty
                        <div class="col-span-2 text-center py-6 text-slate-500">Belum ada prestasi terbaru.</div>
                    @endforelse
                </div>
            </div>

        </div>
    </section>

    <!-- PRODUK UNGGULAN (DINAMIS) -->
    <section id="produk" class="py-16 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @php
                // Judul & deskripsi section diambil dari baris pengaturan
                // `produk_unggulan` (singleton), sama seperti halaman katalog.
                $produkUnggulan = $produkUnggulan ?? null;
                $judulProduk = $produkUnggulan?->judul ?: 'Produk Unggulan';
                $deskripsiProduk = $produkUnggulan?->deskripsi
                    ?: 'Beragam produk unggulan berbasis teknologi dan industri yang mencerminkan keterampilan siswa sesuai kebutuhan dunia kerja.';
                $jurusanBerproduk = $jurusans->filter(fn ($j) => $j->produk->isNotEmpty())->values();
            @endphp

            <div class="mb-12 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                <div>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-2">
                        {{ $judulProduk }}
                    </h2>
                    <p class="text-slate-600 text-base max-w-3xl leading-relaxed">
                        {{ $deskripsiProduk }}
                    </p>
                </div>
                <a href="{{ route('produk-unggulan') }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-brand-blue hover:text-brand-darkBlue transition">
                    <span>Katalog Semua Produk</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @forelse ($jurusanBerproduk as $jurusan)
                    @php
                        // Gradien diturunkan dari warna aksen jurusan supaya kartu
                        // otomatis ikut berubah kalau admin mengganti warnanya.
                        $warna = $jurusan->warna;
                    @endphp
                    <div id="produk-{{ \Illuminate\Support\Str::slug($jurusan->nama) }}"
                        class="rounded-2xl p-8 flex flex-col justify-between relative overflow-hidden shadow-sm hover:shadow-lg transition-shadow"
                        style="background: linear-gradient(135deg, {{ $warna }}26 0%, {{ $warna }}0d 55%, #f1f5f9 100%);">

                        <div class="pr-24 z-10">
                            <h3 class="text-xl sm:text-2xl font-extrabold text-slate-900 mb-3 uppercase">
                                {{ $jurusan->nama }}
                            </h3>
                            <p class="text-slate-700 text-xs sm:text-sm leading-relaxed mb-6 font-semibold line-clamp-3">
                                {{ $jurusan->deskripsi ?: 'Karya siswa jurusan ' . $jurusan->nama . '.' }}
                            </p>
                            <a href="{{ route('produk-unggulan') }}#jurusan-{{ $jurusan->jurusanID }}"
                                class="inline-block bg-slate-900 text-white text-xs font-bold px-5 py-2.5 rounded-lg uppercase tracking-wider hover:bg-slate-800 transition-colors">
                                Semua Produk ({{ $jurusan->produk->count() }})
                            </a>
                        </div>

                        <div class="absolute right-5 bottom-6 w-28 h-28 opacity-95">
                            @if ($jurusan->dokumentasiUrl())
                                <img src="{{ $jurusan->dokumentasiUrl() }}" alt="{{ $jurusan->nama }}" class="w-full h-full object-contain">
                            @elseif ($jurusan->logo)
                                <img src="{{ asset('assets/' . $jurusan->logo) }}" alt="{{ $jurusan->nama }}" class="w-full h-full object-contain">
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 text-center py-10 text-slate-500 text-sm">
                        Belum ada produk unggulan yang dipublikasikan.
                    </div>
                @endforelse
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

                                <div class="pt-3 flex flex-wrap items-center gap-3">
                                    <a href="{{ route('layanan-peminjaman') }}"
                                        class="bg-[#0066B2] hover:bg-blue-700 text-white font-semibold text-xs sm:text-sm px-6 py-2.5 rounded-full transition-all duration-200 shadow-md inline-flex items-center gap-2">
                                        <span>Layanan & Jadwal Aula</span>
                                        <i class="fa-solid fa-arrow-right text-xs"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="md:col-span-5 relative mt-6 md:mt-0 pl-0 sm:pl-2">
                                @php
                                    $landingAula = $aulas->first();
                                    $foto1Url = $landingAula?->foto_dokumentasi_url ?? asset('assets/logosmkk.png');
                                    $foto2Url = $landingAula?->foto_dokumentasi_2_url ?? asset('assets/logosmkk.png');
                                    $hasCustom1 = $landingAula?->has_custom_dokumentasi ?? false;
                                    $hasCustom2 = $landingAula?->has_custom_dokumentasi_2 ?? false;
                                @endphp

                                <div class="relative w-full aspect-[4/3] max-w-[420px] mx-auto">
                                    <!-- Aksen Lingkaran Background -->
                                    <div class="absolute -bottom-4 -right-4 w-48 h-48 border-[14px] border-[#0066B2]/20 rounded-full z-0"></div>

                                    <!-- DOKUMENTASI 1 (Foto Utama Aula / Logo Alternatif) -->
                                    <div class="relative rounded-2xl overflow-hidden shadow-xl h-72 sm:h-80 w-full z-10 border border-slate-100 {{ $hasCustom1 ? 'bg-slate-200' : 'bg-gradient-to-br from-blue-50 to-slate-100 flex items-center justify-center p-6' }}">
                                        @if($hasCustom1)
                                            <img src="{{ $foto1Url }}" alt="{{ $landingAula?->nama ?? 'Aula Sekolah' }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="flex flex-col items-center justify-center text-center p-4">
                                                <img src="{{ $foto1Url }}" alt="Logo SMK" class="w-24 h-24 object-contain mb-3 drop-shadow-sm">
                                                <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">{{ $landingAula?->nama ?? 'Aula SMKN 2 Kra' }}</span>
                                                <span class="text-[10px] text-slate-500 mt-0.5">Gedung Pertemuan &amp; Serbaguna</span>
                                            </div>
                                        @endif
                                        <div class="absolute top-3 left-3 bg-black/50 backdrop-blur-sm text-white px-2.5 py-1 rounded-full text-[10px] font-semibold flex items-center gap-1.5 shadow">
                                            <i class="fa-solid fa-camera text-[9px]"></i>
                                            <span>Foto Aula</span>
                                        </div>
                                    </div>

                                    <!-- DOKUMENTASI 2 (Foto Pendukung / Interior / Logo Alternatif Floating) -->
                                    <div class="absolute -bottom-4 -left-3 sm:-left-5 w-36 sm:w-44 rounded-xl overflow-hidden shadow-2xl border-4 border-white bg-white z-20 transition-transform duration-300 hover:scale-105">
                                        <div class="aspect-video relative {{ $hasCustom2 ? 'bg-slate-100' : 'bg-blue-50/70 flex items-center justify-center p-3' }}">
                                            @if($hasCustom2)
                                                <img src="{{ $foto2Url }}" alt="Interior Aula" class="w-full h-full object-cover">
                                            @else
                                                <div class="flex items-center gap-2">
                                                    <img src="{{ $foto2Url }}" alt="Logo Alternatif" class="w-10 h-10 object-contain">
                                                    <div class="text-left">
                                                        <span class="block text-[10px] font-bold text-slate-800 leading-tight">Fasilitas</span>
                                                        <span class="block text-[8px] text-slate-500">SMKN 2 Kra</span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
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
                        @php
                            $bannerPpdbSrc = $ppdbMaster?->banner_img 
                                ? asset('storage/' . $ppdbMaster->banner_img) 
                                : (!empty($ppdb?->dokumentasi) ? asset('assets/' . $ppdb->dokumentasi) : asset('assets/ppdb.png'));
                        @endphp
                        <img src="{{ $bannerPpdbSrc }}" alt="Banner PPDB" class="w-full h-56 sm:h-64 md:h-80 object-cover">
                    </div>
                </div>

                <div class="lg:col-span-6 space-y-4">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-tight">
                        {{ $ppdbMaster?->judul ?? ($ppdb?->judul ?? 'INFORMASI PPDB SMKN 2 KARANGANYAR') }}
                    </h2>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        {{ $ppdbMaster?->deskripsi ?? ($ppdb?->deskripsi ?? 'Calon Murid Baru diharapkan menyiapkan seluruh dokumen persyaratan sebelum melakukan pengajuan akun.') }}
                    </p>
                    @if(!empty($ppdb?->persyaratan))
                        <p class="text-slate-500 text-xs leading-relaxed underline">
                            Persyaratan: {{ $ppdb->persyaratan }}
                        </p>
                    @endif
                    <div class="pt-2">
                        <a href="{{ route('ppdb') }}"
                            class="bg-brand-blue hover:bg-brand-darkBlue text-white font-semibold px-6 py-2.5 rounded-xl text-sm transition-colors shadow-md inline-block">
                            Lihat Selengkapnya
                        </a>
                    </div>
                </div>
            </div>

            <!-- DAYA TAMPUNG (DINAMIS dari ppdb_jurusan) -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 card-shadow">
                <div class="mb-6 flex items-center gap-3">
                    <h4 class="text-base font-bold text-slate-800">Daya Tampung — Kompetensi Keahlian</h4>
                    <div class="h-[2px] bg-slate-200 flex-1"></div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @php
                        $colorConfig = ['bg-[#2B89FF]', 'bg-[#FF982B]', 'bg-[#FF3B3B]', 'bg-[#28C76F]'];
                        $listDayaTampung = $ppdbJurusans->isNotEmpty() ? $ppdbJurusans : $informasiPpdbs;
                    @endphp
                    @forelse($listDayaTampung as $index => $item)
                        @php
                            $namaJurusan = $item->nama_jurusan ?? ($item->nama_agenda ?? 'Kompetensi Keahlian');
                            $dayaTampung = $item->daya_tampung ?? 0;
                        @endphp
                        <div class="{{ $colorConfig[$index % 4] }} text-white rounded-2xl p-5 hover:scale-105 transition-transform">
                            <div class="text-xs font-extrabold uppercase mb-4 tracking-wider">{{ $namaJurusan }}</div>
                            <div class="text-4xl font-black mb-1">{{ $dayaTampung }}</div>
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