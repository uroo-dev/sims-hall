<aside class="w-72 h-full flex flex-col relative z-20 shrink-0 overflow-y-auto transition-all duration-300">

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
                <a href="{{ route('dashboard') }}" id="menu-dashboard" onclick="setActiveMenu('dashboard')" class="menu-item flex items-center space-x-3.5 px-5 py-3 rounded-full bg-white text-brand-600 font-semibold shadow-md transition-all duration-200">
                    <i class="fa-solid fa-table-cells-large text-lg"></i>
                    <span class="text-sm">Dashboard</span>
                </a>

                <!-- Subheader Category Divider -->
                <div class="pt-4 pb-2 px-2 flex items-center justify-between text-white/70">
                    <span id="role-category-label" class="text-xs font-medium tracking-wide">Admin Sapras</span>
                    <div class="h-[1px] w-16 bg-white/30 rounded-full"></div>
                </div>

                <!-- Dynamic Menu Items List -->
                <div id="sidebar-dynamic-menus" class="space-y-1.5">

                    <a href="#" onclick="setActiveMenu('fasilitas')" class="menu-item flex items-center space-x-3.5 px-5 py-2.5 rounded-xl text-white/90 hover:bg-white/10 font-normal transition-all duration-200">
                        <i class="fa-solid fa-building text-base w-5 text-center"></i>
                        <span class="text-sm">Fasilitas</span>
                    </a>

                    <a href="#" onclick="setActiveMenu('paket')" class="menu-item flex items-center space-x-3.5 px-5 py-2.5 rounded-xl text-white/90 hover:bg-white/10 font-normal transition-all duration-200">
                        <i class="fa-solid fa-box text-base w-5 text-center"></i>
                        <span class="text-sm">Paket Peminjaman</span>
                    </a>

                    <a href="#" onclick="setActiveMenu('persetujuan')" class="menu-item flex items-center space-x-3.5 px-5 py-2.5 rounded-xl text-white/90 hover:bg-white/10 font-normal transition-all duration-200">
                        <i class="fa-solid fa-cart-shopping text-base w-5 text-center"></i>
                        <span class="text-sm">Persetujuan 1</span>
                    </a>

                    <a href="#" onclick="setActiveMenu('laporan')" class="menu-item flex items-center space-x-3.5 px-5 py-2.5 rounded-xl text-white/90 hover:bg-white/10 font-normal transition-all duration-200">
                        <i class="fa-solid fa-chart-column text-base w-5 text-center"></i>
                        <span class="text-sm">Laporan Operasional</span>
                    </a>

                    <a href="#" onclick="setActiveMenu('profil')" class="menu-item flex items-center space-x-3.5 px-5 py-2.5 rounded-xl text-white/90 hover:bg-white/10 font-normal transition-all duration-200">
                        <i class="fa-solid fa-user-group text-base w-5 text-center"></i>
                        <span class="text-sm">Profil</span>
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
