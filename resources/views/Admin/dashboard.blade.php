@extends('Admin.layout.app')

@section('title', 'Dashboard Admin - SMK Negeri 2 Karanganyar')
@section('content')


            <!-- CARD 1: KEPALA SEKOLAH PHOTO CARD -->
            <div
                class="bg-white rounded-2xl p-4 card-shadow border border-gray-100/80 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="font-bold text-brand-600 text-xs tracking-wider uppercase">KEPALA SEKOLAH</h2>
                        <a href="#"
                            class="bg-brand-600 text-white text-[10px] font-medium px-3 py-1 rounded-full flex items-center gap-1 hover:bg-brand-700 transition">
                            <i class="fa-solid fa-eye text-[9px]"></i> Selengkapnya
                        </a>
                    </div>
                    <div class="relative rounded-lg overflow-hidden border border-gray-200 bg-gray-100 mb-3 group">
                        <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&q=80&w=600"
                            alt="Bapak Sukidi S.Pd., M.Pd."
                            class="w-full h-44 object-cover object-center group-hover:scale-105 transition duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent">
                        </div>
                        <div class="absolute bottom-2 left-3 text-white">
                            <p class="text-[10px] bg-brand-600 px-2 py-0.5 rounded text-white inline-block mb-0.5">
                                Kepala SMK Negeri 2 Karanganyar</p>
                        </div>
                    </div>
                </div>
                <div class="text-center pt-1">
                    <h3 class="font-bold text-gray-800 text-sm">Bapak Sukidi S.Pd., M.Pd.</h3>
                    <p class="text-gray-400 text-[11px]">Kepala Sekolah SMKN 2 Karanganyar</p>
                </div>
            </div>

            <!-- CARD 2: KEPALA SEKOLAH STATS TABLE -->
            <div
                class="bg-white rounded-2xl p-4 card-shadow border border-gray-100/80 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="font-bold text-brand-600 text-xs tracking-wider uppercase">KEPALA SEKOLAH</h2>
                        <a href="#"
                            class="bg-brand-600 text-white text-[10px] font-medium px-3 py-1 rounded-full flex items-center gap-1 hover:bg-brand-700 transition">
                            <i class="fa-solid fa-eye text-[9px]"></i> Selengkapnya
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-left">
                            <thead>
                                <tr class="border-b border-gray-200 text-gray-700 font-bold">
                                    <th class="py-1.5 px-2">Instansi</th>
                                    <th class="py-1.5 px-2 text-right">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-gray-600">
                                <tr>
                                    <td class="py-1.5 px-2 font-medium text-gray-800">Organisasi</td>
                                    <td class="py-1.5 px-2 text-right font-bold text-brand-600">5</td>
                                </tr>
                                <tr>
                                    <td class="py-1.5 px-2 font-medium text-gray-800">Guru</td>
                                    <td class="py-1.5 px-2 text-right font-bold text-brand-600">10</td>
                                </tr>
                                <tr>
                                    <td class="py-1.5 px-2 font-medium text-gray-800">Kepala Sekolah</td>
                                    <td class="py-1.5 px-2 text-right font-bold text-brand-600">1</td>
                                </tr>
                                <tr>
                                    <td class="py-1.5 px-2 font-medium text-gray-800">Instansi Luar Terikat</td>
                                    <td class="py-1.5 px-2 text-right font-bold text-brand-600">15</td>
                                </tr>
                                <tr>
                                    <td class="py-1.5 px-2 font-medium text-gray-800">Instansi Luar</td>
                                    <td class="py-1.5 px-2 text-right font-bold text-brand-600">15</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- CARD 3: SEJARAH EXCERPT CARD -->
            <div
                class="bg-white rounded-2xl p-4 card-shadow border border-gray-100/80 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="font-bold text-brand-600 text-xs tracking-wider uppercase">SEJARAH</h2>
                        <a href="#"
                            class="bg-brand-600 text-white text-[10px] font-medium px-3 py-1 rounded-full flex items-center gap-1 hover:bg-brand-700 transition">
                            <i class="fa-solid fa-eye text-[9px]"></i> Selengkapnya
                        </a>
                    </div>
                    <p class="text-xs text-gray-600 leading-relaxed font-normal">
                        Sekolah Menengah Kejuruan Negeri 2 Karanganyar atau yang sering disebut dengan SMK N 2
                        Karanganyar adalah sebuah Sekolah Menengah Kejuruan yang berlokasi di Jl. Laksda Yos Sudarso
                        Telp. (0271) 494549 Fax. (0271) 6498171 Bejan, Karanganyar 57715...
                    </p>
                </div>
                <div
                    class="mt-4 pt-3 border-t border-gray-100 text-[11px] text-gray-400 flex items-center justify-between">
                    <span><i class="fa-regular fa-clock mr-1"></i> Terakhir Diperbarui</span>
                    <span class="font-medium text-gray-600">Hari ini</span>
                </div>
            </div>

        </div>

        <!-- ROW 2: LAPORAN SISTEM & JUMLAH PEMINJAMAN AULA CHART -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

            <!-- LAPORAN SISTEM TABLE (7 COLS) -->
            <div
                class="lg:col-span-7 bg-white rounded-2xl p-5 card-shadow border border-gray-100/80 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-bold text-brand-600 text-sm tracking-wider uppercase">LAPORAN SISTEM</h2>
                        <a href="#"
                            class="bg-brand-600 text-white text-[11px] font-medium px-3 py-1 rounded-full flex items-center gap-1 hover:bg-brand-700 transition">
                            <i class="fa-solid fa-eye text-[9px]"></i> Selengkapnya
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-left">
                            <thead>
                                <tr class="border-b-2 border-gray-200 text-gray-700 font-bold">
                                    <th class="py-2.5 px-2">ID</th>
                                    <th class="py-2.5 px-2">Email Instansi</th>
                                    <th class="py-2.5 px-2">Paket Peminjaman</th>
                                    <th class="py-2.5 px-2">Metode</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-gray-600">
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="py-2.5 px-2 font-semibold text-gray-800">ORD-001</td>
                                    <td class="py-2.5 px-2">Sekretariat@smkndk.sch.id</td>
                                    <td class="py-2.5 px-2"><span
                                            class="bg-blue-50 text-brand-600 px-2 py-0.5 rounded font-medium">Unggulan</span>
                                    </td>
                                    <td class="py-2.5 px-2">Transfer</td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="py-2.5 px-2 font-semibold text-gray-800">ORD-002</td>
                                    <td class="py-2.5 px-2">POS@smkn2kra.sch.id</td>
                                    <td class="py-2.5 px-2"><span
                                            class="bg-gray-100 text-gray-700 px-2 py-0.5 rounded font-medium">Standar
                                            2</span></td>
                                    <td class="py-2.5 px-2">Transfer</td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="py-2.5 px-2 font-semibold text-gray-800">ORD-003</td>
                                    <td class="py-2.5 px-2">Guru@smkn2kra.sch.id</td>
                                    <td class="py-2.5 px-2"><span
                                            class="bg-gray-100 text-gray-700 px-2 py-0.5 rounded font-medium">Standar
                                            1</span></td>
                                    <td class="py-2.5 px-2">Transfer</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- JUMLAH PEMINJAMAN AULA CHART (5 COLS) -->
            <div
                class="lg:col-span-5 bg-white rounded-2xl p-5 card-shadow border border-gray-100/80 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <h2 class="font-bold text-brand-600 text-xs md:text-sm tracking-wider uppercase">JUMLAH
                            PEMINJAMAN AULA</h2>
                        <a href="#"
                            class="bg-brand-600 text-white text-[10px] font-medium px-3 py-1 rounded-full flex items-center gap-1 hover:bg-brand-700 transition">
                            <i class="fa-solid fa-eye text-[9px]"></i> Selengkapnya
                        </a>
                    </div>
                    <div class="h-52 w-full pt-2">
                        <canvas id="aulaChart"></canvas>
                    </div>
                </div>
            </div>

        </div>

        <!-- ROW 3: EKSTRAKULIKULER & PPDB INFO -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

            <!-- EKSTRAKULIKULER CARD -->
            <div
                class="bg-white rounded-2xl p-5 card-shadow border border-gray-100/80 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-bold text-brand-600 text-sm tracking-wider uppercase">EKSTRAKULIKULER</h2>
                        <a href="#"
                            class="bg-brand-600 text-white text-[11px] font-medium px-3 py-1 rounded-full flex items-center gap-1 hover:bg-brand-700 transition">
                            <i class="fa-solid fa-eye text-[9px]"></i> Selengkapnya
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-left">
                            <thead>
                                <tr class="border-b-2 border-gray-200 text-gray-700 font-bold">
                                    <th class="py-2 px-2">Nama</th>
                                    <th class="py-2 px-2 text-right">Logo</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-gray-600">
                                <tr>
                                    <td class="py-2 px-2 font-medium">Organisasi OSIS</td>
                                    <td class="py-2 px-2 text-right">
                                        <div
                                            class="w-7 h-7 rounded-full bg-amber-100 border border-amber-300 text-amber-700 font-bold text-[10px] flex items-center justify-center ml-auto shadow-sm">
                                            OSIS
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-2 px-2 font-medium">Organisasi PMR</td>
                                    <td class="py-2 px-2 text-right">
                                        <div
                                            class="w-7 h-7 rounded-full bg-red-100 border border-red-300 text-red-600 font-bold text-xs flex items-center justify-center ml-auto shadow-sm">
                                            <i class="fa-solid fa-plus"></i>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-2 px-2 font-medium">Organisasi PKS</td>
                                    <td class="py-2 px-2 text-right">
                                        <div
                                            class="w-7 h-7 rounded-full bg-yellow-100 border border-yellow-400 text-yellow-800 font-bold text-xs flex items-center justify-center ml-auto shadow-sm">
                                            <i class="fa-solid fa-shield"></i>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-2 px-2 font-medium">Organisasi AMBALAN</td>
                                    <td class="py-2 px-2 text-right">
                                        <div
                                            class="w-7 h-7 rounded-full bg-emerald-100 border border-emerald-400 text-emerald-800 font-bold text-xs flex items-center justify-center ml-auto shadow-sm">
                                            <i class="fa-solid fa-campground"></i>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-2 px-2 font-medium">Organisasi ROHIS</td>
                                    <td class="py-2 px-2 text-right">
                                        <div
                                            class="w-7 h-7 rounded-full bg-teal-100 border border-teal-300 text-teal-800 font-bold text-xs flex items-center justify-center ml-auto shadow-sm">
                                            <i class="fa-solid fa-kaaba"></i>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-2 px-2 font-medium">Organisasi JURNALISTIK</td>
                                    <td class="py-2 px-2 text-right">
                                        <div
                                            class="w-7 h-7 rounded-full bg-indigo-100 border border-indigo-300 text-indigo-700 font-bold text-xs flex items-center justify-center ml-auto shadow-sm">
                                            <i class="fa-solid fa-newspaper"></i>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- PPDB SMKN 2 KARANGANYAR CARD -->
            <div
                class="bg-white rounded-2xl p-5 card-shadow border border-gray-100/80 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="font-bold text-brand-600 text-sm tracking-wider uppercase">PPDB SMKN 2
                            KARANGANYAR</h2>
                        <a href="#"
                            class="bg-brand-600 text-white text-[11px] font-medium px-3 py-1 rounded-full flex items-center gap-1 hover:bg-brand-700 transition">
                            <i class="fa-solid fa-eye text-[9px]"></i> Selengkapnya
                        </a>
                    </div>

                    <!-- DAYA TAMPUNG SECTION -->
                    <div class="mb-4">
                        <div class="text-xs font-bold text-gray-800 border-b border-gray-200 pb-1 mb-2">
                            Daya Tampung
                        </div>
                        <div class="space-y-1.5 text-xs text-gray-700">
                            <div class="flex justify-between">
                                <span>Rekayasa Perangkat Lunak</span>
                                <span class="font-semibold text-gray-900">: 108 Siswa</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Ototronik</span>
                                <span class="font-semibold text-gray-900">: 108 Siswa</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Permesinan</span>
                                <span class="font-semibold text-gray-900">: 108 Siswa</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Teknik Pembuatan Kain</span>
                                <span class="font-semibold text-gray-900">: 108 Siswa</span>
                            </div>
                            <div
                                class="flex justify-between pt-1 border-t border-dashed border-gray-200 font-bold text-brand-600">
                                <span>Total Quota</span>
                                <span>: 432 Siswa</span>
                            </div>
                        </div>
                    </div>

                    <!-- JALUR SELEKSI SECTION -->
                    <div>
                        <div class="text-xs font-bold text-gray-800 border-b border-gray-200 pb-1 mb-2">
                            Jalur Seleksi
                        </div>
                        <div class="space-y-1.5 text-xs text-gray-700">
                            <div class="flex justify-between">
                                <span>Prestasi</span>
                                <span class="font-semibold text-gray-900">: 75%</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Afirmasi</span>
                                <span class="font-semibold text-gray-900">: 15%</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Domisili Terdekat</span>
                                <span class="font-semibold text-gray-900">: 10%</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <!-- ROW 4: MITRA DUDI (PARTNERS LOGOS) -->
        <div class="bg-white rounded-2xl p-5 card-shadow border border-gray-100/80">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500">MITRA KERJA SAMA DUDI</h3>
                <span class="text-[11px] text-gray-400">Industri & Perusahaan Terkait</span>
            </div>
            <div
                class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-6 items-center justify-items-center opacity-85 hover:opacity-100 transition duration-300">

                <!-- LOGO EXP -->
                <div class="p-2 flex items-center justify-center grayscale hover:grayscale-0 transition">
                    <div
                        class="border-2 border-blue-800 text-blue-800 font-black px-3 py-1 rounded text-xl tracking-tighter italic">
                        EXP
                    </div>
                </div>

                <!-- LOGO MSM SOLO -->
                <div class="p-2 flex items-center justify-center grayscale hover:grayscale-0 transition">
                    <div class="flex items-center gap-1">
                        <div
                            class="w-7 h-7 rounded-full bg-red-600 text-white font-bold flex items-center justify-center text-xs">
                            msm</div>
                        <span class="font-extrabold text-gray-800 text-sm">MSM Solo</span>
                    </div>
                </div>

                <!-- LOGO NASMOCO -->
                <div class="p-2 flex items-center justify-center grayscale hover:grayscale-0 transition">
                    <div class="flex items-center gap-1">
                        <i class="fa-solid fa-car-side text-yellow-500 text-xl"></i>
                        <span class="font-black italic text-gray-800 text-sm tracking-wider">NASMOCO</span>
                    </div>
                </div>

                <!-- LOGO TOYOTA -->
                <div class="p-2 flex items-center justify-center grayscale hover:grayscale-0 transition">
                    <div class="flex flex-col items-center">
                        <i class="fa-solid fa-circle-nodes text-red-600 text-2xl"></i>
                        <span class="font-bold text-gray-800 text-[10px] tracking-widest uppercase">TOYOTA</span>
                    </div>
                </div>

                <!-- LOGO PT YICHAD TEXTILE INDONESIA -->
                <div class="p-2 flex items-center justify-center grayscale hover:grayscale-0 transition">
                    <div class="flex items-center gap-1.5 text-left">
                        <i class="fa-solid fa-shapes text-emerald-600 text-lg"></i>
                        <div class="leading-none">
                            <p class="text-[9px] font-black text-gray-800">PT. YICHAD</p>
                            <p class="text-[8px] font-bold text-gray-500">TEXTILE INDONESIA</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

@endsection

