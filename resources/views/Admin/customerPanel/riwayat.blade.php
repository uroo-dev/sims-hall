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
                                @if ($isTahap1Verified)
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
                                @if ($isTahap2Verified)
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
                                @if ($isTerbayar)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-md text-[11px] font-semibold bg-[#00a844] text-white">
                                        <i class="fa-regular fa-circle-check text-[10px]"></i> Terbayar
                                    </span>
                                @elseif ($statusBayar === 'partial')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-md text-[11px] font-semibold bg-blue-600 text-white">
                                        DP Terverifikasi
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-md text-[11px] font-semibold bg-amber-500 text-white">
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <!-- Aksi -->
                            <td class="py-4 px-3 text-center">
                                <button type="button"
                                        onclick="showNotaModal('{{ $kode }}', '{{ $namaPaket }}', '{{ number_format($harga, 0, ',', '.') }}', '{{ $tanggal }}', '{{ $isTerbayar ? 'LUNAS' : 'PENDING' }}')"
                                        class="inline-flex items-center gap-1.5 px-3 py-1 bg-[#0070ba] hover:bg-[#005a96] text-white text-xs font-semibold rounded-md shadow-xs transition-colors">
                                    <i class="fa-regular fa-circle-dot text-[10px]"></i>
                                    <span>Nota</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr class="hover:bg-gray-50/70 transition-colors">
                            <td class="py-4 px-3 font-semibold text-gray-900">ORD-002</td>
                            <td class="py-4 px-3 font-medium">Standar 2</td>
                            <td class="py-4 px-3">Rp.0</td>
                            <td class="py-4 px-3 text-gray-600">2025-01-22</td>
                            <td class="py-4 px-3 text-center">
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-md text-[11px] font-semibold bg-[#00a844] text-white">
                                    <i class="fa-regular fa-circle-check text-[10px]"></i> Terverifikasi
                                </span>
                            </td>
                            <td class="py-4 px-3 text-center">
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-md text-[11px] font-semibold bg-[#00a844] text-white">
                                    <i class="fa-regular fa-circle-check text-[10px]"></i> Terverifikasi
                                </span>
                            </td>
                            <td class="py-4 px-3 text-center">
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-md text-[11px] font-semibold bg-[#00a844] text-white">
                                    <i class="fa-regular fa-circle-check text-[10px]"></i> Terbayar
                                </span>
                            </td>
                            <td class="py-4 px-3 text-center">
                                <button type="button"
                                        onclick="showNotaModal('ORD-002', 'Standar 2', '0', '2025-01-22', 'LUNAS')"
                                        class="inline-flex items-center gap-1.5 px-3 py-1 bg-[#0070ba] hover:bg-[#005a96] text-white text-xs font-semibold rounded-md shadow-xs transition-colors">
                                    <i class="fa-regular fa-circle-dot text-[10px]"></i>
                                    <span>Nota</span>
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    <!-- CARD 2: PEMBAYARAN SESUAI SCREENSHOT 2 -->
    <div class="bg-white rounded-2xl figma-card-shadow p-6 md:p-8 border border-blue-50/50 space-y-6">

        <h3 class="text-base md:text-lg font-bold text-gray-900 tracking-tight">
            Pembayaran
        </h3>

        <!-- Status Box: Sudah Terbayar Semua / Rincian Tagihan -->
        @if (! $hasUnpaid)
            <div class="py-16 md:py-24 flex items-center justify-center text-center">
                <span class="text-base md:text-lg font-medium text-gray-900 tracking-wide">
                    Sudah terbayar semua
                </span>
            </div>
        @else
            <!-- Tagihan Aktif yang Memerlukan Pembayaran -->
            <div class="space-y-4">
                @foreach ($activePembayarans as $pem)
                    <div class="border border-blue-200 rounded-xl p-5 bg-blue-50/20 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        <div class="space-y-1">
                            <span class="inline-block px-2.5 py-0.5 rounded text-[11px] font-bold bg-amber-100 text-amber-800">
                                Tagihan Aktif ({{ strtoupper($pem->status_pembayaran) }})
                            </span>
                            <h4 class="text-base font-bold text-gray-900">{{ $pem->kode_pembayaran }}</h4>
                            <p class="text-xs text-gray-500">
                                Sisa Tagihan: <strong class="text-[#0070ba] font-bold">Rp {{ number_format($pem->sisa_tagihan, 0, ',', '.') }}</strong>
                                dari total Rp {{ number_format($pem->total_tagihan, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="text-xs text-gray-500">Transfer ke: <strong>Bank Jateng - 1234567890</strong></span>
                            <button type="button" class="px-4 py-2 bg-[#0070ba] text-white text-xs font-bold rounded-lg hover:bg-[#005a96] transition">
                                Konfirmasi Transfer
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>

</div>

<!-- MODAL NOTA PEMINJAMAN -->
<div id="modalNota" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 md:p-7 shadow-2xl relative space-y-5 animate-in fade-in zoom-in-95 duration-200">
        <!-- Header Polos Putih -->
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Nota Peminjaman Aula</h3>
                <span class="text-xs text-gray-500">SMK Negeri 2 Karanganyar</span>
            </div>
            <button type="button" onclick="closeNotaModal()" class="text-gray-400 hover:text-gray-600 p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Body Nota -->
        <div class="space-y-3 text-xs md:text-sm text-gray-700 bg-gray-50/60 p-4 rounded-xl border border-gray-100">
            <div class="flex justify-between">
                <span class="text-gray-500">No. Tagihan:</span>
                <span class="font-bold text-gray-900" id="notaKode">-</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Paket:</span>
                <span class="font-semibold text-gray-900" id="notaPaket">-</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Tanggal Pelaksanaan:</span>
                <span class="font-medium text-gray-800" id="notaTanggal">-</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Total Biaya:</span>
                <span class="font-black text-[#0070ba]" id="notaHarga">Rp 0</span>
            </div>
            <div class="flex justify-between pt-2 border-t border-gray-200">
                <span class="text-gray-500">Status Pembayaran:</span>
                <span class="font-bold text-[#00a844]" id="notaStatus">LUNAS</span>
            </div>
        </div>

        <!-- Footer -->
        <div class="pt-3 flex items-center justify-end gap-2">
            <button type="button" onclick="closeNotaModal()" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-100 rounded-lg">
                Tutup
            </button>
            <button type="button" onclick="window.print()" class="px-4 py-2 text-xs font-bold bg-[#0070ba] text-white rounded-lg hover:bg-blue-700">
                <i class="fa-solid fa-print mr-1"></i> Cetak Nota
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function showNotaModal(kode, paket, harga, tanggal, status) {
        document.getElementById('notaKode').innerText = kode;
        document.getElementById('notaPaket').innerText = paket;
        document.getElementById('notaHarga').innerText = 'Rp ' + harga;
        document.getElementById('notaTanggal').innerText = tanggal;
        document.getElementById('notaStatus').innerText = status;

        document.getElementById('modalNota').classList.remove('hidden');
    }

    function closeNotaModal() {
        document.getElementById('modalNota').classList.add('hidden');
    }
</script>
@endpush
@endsection
