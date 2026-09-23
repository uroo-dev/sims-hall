@extends('Admin.layout.app')

@section('title', 'Dashboard BKK & PKL - SMK Negeri 2 Karanganyar')

@section('sidebar')
    @include('Admin.layout.sidebar-pklbkk')
@endsection

@section('content')
    <main class="flex-1 p-6 lg:p-8 overflow-y-auto space-y-6">

        <!-- TOP HEADER BAR -->
        <header class="w-full bg-white rounded-2xl p-4 md:px-6 md:py-4 figma-card-shadow flex flex-col md:flex-row items-center justify-between gap-4 border border-slate-100">

            <!-- Breadcrumb Title -->
            <div class="flex items-center space-x-2 text-slate-800 text-sm md:text-base font-bold tracking-tight">
                <span id="breadcrumb-role" class="uppercase text-slate-900 font-extrabold">Admin BKK &amp; PKL</span>
                <span class="text-slate-400 font-normal"><i class="fa-solid fa-chevron-right text-xs"></i></span>
                <span id="breadcrumb-page" class="text-slate-500 font-medium">Dashboard</span>
            </div>

            <!-- Right Action Icons & User Info -->
            <div class="flex items-center space-x-4">

                <!-- Info Pill -->
                <div class="hidden lg:flex items-center space-x-2.5 bg-slate-100 hover:bg-slate-200/80 border border-slate-200/80 px-3.5 py-1.5 rounded-full transition-colors">
                    <i class="fa-solid fa-briefcase text-brand-600"></i>
                    <span class="text-[11px] font-semibold text-slate-600">Bursa Kerja Khusus &amp; Praktik Kerja Lapangan</span>
                </div>

                <!-- Gear Icon Button -->
                <button title="Pengaturan" class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 text-brand-600 flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-gear text-lg"></i>
                </button>

                <!-- User Profile Badge Pill -->
                <div class="flex items-center space-x-3 bg-slate-100 hover:bg-slate-200/80 border border-slate-200/80 px-3.5 py-1.5 rounded-full cursor-pointer transition-colors">
                    <div class="w-7 h-7 rounded-full bg-white text-brand-600 border border-slate-300 flex items-center justify-center">
                        <i class="fa-solid fa-user text-xs"></i>
                    </div>
                    <div class="text-left">
                        <p id="header-user-name" class="text-xs font-bold text-slate-800 leading-tight">{{ auth()->user()->name }}</p>
                        <p id="header-user-role" class="text-[10px] text-slate-500 leading-tight">Admin BKK &amp; PKL</p>
                    </div>
                    <i class="fa-solid fa-chevron-down text-xs text-slate-500 ml-1"></i>
                </div>

            </div>
        </header>

        <!-- 3 TOP STAT CARDS GRID -->
        <section id="dashboard" class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100 flex items-center justify-between transition-transform duration-200 hover:-translate-y-1">
                <div>
                    <h3 class="text-xs md:text-sm font-extrabold text-brand-600 uppercase tracking-wide leading-snug max-w-[150px]">Perusahaan Mitra</h3>
                    <p class="text-[10px] text-slate-400 mt-1 font-medium">Tempat PKL terdaftar</p>
                </div>
                <div id="stat-mitra" class="text-4xl lg:text-5xl font-extrabold text-brand-600 pl-2"></div>
            </div>

            <div class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100 flex items-center justify-between transition-transform duration-200 hover:-translate-y-1">
                <div>
                    <h3 class="text-xs md:text-sm font-extrabold text-brand-600 uppercase tracking-wide leading-snug max-w-[150px]">Loker Aktif BKK</h3>
                    <p class="text-[10px] text-slate-400 mt-1 font-medium">Lowongan kerja aktif</p>
                </div>
                <div id="stat-loker" class="text-4xl lg:text-5xl font-extrabold text-brand-600 pl-2"></div>
            </div>

            <div class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100 flex items-center justify-between transition-transform duration-200 hover:-translate-y-1">
                <div>
                    <h3 class="text-xs md:text-sm font-extrabold text-brand-600 uppercase tracking-wide leading-snug max-w-[150px]">Data Pelamar &amp; Tracer</h3>
                    <p class="text-[10px] text-slate-400 mt-1 font-medium">Alumni terdaftar</p>
                </div>
                <div id="stat-pelamar" class="text-4xl lg:text-5xl font-extrabold text-brand-600 pl-2"></div>
            </div>

        </section>

        <!-- ===== LOWONGAN KERJA (KELOLA BKK) ===== -->
        <section id="loker" class="scroll-mt-6 space-y-6">
            <div class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100">
                <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
                    <div class="flex items-center space-x-2">
                        <h2 class="text-sm font-extrabold text-brand-600 uppercase tracking-wider">Kelola BKK - Lowongan Kerja</h2>
                        <span id="loker-count" class="px-2 py-0.5 bg-brand-50 text-brand-600 rounded-full text-[10px] font-bold"></span>
                    </div>
                    <button id="btn-tambah-loker" class="px-3.5 py-1.5 bg-brand-600 hover:bg-brand-700 text-white rounded-full text-[11px] font-semibold flex items-center space-x-1.5 transition-colors shadow-sm">
                        <i class="fa-solid fa-plus text-[10px]"></i>
                        <span>Tambah Loker</span>
                    </button>
                </div>

                <!-- Filter Jurusan -->
                <div id="filter-loker" class="flex flex-wrap items-center gap-2 mb-5"></div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs md:text-sm">
                        <thead>
                            <tr class="border-b-2 border-slate-800 text-slate-800 font-extrabold">
                                <th class="py-3 px-2">ID</th>
                                <th class="py-3 px-2">Perusahaan</th>
                                <th class="py-3 px-2">Posisi</th>
                                <th class="py-3 px-2">Jurusan</th>
                                <th class="py-3 px-2 text-center">Kuota</th>
                                <th class="py-3 px-2">Tutup</th>
                                <th class="py-3 px-2">Status</th>
                                <th class="py-3 px-2 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="loker-table" class="divide-y divide-slate-200 text-slate-700 font-medium"></tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- ===== DATA PELAMAR & TRACER STUDY ===== -->
        <section id="pelamar" class="scroll-mt-6 space-y-6">
            <div class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-sm font-extrabold text-brand-600 uppercase tracking-wider">Data Pelamar &amp; Tracer Study</h2>
                    <button class="px-3.5 py-1.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 rounded-full text-[11px] font-semibold flex items-center space-x-1.5 transition-colors">
                        <i class="fa-solid fa-file-export text-[10px]"></i>
                        <span>Unduh</span>
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs md:text-sm">
                        <thead>
                            <tr class="border-b-2 border-slate-800 text-slate-800 font-extrabold">
                                <th class="py-3 px-2">ID</th>
                                <th class="py-3 px-2">Nama Alumni</th>
                                <th class="py-3 px-2">Angkatan</th>
                                <th class="py-3 px-2">Jurusan</th>
                                <th class="py-3 px-2">Melamar Di</th>
                                <th class="py-3 px-2">Status</th>
                                <th class="py-3 px-2">Tracer Study</th>
                            </tr>
                        </thead>
                        <tbody id="pelamar-table" class="divide-y divide-slate-200 text-slate-700 font-medium"></tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- ===== TEMPAT PKL (KELOLA PKL) ===== -->
        <section id="temppkl" class="scroll-mt-6 space-y-6">
            <div class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100">
                <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
                    <div class="flex items-center space-x-2">
                        <h2 class="text-sm font-extrabold text-brand-600 uppercase tracking-wider">Kelola PKL - Tempat PKL</h2>
                        <span id="mitra-count" class="px-2 py-0.5 bg-brand-50 text-brand-600 rounded-full text-[10px] font-bold"></span>
                    </div>
                    <button id="btn-tambah-mitra" class="px-3.5 py-1.5 bg-brand-600 hover:bg-brand-700 text-white rounded-full text-[11px] font-semibold flex items-center space-x-1.5 transition-colors shadow-sm">
                        <i class="fa-solid fa-plus text-[10px]"></i>
                        <span>Tambah Mitra</span>
                    </button>
                </div>

                <!-- Filter Jurusan -->
                <div id="filter-mitra" class="flex flex-wrap items-center gap-2 mb-5"></div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs md:text-sm">
                        <thead>
                            <tr class="border-b-2 border-slate-800 text-slate-800 font-extrabold">
                                <th class="py-3 px-2">ID</th>
                                <th class="py-3 px-2">Nama Perusahaan</th>
                                <th class="py-3 px-2">Jurusan</th>
                                <th class="py-3 px-2">Bidang</th>
                                <th class="py-3 px-2 text-center">Kuota</th>
                                <th class="py-3 px-2 text-center">Terisi</th>
                                <th class="py-3 px-2">Status</th>
                                <th class="py-3 px-2 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="mitra-table" class="divide-y divide-slate-200 text-slate-700 font-medium"></tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- ===== REKAP JURNAL & ABSENSI SISWA ===== -->
        <section id="jurnal" class="scroll-mt-6 space-y-6">
            <div class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-sm font-extrabold text-brand-600 uppercase tracking-wider">Rekap Jurnal &amp; Absensi Siswa</h2>
                    <span class="text-[10px] font-semibold text-slate-400">Laporan harian dari siswa PKL</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs md:text-sm">
                        <thead>
                            <tr class="border-b-2 border-slate-800 text-slate-800 font-extrabold">
                                <th class="py-3 px-2">NIS</th>
                                <th class="py-3 px-2">Nama Siswa</th>
                                <th class="py-3 px-2">Jurusan</th>
                                <th class="py-3 px-2">Tempat PKL</th>
                                <th class="py-3 px-2">Kehadiran</th>
                                <th class="py-3 px-2 text-center">Progress</th>
                                <th class="py-3 px-2">Verifikasi</th>
                                <th class="py-3 px-2 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="jurnal-table" class="divide-y divide-slate-200 text-slate-700 font-medium"></tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- ===== REKAP NILAI PKL ===== -->
        <section id="nilai" class="scroll-mt-6 space-y-6">
            <div class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-sm font-extrabold text-brand-600 uppercase tracking-wider">Rekap Nilai AKhir PKL</h2>
                    <div class="flex items-center space-x-2">
                        <button class="px-3.5 py-1.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 rounded-full text-[11px] font-semibold flex items-center space-x-1.5 transition-colors">
                            <i class="fa-solid fa-file-arrow-down text-[10px]"></i>
                            <span>Unduh Nilai</span>
                        </button>
                        <button class="px-3.5 py-1.5 bg-brand-600 hover:bg-brand-700 text-white rounded-full text-[11px] font-semibold flex items-center space-x-1.5 transition-colors">
                            <i class="fa-solid fa-print text-[10px]"></i>
                            <span>Cetak</span>
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs md:text-sm">
                        <thead>
                            <tr class="border-b-2 border-slate-800 text-slate-800 font-extrabold">
                                <th class="py-3 px-2">ID</th>
                                <th class="py-3 px-2">Nama Siswa</th>
                                <th class="py-3 px-2">Kelas</th>
                                <th class="py-3 px-2">Semester</th>
                                <th class="py-3 px-2 text-center">Nilai</th>
                                <th class="py-3 px-2 text-center">Predikat</th>
                                <th class="py-3 px-2">Status</th>
                                <th class="py-3 px-2 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="nilai-table" class="divide-y divide-slate-200 text-slate-700 font-medium"></tbody>
                    </table>
                </div>
            </div>
        </section>

    </main>

    <!-- ===== MODAL TAMBAH / EDIT ===== -->
    <div id="modal-backdrop" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4">
        <div class="w-full max-w-md bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100">
            <div class="flex items-center justify-between mb-5">
                <h3 id="modal-title" class="text-sm font-extrabold text-slate-800 uppercase tracking-wider"></h3>
                <button onclick="closeModal()" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="space-y-4">
                <div>
                    <label id="modal-label-1" class="block text-xs font-semibold text-slate-600 mb-1.5"></label>
                    <input id="modal-input-1" type="text" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:bg-white focus:ring-2 focus:ring-brand-600/15">
                </div>
                <div>
                    <label id="modal-label-2" class="block text-xs font-semibold text-slate-600 mb-1.5"></label>
                    <input id="modal-input-2" type="text" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:bg-white focus:ring-2 focus:ring-brand-600/15">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Jurusan</label>
                        <select id="modal-jurusan" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:bg-white"></select>
                    </div>
                    <div>
                        <label id="modal-label-3" class="block text-xs font-semibold text-slate-600 mb-1.5"></label>
                        <input id="modal-input-3" type="text" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:bg-white focus:ring-2 focus:ring-brand-600/15">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label id="modal-label-4" class="block text-xs font-semibold text-slate-600 mb-1.5"></label>
                        <input id="modal-input-4" type="text" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:bg-white focus:ring-2 focus:ring-brand-600/15">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Status</label>
                        <select id="modal-status" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:bg-white">
                            <option>Aktif</option>
                            <option>Pending</option>
                            <option>Tutup</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex space-x-3 mt-6">
                <button onclick="closeModal()" class="flex-1 py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold rounded-full text-sm transition-colors">Batal</button>
                <button onclick="saveModal()" class="flex-1 py-2.5 px-4 bg-brand-600 hover:bg-brand-700 text-white font-semibold rounded-full text-sm shadow-md transition-colors">Simpan</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // ===== KONSTANTA & DATA DEMO =====
        const JURUSAN = ['RPL', 'TKJ', 'TKR', 'TBSM', 'DKV', 'AK'];
        const filterState = { loker: 'Semua', mitra: 'Semua' };
        let modalContext = null;

        let lokers = [
            { id: 'LKR-01', perusahaan: 'PT Telkom Indonesia', posisi: 'Software Developer', jurusan: 'RPL', kuota: 3, deadline: '2026-10-15', status: 'Aktif' },
            { id: 'LKR-02', perusahaan: 'Nasmoco Karanganyar', posisi: 'Teknisi Mekanik', jurusan: 'TKR', kuota: 5, deadline: '2026-11-01', status: 'Aktif' },
            { id: 'LKR-03', perusahaan: 'Bank Jateng', posisi: 'Teller', jurusan: 'AK', kuota: 2, deadline: '2026-09-30', status: 'Pending' },
            { id: 'LKR-04', perusahaan: 'Indomaret Group', posisi: 'Staff Administrasi', jurusan: 'AK', kuota: 4, deadline: '2026-10-20', status: 'Aktif' },
            { id: 'LKR-05', perusahaan: 'Software House Solo', posisi: 'UI/UX Designer', jurusan: 'DKV', kuota: 2, deadline: '2026-10-05', status: 'Aktif' },
            { id: 'LKR-06', perusahaan: 'PT PLN ULP Karanganyar', posisi: 'Operator Jaringan', jurusan: 'TBSM', kuota: 3, deadline: '2026-09-25', status: 'Tutup' },
            { id: 'LKR-07', perusahaan: 'SOLO.NET', posisi: 'Network Administrator', jurusan: 'TKJ', kuota: 2, deadline: '2026-11-15', status: 'Pending' },
            { id: 'LKR-08', perusahaan: 'Astra Isuzu', posisi: 'Quality Control', jurusan: 'TKR', kuota: 2, deadline: '2026-10-10', status: 'Aktif' },
        ];

        let mitraData = [
            { id: 'DUDI-01', nama: 'PT Telekomunikasi Indonesia', jurusan: 'RPL', bidang: 'Jaringan & IT', kuota: 12, terisi: 8, status: 'Aktif' },
            { id: 'DUDI-02', nama: 'Nasmoco Karanganyar', jurusan: 'TKR', bidang: 'Otomotif', kuota: 8, terisi: 8, status: 'Aktif' },
            { id: 'DUDI-03', nama: 'Software House Solo', jurusan: 'RPL', bidang: 'Software Development', kuota: 15, terisi: 9, status: 'Aktif' },
            { id: 'DUDI-04', nama: 'PT PLN ULP Karanganyar', jurusan: 'TBSM', bidang: 'Kelistrikan & Jaringan', kuota: 6, terisi: 4, status: 'Aktif' },
            { id: 'DUDI-05', nama: 'Hotel Brothers Karanganyar', jurusan: 'AK', bidang: 'Hospitality & Administrasi', kuota: 5, terisi: 2, status: 'Pending' },
            { id: 'DUDI-06', nama: 'Pindad Enjiniring Indonesia', jurusan: 'TBSM', bidang: 'Manufaktur & Perbengkelan', kuota: 10, terisi: 10, status: 'Aktif' },
            { id: 'DUDI-07', nama: 'Dinas Kominfo Kab. Karanganyar', jurusan: 'DKV', bidang: 'IT & Public Relation', kuota: 4, terisi: 1, status: 'Pending' },
            { id: 'DUDI-08', nama: 'PT Pertamina Patra Niaga', jurusan: 'TBSM', bidang: 'Logistik & Energi', kuota: 6, terisi: 0, status: 'Aktif' },
        ];

        const pelamarData = [
            { id: 'PLM-01', nama: 'Andika Pratama', angkatan: 2025, jurusan: 'RPL', melamar: 'Software Developer - PT Telkom', status: 'Diterima', tracer: 'Terisi' },
            { id: 'PLM-02', nama: 'Siti Nurhaliza', angkatan: 2024, jurusan: 'AK', melamar: 'Staff Admin - Indomaret', status: 'Proses', tracer: 'Belum' },
            { id: 'PLM-03', nama: 'Bagas Saputra', angkatan: 2025, jurusan: 'TKJ', melamar: 'Network Admin - SOLO.NET', status: 'Proses', tracer: 'Terisi' },
            { id: 'PLM-04', nama: 'Rani Kusuma', angkatan: 2025, jurusan: 'DKV', melamar: 'UI/UX - Software House Solo', status: 'Diterima', tracer: 'Terisi' },
            { id: 'PLM-05', nama: 'Dimas Anggara', angkatan: 2024, jurusan: 'TKR', melamar: 'Teknisi - Nasmoco', status: 'Ditolak', tracer: 'Terisi' },
            { id: 'PLM-06', nama: 'Fitri Handayani', angkatan: 2025, jurusan: 'AK', melamar: 'Teller - Bank Jateng', status: 'Proses', tracer: 'Belum' },
            { id: 'PLM-07', nama: 'Yusuf Maulana', angkatan: 2024, jurusan: 'RPL', melamar: 'Software Developer - PT Telkom', status: 'Diterima', tracer: 'Terisi' },
            { id: 'PLM-08', nama: 'Dewi Lestari', angkatan: 2025, jurusan: 'TBSM', melamar: 'Operator - PT PLN', status: 'Proses', tracer: 'Belum' },
        ];

        let jurnalData = [
            { id: 'JR-001', nis: '011', nama: 'Andi Pratama', jurusan: 'RPL', tempat: 'PT Telkom Indonesia', kehadiran: 'Hadir', progress: '38/90', status: 'Terverifikasi' },
            { id: 'JR-002', nis: '021', nama: 'Siti Rahmawati', jurusan: 'TKR', tempat: 'Nasmoco Karanganyar', kehadiran: 'Hadir', progress: '41/90', status: 'Terverifikasi' },
            { id: 'JR-003', nis: '034', nama: 'Bagas Saputra', jurusan: 'TKJ', tempat: 'PT PLN ULP Karanganyar', kehadiran: 'Sakit', progress: '22/90', status: 'Pending' },
            { id: 'JR-004', nis: '045', nama: 'Rani Kusuma', jurusan: 'DKV', tempat: 'Software House Solo', kehadiran: 'Hadir', progress: '45/90', status: 'Terverifikasi' },
            { id: 'JR-005', nis: '012', nama: 'Dimas Anggara', jurusan: 'TBSM', tempat: 'Pindad Enjiniring', kehadiran: 'Izin', progress: '30/90', status: 'Pending' },
            { id: 'JR-006', nis: '058', nama: 'Fitri Handayani', jurusan: 'AK', tempat: 'Hotel Brothers', kehadiran: 'Hadir', progress: '12/90', status: 'Pending' },
            { id: 'JR-007', nis: '071', nama: 'Yusuf Maulana', jurusan: 'RPL', tempat: 'PT Telkom Indonesia', kehadiran: 'Hadir', progress: '50/90', status: 'Terverifikasi' },
            { id: 'JR-008', nis: '033', nama: 'Dewi Lestari', jurusan: 'TBSM', tempat: 'PT Pertamina Patra Niaga', kehadiran: 'Alpa', progress: '5/90', status: 'Revisi' },
        ];

        let nilaiData = [
            { id: 'NR-01', nama: 'Andi Pratama', kelas: 'XII RPL 1', semester: 'Ganjil', nilai: 88, predikat: 'A', status: 'Tuntas' },
            { id: 'NR-02', nama: 'Siti Rahmawati', kelas: 'XII TKR 2', semester: 'Ganjil', nilai: 85, predikat: 'A', status: 'Tuntas' },
            { id: 'NR-03', nama: 'Bagas Saputra', kelas: 'XII TKJ 1', semester: 'Ganjil', nilai: 79, predikat: 'B', status: 'Tuntas' },
            { id: 'NR-04', nama: 'Rani Kusuma', kelas: 'XII DKV 1', semester: 'Ganjil', nilai: 91, predikat: 'A', status: 'Tuntas' },
            { id: 'NR-05', nama: 'Dimas Anggara', kelas: 'XII TBSM 1', semester: 'Ganjil', nilai: 74, predikat: 'B', status: 'Belum' },
            { id: 'NR-06', nama: 'Fitri Handayani', kelas: 'XII AK 1', semester: 'Ganjil', nilai: 68, predikat: 'C', status: 'Belum' },
            { id: 'NR-07', nama: 'Yusuf Maulana', kelas: 'XII RPL 2', semester: 'Ganjil', nilai: 86, predikat: 'A', status: 'Tuntas' },
            { id: 'NR-08', nama: 'Dewi Lestari', kelas: 'XII TBSM 2', semester: 'Ganjil', nilai: 55, predikat: 'D', status: 'Belum' },
        ];

        // ===== UTIL =====
        function statusBadge(status) {
            let tone;
            if (['Aktif', 'Terverifikasi', 'Diterima', 'Terisi', 'Tuntas', 'Hadir'].includes(status)) {
                tone = 'emerald';
            } else if (['Tutup', 'Revisi', 'Ditolak', 'Alpa', 'Belum'].includes(status)) {
                tone = 'red';
            } else {
                tone = 'amber';
            }
            const badgeMap = {
                emerald: 'bg-emerald-100 text-emerald-700',
                red: 'bg-red-100 text-red-700',
                amber: 'bg-amber-100 text-amber-700',
            };
            const dotMap = { emerald: 'bg-emerald-500', red: 'bg-red-500', amber: 'bg-amber-500' };
            return '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide ' + badgeMap[tone] + '"><span class="w-1.5 h-1.5 rounded-full ' + dotMap[tone] + '"></span>' + status + '</span>';
        }

        function nextId(prefix) {
            const items = prefix === 'LKR' ? lokers : mitraData;
            const max = items.reduce((acc, item) => {
                const num = parseInt(item.id.split('-')[1], 10);
                return num > acc ? num : acc;
            }, 0);
            return prefix + '-' + String(max + 1).padStart(2, '0');
        }

        function chipClass(active) {
            return active
                ? 'px-3 py-1.5 rounded-full text-xs font-semibold border bg-brand-600 border-brand-600 text-white shadow-sm transition-colors'
                : 'px-3 py-1.5 rounded-full text-xs font-semibold border border-slate-200 bg-white text-slate-600 hover:border-brand-500 hover:text-brand-600 transition-colors';
        }

        function renderChips(containerId, onFilter) {
            const container = document.getElementById(containerId);
            const options = ['Semua', ...JURUSAN];
            container.innerHTML = options.map(jurusan => {
                const active = filterState[onFilter] === jurusan;
                return '<button type="button" data-filter="' + onFilter + '" data-jurusan="' + jurusan + '" class="' + chipClass(active) + '" onclick="filterSection(\'' + onFilter + '\',\'' + jurusan + '\')">' + jurusan + '</button>';
            }).join('');
        }

        function filterSection(section, jurusan) {
            filterState[section] = jurusan;
            const containerId = section === 'loker' ? 'filter-loker' : 'filter-mitra';
            renderChips(containerId, section);
            renderLoker();
            renderMitra();
        }

        const iconBtn = '<i class="fa-solid fa-pen text-[10px]"></i>';
        const trashBtn = '<i class="fa-solid fa-trash-can text-[10px]"></i>';
        const actionBtnBase = 'w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-400 hover:text-brand-600 flex items-center justify-center transition-colors';

        // ===== RENDER LOKER =====
        function renderLoker() {
            const filter = filterState.loker;
            const rows = filter === 'Semua' ? lokers : lokers.filter(l => l.jurusan === filter);

            document.getElementById('loker-count').innerText = lokers.length + ' loker terdaftar';
            document.getElementById('loker-table').innerHTML = rows.map(l => `
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="py-3.5 px-2 font-bold text-slate-800">${l.id}</td>
                    <td class="py-3.5 px-2 text-slate-600">${l.perusahaan}</td>
                    <td class="py-3.5 px-2 font-semibold">${l.posisi}</td>
                    <td class="py-3.5 px-2"><span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded-md text-[10px] font-bold">${l.jurusan}</span></td>
                    <td class="py-3.5 px-2 text-center font-bold">${l.kuota}</td>
                    <td class="py-3.5 px-2 text-slate-500">${l.deadline}</td>
                    <td class="py-3.5 px-2">${statusBadge(l.status)}</td>
                    <td class="py-3.5 px-2">
                        <div class="flex items-center justify-end space-x-1.5">
                            <button class="${actionBtnBase}" title="Edit" onclick="openLokerModal('${l.id}')">${iconBtn}</button>
                            <button class="${actionBtnBase} hover:bg-red-50 hover:text-red-600" title="Hapus" onclick="deleteLoker('${l.id}')">${trashBtn}</button>
                        </div>
                    </td>
                </tr>
            `).join('') || '<tr><td colspan="8" class="py-8 text-center text-slate-400">Tidak ada loker untuk jurusan ini.</td></tr>';
        }

        function deleteLoker(id) {
            if (!confirm('Hapus loker ' + id + '?')) return;
            lokers = lokers.filter(l => l.id !== id);
            renderLoker();
            renderMitra();
        }

        // ===== RENDER MITRA (TEMPAT PKL) =====
        function renderMitra() {
            const filter = filterState.mitra;
            const rows = filter === 'Semua' ? mitraData : mitraData.filter(m => m.jurusan === filter);

            document.getElementById('mitra-count').innerText = mitraData.length + ' mitra terdaftar';
            document.getElementById('mitra-table').innerHTML = rows.map(m => `
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="py-3.5 px-2 font-bold text-slate-800">${m.id}</td>
                    <td class="py-3.5 px-2 text-slate-600 font-semibold">${m.nama}</td>
                    <td class="py-3.5 px-2"><span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded-md text-[10px] font-bold">${m.jurusan}</span></td>
                    <td class="py-3.5 px-2 text-slate-500">${m.bidang}</td>
                    <td class="py-3.5 px-2 text-center font-bold">${m.kuota}</td>
                    <td class="py-3.5 px-2 text-center">${m.terisi}</td>
                    <td class="py-3.5 px-2">${statusBadge(m.status)}</td>
                    <td class="py-3.5 px-2">
                        <div class="flex items-center justify-end space-x-1.5">
                            <button class="${actionBtnBase}" title="Edit" onclick="openMitraModal('${m.id}')">${iconBtn}</button>
                            <button class="${actionBtnBase} hover:bg-red-50 hover:text-red-600" title="Hapus" onclick="deleteMitra('${m.id}')">${trashBtn}</button>
                        </div>
                    </td>
                </tr>
            `).join('') || '<tr><td colspan="8" class="py-8 text-center text-slate-400">Tidak ada mitra untuk jurusan ini.</td></tr>';
        }

        function deleteMitra(id) {
            if (!confirm('Hapus mitra ' + id + '?')) return;
            mitraData = mitraData.filter(m => m.id !== id);
            renderMitra();
            renderLoker();
        }

        // ===== RENDER PELAMAR =====
        function renderPelamar() {
            document.getElementById('pelamar-table').innerHTML = pelamarData.map(p => `
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="py-3.5 px-2 font-bold text-slate-800">${p.id}</td>
                    <td class="py-3.5 px-2 font-semibold">${p.nama}</td>
                    <td class="py-3.5 px-2 text-slate-500">${p.angkatan}</td>
                    <td class="py-3.5 px-2"><span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded-md text-[10px] font-bold">${p.jurusan}</span></td>
                    <td class="py-3.5 px-2 text-slate-600">${p.melamar}</td>
                    <td class="py-3.5 px-2">${statusBadge(p.status)}</td>
                    <td class="py-3.5 px-2">${statusBadge(p.tracer)}</td>
                </tr>
            `).join('');
        }

        // ===== REKAP JURNAL & ABSEN =====
        function renderJurnal() {
            document.getElementById('jurnal-table').innerHTML = jurnalData.map(j => `
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="py-3.5 px-2 font-bold text-slate-800">${j.id}</td>
                    <td class="py-3.5 px-2 font-semibold">${j.nama}</td>
                    <td class="py-3.5 px-2"><span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded-md text-[10px] font-bold">${j.jurusan}</span></td>
                    <td class="py-3.5 px-2 text-slate-600">${j.tempat}</td>
                    <td class="py-3.5 px-2">${statusBadge(j.kehadiran)}</td>
                    <td class="py-3.5 px-2">
                        <div class="flex items-center justify-center space-x-2">
                            <div class="w-16 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full bg-brand-600" style="width:${parseInt(j.progress.split('/')[0], 10)}%"></div>
                            </div>
                            <span class="text-[10px] font-bold text-slate-500">${j.progress}</span>
                        </div>
                    </td>
                    <td class="py-3.5 px-2">${statusBadge(j.status)}</td>
                    <td class="py-3.5 px-2">
                        <div class="flex items-center justify-end">
                            ${j.status === 'Pending' || j.status === 'Revisi'
                                ? '<button class="px-2.5 py-1 bg-brand-600 hover:bg-brand-700 text-white rounded-full text-[10px] font-bold transition-colors" onclick="verifyJurnal(\'' + j.id + '\')">Verifikasi</button>'
                                : '<span class="text-[10px] font-bold text-emerald-600"><i class="fa-solid fa-circle-check mr-1"></i>Sudah</span>'}
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function verifyJurnal(id) {
            const item = jurnalData.find(j => j.id === id);
            if (!item) return;
            item.status = 'Terverifikasi';
            renderJurnal();
        }

        // ===== REKAP NILAI =====
        function renderNilai() {
            document.getElementById('nilai-table').innerHTML = nilaiData.map(n => `
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="py-3.5 px-2 font-bold text-slate-800">${n.id}</td>
                    <td class="py-3.5 px-2 font-semibold">${n.nama}</td>
                    <td class="py-3.5 px-2 text-slate-500">${n.kelas}</td>
                    <td class="py-3.5 px-2 text-slate-500">${n.semester}</td>
                    <td class="py-3.5 px-2">
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-brand-50 text-brand-700 text-base font-extrabold">${n.nilai}</span>
                    </td>
                    <td class="py-3.5 px-2 text-center font-bold text-slate-600">${n.predikat}</td>
                    <td class="py-3.5 px-2">${statusBadge(n.status)}</td>
                    <td class="py-3.5 px-2">
                        <div class="flex items-center justify-end">
                            <button class="${actionBtnBase} hover:text-brand-600" title="Input nilai" onclick="inputNilai('${n.id}')">${iconBtn}</button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function inputNilai(id) {
            const item = nilaiData.find(n => n.id === id);
            if (!item) return;
            const nilai = prompt('Input nilai akhir PKL untuk ' + item.nama + ':', item.nilai);
            if (nilai === null) return;
            const num = Math.max(0, Math.min(100, parseInt(nilai, 10) || 0));
            item.nilai = num;
            item.predikat = num >= 90 ? 'A' : num >= 80 ? 'B' : num >= 70 ? 'C' : 'D';
            item.status = num >= 70 ? 'Tuntas' : 'Belum';
            renderNilai();
        }

        // ===== MODAL CRUD =====
        function fillJurusanSelect(selected) {
            document.getElementById('modal-jurusan').innerHTML = JURUSAN.map(j =>
                '<option value="' + j + '"' + (j === selected ? ' selected' : '') + '>' + j + '</option>'
            ).join('');
        }

        function openModal(title) {
            document.getElementById('modal-title').innerText = title;
            document.getElementById('modal-backdrop').classList.remove('hidden');
            document.getElementById('modal-backdrop').classList.add('flex');
        }

        function closeModal() {
            document.getElementById('modal-backdrop').classList.add('hidden');
            document.getElementById('modal-backdrop').classList.remove('flex');
            modalContext = null;
        }

        function openLokerModal(id) {
            const loker = lokers.find(l => l.id === id) || {
                id: nextId('LKR'),
                perusahaan: '',
                posisi: '',
                jurusan: JURUSAN[0],
                kuota: '',
                deadline: new Date(Date.now() + 30 * 86400000).toISOString().split('T')[0],
                status: 'Pending',
            };
            modalContext = { mode: 'loker', id: loker.id };

            document.getElementById('modal-label-1').innerText = 'Nama Perusahaan';
            document.getElementById('modal-input-1').value = loker.perusahaan;
            document.getElementById('modal-label-2').innerText = 'Posisi / Jabatan';
            document.getElementById('modal-input-2').value = loker.posisi;
            document.getElementById('modal-label-3').innerText = 'Kuota';
            document.getElementById('modal-input-3').value = loker.kuota;
            document.getElementById('modal-label-4').innerText = 'Batas Pendaftaran';
            document.getElementById('modal-input-4').value = loker.deadline;
            fillJurusanSelect(loker.jurusan);
            document.getElementById('modal-status').value = loker.status;

            openModal(id ? 'Edit Lowongan Kerja' : 'Tambah Lowongan Kerja');
        }

        function openMitraModal(id) {
            const mitra = mitraData.find(m => m.id === id) || {
                id: nextId('DUDI'),
                nama: '',
                jurusan: JURUSAN[0],
                bidang: '',
                kuota: '',
                terisi: 0,
                status: 'Pending',
            };
            modalContext = { mode: 'mitra', id: mitra.id };

            document.getElementById('modal-label-1').innerText = 'Nama Perusahaan';
            document.getElementById('modal-input-1').value = mitra.nama;
            document.getElementById('modal-label-2').innerText = 'Bidang Industri';
            document.getElementById('modal-input-2').value = mitra.bidang;
            document.getElementById('modal-label-3').innerText = 'Kuota Siswa';
            document.getElementById('modal-input-3').value = mitra.kuota;
            document.getElementById('modal-label-4').innerText = 'Terisi';
            document.getElementById('modal-input-4').value = mitra.terisi;
            fillJurusanSelect(mitra.jurusan);
            document.getElementById('modal-status').value = mitra.status;

            openModal(id ? 'Edit Tempat PKL' : 'Tambah Tempat PKL');
        }

        function saveModal() {
            if (!modalContext) return;

            const read = () => ({
                jurusan: document.getElementById('modal-jurusan').value,
                status: document.getElementById('modal-status').value,
                v1: document.getElementById('modal-input-1').value.trim(),
                v2: document.getElementById('modal-input-2').value.trim(),
                v3: document.getElementById('modal-input-3').value.trim(),
                v4: document.getElementById('modal-input-4').value.trim(),
            });

            if (modalContext.mode === 'loker') {
                const d = read();
                if (!d.v1 || !d.v2) { alert('Perusahaan dan posisi wajib diisi.'); return; }
                const payload = {
                    id: modalContext.id,
                    perusahaan: d.v1,
                    posisi: d.v2,
                    jurusan: d.jurusan,
                    kuota: d.v3 ? parseInt(d.v3, 10) : 0,
                    deadline: d.v4,
                    status: d.status,
                };
                const idx = lokers.findIndex(l => l.id === modalContext.id);
                if (idx >= 0) lokers[idx] = payload;
                else lokers.unshift(payload);
            } else if (modalContext.mode === 'mitra') {
                const d = read();
                if (!d.v1) { alert('Nama perusahaan wajib diisi.'); return; }
                const payload = {
                    id: modalContext.id,
                    nama: d.v1,
                    jurusan: d.jurusan,
                    bidang: d.v2,
                    kuota: d.v3 ? parseInt(d.v3, 10) : 0,
                    terisi: d.v4 ? parseInt(d.v4, 10) : 0,
                    status: d.status,
                };
                const idx = mitraData.findIndex(m => m.id === modalContext.id);
                if (idx >= 0) mitraData[idx] = payload;
                else mitraData.push(payload);
            }

            closeModal();
            renderLoker();
            renderMitra();
            updateStats();
        }

        // ===== STAT & NAV =====
        function updateStats() {
            const aktif = lokers.filter(l => l.status === 'Aktif').length;
            document.getElementById('stat-mitra').innerText = mitraData.length;
            document.getElementById('stat-loker').innerText = aktif;
            document.getElementById('stat-pelamar').innerText = pelamarData.length;
        }

        function scrollToSection(id) {
            const el = document.getElementById(id);
            if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        function setActivePklMenu(section) {
            document.querySelectorAll('.pkl-menu').forEach(el => {
                const isDashboard = el.dataset.section === 'dashboard';
                const isActive = el.dataset.section === section;
                if (isDashboard) {
                    el.className = 'pkl-menu flex items-center space-x-3.5 px-5 py-3 rounded-full transition-all duration-200 ' +
                        (section === 'dashboard'
                            ? 'bg-white text-brand-600 font-semibold shadow-md'
                            : 'bg-white/10 text-white/90 hover:bg-white/15 font-normal');
                    return;
                }
                el.className = 'pkl-menu flex items-center space-x-3.5 px-5 py-2.5 rounded-xl transition-all duration-200 ' +
                    (isActive
                        ? 'bg-white/15 text-white font-semibold'
                        : 'text-white/90 hover:bg-white/10 font-normal');
            });

            const pageMap = {
                dashboard: 'Dashboard',
                loker: 'Lowongan Kerja',
                pelamar: 'Data Pelamar',
                temppkl: 'Tempat PKL',
                jurnal: 'Jurnal & Absensi',
                nilai: 'Rekap Nilai PKL',
            };
            const breadcrumb = document.getElementById('breadcrumb-page');
            if (breadcrumb && pageMap[section]) breadcrumb.innerText = pageMap[section];
            scrollToSection(section);
        }

        // ===== INIT =====
        document.getElementById('btn-tambah-loker').addEventListener('click', () => openLokerModal(null));
        document.getElementById('btn-tambah-mitra').addEventListener('click', () => openMitraModal(null));

        document.getElementById('modal-backdrop').addEventListener('click', (e) => {
            if (e.target.id === 'modal-backdrop') closeModal();
        });

        renderChips('filter-loker', 'loker');
        renderChips('filter-mitra', 'mitra');
        renderLoker();
        renderMitra();
        renderPelamar();
        renderJurnal();
        renderNilai();
        updateStats();
    </script>
@endpush