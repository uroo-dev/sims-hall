@extends('Auth.layout.app')

@section('content')
        <!-- ================= LEFT PANEL (BLUE BANNER) ================= -->
        <div
            class="relative w-full md:w-1/2 school-watermark text-white flex flex-col justify-between p-8 sm:p-12 overflow-hidden min-h-[380px] md:min-h-full">

            <!-- Top Geometric Patterns & Dots -->
            <div class="absolute top-6 left-6 w-24 h-24 bg-grid-pattern opacity-60 pointer-events-none"></div>
            <div class="absolute bottom-6 left-6 w-20 h-20 bg-grid-pattern opacity-60 pointer-events-none"></div>

            <!-- Top Right Corner Curved Accent -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-400/10 rounded-full blur-2xl pointer-events-none">
            </div>

            <!-- Welcome Text Content -->
            <div class="relative z-10 space-y-3 mt-4 sm:mt-6">
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight drop-shadow-md">
                    Selamat Datang
                </h1>
                <p class="text-blue-100 text-sm sm:text-base font-light max-w-sm leading-relaxed drop-shadow">
                    Akses portal akademik dengan mudah, cepat, dan aman.
                </p>
            </div>

            <!-- Floating Central Book Badge Badge -->
            <div class="relative z-10 flex justify-center items-center my-8 md:my-auto">
                <div class="animate-float relative flex items-center justify-center">
                    <!-- Concentric animated rings -->
                    <div class="absolute inset-0 rounded-full border-2 border-dashed border-white/40 w-28 h-28 -m-2 animate-spin-slow"
                        style="animation-duration: 25s;"></div>
                    <div
                        class="w-24 h-24 rounded-full bg-blue-600/60 border-2 border-white/60 backdrop-blur-md flex items-center justify-center shadow-lg transition-transform hover:scale-105 duration-300">
                        <i class="fa-solid fa-book-open text-3xl text-white drop-shadow"></i>
                    </div>
                </div>
            </div>

            <!-- Decorative Floating Rings -->
            <div class="absolute top-1/3 right-12 w-10 h-10 border-2 border-white/20 rounded-full pointer-events-none animate-float"
                style="animation-delay: 1s;"></div>
            <div class="absolute bottom-1/4 left-1/3 w-6 h-6 border border-white/30 rounded-full pointer-events-none animate-float"
                style="animation-delay: 2s;"></div>

            <!-- Bottom Dynamic Wave SVGs -->
            <div class="absolute inset-x-0 bottom-0 pointer-events-none overflow-hidden leading-none z-0">
                <svg class="relative block w-full h-20 text-blue-500/30 animate-wave-slow" viewBox="0 0 1200 120"
                    preserveAspectRatio="none">
                    <path d="M0,0 C150,90 350,-40 500,50 C650,140 900,10 1200,40 L1200,120 L0,120 Z"
                        fill="currentColor"></path>
                </svg>
                <svg class="absolute bottom-0 left-0 w-full h-16 text-blue-400/20 animate-wave-fast"
                    viewBox="0 0 1200 120" preserveAspectRatio="none">
                    <path d="M0,30 C300,110 500,10 700,70 C900,130 1100,20 1200,50 L1200,120 L0,120 Z"
                        fill="currentColor"></path>
                </svg>
            </div>

            <!-- Curved Edge Transition for Large Screens (Wave Divider effect) -->
            <div class="hidden md:block absolute -right-1 top-0 bottom-0 w-16 pointer-events-none z-20">
                <svg class="h-full w-full text-white wave-divider" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="M0,0 C60,25 60,75 0,100 L100,100 L100,0 Z" fill="currentColor"></path>
                </svg>
            </div>
        </div>

        <!-- ================= RIGHT PANEL (LOGIN FORM) ================= -->
        <div class="w-full md:w-1/2 bg-white p-8 sm:p-12 flex flex-col justify-between relative z-10">

            <!-- Top Header & School Badge -->
            <div class="flex items-center justify-between">
                <!-- Back Button -->
                <a href="index.html" type="button"
                    class="w-10 h-10 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-all duration-200 active:scale-95 shadow-sm"
                    title="Kembali">
                    <i class="fa-solid fa-arrow-left text-base"></i>
                </a>

                <!-- School Logo Header -->
                <div
                    class="flex items-center space-x-3 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-100 shadow-sm">
                    <!-- School Logo Emblem SVG/Icon placeholder -->
                    <div
                        class="w-10 h-10 rounded-full flex items-center justify-center text-white shadow-sm overflow-hidden">
                        <img src="{{ asset('assets/logosmkk.png') }}" alt="Logo SMK Negeri 2 Karanganyar"
                            class="w-full h-full object-contain">
                    </div>
                    <div class="text-left">
                        <span class="block text-xs font-bold text-slate-800 tracking-wider">SMK NEGERI 2</span>
                        <span
                            class="block text-[10px] font-semibold text-slate-500 uppercase tracking-widest">KARANGANYAR</span>
                    </div>
                </div>
            </div>

            <!-- Main Form Content -->
            <div class="my-auto py-6 max-w-sm w-full mx-auto">

                <!-- LEFT PANEL: BLUE BANNER WELCOME SECTION -->


                <!-- Main Login Form -->
                <div class="my-auto py-6 max-w-sm w-full mx-auto">

                    <!-- Title -->
                    <div class="text-center mb-8">
                        <h2 class="text-5xl font-bold text-sky-800 tracking-tight">
                            Login
                        </h2>

                        <div class="w-20 h-1 bg-sky-600 rounded-full mx-auto mt-2"></div>
                    </div>


                    <!-- Error Login -->
                    @if ($errors->any())
                        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"
                            role="alert">
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-circle-exclamation mt-0.5"></i>

                                <span>
                                    {{ $errors->first() }}
                                </span>
                            </div>
                        </div>
                    @endif


                    <!-- Laravel Login Form -->
                    <form method="POST" action="{{ route('login') }}" id="loginForm" class="space-y-5">

                        @csrf


                        <!-- Username -->
                        <div class="relative">

                            <!-- Icon -->
                            <div
                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-700">

                                <i class="fa-solid fa-user text-sm"></i>

                            </div>


                            <!-- Input -->
                            <input type="text" id="loginUsername" name="username" value="{{ old('username') }}"
                                required autofocus autocomplete="username"
                                class="w-full pl-11 pr-4 py-3 bg-white text-slate-800 text-sm rounded-full border-2 border-sky-500 focus:border-sky-600 focus:ring-4 focus:ring-sky-100 focus:outline-none transition-all placeholder:text-slate-400 font-medium"
                                placeholder="Masukkan Username">

                        </div>


                        <!-- Password -->
                        <div class="relative">

                            <!-- Icon -->
                            <div
                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-700">

                                <i class="fa-solid fa-lock text-sm"></i>

                            </div>


                            <!-- Password Input -->
                            <input type="password" id="loginPassword" name="password" required
                                autocomplete="current-password"
                                class="w-full pl-11 pr-12 py-3 bg-white text-slate-800 text-sm rounded-full border-2 border-sky-500 focus:border-sky-600 focus:ring-4 focus:ring-sky-100 focus:outline-none transition-all placeholder:text-slate-400 font-medium"
                                placeholder="Masukkan Password">


                            <!-- Show Password Button -->
                            <button type="button" id="toggleLoginPassword"
                                class="absolute right-3 top-1/2 -translate-y-1/2 w-8 h-8 flex items-center justify-center rounded-full text-slate-400 hover:text-sky-600 transition-all"
                                aria-label="Tampilkan password">

                                <i class="fa-solid fa-eye text-sm"></i>

                            </button>

                        </div>


                        <!-- Remember Me -->
                        <div class="flex items-center justify-start pl-2">

                            <label class="inline-flex items-center cursor-pointer group">

                                <input type="checkbox" name="remember" value="1"
                                    class="rounded border-slate-300 text-sky-600 focus:ring-sky-500 w-4 h-4 cursor-pointer transition">

                                <span
                                    class="ml-2 text-xs text-slate-500 font-medium group-hover:text-slate-700 transition">

                                    Ingat saya

                                </span>

                            </label>

                        </div>


                        <!-- Login Button -->
                        <button type="submit" id="btnLoginSubmit"
                            class="w-full py-3.5 px-6 rounded-full bg-sky-600 hover:bg-sky-700 active:bg-sky-800 text-white font-semibold text-sm shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center space-x-2">

                            <span id="loginButtonText">
                                Login
                            </span>

                            <i id="spinnerLogin" class="fa-solid fa-circle-notch fa-spin hidden text-sm">
                            </i>

                        </button>

                    </form>


                    <!-- Divider -->
                    <div class="relative my-8 flex items-center justify-center">

                        <div class="w-full border-t border-slate-200"></div>

                        <div class="absolute bg-white px-2">

                            <div class="w-2 h-2 bg-sky-600 rotate-45"></div>

                        </div>

                    </div>


                    <!-- Register -->
                    <div class="text-center">

                        <p class="text-xs text-slate-500 font-medium">

                            Belum memiliki akun?

                            <a href="{{ url('/registrasi') }}"
                                class="text-sky-600 font-bold hover:underline hover:text-sky-800 transition">

                                Daftar Sekarang

                            </a>

                        </p>

                    </div>

                </div>

                <div class="text-center text-[11px] text-slate-400 font-light">
                    &copy; <span class="yearVal"></span> Portal Akademik SMKN 2 Karanganyar
                </div>
            </div>
        </div>
    </div>
    </div>

@endsection