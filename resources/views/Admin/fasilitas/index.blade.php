@extends('Admin.layout.app')

@section('title', 'Manajemen Fasilitas Aula - SMK Negeri 2 Karanganyar')
@section('page_title', 'Fasilitas Aula')

@section('content')
<div class="space-y-6">

    <!-- PAGE HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white rounded-2xl p-5 md:p-6 figma-card-shadow border border-slate-100">
        <div>
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-6 bg-brand-600 rounded-full inline-block"></span>
                <h1 class="text-xl md:text-2xl font-extrabold text-slate-800 tracking-tight">Manajemen Fasilitas Aula</h1>
            </div>
            <p class="text-xs md:text-sm text-slate-500 mt-1 font-medium pl-4.5">
                Kelola daftar fasilitas dan sarana prasarana penunjang aula SMK Negeri 2 Karanganyar.
            </p>
        </div>

        <button type="button" onclick="openCreateModal()"
            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-brand-600 hover:bg-brand-700 active:scale-95 text-white text-xs md:text-sm font-bold rounded-xl shadow-sm transition transform">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Tambah Fasilitas</span>
        </button>
    </div>

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

    @if ($errors->any())
        <div id="alertError" class="bg-red-50 border border-red-200 text-red-800 rounded-2xl p-4 flex items-center justify-between shadow-sm transition-all duration-300">
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
            <button type="button" onclick="document.getElementById('alertError').remove()" class="text-red-500 hover:text-red-700 p-1">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
    @endif

    <!-- SEARCH & CONTROLS -->
    <div class="bg-white rounded-2xl p-4 md:p-5 figma-card-shadow border border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
        <form action="{{ route('admin.fasilitas.index') }}" method="GET" class="w-full sm:max-w-md flex items-center gap-2">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" name="search" value="{{ $search ?? '' }}"
                    placeholder="Cari nama fasilitas..."
                    class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition">
            </div>
            <button type="submit"
                class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white text-xs md:text-sm font-semibold rounded-xl transition shadow-sm">
                Cari
            </button>
            @if (!empty($search))
                <a href="{{ route('admin.fasilitas.index') }}"
                    class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs md:text-sm font-semibold rounded-xl transition"
                    title="Reset Pencarian">
                    Reset
                </a>
            @endif
        </form>

        <div class="text-xs text-slate-500 font-medium">
            Total Fasilitas: <span class="font-bold text-slate-800">{{ $facilities->total() }}</span> data
        </div>
    </div>

    <!-- DATA TABLE CONTAINER -->
    <div class="bg-white rounded-2xl figma-card-shadow border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs md:text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 font-extrabold uppercase tracking-wider text-[11px]">
                        <th class="py-4 px-4 w-14 text-center">No</th>
                        <th class="py-4 px-4">Nama Fasilitas</th>
                        <th class="py-4 px-4">Deskripsi</th>
                        <th class="py-4 px-4 text-center">Paket Terkait</th>
                        <th class="py-4 px-4 text-center">Terakhir Diperbarui</th>
                        <th class="py-4 px-4 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                    @forelse ($facilities as $index => $facility)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3.5 px-4 text-center text-slate-500 font-bold">
                                {{ $facilities->firstItem() + $index }}
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-brand-600 flex items-center justify-center font-bold text-sm flex-shrink-0">
                                        <i class="fa-solid fa-box"></i>
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-800 block text-sm">{{ $facility->judul }}</span>
                                        <span class="text-[11px] text-slate-400">ID: #{{ $facility->id }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-slate-600 max-w-xs md:max-w-md">
                                <div class="line-clamp-2">
                                    {{ $facility->deskripsi ?: '-' }}
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $facility->paket_peminjamans_count > 0 ? 'bg-blue-50 text-brand-700' : 'bg-slate-100 text-slate-500' }}">
                                    <i class="fa-solid fa-boxes-packing text-[10px]"></i>
                                    <span>{{ $facility->paket_peminjamans_count }} Paket</span>
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center text-xs text-slate-500">
                                {{ $facility->updated_at ? $facility->updated_at->format('d M Y, H:i') : '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button"
                                        onclick="openEditModal({{ $facility->id }}, @js($facility->judul), @js($facility->deskripsi), '{{ route('admin.fasilitas.update', $facility->id) }}')"
                                        class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 flex items-center justify-center transition shadow-sm"
                                        title="Edit Fasilitas">
                                        <i class="fa-regular fa-pen-to-square text-xs"></i>
                                    </button>
                                    <button type="button"
                                        onclick="openDeleteModal({{ $facility->id }}, @js($facility->judul), '{{ route('admin.fasilitas.destroy', $facility->id) }}')"
                                        class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center transition shadow-sm"
                                        title="Hapus Fasilitas">
                                        <i class="fa-regular fa-trash-can text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-3">
                                    <div class="w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 text-2xl">
                                        <i class="fa-solid fa-box-open"></i>
                                    </div>
                                    <div class="font-bold text-slate-700 text-base">Tidak ada fasilitas ditemukan</div>
                                    <p class="text-xs text-slate-500 max-w-sm">
                                        @if (!empty($search))
                                            Tidak ditemukan fasilitas dengan kata kunci "<span class="font-semibold text-slate-700">{{ $search }}</span>". Coba gunakan kata kunci lain.
                                        @else
                                            Belum ada data fasilitas yang tersimpan. Klik tombol di bawah untuk menambahkan fasilitas pertama.
                                        @endif
                                    </p>
                                    @if (empty($search))
                                        <button type="button" onclick="openCreateModal()"
                                            class="mt-2 inline-flex items-center gap-2 px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                                            <i class="fa-solid fa-plus text-xs"></i>
                                            <span>Tambah Fasilitas Baru</span>
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
        @if ($facilities->hasPages())
            <div class="p-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 bg-slate-50/50">
                <div class="text-xs text-slate-500">
                    Menampilkan <span class="font-bold text-slate-700">{{ $facilities->firstItem() }}</span> -
                    <span class="font-bold text-slate-700">{{ $facilities->lastItem() }}</span> dari
                    <span class="font-bold text-slate-700">{{ $facilities->total() }}</span> fasilitas
                </div>
                <div>
                    {{ $facilities->links() }}
                </div>
            </div>
        @endif
    </div>

</div>

<!-- ============================================================== -->
<!-- MODAL: TAMBAH FASILITAS -->
<!-- ============================================================== -->
<div id="modalCreate" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl border border-slate-100 overflow-hidden transform transition-all scale-95 duration-200" id="modalCreateBox">
        <div class="p-5 md:p-6 bg-gradient-to-r from-brand-600 to-[#0073c6] text-white flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-white/20 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-plus"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-base md:text-lg">Tambah Fasilitas Baru</h3>
                    <p class="text-blue-100 text-xs">Isi data fasilitas penunjang aula</p>
                </div>
            </div>
            <button type="button" onclick="closeCreateModal()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form action="{{ route('admin.fasilitas.store') }}" method="POST" class="p-5 md:p-6 space-y-4">
            @csrf

            <div>
                <label for="create_judul" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                    Nama / Judul Fasilitas <span class="text-red-500">*</span>
                </label>
                <input type="text" name="judul" id="create_judul" required maxlength="250"
                    value="{{ old('judul') }}"
                    placeholder="Contoh: Sound System 5000 Watt, Proyektor Laser, Kursi Lipat 300 Pcs"
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition">
            </div>

            <div>
                <label for="create_deskripsi" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                    Deskripsi / Spesifikasi Fasilitas
                </label>
                <textarea name="deskripsi" id="create_deskripsi" rows="4" maxlength="2000"
                    placeholder="Jelaskan detail spesifikasi fasilitas aula (kondisi, kelengkapan, dll)..."
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 transition">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeCreateModal()"
                    class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs md:text-sm font-semibold transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs md:text-sm font-bold shadow-sm transition">
                    Simpan Fasilitas
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL: EDIT FASILITAS -->
<!-- ============================================================== -->
<div id="modalEdit" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl border border-slate-100 overflow-hidden transform transition-all scale-95 duration-200" id="modalEditBox">
        <div class="p-5 md:p-6 bg-gradient-to-r from-amber-500 to-amber-600 text-white flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-white/20 flex items-center justify-center text-lg">
                    <i class="fa-regular fa-pen-to-square"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-base md:text-lg">Edit Fasilitas Aula</h3>
                    <p class="text-amber-100 text-xs">Perbarui informasi fasilitas</p>
                </div>
            </div>
            <button type="button" onclick="closeEditModal()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form id="formEdit" method="POST" class="p-5 md:p-6 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="edit_judul" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                    Nama / Judul Fasilitas <span class="text-red-500">*</span>
                </label>
                <input type="text" name="judul" id="edit_judul" required maxlength="250"
                    placeholder="Contoh: Sound System 5000 Watt"
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition">
            </div>

            <div>
                <label for="edit_deskripsi" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                    Deskripsi / Spesifikasi Fasilitas
                </label>
                <textarea name="deskripsi" id="edit_deskripsi" rows="4" maxlength="2000"
                    placeholder="Jelaskan detail spesifikasi fasilitas aula..."
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition"></textarea>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeEditModal()"
                    class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs md:text-sm font-semibold transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs md:text-sm font-bold shadow-sm transition">
                    Perbarui Fasilitas
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL: HAPUS FASILITAS -->
<!-- ============================================================== -->
<div id="modalDelete" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl border border-slate-100 overflow-hidden transform transition-all scale-95 duration-200" id="modalDeleteBox">
        <div class="p-6 text-center space-y-4">
            <div class="w-14 h-14 mx-auto rounded-full bg-red-100 text-red-600 flex items-center justify-center text-2xl">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <h3 class="font-extrabold text-slate-800 text-lg">Konfirmasi Hapus Fasilitas</h3>
                <p class="text-xs text-slate-500 mt-1">
                    Apakah Anda yakin ingin menghapus fasilitas:
                </p>
                <div id="delete_facility_title" class="font-bold text-slate-800 text-sm mt-2 bg-slate-50 py-2 px-3 rounded-xl border border-slate-200">
                    -
                </div>
                <p class="text-[11px] text-red-500 mt-2 font-medium">
                    Tindakan ini tidak dapat dibatalkan.
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

@endsection

@push('scripts')
<script>
    // Modal Create Handlers
    function openCreateModal() {
        const modal = document.getElementById('modalCreate');
        const box = document.getElementById('modalCreateBox');
        modal.classList.remove('hidden');
        setTimeout(() => {
            box.classList.remove('scale-95');
            box.classList.add('scale-100');
            document.getElementById('create_judul').focus();
        }, 10);
    }

    function closeCreateModal() {
        const modal = document.getElementById('modalCreate');
        const box = document.getElementById('modalCreateBox');
        box.classList.remove('scale-100');
        box.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 150);
    }

    // Modal Edit Handlers
    function openEditModal(id, judul, deskripsi, updateUrl) {
        const modal = document.getElementById('modalEdit');
        const box = document.getElementById('modalEditBox');
        const form = document.getElementById('formEdit');

        form.action = updateUrl;
        document.getElementById('edit_judul').value = judul;
        document.getElementById('edit_deskripsi').value = deskripsi || '';

        modal.classList.remove('hidden');
        setTimeout(() => {
            box.classList.remove('scale-95');
            box.classList.add('scale-100');
            document.getElementById('edit_judul').focus();
        }, 10);
    }

    function closeEditModal() {
        const modal = document.getElementById('modalEdit');
        const box = document.getElementById('modalEditBox');
        box.classList.remove('scale-100');
        box.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 150);
    }

    // Modal Delete Handlers
    function openDeleteModal(id, judul, deleteUrl) {
        const modal = document.getElementById('modalDelete');
        const box = document.getElementById('modalDeleteBox');
        const form = document.getElementById('formDelete');

        form.action = deleteUrl;
        document.getElementById('delete_facility_title').innerText = judul;

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
        }, 150);
    }

    // Close modal on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeCreateModal();
            closeEditModal();
            closeDeleteModal();
        }
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
</script>
@endpush
