@extends('Admin.layout.app')

@section('title', 'Data PKL - BKK')
@section('page_title', $pageTitle ?? 'Data PKL')

@section('content')

    @if (session('success'))
        <div
            class="rounded-xl border border-green-200 bg-green-50 text-green-800 px-4 py-3 text-sm flex items-start gap-2">
            <i class="fa-solid fa-circle-check mt-0.5"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div
            class="rounded-xl border border-red-200 bg-red-50 text-red-800 px-4 py-3 text-sm flex items-start gap-2">
            <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h2 class="font-bold text-gray-900 text-base">Data Penempatan PKL</h2>
            <p class="text-[11px] text-gray-500 mt-0.5">
                Verifikasi balasan DUDI: <strong>FIX</strong> (diterima) atau <strong>Ditolak</strong>.
            </p>
        </div>
        <a href="{{ route('pkl.create') }}"
            class="text-xs font-semibold bg-brand-600 hover:bg-brand-700 text-white px-3.5 py-2 rounded-lg transition">
            <i class="fa-solid fa-plus mr-1.5"></i> Buat Pengajuan PKL
        </a>
    </div>

    {{-- FILTER --}}
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

        @if ($search || $filterStatus || $filterDudi)
            <a href="{{ route('pkl.index') }}"
                class="text-sm font-semibold text-gray-500 hover:text-gray-700 px-3 py-2 rounded-lg transition">Reset</a>
        @endif
    </form>

    {{-- TABEL --}}
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
                                    @if ($p->status_penempatan === \App\Models\PenempatanPkl::STATUS_PENGAJUAN)
                                        {{-- Tombol FIX --}}
                                        <form method="POST" action="{{ route('pkl.penempatan.status', $p) }}"
                                            onsubmit="return confirm('Konfirmasi DUDI {{ $p->dudi?->nama_dudi }} menerima {{ $p->siswa?->nama }}? Status menjadi FIX.')">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status_penempatan" value="FIX">
                                            <button type="submit" title="DUDI Menerima (FIX)"
                                                class="w-8 h-8 inline-flex items-center justify-center text-xs bg-emerald-50 hover:bg-emerald-100 text-emerald-600 rounded-lg transition">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        </form>

                                        {{-- Tombol Ditolak --}}
                                        <form method="POST" action="{{ route('pkl.penempatan.status', $p) }}"
                                            onsubmit="return confirm('Konfirmasi DUDI {{ $p->dudi?->nama_dudi }} menolak {{ $p->siswa?->nama }}?')">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status_penempatan" value="ditolak">
                                            <button type="submit" title="DUDI Menolak"
                                                class="w-8 h-8 inline-flex items-center justify-center text-xs bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-[10px] text-gray-400">Selesai</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center">
                                <i class="fa-solid fa-inbox text-gray-300 text-3xl mb-3 block"></i>
                                <p class="text-sm text-gray-500">Belum ada data penempatan PKL.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
