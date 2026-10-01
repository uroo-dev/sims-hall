@extends('Admin.layout.app')

@section('title', 'Daftar Peminjaman Aula - SMKN 2 Karanganyar')
@section('page_title', 'Daftar Peminjaman Aula')

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

    <!-- ROW 1: KARTU METRIK STATISTIK -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 md:gap-4">
        <!-- 1. Total Peminjaman -->
        <div class="bg-white rounded-2xl p-4 md:p-5 border border-slate-100 figma-card-shadow flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-blue-50 text-brand-600 flex items-center justify-center text-lg flex-shrink-0">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Masuk</p>
                <h4 class="text-lg md:text-xl font-black text-slate-800">{{ number_format($stats['total']) }}</h4>
            </div>
        </div>

        <!-- 2. Menunggu Approval -->
        <div class="bg-white rounded-2xl p-4 md:p-5 border border-slate-100 figma-card-shadow flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg flex-shrink-0">
                <i class="fa-solid fa-hourglass-half"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Menunggu</p>
                <h4 class="text-lg md:text-xl font-black text-amber-600">{{ number_format($stats['pending']) }}</h4>
            </div>
        </div>

        <!-- 3. Disetujui (Approved) -->
        <div class="bg-white rounded-2xl p-4 md:p-5 border border-slate-100 figma-card-shadow flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg flex-shrink-0">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Disetujui</p>
                <h4 class="text-lg md:text-xl font-black text-emerald-600">{{ number_format($stats['approved']) }}</h4>
            </div>
        </div>

        <!-- 4. Ditolak (Rejected) -->
        <div class="bg-white rounded-2xl p-4 md:p-5 border border-slate-100 figma-card-shadow flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center text-lg flex-shrink-0">
                <i class="fa-solid fa-circle-xmark"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Ditolak</p>
                <h4 class="text-lg md:text-xl font-black text-red-600">{{ number_format($stats['rejected']) }}</h4>
            </div>
        </div>

        <!-- 5. Butuh Refund (Refund Pending) -->
        <div class="bg-white rounded-2xl p-4 md:p-5 border border-slate-100 figma-card-shadow flex items-center gap-3.5 col-span-2 md:col-span-1">
            <div class="w-11 h-11 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg flex-shrink-0">
                <i class="fa-solid fa-hand-holding-dollar"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Perlu Refund</p>
                <h4 class="text-lg md:text-xl font-black text-purple-600">{{ number_format($stats['refund_pending']) }}</h4>
            </div>
        </div>
    </div>

    <!-- ROW 2: FILTER & PENCARIAN -->
    <div class="bg-white rounded-2xl p-4 md:p-6 border border-slate-100 figma-card-shadow space-y-4">
        <form method="GET" action="{{ route('admin.peminjaman.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 md:gap-4 items-end">
            <!-- Search Text -->
            <div class="space-y-1 lg:col-span-2">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Cari Peminjam / Invoice</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Nama instansi / invoice..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs md:text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <span class="absolute right-3.5 top-3 text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                </div>
            </div>

            <!-- Tanggal Dari -->
            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Tanggal Dari</label>
                <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs md:text-sm text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- Tanggal Sampai -->
            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Sampai Tanggal</label>
                <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs md:text-sm text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- Filter Status Peminjaman -->
            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Status Pengajuan</label>
                <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs md:text-sm text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved_1" {{ request('status') === 'approved_1' ? 'selected' : '' }}>Disetujui Admin</option>
                    <option value="approved_final" {{ request('status') === 'approved_final' ? 'selected' : '' }}>Disetujui Final</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>

            <!-- Tombol Aksi Filter & Reset -->
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs md:text-sm py-2.5 px-4 rounded-xl shadow-xs transition flex items-center justify-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-filter text-xs"></i>
                    <span>Terapkan</span>
                </button>
                @if(request()->anyFilled(['search', 'status', 'status_pembayaran', 'tanggal_dari', 'tanggal_sampai']))
                    <a href="{{ route('admin.peminjaman.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs md:text-sm py-2.5 px-3 rounded-xl transition flex items-center justify-center" title="Reset Filter">
                        <i class="fa-solid fa-rotate-left text-xs"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- ROW 3: TABEL DAFTAR PEMINJAMAN -->
    <div class="bg-white rounded-2xl border border-slate-100 figma-card-shadow overflow-hidden">
        <div class="p-5 md:p-6 pb-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h3 class="font-black text-slate-900 text-base md:text-lg tracking-tight">Data Permohonan Peminjaman Aula</h3>
                <p class="text-xs text-slate-500 mt-0.5">Kelola data peminjaman aula, persetujuan pengajuan, dan status tagihan</p>
            </div>
            <div class="flex items-center gap-2.5">
                <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-lg text-xs font-bold">
                    {{ $peminjamans->total() }} Data
                </span>
                <!-- Tombol Ekspor PDF dengan Pilihan Periode Tanggal -->
                <button type="button" onclick="openExportModal()"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-bold shadow-xs transition flex items-center gap-1.5 cursor-pointer transform active:scale-95">
                    <i class="fa-solid fa-file-pdf text-sm"></i>
                    <span>Ekspor PDF</span>
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs md:text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-700 font-bold uppercase text-[11px] tracking-wider border-b border-slate-200/80">
                        <th class="py-3.5 px-4">Invoice / ID</th>
                        <th class="py-3.5 px-4">Pemohon & Instansi</th>
                        <th class="py-3.5 px-4">Paket Sewa</th>
                        <th class="py-3.5 px-4">Jadwal Acara</th>
                        <th class="py-3.5 px-4 text-center">Status Pengajuan</th>
                        <th class="py-3.5 px-4 text-center">Status Pembayaran</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse ($peminjamans as $item)
                        @php
                            $pembayaran = $item->pembayaran;
                            $kode = $pembayaran?->kode_pembayaran ?: 'INV-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
                            $namaPaket = $item->paketPeminjaman?->nama_paket ?: 'Paket Aula';
                            $totalTagihan = $pembayaran?->total_tagihan ?? ($item->paketPeminjaman?->harga ?? 0);
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition">
                            <!-- Invoice / ID -->
                            <td class="py-4 px-4 font-mono font-bold text-brand-700">
                                <a href="{{ route('admin.peminjaman.show', $item->id) }}" class="hover:underline">
                                    {{ $kode }}
                                </a>
                                <div class="text-[10px] text-slate-400 font-sans font-normal mt-0.5">
                                    Dibuat: {{ $item->created_at->format('d/m/Y H:i') }}
                                </div>
                            </td>

                            <!-- Pemohon & Instansi -->
                            <td class="py-4 px-4">
                                <div class="font-bold text-slate-900">{{ $item->nama }}</div>
                                <div class="text-xs text-slate-500 flex items-center gap-1.5 mt-0.5">
                                    <i class="fa-regular fa-envelope text-[11px] text-slate-400"></i>
                                    <span>{{ $item->email_instansi }}</span>
                                </div>
                            </td>

                            <!-- Paket Sewa -->
                            <td class="py-4 px-4">
                                <div class="font-semibold text-slate-800">{{ $namaPaket }}</div>
                                <div class="text-xs text-emerald-600 font-bold mt-0.5">
                                    Rp {{ number_format($totalTagihan, 0, ',', '.') }}
                                </div>
                            </td>

                            <!-- Jadwal Acara -->
                            <td class="py-4 px-4 text-xs text-slate-600">
                                <div class="font-medium text-slate-800">
                                    <i class="fa-regular fa-calendar text-slate-400 mr-1"></i>
                                    {{ $item->tanggal_mulai ? \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') : '-' }}
                                </div>
                                <div class="text-[11px] text-slate-500 mt-0.5">
                                    <i class="fa-regular fa-clock text-slate-400 mr-1"></i>
                                    {{ $item->tanggal_mulai ? \Carbon\Carbon::parse($item->tanggal_mulai)->format('H:i') : '' }} -
                                    {{ $item->tanggal_selesai ? \Carbon\Carbon::parse($item->tanggal_selesai)->format('H:i') : '' }} WIB
                                </div>
                            </td>

                            <!-- Status Pengajuan -->
                            <td class="py-4 px-4 text-center">
                                @if (in_array($item->status, ['approved_1', 'approved_final']))
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fa-solid fa-circle-check text-[10px]"></i> Disetujui
                                    </span>
                                @elseif ($item->status === 'cancelled')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-300">
                                        <i class="fa-solid fa-ban text-[10px]"></i> Dibatalkan
                                    </span>
                                @elseif ($item->status === 'rejected')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-red-50 text-red-700 border border-red-200">
                                        <i class="fa-solid fa-circle-xmark text-[10px]"></i> Ditolak
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                        <i class="fa-solid fa-clock text-[10px]"></i> Menunggu Review
                                    </span>
                                @endif
                            </td>

                            <!-- Status Pembayaran -->
                            <td class="py-4 px-4 text-center">
                                @php
                                    $stBayar = $pembayaran?->status_pembayaran ?? 'pending';
                                @endphp
                                @if ($stBayar === 'lunas')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">
                                        <i class="fa-solid fa-check text-[10px]"></i> Lunas
                                    </span>
                                @elseif ($stBayar === 'partial')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-100 text-brand-800">
                                        <i class="fa-solid fa-shield-halved text-[10px]"></i> DP Terbayar
                                    </span>
                                @elseif ($stBayar === 'refund_pending')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-purple-100 text-purple-800 animate-pulse">
                                        <i class="fa-solid fa-hand-holding-dollar text-[10px]"></i> Perlu Refund
                                    </span>
                                @elseif ($stBayar === 'refunded')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700">
                                        <i class="fa-solid fa-arrow-rotate-left text-[10px]"></i> Selesai Refund
                                    </span>
                                @elseif ($stBayar === 'rejected')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-red-100 text-red-800">
                                        <i class="fa-solid fa-ban text-[10px]"></i> Bayar Ditolak
                                    </span>
                                @elseif ($stBayar === 'hangus')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-200 text-slate-600">
                                        Expired
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800">
                                        Pending
                                    </span>
                                @endif
                            </td>

                            <!-- Aksi Detail -->
                            <td class="py-4 px-4 text-center">
                                <a href="{{ route('admin.peminjaman.show', $item->id) }}"
                                   class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold rounded-xl shadow-xs transition">
                                    <i class="fa-regular fa-eye text-xs"></i>
                                    <span>Detail</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 px-4 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 text-xl border border-slate-200">
                                        <i class="fa-regular fa-folder-open"></i>
                                    </div>
                                    <p class="text-sm font-bold text-slate-700">Tidak Ada Data Peminjaman</p>
                                    <p class="text-xs text-slate-400">Belum ada pengajuan peminjaman yang cocok dengan kriteria pencarian.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        @if ($peminjamans->hasPages())
            <div class="p-4 md:p-6 border-t border-slate-100">
                {{ $peminjamans->links() }}
            </div>
        @endif
    </div>

</div>

<!-- MODAL EKSPOR PDF DAFTAR PEMINJAMAN -->
@push('modals')
<div id="exportPdfModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 space-y-5 animate-in fade-in zoom-in-95 duration-200">
        <!-- Modal Header -->
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-file-pdf"></i>
                </div>
                <div>
                    <h4 class="font-black text-slate-800 text-base">Ekspor PDF Daftar Peminjaman</h4>
                    <p class="text-xs text-slate-400">Pilih periode tanggal rekapan peminjaman aula</p>
                </div>
            </div>
            <button type="button" onclick="closeExportModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition cursor-pointer">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- Form Ekspor PDF -->
        <form action="{{ route('admin.peminjaman.export-pdf') }}" method="GET" target="_blank" class="space-y-4">
            <!-- Quick Preset Buttons -->
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Preset Periode Waktu</label>
                <div class="grid grid-cols-5 gap-1.5 text-center text-xs">
                    <button type="button" onclick="setExportPreset('hari_ini')"
                        class="py-2 px-1.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold transition cursor-pointer">
                        Hari Ini
                    </button>
                    <button type="button" onclick="setExportPreset('bulan_ini')"
                        class="py-2 px-1.5 rounded-xl border border-brand-200 bg-brand-50 hover:bg-brand-100 text-brand-700 font-bold transition cursor-pointer">
                        Bulan Ini
                    </button>
                    <button type="button" onclick="setExportPreset('bulan_lalu')"
                        class="py-2 px-1.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold transition cursor-pointer">
                        Bulan Lalu
                    </button>
                    <button type="button" onclick="setExportPreset('tahun_ini')"
                        class="py-2 px-1.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold transition cursor-pointer">
                        Tahun Ini
                    </button>
                    <button type="button" onclick="setExportPreset('semua')"
                        class="py-2 px-1.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold transition cursor-pointer">
                        Semua
                    </button>
                </div>
            </div>

            <!-- Date Inputs -->
            <div class="grid grid-cols-2 gap-3">
                <div class="space-y-1">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider">Tanggal Dari</label>
                    <input type="date" id="modal_tanggal_dari" name="tanggal_dari"
                        value="{{ request('tanggal_dari', now()->startOfMonth()->toDateString()) }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500 font-medium">
                </div>
                <div class="space-y-1">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider">Hingga Tanggal</label>
                    <input type="date" id="modal_tanggal_sampai" name="tanggal_sampai"
                        value="{{ request('tanggal_sampai', now()->endOfMonth()->toDateString()) }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500 font-medium">
                </div>
            </div>

            <!-- Status Permohonan & Status Pembayaran -->
            <div class="grid grid-cols-2 gap-3">
                <div class="space-y-1">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider">Status Permohonan</label>
                    <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500 font-medium">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved_1" {{ request('status') === 'approved_1' ? 'selected' : '' }}>Disetujui Admin</option>
                        <option value="approved_final" {{ request('status') === 'approved_final' ? 'selected' : '' }}>Disetujui Final</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider">Status Pembayaran</label>
                    <select name="status_pembayaran" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500 font-medium">
                        <option value="">Semua Status</option>
                        <option value="lunas" {{ request('status_pembayaran') === 'lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="partial" {{ request('status_pembayaran') === 'partial' ? 'selected' : '' }}>DP / Sebagian</option>
                        <option value="pending" {{ request('status_pembayaran') === 'pending' ? 'selected' : '' }}>Belum Bayar</option>
                        <option value="refunded" {{ request('status_pembayaran') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                </div>
            </div>

            <!-- Modal Action Buttons -->
            <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeExportModal()"
                    class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 font-semibold text-xs transition cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-bold text-xs shadow-xs transition flex items-center gap-2 cursor-pointer transform active:scale-95">
                    <i class="fa-solid fa-file-pdf"></i>
                    <span>Unduh Dokumen PDF</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endpush

@push('scripts')
<script>
    function openExportModal() {
        const modal = document.getElementById('exportPdfModal');
        if (modal) modal.classList.remove('hidden');
    }

    function closeExportModal() {
        const modal = document.getElementById('exportPdfModal');
        if (modal) modal.classList.add('hidden');
    }

    function setExportPreset(preset) {
        const today = new Date();
        const formatDate = (d) => {
            const y = d.getFullYear();
            const m = String(d.getMonth() + 1).padStart(2, '0');
            const day = String(d.getDate()).padStart(2, '0');
            return `${y}-${m}-${day}`;
        };

        let dari = '';
        let sampai = '';

        if (preset === 'hari_ini') {
            dari = formatDate(today);
            sampai = formatDate(today);
        } else if (preset === 'bulan_ini') {
            dari = formatDate(new Date(today.getFullYear(), today.getMonth(), 1));
            sampai = formatDate(new Date(today.getFullYear(), today.getMonth() + 1, 0));
        } else if (preset === 'bulan_lalu') {
            dari = formatDate(new Date(today.getFullYear(), today.getMonth() - 1, 1));
            sampai = formatDate(new Date(today.getFullYear(), today.getMonth(), 0));
        } else if (preset === 'tahun_ini') {
            dari = formatDate(new Date(today.getFullYear(), 0, 1));
            sampai = formatDate(new Date(today.getFullYear(), 11, 31));
        } else if (preset === 'semua') {
            dari = '';
            sampai = '';
        }

        const inputDari = document.getElementById('modal_tanggal_dari');
        const inputSampai = document.getElementById('modal_tanggal_sampai');
        if (inputDari) inputDari.value = dari;
        if (inputSampai) inputSampai.value = sampai;
    }
</script>
@endpush
@endsection
