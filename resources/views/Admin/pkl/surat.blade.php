@extends('Admin.layout.app')

@section('title', 'Surat ' . $surat->nomor_surat)
@section('page_title', $pageTitle ?? 'Detail Surat')

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
            <h2 class="font-bold text-gray-900 text-base">Surat Pengajuan PKL</h2>
            <p class="text-[11px] text-gray-500 mt-0.5">{{ $surat->nomor_surat }}</p>
        </div>
        <a href="{{ route('pkl.index') }}"
            class="text-xs font-semibold text-gray-500 hover:text-gray-700 px-3 py-2 rounded-lg transition">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali
        </a>
    </div>

    {{-- INFO SURAT --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 md:gap-4">
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 card-shadow p-5">
            <h3 class="font-bold text-gray-900 text-sm mb-4">Detail Surat</h3>

            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
                <div>
                    <dt class="text-[11px] text-gray-500 font-medium">Nomor Surat</dt>
                    <dd class="font-semibold text-gray-800">{{ $surat->nomor_surat }}</dd>
                </div>
                <div>
                    <dt class="text-[11px] text-gray-500 font-medium">Tanggal Surat</dt>
                    <dd class="font-semibold text-gray-800">{{ $surat->tanggal_surat->format('d M Y') }}</dd>
                </div>
                <div>
                    <dt class="text-[11px] text-gray-500 font-medium">DUDI Tujuan</dt>
                    <dd class="font-semibold text-gray-800">{{ $surat->dudi->nama_dudi }}</dd>
                    <dd class="text-[11px] text-gray-500">{{ $surat->dudi->alamat }}, {{ $surat->dudi->kota }}</dd>
                </div>
                <div>
                    <dt class="text-[11px] text-gray-500 font-medium">Bidang Usaha</dt>
                    <dd class="font-semibold text-gray-800">{{ $surat->dudi->bidang_usaha }}</dd>
                </div>
                <div>
                    <dt class="text-[11px] text-gray-500 font-medium">Periode PKL</dt>
                    <dd class="font-semibold text-gray-800">
                        {{ $surat->tgl_mulai_pkl->format('d M Y') }} &mdash;
                        {{ $surat->tgl_selesai_pkl->format('d M Y') }}
                        <span class="text-[11px] font-normal text-gray-500">({{ $surat->durasi_pkl }} hari)</span>
                    </dd>
                </div>
                <div>
                    <dt class="text-[11px] text-gray-500 font-medium">File PDF</dt>
                    <dd class="font-semibold">
                        @if ($surat->file_pdf_path)
                            <span class="text-emerald-600">
                                <i class="fa-solid fa-file-pdf mr-1"></i>Sudah di-generate
                            </span>
                        @else
                            <span class="text-amber-600">
                                <i class="fa-solid fa-triangle-exclamation mr-1"></i>Belum ada
                            </span>
                        @endif
                    </dd>
                </div>
            </dl>

            {{-- AKSI PDF --}}
            <div class="mt-5 pt-4 border-t border-gray-100 flex flex-wrap items-center gap-2">
                @if ($surat->file_pdf_path)
                    <a href="{{ route('pkl.surat.download', $surat) }}"
                        class="text-sm font-semibold bg-brand-600 hover:bg-brand-700 text-white px-4 py-2.5 rounded-lg transition">
                        <i class="fa-solid fa-download mr-1.5"></i> Download PDF
                    </a>
                @endif

                <button type="button" onclick="openRegenerateModal()"
                    class="text-sm font-semibold bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-lg transition cursor-pointer">
                    <i class="fa-solid fa-rotate mr-1.5"></i>
                    {{ $surat->file_pdf_path ? 'Generate Ulang' : 'Generate PDF' }}
                </button>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 card-shadow p-5">
            <h3 class="font-bold text-gray-900 text-sm mb-3">Ringkasan</h3>
            @php
                $fix = $surat->penempatanPkls->where('status_penempatan', \App\Models\PenempatanPkl::STATUS_FIX)->count();
                $ditolak = $surat->penempatanPkls->where('status_penempatan', \App\Models\PenempatanPkl::STATUS_DITOLAK)->count();
                $menunggu = $surat->penempatanPkls->where('status_penempatan', \App\Models\PenempatanPkl::STATUS_PENGAJUAN)->count();
            @endphp
            <div class="space-y-2.5 text-sm">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Jumlah Siswa</span>
                    <span class="font-bold text-gray-800">{{ $surat->penempatanPkls->count() }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Diterima (FIX)</span>
                    <span class="font-bold text-emerald-600">{{ $fix }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Menunggu Balasan</span>
                    <span class="font-bold text-amber-600">{{ $menunggu }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Ditolak</span>
                    <span class="font-bold text-red-600">{{ $ditolak }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- DAFTAR SISWA --}}
    <div class="bg-white rounded-2xl border border-gray-100 card-shadow overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-900 text-sm">Siswa dalam Surat Ini</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-[11px] uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="text-left font-semibold px-5 py-3">No</th>
                        <th class="text-left font-semibold px-3 py-3">Nama Siswa</th>
                        <th class="text-left font-semibold px-3 py-3">Kelas / Jurusan</th>
                        <th class="text-left font-semibold px-3 py-3">Guru Pembimbing</th>
                        <th class="text-center font-semibold px-5 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($surat->penempatanPkls as $index => $p)
                        @php
                            $style = match ($p->status_penempatan) {
                                \App\Models\PenempatanPkl::STATUS_FIX => 'bg-emerald-50 text-emerald-700',
                                \App\Models\PenempatanPkl::STATUS_DITOLAK => 'bg-red-50 text-red-700',
                                default => 'bg-amber-50 text-amber-700',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50/70 transition">
                            <td class="px-5 py-3 text-gray-500">{{ $index + 1 }}</td>
                            <td class="px-3 py-3">
                                <div class="font-semibold text-gray-800">{{ $p->siswa?->nama ?? '-' }}</div>
                                <div class="text-[11px] text-gray-500">NIS {{ $p->siswa?->nis ?? '-' }}</div>
                            </td>
                            <td class="px-3 py-3 text-gray-700">{{ $p->siswa?->label_kelas ?? '-' }}</td>
                            <td class="px-3 py-3 text-gray-700">{{ $p->guru?->nama ?? '-' }}</td>
                            <td class="px-5 py-3 text-center">
                                <span class="text-[10px] font-bold px-2 py-1 rounded-md {{ $style }}">
                                    {{ $p->status_label }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

@push('modals')
<!-- ==================== MODAL REGENERATE PDF ==================== -->
<div id="modalRegeneratePdf" class="fixed inset-0 !m-0 z-[100] hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl border border-slate-100 overflow-hidden transform transition-all scale-95 duration-200" id="modalRegeneratePdfBox">
        <div class="p-6 text-center space-y-4">
            <div class="w-14 h-14 mx-auto rounded-2xl bg-blue-50 text-brand-600 border border-blue-100/80 flex items-center justify-center text-2xl shadow-xs">
                <i class="fa-solid fa-file-pdf"></i>
            </div>
            <div>
                <h3 class="font-extrabold text-slate-900 text-lg tracking-tight">Konfirmasi Generate PDF</h3>
                <p class="text-xs text-slate-500 mt-1">
                    {{ $surat->file_pdf_path ? 'Dokumen PDF yang sudah ada akan dibuat ulang dengan data penempatan terbaru.' : 'Sistem akan membuat berkas PDF surat pengajuan PKL ini.' }}
                </p>
                <div class="mt-3 p-3 bg-slate-50 border border-slate-200 rounded-xl text-left space-y-1 text-xs">
                    <div><span class="text-slate-400 font-medium">Nomor Surat:</span> <span class="font-bold text-slate-800">{{ $surat->nomor_surat }}</span></div>
                    <div><span class="text-slate-400 font-medium">Tujuan DUDI:</span> <span class="font-bold text-slate-800">{{ $surat->dudi?->nama_dudi ?? '-' }}</span></div>
                </div>
            </div>

            <form action="{{ route('pkl.surat.regenerate', $surat) }}" method="POST" class="pt-2 flex items-center justify-center gap-3">
                @csrf
                <button type="button" onclick="closeRegenerateModal()"
                    class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs md:text-sm font-semibold transition cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 active:scale-95 text-white text-xs md:text-sm font-bold shadow-sm transition flex items-center justify-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-rotate text-xs"></i>
                    <span>Proses Sekarang</span>
                </button>
            </form>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    function openRegenerateModal() {
        const modal = document.getElementById('modalRegeneratePdf');
        const box = document.getElementById('modalRegeneratePdfBox');
        if (!modal || !box) return;

        document.body.classList.add('overflow-hidden');
        modal.classList.remove('hidden');
        setTimeout(() => {
            box.classList.remove('scale-95');
            box.classList.add('scale-100');
        }, 10);
    }

    function closeRegenerateModal() {
        const modal = document.getElementById('modalRegeneratePdf');
        const box = document.getElementById('modalRegeneratePdfBox');
        if (!modal || !box) return;

        box.classList.remove('scale-100');
        box.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }, 150);
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeRegenerateModal();
    });

    const modalRegeneratePdfEl = document.getElementById('modalRegeneratePdf');
    if (modalRegeneratePdfEl) {
        modalRegeneratePdfEl.addEventListener('click', function(e) {
            if (e.target === this) closeRegenerateModal();
        });
    }
</script>
@endpush
