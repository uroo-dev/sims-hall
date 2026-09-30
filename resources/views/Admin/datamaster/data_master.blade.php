@extends('Admin.layout.app')

@section('title', 'Data Master Sekolah')
@section('content')

<form action="{{ route('datamaster.sekolah.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- ============================================ --}}
    {{-- CARD 1: PROFIL SEKOLAH --}}
    {{-- ============================================ --}}
    <div class="bg-white rounded-2xl p-6 card-shadow border border-gray-100/80 mb-6">
        <div class="flex items-center gap-2 mb-6 border-b border-gray-100 pb-3">
            <i class="fa-solid fa-school text-brand-600 text-lg"></i>
            <h2 class="font-bold text-gray-800 text-base tracking-wide">Profil Sekolah</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <div class="lg:col-span-8 space-y-5">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Judul Profil</label>
                    <input type="text" name="profil_judul" value="{{ old('profil_judul', $sekolah->profil_judul ?? 'SMKN 2 KARANGANYAR') }}" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-brand-500 transition shadow-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Deskripsi Singkat</label>
                    <textarea name="profil_deskripsi" rows="6" required
                        class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:outline-none focus:border-brand-500 transition shadow-sm resize-none">{{ old('profil_deskripsi', $sekolah->profil_deskripsi ?? 'Sebagai Sekolah Pusat Keunggulan...') }}</textarea>
                </div>
            </div>
            <div class="lg:col-span-4">
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Dokumentasi Profil</label>
                <div class="border border-gray-300 rounded-lg p-2 bg-gray-50 flex flex-col items-center justify-center min-h-[13rem] relative group cursor-pointer hover:bg-gray-100 transition">
                    @if(isset($sekolah->profil_dokumentasi) && $sekolah->profil_dokumentasi)
                        <img src="{{ asset('assets/' . $sekolah->profil_dokumentasi) }}" alt="Profil" class="w-full h-full object-contain rounded shadow-sm">
                    @else
                        <img src="{{ asset('assets/dokumentasi-3d.png') }}" alt="Profil Default" class="w-full h-full object-contain rounded shadow-sm">
                    @endif
                    <input type="file" name="profil_dokumentasi" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center rounded pointer-events-none">
                        <span class="text-white text-xs font-medium"><i class="fa-solid fa-camera mr-1"></i> Ganti Gambar</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- CARD 2: SEJARAH --}}
    {{-- ============================================ --}}
    <div class="bg-white rounded-2xl p-6 card-shadow border border-gray-100/80 mb-6">
        <div class="flex items-center gap-2 mb-6 border-b border-gray-100 pb-3">
            <i class="fa-solid fa-book-open text-brand-600 text-lg"></i>
            <h2 class="font-bold text-gray-800 text-base tracking-wide">Sejarah</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <div class="lg:col-span-4 space-y-5">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Judul Sejarah</label>
                    <input type="text" name="judul" value="{{ old('judul', $sekolah->judul ?? 'Sejarah SMKN 2 KARANGANYAR') }}" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-brand-500 transition shadow-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Dokumentasi Sejarah</label>
                    <div class="border border-gray-300 rounded-lg p-2 bg-gray-50 flex flex-col items-center justify-center min-h-[10rem] relative group cursor-pointer hover:bg-gray-100 transition">
                        @if(isset($sekolah->dokumentasi) && $sekolah->dokumentasi)
                            <img src="{{ asset('assets/' . $sekolah->dokumentasi) }}" alt="Dokumentasi" class="w-full h-full object-cover rounded shadow-sm">
                        @else
                            <img src="{{ asset('assets/Sejarah.jpg') }}" alt="Dokumentasi Default" class="w-full h-full object-cover rounded shadow-sm">
                        @endif
                        <input type="file" name="dokumentasi" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center rounded pointer-events-none">
                            <span class="text-white text-xs font-medium"><i class="fa-solid fa-camera mr-1"></i> Ganti Gambar</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lg:col-span-8">
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Deskripsi Sejarah</label>
                <textarea name="sejarah" rows="10" class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:outline-none focus:border-brand-500 transition shadow-sm resize-none">{{ old('sejarah', $sekolah->sejarah ?? '') }}</textarea>
            </div>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- CARD 3: KEPALA SEKOLAH --}}
    {{-- ============================================ --}}
    <div class="bg-white rounded-2xl p-6 card-shadow border border-gray-100/80 mb-6">
        <div class="flex items-center gap-2 mb-6 border-b border-gray-100 pb-3">
            <i class="fa-solid fa-user-tie text-brand-600 text-lg"></i>
            <h2 class="font-bold text-gray-800 text-base tracking-wide">Kepala Sekolah</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <div class="lg:col-span-4 space-y-5">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Nama Kepala Sekolah</label>
                    <input type="text" name="nama_kepsek" value="{{ old('nama_kepsek', $sekolah->nama_kepsek ?? 'Bapak Sukidi S. Pd., M. Pd.') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-brand-500 transition shadow-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Foto Kepala Sekolah</label>
                    <div class="border border-gray-200 rounded-xl overflow-hidden bg-white shadow-sm relative group">
                        <div class="bg-gradient-to-br from-brand-500 to-blue-900 relative">
                            <div class="absolute top-0 left-5 w-1.5 h-16 bg-red-600 z-20 rounded-b"></div>
                            <div class="absolute top-0 left-[1.625rem] w-1.5 h-16 bg-white z-20 rounded-b"></div>
                            @if(isset($sekolah->foto_kepsek) && $sekolah->foto_kepsek)
                                <img src="{{ asset('assets/' . $sekolah->foto_kepsek) }}" alt="Kepala Sekolah" class="w-full h-56 object-cover object-top">
                            @else
                                <img src="{{ asset('assets/foto kepsek.png') }}" alt="Kepala Sekolah Default" class="w-full h-56 object-cover object-top">
                            @endif
                        </div>
                        <input type="file" name="foto_kepsek" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-30">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center z-20 pointer-events-none">
                            <span class="text-white text-xs font-medium"><i class="fa-solid fa-camera mr-1"></i> Ganti Foto</span>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Yel-yel SMKN 2 Karanganyar</label>
                    <input type="text" name="yel_yel" value="{{ old('yel_yel', $sekolah->yel_yel ?? '"SMK Bisa, SMK Hebat, SMK Bisa Hebat, SMKN 2 Karanganyar PASTI BISA"') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-brand-500 transition shadow-sm">
                </div>
            </div>
            <div class="lg:col-span-8">
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Sambutan Kepala Sekolah</label>
                <textarea name="sambutan_kepsek" rows="12" class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:outline-none focus:border-brand-500 transition shadow-sm resize-none">{{ old('sambutan_kepsek', $sekolah->sambutan_kepsek ?? '') }}</textarea>
            </div>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- CARD 4: VISI & MISI (DIPISAH) --}}
    {{-- ============================================ --}}
    <div class="bg-white rounded-2xl p-6 card-shadow border border-gray-100/80">
        <div class="flex items-center gap-2 mb-6 border-b border-gray-100 pb-3">
            <i class="fa-solid fa-bullseye text-brand-600 text-lg"></i>
            <h2 class="font-bold text-gray-800 text-base tracking-wide">Visi & Misi</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Visi</label>
                <textarea name="visi" rows="8" class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:outline-none focus:border-brand-500 transition shadow-sm resize-none">{{ old('visi', $sekolah->visi ?? '') }}</textarea>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Misi</label>
                <textarea name="misi" rows="8" required class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:outline-none focus:border-brand-500 transition shadow-sm resize-none">{{ old('misi', $sekolah->misi ?? '') }}</textarea>
            </div>
        </div>
    </div>

    <!-- Tombol Simpan Global -->
    <div class="mt-6 flex justify-end">
        <button type="submit" class="bg-[#0073c6] hover:bg-brand-700 text-white text-sm font-bold px-8 py-3 rounded-lg transition shadow-md flex items-center gap-2">
            <i class="fa-solid fa-floppy-disk"></i> Simpan Semua Perubahan
        </button>
    </div>
</form>

@endsection