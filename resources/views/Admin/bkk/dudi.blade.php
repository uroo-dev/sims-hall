@extends('Admin.layout.app')

@section('title', 'Data DUDI - BKK')
@section('page_title', $pageTitle ?? 'Data DUDI')

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
            <h2 class="font-bold text-gray-900 text-base">Master DUDI</h2>
            <p class="text-[11px] text-gray-500 mt-0.5">
                DUDI berstatus <strong>tampil di Landing Page</strong> hanya yang sudah di-ACC BKK.
            </p>
        </div>
        <a href="{{ route('pkl.create') }}"
            class="text-xs font-semibold bg-brand-600 hover:bg-brand-700 text-white px-3.5 py-2 rounded-lg transition">
            <i class="fa-solid fa-plus mr-1.5"></i> Pengajuan DUDI Baru
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3 md:gap-4">
        @forelse ($dudis as $d)
            <div class="bg-white rounded-2xl border border-gray-100 card-shadow p-4 flex flex-col">

                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <h3 class="font-bold text-gray-900 text-sm leading-snug">{{ $d->nama_dudi }}</h3>
                        <p class="text-[11px] text-gray-500 mt-1">
                            <i class="fa-solid fa-location-dot mr-1"></i>{{ $d->kota }}
                        </p>
                    </div>
                    @if ($d->is_mitra_resmi)
                        <span
                            class="shrink-0 text-[10px] font-bold px-2 py-1 rounded-md bg-violet-50 text-violet-700">
                            MITRA RESMI
                        </span>
                    @else
                        <span
                            class="shrink-0 text-[10px] font-bold px-2 py-1 rounded-md bg-gray-100 text-gray-600">
                            USULAN BARU
                        </span>
                    @endif
                </div>

                <p class="text-[11px] text-gray-600 mt-2.5 leading-relaxed">{{ $d->bidang_usaha }}</p>
                <p class="text-[11px] text-gray-500 mt-1.5 leading-relaxed">{{ $d->alamat }}</p>

                @if ($d->kontak_person || $d->no_hp)
                    <p class="text-[11px] text-gray-500 mt-1.5">
                        <i class="fa-solid fa-user mr-1"></i>{{ $d->kontak_person ?? '-' }}
                        @if ($d->no_hp)
                            &middot; {{ $d->no_hp }}
                        @endif
                    </p>
                @endif

                {{-- Progress kuota --}}
                <div class="mt-3 pt-3 border-t border-gray-100">
                    <div class="flex items-center justify-between text-[11px] mb-1.5">
                        <span class="text-gray-500">Kapasitas PKL</span>
                        <span class="font-bold text-gray-700">
                            {{ $d->penempatan_fix_count }} / {{ $d->kuota_maksimal ?: '-' }}
                        </span>
                    </div>
                    <div class="h-2 rounded-full bg-gray-100 overflow-hidden">
                        @php
                            $persen = $d->kuota_maksimal > 0
                                ? min(100, round($d->penempatan_fix_count / $d->kuota_maksimal * 100))
                                : 0;
                        @endphp
                        <div class="h-full rounded-full bg-brand-500" style="width: {{ $persen }}%"></div>
                    </div>
                </div>

                {{-- Status landing --}}
                <div class="mt-3 flex items-center gap-2 text-[11px]">
                    <span @class([
                        'inline-flex items-center gap-1.5 px-2 py-1 rounded-md font-semibold',
                        'bg-emerald-50 text-emerald-700' => $d->tampil_di_landing,
                        'bg-gray-100 text-gray-500' => ! $d->tampil_di_landing,
                    ])>
                        <i @class([
                            'fa-solid fa-eye',
                            'fa-solid fa-eye-slash' => ! $d->tampil_di_landing,
                        ])></i>
                        {{ $d->tampil_di_landing ? 'Tayang di Landing' : 'Belum di-ACC' }}
                    </span>
                    <span class="text-gray-400">{{ $d->lowongans_count }} lowongan</span>
                </div>

                {{-- Aksi ACC --}}
                <form method="POST" action="{{ route('pkl.dudi.acc-landing', $d) }}" class="mt-3 pt-3 border-t border-gray-100">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                        class="w-full text-xs font-semibold rounded-lg py-2 transition
                            {{ $d->tampil_di_landing
                                ? 'bg-gray-100 hover:bg-gray-200 text-gray-700'
                                : 'bg-brand-600 hover:bg-brand-700 text-white' }}">
                        <i @class([
                            'fa-solid fa-eye-slash mr-1.5',
                            'fa-solid fa-eye mr-1.5' => ! $d->tampil_di_landing,
                        ])></i>
                        {{ $d->tampil_di_landing ? 'Batalkan Tayang di Landing' : 'ACC Tayang di Landing' }}
                    </button>
                </form>

            </div>
        @empty
            <div class="md:col-span-2 xl:col-span-3 bg-white rounded-2xl border border-gray-100 card-shadow p-10 text-center">
                <i class="fa-solid fa-building text-gray-300 text-3xl mb-3 block"></i>
                <p class="text-sm text-gray-500">Belum ada data DUDI. Buat melalui menu Pengajuan PKL.</p>
            </div>
        @endforelse
    </div>

@endsection
