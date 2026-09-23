<header class="w-full bg-white rounded-2xl p-4 md:px-6 md:py-4 figma-card-shadow flex flex-col md:flex-row items-center justify-between gap-4 border border-slate-100">

    <!-- Breadcrumb Title -->
    <div class="flex items-center space-x-2 text-slate-800 text-sm md:text-base font-bold tracking-tight">
        <span class="uppercase text-slate-900 font-extrabold">Admin BKK &amp; PKL</span>
        <span class="text-slate-400 font-normal"><i class="fa-solid fa-chevron-right text-xs"></i></span>
        <span class="text-slate-500 font-medium">{{ $pklPage ?? 'Dashboard' }}</span>
    </div>

    <!-- Right Action Icons & User Info -->
    <div class="flex items-center space-x-4">

        <!-- Info Pill -->
        <div class="hidden lg:flex items-center space-x-2.5 bg-slate-100 hover:bg-slate-200/80 border border-slate-200/80 px-3.5 py-1.5 rounded-full transition-colors">
            <i class="fa-solid fa-briefcase text-brand-600"></i>
            <span class="text-[11px] font-semibold text-slate-600">Bursa Kerja Khusus &amp; Praktik Kerja Lapangan</span>
        </div>

        <!-- Gear Icon Button -->
        <button title="Pengaturan" class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 text-brand-600 flex items-center justify-center transition-colors">
            <i class="fa-solid fa-gear text-lg"></i>
        </button>

        <!-- User Profile Badge Pill -->
        <div class="flex items-center space-x-3 bg-slate-100 hover:bg-slate-200/80 border border-slate-200/80 px-3.5 py-1.5 rounded-full cursor-pointer transition-colors">
            <div class="w-7 h-7 rounded-full bg-white text-brand-600 border border-slate-300 flex items-center justify-center">
                <i class="fa-solid fa-user text-xs"></i>
            </div>
            <div class="text-left">
                <p class="text-xs font-bold text-slate-800 leading-tight">{{ auth()->user()->name }}</p>
                <p class="text-[10px] text-slate-500 leading-tight">Admin BKK &amp; PKL</p>
            </div>
            <i class="fa-solid fa-chevron-down text-xs text-slate-500 ml-1"></i>
        </div>

    </div>
</header>