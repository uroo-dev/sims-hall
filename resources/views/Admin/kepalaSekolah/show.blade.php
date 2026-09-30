@extends('Admin.layout.app')

@section('title', 'Detail & Keputusan Persetujuan Final - Kepala Sekolah')
@section('page_title', 'Detail Persetujuan Peminjaman')

@section('content')
<div class="space-y-6">

    <!-- ALERT FLASH NOTIFIKASI -->
    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4 flex items-center justify-between shadow-xs">
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

    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-2xl p-4 flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-red-500 text-white flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <span class="text-xs md:text-sm font-medium">{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
    @endif

    <!-- NAVIGASI KEMBALI & HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('kepala-sekolah.peminjaman.index') }}"
                class="w-10 h-10 rounded-2xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 hover:text-slate-900 flex items-center justify-center transition shadow-2xs">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="font-mono text-xs font-bold text-brand-700 bg-blue-50 px-2 py-0.5 rounded-lg border border-blue-100">
                        #{{ $peminjaman->id }}
                    </span>
                    <h2 class="text-lg md:text-xl font-black text-slate-900 tracking-tight">
                        {{ $peminjaman->nama }}
                    </h2>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">Diajukan pada {{ $peminjaman->created_at->translatedFormat('d F Y, H:i') }} WIB</p>
            </div>
        </div>

        <div>
            @if ($peminjaman->status === 'approved_final')
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-emerald-600 text-white shadow-xs">
                    <i class="fa-solid fa-circle-check"></i> Telah Disetujui Final
                </span>
            @elseif ($peminjaman->status === 'approved_1')
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-amber-400 text-slate-900 shadow-xs animate-pulse">
                    <i class="fa-solid fa-signature"></i> Menunggu Persetujuan Anda
                </span>
            @elseif ($peminjaman->status === 'rejected')
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-red-600 text-white shadow-xs">
                    <i class="fa-solid fa-ban"></i> Permohonan Ditolak
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-slate-200 text-slate-700">
                    Menunggu Verifikasi Admin
                </span>
            @endif
        </div>
    </div>

    <!-- BANNER STATUS KEPUTUSAN UTAMA -->
    @if ($peminjaman->status === 'approved_1')
        <div class="p-5 md:p-6 rounded-3xl bg-amber-500 text-slate-900 shadow-md flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-white/20 text-slate-900 flex items-center justify-center text-xl flex-shrink-0 border border-white/30">
                <i class="fa-solid fa-file-signature"></i>
            </div>
            <div>
                <h3 class="font-black text-base md:text-lg tracking-tight">
                    Menunggu Keputusan Persetujuan Final Kepala Sekolah
                </h3>
            </div>
        </div>
    @elseif ($peminjaman->status === 'approved_final')
        <div class="p-5 md:p-6 rounded-3xl bg-emerald-50 border border-emerald-200 text-emerald-900 space-y-2">
            <div class="flex items-center gap-3 font-black text-base md:text-lg text-emerald-800">
                <i class="fa-solid fa-circle-check text-xl text-emerald-600"></i>
                <span>Permohonan Telah Disetujui Secara Final oleh Kepala Sekolah</span>
            </div>
            <p class="text-xs md:text-sm text-emerald-700 leading-relaxed">
                Peminjaman aula ini telah resmi disahkan oleh <strong>{{ $approvalPimpinan?->approver?->name ?? 'Kepala Sekolah' }}</strong> pada {{ $approvalPimpinan?->tanggal_proses ? $approvalPimpinan->tanggal_proses->translatedFormat('d F Y, H:i') : '-' }} WIB. Jadwal pelaksanaan aula telah resmi terkunci dalam agenda sekolah.
            </p>
            @if ($approvalPimpinan && $approvalPimpinan->catatan_approval)
                <div class="pt-2 text-xs text-emerald-800 font-medium">
                    <span class="font-bold">Catatan Pimpinan:</span> {{ $approvalPimpinan->catatan_approval }}
                </div>
            @endif
        </div>
    @elseif ($peminjaman->status === 'rejected')
        <div class="p-5 md:p-6 rounded-3xl bg-red-50 border border-red-200 text-red-900 space-y-2">
            <div class="flex items-center gap-3 font-black text-base md:text-lg text-red-800">
                <i class="fa-solid fa-ban text-xl text-red-600"></i>
                <span>Permohonan Ditolak</span>
            </div>
            <p class="text-xs md:text-sm text-red-700 leading-relaxed">
                Permohonan peminjaman aula ini telah ditolak.
                @if ($approvalPimpinan && $approvalPimpinan->catatan_approval)
                    Alasan: <strong>{{ $approvalPimpinan->catatan_approval }}</strong>
                @elseif ($approvalAdmin && $approvalAdmin->catatan_approval)
                    Alasan: <strong>{{ $approvalAdmin->catatan_approval }}</strong>
                @endif
            </p>
        </div>
    @endif

    <!-- PERINGATAN KONFLIK JADWAL JIKA ADA -->
    @if ($conflictingPeminjaman)
        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-300 text-amber-900 text-xs flex items-start gap-3">
            <i class="fa-solid fa-triangle-exclamation text-amber-600 text-base mt-0.5 flex-shrink-0"></i>
            <div>
                <strong class="font-bold">Peringatan Bentrok Jadwal:</strong>
                Terdapat peminjaman lain yang telah disetujui resmi pada jadwal yang bersinggungan:
                <strong>#{{ $conflictingPeminjaman->id }} - {{ $conflictingPeminjaman->nama }}</strong>
                ({{ $conflictingPeminjaman->tanggal_mulai ? $conflictingPeminjaman->tanggal_mulai->translatedFormat('d M Y H:i') : '' }} s/d {{ $conflictingPeminjaman->tanggal_selesai ? $conflictingPeminjaman->tanggal_selesai->format('H:i') : '' }} WIB).
            </div>
        </div>
    @endif

    <!-- GRID DUA KOLOM: INFORMASI RINCI & PANEL KEPUTUSAN -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- KOLOM KIRI (2/3): INFORMASI LENGKAP -->
        <div class="lg:col-span-2 space-y-6">

            <!-- CARD 1: IDENTITAS PEMOHON -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 figma-card-shadow space-y-4">
                <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-2xl bg-blue-50 text-brand-600 flex items-center justify-center text-lg border border-blue-100 shadow-xs flex-shrink-0">
                        <i class="fa-solid fa-building"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-sm md:text-base tracking-tight">
                            Profil & Data Pemohon
                        </h3>
                        <p class="text-slate-500 text-xs mt-0.5">Identitas instansi penanggung jawab acara</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-slate-400 font-bold uppercase tracking-wider block text-[10px]">Nama Instansi / Pemohon</span>
                        <span class="font-bold text-slate-900 text-sm mt-0.5 block">{{ $peminjaman->nama }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 font-bold uppercase tracking-wider block text-[10px]">Email Kontak</span>
                        <span class="font-bold text-slate-800 text-sm mt-0.5 block">{{ $peminjaman->email_instansi }}</span>
                    </div>

                    <div class="sm:col-span-2 pt-2 border-t border-slate-100">
                        <span class="text-slate-400 font-bold uppercase tracking-wider block text-[10px] mb-1">Catatan Tambahan dari Pemohon</span>
                        <p class="text-slate-700 bg-slate-50 p-3 rounded-xl border border-slate-200/80 leading-relaxed italic">
                            {{ $peminjaman->catatan ?: 'Tidak ada catatan tambahan yang dilampirkan oleh pemohon.' }}
                        </p>
                    </div>

                    <!-- BERKAS SURAT PENGANTAR -->
                    <div class="sm:col-span-2 pt-2 border-t border-slate-100">
                        <span class="text-slate-400 font-bold uppercase tracking-wider block text-[10px] mb-1.5">Berkas Surat Pengantar Pemohon</span>
                        @if ($peminjaman->surat_pengantar)
                            <div class="flex items-center justify-between p-3.5 bg-slate-50 border border-slate-200 rounded-xl">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-red-100 text-red-600 flex items-center justify-center text-sm font-bold flex-shrink-0">
                                        <i class="fa-solid fa-file-pdf"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800 text-xs">Surat Pengantar Resmi</div>
                                        <div class="text-[11px] text-slate-500">Lampiran pengajuan peminjaman aula</div>
                                    </div>
                                </div>
                                <a href="{{ $peminjaman->surat_pengantar_url }}" target="_blank"
                                    class="px-3.5 py-1.5 rounded-lg bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs transition flex items-center gap-1.5 shadow-2xs">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                    <span>Lihat Surat</span>
                                </a>
                            </div>
                        @else
                            <div class="text-slate-400 text-xs italic">
                                Pemohon tidak melampirkan berkas surat pengantar.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- CARD 2: JADWAL & WAKTU PEMAKAIAN AULA -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 figma-card-shadow space-y-4">
                <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg border border-amber-100 shadow-xs flex-shrink-0">
                        <i class="fa-solid fa-calendar-day"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-sm md:text-base tracking-tight">
                            Jadwal Pelaksanaan Peminjaman Aula
                        </h3>
                        <p class="text-slate-500 text-xs mt-0.5">Waktu pemakaian fasilitas aula sekolah</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-1">
                        <span class="text-slate-400 font-bold uppercase text-[10px] block">Waktu Mulai Acara</span>
                        <div class="text-sm font-bold text-slate-900">
                            {{ $peminjaman->tanggal_mulai ? $peminjaman->tanggal_mulai->translatedFormat('l, d F Y') : '-' }}
                        </div>
                        <div class="text-brand-600 font-extrabold text-xs">
                            Pukul {{ $peminjaman->tanggal_mulai ? $peminjaman->tanggal_mulai->format('H:i') : '' }} WIB
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-1">
                        <span class="text-slate-400 font-bold uppercase text-[10px] block">Waktu Selesai Acara</span>
                        <div class="text-sm font-bold text-slate-900">
                            {{ $peminjaman->tanggal_selesai ? $peminjaman->tanggal_selesai->translatedFormat('l, d F Y') : '-' }}
                        </div>
                        <div class="text-slate-600 font-extrabold text-xs">
                            Pukul {{ $peminjaman->tanggal_selesai ? $peminjaman->tanggal_selesai->format('H:i') : '' }} WIB
                        </div>
                    </div>
                </div>
            </div>

            <!-- CARD 3: PAKET PEMINJAMAN & FASILITAS -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 figma-card-shadow space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg border border-purple-100 shadow-xs flex-shrink-0">
                            <i class="fa-solid fa-boxes-packing"></i>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-sm md:text-base tracking-tight">
                                Paket & Fasilitas Aula
                            </h3>
                            <p class="text-slate-500 text-xs mt-0.5">{{ $peminjaman->paketPeminjaman?->nama_paket ?: 'Paket Peminjaman' }}</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-brand-600 bg-blue-50 px-3 py-1 rounded-full border border-blue-100">
                        {{ $peminjaman->paketPeminjaman?->facilities?->count() ?? 0 }} Fasilitas
                    </span>
                </div>

                @if ($peminjaman->paketPeminjaman && $peminjaman->paketPeminjaman->facilities->isNotEmpty())
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        @foreach ($peminjaman->paketPeminjaman->facilities as $fac)
                            <div class="flex items-center gap-2.5 p-3 rounded-xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800">
                                <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
                                <span class="font-medium">{{ $fac->judul }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-slate-400 text-xs italic">Fasilitas standar aula sekolah.</p>
                @endif
            </div>

            <!-- CARD 4: RIWAYAT VERIFIKASI AWAL (ADMIN SARPRAS) -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 figma-card-shadow space-y-4">
                <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg border border-emerald-100 shadow-xs flex-shrink-0">
                        <i class="fa-solid fa-clipboard-check"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-sm md:text-base tracking-tight">
                            Verifikasi Tahap Awal (Admin Sarpras)
                        </h3>
                        <p class="text-slate-500 text-xs mt-0.5">Pemeriksaan teknis ketersediaan & pembayaran</p>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">Status Pemeriksaan Sarpras:</span>
                        @if ($approvalAdmin && $approvalAdmin->status === 'approved')
                            <span class="px-2.5 py-0.5 rounded-full font-bold bg-emerald-100 text-emerald-800 text-[11px] flex items-center gap-1">
                                <i class="fa-solid fa-check text-[9px]"></i> Lolos Verifikasi
                            </span>
                        @elseif ($peminjaman->status === 'approved_1' || $peminjaman->status === 'approved_final')
                            <span class="px-2.5 py-0.5 rounded-full font-bold bg-emerald-100 text-emerald-800 text-[11px] flex items-center gap-1">
                                <i class="fa-solid fa-check text-[9px]"></i> Disetujui Admin Sarpras
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full font-bold bg-slate-200 text-slate-700 text-[11px]">
                                Menunggu Verifikasi
                            </span>
                        @endif
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">Diverifikasi Oleh:</span>
                        <strong class="text-slate-800">{{ $approvalAdmin?->approver?->name ?? 'Tim Admin Sarpras Aula' }}</strong>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">Waktu Verifikasi:</span>
                        <span class="text-slate-700">{{ $approvalAdmin?->tanggal_proses ? $approvalAdmin->tanggal_proses->translatedFormat('d F Y, H:i') . ' WIB' : '-' }}</span>
                    </div>

                    @if ($approvalAdmin && $approvalAdmin->catatan_approval)
                        <div class="pt-2 border-t border-slate-200/60">
                            <span class="text-slate-500 block mb-0.5">Catatan Verifikasi Admin:</span>
                            <p class="text-slate-800 italic bg-white p-2.5 rounded-xl border border-slate-200">
                                "{{ $approvalAdmin->catatan_approval }}"
                            </p>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <!-- KOLOM KANAN (1/3): PANEL KEPUTUSAN KEPALA SEKOLAH & STATUS PEMBAYARAN -->
        <div class="space-y-6">

            <!-- CARD UTAMA KEPUTUSAN KEPALA SEKOLAH -->
            <div class="bg-white rounded-3xl p-6 border-2 {{ $peminjaman->status === 'approved_1' ? 'border-amber-400 ring-4 ring-amber-100' : 'border-slate-100' }} figma-card-shadow space-y-4">
                <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-2xl {{ $peminjaman->status === 'approved_final' ? 'bg-emerald-50 text-emerald-600' : ($peminjaman->status === 'approved_1' ? 'bg-amber-50 text-amber-600' : 'bg-slate-100 text-slate-600') }} flex items-center justify-center text-lg shadow-xs flex-shrink-0">
                        <i class="fa-solid fa-crown"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-sm md:text-base tracking-tight">
                            Keputusan Kepala Sekolah
                        </h3>
                        <p class="text-slate-500 text-xs mt-0.5">Otorisasi persetujuan final</p>
                    </div>
                </div>

                @if ($peminjaman->status === 'approved_1')
                    <div class="space-y-4">
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Permohonan ini telah siap untuk disahkan. Silakan tentukan keputusan Anda:
                        </p>

                        <div class="space-y-2">
                            <!-- Tombol Setuju -->
                            <button type="button" onclick="openModalApproveFinal()"
                                class="w-full py-3 px-4 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs md:text-sm shadow-md transition flex items-center justify-center gap-2 cursor-pointer transform active:scale-98">
                                <i class="fa-solid fa-check text-base"></i>
                                <span>Setujui Permohonan (Final)</span>
                            </button>

                            <!-- Tombol Tolak -->
                            <button type="button" onclick="openModalRejectFinal()"
                                class="w-full py-2.5 px-4 rounded-2xl bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 font-bold text-xs transition flex items-center justify-center gap-2 cursor-pointer">
                                <i class="fa-solid fa-xmark text-sm"></i>
                                <span>Tolak Permohonan</span>
                            </button>
                        </div>
                    </div>
                @elseif ($peminjaman->status === 'approved_final')
                    <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-200 text-center space-y-2 text-xs">
                        <div class="w-12 h-12 rounded-full bg-emerald-600 text-white flex items-center justify-center text-xl mx-auto shadow-sm">
                            <i class="fa-solid fa-stamp"></i>
                        </div>
                        <h4 class="font-black text-emerald-900 text-sm uppercase tracking-wide">
                            Persetujuan Resmi Disahkan
                        </h4>
                        <div class="text-emerald-800 text-[11px] space-y-0.5">
                            <div>Disahkan Oleh: <strong>{{ $approvalPimpinan?->approver?->name ?? 'Kepala Sekolah' }}</strong></div>
                            <div>Tanggal: {{ $approvalPimpinan?->tanggal_proses ? $approvalPimpinan->tanggal_proses->translatedFormat('d M Y H:i') . ' WIB' : '-' }}</div>
                        </div>
                        @if ($approvalPimpinan && $approvalPimpinan->catatan_approval)
                            <div class="p-2.5 bg-white rounded-xl text-emerald-900 border border-emerald-200 text-left italic">
                                "{{ $approvalPimpinan->catatan_approval }}"
                            </div>
                        @endif
                    </div>
                @elseif ($peminjaman->status === 'rejected')
                    <div class="p-4 bg-red-50 rounded-2xl border border-red-200 text-center space-y-2 text-xs">
                        <div class="w-10 h-10 rounded-full bg-red-600 text-white flex items-center justify-center text-base mx-auto">
                            <i class="fa-solid fa-ban"></i>
                        </div>
                        <h4 class="font-bold text-red-900 text-xs uppercase tracking-wide">
                            Permohonan Ditolak
                        </h4>
                        <p class="text-red-700 text-[11px]">
                            Permohonan ini tidak disetujui untuk menggunakan aula sekolah.
                        </p>
                    </div>
                @else
                    <div class="p-4 bg-slate-50 rounded-2xl text-center space-y-1.5 text-xs text-slate-500">
                        <i class="fa-regular fa-clock text-xl block mb-1"></i>
                        <p>Menunggu verifikasi sarpras terlebih dahulu sebelum Anda dapat memberikan persetujuan final.</p>
                    </div>
                @endif
            </div>

            <!-- CARD RINGKASAN PEMBAYARAN -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 figma-card-shadow space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="font-extrabold text-slate-900 text-xs md:text-sm uppercase tracking-wide">
                        Status Pembayaran
                    </h3>
                    @php
                        $stBayar = $peminjaman->pembayaran?->status_pembayaran;
                    @endphp
                    @if ($stBayar === 'lunas')
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">Lunas</span>
                    @elseif ($stBayar === 'partial')
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-100 text-brand-800">DP Terverifikasi</span>
                    @elseif ($stBayar === 'refund_pending')
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-purple-100 text-purple-800">Menunggu Refund</span>
                    @elseif ($stBayar === 'refunded')
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700">Sudah Direfund</span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800">Pending</span>
                    @endif
                </div>

                <div class="space-y-2.5 text-xs">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500">No. Invoice:</span>
                        <span class="font-mono font-bold text-slate-900">{{ $peminjaman->pembayaran?->kode_pembayaran ?: '-' }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-slate-500">Total Biaya Sewa:</span>
                        <span class="font-bold text-slate-900 text-sm">Rp {{ number_format($peminjaman->pembayaran?->total_tagihan ?? 0, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between items-center text-emerald-600">
                        <span>Total Terbayar:</span>
                        <span class="font-bold text-sm">Rp {{ number_format($peminjaman->pembayaran?->total_terbayar ?? 0, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between items-center text-brand-700">
                        <span>Sisa Tagihan:</span>
                        <span class="font-bold text-sm">Rp {{ number_format($peminjaman->pembayaran?->sisa_tagihan ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- PREVIEW BUKTI PEMBAYARAN JIKA ADA -->
                @if ($peminjaman->pembayaran && $peminjaman->pembayaran->details->isNotEmpty())
                    <div class="pt-3 border-t border-slate-100 space-y-2">
                        <span class="text-slate-400 font-bold uppercase text-[10px] block">Bukti Transfer Masuk:</span>
                        @foreach ($peminjaman->pembayaran->details->where('tipe_pembayaran', '!=', 'refund') as $trx)
                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 text-xs space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-slate-800">{{ $trx->label_tipe }}</span>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $trx->status === 'verified' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-700' }}">
                                        {{ $trx->status === 'verified' ? 'Valid' : $trx->status }}
                                    </span>
                                </div>
                                <div class="text-[11px] text-slate-600">
                                    Nominal: <strong>Rp {{ number_format($trx->jumlah_bayar, 0, ',', '.') }}</strong>
                                </div>
                                @if ($trx->bukti_pembayaran)
                                    <a href="{{ $trx->bukti_pembayaran_url }}" target="_blank"
                                        class="inline-flex items-center gap-1 text-[11px] text-brand-600 hover:text-brand-800 font-bold pt-1">
                                        <i class="fa-solid fa-magnifying-glass-plus"></i>
                                        <span>Buka Bukti Transfer</span>
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection

@push('modals')
<!-- ============================================================== -->
<!-- MODAL: APPROVE FINAL KEPALA SEKOLAH -->
<!-- ============================================================== -->
<div id="modalApproveFinal" class="fixed inset-0 !m-0 z-[100] hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl border border-slate-100 overflow-hidden transform transition-all scale-95 duration-200" id="modalApproveFinalBox">
        <!-- HEADER -->
        <div class="p-5 md:p-6 pb-2 md:pb-3 bg-white flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg border border-emerald-100/80 shadow-xs flex-shrink-0">
                    <i class="fa-solid fa-stamp"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base md:text-lg tracking-tight">Persetujuan Final Peminjaman</h3>
                    <p class="text-slate-500 text-xs mt-0.5">Pengesahan resmi peminjaman aula oleh Kepala Sekolah</p>
                </div>
            </div>
            <button type="button" onclick="closeModalApproveFinal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition cursor-pointer" title="Tutup Modal">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form action="{{ route('kepala-sekolah.peminjaman.approve', $peminjaman->id) }}" method="POST" class="p-5 md:p-6 space-y-4">
            @csrf

            <div class="p-4 bg-emerald-50/70 border border-emerald-200/80 rounded-2xl text-xs text-emerald-900 space-y-1">
                <div class="font-bold flex items-center gap-1.5 text-emerald-800">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>Konfirmasi Pengesahan Final</span>
                </div>
                <p class="leading-relaxed text-emerald-700">
                    Dengan menyetujui, peminjaman aula atas nama <strong>{{ $peminjaman->nama }}</strong> pada tanggal <strong>{{ $peminjaman->tanggal_mulai ? $peminjaman->tanggal_mulai->translatedFormat('d F Y') : '-' }}</strong> resmi disahkan oleh Kepala Sekolah dan terkunci dalam kalender agenda sekolah.
                </p>
            </div>

            <div>
                <label for="catatan_approval" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                    Catatan Pengesahan Kepala Sekolah (Opsional)
                </label>
                <textarea name="catatan_approval" id="catatan_approval" rows="3"
                    placeholder="Masukkan instruksi atau catatan pimpinan bagi pemohon..."
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition"></textarea>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeModalApproveFinal()"
                    class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs md:text-sm font-semibold transition cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs md:text-sm font-bold shadow-sm transition flex items-center gap-1.5 cursor-pointer">
                    <i class="fa-solid fa-stamp text-xs"></i>
                    <span>Ya, Sahkan & Setujui Final</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL: REJECT FINAL KEPALA SEKOLAH -->
<!-- ============================================================== -->
<div id="modalRejectFinal" class="fixed inset-0 !m-0 z-[100] hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl border border-slate-100 overflow-hidden transform transition-all scale-95 duration-200" id="modalRejectFinalBox">
        <!-- HEADER -->
        <div class="p-5 md:p-6 pb-2 md:pb-3 bg-white flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center text-lg border border-red-100/80 shadow-xs flex-shrink-0">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base md:text-lg tracking-tight">Tolak Permohonan Peminjaman</h3>
                    <p class="text-slate-500 text-xs mt-0.5">Wajib memberikan keterangan alasan penolakan</p>
                </div>
            </div>
            <button type="button" onclick="closeModalRejectFinal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition cursor-pointer" title="Tutup Modal">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form action="{{ route('kepala-sekolah.peminjaman.reject', $peminjaman->id) }}" method="POST" class="p-5 md:p-6 space-y-4">
            @csrf

            <div class="p-4 bg-red-50/70 border border-red-200/80 rounded-2xl text-xs text-red-900 space-y-1">
                <div class="font-bold flex items-center gap-1.5 text-red-700">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>Konsekuensi Penolakan</span>
                </div>
                <p class="leading-relaxed text-red-800">
                    Permohonan akan ditolak. Jika pemohon telah melakukan pembayaran yang valid, sistem akan otomatis mengarahkan ke proses pengembalian dana (refund) ke rekening pemohon.
                </p>
            </div>

            <div>
                <label for="alasan_penolakan" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                    Alasan Penolakan <span class="text-red-500">* (Wajib diisi)</span>
                </label>
                <textarea name="alasan_penolakan" id="alasan_penolakan" rows="3" required minlength="5"
                    placeholder="Tuliskan alasan penolakan secara jelas (misal: Aula akan dipergunakan untuk kegiatan kedinasan sekolah)..."
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition"></textarea>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeModalRejectFinal()"
                    class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs md:text-sm font-semibold transition cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs md:text-sm font-bold shadow-sm transition flex items-center gap-1.5 cursor-pointer">
                    <i class="fa-solid fa-ban text-xs"></i>
                    <span>Tolak Permohonan</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endpush

@push('scripts')
<script>
    // Modal Approve Final
    function openModalApproveFinal() {
        const modal = document.getElementById('modalApproveFinal');
        const box = document.getElementById('modalApproveFinalBox');
        if (!modal || !box) return;
        document.body.classList.add('overflow-hidden');
        modal.classList.remove('hidden');
        setTimeout(() => {
            box.classList.remove('scale-95');
            box.classList.add('scale-100');
            const input = document.getElementById('catatan_approval');
            if (input) input.focus();
        }, 10);
    }

    function closeModalApproveFinal() {
        const modal = document.getElementById('modalApproveFinal');
        const box = document.getElementById('modalApproveFinalBox');
        if (!modal || !box) return;
        box.classList.remove('scale-100');
        box.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }, 150);
    }

    // Modal Reject Final
    function openModalRejectFinal() {
        const modal = document.getElementById('modalRejectFinal');
        const box = document.getElementById('modalRejectFinalBox');
        if (!modal || !box) return;
        document.body.classList.add('overflow-hidden');
        modal.classList.remove('hidden');
        setTimeout(() => {
            box.classList.remove('scale-95');
            box.classList.add('scale-100');
            const input = document.getElementById('alasan_penolakan');
            if (input) input.focus();
        }, 10);
    }

    function closeModalRejectFinal() {
        const modal = document.getElementById('modalRejectFinal');
        const box = document.getElementById('modalRejectFinalBox');
        if (!modal || !box) return;
        box.classList.remove('scale-100');
        box.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }, 150);
    }

    // Keydown ESC listener
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModalApproveFinal();
            closeModalRejectFinal();
        }
    });

    // Backdrop click listener
    ['modalApproveFinal', 'modalRejectFinal'].forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.addEventListener('click', function(event) {
                if (event.target === modal) {
                    if (modalId === 'modalApproveFinal') closeModalApproveFinal();
                    if (modalId === 'modalRejectFinal') closeModalRejectFinal();
                }
            });
        }
    });
</script>
@endpush
