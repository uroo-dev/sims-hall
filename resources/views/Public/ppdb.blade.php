@extends('Public.layout.app')

@section('title', 'PPDB 2026 - SMK Negeri 2 Karanganyar')
@section('description', 'Informasi PPDB SMKN 2 Karanganyar: persyaratan pendaftaran, tanggal penting, daya tampung, jalur seleksi, dan pilihan kompetensi keahlian.')

@section('content')

    <!-- ============================================================
         KONTEN: PPDB
         ============================================================ -->

    <!-- 1. HERO SECTION (PPDB) -->
    <section id="hero" class="relative py-16 md:py-24 overflow-hidden bg-white">
        <!-- Abstract Background Shapes -->
        <div class="absolute top-0 right-0 w-1/3 h-2/3 bg-blue-50 rounded-bl-[10rem] -z-10 opacity-70"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-100 rounded-full -z-10 opacity-50 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

                <!-- Left Text Content -->
                <div class="lg:col-span-6 space-y-6 z-10">
                    <span class="inline-block text-brand-blue font-bold tracking-wide text-sm sm:text-base uppercase">
                        Sekolah Pusat Keunggulan
                    </span>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-slate-900 tracking-tight leading-[1.1]">
                        PPDB SMKN 2<br />
                        <span class="text-brand-blue">KARANGANYAR</span>
                    </h1>
                    
                    <p class="text-slate-600 text-base sm:text-lg leading-relaxed max-w-xl">
                        Bersama SMKN 2 Karanganyar untuk mencetak generasi unggul yang kompeten dan berkarakter siap di dunia industri.
                    </p>

                    <div class="pt-4">
                        <button onclick="document.getElementById('panduan').scrollIntoView({behavior: 'smooth'})"
                            class="bg-brand-blue hover:bg-brand-darkBlue text-white font-bold px-8 py-3.5 rounded-xl text-sm transition-all shadow-lg shadow-blue-500/30 flex items-center gap-2">
                            Informasi Panduan PPDB
                        </button>
                    </div>
                </div>

                <!-- Right Visual: Poster -->
                <div class="lg:col-span-6 relative mt-12 lg:mt-0 flex justify-center lg:justify-end">
                    <div class="relative w-full max-w-[500px]">
                        <!-- Main Poster Image -->
                        <img src="{{ asset('assets/ppdb.png') }}" alt="Banner SPMB SMKN 2 Karanganyar"
                            class="w-full h-auto rounded-3xl shadow-2xl border-4 border-white object-cover relative z-10">
                        
                        <!-- Abstract decorative shapes -->
                        <div class="absolute -top-6 -left-6 w-24 h-24 dot-pattern opacity-50 z-0"></div>
                        <div class="absolute -bottom-8 -right-8 w-32 h-32 border-[16px] border-brand-blue rounded-full opacity-20 z-0"></div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 2. INFORMASI PANDUAN PPDB -->
    <section id="panduan" class="py-16 bg-[#FAFCFF] relative overflow-hidden">
        <!-- Dot Pattern Top Right -->
        <div class="absolute top-10 right-10 w-32 h-32 dot-pattern opacity-40 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Header -->
            <div class="text-center max-w-3xl mx-auto mb-14">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-3 tracking-tight">
                    Informasi Panduan PPDB
                </h2>
                <p class="text-slate-500 text-sm sm:text-base leading-relaxed">
                    Fasilitas sekolah dengan kapasitas luas untuk berbagai kebutuhan acara institusi,<br class="hidden sm:block">
                    perusahaan, dan masyarakat umum.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- LEFT CARD: Persyaratan PPDB -->
                <div class="bg-white border border-slate-200 rounded-3xl p-8 sm:p-10 shadow-sm relative">
                    <div class="flex items-center gap-3 mb-6">
                        <i class="fa-regular fa-circle-info text-2xl text-slate-900"></i>
                        <h3 class="text-xl sm:text-2xl font-bold text-slate-900 border-b-2 border-slate-900 pb-1 inline-block">
                            Persyaratan PPDB
                        </h3>
                    </div>
                    
                    <ul class="space-y-3.5 mb-8 text-slate-700 text-sm">
                        <li class="flex items-start gap-3">
                            <i class="fa-regular fa-circle-check text-emerald-500 text-lg mt-0.5"></i>
                            <span>Surat Pernyataan Dokumen.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa-regular fa-circle-check text-emerald-500 text-lg mt-0.5"></i>
                            <span>Buku Rapor SMP / Sederajat.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa-regular fa-circle-check text-emerald-500 text-lg mt-0.5"></i>
                            <span>Surat Keterangan Nilai Rapor Semester 1-5.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa-regular fa-circle-check text-emerald-500 text-lg mt-0.5"></i>
                            <span>Ijazah SMP / Surat Berpenghargaan.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa-regular fa-circle-check text-emerald-500 text-lg mt-0.5"></i>
                            <span>Sertifikat Hasil TKA / Tes Kemampuan Akademik.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa-regular fa-circle-check text-emerald-500 text-lg mt-0.5"></i>
                            <span>Akta Kelahiran.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa-regular fa-circle-check text-emerald-500 text-lg mt-0.5"></i>
                            <span>Kartu Keluarga (KK) yang masih berlaku.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa-regular fa-circle-check text-emerald-500 text-lg mt-0.5"></i>
                            <span>Piagam Penghargaan (Jika Memiliki).</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa-regular fa-circle-check text-emerald-500 text-lg mt-0.5"></i>
                            <span>Surat Keterangan Sehat.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa-regular fa-circle-check text-emerald-500 text-lg mt-0.5"></i>
                            <span>Surat Keterangan Tidak Buta Warna.</span>
                        </li>
                    </ul>

                    <a href="#" class="text-brand-blue font-bold text-xs sm:text-sm hover:underline uppercase tracking-wider">
                        PETUNJUK OPERASIONAL SPMB SMAN SMKN JAWA TENGAH 2026
                    </a>
                </div>

                <!-- RIGHT CARD: Tanggal Penting -->
                <div class="bg-white border border-slate-200 rounded-3xl p-8 sm:p-10 shadow-sm relative">
                    <h3 class="text-xl sm:text-2xl font-bold text-slate-900 border-b-2 border-slate-900 pb-1 inline-block mb-8">
                        Tanggal Penting
                    </h3>

                    <!-- Timeline -->
                    <div class="relative border-l-2 border-slate-200 ml-3 space-y-8 pb-4">
                        
                        <!-- Timeline Item 1 -->
                        <div class="relative pl-8">
                            <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-brand-blue border-4 border-white shadow-sm"></div>
                            <h4 class="text-sm font-bold text-brand-blue mb-1">10 - 13 Mei 2024</h4>
                            <p class="text-xs sm:text-sm font-bold text-slate-800 mb-1">Sosialisasi PPDB</p>
                            <p class="text-xs text-slate-500">informasi umum dan pengenalan sistem.</p>
                        </div>

                        <!-- Timeline Item 2 -->
                        <div class="relative pl-8">
                            <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-brand-blue border-4 border-white shadow-sm"></div>
                            <h4 class="text-sm font-bold text-brand-blue mb-1">20 - 30 Juni 2024</h4>
                            <p class="text-xs sm:text-sm font-bold text-slate-800 mb-1">Pendaftaran Online</p>
                            <p class="text-xs text-slate-500">pengisian formulir dan unggah berkas.</p>
                        </div>

                        <!-- Timeline Item 3 -->
                        <div class="relative pl-8">
                            <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-brand-blue border-4 border-white shadow-sm"></div>
                            <h4 class="text-sm font-bold text-brand-blue mb-1">02 - 04 Juli 2024</h4>
                            <p class="text-xs sm:text-sm font-bold text-slate-800 mb-1">Seleksi & Verifikasi</p>
                            <p class="text-xs text-slate-500">proses validasi nilai dan dokumen.</p>
                        </div>

                        <!-- Timeline Item 4 -->
                        <div class="relative pl-8">
                            <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-brand-blue border-4 border-white shadow-sm"></div>
                            <h4 class="text-sm font-bold text-brand-blue mb-1">07 Juli 2024</h4>
                            <p class="text-xs sm:text-sm font-bold text-slate-800 mb-1">Pengumuman Hasil</p>
                            <p class="text-xs text-slate-500">daftar calon siswa yang diterima.</p>
                        </div>

                    </div>

                    <!-- Dot pattern bottom right -->
                    <div class="absolute bottom-4 right-4 w-24 h-24 dot-pattern opacity-30 pointer-events-none"></div>
                </div>

            </div>
        </div>
    </section>

    <!-- 3. PILIHAN KOMPETENSI KEAHLIAN & HASIL SELEKSI -->
    <section id="kompetensi" class="py-16 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <!-- Header -->
            <div class="text-center max-w-3xl mx-auto mb-14">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-3 tracking-tight">
                    Pilihan Kompetensi Keahlian & Hasil Seleksi
                </h2>
                <p class="text-slate-500 text-sm sm:text-base leading-relaxed">
                    Fasilitas sekolah dengan kapasitas luas untuk berbagai kebutuhan acara institusi.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- LEFT PANEL: Informasi -->
                <div class="lg:col-span-4 space-y-6">
                    
                    <!-- Daya Tampung Card -->
                    <div class="bg-[#F8FAFC] border border-slate-200 rounded-3xl p-6 shadow-sm">
                        <h3 class="text-base font-bold text-slate-800 mb-4 border-b-2 border-brand-blue inline-block pb-1">
                            Daya Tampung
                        </h3>
                        <ul class="space-y-2 text-xs sm:text-sm text-slate-600">
                            <li class="flex justify-between items-center py-1 border-b border-slate-100">
                                <span>Rekayasa Perangkat Lunak</span>
                                <span class="font-bold text-slate-800">: 108 Siswa</span>
                            </li>
                            <li class="flex justify-between items-center py-1 border-b border-slate-100">
                                <span>Ototronik</span>
                                <span class="font-bold text-slate-800">: 108 Siswa</span>
                            </li>
                            <li class="flex justify-between items-center py-1 border-b border-slate-100">
                                <span>Mesin</span>
                                <span class="font-bold text-slate-800">: 108 Siswa</span>
                            </li>
                            <li class="flex justify-between items-center py-1 border-b border-slate-100">
                                <span>Teknik Pembuatan Kain</span>
                                <span class="font-bold text-slate-800">: 108 Siswa</span>
                            </li>
                            <li class="flex justify-between items-center py-2 mt-2 border-t-2 border-slate-200">
                                <span class="font-bold text-slate-800">Total</span>
                                <span class="font-bold text-brand-blue text-base">: 432 Siswa</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Jalur Seleksi Card -->
                    <div class="bg-[#F8FAFC] border border-slate-200 rounded-3xl p-6 shadow-sm">
                        <h3 class="text-base font-bold text-slate-800 mb-4 border-b-2 border-brand-blue inline-block pb-1">
                            Jalur Seleksi
                        </h3>
                        <ul class="space-y-2 text-xs sm:text-sm text-slate-600">
                            <li class="flex justify-between items-center py-1 border-b border-slate-100">
                                <span>Prestasi</span>
                                <span class="font-bold text-slate-800">: 75%</span>
                            </li>
                            <li class="flex justify-between items-center py-1 border-b border-slate-100">
                                <span>Afirmasi</span>
                                <span class="font-bold text-slate-800">: 15%</span>
                            </li>
                            <li class="flex justify-between items-center py-1 border-b border-slate-100">
                                <span>Domisili Terdekat</span>
                                <span class="font-bold text-slate-800">: 10%</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Hasil Seleksi Card -->
                    <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
                        <h3 class="text-base font-bold text-slate-800 mb-3 border-b-2 border-brand-blue inline-block pb-1">
                            Hasil Seleksi
                        </h3>
                        <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                            Unduh hasil seleksi PPDB SMKN 2 KARANGANYAR.
                        </p>
                        <button onclick="openModal('Download Hasil Seleksi', 'Mengunduh berkas hasil seleksi PPDB...')"
                            class="w-full bg-brand-blue hover:bg-brand-darkBlue text-white font-bold text-xs py-3 rounded-xl transition-all shadow-md flex items-center justify-center gap-2">
                            <i class="fa-solid fa-download"></i> Download PDF
                        </button>
                    </div>

                </div>

                <!-- RIGHT PANEL: Grid Kompetensi Keahlian -->
                <div class="lg:col-span-8 grid grid-cols-1 sm:grid-cols-2 gap-6">
                    
                    <!-- Card 1: RPL -->
                    <div class="relative rounded-3xl overflow-hidden shadow-md group h-64 lg:h-72">
                        <img src="https://images.unsplash.com/photo-1531403009284-440f080d1e12?w=600&auto=format&fit=crop&q=80"
                            alt="Rekayasa Perangkat Lunak" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent flex flex-col justify-end p-6">
                            <span class="inline-block bg-emerald-500 text-white text-[10px] font-bold px-3 py-1 rounded w-fit mb-2">Terpopuler</span>
                            <h3 class="text-white font-extrabold text-xl sm:text-2xl leading-tight">Rekayasa Perangkat Lunak</h3>
                        </div>
                    </div>

                    <!-- Card 2: Teknik Pembuatan Kain -->
                    <div class="relative rounded-3xl overflow-hidden shadow-md group h-64 lg:h-72">
                        <img src="https://images.unsplash.com/photo-1528459801416-a9e53bbf4e17?w=600&auto=format&fit=crop&q=80"
                            alt="Teknik Pembuatan Kain" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent flex flex-col justify-end p-6">
                            <h3 class="text-white font-extrabold text-xl sm:text-2xl leading-tight">Teknik Pembuatan Kain</h3>
                        </div>
                    </div>

                    <!-- Card 3: Teknik Ototronik -->
                    <div class="relative rounded-3xl overflow-hidden shadow-md group h-64 lg:h-72">
                        <img src="https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?w=600&auto=format&fit=crop&q=80"
                            alt="Teknik Ototronik" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent flex flex-col justify-end p-6">
                            <h3 class="text-white font-extrabold text-xl sm:text-2xl leading-tight">Teknik Ototronik</h3>
                        </div>
                    </div>

                    <!-- Card 4: Teknik Permesinan -->
                    <div class="relative rounded-3xl overflow-hidden shadow-md group h-64 lg:h-72">
                        <img src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=600&auto=format&fit=crop&q=80"
                            alt="Teknik Permesinan" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent flex flex-col justify-end p-6">
                            <h3 class="text-white font-extrabold text-xl sm:text-2xl leading-tight">Teknik Permesinan</h3>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <!-- ============================================================
         FOOTER & PETA LOKASI
         ============================================================ -->

@endsection
