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
                        Kesiswaan<br />
                        <span class="text-brand-blue">SMKN 2 Karanganyar</span>
                    </h1>
                    
                    <p class="text-slate-600 text-base sm:text-lg leading-relaxed max-w-xl">
                        Membangun karakter unggul melalui integrasi nilai moral dan penguasaan teknologi. Kami berdedikasi untuk membina potensi setiap siswa dalam lingkungan yang inklusif, inovatif, dan disiplin.
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

                <!-- RIGHT: Visual Composition (Collage of News) -->
                <div class="lg:col-span-6 relative mt-12 lg:mt-0 flex justify-center lg:justify-end">
                    <div class="relative w-full max-w-[500px] aspect-[4/3]">
                        
                        <!-- Main Card 1: Berita Hari Ini -->
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

                        <!-- Main Card 2: Selamat dan Sukses -->
                        <div class="absolute bottom-0 left-0 w-[70%] bg-gradient-to-br from-emerald-800 to-teal-900 rounded-2xl overflow-hidden shadow-2xl z-20 p-5 text-white border-4 border-white">
                            <div class="text-[10px] font-semibold text-emerald-300 mb-1">SMKN 2 KARANGANYAR MENGUCAPKAN</div>
                            <h4 class="text-xl sm:text-2xl font-black italic tracking-wide text-amber-300 mb-2">
                                SELAMAT DAN SUKSES !
                            </h4>
                            <div class="h-20 bg-slate-800 rounded-lg overflow-hidden">
                                <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=400&auto=format&fit=crop&q=80" alt="Sukses" class="w-full h-full object-cover opacity-80">
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
                
                <!-- LEFT: Text & Download Card -->
                <div class="lg:col-span-5 space-y-6">
                    <p class="text-slate-600 text-base leading-relaxed">
                        Kedisiplinan adalah kunci kesuksesan. Kami menerapkan aturan yang bertujuan membentuk integritas dan profesionalisme siswa sebelum terjun ke dunia industri.
                    </p>

                    <!-- Download Card -->
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm card-shadow">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-full bg-blue-50 text-brand-blue flex items-center justify-center text-xl">
                                <i class="fa-regular fa-file-pdf"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">Buku Saku Siswa</h3>
                                <p class="text-xs text-slate-500">Unduh panduan lengkap peraturan sekolah format PDF untuk referensi di rumah.</p>
                            </div>
                        </div>
                        <button onclick="openModal('Download Buku Saku', 'Mengunduh Buku Saku Siswa SMKN 2 Karanganyar...')"
                            class="w-full bg-brand-blue hover:bg-brand-darkBlue text-white font-bold text-sm py-3 rounded-xl transition-all shadow-md flex items-center justify-center gap-2">
                            <i class="fa-solid fa-download"></i> Download PDF
                        </button>
                    </div>
                </div>

                <!-- RIGHT: Accordions -->
                <div class="lg:col-span-7 space-y-4">
                    
                    <!-- Accordion 1 -->
                    <details class="group bg-brand-blue text-white rounded-2xl overflow-hidden shadow-md transition-all">
                        <summary class="flex items-center justify-between p-5 cursor-pointer font-bold text-lg">
                            <span>Aturan Seragam</span>
                            <i class="fa-solid fa-chevron-down transition-transform group-open:rotate-180"></i>
                        </summary>
                        <div class="px-5 pb-5 text-blue-100 text-sm leading-relaxed">
                            Siswa wajib mengenakan seragam lengkap sesuai jadwal yang ditentukan, termasuk atribut sekolah, sepatu hitam, dan rambut yang rapi. Pelanggaran terhadap aturan seragam akan dikenakan sanksi sesuai tata tertib sekolah.
                        </div>
                    </details>

                    <!-- Accordion 2 -->
                    <details class="group bg-brand-blue text-white rounded-2xl overflow-hidden shadow-md transition-all">
                        <summary class="flex items-center justify-between p-5 cursor-pointer font-bold text-lg">
                            <span>Kehadiran & Jam Belajar</span>
                            <i class="fa-solid fa-chevron-down transition-transform group-open:rotate-180"></i>
                        </summary>
                        <div class="px-5 pb-5 text-blue-100 text-sm leading-relaxed">
                            Siswa diharapkan hadir 15 menit sebelum bel masuk berbunyi. Keterlambatan dan absensi tanpa keterangan akan dicatat dan mempengaruhi penilaian sikap serta kedisiplinan.
                        </div>
                    </details>

                    <!-- Accordion 3 -->
                    <details class="group bg-brand-blue text-white rounded-2xl overflow-hidden shadow-md transition-all">
                        <summary class="flex items-center justify-between p-5 cursor-pointer font-bold text-lg">
                            <span>Penggunaan Gadget</span>
                            <i class="fa-solid fa-chevron-down transition-transform group-open:rotate-180"></i>
                        </summary>
                        <div class="px-5 pb-5 text-blue-100 text-sm leading-relaxed">
                            Penggunaan gadget diperbolehkan hanya untuk keperluan pembelajaran di dalam kelas dengan izin guru. Dilarang menggunakan gadget untuk bermain game atau media sosial selama jam pelajaran berlangsung.
                        </div>
                    </details>

                </div>

            </div>
        </div>
    </section>

    <!-- ============================================================
         KONTEN: EKSTRAKURIKULER
         ============================================================ -->
    <section id="ekstrakurikuler" class="py-16 bg-white relative overflow-hidden">
        <!-- Dot Pattern Bottom Left -->
        <div class="absolute bottom-10 left-10 w-32 h-32 dot-pattern opacity-40 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <!-- Header -->
            <div class="text-center max-w-3xl mx-auto mb-14">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-3 tracking-tight">
                    Ekstrakurikuler
                </h2>
                <p class="text-slate-500 text-sm sm:text-base leading-relaxed">
                    Pilih organisasi yang sesuai dengan minat dan bakatmu untuk mengasah soft-skill dan jaringan pertemanan.
                </p>
            </div>

            <!-- Grid Ekstrakurikuler -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <!-- Card 1: OSIS -->
                <div class="bg-white border border-slate-200 rounded-3xl p-6 flex flex-col justify-between shadow-sm hover:shadow-lg transition-shadow">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-16 h-16 rounded-full bg-yellow-100 flex items-center justify-center shrink-0 border-2 border-yellow-400 overflow-hidden p-1">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/1a/Logo_OSIS.png/600px-Logo_OSIS.png" alt="Logo OSIS" class="w-full h-full object-contain">
                        </div>
                        <div>
                            <h3 class="text-lg font-extrabold text-slate-900">Organisasi OSIS</h3>
                            <p class="text-xs font-semibold text-brand-blue">SMKN 2 Karanganyar</p>
                        </div>
                    </div>
                    <p class="text-slate-500 text-sm leading-relaxed mb-6 flex-grow">
                        Organisasi Siswa Intra Sekolah SMK N 2 KARANGANYAR adalah Organisasi satu-satunya yang ada disekolah yang berada dibawah Waka Kesiswaan.
                    </p>
                    <button onclick="openDetailEskul('OSIS')" class="bg-brand-blue hover:bg-brand-darkBlue text-white text-xs font-bold px-5 py-2.5 rounded-lg w-fit transition-colors">
                        Selengkapnya
                    </button>
                </div>

                <!-- Card 2: PMR -->
                <div class="bg-white border border-slate-200 rounded-3xl p-6 flex flex-col justify-between shadow-sm hover:shadow-lg transition-shadow">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center shrink-0 border-2 border-red-400 overflow-hidden p-2">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/4/4b/Logo_PMR.png/600px-Logo_PMR.png" alt="Logo PMR" class="w-full h-full object-contain">
                        </div>
                        <div>
                            <h3 class="text-lg font-extrabold text-slate-900">Organisasi PMR</h3>
                            <p class="text-xs font-semibold text-brand-blue">SMKN 2 Karanganyar</p>
                        </div>
                    </div>
                    <p class="text-slate-500 text-sm leading-relaxed mb-6 flex-grow">
                        PMR Wira SMK Negeri 2 Karanganyar merupakan salah satu organisasi di lingkungan sekolah.
                    </p>
                    <button onclick="openDetailEskul('PMR')" class="bg-brand-blue hover:bg-brand-darkBlue text-white text-xs font-bold px-5 py-2.5 rounded-lg w-fit transition-colors">
                        Selengkapnya
                    </button>
                </div>

                <!-- Card 3: AMBALAN -->
                <div class="bg-white border border-slate-200 rounded-3xl p-6 flex flex-col justify-between shadow-sm hover:shadow-lg transition-shadow">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center shrink-0 border-2 border-blue-400 overflow-hidden p-1">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Logo_Pramuka.png/600px-Logo_Pramuka.png" alt="Logo Ambalan" class="w-full h-full object-contain">
                        </div>
                        <div>
                            <h3 class="text-lg font-extrabold text-slate-900">Organisasi AMBALAN</h3>
                            <p class="text-xs font-semibold text-brand-blue">SMKN 2 Karanganyar</p>
                        </div>
                    </div>
                    <p class="text-slate-500 text-sm leading-relaxed mb-6 flex-grow">
                        Fasilitas sekolah dengan kapasitas luas untuk berbagai kebutuhan.
                    </p>
                    <button onclick="openDetailEskul('AMBALAN')" class="bg-brand-blue hover:bg-brand-darkBlue text-white text-xs font-bold px-5 py-2.5 rounded-lg w-fit transition-colors">
                        Selengkapnya
                    </button>
                </div>

                <!-- Card 4: PBB -->
                <div class="bg-white border border-slate-200 rounded-3xl p-6 flex flex-col justify-between shadow-sm hover:shadow-lg transition-shadow">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-16 h-16 rounded-full bg-slate-800 flex items-center justify-center shrink-0 border-2 border-slate-600 overflow-hidden p-1">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/c5/Logo_Paskibraka.png/600px-Logo_Paskibraka.png" alt="Logo PBB" class="w-full h-full object-contain">
                        </div>
                        <div>
                            <h3 class="text-lg font-extrabold text-slate-900">Organisasi PBB</h3>
                            <p class="text-xs font-semibold text-brand-blue">SMKN 2 Karanganyar</p>
                        </div>
                    </div>
                    <p class="text-slate-500 text-sm leading-relaxed mb-6 flex-grow">
                        Fasilitas sekolah dengan kapasitas luas untuk berbagai kebutuhan acara institusi, perusahaan, dan masyarakat umum.
                    </p>
                    <button onclick="openDetailEskul('PBB')" class="bg-brand-blue hover:bg-brand-darkBlue text-white text-xs font-bold px-5 py-2.5 rounded-lg w-fit transition-colors">
                        Selengkapnya
                    </button>
                </div>

                <!-- Card 5: ROHIS -->
                <div class="bg-white border border-slate-200 rounded-3xl p-6 flex flex-col justify-between shadow-sm hover:shadow-lg transition-shadow">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-16 h-16 rounded-full bg-yellow-100 flex items-center justify-center shrink-0 border-2 border-yellow-400 overflow-hidden p-1">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/9e/Logo_Rohis.png/600px-Logo_Rohis.png" alt="Logo Rohis" class="w-full h-full object-contain">
                        </div>
                        <div>
                            <h3 class="text-lg font-extrabold text-slate-900">Organisasi ROHIS</h3>
                            <p class="text-xs font-semibold text-brand-blue">SMKN 2 Karanganyar</p>
                        </div>
                    </div>
                    <p class="text-slate-500 text-sm leading-relaxed mb-6 flex-grow">
                        Rohani Islam (disingkat Rohis) adalah sebuah organisasi memperdalam dan memperkuat ajaran Islam.
                    </p>
                    <button onclick="openDetailEskul('ROHIS')" class="bg-brand-blue hover:bg-brand-darkBlue text-white text-xs font-bold px-5 py-2.5 rounded-lg w-fit transition-colors">
                        Selengkapnya
                    </button>
                </div>

                <!-- Card 6: JURNALISTIK -->
                <div class="bg-white border border-slate-200 rounded-3xl p-6 flex flex-col justify-between shadow-sm hover:shadow-lg transition-shadow">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center shrink-0 border-2 border-red-400 overflow-hidden p-1">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Logo_Jurnalistik.png/600px-Logo_Jurnalistik.png" alt="Logo Jurnalistik" class="w-full h-full object-contain">
                        </div>
                        <div>
                            <h3 class="text-lg font-extrabold text-slate-900">Organisasi JURNALISTIK</h3>
                            <p class="text-xs font-semibold text-brand-blue">SMKN 2 Karanganyar</p>
                        </div>
                    </div>
                    <p class="text-slate-500 text-sm leading-relaxed mb-6 flex-grow">
                        Jurnalistik secara bahasa adalah kewartawanan atau kepenulisan.
                    </p>
                    <button onclick="openDetailEskul('JURNALISTIK')" class="bg-brand-blue hover:bg-brand-darkBlue text-white text-xs font-bold px-5 py-2.5 rounded-lg w-fit transition-colors">
                        Selengkapnya
                    </button>
                </div>

            </div>
        </div>
    </section>

    <!-- ============================================================
         KONTEN: PRESTASI
         ============================================================ -->
    <section id="prestasi" class="py-16 bg-[#F8FAFC] relative overflow-hidden">
        <!-- Dot Pattern Bottom Left -->
        <div class="absolute bottom-10 left-10 w-32 h-32 dot-pattern opacity-40 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <!-- Header with Badge -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-10 gap-6">
                <div>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-3 tracking-tight">
                        Semua Prestasi
                    </h2>
                    <p class="text-slate-500 text-sm sm:text-base leading-relaxed max-w-2xl">
                        Dedikasi dan kerja keras siswa-siswi terbaik kami dalam mengharumkan nama sekolah di kancah nasional maupun internasional.
                    </p>
                </div>
                <!-- Badge -->
                <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 flex flex-col items-center justify-center text-center shadow-sm min-w-[140px]">
                    <i class="fa-solid fa-trophy text-brand-blue text-xl mb-1"></i>
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Prestasi</span>
                    <span class="text-2xl font-black text-brand-blue">100+</span>
                </div>
            </div>

            <!-- Prestasi Terbaru (Slider Mockup) -->
            <div class="bg-white rounded-2xl p-6 sm:p-8 card-shadow border border-slate-100 relative mb-10">
                <div class="mb-6">
                    <h4 class="text-lg font-bold text-slate-800 border-b-2 border-brand-blue inline-block pb-1">Prestasi Terbaru</h4>
                </div>

                <div class="relative">
                    <div id="news-container" class="grid grid-cols-1 md:grid-cols-2 gap-6 transition-all duration-300">
                        <!-- Card News 1 -->
                        <div class="bg-gradient-to-r from-blue-900 to-indigo-900 rounded-xl overflow-hidden p-6 text-white relative group h-48 flex flex-col justify-center">
                            <span class="inline-block bg-blue-600 text-white text-[10px] font-bold px-2.5 py-1 rounded mb-3 uppercase w-fit">
                                BERITA HARI INI
                            </span>
                            <h4 class="text-lg sm:text-xl font-bold leading-snug mb-2 group-hover:text-blue-200 transition-colors">
                                SMKN 2 KARANGANYAR SIAP PERTAHANKAN GELAR JATENG DI LKBB-PB NASIONAL
                            </h4>
                        </div>

                        <!-- Card News 2 -->
                        <div class="bg-gradient-to-r from-emerald-800 to-teal-900 rounded-xl overflow-hidden p-6 text-white relative group h-48 flex flex-col justify-center">
                            <div class="text-xs font-semibold text-emerald-300 mb-1">SMKN 2 KARANGANYAR MENGUCAPKAN
                            </div>
                            <h4 class="text-2xl sm:text-3xl font-black italic tracking-wide text-amber-300 group-hover:scale-105 transition-transform">
                                SELAMAT DAN SUKSES !
                            </h4>
                            <p class="text-xs text-emerald-100 mt-2">Juara 1 Lomba Kompetensi Siswa (LKS) Bidang CNC Milling 2026</p>
                        </div>
                    </div>

                    <!-- Slider Arrows Controls -->
                    <button id="prev-news" class="absolute -left-4 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-white shadow-md border border-slate-200 flex items-center justify-center text-slate-700 hover:text-brand-blue transition-colors">
                        <i class="fa-solid fa-chevron-left text-xs"></i>
                    </button>
                    <button id="next-news" class="absolute -right-4 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-white shadow-md border border-slate-200 flex items-center justify-center text-slate-700 hover:text-brand-blue transition-colors">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </button>
                </div>
            </div>

            <!-- Grid Prestasi -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                
                <!-- Card 1 -->
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100 hover:shadow-lg transition-shadow flex flex-col">
                    <div class="h-40 bg-slate-200 relative">
                        <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=400&auto=format&fit=crop&q=80" alt="Prestasi" class="w-full h-full object-cover">
                        <span class="absolute top-2 left-2 bg-brand-blue text-white text-[10px] font-bold px-2 py-1 rounded">JUARA 1</span>
                    </div>
                    <div class="p-4 flex flex-col flex-grow">
                        <h5 class="text-sm font-bold text-slate-900 mb-2 leading-tight">SMKN 2 KARANGANYAR SIAP PERTAHANKAN GELAR JATENG DI LKBB-PB NASIONAL</h5>
                        <p class="text-xs text-slate-500 leading-relaxed flex-grow">Siswa SMK Negeri 2 Karanganyar sukses meraih Juara 1 Lomba Keterampilan Baris-Berbaris (LKBB) Piala Bergilir.</p>
                        <button onclick="openModal('Detail Prestasi', 'Informasi lengkap mengenai prestasi LKBB-PB Nasional.')" class="mt-4 text-brand-blue text-xs font-bold hover:underline text-left w-fit">Selengkapnya</button>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100 hover:shadow-lg transition-shadow flex flex-col">
                    <div class="h-40 bg-slate-200 relative">
                        <img src="https://images.unsplash.com/photo-1523580494863-6f3031224c94?w=400&auto=format&fit=crop&q=80" alt="Prestasi" class="w-full h-full object-cover">
                        <span class="absolute top-2 left-2 bg-brand-blue text-white text-[10px] font-bold px-2 py-1 rounded">JUARA 1</span>
                    </div>
                    <div class="p-4 flex flex-col flex-grow">
                        <h5 class="text-sm font-bold text-slate-900 mb-2 leading-tight">SMKN 2 KARANGANYAR MENGUCAPKAN SELAMAT DAN SUKSES</h5>
                        <p class="text-xs text-slate-500 leading-relaxed flex-grow">Tim SMK Negeri 2 Karanganyar berhasil meraih Juara 1 dalam Lomba Kompetensi Siswa (LKS) Bidang CNC Milling Tingkat Provinsi.</p>
                        <button onclick="openModal('Detail Prestasi', 'Informasi lengkap mengenai prestasi LKS CNC Milling.')" class="mt-4 text-brand-blue text-xs font-bold hover:underline text-left w-fit">Selengkapnya</button>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100 hover:shadow-lg transition-shadow flex flex-col">
                    <div class="h-40 bg-slate-200 relative">
                        <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=400&auto=format&fit=crop&q=80" alt="Prestasi" class="w-full h-full object-cover">
                        <span class="absolute top-2 left-2 bg-brand-blue text-white text-[10px] font-bold px-2 py-1 rounded">JUARA 2</span>
                    </div>
                    <div class="p-4 flex flex-col flex-grow">
                        <h5 class="text-sm font-bold text-slate-900 mb-2 leading-tight">Selamat kepada Anindya Dwi Rahmawati</h5>
                        <p class="text-xs text-slate-500 leading-relaxed flex-grow">Siswa berprestasi dari jurusan Teknologi Informasi dan Komunikasi ini berhasil mengharumkan nama sekolah.</p>
                        <button onclick="openModal('Detail Prestasi', 'Informasi lengkap mengenai prestasi Anindya Dwi Rahmawati.')" class="mt-4 text-brand-blue text-xs font-bold hover:underline text-left w-fit">Selengkapnya</button>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100 hover:shadow-lg transition-shadow flex flex-col">
                    <div class="h-40 bg-slate-200 relative">
                        <img src="https://images.unsplash.com/photo-1531403009284-440f080d1e12?w=400&auto=format&fit=crop&q=80" alt="Prestasi" class="w-full h-full object-cover">
                        <span class="absolute top-2 left-2 bg-brand-blue text-white text-[10px] font-bold px-2 py-1 rounded">JUARA 3</span>
                    </div>
                    <div class="p-4 flex flex-col flex-grow">
                        <h5 class="text-sm font-bold text-slate-900 mb-2 leading-tight">Selamat kepada Muhammad Ainul Yaqin</h5>
                        <p class="text-xs text-slate-500 leading-relaxed flex-grow">Prestasi membanggakan diraih oleh siswa yang mengikuti lomba di bidang teknologi tingkat kabupaten.</p>
                        <button onclick="openModal('Detail Prestasi', 'Informasi lengkap mengenai prestasi Muhammad Ainul Yaqin.')" class="mt-4 text-brand-blue text-xs font-bold hover:underline text-left w-fit">Selengkapnya</button>
                    </div>
                </div>

                <!-- Card 5 -->
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100 hover:shadow-lg transition-shadow flex flex-col">
                    <div class="h-40 bg-slate-200 relative">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&auto=format&fit=crop&q=80" alt="Prestasi" class="w-full h-full object-cover">
                        <span class="absolute top-2 left-2 bg-brand-blue text-white text-[10px] font-bold px-2 py-1 rounded">JUARA 1</span>
                    </div>
                    <div class="p-4 flex flex-col flex-grow">
                        <h5 class="text-sm font-bold text-slate-900 mb-2 leading-tight">Selamat kepada M. ILHAM</h5>
                        <p class="text-xs text-slate-500 leading-relaxed flex-grow">Siswa kelas XI ini berhasil meraih Juara 1 dalam ajang kompetisi keahlian tingkat kabupaten.</p>
                        <button onclick="openModal('Detail Prestasi', 'Informasi lengkap mengenai prestasi M. ILHAM.')" class="mt-4 text-brand-blue text-xs font-bold hover:underline text-left w-fit">Selengkapnya</button>
                    </div>
                </div>

                <!-- Card 6 -->
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100 hover:shadow-lg transition-shadow flex flex-col">
                    <div class="h-40 bg-slate-200 relative">
                        <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=400&auto=format&fit=crop&q=80" alt="Prestasi" class="w-full h-full object-cover">
                        <span class="absolute top-2 left-2 bg-brand-blue text-white text-[10px] font-bold px-2 py-1 rounded">JUARA 2</span>
                    </div>
                    <div class="p-4 flex flex-col flex-grow">
                        <h5 class="text-sm font-bold text-slate-900 mb-2 leading-tight">Selamat kepada BINTU MUHAMMAD</h5>
                        <p class="text-xs text-slate-500 leading-relaxed flex-grow">Meraih Juara 2 dalam Lomba Kompetensi Siswa (LKS) bidang keahlian Teknologi Informasi.</p>
                        <button onclick="openModal('Detail Prestasi', 'Informasi lengkap mengenai prestasi BINTU MUHAMMAD.')" class="mt-4 text-brand-blue text-xs font-bold hover:underline text-left w-fit">Selengkapnya</button>
                    </div>
                </div>

                <!-- Card 7 -->
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100 hover:shadow-lg transition-shadow flex flex-col">
                    <div class="h-40 bg-slate-200 relative">
                        <img src="https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=400&auto=format&fit=crop&q=80" alt="Prestasi" class="w-full h-full object-cover">
                        <span class="absolute top-2 left-2 bg-brand-blue text-white text-[10px] font-bold px-2 py-1 rounded">JUARA 1</span>
                    </div>
                    <div class="p-4 flex flex-col flex-grow">
                        <h5 class="text-sm font-bold text-slate-900 mb-2 leading-tight">Selamat kepada Zainal Abidin</h5>
                        <p class="text-xs text-slate-500 leading-relaxed flex-grow">Siswa berprestasi yang berhasil meraih Juara 1 di bidang CNC Milling tingkat kabupaten.</p>
                        <button onclick="openModal('Detail Prestasi', 'Informasi lengkap mengenai prestasi Zainal Abidin.')" class="mt-4 text-brand-blue text-xs font-bold hover:underline text-left w-fit">Selengkapnya</button>
                    </div>
                </div>

                <!-- Card 8 -->
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100 hover:shadow-lg transition-shadow flex flex-col">
                    <div class="h-40 bg-slate-200 relative">
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400&auto=format&fit=crop&q=80" alt="Prestasi" class="w-full h-full object-cover">
                        <span class="absolute top-2 left-2 bg-brand-blue text-white text-[10px] font-bold px-2 py-1 rounded">JUARA 2</span>
                    </div>
                    <div class="p-4 flex flex-col flex-grow">
                        <h5 class="text-sm font-bold text-slate-900 mb-2 leading-tight">Selamat kepada Muhammad Ainul Yaqin</h5>
                        <p class="text-xs text-slate-500 leading-relaxed flex-grow">Meraih Juara 2 dalam Lomba CNC Milling Tingkat Kabupaten Karanganyar.</p>
                        <button onclick="openModal('Detail Prestasi', 'Informasi lengkap mengenai prestasi Muhammad Ainul Yaqin.')" class="mt-4 text-brand-blue text-xs font-bold hover:underline text-left w-fit">Selengkapnya</button>
                    </div>
                </div>

            </div>

            <!-- Pagination -->
            <div class="flex justify-center items-center gap-2 mt-8">
                <button class="w-8 h-8 rounded-lg bg-slate-200 text-slate-500 flex items-center justify-center text-xs hover:bg-slate-300 transition-colors"><i class="fa-solid fa-chevron-left"></i></button>
                <button class="w-8 h-8 rounded-lg bg-brand-blue text-white flex items-center justify-center text-xs font-bold">1</button>
                <button class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-600 flex items-center justify-center text-xs font-bold hover:bg-slate-50 transition-colors">2</button>
                <span class="px-2 text-slate-400 text-xs">...</span>
                <button class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-600 flex items-center justify-center text-xs font-bold hover:bg-slate-50 transition-colors">9</button>
                <button class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-600 flex items-center justify-center text-xs font-bold hover:bg-slate-50 transition-colors">10</button>
                <button class="w-8 h-8 rounded-lg bg-slate-200 text-slate-500 flex items-center justify-center text-xs hover:bg-slate-300 transition-colors"><i class="fa-solid fa-chevron-right"></i></button>
            </div>

        </div>
    </section>

    <!-- ============================================================
         MODAL DETAIL EKSTRAKURIKULER (Berdasarkan Gambar 4)
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
                
                <!-- Top Section: Logo & Deskripsi -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start mb-10">
                    <!-- Logo & Title -->
                    <div class="md:col-span-4 flex flex-col items-center text-center">
                        <div class="w-32 h-32 rounded-full bg-yellow-100 flex items-center justify-center border-4 border-yellow-400 p-2 mb-4 shadow-lg">
                            <img id="detail-eskul-logo" src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/1a/Logo_OSIS.png/600px-Logo_OSIS.png" alt="Logo Eskul" class="w-full h-full object-contain">
                        </div>
                        <h2 id="detail-eskul-title" class="text-2xl font-black text-slate-900 mb-1">Organisasi OSIS</h2>
                        <p class="text-sm font-semibold text-brand-blue">SMKN 2 Karanganyar</p>
                    </div>

                    <!-- Deskripsi -->
                    <div class="md:col-span-8">
                        <h3 class="text-lg font-bold text-slate-800 mb-3 border-b-2 border-brand-blue inline-block pb-1">Deskripsi</h3>
                        <div id="detail-eskul-desc" class="text-slate-600 text-sm leading-relaxed space-y-3">
                            <p>Organisasi Siswa Intra Sekolah SMK N 2 KARANGANYAR adalah Organisasi satu-satunya yang ada disekolah yang berada dibawah Waka Kesiswaan yang bertugas membantu kegiatan sekolah yang berhubungan dengan siswa.</p>
                            <p>Banyak kegiatan yang sudah terlaksana antara lain Lomba jeda semester, Upacara, Dies Natalis, Anjangsana dengan Pengurus OSIS SMK lain, dan masih banyak lagi. Di OSIS kita juga bergabung dengan forum - forum seperti forum OSIS Kabupaten, dan forum OSIS Provinsi.</p>
                            <p>Adapun tujuan OSIS adalah membantu pihak sekolah dalam melaksanakan kegiatan, mengadakan lomba untuk mengasah kreativitas siswa, serta menampung ide dari guru maupun siswa siswi SMK N 2 KARANGANYAR.</p>
                            <div class="mt-4 pt-4 border-t border-slate-100">
                                <p class="text-slate-800 font-bold text-xs uppercase tracking-wider mb-1">Visi</p>
                                <p class="text-slate-600 text-sm">Berkarakter, Berprestasi dan Berbudaya lingkungan</p>
                                <p class="text-slate-800 font-bold text-xs uppercase tracking-wider mt-3 mb-1">Misi</p>
                                <ul class="list-disc list-inside text-slate-600 text-sm space-y-1">
                                    <li>Menanamkan Keimanan dan Ketakwaan kepada Tuhan Yang Maha Esa</li>
                                    <li>Menyelenggarakan Pendidikan dan Pelatihan yang Berkualitas dan Berbudaya Lingkungan</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Section: Dokumentasi Kegiatan -->
                <div>
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Dokumentasi Kegiatan</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="rounded-xl overflow-hidden shadow-md h-48 bg-slate-200">
                            <img src="https://images.unsplash.com/photo-1523580494863-6f3031224c94?w=600&auto=format&fit=crop&q=80" alt="Dokumentasi 1" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                        </div>
                        <div class="rounded-xl overflow-hidden shadow-md h-48 bg-slate-200">
                            <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=600&auto=format&fit=crop&q=80" alt="Dokumentasi 2" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                        </div>
                        <div class="rounded-xl overflow-hidden shadow-md h-48 bg-slate-200">
                            <img src="https://images.unsplash.com/photo-1531403009284-440f080d1e12?w=600&auto=format&fit=crop&q=80" alt="Dokumentasi 3" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                        </div>
                        <div class="rounded-xl overflow-hidden shadow-md h-48 bg-slate-200">
                            <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=600&auto=format&fit=crop&q=80" alt="Dokumentasi 4" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- ============================================================
         FOOTER & PETA LOKASI
         ============================================================ -->

@endsection

@push('scripts')
    <script>
        // ============================================================
        // DETAIL EKSTRAKURIKULER MODAL LOGIC
        // ============================================================
        const detailEskulModal = document.getElementById('detail-eskul-modal');
        const detailEskulContent = document.getElementById('detail-eskul-content');
        const detailEskulTitle = document.getElementById('detail-eskul-title');
        const detailEskulLogo = document.getElementById('detail-eskul-logo');
        const detailEskulDesc = document.getElementById('detail-eskul-desc');

        const eskulData = {
            'OSIS': {
                title: 'Organisasi OSIS',
                logo: 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/1a/Logo_OSIS.png/600px-Logo_OSIS.png',
                desc: `
                    <p>Organisasi Siswa Intra Sekolah SMK N 2 KARANGANYAR adalah Organisasi satu-satunya yang ada disekolah yang berada dibawah Waka Kesiswaan yang bertugas membantu kegiatan sekolah yang berhubungan dengan siswa.</p>
                    <p>Banyak kegiatan yang sudah terlaksana antara lain Lomba jeda semester, Upacara, Dies Natalis, Anjangsana dengan Pengurus OSIS SMK lain, dan masih banyak lagi. Di OSIS kita juga bergabung dengan forum - forum seperti forum OSIS Kabupaten, dan forum OSIS Provinsi.</p>
                    <p>Adapun tujuan OSIS adalah membantu pihak sekolah dalam melaksanakan kegiatan, mengadakan lomba untuk mengasah kreativitas siswa, serta menampung ide dari guru maupun siswa siswi SMK N 2 KARANGANYAR.</p>
                    <div class="mt-4 pt-4 border-t border-slate-100">
                        <p class="text-slate-800 font-bold text-xs uppercase tracking-wider mb-1">Visi</p>
                        <p class="text-slate-600 text-sm">Berkarakter, Berprestasi dan Berbudaya lingkungan</p>
                        <p class="text-slate-800 font-bold text-xs uppercase tracking-wider mt-3 mb-1">Misi</p>
                        <ul class="list-disc list-inside text-slate-600 text-sm space-y-1">
                            <li>Menanamkan Keimanan dan Ketakwaan kepada Tuhan Yang Maha Esa</li>
                            <li>Menyelenggarakan Pendidikan dan Pelatihan yang Berkualitas dan Berbudaya Lingkungan</li>
                        </ul>
                    </div>
                `
            },
            'PMR': {
                title: 'Organisasi PMR',
                logo: 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4b/Logo_PMR.png/600px-Logo_PMR.png',
                desc: `
                    <p>PMR Wira SMK Negeri 2 Karanganyar merupakan salah satu organisasi di lingkungan sekolah yang bergerak di bidang kemanusiaan dan kesehatan.</p>
                    <p>Kegiatan rutin meliputi pelatihan pertolongan pertama, donor darah, dan bakti sosial. Tujuan utama adalah membentuk siswa yang peduli terhadap sesama dan siap membantu dalam situasi darurat.</p>
                `
            },
            'AMBALAN': {
                title: 'Organisasi AMBALAN',
                logo: 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Logo_Pramuka.png/600px-Logo_Pramuka.png',
                desc: `
                    <p>Ambalan adalah organisasi kepramukaan di tingkat penegak yang ada di SMKN 2 Karanganyar.</p>
                    <p>Kegiatan meliputi perkemahan, penjelajahan, dan pelatihan kepemimpinan. Melalui Ambalan, siswa dilatih untuk mandiri, disiplin, dan memiliki jiwa korsa yang tinggi.</p>
                `
            },
            'PBB': {
                title: 'Organisasi PBB',
                logo: 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c5/Logo_Paskibraka.png/600px-Logo_Paskibraka.png',
                desc: `
                    <p>Pasukan Baris-Berbaris (PBB) adalah ekstrakurikuler yang fokus pada kedisiplinan, ketegasan, dan kekompakan.</p>
                    <p>Anggota PBB sering menjadi petugas upacara bendera di sekolah maupun di tingkat kabupaten. Latihan rutin dilakukan untuk meningkatkan ketahanan fisik dan mental siswa.</p>
                `
            },
            'ROHIS': {
                title: 'Organisasi ROHIS',
                logo: 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/9e/Logo_Rohis.png/600px-Logo_Rohis.png',
                desc: `
                    <p>Rohani Islam (Rohis) adalah organisasi yang bergerak di bidang keagamaan Islam.</p>
                    <p>Kegiatan meliputi kajian rutin, peringatan hari besar Islam, dan bimbingan membaca Al-Qur'an. Tujuan utamanya adalah memperdalam dan memperkuat ajaran Islam di kalangan siswa.</p>
                `
            },
            'JURNALISTIK': {
                title: 'Organisasi JURNALISTIK',
                logo: 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Logo_Jurnalistik.png/600px-Logo_Jurnalistik.png',
                desc: `
                    <p>Jurnalistik secara bahasa adalah kewartawanan atau kepenulisan.</p>
                    <p>Ekstrakurikuler ini melatih siswa dalam menulis berita, fotografi, dan desain grafis untuk majalah dinding atau media sosial sekolah. Siswa diajarkan untuk berpikir kritis dan menyampaikan informasi dengan benar.</p>
                `
            }
        };

        function openDetailEskul(nama) {
            const data = eskulData[nama];
            if (data) {
                detailEskulTitle.innerText = data.title;
                detailEskulLogo.src = data.logo;
                detailEskulDesc.innerHTML = data.desc;
                
                detailEskulModal.classList.remove('hidden');
                setTimeout(() => {
                    detailEskulContent.classList.remove('scale-95', 'opacity-0');
                    detailEskulContent.classList.add('scale-100', 'opacity-100');
                }, 10);
            }
        }

        function closeDetailEskul() {
            detailEskulContent.classList.remove('scale-100', 'opacity-100');
            detailEskulContent.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                detailEskulModal.classList.add('hidden');
            }, 200);
        }

        // Close modal when clicking outside
        detailEskulModal.addEventListener('click', (e) => {
            if (e.target === detailEskulModal) {
                closeDetailEskul();
            }
        });
    </script>
@endpush