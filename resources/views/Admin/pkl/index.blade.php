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
                                        <button type="button" title="DUDI Menerima (FIX)"
                                            onclick="openStatusModal('{{ route('pkl.penempatan.status', $p) }}', @js($p->dudi?->nama_dudi ?? '-'), @js($p->siswa?->nama ?? '-'), 'FIX')"
                                            class="w-8 h-8 inline-flex items-center justify-center text-xs bg-emerald-50 hover:bg-emerald-100 text-emerald-600 rounded-lg transition cursor-pointer">
                                            <i class="fa-solid fa-check"></i>
                                        </button>

                                        {{-- Tombol Ditolak --}}
                                        <button type="button" title="DUDI Menolak"
                                            onclick="openStatusModal('{{ route('pkl.penempatan.status', $p) }}', @js($p->dudi?->nama_dudi ?? '-'), @js($p->siswa?->nama ?? '-'), 'ditolak')"
                                            class="w-8 h-8 inline-flex items-center justify-center text-xs bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition cursor-pointer">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
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

        document.body.classList.add('overflow-hidden');
        modal.classList.remove('hidden');
        setTimeout(() => {
            box.classList.remove('scale-95');
            box.classList.add('scale-100');
        }, 10);
    }

    function closeStatusModal() {
        const modal = document.getElementById('modalConfirmStatus');
        const box = document.getElementById('modalConfirmStatusBox');
        if (!modal || !box) return;

        box.classList.remove('scale-100');
        box.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }, 150);
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeStatusModal();
    });

    const modalConfirmStatusEl = document.getElementById('modalConfirmStatus');
    if (modalConfirmStatusEl) {
        modalConfirmStatusEl.addEventListener('click', function(e) {
            if (e.target === this) closeStatusModal();
        });
    }
</script>
@endpush
