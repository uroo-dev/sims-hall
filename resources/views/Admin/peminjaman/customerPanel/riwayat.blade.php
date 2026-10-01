@extends('Admin.layout.app')

@section('title', 'Cek Peminjaman - SIMS Aula SMK N 2 Karanganyar')
@section('page_title', 'Cek Peminjaman')

@section('content')
<div class="space-y-6">

    <!-- CARD 1: DAFTAR PEMINJAMAN (TABEL SESUAI SCREENSHOT 2) -->
    <div class="bg-white rounded-2xl figma-card-shadow p-6 md:p-8 border border-blue-50/50 space-y-6">

        <!-- Title & Subtitle -->
        <div>
            <h2 class="text-lg md:text-xl font-bold text-gray-900 tracking-tight">
                Daftar Peminjaman
            </h2>
            <p class="text-xs md:text-sm text-gray-500 font-normal">
                Semua pesanan yang masuk ke sistem TEFA
            </p>
        </div>

        <!-- Table Responsive Container -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs md:text-sm">
                <thead>
                    <tr class="border-b border-gray-200 text-gray-800 font-bold">
                        <th class="py-3 px-3">ID</th>
                        <th class="py-3 px-3">Paket Peminjaman</th>
                        <th class="py-3 px-3">Harga</th>
                        <th class="py-3 px-3">Tanggal</th>
                        <th class="py-3 px-3 text-center">Tahap 1</th>
                        <th class="py-3 px-3 text-center">Tahap 2</th>
                        <th class="py-3 px-3 text-center">Status</th>
                        <th class="py-3 px-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse ($peminjamans as $item)
                        @php
                            $kode = $item->pembayaran?->kode_pembayaran ?: 'ORD-' . str_pad($item->id, 3, '0', STR_PAD_LEFT);
                            $namaPaket = $item->paketPeminjaman?->nama_paket ?: 'Paket Aula';
                            $harga = $item->pembayaran?->total_tagihan ?? ($item->paketPeminjaman?->harga ?? 0);
                            $tanggal = \Carbon\Carbon::parse($item->tanggal_mulai)->format('Y-m-d');

                            // Tahap 1: Approval Admin
                            $tahap1 = $item->persetujuans->firstWhere('level', 'admin');
                            $isTahap1Verified = $tahap1 && $tahap1->status === 'approved' || in_array($item->status, ['approved_1', 'approved_final']);

                            // Tahap 2: Approval Pimpinan
                            $tahap2 = $item->persetujuans->firstWhere('level', 'pimpinan');
                            $isTahap2Verified = $tahap2 && $tahap2->status === 'approved' || $item->status === 'approved_final';

                            // Status Pembayaran
                            $statusBayar = $item->pembayaran?->status_pembayaran;
                            $isTerbayar = in_array($statusBayar, ['lunas', 'free']);
                        @endphp
                        <tr class="hover:bg-gray-50/70 transition-colors">
                            <td class="py-4 px-3 font-semibold text-gray-900">
                                {{ $kode }}
                            </td>
                            <td class="py-4 px-3 font-medium">
                                {{ $namaPaket }}
                            </td>
                            <td class="py-4 px-3">
                                Rp.{{ number_format($harga, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-3 text-gray-600">
                                {{ $tanggal }}
                            </td>
                            <!-- Tahap 1 -->
                            <td class="py-4 px-3 text-center">
                                @if ($item->status === 'cancelled')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-md text-[11px] font-semibold bg-slate-500 text-white">
                                        Dibatalkan
                                    </span>
                                @elseif ($isTahap1Verified)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-md text-[11px] font-semibold bg-[#00a844] text-white">
                                        <i class="fa-regular fa-circle-check text-[10px]"></i> Terverifikasi
                                    </span>
                                @elseif ($item->status === 'rejected')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-md text-[11px] font-semibold bg-red-600 text-white">
                                        Ditolak
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-md text-[11px] font-semibold bg-amber-500 text-white">
                                        Menunggu
                                    </span>
                                @endif
                            </td>
                            <!-- Tahap 2 -->
                            <td class="py-4 px-3 text-center">
                                @if ($item->status === 'cancelled')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-md text-[11px] font-semibold bg-slate-500 text-white">
                                        Dibatalkan
                                    </span>
                                @elseif ($isTahap2Verified)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-md text-[11px] font-semibold bg-[#00a844] text-white">
                                        <i class="fa-regular fa-circle-check text-[10px]"></i> Terverifikasi
                                    </span>
                                @elseif ($item->status === 'rejected')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-md text-[11px] font-semibold bg-red-600 text-white">
                                        Ditolak
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-md text-[11px] font-semibold bg-gray-400 text-white">
                                        Menunggu
                                    </span>
                                @endif
                            </td>
                            <!-- Status -->
                            <td class="py-4 px-3 text-center">
                                @if ($item->status === 'cancelled')
                                    @if ($statusBayar === 'refund_pending')
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-md text-[11px] font-semibold bg-purple-600 text-white animate-pulse">
                                            Proses Refund
                                        </span>
                                    @elseif ($statusBayar === 'refunded')
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-md text-[11px] font-semibold bg-slate-600 text-white">
                                            Refund Selesai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-md text-[11px] font-semibold bg-slate-500 text-white">
                                            Dibatalkan
                                        </span>
                                    @endif
                                @elseif ($isTerbayar)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-md text-[11px] font-semibold bg-[#00a844] text-white">
                                        <i class="fa-regular fa-circle-check text-[10px]"></i> Terbayar
                                    </span>
                                @elseif ($statusBayar === 'partial')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-md text-[11px] font-semibold bg-blue-600 text-white">
                                        DP Terverifikasi
                                    </span>
                                @elseif ($statusBayar === 'refund_pending')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-md text-[11px] font-semibold bg-purple-600 text-white animate-pulse">
                                        Proses Refund
                                    </span>
                                @elseif ($statusBayar === 'refunded')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-md text-[11px] font-semibold bg-slate-600 text-white">
                                        Refund Selesai
                                    </span>
                                @elseif ($statusBayar === 'rejected')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-md text-[11px] font-semibold bg-red-600 text-white">
                                        Transfer Ditolak
                                    </span>
                                @elseif ($statusBayar === 'hangus')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-md text-[11px] font-semibold bg-gray-500 text-white">
                                        Expired
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-md text-[11px] font-semibold bg-amber-500 text-white">
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <!-- Aksi -->
                            <td class="py-4 px-3 text-center">
                                <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                    <button type="button"
                                            onclick="showNotaModal('{{ $kode }}', '{{ $namaPaket }}', '{{ number_format($harga, 0, ',', '.') }}', '{{ $tanggal }}', '{{ $isTerbayar ? 'LUNAS' : 'PENDING' }}')"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 bg-[#0070ba] hover:bg-[#005a96] text-white text-xs font-semibold rounded-md shadow-xs transition-colors">
                                        <i class="fa-regular fa-circle-dot text-[10px]"></i>
                                        <span>Nota</span>
                                    </button>
                                    @if ($item->pembayaran && !in_array($item->status, ['cancelled', 'rejected']))
                                        <a href="{{ route('customer.pembayaran.show', $item->pembayaran->id) }}"
                                           class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-700 hover:bg-slate-800 text-white text-xs font-semibold rounded-md shadow-xs transition-colors"
                                           title="Buka Rincian Tagihan & Pembayaran">
                                            <i class="fa-solid fa-receipt text-[10px]"></i>
                                            <span>Bayar</span>
                                        </a>
                                    @elseif ($item->pembayaran && in_array($statusBayar, ['refund_pending', 'refunded']))
                                        <a href="{{ route('customer.pembayaran.show', $item->pembayaran->id) }}"
                                           class="inline-flex items-center gap-1 px-2.5 py-1 bg-purple-700 hover:bg-purple-800 text-white text-xs font-semibold rounded-md shadow-xs transition-colors"
                                           title="Buka Status Refund Dana">
                                            <i class="fa-solid fa-hand-holding-dollar text-[10px]"></i>
                                            <span>Refund</span>
                                        </a>
                                    @endif
                                    @if ($item->canBeCancelled())
                                        @php
                                            $totalTerbayarFormat = $item->pembayaran?->total_terbayar > 0 ? number_format($item->pembayaran->total_terbayar, 0, ',', '.') : '0';
                                        @endphp
                                        <button type="button"
                                                onclick="openCancelModal('{{ $item->id }}', '{{ $kode }}', '{{ $namaPaket }}', '{{ $item->hari_maksimal_cancel }}', '{{ $item->batas_pembatalan->translatedFormat('d M Y, H:i') }}', '{{ route('customer.peminjaman.cancel', $item->id) }}', '{{ $totalTerbayarFormat }}', {{ $item->isEligibleForRefund() ? 'true' : 'false' }})"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-semibold rounded-md shadow-xs transition-colors cursor-pointer"
                                                title="Batalkan Pengajuan">
                                            <i class="fa-solid fa-ban text-[10px]"></i>
                                            <span>Batal</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 px-4 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400 space-y-3">
                                    <div class="w-14 h-14 rounded-full bg-blue-50/60 border border-blue-100 flex items-center justify-center text-[#0070ba] text-2xl">
                                        <i class="fa-regular fa-folder-open"></i>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-sm font-bold text-gray-800">Belum Ada Riwayat Peminjaman</p>
                                        <p class="text-xs text-gray-500">Anda belum memiliki riwayat pengajuan peminjaman aula.</p>
                                    </div>
                                    <a href="{{ route('customer.paket') }}"
                                       class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#0070ba] hover:bg-[#005a96] text-white text-xs font-semibold rounded-xl shadow-xs transition-colors">
                                        <i class="fa-solid fa-plus text-[10px]"></i>
                                        <span>Ajukan Peminjaman</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    <!-- CARD 2: DAFTAR PEMBAYARAN -->
    <div class="bg-white rounded-2xl figma-card-shadow p-6 md:p-8 border border-blue-50/50 space-y-6">

        <div>
            <h3 class="text-base md:text-lg font-bold text-gray-900 tracking-tight">
                Daftar Pembayaran
            </h3>
            <p class="text-xs md:text-sm text-gray-500 font-normal">
                Klik kartu pembayaran di bawah untuk menuju halaman pembayaran dan melengkapi bukti transfer.
            </p>
        </div>

        @if ($peminjamans->isEmpty())
            <div class="py-12 flex flex-col items-center justify-center text-center text-gray-400 space-y-2">
                <div class="w-12 h-12 rounded-full bg-blue-50/50 border border-blue-100 flex items-center justify-center text-[#0070ba] text-xl">
                    <i class="fa-regular fa-credit-card"></i>
                </div>
                <p class="text-xs md:text-sm font-medium text-gray-500">Tidak ada tagihan pembayaran aktif.</p>
            </div>
        @else
            <!-- Tagihan Aktif yang Memerlukan Pembayaran -->
            @if (!empty($activePembayarans))
                <div class="space-y-4">
                    @foreach ($activePembayarans as $pem)
                        <a href="{{ route('customer.pembayaran.show', $pem->id) }}"
                           class="group block border border-blue-200/90 hover:border-[#0070ba] rounded-2xl p-5 md:p-6 bg-gradient-to-r from-blue-50/40 to-indigo-50/20 hover:from-blue-50/70 hover:to-indigo-50/40 shadow-xs hover:shadow-md transition-all cursor-pointer">
                            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                                <div class="space-y-1.5 flex-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $pem->status_pembayaran === 'partial' ? 'bg-blue-100 text-brand-800' : ($pem->status_pembayaran === 'refund_pending' ? 'bg-purple-100 text-purple-800 animate-pulse' : 'bg-amber-100 text-amber-800') }}">
                                            <i class="{{ $pem->status_pembayaran === 'refund_pending' ? 'fa-solid fa-hand-holding-dollar text-purple-600' : 'fa-regular fa-clock' }} text-[10px]"></i>
                                            {{ $pem->status_pembayaran === 'partial' ? 'DP Terverifikasi (Menunggu Pelunasan)' : ($pem->status_pembayaran === 'refund_pending' ? 'Pengajuan Ditolak (Menunggu Refund)' : 'Tagihan Aktif ('.strtoupper($pem->status_pembayaran).')') }}
                                        </span>
                                        <span class="text-xs font-semibold text-gray-700">
                                            {{ $pem->peminjaman?->paketPeminjaman?->nama_paket ?: 'Paket Sewa Aula' }}
                                        </span>
                                        @if ($pem->peminjaman?->tanggal_mulai)
                                            <span class="text-xs text-gray-500">
                                                &bull; Jadwal: {{ \Carbon\Carbon::parse($pem->peminjaman->tanggal_mulai)->format('d M Y') }}
                                            </span>
                                        @endif
                                    </div>
                                    <h4 class="text-base font-bold text-gray-900 group-hover:text-[#0070ba] transition-colors flex items-center gap-2">
                                        <span>{{ $pem->kode_pembayaran }}</span>
                                        <i class="fa-solid fa-arrow-up-right-from-square text-xs text-gray-400 group-hover:text-[#0070ba] transition"></i>
                                    </h4>
                                    <div class="flex items-center gap-4 text-xs text-gray-600 flex-wrap pt-0.5">
                                        <span>Total Tagihan: <strong>Rp {{ number_format($pem->total_tagihan, 0, ',', '.') }}</strong></span>
                                        @if ($pem->status_pembayaran === 'refund_pending' || $pem->peminjaman?->status === 'rejected')
                                            <span>Sisa Tagihan: <strong class="text-slate-500 font-bold text-sm">Rp 0</strong></span>
                                            <span class="text-purple-700 font-bold text-xs">Total Refund: Rp {{ number_format($pem->total_refund ?: $pem->total_terbayar, 0, ',', '.') }}</span>
                                        @else
                                            <span>Sisa Tagihan: <strong class="text-[#0070ba] font-bold text-sm">Rp {{ number_format($pem->sisa_tagihan, 0, ',', '.') }}</strong></span>
                                            @if ($pem->status_pembayaran === 'partial' && $pem->jatuh_tempo_pelunasan)
                                                <span class="text-slate-500 text-[11px]">Tenggat Pelunasan: <strong class="text-amber-700">{{ $pem->jatuh_tempo_pelunasan->translatedFormat('d M Y, H:i') }} WIB</strong></span>
                                            @elseif ($pem->jatuh_tempo_dp)
                                                <span class="text-slate-500 text-[11px]">Tenggat DP: <strong class="text-amber-700">{{ $pem->jatuh_tempo_dp->translatedFormat('d M Y, H:i') }} WIB</strong></span>
                                            @endif
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 self-end md:self-center">
                                    @if (!empty($config?->bank_utama) && !empty($config?->norek_utama))
                                        <span class="text-xs text-gray-500 hidden lg:inline">Tujuan: <strong>{{ $config->bank_utama }} - {{ $config->norek_utama }}</strong></span>
                                    @endif
                                    <span class="inline-flex items-center gap-1.5 px-4 py-2.5 {{ $pem->status_pembayaran === 'refund_pending' ? 'bg-purple-600 group-hover:bg-purple-700' : 'bg-[#0070ba] group-hover:bg-[#005a96]' }} text-white text-xs font-bold rounded-xl shadow-xs transition">
                                        <span>{{ $pem->status_pembayaran === 'refund_pending' ? 'Info Rekening Refund' : 'Buka Pembayaran' }}</span>
                                        <i class="fa-solid fa-chevron-right text-[10px] transform group-hover:translate-x-1 transition-transform"></i>
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            <!-- Riwayat Pembayaran Selesai / Lunas -->
            @if (!empty($completedPembayarans))
                <div class="{{ !empty($activePembayarans) ? 'pt-4 border-t border-slate-100' : '' }} space-y-3">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                            {{ empty($activePembayarans) ? 'Semua Pembayaran Telah Lunas' : 'Riwayat Pembayaran Selesai' }}
                        </h4>
                    </div>
                    <div class="space-y-3">
                        @foreach ($completedPembayarans as $pem)
                            <a href="{{ route('customer.pembayaran.show', $pem->id) }}"
                               class="group block border border-emerald-100 hover:border-emerald-500 rounded-2xl p-4 md:p-5 bg-emerald-50/20 hover:bg-emerald-50/50 shadow-xs hover:shadow-md transition-all cursor-pointer">
                                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-3">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">
                                                <i class="fa-regular fa-circle-check text-[10px]"></i> LUNAS
                                            </span>
                                            <span class="text-xs font-semibold text-gray-700">
                                                {{ $pem->peminjaman?->paketPeminjaman?->nama_paket ?: 'Paket Sewa Aula' }}
                                            </span>
                                            @if ($pem->peminjaman?->tanggal_mulai)
                                                <span class="text-xs text-gray-500">
                                                    &bull; Jadwal: {{ \Carbon\Carbon::parse($pem->peminjaman->tanggal_mulai)->format('d M Y') }}
                                                </span>
                                            @endif
                                        </div>
                                        <h5 class="text-sm font-bold text-gray-900 group-hover:text-emerald-700 transition flex items-center gap-1.5">
                                            <span>{{ $pem->kode_pembayaran }} &bull; Total: Rp {{ number_format($pem->total_tagihan, 0, ',', '.') }}</span>
                                            <i class="fa-solid fa-arrow-up-right-from-square text-xs text-gray-400 group-hover:text-emerald-600 transition"></i>
                                        </h5>
                                    </div>
                                    <span class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-700 text-white text-xs font-semibold rounded-xl group-hover:bg-emerald-600 shadow-xs transition">
                                        <span>Lihat Rincian & Nota</span>
                                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif

    </div>
</div>
@endsection

@push('modals')
<!-- ============================================================== -->
<!-- MODAL: NOTA PEMINJAMAN AULA -->
<!-- ============================================================== -->
<div id="modalNota" class="fixed inset-0 !m-0 z-[100] hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl border border-slate-100 overflow-hidden transform transition-all scale-95 duration-200 max-h-[90vh] flex flex-col" id="modalNotaBox">
        <!-- HEADER -->
        <div class="p-5 md:p-6 pb-3 bg-white flex items-center justify-between border-b border-slate-100/80 flex-shrink-0">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-blue-50 text-brand-600 flex items-center justify-center text-lg border border-blue-100/80 shadow-xs flex-shrink-0">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base md:text-lg tracking-tight">Nota Peminjaman Aula</h3>
                    <p class="text-slate-500 text-xs mt-0.5">Bukti rincian peminjaman aula SMK Negeri 2 Karanganyar</p>
                </div>
            </div>
            <button type="button" onclick="closeNotaModal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition" title="Tutup Modal">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- BODY -->
        <div class="p-5 md:p-6 space-y-4 overflow-y-auto">
            <div class="space-y-3 text-xs md:text-sm text-slate-700 bg-slate-50/70 p-5 rounded-2xl border border-slate-200/70">
                <div class="flex justify-between items-center py-2 border-b border-slate-200/60">
                    <span class="text-slate-500 font-medium">No. Tagihan / ID</span>
                    <span class="font-extrabold text-slate-900 font-mono tracking-tight" id="notaKode">-</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-slate-200/60">
                    <span class="text-slate-500 font-medium">Paket Peminjaman</span>
                    <span class="font-bold text-slate-900" id="notaPaket">-</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-slate-200/60">
                    <span class="text-slate-500 font-medium">Tanggal Pelaksanaan</span>
                    <span class="font-semibold text-slate-800" id="notaTanggal">-</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-slate-200/60">
                    <span class="text-slate-500 font-medium">Total Biaya</span>
                    <span class="font-black text-brand-600 text-base md:text-lg" id="notaHarga">Rp 0</span>
                </div>
                <div class="flex justify-between items-center pt-2">
                    <span class="text-slate-500 font-medium">Status Pembayaran</span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200" id="notaStatusBadge">
                        <i class="fa-solid fa-circle-check text-[10px]" id="notaStatusIcon"></i>
                        <span id="notaStatus">LUNAS</span>
                    </span>
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="p-5 md:p-6 pt-3 border-t border-slate-100 flex items-center justify-end gap-2.5 bg-slate-50/50 flex-shrink-0">
            <button type="button" onclick="closeNotaModal()"
                class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs md:text-sm font-semibold transition">
                Tutup
            </button>
            <button type="button" onclick="window.print()"
                class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs md:text-sm font-bold shadow-sm transition inline-flex items-center gap-2">
                <i class="fa-solid fa-print text-xs"></i>
                <span>Cetak Nota</span>
            </button>
        </div>
    </div>
<!-- MODAL PEMBATALAN PENGAJUAN -->
<div id="modalCancel" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 hidden">
    <div id="modalCancelBox" class="bg-white rounded-3xl max-w-lg w-full p-6 md:p-7 figma-card-shadow transform transition-all duration-200 scale-95 space-y-5">
        <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
            <div class="w-10 h-10 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg font-bold flex-shrink-0">
                <i class="fa-solid fa-ban"></i>
            </div>
            <div>
                <h3 class="font-black text-slate-900 text-base md:text-lg">Konfirmasi Pembatalan Pengajuan</h3>
                <p class="text-xs text-slate-500">Peminjaman Aula SMKN 2 Karanganyar</p>
            </div>
        </div>

        <form id="formCancel" action="" method="POST" class="space-y-4 m-0">
            @csrf
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/70 space-y-2 text-xs">
                <div class="flex justify-between items-center text-slate-600">
                    <span class="font-medium">Invoice:</span>
                    <span class="font-bold font-mono text-slate-900" id="cancelInvoice">-</span>
                </div>
                <div class="flex justify-between items-center text-slate-600">
                    <span class="font-medium">Paket:</span>
                    <span class="font-bold text-slate-900" id="cancelPaket">-</span>
                </div>
                <div class="flex justify-between items-center text-slate-600">
                    <span class="font-medium">Batas Toleransi Refund:</span>
                    <span class="font-bold text-rose-700" id="cancelDeadlineText">-</span>
                </div>
            </div>

            <!-- Dynamic Notification Box for Refund Eligibility -->
            <div id="cancelEligibleBox" class="p-3.5 bg-emerald-50/80 border border-emerald-200 text-emerald-900 rounded-2xl text-[11px] leading-relaxed space-y-1">
                <div class="font-bold flex items-center gap-1.5 text-emerald-800">
                    <i class="fa-solid fa-circle-check text-xs"></i>
                    <span>Toleransi Pengembalian Dana (Refund) Aktif:</span>
                </div>
                <p>
                    Pembatalan diajukan dalam batas toleransi (maksimal <strong>H-<span id="cancelHMinTextEligible"></span></strong>).
                </p>
                <div id="refundInfoBox" class="font-semibold text-purple-900 pt-1 border-t border-emerald-200/60 hidden">
                    Dana pembayaran yang telah terverifikasi sebesar <strong class="text-purple-700" id="cancelRefundNominalText">Rp 0</strong> akan dikembalikan (refund) oleh pihak admin sekolah setelah pembatalan diproses.
                </div>
            </div>

            <div id="cancelExpiredBox" class="p-3.5 bg-rose-50/80 border border-rose-200 text-rose-900 rounded-2xl text-[11px] leading-relaxed space-y-1 hidden">
                <div class="font-bold flex items-center gap-1.5 text-rose-800">
                    <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                    <span>Perhatian: Melebihi Batas Offset Pembatalan</span>
                </div>
                <p>
                    Anda dapat membatalkan pengajuan aula ini kapan saja. Namun karena pembatalan diajukan <strong>melebihi batas offset cancelation (H-<span id="cancelHMinTextExpired"></span>)</strong>, maka seluruh dana pembayaran yang telah disetorkan <strong>TIDAK DAPAT DIREUND (DANA HANGUS)</strong>.
                </p>
            </div>

            <div class="space-y-1.5">
                <label for="alasan_pembatalan" class="block text-xs font-bold text-slate-700">
                    Alasan Pembatalan (Opsional):
                </label>
                <textarea name="alasan_pembatalan" id="alasan_pembatalan" rows="3"
                          placeholder="Tuliskan alasan pembatalan peminjaman..."
                          class="w-full p-3 bg-white border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-2 border-t border-slate-100">
                <button type="button" onclick="closeCancelModal()"
                        class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs md:text-sm font-semibold transition cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs md:text-sm font-bold shadow-xs transition flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-ban text-xs"></i>
                    <span>Ya, Batalkan Pengajuan</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endpush

@push('scripts')
<script>
    function showNotaModal(kode, paket, harga, tanggal, status) {
        document.getElementById('notaKode').innerText = kode;
        document.getElementById('notaPaket').innerText = paket;
        document.getElementById('notaHarga').innerText = 'Rp ' + harga;
        document.getElementById('notaTanggal').innerText = tanggal;
        document.getElementById('notaStatus').innerText = status;

        const badge = document.getElementById('notaStatusBadge');
        const icon = document.getElementById('notaStatusIcon');
        if (status === 'LUNAS' || status === 'TERBAYAR') {
            badge.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200';
            icon.className = 'fa-solid fa-circle-check text-[10px]';
        } else {
            badge.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200';
            icon.className = 'fa-solid fa-clock text-[10px]';
        }

        const modal = document.getElementById('modalNota');
        const box = document.getElementById('modalNotaBox');
        document.body.classList.add('overflow-hidden');
        modal.classList.remove('hidden');
        setTimeout(() => {
            box.classList.remove('scale-95');
            box.classList.add('scale-100');
        }, 10);
    }

    function closeNotaModal() {
        const modal = document.getElementById('modalNota');
        const box = document.getElementById('modalNotaBox');
        box.classList.remove('scale-100');
        box.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }, 150);
    }

    function openCancelModal(id, invoice, paket, hMin, deadline, routeUrl, terbayar, isEligible) {
        document.getElementById('cancelInvoice').innerText = invoice;
        document.getElementById('cancelPaket').innerText = paket;
        document.getElementById('cancelDeadlineText').innerText = 'H-' + hMin + ' (' + deadline + ' WIB)';
        document.getElementById('formCancel').action = routeUrl;

        const eligibleBox = document.getElementById('cancelEligibleBox');
        const expiredBox = document.getElementById('cancelExpiredBox');
        const refundInfo = document.getElementById('refundInfoBox');

        if (isEligible) {
            eligibleBox.classList.remove('hidden');
            expiredBox.classList.add('hidden');
            document.getElementById('cancelHMinTextEligible').innerText = hMin;
            if (terbayar && terbayar !== '0') {
                document.getElementById('cancelRefundNominalText').innerText = 'Rp ' + terbayar;
                refundInfo.classList.remove('hidden');
            } else {
                refundInfo.classList.add('hidden');
            }
        } else {
            eligibleBox.classList.add('hidden');
            expiredBox.classList.remove('hidden');
            document.getElementById('cancelHMinTextExpired').innerText = hMin;
        }

        const modal = document.getElementById('modalCancel');
        const box = document.getElementById('modalCancelBox');
        document.body.classList.add('overflow-hidden');
        modal.classList.remove('hidden');
        setTimeout(() => {
            box.classList.remove('scale-95');
            box.classList.add('scale-100');
        }, 10);
    }

    function closeCancelModal() {
        const modal = document.getElementById('modalCancel');
        const box = document.getElementById('modalCancelBox');
        if (!modal) return;
        box.classList.remove('scale-100');
        box.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }, 150);
    }

    // Close on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeNotaModal();
            closeCancelModal();
        }
    });

    // Close on click outside box
    const modalNota = document.getElementById('modalNota');
    if (modalNota) {
        modalNota.addEventListener('click', function(event) {
            if (event.target === modalNota) {
                closeNotaModal();
            }
        });
    }

    const modalCancel = document.getElementById('modalCancel');
    if (modalCancel) {
        modalCancel.addEventListener('click', function(event) {
            if (event.target === modalCancel) {
                closeCancelModal();
            }
        });
    }
</script>
@endpush
