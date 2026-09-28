@extends('Admin.layout.app')

@section('title', 'Data Siswa PKL - BKK')
@section('page_title', $pageTitle ?? 'Data Siswa PKL')

@section('content')

    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h2 class="font-bold text-gray-900 text-base">Data Siswa PKL</h2>
            <p class="text-[11px] text-gray-500 mt-0.5">Monitoring status PKL seluruh siswa.</p>
        </div>
        <a href="{{ route('pkl.create') }}"
            class="text-xs font-semibold bg-brand-600 hover:bg-brand-700 text-white px-3.5 py-2 rounded-lg transition">
            <i class="fa-solid fa-plus mr-1.5"></i> Buat Pengajuan PKL
        </a>
    </div>

    <form method="GET" action="{{ route('pkl.siswa.index') }}"
        class="bg-white rounded-2xl border border-gray-100 card-shadow p-4 flex flex-wrap items-end gap-3">
        <div class="flex-1 min-w-[180px]">
            <label class="block text-[11px] font-semibold text-gray-600 mb-1.5">Cari</label>
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
            <a href="{{ route('pkl.siswa.index') }}"
                class="text-sm font-semibold text-gray-500 hover:text-gray-700 px-3 py-2 rounded-lg transition">Reset</a>
        @endif
    </form>

    <div class="bg-white rounded-2xl border border-gray-100 card-shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-[11px] uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="text-left font-semibold px-5 py-3">Siswa</th>
                        <th class="text-left font-semibold px-3 py-3">Kelas / Jurusan</th>
                        <th class="text-left font-semibold px-3 py-3">No. HP</th>
                        <th class="text-left font-semibold px-3 py-3">Tempat PKL Terakhir</th>
                        <th class="text-center font-semibold px-5 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($siswas as $s)
                        @php
                            $terakhir = $s->penempatanPkls->first();
                            $style = match ($terakhir?->status_penempatan) {
                                \App\Models\PenempatanPkl::STATUS_FIX => ['bg-emerald-50 text-emerald-700', 'Sudah PKL'],
                                \App\Models\PenempatanPkl::STATUS_DITOLAK => ['bg-red-50 text-red-700', 'Ditolak'],
                                \App\Models\PenempatanPkl::STATUS_PENGAJUAN => ['bg-amber-50 text-amber-700', 'Menunggu'],
                                default => ['bg-gray-100 text-gray-600', 'Belum Ada Tempat'],
                            };
                        @endphp
                        <tr class="hover:bg-gray-50/70 transition">
                            <td class="px-5 py-3">
                                <div class="font-semibold text-gray-800">{{ $s->nama }}</div>
                                <div class="text-[11px] text-gray-500">NIS {{ $s->nis }}</div>
                            </td>
                            <td class="px-3 py-3 text-gray-700">{{ $s->label_kelas }}</td>
                            <td class="px-3 py-3 text-gray-600">{{ $s->no_hp ?? '-' }}</td>
                            <td class="px-3 py-3">
                                <div class="text-gray-800">{{ $terakhir?->dudi?->nama_dudi ?? '-' }}</div>
                                @if ($terakhir?->guru)
                                    <div class="text-[11px] text-gray-500">Pembimbing: {{ $terakhir->guru->nama }}</div>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-center">
                                <span class="text-[10px] font-bold px-2 py-1 rounded-md {{ $style[0] }}">{{ $style[1] }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center">
                                <i class="fa-solid fa-inbox text-gray-300 text-3xl mb-3 block"></i>
                                <p class="text-sm text-gray-500">Tidak ada siswa yang cocok dengan filter.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
