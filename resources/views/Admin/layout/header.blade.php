<header class="w-full bg-white rounded-2xl p-4 md:px-6 md:py-4 figma-card-shadow flex flex-col md:flex-row items-center justify-between gap-4 border border-slate-100">

    <!-- Breadcrumb Title -->
    <div class="flex items-center space-x-2 text-slate-800 text-sm md:text-base font-bold tracking-tight">
        <span id="breadcrumb-role" class="uppercase text-slate-900 font-extrabold">{{ strtoupper(str_replace('_', ' ', auth()->user()->role)) }}</span>
        <span class="text-slate-400 font-normal"><i class="fa-solid fa-chevron-right text-xs"></i></span>
        <span id="breadcrumb-page" class="text-slate-500 font-medium">Dashboard</span>
    </div>

    <!-- Right Action Icons & User Info -->
    <div class="flex items-center space-x-4">

        <!-- Quick Role Switcher (Added for Lomba Demo versatility) -->
        <div class="relative">
            <select id="role-selector" onchange="switchRole(this.value)" aria-label="Pilih mode demo" class="bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-700 text-xs font-semibold rounded-xl px-3 py-2 outline-none cursor-pointer">
                <option value="sapras" selected>Mode: Admin Sapras</option>
                <option value="pkl_bkk">Mode: Admin PKL &amp; BKK</option>
            </select>
        </div>

        <!-- Gear Icon Button -->
        <button title="Pengaturan" class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 text-brand-600 flex items-center justify-center transition-colors">
            <i class="fa-solid fa-gear text-lg"></i>
        </button>

        <!-- User Profile Badge Pill -->
        <div class="flex items-center space-x-3 bg-slate-100 hover:bg-slate-200/80 border border-slate-200/80 px-3.5 py-1.5 rounded-full cursor-pointer transition-colors">
            <!-- User Circle Icon -->
            <div class="w-7 h-7 rounded-full bg-white text-brand-600 border border-slate-300 flex items-center justify-center">
                <i class="fa-solid fa-user text-xs"></i>
            </div>
            <div class="text-left">
                <p id="header-user-name" class="text-xs font-bold text-slate-800 leading-tight">{{ auth()->user()->name }}</p>
                <p id="header-user-role" class="text-[10px] text-slate-500 leading-tight">{{ ucwords(str_replace('_', ' ', auth()->user()->role)) }}</p>
            </div>
            <i class="fa-solid fa-chevron-down text-xs text-slate-500 ml-1"></i>
        </div>

    </div>
</header>
