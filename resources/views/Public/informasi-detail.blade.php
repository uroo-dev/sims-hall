@extends('Public.layout.app')

@section('title', $artikel->judul . ' - SKANDAKRA News')

@push('styles')
<style>
    .article-body-content {
        line-height: 1.85;
        color: #334155;
        font-size: 1rem;
    }
    .article-body-content p {
        margin-bottom: 1.35rem;
    }
    .article-body-content h1 {
        font-size: 1.875rem;
        font-weight: 800;
        margin-top: 2.25rem;
        margin-bottom: 1rem;
        color: #0f172a;
        line-height: 1.3;
    }
    .article-body-content h2 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-top: 2rem;
        margin-bottom: 0.875rem;
        color: #1e293b;
        line-height: 1.35;
    }
    .article-body-content h3 {
        font-size: 1.25rem;
        font-weight: 700;
        margin-top: 1.75rem;
        margin-bottom: 0.75rem;
        color: #334155;
    }
    .article-body-content ul {
        list-style-type: disc;
        margin-left: 1.75rem;
        margin-bottom: 1.35rem;
    }
    .article-body-content ol {
        list-style-type: decimal;
        margin-left: 1.75rem;
        margin-bottom: 1.35rem;
    }
    .article-body-content li {
        margin-bottom: 0.4rem;
    }
    .article-body-content blockquote {
        border-left: 4px solid #0066C4;
        padding-left: 1.25rem;
        font-style: italic;
        color: #475569;
        margin-top: 1.5rem;
        margin-bottom: 1.5rem;
        background-color: #f8fafc;
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
        border-radius: 0 0.75rem 0.75rem 0;
    }
    .article-body-content pre {
        background-color: #0f172a;
        color: #f8fafc;
        padding: 1.25rem;
        border-radius: 1rem;
        overflow-x: auto;
        font-family: monospace;
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
    }
    .article-body-content img {
        max-width: 100%;
        height: auto;
        border-radius: 1rem;
        margin-top: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px -3px rgba(0, 0, 0, 0.08);
    }
    .article-body-content a {
        color: #0066C4;
        text-decoration: underline;
        font-weight: 600;
    }
    .article-body-content a:hover {
        color: #004385;
    }
    .article-body-content strong {
        font-weight: 700;
        color: #0f172a;
    }
</style>
@endpush

@section('content')

    <!-- BREADCRUMB & HERO HEADER -->
    <section class="bg-white border-b border-slate-200/80 pt-8 pb-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Breadcrumb Navigation -->
            <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6 flex-wrap">
                <a href="{{ url('/') }}" class="hover:text-brand-blue transition-colors flex items-center gap-1">
                    <i class="fa-solid fa-house text-[11px]"></i> Beranda
                </a>
                <i class="fa-solid fa-chevron-right text-[9px] text-slate-300"></i>
                <a href="{{ route('informasi') }}" class="hover:text-brand-blue transition-colors">Artikel SKANDAKRA</a>
                <i class="fa-solid fa-chevron-right text-[9px] text-slate-300"></i>
                <span class="text-slate-800 font-semibold truncate max-w-xs sm:max-w-sm">{{ $artikel->judul }}</span>
            </nav>

            <div class="max-w-4xl space-y-4">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="bg-brand-lightBlue text-brand-blue text-xs font-extrabold px-3 py-1 rounded-md uppercase tracking-wider border border-blue-200">
                        {{ $artikel->kategori->nama ?? 'Informasi' }}
                    </span>
                    @php
                        $wordCount = str_word_count(strip_tags($artikel->konten));
                        $readingTime = max(1, ceil($wordCount / 200));
                    @endphp
                    <span class="text-xs text-slate-400 font-medium">• {{ $readingTime }} Menit Waktu Baca</span>
                </div>

                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-slate-900 leading-tight tracking-tight">
                    {{ $artikel->judul }}
                </h1>

                <!-- Meta Author Bar -->
                <div class="pt-2 flex items-center gap-4 text-xs text-slate-500 border-t border-slate-100 mt-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-full bg-brand-blue text-white flex items-center justify-center font-bold text-xs shadow-sm uppercase">
                            {{ substr($artikel->author->name ?? 'H', 0, 1) }}
                        </div>
                        <div>
                            <span class="block font-bold text-slate-800">{{ $artikel->author->name ?? 'Humas SKANDAKRA' }}</span>
                            <span class="text-[11px] text-slate-400">Tim Jurnalistik Sekolah</span>
                        </div>
                    </div>
                    <div class="h-6 w-[1px] bg-slate-200"></div>
                    <div>
                        <span class="block font-semibold text-slate-700">Diterbitkan</span>
                        <span class="text-[11px] text-slate-400">
                            {{ $artikel->published_at ? $artikel->published_at->translatedFormat('d F Y') : $artikel->created_at->translatedFormat('d F Y') }}
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- MAIN EDITORIAL CONTENT LAYOUT (12-COLUMN GRID) -->
    <main class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">

                <!-- LEFT COLUMN: ARTICLE BODY (8 COLUMNS) -->
                <article class="lg:col-span-8 bg-white rounded-2xl p-6 sm:p-10 border border-slate-200/80 shadow-sm">

                    <!-- Featured Image Header -->
                    @if($artikel->gambar)
                        <div class="mb-8 rounded-xl overflow-hidden border border-slate-200">
                            <img src="{{ asset('storage/' . $artikel->gambar) }}"
                                alt="{{ $artikel->judul }}"
                                class="w-full h-72 sm:h-96 object-cover">
                            <p class="p-3 bg-slate-50 text-slate-500 text-xs italic border-t flex items-center gap-2">
                                <i class="fa-solid fa-camera text-slate-400"></i>
                                <span>Dokumentasi publikasi resmi SMK Negeri 2 Karanganyar.</span>
                            </p>
                        </div>
                    @endif

                    <!-- Ringkasan / Lead paragraph -->
                    @if($artikel->ringkasan)
                        <div class="mb-6 p-4 sm:p-5 rounded-2xl bg-blue-50/70 border-l-4 border-brand-blue text-slate-700 font-medium text-sm sm:text-base leading-relaxed italic">
                            {{ $artikel->ringkasan }}
                        </div>
                    @endif

                    <!-- Article Body Content (HTML WYSIWYG) -->
                    <div class="article-body-content">
                        {!! $artikel->konten !!}
                    </div>

                    <!-- Article Tags -->
                    <div class="mt-8 pt-6 border-t border-slate-100 flex items-center gap-2 flex-wrap">
                        <span class="text-xs font-bold text-slate-400 mr-2"><i class="fa-solid fa-tags"></i> TOPIK:</span>
                        <a href="{{ route('informasi', ['kategori' => $artikel->kategori->slug ?? '']) }}" class="text-xs font-semibold bg-slate-100 text-slate-600 px-3 py-1 rounded-md hover:bg-brand-blue hover:text-white transition-colors">
                            {{ $artikel->kategori->nama ?? 'Informasi' }}
                        </a>
                        <span class="text-xs font-semibold bg-slate-100 text-slate-600 px-3 py-1 rounded-md">
                            SKANDAKRA
                        </span>
                        <span class="text-xs font-semibold bg-slate-100 text-slate-600 px-3 py-1 rounded-md">
                            SMKN 2 Karanganyar
                        </span>
                    </div>

                    <!-- ============================================================ -->
                    <!-- SHARE & COPY LINK SECTION -->
                    <!-- ============================================================ -->
                    <div class="mt-10 pt-8 border-t border-slate-200">
                        <div class="bg-gradient-to-br from-slate-50 to-blue-50/40 border border-slate-200/80 rounded-xl p-6">
                            
                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-5">
                                <div>
                                    <h4 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                                        <i class="fa-solid fa-share-nodes text-brand-blue"></i> Bagikan Artikel Ini
                                    </h4>
                                    <p class="text-slate-500 text-xs mt-0.5">Sebarkan informasi resmi dari SMK Negeri 2 Karanganyar ke media sosial Anda.</p>
                                </div>
                            </div>

                            <!-- Social Buttons Row -->
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
                                
                                <!-- WhatsApp -->
                                <a id="share-wa" href="#" target="_blank" rel="noopener noreferrer"
                                    class="bg-[#25D366] hover:bg-[#1ebf59] text-white font-bold text-xs py-2.5 px-3 rounded-lg flex items-center justify-center gap-2 transition-colors shadow-sm">
                                    <i class="fa-brands fa-whatsapp text-base"></i> WhatsApp
                                </a>

                                <!-- Telegram -->
                                <a id="share-telegram" href="#" target="_blank" rel="noopener noreferrer"
                                    class="bg-[#0088cc] hover:bg-[#0077b3] text-white font-bold text-xs py-2.5 px-3 rounded-lg flex items-center justify-center gap-2 transition-colors shadow-sm">
                                    <i class="fa-brands fa-telegram text-base"></i> Telegram
                                </a>

                                <!-- Facebook -->
                                <a id="share-fb" href="#" target="_blank" rel="noopener noreferrer"
                                    class="bg-[#1877F2] hover:bg-[#0d65d9] text-white font-bold text-xs py-2.5 px-3 rounded-lg flex items-center justify-center gap-2 transition-colors shadow-sm">
                                    <i class="fa-brands fa-facebook-f text-sm"></i> Facebook
                                </a>

                                <!-- Twitter / X -->
                                <a id="share-x" href="#" target="_blank" rel="noopener noreferrer"
                                    class="bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs py-2.5 px-3 rounded-lg flex items-center justify-center gap-2 transition-colors shadow-sm">
                                    <i class="fa-brands fa-x-twitter text-sm"></i> X (Twitter)
                                </a>

                            </div>

                            <!-- Interactive Copy Link Input Bar -->
                            <div class="flex items-center gap-2 pt-2 border-t border-slate-200/60">
                                <div class="relative flex-1">
                                    <i class="fa-solid fa-link absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                    <input id="article-url-input" type="text" readonly
                                        class="w-full bg-white border border-slate-300 rounded-lg pl-9 pr-3 py-2 text-xs text-slate-600 focus:outline-none select-all font-mono">
                                </div>
                                <button onclick="copyArticleLink()"
                                    class="bg-brand-blue hover:bg-brand-darkBlue text-white font-bold text-xs px-4 py-2 rounded-lg transition-colors shrink-0 flex items-center gap-1.5 shadow-sm cursor-pointer">
                                    <i class="fa-regular fa-copy"></i>
                                    <span id="copy-btn-text">Salin Tautan</span>
                                </button>
                            </div>

                        </div>
                    </div>

                    <!-- Author Profile Box -->
                    <div class="mt-8 p-6 bg-slate-50 border border-slate-200 rounded-xl flex items-center gap-4">
                        <div class="w-14 h-14 rounded-full bg-brand-blue text-white font-bold text-xl flex items-center justify-center shrink-0 shadow-sm uppercase">
                            {{ substr($artikel->author->name ?? 'H', 0, 1) }}
                        </div>
                        <div>
                            <h5 class="font-bold text-slate-900 text-sm">{{ $artikel->author->name ?? 'Tim Redaksi & Humas SKANDAKRA' }}</h5>
                            <p class="text-slate-500 text-xs mt-1 leading-relaxed">
                                Pusat informasi resmi SMK Negeri 2 Karanganyar. Menyajikan berita kegiatan sekolah, prestasi siswa, dan publikasi vokasi terpercaya.
                            </p>
                        </div>
                    </div>

                </article>

                <!-- RIGHT COLUMN: SIDEBAR (4 COLUMNS) -->
                <aside class="lg:col-span-4 space-y-8 sticky top-28">

                    <!-- Sidebar Card 1: Ringkasan Info Sekolah -->
                    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
                        <h4 class="font-extrabold text-slate-900 text-base mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                            <i class="fa-solid fa-circle-info text-brand-blue"></i> Sekilas Info
                        </h4>
                        <div class="space-y-3 text-xs text-slate-600 leading-relaxed">
                            <p>
                                <strong>Kategori:</strong> {{ $artikel->kategori->nama ?? 'Informasi Umum' }}
                            </p>
                            <p>
                                <strong>Dibaca:</strong> {{ number_format($artikel->views) }} kali
                            </p>
                            <p>
                                <strong>Lokasi:</strong> SMK Negeri 2 Karanganyar
                            </p>
                            <div class="pt-2">
                                <a href="{{ route('informasi') }}" class="text-brand-blue font-bold hover:underline block text-xs">
                                    Lihat Semua Informasi &rarr;
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar Card 2: Artikel Populer / Terbaru Lainnya -->
                    @if($artikelPopulers->count() > 0)
                        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
                            <h4 class="font-extrabold text-slate-900 text-base mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                                <i class="fa-solid fa-fire text-amber-500"></i> Artikel Lainnya
                            </h4>

                            <div class="space-y-4">
                                @foreach($artikelPopulers as $index => $populer)
                                    <a href="{{ route('informasi.show', $populer->slug) }}" class="flex gap-3 group">
                                        <span class="text-lg font-extrabold text-slate-300 group-hover:text-brand-blue transition-colors">
                                            {{ sprintf('%02d', $index + 1) }}
                                        </span>
                                        <div>
                                            <h5 class="text-xs font-bold text-slate-800 group-hover:text-brand-blue transition-colors line-clamp-2 leading-snug">
                                                {{ $populer->judul }}
                                            </h5>
                                            <span class="text-[10px] text-slate-400 mt-1 block">
                                                {{ $populer->published_at ? $populer->published_at->translatedFormat('d M Y') : $populer->created_at->translatedFormat('d M Y') }}
                                            </span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Sidebar Card 3: Banner PPDB / Call to Action -->
                    @php
                        $ppdbMaster = $ppdbMaster ?? \App\Models\Ppdb_master::first();
                    @endphp
                    <div class="bg-gradient-to-br from-brand-blue to-brand-darkBlue text-white rounded-2xl p-6 shadow-md relative overflow-hidden">
                        <div class="relative z-10 space-y-3">
                            <span class="text-[10px] uppercase tracking-wider font-extrabold bg-white/20 px-2.5 py-1 rounded">
                                PPDB SKANDAKRA
                            </span>
                            <h4 class="font-extrabold text-lg leading-snug">
                                {{ $ppdbMaster?->judul ?: 'Ingin Menjadi Bagian dari SKANDAKRA?' }}
                            </h4>
                            <p class="text-xs text-blue-100 leading-relaxed">
                                {{ $ppdbMaster?->deskripsi ?: 'Daftarkan diri Anda pada Penerimaan Peserta Didik Baru SMKN 2 Karanganyar.' }}
                            </p>
                            <a href="{{ route('ppdb') }}" class="inline-block bg-white text-brand-blue font-bold text-xs px-4 py-2.5 rounded-lg shadow hover:bg-slate-100 transition-colors mt-2">
                                Info PPDB Selengkapnya
                            </a>
                        </div>
                    </div>

                </aside>

            </div>
        </div>
    </main>

    <!-- TOAST NOTIFICATION FOR COPY LINK -->
    <div id="toast-notification" class="fixed bottom-6 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-xs font-bold px-5 py-3 rounded-full shadow-2xl z-50 flex items-center gap-2 transition-all duration-300 opacity-0 pointer-events-none translate-y-4">
        <i class="fa-solid fa-circle-check text-emerald-400 text-base"></i>
        <span>Tautan artikel berhasil disalin ke clipboard!</span>
    </div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Fill URL input bar
        const urlInput = document.getElementById('article-url-input');
        if (urlInput) {
            urlInput.value = window.location.href;
        }

        // Setup Auto Share Links Dynamically
        setupShareLinks();
    });

    // Setup URL & Share Links
    function setupShareLinks() {
        const currentUrl = encodeURIComponent(window.location.href);
        const articleTitle = encodeURIComponent(document.title);

        const wa = document.getElementById('share-wa');
        const tg = document.getElementById('share-telegram');
        const fb = document.getElementById('share-fb');
        const x = document.getElementById('share-x');

        if (wa) wa.href = `https://api.whatsapp.com/send?text=${articleTitle}%20${currentUrl}`;
        if (tg) tg.href = `https://t.me/share/url?url=${currentUrl}&text=${articleTitle}`;
        if (fb) fb.href = `https://www.facebook.com/sharer/sharer.php?u=${currentUrl}`;
        if (x) x.href = `https://twitter.com/intent/tweet?url=${currentUrl}&text=${articleTitle}`;
    }

    // Copy Article Link Function
    function copyArticleLink() {
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(() => {
            showToast();
            const btnText = document.getElementById('copy-btn-text');
            if (btnText) {
                btnText.innerText = "Tersalin!";
                setTimeout(() => {
                    btnText.innerText = "Salin Tautan";
                }, 2000);
            }
        }).catch(err => {
            console.error("Gagal menyalin tautan: ", err);
        });
    }

    // Toast Notification Logic
    function showToast() {
        const toast = document.getElementById('toast-notification');
        if (!toast) return;
        toast.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-4');
        toast.classList.add('opacity-100', 'translate-y-0');

        setTimeout(() => {
            toast.classList.remove('opacity-100', 'translate-y-0');
            toast.classList.add('opacity-0', 'pointer-events-none', 'translate-y-4');
        }, 3000);
    }
</script>
@endpush
