@extends('Admin.layout.app')

@section('title', 'Informasi & Persyaratan PPDB')

@section('content')
<div class="space-y-6">

    <!-- PAGE HEADER -->
    <div class="bg-white rounded-2xl p-5 md:p-6 figma-card-shadow border border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-brand-600 mb-1">
                <i class="fa-solid fa-graduation-cap"></i>
                <span class="uppercase tracking-wider">Modul PPDB Online</span>
            </div>
            <h1 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight">Informasi &amp; Persyaratan PPDB</h1>
            <p class="text-xs md:text-sm text-slate-500 mt-1">
                Kelola jadwal tanggal penting, kriteria persyaratan, daya tampung jurusan, jalur pendaftaran, dan dokumen kelulusan.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('index.dashboard.ppdb') }}"
                class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs md:text-sm font-bold shadow-xs transition flex items-center gap-2">
                <i class="fa-solid fa-table-cells-large text-xs text-slate-500"></i>
                <span>Dashboard PPDB</span>
            </a>
            <a href="{{ route('ppdb') }}" target="_blank"
                class="px-4 py-2.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-brand-700 text-xs md:text-sm font-bold transition flex items-center gap-2">
                <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                <span>Lihat Halaman Publik</span>
            </a>
        </div>
    </div>


    <!-- SECTION 1: HEADER & KETERANGAN INFORMASI PPDB -->
    <section class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100 space-y-5">
        <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-brand-600 flex items-center justify-center text-base shrink-0">
                <i class="fa-solid fa-heading"></i>
            </div>
            <div>
                <h2 class="text-sm md:text-base font-extrabold text-slate-800 uppercase tracking-wide">
                    Header &amp; Keterangan Halaman
                </h2>
                <p class="text-xs text-slate-500">
                    Judul dan keterangan ringkas pembuka pada section informasi detail pendaftaran PPDB.
                </p>
            </div>
        </div>

        <form action="{{ route('update.informasi.ppdb') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
                <div class="lg:col-span-4 space-y-1.5">
                    <label for="judul" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Judul Bagian <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="judul" id="judul" maxlength="100" required
                        value="{{ old('judul', $informasi?->judul) }}" placeholder="Contoh: Informasi PPDB 2026/2027"
                        class="w-full px-4 py-2.5 bg-slate-50/80 text-slate-800 text-sm font-medium rounded-xl border border-slate-200 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-100 focus:outline-none transition shadow-xs placeholder:text-slate-400">
                </div>

                <div class="lg:col-span-8 space-y-1.5">
                    <label for="keterangan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Keterangan Singkat
                    </label>
                    <input type="text" name="keterangan" id="keterangan" maxlength="255"
                        value="{{ old('keterangan', $informasi?->keterangan) }}"
                        placeholder="Contoh: Petunjuk teknis pendaftaran, jadwal seleksi, dan persyaratan umum calon peserta didik baru."
                        class="w-full px-4 py-2.5 bg-slate-50/80 text-slate-800 text-sm font-medium rounded-xl border border-slate-200 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-100 focus:outline-none transition shadow-xs placeholder:text-slate-400">
                </div>
            </div>

            <div class="pt-2 flex justify-end">
                <button type="submit"
                    class="py-2.5 px-5 rounded-xl bg-[#0073c6] hover:bg-[#005fa4] active:scale-[0.98] text-white font-bold text-xs md:text-sm shadow-md shadow-blue-500/20 transition flex items-center justify-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Simpan Header</span>
                </button>
            </div>
        </form>
    </section>

    <!-- SECTION 2: TANGGAL PENTING & KALENDER AGENDA -->
    <section class="space-y-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-brand-600 flex items-center justify-center text-base shrink-0">
                <i class="fa-solid fa-calendar-days"></i>
            </div>
            <div>
                <h2 class="text-sm md:text-base font-extrabold text-slate-800 uppercase tracking-wide">
                    Tanggal Penting &amp; Agenda Seleksi
                </h2>
                <p class="text-xs text-slate-500">
                    Klik tanggal pada kalender untuk menetapkan rentang tanggal atau mengisi formulir agenda di sebelah kanan.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- CARD KALENDER INTERAKTIF (7 Kolom) -->
            <div class="lg:col-span-7 bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100 space-y-4 relative isolate">
                <!-- Navigasi Bulan -->
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <button type="button" id="btnPrev" title="Bulan sebelumnya"
                        class="w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 active:scale-95 transition cursor-pointer">
                        <i class="fa-solid fa-chevron-left text-xs"></i>
                    </button>
                    <span id="calendarTitle"
                        class="text-sm font-extrabold uppercase tracking-wider text-brand-700"></span>
                    <button type="button" id="btnNext" title="Bulan berikutnya"
                        class="w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 active:scale-95 transition cursor-pointer">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </button>
                </div>

                <!-- Nama Hari -->
                <div class="grid grid-cols-7 gap-1">
                    @foreach (['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $hari)
                        <div class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400 text-center py-1">
                            {{ $hari }}
                        </div>
                    @endforeach
                </div>

                <!-- Grid Sel Tanggal -->
                <div id="calendarGrid" class="grid grid-cols-7 gap-1.5 min-h-[250px]"></div>

                <!-- Legenda & Keterangan -->
                <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-slate-100 text-xs font-semibold text-slate-500">
                    <div class="flex items-center gap-4">
                        <span class="flex items-center gap-1.5">
                            <i class="w-3.5 h-3.5 rounded bg-brand-600"></i> Ada Agenda
                        </span>
                        <span class="flex items-center gap-1.5">
                            <i class="w-3.5 h-3.5 rounded bg-brand-100 border border-brand-300"></i> Dipilih
                        </span>
                    </div>
                    <span class="text-[11px] text-slate-400">Arahkan kursor ke tanggal untuk detail</span>
                </div>

                <!-- Tooltip Agenda Hover -->
                <div id="agendaTooltip"
                    class="hidden absolute z-50 w-72 p-3.5 rounded-2xl bg-slate-900/95 text-white text-xs shadow-2xl backdrop-blur-md pointer-events-auto border border-white/10">
                    <div class="pointer-events-auto" data-tooltip-content></div>
                </div>
            </div>

            <!-- CARD FORM AGENDA (5 Kolom) -->
            <form id="agendaForm" action="{{ route('post.tanggal-penting.ppdb') }}" method="POST"
                class="lg:col-span-5 bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100 space-y-4">
                @csrf
                <div id="agendaMethod"></div>

                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-pen-to-square text-brand-600 text-sm"></i>
                        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wide">Form Agenda PPDB</h3>
                    </div>
                    <span class="text-[10px] text-slate-400 font-medium">Buat atau perbarui</span>
                </div>

                <!-- Banner Edit Mode -->
                <div id="editBanner"
                    class="hidden p-3 rounded-xl text-xs font-medium bg-blue-50 text-brand-800 border border-brand-200 flex items-center justify-between gap-2">
                    <span class="flex items-center gap-2 min-w-0">
                        <i class="fa-solid fa-pen text-xs shrink-0 text-brand-600"></i>
                        <span class="truncate">Mode Edit: <strong id="editName"></strong></span>
                    </span>
                    <button type="button" onclick="resetForm()" title="Batal mode edit"
                        class="shrink-0 w-6 h-6 flex items-center justify-center rounded-lg hover:bg-brand-100 text-slate-600 transition cursor-pointer">
                        <i class="fa-solid fa-xmark text-xs"></i>
                    </button>
                </div>

                <!-- Input Nama Agenda -->
                <div class="space-y-1.5">
                    <label for="namaAgenda" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Nama Agenda <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_agenda" id="namaAgenda" maxlength="100" required
                        value="{{ old('nama_agenda') }}" placeholder="Contoh: Pendaftaran Online Gelombang 1"
                        class="w-full px-4 py-2.5 bg-slate-50/80 text-slate-800 text-sm font-medium rounded-xl border border-slate-200 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-100 focus:outline-none transition shadow-xs placeholder:text-slate-400">
                </div>

                <!-- Input Rentang Tanggal (Grid 2 Kolom) -->
                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1.5">
                        <label for="tanggalMulai" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Tanggal Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_mulai" id="tanggalMulai" required value="{{ old('tanggal_mulai') }}"
                            class="w-full px-3 py-2 bg-slate-50/80 text-slate-800 text-xs md:text-sm font-medium rounded-xl border border-slate-200 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-100 focus:outline-none transition shadow-xs">
                    </div>

                    <div class="space-y-1.5">
                        <label for="tanggalSelesai" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Tanggal Selesai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_selesai" id="tanggalSelesai" required value="{{ old('tanggal_selesai') }}"
                            class="w-full px-3 py-2 bg-slate-50/80 text-slate-800 text-xs md:text-sm font-medium rounded-xl border border-slate-200 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-100 focus:outline-none transition shadow-xs">
                    </div>
                </div>

                <!-- Input Keterangan Agenda -->
                <div class="space-y-1.5">
                    <label for="keteranganAgenda" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Keterangan Tambahan
                    </label>
                    <input type="text" name="keterangan" id="keteranganAgenda" maxlength="150"
                        value="{{ old('keterangan') }}" placeholder="Contoh: Dilakukan secara daring melalui portal resmi"
                        class="w-full px-4 py-2.5 bg-slate-50/80 text-slate-800 text-sm font-medium rounded-xl border border-slate-200 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-100 focus:outline-none transition shadow-xs placeholder:text-slate-400">
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center gap-2.5 pt-2">
                    <button type="submit"
                        class="flex-1 py-2.5 px-4 rounded-xl bg-[#0073c6] hover:bg-[#005fa4] active:scale-[0.98] text-white font-bold text-xs md:text-sm shadow-md shadow-blue-500/20 transition flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-floppy-disk text-xs"></i>
                        <span id="simpanLabel">Simpan Agenda</span>
                    </button>

                    <button type="button" id="btnClear" onclick="resetForm()"
                        class="px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs md:text-sm font-semibold transition cursor-pointer">
                        Reset
                    </button>

                    <button type="button" id="btnHapus" onclick="hapusAgenda()"
                        class="hidden px-4 py-2.5 rounded-xl bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 text-xs md:text-sm font-semibold transition cursor-pointer">
                        <i class="fa-solid fa-trash text-xs"></i>
                    </button>
                </div>
            </form>

        </div>
    </section>

    <!-- SECTION 3: PERSYARATAN & DOKUMEN PANDUAN -->
    <section id="section-persyaratan" class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-brand-600 flex items-center justify-center text-base shrink-0">
                    <i class="fa-solid fa-list-check"></i>
                </div>
                <div>
                    <h2 class="text-sm md:text-base font-extrabold text-slate-800 uppercase tracking-wide">
                        Persyaratan Masuk &amp; Berkas Pendaftaran
                    </h2>
                    <p class="text-xs text-slate-500">
                        Daftar kriteria dokumen yang wajib disiapkan serta file panduan/brosur PPDB untuk diunduh calon siswa.
                    </p>
                </div>
            </div>

            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-brand-700 self-start sm:self-auto">
                <span>{{ $persyaratan->count() }} Kriteria</span>
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- KIRI: DAFTAR PERSYARATAN (7 Kolom) -->
            <div class="lg:col-span-7 space-y-4">
                <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                    Daftar Butir Persyaratan
                </h3>

                <!-- List Persyaratan -->
                <div class="space-y-2.5 max-h-[350px] overflow-y-auto pr-1">
                    @forelse ($persyaratan as $index => $syarat)
                        <div class="flex items-center gap-2.5 p-2 rounded-xl border border-slate-200/80 bg-slate-50/60 hover:bg-slate-50 transition">
                            <span class="w-6 h-6 rounded-lg bg-blue-100 text-brand-700 text-xs font-bold flex items-center justify-center shrink-0">
                                {{ $index + 1 }}
                            </span>
                            <div class="flex-1 text-xs md:text-sm font-medium text-slate-800 px-2 select-text">
                                {{ $syarat->syarat }}
                            </div>
                            <a href="{{ route('delete.persyaratan.ppdb', $syarat->id) }}"
                                onclick="return confirm('Hapus butir persyaratan ini?')"
                                title="Hapus syarat"
                                class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-500 text-red-600 hover:text-white flex items-center justify-center transition cursor-pointer shrink-0">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </a>
                        </div>
                    @empty
                        <div class="p-6 rounded-xl border border-dashed border-slate-200 text-center text-slate-400 text-xs">
                            <i class="fa-solid fa-inbox text-2xl mb-2 text-slate-300"></i>
                            <p>Belum ada butir persyaratan yang ditambahkan.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Form Tambah Persyaratan Baru -->
                <form action="{{ route('post.persyaratan.ppdb') }}" method="POST" class="pt-2">
                    @csrf
                    <div class="flex gap-2">
                        <input type="text" name="syarat" required
                            placeholder="Ketik butir persyaratan baru..."
                            class="flex-1 px-4 py-2.5 bg-slate-50/80 text-slate-800 text-xs md:text-sm font-medium rounded-xl border border-slate-200 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-100 focus:outline-none transition shadow-xs placeholder:text-slate-400">
                        <button type="submit"
                            class="px-4 py-2.5 bg-brand-600 hover:bg-brand-700 active:scale-95 text-white font-bold text-xs md:text-sm rounded-xl transition flex items-center gap-1.5 shrink-0 cursor-pointer shadow-sm">
                            <i class="fa-solid fa-plus text-xs"></i>
                            <span>Tambah</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- KANAN: FILE BROSUR / PANDUAN PERSYARATAN (5 Kolom) -->
            <div class="lg:col-span-5 bg-slate-50/70 rounded-2xl p-5 border border-slate-200/80 space-y-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs shrink-0">
                        <i class="fa-solid fa-file-arrow-up"></i>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wide">File Panduan Persyaratan</h3>
                        <span class="text-[10px] text-slate-500">File PDF/dokumen unduhan publik</span>
                    </div>
                </div>

                @if ($informasi && $informasi->path_file)
                    <div class="p-3.5 rounded-xl bg-white border border-emerald-200 flex items-center justify-between gap-3 shadow-xs">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <i class="fa-solid fa-file-pdf text-red-500 text-lg shrink-0"></i>
                            <div class="min-w-0">
                                <a href="{{ asset('storage/' . $informasi->path_file) }}" target="_blank"
                                    class="text-xs font-bold text-brand-700 hover:underline truncate block max-w-[200px]"
                                    title="Lihat file saat ini">
                                    {{ basename($informasi->path_file) }}
                                </a>
                                <span class="text-[10px] text-emerald-600 font-semibold flex items-center gap-1">
                                    <i class="fa-solid fa-circle-check text-[8px]"></i> File aktif siap diunduh
                                </span>
                            </div>
                        </div>

                        <!-- Tombol Hapus File -->
                        <form action="{{ route('delete.persyaratan.file.ppdb') }}" method="POST" class="shrink-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-7 h-7 flex items-center justify-center rounded-lg bg-red-50 hover:bg-red-500 text-red-600 hover:text-white transition cursor-pointer"
                                title="Hapus file persyaratan"
                                onclick="return confirm('Yakin ingin menghapus file panduan persyaratan ini?')">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                        </form>
                    </div>
                @else
                    <div class="p-3 rounded-xl bg-white border border-dashed border-slate-300 text-slate-400 text-center text-xs">
                        Belum ada file panduan yang diunggah.
                    </div>
                @endif

                <!-- Form Upload File Persyaratan -->
                <form action="{{ route('upload.persyaratan.file.ppdb') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <div class="space-y-1">
                        <label for="path_file" class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider">
                            {{ $informasi && $informasi->path_file ? 'Ganti File Panduan' : 'Unggah File Panduan' }}
                        </label>
                        <input type="file" name="path_file" id="path_file" required
                            class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 transition cursor-pointer bg-white p-1 rounded-xl border border-slate-200">
                    </div>

                    <button type="submit"
                        class="w-full py-2 px-4 rounded-xl bg-brand-600 hover:bg-brand-700 active:scale-[0.98] text-white font-bold text-xs shadow-sm transition flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-upload text-xs"></i>
                        <span>Upload File Panduan</span>
                    </button>
                </form>
            </div>

        </div>
    </section>

    <!-- SECTION 4: DAYA TAMPUNG, JALUR SELEKSI & GALERI GAMBAR JURUSAN -->
    <section class="space-y-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-brand-600 flex items-center justify-center text-base shrink-0">
                <i class="fa-solid fa-school"></i>
            </div>
            <div>
                <h2 class="text-sm md:text-base font-extrabold text-slate-800 uppercase tracking-wide">
                    Jurusan, Kuota &amp; Jalur Seleksi
                </h2>
                <p class="text-xs text-slate-500">
                    Atur daya tampung masing-masing kompetensi keahlian, pembagian kuota jalur, serta visual galeri jurusan.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- SISI KIRI: DAYA TAMPUNG & JALUR SELEKSI (5 Kolom) -->
            <div class="lg:col-span-5 space-y-6">

                <!-- 1. DAYA TAMPUNG JURUSAN -->
                <div id="section-jurusan" class="bg-white rounded-2xl p-5 md:p-6 figma-card-shadow border border-slate-100 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 text-brand-600 flex items-center justify-center text-xs shrink-0">
                                <i class="fa-solid fa-users-line"></i>
                            </div>
                            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wide">Daya Tampung Jurusan</h3>
                        </div>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-extrabold bg-blue-50 text-brand-700">
                            Total: {{ $totalDayaTampung ?? 0 }}
                        </span>
                    </div>

                    <!-- List Jurusan -->
                    <div class="space-y-2 max-h-[220px] overflow-y-auto pr-1">
                        @forelse ($jurusans as $jurusan)
                            <div class="flex items-center gap-2 p-1.5 bg-slate-50/70 rounded-xl border border-slate-200/80">
                                <form action="{{ route('update.jurusan.ppdb', $jurusan) }}" method="POST"
                                    class="flex flex-1 gap-2 items-center">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="nama_jurusan" value="{{ $jurusan->nama_jurusan }}" required
                                        class="flex-1 px-3 py-1.5 bg-white text-slate-800 text-xs font-semibold rounded-lg border border-slate-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-200 focus:outline-none transition shadow-2xs"
                                        placeholder="Nama Jurusan">
                                    <input type="number" name="daya_tampung" value="{{ $jurusan->daya_tampung }}" required min="0"
                                        class="w-16 px-2 py-1.5 bg-white text-slate-800 text-xs font-bold rounded-lg border border-slate-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-200 focus:outline-none transition text-center shadow-2xs"
                                        placeholder="0">
                                    <button type="submit" title="Simpan perubahan jurusan"
                                        class="w-7 h-7 flex items-center justify-center rounded-lg bg-blue-50 hover:bg-brand-600 text-brand-600 hover:text-white transition cursor-pointer">
                                        <i class="fa-solid fa-floppy-disk text-xs"></i>
                                    </button>
                                </form>
                                <form action="{{ route('delete.jurusan.ppdb', $jurusan) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Hapus jurusan"
                                        onclick="return confirm('Yakin ingin menghapus jurusan ini?')"
                                        class="w-7 h-7 flex items-center justify-center rounded-lg bg-red-50 hover:bg-red-500 text-red-500 hover:text-white transition cursor-pointer">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        @empty
                            <div class="p-3 text-center text-slate-400 text-xs">Belum ada jurusan.</div>
                        @endforelse
                    </div>

                    <!-- Form Tambah Jurusan Baru -->
                    <form id="form-jurusan-tambah" action="{{ route('post.jurusan.ppdb') }}" method="POST"
                        class="pt-2 border-t border-slate-100 flex gap-2 items-center">
                        @csrf
                        <input type="text" name="nama_jurusan" required
                            class="flex-1 px-3 py-2 bg-slate-50 text-slate-800 text-xs font-medium rounded-xl border border-slate-200 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-100 focus:outline-none transition placeholder:text-slate-400"
                            placeholder="Nama Jurusan Baru">
                        <input type="number" name="daya_tampung" required min="0"
                            class="w-16 px-2 py-2 bg-slate-50 text-slate-800 text-xs font-medium rounded-xl border border-slate-200 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-100 focus:outline-none transition text-center placeholder:text-slate-400"
                            placeholder="Kuota">
                        <button type="submit" title="Tambah Jurusan"
                            class="px-3 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-xl transition flex items-center gap-1 text-xs font-bold shrink-0 cursor-pointer shadow-sm">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </form>
                </div>

                <!-- 2. JALUR SELEKSI -->
                <div id="section-jalur" class="bg-white rounded-2xl p-5 md:p-6 figma-card-shadow border border-slate-100 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center text-xs shrink-0">
                                <i class="fa-solid fa-percent"></i>
                            </div>
                            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wide">Jalur Seleksi &amp; Kuota</h3>
                        </div>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-extrabold {{ ($totalPercentase ?? 0) == 100 ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                            Total: {{ $totalPercentase ?? 0 }}%
                        </span>
                    </div>

                    <!-- List Jalur Seleksi -->
                    <div class="space-y-2 max-h-[220px] overflow-y-auto pr-1">
                        @forelse ($jalurs as $jalur)
                            <div class="flex items-center gap-2 p-1.5 bg-slate-50/70 rounded-xl border border-slate-200/80">
                                <form action="{{ route('update.jalur.ppdb', $jalur) }}" method="POST"
                                    class="flex flex-1 gap-2 items-center">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="nama_jalur" value="{{ $jalur->nama_jalur }}" required
                                        class="flex-1 px-3 py-1.5 bg-white text-slate-800 text-xs font-semibold rounded-lg border border-slate-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-200 focus:outline-none transition shadow-2xs"
                                        placeholder="Nama Jalur">
                                    <div class="flex items-center gap-1 bg-white px-2 py-1.5 rounded-lg border border-slate-200 shadow-2xs">
                                        <input type="number" step="0.01" name="percentase"
                                            value="{{ $jalur->percentase }}" min="0" max="100" required
                                            class="w-12 text-slate-800 text-xs font-bold focus:outline-none text-center"
                                            placeholder="0">
                                        <span class="text-xs text-slate-400 font-bold">%</span>
                                    </div>
                                    <button type="submit" title="Simpan perubahan jalur"
                                        class="w-7 h-7 flex items-center justify-center rounded-lg bg-blue-50 hover:bg-brand-600 text-brand-600 hover:text-white transition cursor-pointer">
                                        <i class="fa-solid fa-floppy-disk text-xs"></i>
                                    </button>
                                </form>
                                <form action="{{ route('delete.jalur.ppdb', $jalur) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Hapus jalur"
                                        onclick="return confirm('Yakin ingin menghapus jalur ini?')"
                                        class="w-7 h-7 flex items-center justify-center rounded-lg bg-red-50 hover:bg-red-500 text-red-500 hover:text-white transition cursor-pointer">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        @empty
                            <div class="p-3 text-center text-slate-400 text-xs">Belum ada jalur seleksi.</div>
                        @endforelse
                    </div>

                    <!-- Form Tambah Jalur Baru -->
                    <form id="form-jalur-tambah" action="{{ route('post.jalur.ppdb') }}" method="POST"
                        class="pt-2 border-t border-slate-100 flex gap-2 items-center">
                        @csrf
                        <input type="text" name="nama_jalur" required value="{{ old('nama_jalur') }}"
                            class="flex-1 px-3 py-2 bg-slate-50 text-slate-800 text-xs font-medium rounded-xl border border-slate-200 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-100 focus:outline-none transition placeholder:text-slate-400"
                            placeholder="Nama Jalur Baru">
                        <div class="flex items-center gap-1 bg-slate-50 px-2.5 py-2 rounded-xl border border-slate-200">
                            <input type="number" step="0.01" name="percentase" required min="0" max="100"
                                value="{{ old('percentase') }}"
                                class="w-12 bg-transparent text-slate-800 text-xs font-bold focus:outline-none text-center"
                                placeholder="0">
                            <span class="text-xs text-slate-400 font-bold">%</span>
                        </div>
                        <button type="submit" title="Tambah Jalur"
                            @if (($totalPercentase ?? 0) >= 100) disabled @endif
                            class="px-3 py-2 bg-brand-600 hover:bg-brand-700 active:scale-95 text-white rounded-xl transition flex items-center gap-1 text-xs font-bold shrink-0 cursor-pointer shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </form>
                </div>

            </div>

            <!-- SISI KANAN: DOKUMENTASI GAMBAR JURUSAN 4 SLOT (7 Kolom) -->
            <div id="section-gambar-jurusan" class="lg:col-span-7 bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-700 flex items-center justify-center text-xs shrink-0">
                            <i class="fa-solid fa-images"></i>
                        </div>
                        <div>
                            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wide">Galeri Visual Jurusan</h3>
                            <span class="text-[10px] text-slate-400">4 Slot foto keunggulan kompetensi keahlian</span>
                        </div>
                    </div>
                    <button type="button" onclick="openImageModal(0)"
                        class="px-3 py-1.5 rounded-lg bg-brand-50 hover:bg-brand-100 text-brand-700 text-xs font-bold transition flex items-center gap-1 cursor-pointer">
                        <i class="fa-solid fa-camera text-[10px]"></i>
                        <span>Upload Foto</span>
                    </button>
                </div>

                @php
                    $slot1 = $jurusans->get(0);
                    $slot2 = $jurusans->get(1);
                    $slot3 = $jurusans->get(2);
                    $slot4 = $jurusans->get(3);
                @endphp

                <!-- Grid 4 Slot Gambar Visual -->
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 h-[420px]">

                    <!-- Baris 1: Slot 1 (70% = 8 col) & Slot 2 (30% = 4 col) -->
                    <!-- Slot 1 -->
                    <div class="sm:col-span-8 h-[200px] relative group cursor-pointer border-2 border-dashed border-slate-200 rounded-2xl overflow-hidden hover:border-brand-500 bg-slate-50 transition-all shadow-inner"
                        onclick="openImageModal(0)">
                        @if ($slot1 && $slot1->img)
                            <img src="{{ asset('storage/' . $slot1->img) }}" alt="{{ $slot1->nama_jurusan }}"
                                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 group-hover:text-brand-600 transition-colors p-4">
                                <i class="fa-solid fa-image text-2xl mb-1.5 text-slate-300 group-hover:text-brand-600"></i>
                                <span class="text-xs font-bold text-slate-700">Slot 1: {{ $slot1?->nama_jurusan ?? 'Pilih Jurusan' }}</span>
                                <span class="text-[10px] text-slate-400 mt-0.5">Klik untuk pasang gambar</span>
                            </div>
                        @endif

                        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-[1px] flex flex-col items-center justify-center text-white opacity-0 group-hover:opacity-100 transition">
                            <i class="fa-solid fa-camera text-xl mb-1"></i>
                            <span class="text-xs font-bold">Ganti Gambar</span>
                            <span class="text-[10px] opacity-80">{{ $slot1?->nama_jurusan ?? 'Slot 1' }}</span>
                        </div>

                        @if ($slot1 && $slot1->img)
                            <button type="button"
                                onclick="event.stopPropagation(); if(confirm('Hapus gambar {{ $slot1->nama_jurusan }}?')) { document.getElementById('delete-img-{{ $slot1->id }}').submit(); }"
                                title="Hapus foto"
                                class="absolute top-2 right-2 z-10 bg-white/90 hover:bg-red-500 text-slate-600 hover:text-white rounded-full w-7 h-7 flex items-center justify-center shadow-md opacity-0 group-hover:opacity-100 transition cursor-pointer">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                            <form id="delete-img-{{ $slot1->id }}" action="{{ route('delete.jurusan.image.ppdb', $slot1) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endif
                    </div>

                    <!-- Slot 2 -->
                    <div class="sm:col-span-4 h-[200px] relative group cursor-pointer border-2 border-dashed border-slate-200 rounded-2xl overflow-hidden hover:border-brand-500 bg-slate-50 transition-all shadow-inner"
                        onclick="openImageModal(1)">
                        @if ($slot2 && $slot2->img)
                            <img src="{{ asset('storage/' . $slot2->img) }}" alt="{{ $slot2->nama_jurusan }}"
                                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 group-hover:text-brand-600 transition-colors p-4">
                                <i class="fa-solid fa-image text-2xl mb-1.5 text-slate-300 group-hover:text-brand-600"></i>
                                <span class="text-xs font-bold text-slate-700 text-center">Slot 2: {{ $slot2?->nama_jurusan ?? 'Pilih Jurusan' }}</span>
                                <span class="text-[10px] text-slate-400 mt-0.5">Klik untuk pasang</span>
                            </div>
                        @endif

                        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-[1px] flex flex-col items-center justify-center text-white opacity-0 group-hover:opacity-100 transition text-center px-2">
                            <i class="fa-solid fa-camera text-xl mb-1"></i>
                            <span class="text-xs font-bold">Ganti Gambar</span>
                            <span class="text-[10px] opacity-80">{{ $slot2?->nama_jurusan ?? 'Slot 2' }}</span>
                        </div>

                        @if ($slot2 && $slot2->img)
                            <button type="button"
                                onclick="event.stopPropagation(); if(confirm('Hapus gambar {{ $slot2->nama_jurusan }}?')) { document.getElementById('delete-img-{{ $slot2->id }}').submit(); }"
                                title="Hapus foto"
                                class="absolute top-2 right-2 z-10 bg-white/90 hover:bg-red-500 text-slate-600 hover:text-white rounded-full w-7 h-7 flex items-center justify-center shadow-md opacity-0 group-hover:opacity-100 transition cursor-pointer">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                            <form id="delete-img-{{ $slot2->id }}" action="{{ route('delete.jurusan.image.ppdb', $slot2) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endif
                    </div>

                    <!-- Baris 2: Slot 3 (30% = 4 col) & Slot 4 (70% = 8 col) -->
                    <!-- Slot 3 -->
                    <div class="sm:col-span-4 h-[200px] relative group cursor-pointer border-2 border-dashed border-slate-200 rounded-2xl overflow-hidden hover:border-brand-500 bg-slate-50 transition-all shadow-inner"
                        onclick="openImageModal(2)">
                        @if ($slot3 && $slot3->img)
                            <img src="{{ asset('storage/' . $slot3->img) }}" alt="{{ $slot3->nama_jurusan }}"
                                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 group-hover:text-brand-600 transition-colors p-4">
                                <i class="fa-solid fa-image text-2xl mb-1.5 text-slate-300 group-hover:text-brand-600"></i>
                                <span class="text-xs font-bold text-slate-700 text-center">Slot 3: {{ $slot3?->nama_jurusan ?? 'Pilih Jurusan' }}</span>
                                <span class="text-[10px] text-slate-400 mt-0.5">Klik untuk pasang</span>
                            </div>
                        @endif

                        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-[1px] flex flex-col items-center justify-center text-white opacity-0 group-hover:opacity-100 transition text-center px-2">
                            <i class="fa-solid fa-camera text-xl mb-1"></i>
                            <span class="text-xs font-bold">Ganti Gambar</span>
                            <span class="text-[10px] opacity-80">{{ $slot3?->nama_jurusan ?? 'Slot 3' }}</span>
                        </div>

                        @if ($slot3 && $slot3->img)
                            <button type="button"
                                onclick="event.stopPropagation(); if(confirm('Hapus gambar {{ $slot3->nama_jurusan }}?')) { document.getElementById('delete-img-{{ $slot3->id }}').submit(); }"
                                title="Hapus foto"
                                class="absolute top-2 right-2 z-10 bg-white/90 hover:bg-red-500 text-slate-600 hover:text-white rounded-full w-7 h-7 flex items-center justify-center shadow-md opacity-0 group-hover:opacity-100 transition cursor-pointer">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                            <form id="delete-img-{{ $slot3->id }}" action="{{ route('delete.jurusan.image.ppdb', $slot3) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endif
                    </div>

                    <!-- Slot 4 -->
                    <div class="sm:col-span-8 h-[200px] relative group cursor-pointer border-2 border-dashed border-slate-200 rounded-2xl overflow-hidden hover:border-brand-500 bg-slate-50 transition-all shadow-inner"
                        onclick="openImageModal(3)">
                        @if ($slot4 && $slot4->img)
                            <img src="{{ asset('storage/' . $slot4->img) }}" alt="{{ $slot4->nama_jurusan }}"
                                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 group-hover:text-brand-600 transition-colors p-4">
                                <i class="fa-solid fa-image text-2xl mb-1.5 text-slate-300 group-hover:text-brand-600"></i>
                                <span class="text-xs font-bold text-slate-700">Slot 4: {{ $slot4?->nama_jurusan ?? 'Pilih Jurusan' }}</span>
                                <span class="text-[10px] text-slate-400 mt-0.5">Klik untuk pasang gambar</span>
                            </div>
                        @endif

                        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-[1px] flex flex-col items-center justify-center text-white opacity-0 group-hover:opacity-100 transition">
                            <i class="fa-solid fa-camera text-xl mb-1"></i>
                            <span class="text-xs font-bold">Ganti Gambar</span>
                            <span class="text-[10px] opacity-80">{{ $slot4?->nama_jurusan ?? 'Slot 4' }}</span>
                        </div>

                        @if ($slot4 && $slot4->img)
                            <button type="button"
                                onclick="event.stopPropagation(); if(confirm('Hapus gambar {{ $slot4->nama_jurusan }}?')) { document.getElementById('delete-img-{{ $slot4->id }}').submit(); }"
                                title="Hapus foto"
                                class="absolute top-2 right-2 z-10 bg-white/90 hover:bg-red-500 text-slate-600 hover:text-white rounded-full w-7 h-7 flex items-center justify-center shadow-md opacity-0 group-hover:opacity-100 transition cursor-pointer">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                            <form id="delete-img-{{ $slot4->id }}" action="{{ route('delete.jurusan.image.ppdb', $slot4) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </section>

    <!-- SECTION 5: PUBLIKASI HASIL SELEKSI -->
    <section id="section-hasil-seleksi" class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100 space-y-5">
        <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-brand-600 flex items-center justify-center text-base shrink-0">
                <i class="fa-solid fa-bullhorn"></i>
            </div>
            <div>
                <h2 class="text-sm md:text-base font-extrabold text-slate-800 uppercase tracking-wide">
                    Pengumuman &amp; Dokumen Hasil Seleksi
                </h2>
                <p class="text-xs text-slate-500">
                    Unggah surat keputusan atau daftar kelulusan peserta didik baru (format PDF/DOC).
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
            <div class="lg:col-span-6 space-y-3">
                <div class="text-xs font-bold text-slate-700 uppercase tracking-wider">Status Dokumen Kelulusan</div>

                @if ($informasi && $informasi->path_file_hasil)
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between gap-3 shadow-xs">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center text-lg shrink-0">
                                <i class="fa-solid fa-file-pdf"></i>
                            </div>
                            <div class="min-w-0">
                                <a href="{{ asset('storage/' . $informasi->path_file_hasil) }}" target="_blank"
                                    class="text-xs font-bold text-brand-700 hover:underline truncate block"
                                    title="Lihat dokumen saat ini">
                                    {{ basename($informasi->path_file_hasil) }}
                                </a>
                                <span class="text-[10px] text-emerald-600 font-semibold flex items-center gap-1 mt-0.5">
                                    <i class="fa-solid fa-circle-check text-[8px]"></i> Dokumen hasil seleksi terpublikasi
                                </span>
                            </div>
                        </div>

                        <form action="{{ route('delete.hasil-seleksi.file.ppdb') }}" method="POST" class="shrink-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-3 py-1.5 rounded-lg bg-red-50 hover:bg-red-500 text-red-600 hover:text-white text-xs font-bold transition flex items-center gap-1.5 cursor-pointer"
                                onclick="return confirm('Yakin ingin menghapus file hasil seleksi ini?')">
                                <i class="fa-solid fa-trash text-[10px]"></i>
                                <span>Hapus</span>
                            </button>
                        </form>
                    </div>
                @else
                    <div class="p-4 rounded-2xl bg-slate-50 border border-dashed border-slate-300 text-center text-xs text-slate-400">
                        <i class="fa-solid fa-file-circle-xmark text-2xl text-slate-300 mb-1.5 block"></i>
                        <span>Belum ada dokumen hasil seleksi yang diunggah. Calon siswa akan melihat status pengumuman belum tersedia.</span>
                    </div>
                @endif
            </div>

            <!-- Form Upload Hasil Seleksi -->
            <div class="lg:col-span-6 bg-slate-50/70 p-5 rounded-2xl border border-slate-200/80">
                <form action="{{ route('upload.hasil-seleksi.file.ppdb') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <div class="space-y-1">
                        <label for="path_file_hasil" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            {{ $informasi && $informasi->path_file_hasil ? 'Ganti File Hasil Seleksi' : 'Unggah File Hasil Seleksi' }}
                        </label>
                        <input type="file" name="path_file_hasil" id="path_file_hasil" accept=".pdf,.doc,.docx" required
                            class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 transition cursor-pointer bg-white p-1 rounded-xl border border-slate-200">
                        <p class="text-[10px] text-slate-400">Format yang diterima: PDF, DOC, DOCX. Maksimal 2 MB.</p>
                    </div>

                    <button type="submit"
                        class="py-2.5 px-5 rounded-xl bg-[#0073c6] hover:bg-[#005fa4] active:scale-[0.98] text-white font-bold text-xs md:text-sm shadow-md shadow-blue-500/20 transition flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-cloud-arrow-up text-xs"></i>
                        <span>Upload Dokumen Hasil</span>
                    </button>
                </form>
            </div>
        </div>
    </section>

</div>

<!-- MODAL UPLOAD GAMBAR JURUSAN -->
<div id="modalImageJurusan" class="fixed inset-0 !m-0 z-[100] hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl border border-slate-100 overflow-hidden transform transition-all">
        <div class="p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-brand-600 flex items-center justify-center text-xs shrink-0">
                        <i class="fa-solid fa-camera"></i>
                    </div>
                    <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wide">Upload Foto Jurusan</h3>
                </div>
                <button type="button" onclick="closeImageModal()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition cursor-pointer">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <form id="formImageJurusan" action="" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="space-y-1.5">
                    <label for="jurusanSelect" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Pilih Jurusan <span class="text-red-500">*</span>
                    </label>
                    <select id="jurusanSelect" name="jurusan_id" required
                        class="w-full px-4 py-2.5 bg-slate-50 text-slate-800 text-xs md:text-sm font-medium rounded-xl border border-slate-200 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-100 focus:outline-none transition">
                        <option value="">-- Pilih Jurusan --</option>
                        @foreach ($jurusans as $jurusan)
                            <option value="{{ $jurusan->id }}"
                                data-route="{{ route('update.jurusan.image.ppdb', $jurusan) }}">
                                {{ $jurusan->nama_jurusan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label for="imgInput" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Pilih File Gambar <span class="text-red-500">*</span>
                    </label>
                    <input type="file" id="imgInput" name="img" accept="image/jpeg,image/jpg,image/png,image/webp" required
                        class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 transition cursor-pointer bg-slate-50 p-1 rounded-xl border border-slate-200">
                    <p class="text-[10px] text-slate-400">Format: JPG, JPEG, PNG, WEBP. Maksimal 2 MB.</p>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="button" onclick="closeImageModal()"
                        class="flex-1 py-2.5 px-4 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs md:text-sm font-semibold transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 py-2.5 px-4 rounded-xl bg-[#0073c6] hover:bg-[#005fa4] active:scale-[0.98] text-white text-xs md:text-sm font-bold shadow-md shadow-blue-500/20 transition flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-cloud-arrow-up text-xs"></i>
                        <span>Upload Foto</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        (function() {
            const AGENDAS = @json($agendas);
            const BULAN = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September',
                'Oktober', 'November', 'Desember'
            ];

            const FORM = document.getElementById('agendaForm');
            const METHOD_SLOT = document.getElementById('agendaMethod');
            const GRID = document.getElementById('calendarGrid');
            const TITLE = document.getElementById('calendarTitle');
            const TOOLTIP = document.getElementById('agendaTooltip');
            const TOOLTIP_CONTENT = TOOLTIP.querySelector('[data-tooltip-content]');
            const CAL_CARD = TOOLTIP.parentElement;
            const SHOW_DELAY = 150;
            const HIDE_DELAY = 400;
            const BANNER = document.getElementById('editBanner');
            const EDIT_NAME = document.getElementById('editName');
            const BTN_HAPUS = document.getElementById('btnHapus');
            const LABEL_SIMPAN = document.getElementById('simpanLabel');
            const INPUT_NAMA = document.getElementById('namaAgenda');
            const INPUT_MULAI = document.getElementById('tanggalMulai');
            const INPUT_SELESAI = document.getElementById('tanggalSelesai');
            const INPUT_KETERANGAN = document.getElementById('keteranganAgenda');

            const URL_POST = @json(route('post.tanggal-penting.ppdb'));
            const URL_UPDATE = @json(route('update.tanggal-penting.ppdb', ['agenda' => 0]));
            const URL_DELETE = @json(route('delete.tanggal-penting.ppdb', ['agenda' => 0]));

            const now = new Date();
            let viewYear = now.getFullYear();
            let viewMonth = now.getMonth();
            let start = '';
            let end = '';
            let editingId = null;
            let tooltipTimer = null;
            let hoverRanges = [];

            const pad = (n) => String(n).padStart(2, '0');

            function toKey(date) {
                return date.getFullYear() + '-' + pad(date.getMonth() + 1) + '-' + pad(date.getDate());
            }

            function parseKey(key) {
                const parts = key.split('-').map(Number);
                return new Date(parts[0], parts[1] - 1, parts[2]);
            }

            function labelTanggal(key) {
                if (!key) return '';
                const d = parseKey(key);
                return d.getDate() + ' ' + BULAN[d.getMonth()] + ' ' + d.getFullYear();
            }

            function rentangLabel(a, b) {
                if (!a || !b) return '';
                if (a === b) return labelTanggal(a);

                const mulai = parseKey(a);
                const selesai = parseKey(b);
                const samaTahun = mulai.getFullYear() === selesai.getFullYear();
                const samaBulan = samaTahun && mulai.getMonth() === selesai.getMonth();

                if (samaBulan) {
                    return mulai.getDate() + ' - ' + selesai.getDate() + ' ' + BULAN[selesai.getMonth()] +
                        ' ' + selesai.getFullYear();
                }

                if (samaTahun) {
                    return mulai.getDate() + ' ' + BULAN[mulai.getMonth()] + ' - ' + selesai.getDate() + ' ' +
                        BULAN[selesai.getMonth()] + ' ' + selesai.getFullYear();
                }

                return labelTanggal(a) + ' - ' + labelTanggal(b);
            }

            function agendaDiTanggal(key) {
                return AGENDAS.filter((a) => key >= a.tanggal_mulai && key <= a.tanggal_selesai);
            }

            function adaDraft(key) {
                if (!start || !end) return false;
                return key >= start && key <= end;
            }

            function selClass(key, hoverRangesArr = []) {
                const tersimpanList = agendaDiTanggal(key);
                const tersimpan = tersimpanList.length > 0;
                const draft = adaDraft(key);
                const inHover = hoverRangesArr.some(r => key >= r[0] && key <= r[1]);
                const kelas = [
                    'h-10 flex items-center justify-center rounded-xl text-xs font-bold transition-all cursor-pointer select-none'
                ];

                if (inHover) {
                    kelas.push('bg-[#0073c6] text-white shadow-md scale-105');
                } else if (tersimpan) {
                    kelas.push('bg-brand-600 text-white hover:bg-brand-700 shadow-xs');
                } else if (draft) {
                    kelas.push('bg-blue-100 text-brand-800 hover:bg-blue-200 border border-brand-300');
                } else {
                    kelas.push('text-slate-700 hover:bg-slate-100 hover:text-brand-600');
                }

                if (start && key === start) kelas.push('rounded-l-2xl');
                if (end && key === end) kelas.push('rounded-r-2xl');
                if (key === toKey(now)) kelas.push('ring-2 ring-brand-500 ring-offset-1');

                return kelas.join(' ');
            }

            function updateCellClasses() {
                GRID.querySelectorAll('[data-key]').forEach((sel) => {
                    const key = sel.dataset.key;
                    const luarBulan = sel.dataset.luar === '1';
                    sel.className = selClass(key, hoverRanges) + (luarBulan ? ' opacity-30 font-normal' : '');
                });
            }

            function renderCalendar() {
                TITLE.textContent = BULAN[viewMonth] + ' ' + viewYear;
                hoverRanges = [];
                TOOLTIP.classList.add('hidden');
                GRID.innerHTML = '';

                const pertama = new Date(viewYear, viewMonth, 1);
                let offset = pertama.getDay() - 1;
                if (offset < 0) offset = 6;

                const mulaiGrid = new Date(viewYear, viewMonth, 1 - offset);

                for (let i = 0; i < 42; i++) {
                    const d = new Date(mulaiGrid.getFullYear(), mulaiGrid.getMonth(), mulaiGrid.getDate() + i);
                    const key = toKey(d);
                    const luarBulan = d.getMonth() !== viewMonth;

                    const sel = document.createElement('div');
                    sel.textContent = d.getDate();
                    sel.dataset.key = key;
                    sel.dataset.luar = luarBulan ? '1' : '0';
                    sel.className = selClass(key, hoverRanges) + (luarBulan ? ' opacity-30 font-normal' : '');

                    sel.addEventListener('click', () => pilihTanggal(key));
                    sel.addEventListener('mouseenter', () => {
                        clearTimeout(tooltipTimer);
                        const list = agendaDiTanggal(key);
                        hoverRanges = list.map(a => [a.tanggal_mulai, a.tanggal_selesai]);
                        updateCellClasses();
                        tampilTooltip(sel, list, key);
                    });
                    sel.addEventListener('mouseleave', (e) => {
                        if (e.relatedTarget && TOOLTIP.contains(e.relatedTarget)) return;
                        sembunyiTooltip();
                    });

                    GRID.appendChild(sel);
                }
            }

            function tampilTooltip(anchor, list, key) {
                clearTimeout(tooltipTimer);
                tooltipTimer = setTimeout(() => {
                    let html = '';

                    if (list.length) {
                        html += list.map((a) => {
                            const judul = rentangLabel(a.tanggal_mulai, a.tanggal_selesai);
                            return '<div class="mb-2.5 pb-2.5 border-b border-white/10 last:border-b-0 last:mb-0 last:pb-0 flex items-start justify-between gap-2">' +
                                '<div class="min-w-0">' +
                                '<div class="text-blue-300 font-bold text-[11px] truncate">' + judul + '</div>' +
                                '<div class="font-extrabold text-white text-xs truncate">' + a.nama_agenda + '</div>' +
                                '<div class="text-slate-300 text-[11px] truncate mt-0.5">' + (a.keterangan || '-') + '</div>' +
                                '</div>' +
                                '<button type="button" class="shrink-0 p-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-white transition cursor-pointer" data-action="edit" data-id="' +
                                a.id + '" title="Edit agenda">' +
                                '<i class="fa-solid fa-pen text-[10px]"></i>' +
                                '</button>' +
                                '</div>';
                        }).join('');
                    }

                    html +=
                        '<div class="mt-2.5 pt-2 border-t border-white/15 flex items-center justify-center">' +
                        '<button type="button" class="flex items-center gap-1.5 text-blue-300 hover:text-white text-xs font-bold transition cursor-pointer" data-action="add" data-key="' +
                        key + '">' +
                        '<i class="fa-solid fa-plus text-[10px]"></i>' +
                        '<span>Tambah agenda di tanggal ini</span>' +
                        '</button>' +
                        '</div>';

                    TOOLTIP_CONTENT.innerHTML = html;
                    TOOLTIP.classList.remove('hidden');

                    const induk = TOOLTIP.parentElement;
                    const lebarInduk = induk.clientWidth;
                    const lebarTip = TOOLTIP.offsetWidth;

                    let kiri = anchor.offsetLeft + anchor.offsetWidth / 2 - lebarTip / 2;
                    kiri = Math.max(8, Math.min(kiri, lebarInduk - lebarTip - 8));

                    let atas = anchor.offsetTop - TOOLTIP.offsetHeight - 8;
                    if (atas < 0) atas = anchor.offsetTop + anchor.offsetHeight + 8;

                    TOOLTIP.style.left = kiri + 'px';
                    TOOLTIP.style.top = atas + 'px';

                    TOOLTIP_CONTENT.querySelectorAll('[data-action="edit"]').forEach(btn => {
                        btn.addEventListener('click', (e) => {
                            e.stopPropagation();
                            const id = parseInt(btn.dataset.id);
                            const agenda = AGENDAS.find(a => a.id === id);
                            if (agenda) {
                                loadAgenda(agenda);
                                hoverRanges = [];
                                renderCalendar();
                                TOOLTIP.classList.add('hidden');
                            }
                        });
                    });

                    TOOLTIP_CONTENT.querySelectorAll('[data-action="add"]').forEach(btn => {
                        btn.addEventListener('click', (e) => {
                            e.stopPropagation();
                            const keyTanggal = btn.dataset.key;
                            resetForm();
                            if (keyTanggal) {
                                start = keyTanggal;
                                end = '';
                                INPUT_MULAI.value = keyTanggal;
                                INPUT_SELESAI.value = '';
                            }
                            hoverRanges = [];
                            renderCalendar();
                            TOOLTIP.classList.add('hidden');
                        });
                    });
                }, SHOW_DELAY);
            }

            function hideTooltipNow() {
                clearTimeout(tooltipTimer);
                tooltipTimer = null;
                TOOLTIP.classList.add('hidden');
                hoverRanges = [];
                updateCellClasses();
            }

            function sembunyiTooltip() {
                clearTimeout(tooltipTimer);
                tooltipTimer = setTimeout(hideTooltipNow, HIDE_DELAY);
            }

            function pilihTanggal(key) {
                const tersimpan = agendaDiTanggal(key);

                if (tersimpan.length) {
                    if (editingId !== null) {
                        loadAgenda(tersimpan[0]);
                        renderCalendar();
                        return;
                    }
                }

                if (editingId !== null) {
                    resetForm();
                }

                if (!start || end) {
                    start = key;
                    end = '';
                    INPUT_MULAI.value = key;
                    INPUT_SELESAI.value = '';
                } else {
                    if (key < start) {
                        end = start;
                        start = key;
                    } else {
                        end = key;
                    }
                    INPUT_SELESAI.value = end;
                    if (INPUT_MULAI.value !== start) INPUT_MULAI.value = start;
                }

                renderCalendar();
            }

            function loadAgenda(agenda) {
                editingId = agenda.id;
                start = agenda.tanggal_mulai;
                end = agenda.tanggal_selesai;

                INPUT_NAMA.value = agenda.nama_agenda;
                INPUT_MULAI.value = agenda.tanggal_mulai;
                INPUT_SELESAI.value = agenda.tanggal_selesai;
                INPUT_KETERANGAN.value = agenda.keterangan || '';

                FORM.action = URL_UPDATE.replace(/\/0$/, '/' + agenda.id);
                METHOD_SLOT.innerHTML = '<input type="hidden" name="_method" value="PUT">';
                LABEL_SIMPAN.textContent = 'Perbarui Agenda';
                EDIT_NAME.textContent = agenda.nama_agenda;
                BANNER.classList.remove('hidden');
                BTN_HAPUS.classList.remove('hidden');
            }

            function resetForm() {
                editingId = null;
                start = '';
                end = '';

                FORM.reset();
                FORM.action = URL_POST;
                METHOD_SLOT.innerHTML = '';
                LABEL_SIMPAN.textContent = 'Simpan Agenda';
                BANNER.classList.add('hidden');
                BTN_HAPUS.classList.add('hidden');
                TOOLTIP.classList.add('hidden');

                renderCalendar();
            }

            function hapusAgenda() {
                if (!editingId) return;
                if (!confirm('Hapus agenda ini?')) return;

                FORM.action = URL_DELETE.replace(/\/0$/, '/' + editingId);
                METHOD_SLOT.innerHTML = '<input type="hidden" name="_method" value="DELETE">';
                FORM.submit();
            }

            GRID.addEventListener('mouseleave', (e) => {
                if (e.relatedTarget && TOOLTIP.contains(e.relatedTarget)) return;
                sembunyiTooltip();
            });

            TOOLTIP.addEventListener('mouseenter', () => {
                clearTimeout(tooltipTimer);
            });

            TOOLTIP.addEventListener('mouseleave', (e) => {
                if (e.relatedTarget && (GRID.contains(e.relatedTarget) || CAL_CARD.contains(e.relatedTarget))) {
                    if (e.relatedTarget.closest && e.relatedTarget.closest('[data-key]')) return;
                }
                sembunyiTooltip();
            });

            CAL_CARD.addEventListener('mouseleave', (e) => {
                if (e.relatedTarget && TOOLTIP.contains(e.relatedTarget)) return;
                sembunyiTooltip();
            });

            document.getElementById('btnPrev').addEventListener('click', () => {
                viewMonth--;
                if (viewMonth < 0) {
                    viewMonth = 11;
                    viewYear--;
                }
                renderCalendar();
            });

            document.getElementById('btnNext').addEventListener('click', () => {
                viewMonth++;
                if (viewMonth > 11) {
                    viewMonth = 0;
                    viewYear++;
                }
                renderCalendar();
            });

            [INPUT_MULAI, INPUT_SELESAI].forEach((input) => {
                input.addEventListener('change', () => {
                    start = INPUT_MULAI.value;
                    end = INPUT_SELESAI.value;
                    if (start) {
                        const d = parseKey(start);
                        viewYear = d.getFullYear();
                        viewMonth = d.getMonth();
                    }
                    renderCalendar();
                });
            });

            INPUT_SELESAI.addEventListener('change', () => {
                if (start && end && end < start) {
                    INPUT_SELESAI.value = start;
                    end = start;
                }
            });

            if (start || end) {
                const acuan = parseKey(start || end);
                viewYear = acuan.getFullYear();
                viewMonth = acuan.getMonth();
            }

            window.resetForm = resetForm;
            window.hapusAgenda = hapusAgenda;

            renderCalendar();
        })();

        // MODAL UPLOAD FOTO JURUSAN HANDLERS
        function openImageModal(slotIndex = 0) {
            const modal = document.getElementById('modalImageJurusan');
            const select = document.getElementById('jurusanSelect');
            if (!modal) return;

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            if (select && select.options.length > slotIndex + 1) {
                select.selectedIndex = slotIndex + 1;
                select.dispatchEvent(new Event('change'));
            }
        }

        function closeImageModal() {
            const modal = document.getElementById('modalImageJurusan');
            const form = document.getElementById('formImageJurusan');
            const select = document.getElementById('jurusanSelect');
            if (!modal) return;

            modal.classList.add('hidden');
            modal.classList.remove('flex');
            if (form) form.reset();
            if (select) select.value = '';
            if (form) form.action = '';
        }

        const jurusanSelectEl = document.getElementById('jurusanSelect');
        if (jurusanSelectEl) {
            jurusanSelectEl.addEventListener('change', function() {
                const selected = this.options[this.selectedIndex];
                const route = selected ? selected.dataset.route : null;
                const form = document.getElementById('formImageJurusan');
                if (form) {
                    form.action = route || '';
                }
            });
        }

        const modalImageJurusanEl = document.getElementById('modalImageJurusan');
        if (modalImageJurusanEl) {
            modalImageJurusanEl.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeImageModal();
                }
            });
        }
    </script>
@endpush
