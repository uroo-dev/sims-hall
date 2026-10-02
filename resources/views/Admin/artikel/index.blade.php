@extends('Admin.layout.app')

@section('title', 'Daftar Artikel - SIMS Sekolah')

@section('content')

    <!-- ALERT NOTIFIKASI -->
    @if(session('success'))
        <div class="mb-5 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs md:text-sm font-semibold flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-circle-check text-sm"></i>
                </div>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 transition cursor-pointer">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>
    @endif

    <!-- STATS OVERVIEW CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">
        @php
            $totalSemua = \App\Models\Artikel::count();
            $totalPublished = \App\Models\Artikel::where('status', 'published')->count();
            $totalDraft = \App\Models\Artikel::where('status', 'draft')->count();
        @endphp

        <!-- TOTAL ARTIKEL -->
        <div class="bg-white rounded-2xl p-5 card-shadow border border-gray-100 flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Artikel</span>
                <div class="text-2xl font-extrabold text-gray-800">{{ $totalSemua }}</div>
                <div class="text-[11px] font-semibold text-slate-500">Semua artikel terdata</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-sky-50 text-[#0073c6] flex items-center justify-center text-xl border border-sky-100">
                <i class="fa-solid fa-newspaper"></i>
            </div>
        </div>

        <!-- PUBLISHED -->
        <div class="bg-white rounded-2xl p-5 card-shadow border border-gray-100 flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Artikel Terbit</span>
                <div class="text-2xl font-extrabold text-emerald-600">{{ $totalPublished }}</div>
                <div class="text-[11px] font-semibold text-emerald-600">Status Published</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl border border-emerald-100">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        <!-- DRAFT -->
        <div class="bg-white rounded-2xl p-5 card-shadow border border-gray-100 flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Artikel Draft</span>
                <div class="text-2xl font-extrabold text-amber-600">{{ $totalDraft }}</div>
                <div class="text-[11px] font-semibold text-amber-600">Belum dipublikasikan</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl border border-amber-100">
                <i class="fa-solid fa-file-pen"></i>
            </div>
        </div>
    </div>

    <!-- MAIN CARD -->
    <div class="bg-white rounded-2xl p-6 card-shadow border border-gray-100/80">
        
        <!-- Header + Tombol Tambah -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-sky-100 text-sky-700 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-newspaper"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 text-base">Daftar Artikel Sekolah</h3>
                </div>
                <p class="text-xs text-gray-500 mt-1">Publikasi berita, warta kegiatan, dan dokumentasi sekolah</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.kategori-artikel.index') }}"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold px-4 py-2.5 rounded-xl transition flex items-center gap-2">
                    <i class="fa-solid fa-tags text-xs"></i> Kelola Kategori
                </a>
                <a href="{{ route('admin.artikel.create') }}"
                    class="bg-[#0073c6] hover:bg-sky-700 text-white text-xs font-semibold px-5 py-2.5 rounded-xl transition shadow-sm hover:shadow-md flex items-center gap-2 cursor-pointer active:scale-95">
                    <i class="fa-solid fa-plus text-xs"></i> Buat Artikel Baru
                </a>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <form action="{{ route('admin.artikel.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 mb-6">
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari judul artikel atau ringkasan..."
                    class="w-full border border-gray-200 rounded-xl pl-10 pr-3.5 py-2.5 text-xs md:text-sm focus:outline-none focus:ring-2 focus:ring-[#0073c6]/20 focus:border-[#0073c6] transition shadow-xs">
            </div>

            <select name="kategori_id" onchange="this.form.submit()"
                class="border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs md:text-sm focus:outline-none focus:ring-2 focus:ring-[#0073c6]/20 focus:border-[#0073c6] text-gray-700 bg-white shadow-xs">
                <option value="">Semua Kategori</option>
                @foreach($kategoris as $kat)
                    <option value="{{ $kat->id }}" {{ $kategoriId == $kat->id ? 'selected' : '' }}>
                        {{ $kat->nama }}
                    </option>
                @endforeach
            </select>

            <select name="status" onchange="this.form.submit()"
                class="border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs md:text-sm focus:outline-none focus:ring-2 focus:ring-[#0073c6]/20 focus:border-[#0073c6] text-gray-700 bg-white shadow-xs">
                <option value="">Semua Status</option>
                <option value="published" {{ $status === 'published' ? 'selected' : '' }}>Published (Terbit)</option>
                <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>Draft (Konsep)</option>
            </select>

            @if($search || $kategoriId || $status)
                <a href="{{ route('admin.artikel.index') }}" class="px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-semibold flex items-center justify-center transition">
                    Reset
                </a>
            @endif
        </form>

        <!-- Table -->
        <div class="overflow-x-auto rounded-xl border border-gray-100">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-200 text-gray-700 font-bold text-xs">
                        <th class="py-3 px-4 w-16">Sampul</th>
                        <th class="py-3 px-4">Judul Artikel</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4">Penulis</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4">Tanggal Terbit</th>
                        <th class="py-3 px-4 text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs text-gray-600">
                    @forelse($artikels as $artikel)
                    <tr class="hover:bg-gray-50/70 transition">
                        <td class="py-3 px-4">
                            <div class="w-12 h-12 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 flex-shrink-0 flex items-center justify-center">
                                @if($artikel->gambar)
                                    <img src="{{ asset('storage/' . $artikel->gambar) }}" alt="{{ $artikel->judul }}" class="w-full h-full object-cover">
                                @else
                                    <i class="fa-solid fa-image text-slate-300 text-base"></i>
                                @endif
                            </div>
                        </td>
                        <td class="py-3.5 px-4 font-semibold text-gray-800 max-w-sm">
                            <a href="{{ route('admin.artikel.show', $artikel->id) }}" class="hover:text-[#0073c6] transition line-clamp-2">
                                {{ $artikel->judul }}
                            </a>
                            <span class="block text-[10px] text-gray-400 font-mono mt-0.5 truncate">
                                {{ $artikel->slug }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-sky-50 text-sky-700 border border-sky-100">
                                {{ $artikel->kategori->nama ?? 'Tanpa Kategori' }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-gray-700 font-medium">
                            {{ $artikel->author->name ?? 'Admin Sekolah' }}
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            @if($artikel->status === 'published')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Published
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    Draft
                                </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-gray-500 font-mono text-[11px]">
                            {{ $artikel->published_at ? $artikel->published_at->format('d M Y H:i') : '-' }}
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('admin.artikel.show', $artikel->id) }}"
                                    class="w-7 h-7 rounded-lg bg-blue-50 text-[#0073c6] hover:bg-blue-100 flex items-center justify-center transition"
                                    title="Lihat Detail">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </a>
                                <a href="{{ route('admin.artikel.edit', $artikel->id) }}"
                                    class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 flex items-center justify-center transition"
                                    title="Edit Artikel">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </a>
                                <button type="button" onclick="openDeleteModal({{ $artikel->id }}, '{{ addslashes($artikel->judul) }}')"
                                    class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 flex items-center justify-center transition cursor-pointer"
                                    title="Hapus Artikel">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-10 text-center text-gray-400">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i class="fa-solid fa-newspaper text-3xl text-gray-300"></i>
                                <p class="text-xs">Belum ada artikel yang ditambahkan.</p>
                                <a href="{{ route('admin.artikel.create') }}" class="text-xs text-[#0073c6] font-bold hover:underline">
                                    + Tambah Artikel Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($artikels->hasPages())
            <div class="mt-5">
                {{ $artikels->links() }}
            </div>
        @endif

    </div>

    <!-- MODAL KONFIRMASI HAPUS -->
    <div id="deleteModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl relative text-center border border-gray-100">
            <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-4 text-xl">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h4 class="font-bold text-gray-900 text-base mb-1">Hapus Artikel?</h4>
            <p class="text-xs text-gray-500 mb-4" id="deleteModalDesc">
                Artikel ini akan dihapus permanen.
            </p>

            <form id="deleteForm" method="POST" class="flex gap-2 justify-center">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeDeleteModal()"
                    class="px-4 py-2.5 text-xs font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2.5 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl transition shadow-xs cursor-pointer active:scale-95">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    function openDeleteModal(id, judul) {
        document.getElementById('deleteForm').action = "{{ url('admin/artikel') }}/" + id;
        document.getElementById('deleteModalDesc').textContent = `Artikel "${judul}" akan dihapus secara permanen beserta lampiran gambar.`;
        document.getElementById('deleteModal').classList.remove('hidden');
    }
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>
@endpush
