@extends('Admin.layout.app')

@section('title', 'Career Center - Lowongan Kerja')
@section('page_title', $pageTitle ?? 'Lowongan Kerja')

@section('content')

    @if (session('success'))
        <div
            class="rounded-xl border border-green-200 bg-green-50 text-green-800 px-4 py-3 text-sm flex items-start gap-2">
            <i class="fa-solid fa-circle-check mt-0.5"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h2 class="font-bold text-gray-900 text-base">Career Center - Lowongan Kerja</h2>
            <p class="text-[11px] text-gray-500 mt-0.5">
                Tidak ada proses lamaran di dalam web. Tombol daftar membuka link eksternal.
            </p>
        </div>
        <a href="{{ route('pkl.lowongan.create') }}"
            class="text-xs font-semibold bg-brand-600 hover:bg-brand-700 text-white px-3.5 py-2 rounded-lg transition">
            <i class="fa-solid fa-plus mr-1.5"></i> Tambah Lowongan
        </a>
    </div>

    {{-- FILTER --}}
    <form method="GET" action="{{ route('pkl.lowongan.index') }}"
        class="bg-white rounded-2xl border border-gray-100 card-shadow p-4 flex flex-wrap items-end gap-3">
        <div class="flex-1 min-w-[180px]">
            <label class="block text-[11px] font-semibold text-gray-600 mb-1.5">Cari</label>
            <input type="text" name="q" value="{{ $search }}" placeholder="Nama perusahaan atau posisi..."
                class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
        </div>

        <div class="w-full sm:w-40">
            <label class="block text-[11px] font-semibold text-gray-600 mb-1.5">Tipe</label>
            <select name="tipe"
                class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                <option value="">Semua</option>
                <option value="Pekerjaan" @selected($filterTipe === 'Pekerjaan')>Pekerjaan</option>
                <option value="Magang" @selected($filterTipe === 'Magang')>Magang</option>
            </select>
        </div>

        <div class="w-full sm:w-40">
            <label class="block text-[11px] font-semibold text-gray-600 mb-1.5">Status</label>
            <select name="status"
                class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                <option value="">Semua</option>
                <option value="aktif" @selected($filterStatus === 'aktif')>Aktif (Belum Lewat Deadline)</option>                <option value="expired" @selected($filterStatus === 'expired')>Lewat Deadline</option>
                <option value="nonaktif" @selected($filterStatus === 'nonaktif')>Nonaktif</option>
            </select>
        </div>

        <button type="submit"
            class="text-sm font-semibold bg-brand-600 hover:bg-brand-700 text-white px-4 py-2 rounded-lg transition">
            <i class="fa-solid fa-filter mr-1.5"></i> Terapkan
        </button>

        @if ($search || $filterTipe || $filterStatus)
            <a href="{{ route('pkl.lowongan.index') }}"
                class="text-sm font-semibold text-gray-500 hover:text-gray-700 px-3 py-2 rounded-lg transition">Reset</a>
        @endif
    </form>

    {{-- DAFTAR LOWONGAN --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3 md:gap-4">
        @forelse ($lowongans as $l)
            @php
                $badge = $l->sudah_lewat
                    ? ['bg-gray-100 text-gray-600', 'Lewat Deadline']
                    : ($l->is_active
                        ? ['bg-emerald-50 text-emerald-700', 'Aktif']
                        : ['bg-red-50 text-red-700', 'Nonaktif']);
            @endphp

            <div class="bg-white rounded-2xl border border-gray-100 card-shadow p-4 flex flex-col">

                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <h3 class="font-bold text-gray-900 text-sm leading-snug">{{ $l->posisi }}</h3>
                        <p class="text-[11px] text-gray-600 mt-1 font-medium">{{ $l->nama_perusahaan }}</p>
                        @if ($l->dudi)
                            <p class="text-[11px] text-gray-500 mt-0.5">
                                <i class="fa-solid fa-location-dot mr-1"></i>{{ $l->dudi->kota }}
                            </p>
                        @endif
                    </div>
                    <span class="shrink-0 text-[10px] font-bold px-2 py-1 rounded-md {{ $badge[0] }}">
                        {{ $badge[1] }}
                    </span>
                </div>

                <div class="flex flex-wrap gap-1.5 mt-2.5">
                    <span class="text-[10px] font-semibold px-2 py-1 rounded-md bg-blue-50 text-brand-700">
                        <i class="fa-solid fa-briefcase mr-1"></i>{{ $l->tipe }}
                    </span>
                    <span class="text-[10px] font-semibold px-2 py-1 rounded-md bg-gray-100 text-gray-600">
                        {{ $l->jurusan_sesuai }}
                    </span>
                </div>

                <p class="text-[11px] text-gray-600 mt-2.5 leading-relaxed line-clamp-3">{{ $l->deskripsi }}</p>

                <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between text-[11px]">
                    <span class="text-gray-500">
                        <i class="fa-regular fa-clock mr-1"></i>
                        Deadline {{ $l->deadline->format('d M Y') }}
                    </span>
                    @if (! $l->sudah_lewat && $l->is_active)
                        <span class="font-bold text-amber-600">{{ $l->sisa_hari }} hari lagi</span>
                    @endif
                </div>

                <div class="mt-3 flex items-center gap-2">
                    <a href="{{ $l->link_daftar }}" target="_blank" rel="noopener noreferrer"
                        class="flex-1 text-center text-xs font-semibold bg-brand-600 hover:bg-brand-700 text-white px-3 py-2 rounded-lg transition">
                        <i class="fa-solid fa-arrow-up-right-from-square mr-1.5"></i> Daftar
                    </a>
                    <a href="{{ route('pkl.lowongan.edit', $l) }}"
                        class="w-9 h-9 inline-flex items-center justify-center text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition"
                        title="Edit">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                    <form method="POST" action="{{ route('pkl.lowongan.toggle', $l) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="w-9 h-9 inline-flex items-center justify-center text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition"
                            title="{{ $l->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                            <i @class(['fa-solid fa-toggle-on text-emerald-600' => $l->is_active, 'fa-solid fa-toggle-off' => ! $l->is_active])></i>
                        </button>
                    </form>
                    <button type="button"
                        onclick="openDeleteLowonganModal(@js($l->posisi), '{{ route('pkl.lowongan.destroy', $l) }}')"
                        class="w-9 h-9 inline-flex items-center justify-center text-xs bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition"
                        title="Hapus">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>

            </div>
        @empty
            <div class="md:col-span-2 xl:col-span-3 bg-white rounded-2xl border border-gray-100 card-shadow p-10 text-center">
                <i class="fa-solid fa-briefcase text-gray-300 text-3xl mb-3 block"></i>
                <p class="text-sm text-gray-500">Belum ada lowongan kerja yang sesuai filter.</p>
            </div>
        @endforelse
    </div>

@endsection

@push('modals')
<!-- ==================== MODAL HAPUS LOWONGAN ==================== -->
<div id="modalDeleteLowongan" class="fixed inset-0 !m-0 z-[100] hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl border border-slate-100 overflow-hidden transform transition-all scale-95 duration-200" id="modalDeleteLowonganBox">
        <div class="p-6 text-center space-y-4">
            <div class="w-14 h-14 mx-auto rounded-2xl bg-red-50 text-red-600 border border-red-100/80 flex items-center justify-center text-2xl shadow-xs">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <h3 class="font-extrabold text-slate-900 text-lg tracking-tight">Konfirmasi Hapus Lowongan</h3>
                <p class="text-xs text-slate-500 mt-1">
                    Apakah Anda yakin ingin menghapus lowongan kerja:
                </p>
                <div id="delete_lowongan_title" class="font-bold text-slate-800 text-sm mt-2 bg-slate-50 py-2.5 px-3 rounded-xl border border-slate-200">
                    -
                </div>
                <p class="text-[11px] text-red-500 mt-2 font-medium">
                    Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>

            <form id="formDeleteLowongan" method="POST" class="pt-2 flex items-center justify-center gap-3">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeDeleteLowonganModal()"
                    class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs md:text-sm font-semibold transition cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 active:scale-95 text-white text-xs md:text-sm font-bold shadow-sm transition flex items-center justify-center gap-1.5 cursor-pointer">
                    <i class="fa-regular fa-trash-can text-xs"></i>
                    <span>Ya, Hapus</span>
                </button>
            </form>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    function openDeleteLowonganModal(title, deleteUrl) {
        const modal = document.getElementById('modalDeleteLowongan');
        const box = document.getElementById('modalDeleteLowonganBox');
        const form = document.getElementById('formDeleteLowongan');
        if (!modal || !box || !form) return;

        form.action = deleteUrl;
        document.getElementById('delete_lowongan_title').innerText = title;

        document.body.classList.add('overflow-hidden');
        modal.classList.remove('hidden');
        setTimeout(() => {
            box.classList.remove('scale-95');
            box.classList.add('scale-100');
        }, 10);
    }

    function closeDeleteLowonganModal() {
        const modal = document.getElementById('modalDeleteLowongan');
        const box = document.getElementById('modalDeleteLowonganBox');
        if (!modal || !box) return;

        box.classList.remove('scale-100');
        box.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }, 150);
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteLowonganModal();
        }
    });

    const modalDeleteLowonganEl = document.getElementById('modalDeleteLowongan');
    if (modalDeleteLowonganEl) {
        modalDeleteLowonganEl.addEventListener('click', function(e) {
            if (e.target === this) closeDeleteLowonganModal();
        });
    }
</script>
@endpush
