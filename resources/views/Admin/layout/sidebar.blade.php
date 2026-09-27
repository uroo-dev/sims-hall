<!-- SIDEBAR CONTAINER -->
<aside id="sidebar" class="fixed top-0 bottom-0 left-0 z-50 w-[270px] bg-white lg:bg-transparent flex flex-col p-3 transition-transform duration-300 -translate-x-full lg:translate-x-0">
    
    <div class="bg-brand-600 h-full rounded-[24px] flex flex-col overflow-hidden shadow-xl text-white">
        
        <!-- BRAND / LOGO HEADER -->
        <div class="p-4 bg-white border-b border-gray-100 flex items-center gap-3">
            <img src="{{ asset('assets/logosmkk.png') }}" 
                 alt="Logo SMKN 2 Karanganyar" 
                 class="w-10 h-10 object-contain">
            <div>
                <h1 class="font-bold text-gray-900 leading-tight text-xs tracking-wider">SMK NEGERI 2</h1>
                <p class="font-semibold text-brand-600 text-[11px] tracking-tight">KARANGANYAR</p>
            </div>
        </div>

        <!-- MENU NAVIGATION SCROLLABLE AREA -->
        <div class="flex-1 overflow-y-auto sidebar-scroll px-3 py-4 space-y-4">
            
            <!-- ACTIVE DASHBOARD ITEM -->
            <a href="{{ route('dashboard') }}" id="menu-dashboard" onclick="if(typeof setActiveMenu === 'function') setActiveMenu('dashboard')" class="flex items-center gap-3 px-4 py-2.5 bg-white text-brand-600 rounded-full font-semibold text-xs shadow-md transition transform active:scale-95">
                <i class="fa-solid font-bold fa-table-cells-large text-sm"></i>
                <span>Dashboard</span>
            </a>

            <!-- CATEGORY: DATA MASTER SEKOLAH -->
            <div class="space-y-1">
                <div class="flex items-center gap-2 px-3 text-[11px] font-medium text-blue-100/80 uppercase tracking-wider">
                    <span>Data Master Sekolah</span>
                    <span class="flex-1 h-[1px] bg-white/20"></span>
                </div>
                <a href="#" class="flex items-center gap-3 px-3 py-1.5 text-blue-50 hover:bg-white/10 rounded-lg text-xs transition">
                    <i class="fa-solid fa-graduation-cap w-4 text-center"></i>
                    <span>Dashboard Sekolah</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-1.5 text-blue-50 hover:bg-white/10 rounded-lg text-xs transition">
                    <i class="fa-regular fa-comment-dots w-4 text-center"></i>
                    <span>Data Sekolah</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-1.5 text-blue-50 hover:bg-white/10 rounded-lg text-xs transition">
                    <i class="fa-solid fa-users-gear w-4 text-center"></i>
                    <span>Users</span>
                </a>
            </div>

            <!-- CATEGORY: PEMINJAMAN AULA -->
            <div class="space-y-1">
                <div class="flex items-center gap-2 px-3 text-[11px] font-medium text-blue-100/80 uppercase tracking-wider">
                    <span id="role-category-label">Peminjaman Aula</span>
                    <span class="flex-1 h-[1px] bg-white/20"></span>
                </div>
                <div id="sidebar-dynamic-menus" class="space-y-1">
                    <a href="#" onclick="if(typeof setActiveMenu === 'function') setActiveMenu('aula')" class="flex items-center gap-3 px-3 py-1.5 text-blue-50 hover:bg-white/10 rounded-lg text-xs transition">
                        <i class="fa-solid fa-building-columns w-4 text-center"></i>
                        <span>Dashboard Aula</span>
                    </a>
                    <a href="#" onclick="if(typeof setActiveMenu === 'function') setActiveMenu('fasilitas')" class="flex items-center gap-3 px-3 py-1.5 text-blue-50 hover:bg-white/10 rounded-lg text-xs transition">
                        <i class="fa-solid fa-box w-4 text-center"></i>
                        <span>Fasilitas</span>
                    </a>
                    <a href="#" onclick="if(typeof setActiveMenu === 'function') setActiveMenu('paket')" class="flex items-center gap-3 px-3 py-1.5 text-blue-50 hover:bg-white/10 rounded-lg text-xs transition">
                        <i class="fa-solid fa-boxes-packing w-4 text-center"></i>
                        <span>Paket Peminjaman</span>
                    </a>
                    <a href="#" onclick="if(typeof setActiveMenu === 'function') setActiveMenu('persetujuan')" class="flex items-center gap-3 px-3 py-1.5 text-blue-50 hover:bg-white/10 rounded-lg text-xs transition">
                        <i class="fa-solid fa-cart-shopping w-4 text-center"></i>
                        <span>Persetujuan 1</span>
                    </a>
                    <a href="#" onclick="if(typeof setActiveMenu === 'function') setActiveMenu('laporan')" class="flex items-center gap-3 px-3 py-1.5 text-blue-50 hover:bg-white/10 rounded-lg text-xs transition">
                        <i class="fa-solid fa-chart-simple w-4 text-center"></i>
                        <span>Laporan Operasional</span>
                    </a>
                </div>
            </div>

            <!-- CATEGORY: KEPALA SEKOLAH -->
            <div class="space-y-1">
                <div class="flex items-center gap-2 px-3 text-[11px] font-medium text-blue-100/80 uppercase tracking-wider">
                    <span>Kepala Sekolah</span>
                    <span class="flex-1 h-[1px] bg-white/20"></span>
                </div>
                <a href="#" class="flex items-center gap-3 px-3 py-1.5 text-blue-50 hover:bg-white/10 rounded-lg text-xs transition">
                    <i class="fa-solid fa-user-tie w-4 text-center"></i>
                    <span>Dashboard KS</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-1.5 text-blue-50 hover:bg-white/10 rounded-lg text-xs transition">
                    <i class="fa-solid fa-cart-shopping w-4 text-center"></i>
                    <span>Persetujuan 2</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-1.5 text-blue-50 hover:bg-white/10 rounded-lg text-xs transition">
                    <i class="fa-solid fa-chart-simple w-4 text-center"></i>
                    <span>Laporan Operasional</span>
                </a>
            </div>

            <!-- CATEGORY: KESISWAAN -->
            <div class="space-y-1">
                <div class="flex items-center gap-2 px-3 text-[11px] font-medium text-blue-100/80 uppercase tracking-wider">
                    <span>Kesiswaan</span>
                    <span class="flex-1 h-[1px] bg-white/20"></span>
                </div>
                <a href="#" class="flex items-center gap-3 px-3 py-1.5 text-blue-50 hover:bg-white/10 rounded-lg text-xs transition">
                    <i class="fa-solid fa-users w-4 text-center"></i>
                    <span>Dashboard K</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-1.5 text-blue-50 hover:bg-white/10 rounded-lg text-xs transition">
                    <i class="fa-solid fa-trophy w-4 text-center"></i>
                    <span>Data Prestasi</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-1.5 text-blue-50 hover:bg-white/10 rounded-lg text-xs transition">
                    <i class="fa-solid fa-icons w-4 text-center"></i>
                    <span>Data Ekstrakulikuler</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-1.5 text-blue-50 hover:bg-white/10 rounded-lg text-xs transition">
                    <i class="fa-solid fa-book-bookmark w-4 text-center"></i>
                    <span>Data Tata Tertib</span>
                </a>
            </div>

            <!-- CATEGORY: PRODUK UNGGULAN -->
            <div class="space-y-1">
                <div class="flex items-center gap-2 px-3 text-[11px] font-medium text-blue-100/80 uppercase tracking-wider">
                    <span>Produk Unggulan</span>
                    <span class="flex-1 h-[1px] bg-white/20"></span>
                </div>
                <a href="#" class="flex items-center gap-3 px-3 py-1.5 text-blue-50 hover:bg-white/10 rounded-lg text-xs transition">
                    <i class="fa-solid fa-store w-4 text-center"></i>
                    <span>Dashboard PU</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-1.5 text-blue-50 hover:bg-white/10 rounded-lg text-xs transition">
                    <i class="fa-solid fa-basket-shopping w-4 text-center"></i>
                    <span>Data Produk</span>
                </a>
            </div>

            <!-- CATEGORY: PKL & BKK -->
            <div class="space-y-1">
                <div class="flex items-center gap-2 px-3 text-[11px] font-medium text-blue-100/80 uppercase tracking-wider">
                    <span>PKL & BKK</span>
                    <span class="flex-1 h-[1px] bg-white/20"></span>
                </div>
                <a href="#" class="flex items-center gap-3 px-3 py-1.5 text-blue-50 hover:bg-white/10 rounded-lg text-xs transition">
                    <i class="fa-solid fa-briefcase w-4 text-center"></i>
                    <span>Dashboard PB</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-1.5 text-blue-50 hover:bg-white/10 rounded-lg text-xs transition">
                    <i class="fa-solid fa-bars-staggered w-4 text-center"></i>
                    <span>Data Dudi</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-1.5 text-blue-50 hover:bg-white/10 rounded-lg text-xs transition">
                    <i class="fa-solid fa-laptop-code w-4 text-center"></i>
                    <span>Lowongan Kerja</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-1.5 text-blue-50 hover:bg-white/10 rounded-lg text-xs transition">
                    <i class="fa-solid fa-address-card w-4 text-center"></i>
                    <span>Data PKL</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-1.5 text-blue-50 hover:bg-white/10 rounded-lg text-xs transition">
                    <i class="fa-solid fa-comments w-4 text-center"></i>
                    <span>Data Jurusan</span>
                </a>
            </div>

            <!-- CATEGORY: PPDB -->
            <div class="space-y-1">
                <div class="flex items-center gap-2 px-3 text-[11px] font-medium text-blue-100/80 uppercase tracking-wider">
                    <span>PPDB</span>
                    <span class="flex-1 h-[1px] bg-white/20"></span>
                </div>
                <a href="#" class="flex items-center gap-3 px-3 py-1.5 text-blue-50 hover:bg-white/10 rounded-lg text-xs transition">
                    <i class="fa-solid fa-user-plus w-4 text-center"></i>
                    <span>Dashboard PPDB</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-1.5 text-blue-50 hover:bg-white/10 rounded-lg text-xs transition">
                    <i class="fa-solid fa-file-lines w-4 text-center"></i>
                    <span>Informasi & Persyaratan</span>
                </a>
            </div>

        </div>

        <!-- LOGOUT BUTTON -->
        <div class="p-4 border-t border-white/10 bg-brand-700/50">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin keluar dari sistem admin?')" class="w-full bg-white text-brand-600 hover:bg-red-50 hover:text-red-600 transition font-bold py-2 px-4 rounded-full text-xs shadow flex items-center justify-center gap-2">
                    <span>Logout</span>
                </button>
            </form>
        </div>

    </div>
</aside>
