@extends('Admin.layout.app')

@section('title', 'Konfigurasi Peminjaman - Admin')
@section('page_title', 'Konfigurasi Peminjaman')

@section('content')
<div class="space-y-6">

    <!-- PAGE HEADER -->
    <div class="bg-white rounded-2xl p-5 md:p-6 figma-card-shadow border border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-brand-600 mb-1">
                <i class="fa-solid fa-shield-halved"></i>
                <span>SUPER ADMIN ACCESS</span>
            </div>
            <h1 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight">
                Konfigurasi Peminjaman &amp; Rekening Sekolah
            </h1>
            <p class="text-xs md:text-sm text-slate-500 mt-1">
                Kelola informasi rekening bank, QRIS, minimal booking, toleransi pembatalan, serta batas waktu pembayaran aula.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.peminjaman.dashboard') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs md:text-sm font-semibold transition">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Kembali ke Dashboard</span>
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
                <span class="text-xs md:text-sm font-medium">{{ session('success') }}</span>
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
                <span>Mohon periksa kesalahan input berikut:</span>
            </div>
            <ul class="list-disc list-inside text-xs text-red-600 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- FORM KONFIGURASI -->
    <form action="{{ route('admin.payment-configuration.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- KOLOM KIRI (SPAN 2): REKENING BANK & JATUH TEMPO -->
            <div class="lg:col-span-2 space-y-6">

                <!-- 1. REKENING UTAMA -->
                <div class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100 space-y-5">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center font-bold text-sm">
                                <i class="fa-solid fa-building-columns"></i>
                            </div>
                            <div>
                                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Rekening Bank Utama (Wajib)</h2>
                                <p class="text-xs text-slate-500">Rekening tujuan transfer utama yang diprioritaskan untuk pelanggan.</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-brand-50 text-brand-700 border border-brand-200">
                            Utama
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="bank_utama" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                                Nama Bank <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="bank_utama" id="bank_utama" required maxlength="100"
                                value="{{ old('bank_utama', $config->bank_utama) }}"
                                placeholder="Contoh: Bank Jateng"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition font-medium">
                        </div>

                        <div>
                            <label for="norek_utama" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                                Nomor Rekening <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="norek_utama" id="norek_utama" required maxlength="100"
                                value="{{ old('norek_utama', $config->norek_utama) }}"
                                placeholder="Contoh: 1023000012"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition font-mono font-medium">
                        </div>

                        <div>
                            <label for="atas_nama_utama" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                                Atas Nama <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="atas_nama_utama" id="atas_nama_utama" required maxlength="150"
                                value="{{ old('atas_nama_utama', $config->atas_nama_utama) }}"
                                placeholder="Contoh: SMKN 2 KARANGANYAR"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition font-medium">
                        </div>
                    </div>
                </div>

                <!-- 2. REKENING ALTERNATIF 1 & 2 -->
                <div class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100 space-y-6">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-sm">
                            <i class="fa-solid fa-money-bill-transfer"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Rekening Alternatif Sekolah (2 Rekening)</h2>
                            <p class="text-xs text-slate-500">Pilihan rekening cadangan bagi pelanggan yang ingin transfer antar bank.</p>
                        </div>
                    </div>

                    <!-- Alternatif 1 -->
                    <div class="space-y-3 p-4 bg-slate-50/70 border border-slate-100 rounded-xl">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-700 uppercase tracking-wide flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                Rekening Alternatif 1
                            </span>
                            <span class="text-[11px] text-slate-400">Opsional</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <label for="bank_alternatif_1" class="block text-[11px] font-semibold text-slate-600 mb-1">Nama Bank</label>
                                <input type="text" name="bank_alternatif_1" id="bank_alternatif_1" maxlength="100"
                                    value="{{ old('bank_alternatif_1', $config->bank_alternatif_1) }}"
                                    placeholder="Contoh: Bank BRI"
                                    class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition">
                            </div>
                            <div>
                                <label for="norek_alternatif_1" class="block text-[11px] font-semibold text-slate-600 mb-1">Nomor Rekening</label>
                                <input type="text" name="norek_alternatif_1" id="norek_alternatif_1" maxlength="100"
                                    value="{{ old('norek_alternatif_1', $config->norek_alternatif_1) }}"
                                    placeholder="Contoh: 012301000001503"
                                    class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition font-mono">
                            </div>
                            <div>
                                <label for="atas_nama_alternatif_1" class="block text-[11px] font-semibold text-slate-600 mb-1">Atas Nama</label>
                                <input type="text" name="atas_nama_alternatif_1" id="atas_nama_alternatif_1" maxlength="150"
                                    value="{{ old('atas_nama_alternatif_1', $config->atas_nama_alternatif_1) }}"
                                    placeholder="Contoh: SMK NEGERI 2 KARANGANYAR"
                                    class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition">
                            </div>
                        </div>
                    </div>

                    <!-- Alternatif 2 -->
                    <div class="space-y-3 p-4 bg-slate-50/70 border border-slate-100 rounded-xl">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-700 uppercase tracking-wide flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                Rekening Alternatif 2
                            </span>
                            <span class="text-[11px] text-slate-400">Opsional</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <label for="bank_alternatif_2" class="block text-[11px] font-semibold text-slate-600 mb-1">Nama Bank</label>
                                <input type="text" name="bank_alternatif_2" id="bank_alternatif_2" maxlength="100"
                                    value="{{ old('bank_alternatif_2', $config->bank_alternatif_2) }}"
                                    placeholder="Contoh: Bank BNI / Mandiri"
                                    class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition">
                            </div>
                            <div>
                                <label for="norek_alternatif_2" class="block text-[11px] font-semibold text-slate-600 mb-1">Nomor Rekening</label>
                                <input type="text" name="norek_alternatif_2" id="norek_alternatif_2" maxlength="100"
                                    value="{{ old('norek_alternatif_2', $config->norek_alternatif_2) }}"
                                    placeholder="Contoh: 9876543210"
                                    class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition font-mono">
                            </div>
                            <div>
                                <label for="atas_nama_alternatif_2" class="block text-[11px] font-semibold text-slate-600 mb-1">Atas Nama</label>
                                <input type="text" name="atas_nama_alternatif_2" id="atas_nama_alternatif_2" maxlength="150"
                                    value="{{ old('atas_nama_alternatif_2', $config->atas_nama_alternatif_2) }}"
                                    placeholder="Contoh: SMKN 2 KARANGANYAR"
                                    class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. JATUH TEMPO PEMBAYARAN & BATAS PEMBATALAN -->
                <div class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100 space-y-5">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-sm">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Pengaturan Waktu Pembayaran & Batas Pembatalan</h2>
                            <p class="text-xs text-slate-500">Masa tenggang pembayaran, batas waktu pelunasan, minimal selisih peminjaman, serta toleransi pembatalan pemohon.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- 1. DP JAM -->
                        <div class="p-4 bg-purple-50/40 border border-purple-100 rounded-xl space-y-2 flex flex-col justify-between">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <label for="jatuh_tempo_dp_jam" class="block text-xs font-bold text-slate-800 uppercase tracking-wide">
                                        Batas Waktu Transfer DP (Jam) <span class="text-red-500">*</span>
                                    </label>
                                    <span class="text-[10px] font-bold text-purple-700 bg-purple-100 px-2 py-0.5 rounded-full">Deposit</span>
                                </div>
                                <div class="relative">
                                    <input type="number" name="jatuh_tempo_dp_jam" id="jatuh_tempo_dp_jam" required min="1" max="720"
                                        value="{{ old('jatuh_tempo_dp_jam', $config->jatuh_tempo_dp_jam) }}"
                                        class="w-full pl-3.5 pr-14 py-2.5 bg-white border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 font-bold focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition">
                                    <span class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-xs font-bold text-slate-400">
                                        Jam
                                    </span>
                                </div>
                            </div>
                            <p class="text-[11px] text-slate-500 leading-tight pt-1">
                                Batas waktu bagi pemohon untuk mengunggah bukti transfer DP setelah pengajuan disetujui (default: 24 jam).
                            </p>
                        </div>

                        <!-- 2. PELUNASAN JAM -->
                        <div class="p-4 bg-blue-50/40 border border-blue-100 rounded-xl space-y-2 flex flex-col justify-between">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <label for="jatuh_tempo_pelunasan_jam" class="block text-xs font-bold text-slate-800 uppercase tracking-wide">
                                        Pelunasan <span class="text-red-500">*</span>
                                    </label>
                                    <span class="text-[10px] font-bold text-brand-700 bg-blue-100 px-2 py-0.5 rounded-full">Pelunasan</span>
                                </div>
                                <div class="relative">
                                    <input type="number" name="jatuh_tempo_pelunasan_jam" id="jatuh_tempo_pelunasan_jam" required min="1" max="720"
                                        value="{{ old('jatuh_tempo_pelunasan_jam', $config->jatuh_tempo_pelunasan_jam) }}"
                                        class="w-full pl-3.5 pr-14 py-2.5 bg-white border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 font-bold focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition">
                                    <span class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-xs font-bold text-slate-400">
                                        Jam
                                    </span>
                                </div>
                            </div>
                            <p class="text-[11px] text-slate-500 leading-tight pt-1">
                                Batas waktu menyelesaikan pelunasan sisa tagihan sewa aula sebelum hari pelaksanaan (default: 48 jam).
                            </p>
                        </div>

                        <!-- 3. MINIMAL SELISIH BOOKING (HARI) -->
                        <div class="p-4 bg-emerald-50/40 border border-emerald-100 rounded-xl space-y-2 flex flex-col justify-between">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <label for="minimal_hari_booking" class="block text-xs font-bold text-slate-800 uppercase tracking-wide">
                                        Min. Booking <span class="text-red-500">*</span>
                                    </label>
                                    <span class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">H-Acara</span>
                                </div>
                                <div class="relative">
                                    <input type="number" name="minimal_hari_booking" id="minimal_hari_booking" required min="1" max="365"
                                        value="{{ old('minimal_hari_booking', $config->minimal_hari_booking ?? 3) }}"
                                        class="w-full pl-3.5 pr-14 py-2.5 bg-white border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 font-bold focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition">
                                    <span class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-xs font-bold text-slate-400">
                                        Hari
                                    </span>
                                </div>
                            </div>
                            <p class="text-[11px] text-slate-500 leading-tight pt-1">
                                Minimal selisih hari pemesanan ke hari H acara. Wajib lebih besar dari batas waktu pelunasan final.
                            </p>
                        </div>

                        <!-- 4. OFFSET BATAS WAKTU PEMBATALAN (H-) -->
                        <div class="p-4 bg-rose-50/40 border border-rose-100 rounded-xl space-y-2 flex flex-col justify-between">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <label for="offset_hari_pembatalan" class="block text-xs font-bold text-slate-800 uppercase tracking-wide">
                                        Batas Batal (H-) <span class="text-red-500">*</span>
                                    </label>
                                    <span class="text-[10px] font-bold text-rose-700 bg-rose-100 px-2 py-0.5 rounded-full">Batal Sewa</span>
                                </div>
                                <div class="relative">
                                    <input type="number" name="offset_hari_pembatalan" id="offset_hari_pembatalan" required min="1" max="365"
                                        value="{{ old('offset_hari_pembatalan', $config->offset_hari_pembatalan ?? 1) }}"
                                        placeholder="Contoh: 9"
                                        class="w-full pl-3.5 pr-14 py-2.5 bg-white border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 font-bold focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition">
                                    <span class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-xs font-bold text-slate-400">
                                        H-Hari
                                    </span>
                                </div>
                            </div>
                            <p class="text-[11px] text-slate-500 leading-tight pt-1">
                                Waktu toleransi maksimal pembatalan pengajuan sebelum hari H. Misal diatur <strong>9</strong>, maka pembatalan bisa dilakukan sampai <strong>H-9</strong> peminjaman. Nilai ini tidak boleh melebihi minimal selisih booking.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- 4. INSTRUKSI PEMBAYARAN -->
                <div class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100 space-y-4">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-sm">
                            <i class="fa-solid fa-list-check"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Instruksi / Catatan Pembayaran</h2>
                            <p class="text-xs text-slate-500">Teks petunjuk cara transfer yang akan ditampilkan pada panel pelanggan.</p>
                        </div>
                    </div>

                    <div>
                        <textarea name="instruksi_pembayaran" id="instruksi_pembayaran" rows="3" maxlength="2000"
                            placeholder="Tuliskan petunjuk pembayaran untuk penyewa aula..."
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition leading-relaxed">{{ old('instruksi_pembayaran', $config->instruksi_pembayaran) }}</textarea>
                    </div>
                </div>

            </div>

            <!-- KOLOM KANAN (SPAN 1): QRIS & STATUS AKTIF -->
            <div class="space-y-6">

                <!-- CARD QRIS -->
                <div class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100 space-y-5">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                                <i class="fa-solid fa-qrcode"></i>
                            </div>
                            <div>
                                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Metode QRIS</h2>
                                <p class="text-[11px] text-slate-500">Pembayaran instan via e-wallet & m-banking.</p>
                            </div>
                        </div>
                        @if ($config->qris_image)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500">
                                Belum Ada
                            </span>
                        @endif
                    </div>

                    <div>
                        <label for="qris_merchant" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                            Nama Merchant QRIS
                        </label>
                        <input type="text" name="qris_merchant" id="qris_merchant" maxlength="150"
                            value="{{ old('qris_merchant', $config->qris_merchant) }}"
                            placeholder="Contoh: SMKN 2 KRA AULA"
                            class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition font-medium">
                    </div>

                    <!-- PREVIEW ATAU UPLOAD QRIS -->
                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide">
                            Gambar / Barcode QRIS
                        </label>

                        @if ($config->qris_image)
                            <div class="relative bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl p-4 flex flex-col items-center justify-center text-center group">
                                <img src="{{ $config->qris_url }}" alt="QRIS Sekolah" class="max-h-56 max-w-full rounded-xl object-contain shadow-sm bg-white p-2">
                                <div class="mt-3 flex items-center gap-2">
                                    <label class="inline-flex items-center gap-1.5 text-xs text-red-600 hover:text-red-700 cursor-pointer font-semibold">
                                        <input type="checkbox" name="delete_qris" value="1" class="rounded border-slate-300 text-red-600 focus:ring-red-500">
                                        <span>Hapus Gambar QRIS</span>
                                    </label>
                                </div>
                            </div>
                        @endif

                        <div class="space-y-1.5">
                            <input type="file" name="qris_image" id="qris_image" accept="image/png,image/jpeg,image/webp"
                                class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                            <p class="text-[11px] text-slate-400">
                                Format: JPG, PNG, WEBP. Maks 2MB. Gambar akan otomatis diperbarui bila Anda memilih file baru.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- CARD STATUS & ACTIONS -->
                <div class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="block text-xs font-bold text-slate-800 uppercase tracking-wide">Status Pembayaran</span>
                            <span class="text-xs text-slate-500">Aktifkan sistem pembayaran untuk pelanggan</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $config->is_active) ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>

                    <div class="pt-4 border-t border-slate-100 space-y-2.5">
                        <button type="submit"
                            class="w-full py-3 px-4 bg-brand-600 hover:bg-brand-700 active:scale-95 text-white font-bold rounded-xl text-xs md:text-sm shadow-md transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-floppy-disk text-sm"></i>
                            <span>Simpan Konfigurasi</span>
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </form>

</div>
@endsection
