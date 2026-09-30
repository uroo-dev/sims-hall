@extends('Admin.layout.app')

@section('title', 'Pembayaran Sewa Aula - SMKN 2 Karanganyar')

@section('content')
<div class="space-y-6">

    <!-- STEP PROGRESS TRACKER -->
    <div class="bg-white rounded-2xl p-4 md:p-6 figma-card-shadow border border-slate-100">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 text-center">
            <!-- Step 1 -->
            <div class="flex items-center gap-2.5 p-2 rounded-xl bg-slate-50 text-slate-500">
                <div class="w-7 h-7 rounded-full bg-emerald-500 text-white font-bold text-xs flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-check"></i>
                </div>
                <div class="text-left">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase">Langkah 1</span>
                    <span class="text-xs font-bold text-slate-700">Pilih Paket</span>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="flex items-center gap-2.5 p-2 rounded-xl bg-slate-50 text-slate-500">
                <div class="w-7 h-7 rounded-full bg-emerald-500 text-white font-bold text-xs flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-check"></i>
                </div>
                <div class="text-left">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase">Langkah 2</span>
                    <span class="text-xs font-bold text-slate-700">Form Pengajuan</span>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="flex items-center gap-2.5 p-2 rounded-xl bg-brand-50 border border-brand-200 text-brand-700">
                <div class="w-7 h-7 rounded-full bg-brand-600 text-white font-bold text-xs flex items-center justify-center flex-shrink-0 shadow-xs">
                    3
                </div>
                <div class="text-left">
                    <span class="block text-[10px] font-bold text-brand-500 uppercase">Langkah 3</span>
                    <span class="text-xs font-black text-brand-700">Pembayaran DP / Lunas</span>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="flex items-center gap-2.5 p-2 rounded-xl bg-slate-50 text-slate-400 {{ $pembayaran->status_pembayaran === 'lunas' ? 'opacity-100 bg-emerald-50 text-emerald-700' : 'opacity-60' }}">
                <div class="w-7 h-7 rounded-full {{ $pembayaran->status_pembayaran === 'lunas' ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-slate-500' }} font-bold text-xs flex items-center justify-center flex-shrink-0">
                    @if ($pembayaran->status_pembayaran === 'lunas')
                        <i class="fa-solid fa-check"></i>
                    @else
                        4
                    @endif
                </div>
                <div class="text-left">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase">Langkah 4</span>
                    <span class="text-xs font-bold text-slate-500">Verifikasi Sekolah</span>
                </div>
            </div>
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
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-2xl p-4 space-y-2 shadow-sm animate-fade-in">
            <div class="flex items-center gap-2 font-bold text-xs md:text-sm text-red-700">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span>Terdapat kesalahan pengiriman bukti pembayaran:</span>
            </div>
            <ul class="list-disc list-inside text-xs text-red-600 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- CARD TENGGAT WAKTU & STATUS (PUTIH SOLID, COMPACT/TIPIS, HIGHLIGHT COUNTDOWN SAJA) -->
    @php
        $isPartial = $pembayaran->status_pembayaran === 'partial' || ($pembayaran->total_terbayar > 0 && $pembayaran->sisa_tagihan > 0);
        $targetDeadline = $isPartial
            ? ($pembayaran->jatuh_tempo_pelunasan ?: ($pembayaran->peminjaman?->tanggal_mulai ? \Carbon\Carbon::parse($pembayaran->peminjaman->tanggal_mulai)->subHours((int) ($config->jatuh_tempo_pelunasan_jam ?? 24)) : now()->addHours(48)))
            : ($pembayaran->jatuh_tempo_dp ?: now()->addHours(24));
        $isExpired = now()->isAfter($targetDeadline) && in_array($pembayaran->status_pembayaran, ['pending', 'rejected']);
    @endphp
    <div class="bg-white rounded-2xl p-4 md:py-3.5 md:px-5 border border-slate-100 figma-card-shadow flex flex-col md:flex-row md:items-center justify-between gap-4">
        <!-- Info Kiri: Invoice, Paket, Pemohon & Jadwal -->
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 bg-slate-100 text-slate-700 rounded-md text-[10px] font-bold tracking-wider uppercase">
                    Invoice {{ $pembayaran->kode_pembayaran }}
                </span>
                @if ($pembayaran->status_pembayaran === 'lunas')
                    <span class="px-2.5 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-md text-[10px] font-bold uppercase">
                        <i class="fa-solid fa-check mr-1"></i> Lunas
                    </span>
                @elseif ($pembayaran->status_pembayaran === 'partial')
                    <span class="px-2.5 py-0.5 bg-blue-50 text-brand-700 border border-blue-200 rounded-md text-[10px] font-bold uppercase">
                        <i class="fa-solid fa-shield-halved mr-1"></i> DP Terverifikasi
                    </span>
                @elseif ($pembayaran->status_pembayaran === 'refund_pending')
                    <span class="px-2.5 py-0.5 bg-purple-50 text-purple-700 border border-purple-200 rounded-md text-[10px] font-bold uppercase animate-pulse">
                        <i class="fa-solid fa-hand-holding-dollar mr-1"></i> Menunggu Refund
                    </span>
                @elseif ($pembayaran->status_pembayaran === 'refunded')
                    <span class="px-2.5 py-0.5 bg-slate-100 text-slate-700 border border-slate-200 rounded-md text-[10px] font-bold uppercase">
                        <i class="fa-solid fa-check-double mr-1"></i> Refund Selesai
                    </span>
                @elseif ($pembayaran->status_pembayaran === 'rejected')
                    <span class="px-2.5 py-0.5 bg-red-50 text-red-700 border border-red-200 rounded-md text-[10px] font-bold uppercase">
                        <i class="fa-solid fa-triangle-exclamation mr-1"></i> Transfer Ditolak
                    </span>
                @elseif ($pembayaran->status_pembayaran === 'hangus')
                    <span class="px-2.5 py-0.5 bg-slate-200 text-slate-700 rounded-md text-[10px] font-bold uppercase">
                        Expired
                    </span>
                @else
                    <span class="px-2.5 py-0.5 bg-amber-50 text-amber-800 border border-amber-200 rounded-md text-[10px] font-bold uppercase">
                        Menunggu Pembayaran
                    </span>
                @endif
            </div>

            <h1 class="text-base md:text-lg font-black text-slate-800 tracking-tight leading-snug">
                {{ $paket?->nama_paket ?: 'Paket Sewa Aula' }}
            </h1>
            <p class="text-xs text-slate-500">
                Peminjam: <strong class="text-slate-700">{{ $pembayaran->peminjaman?->nama }}</strong> &bull;
                Jadwal: {{ $pembayaran->peminjaman?->tanggal_mulai?->translatedFormat('d M Y, H:i') }} - {{ $pembayaran->peminjaman?->tanggal_selesai?->translatedFormat('d M Y, H:i') }} WIB
            </p>
        </div>

        <!-- Info Kanan: Highlight Countdown Teks Saja -->
        <div class="flex flex-col md:items-end justify-center pt-2 md:pt-0 border-t md:border-t-0 border-slate-100">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1">
                <i class="fa-regular fa-clock text-amber-500"></i> {{ $isPartial ? 'Tenggat Waktu Pelunasan' : 'Tenggat Waktu Pembayaran DP' }}
            </span>
            <div class="flex items-center gap-1 font-mono text-xl md:text-2xl font-black tracking-wider text-brand-700 mt-0.5">
                <span id="cd-hours">00</span>
                <span class="text-slate-300 font-sans">:</span>
                <span id="cd-minutes">00</span>
                <span class="text-slate-300 font-sans">:</span>
                <span id="cd-seconds">00</span>
            </div>
            <div id="countdownStatus" class="text-[11px] font-medium text-slate-500">
                Batas: {{ $targetDeadline->translatedFormat('d M Y, H:i') }} WIB
            </div>
        </div>
    </div>

    <!-- BANNER PERINGATAN REJECT PEMBAYARAN (TRANSFER ULANG) -->
    @if ($pembayaran->status_pembayaran === 'rejected' && $pembayaran->peminjaman?->status !== 'rejected')
        <div class="bg-red-50 border-2 border-red-200 text-red-900 rounded-2xl p-5 shadow-xs flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-red-600 text-white flex items-center justify-center font-bold text-lg flex-shrink-0">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div class="space-y-1.5 text-xs md:text-sm">
                <h3 class="font-black text-red-900 text-sm md:text-base">Bukti Pembayaran Ditolak Oleh Admin! Silakan Transfer Ulang</h3>
                <p class="text-red-700 leading-relaxed">
                    <strong>Alasan Penolakan:</strong> {{ $pembayaran->catatan }}
                </p>
                <div class="text-[11px] text-red-800 bg-red-100/60 p-2.5 rounded-xl border border-red-200">
                    <i class="fa-regular fa-clock mr-1 font-bold"></i> Batas Waktu Transfer Ulang: <strong>{{ $targetDeadline->translatedFormat('d F Y, H:i') }} WIB</strong>. Jika melewati batas waktu tersebut tanpa mengunggah bukti pembayaran yang valid, sistem secara otomatis akan membatalkan dan menolak permohonan peminjaman aula.
                </div>
            </div>
        </div>
    @endif

    <!-- BANNER KEDALUWARSA / PERMOHONAN DIBATALKAN OTOMATIS OLEH SISTEM -->
    @if ($pembayaran->status_pembayaran === 'hangus' || ($pembayaran->peminjaman?->status === 'rejected' && $pembayaran->status_pembayaran !== 'refund_pending' && $pembayaran->status_pembayaran !== 'refunded'))
        <div class="bg-slate-100 border-2 border-slate-300 text-slate-800 rounded-2xl p-5 shadow-xs flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-slate-600 text-white flex items-center justify-center font-bold text-lg flex-shrink-0">
                <i class="fa-solid fa-ban"></i>
            </div>
            <div class="space-y-1 text-xs md:text-sm">
                <h3 class="font-black text-slate-900 text-sm md:text-base">Permohonan Peminjaman Aula Telah Ditolak / Dibatalkan</h3>
                <p class="text-slate-600 leading-relaxed">
                    {{ $pembayaran->catatan ?: 'Permohonan peminjaman aula ini telah ditolak oleh admin atau melewati batas waktu pembayaran yang ditentukan.' }}
                </p>
                <div class="pt-2">
                    <a href="{{ route('customer.paket') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold rounded-xl shadow-xs transition">
                        <i class="fa-solid fa-plus text-[10px]"></i>
                        <span>Ajukan Permohonan Baru</span>
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- MODUL KHUSUS REFUND (JIKA STATUS PEMBAYARAN REFUND_PENDING ATAU REFUNDED) -->
    @if (in_array($pembayaran->status_pembayaran, ['refund_pending', 'refunded']))
        <div class="bg-purple-50/60 rounded-3xl p-6 md:p-8 border-2 border-purple-200 figma-card-shadow space-y-6">
            <div class="flex items-center gap-3.5 pb-4 border-b border-purple-200/80">
                <div class="w-12 h-12 rounded-2xl bg-purple-600 text-white flex items-center justify-center text-xl font-bold flex-shrink-0 shadow-xs">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                </div>
                <div>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-200 text-purple-800 uppercase tracking-wider">
                        Pengembalian Dana (Refund)
                    </span>
                    <h2 class="text-base md:text-lg font-black text-slate-900 tracking-tight mt-0.5">
                        Pengajuan Peminjaman Ditolak & Proses Pengembalian Dana
                    </h2>
                    <p class="text-xs text-slate-500">
                        Pihak sekolah mengembalikan dana sebesar <strong class="text-purple-700">Rp {{ number_format($pembayaran->total_refund, 0, ',', '.') }}</strong> yang telah Anda bayarkan.
                    </p>
                </div>
            </div>

            <!-- Catatan Alasan Penolakan dari Admin -->
            <div class="p-4 bg-white rounded-2xl border border-purple-100 text-xs md:text-sm space-y-1">
                <span class="font-bold text-slate-700 block">Keterangan / Alasan Penolakan dari Pihak Sekolah:</span>
                <p class="text-slate-600 italic">
                    "{{ $pembayaran->catatan }}"
                </p>
            </div>

            @php
                $refund = $refundDetail ?: $pembayaran->details->firstWhere('tipe_pembayaran', 'refund');
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- KOLOM 1: DATA REKENING PEMOHON -->
                <div class="bg-white p-5 md:p-6 rounded-2xl border border-purple-100 space-y-4">
                    <div class="flex items-center gap-2 font-bold text-slate-800 text-xs md:text-sm uppercase tracking-wide">
                        <i class="fa-solid fa-credit-card text-purple-600"></i>
                        <span>1. Rekening Pengembalian Dana Anda</span>
                    </div>

                    @if ($refund && $refund->norek_tujuan)
                        <!-- Data Rekening Sudah Terisi -->
                        <div class="p-4 bg-purple-50/40 rounded-xl border border-purple-100 space-y-2 text-xs">
                            <div>
                                <span class="text-slate-400 block text-[10px] uppercase font-bold">Nama Bank:</span>
                                <strong class="text-slate-900 text-sm">{{ $refund->bank_tujuan }}</strong>
                            </div>
                            <div>
                                <span class="text-slate-400 block text-[10px] uppercase font-bold">Nomor Rekening:</span>
                                <span class="font-mono font-black text-purple-700 text-base">{{ $refund->norek_tujuan }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block text-[10px] uppercase font-bold">Atas Nama Pemilik:</span>
                                <strong class="text-slate-800 text-xs">{{ $refund->atas_nama_pengirim }}</strong>
                            </div>
                        </div>

                        @if ($pembayaran->status_pembayaran === 'refund_pending' && !$refund->bukti_pembayaran)
                            <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-[11px] text-amber-800 flex items-center gap-2">
                                <i class="fa-solid fa-hourglass-half text-amber-600"></i>
                                <span>Data rekening Anda telah diterima. Menunggu pihak sekolah mentransfer dan mengirimkan bukti transfer refund.</span>
                            </div>
                        @endif
                    @else
                        <!-- Form Pengisian Rekening Pemohon -->
                        <form action="{{ route('customer.pembayaran.rekening-refund', $pembayaran->id) }}" method="POST" class="space-y-3">
                            @csrf
                            <p class="text-[11px] text-slate-500">
                                Silakan masukkan nomor rekening tujuan yang aktif untuk menerima transfer pengembalian dana (refund):
                            </p>

                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-slate-700 uppercase">Nama Bank <span class="text-red-500">*</span></label>
                                <input type="text" name="bank_tujuan" required placeholder="Contoh: Bank BCA, BRI, Mandiri, Bank Jateng"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-purple-500 focus:outline-none">
                            </div>

                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-slate-700 uppercase">Nomor Rekening <span class="text-red-500">*</span></label>
                                <input type="text" name="norek_tujuan" required placeholder="Contoh: 1234567890"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-mono text-slate-800 focus:bg-white focus:ring-2 focus:ring-purple-500 focus:outline-none">
                            </div>

                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-slate-700 uppercase">Atas Nama Pemilik Rekening <span class="text-red-500">*</span></label>
                                <input type="text" name="atas_nama_pengirim" required placeholder="Nama lengkap sesuai buku tabungan"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-purple-500 focus:outline-none">
                            </div>

                            <button type="submit"
                                class="w-full py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-paper-plane text-xs"></i>
                                <span>Simpan Data Rekening Refund</span>
                            </button>
                        </form>
                    @endif
                </div>

                <!-- KOLOM 2: BUKTI TRANSFER REFUND DARI SEKOLAH & KONFIRMASI -->
                <div class="bg-white p-5 md:p-6 rounded-2xl border border-purple-100 space-y-4">
                    <div class="flex items-center gap-2 font-bold text-slate-800 text-xs md:text-sm uppercase tracking-wide">
                        <i class="fa-solid fa-receipt text-purple-600"></i>
                        <span>2. Bukti Transfer Refund Dari Sekolah</span>
                    </div>

                    @if ($refund && $refund->bukti_pembayaran)
                        <!-- Admin Sudah Mengirim Bukti Transfer Refund -->
                        <div class="space-y-3">
                            <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-200 text-xs text-emerald-800 flex items-center justify-between">
                                <span class="font-bold"><i class="fa-solid fa-check-circle mr-1"></i> Bukti Transfer Telah Diunggah</span>
                                <span class="text-[11px] text-emerald-600">{{ $refund->tanggal_bayar ? $refund->tanggal_bayar->format('d/m/Y H:i') : '' }}</span>
                            </div>

                            <a href="{{ $refund->bukti_pembayaran_url }}" target="_blank"
                                class="block rounded-xl overflow-hidden border border-slate-200 hover:opacity-95 transition">
                                <img src="{{ $refund->bukti_pembayaran_url }}" alt="Bukti Transfer Refund" class="w-full h-40 object-cover">
                            </a>

                            @if ($pembayaran->status_pembayaran === 'refund_pending')
                                <!-- Form Tombol Konfirmasi Dana Telah Diterima -->
                                <form action="{{ route('customer.pembayaran.konfirmasi-refund', $pembayaran->id) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin telah menerima dana pengembalian ke rekening Anda?')">
                                    @csrf
                                    <button type="submit"
                                        class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-black shadow-xs transition flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-circle-check"></i>
                                        <span>Konfirmasi Dana Telah Diterima (Selesai)</span>
                                    </button>
                                </form>
                            @else
                                <div class="p-3.5 bg-emerald-50 rounded-xl text-xs text-emerald-800 text-center font-bold flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-check-double text-base"></i>
                                    <span>Pengembalian dana telah selesai dan dikonfirmasi diterima.</span>
                                </div>
                            @endif
                        </div>
                    @else
                        <!-- Menunggu Admin Unggah Bukti -->
                        <div class="p-8 text-center text-slate-400 space-y-2">
                            <i class="fa-regular fa-clock text-3xl text-purple-300 block mb-1"></i>
                            <p class="font-bold text-slate-700 text-xs md:text-sm">Menunggu Transfer Dari Sekolah</p>
                            <p class="text-[11px] text-slate-400">
                                Bukti transfer refund akan tampil di sini setelah admin sekolah mentransfer dana ke rekening yang Anda masukkan.
                            </p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    @endif

    <!-- MAIN TWO-COLUMN CONTENT GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- KOLOM KIRI (SPAN 2): FORMULIR KONFIRMASI PEMBAYARAN & RIWAYAT -->
        <div class="lg:col-span-2 space-y-6">

            <!-- 1. FORMULIR KONFIRMASI PEMBAYARAN (HANYA MUNCUL JIKA STATUS BUKAN LUNAS, BUKAN REFUND, DAN BUKAN HANGUS) -->
            @if (!in_array($pembayaran->status_pembayaran, ['lunas', 'refund_pending', 'refunded', 'hangus']) && $pembayaran->peminjaman?->status !== 'rejected')
                <div class="bg-white rounded-2xl p-6 md:p-8 figma-card-shadow border border-slate-100 space-y-7">
                    <!-- Header Card -->
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center font-bold text-base flex-shrink-0">
                            <i class="fa-solid fa-receipt"></i>
                        </div>
                        <div>
                            <h2 class="text-sm md:text-base font-bold text-slate-800 uppercase tracking-wide">
                                {{ $pembayaran->status_pembayaran === 'rejected' ? 'Konfirmasi Transfer Ulang Bukti Pembayaran' : 'Konfirmasi & Unggah Bukti Transfer' }}
                            </h2>
                            <p class="text-xs text-slate-500 mt-0.5">Pilih rekening tujuan, skema pembayaran, dan kirimkan struk transfer Anda</p>
                        </div>
                    </div>

                    <form action="{{ route('customer.pembayaran.bayar', $pembayaran->id) }}" method="POST" enctype="multipart/form-data" class="space-y-7">
                        @csrf

                        <!-- KOMPONEN 1: PILIHAN SKEMA PEMBAYARAN (DP ATAU LANGSUNG LUNAS) -->
                        <div class="space-y-2.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide">
                                1. Pilih Skema Pembayaran <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                                <!-- Option 1: Bayar DP -->
                                <label class="payment-option cursor-pointer p-4 rounded-2xl border-2 border-brand-500 bg-brand-50/30 flex items-start gap-3 transition hover:border-brand-600 shadow-2xs">
                                    <input type="radio" name="tipe_pembayaran" value="dp" checked onchange="updatePaymentScheme('dp')"
                                        class="mt-1 text-brand-600 focus:ring-brand-500">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-xs font-black text-slate-800">Bayar Uang Muka (DP)</span>
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-brand-100 text-brand-700">Cicilan 1</span>
                                        </div>
                                        <div class="text-base md:text-lg font-black text-brand-700">
                                            Rp {{ number_format($nominalDp, 0, ',', '.') }}
                                        </div>
                                        <p class="text-[11px] text-slate-500 leading-tight">
                                            Konfirmasi booking aula awal. Pelunasan dapat diselesaikan kemudian.
                                        </p>
                                    </div>
                                </label>

                                <!-- Option 2: Langsung Lunas -->
                                <label class="payment-option cursor-pointer p-4 rounded-2xl border-2 border-slate-200 bg-slate-50/50 flex items-start gap-3 transition hover:border-brand-500 shadow-2xs">
                                    <input type="radio" name="tipe_pembayaran" value="lunas_langsung" onchange="updatePaymentScheme('lunas_langsung')"
                                        class="mt-1 text-brand-600 focus:ring-brand-500">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-xs font-black text-slate-800">Langsung Lunas (100%)</span>
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-100 text-emerald-700">Penuh</span>
                                        </div>
                                        <div class="text-base md:text-lg font-black text-emerald-700">
                                            Rp {{ number_format($pembayaran->sisa_tagihan, 0, ',', '.') }}
                                        </div>
                                        <p class="text-[11px] text-slate-500 leading-tight">
                                            Selesaikan pembayaran penuh tanpa perlu membayar cicilan berikutnya.
                                        </p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- KOMPONEN 2: REKENING TUJUAN & NOMINAL BAYAR -->
                        <div class="pt-3 border-t border-slate-100/80 space-y-4">
                            <span class="block text-xs font-bold text-slate-700 uppercase tracking-wide">
                                2. Rekening Tujuan Sekolah & Nominal Transfer
                            </span>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label for="bank_tujuan" class="block text-xs font-semibold text-slate-600 mb-1.5">
                                        Pilih Rekening / Metode Tujuan <span class="text-red-500">*</span>
                                    </label>
                                    <select name="bank_tujuan" id="bank_tujuan" required onchange="handleBankChange(this)"
                                        class="w-full px-3.5 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition">
                                        <option value="{{ $config->bank_utama }}" data-type="bank" data-norek="{{ $config->norek_utama }}" data-an="{{ $config->atas_nama_utama }}">
                                            {{ $config->bank_utama }} - {{ $config->norek_utama }} (a.n. {{ $config->atas_nama_utama }})
                                        </option>
                                        @if ($config->bank_alternatif_1)
                                            <option value="{{ $config->bank_alternatif_1 }}" data-type="bank" data-norek="{{ $config->norek_alternatif_1 }}" data-an="{{ $config->atas_nama_alternatif_1 }}">
                                                {{ $config->bank_alternatif_1 }} - {{ $config->norek_alternatif_1 }} (a.n. {{ $config->atas_nama_alternatif_1 }})
                                            </option>
                                        @endif
                                        @if ($config->bank_alternatif_2)
                                            <option value="{{ $config->bank_alternatif_2 }}" data-type="bank" data-norek="{{ $config->norek_alternatif_2 }}" data-an="{{ $config->atas_nama_alternatif_2 }}">
                                                {{ $config->bank_alternatif_2 }} - {{ $config->norek_alternatif_2 }} (a.n. {{ $config->atas_nama_alternatif_2 }})
                                            </option>
                                        @endif
                                        <option value="QRIS" data-type="qris" data-merchant="{{ $config->qris_merchant ?: 'SMKN 2 KRA AULA' }}">
                                            QRIS Resmi Sekolah ({{ $config->qris_merchant ?: 'SMKN 2 KRA AULA' }})
                                        </option>
                                    </select>

                                    <!-- Box Info Rekening Bank (Tampil saat pilih Bank) -->
                                    <div id="bankInfoBox" class="mt-2.5 p-2.5 bg-blue-50/40 border border-blue-100/70 rounded-xl flex items-center justify-between text-xs">
                                        <div class="text-slate-600 text-[11px] truncate mr-2">
                                            No. Rek: <strong id="selectedNorek" class="font-mono text-slate-800 font-bold">{{ $config->norek_utama }}</strong>
                                            <span class="text-slate-300 mx-1.5">|</span>
                                            a.n. <strong id="selectedAtasNama" class="text-slate-800">{{ $config->atas_nama_utama }}</strong>
                                        </div>
                                        <button type="button" onclick="salinNorekTerpilih(this)"
                                            class="px-2.5 py-1 bg-white hover:bg-slate-100 border border-slate-200 rounded-lg text-[11px] font-bold text-brand-600 flex items-center gap-1 shadow-2xs transition flex-shrink-0">
                                            <i class="fa-regular fa-copy"></i>
                                            <span>Salin</span>
                                        </button>
                                    </div>
                                </div>

                                <div>
                                    <label for="jumlah_bayar" class="block text-xs font-semibold text-slate-600 mb-1.5">
                                        Jumlah Transfer (Rp) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="jumlah_bayar" id="jumlah_bayar" required min="1000"
                                        value="{{ old('jumlah_bayar', $nominalDp) }}"
                                        class="w-full px-3.5 py-3 bg-slate-100 border border-slate-200 rounded-xl text-xs md:text-sm font-black text-brand-700 focus:outline-none transition">
                                </div>
                            </div>

                            <!-- Gambar QRIS Lebar 2/3 dari Card Parent (Tampil saat pilih QRIS) -->
                            <div id="qrisInfoBox" class="w-full hidden pt-2 text-center">
                                @if ($config->qris_image)
                                    <img src="{{ $config->qris_url }}" alt="QRIS {{ $config->qris_merchant ?: 'Sekolah' }}"
                                        class="w-full md:w-2/3 mx-auto h-auto rounded-2xl border border-slate-200 object-contain shadow-xs">
                                @else
                                    <div class="w-full md:w-2/3 mx-auto py-12 text-center bg-slate-50 border border-dashed border-slate-200 rounded-2xl text-slate-400">
                                        <i class="fa-solid fa-qrcode text-6xl mb-2 text-slate-300 block"></i>
                                        <span class="text-xs">QR Code belum diunggah di konfigurasi</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- KOMPONEN 3: DATA REKENING PENGIRIM -->
                        <div class="pt-3 border-t border-slate-100/80 space-y-4">
                            <span class="block text-xs font-bold text-slate-700 uppercase tracking-wide">
                                3. Identitas Rekening Pengirim
                            </span>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                <div>
                                    <label for="bank_pengirim" class="block text-xs font-semibold text-slate-600 mb-1.5">
                                        Bank Asal Pengirim <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="bank_pengirim" id="bank_pengirim" required maxlength="100"
                                        value="{{ old('bank_pengirim') }}"
                                        placeholder="Contoh: BCA / BRI / Mandiri"
                                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition font-medium">
                                </div>

                                <div>
                                    <label for="norek_pengirim" class="block text-xs font-semibold text-slate-600 mb-1.5">
                                        Nomor Rekening Pengirim <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="norek_pengirim" id="norek_pengirim" required maxlength="100"
                                        value="{{ old('norek_pengirim') }}"
                                        placeholder="Nomor rekening Anda"
                                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition font-mono font-medium">
                                </div>

                                <div>
                                    <label for="atas_nama_pengirim" class="block text-xs font-semibold text-slate-600 mb-1.5">
                                        Atas Nama Pengirim <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="atas_nama_pengirim" id="atas_nama_pengirim" required maxlength="150"
                                        value="{{ old('atas_nama_pengirim', $user?->name) }}"
                                        placeholder="Nama di buku tabungan"
                                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition font-medium">
                                </div>
                            </div>
                        </div>

                        <!-- KOMPONEN 4: UPLOAD BUKTI & TANGGAL TRANSFER -->
                        <div class="pt-3 border-t border-slate-100/80 space-y-4">
                            <span class="block text-xs font-bold text-slate-700 uppercase tracking-wide">
                                4. Bukti Struk & Waktu Transfer
                            </span>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label for="bukti_pembayaran" class="block text-xs font-semibold text-slate-600 mb-1.5">
                                        Unggah Bukti Struk / Transfer <span class="text-red-500">*</span>
                                    </label>
                                    <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" required accept="image/jpeg,image/png,image/webp"
                                        class="w-full text-xs text-slate-500 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 cursor-pointer border border-slate-200 rounded-xl bg-slate-50/50 p-1">
                                    <p class="text-[11px] text-slate-400 mt-1.5">Format berkas: Gambar JPG, JPEG, PNG, WEBP (Maks. 3MB).</p>
                                </div>

                                <div>
                                    <label for="tanggal_bayar" class="block text-xs font-semibold text-slate-600 mb-1.5">
                                        Waktu Transfer Dilakukan <span class="text-red-500">*</span>
                                    </label>
                                    <input type="datetime-local" name="tanggal_bayar" id="tanggal_bayar" required
                                        value="{{ old('tanggal_bayar', now()->format('Y-m-d\TH:i')) }}"
                                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition font-medium">
                                    <p class="text-[11px] text-slate-400 mt-1.5">Sesuai jam dan tanggal yang tertera pada bukti transfer.</p>
                                </div>
                            </div>
                        </div>

                        <!-- KOMPONEN 5: CATATAN OPSIONAL -->
                        <div class="pt-3 border-t border-slate-100/80 space-y-2">
                            <label for="catatan" class="block text-xs font-bold text-slate-700 uppercase tracking-wide">
                                5. Catatan Tambahan (Opsional)
                            </label>
                            <input type="text" name="catatan" id="catatan" maxlength="1000"
                                value="{{ old('catatan') }}"
                                placeholder="Contoh: Transfer DP aula via m-BCA a.n Budi Santoso"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition">
                        </div>

                        <!-- SUBMIT -->
                        <div class="pt-4 border-t border-slate-100">
                            <button type="submit" id="btnSubmitPayment"
                                class="w-full py-3.5 px-4 bg-brand-600 hover:bg-brand-700 active:scale-95 text-white font-bold rounded-xl text-sm shadow-md transition flex items-center justify-center gap-2">
                                <i class="fa-solid fa-paper-plane text-xs"></i>
                                <span>Kirim Bukti Pembayaran</span>
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- 2. RIWAYAT TRANSAKSI / CICILAN -->
            @if ($pembayaran->details->isNotEmpty())
                <div class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100 space-y-4">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                            <i class="fa-solid fa-list-check"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Riwayat Transfer yang Telah Diunggah</h2>
                            <p class="text-xs text-slate-500">Status verifikasi oleh pihak pengelola aula</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        @foreach ($pembayaran->details as $trx)
                            <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl flex flex-col md:flex-row md:items-center justify-between gap-3">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono text-xs font-bold text-slate-800">{{ $trx->kode_transaksi }}</span>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $trx->tipe_pembayaran === 'dp' ? 'bg-blue-100 text-brand-700' : 'bg-emerald-100 text-emerald-700' }}">
                                            {{ $trx->label_tipe }}
                                        </span>
                                    </div>
                                    <div class="text-xs text-slate-600">
                                        Dari <strong>{{ $trx->bank_pengirim }}</strong> ({{ $trx->norek_pengirim }} a.n. {{ $trx->atas_nama_pengirim }})
                                        ke <strong>{{ $trx->bank_tujuan }}</strong>
                                    </div>
                                    <div class="text-[11px] text-slate-400">
                                        Waktu: {{ $trx->tanggal_bayar?->translatedFormat('d M Y, H:i') }} WIB
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="text-right">
                                        <div class="font-black text-sm text-slate-800">
                                            Rp {{ number_format($trx->jumlah_bayar, 0, ',', '.') }}
                                        </div>
                                        <span class="inline-block mt-0.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $trx->status === 'verified' ? 'bg-emerald-100 text-emerald-700' : ($trx->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-800') }}">
                                            {{ $trx->status === 'verified' ? 'Terverifikasi' : ($trx->status === 'rejected' ? 'Ditolak' : 'Menunggu Verifikasi') }}
                                        </span>
                                    </div>

                                    @if ($trx->bukti_pembayaran_url)
                                        <a href="{{ $trx->bukti_pembayaran_url }}" target="_blank"
                                            class="w-9 h-9 rounded-xl bg-white hover:bg-brand-50 text-slate-600 hover:text-brand-600 border border-slate-200 flex items-center justify-center transition shadow-xs"
                                            title="Lihat Bukti Transfer">
                                            <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        <!-- KOLOM KANAN (SPAN 1): RINGKASAN TAGIHAN & FASILITAS AULA -->
        <div class="space-y-6">

            <!-- CARD RINGKASAN TAGIHAN -->
            <div class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100 space-y-4">
                <span class="text-xs font-bold text-brand-600 uppercase tracking-wider block">
                    Ringkasan Tagihan Sewa
                </span>

                <div class="space-y-2.5 text-xs text-slate-600">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                        <span>Total Biaya Paket:</span>
                        <strong class="font-extrabold text-slate-800">Rp {{ number_format($pembayaran->total_tagihan, 0, ',', '.') }}</strong>
                    </div>
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                        <span>Total Terverifikasi:</span>
                        <strong class="font-extrabold text-emerald-600">Rp {{ number_format($pembayaran->total_terbayar, 0, ',', '.') }}</strong>
                    </div>
                    <div class="flex items-center justify-between pt-1">
                        <span class="text-sm font-bold text-slate-800">Sisa Tagihan:</span>
                        <span class="text-lg font-black text-brand-700">
                            Rp {{ number_format($pembayaran->sisa_tagihan, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <div class="p-3 bg-blue-50/70 border border-blue-100 rounded-xl space-y-1 text-xs">
                    <div class="flex items-center justify-between text-slate-700">
                        <span>Minimal DP:</span>
                        <strong class="text-brand-700 font-bold">Rp {{ number_format($nominalDp, 0, ',', '.') }}</strong>
                    </div>
                    <div class="text-[11px] text-slate-500">
                        Pembayaran DP dapat mengamankan jadwal aula untuk Anda.
                    </div>
                </div>
            </div>

            <!-- CARD DETAIL PAKET & FASILITAS -->
            <div class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100 space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Detail Fasilitas Paket
                    </span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-brand-700">
                        {{ $paket?->kategori }}
                    </span>
                </div>

                <div class="space-y-2 max-h-56 overflow-y-auto pr-1 text-xs text-slate-600">
                    @if ($paket && $paket->facilities->isNotEmpty())
                        @foreach ($paket->facilities as $fac)
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-circle-check text-brand-600 text-xs flex-shrink-0"></i>
                                <span>{{ $fac->judul }}</span>
                            </div>
                        @endforeach
                    @else
                        <div class="text-slate-400 italic">Fasilitas standar aula SMK Negeri 2 Karanganyar.</div>
                    @endif
                </div>

                @if ($pembayaran->peminjaman?->catatan)
                    <div class="pt-3 border-t border-slate-100 text-xs text-slate-500">
                        <span class="font-bold text-slate-700 block mb-0.5">Catatan Pemohon:</span>
                        <p class="italic bg-slate-50 p-2.5 rounded-xl border border-slate-100">{{ $pembayaran->peminjaman->catatan }}</p>
                    </div>
                @endif
            </div>

            <!-- TOMBOL NAVIGASI CEPAT -->
            <div class="space-y-2">
                <a href="{{ route('customer.cek-peminjaman') }}"
                    class="w-full py-3 px-4 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-bold text-center block transition shadow-xs">
                    <i class="fa-solid fa-desktop mr-1.5 text-xs text-brand-600"></i> Lihat Status Peminjaman
                </a>
            </div>

        </div>

    </div>

</div>

@endsection

@push('scripts')
<script>
    // Copy to clipboard helper
    function salinTeks(teks, btn) {
        if (!navigator.clipboard) {
            const temp = document.createElement('textarea');
            temp.value = teks;
            document.body.appendChild(temp);
            temp.select();
            document.execCommand('copy');
            document.body.removeChild(temp);
        } else {
            navigator.clipboard.writeText(teks);
        }

        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-check text-emerald-600"></i> <span class="text-emerald-600">Disalin!</span>';
        setTimeout(() => {
            btn.innerHTML = originalHtml;
        }, 2000);
    }

    // Salin nomor rekening yang saat ini terpilih pada select dropdown
    function salinNorekTerpilih(btn) {
        const selectEl = document.getElementById('bank_tujuan');
        if (!selectEl) return;
        const selectedOption = selectEl.options[selectEl.selectedIndex];
        const norek = selectedOption.getAttribute('data-norek') || '';
        if (norek) {
            salinTeks(norek, btn);
        }
    }

    // Handler ketika user memilih rekening bank berbeda atau QRIS di dropdown
    function handleBankChange(selectEl) {
        const selectedOption = selectEl.options[selectEl.selectedIndex];
        const isQris = selectedOption.value === 'QRIS' || selectedOption.getAttribute('data-type') === 'qris';
        const bankInfoBox = document.getElementById('bankInfoBox');
        const qrisInfoBox = document.getElementById('qrisInfoBox');

        if (isQris) {
            if (bankInfoBox) bankInfoBox.classList.add('hidden');
            if (qrisInfoBox) qrisInfoBox.classList.remove('hidden');
        } else {
            if (qrisInfoBox) qrisInfoBox.classList.add('hidden');
            if (bankInfoBox) bankInfoBox.classList.remove('hidden');

            const norek = selectedOption.getAttribute('data-norek') || '';
            const an = selectedOption.getAttribute('data-an') || '';
            const norekEl = document.getElementById('selectedNorek');
            const anEl = document.getElementById('selectedAtasNama');
            if (norekEl) norekEl.innerText = norek;
            if (anEl) anEl.innerText = an;
        }
    }

    // Dynamic Payment Scheme Switcher
    const nominalDp = {{ (float) $nominalDp }};
    const nominalLunas = {{ (float) $pembayaran->sisa_tagihan }};

    function updatePaymentScheme(scheme) {
        const inputJumlah = document.getElementById('jumlah_bayar');
        if (scheme === 'dp') {
            inputJumlah.value = nominalDp;
        } else {
            inputJumlah.value = nominalLunas;
        }
    }

    // REAL-TIME COUNTDOWN TIMER (NO REFRESH REQUIRED)
    // Target ISO timestamp
    const deadlineIso = @js($targetDeadline->toIso8601String());
    const deadlineTime = new Date(deadlineIso).getTime();

    function updateCountdown() {
        const now = new Date().getTime();
        const difference = deadlineTime - now;

        const hoursEl = document.getElementById('cd-hours');
        const minutesEl = document.getElementById('cd-minutes');
        const secondsEl = document.getElementById('cd-seconds');
        const statusEl = document.getElementById('countdownStatus');

        if (difference <= 0) {
            if (hoursEl) hoursEl.innerText = '00';
            if (minutesEl) minutesEl.innerText = '00';
            if (secondsEl) secondsEl.innerText = '00';
            if (statusEl) {
                statusEl.innerHTML = '<span class="text-red-600 font-extrabold"><i class="fa-solid fa-triangle-exclamation mr-1"></i> WAKTU TRANSFER TELAH BERAKHIR (KADALUARSA)</span>';
            }
            return;
        }

        const totalHours = Math.floor(difference / (1000 * 60 * 60));
        const minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((difference % (1000 * 60)) / 1000);

        if (hoursEl) hoursEl.innerText = String(totalHours).padStart(2, '0');
        if (minutesEl) minutesEl.innerText = String(minutes).padStart(2, '0');
        if (secondsEl) secondsEl.innerText = String(seconds).padStart(2, '0');

        // Dynamic visual urgency status
        if (totalHours < 2) {
            statusEl.innerHTML = '<span class="text-amber-600 font-bold"><i class="fa-solid fa-bell mr-1"></i> Segera Berakhir: Selesaikan transfer dalam hitungan jam</span>';
        }
    }

    // Jalankan timer setiap 1 detik secara real-time
    updateCountdown();
    setInterval(updateCountdown, 1000);
</script>
@endpush
