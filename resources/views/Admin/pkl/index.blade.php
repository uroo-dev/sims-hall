@extends('Admin.layout.app')

@section('title', 'Data PKL - BKK')
@section('page_title', $pageTitle ?? 'Data PKL')

@section('content')

    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            {{-- Breadcrumb Navigation --}}
            <nav class="flex items-center gap-1.5 text-xs text-gray-500 mb-1">
                <a href="{{ route('pkl.index') }}" class="hover:text-brand-600 font-medium transition">Data Penempatan PKL</a>
                @if ($filterJurusan)
                    <i class="fa-solid fa-chevron-right text-[10px] text-gray-400"></i>
                    @if ($filterKelas)
                        <a href="{{ route('pkl.index', ['jurusan' => $filterJurusan]) }}" class="hover:text-brand-600 font-medium transition">{{ $filterJurusan }}</a>
                        <i class="fa-solid fa-chevron-right text-[10px] text-gray-400"></i>
                        <span class="font-bold text-brand-600">{{ $filterKelas }}</span>
                    @else
                        <span class="font-bold text-brand-600">{{ $filterJurusan }}</span>
                    @endif
                @elseif ($viewLevel === 'search')
                    <i class="fa-solid fa-chevron-right text-[10px] text-gray-400"></i>
                    <span class="font-bold text-brand-600">Pencarian</span>
                @endif
            </nav>

            <h2 class="font-extrabold text-gray-900 text-lg tracking-tight">
                @if ($viewLevel === 'jurusan')
                    Penempatan PKL per Jurusan
                @elseif ($viewLevel === 'kelas')
                    Jurusan {{ $filterJurusan }} &mdash; Pilih Kelas
                @elseif ($viewLevel === 'siswa')
                    Daftar Siswa Kelas {{ $filterKelas }}
                @else
                    Hasil Pencarian Penempatan PKL
                @endif
            </h2>
            <p class="text-xs text-gray-500 mt-0.5">
                @if ($viewLevel === 'jurusan')
                    Pilih jurusan untuk melihat rincian progres penempatan PKL per kelas dan per siswa.
                @elseif ($viewLevel === 'kelas')
                    Pilih kelas untuk melihat daftar siswa, status FIX, download PDF surat, dan perbaikan data.
                @elseif ($viewLevel === 'siswa')
                    Monitoring status PKL siswa kelas {{ $filterKelas }}, aksi unduh PDF surat, dan update data surat.
                @else
                    Menampilkan data penempatan yang sesuai dengan filter pencarian.
                @endif
            </p>
        </div>

        <div class="flex items-center gap-2">
            @if ($viewLevel === 'kelas')
                <a href="{{ route('pkl.index') }}"
                    class="text-xs font-semibold bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 px-3.5 py-2 rounded-xl transition shadow-2xs">
                    <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali ke Jurusan
                </a>
            @elseif ($viewLevel === 'siswa')
                <a href="{{ route('pkl.index', ['jurusan' => $filterJurusan]) }}"
                    class="text-xs font-semibold bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 px-3.5 py-2 rounded-xl transition shadow-2xs">
                    <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali ke Daftar Kelas
                </a>
            @elseif ($viewLevel === 'search')
                <a href="{{ route('pkl.index') }}"
                    class="text-xs font-semibold bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 px-3.5 py-2 rounded-xl transition shadow-2xs">
                    <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali ke Tampilan Jurusan
                </a>
            @endif

            <a href="{{ route('pkl.create') }}"
                class="text-xs font-bold bg-brand-600 hover:bg-brand-700 text-white px-4 py-2 rounded-xl transition shadow-xs flex items-center gap-1.5">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Buat Pengajuan PKL</span>
            </a>
        </div>
    </div>

    {{-- ==================== LEVEL 1: LIST JURUSAN ==================== --}}
    @if ($viewLevel === 'jurusan')
        {{-- Search quick filter --}}
        <form method="GET" action="{{ route('pkl.index') }}"
            class="bg-white rounded-2xl border border-gray-100 card-shadow p-4 flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[220px]">
                <label class="block text-[11px] font-semibold text-gray-600 mb-1.5">Cari Cepat Siswa / DUDI</label>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-xs text-gray-400"></i>
                    <input type="text" name="q" value="{{ $search }}" placeholder="Ketik nama siswa atau perusahaan DUDI..."
                        class="w-full text-sm pl-9 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                </div>
            </div>

            <div class="w-full sm:w-48">
                <label class="block text-[11px] font-semibold text-gray-600 mb-1.5">Filter Status</label>
                <select name="status"
                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    <option value="">Semua Status</option>
                    @foreach (\App\Models\PenempatanPkl::STATUS_LABEL as $key => $label)
                        <option value="{{ $key }}" @selected($filterStatus === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit"
                class="text-sm font-semibold bg-brand-600 hover:bg-brand-700 text-white px-4 py-2 rounded-lg transition cursor-pointer">
                <i class="fa-solid fa-filter mr-1.5"></i> Cari Data
            </button>
        </form>

        {{-- Grid Cards Jurusan --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @forelse ($jurusanList as $j)
                <div class="bg-white rounded-2xl border border-gray-100 card-shadow p-5 flex flex-col justify-between hover:border-brand-200 transition group">
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-blue-50 text-brand-600 border border-blue-100/70 flex items-center justify-center text-xl shrink-0 group-hover:scale-105 transition-transform">
                                    <i class="fa-solid fa-graduation-cap"></i>
                                </div>
                                <div>
                                    <h3 class="font-extrabold text-gray-900 text-base group-hover:text-brand-600 transition">
                                        {{ $j->nama }}
                                    </h3>
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        {{ count($j->list_kelas) }} Kelas terdaftar
                                    </p>
                                </div>
                            </div>
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 shrink-0">
                                {{ $j->total }} Siswa
                            </span>
                        </div>

                        {{-- Progress Bar FIX --}}
                        <div class="mt-4 p-3 bg-slate-50/80 rounded-xl border border-slate-100">
                            <div class="flex items-center justify-between text-xs mb-1.5">
                                <span class="font-semibold text-slate-700">Progres Penempatan FIX:</span>
                                <span class="font-extrabold text-emerald-600 text-sm">{{ $j->persentase_fix }}%</span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-2.5 overflow-hidden">
                                <div class="bg-emerald-500 h-2.5 rounded-full transition-all duration-500"
                                    style="width: {{ $j->persentase_fix }}%"></div>
                            </div>
                            <div class="flex items-center justify-between text-[11px] text-slate-500 mt-2">
                                <span><strong class="text-emerald-600">{{ $j->fix }}</strong> FIX</span>
                                <span><strong class="text-amber-600">{{ $j->pengajuan }}</strong> Menunggu</span>
                                <span><strong class="text-slate-700">{{ $j->belum }}</strong> Belum Dapat</span>
                            </div>
                        </div>

                        {{-- List Kelas preview --}}
                        @if (!empty($j->list_kelas))
                            <div class="mt-3.5">
                                <span class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider block mb-1.5">Kelas:</span>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach ($j->list_kelas as $kelasItem)
                                        <a href="{{ route('pkl.index', ['jurusan' => $j->nama, 'kelas' => $kelasItem]) }}"
                                            class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-gray-100 hover:bg-brand-50 hover:text-brand-600 text-gray-700 transition">
                                            {{ $kelasItem }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="mt-5 pt-4 border-t border-gray-100">
                        <a href="{{ route('pkl.index', ['jurusan' => $j->nama]) }}"
                            class="w-full flex items-center justify-between text-xs font-bold text-white bg-brand-600 hover:bg-brand-700 px-4 py-2.5 rounded-xl shadow-xs transition cursor-pointer">
                            <span>Buka Jurusan (Lihat {{ count($j->list_kelas) }} Kelas)</span>
                            <i class="fa-solid fa-chevron-right text-xs"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-2 bg-white rounded-2xl border border-gray-100 card-shadow p-12 text-center">
                    <i class="fa-solid fa-school text-4xl text-gray-300 mb-3 block"></i>
                    <p class="text-gray-500 text-sm">Belum ada data jurusan siswa yang tersimpan.</p>
                </div>
            @endforelse
        </div>

    {{-- ==================== LEVEL 2: LIST KELAS ==================== --}}
    @elseif ($viewLevel === 'kelas')
        {{-- Banner Jurusan --}}
        @php
            $currentJurusan = $jurusanList->firstWhere('nama', $filterJurusan);
        @endphp
        @if ($currentJurusan)
            <div class="bg-gradient-to-r from-brand-600 to-blue-700 rounded-2xl p-5 text-white shadow-md flex items-center justify-between flex-wrap gap-4">
                <div class="space-y-1">
                    <span class="text-blue-200 text-xs font-semibold uppercase tracking-wider">Jurusan Terpilih</span>
                    <h3 class="text-xl font-black tracking-tight">{{ $currentJurusan->nama }}</h3>
                    <p class="text-xs text-blue-100">Total {{ $currentJurusan->total }} siswa &middot; {{ count($kelasList) }} rombel kelas</p>
                </div>
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-xl px-4 py-2.5 text-center">
                    <span class="text-[11px] text-blue-100 block">Progres Jurusan</span>
                    <span class="text-2xl font-black text-white">{{ $currentJurusan->persentase_fix }}%</span>
                    <span class="text-[10px] text-blue-200 block">{{ $currentJurusan->fix }} / {{ $currentJurusan->total }} FIX</span>
                </div>
            </div>
        @endif

        {{-- Grid Cards Kelas --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse ($kelasList as $k)
                <div class="bg-white rounded-2xl border border-gray-100 card-shadow p-5 flex flex-col justify-between hover:border-brand-200 transition group">
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-100/70 flex items-center justify-center text-lg shrink-0 group-hover:scale-105 transition-transform">
                                    <i class="fa-solid fa-users"></i>
                                </div>
                                <div>
                                    <h3 class="font-extrabold text-gray-900 text-base group-hover:text-brand-600 transition">
                                        {{ $k->kelas }}
                                    </h3>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $k->jurusan }}</p>
                                </div>
                            </div>
                            <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 shrink-0">
                                {{ $k->total }} Siswa
                            </span>
                        </div>

                        {{-- Progres Kelas --}}
                        <div class="mt-4 p-3 bg-slate-50/80 rounded-xl border border-slate-100">
                            <div class="flex items-center justify-between text-xs mb-1.5">
                                <span class="font-semibold text-slate-700">Sudah Dapat PKL:</span>
                                <span class="font-extrabold text-emerald-600 text-sm">{{ $k->persentase_fix }}%</span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                                <div class="bg-emerald-500 h-2 rounded-full transition-all duration-500"
                                    style="width: {{ $k->persentase_fix }}%"></div>
                            </div>
                            <div class="flex items-center justify-between text-[11px] text-slate-500 mt-2">
                                <span><strong class="text-emerald-600">{{ $k->fix }}</strong> FIX</span>
                                <span><strong class="text-amber-600">{{ $k->pengajuan }}</strong> Menunggu</span>
                                <span><strong class="text-slate-700">{{ $k->belum }}</strong> Belum</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 pt-4 border-t border-gray-100">
                        <a href="{{ route('pkl.index', ['jurusan' => $filterJurusan, 'kelas' => $k->kelas]) }}"
                            class="w-full flex items-center justify-between text-xs font-bold text-white bg-brand-600 hover:bg-brand-700 px-4 py-2.5 rounded-xl shadow-xs transition cursor-pointer">
                            <span>Buka Siswa Kelas {{ $k->kelas }}</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 bg-white rounded-2xl border border-gray-100 card-shadow p-12 text-center">
                    <i class="fa-solid fa-chalkboard-user text-4xl text-gray-300 mb-3 block"></i>
                    <p class="text-gray-500 text-sm">Tidak ditemukan kelas untuk jurusan {{ $filterJurusan }}.</p>
                </div>
            @endforelse
        </div>

    {{-- ==================== LEVEL 3: TABEL SISWA PER KELAS ==================== --}}
    @elseif ($viewLevel === 'siswa')
        {{-- Summary Banner Kelas --}}
        @if ($kelasStats)
            <div class="bg-white rounded-2xl border border-gray-100 card-shadow p-5">
                <div class="flex items-center justify-between flex-wrap gap-4 mb-4">
                    <div>
                        <span class="text-[11px] font-bold text-brand-600 uppercase tracking-wider block">Ringkasan Progres Kelas</span>
                        <h3 class="text-xl font-black text-gray-900 mt-0.5">Kelas {{ $kelasStats->kelas }}</h3>
                        <p class="text-xs text-gray-500">{{ $kelasStats->jurusan }}</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-center">
                            <span class="text-[10px] text-gray-500 block">Total Siswa</span>
                            <span class="font-extrabold text-sm text-gray-800">{{ $kelasStats->total }}</span>
                        </div>
                        <div class="px-3 py-2 bg-emerald-50 border border-emerald-200 rounded-xl text-center">
                            <span class="text-[10px] text-emerald-700 block">Sudah PKL (FIX)</span>
                            <span class="font-extrabold text-sm text-emerald-700">{{ $kelasStats->fix }}</span>
                        </div>
                        <div class="px-3 py-2 bg-amber-50 border border-amber-200 rounded-xl text-center">
                            <span class="text-[10px] text-amber-700 block">Menunggu</span>
                            <span class="font-extrabold text-sm text-amber-700">{{ $kelasStats->pengajuan }}</span>
                        </div>
                        <div class="px-3 py-2 bg-red-50 border border-red-200 rounded-xl text-center">
                            <span class="text-[10px] text-red-700 block">Belum PKL</span>
                            <span class="font-extrabold text-sm text-red-700">{{ $kelasStats->belum }}</span>
                        </div>
                    </div>
                </div>

                {{-- Progress Bar --}}
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-semibold text-gray-700">Persentase Penempatan FIX</span>
                        <span class="font-extrabold text-emerald-600 text-sm">{{ $kelasStats->persentase_fix }}%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                        <div class="bg-emerald-500 h-3 rounded-full transition-all duration-500"
                            style="width: {{ $kelasStats->persentase_fix }}%"></div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Filter dalam Kelas --}}
        <form method="GET" action="{{ route('pkl.index') }}"
            class="bg-white rounded-2xl border border-gray-100 card-shadow p-4 flex flex-wrap items-end gap-3">
            <input type="hidden" name="jurusan" value="{{ $filterJurusan }}">
            <input type="hidden" name="kelas" value="{{ $filterKelas }}">

            <div class="flex-1 min-w-[180px]">
                <label class="block text-[11px] font-semibold text-gray-600 mb-1.5">Cari Siswa</label>
                <input type="text" name="q" value="{{ $search }}" placeholder="Nama atau NIS..."
                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
            </div>

            <div class="w-full sm:w-48">
                <label class="block text-[11px] font-semibold text-gray-600 mb-1.5">Status PKL</label>
                <select name="status"
                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    <option value="">Semua Siswa</option>
                    <option value="fix" @selected($filterStatus === 'fix')>Diterima (FIX)</option>
                    <option value="pengajuan" @selected($filterStatus === 'pengajuan')>Menunggu Balasan</option>
                    <option value="ditolak" @selected($filterStatus === 'ditolak')>Ditolak</option>
                    <option value="belum" @selected($filterStatus === 'belum')>Belum Ada Tempat</option>
                </select>
            </div>

            <button type="submit"
                class="text-sm font-semibold bg-brand-600 hover:bg-brand-700 text-white px-4 py-2 rounded-lg transition">
                <i class="fa-solid fa-filter mr-1.5"></i> Terapkan
            </button>

            @if ($search || $filterStatus)
                <a href="{{ route('pkl.index', ['jurusan' => $filterJurusan, 'kelas' => $filterKelas]) }}"
                    class="text-sm font-semibold text-gray-500 hover:text-gray-700 px-3 py-2 rounded-lg transition">Reset</a>
            @endif
        </form>

        {{-- Tabel Siswa Kelas --}}
        <div class="bg-white rounded-2xl border border-gray-100 card-shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-[11px] uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="text-left font-semibold px-5 py-3">No</th>
                            <th class="text-left font-semibold px-4 py-3">Siswa</th>
                            <th class="text-left font-semibold px-3 py-3">Tempat PKL (DUDI)</th>
                            <th class="text-left font-semibold px-3 py-3">Pembimbing</th>
                            <th class="text-left font-semibold px-3 py-3">No. Surat</th>
                            <th class="text-center font-semibold px-3 py-3">Status</th>
                            <th class="text-center font-semibold px-5 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($siswasKelas as $idx => $s)
                            @php
                                $penempatan = $s->penempatanPkls->first();
                                $surat = $penempatan?->suratPengajuan;
                                $status = $penempatan?->status_penempatan;
                                $style = match ($status) {
                                    \App\Models\PenempatanPkl::STATUS_FIX => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    \App\Models\PenempatanPkl::STATUS_DITOLAK => 'bg-red-50 text-red-700 border-red-200',
                                    \App\Models\PenempatanPkl::STATUS_PENGAJUAN => 'bg-amber-50 text-amber-700 border-amber-200',
                                    default => 'bg-gray-100 text-gray-600 border-gray-200',
                                };
                                $statusLabel = match ($status) {
                                    \App\Models\PenempatanPkl::STATUS_FIX => 'Diterima (FIX)',
                                    \App\Models\PenempatanPkl::STATUS_DITOLAK => 'Ditolak',
                                    \App\Models\PenempatanPkl::STATUS_PENGAJUAN => 'Menunggu Balasan',
                                    default => 'Belum Ada Tempat',
                                };
                            @endphp
                            <tr class="hover:bg-gray-50/70 transition">
                                <td class="px-5 py-3 text-xs text-gray-400 font-medium">{{ $idx + 1 }}</td>
                                <td class="px-4 py-3">
                                    <div class="font-bold text-gray-900 text-sm">{{ $s->nama }}</div>
                                    <div class="text-[11px] text-gray-500">NIS: {{ $s->nis }}</div>
                                </td>
                                <td class="px-3 py-3">
                                    @if ($penempatan && $penempatan->dudi)
                                        <div class="font-semibold text-gray-800 text-xs">{{ $penempatan->dudi->nama_dudi }}</div>
                                        <div class="text-[11px] text-gray-500">{{ $penempatan->dudi->kota }}</div>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Belum terdaftar</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-xs text-gray-700">
                                    {{ $penempatan?->guru?->nama ?? '-' }}
                                </td>
                                <td class="px-3 py-3">
                                    @if ($surat)
                                        <a href="{{ route('pkl.surat.show', $surat) }}"
                                            class="text-xs font-bold text-brand-600 hover:text-brand-800 hover:underline inline-flex items-center gap-1"
                                            title="Buka / Edit Surat">
                                            <i class="fa-solid fa-file-lines text-[11px]"></i>
                                            <span>{{ $surat->nomor_surat }}</span>
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-center">
                                    <span class="text-[10px] font-bold px-2.5 py-1 rounded-md border {{ $style }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-center gap-1.5">
                                        @if ($surat)
                                            {{-- Tombol Download Ulang File PDF --}}
                                            <a href="{{ route('pkl.surat.download', $surat) }}"
                                                class="px-2.5 py-1.5 inline-flex items-center gap-1 text-xs font-semibold bg-blue-50 hover:bg-blue-100 text-brand-700 rounded-lg transition"
                                                title="Download Ulang Berkas PDF">
                                                <i class="fa-solid fa-file-arrow-down text-brand-600"></i>
                                                <span class="hidden sm:inline">PDF</span>
                                            </a>

                                            {{-- Tombol Update / Edit Data Surat (jika typo) --}}
                                            <a href="{{ route('pkl.surat.show', $surat) }}"
                                                class="px-2.5 py-1.5 inline-flex items-center gap-1 text-xs font-semibold bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-lg transition"
                                                title="Update Data Surat / Perbaiki Typo">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                                <span class="hidden sm:inline">Update</span>
                                            </a>

                                            {{-- Quick Action Response Status jika masih pengajuan --}}
                                            @if ($status === \App\Models\PenempatanPkl::STATUS_PENGAJUAN)
                                                <button type="button" title="Setujui (FIX)"
                                                    onclick="openStatusModal('{{ route('pkl.penempatan.status', $penempatan) }}', @js($penempatan->dudi?->nama_dudi ?? '-'), @js($s->nama), 'FIX')"
                                                    class="w-7 h-7 inline-flex items-center justify-center text-xs bg-emerald-50 hover:bg-emerald-100 text-emerald-600 rounded-lg transition cursor-pointer">
                                                    <i class="fa-solid fa-check"></i>
                                                </button>
                                                <button type="button" title="Tolak"
                                                    onclick="openStatusModal('{{ route('pkl.penempatan.status', $penempatan) }}', @js($penempatan->dudi?->nama_dudi ?? '-'), @js($s->nama), 'ditolak')"
                                                    class="w-7 h-7 inline-flex items-center justify-center text-xs bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition cursor-pointer">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </button>
                                            @endif
                                        @else
                                            <a href="{{ route('pkl.create') }}"
                                                class="px-2.5 py-1.5 inline-flex items-center gap-1 text-xs font-medium bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition"
                                                title="Buat Pengajuan PKL untuk siswa ini">
                                                <i class="fa-solid fa-plus text-[10px]"></i>
                                                <span>Ajukan</span>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-10 text-center">
                                    <i class="fa-solid fa-user-slash text-gray-300 text-3xl mb-3 block"></i>
                                    <p class="text-sm text-gray-500">Tidak ada siswa yang cocok dengan filter di kelas ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    {{-- ==================== LEVEL SEARCH: TABEL FLAT PENEMPATAN ==================== --}}
    @else
        {{-- Form Filter --}}
        <form method="GET" action="{{ route('pkl.index') }}"
            class="bg-white rounded-2xl border border-gray-100 card-shadow p-4 flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[180px]">
                <label class="block text-[11px] font-semibold text-gray-600 mb-1.5">Cari</label>
                <input type="text" name="q" value="{{ $search }}" placeholder="Nama siswa atau DUDI..."
                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
            </div>

            <div class="w-full sm:w-48">
                <label class="block text-[11px] font-semibold text-gray-600 mb-1.5">Status</label>
                <select name="status"
                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    <option value="">Semua Status</option>
                    @foreach (\App\Models\PenempatanPkl::STATUS_LABEL as $key => $label)
                        <option value="{{ $key }}" @selected($filterStatus === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-full sm:w-56">
                <label class="block text-[11px] font-semibold text-gray-600 mb-1.5">DUDI</label>
                <select name="dudi_id"
                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    <option value="">Semua DUDI</option>
                    @foreach ($dudis as $d)
                        <option value="{{ $d->id }}" @selected($filterDudi == $d->id)>{{ $d->nama_dudi }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit"
                class="text-sm font-semibold bg-brand-600 hover:bg-brand-700 text-white px-4 py-2 rounded-lg transition">
                <i class="fa-solid fa-filter mr-1.5"></i> Terapkan
            </button>

            <a href="{{ route('pkl.index') }}"
                class="text-sm font-semibold text-gray-500 hover:text-gray-700 px-3 py-2 rounded-lg transition">Reset</a>
        </form>

        {{-- Tabel Pencarian --}}
        <div class="bg-white rounded-2xl border border-gray-100 card-shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-[11px] uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="text-left font-semibold px-5 py-3">Siswa</th>
                            <th class="text-left font-semibold px-3 py-3">DUDI</th>
                            <th class="text-left font-semibold px-3 py-3">Guru Pembimbing</th>
                            <th class="text-left font-semibold px-3 py-3">Surat</th>
                            <th class="text-center font-semibold px-3 py-3">Status</th>
                            <th class="text-center font-semibold px-5 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($penempatans as $p)
                            <tr class="hover:bg-gray-50/70 transition">
                                <td class="px-5 py-3">
                                    <div class="font-semibold text-gray-800">{{ $p->siswa?->nama ?? '-' }}</div>
                                    <div class="text-[11px] text-gray-500">
                                        {{ $p->siswa?->label_kelas ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-3 py-3">
                                    <div class="text-gray-800">{{ $p->dudi?->nama_dudi ?? '-' }}</div>
                                    <div class="text-[11px] text-gray-500">{{ $p->dudi?->kota ?? '-' }}</div>
                                </td>
                                <td class="px-3 py-3 text-gray-700">{{ $p->guru?->nama ?? '-' }}</td>
                                <td class="px-3 py-3">
                                    <a href="{{ route('pkl.surat.show', $p->surat_pengajuan_id) }}"
                                        class="text-xs font-semibold text-brand-600 hover:text-brand-700">
                                        {{ $p->suratPengajuan?->nomor_surat ?? '-' }}
                                    </a>
                                </td>
                                <td class="px-3 py-3 text-center">
                                    @php
                                        $style = match ($p->status_penempatan) {
                                            \App\Models\PenempatanPkl::STATUS_FIX => 'bg-emerald-50 text-emerald-700',
                                            \App\Models\PenempatanPkl::STATUS_DITOLAK => 'bg-red-50 text-red-700',
                                            default => 'bg-amber-50 text-amber-700',
                                        };
                                    @endphp
                                    <span class="text-[10px] font-bold px-2 py-1 rounded-md {{ $style }}">
                                        {{ $p->status_label }}
                                    </span>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-center gap-1.5">
                                        {{-- Download PDF --}}
                                        @if ($p->surat_pengajuan_id)
                                            <a href="{{ route('pkl.surat.download', $p->surat_pengajuan_id) }}"
                                                class="w-8 h-8 inline-flex items-center justify-center text-xs bg-blue-50 hover:bg-blue-100 text-brand-700 rounded-lg transition"
                                                title="Download Ulang PDF">
                                                <i class="fa-solid fa-file-arrow-down"></i>
                                            </a>
                                            <a href="{{ route('pkl.surat.show', $p->surat_pengajuan_id) }}"
                                                class="w-8 h-8 inline-flex items-center justify-center text-xs bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-lg transition"
                                                title="Edit / Update Data Surat">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                        @endif

                                        @if ($p->status_penempatan === \App\Models\PenempatanPkl::STATUS_PENGAJUAN)
                                            <button type="button" title="DUDI Menerima (FIX)"
                                                onclick="openStatusModal('{{ route('pkl.penempatan.status', $p) }}', @js($p->dudi?->nama_dudi ?? '-'), @js($p->siswa?->nama ?? '-'), 'FIX')"
                                                class="w-8 h-8 inline-flex items-center justify-center text-xs bg-emerald-50 hover:bg-emerald-100 text-emerald-600 rounded-lg transition cursor-pointer">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                            <button type="button" title="DUDI Menolak"
                                                onclick="openStatusModal('{{ route('pkl.penempatan.status', $p) }}', @js($p->dudi?->nama_dudi ?? '-'), @js($p->siswa?->nama ?? '-'), 'ditolak')"
                                                class="w-8 h-8 inline-flex items-center justify-center text-xs bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition cursor-pointer">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center">
                                    <i class="fa-solid fa-inbox text-gray-300 text-3xl mb-3 block"></i>
                                    <p class="text-sm text-gray-500">Belum ada data penempatan PKL yang sesuai.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

@endsection

@push('modals')
<!-- ==================== MODAL KONFIRMASI STATUS ==================== -->
<div id="modalConfirmStatus" class="fixed inset-0 !m-0 z-[100] hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl border border-slate-100 overflow-hidden transform transition-all scale-95 duration-200" id="modalConfirmStatusBox">
        <div class="p-6 text-center space-y-4">
            <div id="statusModalIcon" class="w-14 h-14 mx-auto rounded-2xl bg-blue-50 text-blue-600 border border-blue-100/80 flex items-center justify-center text-2xl shadow-xs">
                <i class="fa-solid fa-question"></i>
            </div>
            <div>
                <h3 id="statusModalTitle" class="font-extrabold text-slate-900 text-lg tracking-tight">Konfirmasi Status Penempatan</h3>
                <p id="statusModalDesc" class="text-xs text-slate-500 mt-1">
                    Pastikan keputusan respon dari DUDI sudah sesuai:
                </p>
                <div class="mt-3 p-3 bg-slate-50 border border-slate-200 rounded-xl text-left space-y-1 text-xs">
                    <div><span class="text-slate-400 font-medium">Siswa:</span> <span id="statusSiswaText" class="font-bold text-slate-800"></span></div>
                    <div><span class="text-slate-400 font-medium">DUDI:</span> <span id="statusDudiText" class="font-bold text-slate-800"></span></div>
                    <div><span class="text-slate-400 font-medium">Status Baru:</span> <span id="statusBaruText" class="font-bold"></span></div>
                </div>
            </div>

            <form id="formConfirmStatus" method="POST" class="pt-2 flex items-center justify-center gap-3">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status_penempatan" id="inputStatusPenempatan" value="">
                <button type="button" onclick="closeStatusModal()"
                    class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs md:text-sm font-semibold transition cursor-pointer">
                    Batal
                </button>
                <button type="submit" id="btnSubmitStatus"
                    class="flex-1 px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 active:scale-95 text-white text-xs md:text-sm font-bold shadow-sm transition flex items-center justify-center gap-1.5 cursor-pointer">
                    <span>Konfirmasi</span>
                </button>
            </form>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    function openStatusModal(actionUrl, dudiName, siswaName, status) {
        const modal = document.getElementById('modalConfirmStatus');
        const box = document.getElementById('modalConfirmStatusBox');
        const form = document.getElementById('formConfirmStatus');
        const icon = document.getElementById('statusModalIcon');
        const title = document.getElementById('statusModalTitle');
        const siswa = document.getElementById('statusSiswaText');
        const dudi = document.getElementById('statusDudiText');
        const statusBaru = document.getElementById('statusBaruText');
        const inputStatus = document.getElementById('inputStatusPenempatan');
        const btnSubmit = document.getElementById('btnSubmitStatus');
        if (!modal || !box || !form) return;

        form.action = actionUrl;
        inputStatus.value = status;
        siswa.innerText = siswaName;
        dudi.innerText = dudiName;

        if (status === 'FIX') {
            title.innerText = 'Konfirmasi DUDI Menerima';
            icon.className = 'w-14 h-14 mx-auto rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100/80 flex items-center justify-center text-2xl shadow-xs';
            icon.innerHTML = '<i class="fa-solid fa-check"></i>';
            statusBaru.innerText = 'DITERIMA (FIX)';
            statusBaru.className = 'font-bold text-emerald-600';
            btnSubmit.className = 'flex-1 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white text-xs md:text-sm font-bold shadow-sm transition flex items-center justify-center gap-1.5 cursor-pointer';
            btnSubmit.innerHTML = '<span>Ya, Setujui (FIX)</span>';
        } else {
            title.innerText = 'Konfirmasi DUDI Menolak';
            icon.className = 'w-14 h-14 mx-auto rounded-2xl bg-red-50 text-red-600 border border-red-100/80 flex items-center justify-center text-2xl shadow-xs';
            icon.innerHTML = '<i class="fa-solid fa-xmark"></i>';
            statusBaru.innerText = 'DITOLAK';
            statusBaru.className = 'font-bold text-red-600';
            btnSubmit.className = 'flex-1 px-4 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 active:scale-95 text-white text-xs md:text-sm font-bold shadow-sm transition flex items-center justify-center gap-1.5 cursor-pointer';
            btnSubmit.innerHTML = '<span>Ya, Tolak</span>';
        }

        window.openModal('modalConfirmStatus');
    }

    function closeStatusModal() {
        window.closeModal('modalConfirmStatus');
    }
</script>
@endpush
