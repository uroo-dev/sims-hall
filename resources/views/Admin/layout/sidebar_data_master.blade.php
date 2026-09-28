<!-- SIDEBAR CONTAINER -->
<aside id="sidebar"
    class="fixed top-0 bottom-0 left-0 z-50 w-[270px] bg-white flex flex-col transition-transform duration-300 -translate-x-full lg:translate-x-0 border-r border-gray-100">

    <!-- BRAND / LOGO HEADER (Latar Belakang Putih) -->
    <div class="p-5 bg-white flex items-center gap-3">
        <img src="{{ asset('assets/logosmkk.png') }}" alt="Logo SMKN 2 Karanganyar" class="w-10 h-10 object-contain">
        <div>
            <h1 class="font-bold text-gray-900 leading-tight text-xs tracking-wider uppercase">SMK NEGERI 2</h1>
            <p class="font-semibold text-gray-500 text-[11px] tracking-tight uppercase">KARANGANYAR</p>
        </div>
    </div>

    <!-- MAIN BLUE CONTAINER -->
    <div class="bg-[#0073c6] flex-1 rounded-tr-[40px] flex flex-col overflow-hidden text-white pt-6 pb-6 px-4">

        <!-- MENU NAVIGATION SCROLLABLE AREA -->
        <div class="flex-1 overflow-y-auto sidebar-scroll pr-1 space-y-6">

            <!-- DASHBOARD UTAMA -->
         

            <!-- CATEGORY: DATA MASTER SEKOLAH -->
            <div class="space-y-3">
                <div class="flex items-center justify-between text-[12px] font-medium text-blue-100/90 tracking-wide">
                    <span>Data Master Sekolah</span>
                    <span class="w-12 h-[1px] bg-white/30"></span>
                </div>
                <div class="space-y-2.5 pl-1">
                    <!-- Dashboard Data Master -->
                    <a href="{{ route('datamaster.index') }}"
                        class="flex items-center gap-3 py-1.5 px-3 rounded-full text-sm transition 
                        {{ request()->routeIs('datamaster.index') ? 'bg-white/20 text-white font-bold' : 'text-white hover:text-blue-100 font-medium' }}">
                        <i class="fa-solid fa-graduation-cap w-5 text-center text-base"></i>
                        <span>Dashboard Master</span>
                    </a>
                    
                    <!-- Data Sekolah -->
                    <a href="{{ route('datamaster.sekolah.edit') }}"
                        class="flex items-center gap-3 py-1.5 px-3 rounded-full text-sm transition 
                        {{ request()->routeIs('datamaster.sekolah.*') ? 'bg-white/20 text-white font-bold' : 'text-white hover:text-blue-100 font-medium' }}">
                        <i class="fa-regular fa-comment-dots w-5 text-center text-base"></i>
                        <span>Data Sekolah</span>
                    </a>
                    
                    <!-- Users -->
                    <a href="{{ route('datamaster.users') }}"
                        class="flex items-center gap-3 py-1.5 px-3 rounded-full text-sm transition 
                        {{ request()->routeIs('datamaster.users*') ? 'bg-white/20 text-white font-bold' : 'text-white hover:text-blue-100 font-medium' }}">
                        <i class="fa-solid fa-users-gear w-5 text-center text-base"></i>
                        <span>Users</span>
                    </a>
                </div>
            </div>

        </div>

        <!-- LOGOUT BUTTON CONTAINER -->
        <div class="pt-4 mt-2">
            <form action="{{ route('logout') }}" method="POST" id="logout-form-sidebar" onsubmit="return confirm('Apakah Anda yakin ingin keluar dari portal admin?')">
                @csrf
                <button type="submit"
                    class="w-full bg-white text-[#0073c6] hover:bg-gray-100 transition font-bold py-3 px-4 rounded-full text-sm shadow-sm flex items-center justify-center">
                    <span>Logout</span>
                </button>
            </form>
        </div>

    </div>
</aside>