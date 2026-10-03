@extends('Admin.layout.app')

@section('title', 'Dashboard Kesiswaan | Admin Kesiswaan')

@section('breadcrumb-role', 'Kesiswaan')
@section('breadcrumb-page', 'Dashboard Kesiswaan')

@section('content')
    <div class="space-y-6">


        <!-- CARD: JUDUL, DESKRIPSI & DOKUMENTASI KESISWAAN -->
        <section class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100">
            <form method="POST" action="{{ route('admin.kesiswaan.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    <!-- KIRI: JUDUL, DESKRIPSI & TOMBOL SIMPAN -->
                    <div class="lg:col-span-7 flex flex-col justify-between space-y-5">
                        <div class="space-y-4">
                            <div class="space-y-1.5">
                                <label for="judul" class="block text-xs font-bold text-slate-700">Judul</label>
                                <input
                                    id="judul"
                                    name="judul"
                                    type="text"
                                    value="{{ old('judul', $kesiswaan->judul) }}"
                                    placeholder="Kesiswaan SMKN 2 Karanganyar"
                                    required
                                    class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-2 focus:ring-brand-600/15"
                                >
                            </div>

                            <div class="space-y-1.5">
                                <label for="deskripsi" class="block text-xs font-bold text-slate-700">Deskripsi singkat:</label>
                                <textarea
                                    id="deskripsi"
                                    name="deskripsi"
                                    rows="7"
                                    placeholder="Membangun karakter unggul melalui integrasi nilai moral dan penguasaan teknologi. Kami berdedikasi untuk membina potensi setiap siswa dalam lingkungan yang inklusif, inovatif, dan disiplin."
                                    required
                                    class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-2 focus:ring-brand-600/15"
                                >{{ old('deskripsi', $kesiswaan->deskripsi) }}</textarea>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="px-6 py-2.5 bg-[#0066C4] hover:bg-blue-700 text-white rounded-xl text-sm font-bold shadow-md shadow-blue-500/20 transition transform active:scale-95">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>

                    <!-- KANAN: DOKUMENTASI (2 FOTO SEPERTI FIGMA DESIGN & DATA MASTER) -->
                    <div class="lg:col-span-5 space-y-2">
                        <label class="block text-xs font-bold text-slate-700">Dokumentasi</label>
                        <div class="grid grid-cols-2 gap-4">
                            @php
                                $defaultPosters = [
                                    asset('assets/prestasi/banner_terbaru_2.png'),
                                    asset('assets/prestasi/banner_terbaru_1.png')
                                ];
                            @endphp
                            @for ($i = 0; $i < 2; $i++)
                                @php
                                    $imgSrc = $dokumentasiUrls[$i] ?? $defaultPosters[$i];
                                @endphp
                                <div class="bg-white rounded-2xl border border-slate-200 p-2 shadow-sm relative group cursor-pointer hover:border-[#0066C4] transition flex flex-col items-center justify-center">
                                    <div class="w-full h-60 sm:h-68 rounded-xl overflow-hidden bg-slate-50 relative">
                                        <img id="preview-doc-{{ $i }}" src="{{ $imgSrc }}" alt="Dokumentasi {{ $i + 1 }}" class="w-full h-full object-cover rounded-xl transition duration-300 group-hover:scale-[1.02]">
                                        <!-- Hover Overlay Ganti Gambar -->
                                        <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center pointer-events-none rounded-xl">
                                            <span class="text-white text-xs font-semibold px-3 py-1.5 rounded-lg bg-black/60 backdrop-blur-sm shadow-md">
                                                <i class="fa-solid fa-camera mr-1.5"></i> Ganti Gambar
                                            </span>
                                        </div>
                                    </div>
                                    <input type="file" name="dokumentasi[{{ $i }}]" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="previewKesiswaanDoc(this, 'preview-doc-{{ $i }}')">
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </form>
        </section>

        <!-- WIDGETS ROW: EKSTRAKURIKULER, TATA TERTIB, JUMLAH PRESTASI -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">

            <!-- CARD 1: EKSTRAKURIKULER -->
            <div class="md:col-span-4 bg-white rounded-2xl p-5 figma-card-shadow border border-slate-100 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                        <h3 class="text-xs font-black uppercase tracking-wider text-slate-800">Ekstrakulikuler</h3>
                        <a href="{{ route('admin.kesiswaan.ekstrakurikuler.index') }}" class="px-3 py-1 bg-[#0066C4] text-white rounded-lg text-[11px] font-bold hover:bg-blue-700 transition flex items-center gap-1">
                            <span>Selengkapnya</span>
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="text-slate-400 font-bold border-b border-slate-100">
                                    <th class="pb-2">Nama</th>
                                    <th class="pb-2 text-right">Logo</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($ekstrakurikulers as $eskul)
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="py-2.5 font-medium text-slate-800">{{ $eskul->nama }}</td>
                                        <td class="py-2.5 text-right">
                                            @if ($eskul->logoUrl())
                                                <img src="{{ $eskul->logoUrl() }}" alt="{{ $eskul->nama }}" class="w-7 h-7 object-contain inline-block rounded-md border border-slate-200 p-0.5">
                                            @else
                                                <span class="w-7 h-7 inline-flex items-center justify-center bg-slate-100 rounded-md text-[10px] text-slate-500">N/A</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="py-4 text-center text-slate-400 text-xs">Belum ada data ekstrakurikuler.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- CARD 2: TATA TERTIB -->
            <div class="md:col-span-3 bg-white rounded-2xl p-5 figma-card-shadow border border-slate-100 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-6">
                        <h3 class="text-xs font-black uppercase tracking-wider text-slate-800">Tata Tertib</h3>
                        <a href="{{ route('admin.kesiswaan.tata-tertib.index') }}" class="px-3 py-1 bg-[#0066C4] text-white rounded-lg text-[11px] font-bold hover:bg-blue-700 transition flex items-center gap-1">
                            <span>Selengkapnya</span>
                        </a>
                    </div>

                    <div class="py-6 flex items-center justify-center">
                        <span class="text-7xl font-extrabold text-[#0066C4] tracking-tight">
                            {{ $totalTataTertib }}
                        </span>
                    </div>
                </div>
                <p class="text-center text-xs text-slate-400 font-medium">Total Peraturan & Pedoman Siswa</p>
            </div>

            <!-- CARD 3: JUMLAH PRESTASI (CHART BAR) -->
            <div class="md:col-span-5 bg-white rounded-2xl p-5 figma-card-shadow border border-slate-100 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                        <h3 class="text-xs font-black uppercase tracking-wider text-slate-800">Jumlah Prestasi</h3>
                        <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-[11px] font-bold">
                            Total: {{ $prestasiAkademik + $prestasiNonAkademik }}
                        </span>
                    </div>

                    <!-- Bar Chart Visual -->
                    @php
                        $max = max(1, $prestasiAkademik, $prestasiNonAkademik);
                        $heightAkademik = min(100, max(15, ($prestasiAkademik / $max) * 100));
                        $heightNonAkademik = min(100, max(15, ($prestasiNonAkademik / $max) * 100));
                    @endphp
                    <div class="h-44 flex items-end justify-center gap-12 pt-4 pb-2 px-6 border-b border-slate-100">
                        <!-- Bar Akademik -->
                        <div class="flex flex-col items-center gap-2 h-full justify-end w-20">
                            <span class="text-xs font-bold text-slate-600">{{ $prestasiAkademik }}</span>
                            <div class="w-full bg-emerald-400 rounded-t-lg transition-all duration-500 shadow-sm" style="height: {{ $heightAkademik }}%;"></div>
                        </div>

                        <!-- Bar Non Akademik -->
                        <div class="flex flex-col items-center gap-2 h-full justify-end w-20">
                            <span class="text-xs font-bold text-slate-600">{{ $prestasiNonAkademik }}</span>
                            <div class="w-full bg-emerald-500 rounded-t-lg transition-all duration-500 shadow-sm" style="height: {{ $heightNonAkademik }}%;"></div>
                        </div>
                    </div>
                    <div class="flex justify-center gap-12 pt-2 text-[11px] font-bold text-slate-600">
                        <span class="w-20 text-center">AKADEMIK</span>
                        <span class="w-20 text-center">NON AKADEMIK</span>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

@push('scripts')
    <script>
        function previewKesiswaanDoc(input, previewId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById(previewId);
                    if (img) {
                        img.src = e.target.result;
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush
