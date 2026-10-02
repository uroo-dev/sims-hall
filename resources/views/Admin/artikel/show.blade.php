@extends('Admin.layout.app')

@section('title', $artikel->judul . ' - SIMS Sekolah')

@section('content')

    <!-- BREADCRUMB / ACTION HEADER -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.artikel.index') }}"
                class="w-10 h-10 rounded-xl bg-white border border-gray-200 text-gray-600 hover:text-gray-900 hover:bg-gray-50 flex items-center justify-center transition shadow-xs">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-sky-50 text-sky-700 border border-sky-100">
                        {{ $artikel->kategori->nama ?? 'Umum' }}
                    </span>
                    @if($artikel->status === 'published')
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            Published
                        </span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                            Draft
                        </span>
                    @endif
                </div>
                <h2 class="text-lg font-extrabold text-gray-900 mt-1 line-clamp-1">Pratinjau Artikel</h2>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.artikel.edit', $artikel->id) }}"
                class="bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold px-4 py-2.5 rounded-xl transition shadow-xs flex items-center gap-1.5 active:scale-95">
                <i class="fa-solid fa-pen-to-square text-xs"></i> Edit Artikel
            </a>
            <button type="button" onclick="openDeleteModal()"
                class="bg-rose-50 text-rose-600 hover:bg-rose-100 text-xs font-semibold px-4 py-2.5 rounded-xl transition flex items-center gap-1.5 cursor-pointer">
                <i class="fa-solid fa-trash text-xs"></i> Hapus
            </button>
        </div>
    </div>

    <!-- ARTICLE DETAIL CARD -->
    <div class="bg-white rounded-3xl p-6 sm:p-10 card-shadow border border-gray-100/80 max-w-4xl mx-auto space-y-6">
        
        <!-- Judul & Info Meta -->
        <div class="space-y-3 pb-6 border-b border-gray-100">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 leading-tight">
                {{ $artikel->judul }}
            </h1>

            <div class="flex flex-wrap items-center gap-4 text-xs text-gray-500 font-medium">
                <div class="flex items-center gap-1.5">
                    <i class="fa-solid fa-user-pen text-slate-400"></i>
                    <span>Oleh: <strong class="text-gray-800">{{ $artikel->author->name ?? 'Admin Sekolah' }}</strong></span>
                </div>
                <div class="flex items-center gap-1.5">
                    <i class="fa-regular fa-calendar text-slate-400"></i>
                    <span>{{ $artikel->published_at ? $artikel->published_at->translatedFormat('d F Y H:i') : 'Belum Terbit' }}</span>
                </div>
                <div class="flex items-center gap-1.5 font-mono text-[11px] text-slate-400">
                    <i class="fa-solid fa-link text-slate-300"></i>
                    <span>/{{ $artikel->slug }}</span>
                </div>
            </div>
        </div>

        <!-- Cover Image (jika ada) -->
        @if($artikel->gambar)
            <div class="w-full h-72 sm:h-96 rounded-2xl overflow-hidden bg-slate-100 shadow-sm border border-slate-200">
                <img src="{{ asset('storage/' . $artikel->gambar) }}" alt="{{ $artikel->judul }}" class="w-full h-full object-cover">
            </div>
        @endif

        <!-- Ringkasan / Lead -->
        @if($artikel->ringkasan)
            <div class="p-4 sm:p-5 rounded-2xl bg-sky-50/60 border-l-4 border-[#0073c6] text-slate-700 text-xs sm:text-sm font-medium italic leading-relaxed">
                {{ $artikel->ringkasan }}
            </div>
        @endif

        <!-- Konten Lengkap (WYSIWYG Rendered) -->
        <div class="article-content max-w-none text-sm sm:text-base pt-2">
            {!! $artikel->konten !!}
        </div>

    </div>

    <!-- MODAL KONFIRMASI HAPUS -->
    <div id="deleteModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl relative text-center border border-gray-100">
            <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-4 text-xl">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h4 class="font-bold text-gray-900 text-base mb-1">Hapus Artikel?</h4>
            <p class="text-xs text-gray-500 mb-4">
                Artikel "{{ $artikel->judul }}" akan dihapus secara permanen.
            </p>

            <form action="{{ route('admin.artikel.destroy', $artikel->id) }}" method="POST" class="flex gap-2 justify-center">
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

@push('styles')
<style>
    .article-content {
        line-height: 1.8;
        color: #27272a;
    }
    .article-content p {
        margin-bottom: 1.25rem;
    }
    .article-content h1 {
        font-size: 1.75rem;
        font-weight: 800;
        margin-top: 2rem;
        margin-bottom: 1rem;
        color: #111827;
        line-height: 1.3;
    }
    .article-content h2 {
        font-size: 1.375rem;
        font-weight: 700;
        margin-top: 1.75rem;
        margin-bottom: 0.75rem;
        color: #1f2937;
        line-height: 1.35;
    }
    .article-content h3 {
        font-size: 1.125rem;
        font-weight: 700;
        margin-top: 1.5rem;
        margin-bottom: 0.5rem;
        color: #374151;
    }
    .article-content ul {
        list-style-type: disc;
        margin-left: 1.5rem;
        margin-bottom: 1.25rem;
    }
    .article-content ol {
        list-style-type: decimal;
        margin-left: 1.5rem;
        margin-bottom: 1.25rem;
    }
    .article-content li {
        margin-bottom: 0.375rem;
    }
    .article-content blockquote {
        border-left: 4px solid #0073c6;
        padding-left: 1rem;
        font-style: italic;
        color: #4b5563;
        margin-top: 1.25rem;
        margin-bottom: 1.25rem;
        background-color: #f8fafc;
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
        border-radius: 0 0.5rem 0.5rem 0;
    }
    .article-content pre {
        background-color: #1e293b;
        color: #f8fafc;
        padding: 1rem;
        border-radius: 0.75rem;
        overflow-x: auto;
        font-family: monospace;
        margin-bottom: 1.25rem;
        font-size: 0.875rem;
    }
    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 0.75rem;
        margin-top: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    .article-content a {
        color: #0073c6;
        text-decoration: underline;
        font-weight: 500;
    }
    .article-content a:hover {
        color: #005a9e;
    }
    .article-content strong {
        font-weight: 700;
        color: #111827;
    }
</style>
@endpush

@push('scripts')
<script>
    function openDeleteModal() {
        document.getElementById('deleteModal').classList.remove('hidden');
    }
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>
@endpush
