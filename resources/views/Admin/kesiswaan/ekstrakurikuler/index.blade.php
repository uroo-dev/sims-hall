@extends('Admin.layout.app')

@section('title', 'Data Ekstrakulikuler | Admin Kesiswaan')

@section('breadcrumb-role', 'Kesiswaan')
@section('breadcrumb-page', 'Data Ekstrakulikuler')

@section('content')
    <div class="space-y-6">


        <!-- FORM TAMBAH EKSTRAKURIKULER -->
        <section class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100">
            <form method="POST" action="{{ route('admin.kesiswaan.ekstrakurikuler.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
                    
                    <!-- KOLOM 1: NAMA, SEKOLAH, LOGO -->
                    <div class="lg:col-span-4 space-y-3.5">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700">Nama Ekstrakulikuler</label>
                            <input
                                name="nama"
                                type="text"
                                value="{{ old('nama') }}"
                                placeholder="Contoh: Organisasi OSIS"
                                required
                                class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-2 focus:ring-brand-600/15"
                            >
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700">Sekolah / Instansi</label>
                            <input
                                name="sekolah"
                                type="text"
                                value="{{ old('sekolah', 'SMKN 2 Karanganyar') }}"
                                placeholder="SMKN 2 Karanganyar"
                                class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-2 focus:ring-brand-600/15"
                            >
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700">Logo Ekstrakulikuler</label>
                            <div class="relative rounded-xl border-2 border-dashed border-slate-200 bg-slate-50 p-4 text-center hover:border-brand-400 transition cursor-pointer flex flex-col items-center justify-center min-h-[110px]">
                                <i class="fa-regular fa-image text-slate-400 text-2xl mb-1"></i>
                                <span class="text-[11px] text-slate-500 font-medium logo-placeholder">Pilih file logo...</span>
                                <input type="file" name="logo" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer" onchange="previewFileName(this, '.logo-placeholder')">
                            </div>
                        </div>
                    </div>

                    <!-- KOLOM 2: DESKRIPSI -->
                    <div class="lg:col-span-5 space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700">Deskripsi</label>
                        <textarea
                            name="deskripsi"
                            rows="9"
                            placeholder="Tuliskan deskripsi lengkap mengenai kegiatan, visi, dan misi ekstrakulikuler..."
                            class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-2 focus:ring-brand-600/15"
                        >{{ old('deskripsi') }}</textarea>
                    </div>

                    <!-- KOLOM 3: DOKUMENTASI & SUBMIT BUTTON -->
                    <div class="lg:col-span-3 space-y-3.5 flex flex-col justify-between h-full">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700">Dokumentasi</label>
                            <div class="relative rounded-xl border-2 border-dashed border-slate-200 bg-slate-50 p-4 text-center hover:border-brand-400 transition cursor-pointer flex flex-col items-center justify-center min-h-[170px]">
                                <i class="fa-solid fa-cloud-arrow-up text-slate-400 text-3xl mb-2"></i>
                                <span class="text-[11px] text-slate-500 font-medium doc-placeholder">Upload Foto Kegiatan</span>
                                <input type="file" name="dokumentasi" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer" onchange="previewFileName(this, '.doc-placeholder')">
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full py-2.5 bg-[#0066C4] hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md shadow-blue-500/20 transition transform active:scale-95 flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-plus text-[11px]"></i>
                                <span>Tambah Ekstrakurikuler</span>
                            </button>
                        </div>
                    </div>

                </div>
            </form>
        </section>

        <!-- SEARCH BAR & FILTER -->
        <div class="flex items-center gap-3">
            <form method="GET" action="{{ route('admin.kesiswaan.ekstrakurikuler.index') }}" class="flex items-center gap-2 max-w-md w-full">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari Ekstrakulikuler..."
                        class="block w-full pl-9 pr-4 py-2 bg-white rounded-xl border border-slate-200 text-xs text-slate-900 outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 shadow-sm"
                    >
                </div>
                <button type="submit" class="px-4 py-2 bg-[#0066C4] hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center gap-1.5">
                    <i class="fa-solid fa-filter text-[11px]"></i>
                    <span>Filter</span>
                </button>
                @if ($search !== '')
                    <a href="{{ route('admin.kesiswaan.ekstrakurikuler.index') }}" class="px-3 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl text-xs font-bold transition">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- DAFTAR EKSTRAKURIKULER TABLE -->
        <section class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100">
            <h3 class="text-sm font-extrabold text-slate-800 mb-4">Daftar Ekstrakulikuler</h3>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-slate-400 font-bold border-b border-slate-100">
                            <th class="pb-3 px-3">Nama Ekstrakulikuler</th>
                            <th class="pb-3 px-3">Sekolah / Instansi</th>
                            <th class="pb-3 px-3">Deskripsi</th>
                            <th class="pb-3 px-3 text-center">Logo</th>
                            <th class="pb-3 px-3 text-center">Dokumentasi</th>
                            <th class="pb-3 px-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($ekstrakurikulers as $item)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3 px-3 font-semibold text-slate-800">{{ $item->nama }}</td>
                                <td class="py-3 px-3 text-slate-600">{{ $item->sekolah }}</td>
                                <td class="py-3 px-3 text-slate-500 max-w-xs truncate">{{ $item->deskripsi ?? '-' }}</td>
                                <td class="py-3 px-3 text-center">
                                    @if ($item->logoUrl())
                                        <button onclick="openMediaModal('{{ $item->logoUrl() }}', 'Logo: {{ $item->nama }}')" class="px-2.5 py-1 bg-blue-50 text-[#0066C4] border border-blue-200 rounded-lg text-[10px] font-bold hover:bg-blue-100 transition inline-flex items-center gap-1">
                                            <i class="fa-solid fa-file-image"></i> Lihat File
                                        </button>
                                    @else
                                        <span class="text-slate-400 text-[10px]">-</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-center">
                                    @if ($item->dokumentasiUrl())
                                        <button onclick="openMediaModal('{{ $item->dokumentasiUrl() }}', 'Dokumentasi: {{ $item->nama }}')" class="px-2.5 py-1 bg-blue-50 text-[#0066C4] border border-blue-200 rounded-lg text-[10px] font-bold hover:bg-blue-100 transition inline-flex items-center gap-1">
                                            <i class="fa-solid fa-file-image"></i> Lihat File
                                        </button>
                                    @else
                                        <span class="text-slate-400 text-[10px]">-</span>
                                    @endif
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
                                        <button onclick="openDeleteModal('{{ route('admin.kesiswaan.ekstrakurikuler.destroy', $item->ekstrakurikulerID) }}', '{{ $item->nama }}')" class="text-red-500 hover:text-red-700 transition" title="Hapus">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 text-center text-slate-400">Tidak ada data ekstrakulikuler.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div class="mt-4">
                {{ $ekstrakurikulers->links() }}
            </div>
        </section>

    </div>

    <!-- MODAL MEDIA PREVIEW -->
    <div id="mediaModal" class="fixed inset-0 !m-0 z-[100] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl max-w-lg w-full overflow-hidden shadow-2xl border border-slate-100 relative">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                <h4 id="mediaTitle" class="text-xs font-bold text-slate-800">Preview Media</h4>
                <button onclick="closeMediaModal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
            <div class="p-4 flex items-center justify-center bg-slate-50 min-h-[250px]">
                <img id="mediaImage" src="" alt="Media" class="max-h-[70vh] object-contain rounded-2xl">
            </div>
        </div>
    </div>

    <!-- MODAL DETAIL -->
    <div id="detailModal" class="fixed inset-0 !m-0 z-[100] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl max-w-xl w-full overflow-hidden shadow-2xl border border-slate-100 relative">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <h4 class="text-sm font-bold text-slate-800">Detail Ekstrakurikuler</h4>
                <button onclick="closeDetailModal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-center gap-4">
                    <div id="detailLogoContainer" class="w-16 h-16 rounded-2xl border border-slate-200 p-1 flex items-center justify-center bg-white shrink-0">
                        <img id="detailLogo" src="" alt="Logo" class="max-h-full max-w-full object-contain">
                    </div>
                    <div>
                        <h3 id="detailNama" class="text-base font-extrabold text-slate-800"></h3>
                        <p id="detailSekolah" class="text-xs text-slate-500 font-medium"></p>
                    </div>
                </div>

                <div>
                    <h5 class="text-xs font-bold text-slate-700 mb-1">Deskripsi</h5>
                    <p id="detailDeskripsi" class="text-xs text-slate-600 leading-relaxed bg-slate-50 p-3.5 rounded-2xl border border-slate-100"></p>
                </div>

                <div id="detailDokumentasiContainer">
                    <h5 class="text-xs font-bold text-slate-700 mb-1">Dokumentasi</h5>
                    <img id="detailDokumentasi" src="" alt="Dokumentasi" class="w-full h-44 object-cover rounded-2xl border border-slate-200">
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT -->
    <div id="editModal" class="fixed inset-0 !m-0 z-[100] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl max-w-2xl w-full overflow-hidden shadow-2xl border border-slate-100 relative max-h-[90vh] flex flex-col">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <h4 class="text-sm font-bold text-slate-800">Edit Ekstrakulikuler</h4>
                <button onclick="closeEditModal()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
            <form id="editForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-4 overflow-y-auto">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700">Nama Ekstrakulikuler</label>
                        <input id="editNama" name="nama" type="text" required class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs text-slate-900 outline-none focus:border-brand-500">
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700">Sekolah / Instansi</label>
                        <input id="editSekolah" name="sekolah" type="text" class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs text-slate-900 outline-none focus:border-brand-500">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Deskripsi</label>
                    <textarea id="editDeskripsi" name="deskripsi" rows="5" class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs text-slate-900 outline-none focus:border-brand-500"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700">Ganti Logo (Opsional)</label>
                        <input type="file" name="logo" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-brand-700 hover:file:bg-blue-100">
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700">Ganti Dokumentasi (Opsional)</label>
                        <input type="file" name="dokumentasi" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-brand-700 hover:file:bg-blue-100">
                    </div>
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
            <p class="text-xs text-slate-500 mb-6">Apakah Anda yakin ingin menghapus ekstrakulikuler <span id="deleteItemName" class="font-bold text-slate-700"></span>?</p>
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
        function previewFileName(input, targetSelector) {
            if (input.files && input.files[0]) {
                document.querySelector(targetSelector).textContent = input.files[0].name;
            }
        }

        function openMediaModal(url, title) {
            document.getElementById('mediaImage').src = url;
            document.getElementById('mediaTitle').textContent = title;
            if (window.openModal) window.openModal('mediaModal');
            else document.getElementById('mediaModal').classList.remove('hidden');
        }

        function closeMediaModal() {
            if (window.closeModal) window.closeModal('mediaModal');
            else document.getElementById('mediaModal').classList.add('hidden');
        }

        function openDetailModal(item) {
            document.getElementById('detailNama').textContent = item.nama;
            document.getElementById('detailSekolah').textContent = item.sekolah || 'SMKN 2 Karanganyar';
            document.getElementById('detailDeskripsi').textContent = item.deskripsi || '-';

            const logoImg = document.getElementById('detailLogo');
            if (item.logo) {
                logoImg.src = item.logo.startsWith('http') ? item.logo : `/storage/${item.logo}`;
                document.getElementById('detailLogoContainer').classList.remove('hidden');
            } else {
                document.getElementById('detailLogoContainer').classList.add('hidden');
            }

            const docImg = document.getElementById('detailDokumentasi');
            if (item.dokumentasi) {
                docImg.src = item.dokumentasi.startsWith('http') ? item.dokumentasi : `/storage/${item.dokumentasi}`;
                document.getElementById('detailDokumentasiContainer').classList.remove('hidden');
            } else {
                document.getElementById('detailDokumentasiContainer').classList.add('hidden');
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
            form.action = `/admin/kesiswaan/ekstrakurikuler/${item.ekstrakurikulerID}`;
            document.getElementById('editNama').value = item.nama;
            document.getElementById('editSekolah').value = item.sekolah || 'SMKN 2 Karanganyar';
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
