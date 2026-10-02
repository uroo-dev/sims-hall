<!-- FOOTER & PETA LOKASI -->
<footer class="bg-brand-blue text-white rounded-t-[3rem] pt-16 pb-0 mt-12 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

            <div class="lg:col-span-6 space-y-6">
                <div class="flex items-center gap-3">
                    <div class="flex items-center">
                        <img src="{{ asset('assets/smk hebat.png') }}" alt="Logo SMK Bisa-Hebat"
                            class="h-24 sm:h-32 w-auto object-contain">
                    </div>
                </div>
                <p class="text-blue-100 text-sm leading-relaxed max-w-md">
                    SMK Negeri 2 Karanganyar adalah salah satu Sekolah Menengah Kejuruan favorit di Kabupaten
                    Karanganyar. Serta merupakan sekolah yang berpendidikan karakter, berwawasan, disiplin, tanggung
                    jawab, dan bermoral baik.
                </p>
            </div>

            <div class="lg:col-span-6">
                <div class="bg-white text-slate-800 rounded-2xl p-4 shadow-2xl relative overflow-hidden">
                    <div class="flex justify-between items-start mb-3 border-b pb-2">
                        <div>
                            <h4 class="font-bold text-sm text-slate-900">SMKN 2 Karanganyar</h4>
                            <p class="text-[11px] text-slate-500">Jl. Yos Sudarso, Jengglong, Bejen, Kec.
                                Karanganyar, Jawa Tengah 57716</p>
                            <div class="flex items-center gap-1 mt-1">
                                <span class="text-xs font-bold text-amber-500">4.6</span>
                                <div class="text-amber-400 text-[10px]">
                                     <i class="fa-solid fa-star"></i>
                                     <i class="fa-solid fa-star"></i>
                                     <i class="fa-solid fa-star"></i>
                                     <i class="fa-solid fa-star"></i>
                                     <i class="fa-solid fa-star-half-stroke"></i>
                                </div>
                                <span class="text-[10px] text-blue-600 underline cursor-pointer">217 reviews</span>
                            </div>
                        </div>
                        <button
                            onclick="openModal('Peta Lokasi Google Maps', 'Membuka navigasi lokasi SMKN 2 Karanganyar di Google Maps.')"
                            class="bg-blue-50 text-brand-blue hover:bg-blue-100 p-2 rounded-lg text-xs font-semibold flex items-center gap-1">
                            <i class="fa-solid fa-diamond-turn-right text-brand-blue"></i> Directions
                        </button>
                    </div>

                    <div
                        class="w-full h-44 bg-slate-200 rounded-lg relative overflow-hidden flex items-center justify-center">
                        <iframe title="SMKN 2 Karanganyar Map" class="w-full h-full border-0 rounded-lg"
                            src="https://maps.google.com/maps?q=SMK%20Negeri%202%20Karanganyar&t=&z=15&ie=UTF8&iwloc=&output=embed"
                            loading="lazy">
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

    <div class="bg-[#1A1A1A] text-slate-400 text-xs text-center py-4 border-t border-slate-800">
        © 2026 SMKN 2 Karanganyar. All Rights Reserved
    </div>
</footer>
