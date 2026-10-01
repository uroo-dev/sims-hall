@extends('Admin.layout.app')

@section('title', 'Edit Produk Unggulan | Admin')

@section('breadcrumb-role', 'Produk Unggulan')
@section('breadcrumb-page', 'Edit Produk')

@section('content')
    <main class="flex-1 p-6 lg:p-8 overflow-y-auto space-y-6">

        @include('Admin.layout.header')

        @if ($errors->any())
            <div class="rounded-2xl border border-red-100 bg-red-50 px-5 py-3 text-sm font-semibold text-red-700 flex items-center gap-2.5" role="alert">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <section class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
                <div>
                    <h2 class="text-sm font-extrabold text-brand-600 uppercase tracking-wider">Edit Produk Unggulan</h2>
                    <p class="text-[11px] font-semibold text-slate-500 mt-1">Kode produk: {{ $produk->kode_produk }}</p>
                </div>

                <a href="{{ route('produk.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-full text-xs font-bold flex items-center gap-2 transition-colors">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i> Kembali
                </a>
            </div>

            <form method="POST" action="{{ route('produk.update', $produk) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @include('Admin.produk_unggulan._form-fields', ['produk' => $produk])

                <div class="flex justify-end mt-5">
                    <button type="submit" class="px-5 py-2 bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold rounded-full shadow flex items-center gap-2 transition-colors">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </section>

    </main>
@endsection
