@extends('Admin.layout.app')

@section('title', 'Dashboard PPDB')

@section('content')
<div class="space-y-6">

    <!-- PAGE HEADER -->
    <div class="bg-white rounded-2xl p-5 md:p-6 figma-card-shadow border border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-brand-600 mb-1">
                <i class="fa-solid fa-graduation-cap"></i>
                <span class="uppercase tracking-wider">Modul PPDB Online</span>
            </div>
            <h1 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight">Dashboard PPDB</h1>
            <p class="text-xs md:text-sm text-slate-500 mt-1">
                Kelola banner sambutan dan informasi utama pendaftaran peserta didik baru SMKN 2 Karanganyar.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('ppdb') }}" target="_blank"
                class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs md:text-sm font-bold shadow-xs transition flex items-center gap-2">
                <i class="fa-solid fa-arrow-up-right-from-square text-xs text-slate-500"></i>
                <span>Lihat Halaman Publik</span>
            </a>
            <a href="{{ route('index.informasi.ppdb') }}"
                class="px-4 py-2.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-brand-700 text-xs md:text-sm font-bold transition flex items-center gap-2">
                <i class="fa-solid fa-file-lines text-xs"></i>
                <span>Informasi & Persyaratan</span>
            </a>
        </div>
    </div>


    <!-- FORM UTAMA -->
    <form id="formMasterPpdb" action="{{ route('update.master.ppdb') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Penanda penghapusan gambar oleh user (diklik tombol X) --}}
        <input type="hidden" name="hapus_gambar" id="hapusGambar" value="0">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- KOLOM KIRI: FORM SAMBUTAN & DESKRIPSI (7 Kolom) -->
            <div class="lg:col-span-7 space-y-6">

                <div class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100 space-y-5">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-brand-600 flex items-center justify-center text-base shrink-0">
                            <i class="fa-solid fa-bullhorn"></i>
                        </div>
                        <div>
                            <h2 class="text-sm md:text-base font-extrabold text-slate-800 uppercase tracking-wide">
                                Informasi Sambutan PPDB
                            </h2>
                            <p class="text-xs text-slate-500">
                                Judul dan deskripsi sambutan yang ditampilkan di bagian atas halaman informasi PPDB.
                            </p>
                        </div>
                    </div>

                    <!-- Input Judul -->
                    <div class="space-y-1.5">
                        <label for="judul" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Judul Utama <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="judul" id="judul" maxlength="100" required
                            value="{{ old('judul', $master?->judul) }}"
                            placeholder="Contoh: PPDB SMK Negeri 2 Karanganyar Tahun Ajaran 2026/2027"
                            class="w-full px-4 py-2.5 bg-slate-50/80 text-slate-800 text-sm font-medium rounded-xl border border-slate-200 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-100 focus:outline-none transition shadow-xs placeholder:text-slate-400">
                        <p class="text-[11px] text-slate-400">Maksimal 100 karakter.</p>
                    </div>

                    <!-- Input Deskripsi -->
                    <div class="space-y-1.5">
                        <label for="deskripsi" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Deskripsi Informasi <span class="text-red-500">*</span>
                        </label>
                        <textarea name="deskripsi" id="deskripsi" rows="7" required
                            placeholder="Tuliskan deskripsi lengkap atau panduan awal pendaftaran bagi calon peserta didik baru..."
                            class="w-full px-4 py-3 bg-slate-50/80 text-slate-800 text-sm font-medium rounded-xl border border-slate-200 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-100 focus:outline-none transition shadow-xs placeholder:text-slate-400 resize-y min-h-[140px]">{{ old('deskripsi', $master?->deskripsi) }}</textarea>
                        <p class="text-[11px] text-slate-400">Jelaskan jadwal ringkas, ketentuan umum, atau visi keunggulan sekolah.</p>
                    </div>
                </div>

                <!-- CARD PANDUAN PENGELOLAAN MODUL -->
                <div class="bg-gradient-to-br from-blue-50/70 to-indigo-50/40 rounded-2xl p-5 border border-blue-100/80 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-brand-600 text-white flex items-center justify-center shrink-0 shadow-sm mt-0.5">
                            <i class="fa-solid fa-list-check text-sm"></i>
                        </div>
                        <div>
                            <h3 class="text-xs font-extrabold uppercase tracking-wide text-brand-700">Lanjutkan Pengaturan PPDB</h3>
                            <p class="text-xs text-slate-600 mt-0.5">
                                Atur kalender tanggal penting, syarat pendaftaran, daya tampung jurusan, dan jalur seleksi.
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('index.informasi.ppdb') }}"
                        class="px-4 py-2 rounded-xl bg-white hover:bg-slate-50 text-brand-700 border border-blue-200 text-xs font-bold shadow-xs transition flex items-center gap-1.5 shrink-0">
                        <span>Kelola Detail</span>
                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </a>
                </div>

            </div>

            <!-- KOLOM KANAN: BANNER GAMBAR & AKSI SIMPAN (5 Kolom) -->
            <div class="lg:col-span-5 space-y-6">

                <!-- CARD BANNER -->
                <div class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 text-brand-600 flex items-center justify-center text-xs shrink-0">
                                <i class="fa-solid fa-image"></i>
                            </div>
                            <div>
                                <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wide">Banner Utama</h2>
                                <span class="text-[10px] text-slate-400">Rasio 16:9 disarankan</span>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <span id="badgeBannerStatus" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold {{ $master?->banner_img ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600' }}">
                            <i class="fa-solid {{ $master?->banner_img ? 'fa-check' : 'fa-circle-info' }} text-[9px]"></i>
                            <span>{{ $master?->banner_img ? 'Banner Terpasang' : 'Belum Ada' }}</span>
                        </span>
                    </div>

                    <!-- Area Upload & Preview -->
                    <div
                        class="w-full aspect-[16/9] relative rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50/70 overflow-hidden flex items-center justify-center group hover:border-brand-500 hover:bg-blue-50/20 transition-all cursor-pointer shadow-inner"
                        onclick="document.getElementById('gambar').click()">

                        <!-- Preview Gambar -->
                        <img id="imgPreview" src="{{ $bannerUrl ?? '' }}" alt="Preview Banner PPDB"
                            class="absolute inset-0 w-full h-full object-cover transition-transform duration-300 group-hover:scale-105 {{ $master?->banner_img ? '' : 'hidden' }}">

                        <!-- Overlay Saat Hover Preview -->
                        <div id="imgHoverOverlay" class="absolute inset-0 bg-slate-900/40 backdrop-blur-[2px] opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center text-white {{ $master?->banner_img ? '' : 'hidden' }}">
                            <i class="fa-solid fa-camera text-2xl mb-1 drop-shadow"></i>
                            <span class="text-xs font-bold">Ganti Gambar Banner</span>
                        </div>

                        <!-- Placeholder -->
                        <div id="imgPlaceholder"
                            class="flex flex-col items-center justify-center text-center px-4 text-slate-400 group-hover:text-brand-600 transition-colors {{ $master?->banner_img ? 'hidden' : '' }}">
                            <div class="w-12 h-12 rounded-full bg-slate-100 group-hover:bg-blue-100 flex items-center justify-center mb-2 transition-colors">
                                <i class="fa-solid fa-cloud-arrow-up text-xl text-slate-400 group-hover:text-brand-600 transition-colors"></i>
                            </div>
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-700">Pilih Gambar Banner</span>
                            <span class="text-[11px] text-slate-400 mt-1 max-w-[200px]">Klik di sini untuk menelusuri file dari perangkat</span>
                        </div>

                        <!-- Tombol Hapus (X) -->
                        <button type="button" id="btnHapusGambar" title="Hapus gambar banner"
                            onclick="event.stopPropagation(); resetBanner();"
                            class="absolute top-3 right-3 z-10 w-8 h-8 flex items-center justify-center rounded-full bg-white/90 hover:bg-red-500 text-slate-600 hover:text-white shadow-md transition-colors cursor-pointer {{ $master?->banner_img ? '' : 'hidden' }}">
                            <i class="fa-solid fa-xmark text-xs"></i>
                        </button>

                        <input type="file" name="banner_img" id="gambar" accept="image/jpeg,image/png,image/webp,image/jpg" class="hidden">
                    </div>

                    <div class="text-[11px] text-slate-400 flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-info text-[10px] text-slate-400"></i>
                        <span>Format yang didukung: JPG, PNG, WEBP. Ukuran maks. 2 MB.</span>
                    </div>
                </div>

                <!-- CARD AKSI SIMPAN -->
                <div class="bg-white rounded-2xl p-5 figma-card-shadow border border-slate-100 space-y-3">
                    <button type="submit"
                        class="w-full py-3 px-5 rounded-xl bg-[#0073c6] hover:bg-[#005fa4] active:scale-[0.98] text-white font-bold text-sm shadow-md shadow-blue-500/20 transition flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-floppy-disk text-xs"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                    <p class="text-center text-[11px] text-slate-400">
                        Perubahan akan langsung diperbarui di landing page PPDB.
                    </p>
                </div>

            </div>

        </div>
    </form>
</div>
@endsection

@push('scripts')
    <script>
        const bannerInput = document.getElementById('gambar');
        const bannerPreview = document.getElementById('imgPreview');
        const bannerPlaceholder = document.getElementById('imgPlaceholder');
        const bannerHoverOverlay = document.getElementById('imgHoverOverlay');
        const hapusGambar = document.getElementById('hapusGambar');
        const btnHapusGambar = document.getElementById('btnHapusGambar');
        const badgeBannerStatus = document.getElementById('badgeBannerStatus');
        let objectUrl = null;

        function showBanner(src) {
            bannerPreview.src = src;
            bannerPreview.classList.remove('hidden');
            if (bannerHoverOverlay) bannerHoverOverlay.classList.remove('hidden');
            bannerPlaceholder.classList.add('hidden');
            btnHapusGambar.classList.remove('hidden');

            if (badgeBannerStatus) {
                badgeBannerStatus.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200';
                badgeBannerStatus.innerHTML = '<i class="fa-solid fa-clock text-[9px]"></i><span>Siap Disimpan</span>';
            }
        }

        function clearBanner() {
            bannerInput.value = '';
            bannerPreview.removeAttribute('src');
            bannerPreview.classList.add('hidden');
            if (bannerHoverOverlay) bannerHoverOverlay.classList.add('hidden');
            bannerPlaceholder.classList.remove('hidden');
            btnHapusGambar.classList.add('hidden');

            if (badgeBannerStatus) {
                badgeBannerStatus.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600';
                badgeBannerStatus.innerHTML = '<i class="fa-solid fa-circle-info text-[9px]"></i><span>Belum Ada</span>';
            }

            if (objectUrl) {
                URL.revokeObjectURL(objectUrl);
                objectUrl = null;
            }
        }

        function resetBanner() {
            hapusGambar.value = '1';
            clearBanner();
        }

        if (bannerInput) {
            bannerInput.addEventListener('change', function() {
                hapusGambar.value = '0';

                if (!this.files || !this.files[0]) {
                    clearBanner();
                    return;
                }

                if (objectUrl) {
                    URL.revokeObjectURL(objectUrl);
                }

                objectUrl = URL.createObjectURL(this.files[0]);
                showBanner(objectUrl);
            });
        }
    </script>
@endpush
