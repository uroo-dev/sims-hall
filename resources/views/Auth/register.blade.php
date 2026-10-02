@extends('Auth.layout.app')

@section('title', 'Daftar Akun Pelanggan - SMK NEGERI 2 KARANGANYAR')

@push('styles')
    <style>
        /* Pulse & floating effects */
        @keyframes pulseGlow {
            0%, 100% { opacity: 0.4; transform: scale(1); }
            50% { opacity: 0.8; transform: scale(1.08); }
        }

        .animate-pulse-glow {
            animation: pulseGlow 4s ease-in-out infinite;
        }

        /* Grid dot & plus patterns */
        .bg-grid-pattern {
            background-image: radial-gradient(rgba(255, 255, 255, 0.25) 1.5px, transparent 1.5px);
            background-size: 16px 16px;
        }

        .bg-plus-pattern {
            background-image: radial-gradient(#0284c7 1px, transparent 1px);
            background-size: 10px 10px;
        }

        .school-watermark {
            background-image: linear-gradient(to bottom, rgba(2, 106, 196, 0.85), rgba(1, 55, 115, 0.95)),
                url('https://images.unsplash.com/photo-1562774053-701939374585?auto=format&fit=crop&w=1200&q=80');
            background-size: cover;
            background-position: center;
        }

        .wave-divider {
            filter: drop-shadow(6px 0px 10px rgba(0, 0, 0, 0.15));
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
    </style>
@endpush

@section('content')
    <!-- Main Card Container -->
    <div class="w-full max-w-5xl bg-white rounded-3xl shadow-2xl overflow-hidden min-h-[640px] relative flex flex-col md:flex-row transition-all duration-300">

        <!-- ================= LEFT PANEL: REGISTRATION FORM SECTION ================= -->
        <div class="w-full md:w-1/2 bg-white p-6 sm:p-10 flex flex-col justify-between relative z-10 custom-scrollbar max-h-[92vh] md:max-h-none overflow-y-auto">

            <!-- Top Left Decorative Accent -->
            <div class="absolute -top-6 -left-6 w-20 h-20 bg-sky-600 transform rotate-45 pointer-events-none rounded-lg shadow-md"></div>

            <!-- Top Navigation & School Header -->
            <div class="flex items-center justify-between relative z-20 mb-3">
                <a href="{{ route('login') }}" class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center transition-all duration-200 active:scale-95 shadow-sm" title="Kembali ke Halaman Login">
                    <i class="fa-solid fa-arrow-left text-base"></i>
                </a>

                <!-- School Small Emblem Header -->
                <div class="flex items-center space-x-2.5 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-100 shadow-sm">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-white shadow-sm overflow-hidden bg-white">
                        <img src="{{ asset('assets/logosmkk.png') }}" alt="Logo SMKN 2 Karanganyar" class="w-full h-full object-contain">
                    </div>
                    <div class="text-left">
                        <span class="block text-[11px] font-bold text-slate-800 tracking-wider leading-tight">SMK NEGERI 2</span>
                        <span class="block text-[9px] font-semibold text-slate-500 uppercase tracking-widest leading-tight">KARANGANYAR</span>
                    </div>
                </div>
            </div>

            <!-- Main Form Content -->
            <div class="my-auto py-2 max-w-sm w-full mx-auto relative z-20">

                <!-- Header Title -->
                <div class="text-center mb-4">
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-sky-600 tracking-tight">Daftar Akun</h1>
                    <div class="w-12 h-1 bg-sky-600 rounded-full mx-auto mt-1.5"></div>
                    <p class="text-[11px] text-slate-500 font-medium mt-2 leading-tight px-2">
                        Pendaftaran akun peminjam aula SMK Negeri 2 Karanganyar
                    </p>
                    <div class="mt-2.5 inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold bg-sky-50 text-sky-700 border border-sky-200">
                        <i class="fa-solid fa-shield-halved text-sky-500"></i> Role Akun: Pelanggan Peminjam
                    </div>
                </div>

                <!-- Alert Errors Global (jika ada) -->
                @if ($errors->any())
                    <div class="mb-4 p-3 rounded-2xl text-xs font-medium bg-rose-50 text-rose-700 border border-rose-200 flex items-start gap-2.5">
                        <i class="fa-solid fa-circle-exclamation text-rose-500 mt-0.5 flex-shrink-0"></i>
                        <div class="space-y-1">
                            <span class="font-bold">Pendaftaran gagal:</span>
                            <ul class="list-disc list-inside space-y-0.5 text-[11px]">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <!-- Form Registrasi -->
                <form action="{{ route('registrasi.store') }}" method="POST" id="registerForm" class="space-y-3">
                    @csrf

                    <!-- 1. Nama Lengkap / Instansi Input -->
                    <div>
                        <label for="regName" class="block text-[11px] font-semibold text-slate-700 mb-1">
                            Nama Lengkap / Instansi <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-address-card text-xs"></i>
                            </div>
                            <input
                                type="text"
                                id="regName"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                class="w-full pl-9 pr-3.5 py-2.5 bg-white text-slate-800 text-xs rounded-full border-2 @error('name') border-rose-400 @else border-sky-500 @enderror focus:border-sky-600 focus:ring-4 focus:ring-sky-100 focus:outline-none transition-all placeholder:text-slate-400 font-medium"
                                placeholder="Nama Lengkap / Nama Instansi Pemohon"
                            >
                        </div>
                        @error('name')
                            <p class="text-[10px] text-rose-600 mt-1 pl-3">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 2. Username Input -->
                    <div>
                        <label for="regUsername" class="block text-[11px] font-semibold text-slate-700 mb-1">
                            Username <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-user text-xs"></i>
                            </div>
                            <input
                                type="text"
                                id="regUsername"
                                name="username"
                                value="{{ old('username') }}"
                                required
                                autocomplete="username"
                                class="w-full pl-9 pr-3.5 py-2.5 bg-white text-slate-800 text-xs rounded-full border-2 @error('username') border-rose-400 @else border-sky-500 @enderror focus:border-sky-600 focus:ring-4 focus:ring-sky-100 focus:outline-none transition-all placeholder:text-slate-400 font-medium"
                                placeholder="Username untuk login (tanpa spasi)"
                            >
                        </div>
                        @error('username')
                            <p class="text-[10px] text-rose-600 mt-1 pl-3">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 3. Email Input -->
                    <div>
                        <label for="regEmail" class="block text-[11px] font-semibold text-slate-700 mb-1">
                            Email Kontak / Instansi <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-envelope text-xs"></i>
                            </div>
                            <input
                                type="email"
                                id="regEmail"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autocomplete="email"
                                class="w-full pl-9 pr-3.5 py-2.5 bg-white text-slate-800 text-xs rounded-full border-2 @error('email') border-rose-400 @else border-sky-500 @enderror focus:border-sky-600 focus:ring-4 focus:ring-sky-100 focus:outline-none transition-all placeholder:text-slate-400 font-medium"
                                placeholder="email@instansi.com"
                            >
                        </div>
                        @error('email')
                            <p class="text-[10px] text-rose-600 mt-1 pl-3">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 4. Password Input -->
                    <div>
                        <label for="regPassword" class="block text-[11px] font-semibold text-slate-700 mb-1">
                            Password <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-lock text-xs"></i>
                            </div>
                            <input
                                type="password"
                                id="regPassword"
                                name="password"
                                required
                                autocomplete="new-password"
                                class="w-full pl-9 pr-3.5 py-2.5 bg-white text-slate-800 text-xs rounded-full border-2 @error('password') border-rose-400 @else border-sky-500 @enderror focus:border-sky-600 focus:ring-4 focus:ring-sky-100 focus:outline-none transition-all placeholder:text-slate-400 font-medium"
                                placeholder="Minimal 6 karakter"
                            >
                        </div>
                        @error('password')
                            <p class="text-[10px] text-rose-600 mt-1 pl-3">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 5. Konfirmasi Password Input -->
                    <div>
                        <label for="regPasswordConfirmation" class="block text-[11px] font-semibold text-slate-700 mb-1">
                            Konfirmasi Password <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-shield-halved text-xs"></i>
                            </div>
                            <input
                                type="password"
                                id="regPasswordConfirmation"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                class="w-full pl-9 pr-3.5 py-2.5 bg-white text-slate-800 text-xs rounded-full border-2 border-sky-500 focus:border-sky-600 focus:ring-4 focus:ring-sky-100 focus:outline-none transition-all placeholder:text-slate-400 font-medium"
                                placeholder="Ketik ulang password"
                            >
                        </div>
                    </div>

                    <!-- Toggle Password Visibility -->
                    <div class="flex items-center justify-start pl-2 pt-0.5">
                        <label class="inline-flex items-center cursor-pointer group">
                            <input
                                type="checkbox"
                                id="regTogglePassword"
                                class="rounded border-slate-300 text-sky-600 focus:ring-sky-500 w-3.5 h-3.5 cursor-pointer transition"
                            >
                            <span class="ml-2 text-[11px] text-slate-500 font-medium group-hover:text-slate-700 transition">
                                Lihat Password
                            </span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        id="btnSubmitDaftar"
                        class="w-full py-3 px-6 rounded-full bg-sky-600 hover:bg-sky-700 active:bg-sky-800 text-white font-semibold text-xs shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center space-x-2 mt-3 cursor-pointer"
                    >
                        <span>Daftar Akun Pelanggan</span>
                        <i id="spinnerDaftar" class="fa-solid fa-circle-notch fa-spin hidden text-xs"></i>
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative my-4 flex items-center justify-center">
                    <div class="w-full border-t border-slate-200"></div>
                    <div class="absolute bg-white px-3 text-[10px] font-semibold text-slate-400 uppercase tracking-widest">
                        atau
                    </div>
                </div>

                <!-- Footer to Login -->
                <div class="text-center">
                    <p class="text-xs text-slate-500 font-medium">
                        Sudah memiliki akun?
                        <a href="{{ route('login') }}" class="text-sky-600 font-bold hover:underline hover:text-sky-800 transition">
                            Login Sekarang
                        </a>
                    </p>
                </div>
            </div>

            <!-- Bottom Left Decorative Grid -->
            <div class="absolute bottom-3 left-3 w-16 h-16 bg-plus-pattern opacity-40 pointer-events-none"></div>

            <!-- Footer Copyright -->
            <div class="text-center text-[10px] text-slate-400 font-light mt-2">
                &copy; {{ date('Y') }} Portal Peminjaman Aula SMKN 2 Karanganyar
            </div>
        </div>

        <!-- ================= RIGHT PANEL: BANNER & WATERMARK SECTION ================= -->
        <div class="relative w-full md:w-1/2 school-watermark text-white flex flex-col justify-between p-8 sm:p-12 overflow-hidden min-h-[380px] md:min-h-full">

            <!-- Top Grid Pattern Overlay -->
            <div class="absolute top-6 right-6 w-24 h-24 bg-grid-pattern opacity-60 pointer-events-none"></div>

            <!-- Glowing Floating Circles -->
            <div class="absolute top-1/4 right-1/4 w-12 h-12 border-2 border-white/20 rounded-full pointer-events-none animate-float" style="animation-delay: 0.5s;"></div>
            <div class="absolute bottom-1/3 right-10 w-8 h-8 border border-white/30 rounded-full pointer-events-none animate-float" style="animation-delay: 2.2s;"></div>
            <div class="absolute top-1/2 left-10 w-6 h-6 border border-white/25 rounded-full pointer-events-none animate-float" style="animation-delay: 1.5s;"></div>

            <!-- Center Animated Emblem Badge -->
            <div class="my-auto relative z-10 flex flex-col items-center justify-center text-center py-8">
                <div class="animate-float relative flex items-center justify-center">
                    <!-- Dashed rotating outer ring -->
                    <div class="absolute inset-0 rounded-full border-2 border-dashed border-white/50 w-28 h-28 -m-2 animate-spin" style="animation-duration: 30s;"></div>
                    <!-- Inner glowing circular emblem -->
                    <div class="w-24 h-24 rounded-full bg-sky-600/70 border-2 border-white/70 backdrop-blur-md flex items-center justify-center shadow-2xl transition-transform hover:scale-110 duration-300 group cursor-pointer">
                        <i class="fa-solid fa-file-signature text-3xl text-white drop-shadow transform group-hover:rotate-6 transition duration-300"></i>
                    </div>
                </div>

                <span class="mt-6 text-xs tracking-wider uppercase font-medium text-sky-100 bg-white/10 px-4 py-1.5 rounded-full border border-white/20 backdrop-blur-sm">
                    Pendaftaran Online Aula
                </span>

                <h2 class="mt-4 text-xl sm:text-2xl font-extrabold text-white drop-shadow-md max-w-xs">
                    Layanan Cepat, Transparan, & Terintegrasi
                </h2>

                <p class="mt-2 text-xs text-blue-100 max-w-xs leading-relaxed font-light drop-shadow">
                    Daftar akun pelanggan untuk mengecek ketersediaan kalender aula, mengajukan jadwal peminjaman, serta memantau status persetujuan secara real-time.
                </p>
            </div>

            <!-- Bottom Dynamic Waves -->
            <div class="absolute inset-x-0 bottom-0 pointer-events-none overflow-hidden leading-none z-0">
                <svg class="relative block w-full h-24 text-sky-500/30 animate-wave-slow" viewBox="0 0 1200 120" preserveAspectRatio="none">
                    <path d="M0,0 C150,90 350,-40 500,50 C650,140 900,10 1200,40 L1200,120 L0,120 Z" fill="currentColor"></path>
                </svg>
                <svg class="absolute bottom-0 left-0 w-full h-16 text-sky-400/20 animate-wave-fast" viewBox="0 0 1200 120" preserveAspectRatio="none">
                    <path d="M0,30 C300,110 500,10 700,70 C900,130 1100,20 1200,50 L1200,120 L0,120 Z" fill="currentColor"></path>
                </svg>
            </div>

            <!-- Left Wave Divider on MD Screens -->
            <div class="hidden md:block absolute -left-1 top-0 bottom-0 w-16 pointer-events-none z-20 transform rotate-180">
                <svg class="h-full w-full text-white wave-divider" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="M0,0 C60,25 60,75 0,100 L100,100 L100,0 Z" fill="currentColor"></path>
                </svg>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        const passwordInput = document.getElementById('regPassword');
        const passwordConfirmInput = document.getElementById('regPasswordConfirmation');
        const toggleCheckbox = document.getElementById('regTogglePassword');

        if (toggleCheckbox && passwordInput && passwordConfirmInput) {
            toggleCheckbox.addEventListener('change', function () {
                const type = this.checked ? 'text' : 'password';
                passwordInput.type = type;
                passwordConfirmInput.type = type;
            });
        }

        const form = document.getElementById('registerForm');
        const btn = document.getElementById('btnSubmitDaftar');
        const spinner = document.getElementById('spinnerDaftar');

        if (form && btn && spinner) {
            form.addEventListener('submit', function () {
                btn.disabled = true;
                btn.classList.add('opacity-80', 'cursor-not-allowed');
                spinner.classList.remove('hidden');
            });
        }
    </script>
@endpush
