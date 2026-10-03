@extends('Public.layout.app')

@section('title', 'Detail Lowongan: ' . $lowongan->posisi . ' — BKK SMKN 2 Karanganyar')
@section('meta_description', 'Info Lowongan Kerja ' . $lowongan->posisi . ' di ' . $lowongan->nama_perusahaan . ' (' . $lowongan->tipe . '). Batas pendaftaran ' . \Carbon\Carbon::parse($lowongan->deadline)->translatedFormat('d F Y') . ' melalui BKK SMKN 2 Karanganyar.')
@section('og_type', 'article')

@section('structured_data')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@type": "JobPosting",
    "title": {{ json_encode($lowongan->posisi) }},
    "description": {{ json_encode($lowongan->deskripsi) }},
    "datePosted": "{{ $lowongan->created_at->toIso8601String() }}",
    "validThrough": "{{ \Carbon\Carbon::parse($lowongan->deadline)->endOfDay()->toIso8601String() }}",
    "employmentType": "{{ $lowongan->tipe === 'Magang' ? 'INTERN' : 'FULL_TIME' }}",
    "hiringOrganization": {
        "@type": "Organization",
        "name": {{ json_encode($lowongan->nama_perusahaan) }},
        "sameAs": "{{ url('/bkk') }}"
    },
    "jobLocation": {
        "@type": "Place",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": {{ json_encode($lowongan->dudi?->kota ?? 'Karanganyar') }},
            "addressRegion": "Jawa Tengah",
            "addressCountry": "ID"
        }
    }
}
</script>
@endsection

@section('content')
    <div class="py-10 bg-[#F8FAFC]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Breadcrumb & Back Link --}}
            <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500">
                    <a href="{{ url('/') }}" class="hover:text-brand-blue">Beranda</a>
                    <i class="fa-solid fa-chevron-right text-[10px] text-slate-300"></i>
                    <a href="{{ route('bkk') }}" class="hover:text-brand-blue">Bursa Kerja Khusus</a>
                    <i class="fa-solid fa-chevron-right text-[10px] text-slate-300"></i>
                    <span class="text-slate-900 truncate max-w-xs">{{ $lowongan->posisi }}</span>
                </nav>

                <a href="{{ route('bkk') }}#lowongan"
                    class="inline-flex items-center gap-2 bg-white border border-slate-200 hover:border-brand-blue text-slate-700 hover:text-brand-blue font-bold px-4 py-2 rounded-xl text-xs transition-colors shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Lowongan
                </a>
            </div>

            @php
                // Deteksi icon berdasarkan jurusan/posisi
                $jurusanText = strtolower($lowongan->jurusan_sesuai . ' ' . $lowongan->posisi);
                $iconClass = 'fa-briefcase';
                if (str_contains($jurusanText, 'mesin')) {
                    $iconClass = 'fa-wrench';
                } elseif (str_contains($jurusanText, 'kain') || str_contains($jurusanText, 'tekstil')) {
                    $iconClass = 'fa-user-tie';
                } elseif (str_contains($jurusanText, 'oto')) {
                    $iconClass = 'fa-car';
                } elseif (str_contains($jurusanText, 'rpl') || str_contains($jurusanText, 'web') || str_contains($jurusanText, 'soft') || str_contains($jurusanText, 'code')) {
                    $iconClass = 'fa-code';
                }
            @endphp

            {{-- ============================================================
                 TOP SECTION: LOGO & JUDUL LOWONGAN (SESUAI DESAIN MOCKUP)
                 ============================================================ --}}
            <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm mb-8 relative overflow-hidden">
                <div class="flex flex-col md:flex-row items-start md:items-center gap-6">

                    <!-- Logo or Icon Box -->
                    <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl bg-blue-50 flex items-center justify-center p-3 border border-blue-200 shrink-0 shadow-sm overflow-hidden">
                        @if ($lowongan->logo_url)
                            <img src="{{ $lowongan->logo_url }}" alt="Logo {{ $lowongan->dudi?->nama_dudi ?? $lowongan->nama_perusahaan }}" class="w-full h-full object-contain">
                        @else
                            <i class="fa-solid {{ $iconClass }} text-brand-blue text-4xl"></i>
                        @endif
                    </div>

                    <div class="flex-grow">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-2">
                            <h1 class="text-2xl sm:text-3xl font-black text-slate-900">
                                {{ $lowongan->posisi }}
                            </h1>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="bg-emerald-600 text-white text-[11px] font-bold px-3 py-1 rounded-lg">
                                    {{ $lowongan->jurusan_sesuai }}
                                </span>
                                <span class="bg-blue-600 text-white text-[11px] font-bold px-3 py-1 rounded-lg">
                                    {{ $lowongan->tipe }}
                                </span>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500 font-semibold mb-4">
                            @php
                                // Pemisah bullet hanya muncul kalau ada isi di
                                // sebelah kanannya, supaya tidak ada "• •".
                                $adaNama = (bool) $lowongan->dudi?->nama_dudi;
                                $adaKota = (bool) $lowongan->dudi?->kota;
                            @endphp

                            @if ($adaNama)
                                <span class="flex items-center gap-1.5 text-slate-700">
                                    <i class="fa-solid fa-building text-brand-blue"></i>
                                    {{ $lowongan->dudi->nama_dudi }}
                                </span>
                            @endif

                            @if ($adaKota)
                                @if ($adaNama)
                                    <span>&bull;</span>
                                @endif
                                <span class="flex items-center gap-1.5">
                                    <i class="fa-solid fa-location-dot text-brand-blue"></i>
                                    {{ $lowongan->dudi->kota }}
                                </span>
                            @endif

                            @if ($adaNama || $adaKota)
                                <span>&bull;</span>
                            @endif
                            <span class="flex items-center gap-1.5 text-amber-600">
                                <i class="fa-regular fa-clock"></i>
                                Batas: {{ $lowongan->deadline->locale('id')->translatedFormat('d F Y') }}
                            </span>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            <a href="{{ $lowongan->link_daftar }}" target="_blank" rel="noopener noreferrer"
                                class="bg-brand-blue hover:bg-brand-darkBlue text-white font-bold px-6 py-2.5 rounded-xl text-xs transition-all shadow-md shadow-blue-500/25 inline-flex items-center gap-2">
                                Lamar Lowongan Ini <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                            </a>

                            @if ($lowongan->dudi_id && $lowongan->dudi?->tampil_di_landing)
                                <a href="{{ route('pkl.detail', $lowongan->dudi_id) }}"
                                    class="bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 font-bold px-4 py-2.5 rounded-xl text-xs transition-colors inline-flex items-center gap-1.5">
                                    <i class="fa-solid fa-circle-info text-brand-blue"></i> Profil Lengkap Mitra DUDI
                                </a>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

            {{-- ============================================================
                 BOTTOM SECTION: PROFIL PERUSAHAAN & INFORMASI SINGKAT
                 ============================================================ --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                <!-- LEFT (8 COLS): DETAIL & PERSYARATAN -->
                <div class="lg:col-span-8 space-y-6">

                    <!-- Profil Perusahaan -->
                    <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-1.5 h-7 bg-brand-blue rounded-full"></div>
                            <h3 class="text-xl font-bold text-slate-900">Profil Perusahaan</h3>
                        </div>

                        <p class="text-sm text-slate-600 leading-relaxed">
                            {{ $lowongan->dudi?->deskripsi ?: ($lowongan->deskripsi ?: 'Profil perusahaan belum diisi oleh BKK.') }}
                        </p>
                    </div>

                    <!-- Deskripsi & Kualifikasi Lowongan -->
                    <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-1.5 h-7 bg-brand-blue rounded-full"></div>
                            <h3 class="text-xl font-bold text-slate-900">Deskripsi &amp; Persyaratan Pekerjaan</h3>
                        </div>

                        <div class="text-sm text-slate-700 leading-relaxed whitespace-pre-line space-y-3">
                            {{ $lowongan->deskripsi }}
                        </div>
                    </div>

                    @if ($lowonganLainnya->isNotEmpty())
                        <!-- Lowongan Lainnya -->
                        <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm">
                            <h3 class="text-lg font-bold text-slate-900 mb-4">Lowongan Kerja Lainnya</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                @foreach ($lowonganLainnya as $other)
                                    <a href="{{ route('bkk.detail', $other->id) }}"
                                        class="block bg-slate-50 border border-slate-200 hover:border-brand-blue rounded-2xl p-4 transition-all group">
                                        <span class="block text-[10px] font-bold uppercase text-brand-blue mb-1 truncate">
                                            {{ $other->jurusan_sesuai }}
                                        </span>
                                        <h4 class="font-extrabold text-slate-900 text-sm group-hover:text-brand-blue transition-colors truncate">
                                            {{ $other->posisi }}
                                        </h4>
                                        <p class="text-[11px] text-slate-500 mt-1 truncate">
                                            {{ $other->dudi?->nama_dudi ?? $other->nama_perusahaan }}
                                        </p>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

                <!-- RIGHT (4 COLS): INFORMASI SINGKAT & LAMAR -->
                <div class="lg:col-span-4 space-y-6">

                    <!-- Informasi Singkat Card -->
                    <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
                        <div class="flex items-center gap-2 mb-4">
                            <i class="fa-regular fa-circle-info text-brand-blue text-lg"></i>
                            <h3 class="text-lg font-bold text-slate-900">Informasi Singkat</h3>
                        </div>

                        <div class="space-y-3">
                            <div class="bg-slate-50 border border-slate-100 rounded-2xl p-3.5 text-center">
                                <span class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Pendidikan</span>
                                <span class="block text-sm font-bold text-slate-800">
                                    {{ $lowongan->jurusan_sesuai === \App\Models\Lowongan::SEMUA_JURUSAN ? 'Semua Jurusan SMK' : 'Lulusan ' . $lowongan->jurusan_sesuai }}
                                </span>
                            </div>

                            <div class="bg-slate-50 border border-slate-100 rounded-2xl p-3.5 text-center">
                                <span class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Pengalaman</span>
                                <span class="block text-sm font-bold text-slate-800">Fresh Graduate / Alumni</span>
                            </div>

                            <div class="bg-slate-50 border border-slate-100 rounded-2xl p-3.5 text-center">
                                <span class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Tipe Pekerjaan</span>
                                <span class="block text-sm font-bold text-slate-800">{{ $lowongan->tipe }}</span>
                            </div>

                            <div class="bg-slate-50 border border-slate-100 rounded-2xl p-3.5 text-center">
                                <span class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Batas Akhir Lamaran</span>
                                <span class="block text-sm font-bold text-amber-600">
                                    {{ $lowongan->deadline->locale('id')->translatedFormat('d F Y') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Call To Action Box -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-3xl p-6 text-center shadow-sm">
                        <div class="w-12 h-12 rounded-full bg-brand-blue text-white flex items-center justify-center mx-auto mb-3 text-lg shadow-md shadow-blue-500/20">
                            <i class="fa-solid fa-paper-plane"></i>
                        </div>
                        <h4 class="font-extrabold text-slate-900 text-base mb-1">Daftar Sekarang</h4>
                        <p class="text-xs text-slate-600 leading-relaxed mb-4">
                            Siapkan berkas lamaran Anda dan klik tautan di bawah ini untuk mengajukan lamaran langsung ke perusahaan.
                        </p>
                        <a href="{{ $lowongan->link_daftar }}" target="_blank" rel="noopener noreferrer"
                            class="w-full inline-flex items-center justify-center gap-2 bg-brand-blue hover:bg-brand-darkBlue text-white font-bold py-3.5 px-4 rounded-xl text-xs transition-all shadow-md shadow-blue-500/30">
                            Lamar Lowongan Sekarang <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                        </a>
                    </div>

                </div>

            </div>

        </div>
    </div>
@endsection
