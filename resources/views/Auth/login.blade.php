@extends('Auth.layout.app')

@section('title', 'Masuk | SIM Sarpras SMK N 2 Kra')

@section('content')
    <div class="flex min-h-screen w-full relative overflow-x-hidden">

        <!-- LEFT SIDEBAR -->
        <aside class="w-72 min-h-screen flex flex-col relative z-20 shrink-0 transition-all duration-300">

            <!-- Top School Branding Box (White Background) -->
            <div class="bg-white px-5 py-4 flex items-center space-x-3 h-20 border-b border-slate-100">
                <!-- School Logo SVG/Badge -->
                <div class="w-10 h-10 rounded-full bg-brand-600 flex items-center justify-center text-white shadow-md shrink-0">
                    <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-black text-slate-800 tracking-wider uppercase leading-snug">SMK NEGERI 2</p>
                    <p class="text-[11px] font-bold text-slate-600 tracking-wider uppercase leading-tight">KARANGANYAR</p>
                </div>
            </div>

            <!-- Blue Sidebar Body with Rounded Top-Right Curved Shoulder -->
            <div class="bg-brand-600 flex-1 flex flex-col justify-between p-5 pt-6 rounded-tr-[50px] shadow-xl">
                <div class="space-y-2">
                    <!-- Active Capsule Item: Portal Admin -->
                    <div class="flex items-center space-x-3.5 px-5 py-3 rounded-full bg-white text-brand-600 font-semibold shadow-md transition-all duration-200">
                        <i class="fa-solid fa-lock text-lg"></i>
                        <span class="text-sm">Portal Admin</span>
                    </div>

                    <!-- Subheader Category Divider -->
                    <div class="pt-4 pb-2 px-2 flex items-center justify-between text-white/70">
                        <span class="text-xs font-medium tracking-wide">SIM Sarpras</span>
                        <div class="h-[1px] w-16 bg-white/30 rounded-full"></div>
                    </div>

                    <!-- Intro Points -->
                    <div class="px-2 pt-1 text-white/85 text-sm space-y-2.5 leading-relaxed">
                        <p>Kelola peminjaman aula &amp; fasilitas sekolah dalam satu sistem.</p>
                        <p class="flex items-start space-x-2">
                            <i class="fa-solid fa-circle-check text-xs mt-1"></i>
                            <span>Persetujuan &amp; laporan operasional</span>
                        </p>
                        <p class="flex items-start space-x-2">
                            <i class="fa-solid fa-circle-check text-xs mt-1"></i>
                            <span>PPDB, PKL/BKK, &amp; kesiswaan</span>
                        </p>
                    </div>
                </div>

                <!-- Bottom Info Card -->
                <div class="pt-5">
                    <div class="rounded-2xl bg-white/10 px-4 py-3 text-center">
                        <p class="text-[11px] text-white/80 font-medium">SMK Negeri 2 Karanganyar</p>
                        <p class="text-[10px] text-white/60 mt-0.5">&copy; {{ date('Y') }}</p>
                    </div>
                </div>
            </div>

        </aside>

        <!-- RIGHT MAIN CONTENT -->
        <main class="flex-1 p-6 lg:p-8 overflow-y-auto space-y-6">

            <!-- TOP HEADER BAR -->
            <header class="w-full bg-white rounded-2xl p-4 md:px-6 md:py-4 figma-card-shadow flex flex-col md:flex-row items-center justify-between gap-4 border border-slate-100">

                <!-- Breadcrumb Title -->
                <div class="flex items-center space-x-2 text-slate-800 text-sm md:text-base font-bold tracking-tight">
                    <span class="uppercase text-slate-900 font-extrabold">SIM Sarpras</span>
                    <span class="text-slate-400 font-normal"><i class="fa-solid fa-chevron-right text-xs"></i></span>
                    <span class="text-slate-500 font-medium">Masuk</span>
                </div>

                <!-- Info Pill -->
                <div class="hidden md:flex items-center space-x-2.5 bg-slate-100 hover:bg-slate-200/80 border border-slate-200/80 px-3.5 py-1.5 rounded-full transition-colors">
                    <i class="fa-solid fa-circle-info text-brand-600"></i>
                    <span class="text-[11px] font-semibold text-slate-600">Butuh bantuan? Hubungi admin</span>
                </div>
            </header>

            <!-- LOGIN CARD -->
            <div class="flex items-center justify-center">
                <div class="w-full max-w-md py-6">
                    <div class="bg-white rounded-2xl p-7 sm:p-8 figma-card-shadow border border-slate-100">
                        <div class="mb-6">
                            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Masuk ke Portal Admin</h2>
                            <p class="text-sm text-slate-500 mt-1.5">Gunakan akun admin Anda untuk melanjutkan.</p>
                        </div>

                        @if ($errors->any())
                            <div class="mb-5 rounded-xl border border-red-100 bg-red-50 px-4 py-3 text-sm font-medium text-red-700" role="alert">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}" class="space-y-5">
                            @csrf

                            <div class="space-y-2">
                                <label for="username" class="block text-sm font-semibold text-slate-700">Username</label>
                                <div class="relative">
                                    <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" aria-hidden="true">
                                        <i class="fa-solid fa-user w-4 text-center"></i>
                                    </span>
                                    <input
                                        id="username"
                                        name="username"
                                        type="text"
                                        value="{{ old('username') }}"
                                        required
                                        autofocus
                                        autocomplete="username"
                                        placeholder="Masukkan username"
                                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm text-slate-900 placeholder:text-slate-500 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-2 focus:ring-brand-600/15"
                                    >
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
                                <div class="relative">
                                    <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" aria-hidden="true">
                                        <i class="fa-solid fa-lock w-4 text-center"></i>
                                    </span>
                                    <input
                                        id="password"
                                        name="password"
                                        type="password"
                                        required
                                        autocomplete="current-password"
                                        placeholder="Masukkan password"
                                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-11 text-sm text-slate-900 placeholder:text-slate-500 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-2 focus:ring-brand-600/15"
                                    >
                                    <button
                                        type="button"
                                        id="toggle-password"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 w-8 h-8 flex items-center justify-center rounded-lg transition-colors"
                                        aria-label="Tampilkan password"
                                    >
                                        <i class="fa-solid fa-eye text-slate-400 hover:text-brand-600" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="flex items-center pt-1">
                                <label for="remember" class="flex items-center space-x-2.5 text-sm text-slate-600 cursor-pointer select-none">
                                    <input
                                        id="remember"
                                        name="remember"
                                        type="checkbox"
                                        value="1"
                                        class="w-4 h-4 rounded border-slate-300 text-brand-600 focus:ring-brand-600/30 cursor-pointer"
                                    >
                                    <span>Ingat saya</span>
                                </label>
                            </div>

                            <button
                                type="submit"
                                class="w-full py-3 px-6 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-full shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center space-x-2.5 text-sm active:scale-[0.98]"
                            >
                                <span>Masuk</span>
                                <span class="w-7 h-7 rounded-full bg-white/15 flex items-center justify-center" aria-hidden="true">
                                    <i class="fa-solid fa-arrow-right text-xs"></i>
                                </span>
                            </button>
                        </form>
                    </div>

                    <p class="text-center text-xs text-slate-500 mt-6">&copy; {{ date('Y') }} SMK Negeri 2 Karanganyar</p>
                </div>
            </div>

        </main>

    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('toggle-password')?.addEventListener('click', function () {
            const input = document.getElementById('password');
            const icon = this.querySelector('i');
            const isVisible = input.type === 'text';

            input.type = isVisible ? 'password' : 'text';
            icon.classList.toggle('fa-eye', isVisible);
            icon.classList.toggle('fa-eye-slash', !isVisible);
        });
    </script>
@endpush