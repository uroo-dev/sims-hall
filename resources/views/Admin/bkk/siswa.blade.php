@extends('Admin.layout.app')

@section('title', 'Data Siswa PKL - BKK')
@section('page_title', $pageTitle ?? 'Data Siswa PKL')

@section('content')

    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-1.5 text-xs text-gray-500 mb-1">
                <a href="{{ route('pkl.siswa.index') }}" class="hover:text-brand-600 font-medium transition">Data Siswa PKL</a>
                @if ($filterKelas)
                    <i class="fa-solid fa-chevron-right text-[10px] text-gray-400"></i>
                    <span class="font-bold text-brand-600">Kelas {{ $filterKelas }}</span>
                @elseif ($search)
                    <i class="fa-solid fa-chevron-right text-[10px] text-gray-400"></i>
                    <span class="font-bold text-brand-600">Pencarian</span>
                @endif
            </nav>

            <h2 class="font-extrabold text-gray-900 text-lg tracking-tight">
                @if ($filterKelas)
                    Data Siswa Kelas {{ $filterKelas }}
                @else
                    Data Siswa PKL per Kelas
                @endif
            </h2>
            <p class="text-xs text-gray-500 mt-0.5">
                @if ($filterKelas)
                    Daftar siswa dan status penempatan PKL untuk kelas {{ $filterKelas }}.
                @else
                    Pilih kelas untuk melihat daftar siswa dan monitoring status PKL.
                @endif
            </p>
        </div>

        <div class="flex items-center gap-2">
            @if ($filterKelas || $search)
                <a href="{{ route('pkl.siswa.index') }}"
                    class="text-xs font-semibold bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 px-3.5 py-2 rounded-xl transition shadow-2xs">
                    <i class="fa-solid fa-arrow-left mr-1.5"></i> Pilih Kelas Lain
                </a>
            @endif

            <a href="{{ route('pkl.create') }}"
                class="text-xs font-bold bg-brand-600 hover:bg-brand-700 text-white px-4 py-2 rounded-xl transition shadow-xs flex items-center gap-1.5">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Buat Pengajuan PKL</span>
            </a>
        </div>
    </div>

    {{-- ==================== PILIHAN KELAS DULU JIKA BELUM MEMILIH KELAS ==================== --}}
    @if (!$filterKelas && !$search)
        {{-- Search bar cepat --}}
        <form method="GET" action="{{ route('pkl.siswa.index') }}"
            class="bg-white rounded-2xl border border-gray-100 card-shadow p-4 flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-[11px] font-semibold text-gray-600 mb-1.5">Cari Siswa Langsung</label>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-xs text-gray-400"></i>
                    <input type="text" name="q" value="{{ $search }}" placeholder="Nama siswa atau NIS..."
                        class="w-full text-sm pl-9 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                </div>
            </div>
            <button type="submit"
                class="text-sm font-semibold bg-brand-600 hover:bg-brand-700 text-white px-4 py-2 rounded-lg transition">
                <i class="fa-solid fa-filter mr-1.5"></i> Cari Siswa
            </button>
        </form>

        {{-- Grid Cards Kelas --}}
        <div>
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-extrabold text-gray-900 text-sm">Pilih Kelas</h3>
                <span class="text-xs text-gray-500">{{ count($kelasList) }} Rombongan Belajar</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse ($kelasList as $k)
                    <div class="bg-white rounded-2xl border border-gray-100 card-shadow p-5 flex flex-col justify-between hover:border-brand-200 transition group">
                        <div>
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-xl bg-blue-50 text-brand-600 border border-blue-100/70 flex items-center justify-center text-lg shrink-0 group-hover:scale-105 transition-transform">
                                        <i class="fa-solid fa-chalkboard-user"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-extrabold text-gray-900 text-base group-hover:text-brand-600 transition">
                                            {{ $k->kelas }}
                                        </h3>
                                        <p class="text-xs text-gray-500 mt-0.5 truncate max-w-[170px]" title="{{ $k->jurusan }}">{{ $k->jurusan }}</p>
                                    </div>
                                </div>
                                <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 shrink-0">
                                    {{ $k->total }} Siswa
                                </span>
                            </div>

                            {{-- Progres PKL --}}
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
                                    <span><strong class="text-amber-600">{{ $k->menunggu }}</strong> Menunggu</span>
                                    <span><strong class="text-slate-700">{{ $k->belum }}</strong> Belum</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 pt-4 border-t border-gray-100">
                            <a href="{{ route('pkl.siswa.index', ['kelas' => $k->kelas]) }}"
                                class="w-full flex items-center justify-between text-xs font-bold text-white bg-brand-600 hover:bg-brand-700 px-4 py-2.5 rounded-xl shadow-xs transition cursor-pointer">
                                <span>Buka Siswa Kelas {{ $k->kelas }}</span>
                                <i class="fa-solid fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 bg-white rounded-2xl border border-gray-100 card-shadow p-12 text-center">
                        <i class="fa-solid fa-school text-4xl text-gray-300 mb-3 block"></i>
                        <p class="text-gray-500 text-sm">Belum ada data kelas siswa.</p>
                    </div>
                @endforelse
            </div>
        </div>

    {{-- ==================== TABEL SISWA (SETELAH KELAS DIPILIH ATAU SEARCH) ==================== --}}
    @else
        {{-- Switcher Pills Kelas --}}
        @if ($kelasList->isNotEmpty())
            <div class="bg-white rounded-2xl border border-gray-100 card-shadow p-3 flex items-center gap-2 overflow-x-auto">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider pl-2 shrink-0">Kelas:</span>
                <a href="{{ route('pkl.siswa.index') }}"
                    class="text-xs font-semibold px-3 py-1.5 rounded-xl transition shrink-0 {{ !$filterKelas ? 'bg-brand-600 text-white shadow-xs' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Semua Kelas
                </a>
                @foreach ($kelasList as $k)
                    <a href="{{ route('pkl.siswa.index', ['kelas' => $k->kelas]) }}"
                        class="text-xs font-semibold px-3 py-1.5 rounded-xl transition shrink-0 {{ $filterKelas === $k->kelas ? 'bg-brand-600 text-white shadow-xs' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        {{ $k->kelas }}
                        <span class="ml-1 text-[10px] opacity-75">({{ $k->total }})</span>
                    </a>
                @endforeach
            </div>
        @endif

        {{-- Summary Banner jika Kelas Terpilih --}}
        @if ($selectedKelasInfo)
            <div class="bg-white rounded-2xl border border-gray-100 card-shadow p-5">
                <div class="flex items-center justify-between flex-wrap gap-4 mb-3">
                    <div>
                        <span class="text-[10px] font-bold text-brand-600 uppercase tracking-wider block">Kelas Aktif</span>
                        <h3 class="text-lg font-extrabold text-gray-900">Kelas {{ $selectedKelasInfo->kelas }}</h3>
                        <p class="text-xs text-gray-500">{{ $selectedKelasInfo->jurusan }}</p>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <div class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-center">
                            <span class="text-[10px] text-gray-400 block">Total</span>
                            <span class="font-extrabold text-xs text-gray-800">{{ $selectedKelasInfo->total }} Siswa</span>
                        </div>
                        <div class="px-3 py-1.5 bg-emerald-50 border border-emerald-200 rounded-xl text-center">
                            <span class="text-[10px] text-emerald-700 block">Sudah FIX</span>
                            <span class="font-extrabold text-xs text-emerald-700">{{ $selectedKelasInfo->fix }}</span>
                        </div>
                        <div class="px-3 py-1.5 bg-amber-50 border border-amber-200 rounded-xl text-center">
                            <span class="text-[10px] text-amber-700 block">Menunggu</span>
                            <span class="font-extrabold text-xs text-amber-700">{{ $selectedKelasInfo->menunggu }}</span>
                        </div>
                        <div class="px-3 py-1.5 bg-red-50 border border-red-200 rounded-xl text-center">
                            <span class="text-[10px] text-red-700 block">Belum</span>
                            <span class="font-extrabold text-xs text-red-700">{{ $selectedKelasInfo->belum }}</span>
                        </div>
                    </div>
                </div>

                {{-- Progress Bar --}}
                <div class="space-y-1">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-semibold text-gray-700">Persentase Siswa Sudah Dapat Tempat PKL:</span>
                        <span class="font-extrabold text-emerald-600">{{ $selectedKelasInfo->persentase_fix }}%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-emerald-500 h-2.5 rounded-full transition-all duration-500"
                            style="width: {{ $selectedKelasInfo->persentase_fix }}%"></div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Filter & Search Form --}}
        <form method="GET" action="{{ route('pkl.siswa.index') }}"
            class="bg-white rounded-2xl border border-gray-100 card-shadow p-4 flex flex-wrap items-end gap-3">
            @if ($filterKelas)
                <input type="hidden" name="kelas" value="{{ $filterKelas }}">
            @endif

            <div class="flex-1 min-w-[180px]">
                <label class="block text-[11px] font-semibold text-gray-600 mb-1.5">Cari Siswa</label>
                <input type="text" name="q" value="{{ $search }}" placeholder="Nama atau NIS siswa..."
                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
            </div>

            <div class="w-full sm:w-56">
                <label class="block text-[11px] font-semibold text-gray-600 mb-1.5">Status PKL</label>
                <select name="status"
                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    <option value="">Semua Siswa</option>
                    <option value="fix" @selected($filterStatus === 'fix')>Sudah PKL (FIX)</option>
                    <option value="menunggu" @selected($filterStatus === 'menunggu')>Menunggu Balasan</option>
                    <option value="ditolak" @selected($filterStatus === 'ditolak')>Pernah Ditolak</option>
                    <option value="belum" @selected($filterStatus === 'belum')>Belum Ada Tempat PKL</option>
                </select>
            </div>

            <button type="submit"
                class="text-sm font-semibold bg-brand-600 hover:bg-brand-700 text-white px-4 py-2 rounded-lg transition">
                <i class="fa-solid fa-filter mr-1.5"></i> Terapkan
            </button>

            @if ($search || $filterStatus)
                <a href="{{ route('pkl.siswa.index', $filterKelas ? ['kelas' => $filterKelas] : []) }}"
                    class="text-sm font-semibold text-gray-500 hover:text-gray-700 px-3 py-2 rounded-lg transition">Reset</a>
            @endif
        </form>

        {{-- Tabel Siswa --}}
        <div class="bg-white rounded-2xl border border-gray-100 card-shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-[11px] uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="text-left font-semibold px-5 py-3">No</th>
                            <th class="text-left font-semibold px-4 py-3">Siswa</th>
                            <th class="text-left font-semibold px-3 py-3">Kelas / Jurusan</th>
                            <th class="text-left font-semibold px-3 py-3">No. HP</th>
                            <th class="text-left font-semibold px-3 py-3">Tempat PKL Terakhir</th>
                            <th class="text-center font-semibold px-5 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($siswas as $idx => $s)
                            @php
                                $terakhir = $s->penempatanPkls->first();
                                $style = match ($terakhir?->status_penempatan) {
                                    \App\Models\PenempatanPkl::STATUS_FIX => ['bg-emerald-50 text-emerald-700', 'Sudah PKL (FIX)'],
                                    \App\Models\PenempatanPkl::STATUS_DITOLAK => ['bg-red-50 text-red-700', 'Ditolak'],
                                    \App\Models\PenempatanPkl::STATUS_PENGAJUAN => ['bg-amber-50 text-amber-700', 'Menunggu Balasan'],
                                    default => ['bg-gray-100 text-gray-600', 'Belum Ada Tempat'],
                                };
                            @endphp
                            <tr class="hover:bg-gray-50/70 transition">
                                <td class="px-5 py-3 text-xs text-gray-400 font-medium">{{ $idx + 1 }}</td>
                                <td class="px-4 py-3">
                                    <div class="font-bold text-gray-900">{{ $s->nama }}</div>
                                    <div class="text-[11px] text-gray-500">NIS {{ $s->nis }}</div>
                                </td>
                                <td class="px-3 py-3 text-gray-700 text-xs">{{ $s->label_kelas }}</td>
                                <td class="px-3 py-3 text-gray-600 text-xs">{{ $s->no_hp ?? '-' }}</td>
                                <td class="px-3 py-3">
                                    <div class="text-gray-800 font-semibold text-xs">{{ $terakhir?->dudi?->nama_dudi ?? '-' }}</div>
                                    @if ($terakhir?->guru)
                                        <div class="text-[11px] text-gray-500">Pembimbing: {{ $terakhir->guru->nama }}</div>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <span class="text-[10px] font-bold px-2.5 py-1 rounded-md {{ $style[0] }}">{{ $style[1] }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center">
                                    <i class="fa-solid fa-inbox text-gray-300 text-3xl mb-3 block"></i>
                                    <p class="text-sm text-gray-500">Tidak ada siswa yang cocok dengan filter.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

@endsection
