@extends('Admin.layout.app')

@section('title', 'Kategori Artikel - SIMS Sekolah')

@section('content')

    <!-- MAIN CARD -->
    <div class="bg-white rounded-2xl p-6 card-shadow border border-gray-100/80">
        
        <!-- Header + Tombol Tambah -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-sky-100 text-[#0073c6] flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-tags"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 text-base">Kategori Artikel</h3>
                </div>
                <p class="text-xs text-gray-500 mt-1">Kelola kategori dan topik pengelompokan artikel berita sekolah</p>
            </div>
            <button onclick="openAddModal()"
                class="bg-[#0073c6] hover:bg-sky-700 text-white text-xs font-semibold px-5 py-2.5 rounded-xl transition shadow-sm hover:shadow-md flex items-center gap-2 cursor-pointer active:scale-95">
                <i class="fa-solid fa-plus text-xs"></i> Tambah Kategori
            </button>
        </div>

        <!-- Search Bar -->
        <div class="flex flex-col sm:flex-row gap-3 mb-6">
            <form action="{{ route('admin.kategori-artikel.index') }}" method="GET" class="relative flex-1 max-w-md">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau deskripsi kategori..."
                    class="w-full border border-gray-200 rounded-xl pl-10 pr-10 py-2.5 text-xs md:text-sm focus:outline-none focus:ring-2 focus:ring-[#0073c6]/20 focus:border-[#0073c6] transition shadow-xs">
                @if($search)
                    <a href="{{ route('admin.kategori-artikel.index') }}" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 text-xs">
                        <i class="fa-solid fa-times"></i>
                    </a>
                @endif
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-xl border border-gray-100">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-200 text-gray-700 font-bold text-xs">
                        <th class="py-3 px-4 w-12 text-center">No</th>
                        <th class="py-3 px-4">Nama Kategori</th>
                        <th class="py-3 px-4">Slug URL</th>
                        <th class="py-3 px-4">Deskripsi</th>
                        <th class="py-3 px-4 text-center">Total Artikel</th>
                        <th class="py-3 px-4 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs text-gray-600">
                    @forelse($kategoris as $index => $kategori)
                    <tr class="hover:bg-gray-50/70 transition">
                        <td class="py-3.5 px-4 text-center font-medium text-gray-500">
                            {{ $kategoris->firstItem() + $index }}
                        </td>
                        <td class="py-3.5 px-4 font-bold text-gray-800">
                            <span class="inline-flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-sky-500"></span>
                                {{ $kategori->nama }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 font-mono text-slate-500 text-[11px]">
                            {{ $kategori->slug }}
                        </td>
                        <td class="py-3.5 px-4 text-gray-500 max-w-xs truncate">
                            {{ $kategori->deskripsi ?: '-' }}
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                {{ $kategori->artikels_count }} Artikel
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <button onclick="openEditModal({{ $kategori->id }}, '{{ addslashes($kategori->nama) }}', '{{ addslashes($kategori->deskripsi ?? '') }}')"
                                    class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 flex items-center justify-center transition cursor-pointer"
                                    title="Edit Kategori">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </button>
                                <button onclick="openDeleteModal({{ $kategori->id }}, '{{ addslashes($kategori->nama) }}', {{ $kategori->artikels_count }})"
                                    class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 flex items-center justify-center transition cursor-pointer"
                                    title="Hapus Kategori">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-400">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i class="fa-solid fa-folder-open text-3xl text-gray-300"></i>
                                <p class="text-xs">Belum ada kategori artikel yang terdaftar.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($kategoris->hasPages())
            <div class="mt-5">
                {{ $kategoris->links() }}
            </div>
        @endif

    </div>

    <!-- MODAL TAMBAH KATEGORI -->
    <div id="addModal" class="hidden fixed inset-0 !m-0 z-[100] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity" role="dialog" aria-modal="true">
        <div id="addModalBox" class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100 transform transition-all scale-95 duration-200">
            <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-5">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-sky-100 text-[#0073c6] flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-plus"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 text-sm md:text-base">Tambah Kategori Artikel</h3>
                </div>
                <button type="button" onclick="closeAddModal()" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition cursor-pointer">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <form action="{{ route('admin.kategori-artikel.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wide">
                        Nama Kategori <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="nama" required maxlength="100"
                        placeholder="Contoh: Berita Sekolah, Prestasi, dsb."
                        class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs md:text-sm focus:outline-none focus:ring-2 focus:ring-[#0073c6]/20 focus:border-[#0073c6] transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wide">
                        Deskripsi (Opsional)
                    </label>
                    <textarea name="deskripsi" rows="3" maxlength="500"
                        placeholder="Keterangan singkat kategori artikel..."
                        class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs md:text-sm focus:outline-none focus:ring-2 focus:ring-[#0073c6]/20 focus:border-[#0073c6] transition"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-gray-100">
                    <button type="button" onclick="closeAddModal()"
                        class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs md:text-sm font-semibold transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-[#0073c6] hover:bg-[#005fa6] text-white text-xs md:text-sm font-bold shadow-md shadow-blue-500/20 active:scale-95 transition flex items-center justify-center gap-2 cursor-pointer">
                        Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT KATEGORI -->
    <div id="editModal" class="hidden fixed inset-0 !m-0 z-[100] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity" role="dialog" aria-modal="true">
        <div id="editModalBox" class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100 transform transition-all scale-95 duration-200">
            <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-5">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 text-sm md:text-base">Edit Kategori Artikel</h3>
                </div>
                <button type="button" onclick="closeEditModal()" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition cursor-pointer">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <form id="editForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wide">
                        Nama Kategori <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="editNama" name="nama" required maxlength="100"
                        class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs md:text-sm focus:outline-none focus:ring-2 focus:ring-[#0073c6]/20 focus:border-[#0073c6] transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wide">
                        Deskripsi (Opsional)
                    </label>
                    <textarea id="editDeskripsi" name="deskripsi" rows="3" maxlength="500"
                        class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs md:text-sm focus:outline-none focus:ring-2 focus:ring-[#0073c6]/20 focus:border-[#0073c6] transition"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-gray-100">
                    <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs md:text-sm font-semibold transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs md:text-sm font-bold shadow-md shadow-amber-500/20 active:scale-95 transition flex items-center justify-center gap-2 cursor-pointer">
                        Perbarui Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL KONFIRMASI HAPUS -->
    <div id="deleteModal" class="hidden fixed inset-0 !m-0 z-[100] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity" role="dialog" aria-modal="true">
        <div id="deleteModalBox" class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border border-slate-100 text-center transform transition-all scale-95 duration-200 space-y-4">
            <div class="w-14 h-14 rounded-2xl bg-red-50 text-red-600 border border-red-100/80 flex items-center justify-center mx-auto text-2xl shadow-xs">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <h4 class="font-extrabold text-slate-900 text-lg tracking-tight">Hapus Kategori?</h4>
                <p class="text-xs md:text-sm text-slate-500 mt-1 leading-relaxed" id="deleteModalDesc">
                    Apakah Anda yakin ingin menghapus kategori ini?
                </p>
            </div>

            <form id="deleteForm" method="POST" class="pt-2 flex items-center justify-center gap-3">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeDeleteModal()"
                    class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs md:text-sm font-semibold transition cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 active:scale-95 text-white text-xs md:text-sm font-bold shadow-md shadow-red-500/20 transition flex items-center justify-center gap-2 cursor-pointer">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    function openAddModal() {
        window.openModal('addModal');
    }
    function closeAddModal() {
        window.closeModal('addModal');
    }

    function openEditModal(id, nama, deskripsi) {
        document.getElementById('editForm').action = "{{ url('admin/kategori-artikel') }}/" + id;
        document.getElementById('editNama').value = nama;
        document.getElementById('editDeskripsi').value = deskripsi;
        window.openModal('editModal');
    }
    function closeEditModal() {
        window.closeModal('editModal');
    }

    function openDeleteModal(id, nama, count) {
        document.getElementById('deleteForm').action = "{{ url('admin/kategori-artikel') }}/" + id;
        let desc = `Kategori "${nama}" akan dihapus permanen.`;
        if (count > 0) {
            desc += ` Terdapat ${count} artikel yang bernaung di kategori ini yang juga akan terhapus.`;
        }
        document.getElementById('deleteModalDesc').textContent = desc;
        window.openModal('deleteModal');
    }
    function closeDeleteModal() {
        window.closeModal('deleteModal');
    }
</script>
@endpush
