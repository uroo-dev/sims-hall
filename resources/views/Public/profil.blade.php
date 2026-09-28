@extends('Public.layout.app')

@section('title', 'Profile Sekolah - SMK Negeri 2 Karanganyar')
@section('description', 'Sejarah, sambutan Kepala Sekolah, visi dan misi SMK Negeri 2 Karanganyar.')

@section('content')

    <!-- KONTEN PROFILE — BAGIAN 1: SEJARAH SEKOLAH -->
    <section id="sejarah" class="relative pt-10 pb-16 md:pb-20 overflow-hidden">
        <!-- Decorative Dot Pattern -->
        <div class="absolute top-8 left-6 w-24 h-24 dot-pattern opacity-40 pointer-events-none hidden sm:block"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            <!-- FOTO BANNER GERBANG SEKOLAH -->
            <div class="relative rounded-3xl overflow-hidden shadow-2xl">
                <img src="{{ asset('assets/Sejarah.jpg') }}" alt="Gerbang SMK Negeri 2 Karanganyar"
                    class="w-full h-72 sm:h-80 md:h-[26rem] object-cover">

                <!-- Overlay Gelap -->
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/40 to-slate-900/20">
                </div>
            </div>

            <!-- KARTU SEJARAH (Overlap ke atas foto) -->
            <div
                class="relative -mt-12 sm:-mt-20 mx-auto max-w-5xl bg-white rounded-3xl shadow-2xl border border-slate-100 p-6 sm:p-10 md:p-12">
                <h2
                    class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-slate-900 text-center mb-6 sm:mb-8 tracking-tight">
                    Sejarah SMKN 2 KARANGANYAR
                </h2>

                <div class="space-y-5 text-slate-600 text-sm sm:text-base leading-relaxed text-justify-custom">
                    <p>
                        Sekolah Menengah Kejuruan Negeri 2 Karanganyar atau yang sering disebut dengan SMK N 2
                        Karanganyar adalah sebuah Sekolah Menengah Kejuruan yang berlokasi di Jl. Lakada Yos Sudarso
                        (Telp. (0271) 494549 Fax. (0271) 6498171 Bejen, Karanganyar 57716.
                    </p>

                    <p>
                        SMK N 2 Karanganyar berdiri sejak tahun 1997 di area tanah seluas 27.720 m<sup>2</sup> dan
                        diresmikan pada tanggal 18 November 1997 oleh Menteri Pendidikan Nasional yaitu Prof. Dr. Ing.
                        Wardiman Djojonegoro dengan satu program studi teknik Mesin.
                    </p>

                    <p>
                        Sekolah ini pertamakali dipimpin oleh Kepala Sekolah Drs. Surip Sunarmo dari Tahun Pelajaran
                        1997/1998 hingga Tahun Pelajaran 2005/2006. Setelah itu dijabat oleh Bapak Kepala Sekolah pada
                        Tahun Pelajaran 2004/2005 untuk membuka satu Program Studi Teknologi Tekstil. Pada Tahun
                        Pelajaran 2006/2007, sekolah juga membuka satu Program Studi Keahlian Rekayasa Perangkat Lunak.
                        Kemudian pada Tahun Pelajaran 2008/2009, SMK N 2 Karanganyar membuka satu Program Studi Keahlian
                        Teknik Ototronik. Kepala Sekolah yang pernah menjabat mulai dari Bapak Drs. Suryanto HS, S.Pd.,
                        M.M. hingga Bapak S. Suharto, M.M. sebagai pelaksana tugas Kepala Sekolah. Kemudian Tahun
                        Pelajaran 2007/2008 dari seorang guru Karanganyar yang lulus ujian dan mendapatkan surat
                        keputusan Bupati sebagai Kepala Sekolah yaitu Drs. Wahyu Widodo, M.T.
                    </p>

                    <p>
                        Ditahun Pelajaran 2008/2009, SMK N 2 Karanganyar membuka 2 Program Studi sekaligus, yaitu
                        Teknik Otomotif Elektronik dan Rekayasa Perangkat Lunak.
                    </p>
                </div>

                <!-- Highlight Strip Identitas Sekolah -->
                <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-brand-lightBlue rounded-2xl p-5 text-center border border-blue-100">
                        <div class="text-2xl font-black text-brand-blue mb-1">1997</div>
                        <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Tahun Berdiri</div>
                    </div>
                    <div class="bg-brand-lightBlue rounded-2xl p-5 text-center border border-blue-100">
                        <div class="text-2xl font-black text-brand-blue mb-1">27.720 m<sup>2</sup></div>
                        <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Luas Area</div>
                    </div>
                    <div class="bg-brand-lightBlue rounded-2xl p-5 text-center border border-blue-100">
                        <div class="text-2xl font-black text-brand-blue mb-1">4</div>
                        <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Kompetensi Keahlian
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- KONTEN PROFILE — BAGIAN 2: KEPALA SEKOLAH & SAMBUTAN -->
    <section id="kepsek" class="py-16 bg-white relative overflow-hidden">
        <!-- Decorative Dot Pattern Kanan Atas -->
        <div class="absolute top-10 right-8 w-24 h-24 dot-pattern opacity-40 pointer-events-none hidden sm:block"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            <!-- Judul Utama -->
            <div class="text-center max-w-3xl mx-auto mb-14">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Kepala Sekolah SMKN 2 KARANGANYAR
                </h2>
                <div class="w-24 h-1 bg-brand-blue rounded-full mx-auto mt-4"></div>
            </div>

            <!-- Layout: Foto Kepala Sekolah (Kiri) + Sambutan (Kanan) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">

                <!-- SISI KIRI: Foto & Kartu Nama -->
                <div class="lg:col-span-4 h-full">
                    <div
                        class="bg-white border border-slate-200 rounded-3xl overflow-hidden card-shadow h-full flex flex-col">
                        <!-- Foto Kepala Sekolah -->
                        <div class="relative bg-gradient-to-br from-brand-blue to-blue-900 flex-grow">
                            <!-- Ornamen Bendera Merah Putih -->
                            <div class="absolute top-0 left-5 w-1.5 h-20 bg-red-600 z-20 rounded-b"></div>
                            <div class="absolute top-0 left-[1.625rem] w-1.5 h-20 bg-white z-20 rounded-b"></div>

                            <img src="{{ asset('assets/foto-kepsek.png') }}" alt="Bapak Sukidi S. Pd., M. Pd."
                                class="w-full h-full object-cover object-top min-h-[20rem]">
                        </div>

                        <!-- Kartu Nama -->
                        <div class="bg-white border-t-4 border-brand-blue p-6 text-center">
                            <h3 class="text-lg sm:text-xl font-extrabold text-slate-900 leading-tight">
                                Bapak Sukidi S. Pd., M. Pd.
                            </h3>
                            <p class="text-xs sm:text-sm font-semibold text-brand-blue mt-1">
                                Kepala Sekolah SMKN 2 Karanganyar
                            </p>
                        </div>
                    </div>
                </div>

                <!-- SISI KANAN: Sambutan, Quote, Visi & Misi -->
                <div class="lg:col-span-8 space-y-6">

                    <!-- Kartu Sambutan -->
                    <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-9 card-shadow">
                        <div class="mb-6">
                            <h3
                                class="text-xl sm:text-2xl font-bold text-slate-900 border-b-4 border-brand-blue inline-block pb-1.5">
                                Sambutan Kepala Sekolah
                            </h3>
                        </div>

                        <div class="space-y-4 text-slate-600 text-sm sm:text-base leading-relaxed text-justify-custom">
                            <p class="font-semibold text-slate-800 italic">
                                Bismillahirrohmanirrohim
                            </p>
                            <p class="font-semibold text-slate-800">
                                Assalamualaikum Warahmatullahi Wabarakatuh
                            </p>

                            <p>
                                Alhamdulillahhi robbil alamin kami panjatkan kehadirat Allah SWT, bahwasannya dengan
                                rahmat dan karunia-Nya lah akhirnya Website sekolah ini dengan alamat
                                <span class="font-semibold text-brand-blue">www.smkn2karanganyar.sch.id</span> dapat
                                kami perbaharui. Kami mengucapkan selamat datang di Website kami Sekolah Menengah
                                Kejuruan Negeri (SMKN) 2 Karanganyar yang saya tujukan untuk seluruh unsur pimpinan,
                                guru, karyawan dan siswa serta khalayak umum guna dapat mengakses seluruh informasi
                                tentang segala profil, aktifitas/kegiatan serta fasilitas sekolah kami.
                            </p>

                            <p>
                                Kami selaku pimpinan sekolah mengucapkan terima kasih kepada tim pembuat Website ini
                                yang telah berusaha untuk dapat lebih memperkenalkan segala perihal yang dimiliki oleh
                                sekolah. Dan tentunya Website sekolah kami masih terdapat banyak kekurangan, oleh karena
                                itu kepada seluruh lapisan masyarakat dapat memberikan saran dan kritik yang membangun
                                demi kemajuan Website ini lebih lanjut.
                            </p>

                            <p>
                                Terima kasih sekian yang dapat kami sampaikan, apabila terdapat kekurangan dan
                                kealahan, mohon dimaafkan.
                            </p>

                            <p class="font-semibold text-slate-800">
                                Wassalamualaikum Warahmatullahi Wabarakatuh
                            </p>
                        </div>

                        <!-- Quote Slogan -->
                        <div
                            class="mt-8 bg-brand-lightBlue border-l-4 border-brand-blue rounded-r-2xl px-5 py-4 text-center">
                            <p class="text-xs sm:text-sm font-extrabold text-brand-blue tracking-wide uppercase">
                                " SMK Bisa, SMK Hebat, SMK Bisa Hebat, SMKN 2 Karanganyar PASTI BISA "
                            </p>
                        </div>
                    </div>

                    <!-- Kartu Visi & Misi -->
                    <div class="bg-white border-2 border-slate-200 rounded-3xl p-6 sm:p-8 card-shadow">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">

                            <!-- VISI -->
                            <div class="md:col-span-5">
                                <h4
                                    class="text-base font-extrabold text-slate-900 uppercase tracking-wider mb-3 flex items-center gap-2">
                                    <i class="fa-solid fa-eye text-brand-blue"></i> Visi
                                </h4>
                                <p class="text-slate-600 text-xs sm:text-sm leading-relaxed text-justify-custom">
                                    Terwujudnya Lulusan yang Berkarakter, Berprestasi, Berwawasan Global dan Berbudaya
                                    Lingkungan
                                </p>
                            </div>

                            <!-- Divider -->
                            <div class="hidden md:block md:col-span-1">
                                <div class="w-px h-full bg-slate-200 mx-auto"></div>
                            </div>

                            <!-- MISI -->
                            <div class="md:col-span-6">
                                <h4
                                    class="text-base font-extrabold text-slate-900 uppercase tracking-wider mb-3 flex items-center gap-2">
                                    <i class="fa-solid fa-bullseye text-brand-blue"></i> Misi
                                </h4>
                                <ol class="space-y-2 text-slate-600 text-xs sm:text-sm leading-relaxed">
                                    <li class="flex gap-2">
                                        <span class="font-bold text-brand-blue">1.</span>
                                        <span>Menanamkan keimanan dan ketaqwaan kepada Tuhan YME lewat pengamalan ajaran
                                            agama</span>
                                    </li>
                                    <li class="flex gap-2">
                                        <span class="font-bold text-brand-blue">2.</span>
                                        <span>Mewujudkan profil lulusan yang kompetitif, kolaboratif dan
                                            berintegritas</span>
                                    </li>
                                    <li class="flex gap-2">
                                        <span class="font-bold text-brand-blue">3.</span>
                                        <span>Menyelenggarakan Penyelenggaraan yang Efektifitas, Berwawasan Global dan
                                            Berbudaya Lingkungan</span>
                                    </li>
                                </ol>
                            </div>

                        </div>
                    </div>

                </div>

            </div>

        </div>
    </section>

@endsection
