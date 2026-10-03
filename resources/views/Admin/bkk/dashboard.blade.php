@extends('Admin.layout.app')

@section('title', 'Dashboard BKK & PKL - SMK Negeri 2 Karanganyar')
@section('page_title', $pageTitle ?? 'Dashboard BKK & PKL')

@section('content')


    {{-- STATISTIC CARDS --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
        @php
            $cards = [
                [
                    'label' => 'Total Siswa',
                    'value' => $summary->total_siswa,
                    'icon' => 'fa-users',
                    'color' => 'bg-blue-50 text-brand-600',
                ],
                [
                    'label' => 'Sudah PKL (FIX)',
                    'value' => $summary->total_fix,
                    'icon' => 'fa-circle-check',
                    'color' => 'bg-emerald-50 text-emerald-600',
                ],
                [
                    'label' => 'Menunggu Balasan',
                    'value' => $summary->total_menunggu,
                    'icon' => 'fa-hourglass-half',
                    'color' => 'bg-amber-50 text-amber-600',
                ],
                [
                    'label' => 'DUDI Mitra Resmi',
                    'value' => $summary->total_mitra_resmi,
                    'icon' => 'fa-building',
                    'color' => 'bg-violet-50 text-violet-600',
                ],
            ];
        @endphp

        @foreach ($cards as $card)
            <div class="bg-white rounded-2xl border border-gray-100 card-shadow p-4 flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl {{ $card['color'] }} flex items-center justify-center shrink-0">
                    <i class="fa-solid {{ $card['icon'] }}"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-2xl font-bold text-gray-900 leading-none">{{ $card['value'] }}</div>
                    <div class="text-[11px] text-gray-500 mt-1.5 font-medium truncate">{{ $card['label'] }}</div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- FILTER --}}
    <form method="GET" action="{{ route('pkl.dashboard') }}"
        class="bg-white rounded-2xl border border-gray-100 card-shadow p-4 flex flex-wrap items-end gap-3">
        <div class="flex-1 min-w-[160px]">
            <label class="block text-[11px] font-semibold text-gray-600 mb-1.5">Jurusan</label>
            <select name="jurusan"
                class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                <option value="">Semua Jurusan</option>
                @foreach ($listJurusan as $j)
                    <option value="{{ $j }}" @selected($filterJurusan === $j)>{{ $j }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex-1 min-w-[140px]">
            <label class="block text-[11px] font-semibold text-gray-600 mb-1.5">Kelas</label>
            <select name="kelas"
                class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                <option value="">Semua Kelas</option>
                @foreach ($listKelas as $k)
                    <option value="{{ $k }}" @selected($filterKelas === $k)>{{ $k }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex-1 min-w-[160px]">
            <label class="block text-[11px] font-semibold text-gray-600 mb-1.5">Status PKL</label>
            <select name="status"
                class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                <option value="">Semua Status</option>
                <option value="fix" @selected($filterStatus === 'fix')>Sudah PKL (FIX)</option>
                <option value="menunggu" @selected($filterStatus === 'menunggu')>Menunggu Balasan</option>
                <option value="ditolak" @selected($filterStatus === 'ditolak')>Ditolak DUDI</option>
                <option value="belum" @selected($filterStatus === 'belum')>Belum Ada Tempat PKL</option>
            </select>
        </div>

        <button type="submit"
            class="text-sm font-semibold bg-brand-600 hover:bg-brand-700 text-white px-4 py-2 rounded-lg transition">
            <i class="fa-solid fa-filter mr-1.5"></i> Terapkan
        </button>

        @if ($filterJurusan || $filterKelas || $filterStatus)
            <a href="{{ route('pkl.dashboard') }}"
                class="text-sm font-semibold text-gray-500 hover:text-gray-700 px-3 py-2 rounded-lg transition">
                Reset
            </a>
        @endif
    </form>

    {{-- REKAP PER KELAS / JURUSAN --}}
    <div class="bg-white rounded-2xl border border-gray-100 card-shadow overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between flex-wrap gap-2">
            <div>
                <h2 class="font-bold text-gray-900 text-sm">Rekap Status PKL per Kelas &amp; Jurusan</h2>
                <p class="text-[11px] text-gray-500 mt-0.5">
                    {{ $rekap->count() }} baris rekap dari {{ $summary->total_siswa }} siswa
                </p>
            </div>
            <a href="{{ route('pkl.create') }}"
                class="text-xs font-semibold bg-brand-600 hover:bg-brand-700 text-white px-3.5 py-2 rounded-lg transition">
                <i class="fa-solid fa-plus mr-1.5"></i> Buat Pengajuan PKL
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-[11px] uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="text-left font-semibold px-5 py-3">Kelas / Jurusan</th>
                        <th class="text-center font-semibold px-3 py-3">Total</th>
                        <th class="text-center font-semibold px-3 py-3">FIX</th>
                        <th class="text-center font-semibold px-3 py-3">Menunggu</th>
                        <th class="text-center font-semibold px-3 py-3">Ditolak</th>
                        <th class="text-center font-semibold px-3 py-3">Belum Pkl</th>
                        <th class="text-left font-semibold px-5 py-3 min-w-[160px]">Capaian</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($rekap as $row)
                        <tr class="hover:bg-gray-50/70 transition">
                            <td class="px-5 py-3">
                                <div class="font-semibold text-gray-800">{{ $row->kelas }}</div>
                                <div class="text-[11px] text-gray-500">{{ $row->jurusan }}</div>
                            </td>
                            <td class="text-center font-semibold text-gray-700">{{ $row->total }}</td>
                            <td class="text-center">
                                <span
                                    class="inline-flex items-center justify-center min-w-[28px] px-2 py-0.5 rounded-md text-xs font-bold bg-emerald-50 text-emerald-700">{{ $row->fix }}</span>
                            </td>
                            <td class="text-center">
                                <span
                                    class="inline-flex items-center justify-center min-w-[28px] px-2 py-0.5 rounded-md text-xs font-bold bg-amber-50 text-amber-700">{{ $row->menunggu }}</span>
                            </td>
                            <td class="text-center">
                                <span
                                    class="inline-flex items-center justify-center min-w-[28px] px-2 py-0.5 rounded-md text-xs font-bold bg-red-50 text-red-700">{{ $row->ditolak }}</span>
                            </td>
                            <td class="text-center">
                                <span
                                    class="inline-flex items-center justify-center min-w-[28px] px-2 py-0.5 rounded-md text-xs font-bold bg-gray-100 text-gray-600">{{ $row->belum_pkl }}</span>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 h-2 rounded-full bg-gray-100 overflow-hidden">
                                        <div class="h-full rounded-full bg-emerald-500"
                                            style="width: {{ $row->persentase_fix }}%"></div>
                                    </div>
                                    <span class="text-[11px] font-bold text-gray-600 w-9 text-right">
                                        {{ $row->persentase_fix }}%
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center">
                                <i class="fa-solid fa-inbox text-gray-300 text-3xl mb-3 block"></i>
                                <p class="text-sm text-gray-500">Tidak ada data siswa yang cocok dengan filter.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
