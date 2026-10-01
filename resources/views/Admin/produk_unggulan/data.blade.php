@extends('Admin.layout.app')

@section('title', 'Data Produk Unggulan | Admin')

@section('breadcrumb-role', 'Produk Unggulan')
@section('breadcrumb-page', 'Data Produk')

@section('content')
    <main class="flex-1 p-6 lg:p-8 overflow-y-auto space-y-6">

        @include('Admin.layout.header')

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

        <!-- FORM TAMBAH PRODUK -->
        <section class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-sm font-extrabold text-brand-600 uppercase tracking-wider">Tambah Produk Unggulan</h2>
                <span class="px-3 py-1 bg-brand-50 text-brand-600 rounded-full text-[10px] font-semibold">Kode produk dibuat otomatis</span>
            </div>

            <form method="POST" action="{{ route('produk.store') }}" enctype="multipart/form-data">
                @csrf

                @include('Admin.produk_unggulan._form-fields', ['produk' => null])

                <div class="flex justify-end mt-5">
                    <button type="submit" class="px-5 py-2 bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold rounded-full shadow flex items-center gap-2 transition-colors">
                        <i class="fa-solid fa-plus"></i> Tambah Produk
                    </button>
                </div>
            </form>
        </section>

        <!-- DAFTAR PRODUK UNGGULAN -->
        <section class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
                <h2 class="text-sm font-extrabold text-brand-600 uppercase tracking-wider">Daftar Produk Unggulan</h2>

                <form method="GET" action="{{ route('produk.index') }}" class="relative">
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari produk..."
                        class="w-44 sm:w-56 rounded-full border border-slate-200 bg-slate-50 pl-9 pr-9 py-2 text-xs text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-2 focus:ring-brand-600/15"
                    >
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[10px]"></i>

                    @if ($search !== '')
                        <a href="{{ route('produk.index') }}" title="Hapus pencarian" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-red-500 text-xs">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    @endif
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs md:text-sm">
                    <thead>
                        <tr class="border-b-2 border-slate-800 text-slate-800 font-extrabold">
                            <th class="py-3 px-2">Kode Produk</th>
                            <th class="py-3 px-2">Nama</th>
                            <th class="py-3 px-2">Deskripsi</th>
                            <th class="py-3 px-2">Jurusan</th>
                            <th class="py-3 px-2">Dokumentasi</th>
                            <th class="py-3 px-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-slate-700 font-medium">
                        @forelse ($produk as $item)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-3.5 px-2 font-bold text-slate-800">{{ $item->kode_produk }}</td>
                                <td class="py-3.5 px-2">{{ $item->nama }}</td>
                                <td class="py-3.5 px-2 max-w-[220px]">
                                    <span class="block truncate" title="{{ $item->deskripsi }}">{{ $item->deskripsi }}</span>
                                </td>
                                <td class="py-3.5 px-2">{{ $item->jurusan?->nama ?? '-' }}</td>
                                <td class="py-3.5 px-2">
                                    @if ($item->dokumentasiUrl())
                                        <a href="{{ $item->dokumentasiUrl() }}" target="_blank" rel="noopener" class="inline-block px-2 py-0.5 rounded bg-blue-50 text-brand-600 font-semibold hover:bg-blue-100 transition-colors">
                                            Lihat File
                                        </a>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-2">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('produk.edit', $item) }}" title="Edit produk"
                                            class="w-7 h-7 rounded-lg bg-blue-50 text-brand-600 hover:bg-blue-100 flex items-center justify-center transition-colors">
                                            <i class="fa-solid fa-pen text-[10px]"></i>
                                        </a>

                                        <form method="POST" action="{{ route('produk.destroy', $item) }}"
                                            onsubmit="return confirm('Hapus produk {{ $item->kode_produk }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Hapus produk"
                                                class="w-7 h-7 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center transition-colors">
                                                <i class="fa-solid fa-trash text-[10px]"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-10 px-2 text-center text-slate-400 font-medium">
                                    {{ $search !== '' ? 'Produk tidak ditemukan.' : 'Belum ada produk unggulan.' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($produk->hasPages())
                <div class="mt-5 flex items-center justify-between">
                    <p class="text-xs text-slate-500">
                        Menampilkan {{ $produk->firstItem() }}–{{ $produk->lastItem() }} dari {{ $produk->total() }} produk
                    </p>
                    {{ $produk->links() }}
                </div>
            @endif
        </section>

    </main>
@endsection
