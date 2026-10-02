@extends('Admin.layout.app')

@section('title', 'Konfigurasi Aula - Admin Aula')
@section('page_title', 'Konfigurasi Aula')

@section('content')
<div class="space-y-6">

    <!-- PAGE HEADER -->
    <div class="bg-white rounded-2xl p-5 md:p-6 figma-card-shadow border border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-brand-600 mb-1">
                <i class="fa-solid fa-hotel"></i>
                <span class="uppercase tracking-wider">Modul Peminjaman Aula</span>
            </div>
            <h1 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight">
                Konfigurasi Informasi &amp; Profil Aula
            </h1>
            <p class="text-xs md:text-sm text-slate-500 mt-1">
                Kelola nama gedung, judul promosi, deskripsi fasilitas aula, serta foto dokumentasi yang ditampilkan di halaman publik.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('layanan-peminjaman') }}" target="_blank"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-brand-50 text-brand-600 hover:bg-brand-100 text-xs md:text-sm font-semibold transition border border-brand-200">
                <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                <span>Lihat Halaman Publik</span>
            </a>
            <a href="{{ route('admin.peminjaman.dashboard') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs md:text-sm font-semibold transition">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Dashboard</span>
            </a>
        </div>
    </div>

    <!-- NOTIFIKASI SUCCESS / ERROR -->
    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4 flex items-center justify-between shadow-sm animate-fade-in">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-check"></i>
                </div>
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-900">Berhasil Disimpan</h4>
                    <span class="text-xs md:text-sm font-medium">{{ session('success') }}</span>
                </div>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-2xl p-4 space-y-2 shadow-sm">
            <div class="flex items-center gap-2 font-bold text-xs md:text-sm text-red-700">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span>Terdapat kesalahan input:</span>
            </div>
            <ul class="list-disc list-inside text-xs text-red-600 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- FORM KONFIGURASI AULA -->
    <form action="{{ route('admin.aula.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- KOLOM KIRI (SPAN 8): INFORMASI UTAMA & DESKRIPSI -->
            <div class="lg:col-span-8 space-y-6">

                <!-- CARD: DATA PROFIL AULA -->
                <div class="bg-white rounded-2xl p-5 md:p-6 figma-card-shadow border border-slate-100 space-y-5">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center">
                                <i class="fa-solid fa-building text-sm"></i>
                            </div>
                            <div>
                                <h2 class="font-bold text-slate-800 text-sm md:text-base">Informasi Pokok Aula</h2>
                                <p class="text-[11px] text-slate-500">Nama gedung dan kalimat judul promosi layanan peminjaman aula.</p>
                            </div>
                        </div>
                        <span class="text-[11px] font-semibold text-slate-400 bg-slate-50 px-2.5 py-1 rounded-md">Tabel: aulas</span>
                    </div>

                    <!-- NAMA AULA -->
                    <div>
                        <label for="nama" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Nama Gedung / Aula <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-landmark text-sm"></i>
                            </span>
                            <input type="text" name="nama" id="nama"
                                value="{{ old('nama', $aula->nama ?? 'Aula Utama SMKN 2 Karanganyar') }}" required
                                placeholder="Contoh: Graha Krida Utama SMK Negeri 2 Karanganyar"
                                class="w-full pl-10 pr-4 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-xs md:text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100 transition shadow-sm">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Nama resmi gedung yang akan dicantumkan di formulir peminjaman dan bukti sewa.</p>
                    </div>

                    <!-- JUDUL BANNER PROMOSI -->
                    <div>
                        <label for="judul" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Tagline / Judul Banner Layanan
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-bullhorn text-sm"></i>
                            </span>
                            <input type="text" name="judul" id="judul"
                                value="{{ old('judul', $aula->judul ?? 'Sewa Aula Eksklusif Karanganyar') }}"
                                placeholder="Contoh: Sewa Aula Eksklusif &amp; Modern Karanganyar"
                                class="w-full pl-10 pr-4 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-xs md:text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100 transition shadow-sm">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Ditampilkan sebagai headline utama pada Hero Section di halaman publik layanan peminjaman.</p>
                    </div>

                    <!-- DESKRIPSI AULA -->
                    <div>
                        <label for="deskripsi" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Deskripsi Profil &amp; Kapasitas Aula
                        </label>
                        <textarea name="deskripsi" id="deskripsi" rows="6"
                            placeholder="Tuliskan deskripsi lengkap mengenai gedung aula, luas bangunan, daya tampung orang, peruntukan acara (pernikahan, wisuda, pameran), aksesibilitas, dan keunggulan lainnya..."
                            class="w-full p-3.5 bg-slate-50/50 border border-slate-200 rounded-xl text-xs md:text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100 transition shadow-sm leading-relaxed resize-y">{{ old('deskripsi', $aula->deskripsi ?? 'Gedung serbaguna dengan kapasitas hingga 1.000 orang, dilengkapi AC sentral, panggung megah, akustik profesional, serta area parkir luas untuk berbagai kegiatan formal maupun resepsi.') }}</textarea>
                        <p class="text-[11px] text-slate-400 mt-1">Rangkuman informasi ini membantu calon penyewa memahami daya tampung dan kelayakan aula untuk acara mereka.</p>
                    </div>

                </div>

                <!-- CARD: SINKRONISASI & INTEGRASI -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50/50 rounded-2xl p-5 border border-blue-100/80 flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-sm mt-0.5">
                        <i class="fa-solid fa-circle-nodes text-base"></i>
                    </div>
                    <div class="space-y-1">
                        <h4 class="text-xs md:text-sm font-bold text-slate-800">Integrasi Otomatis Halaman Publik</h4>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Perubahan pada konfigurasi aula ini akan langsung terhubung dan diperbarui secara otomatis pada halaman
                            <a href="{{ route('layanan-peminjaman') }}" target="_blank" class="font-semibold text-brand-600 hover:underline">/layanan-peminjaman</a>,
                            pilihan aula bagi pelanggan saat membuat peminjaman, serta menjadi basis pengetahuan untuk respon chatbot asisten virtual AI.
                        </p>
                    </div>
                </div>

            </div>

            <!-- KOLOM KANAN (SPAN 4): MEDIA DOKUMENTASI & FOTO -->
            <div class="lg:col-span-4 space-y-6">

                <!-- CARD: DOKUMENTASI FOTO UTAMA -->
                <div class="bg-white rounded-2xl p-5 md:p-6 figma-card-shadow border border-slate-100 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-image text-brand-600 text-sm"></i>
                            <h3 class="font-bold text-slate-800 text-xs md:text-sm uppercase tracking-wider">Foto Utama Aula</h3>
                        </div>
                        <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">Banner</span>
                    </div>

                    <!-- PREVIEW GAMBAR UTAMA -->
                    <div class="relative rounded-xl overflow-hidden border border-slate-200 bg-slate-100 aspect-video group shadow-inner flex items-center justify-center">
                        <img id="preview-dokumentasi"
                            src="{{ $aula->foto_dokumentasi_url }}"
                            alt="Foto Utama Aula"
                            class="w-full h-full {{ $aula->has_custom_dokumentasi ? 'object-cover' : 'object-contain p-4' }} group-hover:scale-105 transition duration-300">

                        <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center pointer-events-none">
                            <span class="text-white text-xs font-semibold px-3 py-1.5 rounded-lg bg-black/50 backdrop-blur-sm">
                                <i class="fa-solid fa-camera mr-1.5"></i> Ganti Foto Utama
                            </span>
                        </div>
                    </div>

                    <!-- FILE INPUT UTAMA -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Unggah Foto Utama Baru</label>
                        <input type="file" name="dokumentasi" id="dokumentasi" accept="image/jpeg,image/png,image/jpg,image/webp"
                            onchange="previewImage(this, 'preview-dokumentasi')"
                            class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-600 hover:file:bg-brand-100 cursor-pointer border border-slate-200 rounded-xl bg-slate-50/50">
                        <p class="text-[10px] text-slate-400 mt-1">Format: JPG, PNG, WEBP. Maksimal 3MB. Disarankan rasio landscape 16:9.</p>
                    </div>
                </div>

                <!-- CARD: DOKUMENTASI FOTO PENDUKUNG -->
                <div class="bg-white rounded-2xl p-5 md:p-6 figma-card-shadow border border-slate-100 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-images text-indigo-600 text-sm"></i>
                            <h3 class="font-bold text-slate-800 text-xs md:text-sm uppercase tracking-wider">Foto Pendukung (Interior)</h3>
                        </div>
                        <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full">Sekunder</span>
                    </div>

                    <!-- PREVIEW GAMBAR PENDUKUNG -->
                    <div class="relative rounded-xl overflow-hidden border border-slate-200 bg-slate-100 aspect-video group shadow-inner flex items-center justify-center">
                        <img id="preview-dokumentasi-2"
                            src="{{ $aula->foto_dokumentasi_2_url }}"
                            alt="Foto Pendukung Aula"
                            class="w-full h-full {{ $aula->has_custom_dokumentasi_2 ? 'object-cover' : 'object-contain p-4' }} group-hover:scale-105 transition duration-300">

                        <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center pointer-events-none">
                            <span class="text-white text-xs font-semibold px-3 py-1.5 rounded-lg bg-black/50 backdrop-blur-sm">
                                <i class="fa-solid fa-camera mr-1.5"></i> Ganti Foto Pendukung
                            </span>
                        </div>
                    </div>

                        <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center pointer-events-none">
                            <span class="text-white text-xs font-semibold px-3 py-1.5 rounded-lg bg-black/50 backdrop-blur-sm">
                                <i class="fa-solid fa-camera mr-1.5"></i> Ganti Foto Pendukung
                            </span>
                        </div>
                    </div>

                    <!-- FILE INPUT PENDUKUNG -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Unggah Foto Pendukung Baru</label>
                        <input type="file" name="dokumentasi_2" id="dokumentasi_2" accept="image/jpeg,image/png,image/jpg,image/webp"
                            onchange="previewImage(this, 'preview-dokumentasi-2')"
                            class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 cursor-pointer border border-slate-200 rounded-xl bg-slate-50/50">
                        <p class="text-[10px] text-slate-400 mt-1">Format: JPG, PNG, WEBP. Maksimal 3MB. Disarankan foto sudut dalam / panggung.</p>
                    </div>
                </div>

                <!-- SUBMIT BUTTON CARD -->
                <div class="bg-white rounded-2xl p-5 figma-card-shadow border border-slate-100 space-y-3">
                    <button type="submit"
                        class="w-full py-3 px-5 rounded-xl bg-[#0073c6] hover:bg-[#005fa4] active:scale-[0.99] text-white font-bold text-sm shadow-md shadow-blue-500/20 transition flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-floppy-disk text-base"></i>
                        <span>Simpan Konfigurasi Aula</span>
                    </button>
                    <p class="text-[11px] text-slate-400 text-center">Pastikan data dan foto yang diunggah telah sesuai sebelum menyimpan.</p>
                </div>

            </div>

        </div>

    </form>

</div>

@push('scripts')
<script>
    function previewImage(input, previewId) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewElement = document.getElementById(previewId);
                if (previewElement) {
                    previewElement.src = e.target.result;
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection
