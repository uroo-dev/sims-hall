@php
    $sekolah = config('sekolah') ?? [
        'nama' => 'SMK Negeri 2 Karanganyar',
        'nama_pendek' => 'SMKN 2 Karanganyar',
        'alamat' => 'Jl. Yos Sudarso, Jengglong, Bejen, Kec. Karanganyar, Jawa Tengah 57716',
        'tentang' =>
            'SMK Negeri 2 Karanganyar adalah salah satu Sekolah Menengah Kejuruan favorit di Kabupaten Karanganyar. Serta merupakan sekolah yang berpendidikan karakter, berwawasan, disiplin, tanggung jawab, dan bermoral baik.',
        'rating' => 4.6,
        'jumlah_ulasan' => 217,
        'maps_url' => 'https://maps.google.com/?q=SMK+Negeri+2+Karanganyar',
        'maps_embed_url' =>
            'https://maps.google.com/maps?q=SMK%20Negeri%202%20Karanganyar&t=&z=15&ie=UTF8&iwloc=&output=embed',
    ];
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
                    {{ $sekolah['tentang'] ?? 'SMK Negeri 2 Karanganyar adalah salah satu Sekolah Menengah Kejuruan favorit di Kabupaten Karanganyar. Serta merupakan sekolah yang berpendidikan karakter, berwawasan, disiplin, tanggung jawab, dan bermoral baik.' }}
                </p>
                <div class="pt-2 flex flex-wrap items-center gap-2.5">
                    <a href="https://facebook.com/smkn2kra" target="_blank" rel="noopener" aria-label="Facebook"
                        class="w-9 h-9 rounded-lg bg-white/10 hover:bg-white hover:text-brand-blue text-white flex items-center justify-center transition-all border border-white/10">
                        <i class="fa-brands fa-facebook-f text-base"></i>
                    </a>
                    <a href="https://instagram.com/smkn2kra" target="_blank" rel="noopener" aria-label="Instagram"
                        class="w-9 h-9 rounded-lg bg-white/10 hover:bg-pink-600 hover:text-white text-white flex items-center justify-center transition-all border border-white/10">
                        <i class="fa-brands fa-instagram text-base"></i>
                    </a>
                    <a href="https://www.youtube.com/@smkn2kra" target="_blank" rel="noopener" aria-label="YouTube"
                        class="w-9 h-9 rounded-lg bg-white/10 hover:bg-red-600 hover:text-white text-white flex items-center justify-center transition-all border border-white/10">
                        <i class="fa-brands fa-youtube text-base"></i>
                    </a>
                    <a href="https://www.tiktok.com/@smkn2kra" target="_blank" rel="noopener" aria-label="TikTok"
                        class="w-9 h-9 rounded-lg bg-white/10 hover:bg-black hover:text-white text-white flex items-center justify-center transition-all border border-white/10">
                        <i class="fa-brands fa-tiktok text-base"></i>
                    </a>
                    <a href="https://www.linkedin.com/school/smkn2karanganyar" target="_blank" rel="noopener"
                        aria-label="LinkedIn"
                        class="w-9 h-9 rounded-lg bg-white/10 hover:bg-blue-600 hover:text-white text-white flex items-center justify-center transition-all border border-white/10">
                        <i class="fa-brands fa-linkedin-in text-base"></i>
                    </a>
                </div>
            </div>

            <!-- Right Footer Google Maps -->
            <div class="lg:col-span-6">
                <div class="bg-white text-slate-800 rounded-2xl p-4 shadow-2xl relative overflow-hidden">
                    <div class="flex justify-between items-start mb-3 border-b border-slate-100 pb-2">
                        <div>
                            <h4 class="font-bold text-sm text-slate-900">
                                {{ $sekolah['nama_pendek'] ?? 'SMKN 2 Karanganyar' }}</h4>
                            <p class="text-[11px] text-slate-500">
                                {{ $sekolah['alamat'] ?? 'Jl. Yos Sudarso, Jengglong, Bejen, Kec. Karanganyar, Jawa Tengah 57716' }}
                            </p>
                            <div class="flex items-center gap-1 mt-1">
                                <span class="text-xs font-bold text-amber-500">{{ $sekolah['rating'] ?? '4.6' }}</span>
                                <div class="text-amber-400 text-[10px]"
                                    aria-label="Rating {{ $sekolah['rating'] ?? 4.6 }} dari 5">
                                    @php $rating = (float) ($sekolah['rating'] ?? 4.6); @endphp
                                    @for ($bintang = 1; $bintang <= 5; $bintang++)
                                        @if ($rating >= $bintang)
                                            <i class="fa-solid fa-star"></i>
                                        @elseif ($rating >= $bintang - 0.5)
                                            <i class="fa-solid fa-star-half-stroke"></i>
                                        @else
                                            <i class="fa-regular fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="text-[10px] text-blue-600">{{ $sekolah['jumlah_ulasan'] ?? 217 }}
                                    reviews</span>
                            </div>
                        </div>
                        <a href="{{ $sekolah['maps_url'] ?? 'https://maps.google.com/?q=SMK+Negeri+2+Karanganyar' }}"
                            target="_blank" rel="noopener"
                            class="bg-blue-50 text-brand-blue hover:bg-blue-100 p-2 rounded-lg text-xs font-semibold flex items-center gap-1">
                            <i class="fa-solid fa-diamond-turn-right"></i> Directions
                        </a>
                    </div>

                    <div
                        class="w-full h-44 bg-slate-200 rounded-lg relative overflow-hidden flex items-center justify-center">
                        <iframe title="Peta {{ $sekolah['nama_pendek'] ?? 'SMKN 2 Karanganyar' }}"
                            class="w-full h-full border-0 rounded-lg"
                            src="{{ $sekolah['maps_embed_url'] ?? 'https://maps.google.com/maps?q=SMK%20Negeri%202%20Karanganyar&t=&z=15&ie=UTF8&iwloc=&output=embed' }}"
                            loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>

        </div>
        <!-- Section Powered By -->
        <div class="mt-12 pt-8 border-t border-blue-400/30 text-center">
            <p class="text-xs uppercase tracking-widest text-blue-100 font-semibold mb-4">Powered by:</p>

            <!-- Container Putih Transparan Tone Lebih Terang -->
            <div
                class="bg-white/40 backdrop-blur-md border border-white/50 rounded-2xl px-6 py-4 inline-flex flex-wrap items-center justify-center gap-6 sm:gap-8 max-w-5xl mx-auto shadow-sm">
                <img src="{{ asset('assets/logo-jhic/logo1.png') }}" alt="JHIC 2.0"
                    class="h-8 sm:h-9 w-auto object-contain hover:scale-105 transition-transform">
                <img src="{{ asset('assets/logo-jhic/logo2.png') }}" alt="Jagoan Hosting"
                    class="h-8 sm:h-9 w-auto object-contain hover:scale-105 transition-transform">
                <img src="{{ asset('assets/logo-jhic/logo3.png') }}" alt="KOMDIGI"
                    class="h-8 sm:h-9 w-auto object-contain hover:scale-105 transition-transform">
                <img src="{{ asset('assets/logo-jhic/logo4.png') }}" alt="Garuda Spark"
                    class="h-8 sm:h-9 w-auto object-contain hover:scale-105 transition-transform">

                <!-- Logo Ke-5 Ukuran Diperkecil -->
                <img src="{{ asset('assets/logo-jhic/logo5.png') }}" alt="NGALUP"
                    class="h-5 sm:h-6 w-auto object-contain hover:scale-105 transition-transform">
            </div>
        </div>
    </div>

    <!-- Copyright Bottom Bar -->
    <div class="bg-[#1A1A1A] text-slate-400 text-xs text-center py-4 border-t border-slate-800">
        &copy; {{ now()->year }} {{ $sekolah['nama_pendek'] ?? 'SMKN 2 Karanganyar' }}. All Rights Reserved
    </div>
</footer>
