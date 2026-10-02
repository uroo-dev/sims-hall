@extends('Public.layout.app')

@section('title', 'Kesiswaan & Prestasi - SMK Negeri 2 Karanganyar')
@section('description', 'Tata tertib dan norma sekolah, ekstrakurikuler, serta prestasi siswa SMK Negeri 2 Karanganyar.')

@section('content')

    <!-- ============================================================
         KONTEN: KESISWAAN (HERO SECTION)
         ============================================================ -->
    <section id="kesiswaan" class="relative py-16 md:py-24 overflow-hidden bg-white">
        <!-- Abstract Background Shapes -->
        <div class="absolute top-0 right-0 w-1/3 h-2/3 bg-blue-50 rounded-bl-[10rem] -z-10 opacity-70"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-100 rounded-full -z-10 opacity-50 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

                <!-- LEFT: Text Content -->
                <div class="lg:col-span-6 space-y-6 z-10">
                    <span class="inline-block text-brand-blue font-bold tracking-wide text-sm sm:text-base uppercase">
                        SMK 2 Karanganyar – Sekolah Pusat Keunggulan
                    </span>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-slate-900 tracking-tight leading-[1.1]">
                        {{ $kesiswaan->judul ?: 'Kesiswaan SMKN 2 Karanganyar' }}
                    </h1>
                    
                    <p class="text-slate-600 text-base sm:text-lg leading-relaxed max-w-xl">
                        {{ $kesiswaan->deskripsi ?: 'Membangun karakter unggul melalui integrasi nilai moral dan penguasaan teknologi. Kami berdedikasi untuk membina potensi setiap siswa dalam lingkungan yang inklusif, inovatif, dan disiplin.' }}
                    </p>

                    <div class="flex flex-wrap gap-4 pt-4">
                        <button onclick="document.getElementById('ekstrakurikuler').scrollIntoView({behavior: 'smooth'})"
                            class="bg-brand-blue hover:bg-brand-darkBlue text-white font-bold px-8 py-3.5 rounded-xl text-sm transition-all shadow-lg shadow-blue-500/30 flex items-center gap-2">
                            Daftar Ekstrakurikuler <i class="fa-solid fa-arrow-right"></i>
                        </button>
                        <button onclick="document.getElementById('prestasi').scrollIntoView({behavior: 'smooth'})"
                            class="bg-white border-2 border-slate-200 hover:border-brand-blue text-slate-700 hover:text-brand-blue font-bold px-8 py-3.5 rounded-xl text-sm transition-all shadow-sm">
                            Lihat Prestasi
                        </button>
                    </div>
                </div>

                <!-- RIGHT: Visual Composition (Collage of News / Dokumentasi) -->
                <div class="lg:col-span-6 relative mt-12 lg:mt-0 flex justify-center lg:justify-end">
                    <div class="relative w-full max-w-[500px] aspect-[4/3]">
                        @php
                            $heroDocs = $kesiswaan->dokumentasi_urls;
                        @endphp

                        @if (count($heroDocs) >= 2)
                            <!-- Main Card 1 -->
                            <div class="absolute top-0 right-0 w-[80%] rounded-2xl overflow-hidden shadow-2xl z-10 p-2 bg-white border-4 border-white">
                                <img src="{{ $heroDocs[0] }}" alt="Dokumentasi 1" class="w-full h-44 object-cover rounded-xl">
                            </div>

                            <!-- Main Card 2 -->
                            <div class="absolute bottom-0 left-0 w-[70%] rounded-2xl overflow-hidden shadow-2xl z-20 p-2 bg-white border-4 border-white">
                                <img src="{{ $heroDocs[1] }}" alt="Dokumentasi 2" class="w-full h-36 object-cover rounded-xl">
                            </div>
                        @elseif (count($heroDocs) == 1)
                            <div class="absolute inset-0 rounded-2xl overflow-hidden shadow-2xl z-10 p-2 bg-white border-4 border-white">
                                <img src="{{ $heroDocs[0] }}" alt="Dokumentasi" class="w-full h-full object-cover rounded-xl">
                            </div>
                        @else
                            <!-- Default Fallback Cards -->
                            <div class="absolute top-0 right-0 w-[80%] bg-gradient-to-br from-blue-900 to-indigo-900 rounded-2xl overflow-hidden shadow-2xl z-10 p-5 text-white border-4 border-white">
                                <span class="inline-block bg-blue-600 text-white text-[10px] font-bold px-2.5 py-1 rounded mb-3 uppercase tracking-wider">
                                    BERITA HARI INI
                                </span>
                                <h4 class="text-sm sm:text-base font-bold leading-snug mb-2">
                                    SMKN 2 KARANGANYAR SIAP PERTAHANKAN GELAR JATENG DI LKBB-PB NASIONAL
                                </h4>
                                <div class="h-24 bg-slate-800 rounded-lg mt-2 overflow-hidden">
                                    <img src="https://images.unsplash.com/photo-1523580494863-6f3031224c94?w=400&auto=format&fit=crop&q=80" alt="News" class="w-full h-full object-cover opacity-80">
                                </div>
                            </div>

                            <div class="absolute bottom-0 left-0 w-[70%] bg-gradient-to-br from-emerald-800 to-teal-900 rounded-2xl overflow-hidden shadow-2xl z-20 p-5 text-white border-4 border-white">
                                <div class="text-[10px] font-semibold text-emerald-300 mb-1">SMKN 2 KARANGANYAR MENGUCAPKAN</div>
                                <h4 class="text-xl sm:text-2xl font-black italic tracking-wide text-amber-300 mb-2">
                                    SELAMAT DAN SUKSES !
                                </h4>
                                <div class="h-20 bg-slate-800 rounded-lg overflow-hidden">
                                    <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=400&auto=format&fit=crop&q=80" alt="Sukses" class="w-full h-full object-cover opacity-80">
                                </div>
                            </div>
                        @endif

                        <!-- Abstract decorative shapes -->
                        <div class="absolute -top-6 -left-6 w-24 h-24 dot-pattern opacity-50 z-0"></div>
                        <div class="absolute -bottom-8 -right-8 w-32 h-32 border-[16px] border-brand-blue rounded-full opacity-20 z-0"></div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ============================================================
         KONTEN: TATA TERTIB & NORMA SEKOLAH
         ============================================================ -->
    <section id="tata-tertib" class="py-16 bg-[#FAFCFF] relative overflow-hidden">
        <!-- Dot Pattern Top Right -->
        <div class="absolute top-10 right-10 w-32 h-32 dot-pattern opacity-40 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <!-- Header -->
            <div class="text-center max-w-3xl mx-auto mb-14">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Tata Tertib & Norma Sekolah
                </h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
                
                <!-- LEFT: Text & Download Card (Buku Saku) -->
                <div class="lg:col-span-5 space-y-6">
                    <p class="text-slate-600 text-base leading-relaxed">
                        Kedisiplinan adalah kunci kesuksesan. Kami menerapkan aturan yang bertujuan membentuk integritas dan profesionalisme siswa sebelum terjun ke dunia industri.
                    </p>

                    @php
                        $bukuSaku = $tataTertibs->first(function ($t) {
                            return str_contains(strtolower($t->judul ?? ''), 'buku saku') || !empty($t->file_pdf);
                        });
                    @endphp

                    <!-- Download Card -->
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm card-shadow">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-full bg-blue-50 text-brand-blue flex items-center justify-center text-xl">
                                <i class="fa-regular fa-file-pdf"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">{{ $bukuSaku ? $bukuSaku->judul : 'Buku Saku Siswa' }}</h3>
                                <p class="text-xs text-slate-500">
                                    {{ $bukuSaku && $bukuSaku->deskripsi ? Str::limit($bukuSaku->deskripsi, 90) : 'Unduh panduan lengkap peraturan sekolah format PDF untuk referensi di rumah.' }}
                                </p>
                            </div>
                        </div>

                        <!-- Download Button (PDF Utama yang mencakup semua tata tertib) -->
                        <a href="{{ route('kesiswaan.buku-saku.pdf') }}"
                            class="w-full bg-brand-blue hover:bg-brand-darkBlue text-white font-bold text-sm py-3.5 rounded-xl transition-all shadow-md flex items-center justify-center gap-2">
                            <i class="fa-solid fa-download"></i> Download PDF Tata Tertib
                        </a>
                    </div>
                </div>

                <!-- RIGHT: Accordions / Dropdown (Daftar Tata Tertib Dinamis - Tanpa tombol download per butir) -->
                <div class="lg:col-span-7 space-y-4">
                    @forelse ($tataTertibs as $item)
                        @if ($bukuSaku && $item->tata_tertibID === $bukuSaku->tata_tertibID && $tataTertibs->count() > 1)
                            @continue
                        @endif
                        <details class="group bg-brand-blue text-white rounded-2xl overflow-hidden shadow-md transition-all">
                            <summary class="flex items-center justify-between p-5 cursor-pointer font-bold text-lg">
                                <span>{{ $item->judul }}</span>
                                <i class="fa-solid fa-chevron-down transition-transform group-open:rotate-180"></i>
                            </summary>
                            <div class="px-5 pb-5 text-blue-100 text-sm leading-relaxed whitespace-pre-line">
                                {{ $item->deskripsi }}
                            </div>
                        </details>
                    @empty
                        <!-- Default Accordions jika belum ada data -->
                        <details class="group bg-brand-blue text-white rounded-2xl overflow-hidden shadow-md transition-all">
                            <summary class="flex items-center justify-between p-5 cursor-pointer font-bold text-lg">
                                <span>Aturan Seragam</span>
                                <i class="fa-solid fa-chevron-down transition-transform group-open:rotate-180"></i>
                            </summary>
                            <div class="px-5 pb-5 text-blue-100 text-sm leading-relaxed">
                                Siswa wajib mengenakan seragam lengkap sesuai jadwal yang ditentukan, termasuk atribut sekolah, sepatu hitam, dan kerapian.
                            </div>
                        </details>
                        <details class="group bg-brand-blue text-white rounded-2xl overflow-hidden shadow-md transition-all">
                            <summary class="flex items-center justify-between p-5 cursor-pointer font-bold text-lg">
                                <span>Kehadiran & Jam Belajar</span>
                                <i class="fa-solid fa-chevron-down transition-transform group-open:rotate-180"></i>
                            </summary>
                            <div class="px-5 pb-5 text-blue-100 text-sm leading-relaxed">
                                Siswa diharapkan hadir 15 menit sebelum bel masuk berbunyi. Keterlambatan dan absensi tanpa keterangan akan dicatat dalam buku kedisiplinan.
                            </div>
                        </details>
                        <details class="group bg-brand-blue text-white rounded-2xl overflow-hidden shadow-md transition-all">
                            <summary class="flex items-center justify-between p-5 cursor-pointer font-bold text-lg">
                                <span>Penggunaan Gadget</span>
                                <i class="fa-solid fa-chevron-down transition-transform group-open:rotate-180"></i>
                            </summary>
                            <div class="px-5 pb-5 text-blue-100 text-sm leading-relaxed">
                                Penggunaan gadget diperbolehkan hanya untuk keperluan pembelajaran di dalam kelas dengan izin guru pengampu.
                            </div>
                        </details>
                    @endforelse
                </div>

            </div>
        </div>
    </section>

    <!-- ============================================================
         KONTEN: EKSTRAKURIKULER & ORGANISASI (SEPERTI FIGMA DESIGN)
         ============================================================ -->
    <section id="ekstrakurikuler" class="py-16 bg-[#FAFCFF] relative overflow-hidden">
        <!-- Plus Pattern Top Right -->
        <div class="absolute top-4 right-4 sm:top-8 sm:right-8 w-32 h-32 sm:w-40 sm:h-40 plus-tex opacity-70 pointer-events-none z-0"></div>
        <!-- Plus Pattern Bottom Left -->
        <div class="absolute bottom-4 left-4 sm:bottom-8 sm:left-8 w-32 h-32 sm:w-40 sm:h-40 plus-tex opacity-70 pointer-events-none z-0"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <!-- Header -->
            <div class="text-center max-w-3xl mx-auto mb-14">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-3 tracking-tight">
                    Ekstrakurikuler & Organisasi
                </h2>
                <p class="text-slate-500 text-sm sm:text-base leading-relaxed">
                    Pilih organisasi yang sesuai dengan minat dan bakatmu untuk mengasah soft-skill, kepemimpinan, dan jejaring pertemanan.
                </p>
            </div>

            <!-- Grid Organisasi (Layout Panjang-Pendek Asimetris Sesuai Mockup Template) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-7 items-stretch">
                @forelse ($ekstrakurikulers as $eskul)
                    @php
                        $rowIndex = floor($loop->index / 2);
                        $isEvenRow = ($rowIndex % 2) === 0;
                        $isFirstInRow = ($loop->index % 2) === 0;

                        // Pola Panjang - Pendek (Sesuai Foto Mockup):
                        // Baris 1: Kartu Kiri PANJANG (col-span-7), Kartu Kanan PENDEK (col-span-5)
                        // Baris 2: Kartu Kiri PENDEK (col-span-5), Kartu Kanan PANJANG (col-span-7)
                        // Baris 3: Kartu Kiri PANJANG (col-span-7), Kartu Kanan PENDEK (col-span-5)
                        $colSpan = $isEvenRow 
                            ? ($isFirstInRow ? 'lg:col-span-7' : 'lg:col-span-5')
                            : ($isFirstInRow ? 'lg:col-span-5' : 'lg:col-span-7');

                        // Posisi Logo:
                        // Baris 1 & 3: Logo di KIRI (sm:flex-row)
                        // Baris 2: Logo di KANAN (sm:flex-row-reverse)
                        $isReversed = ! $isEvenRow;

                        $namaRaw = $eskul->nama;
                        $isOrganisasiPrefix = str_starts_with(strtolower($namaRaw), 'organisasi ');
                        $prefix = $isOrganisasiPrefix ? 'Organisasi ' : '';
                        $highlightName = $isOrganisasiPrefix ? substr($namaRaw, 11) : $namaRaw;
                    @endphp
                    <div class="{{ $colSpan }} bg-white rounded-2xl p-5 sm:p-6 shadow-[0_4px_25px_rgba(0,0,0,0.06)] border border-slate-100/90 flex flex-col {{ $isReversed ? 'sm:flex-row-reverse' : 'sm:flex-row' }} items-center justify-between gap-5 sm:gap-6 hover:shadow-xl transition-all duration-300">
                        <!-- Logo Organisasi -->
                        <div class="{{ str_contains($colSpan, 'col-span-7') ? 'w-28 h-28 sm:w-34 sm:h-34' : 'w-24 h-24 sm:w-28 sm:h-28' }} shrink-0 flex items-center justify-center p-1.5">
                            @if ($eskul->logoUrl())
                                <img src="{{ $eskul->logoUrl() }}" alt="Logo {{ $eskul->nama }}" class="max-w-full max-h-full object-contain drop-shadow-sm">
                            @else
                                <div class="w-20 h-20 rounded-full bg-blue-50 flex items-center justify-center text-[#0066C4] text-2xl">
                                    <i class="fa-solid fa-users"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Informasi Organisasi -->
                        <div class="flex-1 flex flex-col justify-between self-stretch text-left">
                            <div>
                                <h3 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight leading-snug">
                                    {{ $prefix }}<span class="text-[#0066C4] font-black">{{ $highlightName }}</span>
                                </h3>
                                <p class="text-[11px] sm:text-xs font-semibold text-slate-800 mt-0.5 mb-2">
                                    {{ $eskul->sekolah ?: 'SMKN 2 Karanganyar' }}
                                </p>
                                <p class="text-xs sm:text-[13px] text-slate-500 leading-relaxed line-clamp-3">
                                    {{ $eskul->deskripsi }}
                                </p>
                            </div>
                            <div class="pt-4">
                                <button onclick="showDetailEskulDynamic({{ json_encode([
                                    'nama' => $eskul->nama,
                                    'sekolah' => $eskul->sekolah,
                                    'deskripsi' => $eskul->deskripsi,
                                    'logo' => $eskul->logoUrl(),
                                    'dokumentasi' => $eskul->dokumentasiUrl()
                                ]) }})" class="bg-[#0066C4] hover:bg-blue-700 text-white font-semibold text-xs px-5 py-2.5 rounded-lg shadow-sm transition-all transform active:scale-95 w-fit">
                                    Selengkapnya
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-slate-500 text-sm bg-white rounded-2xl border border-slate-200">
                        Belum ada data organisasi atau ekstrakurikuler yang ditambahkan.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- ============================================================
         KONTEN: PRESTASI
         ============================================================ -->
    <section id="prestasi" class="py-16 bg-[#F8FAFC] relative overflow-hidden">
        <!-- Plus Texture Clusters (Figma style) -->
        <div class="absolute right-0 top-[260px] w-24 h-44 plus-tex opacity-50 pointer-events-none hidden sm:block"></div>
        <div class="absolute -left-2 bottom-12 w-28 h-36 plus-tex opacity-50 pointer-events-none hidden sm:block"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <!-- Header with Badge (Hardcoded 100+) -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-6">
                <div>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-3 tracking-tight">
                        Semua Prestasi
                    </h2>
                    <p class="text-slate-500 text-sm sm:text-base leading-relaxed max-w-2xl">
                        Dedikasi dan kerja keras siswa-siswi terbaik kami dalam mengharumkan nama sekolah di kancah nasional maupun internasional.
                    </p>
                </div>
                <!-- Badge Hardcoded 100+ -->
                <div class="bg-[#B9D5F9] border border-[#9AC5F4] rounded-2xl px-5 py-3 flex items-center gap-3.5 shadow-sm min-w-[130px] self-start md:self-auto">
                    <i class="fa-solid fa-trophy text-[#0066C4] text-2xl"></i>
                    <div class="text-left">
                        <span class="block text-[10px] font-bold text-slate-600 tracking-wider leading-none mb-1 uppercase">PRESTASI</span>
                        <span class="block text-2xl font-black text-[#0066C4] leading-none">100+</span>
                    </div>
                </div>
            </div>

            <!-- ============================================================
                 CONTAINER: PRESTASI TERBARU (CAROUSEL BANNER)
                 ============================================================ -->
            <div class="bg-white rounded-3xl p-6 sm:p-7 shadow-sm border border-slate-100 relative mb-12">
                <!-- Tab Heading -->
                <div class="mb-5">
                    <span class="text-sm sm:text-base font-bold text-slate-800 border-b-2 border-brand-blue pb-1 inline-block tracking-tight">
                        Prestasi Terbaru
                    </span>
                </div>

                <!-- Slider wrapper with relative arrow buttons -->
                <div class="relative flex items-center">
                    <!-- Prev Button -->
                    <button onclick="scrollPrestasiBanner(-1)" type="button" aria-label="Sebelumnya"
                        class="absolute -left-3 sm:-left-4 z-20 w-8 h-8 rounded-full bg-slate-200/90 hover:bg-slate-300 text-slate-600 flex items-center justify-center shadow-md transition-all active:scale-95">
                        <i class="fa-solid fa-chevron-left text-xs"></i>
                    </button>

                    <!-- Slider Content -->
                    <div id="prestasi-banner-slider" class="w-full flex gap-4 sm:gap-6 overflow-x-auto scroll-smooth snap-x snap-mandatory scrollbar-none py-1">
                        <!-- Banner 1: LKBB-PB Nasional -->
                        <div class="min-w-[280px] sm:min-w-[420px] md:min-w-[calc(50%-12px)] flex-1 snap-start rounded-2xl overflow-hidden shadow-sm bg-gradient-to-r from-[#0a1a44] via-[#0f2a6b] to-[#0a183d] flex items-center justify-center cursor-pointer group"
                             onclick="openModal('SMKN 2 KARANGANYAR SIAP PERTAHANKAN GELAR JATENG DI LKBB-PB NASIONAL', 'SMK Negeri 2 Karanganyar siap mempertahankan gelar juara Jawa Tengah di ajang LKBB-PB Nasional dengan persiapan matang dan dedikasi tim terbaik.', '{{ asset('assets/prestasi/banner_terbaru_1.png') }}')">
                            <img src="{{ asset('assets/prestasi/banner_terbaru_1.png') }}" alt="Banner LKBB-PB" class="w-full h-auto object-cover rounded-xl transition-transform duration-300 group-hover:scale-[1.02]">
                        </div>

                        <!-- Banner 2: Selamat dan Sukses -->
                        <div class="min-w-[280px] sm:min-w-[420px] md:min-w-[calc(50%-12px)] flex-1 snap-start rounded-2xl overflow-hidden shadow-sm bg-gradient-to-r from-[#1c384a] via-[#244b63] to-[#162f3f] flex items-center justify-center cursor-pointer group"
                             onclick="openModal('SMKN 2 KARANGANYAR MENGUCAPKAN SELAMAT DAN SUKSES !', 'Apresiasi setinggi-tingginya kepada seluruh civitas akademika dan siswa-siswi berprestasi atas dedikasi dan kontribusi luar biasa untuk sekolah tercinta.', '{{ asset('assets/prestasi/banner_terbaru_2.png') }}')">
                            <img src="{{ asset('assets/prestasi/banner_terbaru_2.png') }}" alt="Banner Selamat dan Sukses" class="w-full h-auto object-cover rounded-xl transition-transform duration-300 group-hover:scale-[1.02]">
                        </div>
                    </div>

                    <!-- Next Button -->
                    <button onclick="scrollPrestasiBanner(1)" type="button" aria-label="Selanjutnya"
                        class="absolute -right-3 sm:-right-4 z-20 w-8 h-8 rounded-full bg-slate-200/90 hover:bg-slate-300 text-slate-600 flex items-center justify-center shadow-md transition-all active:scale-95">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </button>
                </div>
            </div>

            <!-- Grid Prestasi (4 Kolom per Baris) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12 relative z-10">
                @forelse ($prestasis as $item)
                    @php
                        $prestasiImg = $item->dokumentasiUrl() ?: asset('assets/prestasi/prestasi_' . (($loop->index % 8) + 1) . '.png');
                    @endphp
                    <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100 hover:shadow-lg transition-all duration-300 flex flex-col group">
                        <div class="h-44 bg-slate-100 relative overflow-hidden flex items-center justify-center">
                            <img src="{{ $prestasiImg }}" alt="{{ $item->judul }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                        </div>
                        <div class="p-4 flex flex-col flex-grow justify-between">
                            <p class="text-[11px] sm:text-xs text-slate-600 leading-relaxed mb-4 flex-grow line-clamp-4">
                                {{ $item->deskripsi }}
                            </p>
                            <div class="flex justify-end pt-1">
                                @if (Str::contains(strtolower($item->deskripsi), 'snbt'))
                                    <button onclick="openModal('{{ addslashes($item->judul) }}', '{{ addslashes($item->deskripsi) }}', '{{ $prestasiImg }}')" 
                                        class="bg-[#0066C4] hover:bg-blue-700 text-white text-[10px] font-bold px-3.5 py-1 rounded-full uppercase tracking-wider transition-colors shadow-sm">
                                        Selanjutnya
                                    </button>
                                @else
                                    <button onclick="openModal('{{ addslashes($item->judul) }}', '{{ addslashes($item->deskripsi) }}', '{{ $prestasiImg }}')" 
                                        class="bg-[#374151] hover:bg-slate-900 text-white text-[10px] font-bold px-3.5 py-1 rounded-full uppercase tracking-wider transition-colors shadow-sm">
                                        Lihat Detail
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-10 text-center text-slate-500 text-sm bg-white rounded-2xl border border-slate-200">
                        Belum ada prestasi yang ditambahkan.
                    </div>
                @endforelse
            </div>

        </div>
    </section>

    <!-- ============================================================
         MODAL DETAIL EKSTRAKURIKULER
         ============================================================ -->
    <div id="detail-eskul-modal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl max-w-4xl w-full max-h-[90vh] overflow-y-auto shadow-2xl relative transform transition-all scale-95 opacity-0" id="detail-eskul-content">
            
            <!-- Modal Header -->
            <div class="sticky top-0 bg-white z-10 px-6 py-4 border-b border-slate-100 flex items-center justify-between rounded-t-3xl">
                <h3 class="text-lg font-bold text-slate-800">Detail Ekstrakurikuler</h3>
                <button onclick="closeDetailEskul()" class="text-slate-400 hover:text-slate-600 text-xl w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100 transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 sm:p-8">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start mb-8">
                    <!-- Logo & Title -->
                    <div class="md:col-span-4 flex flex-col items-center text-center">
                        <div id="modalLogoWrap" class="w-32 h-32 rounded-full bg-slate-50 flex items-center justify-center border-4 border-brand-blue/30 p-2 mb-4 shadow-lg overflow-hidden">
                            <img id="detail-eskul-logo" src="" alt="Logo Eskul" class="w-full h-full object-contain">
                        </div>
                        <h2 id="detail-eskul-title" class="text-2xl font-black text-slate-900 mb-1"></h2>
                        <p id="detail-eskul-school" class="text-sm font-semibold text-brand-blue">SMKN 2 Karanganyar</p>
                    </div>

                    <!-- Deskripsi -->
                    <div class="md:col-span-8">
                        <h3 class="text-lg font-bold text-slate-800 mb-3 border-b-2 border-brand-blue inline-block pb-1">Deskripsi</h3>
                        <div id="detail-eskul-desc" class="text-slate-600 text-sm leading-relaxed space-y-3 whitespace-pre-line"></div>
                    </div>
                </div>

                <!-- Dokumentasi Foto -->
                <div id="detail-eskul-doc-wrap">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Dokumentasi Kegiatan</h3>
                    <div class="rounded-2xl overflow-hidden shadow-md max-h-72 bg-slate-100">
                        <img id="detail-eskul-doc" src="" alt="Dokumentasi Kegiatan" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- GLOBAL INFO / PRESTASI MODAL -->
    <div id="global-modal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl max-w-lg w-full overflow-hidden shadow-2xl relative text-left transform transition-all">
            <button onclick="closeModal()" type="button" aria-label="Tutup"
                class="absolute top-3 right-3 z-20 w-8 h-8 rounded-full bg-slate-900/60 hover:bg-slate-900 text-white flex items-center justify-center transition shadow">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
            <div id="global-modal-img-wrap" class="h-60 sm:h-64 bg-slate-100 hidden relative overflow-hidden flex items-center justify-center">
                <img id="global-modal-img" src="" alt="Prestasi" class="w-full h-full object-cover">
            </div>
            <div class="p-6">
                <h4 id="global-modal-title" class="text-base sm:text-lg font-bold text-slate-900 mb-2 leading-snug"></h4>
                <p id="global-modal-body" class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-6"></p>
                <div class="flex justify-end">
                    <button onclick="closeModal()" class="px-5 py-2 bg-brand-blue hover:bg-brand-darkBlue text-white font-bold rounded-xl text-xs transition shadow-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function showDetailEskulDynamic(item) {
            document.getElementById('detail-eskul-title').textContent = item.nama;
            document.getElementById('detail-eskul-school').textContent = item.sekolah || 'SMKN 2 Karanganyar';
            document.getElementById('detail-eskul-desc').textContent = item.deskripsi || 'Belum ada deskripsi untuk ekstrakurikuler ini.';

            const logo = document.getElementById('detail-eskul-logo');
            if (item.logo) {
                logo.src = item.logo;
                document.getElementById('modalLogoWrap').classList.remove('hidden');
            } else {
                document.getElementById('modalLogoWrap').classList.add('hidden');
            }

            const docWrap = document.getElementById('detail-eskul-doc-wrap');
            const doc = document.getElementById('detail-eskul-doc');
            if (item.dokumentasi) {
                doc.src = item.dokumentasi;
                docWrap.classList.remove('hidden');
            } else {
                docWrap.classList.add('hidden');
            }

            const modal = document.getElementById('detail-eskul-modal');
            const content = document.getElementById('detail-eskul-content');
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeDetailEskul() {
            const modal = document.getElementById('detail-eskul-modal');
            const content = document.getElementById('detail-eskul-content');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        }

        function openModal(title, body, imageUrl = null) {
            document.getElementById('global-modal-title').textContent = title;
            document.getElementById('global-modal-body').textContent = body;
            const imgWrap = document.getElementById('global-modal-img-wrap');
            const imgEl = document.getElementById('global-modal-img');
            const finalImage = imageUrl || "{{ asset('assets/prestasi/prestasi_1.png') }}";
            if (imgWrap && imgEl) {
                imgEl.src = finalImage;
                imgWrap.classList.remove('hidden');
            }
            document.getElementById('global-modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('global-modal').classList.add('hidden');
        }

        function scrollPrestasiBanner(direction) {
            const slider = document.getElementById('prestasi-banner-slider');
            if (!slider) return;
            const scrollAmount = slider.clientWidth * 0.75;
            slider.scrollBy({ left: direction * scrollAmount, behavior: 'smooth' });
        }
    </script>
@endpush