<aside class="w-72 min-h-screen flex flex-col relative z-20 shrink-0 transition-all duration-300">

    <!-- Top School Branding Box (White Background) -->
    <div class="bg-white px-5 py-4 flex items-center space-x-3 h-20 border-b border-slate-100">
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
        <div>
            <!-- Navigation Items -->
            <div class="space-y-2">

                <!-- Active Capsule Item: Dashboard -->
                <a href="{{ route('dashboard.pkl') }}" data-section="dashboard" class="pkl-menu flex items-center space-x-3.5 px-5 py-3 rounded-full bg-white text-brand-600 font-semibold shadow-md transition-all duration-200">
                    <i class="fa-solid fa-table-cells-large text-lg"></i>
                    <span class="text-sm">Dashboard</span>
                </a>

                <!-- Subheader Category Divider -->
                <div class="pt-4 pb-2 px-2 flex items-center justify-between text-white/70">
                    <span class="text-xs font-medium tracking-wide">BKK &amp; PKL</span>
                    <div class="h-[1px] w-16 bg-white/30 rounded-full"></div>
                </div>

                <!-- Dynamic Menu Items List -->
                <div id="sidebar-pkl-menus" class="space-y-1.5">

                    <a href="#loker" data-section="loker" onclick="setActivePklMenu('loker'); return true;" class="pkl-menu flex items-center space-x-3.5 px-5 py-2.5 rounded-xl text-white/90 hover:bg-white/10 font-normal transition-all duration-200 cursor-pointer">
                        <i class="fa-solid fa-briefcase text-base w-5 text-center"></i>
                        <span class="text-sm">Lowongan Kerja</span>
                    </a>

                    <a href="#pelamar" data-section="pelamar" onclick="setActivePklMenu('pelamar'); return true;" class="pkl-menu flex items-center space-x-3.5 px-5 py-2.5 rounded-xl text-white/90 hover:bg-white/10 font-normal transition-all duration-200 cursor-pointer">
                        <i class="fa-solid fa-user-graduate text-base w-5 text-center"></i>
                        <span class="text-sm">Data Pelamar</span>
                    </a>

                    <a href="#temppkl" data-section="temppkl" onclick="setActivePklMenu('temppkl'); return true;" class="pkl-menu flex items-center space-x-3.5 px-5 py-2.5 rounded-xl text-white/90 hover:bg-white/10 font-normal transition-all duration-200 cursor-pointer">
                        <i class="fa-solid fa-handshake text-base w-5 text-center"></i>
                        <span class="text-sm">Tempat PKL</span>
                    </a>

                    <a href="#jurnal" data-section="jurnal" onclick="setActivePklMenu('jurnal'); return true;" class="pkl-menu flex items-center space-x-3.5 px-5 py-2.5 rounded-xl text-white/90 hover:bg-white/10 font-normal transition-all duration-200 cursor-pointer">
                        <i class="fa-solid fa-book-open text-base w-5 text-center"></i>
                        <span class="text-sm">Jurnal &amp; Absensi</span>
                    </a>

                    <a href="#nilai" data-section="nilai" onclick="setActivePklMenu('nilai'); return true;" class="pkl-menu flex items-center space-x-3.5 px-5 py-2.5 rounded-xl text-white/90 hover:bg-white/10 font-normal transition-all duration-200 cursor-pointer">
                        <i class="fa-solid fa-clipboard-check text-base w-5 text-center"></i>
                        <span class="text-sm">Rekap Nilai PKL</span>
                    </a>

                </div>
            </div>
        </div>

        <!-- Bottom Logout Pill Button -->
        <div class="pt-5">
            <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin keluar dari portal admin?')">
                @csrf
                <button type="submit" class="w-full py-2.5 px-6 bg-white hover:bg-slate-100 text-brand-600 font-bold rounded-full shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center space-x-2 text-sm">
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>

</aside>