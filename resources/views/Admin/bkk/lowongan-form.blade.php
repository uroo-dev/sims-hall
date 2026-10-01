@extends('Admin.layout.app')

@section('title', ($lowongan ? 'Edit' : 'Tambah') . ' Lowongan Kerja - BKK')
@section('page_title', $pageTitle ?? 'Form Lowongan Kerja')

@section('content')

    @if ($errors->any())
        <div
            class="rounded-xl border border-red-200 bg-red-50 text-red-800 px-4 py-3 text-sm flex items-start gap-2">
            <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
            <div>
                <p class="font-semibold">Periksa kembali isian Anda:</p>
                <ul class="list-disc list-inside mt-1 space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form method="POST"
        action="{{ $lowongan ? route('pkl.lowongan.update', $lowongan) : route('pkl.lowongan.store') }}">
        @csrf
        @if ($lowongan)
            @method('PUT')
        @endif

        <div class="bg-white rounded-2xl border border-gray-100 card-shadow p-5 space-y-4">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-semibold text-gray-600 mb-1.5">
                        Nama Perusahaan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_perusahaan" required
                        value="{{ old('nama_perusahaan', $lowongan?->nama_perusahaan) }}"
                        class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                        placeholder="PT / CV / Toko Name">
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-gray-600 mb-1.5">
                        Posisi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="posisi" required
                        value="{{ old('posisi', $lowongan?->posisi) }}"
                        class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                        placeholder="Junior Frontend Developer">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-[11px] font-semibold text-gray-600 mb-1.5">
                        Tipe <span class="text-red-500">*</span>
                    </label>
                    <select name="tipe" required
                        class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                        @foreach ([\App\Models\Lowongan::TIPE_PEKERJAAN, \App\Models\Lowongan::TIPE_MAGANG] as $t)
                            <option value="{{ $t }}" @selected(old('tipe', $lowongan?->tipe ?? 'Pekerjaan') === $t)>
                                {{ $t }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-gray-600 mb-1.5">
                        Jurusan Sesuai <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="jurusan_sesuai" required
                        value="{{ old('jurusan_sesuai', $lowongan?->jurusan_sesuai ?? 'Semua Jurusan') }}"
                        class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                        placeholder="RPL, TKJ atau Semua Jurusan">
                    <p class="text-[10px] text-gray-400 mt-1">Pisahkan dengan koma. Contoh: RPL, TKJ</p>
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-gray-600 mb-1.5">
                        Deadline <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="deadline" required
                        value="{{ old('deadline', $lowongan?->deadline?->toDateString()) }}"
                        class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-gray-600 mb-1.5">
                    DUDI Penerbit <span class="font-normal text-gray-400">(opsional)</span>
                </label>
                <select name="dudi_id"
                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    <option value="">-- Lowongan umum (tanpa DUDI) --</option>
                    @foreach ($dudis as $d)
                        <option value="{{ $d->id }}" @selected(old('dudi_id', $lowongan?->dudi_id) == $d->id)>
                            {{ $d->nama_dudi }} ({{ $d->kota }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-gray-600 mb-1.5">
                    Deskripsi <span class="text-red-500">*</span>
                </label>
                <textarea name="deskripsi" rows="5" required
                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                    placeholder="Jelaskan kualifikasi, tanggung jawab, dan benefit yang ditawarkan.">{{ old('deskripsi', $lowongan?->deskripsi) }}</textarea>
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-gray-600 mb-1.5">
                    Link Daftar <span class="text-red-500">*</span>
                </label>
                <input type="url" name="link_daftar" required
                    value="{{ old('link_daftar', $lowongan?->link_daftar) }}"
                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                    placeholder="https://forms.gle/... atau https://wa.me/62...">
                <p class="text-[10px] text-gray-400 mt-1">
                    Link eksternal: Google Form, WhatsApp HRD, atau website resmi perusahaan.
                </p>
            </div>

            <label class="flex items-center gap-2.5 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $lowongan?->is_active ?? true))
                    class="w-4 h-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                <span class="text-sm text-gray-700 font-medium">Aktifkan lowongan ini</span>
            </label>

            <div class="flex items-center gap-2 pt-2 border-t border-gray-100">
                <button type="submit"
                    class="text-sm font-semibold bg-brand-600 hover:bg-brand-700 text-white px-5 py-2.5 rounded-lg transition">
                    <i class="fa-solid fa-floppy-disk mr-1.5"></i>
                    {{ $lowongan ? 'Simpan Perubahan' : 'Simpan Lowongan' }}
                </button>
                <a href="{{ route('pkl.lowongan.index') }}"
                    class="text-sm font-semibold text-gray-500 hover:text-gray-700 px-4 py-2.5 rounded-lg transition">
                    Batal
                </a>
            </div>
        </div>
    </form>

@endsection
