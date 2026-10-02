{{-- PARTIAL: Menu navigasi PKL & BKK
     Digunakan oleh sidebar untuk role bkk/admin_pklbkk (standalone) dan
     super_admin/super_duper_admin (di dalam section PKL & BKK).
--}}
<!-- DASHBOARD BKK -->
<a href="{{ route('pkl.dashboard') }}"
    class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('pkl.dashboard') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
    <i class="fa-solid fa-chart-pie text-base w-5 text-center"></i>
    <span>Dashboard</span>
</a>

<!-- DATA DUDI -->
<a href="{{ route('pkl.dudi.index') }}"
    class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('pkl.dudi.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
    <i class="fa-solid fa-building text-base w-5 text-center"></i>
    <span>Data DUDI</span>
</a>

<!-- LOWONGAN KERJA -->
<a href="{{ route('pkl.lowongan.index') }}"
    class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('pkl.lowongan.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
    <i class="fa-solid fa-laptop-code text-base w-5 text-center"></i>
    <span>Lowongan Kerja</span>
</a>

<!-- DATA SISWA PKL -->
<a href="{{ route('pkl.siswa.index') }}"
    class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('pkl.siswa.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
    <i class="fa-solid fa-user-graduate text-base w-5 text-center"></i>
    <span>Data Siswa PKL</span>
</a>

<!-- PENEMPATAN PKL -->
<a href="{{ route('pkl.index') }}"
    class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('pkl.index') || request()->routeIs('pkl.penempatan.*') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
    <i class="fa-solid fa-address-card text-base w-5 text-center"></i>
    <span>Penempatan PKL</span>
</a>

<!-- BUAT PENGAJUAN -->
<a href="{{ route('pkl.create') }}"
    class="flex items-center gap-3 px-5 py-3.5 {{ request()->routeIs('pkl.create') || request()->routeIs('pkl.store') ? 'bg-white text-[#0073c6] shadow-sm' : 'text-white hover:bg-white/10' }} rounded-full font-bold text-sm transition transform active:scale-95">
    <i class="fa-solid fa-file-signature text-base w-5 text-center"></i>
    <span>Buat Pengajuan</span>
</a>
