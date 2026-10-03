@extends('Public.layout.app')

@section('title', $master?->judul ? $master->judul . ' - SMK Negeri 2 Karanganyar' : 'PPDB 2026 - SMK Negeri 2 Karanganyar')
@section('meta_description', 'Informasi PPDB SMKN 2 Karanganyar: persyaratan pendaftaran, tanggal penting, daya tampung, jalur seleksi, dan pilihan kompetensi keahlian.')

@section('content')

    <!-- ============================================================
         KONTEN: PPDB
         ============================================================ -->

    <!-- 1. HERO SECTION (PPDB) -->
    <section id="hero" class="relative py-16 md:py-24 overflow-hidden bg-white">
        <!-- Abstract Background Shapes -->
        <div class="absolute top-0 right-0 w-1/3 h-2/3 bg-blue-50 rounded-bl-[10rem] -z-10 opacity-70"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-100 rounded-full -z-10 opacity-50 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

                <!-- Left Text Content -->
                <div class="lg:col-span-6 space-y-6 z-10">
                    <span class="inline-block text-brand-blue font-bold tracking-wide text-sm sm:text-base uppercase">
                        Sekolah Pusat Keunggulan
                    </span>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-slate-900 tracking-tight leading-[1.1]">
                        @if ($master?->judul)
                            {!! nl2br(e($master->judul)) !!}
                        @else
                            PPDB SMKN 2<br />
                            <span class="text-brand-blue">KARANGANYAR</span>
                        @endif
                    </h1>

                    <p class="text-slate-600 text-base sm:text-lg leading-relaxed max-w-xl">
                        {{ $master?->deskripsi ?? 'Bersama SMKN 2 Karanganyar untuk mencetak generasi unggul yang kompeten dan berkarakter siap di dunia industri.' }}
                    </p>

                    <div class="pt-4">
                        <button onclick="document.getElementById('panduan').scrollIntoView({behavior: 'smooth'})"
                            class="bg-brand-blue hover:bg-brand-darkBlue text-white font-bold px-8 py-3.5 rounded-xl text-sm transition-all shadow-lg shadow-blue-500/30 flex items-center gap-2">
                            Informasi Panduan PPDB
                        </button>
                    </div>
                </div>

                <!-- Right Visual: Poster -->
                <div class="lg:col-span-6 relative mt-12 lg:mt-0 flex justify-center lg:justify-end">
                    <div class="relative w-full max-w-[500px]">
                        <!-- Main Poster Image -->
                        <img src="{{ $master?->banner_img ? asset('storage/' . $master->banner_img) : asset('assets/ppdb.png') }}"
                            alt="{{ $master?->judul ?? 'Banner SPMB SMKN 2 Karanganyar' }}"
                            class="w-full h-auto rounded-3xl shadow-2xl border-4 border-white object-cover relative z-10">

                        <!-- Abstract decorative shapes -->
                        <div class="absolute -top-6 -left-6 w-24 h-24 dot-pattern opacity-50 z-0"></div>
                        <div class="absolute -bottom-8 -right-8 w-32 h-32 border-[16px] border-brand-blue rounded-full opacity-20 z-0"></div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 2. INFORMASI PANDUAN PPDB -->
    <section id="panduan" class="py-16 bg-[#FAFCFF] relative overflow-hidden">
        <!-- Dot Pattern Top Right -->
        <div class="absolute top-10 right-10 w-32 h-32 dot-pattern opacity-40 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Header -->
            <div class="text-center max-w-3xl mx-auto mb-14">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-3 tracking-tight">
                    {{ $informasi?->judul ?? 'Informasi Panduan PPDB' }}
                </h2>
                <p class="text-slate-500 text-sm sm:text-base leading-relaxed">
                    {{ $informasi?->keterangan ?? 'Panduan lengkap dan tahapan pendaftaran peserta didik baru SMK Negeri 2 Karanganyar.' }}
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <!-- LEFT CARD: Persyaratan PPDB -->
                <div class="bg-white border border-slate-200 rounded-3xl p-8 sm:p-10 shadow-sm relative">
                    <div class="flex items-center gap-3 mb-6">
                        <h3 class="text-xl sm:text-2xl font-bold text-slate-900 border-b-2 border-slate-900 pb-1 inline-block">
                            Persyaratan PPDB
                        </h3>
                    </div>

                    <ul class="space-y-3.5 mb-8 text-slate-700 text-sm">
                        @forelse ($persyaratan as $syarat)
                            <li class="flex items-start gap-3">
                                <i class="fa-regular fa-circle-check text-emerald-500 text-lg mt-0.5 shrink-0"></i>
                                <span>{{ $syarat->syarat }}</span>
                            </li>
                        @empty
                            <li class="text-slate-400 italic text-sm">Belum ada persyaratan yang ditambahkan.</li>
                        @endforelse
                    </ul>

                    @if ($informasi?->path_file)
                        <a href="{{ asset('storage/' . $informasi->path_file) }}" target="_blank" download
                            class="text-brand-blue font-bold text-xs sm:text-sm hover:underline uppercase tracking-wider inline-flex items-center gap-2">
                            <i class="fa-solid fa-file-arrow-down text-base"></i> PETUNJUK OPERASIONAL SPMB SMAN SMKN JAWA TENGAH
                        </a>
                    @else
                        <span class="text-slate-400 font-bold text-xs sm:text-sm uppercase tracking-wider inline-flex items-center gap-2">
                            <i class="fa-solid fa-file-lines text-base"></i> PETUNJUK OPERASIONAL BELUM TERSEDIA
                        </span>
                    @endif
                </div>

                <!-- RIGHT CARD: Tanggal Penting -->
                <div class="bg-white border border-slate-200 rounded-3xl p-8 sm:p-10 shadow-sm relative">
                    <h3 class="text-xl sm:text-2xl font-bold text-slate-900 border-b-2 border-slate-900 pb-1 inline-block mb-8">
                        Tanggal Penting
                    </h3>

                    <!-- Timeline -->
                    <div class="relative border-l-2 border-slate-200 ml-3 space-y-8 pb-4">
                        @forelse ($agendas as $agenda)
                            <div class="relative pl-8">
                                <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-brand-blue border-4 border-white shadow-sm"></div>
                                <h4 class="text-sm font-bold text-brand-blue mb-1">
                                    {{ $agenda->rentang_tanggal }}
                                </h4>
                                <p class="text-xs sm:text-sm font-bold text-slate-800 mb-1">{{ $agenda->nama_agenda }}</p>
                                <p class="text-xs text-slate-500">{{ $agenda->keterangan }}</p>
                            </div>
                        @empty
                            <div class="relative pl-8 text-slate-400 italic text-sm">
                                Belum ada agenda tanggal penting yang ditambahkan.
                            </div>
                        @endforelse
                    </div>

                    <!-- Dot pattern bottom right -->
                    <div class="absolute bottom-4 right-4 w-24 h-24 dot-pattern opacity-30 pointer-events-none"></div>
                </div>

            </div>
        </div>
    </section>

    <!-- 3. PILIHAN KOMPETENSI KEAHLIAN & HASIL SELEKSI -->
    <section id="kompetensi" class="py-16 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            <!-- Header -->
            <div class="text-center max-w-3xl mx-auto mb-14">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-3 tracking-tight">
                    Pilihan Kompetensi Keahlian & Hasil Seleksi
                </h2>
                <p class="text-slate-500 text-sm sm:text-base leading-relaxed">
                    Informasi daya tampung setiap kompetensi keahlian dan persentase kuota jalur seleksi penerimaan.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                <!-- LEFT PANEL: Informasi -->
                <div class="lg:col-span-4 space-y-6">

                    <!-- Daya Tampung Card -->
                    <div class="bg-[#F8FAFC] border border-slate-200 rounded-3xl p-6 shadow-sm">
                        <h3 class="text-base font-bold text-slate-800 mb-4 border-b-2 border-brand-blue inline-block pb-1">
                            Daya Tampung
                        </h3>
                        <ul class="space-y-2 text-xs sm:text-sm text-slate-600">
                            @forelse ($jurusans as $jurusan)
                                <li class="flex justify-between items-center py-1 border-b border-slate-100">
                                    <span>{{ $jurusan->nama_jurusan }}</span>
                                    <span class="font-bold text-slate-800">: {{ $jurusan->daya_tampung }} Siswa</span>
                                </li>
                            @empty
                                <li class="py-1 text-slate-400 text-xs italic">Belum ada data jurusan.</li>
                            @endforelse
                            <li class="flex justify-between items-center py-2 mt-2 border-t-2 border-slate-200">
                                <span class="font-bold text-slate-800">Total</span>
                                <span class="font-bold text-brand-blue text-base">: {{ $totalDayaTampung }} Siswa</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Jalur Seleksi Card -->
                    <div class="bg-[#F8FAFC] border border-slate-200 rounded-3xl p-6 shadow-sm">
                        <h3 class="text-base font-bold text-slate-800 mb-4 border-b-2 border-brand-blue inline-block pb-1">
                            Jalur Seleksi
                        </h3>
                        <ul class="space-y-2 text-xs sm:text-sm text-slate-600">
                            @forelse ($jalurs as $jalur)
                                <li class="flex justify-between items-center py-1 border-b border-slate-100">
                                    <span>{{ $jalur->nama_jalur }}</span>
                                    <span class="font-bold text-slate-800">: {{ rtrim(rtrim(number_format($jalur->percentase, 2), '0'), '.') }}%</span>
                                </li>
                            @empty
                                <li class="py-1 text-slate-400 text-xs italic">Belum ada data jalur seleksi.</li>
                            @endforelse
                        </ul>
                    </div>

                    <!-- Hasil Seleksi Card -->
                    <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
                        <h3 class="text-base font-bold text-slate-800 mb-3 border-b-2 border-brand-blue inline-block pb-1">
                            Hasil Seleksi
                        </h3>
                        <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                            Unduh hasil seleksi PPDB SMKN 2 KARANGANYAR.
                        </p>
                        @if ($informasi?->path_file_hasil)
                            <a href="{{ asset('storage/' . $informasi->path_file_hasil) }}" target="_blank" download
                                class="w-full bg-brand-blue hover:bg-brand-darkBlue text-white font-bold text-xs py-3 rounded-xl transition-all shadow-md flex items-center justify-center gap-2">
                                <i class="fa-solid fa-download"></i> Download PDF
                            </a>
                        @else
                            <button onclick="openModal('Download Hasil Seleksi', 'Pengumuman dan berkas hasil seleksi PPDB belum dipublikasikan oleh pihak sekolah.')"
                                class="w-full bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs py-3 rounded-xl transition-all shadow-sm flex items-center justify-center gap-2">
                                <i class="fa-solid fa-download"></i> Download PDF
                            </button>
                        @endif
                    </div>

                </div>

                <!-- RIGHT PANEL: Grid Kompetensi Keahlian (Pola 2x2: 70/30 & 30/70) -->
                <div class="lg:col-span-8 flex flex-col gap-6">
                    @php
                        $defaultImgs = ['assets/rpl.png', 'assets/tpk.png', 'assets/oto.png', 'assets/mesin.png'];
                        $chunks = $jurusans->chunk(4);
                    @endphp

                    @forelse ($chunks as $chunk)
                        @php
                            $slot1 = $chunk->values()->get(0);
                            $slot2 = $chunk->values()->get(1);
                            $slot3 = $chunk->values()->get(2);
                            $slot4 = $chunk->values()->get(3);
                        @endphp

                        {{-- Baris 1: 70% & 30% --}}
                        @if ($slot1)
                            <div class="flex flex-col sm:flex-row gap-6">
                                {{-- Slot 1: 70% (atau 100% jika slot 2 tidak ada) --}}
                                <div class="w-full {{ $slot2 ? 'sm:w-[70%]' : 'sm:w-full' }} relative rounded-3xl overflow-hidden shadow-md group h-64 lg:h-72">
                                    <img src="{{ $slot1->img ? asset('storage/' . $slot1->img) : asset($defaultImgs[0]) }}"
                                        alt="{{ $slot1->nama_jurusan }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent flex flex-col justify-end p-6">
                                        <h3 class="text-white font-extrabold text-xl sm:text-2xl leading-tight">{{ $slot1->nama_jurusan }}</h3>
                                    </div>
                                </div>

                                {{-- Slot 2: 30% --}}
                                @if ($slot2)
                                    <div class="w-full sm:w-[30%] relative rounded-3xl overflow-hidden shadow-md group h-64 lg:h-72">
                                        <img src="{{ $slot2->img ? asset('storage/' . $slot2->img) : asset($defaultImgs[1]) }}"
                                            alt="{{ $slot2->nama_jurusan }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent flex flex-col justify-end p-6">
                                            <h3 class="text-white font-extrabold text-xl sm:text-2xl leading-tight">{{ $slot2->nama_jurusan }}</h3>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        {{-- Baris 2: 30% & 70% --}}
                        @if ($slot3)
                            <div class="flex flex-col sm:flex-row gap-6">
                                {{-- Slot 3: 30% (atau 100% jika slot 4 tidak ada) --}}
                                <div class="w-full {{ $slot4 ? 'sm:w-[30%]' : 'sm:w-full' }} relative rounded-3xl overflow-hidden shadow-md group h-64 lg:h-72">
                                    <img src="{{ $slot3->img ? asset('storage/' . $slot3->img) : asset($defaultImgs[2]) }}"
                                        alt="{{ $slot3->nama_jurusan }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent flex flex-col justify-end p-6">
                                        <h3 class="text-white font-extrabold text-xl sm:text-2xl leading-tight">{{ $slot3->nama_jurusan }}</h3>
                                    </div>
                                </div>

                                {{-- Slot 4: 70% --}}
                                @if ($slot4)
                                    <div class="w-full sm:w-[70%] relative rounded-3xl overflow-hidden shadow-md group h-64 lg:h-72">
                                        <img src="{{ $slot4->img ? asset('storage/' . $slot4->img) : asset($defaultImgs[3]) }}"
                                            alt="{{ $slot4->nama_jurusan }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent flex flex-col justify-end p-6">
                                            <h3 class="text-white font-extrabold text-xl sm:text-2xl leading-tight">{{ $slot4->nama_jurusan }}</h3>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    @empty
                        <div class="text-center py-12 text-slate-400">
                            <i class="fa-solid fa-graduation-cap text-4xl mb-3"></i>
                            <p>Belum ada data kompetensi keahlian.</p>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>
    </section>

@endsection
