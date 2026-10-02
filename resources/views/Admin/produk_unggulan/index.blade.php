@extends('Admin.layout.app')

@section('title', 'Dashboard Produk Unggulan | Admin')

@section('breadcrumb-role', 'Produk Unggulan')
@section('breadcrumb-page', 'Dashboard PU')

@section('content')
    <div class="space-y-6">

        @if (session('success'))
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-3 text-sm font-semibold text-emerald-700 flex items-center gap-2.5" role="status">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-2xl border border-red-100 bg-red-50 px-5 py-3 text-sm font-semibold text-red-700 flex items-center gap-2.5" role="alert">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <!-- CARD: JUDUL, DESKRIPSI & DOKUMENTASI -->
        <section class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100">
            <form method="POST" action="{{ route('produk-unggulan.update') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="flex flex-wrap items-center justify-between gap-3">
                    <h2 class="text-sm font-extrabold text-brand-600 uppercase tracking-wider">
                        Produk Unggulan SMKN 2 Karanganyar
                    </h2>
                    <a href="{{ route('produk.index') }}" class="px-3.5 py-1 bg-brand-600 hover:bg-brand-700 text-white rounded-full text-[11px] font-semibold flex items-center space-x-1.5 transition-colors shadow-sm">
                        <i class="fa-solid fa-basket-shopping text-[10px]"></i>
                        <span>Kelola Produk</span>
                    </a>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 space-y-4">
                        <div class="space-y-2">
                            <label for="judul" class="block text-xs font-bold text-slate-700">Judul</label>
                            <input
                                id="judul"
                                name="judul"
                                type="text"
                                value="{{ old('judul', $produkUnggulan->judul) }}"
                                placeholder="Produk Unggulan SMK N 2 Karanganyar"
                                maxlength="255"
                                required
                                class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-2 focus:ring-brand-600/15"
                            >
                            @error('judul')
                                <p class="text-[11px] font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="deskripsi" class="block text-xs font-bold text-slate-700">Deskripsi Singkat</label>
                            <textarea
                                id="deskripsi"
                                name="deskripsi"
                                rows="5"
                                placeholder="Di SMK N 2 Karanganyar, siswa tidak hanya mendidik namun juga menciptakan inovasi melalui kolaborasi berbasis industri dunia kerja, mulai dari mengelola karya-karya siswa yang kompetitif serta siap menghadapi tantangan global."
                                required
                                class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-2 focus:ring-brand-600/15"
                            >{{ old('deskripsi', $produkUnggulan->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <p class="text-[11px] font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <p class="block text-xs font-bold text-slate-700">Dokumentasi</p>

                        <div class="grid grid-cols-2 gap-2">
                            @forelse ($dokumentasiUrl as $index => $url)
                                <label class="group relative block overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                    <img src="{{ $url }}" alt="Dokumentasi produk unggulan" class="w-full h-20 object-cover">
                                    <span class="absolute inset-x-0 bottom-0 bg-slate-900/70 text-white text-[9px] font-semibold px-2 py-1 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition">
                                        <input type="checkbox" name="hapus_dokumentasi[]" value="{{ $produkUnggulan->dokumentasi_list[$index] }}" class="w-3 h-3 rounded border-slate-300 text-brand-600 focus:ring-brand-600/30">
                                        <span>Hapus</span>
                                    </span>
                                </label>
                            @empty
                                @for ($i = 0; $i < 4; $i++)
                                    <div class="w-full h-20 rounded-xl border border-slate-200 bg-slate-50"></div>
                                @endfor
                            @endforelse
                        </div>

                        <label for="dokumentasi" class="mt-3 flex cursor-pointer flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-slate-200 px-4 py-5 text-center text-xs font-semibold text-slate-500 transition hover:border-brand-500 hover:text-brand-600">
                            <i class="fa-solid fa-cloud-arrow-up text-xl"></i>
                            <span>Unggah foto (maks. 4 gambar, 1 MB)</span>
                            <input id="dokumentasi" name="dokumentasi[]" type="file" accept="image/jpeg,image/png,image/webp" multiple class="sr-only">
                        </label>

                        <div id="dokumentasi-preview" class="hidden mt-3">
                            <p class="text-[11px] font-semibold text-slate-600 mb-1.5">Preview:</p>
                            <div id="dokumentasi-preview-grid" class="grid grid-cols-2 gap-2"></div>
                        </div>

                        @error('dokumentasi')
                            <p class="text-[11px] font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                        @error('dokumentasi.*')
                            <p class="text-[11px] font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-end gap-3">
                    @if ($produkUnggulan->exists)
                        <button type="button" onclick="document.getElementById('reset-produk-unggulan').submit()" class="px-5 py-2 bg-white hover:bg-red-50 text-red-600 border border-red-100 rounded-full text-xs font-bold transition-colors">
                            Reset Data
                        </button>
                    @endif

                    <button type="submit" class="px-5 py-2 bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold rounded-full shadow transition-colors">
                        Simpan Perubahan
                    </button>
                </div>
            </form>

            @if ($produkUnggulan->exists)
                <form id="reset-produk-unggulan" method="POST" action="{{ route('produk-unggulan.destroy') }}" class="hidden"
                    onsubmit="return confirm('Hapus data produk unggulan beserta dokumentasinya?')">
                    @csrf
                    @method('DELETE')
                </form>
            @endif
        </section>

        <!-- ROW 2: RINGKASAN DESKRIPSI & JUMLAH PRODUK -->
        <section class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xs font-extrabold text-brand-600 uppercase tracking-wider">Deskripsi Singkat</h2>
                        <span class="px-3 py-1 bg-brand-50 text-brand-600 rounded-full text-[10px] font-semibold">
                            Landing Page
                        </span>
                    </div>
                    <p class="text-sm leading-relaxed text-slate-600">
                        {{ $produkUnggulan->deskripsi ?? 'Belum ada deskripsi. Isi kolom deskripsi singkat lalu simpan perubahan.' }}
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xs font-extrabold text-brand-600 uppercase tracking-wider">Jumlah Produk</h2>
                        <a href="{{ route('produk.index') }}" class="px-3 py-1 bg-brand-600 text-white rounded-full text-[10px] font-medium flex items-center gap-1 hover:bg-brand-700 transition">
                            <i class="fa-solid fa-eye text-[9px]"></i> Selengkapnya
                        </a>
                    </div>

                    <div class="flex items-end justify-between gap-4 mb-5">
                        <p class="text-4xl font-extrabold text-brand-600 leading-none">{{ $totalProduk }}</p>
                        <p class="text-[11px] font-semibold text-slate-500 text-right">Total produk<br>unggulan</p>
                    </div>

                    <div class="space-y-3">
                        @php $maksimum = max(1, (int) $produkPerJurusan->max('produk_count')); @endphp

                        @forelse ($produkPerJurusan as $item)
                            <div class="space-y-1">
                                <div class="flex items-center justify-between text-[11px] font-semibold text-slate-600">
                                    <span>{{ $item->nama }}</span>
                                    <span class="text-slate-500">{{ $item->produk_count }}</span>
                                </div>
                                <div class="h-2 w-full rounded-full bg-slate-100 overflow-hidden">
                                    <div class="h-full rounded-full bg-brand-500" style="width: {{ round($item->produk_count / $maksimum * 100) }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs font-medium text-slate-400">Belum ada data jurusan.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </section>

        <!-- ROW 3: DAFTAR PRODUK UNGGULAN -->
        <section class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-xs font-extrabold text-brand-600 uppercase tracking-wider">Daftar Produk Unggulan</h2>
                <a href="{{ route('produk.index') }}" class="px-3 py-1 bg-brand-600 text-white rounded-full text-[10px] font-medium flex items-center gap-1 hover:bg-brand-700 transition">
                    <i class="fa-solid fa-eye text-[9px]"></i> Kelola Produk
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 text-[11px] uppercase tracking-wider text-slate-400">
                            <th class="pb-3 pr-4 font-semibold">Kode</th>
                            <th class="pb-3 pr-4 font-semibold">Produk</th>
                            <th class="pb-3 pr-4 font-semibold">Jurusan</th>
                            <th class="pb-3 pr-4 font-semibold">Deskripsi</th>
                            <th class="pb-3 font-semibold">Dokumentasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($produkList as $produk)
                            <tr class="text-slate-600">
                                <td class="py-3 pr-4 font-mono text-xs font-semibold text-brand-600 whitespace-nowrap">{{ $produk->kode_produk }}</td>
                                <td class="py-3 pr-4 font-semibold text-slate-800 whitespace-nowrap">{{ $produk->nama }}</td>
                                <td class="py-3 pr-4 text-xs whitespace-nowrap">{{ $produk->jurusan->nama ?? '-' }}</td>
                                <td class="py-3 pr-4 text-xs max-w-xs truncate" title="{{ $produk->deskripsi }}">{{ $produk->deskripsi }}</td>
                                <td class="py-3">
                                    @if ($produk->dokumentasi)
                                        <img src="{{ $produk->dokumentasiUrl() }}" alt="Dokumentasi {{ $produk->nama }}" class="w-12 h-12 rounded-lg object-cover border border-slate-100">
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-xs font-medium text-slate-400">Belum ada produk unggulan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($produkList->hasPages())
                <div class="mt-4 flex items-center justify-between">
                    <p class="text-xs text-slate-500">
                        Menampilkan {{ $produkList->firstItem() }}–{{ $produkList->lastItem() }} dari {{ $produkList->total() }} produk
                    </p>
                    {{ $produkList->links() }}
                </div>
            @endif
        </section>

    </div>

    <script>
        document.getElementById('dokumentasi').addEventListener('change', function (e) {
            const files = Array.from(e.target.files);
            if (files.length === 0) return;

            const grid = document.getElementById('dokumentasi-preview-grid');
            grid.innerHTML = '';

            files.forEach(function (file) {
                const reader = new FileReader();
                reader.onload = function (event) {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'relative';
                    const img = document.createElement('img');
                    img.src = event.target.result;
                    img.alt = 'Preview dokumentasi';
                    img.className = 'w-full h-20 object-cover rounded-xl border border-slate-200';
                    wrapper.appendChild(img);
                    grid.appendChild(wrapper);
                };
                reader.readAsDataURL(file);
            });

            document.getElementById('dokumentasi-preview').classList.remove('hidden');
        });
    </script>
@endsection
