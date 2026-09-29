@extends('Admin.layout.app')

@section('title', 'Paket Peminjaman - SIMS Aula SMK N 2 Karanganyar')
@section('page_title', 'Paket Peminjaman')

@section('content')
<div class="space-y-6">

    @if ($pakets->isEmpty())
        <!-- EMPTY CASE: KETIKA BELUM ADA DATA PAKET PEMINJAMAN -->
        <div class="bg-white rounded-2xl figma-card-shadow p-12 text-center border border-blue-50/50">
            <div class="flex flex-col items-center justify-center text-gray-400 space-y-3">
                <div class="w-16 h-16 rounded-full bg-blue-50/60 border border-blue-100 flex items-center justify-center text-[#0070ba] text-3xl">
                    <i class="fa-solid fa-box-open"></i>
                </div>
                <div class="space-y-1">
                    <h3 class="text-base font-bold text-gray-800">Belum Ada Paket Peminjaman</h3>
                    <p class="text-xs md:text-sm text-gray-500 max-w-sm">Saat ini belum ada data paket peminjaman aula yang tersedia di sistem.</p>
                </div>
            </div>
        </div>
    @else
        <!-- Container Grid Paket Peminjaman -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch pt-3">

            @if ($paketUnggulan)
                <!-- KOLOM 1 (KIRI): CARD UNGGULAN (Tall Card) -->
                <div class="{{ $paketLainnya->isNotEmpty() ? 'lg:col-span-4' : 'lg:col-span-6 lg:col-start-4' }} flex flex-col">
                    @php
                        $unggulan = $paketUnggulan;
                        $hargaUnggulan = number_format($unggulan->harga, 0, ',', '.');
                    @endphp
                    <div class="bg-white rounded-2xl border-2 border-[#0070ba] figma-card-shadow p-7 relative flex flex-col justify-between h-full pt-8">

                        <!-- Badge Kategori di Atas Tengah -->
                        <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-[#0070ba] text-white text-xs font-bold px-8 py-1 rounded-full shadow-sm whitespace-nowrap">
                            {{ $unggulan->nama_paket ?: ucfirst($unggulan->kategori) }}
                        </div>

                        <div class="space-y-6">
                            <!-- Harga Paket -->
                            <div class="pt-2 text-gray-700 font-medium">
                                <span class="text-sm">Rp. </span>
                                <span class="text-3xl font-black text-[#0070ba] tracking-tight">{{ $hargaUnggulan }}</span>
                                <span class="text-xs text-gray-500"> / 12 Jam</span>
                            </div>

                            @if ($unggulan->facilities->isNotEmpty())
                                @php
                                    $mainFacilities = $unggulan->facilities->take(4);
                                    $extraFacilities = $unggulan->facilities->slice(4);
                                @endphp
                                <!-- Fitur Utama Centang -->
                                <ul class="space-y-3 text-xs md:text-sm text-gray-700">
                                    @foreach ($mainFacilities as $fac)
                                        <li class="flex items-center gap-2.5">
                                            <span class="text-gray-400 text-sm">
                                                <i class="fa-regular fa-circle-check"></i>
                                            </span>
                                            <span>{{ $fac->judul }}</span>
                                        </li>
                                    @endforeach
                                </ul>

                                @if ($extraFacilities->isNotEmpty())
                                    <!-- Sub Fasilitas Tambahan Bernomor -->
                                    <div class="space-y-2 pt-2 border-t border-gray-100">
                                        <span class="block text-xs md:text-sm font-semibold text-gray-800">Fasilitas :</span>
                                        <ol class="space-y-1.5 text-xs text-gray-600 pl-4 list-decimal">
                                            @foreach ($extraFacilities as $fac)
                                                <li>{{ $fac->judul }}</li>
                                            @endforeach
                                        </ol>
                                    </div>
                                @endif
                            @elseif (!empty($unggulan->deskripsi))
                                <div class="text-xs md:text-sm text-gray-600 leading-relaxed pt-2">
                                    {{ $unggulan->deskripsi }}
                                </div>
                            @else
                                <div class="text-xs text-gray-400 italic pt-2">
                                    Belum ada daftar fasilitas untuk paket ini.
                                </div>
                            @endif
                        </div>

                        <!-- Tombol Aksi Bawah -->
                        <div class="pt-8 space-y-2">
                            <button type="button"
                                    onclick="openDetailModal({{ $unggulan->toJson() }})"
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
            @endif

            @if ($paketLainnya->isNotEmpty())
                <!-- KOLOM 2 & 3 (KANAN): GRID 2x2 CARD PAKET LAINNYA -->
                <div class="lg:col-span-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                    @foreach ($paketLainnya as $pkt)
                        @php
                            $hargaFormatted = number_format($pkt->harga, 0, ',', '.');
                        @endphp
                        <div class="bg-white rounded-2xl border-2 border-[#0070ba] figma-card-shadow p-6 relative flex flex-col justify-between pt-8 hover:shadow-md transition-shadow">

                            <!-- Badge Kategori di Atas Tengah -->
                            <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-[#0070ba] text-white text-xs font-bold px-7 py-1 rounded-full shadow-sm whitespace-nowrap">
                                {{ $pkt->nama_paket ?: ucfirst($pkt->kategori) }}
                            </div>

                            <div class="space-y-5">
                                <!-- Harga Paket -->
                                <div class="pt-2 text-gray-700 font-medium">
                                    <span class="text-sm">Rp. </span>
                                    <span class="text-2xl md:text-3xl font-black text-[#0070ba] tracking-tight">{{ $hargaFormatted }}</span>
                                    <span class="text-xs text-gray-500"> / 12 Jam</span>
                                </div>

                                <!-- Fitur Centang -->
                                @if ($pkt->facilities->isNotEmpty())
                                    <ul class="space-y-2.5 text-xs text-gray-700">
                                        @foreach ($pkt->facilities->take(4) as $fac)
                                            <li class="flex items-center gap-2.5">
                                                <span class="text-gray-400 text-sm">
                                                    <i class="fa-regular fa-circle-check"></i>
                                                </span>
                                                <span>{{ $fac->judul }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @elseif (!empty($pkt->deskripsi))
                                    <p class="text-xs text-gray-600 line-clamp-3">
                                        {{ $pkt->deskripsi }}
                                    </p>
                                @else
                                    <p class="text-xs text-gray-400 italic">
                                        Belum ada daftar fasilitas untuk paket ini.
                                    </p>
                                @endif
                            </div>

                            <!-- Tombol Aksi Bawah -->
                            <div class="pt-6 space-y-2">
                                <button type="button"
                                        onclick="openDetailModal({{ $pkt->toJson() }})"
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
            @endif

        </div>
    @endif

</div>
@endsection

@push('modals')
<!-- ============================================================== -->
<!-- MODAL: DETAIL PAKET PEMINJAMAN -->
<!-- ============================================================== -->
<div id="modalDetailPaket" class="fixed inset-0 !m-0 z-[100] hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl border border-slate-100 overflow-hidden transform transition-all scale-95 duration-200 max-h-[90vh] flex flex-col" id="modalDetailPaketBox">

        <!-- HEADER -->
        <div class="p-5 md:p-6 pb-3 bg-white flex items-center justify-between border-b border-slate-100/80 flex-shrink-0">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-blue-50 text-brand-600 flex items-center justify-center text-lg border border-blue-100/80 shadow-xs flex-shrink-0">
                    <i class="fa-solid fa-cube"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="font-extrabold text-slate-900 text-base md:text-lg tracking-tight" id="modalNamaPaket">Detail Paket</h3>
                        <span class="px-2.5 py-0.5 bg-blue-50 text-brand-700 border border-blue-100 text-[10px] font-bold rounded-full uppercase" id="modalKategoriBadge">
                            PAKET AULA
                        </span>
                    </div>
                    <p class="text-slate-500 text-xs mt-0.5">Informasi rincian fasilitas dan spesifikasi sewa aula</p>
                </div>
            </div>
            <button type="button" onclick="closeDetailModal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition" title="Tutup Modal">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- BODY -->
        <div class="p-5 md:p-6 space-y-4 overflow-y-auto">
            <!-- Harga Card -->
            <div class="bg-gradient-to-r from-blue-50/80 to-slate-50 border border-blue-100/80 rounded-2xl p-4 flex items-center justify-between">
                <div>
                    <span class="block text-slate-500 text-xs font-semibold uppercase tracking-wider">Biaya Sewa Paket</span>
                    <span class="text-xs text-slate-400">Durasi pemakaian standar aula</span>
                </div>
                <div class="text-right">
                    <span class="text-xl md:text-2xl font-black text-brand-600 tracking-tight" id="modalHarga">Rp 0</span>
                    <span class="block text-[11px] text-slate-500">/ 12 Jam</span>
                </div>
            </div>

            <!-- Deskripsi -->
            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                    Deskripsi Paket
                </label>
                <div class="p-3.5 bg-slate-50 border border-slate-200/80 rounded-xl text-xs md:text-sm text-slate-700 leading-relaxed" id="modalDeskripsi">
                    Tidak ada deskripsi.
                </div>
            </div>

            <!-- Fasilitas Termasuk -->
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                    Fasilitas Termasuk
                </label>
                <div id="modalFasilitasContainer" class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-slate-700 max-h-48 overflow-y-auto pr-1">
                    <!-- Dynamic facility pills -->
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="p-5 md:p-6 pt-3 border-t border-slate-100 flex items-center justify-end gap-2.5 bg-slate-50/50 flex-shrink-0">
            <button type="button" onclick="closeDetailModal()"
                class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs md:text-sm font-semibold transition">
                Tutup
            </button>
            <a href="{{ route('customer.cek-peminjaman') }}"
                class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs md:text-sm font-bold shadow-sm transition inline-flex items-center gap-2">
                <i class="fa-solid fa-arrow-right text-xs"></i>
                <span>Pilih Paket</span>
            </a>
        </div>

    </div>
</div>
@endpush

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
                el.className = 'flex items-center gap-2 bg-slate-50 border border-slate-200/70 rounded-xl p-2.5 text-xs text-slate-700 font-medium';
                el.innerHTML = `<i class="fa-solid fa-circle-check text-brand-600 text-xs flex-shrink-0"></i> <span class="truncate">${f.judul}</span>`;
                container.appendChild(el);
            });
        } else {
            const el = document.createElement('div');
            el.className = 'col-span-full text-slate-400 text-xs italic py-2';
            el.innerText = 'Tidak ada fasilitas khusus yang terdaftar.';
            container.appendChild(el);
        }

        const modal = document.getElementById('modalDetailPaket');
        const box = document.getElementById('modalDetailPaketBox');
        document.body.classList.add('overflow-hidden');
        modal.classList.remove('hidden');
        setTimeout(() => {
            box.classList.remove('scale-95');
            box.classList.add('scale-100');
        }, 10);
    }

    function closeDetailModal() {
        const modal = document.getElementById('modalDetailPaket');
        const box = document.getElementById('modalDetailPaketBox');
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
            closeDetailModal();
        }
    });

    // Close on click outside box
    const modalDetailPaket = document.getElementById('modalDetailPaket');
    if (modalDetailPaket) {
        modalDetailPaket.addEventListener('click', function(event) {
            if (event.target === modalDetailPaket) {
                closeDetailModal();
            }
        });
    }
</script>
@endpush
