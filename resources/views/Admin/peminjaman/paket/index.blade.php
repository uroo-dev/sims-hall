@extends('Admin.layout.app')

@section('title', 'Manajemen Paket Peminjaman - SMK Negeri 2 Karanganyar')
@section('page_title', 'Paket Peminjaman')

@section('content')
<div class="space-y-6">

    <!-- FLASH MESSAGES -->
    @if (session('success'))
        <div id="alertSuccess" class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4 flex items-center justify-between shadow-sm transition-all duration-300">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold flex-shrink-0">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div class="text-xs md:text-sm font-semibold">
                    {{ session('success') }}
                </div>
            </div>
            <button type="button" onclick="document.getElementById('alertSuccess').remove()" class="text-emerald-500 hover:text-emerald-700 p-1">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div id="alertErrorSession" class="bg-red-50 border border-red-200 text-red-800 rounded-2xl p-4 flex items-center justify-between shadow-sm transition-all duration-300">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-red-100 text-red-600 flex items-center justify-center font-bold flex-shrink-0">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div class="text-xs md:text-sm font-semibold">
                    {{ session('error') }}
                </div>
            </div>
            <button type="button" onclick="document.getElementById('alertErrorSession').remove()" class="text-red-500 hover:text-red-700 p-1">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div id="alertErrorValidation" class="bg-red-50 border border-red-200 text-red-800 rounded-2xl p-4 flex items-center justify-between shadow-sm transition-all duration-300">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl bg-red-100 text-red-600 flex items-center justify-center font-bold flex-shrink-0 mt-0.5">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div class="text-xs md:text-sm">
                    <div class="font-bold">Terjadi kesalahan pada input data:</div>
                    <ul class="list-disc list-inside mt-1 text-red-700 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" onclick="document.getElementById('alertErrorValidation').remove()" class="text-red-500 hover:text-red-700 p-1">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
    @endif

    <!-- ACTION, STAT & SEARCH CONTROLS -->
    <div class="bg-white rounded-2xl p-4 md:p-5 figma-card-shadow border border-slate-100 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
        <!-- 1. BTN TAMBAH PAKET & 2. TOTAL PAKET -->
        <div class="flex flex-wrap items-center gap-3">
            @if(!auth()->user()->isSuperAdmin())
                <button type="button" onclick="openCreateModal()"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-brand-600 hover:bg-brand-700 active:scale-95 text-white text-xs md:text-sm font-bold rounded-xl shadow-sm transition transform">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Tambah Paket</span>
                </button>
            @else
                <span class="inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-amber-50 text-amber-800 border border-amber-200 text-xs md:text-sm font-semibold rounded-xl">
                    <i class="fa-solid fa-lock text-amber-600 text-xs"></i>
                    <span>Mode Baca (Super Admin)</span>
                </span>
            @endif

            <div class="flex items-center gap-2 text-xs md:text-sm text-slate-600 font-medium bg-slate-50 px-3.5 py-2.5 rounded-xl border border-slate-200">
                <i class="fa-solid fa-boxes-packing text-brand-600 text-xs"></i>
                <span>Total Paket: <strong class="text-slate-800 font-bold">{{ $pakets->total() }}</strong></span>
            </div>
        </div>

        <!-- 3. SEARCH BAR -->
        <form action="{{ route('admin.paket.index') }}" method="GET" class="w-full md:max-w-md flex items-center gap-2">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" name="search" value="{{ $search ?? '' }}"
                    placeholder="Cari kategori, nama paket, fasilitas..."
                    class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition">
            </div>
            <button type="submit"
                class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white text-xs md:text-sm font-semibold rounded-xl transition shadow-sm flex-shrink-0">
                Cari
            </button>
            @if (!empty($search))
                <a href="{{ route('admin.paket.index') }}"
                    class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs md:text-sm font-semibold rounded-xl transition flex-shrink-0"
                    title="Reset Pencarian">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- DATA TABLE CONTAINER -->
    <div class="bg-white rounded-2xl figma-card-shadow border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs md:text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 font-extrabold uppercase tracking-wider text-[11px]">
                        <th class="py-4 px-4 w-14 text-center">No</th>
                        <th class="py-4 px-4">Paket & Kategori</th>
                        <th class="py-4 px-4">Harga Sewa</th>
                        <th class="py-4 px-4">Fasilitas Termasuk</th>
                        <th class="py-4 px-4">Deskripsi</th>
                        @if(!auth()->user()->isSuperAdmin())
                            <th class="py-4 px-4 text-center w-28">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                    @forelse ($pakets as $index => $paket)
                        @php
                            $badgeColor = match($paket->kategori) {
                                'unggulan' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'terjangkau' => 'bg-sky-50 text-sky-700 border-sky-200',
                                default => 'bg-slate-100 text-slate-700 border-slate-200',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3.5 px-4 text-center text-slate-500 font-bold">
                                {{ $pakets->firstItem() + $index }}
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-brand-600 flex items-center justify-center font-bold text-sm flex-shrink-0">
                                        <i class="fa-solid fa-boxes-packing"></i>
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-800 block text-sm">
                                            {{ $paket->nama_paket ?: 'Paket ' . ucwords($paket->kategori) }}
                                        </span>
                                        <span class="inline-block mt-0.5 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase border {{ $badgeColor }}">
                                            {{ $paket->kategori }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="font-bold text-brand-600">Rp {{ number_format($paket->harga, 0, ',', '.') }}</div>
                                @if ($paket->harga_dp)
                                    <div class="text-[11px] text-slate-500 font-medium">DP: Rp {{ number_format($paket->harga_dp, 0, ',', '.') }}</div>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 max-w-xs md:max-w-md">
                                <div class="flex flex-wrap gap-1.5">
                                    @forelse ($paket->facilities as $fac)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg text-[11px] font-semibold bg-blue-50 text-brand-700 border border-blue-100">
                                            <i class="fa-solid fa-check text-[9px]"></i>
                                            <span>{{ $fac->judul }}</span>
                                        </span>
                                    @empty
                                        <span class="text-xs text-slate-400 italic">Belum ada fasilitas</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-slate-600 max-w-xs">
                                <div class="line-clamp-2">
                                    {{ $paket->deskripsi ?: '-' }}
                                </div>
                            </td>
                            @if(!auth()->user()->isSuperAdmin())
                                <td class="py-3.5 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button"
                                            onclick="openEditModal({{ $paket->id }}, @js($paket->nama_paket), @js($paket->kategori), {{ $paket->harga }}, @js($paket->harga_dp), @js($paket->deskripsi), @js($paket->facilities->pluck('id')), '{{ route('admin.paket.update', $paket->id) }}')"
                                            class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 flex items-center justify-center transition shadow-sm"
                                            title="Edit Paket Peminjaman">
                                            <i class="fa-regular fa-pen-to-square text-xs"></i>
                                        </button>
                                        <button type="button"
                                            onclick="openDeleteModal({{ $paket->id }}, @js($paket->nama_paket ?: 'Paket ' . ucwords($paket->kategori)), '{{ route('admin.paket.destroy', $paket->id) }}')"
                                            class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center transition shadow-sm"
                                            title="Hapus Paket Peminjaman">
                                            <i class="fa-regular fa-trash-can text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->isSuperAdmin() ? 5 : 6 }}" class="py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-3">
                                    <div class="w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 text-2xl">
                                        <i class="fa-solid fa-boxes-packing"></i>
                                    </div>
                                    <div class="font-bold text-slate-700 text-base">Tidak ada paket peminjaman ditemukan</div>
                                    <p class="text-xs text-slate-500 max-w-sm">
                                        @if (!empty($search))
                                            Tidak ditemukan paket dengan kata kunci "<span class="font-semibold text-slate-700">{{ $search }}</span>". Coba gunakan kata kunci lain.
                                        @else
                                            Belum ada paket peminjaman aula yang tersimpan. Klik tombol di bawah untuk menambahkan paket pertama.
                                        @endif
                                    </p>
                                    @if (empty($search) && !auth()->user()->isSuperAdmin())
                                        <button type="button" onclick="openCreateModal()"
                                            class="mt-2 inline-flex items-center gap-2 px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                                            <i class="fa-solid fa-plus text-xs"></i>
                                            <span>Tambah Paket Baru</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        @if ($pakets->hasPages())
            <div class="p-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 bg-slate-50/50">
                <div class="text-xs text-slate-500">
                    Menampilkan <span class="font-bold text-slate-700">{{ $pakets->firstItem() }}</span> -
                    <span class="font-bold text-slate-700">{{ $pakets->lastItem() }}</span> dari
                    <span class="font-bold text-slate-700">{{ $pakets->total() }}</span> paket
                </div>
                <div>
                    {{ $pakets->links() }}
                </div>
            </div>
        @endif
    </div>

</div>
@endsection

@push('modals')
@if(!auth()->user()->isSuperAdmin())
<!-- ============================================================== -->
<!-- MODAL: TAMBAH PAKET PEMINJAMAN -->
<!-- ============================================================== -->
<div id="modalCreate" class="fixed inset-0 !m-0 z-[100] hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-3xl w-full max-w-2xl shadow-2xl border border-slate-100 overflow-hidden transform transition-all scale-95 duration-200 max-h-[90vh] flex flex-col" id="modalCreateBox">
        <!-- HEADER -->
        <div class="p-5 md:p-6 pb-2 md:pb-3 bg-white flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-blue-50 text-brand-600 flex items-center justify-center text-lg border border-blue-100/80 shadow-xs flex-shrink-0">
                    <i class="fa-solid fa-boxes-packing"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base md:text-lg tracking-tight">Tambah Paket Peminjaman Baru</h3>
                    <p class="text-slate-500 text-xs mt-0.5">Atur paket sewa aula beserta fasilitas di dalamnya</p>
                </div>
            </div>
            <button type="button" onclick="closeCreateModal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition" title="Tutup Modal">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- FORM BODY -->
        <form action="{{ route('admin.paket.store') }}" method="POST" class="p-5 md:p-6 space-y-4 overflow-y-auto">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="create_kategori" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                        Kategori Paket <span class="text-red-500">*</span>
                    </label>
                    <select name="kategori" id="create_kategori" required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition">
                        <option value="" disabled {{ old('kategori') ? '' : 'selected' }}>-- Pilih Kategori --</option>
                        <option value="unggulan" {{ old('kategori') === 'unggulan' ? 'selected' : '' }}>Unggulan</option>
                        <option value="terjangkau" {{ old('kategori') === 'terjangkau' ? 'selected' : '' }}>Terjangkau</option>
                        <option value="standar 1" {{ old('kategori') === 'standar 1' ? 'selected' : '' }}>Standar 1</option>
                        <option value="standar 2" {{ old('kategori') === 'standar 2' ? 'selected' : '' }}>Standar 2</option>
                        <option value="standar 3" {{ old('kategori') === 'standar 3' ? 'selected' : '' }}>Standar 3</option>
                    </select>
                </div>

                <div>
                    <label for="create_nama_paket" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                        Nama / Judul Paket (Opsional)
                    </label>
                    <input type="text" name="nama_paket" id="create_nama_paket" maxlength="150"
                        value="{{ old('nama_paket') }}"
                        placeholder="Contoh: Paket Wedding Full Service"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="create_harga" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                        Harga Sewa (Rp) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center font-bold text-slate-500 text-xs">
                            Rp
                        </span>
                        <input type="number" name="harga" id="create_harga" required min="0" step="1000"
                            value="{{ old('harga') }}"
                            placeholder="Contoh: 6000000"
                            class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition">
                    </div>
                </div>

                <div>
                    <label for="create_harga_dp" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                        Harga Deposit (DP) (Rp)
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center font-bold text-slate-500 text-xs">
                            Rp
                        </span>
                        <input type="number" name="harga_dp" id="create_harga_dp" min="0" step="1000"
                            value="{{ old('harga_dp') }}"
                            placeholder="Contoh: 2000000"
                            class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition">
                    </div>
                </div>
            </div>

            <!-- MULTI-SELECT DROPDOWN FASILITAS -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide">
                        Fasilitas yang Termasuk <span class="text-red-500">*</span>
                    </label>
                    <span id="create_selected_count" class="text-[11px] text-brand-600 font-bold">0 dipilih</span>
                </div>

                <!-- DROPDOWN WRAPPER -->
                <div class="relative" id="create_dropdown_wrapper">
                    <!-- Dropdown Trigger Button -->
                    <button type="button" onclick="toggleFacilitiesDropdown('create')"
                        class="w-full flex items-center justify-between px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-700 hover:bg-slate-100/70 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition text-left">
                        <span id="create_dropdown_placeholder" class="text-slate-500 truncate">
                            Pilih fasilitas aula untuk paket ini...
                        </span>
                        <i class="fa-solid fa-chevron-down text-xs text-slate-400 ml-2"></i>
                    </button>

                    <!-- Dropdown Menu Box -->
                    <div id="create_dropdown_box" class="hidden absolute left-0 right-0 top-full mt-1.5 bg-white border border-slate-200 rounded-2xl shadow-xl z-50 p-3 max-h-60 overflow-y-auto space-y-2">
                        <!-- Search filter inside dropdown -->
                        <div class="relative mb-2">
                            <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center text-slate-400 pointer-events-none">
                                <i class="fa-solid fa-magnifying-glass text-[11px]"></i>
                            </span>
                            <input type="text" id="create_facility_search" oninput="filterFacilityList('create')"
                                placeholder="Filter fasilitas..."
                                class="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-1 focus:ring-brand-600">
                        </div>

                        <!-- Facility Checkbox Items -->
                        <div id="create_facility_items" class="space-y-1">
                            @forelse ($availableFacilities as $facility)
                                <label class="facility-item flex items-center gap-2.5 p-2 rounded-xl hover:bg-slate-50 cursor-pointer transition"
                                    data-title="{{ strtolower($facility->judul) }}">
                                    <input type="checkbox" name="facility_ids[]" value="{{ $facility->id }}"
                                        onchange="updateFacilitySelection('create')"
                                        class="w-4 h-4 rounded text-brand-600 border-slate-300 focus:ring-brand-600 cursor-pointer facility-checkbox-create">
                                    <span class="text-xs text-slate-700 font-medium select-none">{{ $facility->judul }}</span>
                                </label>
                            @empty
                                <div class="text-xs text-slate-400 p-2 text-center">
                                    Belum ada data fasilitas. Silakan tambahkan fasilitas terlebih dahulu.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- SELECTED CHIPS PREVIEW -->
                <div id="create_selected_chips" class="flex flex-wrap gap-1.5 mt-2"></div>
            </div>

            <div>
                <label for="create_deskripsi" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                    Deskripsi / Ketentuan Paket
                </label>
                <textarea name="deskripsi" id="create_deskripsi" rows="3" maxlength="2000"
                    placeholder="Contoh: Berlaku durasi maksimal 12 jam, termasuk teknisi standby..."
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2.5 flex-shrink-0">
                <button type="button" onclick="closeCreateModal()"
                    class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs md:text-sm font-semibold transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs md:text-sm font-bold shadow-sm transition">
                    Simpan Paket
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL: EDIT PAKET PEMINJAMAN -->
<!-- ============================================================== -->
<div id="modalEdit" class="fixed inset-0 !m-0 z-[100] hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-3xl w-full max-w-2xl shadow-2xl border border-slate-100 overflow-hidden transform transition-all scale-95 duration-200 max-h-[90vh] flex flex-col" id="modalEditBox">
        <!-- HEADER -->
        <div class="p-5 md:p-6 pb-2 md:pb-3 bg-white flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg border border-amber-100/80 shadow-xs flex-shrink-0">
                    <i class="fa-regular fa-pen-to-square"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base md:text-lg tracking-tight">Edit Paket Peminjaman</h3>
                    <p class="text-slate-500 text-xs mt-0.5">Perbarui kategori, tarif, dan fasilitas paket aula</p>
                </div>
            </div>
            <button type="button" onclick="closeEditModal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition" title="Tutup Modal">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- FORM BODY -->
        <form id="formEdit" method="POST" class="p-5 md:p-6 space-y-4 overflow-y-auto">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="edit_kategori" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                        Kategori Paket <span class="text-red-500">*</span>
                    </label>
                    <select name="kategori" id="edit_kategori" required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition">
                        <option value="unggulan">Unggulan</option>
                        <option value="terjangkau">Terjangkau</option>
                        <option value="standar 1">Standar 1</option>
                        <option value="standar 2">Standar 2</option>
                        <option value="standar 3">Standar 3</option>
                    </select>
                </div>

                <div>
                    <label for="edit_nama_paket" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                        Nama / Judul Paket (Opsional)
                    </label>
                    <input type="text" name="nama_paket" id="edit_nama_paket" maxlength="150"
                        placeholder="Contoh: Paket Wedding Full Service"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="edit_harga" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                        Harga Sewa (Rp) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center font-bold text-slate-500 text-xs">
                            Rp
                        </span>
                        <input type="number" name="harga" id="edit_harga" required min="0" step="1000"
                            class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition">
                    </div>
                </div>

                <div>
                    <label for="edit_harga_dp" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                        Harga Deposit (DP) (Rp)
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center font-bold text-slate-500 text-xs">
                            Rp
                        </span>
                        <input type="number" name="harga_dp" id="edit_harga_dp" min="0" step="1000"
                            placeholder="Contoh: 2000000"
                            class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition">
                    </div>
                </div>
            </div>

            <!-- MULTI-SELECT DROPDOWN FASILITAS EDIT -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide">
                        Fasilitas yang Termasuk <span class="text-red-500">*</span>
                    </label>
                    <span id="edit_selected_count" class="text-[11px] text-amber-600 font-bold">0 dipilih</span>
                </div>

                <!-- DROPDOWN WRAPPER -->
                <div class="relative" id="edit_dropdown_wrapper">
                    <!-- Dropdown Trigger Button -->
                    <button type="button" onclick="toggleFacilitiesDropdown('edit')"
                        class="w-full flex items-center justify-between px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-700 hover:bg-slate-100/70 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition text-left">
                        <span id="edit_dropdown_placeholder" class="text-slate-500 truncate">
                            Pilih fasilitas aula...
                        </span>
                        <i class="fa-solid fa-chevron-down text-xs text-slate-400 ml-2"></i>
                    </button>

                    <!-- Dropdown Menu Box -->
                    <div id="edit_dropdown_box" class="hidden absolute left-0 right-0 top-full mt-1.5 bg-white border border-slate-200 rounded-2xl shadow-xl z-50 p-3 max-h-60 overflow-y-auto space-y-2">
                        <!-- Search filter inside dropdown -->
                        <div class="relative mb-2">
                            <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center text-slate-400 pointer-events-none">
                                <i class="fa-solid fa-magnifying-glass text-[11px]"></i>
                            </span>
                            <input type="text" id="edit_facility_search" oninput="filterFacilityList('edit')"
                                placeholder="Filter fasilitas..."
                                class="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-1 focus:ring-amber-500">
                        </div>

                        <!-- Facility Checkbox Items -->
                        <div id="edit_facility_items" class="space-y-1">
                            @forelse ($availableFacilities as $facility)
                                <label class="facility-item flex items-center gap-2.5 p-2 rounded-xl hover:bg-slate-50 cursor-pointer transition"
                                    data-title="{{ strtolower($facility->judul) }}">
                                    <input type="checkbox" name="facility_ids[]" value="{{ $facility->id }}"
                                        onchange="updateFacilitySelection('edit')"
                                        class="w-4 h-4 rounded text-amber-600 border-slate-300 focus:ring-amber-500 cursor-pointer facility-checkbox-edit">
                                    <span class="text-xs text-slate-700 font-medium select-none">{{ $facility->judul }}</span>
                                </label>
                            @empty
                                <div class="text-xs text-slate-400 p-2 text-center">
                                    Belum ada data fasilitas.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- SELECTED CHIPS PREVIEW -->
                <div id="edit_selected_chips" class="flex flex-wrap gap-1.5 mt-2"></div>
            </div>

            <div>
                <label for="edit_deskripsi" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                    Deskripsi / Ketentuan Paket
                </label>
                <textarea name="deskripsi" id="edit_deskripsi" rows="3" maxlength="2000"
                    placeholder="Contoh: Ketentuan tambahan paket..."
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2.5 flex-shrink-0">
                <button type="button" onclick="closeEditModal()"
                    class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs md:text-sm font-semibold transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs md:text-sm font-bold shadow-sm transition">
                    Perbarui Paket
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL: HAPUS PAKET PEMINJAMAN -->
<!-- ============================================================== -->
<div id="modalDelete" class="fixed inset-0 !m-0 z-[100] hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl border border-slate-100 overflow-hidden transform transition-all scale-95 duration-200" id="modalDeleteBox">
        <div class="p-6 text-center space-y-4">
            <div class="w-14 h-14 mx-auto rounded-2xl bg-red-50 text-red-600 border border-red-100 flex items-center justify-center text-2xl shadow-xs">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <h3 class="font-extrabold text-slate-900 text-lg tracking-tight">Konfirmasi Hapus Paket</h3>
                <p class="text-xs text-slate-500 mt-1">
                    Apakah Anda yakin ingin menghapus paket peminjaman:
                </p>
                <div id="delete_paket_title" class="font-bold text-slate-800 text-sm mt-2 bg-slate-50 py-2.5 px-3 rounded-xl border border-slate-200">
                    -
                </div>
                <p class="text-[11px] text-red-500 mt-2 font-medium">
                    Tindakan ini juga akan menghapus relasi fasilitas pada paket ini.
                </p>
            </div>

            <form id="formDelete" method="POST" class="pt-2 flex items-center justify-center gap-3">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeDeleteModal()"
                    class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs md:text-sm font-semibold transition">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs md:text-sm font-bold shadow-sm transition">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    // Toggle Custom Dropdown Menu Box
    function toggleFacilitiesDropdown(mode) {
        const box = document.getElementById(`${mode}_dropdown_box`);
        if (box) {
            box.classList.toggle('hidden');
            if (!box.classList.contains('hidden')) {
                const search = document.getElementById(`${mode}_facility_search`);
                if (search) search.focus();
            }
        }
    }

    // Filter search inside dropdown
    function filterFacilityList(mode) {
        const query = document.getElementById(`${mode}_facility_search`).value.toLowerCase();
        const items = document.querySelectorAll(`#${mode}_facility_items .facility-item`);
        items.forEach(item => {
            const title = item.getAttribute('data-title') || '';
            if (title.includes(query)) {
                item.classList.remove('hidden');
            } else {
                item.classList.add('hidden');
            }
        });
    }

    // Update chips & count on selection change
    function updateFacilitySelection(mode) {
        const checkboxes = document.querySelectorAll(`.facility-checkbox-${mode}:checked`);
        const countSpan = document.getElementById(`${mode}_selected_count`);
        const placeholder = document.getElementById(`${mode}_dropdown_placeholder`);
        const chipsContainer = document.getElementById(`${mode}_selected_chips`);

        chipsContainer.innerHTML = '';
        const count = checkboxes.length;

        if (countSpan) countSpan.innerText = `${count} dipilih`;

        if (count === 0) {
            if (placeholder) placeholder.innerText = 'Pilih fasilitas aula untuk paket ini...';
        } else {
            if (placeholder) placeholder.innerText = `${count} fasilitas dipilih`;

            checkboxes.forEach(cb => {
                const label = cb.closest('label');
                const title = label ? label.querySelector('span').innerText : '';

                const chip = document.createElement('span');
                chip.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-brand-700 border border-blue-100';
                chip.innerHTML = `<span>${title}</span><button type="button" class="text-blue-400 hover:text-red-500 font-bold">&times;</button>`;

                chip.querySelector('button').addEventListener('click', (e) => {
                    e.stopPropagation();
                    cb.checked = false;
                    updateFacilitySelection(mode);
                });

                chipsContainer.appendChild(chip);
            });
        }
    }

    // Modal Create Handlers
    function openCreateModal() {
        const modal = document.getElementById('modalCreate');
        const box = document.getElementById('modalCreateBox');
        document.body.classList.add('overflow-hidden');
        modal.classList.remove('hidden');
        setTimeout(() => {
            box.classList.remove('scale-95');
            box.classList.add('scale-100');
            document.getElementById('create_kategori').focus();
        }, 10);
    }

    function closeCreateModal() {
        const modal = document.getElementById('modalCreate');
        const box = document.getElementById('modalCreateBox');
        box.classList.remove('scale-100');
        box.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }, 150);
    }

    // Modal Edit Handlers
    function openEditModal(id, namaPaket, kategori, harga, hargaDp, deskripsi, facilityIds, updateUrl) {
        const modal = document.getElementById('modalEdit');
        const box = document.getElementById('modalEditBox');
        const form = document.getElementById('formEdit');

        form.action = updateUrl;
        document.getElementById('edit_nama_paket').value = namaPaket || '';
        document.getElementById('edit_kategori').value = kategori;
        document.getElementById('edit_harga').value = harga;
        document.getElementById('edit_harga_dp').value = (hargaDp !== null && hargaDp !== undefined) ? hargaDp : '';
        document.getElementById('edit_deskripsi').value = deskripsi || '';

        // Reset and check matching checkboxes
        const selectedIds = Array.isArray(facilityIds) ? facilityIds.map(Number) : [];
        document.querySelectorAll('.facility-checkbox-edit').forEach(cb => {
            cb.checked = selectedIds.includes(Number(cb.value));
        });

        updateFacilitySelection('edit');

        document.body.classList.add('overflow-hidden');
        modal.classList.remove('hidden');
        setTimeout(() => {
            box.classList.remove('scale-95');
            box.classList.add('scale-100');
            document.getElementById('edit_kategori').focus();
        }, 10);
    }

    function closeEditModal() {
        const modal = document.getElementById('modalEdit');
        const box = document.getElementById('modalEditBox');
        box.classList.remove('scale-100');
        box.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }, 150);
    }

    // Modal Delete Handlers
    function openDeleteModal(id, nama, deleteUrl) {
        const modal = document.getElementById('modalDelete');
        const box = document.getElementById('modalDeleteBox');
        const form = document.getElementById('formDelete');

        form.action = deleteUrl;
        document.getElementById('delete_paket_title').innerText = nama;

        document.body.classList.add('overflow-hidden');
        modal.classList.remove('hidden');
        setTimeout(() => {
            box.classList.remove('scale-95');
            box.classList.add('scale-100');
        }, 10);
    }

    function closeDeleteModal() {
        const modal = document.getElementById('modalDelete');
        const box = document.getElementById('modalDeleteBox');
        box.classList.remove('scale-100');
        box.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }, 150);
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeCreateModal();
            closeEditModal();
            closeDeleteModal();
            document.querySelectorAll('#create_dropdown_box, #edit_dropdown_box').forEach(el => el.classList.add('hidden'));
        }
    });

    // Close dropdown on click outside
    document.addEventListener('click', function(event) {
        ['create', 'edit'].forEach(mode => {
            const wrapper = document.getElementById(`${mode}_dropdown_wrapper`);
            const box = document.getElementById(`${mode}_dropdown_box`);
            if (wrapper && box && !wrapper.contains(event.target)) {
                box.classList.add('hidden');
            }
        });
    });

    // Close modal on click outside box
    ['modalCreate', 'modalEdit', 'modalDelete'].forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.addEventListener('click', function(event) {
                if (event.target === modal) {
                    if (modalId === 'modalCreate') closeCreateModal();
                    if (modalId === 'modalEdit') closeEditModal();
                    if (modalId === 'modalDelete') closeDeleteModal();
                }
            });
        }
    });

    // Initialize selections on load
    document.addEventListener('DOMContentLoaded', function() {
        updateFacilitySelection('create');
    });
</script>
@endif
@endpush
