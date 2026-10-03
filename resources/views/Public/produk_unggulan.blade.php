@extends('Public.layout.app')

@section('title', ($produkUnggulan->judul ?: 'Produk Unggulan').' - '.config('sekolah.nama'))
@section('meta_description', \Illuminate\Support\Str::limit($produkUnggulan->deskripsi ?: 'Produk unggulan karya siswa '.config('sekolah.nama'), 155))
@section('meta_keywords', 'Produk Unggulan SMK, Teaching Factory SMKN 2 Karanganyar, TeFa, Produk Siswa SMK')

@section('content')

    {{-- ============================================================
         HERO: PRODUK UNGGULAN
         ============================================================ --}}
    <section id="produk" class="relative py-16 md:py-24 overflow-hidden bg-white">
        <div class="absolute top-0 right-0 w-1/3 h-2/3 bg-blue-50 rounded-bl-[10rem] -z-10 opacity-70"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-100 rounded-full -z-10 opacity-50 blur-3xl"></div>
        <div class="dot-pattern absolute bottom-10 left-6 w-32 h-32 -z-10 opacity-25" aria-hidden="true"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

                <!-- LEFT: Text Content -->
                <div class="lg:col-span-6 space-y-6 z-10">
                    <span class="inline-block text-brand-blue font-bold tracking-wide text-sm sm:text-base uppercase">
                        {{ config('sekolah.nama_pendek') }} – Sekolah Pusat Keunggulan
                    </span>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-slate-900 tracking-tight leading-[1.1]">
                        @if ($produkUnggulan->judul)
                            @php $baris = $produkUnggulan->judul_baris; @endphp
                            {{ $baris['atas'] }}
                            @if ($baris['bawah'])
                                <br>
                                <span class="text-brand-blue">{{ $baris['bawah'] }}</span>
                            @endif
                        @else
                            Produk Unggulan<br>
                            <span class="text-brand-blue">{{ config('sekolah.nama_pendek') }}</span>
                        @endif
                    </h1>

                    <p class="text-slate-600 text-base sm:text-lg leading-relaxed max-w-xl">
                        {{ $produkUnggulan->deskripsi ?: 'Kami tidak hanya mendidik, tetapi juga mencetak inovator. Melalui kurikulum berbasis industri dan fasilitas laboratorium terkini, siswa kami menghasilkan karya-karya nyata yang kompetitif, presisi, dan siap menjawab tantangan pasar global.' }}
                    </p>

                    <div class="pt-4">
                        <a href="#semua-produk"
                            class="inline-block bg-white border-2 border-slate-200 hover:border-brand-blue text-slate-700 hover:text-brand-blue font-bold px-8 py-3.5 rounded-xl text-sm transition-all card-shadow hover:floating-button-shadow">
                            Lihat Semua Produk
                        </a>
                    </div>
                </div>

                <!-- RIGHT: Dokumentasi / Jurusan -->
                <div class="lg:col-span-6 relative mt-12 lg:mt-0 flex justify-center lg:justify-end">
                    @php
                        // Pola zig-zag: baris 1 = panjang + pendek, baris 2 = pendek + panjang.
                        $polaLebar = ['col-span-3', 'col-span-2', 'col-span-2', 'col-span-3'];
                        // Gradien aksen per kartu, mengikuti urutan jurusan.
                        $polaGradien = [
                            'from-emerald-900/80 via-emerald-900/40',
                            'from-red-900/80 via-red-900/40',
                            'from-blue-900/80 via-blue-900/40',
                            'from-amber-900/80 via-amber-900/40',
                        ];
                    @endphp

                    <div class="relative w-full max-w-[500px] grid grid-cols-5 gap-4">
                        @forelse ($dokumentasiUrl as $index => $url)
                            @php $jurusan = $jurusanList[$index] ?? null; @endphp
                            <div class="{{ $polaLebar[$index % 4] }} relative rounded-2xl overflow-hidden shadow-lg h-40 sm:h-48 group">
                                <img src="{{ $url }}" alt="Dokumentasi produk unggulan {{ $index + 1 }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                                <div class="absolute inset-0 bg-gradient-to-t {{ $polaGradien[$index % 4] }} to-transparent flex flex-col justify-end p-4">
                                    <h3 class="text-white font-bold text-sm sm:text-base leading-tight">
                                        {{ $jurusan->nama ?? $produkUnggulan->judul ?: 'Produk Unggulan' }}
                                    </h3>
                                </div>
                            </div>
                        @empty
                            @forelse ($jurusanList as $jurusan)
                                <a href="#jurusan-{{ $jurusan->jurusanID }}"
                                    class="{{ $polaLebar[$loop->index % 4] }} relative rounded-2xl overflow-hidden shadow-lg h-40 sm:h-48 group block"
                                    style="background-color: {{ $jurusan->warna }}">
                                    @if ($jurusan->dokumentasiUrl())
                                        <img src="{{ $jurusan->dokumentasiUrl() }}" alt="{{ $jurusan->nama }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                                    @endif
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent flex flex-col justify-end p-4">
                                        <h3 class="text-white font-bold text-sm sm:text-base leading-tight">{{ $jurusan->nama }}</h3>
                                        <p class="text-white/80 text-[11px] font-semibold mt-0.5">{{ $jurusan->produk->count() }} produk</p>
                                    </div>
                                </a>
                            @empty
                                <p class="col-span-5 rounded-2xl border-2 border-dashed border-slate-200 p-8 text-center text-sm font-medium text-slate-400">
                                    Belum ada dokumentasi maupun jurusan yang dipublikasikan.
                                </p>
                            @endforelse
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ============================================================
         MITRA DUDI
         ============================================================ --}}
    <section id="mitra" class="py-12 bg-white relative overflow-hidden">
        <div class="dot-pattern absolute top-8 right-6 w-28 h-28 -z-10 opacity-20" aria-hidden="true"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div id="semua-produk" class="text-center max-w-3xl mx-auto mb-10 scroll-mt-28">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-3 tracking-tight">
                    Semua Produk Unggulan
                </h2>
                <p class="text-slate-500 text-sm sm:text-base leading-relaxed">
                    Siswa kami menghasilkan karya-karya nyata yang kompetitif, presisi, dan siap menjawab tantangan pasar global.
                </p>
            </div>

            <div class="bg-white border border-slate-200 rounded-3xl p-8 md:p-10 shadow-sm mb-12">
                <div class="mb-8 flex items-center gap-4">
                    <h3 class="text-base md:text-lg font-extrabold text-slate-800 tracking-wider uppercase">
                        Mitra DUDI — Kerjasama Industri
                    </h3>
                    <div class="h-[3px] bg-slate-200 flex-1 rounded-full"></div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6 lg:gap-8 items-center justify-items-center">
                    @forelse ($dudis as $dudi)
                        <a href="{{ route('pkl-bkk') }}#dudi-{{ $dudi->id }}"
                            title="Mitra DUDI: {{ $dudi->nama }} - Lihat detail di PKL & BKK"
                            class="flex flex-col items-center justify-center w-full h-24 p-3 rounded-2xl border border-slate-100/80 bg-slate-50/50 hover:bg-white hover:border-brand-blue/30 hover:shadow-md transition-all duration-300 group hover:-translate-y-1">
                            @if ($dudi->logo)
                                <img src="{{ asset('assets/' . $dudi->logo) }}" alt="Logo {{ $dudi->nama }}"
                                    class="h-14 md:h-16 w-auto max-w-[120px] object-contain filter drop-shadow-sm select-none group-hover:scale-105 transition-transform">
                            @else
                                <div class="font-black text-sm md:text-base text-blue-800 tracking-tight px-3 py-1.5 rounded-lg bg-blue-50 text-center line-clamp-2">
                                    {{ $dudi->nama }}
                                </div>
                            @endif
                            <span class="mt-2 text-[10px] font-semibold text-slate-400 group-hover:text-brand-blue transition-colors flex items-center gap-1">
                                <span>Lihat di BKK</span>
                                <i class="fa-solid fa-arrow-up-right-from-square text-[8px]"></i>
                            </span>
                        </a>
                    @empty
                        @foreach (config('sekolah.mitra') as $mitra)
                            <a href="{{ route('pkl-bkk') }}#mitra"
                                class="flex flex-col items-center justify-center w-full h-20 text-center hover:scale-105 transition-transform duration-300">
                                <span class="font-black text-lg sm:text-xl text-slate-700 tracking-tight leading-tight">{{ $mitra['nama'] }}</span>
                                @if ($mitra['keterangan'])
                                    <span class="text-[10px] font-bold text-slate-500 tracking-widest uppercase">{{ $mitra['keterangan'] }}</span>
                                @endif
                            </a>
                        @endforeach
                    @endforelse
                </div>

                <div class="mt-8 text-center pt-2">
                    <a href="{{ route('pkl-bkk') }}#mitra"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-[#0066C4] hover:bg-blue-700 text-white font-bold text-xs sm:text-sm rounded-xl shadow-md shadow-blue-500/20 transition-all transform active:scale-95">
                        <i class="fa-solid fa-handshake"></i>
                        <span>Lihat Semua Mitra DUDI &amp; Info Penempatan PKL / BKK</span>
                        <i class="fa-solid fa-arrow-right text-xs ml-1"></i>
                    </a>
                </div>
            </div>

        </div>
    </section>

    {{-- ============================================================
         PRODUK PER JURUSAN
         ============================================================ --}}
    @forelse ($jurusanBerproduk as $jurusan)
        <section id="jurusan-{{ $jurusan->jurusanID }}"
            class="py-12 relative scroll-mt-24 {{ $loop->even ? 'bg-white' : 'bg-[#F8FAFC]' }}">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <!-- Section Header -->
                <div class="text-white font-extrabold text-sm sm:text-base px-6 py-4 rounded-t-2xl card-shadow flex flex-wrap items-center justify-between gap-2"
                    style="background-color: {{ $jurusan->warna }}">
                    <span class="uppercase tracking-wide">Produk {{ $jurusan->nama }}</span>
                    <span class="text-[11px] font-semibold bg-white/20 rounded-full px-3 py-1">
                        {{ $jurusan->produk->count() }} produk
                    </span>
                </div>

                <!-- Product Grid -->
                <div class="bg-white border border-slate-200 rounded-b-3xl p-6 sm:p-8 shadow-sm mb-12">
                    @if ($jurusan->deskripsi)
                        <p class="text-xs sm:text-sm text-slate-500 leading-relaxed mb-6 max-w-3xl">{{ $jurusan->deskripsi }}</p>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach ($jurusan->produk as $produk)
                            <article class="bg-white border border-slate-100 rounded-2xl p-4 flex flex-col shadow-sm hover:shadow-md transition-shadow">
                                <div class="h-40 bg-slate-100 rounded-xl overflow-hidden mb-4 flex items-center justify-center">
                                    @if ($produk->dokumentasiUrl())
                                        <img src="{{ $produk->dokumentasiUrl() }}" alt="{{ $produk->nama }}"
                                            class="w-full h-full object-cover" loading="lazy">
                                    @else
                                        <i class="fa-regular fa-image text-3xl text-slate-300"></i>
                                    @endif
                                </div>

                                <p class="text-[10px] font-bold tracking-wider text-slate-400 mb-1">{{ $produk->kode_produk }}</p>
                                <h4 class="text-sm font-bold text-slate-900 mb-2">{{ $produk->nama }}</h4>
                                <p class="text-xs text-slate-500 leading-relaxed grow">
                                    {{ \Illuminate\Support\Str::limit($produk->deskripsi, 110) }}
                                </p>

                                <button type="button"
                                    class="mt-4 px-4 py-2 rounded-lg text-xs font-bold text-white text-left w-fit card-shadow hover:brightness-110 active:scale-95 transition-all cursor-pointer flex items-center gap-1.5"
                                    style="background-color: {{ $jurusan->warna }}"
                                    data-modal-open
                                    data-modal-title="{{ $produk->nama }}"
                                    data-modal-meta="{{ $produk->kode_produk }} · {{ $jurusan->nama }}"
                                    data-modal-body="{{ $produk->deskripsi }}"
                                    data-modal-image="{{ $produk->dokumentasiUrl() }}"
                                    onclick="openProdukModal(this)">
                                    <span>Selengkapnya</span>
                                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                </button>
                            </article>
                        @endforeach
                    </div>
                </div>

            </div>
        </section>
    @empty
        <section class="py-16 bg-[#F8FAFC]">
            <div class="max-w-3xl mx-auto px-4 text-center">
                <i class="fa-regular fa-folder-open text-4xl text-slate-300 mb-4"></i>
                <h2 class="text-xl font-extrabold text-slate-700 mb-2">Belum Ada Produk Unggulan</h2>
                <p class="text-sm text-slate-500">Data produk unggulan akan muncul di sini setelah admin menambahkannya.</p>
            </div>
        </section>
    @endforelse

@push('modals')
<div id="modalDetailProduk" class="fixed inset-0 !m-0 z-[100] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true">
    <div id="modalDetailProdukBox" class="bg-white rounded-3xl max-w-lg w-full overflow-hidden shadow-2xl border border-slate-100 relative transform transition-all scale-95 opacity-0 duration-200">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div>
                <span id="modalProdukMeta" class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-[#0060ac] uppercase tracking-wider mb-1"></span>
                <h4 id="modalProdukTitle" class="text-base md:text-lg font-black text-slate-800 leading-tight">Detail Produk</h4>
            </div>
            <button onclick="closeModal('modalDetailProduk')" type="button" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition cursor-pointer">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
        <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
            <div id="modalProdukImageWrap" class="hidden">
                <img id="modalProdukImage" src="" alt="Produk" class="w-full h-56 object-cover rounded-2xl border border-slate-200 shadow-sm">
            </div>
            <div>
                <h5 class="text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5 flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-info text-[#0060ac]"></i>
                    <span>Deskripsi Produk</span>
                </h5>
                <p id="modalProdukBody" class="text-xs md:text-sm text-slate-600 leading-relaxed bg-slate-50 p-4 rounded-2xl border border-slate-100 whitespace-pre-line"></p>
            </div>
        </div>
        <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between gap-3">
            <a id="modalProdukWaBtn" href="https://wa.me/{{ preg_replace('/[^0-9]/', '', config('sekolah.telepon', '6281234567890')) }}?text=Halo%20SMKN%202%20Karanganyar,%20saya%20tertarik%20dengan%20produk%20unggulan" target="_blank" rel="noopener noreferrer"
                class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-bold text-xs md:text-sm shadow-md shadow-emerald-500/20 transition flex items-center gap-2 cursor-pointer">
                <i class="fa-brands fa-whatsapp text-sm"></i>
                <span>Tanya Produk</span>
            </a>
            <button type="button" onclick="closeModal('modalDetailProduk')" class="px-5 py-2.5 rounded-xl bg-[#0060ac] hover:bg-[#004f8f] active:scale-95 text-white font-bold text-xs md:text-sm shadow-md shadow-blue-500/20 transition cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    function openProdukModal(btn) {
        if (!btn) return;
        const title = btn.getAttribute('data-modal-title') || 'Detail Produk';
        const meta = btn.getAttribute('data-modal-meta') || '';
        const body = btn.getAttribute('data-modal-body') || '';
        const image = btn.getAttribute('data-modal-image');

        const titleEl = document.getElementById('modalProdukTitle');
        const metaEl = document.getElementById('modalProdukMeta');
        const bodyEl = document.getElementById('modalProdukBody');
        if (titleEl) titleEl.innerText = title;
        if (metaEl) metaEl.innerText = meta;
        if (bodyEl) bodyEl.innerText = body;

        const imgEl = document.getElementById('modalProdukImage');
        const imgWrap = document.getElementById('modalProdukImageWrap');
        if (imgWrap && imgEl) {
            if (image && image.trim() !== '') {
                imgEl.src = image;
                imgEl.alt = title;
                imgWrap.classList.remove('hidden');
            } else {
                imgEl.removeAttribute('src');
                imgWrap.classList.add('hidden');
            }
        }

        const waBtn = document.getElementById('modalProdukWaBtn');
        if (waBtn) {
            const text = encodeURIComponent('Halo SMKN 2 Karanganyar, saya tertarik dan ingin menanyakan informasi tentang produk unggulan: ' + title + ' (' + meta + ').');
            const phone = '{{ preg_replace("/[^0-9]/", "", config("sekolah.telepon", "6281234567890")) }}';
            waBtn.href = 'https://wa.me/' + phone + '?text=' + text;
        }

        if (window.openModal) {
            window.openModal('modalDetailProduk');
        }
    }

    // Attach event listeners for any dynamic or existing buttons
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[data-modal-open]').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                openProdukModal(this);
            });
        });
    });
</script>
@endpush
@endsection

