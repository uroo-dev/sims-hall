@extends('Admin.layout.app')

@section('title', 'Data Tata Tertib | Admin Kesiswaan')

@section('breadcrumb-role', 'Kesiswaan')
@section('breadcrumb-page', 'Data Tata Tertib')

@section('content')
    <div class="space-y-6">


        <!-- FORM TAMBAH TATA TERTIB -->
        <section class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100 max-w-2xl">
            <form method="POST" action="{{ route('admin.kesiswaan.tata-tertib.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Judul</label>
                    <input
                        name="judul"
                        type="text"
                        value="{{ old('judul') }}"
                        placeholder="Contoh: Aturan Seragam, Kehadiran & Jam Belajar, Buku Saku Siswa"
                        required
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-2 focus:ring-brand-600/15"
                    >
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Deskripsi</label>
                    <textarea
                        name="deskripsi"
                        rows="4"
                        placeholder="Tuliskan butir-butir aturan, larangan, atau penjelasan tata tertib..."
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-2 focus:ring-brand-600/15"
                    >{{ old('deskripsi') }}</textarea>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">File PDF Dokumen / Buku Saku (Opsional)</label>
                    <input
                        type="file"
                        name="file_pdf"
                        accept="application/pdf"
                        class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-[#0066C4] hover:file:bg-blue-100"
                    >
                    <p class="text-[11px] text-slate-400">Format: PDF (Maks. 10MB)</p>
                </div>

                <div class="pt-2">
                    <button type="submit" class="px-5 py-2.5 bg-[#0066C4] hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md shadow-blue-500/20 transition transform active:scale-95 flex items-center gap-1.5">
                        <i class="fa-solid fa-plus text-[11px]"></i>
                        <span>Tambah Tata Tertib</span>
                    </button>
                </div>
            </form>
        </section>

        <!-- SEARCH BAR & FILTER -->
        <div class="flex items-center gap-3">
            <form method="GET" action="{{ route('admin.kesiswaan.tata-tertib.index') }}" class="flex items-center gap-2 max-w-md w-full">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari Tata Tertib..."
                        class="block w-full pl-9 pr-4 py-2 bg-white rounded-xl border border-slate-200 text-xs text-slate-900 outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 shadow-sm"
                    >
                </div>
                <button type="submit" class="px-4 py-2 bg-[#0066C4] hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center gap-1.5">
                    <i class="fa-solid fa-filter text-[11px]"></i>
                    <span>Filter</span>
                </button>
                @if ($search !== '')
                    <a href="{{ route('admin.kesiswaan.tata-tertib.index') }}" class="px-3 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl text-xs font-bold transition">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- DAFTAR TATA TERTIB TABLE -->
        <section class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
                <h3 class="text-sm font-extrabold text-slate-800">Daftar Tata Tertib</h3>
                <a href="{{ route('admin.kesiswaan.tata-tertib.export-all-pdf') }}" target="_blank" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-sm inline-flex items-center gap-1.5 w-fit">
                    <i class="fa-solid fa-book"></i>
                    <span>Unduh Buku Saku (Semua) PDF</span>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-slate-400 font-bold border-b border-slate-100">
                            <th class="pb-3 px-3">Judul</th>
                            <th class="pb-3 px-3">Deskripsi</th>
                            <th class="pb-3 px-3 text-center">Convert to PDF / Dokumen</th>
                            <th class="pb-3 px-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($tataTertibs as $item)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3 px-3 font-semibold text-slate-800">{{ $item->judul }}</td>
                                <td class="py-3 px-3 text-slate-500 max-w-md truncate">{{ $item->deskripsi ?? '-' }}</td>
                                <td class="py-3 px-3 text-center">
                                    <div class="inline-flex items-center gap-1.5">
                                        <a href="{{ route('admin.kesiswaan.tata-tertib.pdf', $item->tata_tertibID) }}" target="_blank" class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded-lg text-[10px] font-bold transition inline-flex items-center gap-1 shadow-sm" title="Convert / Unduh PDF Dokumen">
                                            <i class="fa-solid fa-file-pdf"></i> Unduh PDF
                                        </a>
                                        @if ($item->file_pdf)
                                            <a href="{{ $item->filePdfUrl() }}" target="_blank" class="px-2 py-1 bg-blue-50 hover:bg-blue-100 text-[#0066C4] rounded-lg text-[10px] font-bold transition inline-flex items-center gap-1" title="Buka File Lampiran Asli">
                                                <i class="fa-solid fa-paperclip"></i> Lampiran
                                            </a>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3 px-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- VIEW BUTTON -->
                                        <button onclick="openDetailModal({{ json_encode($item) }})" class="text-slate-500 hover:text-blue-600 transition" title="Detail">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>

                                        <!-- EDIT BUTTON -->
                                        <button onclick="openEditModal({{ json_encode($item) }})" class="text-slate-500 hover:text-amber-600 transition" title="Edit">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </button>

                                        <!-- DELETE BUTTON -->
                                        <button onclick="openDeleteModal('{{ route('admin.kesiswaan.tata-tertib.destroy', $item->tata_tertibID) }}', '{{ $item->judul }}')" class="text-red-500 hover:text-red-700 transition" title="Hapus">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-slate-400">Tidak ada data tata tertib.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div class="mt-4">
                {{ $tataTertibs->links() }}
            </div>
        </section>

    </div>

    <!-- MODAL DETAIL -->
    <div id="detailModal" class="fixed inset-0 !m-0 z-[100] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl max-w-lg w-full overflow-hidden shadow-2xl border border-slate-100 relative">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <h4 class="text-sm font-bold text-slate-800">Detail Tata Tertib</h4>
                <button onclick="closeDetailModal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <h3 id="detailJudul" class="text-base font-extrabold text-slate-800 mb-2"></h3>
                    <div id="detailDeskripsi" class="text-xs text-slate-600 leading-relaxed bg-slate-50 p-4 rounded-2xl border border-slate-100 whitespace-pre-line"></div>
                </div>

                <div class="pt-2 space-y-2">
                    <a id="detailGeneratedPdfLink" href="" target="_blank" class="w-full py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-bold flex items-center justify-center gap-2 shadow-sm transition">
                        <i class="fa-solid fa-file-pdf"></i>
                        <span>Unduh Dokumen PDF Resmi</span>
                    </a>
                    <div id="detailFileContainer" class="hidden">
                        <a id="detailFileLink" href="" target="_blank" class="w-full py-2 bg-blue-50 hover:bg-blue-100 text-[#0066C4] rounded-xl text-xs font-bold flex items-center justify-center gap-2 transition">
                            <i class="fa-solid fa-paperclip"></i>
                            <span>Buka Lampiran File Asli</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT -->
    <div id="editModal" class="fixed inset-0 !m-0 z-[100] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl max-w-xl w-full overflow-hidden shadow-2xl border border-slate-100 relative max-h-[90vh] flex flex-col">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <h4 class="text-sm font-bold text-slate-800">Edit Tata Tertib</h4>
                <button onclick="closeEditModal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
            <form id="editForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-4 overflow-y-auto">
                @csrf
                @method('PUT')

                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Judul</label>
                    <input id="editJudul" name="judul" type="text" required class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs text-slate-900 outline-none focus:border-brand-500">
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Deskripsi</label>
                    <textarea id="editDeskripsi" name="deskripsi" rows="5" class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs text-slate-900 outline-none focus:border-brand-500"></textarea>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Ganti File PDF Dokumen (Opsional)</label>
                    <input type="file" name="file_pdf" accept="application/pdf" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-[#0066C4] hover:file:bg-blue-100">
                </div>

                <div class="pt-4 flex justify-end gap-2.5 border-t border-slate-100">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs md:text-sm font-semibold transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#0060ac] hover:bg-[#004f8f] active:scale-95 text-white text-xs md:text-sm font-bold shadow-md shadow-blue-500/20 transition flex items-center gap-2 cursor-pointer">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL DELETE CONFIRMATION -->
    <div id="deleteModal" class="fixed inset-0 !m-0 z-[100] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl max-w-sm w-full p-6 text-center shadow-2xl border border-slate-100 relative">
            <div class="w-14 h-14 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-rose-100 text-2xl shadow-xs">
                <i class="fa-regular fa-trash-can"></i>
            </div>
            <h4 class="text-base font-extrabold text-slate-900 mb-1">Konfirmasi Hapus</h4>
            <p class="text-xs text-slate-500 mb-6">Apakah Anda yakin ingin menghapus tata tertib <span id="deleteItemName" class="font-bold text-slate-700"></span>?</p>
            <form id="deleteForm" method="POST" class="flex justify-center gap-2.5">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeDeleteModal()" class="w-full py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs md:text-sm font-semibold transition cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="w-full py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 active:scale-95 text-white text-xs md:text-sm font-bold shadow-sm transition cursor-pointer">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>

    <script>
        function openDetailModal(item) {
            document.getElementById('detailJudul').textContent = item.judul;
            document.getElementById('detailDeskripsi').textContent = item.deskripsi || '-';
            document.getElementById('detailGeneratedPdfLink').href = `/admin/kesiswaan/tata-tertib/${item.tata_tertibID}/pdf`;

            const fileLink = document.getElementById('detailFileLink');
            const fileContainer = document.getElementById('detailFileContainer');
            if (item.file_pdf) {
                fileLink.href = item.file_pdf.startsWith('http') ? item.file_pdf : `/storage/${item.file_pdf}`;
                fileContainer.classList.remove('hidden');
            } else {
                fileContainer.classList.add('hidden');
            }

            if (window.openModal) window.openModal('detailModal');
            else document.getElementById('detailModal').classList.remove('hidden');
        }

        function closeDetailModal() {
            if (window.closeModal) window.closeModal('detailModal');
            else document.getElementById('detailModal').classList.add('hidden');
        }

        function openEditModal(item) {
            const form = document.getElementById('editForm');
            form.action = `/admin/kesiswaan/tata-tertib/${item.tata_tertibID}`;
            document.getElementById('editJudul').value = item.judul;
            document.getElementById('editDeskripsi').value = item.deskripsi || '';
            if (window.openModal) window.openModal('editModal');
            else document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            if (window.closeModal) window.closeModal('editModal');
            else document.getElementById('editModal').classList.add('hidden');
        }

        function openDeleteModal(actionUrl, name) {
            document.getElementById('deleteForm').action = actionUrl;
            document.getElementById('deleteItemName').textContent = name;
            if (window.openModal) window.openModal('deleteModal');
            else document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            if (window.closeModal) window.closeModal('deleteModal');
            else document.getElementById('deleteModal').classList.add('hidden');
        }
    </script>
@endsection
