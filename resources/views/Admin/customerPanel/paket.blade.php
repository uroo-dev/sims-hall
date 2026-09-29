@extends('Admin.layout.app')

@section('title', 'Paket Peminjaman - SIMS Aula SMK N 2 Karanganyar')
@section('page_title', 'Paket Peminjaman')

@section('content')
<div class="space-y-6">

    <!-- Container Grid Paket Peminjaman sesuai Screenshot 1 -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch pt-3">

        <!-- KOLOM 1 (KIRI): CARD UNGGULAN (Tall Card) -->
        <div class="lg:col-span-4 flex flex-col">
            @php
                $unggulan = $paketUnggulan;
                $hargaUnggulan = $unggulan ? number_format($unggulan->harga, 0, ',', '.') : '6.000.000';
            @endphp
            <div class="bg-white rounded-2xl border-2 border-[#0070ba] figma-card-shadow p-7 relative flex flex-col justify-between h-full pt-8">

                <!-- Badge Kategori di Atas Tengah -->
                <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-[#0070ba] text-white text-xs font-bold px-8 py-1 rounded-full shadow-sm whitespace-nowrap">
                    {{ $unggulan?->nama_paket ?: 'Unggulan' }}
                </div>

                <div class="space-y-6">
                    <!-- Harga Paket -->
                    <div class="pt-2 text-gray-700 font-medium">
                        <span class="text-sm">Rp. </span>
                        <span class="text-3xl font-black text-[#0070ba] tracking-tight">{{ $hargaUnggulan }}</span>
                        <span class="text-xs text-gray-500"> / 12 Jam</span>
                    </div>

                    <!-- Fitur Utama Centang -->
                    <ul class="space-y-3 text-xs md:text-sm text-gray-700">
                        <li class="flex items-center gap-2.5">
                            <span class="text-gray-400 text-sm">
                                <i class="fa-regular fa-circle-check"></i>
                            </span>
                            <span>Sound System Medium</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <span class="text-gray-400 text-sm">
                                <i class="fa-regular fa-circle-check"></i>
                            </span>
                            <span>Mic 4</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <span class="text-gray-400 text-sm">
                                <i class="fa-regular fa-circle-check"></i>
                            </span>
                            <span>500 Kursi + Cover</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <span class="text-gray-400 text-sm">
                                <i class="fa-regular fa-circle-check"></i>
                            </span>
                            <span>Proyektor 2</span>
                        </li>
                    </ul>

                    <!-- Sub Fasilitas Tambahan Bernomor -->
                    <div class="space-y-2 pt-2 border-t border-gray-100">
                        <span class="block text-xs md:text-sm font-semibold text-gray-800">Fasilitas :</span>
                        <ol class="space-y-1.5 text-xs text-gray-600 pl-4 list-decimal">
                            <li>Wifi 1080mbps</li>
                            <li>Podium</li>
                            <li>Parkir Luas (Gratis)</li>
                            <li>Staff Keamanan</li>
                        </ol>
                    </div>
                </div>

                <!-- Tombol Aksi Bawah -->
                <div class="pt-8 space-y-2">
                    <button type="button"
                            onclick="openDetailModal({{ $unggulan ? $unggulan->toJson() : '{}' }})"
                            class="w-full text-xs text-[#0070ba] hover:underline font-semibold text-center block py-1">
                        <i class="fa-solid fa-circle-info mr-1"></i> Lihat Detail Paket
                    </button>
                    <a href="{{ route('customer.cek-peminjaman') }}"
                       class="w-full bg-[#0070ba] hover:bg-[#005a96] text-white text-sm font-bold py-2.5 px-4 rounded-xl text-center block shadow transition-colors">
                        Pilih Paket
                    </a>
                </div>

            </div>
        </div>

        <!-- KOLOM 2 & 3 (KANAN): GRID 2x2 CARD PAKET LAINNYA -->
        <div class="lg:col-span-8 grid grid-cols-1 md:grid-cols-2 gap-8">

            @php
                $daftarKategori = [
                    'terjangkau' => ['title' => 'Terjangkau', 'default_price' => '1.500.000', 'durasi' => '4 Jam', 'kursi' => '100 Kursi', 'mic' => 'Mic 2', 'sound' => 'Sound System Standar', 'proyektor' => 'Proyektor 1'],
                    'standar 1'  => ['title' => 'Standar 1', 'default_price' => '3.500.000', 'durasi' => '12 Jam', 'kursi' => '500 Kursi + Cover', 'mic' => 'Mic 4', 'sound' => 'Sound System Medium', 'proyektor' => 'Proyektor 2'],
                    'standar 2'  => ['title' => 'Standar 2', 'default_price' => '4.400.000', 'durasi' => '12 Jam', 'kursi' => '500 Kursi + Cover', 'mic' => 'Mic 4', 'sound' => 'Sound System Medium', 'proyektor' => 'Proyektor 2'],
                    'standar 3'  => ['title' => 'Standar 3', 'default_price' => '5.500.000', 'durasi' => '12 Jam', 'kursi' => '500 Kursi + Cover', 'mic' => 'Mic 4', 'sound' => 'Sound System Medium', 'proyektor' => 'Proyektor 2'],
                ];
            @endphp

            @foreach ($daftarKategori as $katKey => $meta)
                @php
                    $pkt = $pakets->firstWhere('kategori', $katKey);
                    $hargaFormatted = $pkt ? number_format($pkt->harga, 0, ',', '.') : $meta['default_price'];
                @endphp

                <div class="bg-white rounded-2xl border-2 border-[#0070ba] figma-card-shadow p-6 relative flex flex-col justify-between pt-8 hover:shadow-md transition-shadow">

                    <!-- Badge Kategori di Atas Tengah -->
                    <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-[#0070ba] text-white text-xs font-bold px-7 py-1 rounded-full shadow-sm whitespace-nowrap">
                        {{ $pkt?->nama_paket ?: $meta['title'] }}
                    </div>

                    <div class="space-y-5">
                        <!-- Harga Paket -->
                        <div class="pt-2 text-gray-700 font-medium">
                            <span class="text-sm">Rp. </span>
                            <span class="text-2xl md:text-3xl font-black text-[#0070ba] tracking-tight">{{ $hargaFormatted }}</span>
                            <span class="text-xs text-gray-500"> / {{ $meta['durasi'] }}</span>
                        </div>

                        <!-- Fitur Centang -->
                        <ul class="space-y-2.5 text-xs text-gray-700">
                            <li class="flex items-center gap-2.5">
                                <span class="text-gray-400 text-sm">
                                    <i class="fa-regular fa-circle-check"></i>
                                </span>
                                <span>{{ $meta['sound'] }}</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <span class="text-gray-400 text-sm">
                                    <i class="fa-regular fa-circle-check"></i>
                                </span>
                                <span>{{ $meta['mic'] }}</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <span class="text-gray-400 text-sm">
                                    <i class="fa-regular fa-circle-check"></i>
                                </span>
                                <span>{{ $meta['kursi'] }}</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <span class="text-gray-400 text-sm">
                                    <i class="fa-regular fa-circle-check"></i>
                                </span>
                                <span>{{ $meta['proyektor'] }}</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Tombol Aksi Bawah -->
                    <div class="pt-6 space-y-2">
                        <button type="button"
                                onclick="openDetailModal({{ $pkt ? $pkt->toJson() : json_encode(['nama_paket' => $meta['title'], 'harga' => $meta['default_price'], 'deskripsi' => 'Paket fasilitas aula sekolah lengkap.']) }})"
                                class="w-full text-xs text-[#0070ba] hover:underline font-semibold text-center block py-1">
                            <i class="fa-solid fa-circle-info mr-1"></i> Detail Paket
                        </button>
                        <a href="{{ route('customer.cek-peminjaman') }}"
                           class="w-full border-2 border-[#0070ba] text-[#0070ba] hover:bg-[#0070ba] hover:text-white text-sm font-bold py-2 px-4 rounded-xl text-center block transition-all">
                            Pilih Paket
                        </a>
                    </div>

                </div>
            @endforeach

        </div>

    </div>

</div>

<!-- MODAL DETAIL PAKET PEMINJAMAN -->
<div id="modalDetailPaket" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 md:p-7 shadow-2xl relative space-y-5 animate-in fade-in zoom-in-95 duration-200">

        <!-- Header Modal Polos Putih Sesuai Ketentuan User -->
        <div class="flex items-start justify-between pb-3 border-b border-gray-100">
            <div>
                <span class="inline-block px-3 py-1 bg-blue-50 text-[#0070ba] text-xs font-bold rounded-full mb-1" id="modalKategoriBadge">
                    Paket Aula
                </span>
                <h3 class="text-xl font-bold text-gray-900" id="modalNamaPaket">Detail Paket</h3>
            </div>
            <button type="button" onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Body Modal -->
        <div class="space-y-4">
            <!-- Harga -->
            <div class="bg-blue-50/50 rounded-xl p-4 flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-600">Biaya Sewa Paket :</span>
                <span class="text-xl font-black text-[#0070ba]" id="modalHarga">Rp 0</span>
            </div>

            <!-- Deskripsi -->
            <div class="space-y-1">
                <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Deskripsi Paket</h4>
                <p class="text-xs md:text-sm text-gray-600 leading-relaxed" id="modalDeskripsi">
                    Tidak ada deskripsi.
                </p>
            </div>

            <!-- Daftar Fasilitas Terkoneksi -->
            <div class="space-y-2">
                <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Fasilitas Termasuk :</h4>
                <div id="modalFasilitasContainer" class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-gray-700">
                    <!-- Dynamic facility pills -->
                </div>
            </div>
        </div>

        <!-- Footer Modal -->
        <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
            <button type="button" onclick="closeDetailModal()" class="px-5 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-100 rounded-xl transition">
                Tutup
            </button>
            <a href="{{ route('customer.cek-peminjaman') }}" class="px-5 py-2 text-xs font-bold bg-[#0070ba] hover:bg-blue-700 text-white rounded-xl shadow transition">
                Ajukan Sekarang
            </a>
        </div>

    </div>
</div>

@push('scripts')
<script>
    function openDetailModal(paket) {
        if (!paket) return;

        document.getElementById('modalNamaPaket').innerText = paket.nama_paket || 'Paket Peminjaman';
        document.getElementById('modalKategoriBadge').innerText = (paket.kategori ? paket.kategori.toUpperCase() : 'PAKET AULA');

        const harga = paket.harga ? new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(paket.harga) : 'Rp 0';
        document.getElementById('modalHarga').innerText = harga;

        document.getElementById('modalDeskripsi').innerText = paket.deskripsi || 'Paket sewa fasilitas aula SMK Negeri 2 Karanganyar.';

        const container = document.getElementById('modalFasilitasContainer');
        container.innerHTML = '';

        if (paket.facilities && paket.facilities.length > 0) {
            paket.facilities.forEach(f => {
                const el = document.createElement('div');
                el.className = 'flex items-center gap-2 bg-gray-50 border border-gray-100 rounded-lg p-2';
                el.innerHTML = `<i class="fa-regular fa-circle-check text-[#0070ba] text-xs"></i> <span>${f.judul}</span>`;
                container.appendChild(el);
            });
        } else {
            const defaultFasilitas = ['Sound System', 'Mic Wireless', 'Kursi & Cover', 'Proyektor & Layar', 'Wifi Area', 'Staff Keamanan'];
            defaultFasilitas.forEach(f => {
                const el = document.createElement('div');
                el.className = 'flex items-center gap-2 bg-gray-50 border border-gray-100 rounded-lg p-2';
                el.innerHTML = `<i class="fa-regular fa-circle-check text-[#0070ba] text-xs"></i> <span>${f}</span>`;
                container.appendChild(el);
            });
        }

        document.getElementById('modalDetailPaket').classList.remove('hidden');
    }

    function closeDetailModal() {
        document.getElementById('modalDetailPaket').classList.add('hidden');
    }
</script>
@endpush
@endsection
