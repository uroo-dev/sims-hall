@extends('Admin.layout.app')

@section('title', 'Detail Peminjaman Aula - ' . ($pembayaran?->kode_pembayaran ?: 'INV-' . $peminjaman->id))
@section('page_title', 'Detail Peminjaman Aula')

@section('content')
<div class="space-y-6">

    <!-- TOP BREADCRUMB & ACTION HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                <a href="{{ route('admin.peminjaman.index') }}" class="hover:text-brand-600 transition flex items-center gap-1">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    <span>Kembali ke Daftar</span>
                </a>
                <span>&bull;</span>
                <span class="text-slate-800 font-bold font-mono">{{ $pembayaran?->kode_pembayaran ?: 'INV-' . $peminjaman->id }}</span>
            </div>
            <h1 class="text-xl md:text-2xl font-black text-slate-900 tracking-tight mt-1 flex items-center gap-3">
                <span>{{ $peminjaman->nama }}</span>
                @if (in_array($peminjaman->status, ['approved_1', 'approved_final']))
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <i class="fa-solid fa-circle-check mr-1"></i> Disetujui
                    </span>
                @elseif ($peminjaman->status === 'cancelled')
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-300">
                        <i class="fa-solid fa-ban mr-1"></i> Dibatalkan Pemohon
                    </span>
                @elseif ($peminjaman->status === 'rejected')
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                        <i class="fa-solid fa-circle-xmark mr-1"></i> Ditolak
                    </span>
                @else
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                        <i class="fa-solid fa-clock mr-1"></i> Menunggu Persetujuan
                    </span>
                @endif
            </h1>
        </div>

        <!-- TOMBOL AKSI CEPAT APPROVE & REJECT DI HEADER (JIKA MASIH PENDING) -->
        @php
            $pendingPayments = $pembayaran ? $pembayaran->details->where('tipe_pembayaran', '!=', 'refund')->where('status', 'pending') : collect();
            $verifiedPayments = $pembayaran ? $pembayaran->details->where('tipe_pembayaran', '!=', 'refund')->where('status', 'verified') : collect();
            $hasPendingPayment = $pendingPayments->isNotEmpty();
            $hasVerifiedPayment = $pembayaran && ($pembayaran->total_terbayar > 0 || $verifiedPayments->isNotEmpty());
            $isPaymentFailed = $pembayaran && ($pembayaran->status_pembayaran === 'rejected' || ($pembayaran->details->where('tipe_pembayaran', '!=', 'refund')->isNotEmpty() && $pembayaran->details->where('tipe_pembayaran', '!=', 'refund')->every(fn($d) => $d->status === 'rejected')));
            $isNeedSetCustomPrice = $peminjaman->is_custom && (!$peminjaman->harga_custom || !$pembayaran || (float) $pembayaran->total_tagihan <= 0);
        @endphp
        @if (!in_array($peminjaman->status, ['rejected', 'cancelled']))
            @if(!auth()->user()->isSuperAdmin())
                <div class="flex items-center gap-2">
                    @if ($hasPendingPayment)
                        <button type="button" onclick="alert('Harap verifikasi bukti pembayaran pemohon terlebih dahulu (apakah valid atau ditolak) sebelum menolak permohonan peminjaman.')"
                            class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-500 border border-slate-300 rounded-xl text-xs md:text-sm font-bold shadow-2xs transition flex items-center gap-2 cursor-pointer"
                            title="Verifikasi pembayaran terlebih dahulu">
                            <i class="fa-solid fa-ban text-xs"></i>
                            <span>Tolak Pengajuan</span>
                        </button>
                    @else
                        <button type="button" onclick="openModalReject()"
                            class="px-4 py-2 bg-white hover:bg-red-50 text-red-600 border border-red-200 rounded-xl text-xs md:text-sm font-bold shadow-2xs transition flex items-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-ban text-xs"></i>
                            <span>Tolak Pengajuan</span>
                        </button>
                    @endif

                    @if (!in_array($peminjaman->status, ['approved_1', 'approved_final']))
                        @if (isset($conflictingApproved) && $conflictingApproved)
                            <button type="button" onclick="alert('Tidak dapat menyetujui peminjaman: Jadwal bentrok dengan peminjaman yang sudah disetujui (#{{ $conflictingApproved->id }} - {{ $conflictingApproved->nama }}).')"
                                title="Jadwal peminjaman bentrok dengan peminjaman lain yang sudah disetujui"
                                class="px-5 py-2 bg-slate-200 text-slate-500 cursor-not-allowed rounded-xl text-xs md:text-sm font-bold shadow-xs transition flex items-center gap-2">
                                <i class="fa-solid fa-triangle-exclamation text-amber-500 text-xs"></i>
                                <span>Jadwal Bentrok</span>
                            </button>
                        @elseif ($isNeedSetCustomPrice)
                            <button type="button" onclick="document.getElementById('formTetapkanHargaCustom')?.scrollIntoView({behavior: 'smooth'})"
                                title="Tetapkan harga sewa paket custom terlebih dahulu sebelum persetujuan"
                                class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs md:text-sm font-bold shadow-xs transition flex items-center gap-2 cursor-pointer">
                                <i class="fa-solid fa-tag text-xs"></i>
                                <span>Tetapkan Harga Dulu</span>
                            </button>
                        @elseif ($hasPendingPayment)
                            <button type="button" onclick="alert('Tidak dapat menyetujui peminjaman: Terdapat bukti transfer pembayaran yang belum diverifikasi. Harap verifikasi bukti pembayaran pemohon terlebih dahulu.')"
                                title="Harap verifikasi bukti pembayaran terlebih dahulu"
                                class="px-5 py-2 bg-slate-200 text-slate-500 hover:bg-slate-300 rounded-xl text-xs md:text-sm font-bold shadow-xs transition flex items-center gap-2 cursor-pointer">
                                <i class="fa-solid fa-clock text-amber-500 text-xs"></i>
                                <span>Verifikasi Pembayaran Dulu</span>
                            </button>
                        @elseif (!$hasVerifiedPayment)
                            <button type="button" onclick="alert('Tidak dapat menyetujui peminjaman: Pembayaran belum diverifikasi benar (valid). Pastikan pemohon telah membayar dan bukti pembayaran telah diverifikasi valid sebelum menyetujui peminjaman.')"
                                title="Pembayaran belum diverifikasi valid"
                                class="px-5 py-2 bg-slate-200 text-slate-500 hover:bg-slate-300 rounded-xl text-xs md:text-sm font-bold shadow-xs transition flex items-center gap-2 cursor-pointer">
                                <i class="fa-solid fa-shield-halved text-slate-400 text-xs"></i>
                                <span>Belum Ada Pembayaran Valid</span>
                            </button>
                        @else
                            <button type="button" onclick="openModalApprove()"
                                class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs md:text-sm font-bold shadow-xs transition flex items-center gap-2 cursor-pointer">
                                <i class="fa-solid fa-check text-xs"></i>
                                <span>Setujui (Approve)</span>
                            </button>
                        @endif
                    @endif
                </div>
            @else
                <span class="inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-amber-50 text-amber-800 border border-amber-200 text-xs md:text-sm font-semibold rounded-xl">
                    <i class="fa-solid fa-lock text-amber-600 text-xs"></i>
                    <span>Mode Baca (Super Admin)</span>
                </span>
            @endif
        @endif
    </div>


    <!-- PERINGATAN JADWAL BENTROK -->
    @if (isset($conflictingApproved) && $conflictingApproved)
        <div class="bg-amber-50 border-2 border-amber-300 text-amber-900 rounded-2xl p-4 flex items-start gap-3 shadow-xs">
            <div class="w-8 h-8 rounded-full bg-amber-500 text-white flex items-center justify-center font-bold text-sm shrink-0 mt-0.5">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div class="text-xs md:text-sm space-y-1">
                <p class="font-bold text-amber-900">
                    Peringatan: Jadwal Bentrok dengan Peminjaman yang Sudah Disetujui!
                </p>
                <p class="text-amber-800">
                    Jadwal pengajuan ini (<span class="font-semibold">{{ \Carbon\Carbon::parse($peminjaman->tanggal_mulai)->translatedFormat('d M Y H:i') }} &ndash; {{ \Carbon\Carbon::parse($peminjaman->tanggal_selesai)->translatedFormat('d M Y H:i') }} WIB</span>) bertabrakan dengan peminjaman aula yang sudah disetujui:
                    <a href="{{ route('admin.peminjaman.show', $conflictingApproved->id) }}" class="font-bold text-brand-700 underline hover:text-brand-900">
                        #{{ $conflictingApproved->id }} - {{ $conflictingApproved->nama }}
                    </a>
                    (<span class="font-medium">{{ \Carbon\Carbon::parse($conflictingApproved->tanggal_mulai)->translatedFormat('d M Y H:i') }} &ndash; {{ \Carbon\Carbon::parse($conflictingApproved->tanggal_selesai)->translatedFormat('H:i') }} WIB</span>).
                </p>
                <p class="text-[11px] text-amber-700 font-medium">
                    * Pengajuan ini tidak dapat disetujui sebelum peminjaman yang bentrok dibatalkan atau jadwal disesuaikan.
                </p>
            </div>
        </div>
    @endif

    <!-- BANNER PERINGATAN PEMBATALAN OLEH PEMOHON -->
    @if ($peminjaman->status === 'cancelled')
        <div class="bg-slate-50 border-2 border-slate-300 text-slate-800 rounded-2xl p-4 md:p-5 flex items-start gap-3.5 shadow-xs">
            <div class="w-9 h-9 rounded-xl bg-slate-700 text-white flex items-center justify-center font-bold text-base flex-shrink-0">
                <i class="fa-solid fa-ban"></i>
            </div>
            <div class="space-y-1 text-xs md:text-sm">
                <h4 class="font-black text-slate-900">Pengajuan Peminjaman Telah Dibatalkan</h4>
                <p class="text-slate-600">
                    Pengajuan peminjaman aula ini telah dibatalkan oleh pemohon.
                    @if ($hasPendingPayment)
                        <strong class="text-amber-700 block mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i> Perhatian Admin: Terdapat bukti transfer pembayaran pemohon yang menunggu verifikasi di bawah. Jika pembayaran diverifikasi valid, dana akan otomatis dialihkan ke alur pengembalian (refund).</strong>
                    @elseif ($pembayaran && in_array($pembayaran->status_pembayaran, ['refund_pending', 'refunded']))
                        <strong class="text-purple-700 block mt-1"><i class="fa-solid fa-hand-holding-dollar mr-1"></i> Pembayaran sebesar Rp {{ number_format($pembayaran->total_refund, 0, ',', '.') }} telah diverifikasi valid dan dalam proses pengembalian dana (refund).</strong>
                    @endif
                </p>
            </div>
        </div>
    @endif

    <!-- FLASH MESSAGES -->
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

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-2xl p-4 space-y-1 shadow-xs">
            <div class="font-bold text-xs md:text-sm flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>Terdapat kesalahan pengisian data:</span>
            </div>
            <ul class="list-disc list-inside text-xs space-y-0.5">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- MAIN TWO-COLUMN CONTENT GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- KOLOM KIRI (SPAN 2): DETAIL PEMINJAMAN, FASILITAS, SURAT PENGANTAR, PERSETUJUAN -->
        <div class="lg:col-span-2 space-y-6">

            <!-- CARD 1: INFORMASI PEMINJAM & INSTANSI -->
            <div class="bg-white rounded-2xl p-6 md:p-7 border border-slate-100 figma-card-shadow space-y-5">
                <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-brand-600 flex items-center justify-center font-bold text-base flex-shrink-0">
                        <i class="fa-solid fa-building-columns"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm md:text-base uppercase tracking-wide">Informasi Pemohon & Instansi</h3>
                        <p class="text-xs text-slate-500">Data lengkap instansi dan kontak pemohon sewa aula</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs md:text-sm">
                    <div class="p-3.5 bg-slate-50 rounded-xl space-y-1">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Nama Lengkap / Instansi</span>
                        <div class="font-bold text-slate-900 text-sm md:text-base">{{ $peminjaman->nama }}</div>
                    </div>

                    <div class="p-3.5 bg-slate-50 rounded-xl space-y-1">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Email Resmi Instansi</span>
                        <div class="font-semibold text-slate-800 flex items-center gap-2">
                            <i class="fa-regular fa-envelope text-brand-600"></i>
                            <a href="mailto:{{ $peminjaman->email_instansi }}" class="hover:underline text-brand-700">{{ $peminjaman->email_instansi }}</a>
                        </div>
                    </div>

                    <div class="p-3.5 bg-slate-50 rounded-xl space-y-1 sm:col-span-2">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Catatan / Deskripsi Acara</span>
                        <p class="text-slate-700 leading-relaxed italic">
                            "{{ $peminjaman->catatan ?: 'Tidak ada catatan tambahan yang diberikan oleh pemohon.' }}"
                        </p>
                    </div>
                </div>
            </div>

            <!-- CARD 2: JADWAL & PAKET FASILITAS -->
            @php
                $paket = $peminjaman->paketPeminjaman;
                $isCustom = (bool) $peminjaman->is_custom;
                $fasilitasList = $peminjaman->daftar_fasilitas ?? collect();
            @endphp
            <div class="bg-white rounded-2xl p-6 md:p-7 border border-slate-100 figma-card-shadow space-y-5">
                <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl {{ $isCustom ? 'bg-purple-50 text-purple-600' : 'bg-emerald-50 text-emerald-600' }} flex items-center justify-center font-bold text-base flex-shrink-0">
                        <i class="fa-solid {{ $isCustom ? 'fa-sliders' : 'fa-boxes-packing' }}"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm md:text-base uppercase tracking-wide">
                            {{ $isCustom ? 'Paket Custom & Jadwal Pelaksanaan' : 'Paket Sewa & Jadwal Pelaksanaan' }}
                        </h3>
                        <p class="text-xs text-slate-500">Rincian paket, durasi waktu sewa, dan fasilitas yang termasuk</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs md:text-sm">
                    <!-- Paket -->
                    <div class="p-4 {{ $isCustom ? 'bg-purple-50/50 border border-purple-100' : 'bg-emerald-50/40 border border-emerald-100' }} rounded-2xl space-y-1">
                        <span class="text-[10px] font-bold {{ $isCustom ? 'text-purple-700' : 'text-emerald-600' }} uppercase tracking-wider block">
                            Paket Peminjaman
                        </span>
                        <div class="text-base md:text-lg font-black text-slate-800">
                            {{ $peminjaman->nama_paket ?? ($paket?->nama_paket ?: 'Paket Aula') }}
                        </div>
                        <div class="text-xs font-semibold text-slate-500 flex items-center gap-1.5">
                            <span>Tipe:</span>
                            @if ($isCustom)
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-purple-800">Custom (Pilihan Fasilitas)</span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700">{{ ucfirst($paket?->kategori ?: '-') }}</span>
                            @endif
                        </div>
                        <div class="text-sm font-black {{ $isCustom ? 'text-purple-700' : 'text-emerald-700' }} mt-2">
                            @if ($isCustom)
                                @if ($peminjaman->harga_custom)
                                    Rp {{ number_format($peminjaman->harga_custom, 0, ',', '.') }}
                                @else
                                    <span class="text-amber-600 font-bold text-xs flex items-center gap-1">
                                        <i class="fa-solid fa-hourglass-half text-[11px]"></i>
                                        <span>Belum Ditetapkan Admin</span>
                                    </span>
                                @endif
                            @else
                                Rp {{ number_format($paket?->harga ?? 0, 0, ',', '.') }}
                            @endif
                        </div>
                    </div>

                    <!-- Jadwal Waktu -->
                    <div class="p-4 bg-slate-50 border border-slate-200/70 rounded-2xl space-y-2">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Waktu Sewa Aula</span>
                        <div class="space-y-1">
                            <div class="flex items-center gap-2 text-slate-800 font-semibold text-xs">
                                <i class="fa-regular fa-calendar-check text-brand-600"></i>
                                <span>Mulai: {{ $peminjaman->tanggal_mulai ? $peminjaman->tanggal_mulai->translatedFormat('l, d F Y, H:i') : '-' }} WIB</span>
                            </div>
                            <div class="flex items-center gap-2 text-slate-800 font-semibold text-xs">
                                <i class="fa-regular fa-calendar-xmark text-red-500"></i>
                                <span>Selesai: {{ $peminjaman->tanggal_selesai ? $peminjaman->tanggal_selesai->translatedFormat('l, d F Y, H:i') : '-' }} WIB</span>
                            </div>
                        </div>
                        @if ($peminjaman->tanggal_mulai && $peminjaman->tanggal_selesai)
                            <div class="pt-1 text-[11px] text-slate-500">
                                Durasi sewa: <strong>{{ $peminjaman->tanggal_mulai->diffInHours($peminjaman->tanggal_selesai) }} Jam</strong>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Fasilitas Yang Termasuk -->
                <div class="space-y-2 pt-2">
                    <div class="flex items-center justify-between">
                        <span class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            {{ $isCustom ? 'Fasilitas yang Diajukan Pemohon:' : 'Fasilitas yang Didapatkan:' }}
                        </span>
                        @if ($isCustom)
                            <span class="text-[11px] font-bold text-purple-700 bg-purple-50 px-2.5 py-0.5 rounded-full border border-purple-200">
                                {{ $fasilitasList->count() }} Fasilitas Dipilih
                            </span>
                        @endif
                    </div>

                    @if ($fasilitasList->isNotEmpty())
                        <div class="flex flex-wrap gap-2">
                            @foreach ($fasilitasList as $fac)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 text-slate-800 rounded-xl text-xs font-medium border border-slate-200/60">
                                    <i class="fa-solid fa-check {{ $isCustom ? 'text-purple-600' : 'text-emerald-600' }} text-[10px]"></i>
                                    <span>{{ $fac->judul }}</span>
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-slate-400 italic">Tidak ada fasilitas khusus yang terlampir pada pengajuan ini.</p>
                    @endif
                </div>

                <!-- FORM PENETAPAN / STATUS HARGA PAKET CUSTOM OLEH ADMIN AULA -->
                @if ($isCustom && !in_array($peminjaman->status, ['rejected', 'cancelled']))
                    @if ($peminjaman->hasVerifiedPayment())
                        <!-- HARGA TERKUNCI KARENA SUDAH ADA PEMBAYARAN TERVERIFIKASI -->
                        <div class="mt-4 p-5 bg-slate-50 rounded-2xl border-2 border-slate-200 space-y-3">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-bold text-sm shadow-xs">
                                        <i class="fa-solid fa-lock"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-black text-slate-900 text-xs md:text-sm uppercase tracking-wide">
                                            Harga Paket Custom Terkunci
                                        </h4>
                                        <p class="text-[11px] text-slate-500">
                                            Harga sewa tidak dapat diubah karena sudah ada pembayaran yang terverifikasi oleh admin.
                                        </p>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-lg text-xs font-bold flex items-center gap-1 flex-shrink-0">
                                    <i class="fa-solid fa-shield-check text-[10px]"></i> Terverifikasi
                                </span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2 text-xs">
                                <div class="p-3 bg-white rounded-xl border border-slate-200/70">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase block">Total Harga Sewa</span>
                                    <span class="text-sm font-black text-slate-900">Rp {{ number_format($peminjaman->harga_custom ?? 0, 0, ',', '.') }}</span>
                                </div>
                                <div class="p-3 bg-white rounded-xl border border-slate-200/70">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase block">Total Terbayar</span>
                                    <span class="text-sm font-black text-emerald-600">Rp {{ number_format($pembayaran?->total_terbayar ?? 0, 0, ',', '.') }}</span>
                                </div>
                                <div class="p-3 bg-white rounded-xl border border-slate-200/70">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase block">Sisa Tagihan</span>
                                    <span class="text-sm font-black text-slate-700">Rp {{ number_format($pembayaran?->sisa_tagihan ?? 0, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    @elseif (!auth()->user()->isSuperAdmin())
                        <div id="formTetapkanHargaCustom" class="mt-4 p-5 bg-gradient-to-br from-amber-50/80 to-purple-50/50 rounded-2xl border-2 border-amber-300 space-y-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-amber-500 text-white flex items-center justify-center font-bold text-sm shadow-xs">
                                        <i class="fa-solid fa-tag"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-black text-slate-900 text-xs md:text-sm uppercase tracking-wide">
                                            {{ $peminjaman->harga_custom ? 'Perbarui Harga Paket Custom' : 'Tetapkan Harga Paket Custom' }}
                                        </h4>
                                        <p class="text-[11px] text-slate-500">
                                            Admin aula memverifikasi fasilitas terpilih dan menentukan total tagihan sewa.
                                        </p>
                                    </div>
                                </div>
                                @if ($peminjaman->harga_custom)
                                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-lg text-xs font-bold flex items-center gap-1 flex-shrink-0">
                                        <i class="fa-solid fa-check text-[10px]"></i> Harga Aktif
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-amber-100 text-amber-900 rounded-lg text-xs font-bold flex items-center gap-1 flex-shrink-0 animate-pulse">
                                        <i class="fa-solid fa-clock text-[10px]"></i> Wajib Ditetapkan
                                    </span>
                                @endif
                            </div>

                            <form action="{{ route('admin.peminjaman.set-harga-custom', $peminjaman->id) }}" method="POST" class="space-y-4 pt-1">
                                @csrf
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label for="harga" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                                            Total Biaya Sewa (Rp) <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" name="harga" id="harga" required min="10000" step="1000"
                                            value="{{ old('harga', $peminjaman->harga_custom ? (int) $peminjaman->harga_custom : '') }}"
                                            placeholder="Contoh: 1500000"
                                            class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs md:text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">
                                        <p class="text-[10px] text-slate-500 mt-1">Total harga sewa keseluruhan untuk fasilitas yang diajukan.</p>
                                    </div>

                                    <div>
                                        <label for="harga_dp" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                                            Nominal Minimum DP (Rp)
                                        </label>
                                        <input type="number" name="harga_dp" id="harga_dp" min="0" step="1000"
                                            value="{{ old('harga_dp') }}"
                                            placeholder="Kosongkan jika otomatis 30% dari total sewa"
                                            class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition font-medium">
                                        <p class="text-[10px] text-slate-500 mt-1">Opsional: Jika dikosongkan, nominal DP dihitung 30% dari total harga sewa.</p>
                                    </div>
                                </div>

                                <div>
                                    <label for="catatan_harga" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                                        Catatan / Penjelasan Harga untuk Pemohon
                                    </label>
                                    <input type="text" name="catatan_harga" id="catatan_harga" maxlength="1000"
                                        value="{{ old('catatan_harga') }}"
                                        placeholder="Contoh: Termasuk biaya kebersihan, 4 mic wireless, sound 5000W, dan genset"
                                        class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition font-medium">
                                </div>

                                <div class="pt-1 flex items-center justify-end gap-2">
                                    <button type="submit"
                                        class="px-5 py-2.5 bg-amber-600 hover:bg-amber-700 active:scale-95 text-white text-xs md:text-sm font-bold rounded-xl shadow-xs transition flex items-center gap-2 cursor-pointer">
                                        <i class="fa-solid fa-paper-plane text-xs"></i>
                                        <span>{{ $peminjaman->harga_custom ? 'Simpan Perubahan Harga' : 'Tetapkan Harga & Terbitkan Tagihan' }}</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                @endif
            </div>

            <!-- CARD 3: BERKAS SURAT PENGANTAR / PENGAJUAN -->
            <div class="bg-white rounded-2xl p-6 md:p-7 border border-slate-100 figma-card-shadow space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-base flex-shrink-0">
                            <i class="fa-solid fa-file-contract"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-sm md:text-base uppercase tracking-wide">Berkas Surat Pengantar / Pengajuan</h3>
                            <p class="text-xs text-slate-500">Dokumen permohonan resmi dari instansi atau pihak peminjam</p>
                        </div>
                    </div>

                    @if ($peminjaman->surat_pengantar)
                        <a href="{{ $peminjaman->surat_pengantar_url }}" target="_blank"
                            class="px-3.5 py-1.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold shadow-2xs transition flex items-center gap-1.5">
                            <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                            <span>Buka Berkas</span>
                        </a>
                    @endif
                </div>

                @if ($peminjaman->surat_pengantar)
                    <div class="p-4 bg-purple-50/30 border border-purple-100 rounded-2xl flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-12 h-12 rounded-xl bg-white border border-purple-200 flex items-center justify-center text-purple-600 text-xl flex-shrink-0">
                                <i class="fa-regular fa-file-pdf"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs md:text-sm font-bold text-slate-800 truncate">Surat Pengantar Peminjaman Aula</p>
                                <p class="text-[11px] text-slate-500 truncate">{{ basename($peminjaman->surat_pengantar) }}</p>
                            </div>
                        </div>

                        <a href="{{ $peminjaman->surat_pengantar_url }}" download
                            class="px-3.5 py-2 bg-white hover:bg-purple-50 border border-purple-200 text-purple-700 rounded-xl text-xs font-bold transition flex items-center gap-1.5 flex-shrink-0">
                            <i class="fa-solid fa-download text-xs"></i>
                            <span>Unduh</span>
                        </a>
                    </div>
                @else
                    <div class="p-6 bg-slate-50 rounded-2xl text-center text-slate-400 text-xs">
                        <i class="fa-regular fa-file text-2xl mb-1 block"></i>
                        <span>Pemohon tidak mengunggah dokumen surat pengantar tambahan.</span>
                    </div>
                @endif
            </div>

            <!-- CARD 4: RIWAYAT PERSETUJUAN & CATATAN ADMIN -->
            <div class="bg-white rounded-2xl p-6 md:p-7 border border-slate-100 figma-card-shadow space-y-4">
                <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-base flex-shrink-0">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm md:text-base uppercase tracking-wide">Riwayat Persetujuan / Catatan Sistem</h3>
                        <p class="text-xs text-slate-500">Log approval atau penolakan pengajuan aula</p>
                    </div>
                </div>

                @if ($peminjaman->persetujuans->isNotEmpty())
                    <div class="space-y-3">
                        @foreach ($peminjaman->persetujuans as $ps)
                            <div class="p-4 rounded-2xl border {{ $ps->status === 'approved' ? 'bg-emerald-50/30 border-emerald-100' : ($ps->status === 'rejected' ? 'bg-red-50/30 border-red-100' : 'bg-slate-50 border-slate-200') }}">
                                <div class="flex items-center justify-between text-xs mb-1.5">
                                    <div class="font-bold {{ $ps->status === 'approved' ? 'text-emerald-700' : ($ps->status === 'rejected' ? 'text-red-700' : 'text-slate-700') }}">
                                        @if ($ps->status === 'approved')
                                            <i class="fa-solid fa-check-circle mr-1"></i> Disetujui oleh: {{ $ps->approver?->name ?: 'Admin Aula' }}
                                        @elseif ($ps->status === 'rejected')
                                            <i class="fa-solid fa-times-circle mr-1"></i> Ditolak oleh: {{ $ps->approver?->name ?: 'Admin Aula' }}
                                        @else
                                            <i class="fa-regular fa-clock mr-1"></i> Menunggu review
                                        @endif
                                        <span class="text-[10px] text-slate-400 font-normal ml-1">({{ ucfirst($ps->level) }})</span>
                                    </div>
                                    <span class="text-[11px] text-slate-400">
                                        {{ $ps->tanggal_proses ? $ps->tanggal_proses->format('d/m/Y H:i') : $ps->created_at->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-700 leading-relaxed font-normal">
                                    <strong>Catatan:</strong> {{ $ps->catatan_approval ?: 'Tidak ada catatan.' }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-400 italic">Belum ada catatan persetujuan yang tercatat.</p>
                @endif
            </div>

        </div>

        <!-- KOLOM KANAN (SPAN 1): TAGIHAN, BUKTI PEMBAYARAN, REFUND BOX & AKSI -->
        <div class="space-y-6">

            <!-- CARD 5: RINGKASAN TAGIHAN & PEMBAYARAN -->
            <div class="bg-white rounded-2xl p-6 border border-slate-100 figma-card-shadow space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Status Tagihan</span>
                    @php
                        $stBayar = $pembayaran?->status_pembayaran ?? 'pending';
                    @endphp
                    @if ($stBayar === 'lunas')
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">Lunas</span>
                    @elseif ($stBayar === 'partial')
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-100 text-brand-800">DP Terverifikasi</span>
                    @elseif ($stBayar === 'refund_pending')
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-purple-100 text-purple-800 animate-pulse">Menunggu Refund</span>
                    @elseif ($stBayar === 'refunded')
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700">Sudah Direfund</span>
                    @elseif ($stBayar === 'rejected')
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-red-100 text-red-800">Pembayaran Ditolak</span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800">Pending</span>
                    @endif
                </div>

                <div class="space-y-2 text-xs">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500">Nomor Invoice:</span>
                        <span class="font-mono font-bold text-slate-900">{{ $pembayaran?->kode_pembayaran ?: '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500">Total Biaya Sewa:</span>
                        @if ($isCustom && (!$pembayaran || (float) $pembayaran->total_tagihan <= 0))
                            <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-amber-100 text-amber-900 border border-amber-200">
                                Belum Ditetapkan
                            </span>
                        @else
                            <span class="font-bold text-slate-900 text-sm">Rp {{ number_format($pembayaran?->total_tagihan ?? 0, 0, ',', '.') }}</span>
                        @endif
                    </div>
                    <div class="flex justify-between items-center text-emerald-600">
                        <span>Total Terbayar:</span>
                        <span class="font-bold text-sm">Rp {{ number_format($pembayaran?->total_terbayar ?? 0, 0, ',', '.') }}</span>
                    </div>
                    @php
                        $isDitolakOrRefund = in_array($pembayaran?->status_pembayaran, ['refund_pending', 'refunded', 'rejected', 'hangus']) || $peminjaman->status === 'rejected';
                        $sisaTagihanAdmin = $isDitolakOrRefund ? 0 : ($pembayaran?->sisa_tagihan ?? 0);
                    @endphp
                    <div class="flex justify-between items-center text-brand-700">
                        <span>Sisa Tagihan:</span>
                        @if ($isCustom && (!$pembayaran || (float) $pembayaran->total_tagihan <= 0))
                            <span class="text-xs font-bold text-slate-400 italic">Menunggu Harga</span>
                        @else
                            <span class="font-bold text-sm">Rp {{ number_format($sisaTagihanAdmin, 0, ',', '.') }}</span>
                        @endif
                    </div>

                    @if ($pembayaran && $pembayaran->total_refund > 0)
                        <div class="flex justify-between items-center text-purple-700 pt-1 border-t border-slate-100">
                            <span class="font-bold">Total Refund:</span>
                            <span class="font-black text-sm">Rp {{ number_format($pembayaran->total_refund, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    @if ($pembayaran)
                        <div class="pt-2 border-t border-slate-100 text-[11px] text-slate-500">
                            @if ($pembayaran->status_pembayaran === 'lunas' || $pembayaran->isLunas())
                                <div class="flex items-center gap-1.5 text-emerald-600 font-semibold">
                                    <i class="fa-solid fa-circle-check text-xs"></i>
                                    <span>Tagihan Telah Lunas (Tidak Ada Batas Waktu)</span>
                                </div>
                            @elseif ($isDitolakOrRefund)
                                <div class="flex items-center gap-1.5 text-purple-700 font-semibold">
                                    <i class="fa-solid fa-hand-holding-dollar text-xs"></i>
                                    <span>Peminjaman Ditolak (Tidak Ada Batas Waktu Pembayaran)</span>
                                </div>
                            @elseif ($pembayaran->status_pembayaran === 'partial' || ($pembayaran->total_terbayar > 0 && $pembayaran->sisa_tagihan > 0))
                                <div class="space-y-0.5">
                                    <div class="flex items-center gap-1 text-brand-700 font-medium">
                                        <i class="fa-solid fa-calendar-check text-[11px]"></i>
                                        <span>Batas Waktu Pelunasan (Final):</span>
                                    </div>
                                    <div class="font-bold text-slate-800 text-xs">
                                        {{ $pembayaran->jatuh_tempo_pelunasan ? $pembayaran->jatuh_tempo_pelunasan->translatedFormat('d M Y, H:i') . ' WIB' : '-' }}
                                        @if ($peminjaman->tanggal_mulai && $config?->jatuh_tempo_pelunasan_jam)
                                            <span class="text-[10px] font-normal text-slate-500">({{ $config->jatuh_tempo_pelunasan_jam }} jam sebelum Hari H)</span>
                                        @endif
                                    </div>
                                </div>
                            @elseif ($pembayaran->jatuh_tempo_dp)
                                <div class="space-y-0.5">
                                    <div class="flex items-center gap-1 text-slate-600 font-medium">
                                        <i class="fa-regular fa-clock text-[11px]"></i>
                                        <span>Batas Waktu Transfer DP:</span>
                                    </div>
                                    <div class="font-bold text-slate-800 text-xs">
                                        {{ $pembayaran->jatuh_tempo_dp->translatedFormat('d M Y, H:i') }} WIB
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- CARD 6: BUKTI TRANSAKSI PEMBAYARAN DARI PEMOHON -->
            <div id="cardBuktiTransfer" class="bg-white rounded-2xl p-6 border border-slate-100 figma-card-shadow space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800 text-xs md:text-sm uppercase tracking-wide">
                        Bukti Transfer Pemohon
                    </h3>
                    <span class="text-[11px] font-bold text-brand-600">
                        {{ $pembayaran ? $pembayaran->details->where('tipe_pembayaran', '!=', 'refund')->count() : 0 }} Transaksi
                    </span>
                </div>

                @if ($pembayaran && $pembayaran->details->where('tipe_pembayaran', '!=', 'refund')->isNotEmpty())
                    <div class="space-y-4">
                        @foreach ($pembayaran->details->where('tipe_pembayaran', '!=', 'refund') as $trx)
                            <div class="p-4 bg-slate-50 rounded-2xl border {{ $trx->status === 'pending' ? 'border-amber-300 ring-2 ring-amber-100' : 'border-slate-200/80' }} space-y-3">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-bold text-slate-800">{{ $trx->label_tipe }}</span>
                                    @if ($trx->status === 'verified')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 flex items-center gap-1">
                                            <i class="fa-solid fa-check text-[9px]"></i> Valid
                                        </span>
                                    @elseif ($trx->status === 'rejected')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-800 flex items-center gap-1">
                                            <i class="fa-solid fa-xmark text-[9px]"></i> Ditolak / Gagal
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 animate-pulse flex items-center gap-1">
                                            <i class="fa-regular fa-clock text-[9px]"></i> Menunggu Verifikasi
                                        </span>
                                    @endif
                                </div>

                                <div class="text-xs space-y-1 text-slate-600">
                                    <div>Nominal: <strong class="text-slate-900 font-bold">Rp {{ number_format($trx->jumlah_bayar, 0, ',', '.') }}</strong></div>
                                    <div>Tujuan: {{ $trx->bank_tujuan }}</div>
                                    <div>Pengirim: {{ $trx->bank_pengirim }} - {{ $trx->norek_pengirim }} (a.n {{ $trx->atas_nama_pengirim }})</div>
                                    <div class="text-[11px] text-slate-400">Waktu Bayar: {{ $trx->tanggal_bayar ? $trx->tanggal_bayar->format('d/m/Y H:i') : '-' }}</div>
                                    @if ($trx->catatan)
                                        <div class="text-[11px] text-red-600 font-medium italic">Catatan: {{ $trx->catatan }}</div>
                                    @endif
                                </div>

                                <!-- PREVIEW BUKTI TRANSFER -->
                                @if ($trx->bukti_pembayaran)
                                    <div class="pt-2">
                                        <a href="{{ $trx->bukti_pembayaran_url }}" target="_blank"
                                            class="block group relative rounded-xl overflow-hidden border border-slate-200 bg-white">
                                            <img src="{{ $trx->bukti_pembayaran_url }}" alt="Bukti Transfer"
                                                class="w-full h-36 object-cover group-hover:scale-105 transition duration-300">
                                            <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-xs font-bold gap-1">
                                                <i class="fa-solid fa-magnifying-glass-plus"></i>
                                                <span>Lihat Bukti Asli</span>
                                            </div>
                                        </a>
                                    </div>
                                @endif

                                <!-- AKSI VERIFIKASI / TOLAK BUKTI TRANSFER -->
                                @if(!auth()->user()->isSuperAdmin())
                                    @if ($trx->status === 'pending')
                                        <div class="pt-3 border-t border-slate-200/60 flex items-stretch gap-2">
                                            <form action="{{ route('admin.peminjaman.verifikasi-pembayaran', [$peminjaman->id, $trx->id]) }}" method="POST" class="flex-1 m-0 flex">
                                                @csrf
                                                <button type="submit"
                                                    class="w-full h-9 px-3 bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer whitespace-nowrap">
                                                    <i class="fa-solid fa-circle-check text-xs"></i>
                                                    <span>Verifikasi benar</span>
                                                </button>
                                            </form>

                                            <button type="button" onclick="openModalRejectPayment('{{ $trx->id }}')"
                                                class="flex-1 h-9 px-3 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer whitespace-nowrap">
                                                <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                                                <span>Tolak Bukti</span>
                                            </button>
                                        </div>
                                    @elseif ($trx->status === 'verified' && !in_array($peminjaman->status, ['rejected', 'cancelled']))
                                        <div class="pt-2 border-t border-slate-200/60 flex items-center justify-between">
                                            <span class="text-[11px] text-emerald-700 font-bold flex items-center gap-1">
                                                <i class="fa-solid fa-circle-check"></i> Pembayaran Terverifikasi Benar
                                            </span>
                                            <button type="button" onclick="openModalRejectPayment('{{ $trx->id }}')"
                                                class="text-xs text-red-600 hover:text-red-800 font-semibold transition flex items-center gap-1 cursor-pointer">
                                                <i class="fa-solid fa-triangle-exclamation text-[10px]"></i>
                                                <span>Tolak Pembayaran</span>
                                            </button>
                                        </div>
                                    @elseif ($trx->status === 'verified')
                                        <div class="pt-2 border-t border-slate-200/60 flex items-center justify-between">
                                            <span class="text-[11px] text-emerald-700 font-bold flex items-center gap-1">
                                                <i class="fa-solid fa-circle-check"></i> Pembayaran Terverifikasi Benar
                                            </span>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-6 bg-slate-50 rounded-2xl text-center text-slate-400 text-xs">
                        <i class="fa-regular fa-clock text-2xl mb-1 block"></i>
                        <span>Pemohon belum mengunggah bukti transfer pembayaran.</span>
                    </div>
                @endif
            </div>

            <!-- CARD 7: PROSES REFUND (PENGEMBALIAN DANA DEPOSIT/LUNAS) -->
            @if ($pembayaran && in_array($pembayaran->status_pembayaran, ['refund_pending', 'refunded']))
                <div class="bg-purple-50/50 rounded-2xl p-6 border-2 border-purple-200 figma-card-shadow space-y-4">
                    <div class="flex items-center gap-2.5 pb-2 border-b border-purple-200/60">
                        <div class="w-8 h-8 rounded-lg bg-purple-600 text-white flex items-center justify-center text-sm font-bold flex-shrink-0">
                            <i class="fa-solid fa-hand-holding-dollar"></i>
                        </div>
                        <div>
                            <h3 class="font-black text-purple-900 text-xs md:text-sm uppercase tracking-wide">Pengembalian Dana (Refund)</h3>
                            <p class="text-[11px] text-purple-600">Nominal Wajib Dikembalikan: <strong>Rp {{ number_format($pembayaran->total_refund, 0, ',', '.') }}</strong></p>
                        </div>
                    </div>

                    @php
                        $refund = $refundDetail ?: $pembayaran->details->firstWhere('tipe_pembayaran', 'refund');
                    @endphp

                    @if ($refund && $refund->norek_tujuan)
                        <!-- Data Rekening Pengembalian yang Diisi Pemohon -->
                        <div class="bg-white p-4 rounded-xl border border-purple-100 space-y-2 text-xs">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Rekening Tujuan Pemohon:</span>
                            <div class="font-bold text-slate-900 text-sm">{{ $refund->bank_tujuan }}</div>
                            <div class="font-mono font-bold text-purple-800 text-base tracking-wider">{{ $refund->norek_tujuan }}</div>
                            <div class="text-slate-600">Atas Nama: <strong>{{ $refund->atas_nama_pengirim }}</strong></div>
                        </div>

                        <!-- Cek Status Bukti Transfer Refund dari Admin -->
                        @if ($refund->bukti_pembayaran)
                            <div class="bg-emerald-50 p-4 rounded-xl border border-emerald-200 space-y-2 text-xs">
                                <div class="flex items-center justify-between font-bold text-emerald-800">
                                    <span>Bukti Transfer Refund Terkirim</span>
                                    @if ($pembayaran->status_pembayaran === 'refunded')
                                        <span class="px-2 py-0.5 rounded-full bg-emerald-600 text-white text-[10px]">Telah Dikonfirmasi Pemohon</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full bg-amber-500 text-white text-[10px]">Menunggu Konfirmasi</span>
                                    @endif
                                </div>
                                <div class="pt-1">
                                    <a href="{{ $refund->bukti_pembayaran_url }}" target="_blank"
                                        class="block rounded-lg overflow-hidden border border-emerald-200">
                                        <img src="{{ $refund->bukti_pembayaran_url }}" alt="Bukti Transfer Refund" class="w-full h-32 object-cover">
                                    </a>
                                </div>
                                <p class="text-[11px] text-emerald-700">
                                    Dikirim pada {{ $refund->tanggal_bayar ? $refund->tanggal_bayar->format('d/m/Y H:i') : '-' }} WIB
                                </p>
                            </div>
                        @else
                            @if(!auth()->user()->isSuperAdmin())
                                <!-- Formulir Admin Upload Bukti Transfer Refund -->
                                <form action="{{ route('admin.peminjaman.upload-refund', $peminjaman->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3 pt-1">
                                    @csrf
                                    <div class="space-y-1">
                                        <label class="block text-xs font-bold text-slate-700 uppercase">Bank Asal Sekolah</label>
                                        <input type="text" name="bank_pengirim" value="{{ $config?->bank_utama ?: 'Bank Jateng' }}"
                                            class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs focus:ring-2 focus:ring-purple-500">
                                    </div>

                                    <div class="space-y-1">
                                        <label class="block text-xs font-bold text-slate-700 uppercase">Unggah Bukti Transfer Refund <span class="text-red-500">*</span></label>
                                        <input type="file" name="bukti_refund" required accept="image/jpeg,image/png,image/webp"
                                            class="w-full text-xs text-slate-500 file:mr-2 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-purple-600 file:text-white hover:file:bg-purple-700">
                                    </div>

                                    <div class="space-y-1">
                                        <label class="block text-xs font-bold text-slate-700 uppercase">Catatan Tambahan (Opsional)</label>
                                        <textarea name="catatan" rows="2" placeholder="Catatan ke pemohon..."
                                            class="w-full bg-white border border-slate-200 rounded-xl p-2.5 text-xs focus:ring-2 focus:ring-purple-500"></textarea>
                                    </div>

                                    <button type="submit"
                                        class="w-full py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5">
                                        <i class="fa-solid fa-paper-plane text-xs"></i>
                                        <span>Kirim Bukti Transfer Refund</span>
                                    </button>
                                </form>
                            @else
                                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 text-center space-y-1 text-xs text-slate-500">
                                    <i class="fa-solid fa-lock text-slate-400 block mb-1"></i>
                                    <p class="font-semibold text-slate-700">Mode Baca (Super Admin)</p>
                                    <p class="text-[11px]">Pengunggahan bukti transfer refund diproses oleh Admin Aula.</p>
                                </div>
                            @endif
                        @endif
                    @else
                        <!-- Pemohon Belum Mengisi Rekening -->
                        <div class="p-4 bg-white rounded-xl border border-purple-100 text-center space-y-1.5 text-xs">
                            <i class="fa-solid fa-hourglass-half text-purple-600 text-xl block mb-1"></i>
                            <p class="font-bold text-slate-800">Menunggu Data Rekening Pemohon</p>
                            <p class="text-slate-500 text-[11px]">
                                Pemohon telah diminta mengisi rekening pengembalian dana melalui customer panel. Setelah terisi, Anda dapat mentransfer dan mengunggah buktinya di sini.
                            </p>
                        </div>
                    @endif
                </div>
            @endif

        </div>

    </div>

</div>
@endsection

@push('modals')
@if(!auth()->user()->isSuperAdmin())
<!-- ============================================================== -->
<!-- MODAL: APPROVE PENGAJUAN -->
<!-- ============================================================== -->
<div id="modalApprove" class="fixed inset-0 !m-0 z-[100] hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl border border-slate-100 overflow-hidden transform transition-all scale-95 duration-200" id="modalApproveBox">
        <!-- HEADER -->
        <div class="p-5 md:p-6 pb-2 md:pb-3 bg-white flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg border border-emerald-100/80 shadow-xs flex-shrink-0">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base md:text-lg tracking-tight">Setujui Permohonan Peminjaman</h3>
                    <p class="text-slate-500 text-xs mt-0.5">Konfirmasi persetujuan jadwal dan peminjaman aula</p>
                </div>
            </div>
            <button type="button" onclick="closeModalApprove()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition cursor-pointer" title="Tutup Modal">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form action="{{ route('admin.peminjaman.approve', $peminjaman->id) }}" method="POST" class="p-5 md:p-6 space-y-4">
            @csrf

            <div class="p-4 bg-emerald-50/70 border border-emerald-200/80 rounded-2xl text-xs text-emerald-900 space-y-1">
                <div class="font-bold flex items-center gap-1.5 text-emerald-800">
                    <i class="fa-solid fa-circle-info"></i>
                    <span>Konfirmasi Pengesahan Jadwal</span>
                </div>
                <p class="leading-relaxed text-emerald-700">
                    Peminjaman aula pada tanggal yang diajukan akan resmi dikunci dan disetujui. Pastikan Anda telah memeriksa dan memverifikasi bukti pembayaran yang masuk.
                </p>
            </div>

            <div>
                <label for="catatan_approval" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                    Catatan Approval (Opsional)
                </label>
                <textarea name="catatan_approval" id="catatan_approval" rows="3"
                    placeholder="Masukkan catatan atau instruksi khusus bagi pemohon..."
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition"></textarea>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeModalApprove()"
                    class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs md:text-sm font-semibold transition cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs md:text-sm font-bold shadow-sm transition flex items-center gap-1.5 cursor-pointer">
                    <i class="fa-solid fa-check text-xs"></i>
                    <span>Ya, Setujui Permohonan</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL: REJECT PENGAJUAN (WAJIB ALASAN & MEKANISME REFUND) -->
<!-- ============================================================== -->
<div id="modalReject" class="fixed inset-0 !m-0 z-[100] hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl border border-slate-100 overflow-hidden transform transition-all scale-95 duration-200" id="modalRejectBox">
        <!-- HEADER -->
        <div class="p-5 md:p-6 pb-2 md:pb-3 bg-white flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center text-lg border border-red-100/80 shadow-xs flex-shrink-0">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base md:text-lg tracking-tight">Tolak Permohonan Peminjaman</h3>
                    <p class="text-slate-500 text-xs mt-0.5">Wajib mengisi alasan penolakan permohonan</p>
                </div>
            </div>
            <button type="button" onclick="closeModalReject()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition cursor-pointer" title="Tutup Modal">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form action="{{ route('admin.peminjaman.reject', $peminjaman->id) }}" method="POST" class="p-5 md:p-6 space-y-4">
            @csrf

            @if ($hasVerifiedPayment)
                <div class="p-4 bg-purple-50/70 border border-purple-200/80 rounded-2xl text-xs text-purple-900 space-y-1.5">
                    <div class="font-bold flex items-center gap-1.5 text-purple-700">
                        <i class="fa-solid fa-hand-holding-dollar"></i>
                        <span>Status Pembayaran: Terverifikasi Benar (Valid)</span>
                    </div>
                    <p class="leading-relaxed text-purple-800">
                        Karena pemohon telah melakukan pembayaran yang diverifikasi benar (<strong>Rp {{ number_format($pembayaran->total_terbayar ?: $verifiedPayments->sum('jumlah_bayar'), 0, ',', '.') }}</strong>), penolakan permohonan ini akan <strong>melanjutkan ke proses pengembalian dana (refund)</strong> ke rekening pemohon.
                    </p>
                </div>
            @elseif ($isPaymentFailed)
                <div class="p-4 bg-red-50/70 border border-red-200/80 rounded-2xl text-xs text-red-900 space-y-1.5">
                    <div class="font-bold flex items-center gap-1.5 text-red-700">
                        <i class="fa-solid fa-ban"></i>
                        <span>Status Pembayaran: Gagal / Ditolak</span>
                    </div>
                    <p class="leading-relaxed text-red-800">
                        Karena status pembayaran gagal atau ditolak, permohonan peminjaman ini akan ditolak dan <strong>proses refund TIDAK akan dilakukan</strong>.
                    </p>
                </div>
            @else
                <div class="p-4 bg-slate-50 border border-slate-200/80 rounded-2xl text-xs text-slate-700 space-y-1.5">
                    <div class="font-bold flex items-center gap-1.5 text-slate-800">
                        <i class="fa-solid fa-circle-info text-slate-500"></i>
                        <span>Status Pembayaran: Belum Ada Pembayaran Masuk</span>
                    </div>
                    <p class="leading-relaxed text-slate-600">
                        Pemohon belum melakukan pembayaran yang valid. Penolakan ini akan membatalkan peminjaman dan proses refund tidak dilakukan.
                    </p>
                </div>
            @endif

            <div>
                <label for="alasan_penolakan" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                    Alasan Penolakan <span class="text-red-500">* (Wajib diisi)</span>
                </label>
                <textarea name="alasan_penolakan" id="alasan_penolakan" rows="3" required minlength="5"
                    placeholder="Tuliskan alasan penolakan secara jelas (misal: Aula akan digunakan untuk agenda internal sekolah)..."
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition"></textarea>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeModalReject()"
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

<!-- ============================================================== -->
<!-- MODAL: REJECT PEMBAYARAN DEPOSIT (TRANSFER ULANG DENGAN DEADLINE BARU) -->
<!-- ============================================================== -->
<div id="modalRejectPayment" class="fixed inset-0 !m-0 z-[100] hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl border border-slate-100 overflow-hidden transform transition-all scale-95 duration-200" id="modalRejectPaymentBox">
        <!-- HEADER -->
        <div class="p-5 md:p-6 pb-2 md:pb-3 bg-white flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg border border-amber-100/80 shadow-xs flex-shrink-0">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base md:text-lg tracking-tight">Tolak Bukti Pembayaran</h3>
                    <p class="text-slate-500 text-xs mt-0.5">Status pembayaran akan diubah menjadi Gagal / Ditolak</p>
                </div>
            </div>
            <button type="button" onclick="closeModalRejectPayment()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition cursor-pointer" title="Tutup Modal">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form action="{{ route('admin.peminjaman.reject-pembayaran', $peminjaman->id) }}" method="POST" class="p-5 md:p-6 space-y-4">
            @csrf
            <input type="hidden" name="detail_id" id="reject_detail_id" value="">

            <div class="p-4 bg-amber-50/70 border border-amber-200/80 rounded-2xl text-xs text-amber-900 space-y-1">
                <div class="font-bold flex items-center gap-1.5 text-amber-800">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>Konsekuensi Penolakan Bukti</span>
                </div>
                <p class="leading-relaxed text-amber-800">
                    Pemohon akan diminta mentransfer ulang dengan batas waktu pembayaran baru (<strong>{{ $config?->jatuh_tempo_dp_jam ?: 24 }} jam</strong>). Jika nantinya permohonan ditolak saat status pembayaran masih gagal, maka <strong>refund tidak akan dilakukan</strong>.
                </p>
            </div>

            <div>
                <label for="alasan_penolakan_pembayaran" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                    Alasan Penolakan Pembayaran <span class="text-red-500">*</span>
                </label>
                <textarea name="alasan_penolakan" id="alasan_penolakan_pembayaran" rows="3" required minlength="5"
                    placeholder="Contoh: Bukti transfer buram / nominal tidak sesuai / dana belum masuk mutasi..."
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition"></textarea>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeModalRejectPayment()"
                    class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs md:text-sm font-semibold transition cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs md:text-sm font-bold shadow-sm transition flex items-center gap-1.5 cursor-pointer">
                    <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                    <span>Tolak Pembayaran</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endpush

@push('scripts')
<script>
    // Modal Approve Handlers
    function openModalApprove() {
        const modal = document.getElementById('modalApprove');
        const box = document.getElementById('modalApproveBox');
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

    function closeModalApprove() {
        const modal = document.getElementById('modalApprove');
        const box = document.getElementById('modalApproveBox');
        if (!modal || !box) return;
        box.classList.remove('scale-100');
        box.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }, 150);
    }

    // Modal Reject Handlers
    function openModalReject() {
        const modal = document.getElementById('modalReject');
        const box = document.getElementById('modalRejectBox');
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

    function closeModalReject() {
        const modal = document.getElementById('modalReject');
        const box = document.getElementById('modalRejectBox');
        if (!modal || !box) return;
        box.classList.remove('scale-100');
        box.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }, 150);
    }

    // Modal Reject Payment Handlers
    function openModalRejectPayment(detailId = '') {
        const inputDetail = document.getElementById('reject_detail_id');
        if (inputDetail) {
            inputDetail.value = detailId || '';
        }
        const modal = document.getElementById('modalRejectPayment');
        const box = document.getElementById('modalRejectPaymentBox');
        if (!modal || !box) return;
        document.body.classList.add('overflow-hidden');
        modal.classList.remove('hidden');
        setTimeout(() => {
            box.classList.remove('scale-95');
            box.classList.add('scale-100');
            const input = document.getElementById('alasan_penolakan_pembayaran');
            if (input) input.focus();
        }, 10);
    }

    function closeModalRejectPayment() {
        const modal = document.getElementById('modalRejectPayment');
        const box = document.getElementById('modalRejectPaymentBox');
        if (!modal || !box) return;
        box.classList.remove('scale-100');
        box.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }, 150);
    }

    // Close modal on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModalApprove();
            closeModalReject();
            closeModalRejectPayment();
        }
    });

    // Close modal on click outside box
    ['modalApprove', 'modalReject', 'modalRejectPayment'].forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.addEventListener('click', function(event) {
                if (event.target === modal) {
                    if (modalId === 'modalApprove') closeModalApprove();
                    if (modalId === 'modalReject') closeModalReject();
                    if (modalId === 'modalRejectPayment') closeModalRejectPayment();
                }
            });
        }
    });
</script>
@endif
@endpush
