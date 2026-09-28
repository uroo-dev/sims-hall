@extends('Public.layout.app')

@section('title', 'SMK Negeri 2 Karanganyar - Sekolah Pusat Keunggulan')
@section('description', 'Sebagai Sekolah Pusat Keunggulan, SMKN 2 Karanganyar menghadirkan siswa berkualitas dengan standar industri melalui kolaborasi dengan dunia industri.')

@section('content')
    <!-- HERO SECTION (Foto 1) -->
    <section id="hero" class="relative py-12 md:py-20 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

                <!-- Left Text Content -->
                <div class="lg:col-span-6 space-y-6 z-10">
                    <span class="inline-block text-brand-blue font-bold tracking-wide text-base sm:text-lg">
                        Sekolah Pusat Keunggulan
                    </span>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-slate-900 tracking-tight leading-tight">
                        SMKN 2<br />
                        <span class="text-slate-800">KARANGANYAR</span>
                    </h1>
                    <p class="text-slate-600 text-base sm:text-lg leading-relaxed max-w-xl">
                        Sebagai Sekolah Pusat Keunggulan, kami berkomitmen menghadirkan siswa berkualitas dengan standar
                        industri. Kolaborasi dengan dunia industri menjadikan siswa lebih siap menghadapi tantangan
                        kerja dan peluang masa depan.
                    </p>
                    <div class="pt-2">
                        <button
                            onclick="openModal('Tentang SMKN 2 Karanganyar', 'SMKN 2 Karanganyar mencetak lulusan unggul berkarakter, berdaya saing global, serta siap kerja di era transformasi digital.')"
                            class="bg-brand-blue hover:bg-brand-darkBlue text-white font-semibold px-8 py-3.5 rounded-full shadow-lg shadow-blue-500/25 transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                            Pelajari Selengkapnya
                        </button>
                    </div>
                </div>

                <!-- Right Visual: Interactive Moving Illustration Container -->
                <div class="lg:col-span-6 relative flex justify-center items-center group cursor-pointer">
                    <!-- Inner Container diperluas ke max-w-xl -->
                    <div class="w-full max-w-xl relative flex items-center justify-center p-2">
                        <img src="{{ asset('assets/full-jurusan-logo.png') }}" alt="Ilustrasi SMKN 2 Karanganyar"
                            class="w-full h-auto object-contain filter drop-shadow-2xl group-hover:scale-115 group-hover:-translate-y-3 transition-all duration-300 transform-gpu select-none">
                    </div>
                </div>
            </div>

        </div>
        </div>
    </section>




    <!-- KOMPETENSI KEAHLIAN & MITRA DUDI (Foto 2) -->
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

            <!-- 4 Vertical Jurusan Cards Grid (Foto 2) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 pt-12 mb-20">

                <!-- Card 1: Teknik Pemesinan (Blue) -->
                <div class="relative group cursor-pointer pt-16">
                    <!-- Card Container -->
                    <div
                        class="bg-[#5FB0FF] rounded-b-3xl rounded-t-[40px] p-6 text-slate-900 flex flex-col justify-between shadow-xl shadow-blue-500/20 group-hover:-translate-y-3 transition-all duration-300 relative z-10 min-h-[380px]">
                        <!-- Floating 3D Graphic (Overlapping Out of Card) -->
                        <div class="absolute -top-16 left-0 right-0 flex justify-center pointer-events-none">
                            <img src="{{ asset('assets/logo_mesin.png') }}" alt="Teknik Pemesinan"
                                class="w-52 h-auto object-contain filter drop-shadow-xl group-hover:scale-110 group-hover:-translate-y-2 transition-all duration-300 transform-gpu">
                        </div>

                        <!-- Content Area -->
                        <div class="mt-28 space-y-3">
                            <h3 class="text-2xl font-bold tracking-tight text-slate-900 leading-tight">
                                Teknik<br />Pemesinan
                            </h3>
                            <p class="text-slate-800/90 text-sm font-medium leading-relaxed">
                                mempelajari tentang cara memproduksi barang teknik dan menggunakan mesin.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Teknik Pembuatan Kain (Yellow) -->
                <div class="relative group cursor-pointer pt-16">
                    <!-- Card Container -->
                    <div
                        class="bg-[#FCE055] rounded-b-3xl rounded-t-[40px] p-6 text-slate-900 flex flex-col justify-between shadow-xl shadow-amber-500/20 group-hover:-translate-y-3 transition-all duration-300 relative z-10 min-h-[380px]">
                        <!-- Floating 3D Graphic -->
                        <div class="absolute -top-16 left-0 right-0 flex justify-center pointer-events-none">
                            <img src="{{ asset('assets/logo_tekstil.png') }}" alt="Teknik Pembuatan Kain"
                                class="w-52 h-auto object-contain filter drop-shadow-xl group-hover:scale-110 group-hover:-translate-y-2 transition-all duration-300 transform-gpu">
                        </div>

                        <!-- Content Area -->
                        <div class="mt-28 space-y-3">
                            <h3 class="text-2xl font-bold tracking-tight text-slate-900 leading-tight">
                                Teknik<br />Pembuatan Kain
                            </h3>
                            <p class="text-slate-800/90 text-sm font-medium leading-relaxed">
                                mempelajari tentang desain tenun, mesin pembuatan kain, pemeliharaan dan perawatan, dan
                                pengendalian mutunya.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Teknik Ototronik (Red/Coral) -->
                <div class="relative group cursor-pointer pt-16">
                    <!-- Card Container -->
                    <div
                        class="bg-[#FF5A5F] rounded-b-3xl rounded-t-[40px] p-6 text-slate-900 flex flex-col justify-between shadow-xl shadow-red-500/20 group-hover:-translate-y-3 transition-all duration-300 relative z-10 min-h-[380px]">
                        <!-- Floating 3D Graphic -->
                        <div class="absolute -top-16 left-0 right-0 flex justify-center pointer-events-none">
                            <img src="{{ asset('assets/logo_oto.png') }}" alt="Teknik Ototronik"
                                class="w-52 h-auto object-contain filter drop-shadow-xl group-hover:scale-110 group-hover:-translate-y-2 transition-all duration-300 transform-gpu">
                        </div>

                        <!-- Content Area -->
                        <div class="mt-28 space-y-3">
                            <h3 class="text-2xl font-bold tracking-tight text-slate-900 leading-tight">
                                Teknik<br />Ototronik
                            </h3>
                            <p class="text-slate-900/90 text-sm font-medium leading-relaxed">
                                mempelajari tentang otomotif dalam penguasaan teknologi elektronik dan kontrol pada
                                kendaraan bermotor.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Rekayasa Perangkat Lunak (Green) -->
                <div class="relative group cursor-pointer pt-16">
                    <!-- Card Container -->
                    <div
                        class="bg-[#10C863] rounded-b-3xl rounded-t-[40px] p-6 text-slate-900 flex flex-col justify-between shadow-xl shadow-emerald-500/20 group-hover:-translate-y-3 transition-all duration-300 relative z-10 min-h-[380px]">
                        <!-- Floating 3D Graphic -->
                        <div class="absolute -top-16 left-0 right-0 flex justify-center pointer-events-none">
                            <img src="{{ asset('assets/logo_rpl.png') }}" alt="Rekayasa Perangkat Lunak"
                                class="w-52 h-auto object-contain filter drop-shadow-xl group-hover:scale-110 group-hover:-translate-y-2 transition-all duration-300 transform-gpu">
                        </div>

                        <!-- Content Area -->
                        <div class="mt-28 space-y-3">
                            <h3 class="text-2xl font-bold tracking-tight text-slate-900 leading-tight">
                                Rekayasa<br />Perangkat Lunak
                            </h3>
                            <p class="text-slate-900/90 text-sm font-medium leading-relaxed">
                                mempelajari tentang pengembangan perangkat lunak termasuk, pembuatan, pemeliharaan, dan
                                manajemen organisasi.
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- MITRA DUDI Container (Ukuran Diperbesar) -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-8 md:p-10 shadow-md">
                <div class="mb-8 flex items-center gap-4">
                    <h4 class="text-base md:text-lg font-extrabold text-slate-800 tracking-wider uppercase">
                        MITRA DUDI — Kerjasama Industri
                    </h4>
                    <div class="h-[3px] bg-slate-200 flex-1 rounded-full"></div>
                </div>

                <!-- Grid Layout dengan Tinggi Container Minimum h-24 (96px) -->
                <div
                    class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6 lg:gap-10 items-center justify-items-center">

                    <!-- Brand 1: EXP -->
                    <div
                        class="flex items-center justify-center w-full h-24 group hover:scale-110 transition-transform duration-300">
                        <div
                            class="font-black text-3xl md:text-4xl text-blue-800 tracking-tighter border-4 border-blue-800 px-6 py-2 rounded-xl bg-blue-50/50 shadow-sm">
                            EXP
                        </div>
                    </div>

                    <!-- Brand 2: MSM Solo -->
                    <div
                        class="flex items-center justify-center w-full h-24 group hover:scale-110 transition-transform duration-300">
                        <img src="{{ asset('assets/msm.png') }}" alt="Logo MSM Solo"
                            class="h-16 md:h-20 w-auto object-contain filter drop-shadow-md select-none">
                    </div>

                    <!-- Brand 3: NASMOCO -->
                    <div
                        class="flex items-center justify-center w-full h-24 group hover:scale-110 transition-transform duration-300">
                        <img src="{{ asset('assets/Nasmoco.png') }}" alt="Logo Nasmoco"
                            class="h-16 md:h-20 w-auto object-contain filter drop-shadow-md select-none">
                    </div>

                    <!-- Brand 4: TOYOTA -->
                    <div
                        class="flex flex-col items-center justify-center w-full h-24 group hover:scale-110 transition-transform duration-300 cursor-pointer">
                        <img src="{{ asset('assets/Toyota.png') }}" alt="Logo Nasmoco"
                            class="h-16 md:h-20 w-auto object-contain filter drop-shadow-md select-none">
                    </div>

                    <!-- Brand 5: PT YICHAO TEXTILE -->
                    <div
                        class="flex flex-col items-center justify-center w-full h-24 text-center group hover:scale-110 transition-transform duration-300">
                        <img src="{{ asset('assets/textil.png') }}" alt="Logo Nasmoco"
                            class="h-16 md:h-20 w-auto object-contain filter drop-shadow-md select-none">
                    </div>

                </div>
            </div>

        </div>
    </section>

    <!-- KESISWAAN & PRESTASI TERBARU (Foto 3) -->
    <section id="kesiswaan" class="py-16 bg-[#F8FAFC] relative">
        <!-- Decorative Dot Pattern (Top Left) -->
        <div class="absolute top-8 left-8 w-24 h-24 dot-pattern opacity-40 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            <!-- Section Header -->
            <div class="mb-12">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-2">
                    Kesiswaan
                </h2>
                <p class="text-slate-600 text-base">
                    Membentuk karakter, kedisiplinan, dan potensi non-akademik.
                </p>
            </div>

            <!-- Grid Kesiswaan Cards (Foto 3) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-16">

                <!-- Left: Card LOLOS SNBT 2026 (Foto 3 Left) -->
                <div
                    class="lg:col-span-6 bg-white border border-slate-200 rounded-2xl p-6 card-shadow flex flex-col justify-between">
                    <div>
                        <div
                            class="bg-blue-900 text-white font-bold text-center py-2.5 rounded-lg mb-6 tracking-wide text-sm sm:text-base">
                            LOLOS SNBT (Seleksi Nasional Berdasarkan Tes) Tahun 2026
                        </div>

                        <!-- 4 Student Photo Grids -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
                            <!-- Student 1 -->
                            <div class="text-center group">
                                <div class="bg-slate-100 rounded-lg overflow-hidden mb-2 aspect-[3/4] relative border">
                                    <img src="https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=200&auto=format&fit=crop&q=80"
                                        alt="Nofal Mita"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                    <span
                                        class="absolute top-1 right-1 bg-red-600 text-white text-[9px] font-bold px-1 rounded">12
                                        MA</span>
                                </div>
                                <div class="text-xs font-bold text-slate-800">Nofal Mita Hulhaq</div>
                                <div class="text-[10px] text-slate-500">UNS: TEKNIK MESIN</div>
                            </div>
                            <!-- Student 2 -->
                            <div class="text-center group">
                                <div class="bg-slate-100 rounded-lg overflow-hidden mb-2 aspect-[3/4] relative border">
                                    <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200&auto=format&fit=crop&q=80"
                                        alt="Raras Putri"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                    <span
                                        class="absolute top-1 right-1 bg-red-600 text-white text-[9px] font-bold px-1 rounded">12
                                        RB</span>
                                </div>
                                <div class="text-xs font-bold text-slate-800">Raras Putri Febriana</div>
                                <div class="text-[10px] text-slate-500">UNS: PTIK</div>
                            </div>
                            <!-- Student 3 -->
                            <div class="text-center group">
                                <div class="bg-slate-100 rounded-lg overflow-hidden mb-2 aspect-[3/4] relative border">
                                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&auto=format&fit=crop&q=80"
                                        alt="Davin Wahyu"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                    <span
                                        class="absolute top-1 right-1 bg-red-600 text-white text-[9px] font-bold px-1 rounded">12
                                        RB</span>
                                </div>
                                <div class="text-xs font-bold text-slate-800">Davin Wahyu Amanta</div>
                                <div class="text-[10px] text-slate-500">UNS: PTIK</div>
                            </div>
                            <!-- Student 4 -->
                            <div class="text-center group">
                                <div class="bg-slate-100 rounded-lg overflow-hidden mb-2 aspect-[3/4] relative border">
                                    <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=200&auto=format&fit=crop&q=80"
                                        alt="Faiz Bayu"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                    <span
                                        class="absolute top-1 right-1 bg-red-600 text-white text-[9px] font-bold px-1 rounded">12
                                        RA</span>
                                </div>
                                <div class="text-xs font-bold text-slate-800">M. Faiz Bayu Nur A.</div>
                                <div class="text-[10px] text-slate-500">UNS: MANAJEMEN</div>
                            </div>
                        </div>

                        <p class="text-slate-600 text-xs text-center leading-relaxed">
                            Selamat dan Sukses bagi peserta didik SMKN 2 KARANGANYAR yang telah Lolos SNBT (Seleksi
                            Nasional Berdasarkan Tes) Tahun 2026.
                        </p>
                    </div>

                    <div class="text-right mt-4">
                        <span
                            class="inline-block bg-brand-blue text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase">
                            AKADEMIK
                        </span>
                    </div>
                </div>

                <!-- Middle: Ekstrakurikuler Card -->
                <div
                    class="lg:col-span-3 bg-white border border-slate-200 rounded-2xl p-6 card-shadow flex flex-col justify-between hover:border-brand-blue transition-colors">
                    <div>
                        <div
                            class="w-12 h-12 rounded-full bg-blue-50 text-brand-blue flex items-center justify-center text-2xl mb-6">
                            <i class="fa-solid fa-futbol"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-3">Ekstrakurikuler</h3>
                        <p class="text-slate-600 text-sm leading-relaxed mb-6">
                            Pilihan kegiatan mulai dari kedisiplinan, olahraga, hingga klub teknologi.
                        </p>
                    </div>
                    <button
                        onclick="openModal('Pendaftaran Ekstrakurikuler', 'Silahkan pilih ekstrakurikuler yang Anda minati: Paskibra, Pramuka, Futsal, Basket, Coding Club, atau Robotik.')"
                        class="inline-flex items-center text-brand-blue font-bold text-sm hover:translate-x-1 transition-transform">
                        Daftar Eskul <i class="fa-solid fa-chevron-right text-xs ml-1"></i>
                    </button>
                </div>

                <!-- Right: Tata Tertib Card (Blue Solid) -->
                <div
                    class="lg:col-span-3 bg-brand-blue text-white rounded-2xl p-6 card-shadow flex flex-col justify-between">
                    <div>
                        <div class="text-2xl mb-6">
                            <i class="fa-solid fa-scale-balanced"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-3">Tata Tertib</h3>
                        <p class="text-blue-100 text-sm leading-relaxed mb-6">
                            Pedoman kedisiplinan siswa untuk membentuk etos kerja profesional.
                        </p>
                    </div>
                    <button
                        onclick="openModal('Unduh Tata Tertib PDF', 'Mengunduh berkas lengkap Tata Tertib Siswa SMKN 2 Karanganyar Tahun Ajaran 2026/2027...')"
                        class="bg-white/20 hover:bg-white/30 text-white font-semibold text-xs py-2.5 px-4 rounded-lg w-fit transition-colors">
                        Unduh PDF
                    </button>
                </div>

            </div>

            <!-- PRESTASI TERBARU SECTION (Foto 3 Bottom) -->
            <div class="bg-white rounded-2xl p-6 sm:p-8 card-shadow border border-slate-100 relative">
                <div class="mb-6">
                    <h4 class="text-lg font-bold text-slate-800 border-b-2 border-brand-blue inline-block pb-1">Prestasi
                        Terbaru</h4>
                </div>

                <!-- Interactive News Slider Carousel -->
                <div class="relative">
                    <div id="news-container" class="grid grid-cols-1 md:grid-cols-2 gap-6 transition-all duration-300">
                        <!-- Card News 1 -->
                        <div
                            class="bg-gradient-to-r from-blue-900 to-indigo-900 rounded-xl overflow-hidden p-6 text-white relative group">
                            <span
                                class="inline-block bg-blue-600 text-white text-[10px] font-bold px-2.5 py-1 rounded mb-3 uppercase">
                                BERITA HARI INI
                            </span>
                            <h4
                                class="text-lg sm:text-xl font-bold leading-snug mb-4 group-hover:text-blue-200 transition-colors">
                                SMKN 2 KARANGANYAR SIAP PERTAHANKAN GELAR JATENG DI LKBB-PB NASIONAL
                            </h4>
                        </div>

                        <!-- Card News 2 -->
                        <div
                            class="bg-gradient-to-r from-emerald-800 to-teal-900 rounded-xl overflow-hidden p-6 text-white relative group">
                            <div class="text-xs font-semibold text-emerald-300 mb-1">SMKN 2 KARANGANYAR MENGUCAPKAN
                            </div>
                            <h4
                                class="text-2xl sm:text-3xl font-black italic tracking-wide text-amber-300 group-hover:scale-105 transition-transform">
                                SELAMAT DAN SUKSES !
                            </h4>
                            <p class="text-xs text-emerald-100 mt-2">Juara 1 Lomba Kompetensi Siswa (LKS) Bidang CNC
                                Milling 2026</p>
                        </div>
                    </div>

                    <!-- Slider Arrows Controls -->
                    <button id="prev-news"
                        class="absolute -left-4 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-white shadow-md border border-slate-200 flex items-center justify-center text-slate-700 hover:text-brand-blue transition-colors">
                        <i class="fa-solid fa-chevron-left text-xs"></i>
                    </button>
                    <button id="next-news"
                        class="absolute -right-4 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-white shadow-md border border-slate-200 flex items-center justify-center text-slate-700 hover:text-brand-blue transition-colors">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </button>
                </div>
            </div>

        </div>
    </section>

    <!-- PRODUK UNGGULAN (Foto 4) -->
    <section id="produk" class="py-16 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-4">
                    Produk Unggulan
                </h2>
                <p class="text-slate-600 text-base sm:text-lg">
                    Beragam produk unggulan berbasis teknologi dan industri yang mencerminkan keterampilan siswa sesuai
                    kebutuhan dunia kerja.
                </p>
            </div>

            <!-- 4 Products Grid (Foto 4) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                <!-- Product 1: Teknik Permesinan (Blue Gradient) -->
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
                        <button
                            onclick="openModal('Katalog Teknik Permesinan', 'Menampilkan daftar produk presisi tinggi, sparepart custom, dan komponen mesin buatan siswa.')"
                            class="bg-slate-900 text-white text-xs font-bold px-5 py-2.5 rounded-lg uppercase tracking-wider hover:bg-slate-800 transition-colors">
                            SEMUA PRODUK
                        </button>
                    </div>
                    <!-- Right Mockup Visual (Bolt / Hardware) -->
                    <div class="absolute right-5 bottom-10 w-32 h-32 opacity-90">
                        <img src="{{ asset('assets/produk-mesin.png') }}" alt="Bolt / Hardware" class="w-full h-full object-contain">
                    </div>
                </div>

                <!-- Product 2: Teknik Pembuatan Kain (Yellow Gradient) -->
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
                        <button
                            onclick="openModal('Katalog Teknik Pembuatan Kain', 'Menampilkan koleksi kain Batik tulis, Tenun tradisional, dan Ecoprint ramah lingkungan.')"
                            class="bg-slate-900 text-white text-xs font-bold px-5 py-2.5 rounded-lg uppercase tracking-wider hover:bg-slate-800 transition-colors">
                            SEMUA PRODUK
                        </button>
                    </div>
                    <!-- Right Mockup Visual (Fashion Textile) -->
                    <div class="absolute right-5 bottom-10 w-32 h-36 flex items-center justify-center opacity-90">
                        <img src="{{ asset('assets/produk-tpk.png') }}" alt="Bolt / Hardware" class="w-full h-full object-contain">
                    </div>
                </div>

                <!-- Product 3: RPL (Green Gradient) -->
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
                        <button
                            onclick="openModal('Portofolio Produk RPL', 'Layanan pembuatan Website, Aplikasi Android, Sistem Kasir POS, dan UI/UX Design oleh Teaching Factory RPL.')"
                            class="bg-slate-900 text-white text-xs font-bold px-5 py-2.5 rounded-lg uppercase tracking-wider hover:bg-slate-800 transition-colors">
                            SEMUA PRODUK
                        </button>
                    </div>
                    <!-- Right Mockup Visual (Laptop & Smartphone Screen) -->
                    <div class="absolute right-5 bottom-10 w-32 h-36 flex items-center justify-center opacity-90">
                        <img src="{{ asset('assets/produk-rpl.png') }}" alt="Bolt / Hardware" class="w-full h-full object-contain">
                    </div>
                </div>

                <!-- Product 4: Teknik Ototronik (Red Gradient) -->
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
                        <button
                            onclick="openModal('Layanan Teknik Ototronik', 'Jasa tune-up mesin injeksi, diagnosa scanner komputerisasi, dan kelistrikan otomotif.')"
                            class="bg-slate-900 text-white text-xs font-bold px-5 py-2.5 rounded-lg uppercase tracking-wider hover:bg-slate-800 transition-colors">
                            SEMUA PRODUK
                        </button>
                    </div>
                    <!-- Right Mockup Visual (Car Parts) -->
                    <div class="absolute right-5 bottom-10 w-32 h-36 flex items-center justify-center opacity-90">
                        <img src="{{ asset('assets/produk-oto.png') }}" alt="Bolt / Hardware" class="w-full h-full object-contain">
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- LAYANAN PEMINJAMAN AULA -->
    <section id="aula" class="py-16 bg-[#FAFCFF] relative overflow-hidden font-sans">

        <!-- Pattern Titik-Titik (Dot Pattern) di Pojok Kanan Atas -->
        <div class="absolute top-8 right-8 w-28 h-28 dot-pattern opacity-40 pointer-events-none"></div>


        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            <!-- Header Utama -->
            <div class="text-center max-w-3xl mx-auto mb-14">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-3 tracking-tight">
                    Layanan Peminjaman Aula
                </h2>
                <p class="text-slate-500 text-sm sm:text-base leading-relaxed">
                    Fasilitas sekolah dengan kapasitas luas untuk berbagai kebutuhan acara institusi,<br
                        class="hidden sm:block"> perusahaan, dan masyarakat umum.
                </p>
            </div>

            <!-- Layout Utama: Kiri (Kartu Harga) & Kanan (Detail Informasi) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">

                <!-- SISI KIRI: Pricing Cards (Kapasitas 4 Kolom) -->
                <div class="lg:col-span-4 flex flex-col justify-between gap-6">

                    <!-- 1. Paket Unggulan Card -->
                    <div class="bg-white border-2 border-[#0066B2] rounded-2xl p-6 relative shadow-sm">
                        <!-- Pill Badge Kapsul -->
                        <div
                            class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-[#0066B2] text-white text-xs font-semibold px-8 py-1 rounded-full">
                            Unggulan
                        </div>

                        <!-- Harga -->
                        <div class="mt-2 mb-5 flex items-baseline justify-center gap-1">
                            <span class="text-xs font-medium text-slate-400">Rp.</span>
                            <span class="text-2xl sm:text-3xl font-bold text-[#0066B2]">6.000.000</span>
                            <span class="text-xs text-slate-400">/ 12 Jam</span>
                        </div>

                        <!-- List Fasilitas -->
                        <ul class="space-y-2.5 mb-6 text-slate-700 text-xs sm:text-sm">
                            <li class="flex items-center gap-2.5">
                                <i class="fa-regular fa-circle-check text-[#0066B2] text-base"></i>
                                <span>Sound System Medium</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <i class="fa-regular fa-circle-check text-[#0066B2] text-base"></i>
                                <span>Mic 4</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <i class="fa-regular fa-circle-check text-[#0066B2] text-base"></i>
                                <span>500 Kursi + Cover</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <i class="fa-regular fa-circle-check text-[#0066B2] text-base"></i>
                                <span>Proyektor 2</span>
                            </li>
                        </ul>

                        <!-- Tombol Aksi -->
                        <button
                            onclick="openModal('Pemesanan Paket Unggulan', 'Form reservasi Aula 12 Jam (Rp 6.000.000).')"
                            class="w-full bg-[#0066B2] hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold py-2.5 rounded-full transition-all duration-200">
                            Pilih Paket
                        </button>
                    </div>

                    <!-- 2. Paket Terjangkau Card -->
                    <div class="bg-white border-2 border-[#0066B2] rounded-2xl p-6 relative shadow-sm">
                        <!-- Pill Badge Kapsul -->
                        <div
                            class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-[#0066B2] text-white text-xs font-semibold px-8 py-1 rounded-full">
                            Terjangkau
                        </div>

                        <!-- Harga -->
                        <div class="mt-2 mb-5 flex items-baseline justify-center gap-1">
                            <span class="text-xs font-medium text-slate-400">Rp.</span>
                            <span class="text-2xl sm:text-3xl font-bold text-[#0066B2]">1.500.000</span>
                            <span class="text-xs text-slate-400">/ 4 Jam</span>
                        </div>

                        <!-- List Fasilitas -->
                        <ul class="space-y-2.5 mb-6 text-slate-700 text-xs sm:text-sm">
                            <li class="flex items-center gap-2.5">
                                <i class="fa-regular fa-circle-check text-[#0066B2] text-base"></i>
                                <span>Sound System Standar</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <i class="fa-regular fa-circle-check text-[#0066B2] text-base"></i>
                                <span>Mic 2</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <i class="fa-regular fa-circle-check text-[#0066B2] text-base"></i>
                                <span>100 Kursi</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <i class="fa-regular fa-circle-check text-[#0066B2] text-base"></i>
                                <span>Proyektor 1</span>
                            </li>
                        </ul>

                        <!-- Tombol Aksi -->
                        <button
                            onclick="openModal('Pemesanan Paket Terjangkau', 'Form reservasi Aula 4 Jam (Rp 1.500.000).')"
                            class="w-full bg-[#0066B2] hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold py-2.5 rounded-full transition-all duration-200">
                            Pilih Paket
                        </button>
                    </div>

                </div>

                <!-- SISI KANAN: Informasi Peminjaman & Gambar Visual Side-by-Side (Kapasitas 8 Kolom) -->
                <div
                    class="lg:col-span-8 bg-white rounded-3xl p-8 sm:p-10 pb-10 sm:pb-12 border border-slate-100 shadow-xl flex flex-col justify-between">

                    <div>
                        <!-- Judul Section Informasi dengan Garis Bawah Tegas -->
                        <div class="flex items-center gap-3 mb-6">
                            <i class="fa-regular fa-circle-info text-2xl text-slate-900"></i>
                            <h3
                                class="text-xl sm:text-2xl font-bold text-slate-900 border-b-2 border-slate-900 pb-1 inline-block">
                                Informasi Peminjaman Aula
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">

                            <!-- Deskripsi & Poin Poin (Sisi Kiri Konten Informasi) -->
                            <div class="md:col-span-7 space-y-4">
                                <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                                    Kami menyediakan layanan peminjaman aula sekolah untuk berbagai kebutuhan kegiatan.
                                    Mulai dari acara sekolah, organisasi, rapat, seminar, hingga kegiatan instansi luar.
                                </p>

                                <div>
                                    <p class="text-slate-800 font-semibold text-xs sm:text-sm mb-1.5">Layanan kami
                                        mencakup:</p>
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

                                <!-- Tombol Mulai Peminjaman Kapsul -->
                                <div class="pt-3">
                                    <button onclick="openModal('Mulai Peminjaman Aula', 'Form jadwal peminjaman aula.')"
                                        class="bg-[#0066B2] hover:bg-blue-700 text-white font-semibold text-xs sm:text-sm px-6 py-2.5 rounded-full transition-all duration-200 shadow-md">
                                        Mulai Peminjaman
                                    </button>
                                </div>
                            </div>

                            <!-- Visual Foto Aula & Overlay Laptop (Sisi Kanan Konten Informasi) -->
                            <div class="md:col-span-5 relative mt-4 md:mt-0 pl-0 sm:pl-2">
                                <!-- Foto Utama Aula diperpanjang ukurannya (h-80 sm:h-96 / aspect-[3/4]) -->
                                <div
                                    class="relative rounded-2xl overflow-hidden shadow-md h-80 sm:h-96 w-full bg-slate-200">
                                    <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=800&auto=format&fit=crop&q=80"
                                        alt="Gedung Auditorium Aula" class="w-full h-full object-cover">
                                </div>

                                <!-- Mockup Overlay Laptop yang menyesuaikan posisi baru -->
                                <div
                                    class="absolute -bottom-3 -left-3 sm:-left-6 w-44 sm:w-52 rounded-xl overflow-hidden shadow-2xl border-2 border-white bg-white transition-transform duration-300 hover:scale-105 hidden sm:block z-20">
                                    <img src="https://images.unsplash.com/photo-1531403009284-440f080d1e12?w=500&auto=format&fit=crop&q=80"
                                        alt="Sistem Reservasi Online Mockup" class="w-full h-auto object-cover">
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

            </div>

        </div>
    </section>

    <!-- INFORMASI PPDB & DAYA TAMPUNG (Foto 6) -->
    <section id="ppdb" class="py-16 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Top Banner & Text Grid (Foto 6 Top) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center mb-16">

                <!-- Left Banner Graphic Mockup -->
                <div class="lg:col-span-6">
                    <div class="rounded-2xl overflow-hidden shadow-xl border border-slate-100 bg-slate-100">
                        <img src="{{ asset('assets/ppdb.png') }}" alt="Banner SPMB SMKN 2 Karanganyar"
                            class="w-full h-56 sm:h-64 md:h-80 object-cover">
                    </div>
                </div>

                <!-- Right PPDB Info Text -->
                <div class="lg:col-span-6 space-y-4">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-tight">
                        INFORMASI PPDB<br />
                        SMKN 2 KARANGANYAR
                    </h2>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Calon Murid Baru yang akan mengikuti PPDB Tahun 2026 diharapkan menyiapkan seluruh dokumen
                        persyaratan sebelum melakukan pengajuan akun. Kelengkapan berkas yang diunggah akan memperlancar
                        proses verifikasi data dan menghindari kendala saat pendaftaran.
                    </p>
                    <p class="text-slate-500 text-xs leading-relaxed underline">
                        Persyaratan ini mengacu pada Petunjuk Operasional Penyelenggaraan SPMB SMA Negeri, SMK Negeri,
                        dan SLB Negeri Provinsi Jawa Tengah Tahun Ajaran 2026/2027.
                    </p>
                    <div class="pt-2">
                        <button
                            onclick="openModal('Portal Resmi PPDB 2026', 'Mengarahkan ke portal resmi verifikasi berkas & pendaftaran online SPMB Jawa Tengah.')"
                            class="bg-brand-blue hover:bg-brand-darkBlue text-white font-semibold px-6 py-2.5 rounded-xl text-sm transition-colors shadow-md">
                            Lihat Selengkapnya
                        </button>
                    </div>
                </div>

            </div>

            <!-- Daya Tampung Box (Foto 6 Bottom) -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 card-shadow">
                <div class="mb-6 flex items-center gap-3">
                    <h4 class="text-base font-bold text-slate-800">Daya Tampung — Kompetensi Keahlian</h4>
                    <div class="h-[2px] bg-slate-200 flex-1"></div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Teknik Permesinan -->
                    <div class="bg-[#2B89FF] text-white rounded-2xl p-5 hover:scale-105 transition-transform">
                        <div class="text-xs font-extrabold uppercase mb-4 tracking-wider">TEKNIK PERMESINAN</div>
                        <div class="text-4xl font-black mb-1">108</div>
                        <div class="text-xs text-blue-100 font-medium">Siswa</div>
                    </div>

                    <!-- Teknik Pembuatan Kain -->
                    <div class="bg-[#FF982B] text-white rounded-2xl p-5 hover:scale-105 transition-transform">
                        <div class="text-xs font-extrabold uppercase mb-4 tracking-wider">TEKNIK PEMBUATAN KAIN</div>
                        <div class="text-4xl font-black mb-1">108</div>
                        <div class="text-xs text-amber-100 font-medium">Siswa</div>
                    </div>

                    <!-- Teknik Ototronik -->
                    <div class="bg-[#FF3B3B] text-white rounded-2xl p-5 hover:scale-105 transition-transform">
                        <div class="text-xs font-extrabold uppercase mb-4 tracking-wider">TEKNIK OTOTRONIK</div>
                        <div class="text-4xl font-black mb-1">108</div>
                        <div class="text-xs text-red-100 font-medium">Siswa</div>
                    </div>

                    <!-- Rekayasa Perangkat Lunak -->
                    <div class="bg-[#28C76F] text-white rounded-2xl p-5 hover:scale-105 transition-transform">
                        <div class="text-xs font-extrabold uppercase mb-4 tracking-wider">REKAYASA PERANGKAT LUNAK</div>
                        <div class="text-4xl font-black mb-1">108</div>
                        <div class="text-xs text-emerald-100 font-medium">Siswa</div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
