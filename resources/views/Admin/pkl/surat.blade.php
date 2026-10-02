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
        <div class="flex items-center gap-2">
            <button type="button" onclick="openEditSuratModal()"
                class="text-xs font-bold bg-amber-500 hover:bg-amber-600 text-white px-3.5 py-2 rounded-lg transition shadow-xs flex items-center gap-1.5 cursor-pointer">
                <i class="fa-solid fa-pen-to-square"></i>
                <span>Edit Data Surat</span>
            </button>
            <a href="{{ route('pkl.index') }}"
                class="text-xs font-semibold text-gray-500 hover:text-gray-700 bg-white border border-gray-200 px-3 py-2 rounded-lg transition">
                <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali
            </a>
        </div>
    </div>

    {{-- INFO SURAT --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 md:gap-4">
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 card-shadow p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-900 text-sm">Detail Surat</h3>
                <button type="button" onclick="openEditSuratModal()"
                    class="text-xs font-semibold text-amber-600 hover:text-amber-700 bg-amber-50 px-2.5 py-1 rounded-md transition cursor-pointer flex items-center gap-1">
                    <i class="fa-solid fa-pen-to-square"></i> Perbaiki Typo / Edit
                </button>
            </div>

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

            {{-- AKSI PDF & EDIT --}}
            <div class="mt-5 pt-4 border-t border-gray-100 flex flex-wrap items-center gap-2">
                @if ($surat->file_pdf_path)
                    <a href="{{ route('pkl.surat.download', $surat) }}"
                        class="text-sm font-semibold bg-brand-600 hover:bg-brand-700 text-white px-4 py-2.5 rounded-lg transition shadow-xs flex items-center gap-1.5">
                        <i class="fa-solid fa-download"></i> Download PDF
                    </a>
                @endif

                <button type="button" onclick="openEditSuratModal()"
                    class="text-sm font-semibold bg-amber-500 hover:bg-amber-600 text-white px-4 py-2.5 rounded-lg transition cursor-pointer flex items-center gap-1.5 shadow-xs">
                    <i class="fa-solid fa-pen-to-square"></i>
                    <span>Edit Data Surat</span>
                </button>

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
<!-- ==================== MODAL EDIT DATA SURAT ==================== -->
<div id="modalEditSurat" class="fixed inset-0 !m-0 z-[100] hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-3xl w-full max-w-2xl shadow-2xl border border-slate-100 overflow-hidden transform transition-all scale-95 duration-200 max-h-[90vh] flex flex-col" id="modalEditSuratBox">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between bg-slate-50/70">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm font-bold border border-amber-100">
                    <i class="fa-solid fa-pen-to-square"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-sm">Edit Data Surat Pengajuan</h3>
                    <p class="text-[11px] text-slate-500">Nomor: {{ $surat->nomor_surat }}</p>
                </div>
            </div>
            <button type="button" onclick="closeEditSuratModal()" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>

        <form action="{{ route('pkl.surat.update', $surat) }}" method="POST" id="form-edit-surat" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            @method('PUT')

            <div class="p-5 space-y-4 overflow-y-auto flex-1">
                {{-- DUDI & Guru --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-700 mb-1.5">
                            DUDI Tujuan <span class="text-red-500">*</span>
                        </label>
                        <select name="dudi_id" required
                            class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                            @foreach ($dudis as $d)
                                <option value="{{ $d->id }}" @selected(old('dudi_id', $surat->dudi_id) == $d->id)>
                                    {{ $d->nama_dudi }} ({{ $d->kota }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-gray-700 mb-1.5">
                            Guru Pembimbing <span class="text-red-500">*</span>
                        </label>
                        @php
                            $currentGuruId = $surat->penempatanPkls->first()?->guru_id;
                        @endphp
                        <select name="guru_id" required
                            class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                            @foreach ($gurus as $g)
                                <option value="{{ $g->id }}" @selected(old('guru_id', $currentGuruId) == $g->id)>
                                    {{ $g->nama }} ({{ $g->jurusan }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Tanggal Surat & Periode PKL --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-700 mb-1.5">
                            Tanggal Surat <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_surat" required max="{{ now()->toDateString() }}"
                            value="{{ old('tanggal_surat', $surat->tanggal_surat->format('Y-m-d')) }}"
                            class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-gray-700 mb-1.5">
                            Mulai PKL <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tgl_mulai_pkl" required
                            value="{{ old('tgl_mulai_pkl', $surat->tgl_mulai_pkl->format('Y-m-d')) }}"
                            class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-gray-700 mb-1.5">
                            Selesai PKL <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tgl_selesai_pkl" required
                            value="{{ old('tgl_selesai_pkl', $surat->tgl_selesai_pkl->format('Y-m-d')) }}"
                            class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    </div>
                </div>

                {{-- Daftar Siswa --}}
                <div>
                    @php
                        $assignedIds = $surat->penempatanPkls->pluck('siswa_id')->all();
                    @endphp
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-[11px] font-semibold text-gray-700">
                            Pilih Siswa yang Masuk dalam Surat Ini <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center gap-2">
                            <button type="button" id="btn-edit-pilih-semua" class="text-[11px] text-brand-600 hover:underline">Pilih semua</button>
                            <span class="text-gray-300">&bull;</span>
                            <button type="button" id="btn-edit-kosongkan" class="text-[11px] text-gray-500 hover:underline">Kosongkan</button>
                        </div>
                    </div>

                    <div class="mb-2">
                        <input type="text" id="cari-siswa-modal" placeholder="Filter nama siswa atau kelas..."
                            class="w-full text-xs border border-gray-200 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    </div>

                    <div class="max-h-48 overflow-y-auto border border-gray-200 rounded-xl divide-y divide-gray-100 p-1">
                        @foreach ($availableSiswas as $s)
                            @php
                                $isChecked = in_array($s->id, old('siswa_ids', $assignedIds));
                            @endphp
                            <label class="edit-siswa-item flex items-center gap-3 px-3 py-2 hover:bg-gray-50 rounded-lg cursor-pointer transition text-xs"
                                data-text="{{ strtolower($s->nama . ' ' . $s->label_kelas . ' ' . $s->nis) }}">
                                <input type="checkbox" name="siswa_ids[]" value="{{ $s->id }}"
                                    @checked($isChecked)
                                    class="edit-siswa-checkbox w-4 h-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                                <span class="flex-1 min-w-0">
                                    <span class="font-bold text-gray-800 block truncate">{{ $s->nama }}</span>
                                    <span class="text-[10px] text-gray-500">{{ $s->label_kelas }} &middot; NIS {{ $s->nis }}</span>
                                </span>
                                @if (in_array($s->id, $assignedIds))
                                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-blue-50 text-brand-700">Sudah Ada</span>
                                @endif
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-800 flex items-start gap-2">
                    <i class="fa-solid fa-circle-info text-amber-600 mt-0.5"></i>
                    <span>Setelah disimpan, sistem akan langsung <strong>men-generate ulang berkas PDF</strong> surat ini dengan data perubahan yang baru.</span>
                </div>
            </div>

            <div class="p-4 border-t border-gray-100 bg-gray-50/80 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeEditSuratModal()"
                    class="px-4 py-2 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-100 text-xs font-semibold transition cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 active:scale-95 text-white text-xs font-bold shadow-xs transition flex items-center gap-1.5 cursor-pointer">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Simpan &amp; Update PDF</span>
                </button>
            </div>
        </form>
    </div>
</div>

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
    function openEditSuratModal() {
        const modal = document.getElementById('modalEditSurat');
        const box = document.getElementById('modalEditSuratBox');
        if (!modal || !box) return;

        document.body.classList.add('overflow-hidden');
        modal.classList.remove('hidden');
        setTimeout(() => {
            box.classList.remove('scale-95');
            box.classList.add('scale-100');
        }, 10);
    }

    function closeEditSuratModal() {
        const modal = document.getElementById('modalEditSurat');
        const box = document.getElementById('modalEditSuratBox');
        if (!modal || !box) return;

        box.classList.remove('scale-100');
        box.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }, 150);
    }

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

    document.addEventListener('DOMContentLoaded', function() {
        // Modal live search siswa
        const searchInput = document.getElementById('cari-siswa-modal');
        const siswaItems = Array.from(document.querySelectorAll('.edit-siswa-item'));
        const checkboxes = Array.from(document.querySelectorAll('.edit-siswa-checkbox'));

        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const q = e.target.value.toLowerCase().trim();
                siswaItems.forEach(function(item) {
                    item.style.display = !q || item.dataset.text.includes(q) ? '' : 'none';
                });
            });
        }

        const btnPilihSemua = document.getElementById('btn-edit-pilih-semua');
        const btnKosongkan = document.getElementById('btn-edit-kosongkan');

        if (btnPilihSemua) {
            btnPilihSemua.addEventListener('click', function() {
                checkboxes.forEach(function(cb) {
                    if (cb.closest('.edit-siswa-item').style.display !== 'none') cb.checked = true;
                });
            });
        }

        if (btnKosongkan) {
            btnKosongkan.addEventListener('click', function() {
                checkboxes.forEach(function(cb) { cb.checked = false; });
            });
        }

        const formEdit = document.getElementById('form-edit-surat');
        if (formEdit) {
            formEdit.addEventListener('submit', function(e) {
                const checked = checkboxes.filter(function(cb) { return cb.checked; });
                if (checked.length === 0) {
                    e.preventDefault();
                    alert('Pilih minimal satu siswa dalam surat ini.');
                }
            });
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEditSuratModal();
            closeRegenerateModal();
        }
    });
</script>
@endpush
