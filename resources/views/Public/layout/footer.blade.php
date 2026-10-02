@php
    $sekolah = config('sekolah');
@endphp

<!-- FOOTER & PETA LOKASI -->
<footer class="bg-brand-blue text-white rounded-t-[3rem] pt-16 pb-0 mt-12 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

            <!-- Left Footer Info -->
            <div class="lg:col-span-6 space-y-6">
                @if (file_exists(public_path('assets/smk hebat.png')))
                    <div class="flex items-center">
                        <img src="{{ asset('assets/smk hebat.png') }}" alt="Logo SMK Bisa-Hebat"
                            class="h-24 sm:h-32 w-auto object-contain">
                    </div>
                @else
                    <p class="text-2xl sm:text-3xl font-black tracking-tight">SMK BISA — SMK HEBAT</p>
                @endif

                <p class="text-blue-100 text-sm leading-relaxed max-w-md">
                    {{ $sekolah['tentang'] }}
                </p>
            </div>

            <!-- Right Footer Google Maps -->
            <div class="lg:col-span-6">
                <div class="bg-white text-slate-800 rounded-2xl p-4 shadow-2xl relative overflow-hidden">
                    <div class="flex justify-between items-start mb-3 border-b border-slate-100 pb-2">
                        <div>
                            <h4 class="font-bold text-sm text-slate-900">{{ $sekolah['nama_pendek'] }}</h4>
                            <p class="text-[11px] text-slate-500">{{ $sekolah['alamat'] }}</p>
                            <div class="flex items-center gap-1 mt-1">
                                <span class="text-xs font-bold text-amber-500">{{ $sekolah['rating'] }}</span>
                                <div class="text-amber-400 text-[10px]" aria-label="Rating {{ $sekolah['rating'] }} dari 5">
                                    @for ($bintang = 1; $bintang <= 5; $bintang++)
                                        @if ($sekolah['rating'] >= $bintang)
                                            <i class="fa-solid fa-star"></i>
                                        @elseif ($sekolah['rating'] >= $bintang - 0.5)
                                            <i class="fa-solid fa-star-half-stroke"></i>
                                        @else
                                            <i class="fa-regular fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="text-[10px] text-blue-600">{{ $sekolah['jumlah_ulasan'] }} reviews</span>
                            </div>
                        </div>
                        <a href="{{ $sekolah['maps_url'] }}" target="_blank" rel="noopener"
                            class="bg-blue-50 text-brand-blue hover:bg-blue-100 p-2 rounded-lg text-xs font-semibold flex items-center gap-1">
                            <i class="fa-solid fa-diamond-turn-right"></i> Directions
                        </a>
                    </div>

                    <div class="w-full h-44 bg-slate-200 rounded-lg relative overflow-hidden flex items-center justify-center">
                        <iframe title="Peta {{ $sekolah['nama_pendek'] }}" class="w-full h-full border-0 rounded-lg"
                            src="{{ $sekolah['maps_embed_url'] }}" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Copyright Bottom Bar -->
    <div class="bg-[#1A1A1A] text-slate-400 text-xs text-center py-4 border-t border-slate-800">
        &copy; {{ now()->year }} {{ $sekolah['nama_pendek'] }}. All Rights Reserved
    </div>
</footer>
