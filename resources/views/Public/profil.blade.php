@extends('Public.layout.app')

@section('title', 'Profil & Sejarah — SMK Negeri 2 Karanganyar')
@section('meta_description', 'Profil lengkap, sejarah pendirian, visi dan misi, serta sarana dan prasarana SMK Negeri 2 Karanganyar.')
@section('meta_keywords', 'Profil SMKN 2 Karanganyar, Visi Misi SMK Negeri 2 Karanganyar, Sejarah Skandakra, Sarana Prasarana')

@section('content')
    <!-- ============================================================
         KONTEN PROFILE — BAGIAN 1: SEJARAH SEKOLAH
         ============================================================ -->
    <section id="sejarah" class="relative pt-10 pb-16 md:pb-20 overflow-hidden">
        <div class="absolute top-8 left-6 w-24 h-24 dot-pattern opacity-40 pointer-events-none hidden sm:block"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            <!-- FOTO BANNER (Menggunakan dokumentasi profil dari database) -->
            <div class="relative rounded-3xl overflow-hidden shadow-2xl">
                @if (isset($sekolah->profil_dokumentasi) && $sekolah->dokumentasi)
                    <img src="{{ asset('assets/' . $sekolah->dokumentasi) }}"
                        alt="{{ $sekolah->profil_judul ?? 'SMKN 2 Karanganyar' }}"
                        class="w-full h-72 sm:h-80 md:h-[26rem] object-cover">
                @else
                    <img src="{{ asset('assets/Sejarah.jpg') }}" alt="Gerbang SMK Negeri 2 Karanganyar"
                        class="w-full h-72 sm:h-80 md:h-[26rem] object-cover">
                @endif

                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/40 to-slate-900/20"></div>
            </div>

            <!-- KARTU SEJARAH (Overlap ke atas foto) -->
            <div
                class="relative -mt-12 sm:-mt-20 mx-auto max-w-5xl bg-white rounded-3xl shadow-2xl border border-slate-100 p-6 sm:p-10 md:p-12">
                <h2
                    class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-slate-900 text-center mb-6 sm:mb-8 tracking-tight">
                    {{ $sekolah->judul ?? 'Sejarah SMKN 2 KARANGANYAR' }}
                </h2>

                <div class="space-y-5 text-slate-600 text-sm sm:text-base leading-relaxed text-justify-custom">
                    {!! nl2br(e($sekolah->sejarah ?? 'Data sejarah belum diisi.')) !!}
                </div>

                <!-- Highlight Strip Identitas Sekolah -->
                <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-brand-lightBlue rounded-2xl p-5 text-center border border-blue-100">
                        <div class="text-2xl font-black text-brand-blue mb-1">1997</div>
                        <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Tahun Berdiri</div>
                    </div>
                    <div class="bg-brand-lightBlue rounded-2xl p-5 text-center border border-blue-100">
                        <div class="text-2xl font-black text-brand-blue mb-1">27.720 m<sup>2</sup></div>
                        <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Luas Area</div>
                    </div>
                    <div class="bg-brand-lightBlue rounded-2xl p-5 text-center border border-blue-100">
                        <div class="text-2xl font-black text-brand-blue mb-1">4</div>
                        <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Kompetensi Keahlian
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- ============================================================
         KONTEN PROFILE — BAGIAN 2: KEPALA SEKOLAH & SAMBUTAN
         ============================================================ -->
    <section id="kepsek" class="py-16 bg-white relative overflow-hidden">
        <div class="absolute top-10 right-8 w-24 h-24 dot-pattern opacity-40 pointer-events-none hidden sm:block">
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            <div class="text-center max-w-3xl mx-auto mb-14">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Kepala Sekolah {{ $sekolah->profil_judul ?? 'SMKN 2 KARANGANYAR' }}
                </h2>
                <div class="w-24 h-1 bg-brand-blue rounded-full mx-auto mt-4"></div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">

                <!-- SISI KIRI: Foto & Kartu Nama -->
                <div class="lg:col-span-4 h-full">
                    <div
                        class="bg-white border border-slate-200 rounded-3xl overflow-hidden card-shadow h-full flex flex-col">
                        <div class="relative bg-gradient-to-br from-brand-blue to-blue-900 flex-grow">
                            <div class="absolute top-0 left-5 w-1.5 h-20 bg-red-600 z-20 rounded-b"></div>
                            <div class="absolute top-0 left-[1.625rem] w-1.5 h-20 bg-white z-20 rounded-b"></div>

                            @if (isset($sekolah->foto_kepsek) && $sekolah->foto_kepsek)
                                <img src="{{ asset('assets/' . $sekolah->foto_kepsek) }}"
                                    alt="{{ $sekolah->nama_kepsek }}"
                                    class="w-full h-full object-cover object-top min-h-[20rem]">
                            @else
                                <img src="{{ asset('assets/foto kepsek.png') }}" alt="Kepala Sekolah"
                                    class="w-full h-full object-cover object-top min-h-[20rem]">
                            @endif
                        </div>

                        <div class="bg-white border-t-4 border-brand-blue p-6 text-center">
                            <h3 class="text-lg sm:text-xl font-extrabold text-slate-900 leading-tight">
                                {{ $sekolah->nama_kepsek ?? 'Bapak Sukidi S. Pd., M. Pd.' }}
                            </h3>
                            <p class="text-xs sm:text-sm font-semibold text-brand-blue mt-1">
                                Kepala Sekolah {{ $sekolah->profil_judul ?? 'SMKN 2 Karanganyar' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- SISI KANAN: Sambutan, Quote, Visi & Misi -->
                <div class="lg:col-span-8 space-y-6">

                    <!-- Kartu Sambutan -->
                    <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-9 card-shadow">
                        <div class="mb-6">
                            <h3
                                class="text-xl sm:text-2xl font-bold text-slate-900 border-b-4 border-brand-blue inline-block pb-1.5">
                                Sambutan Kepala Sekolah
                            </h3>
                        </div>

                        <div class="space-y-4 text-slate-600 text-sm sm:text-base leading-relaxed text-justify-custom">
                            {!! nl2br(e($sekolah->sambutan_kepsek ?? 'Data sambutan belum diisi.')) !!}
                        </div>

                        <!-- Quote Slogan (Yel-yel) -->
                        @if (isset($sekolah->yel_yel) && $sekolah->yel_yel)
                            <div
                                class="mt-8 bg-brand-lightBlue border-l-4 border-brand-blue rounded-r-2xl px-5 py-4 text-center">
                                <p class="text-xs sm:text-sm font-extrabold text-brand-blue tracking-wide uppercase">
                                    {{ $sekolah->yel_yel }}
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Kartu Visi & Misi -->
                    <div class="bg-white border-2 border-slate-200 rounded-3xl p-6 sm:p-8 card-shadow">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">

                            <!-- VISI -->
                            <div class="md:col-span-5">
                                <h4
                                    class="text-base font-extrabold text-slate-900 uppercase tracking-wider mb-3 flex items-center gap-2">
                                    <i class="fa-solid fa-eye text-brand-blue"></i> Visi
                                </h4>
                                <div class="text-slate-600 text-xs sm:text-sm leading-relaxed text-justify-custom">
                                    {!! nl2br(e($sekolah->visi ?? 'Data visi belum diisi.')) !!}
                                </div>
                            </div>

                            <!-- Divider -->
                            <div class="hidden md:block md:col-span-1">
                                <div class="w-px h-full bg-slate-200 mx-auto"></div>
                            </div>

                            <!-- MISI -->
                            <div class="md:col-span-6">
                                <h4
                                    class="text-base font-extrabold text-slate-900 uppercase tracking-wider mb-3 flex items-center gap-2">
                                    <i class="fa-solid fa-bullseye text-brand-blue"></i> Misi
                                </h4>
                                <div class="space-y-2 text-slate-600 text-xs sm:text-sm leading-relaxed">
                                    {!! nl2br(e($sekolah->misi ?? 'Data misi belum diisi.')) !!}
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

            </div>

        </div>
    </section>

@endsection