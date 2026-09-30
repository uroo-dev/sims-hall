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
        @if ($peminjaman->status !== 'rejected')
            <div class="flex items-center gap-2">
                <button type="button" onclick="openModalReject()"
                    class="px-4 py-2 bg-white hover:bg-red-50 text-red-600 border border-red-200 rounded-xl text-xs md:text-sm font-bold shadow-2xs transition flex items-center gap-2">
                    <i class="fa-solid fa-ban text-xs"></i>
                    <span>Tolak Pengajuan</span>
                </button>
                @if (!in_array($peminjaman->status, ['approved_1', 'approved_final']))
                    @if (isset($conflictingApproved) && $conflictingApproved)
                        <button type="button" onclick="alert('Tidak dapat menyetujui peminjaman: Jadwal bentrok dengan peminjaman yang sudah disetujui (#{{ $conflictingApproved->id }} - {{ $conflictingApproved->nama }}).')"
                            title="Jadwal peminjaman bentrok dengan peminjaman lain yang sudah disetujui"
                            class="px-5 py-2 bg-slate-200 text-slate-500 cursor-not-allowed rounded-xl text-xs md:text-sm font-bold shadow-xs transition flex items-center gap-2">
                            <i class="fa-solid fa-triangle-exclamation text-amber-500 text-xs"></i>
                            <span>Jadwal Bentrok</span>
                        </button>
                    @else
                        <button type="button" onclick="openModalApprove()"
                            class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs md:text-sm font-bold shadow-xs transition flex items-center gap-2">
                            <i class="fa-solid fa-check text-xs"></i>
                            <span>Setujui (Approve)</span>
                        </button>
                    @endif
                @endif
            </div>
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
            @endphp
            <div class="bg-white rounded-2xl p-6 md:p-7 border border-slate-100 figma-card-shadow space-y-5">
                <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-base flex-shrink-0">
                        <i class="fa-solid fa-boxes-packing"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm md:text-base uppercase tracking-wide">Paket Sewa & Jadwal Pelaksanaan</h3>
                        <p class="text-xs text-slate-500">Rincian paket, durasi waktu sewa, dan fasilitas yang termasuk</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs md:text-sm">
                    <!-- Paket -->
                    <div class="p-4 bg-emerald-50/40 border border-emerald-100 rounded-2xl space-y-1">
                        <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider block">Paket Peminjaman</span>
                        <div class="text-base md:text-lg font-black text-slate-800">{{ $paket?->nama_paket ?: 'Paket Aula' }}</div>
                        <div class="text-xs font-semibold text-slate-500">Kategori: {{ ucfirst($paket?->kategori ?: '-') }}</div>
                        <div class="text-sm font-black text-emerald-700 mt-2">
                            Rp {{ number_format($paket?->harga ?? 0, 0, ',', '.') }}
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
                    <span class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Fasilitas yang Didapatkan:</span>
                    @if ($paket && $paket->facilities->isNotEmpty())
                        <div class="flex flex-wrap gap-2">
                            @foreach ($paket->facilities as $fac)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 text-slate-800 rounded-xl text-xs font-medium">
                                    <i class="fa-solid fa-check text-emerald-600 text-[10px]"></i>
                                    <span>{{ $fac->name }}</span>
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-slate-400 italic">Tidak ada fasilitas khusus yang terlampir pada paket ini.</p>
                    @endif
                </div>
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
                        <span class="font-bold text-slate-900 text-sm">Rp {{ number_format($pembayaran?->total_tagihan ?? 0, 0, ',', '.') }}</span>
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
                        <span class="font-bold text-sm">Rp {{ number_format($sisaTagihanAdmin, 0, ',', '.') }}</span>
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
            <div class="bg-white rounded-2xl p-6 border border-slate-100 figma-card-shadow space-y-4">
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
                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-3">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-bold text-slate-800">{{ $trx->label_tipe }}</span>
                                    @if ($trx->status === 'verified')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Valid</span>
                                    @elseif ($trx->status === 'rejected')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-800">Ditolak</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">Pending Review</span>
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

                                <!-- AKSI TOLAK BUKTI TRANSFER INI JIKA TIDAK VALID -->
                                @if ($trx->status === 'pending' || ($trx->status === 'verified' && $peminjaman->status !== 'rejected'))
                                    <div class="pt-2 border-t border-slate-200/60">
                                        <button type="button" onclick="openModalRejectPayment()"
                                            class="w-full py-2 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5">
                                            <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                                            <span>Tolak Bukti Pembayaran</span>
                                        </button>
                                    </div>
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

<!-- ============================================================== -->
<!-- MODAL: APPROVE PENGAJUAN -->
<!-- ============================================================== -->
<div id="modalApprove" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
    <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl border border-slate-100 p-6 space-y-5">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg flex-shrink-0">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <h3 class="font-black text-slate-900 text-base">Setujui Permohonan Peminjaman?</h3>
                <p class="text-xs text-slate-500">Peminjaman aula akan disetujui dan bukti pembayaran diverifikasi.</p>
            </div>
        </div>

        <form action="{{ route('admin.peminjaman.approve', $peminjaman->id) }}" method="POST" class="space-y-4">
            @csrf
            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-700 uppercase">Catatan Approval (Opsional)</label>
                <textarea name="catatan_approval" rows="3" placeholder="Masukkan catatan atau instruksi bagi pemohon..."
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs md:text-sm focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="closeModalApprove()"
                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-xs transition flex items-center gap-1.5">
                    <i class="fa-solid fa-check"></i>
                    <span>Ya, Setujui Permohonan</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL: REJECT PENGAJUAN (WAJIB ALASAN & MEKANISME REFUND) -->
<!-- ============================================================== -->
<div id="modalReject" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
    <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl border border-slate-100 p-6 space-y-5">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center text-lg flex-shrink-0">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <h3 class="font-black text-slate-900 text-base">Tolak Permohonan Peminjaman</h3>
                <p class="text-xs text-slate-500">Anda wajib mengisi keterangan alasan penolakan permohonan.</p>
            </div>
        </div>

        @if ($pembayaran && ($pembayaran->total_terbayar > 0 || $pembayaran->details->whereIn('status', ['verified', 'pending'])->whereIn('tipe_pembayaran', ['dp', 'lunas_langsung', 'pelunasan'])->isNotEmpty()))
            <div class="p-3.5 bg-amber-50 border border-amber-200 rounded-2xl text-xs text-amber-900 space-y-1">
                <div class="font-bold flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-info text-amber-600"></i>
                    <span>Perhatian: Pembayaran Telah Tercatat!</span>
                </div>
                <p class="leading-relaxed">
                    Karena pemohon telah melakukan pembayaran, status pembayaran akan otomatis diubah menjadi <strong>Refund Pending</strong>. Anda akan diminta untuk mengembalikan dana (refund) sebesar nominal yang telah dibayar setelah permohonan ditolak.
                </p>
            </div>
        @endif

        <form action="{{ route('admin.peminjaman.reject', $peminjaman->id) }}" method="POST" class="space-y-4">
            @csrf
            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-700 uppercase">
                    Alasan Penolakan <span class="text-red-500">* (Wajib diisi)</span>
                </label>
                <textarea name="alasan_penolakan" rows="3" required minlength="5"
                    placeholder="Tuliskan alasan penolakan secara jelas (misal: Aula akan digunakan untuk agenda internal sekolah)..."
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs md:text-sm focus:bg-white focus:ring-2 focus:ring-red-500 focus:outline-none"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="closeModalReject()"
                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-bold shadow-xs transition flex items-center gap-1.5">
                    <i class="fa-solid fa-ban"></i>
                    <span>Tolak Permohonan</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL: REJECT PEMBAYARAN DEPOSIT (TRANSFER ULANG DENGAN DEADLINE BARU) -->
<!-- ============================================================== -->
<div id="modalRejectPayment" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
    <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl border border-slate-100 p-6 space-y-5">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg flex-shrink-0">
                <i class="fa-solid fa-receipt"></i>
            </div>
            <div>
                <h3 class="font-black text-slate-900 text-base">Tolak Bukti Pembayaran Deposit</h3>
                <p class="text-xs text-slate-500">Status pembayaran akan diubah menjadi Rejected dan pemohon diminta transfer ulang.</p>
            </div>
        </div>

        <form action="{{ route('admin.peminjaman.reject-pembayaran', $peminjaman->id) }}" method="POST" class="space-y-4">
            @csrf
            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-700 uppercase">
                    Alasan Penolakan Pembayaran <span class="text-red-500">*</span>
                </label>
                <textarea name="alasan_penolakan" rows="3" required minlength="5"
                    placeholder="Contoh: Bukti transfer buram / nominal tidak sesuai / dana belum masuk mutasi..."
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs md:text-sm focus:bg-white focus:ring-2 focus:ring-amber-500 focus:outline-none"></textarea>
            </div>

            <div class="p-3 bg-amber-50 rounded-xl text-[11px] text-amber-800 leading-relaxed">
                Pemohon akan diberikan tenggat waktu pembayaran baru (<strong>{{ $config?->jatuh_tempo_dp_jam ?: 24 }} jam</strong>). Jika melewati tenggat waktu tersebut, sistem secara otomatis mengubah status peminjaman menjadi Rejected.
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="closeModalRejectPayment()"
                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-bold shadow-xs transition flex items-center gap-1.5">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>Tolak Pembayaran</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModalApprove() {
        document.getElementById('modalApprove').classList.remove('hidden');
    }
    function closeModalApprove() {
        document.getElementById('modalApprove').classList.add('hidden');
    }

    function openModalReject() {
        document.getElementById('modalReject').classList.remove('hidden');
    }
    function closeModalReject() {
        document.getElementById('modalReject').classList.add('hidden');
    }

    function openModalRejectPayment() {
        document.getElementById('modalRejectPayment').classList.remove('hidden');
    }
    function closeModalRejectPayment() {
        document.getElementById('modalRejectPayment').classList.add('hidden');
    }
</script>
@endsection
