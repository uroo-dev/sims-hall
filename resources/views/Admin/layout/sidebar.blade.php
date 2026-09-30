{{--
    SIDEBAR CONTAINER

    Tampilan mengikuti desain branch `dapin` (biru #0073c6, kategori dengan
    garis pemisah, logout pill). Data menu & pembatasan role mengikuti
    config/menu.php + App\Support\Menu supaya modul PKL & BKK milik branch
    `uroo` tetap muncul hanya untuk role yang berhak.

    Item dengan route null (modul belum punya halaman) dirender sebagai
    `href="#"` dengan teks redup — sama seperti desain aslinya, tapi jelas
    terbaca sebagai "belum tersedia".
--}}
<aside id="sidebar"
    class="fixed top-0 bottom-0 left-0 z-50 w-[270px] bg-white flex flex-col transition-transform duration-300 -translate-x-full lg:translate-x-0 border-r border-gray-100">

    @php
        $role = auth()->user()?->role;
        $modules = App\Support\Menu::forRole($role);
    @endphp

    <!-- BRAND / LOGO HEADER (Latar Belakang Putih) -->
    <div class="p-5 bg-white flex items-center gap-3">
        <img src="assets/logosmkk.png" alt="Logo SMKN 2 Karanganyar" class="w-10 h-10 object-contain">
        <div>
            <h1 class="font-bold text-gray-900 leading-tight text-xs tracking-wider uppercase">SMK NEGERI 2</h1>
            <p class="font-semibold text-gray-500 text-[11px] tracking-tight uppercase">KARANGANYAR</p>
        </div>
    </div>

    <!-- MAIN BLUE CONTAINER -->
    <div class="bg-[#0073c6] flex-1 rounded-tr-[40px] flex flex-col overflow-hidden text-white pt-6 pb-6 px-4">

        <!-- MENU NAVIGATION SCROLLABLE AREA -->
        <div class="flex-1 overflow-y-auto sidebar-scroll pr-1 space-y-6">

            @foreach ($modules as $module)
                @php
                    $moduleRoute = $module['route'] ?? null;
                    $moduleUrl = App\Support\Menu::url($moduleRoute, $role);
                    $moduleActive = App\Support\Menu::isActive($moduleRoute, $role);
                @endphp

                {{-- Modul tanpa kategori anak, mis. Dashboard --}}
                @if (empty($module['children']))
                    <a href="{{ $moduleUrl ?? '#' }}"
                        @if ($moduleUrl === null) aria-disabled="true" @endif
                        @class([
                            'flex items-center gap-3 px-5 py-3.5 rounded-full font-bold text-sm shadow-sm transition transform active:scale-95',
                            'bg-white text-[#0073c6]' => $moduleActive,
                            'text-white hover:bg-white/10' => ! $moduleActive && $moduleUrl !== null,
                            'text-white/40 cursor-default' => $moduleUrl === null,
                        ])>
                        <i class="fa-solid {{ $module['icon'] }} text-base"></i>
                        <span>{{ $module['label'] }}</span>
                    </a>
                @else
                    {{-- Modul berkategori --}}
                    <div class="space-y-3">
                        <div
                            class="flex items-center justify-between text-[12px] font-medium text-blue-100/90 tracking-wide">
                            <span>{{ $module['label'] }}</span>
                            <span class="w-12 h-[1px] bg-white/30"></span>
                        </div>
                        <div class="space-y-2.5 pl-1">
                            @foreach ($module['children'] as $child)
                                @php
                                    $childUrl = App\Support\Menu::url($child['route'] ?? null, $role);
                                    $childActive = App\Support\Menu::isActive($child['route'] ?? null, $role);
                                @endphp
                                <a href="{{ $childUrl ?? '#' }}"
                                    @if ($childUrl === null) aria-disabled="true" @endif
                                    @class([
                                        'flex items-center gap-3 py-1.5 font-medium text-sm transition',
                                        // Aktif: putih solid + tebal
                                        'text-white font-semibold' => $childActive,
                                        // Tersedia tapi tidak aktif
                                        'text-white/90 hover:text-blue-100' => ! $childActive && $childUrl !== null,
                                        // Belum ada halamannya
                                        'text-white/40 cursor-default' => $childUrl === null,
                                    ])>
                                    <i class="fa-solid {{ $child['icon'] }} w-5 text-center text-base"></i>
                                    <span>{{ $child['label'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach

        </div>

        <!-- LOGOUT BUTTON CONTAINER -->
        <div class="pt-4 mt-2">
         <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin keluar dari portal admin?')">
             @csrf
            <button name="logout"
                class="w-full bg-white text-[#0073c6] hover:bg-gray-100 transition font-bold py-3 px-4 rounded-full text-sm shadow-sm flex items-center justify-center">
                <span>Logout</span>
            </button>
         </form>
        </div>

    </div>
</aside>
