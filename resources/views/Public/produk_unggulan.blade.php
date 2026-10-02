@extends('Public.layout.app')

@section('title', ($produkUnggulan->judul ?: 'Produk Unggulan').' - '.config('sekolah.nama'))
@section('description', \Illuminate\Support\Str::limit($produkUnggulan->deskripsi ?: 'Produk unggulan karya siswa '.config('sekolah.nama'), 155))

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

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-8 items-center justify-items-center">
                    @foreach (config('sekolah.mitra') as $mitra)
                        <div class="flex flex-col items-center justify-center w-full h-20 text-center hover:scale-105 transition-transform duration-300">
                            <span class="font-black text-lg sm:text-xl text-slate-700 tracking-tight leading-tight">{{ $mitra['nama'] }}</span>
                            @if ($mitra['keterangan'])
                                <span class="text-[10px] font-bold text-slate-500 tracking-widest uppercase">{{ $mitra['keterangan'] }}</span>
                            @endif
                        </div>
                    @endforeach
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
                                    class="mt-4 px-4 py-2 rounded-lg text-xs font-bold text-white text-left w-fit card-shadow hover:brightness-110 transition-all"
                                    style="background-color: {{ $jurusan->warna }}"
                                    data-modal-open
                                    data-modal-title="{{ $produk->nama }}"
                                    data-modal-meta="{{ $produk->kode_produk }} · {{ $jurusan->nama }}"
                                    data-modal-body="{{ $produk->deskripsi }}"
                                    data-modal-image="{{ $produk->dokumentasiUrl() }}">
                                    Selengkapnya
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

@endsection
