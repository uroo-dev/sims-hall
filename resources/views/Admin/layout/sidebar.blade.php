@php
    use App\Support\Menu;

    $role = auth()->user()?->role;
    $modules = Menu::forRole($role);
@endphp

<!-- SIDEBAR CONTAINER -->
<aside id="sidebar"
    class="fixed top-0 bottom-0 left-0 z-50 w-[270px] bg-white flex flex-col transition-transform duration-300 -translate-x-full lg:translate-x-0 border-r border-gray-100">

    <!-- BRAND / LOGO HEADER -->
    <div class="p-5 bg-white flex items-center gap-3">
        <img src="{{ asset('assets/logosmkk.png') }}" alt="Logo SMKN 2 Karanganyar"
            class="w-10 h-10 object-contain">
        <div>
            <h1 class="font-bold text-gray-900 leading-tight text-xs tracking-wider uppercase">SMK NEGERI 2</h1>
            <p class="font-semibold text-gray-500 text-[11px] tracking-tight uppercase">KARANGANYAR</p>
        </div>
    </div>

    <!-- MAIN BLUE CONTAINER -->
    <div class="bg-[#0073c6] flex-1 rounded-tr-[40px] flex flex-col overflow-hidden text-white pt-6 pb-6 px-4">

        <!-- MENU NAVIGATION SCROLLABLE AREA -->
        <div class="flex-1 overflow-y-auto sidebar-scroll pr-1 space-y-6">

            @forelse ($modules as $module)
                @php
                    $isModuleActive = false;
                    if (isset($module['children'])) {
                        foreach ($module['children'] as $child) {
                            if (Menu::isActive($child['route'] ?? null, $role)) {
                                $isModuleActive = true;
                                break;
                            }
                        }
                    } else {
                        $isModuleActive = Menu::isActive($module['route'] ?? null, $role);
                    }

                    $linkClasses = 'flex items-center gap-3 rounded-lg transition text-sm';
                    $linkClasses .= $isModuleActive
                        ? ' bg-white/15 text-white font-semibold'
                        : ' text-white hover:text-blue-100 font-medium';
                @endphp

                @if (isset($module['children']))
                    {{-- CATEGORY: ber-submenu --}}
                    <div class="space-y-3">
                        <div
                            class="flex items-center justify-between text-[12px] font-medium text-blue-100/90 tracking-wide">
                            <span>{{ $module['label'] }}</span>
                            <span class="w-12 h-[1px] bg-white/30"></span>
                        </div>
                        <div class="space-y-2.5 pl-1">
                            @foreach ($module['children'] as $child)
                                @php
                                    $url = Menu::url($child['route'] ?? null, $role);
                                    $active = Menu::isActive($child['route'] ?? null, $role);
                                    $classes = 'flex items-center gap-3 py-1.5 rounded-lg transition text-sm';
                                    $classes .= $active
                                        ? ' bg-white/15 text-white font-semibold'
                                        : ' text-white hover:text-blue-100 font-medium';
                                @endphp
                                <a href="{{ $url ?? '#' }}" @if (! $url) onclick="return false" @endif
                                    class="{{ $classes }}">
                                    <i class="fa-solid {{ $child['icon'] }} w-5 text-center text-base"></i>
                                    <span>{{ $child['label'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    {{-- SINGLE LINK: mis. Dashboard --}}
                    <a href="{{ Menu::url($module['route'] ?? null, $role) ?? '#' }}"
                        class="flex items-center gap-3 px-5 py-3.5 rounded-full shadow-sm transition transform active:scale-95
                            {{ $isModuleActive
                                ? 'bg-white text-[#0073c6] font-bold'
                                : 'text-white hover:text-blue-100 font-medium' }}">
                        <i class="fa-solid {{ $module['icon'] }} text-base"></i>
                        <span>{{ $module['label'] }}</span>
                    </a>
                @endif
            @empty
                <div class="px-3 py-6 text-center text-sm text-blue-100/80">
                    <i class="fa-solid fa-lock mb-2 block text-2xl"></i>
                    Tidak ada modul yang dapat diakses oleh role Anda.
                </div>
            @endforelse

        </div>

        <!-- LOGOUT BUTTON CONTAINER -->
        <div class="pt-4 mt-2">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin keluar dari sistem admin?')"
                    class="w-full bg-white text-[#0073c6] hover:bg-gray-100 transition font-bold py-3 px-4 rounded-full text-sm shadow-sm flex items-center justify-center">
                    <span>Logout</span>
                </button>
            </form>
        </div>

    </div>
</aside>
