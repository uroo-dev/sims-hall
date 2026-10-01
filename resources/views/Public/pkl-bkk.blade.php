@extends('Public.layout.app')

@section('title', 'PKL & BKK — SMK N 2 Karanganyar')

@section('content')
    {{-- ------------------------------------------------------------------
         Halaman PKL & BKK.
         Seluruh angka & nama diambil dari database modul PKL & BKK, bukan
         data statis: mitra dari scope `forLandingPage`, lowongan dari scope
         `active` (deadline >= hari ini), rekap dari scope status penempatan.
         ------------------------------------------------------------------ --}}

    <!-- HERO -->
    <section id="hero" class="relative py-12 md:py-16 bg-slate-50 overflow-hidden">
        <div class="absolute inset-0 plus-tex opacity-[0.04] pointer-events-none"></div>
        <div class="absolute top-6 right-6 w-32 h-32 rounded-full bg-brand-100/60 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-3xl">
                <span class="inline-block text-brand-blue font-bold tracking-wide text-base sm:text-lg">
                    Praktik Kerja Lapangan & Bantuan Kegiatan Kerja
                </span>
                <h1 class="mt-3 text-4xl sm:text-5xl font-black text-slate-900 tracking-tight leading-tight">
                    PKL &amp; BKK
                </h1>
                <p class="mt-4 text-slate-600 text-base sm:text-lg leading-relaxed">
                    SMKN 2 Karanganyar bekerja sama dengan {{ $rekap['total_dudi'] }} mitra industri resmi
                    untuk menempatkan siswa pada lingkungan kerja yang relevan dengan bidang studi mereka.
                    Semua posisi dan kuota di bawah ini bersumber langsung dari data penempatan PKL &amp; BKK sekolah.
                </p>

                <div class="mt-8 flex flex-wrap items-center gap-4">
                    <a href="#lowongan"
                        class="bg-brand-blue hover:bg-brand-darkBlue text-white font-semibold px-8 py-3.5 rounded-full shadow-lg shadow-blue-500/25 transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                        Lihat Lowongan
                    </a>
                    <a href="#mitra"
                        class="inline-flex items-center text-brand-blue font-bold text-sm hover:translate-x-1 transition-transform">
                        Mitra DUDI <i class="fa-solid fa-chevron-right text-xs ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- REKAP ANGKA -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-5">
                @php
                    $statistik = [
                        [
                            'nilai' => $rekap['siswa_fix'],
                            'label' => 'Siswa PKL (FIX)',
                            'ikon' => 'fa-user-check',
                            'warna' => 'text-blue-700',
                        ],
                        [
                            'nilai' => $rekap['siswa_menunggu'],
                            'label' => 'Menunggu Penempatan',
                            'ikon' => 'fa-hourglass-half',
                            'warna' => 'text-amber-600',
                        ],
                        [
                            'nilai' => $rekap['siswa_belum_pkl'],
                            'label' => 'Belum PKL',
                            'ikon' => 'fa-user-clock',
                            'warna' => 'text-slate-600',
                        ],
                        [
                            'nilai' => $rekap['total_dudi'],
                            'label' => 'Mitra DUDI',
                            'ikon' => 'fa-handshake',
                            'warna' => 'text-brand-blue',
                        ],
                        [
                            'nilai' => $rekap['total_lowongan'],
                            'label' => 'Lowongan Aktif',
                            'ikon' => 'fa-briefcase',
                            'warna' => 'text-blue-700',
                        ],
                    ];
                @endphp

                @foreach ($statistik as $s)
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-md">
                        <i class="fa-solid {{ $s['ikon'] }} {{ $s['warna'] }} text-xl"></i>
                        <p class="mt-4 text-4xl font-black text-slate-900 tracking-tight leading-none">
                            {{ number_format($s['nilai'], 0, ',', '.') }}
                        </p>
                        <p class="mt-2 text-xs font-semibold uppercase tracking-wider text-slate-500 leading-snug">
                            {{ $s['label'] }}
                        </p>
                    </div>
                @endforeach
            </div>

            {{-- Bar kapasitas mitra: kuota resmi versus yang sudah terisi FIX. --}}
            @if ($totalKuota > 0)
                <div class="mt-6 bg-slate-50 border border-slate-200/80 rounded-2xl p-6">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <p class="text-sm font-bold text-slate-800">
                            <i class="fa-solid fa-chart-simple text-brand-blue mr-1.5"></i>
                            Kapasitas Penempatan di Mitra
                        </p>
                        <p class="text-sm font-semibold text-slate-600">
                            <span class="text-slate-900 font-black">{{ $kuotaTerpakai }}</span>
                            dari <span class="text-slate-900 font-black">{{ number_format($totalKuota, 0, ',', '.') }}</span>
                            kuota terisi
                        </p>
                    </div>
                    <div class="mt-3 h-2.5 w-full rounded-full bg-slate-200 overflow-hidden">
                        @php
                            $persen = $totalKuota > 0
                                ? min(100, (int) round($kuotaTerpakai / $totalKuota * 100))
                                : 0;
                        @endphp
                        <div class="h-full rounded-full bg-brand-blue transition-all duration-700" style="width: {{ $persen }}%"></div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- MITRA DUDI -->
    <section id="mitra" class="py-16 bg-[#F8FAFC] relative scroll-mt-24">
        <div class="absolute top-8 left-8 w-24 h-24 dot-pattern opacity-40 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-4">Mitra Industri</h2>
                <p class="text-slate-600 text-base sm:text-lg">
                    Perusahaan dan lembaga yang sudah menandatangani kerja sama resmi dengan
                    sekolah untuk menerima siswa PKL.
                </p>
            </div>

            @forelse ($dudis as $dudi)
                <div class="bg-white border border-slate-200/80 rounded-2xl p-8 md:p-10 shadow-md mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                        {{-- Logo --}}
                        <div class="md:col-span-2 flex md:justify-center items-start">
                            @if ($dudi->logo)
                                <img src="{{ asset('assets/' . $dudi->logo) }}" alt="Logo {{ $dudi->nama }}"
                                    class="h-20 w-auto object-contain filter drop-shadow-md select-none">
                            @else
                                <div class="font-black text-lg text-blue-800 tracking-tighter border-4 border-blue-800 px-3 py-2 rounded-xl bg-blue-50/50 shadow-sm text-center">
                                    {{ Str::limit($dudi->nama, 14) }}
                                </div>
                            @endif
                        </div>

                        {{-- Identitas & deskripsi --}}
                        <div class="md:col-span-6">
                            <h3 class="text-xl font-extrabold text-slate-900 tracking-tight leading-tight">
                                {{ $dudi->nama }}
                            </h3>

                            <div class="mt-2 flex flex-wrap gap-x-4 gap-y-1 text-sm text-slate-600">
                                @if ($dudi->kota)
                                    <span><i class="fa-solid fa-location-dot text-slate-400 mr-1"></i>{{ $dudi->kota }}</span>
                                @endif
                                @if ($dudi->bidang_usaha)
                                    <span><i class="fa-solid fa-industry text-slate-400 mr-1"></i>{{ $dudi->bidang_usaha }}</span>
                                @endif
                                @if ($dudi->jurusan)
                                    <span><i class="fa-solid fa-graduation-cap text-slate-400 mr-1"></i>{{ $dudi->jurusan->nama }}</span>
                                @endif
                            </div>

                            @if ($dudi->deskripsi)
                                <p class="mt-4 text-slate-700 text-sm leading-relaxed">
                                    {{ Str::limit($dudi->deskripsi, 230) }}
                                </p>
                            @endif

                            @php $program = collect([$dudi->program_1, $dudi->program_2, $dudi->program_3])->filter(); @endphp
                            @if ($program->isNotEmpty())
                                <div class="mt-4 flex flex-wrap gap-2">
                                    @foreach ($program as $p)
                                        <span class="px-2.5 py-1 rounded-full bg-blue-50 text-blue-800 border border-blue-100 text-xs font-semibold">
                                            {{ $p }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- Kapasitas --}}
                        <div class="md:col-span-4">
                            <div class="grid grid-cols-2 gap-3">
                                <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-4 text-center">
                                    <p class="text-2xl font-black text-slate-900 leading-none">{{ $dudi->sisa_kuota }}</p>
                                    <p class="mt-1.5 text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                                        Sisa Kuota
                                    </p>
                                </div>
                                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 text-center">
                                    <p class="text-2xl font-black text-blue-800 leading-none">{{ $dudi->jumlah_siswa_fix }}</p>
                                    <p class="mt-1.5 text-[11px] font-semibold uppercase tracking-wider text-blue-700">
                                        Siswa PKL
                                    </p>
                                </div>
                            </div>

                            <dl class="mt-4 space-y-2 text-sm">
                                @if ($dudi->kontak_person || $dudi->no_hp)
                                    <div class="flex gap-2">
                                        <dt class="w-24 shrink-0 text-slate-500">Kontak</dt>
                                        <dd class="font-semibold text-slate-800">
                                            {{ $dudi->kontak_person ?? '—' }}
                                            @if ($dudi->no_hp)
                                                <span class="block font-normal text-slate-600">{{ $dudi->no_hp }}</span>
                                            @endif
                                        </dd>
                                    </div>
                                @endif
                                @if ($dudi->alamat)
                                    <div class="flex gap-2">
                                        <dt class="w-24 shrink-0 text-slate-500">Alamat</dt>
                                        <dd class="text-slate-700">{{ Str::limit($dudi->alamat, 80) }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white border border-slate-200/80 rounded-2xl p-12 text-center shadow-md">
                    <i class="fa-solid fa-handshake text-4xl text-slate-300"></i>
                    <p class="mt-4 text-slate-600 font-semibold">Belum ada mitra DUDI yang dipublikasikan.</p>
                    <p class="mt-1 text-sm text-slate-500">
                        Mitra akan tampil di sini setelah kerja samanya disetujui pihak sekolah.
                    </p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- LOWONGAN -->
    <section id="lowongan" class="py-16 bg-white relative scroll-mt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-12">
                <h4 class="text-base md:text-lg font-extrabold text-slate-800 tracking-wider uppercase">
                    Lowongan Pekerjaan &amp; Magang
                </h4>
                <div class="h-[3px] bg-slate-200 flex-1 rounded-full mt-3"></div>
            </div>

            @forelse ($lowongans as $lowongan)
                <div class="border border-slate-200/80 rounded-2xl p-6 md:p-8 shadow-md mb-5 hover:shadow-lg hover:-translate-y-0.5 transition-all">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
                        {{-- Badge tipe --}}
                        <div class="lg:col-span-2">
                            @if ($lowongan->tipe === \App\Models\Lowongan::TIPE_MAGANG)
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold uppercase tracking-wider">
                                    <i class="fa-solid fa-user-graduate mr-1.5"></i>Magang
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-blue-50 text-blue-800 border border-blue-100 text-xs font-bold uppercase tracking-wider">
                                    <i class="fa-solid fa-briefcase mr-1.5"></i>Pekerjaan
                                </span>
                            @endif
                        </div>

                        {{-- Posisi & perusahaan --}}
                        <div class="lg:col-span-7">
                            <h3 class="text-xl font-extrabold text-slate-900 tracking-tight leading-snug">
                                {{ $lowongan->posisi }}
                            </h3>
                            <p class="mt-1.5 text-sm text-slate-600">
                                <i class="fa-solid fa-building text-slate-400 mr-1.5"></i>
                                <span class="font-semibold text-slate-800">{{ $lowongan->nama_perusahaan }}</span>
                                @if ($lowongan->dudi?->kota)
                                    <span class="text-slate-500">&middot; {{ $lowongan->dudi->kota }}</span>
                                @endif
                            </p>
                            <p class="mt-1 text-sm text-slate-600">
                                <i class="fa-solid fa-graduation-cap text-slate-400 mr-1.5"></i>
                                {{ $lowongan->jurusan_sesuai }}
                            </p>

                            @if ($lowongan->deskripsi)
                                <p class="mt-3 text-slate-700 text-sm leading-relaxed">
                                    {{ Str::limit($lowongan->deskripsi, 260) }}
                                </p>
                            @endif
                        </div>

                        {{-- Deadline & aksi --}}
                        <div class="lg:col-span-3">
                            <div @class([
                                'rounded-xl border p-4 text-center',
                                'border-red-200 bg-red-50' => $lowongan->sisa_hari <= 3,
                                'border-slate-200 bg-slate-50' => $lowongan->sisa_hari > 3,
                            ])>
                                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                                    Batas Pendaftaran
                                </p>
                                <p class="mt-1.5 text-base font-extrabold text-slate-900 leading-tight">
                                    {{ $lowongan->deadline->locale('id')->translatedFormat('d F Y') }}
                                </p>
                                <p @class([
                                    'mt-1 text-xs font-bold',
                                    'text-red-600' => $lowongan->sisa_hari <= 3,
                                    'text-slate-500' => $lowongan->sisa_hari > 3,
                                ])>
                                    @if ($lowongan->sisa_hari === 0)
                                        Hari terakhir
                                    @else
                                        {{ $lowongan->sisa_hari }} hari lagi
                                    @endif
                                </p>
                            </div>

                            @if ($lowongan->link_daftar)
                                <a href="{{ $lowongan->link_daftar }}" target="_blank" rel="noopener noreferrer"
                                    class="mt-3 w-full inline-flex items-center justify-center bg-brand-blue hover:bg-brand-darkBlue text-white font-semibold text-sm px-5 py-3 rounded-full shadow-lg shadow-blue-500/25 transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                                    Daftar Sekarang
                                    <i class="fa-solid fa-arrow-up-right-from-square text-[11px] ml-2"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="border border-slate-200/80 rounded-2xl p-12 text-center shadow-md bg-slate-50">
                    <i class="fa-solid fa-briefcase text-4xl text-slate-300"></i>
                    <p class="mt-4 text-slate-600 font-semibold">Belum ada lowongan yang aktif saat ini.</p>
                    <p class="mt-1 text-sm text-slate-500">
                        Silakan cek kembali beberapa waktu lagi.
                    </p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- AJAKAN -->
    <section class="pb-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-brand-blue rounded-2xl p-10 md:p-14 text-white text-center shadow-xl shadow-blue-500/25">
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                    Sudah Siap PKL?
                </h2>
                <p class="mt-3 text-white/90 text-base sm:text-lg max-w-2xl mx-auto">
                    Pendaftaran dan penempatan siswa PKL &amp; BKK dilakukan melalui BKK sekolah.
                    Datang ke ruang BKK atau hubungi wali kelas Anda untuk informasi lebih lanjut.
                </p>
                <div class="mt-8 flex flex-wrap justify-center gap-4">
                    <a href="#lowongan"
                        class="bg-white text-brand-blue hover:bg-slate-100 font-semibold px-8 py-3.5 rounded-full shadow-lg transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                        Pilih Lowongan
                    </a>
                    <a href="{{ route('profil') }}"
                        class="inline-flex items-center border-2 border-white/70 hover:bg-white/10 font-semibold px-8 py-3.5 rounded-full transition-all">
                        Profil Sekolah
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection