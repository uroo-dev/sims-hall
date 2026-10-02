@extends('Admin.layout.app')

@section('title', 'Dashboard PPDB - SMK Negeri 2 Karanganyar')

@section('content')
    <div class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100">
        <form action="{{ route('update.master.ppdb') }}" method="POST" enctype="multipart/form-data" class="flex flex-col">
            @csrf

            {{-- Penanda penghapusan gambar oleh user (diklik tombol X) --}}
            <input type="hidden" name="hapus_gambar" id="hapusGambar" value="0">

            @if (session('success'))
                <div class="mb-4 p-3 rounded-xl text-xs font-medium bg-green-50 text-green-700 border border-green-200 flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-sm shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-3 rounded-xl text-xs font-medium bg-red-50 text-red-700 border border-red-200 flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation text-sm shrink-0"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <div class="flex gap-6 h-80">
                <!-- KOLOM KIRI: JUDUL (20%) + DESKRIPSI (80%) -->
                <div class="flex-1 flex flex-col gap-4 min-w-0">

                    <!-- Judul -->
                    <div class="h-[20%] flex flex-col">
                        <label for="judul" class="text-xs font-extrabold uppercase tracking-wider text-brand-600 mb-1">
                            Judul
                        </label>
                        <input type="text" name="judul" id="judul" value="{{ old('judul', $master?->judul) }}"
                            placeholder="PPDB ..."
                            class="w-full h-full px-4 bg-slate-50 text-slate-800 text-sm font-medium rounded-xl border-2 border-slate-200 focus:border-brand-600 focus:ring-4 focus:ring-brand-100 focus:outline-none transition placeholder:text-slate-400">
                    </div>

                    <!-- Deskripsi -->
                    <div class="flex-1 flex flex-col min-h-0">
                        <label for="deskripsi" class="text-xs font-extrabold uppercase tracking-wider text-brand-600 mb-1">
                            Deskripsi
                        </label>
                        <textarea name="deskripsi" id="deskripsi" rows="4"
                            placeholder="Tuliskan deskripsi informasi PPDB ..."
                            class="w-full flex-1 px-4 py-3 bg-slate-50 text-slate-800 text-sm font-medium rounded-xl border-2 border-slate-200 focus:border-brand-600 focus:ring-4 focus:ring-brand-100 focus:outline-none transition placeholder:text-slate-400 resize-none">{{ old('deskripsi', $master?->deskripsi) }}</textarea>
                    </div>

                </div>

                <!-- KOLOM KANAN: INPUT GAMBAR (100%) -->
                <div class="flex-1 flex flex-col min-w-0">
                    <label for="gambar" class="text-xs font-extrabold uppercase tracking-wider text-brand-600 mb-1">
                        Gambar
                    </label>

                    <div
                        class="flex-1 relative rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 overflow-hidden flex items-center justify-center group hover:border-brand-500 transition-colors cursor-pointer"
                        onclick="document.getElementById('gambar').click()">

                        <!-- Preview Gambar -->
                        <img id="imgPreview" src="{{ $bannerUrl ?? '' }}" alt="Preview Banner"
                            class="absolute inset-0 w-full h-full object-cover {{ $master?->banner_img ? '' : 'hidden' }}">

                        <!-- Placeholder -->
                        <div id="imgPlaceholder"
                            class="flex flex-col items-center justify-center text-center px-6 text-slate-400 group-hover:text-brand-600 transition-colors {{ $master?->banner_img ? 'hidden' : '' }}">
                            <i class="fa-solid fa-image text-3xl mb-2"></i>
                            <span class="text-xs font-semibold uppercase tracking-wider">Belum ada gambar</span>
                            <span class="text-[11px] font-medium mt-1">Klik area ini untuk memilih gambar</span>
                        </div>

                        <!-- Tombol Hapus (X) -->
                        <button type="button" id="btnHapusGambar" title="Hapus gambar"
                            onclick="event.stopPropagation(); resetBanner();"
                            class="absolute top-2 right-2 w-7 h-7 flex items-center justify-center rounded-full bg-white/90 hover:bg-red-500 text-slate-600 hover:text-white shadow-sm transition-colors {{ $master?->banner_img ? '' : 'hidden' }}">
                            <i class="fa-solid fa-xmark text-xs"></i>
                        </button>

                        <input type="file" name="banner_img" id="gambar" accept="image/*" class="hidden">
                    </div>
                </div>
            </div>

            <button type="submit" class="bg-brand-600 hover:bg-brand-700 w-fit p-3 rounded-xl text-white mt-5">Simpan Perubahan</button>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        const bannerInput = document.getElementById('gambar');
        const bannerPreview = document.getElementById('imgPreview');
        const bannerPlaceholder = document.getElementById('imgPlaceholder');
        const hapusGambar = document.getElementById('hapusGambar');
        const btnHapusGambar = document.getElementById('btnHapusGambar');
        let objectUrl = null;

        function showBanner(src) {
            bannerPreview.src = src;
            bannerPreview.classList.remove('hidden');
            bannerPlaceholder.classList.add('hidden');
            btnHapusGambar.classList.remove('hidden');
        }

        function clearBanner() {
            bannerInput.value = '';
            bannerPreview.removeAttribute('src');
            bannerPreview.classList.add('hidden');
            bannerPlaceholder.classList.remove('hidden');
            btnHapusGambar.classList.add('hidden');

            if (objectUrl) {
                URL.revokeObjectURL(objectUrl);
                objectUrl = null;
            }
        }

        function resetBanner() {
            hapusGambar.value = '1';
            clearBanner();
        }

        if (bannerInput) {
            bannerInput.addEventListener('change', function() {
                hapusGambar.value = '0';

                if (!this.files || !this.files[0]) {
                    clearBanner();
                    return;
                }

                if (objectUrl) {
                    URL.revokeObjectURL(objectUrl);
                }

                objectUrl = URL.createObjectURL(this.files[0]);
                showBanner(objectUrl);
            });
        }
    </script>
@endpush
