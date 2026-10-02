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

    @if ($errors->any())
        <div
            class="rounded-xl border border-red-200 bg-red-50 text-red-800 px-4 py-3 text-sm flex items-start gap-2">
            <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
            <div>
                <p class="font-semibold">Terjadi kesalahan pada input data:</p>
                <ul class="list-disc list-inside mt-1 space-y-0.5 text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h2 class="font-bold text-gray-900 text-base">Master DUDI (Dunia Usaha & Industri)</h2>
            <p class="text-[11px] text-gray-500 mt-0.5">
                Kelola profil, kuota penempatan, logo mitra industri, dan persetujuan tayang di Landing Page.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" onclick="openCreateDudiModal()"
                class="text-xs font-semibold bg-brand-600 hover:bg-brand-700 text-white px-3.5 py-2 rounded-lg transition shadow-sm flex items-center gap-1.5">
                <i class="fa-solid fa-plus"></i> Tambah Mitra DUDI
            </button>
            <a href="{{ route('pkl.create') }}"
                class="text-xs font-semibold bg-gray-100 hover:bg-gray-200 text-gray-700 px-3.5 py-2 rounded-lg transition flex items-center gap-1.5">
                <i class="fa-solid fa-file-signature"></i> Pengajuan PKL Siswa
            </a>
        </div>
    </div>

    {{-- GRID DAFTAR DUDI --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3 md:gap-4">
        @forelse ($dudis as $d)
            <div class="bg-white rounded-2xl border border-gray-100 card-shadow p-4 flex flex-col justify-between">

                <div>
                    {{-- Header Kartu & Logo DUDI --}}
                    <div class="flex items-start gap-3">
                        @if ($d->logo_url)
                            <img src="{{ $d->logo_url }}" alt="Logo {{ $d->nama_dudi }}"
                                class="w-12 h-12 object-contain rounded-xl border border-gray-100 bg-gray-50 p-1 shrink-0 shadow-sm">
                        @else
                            <div class="w-12 h-12 rounded-xl bg-blue-50 text-brand-600 border border-blue-100 flex items-center justify-center shrink-0 font-black text-sm">
                                {{ strtoupper(substr($d->nama_dudi, 0, 3)) }}
                            </div>
                        @endif

                        <div class="min-w-0 flex-1">
                            <div class="flex items-start justify-between gap-1">
                                <h3 class="font-bold text-gray-900 text-sm leading-snug truncate" title="{{ $d->nama_dudi }}">
                                    {{ $d->nama_dudi }}
                                </h3>
                                @if ($d->is_mitra_resmi)
                                    <span class="shrink-0 text-[9px] font-bold px-1.5 py-0.5 rounded bg-violet-50 text-violet-700 border border-violet-100">
                                        MITRA RESMI
                                    </span>
                                @else
                                    <span class="shrink-0 text-[9px] font-bold px-1.5 py-0.5 rounded bg-gray-100 text-gray-600">
                                        USULAN BARU
                                    </span>
                                @endif
                            </div>
                            <p class="text-[11px] text-gray-500 mt-0.5">
                                <i class="fa-solid fa-location-dot mr-1 text-brand-500"></i>{{ $d->kota }}
                            </p>
                        </div>
                    </div>

                    <p class="text-[11px] text-gray-600 mt-2.5 font-medium leading-relaxed">{{ $d->bidang_usaha }}</p>
                    <p class="text-[11px] text-gray-500 mt-1 leading-relaxed line-clamp-2" title="{{ $d->alamat }}">{{ $d->alamat }}</p>

                    @if ($d->kontak_person || $d->no_hp)
                        <p class="text-[11px] text-gray-500 mt-1.5">
                            <i class="fa-solid fa-user mr-1 text-gray-400"></i>{{ $d->kontak_person ?? '-' }}
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

                    {{-- Status landing & lowongan --}}
                    <div class="mt-3 flex items-center justify-between text-[11px]">
                        <span @class([
                            'inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md font-semibold text-[10px]',
                            'bg-emerald-50 text-emerald-700 border border-emerald-100' => $d->tampil_di_landing,
                            'bg-gray-100 text-gray-500' => ! $d->tampil_di_landing,
                        ])>
                            <i @class([
                                'fa-solid fa-eye text-[10px]',
                                'fa-solid fa-eye-slash text-[10px]' => ! $d->tampil_di_landing,
                            ])></i>
                            {{ $d->tampil_di_landing ? 'Tayang di Landing' : 'Belum di-ACC' }}
                        </span>
                        <span class="text-gray-400 text-[10px]">{{ $d->lowongans_count }} lowongan</span>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="mt-4 pt-3 border-t border-gray-100 space-y-2">
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button"
                            onclick='openEditDudiModal(@json($d))'
                            class="text-xs font-semibold bg-gray-50 hover:bg-gray-100 text-slate-700 border border-gray-200 rounded-lg py-1.5 transition text-center flex items-center justify-center gap-1">
                            <i class="fa-solid fa-pen text-[11px]"></i> Edit &amp; Logo
                        </button>

                        <a href="{{ route('pkl.detail', $d->id) }}" target="_blank"
                            class="text-xs font-semibold bg-blue-50 hover:bg-blue-100 text-brand-700 border border-blue-100 rounded-lg py-1.5 transition text-center flex items-center justify-center gap-1">
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i> Lihat Publik
                        </a>
                    </div>

                    {{-- Aksi ACC Landing --}}
                    <form method="POST" action="{{ route('pkl.dudi.acc-landing', $d) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="w-full text-xs font-semibold rounded-lg py-1.5 transition
                                {{ $d->tampil_di_landing
                                    ? 'bg-gray-100 hover:bg-gray-200 text-gray-700'
                                    : 'bg-brand-600 hover:bg-brand-700 text-white' }}">
                            <i @class([
                                'fa-solid fa-eye-slash mr-1',
                                'fa-solid fa-eye mr-1' => ! $d->tampil_di_landing,
                            ])></i>
                            {{ $d->tampil_di_landing ? 'Batalkan Tayang di Landing' : 'ACC Tayang di Landing' }}
                        </button>
                    </form>
                </div>

            </div>
        @empty
            <div class="md:col-span-2 xl:col-span-3 bg-white rounded-2xl border border-gray-100 card-shadow p-10 text-center">
                <i class="fa-solid fa-building text-gray-300 text-3xl mb-3 block"></i>
                <p class="text-sm text-gray-500">Belum ada data DUDI. Tambahkan melalui tombol di atas.</p>
            </div>
        @endforelse
    </div>

    {{-- ============================================================
         MODAL: TAMBAH MITRA DUDI DENGAN UPLOAD LOGO
         ============================================================ --}}
    <div id="modalCreateDudi" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-2xl w-full max-w-lg overflow-hidden transform transition-all">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="font-bold text-gray-900 text-sm sm:text-base flex items-center gap-2">
                    <i class="fa-solid fa-building text-brand-600"></i> Tambah Mitra DUDI Baru
                </h3>
                <button type="button" onclick="closeCreateDudiModal()" class="text-gray-400 hover:text-gray-600 text-lg">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('pkl.dudi.store') }}" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-600 mb-1">Nama Perusahaan / DUDI <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_dudi" required placeholder="PT Mega Kreasi Digital"
                            class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-600 mb-1">Kota / Kabupaten <span class="text-red-500">*</span></label>
                        <input type="text" name="kota" required placeholder="Karanganyar"
                            class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-600 mb-1">Bidang Usaha <span class="text-red-500">*</span></label>
                        <input type="text" name="bidang_usaha" required placeholder="Teknologi Informasi / Tekstil / Otomotif"
                            class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-600 mb-1">Kuota Maksimal PKL</label>
                        <input type="number" name="kuota_maksimal" min="0" max="1000" value="5"
                            class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-gray-600 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
                    <textarea name="alamat" rows="2" required placeholder="Jl. Lawu No. 120, Karanganyar"
                        class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-600 mb-1">Kontak Person (PIC)</label>
                        <input type="text" name="kontak_person" placeholder="Bpk. Joko"
                            class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-600 mb-1">No. HP / WhatsApp</label>
                        <input type="text" name="no_hp" placeholder="08123456789"
                            class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-gray-600 mb-1">Deskripsi Singkat Perusahaan</label>
                    <textarea name="deskripsi" rows="2" placeholder="Profil singkat perusahaan mitra industri..."
                        class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500"></textarea>
                </div>

                {{-- Upload Foto / Logo DUDI --}}
                <div class="p-3 bg-blue-50/50 border border-blue-100 rounded-xl">
                    <label class="block text-[11px] font-bold text-slate-700 mb-1">
                        Foto / Logo Mitra DUDI <span class="font-normal text-gray-400">(Opsional, max 2MB)</span>
                    </label>
                    <input type="file" name="logo" accept="image/*"
                        class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-600 file:text-white hover:file:bg-brand-700 border border-gray-200 rounded-lg p-1 bg-white">
                    <p class="text-[10px] text-gray-400 mt-1">Format: JPG, PNG, WEBP, SVG. Gambar ini akan tampil di Landing Page PKL &amp; halaman detail mitra.</p>
                </div>

                <div class="flex items-center gap-4 text-xs font-medium text-gray-700">
                    <label class="flex items-center gap-1.5 cursor-pointer">
                        <input type="checkbox" name="is_mitra_resmi" value="1" checked class="rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                        Mitra Resmi
                    </label>
                    <label class="flex items-center gap-1.5 cursor-pointer">
                        <input type="checkbox" name="tampil_di_landing" value="1" checked class="rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                        Tayang di Landing Page
                    </label>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
                    <button type="button" onclick="closeCreateDudiModal()"
                        class="px-4 py-2 text-xs font-semibold text-gray-600 hover:text-gray-800 rounded-lg">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-xs font-semibold bg-brand-600 hover:bg-brand-700 text-white rounded-lg transition shadow-sm">
                        Simpan DUDI
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ============================================================
         MODAL: EDIT MITRA DUDI & GANTI LOGO
         ============================================================ --}}
    <div id="modalEditDudi" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-2xl w-full max-w-lg overflow-hidden transform transition-all">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="font-bold text-gray-900 text-sm sm:text-base flex items-center gap-2">
                    <i class="fa-solid fa-pen text-brand-600"></i> Edit Mitra DUDI &amp; Logo
                </h3>
                <button type="button" onclick="closeEditDudiModal()" class="text-gray-400 hover:text-gray-600 text-lg">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form id="formEditDudi" method="POST" action="" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-600 mb-1">Nama Perusahaan / DUDI <span class="text-red-500">*</span></label>
                        <input type="text" id="edit_nama_dudi" name="nama_dudi" required
                            class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-600 mb-1">Kota / Kabupaten <span class="text-red-500">*</span></label>
                        <input type="text" id="edit_kota" name="kota" required
                            class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-600 mb-1">Bidang Usaha <span class="text-red-500">*</span></label>
                        <input type="text" id="edit_bidang_usaha" name="bidang_usaha" required
                            class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-600 mb-1">Kuota Maksimal PKL</label>
                        <input type="number" id="edit_kuota_maksimal" name="kuota_maksimal" min="0" max="1000"
                            class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-gray-600 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
                    <textarea id="edit_alamat" name="alamat" rows="2" required
                        class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-600 mb-1">Kontak Person (PIC)</label>
                        <input type="text" id="edit_kontak_person" name="kontak_person"
                            class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-600 mb-1">No. HP / WhatsApp</label>
                        <input type="text" id="edit_no_hp" name="no_hp"
                            class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-gray-600 mb-1">Deskripsi Singkat Perusahaan</label>
                    <textarea id="edit_deskripsi" name="deskripsi" rows="2"
                        class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500"></textarea>
                </div>

                {{-- Upload & Preview Logo DUDI --}}
                <div class="p-3 bg-blue-50/50 border border-blue-100 rounded-xl space-y-2">
                    <label class="block text-[11px] font-bold text-slate-700">
                        Foto / Logo Mitra DUDI
                    </label>

                    <div id="edit_logo_preview_container" class="hidden items-center gap-3 p-2 bg-white border border-gray-200 rounded-lg">
                        <img id="edit_logo_preview" src="" alt="Logo saat ini" class="w-12 h-12 object-contain rounded border p-1 bg-white">
                        <div class="text-xs">
                            <span class="font-semibold text-gray-700 block">Logo saat ini terpasang</span>
                            <label class="inline-flex items-center gap-1.5 text-red-600 cursor-pointer text-[11px] mt-1">
                                <input type="checkbox" name="hapus_logo" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                Hapus logo ini
                            </label>
                        </div>
                    </div>

                    <input type="file" name="logo" accept="image/*"
                        class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-600 file:text-white hover:file:bg-brand-700 border border-gray-200 rounded-lg p-1 bg-white">
                    <p class="text-[10px] text-gray-400">Pilih file baru jika ingin mengganti logo yang sudah ada (Maks 2MB).</p>
                </div>

                <div class="flex items-center gap-4 text-xs font-medium text-gray-700">
                    <label class="flex items-center gap-1.5 cursor-pointer">
                        <input type="checkbox" id="edit_is_mitra_resmi" name="is_mitra_resmi" value="1" class="rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                        Mitra Resmi
                    </label>
                    <label class="flex items-center gap-1.5 cursor-pointer">
                        <input type="checkbox" id="edit_tampil_di_landing" name="tampil_di_landing" value="1" class="rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                        Tayang di Landing Page
                    </label>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
                    <button type="button" onclick="closeEditDudiModal()"
                        class="px-4 py-2 text-xs font-semibold text-gray-600 hover:text-gray-800 rounded-lg">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-xs font-semibold bg-brand-600 hover:bg-brand-700 text-white rounded-lg transition shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script Modal --}}
    <script>
        function openCreateDudiModal() {
            document.getElementById('modalCreateDudi').classList.remove('hidden');
        }

        function closeCreateDudiModal() {
            document.getElementById('modalCreateDudi').classList.add('hidden');
        }

        function openEditDudiModal(dudi) {
            const form = document.getElementById('formEditDudi');
            form.action = "{{ url('dashboard/pkl-bkk/dudi') }}/" + dudi.id;

            document.getElementById('edit_nama_dudi').value = dudi.nama_dudi || '';
            document.getElementById('edit_kota').value = dudi.kota || '';
            document.getElementById('edit_bidang_usaha').value = dudi.bidang_usaha || '';
            document.getElementById('edit_kuota_maksimal').value = dudi.kuota_maksimal || 0;
            document.getElementById('edit_alamat').value = dudi.alamat || '';
            document.getElementById('edit_kontak_person').value = dudi.kontak_person || '';
            document.getElementById('edit_no_hp').value = dudi.no_hp || '';
            document.getElementById('edit_deskripsi').value = dudi.deskripsi || '';
            document.getElementById('edit_is_mitra_resmi').checked = Boolean(dudi.is_mitra_resmi);
            document.getElementById('edit_tampil_di_landing').checked = Boolean(dudi.tampil_di_landing);

            const previewContainer = document.getElementById('edit_logo_preview_container');
            const previewImg = document.getElementById('edit_logo_preview');
            
            // Check logo URL
            if (dudi.logo) {
                let logoSrc = '';
                if (dudi.logo.startsWith('http://') || dudi.logo.startsWith('https://')) {
                    logoSrc = dudi.logo;
                } else if (dudi.logo.startsWith('dudi-logo/')) {
                    logoSrc = "{{ asset('storage') }}/" + dudi.logo;
                } else {
                    logoSrc = "{{ asset('assets') }}/" + dudi.logo;
                }
                previewImg.src = logoSrc;
                previewContainer.classList.remove('hidden');
                previewContainer.classList.add('flex');
            } else {
                previewContainer.classList.add('hidden');
                previewContainer.classList.remove('flex');
            }

            document.getElementById('modalEditDudi').classList.remove('hidden');
        }

        function closeEditDudiModal() {
            document.getElementById('modalEditDudi').classList.add('hidden');
        }

        // Close on ESC key
        window.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeCreateDudiModal();
                closeEditDudiModal();
            }
        });
    </script>

@endsection
