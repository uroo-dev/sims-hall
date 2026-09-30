@extends('Admin.layout.app')

@section('title', 'Laporan Rekapitulasi Pemasukan Aula - SMKN 2 Karanganyar')
@section('page_title', 'Laporan Pemasukan Aula')

@section('content')
<div class="space-y-6">

    <!-- BARIS 1: HEADER & ACTION BUTTONS -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-5 md:p-6 rounded-3xl border border-slate-100 figma-card-shadow">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold text-brand-600 uppercase tracking-wider mb-1">
                <i class="fa-solid fa-file-invoice-dollar"></i>
                <span>Keuangan & Sarpras Aula</span>
            </div>
            <h2 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight">
                Rekapitulasi Pemasukan Sewa Aula
            </h2>
            <p class="text-xs md:text-sm text-slate-500 mt-1">
                Pantau realisasi pendapatan sewa aula, rekapitulasi dana masuk, status pelunasan, dan ekspor dokumen PDF resmi.
            </p>
        </div>

        <div class="flex items-center gap-2.5 flex-wrap">
            <!-- Tombol Ekspor PDF -->
            @php
                $pdfRoute = $isKepalaSekolah ? route('kepala-sekolah.laporan.pdf', request()->query()) : route('admin.laporan.pdf', request()->query());
            @endphp
            <a href="{{ $pdfRoute }}" target="_blank"
                class="px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-2xl text-xs md:text-sm font-bold shadow-sm hover:shadow transition flex items-center gap-2 transform active:scale-95">
                <i class="fa-solid fa-file-pdf text-sm"></i>
                <span>Ekspor Dokumen PDF</span>
            </a>

            <!-- Tombol Cetak Browser -->
            <button type="button" onclick="window.print()"
                class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl text-xs md:text-sm font-bold transition flex items-center gap-2">
                <i class="fa-solid fa-print text-sm"></i>
                <span>Cetak Cepat</span>
            </button>
        </div>
    </div>

    <!-- BARIS 2: KARTU INDIKATOR KEUANGAN (KPI METRICS) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5 md:gap-4">
        <!-- 1. Pemasukan Bersih (Netto) -->
        <div class="bg-white rounded-3xl p-5 border border-slate-100 figma-card-shadow flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Pemasukan Bersih (Netto)</span>
                    <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-sack-dollar"></i>
                    </span>
                </div>
                <h3 class="text-xl md:text-2xl font-black text-emerald-600 mt-2 tracking-tight">
                    Rp {{ number_format($stats['total_pemasukan_netto'], 0, ',', '.') }}
                </h3>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span>Pemasukan Riil Masuk</span>
                <span class="font-bold text-emerald-600">{{ $stats['persen_lunas'] }}% Realisasi</span>
            </div>
        </div>

        <!-- 2. Pemasukan Bruto -->
        <div class="bg-white rounded-3xl p-5 border border-slate-100 figma-card-shadow flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Pemasukan Bruto</span>
                    <span class="w-8 h-8 rounded-xl bg-blue-50 text-brand-600 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-money-bill-transfer"></i>
                    </span>
                </div>
                <h3 class="text-lg md:text-xl font-black text-slate-800 mt-2">
                    Rp {{ number_format($stats['total_pemasukan_bruto'], 0, ',', '.') }}
                </h3>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span>Total Dana Diterima</span>
                <span class="font-bold text-slate-700">{{ $stats['count_lunas'] + $stats['count_partial'] }} Transaksi</span>
            </div>
        </div>

        <!-- 3. Total Refund -->
        <div class="bg-white rounded-3xl p-5 border border-slate-100 figma-card-shadow flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Refund (Retur)</span>
                    <span class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-hand-holding-dollar"></i>
                    </span>
                </div>
                <h3 class="text-lg md:text-xl font-black {{ $stats['total_refund'] > 0 ? 'text-purple-600' : 'text-slate-800' }} mt-2">
                    Rp {{ number_format($stats['total_refund'], 0, ',', '.') }}
                </h3>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span>Dana Dikembalikan</span>
                <span class="font-bold text-purple-600">{{ $stats['count_refunded'] }} Peminjaman</span>
            </div>
        </div>

        <!-- 4. Total Nilai Tagihan Kontrak -->
        <div class="bg-white rounded-3xl p-5 border border-slate-100 figma-card-shadow flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Nilai Sewa</span>
                    <span class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-file-contract"></i>
                    </span>
                </div>
                <h3 class="text-lg md:text-xl font-black text-slate-800 mt-2">
                    Rp {{ number_format($stats['total_tagihan'], 0, ',', '.') }}
                </h3>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span>Total Tagihan Kontrak</span>
                <span class="font-bold text-slate-700">{{ $stats['total_peminjaman'] }} Pengajuan</span>
            </div>
        </div>

        <!-- 5. Sisa Piutang / Belum Lunas -->
        <div class="bg-white rounded-3xl p-5 border border-slate-100 figma-card-shadow flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Sisa Tagihan (Piutang)</span>
                    <span class="w-8 h-8 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </span>
                </div>
                <h3 class="text-lg md:text-xl font-black {{ $stats['total_sisa_tagihan'] > 0 ? 'text-orange-600' : 'text-slate-800' }} mt-2">
                    Rp {{ number_format($stats['total_sisa_tagihan'], 0, ',', '.') }}
                </h3>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span>Belum Dilunasi</span>
                <span class="font-bold text-orange-600">{{ $stats['count_partial'] + $stats['count_pending'] }} Booking</span>
            </div>
        </div>
    </div>

    <!-- BARIS 3: ANALITIK GRAFIK VISUAL -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <!-- Grafik Tren Bulanan (2 Kolom) -->
        <div class="lg:col-span-2 bg-white p-5 md:p-6 rounded-3xl border border-slate-100 figma-card-shadow space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm md:text-base font-bold text-slate-800">Tren Pemasukan Aula (6 Bulan Terakhir)</h3>
                    <p class="text-xs text-slate-400">Perbandingan pemasukan riil dan pengembalian refund per bulan</p>
                </div>
                <span class="px-3 py-1 bg-blue-50 text-brand-700 rounded-full text-xs font-bold">
                    <i class="fa-solid fa-chart-simple mr-1"></i> Bulanan
                </span>
            </div>
            <div class="relative h-64 md:h-72 w-full">
                <canvas id="monthlyIncomeChart"></canvas>
            </div>
        </div>

        <!-- Proporsi Paket Peminjaman (1 Kolom) -->
        <div class="bg-white p-5 md:p-6 rounded-3xl border border-slate-100 figma-card-shadow space-y-4 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm md:text-base font-bold text-slate-800">Kontribusi Paket Aula</h3>
                        <p class="text-xs text-slate-400">Distribusi pendapatan per paket sewa</p>
                    </div>
                    <span class="w-7 h-7 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-chart-pie"></i>
                    </span>
                </div>
                <div class="relative h-56 w-full mt-3 flex items-center justify-center">
                    <canvas id="packageShareChart"></canvas>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 grid grid-cols-3 gap-2 text-center text-xs">
                <div class="p-2 rounded-xl bg-emerald-50 text-emerald-800">
                    <span class="block text-[10px] font-bold text-emerald-600 uppercase">Lunas</span>
                    <span class="font-black text-sm">{{ $stats['count_lunas'] }}</span>
                </div>
                <div class="p-2 rounded-xl bg-amber-50 text-amber-800">
                    <span class="block text-[10px] font-bold text-amber-600 uppercase">DP / Cicil</span>
                    <span class="font-black text-sm">{{ $stats['count_partial'] }}</span>
                </div>
                <div class="p-2 rounded-xl bg-slate-100 text-slate-700">
                    <span class="block text-[10px] font-bold text-slate-500 uppercase">Pending</span>
                    <span class="font-black text-sm">{{ $stats['count_pending'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- BARIS 4: FILTER PENCARIAN & PERIODE LAPORAN -->
    @php
        $formAction = $isKepalaSekolah ? route('kepala-sekolah.laporan.index') : route('admin.laporan.index');
    @endphp
    <div class="bg-white p-5 md:p-6 rounded-3xl border border-slate-100 figma-card-shadow space-y-4">
        <form method="GET" action="{{ $formAction }}" id="filterForm" class="space-y-4">
            
            <!-- Quick Preset Buttons -->
            <div class="flex items-center gap-2 overflow-x-auto pb-1">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap mr-1">Preset:</span>
                
                <a href="{{ request()->fullUrlWithQuery(['preset' => 'hari_ini', 'tanggal_dari' => null, 'tanggal_sampai' => null]) }}"
                    class="px-3.5 py-1.5 rounded-full text-xs font-bold transition whitespace-nowrap {{ $filter['preset'] === 'hari_ini' ? 'bg-[#0073c6] text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                    Hari Ini
                </a>
                <a href="{{ request()->fullUrlWithQuery(['preset' => 'bulan_ini', 'tanggal_dari' => null, 'tanggal_sampai' => null]) }}"
                    class="px-3.5 py-1.5 rounded-full text-xs font-bold transition whitespace-nowrap {{ $filter['preset'] === 'bulan_ini' ? 'bg-[#0073c6] text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                    Bulan Ini
                </a>
                <a href="{{ request()->fullUrlWithQuery(['preset' => 'bulan_lalu', 'tanggal_dari' => null, 'tanggal_sampai' => null]) }}"
                    class="px-3.5 py-1.5 rounded-full text-xs font-bold transition whitespace-nowrap {{ $filter['preset'] === 'bulan_lalu' ? 'bg-[#0073c6] text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                    Bulan Lalu
                </a>
                <a href="{{ request()->fullUrlWithQuery(['preset' => 'tahun_ini', 'tanggal_dari' => null, 'tanggal_sampai' => null]) }}"
                    class="px-3.5 py-1.5 rounded-full text-xs font-bold transition whitespace-nowrap {{ $filter['preset'] === 'tahun_ini' ? 'bg-[#0073c6] text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                    Tahun Ini
                </a>
                <a href="{{ request()->fullUrlWithQuery(['preset' => 'semua', 'tanggal_dari' => null, 'tanggal_sampai' => null]) }}"
                    class="px-3.5 py-1.5 rounded-full text-xs font-bold transition whitespace-nowrap {{ $filter['preset'] === 'semua' ? 'bg-[#0073c6] text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                    Semua Waktu
                </a>
            </div>

            <!-- Form Grid Filters -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5 items-end">
                <input type="hidden" name="custom_range" value="1">
                <input type="hidden" name="preset" value="custom">

                <!-- 1. Filter Berdasarkan Tanggal -->
                <div class="space-y-1">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider">Dasar Acuan Tanggal</label>
                    <select name="filter_by" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-3.5 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500 font-medium">
                        <option value="transaksi" {{ $filter['filter_by'] === 'transaksi' ? 'selected' : '' }}>Tanggal Transaksi (Uang Masuk)</option>
                        <option value="sewa" {{ $filter['filter_by'] === 'sewa' ? 'selected' : '' }}>Tanggal Sewa Pelaksanaan Aula</option>
                        <option value="pengajuan" {{ $filter['filter_by'] === 'pengajuan' ? 'selected' : '' }}>Tanggal Pengajuan Booking</option>
                    </select>
                </div>

                <!-- 2. Tanggal Mulai -->
                <div class="space-y-1">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider">Mulai Dari</label>
                    <input type="date" name="tanggal_dari" value="{{ $filter['tanggal_dari'] }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-3.5 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500 font-medium">
                </div>

                <!-- 3. Tanggal Sampai -->
                <div class="space-y-1">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider">Sampai Dengan</label>
                    <input type="date" name="tanggal_sampai" value="{{ $filter['tanggal_sampai'] }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-3.5 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500 font-medium">
                </div>

                <!-- 4. Status Pembayaran -->
                <div class="space-y-1">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider">Status Pembayaran</label>
                    <select name="status_pembayaran" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-3.5 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500 font-medium">
                        <option value="all" {{ $filter['status_pembayaran'] === 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="lunas" {{ $filter['status_pembayaran'] === 'lunas' ? 'selected' : '' }}>Lunas (100%)</option>
                        <option value="partial" {{ $filter['status_pembayaran'] === 'partial' ? 'selected' : '' }}>DP / Sebagian (Partial)</option>
                        <option value="pending" {{ $filter['status_pembayaran'] === 'pending' ? 'selected' : '' }}>Belum Bayar (Pending)</option>
                        <option value="refunded" {{ $filter['status_pembayaran'] === 'refunded' ? 'selected' : '' }}>Ada Refund / Retur</option>
                    </select>
                </div>

                <!-- 5. Paket Peminjaman Aula -->
                <div class="space-y-1">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider">Paket Aula</label>
                    <select name="paket_id" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-3.5 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500 font-medium">
                        <option value="all">Semua Paket</option>
                        @foreach ($daftarPaket as $paket)
                            <option value="{{ $paket->id }}" {{ (string) $filter['paket_id'] === (string) $paket->id ? 'selected' : '' }}>
                                {{ $paket->nama_paket }} (Rp {{ number_format($paket->harga, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Search Keyword & Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2">
                <div class="relative w-full sm:max-w-md">
                    <input type="text" name="search" value="{{ $filter['search'] }}"
                        placeholder="Cari nama pemohon, instansi, atau no invoice..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-2xl pl-10 pr-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <span class="absolute left-3.5 top-3 text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                    <a href="{{ $formAction }}"
                        class="px-4 py-2.5 rounded-2xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-bold transition flex items-center gap-2">
                        <i class="fa-solid fa-rotate-left text-xs"></i>
                        <span>Reset</span>
                    </a>

                    <button type="submit"
                        class="px-5 py-2.5 bg-[#0073c6] hover:bg-[#0060ac] text-white rounded-2xl text-xs font-bold shadow-sm transition flex items-center gap-2 transform active:scale-95">
                        <i class="fa-solid fa-filter text-xs"></i>
                        <span>Terapkan Filter</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- BARIS 5: TABEL REKAPITULASI PEMASUKAN AULA -->
    <div class="bg-white rounded-3xl border border-slate-100 figma-card-shadow overflow-hidden">
        
        <!-- Table Header Info -->
        <div class="p-5 md:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-slate-50/50">
            <div>
                <h3 class="text-base font-bold text-slate-800">Daftar Transaksi Rekapitulasi Keuangan</h3>
                <p class="text-xs text-slate-500 mt-0.5">
                    Menampilkan {{ $peminjamans->total() }} riwayat transaksi sesuai kriteria filter yang diterapkan.
                </p>
            </div>
            <div class="text-xs font-semibold text-slate-500">
                Total Pemasukan Halaman Ini: 
                <span class="font-black text-emerald-600">
                    Rp {{ number_format($peminjamans->sum(fn ($p) => (float) ($p->pembayaran?->total_terbayar ?? 0)), 0, ',', '.') }}
                </span>
            </div>
        </div>

        <!-- Table Container -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                        <th class="py-3.5 px-4 text-center w-12">No</th>
                        <th class="py-3.5 px-4">Invoice / Kode</th>
                        <th class="py-3.5 px-4">Pemohon / Instansi</th>
                        <th class="py-3.5 px-4">Paket Aula</th>
                        <th class="py-3.5 px-4">Jadwal Sewa</th>
                        <th class="py-3.5 px-4 text-right">Nilai Kontrak</th>
                        <th class="py-3.5 px-4 text-right">Dana Masuk</th>
                        <th class="py-3.5 px-4 text-right">Sisa Tagihan</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse ($peminjamans as $index => $item)
                        @php
                            $pembayaran = $item->pembayaran;
                            $tagihan = (float) ($pembayaran->total_tagihan ?? 0);
                            $terbayar = (float) ($pembayaran->total_terbayar ?? 0);
                            $refund = (float) ($pembayaran->total_refund ?? 0);
                            $sisa = (float) ($pembayaran->sisa_tagihan ?? 0);
                            $status = $pembayaran->status_pembayaran ?? 'pending';

                            $detailUrl = $isKepalaSekolah 
                                ? route('kepala-sekolah.peminjaman.show', $item) 
                                : route('admin.peminjaman.show', $item);
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition">
                            <!-- No -->
                            <td class="py-3.5 px-4 text-center text-slate-400 font-semibold">
                                {{ $peminjamans->firstItem() + $index }}
                            </td>

                            <!-- Invoice -->
                            <td class="py-3.5 px-4">
                                <span class="font-mono font-bold text-brand-600 block">
                                    {{ $pembayaran->kode_pembayaran ?? 'INV-' . str_pad($item->id, 5, '0', STR_PAD_LEFT) }}
                                </span>
                                <span class="text-[10px] text-slate-400">
                                    ID #{{ $item->id }}
                                </span>
                            </td>

                            <!-- Pemohon & Instansi -->
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-800">{{ $item->nama }}</div>
                                <div class="text-[11px] text-slate-400 flex items-center gap-1.5 mt-0.5">
                                    <i class="fa-regular fa-envelope text-[10px]"></i>
                                    <span>{{ $item->email_instansi }}</span>
                                </div>
                            </td>

                            <!-- Paket Aula -->
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-800 block">
                                    {{ $item->paketPeminjaman->nama_paket ?? 'Paket Peminjaman' }}
                                </span>
                                <span class="text-[10px] text-slate-400 uppercase">
                                    {{ $item->paketPeminjaman->kategori ?? '-' }}
                                </span>
                            </td>

                            <!-- Jadwal Sewa -->
                            <td class="py-3.5 px-4 text-[11px]">
                                <div class="text-slate-800 font-semibold">
                                    {{ $item->tanggal_mulai ? $item->tanggal_mulai->format('d M Y') : '-' }}
                                </div>
                                <div class="text-slate-400 text-[10px]">
                                    {{ $item->tanggal_mulai ? $item->tanggal_mulai->format('H:i') : '' }} - {{ $item->tanggal_selesai ? $item->tanggal_selesai->format('H:i') : '' }} WIB
                                </div>
                            </td>

                            <!-- Nilai Kontrak Tagihan -->
                            <td class="py-3.5 px-4 text-right font-semibold text-slate-700">
                                Rp {{ number_format($tagihan, 0, ',', '.') }}
                            </td>

                            <!-- Dana Masuk (Terbayar) -->
                            <td class="py-3.5 px-4 text-right">
                                <span class="font-bold text-emerald-600 block">
                                    Rp {{ number_format($terbayar, 0, ',', '.') }}
                                </span>
                                @if ($refund > 0)
                                    <span class="text-[10px] text-purple-600 block">
                                        Retur: Rp {{ number_format($refund, 0, ',', '.') }}
                                    </span>
                                @endif
                                @if ($pembayaran && $pembayaran->details->count() > 0)
                                    <span class="text-[9px] text-slate-400">
                                        ({{ $pembayaran->details->count() }}x bayar)
                                    </span>
                                @endif
                            </td>

                            <!-- Sisa Tagihan -->
                            <td class="py-3.5 px-4 text-right">
                                @if ($sisa > 0)
                                    <span class="font-bold text-orange-600">
                                        Rp {{ number_format($sisa, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 font-bold text-emerald-600 text-[11px]">
                                        <i class="fa-solid fa-check-circle text-xs"></i> Lunas
                                    </span>
                                @endif
                            </td>

                            <!-- Status Pembayaran -->
                            <td class="py-3.5 px-4 text-center">
                                @if ($status === 'lunas')
                                    <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800">
                                        LUNAS
                                    </span>
                                @elseif ($status === 'partial')
                                    <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-black bg-amber-100 text-amber-800">
                                        DP / CICIL
                                    </span>
                                @elseif ($status === 'pending')
                                    <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-black bg-blue-100 text-blue-800">
                                        MENUNGGU
                                    </span>
                                @elseif ($status === 'refunded' || $status === 'refund_pending')
                                    <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-black bg-purple-100 text-purple-800">
                                        REFUND
                                    </span>
                                @else
                                    <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-black bg-red-100 text-red-800">
                                        {{ strtoupper($status) }}
                                    </span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="py-3.5 px-4 text-center">
                                <a href="{{ $detailUrl }}"
                                    class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-brand-50 hover:text-brand-600 text-slate-600 inline-flex items-center justify-center transition"
                                    title="Lihat Rincian Transaksi">
                                    <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-12 text-center text-slate-400">
                                <div class="w-16 h-16 rounded-3xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3 text-2xl">
                                    <i class="fa-regular fa-folder-open"></i>
                                </div>
                                <h4 class="font-bold text-slate-700 text-sm">Tidak Ada Data Transaksi Pemasukan</h4>
                                <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                                    Tidak ditemukan transaksi peminjaman aula yang sesuai dengan kriteria dan rentang tanggal yang Anda tentukan.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <!-- Table Footer Summary -->
                @if ($peminjamans->count() > 0)
                    <tfoot>
                        <tr class="bg-slate-50 border-t-2 border-slate-200 font-bold text-slate-800 text-xs">
                            <td colspan="5" class="py-3.5 px-4 text-center uppercase tracking-wider text-[11px] text-slate-500">
                                Akumulasi Total Halaman Ini
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                Rp {{ number_format($peminjamans->sum(fn ($p) => (float) ($p->pembayaran?->total_tagihan ?? 0)), 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-right text-emerald-600">
                                Rp {{ number_format($peminjamans->sum(fn ($p) => (float) ($p->pembayaran?->total_terbayar ?? 0)), 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-right text-orange-600">
                                Rp {{ number_format($peminjamans->sum(fn ($p) => (float) ($p->pembayaran?->sisa_tagihan ?? 0)), 0, ',', '.') }}
                            </td>
                            <td colspan="2" class="py-3.5 px-4 text-center text-[10px] text-slate-400">
                                SIMS Aula SMKN 2 Kra
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>

        <!-- Paginasi -->
        @if ($peminjamans->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $peminjamans->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Grafik Tren Bulanan (Chart.js)
        const ctxMonthly = document.getElementById('monthlyIncomeChart');
        if (ctxMonthly) {
            const monthlyLabels = {!! json_encode($chartData['monthly']['labels']) !!};
            const monthlyInflow = {!! json_encode($chartData['monthly']['income']) !!};
            const monthlyRefund = {!! json_encode($chartData['monthly']['refund']) !!};

            new Chart(ctxMonthly, {
                type: 'bar',
                data: {
                    labels: monthlyLabels,
                    datasets: [
                        {
                            label: 'Pemasukan Riil (Rp)',
                            data: monthlyInflow,
                            backgroundColor: '#0073c6',
                            borderRadius: 8,
                            barPercentage: 0.6,
                        },
                        {
                            label: 'Refund / Retur (Rp)',
                            data: monthlyRefund,
                            backgroundColor: '#a855f7',
                            borderRadius: 8,
                            barPercentage: 0.6,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: { family: 'Inter', size: 11, weight: '600' },
                                boxWidth: 12,
                                usePointStyle: true,
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f1f5f9' },
                            ticks: {
                                font: { family: 'Inter', size: 10 },
                                callback: function(value) {
                                    if (value >= 1000000) return (value / 1000000) + ' Jt';
                                    if (value >= 1000) return (value / 1000) + ' Rb';
                                    return value;
                                }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { family: 'Inter', size: 11 } }
                        }
                    }
                }
            });
        }

        // 2. Grafik Donut Proporsi Paket Peminjaman (Chart.js)
        const ctxPackage = document.getElementById('packageShareChart');
        if (ctxPackage) {
            const packageLabels = {!! json_encode($chartData['paket']['labels']) !!};
            const packageRevenues = {!! json_encode($chartData['paket']['revenues']) !!};

            const colors = ['#0073c6', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899', '#6366f1'];

            new Chart(ctxPackage, {
                type: 'doughnut',
                data: {
                    labels: packageLabels,
                    datasets: [{
                        data: packageRevenues,
                        backgroundColor: colors.slice(0, packageLabels.length),
                        borderWidth: 2,
                        borderColor: '#ffffff',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: { family: 'Inter', size: 10 },
                                boxWidth: 10,
                                usePointStyle: true,
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                                }
                            }
                        }
                    },
                    cutout: '65%'
                }
            });
        }
    });
</script>
@endpush
