@php
    $inputClass = 'block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-2 focus:ring-brand-600/15';
    $labelClass = 'block text-xs font-bold text-slate-700';
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="space-y-2">
            <label for="nama" class="{{ $labelClass }}">Nama Produk</label>
            <input
                id="nama"
                name="nama"
                type="text"
                value="{{ old('nama', $produk->nama ?? '') }}"
                maxlength="255"
                required
                placeholder="Masukkan nama produk"
                class="{{ $inputClass }}"
            >
            @error('nama')
                <p class="text-[11px] font-semibold text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-2">
            <label for="jurusanID" class="{{ $labelClass }}">Jurusan</label>
            <select id="jurusanID" name="jurusanID" class="{{ $inputClass }}">
                <option value="">Pilih jurusan</option>
                @foreach ($jurusan as $item)
                    <option value="{{ $item->jurusanID }}" @selected((string) old('jurusanID', $produk->jurusanID ?? '') === (string) $item->jurusanID)>
                        {{ $item->nama }}
                    </option>
                @endforeach
            </select>
            @error('jurusanID')
                <p class="text-[11px] font-semibold text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="md:col-span-2 space-y-2">
            <label for="deskripsi" class="{{ $labelClass }}">Deskripsi</label>
            <textarea
                id="deskripsi"
                name="deskripsi"
                rows="3"
                required
                placeholder="Deskripsikan produk secara singkat"
                class="{{ $inputClass }}"
            >{{ old('deskripsi', $produk->deskripsi ?? '') }}</textarea>
            @error('deskripsi')
                <p class="text-[11px] font-semibold text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="flex flex-col space-y-2">
        <label for="dokumentasi" class="{{ $labelClass }}">Dokumentasi</label>

        @if (! empty($produk?->dokumentasi))
            <div class="space-y-2">
                <img src="{{ $produk->dokumentasiUrl() }}" alt="Dokumentasi {{ $produk->nama }}" class="w-full h-28 object-cover rounded-xl border border-slate-200">
                <label class="flex items-center gap-2 text-[11px] font-semibold text-slate-600 cursor-pointer select-none">
                    <input type="checkbox" name="hapus_dokumentasi" value="1" class="w-3.5 h-3.5 rounded border-slate-300 text-brand-600 focus:ring-brand-600/30">
                    <span>Hapus dokumentasi ini</span>
                </label>
            </div>
        @endif

        <label for="dokumentasi" class="flex-1 flex cursor-pointer flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-slate-200 px-4 py-6 text-center text-xs font-semibold text-slate-500 transition hover:border-brand-500 hover:text-brand-600">
            <i class="fa-solid fa-image text-2xl"></i>
            <span>{{ ! empty($produk?->dokumentasi) ? 'Ganti foto produk' : 'Unggah foto produk' }}</span>
            <span class="text-[10px] font-medium text-slate-400">JPG/PNG/WEBP, maks. 1 MB</span>
            <input id="dokumentasi" name="dokumentasi" type="file" accept="image/jpeg,image/png,image/webp" class="sr-only">
        </label>

        <div id="dokumentasi-preview" class="hidden">
            <p class="text-[11px] font-semibold text-slate-600 mb-1.5">Preview:</p>
            <img id="dokumentasi-preview-img" src="" alt="Preview dokumentasi" class="w-full h-28 object-cover rounded-xl border border-slate-200">
        </div>

        @error('dokumentasi')
            <p class="text-[11px] font-semibold text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>

<script>
    document.getElementById('dokumentasi').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (event) {
            document.getElementById('dokumentasi-preview-img').src = event.target.result;
            document.getElementById('dokumentasi-preview').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    });
</script>
