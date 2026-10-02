@extends('Public.layout.app')

@section('title', 'Detail Mitra DUDI: ' . $dudi->nama_dudi . ' — SMKN 2 Karanganyar')

@section('content')
    <div class="py-10 bg-[#F8FAFC]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Breadcrumb & Back Link --}}
            <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500">
                    <a href="{{ url('/') }}" class="hover:text-brand-blue">Beranda</a>
                    <i class="fa-solid fa-chevron-right text-[10px] text-slate-300"></i>
                    <a href="{{ route('pkl') }}" class="hover:text-brand-blue">Praktik Kerja Lapangan</a>
                    <i class="fa-solid fa-chevron-right text-[10px] text-slate-300"></i>
                    <span class="text-slate-900 truncate max-w-xs">{{ $dudi->nama_dudi }}</span>
                </nav>

                <a href="{{ route('pkl') }}#mitra"
                    class="inline-flex items-center gap-2 bg-white border border-slate-200 hover:border-brand-blue text-slate-700 hover:text-brand-blue font-bold px-4 py-2 rounded-xl text-xs transition-colors shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Mitra
                </a>
            </div>

            {{-- ============================================================
                 TOP SECTION: LOGO & PROFIL PERUSAHAAN (SESUAI DESAIN MOCKUP)
                 ============================================================ --}}
            <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm mb-8 relative overflow-hidden">
                <div class="absolute top-6 right-6">
                    @if ($dudi->is_mitra_resmi)
                        <span class="bg-emerald-600 text-white text-xs font-bold px-3.5 py-1.5 rounded-lg shadow-sm flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-check"></i> Mitra Resmi
                        </span>
                    @else
                        <span class="bg-blue-600 text-white text-xs font-bold px-3.5 py-1.5 rounded-lg shadow-sm">
                            Mitra Terdaftar
                        </span>
                    @endif
                </div>

                <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                    <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl bg-blue-50 flex items-center justify-center p-3 border border-blue-200 shrink-0 shadow-sm overflow-hidden">
                        @if ($dudi->logo_url)
                            <img src="{{ $dudi->logo_url }}" alt="Logo {{ $dudi->nama_dudi }}" class="w-full h-full object-contain">
                        @else
                            <span class="font-black text-brand-blue text-2xl sm:text-3xl">
                                {{ strtoupper(substr($dudi->nama_dudi, 0, 3)) }}
                            </span>
                        @endif
                    </div>

                    <div class="flex-grow pr-0 md:pr-28">
                        <div class="flex flex-wrap items-center gap-2 mb-1.5">
                            <span class="text-xs font-bold uppercase tracking-wider text-brand-blue bg-blue-50 px-2.5 py-0.5 rounded border border-blue-100">
                                {{ $dudi->jurusan?->nama ?? $dudi->bidang_usaha ?? 'Umum' }}
                            </span>
                            <span class="text-xs text-slate-400">&bull;</span>
                            <span class="text-xs text-slate-500 font-medium">
                                <i class="fa-solid fa-location-dot text-brand-blue mr-1"></i> {{ $dudi->kota ?? 'Karanganyar' }}
                            </span>
                        </div>

                        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 mb-2">
                            {{ $dudi->nama_dudi }}
                        </h1>

                        <p class="text-sm text-slate-600 leading-relaxed mb-4 max-w-3xl">
                            {{ $dudi->deskripsi ?? 'Mitra industri resmi yang bekerja sama dengan SMK Negeri 2 Karanganyar dalam program Praktik Kerja Lapangan (PKL) dan sinkronisasi kurikulum berbasis industri.' }}
                        </p>

                        <div class="flex flex-wrap items-center gap-3">
                            @if (!empty($dudi->no_hp))
                                <a href="tel:{{ $dudi->no_hp }}"
                                    class="bg-blue-50 text-brand-blue hover:bg-blue-100 px-4 py-2 rounded-lg text-xs font-semibold flex items-center gap-2 transition-colors">
                                    <i class="fa-solid fa-phone"></i> Hubungi Mitra: {{ $dudi->no_hp }}
                                </a>
                            @endif

                            <div class="bg-slate-50 border border-slate-200 px-3.5 py-1.5 rounded-lg text-xs font-semibold text-slate-700 flex items-center gap-2">
                                <i class="fa-solid fa-users text-brand-blue"></i>
                                <span>Kuota: <strong>{{ $dudi->kuota_maksimal }}</strong></span>
                                <span class="text-slate-300">|</span>
                                <span>Terisi: <strong class="text-emerald-600">{{ $siswaFixCount }}</strong></span>
                                <span class="text-slate-300">|</span>
                                <span>Sisa: <strong class="text-brand-blue">{{ $dudi->sisa_kuota }}</strong></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============================================================
                 BOTTOM SECTION: TABEL SISWA AKTIF & PROGRAM TERSEDIA
                 ============================================================ --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                <!-- LEFT (8 COLS): SISWA AKTIF PKL -->
                <div class="lg:col-span-8 bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-slate-900">Siswa Aktif PKL</h3>
                            <p class="text-xs text-slate-500 mt-1">Daftar siswa SMKN 2 Karanganyar yang ditempatkan pada mitra industri ini.</p>
                        </div>
                        <span class="bg-blue-50 text-brand-blue text-xs font-bold px-3 py-1.5 rounded-xl border border-blue-100">
                            {{ count($penempatans) }} Siswa
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-200 text-xs text-slate-400 uppercase tracking-wider font-bold">
                                    <th class="py-3 px-3">Nama Siswa</th>
                                    <th class="py-3 px-3">Jurusan</th>
                                    <th class="py-3 px-3">Periode</th>
                                    <th class="py-3 px-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-slate-100">
                                @forelse ($penempatans as $penempatan)
                                    @php
                                        $siswa = $penempatan->siswa;
                                        $surat = $penempatan->suratPengajuan;
                                        $jurusanNama = $siswa?->jurusan ?? 'Teknik';

                                        // Badge styling jurusan
                                        $badgeBg = 'bg-blue-100 text-blue-800';
                                        if (str_contains(strtolower($jurusanNama), 'mesin')) {
                                            $badgeBg = 'bg-blue-100 text-blue-700';
                                        } elseif (str_contains(strtolower($jurusanNama), 'kain') || str_contains(strtolower($jurusanNama), 'tekstil')) {
                                            $badgeBg = 'bg-amber-100 text-amber-800';
                                        } elseif (str_contains(strtolower($jurusanNama), 'oto')) {
                                            $badgeBg = 'bg-red-100 text-red-700';
                                        } elseif (str_contains(strtolower($jurusanNama), 'rpl') || str_contains(strtolower($jurusanNama), 'perangkat')) {
                                            $badgeBg = 'bg-emerald-100 text-emerald-800';
                                        }
                                    @endphp
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="py-3.5 px-3">
                                            <div class="font-bold text-slate-900">{{ $siswa->nama ?? 'Siswa SMKN 2' }}</div>
                                            <div class="text-[11px] text-slate-400">NIS: {{ $siswa->nis ?? '-' }} &bull; Kelas {{ $siswa->kelas ?? 'XII' }}</div>
                                        </td>
                                        <td class="py-3.5 px-3">
                                            <span class="{{ $badgeBg }} text-[11px] font-bold px-2.5 py-1 rounded-md">
                                                {{ $jurusanNama }}
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-3 text-xs text-slate-600 font-medium whitespace-nowrap">
                                            @if ($surat && $surat->tgl_mulai_pkl && $surat->tgl_selesai_pkl)
                                                {{ $surat->tgl_mulai_pkl->translatedFormat('M Y') }} - {{ $surat->tgl_selesai_pkl->translatedFormat('M Y') }}
                                            @else
                                                Periode 2026
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-3 whitespace-nowrap">
                                            @if ($penempatan->status_penempatan === \App\Models\PenempatanPkl::STATUS_FIX)
                                                <span class="text-emerald-600 font-bold text-xs flex items-center gap-1.5">
                                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                                    Berlangsung
                                                </span>
                                            @elseif ($penempatan->status_penempatan === \App\Models\PenempatanPkl::STATUS_PENGAJUAN)
                                                <span class="text-amber-600 font-bold text-xs flex items-center gap-1.5">
                                                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                                    Pengajuan
                                                </span>
                                            @else
                                                <span class="text-red-500 font-bold text-xs">
                                                    {{ $penempatan->status_penempatan }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-8 text-center text-slate-400 text-sm">
                                            <i class="fa-solid fa-user-group text-slate-300 text-3xl mb-2 block"></i>
                                            Belum ada siswa yang tercatat aktif di mitra ini untuk periode saat ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- RIGHT (4 COLS): PROGRAM TERSEDIA & KONTAK -->
                <div class="lg:col-span-4 space-y-6">

                    <!-- Program Tersedia -->
                    <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
                        <div class="flex items-center gap-2 mb-4">
                            <i class="fa-solid fa-clipboard-check text-brand-blue text-lg"></i>
                            <h3 class="text-lg font-bold text-slate-900">Program Tersedia</h3>
                        </div>

                        <div class="space-y-3">
                            @foreach ($programs as $prog)
                                @php
                                    $judul = is_array($prog) ? ($prog['judul'] ?? 'Program Spesialisasi') : $prog;
                                    $deskripsi = is_array($prog) ? ($prog['deskripsi'] ?? 'Program peningkatan kompetensi siswa terstandar industri.') : 'Program pelatihan dan praktik kerja langsung di lingkungan industri mitra.';
                                @endphp
                                <div class="bg-blue-50/70 border border-blue-100 rounded-2xl p-4 transition-all hover:bg-blue-50">
                                    <h4 class="text-sm font-bold text-brand-blue mb-1">
                                        {{ $judul }}
                                    </h4>
                                    <p class="text-xs text-slate-600 leading-relaxed">
                                        {{ $deskripsi }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Informasi Lokasi & Kontak -->
                    <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
                        <div class="flex items-center gap-2 mb-4">
                            <i class="fa-solid fa-circle-info text-brand-blue text-lg"></i>
                            <h3 class="text-lg font-bold text-slate-900">Informasi Mitra</h3>
                        </div>

                        <div class="space-y-3 text-xs text-slate-600">
                            <div class="flex items-start gap-2.5">
                                <i class="fa-solid fa-map-location-dot text-brand-blue mt-0.5"></i>
                                <div>
                                    <span class="block font-bold text-slate-800">Alamat Perusahaan:</span>
                                    <span>{{ $dudi->alamat ?? 'Jl. Raya Industri' }}, {{ $dudi->kota ?? 'Karanganyar' }}</span>
                                </div>
                            </div>

                            <div class="flex items-start gap-2.5">
                                <i class="fa-solid fa-user-tie text-brand-blue mt-0.5"></i>
                                <div>
                                    <span class="block font-bold text-slate-800">Kontak Person:</span>
                                    <span>{{ $dudi->kontak_person ?? 'Koordinator PKL Perusahaan' }}</span>
                                </div>
                            </div>

                            <div class="flex items-start gap-2.5">
                                <i class="fa-solid fa-briefcase text-brand-blue mt-0.5"></i>
                                <div>
                                    <span class="block font-bold text-slate-800">Bidang Usaha:</span>
                                    <span>{{ $dudi->bidang_usaha ?? $dudi->jurusan?->nama ?? 'Industri & Manufaktur' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
@endsection
