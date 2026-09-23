@extends('Admin.layout.app')

@section('title', 'Dashboard BKK & PKL - SMK Negeri 2 Karanganyar')

@section('content')
    <main class="flex-1 h-full overflow-y-auto p-6 lg:p-8 space-y-6">

        @include('Admin.PklBkk.partials.header', ['pklPage' => 'Dashboard'])

        <!-- 3 TOP STAT CARDS GRID -->
        <section class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100 flex items-center justify-between transition-transform duration-200 hover:-translate-y-1">
                <div>
                    <h3 class="text-xs md:text-sm font-extrabold text-brand-600 uppercase tracking-wide leading-snug max-w-[150px]">Perusahaan Mitra</h3>
                    <p class="text-[10px] text-slate-400 mt-1 font-medium">Tempat PKL terdaftar</p>
                </div>
                <div id="stat-mitra" class="text-4xl lg:text-5xl font-extrabold text-brand-600 pl-2">0</div>
            </div>

            <div class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100 flex items-center justify-between transition-transform duration-200 hover:-translate-y-1">
                <div>
                    <h3 class="text-xs md:text-sm font-extrabold text-brand-600 uppercase tracking-wide leading-snug max-w-[150px]">Loker Aktif BKK</h3>
                    <p class="text-[10px] text-slate-400 mt-1 font-medium">Lowongan kerja aktif</p>
                </div>
                <div id="stat-loker" class="text-4xl lg:text-5xl font-extrabold text-brand-600 pl-2">0</div>
            </div>

            <div class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100 flex items-center justify-between transition-transform duration-200 hover:-translate-y-1">
                <div>
                    <h3 class="text-xs md:text-sm font-extrabold text-brand-600 uppercase tracking-wide leading-snug max-w-[150px]">Data Pelamar &amp; Tracer</h3>
                    <p class="text-[10px] text-slate-400 mt-1 font-medium">Alumni terdaftar</p>
                </div>
                <div id="stat-pelamar" class="text-4xl lg:text-5xl font-extrabold text-brand-600 pl-2">0</div>
            </div>

        </section>

        <!-- Petunjuk ringkas -->
        <section class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100">
            <h2 class="text-sm font-extrabold text-brand-600 uppercase tracking-wider mb-4">Modul BKK &amp; PKL</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs text-slate-600">
                <a href="{{ route('pklbkk.loker') }}" class="rounded-xl border border-slate-200 p-4 hover:border-brand-500 hover:text-brand-600 transition-colors">
                    <i class="fa-solid fa-briefcase text-brand-600 text-lg mb-2 block"></i>
                    <span class="font-bold block">Lowongan Kerja</span>
                    Kelola lowongan kerja dari mitra DUDI.
                </a>
                <a href="{{ route('pklbkk.pelamar') }}" class="rounded-xl border border-slate-200 p-4 hover:border-brand-500 hover:text-brand-600 transition-colors">
                    <i class="fa-solid fa-user-graduate text-brand-600 text-lg mb-2 block"></i>
                    <span class="font-bold block">Data Pelamar &amp; Tracer</span>
                    Pantau status pelamar alumni &amp; tracer study.
                </a>
                <a href="{{ route('pklbkk.tempat') }}" class="rounded-xl border border-slate-200 p-4 hover:border-brand-500 hover:text-brand-600 transition-colors">
                    <i class="fa-solid fa-handshake text-brand-600 text-lg mb-2 block"></i>
                    <span class="font-bold block">Tempat PKL</span>
                    Kelola tempat PKL &amp; kuota siswa.
                </a>
                <a href="{{ route('pklbkk.jurnal') }}" class="rounded-xl border border-slate-200 p-4 hover:border-brand-500 hover:text-brand-600 transition-colors">
                    <i class="fa-solid fa-book-open text-brand-600 text-lg mb-2 block"></i>
                    <span class="font-bold block">Jurnal &amp; Nilai</span>
                    Verifikasi jurnal, absensi, dan rekap nilai.
                </a>
            </div>
        </section>

    </main>
@endsection

@push('scripts')
    <script>
        const pklCount = (key, fallback) => {
            try {
                const raw = JSON.parse(localStorage.getItem(key) || '[]');
                return raw.length || fallback;
            } catch {
                return fallback;
            }
        };

        const countAktif = (key) => {
            try {
                const raw = JSON.parse(localStorage.getItem(key) || '[]');
                return raw.filter(i => i.status === 'Aktif').length || 0;
            } catch {
                return 0;
            }
        };

        document.getElementById('stat-mitra').innerText = pklCount('pklbkk.mitras', 8);
        document.getElementById('stat-loker').innerText = countAktif('pklbkk.lokers') || pklCount('pklbkk.lokers', 8);
        document.getElementById('stat-pelamar').innerText = pklCount('pklbkk.pelamars', 8) || 8;
    </script>
@endpush