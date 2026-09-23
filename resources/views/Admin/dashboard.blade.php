@extends('Admin.layout.app')

@section('title', 'Dashboard Admin - SMK Negeri 2 Karanganyar')

@section('content')
    <!-- RIGHT MAIN CONTENT -->
    <main class="flex-1 p-6 lg:p-8 overflow-y-auto space-y-6">

        @include('Admin.layout.header')

        <!-- 3 TOP STAT CARDS GRID -->
        <section class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Stat Card 1 -->
            <div class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100 flex items-center justify-between transition-transform duration-200 hover:-translate-y-1">
                <div>
                    <h3 id="stat-1-label" class="text-xs md:text-sm font-extrabold text-brand-600 uppercase tracking-wide leading-snug max-w-[140px]">
                        PEMINJAMAN TERVERIFIKASI
                    </h3>
                </div>
                <div id="stat-1-value" class="text-4xl lg:text-5xl font-extrabold text-brand-600 pl-2">
                    3
                </div>
            </div>

            <!-- Stat Card 2 -->
            <div class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100 flex items-center justify-between transition-transform duration-200 hover:-translate-y-1">
                <div>
                    <h3 id="stat-2-label" class="text-xs md:text-sm font-extrabold text-brand-600 uppercase tracking-wide leading-snug max-w-[140px]">
                        JUMLAH PAKET PEMINJAMAN
                    </h3>
                </div>
                <div id="stat-2-value" class="text-4xl lg:text-5xl font-extrabold text-brand-600 pl-2">
                    5
                </div>
            </div>

            <!-- Stat Card 3 -->
            <div class="bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100 flex items-center justify-between transition-transform duration-200 hover:-translate-y-1">
                <div>
                    <h3 id="stat-3-label" class="text-xs md:text-sm font-extrabold text-brand-600 uppercase tracking-wide leading-snug max-w-[140px]">
                        JUMLAH FASILITAS
                    </h3>
                </div>
                <div id="stat-3-value" class="text-4xl lg:text-5xl font-extrabold text-brand-600 pl-2">
                    4
                </div>
            </div>

        </section>

        <!-- MAIN CONTENT GRID (2 COLUMNS) -->
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- LEFT COLUMN: OPERATIONAL REPORT TABLE (Span 2 cols) -->
            <div class="lg:col-span-2 bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100 flex flex-col justify-between">
                <div>
                    <!-- Card Header -->
                    <div class="flex items-center justify-between mb-6">
                        <h2 id="table-card-title" class="text-sm font-extrabold text-brand-600 uppercase tracking-wider">
                            LAPORAN OPERASIONAL
                        </h2>
                        <!-- Selengkapnya Badge Button -->
                        <button class="px-3.5 py-1 bg-brand-600 hover:bg-brand-700 text-white rounded-full text-[11px] font-semibold flex items-center space-x-1.5 transition-colors shadow-sm">
                            <i class="fa-solid fa-sliders text-[10px]"></i>
                            <span>Selengkapnya</span>
                        </button>
                    </div>

                    <!-- Data Table Container -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs md:text-sm">
                            <thead>
                                <tr id="table-headers" class="border-b-2 border-slate-800 text-slate-800 font-extrabold">
                                    <th class="py-3 px-2">ID</th>
                                    <th class="py-3 px-2">Email Instansi</th>
                                    <th class="py-3 px-2">Paket Peminjaman</th>
                                    <th class="py-3 px-2">Metode</th>
                                </tr>
                            </thead>
                            <tbody id="table-body" class="divide-y divide-slate-200 text-slate-700 font-medium">

                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="py-3.5 px-2 font-bold text-slate-800">ORD-001</td>
                                    <td class="py-3.5 px-2 text-slate-600">Sekretariat@kemenkeu.go.id</td>
                                    <td class="py-3.5 px-2">Unggulan</td>
                                    <td class="py-3.5 px-2">Transfer</td>
                                </tr>

                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="py-3.5 px-2 font-bold text-slate-800">ORD-002</td>
                                    <td class="py-3.5 px-2 text-slate-600">P3k@smk2nkra.sch.id</td>
                                    <td class="py-3.5 px-2">Standar 2</td>
                                    <td class="py-3.5 px-2">Transfer</td>
                                </tr>

                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="py-3.5 px-2 font-bold text-slate-800">ORD-003</td>
                                    <td class="py-3.5 px-2 text-slate-600">Humas@smk2nkra.sch.id</td>
                                    <td class="py-3.5 px-2">Standar 1</td>
                                    <td class="py-3.5 px-2">Transfer</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: PROFILE CARD (Span 1 col) -->
            <div class="lg:col-span-1 bg-white rounded-2xl p-6 figma-card-shadow border border-slate-100 flex flex-col justify-between">
                <div>
                    <!-- Card Header -->
                    <div class="flex items-center justify-between mb-6 pb-2 border-b border-slate-100">
                        <div>
                            <h2 class="text-sm font-extrabold text-brand-600 uppercase tracking-wider relative inline-block">
                                PROFIL
                                <div class="h-0.5 w-full bg-brand-600 rounded-full mt-1"></div>
                            </h2>
                        </div>
                        <!-- Selengkapnya Badge Button -->
                        <button class="px-3.5 py-1 bg-brand-600 hover:bg-brand-700 text-white rounded-full text-[11px] font-semibold flex items-center space-x-1.5 transition-colors shadow-sm">
                            <i class="fa-solid fa-sliders text-[10px]"></i>
                            <span>Selengkapnya</span>
                        </button>
                    </div>

                    <!-- Profile Image Frame (Figma Vector Silhouette Accent) -->
                    <div class="flex flex-col items-center justify-center my-4">
                        <div class="w-48 h-56 rounded-2xl border-2 border-slate-200 bg-slate-50 p-2 flex items-center justify-center shadow-inner overflow-hidden relative">
                            <!-- Dark Silhouette Vector Illustration -->
                            <svg class="w-full h-full text-slate-700 transform scale-105 translate-y-2" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                        </div>

                        <!-- Name & Email Subtitle -->
                        <div class="text-center mt-5">
                            <h3 id="profile-card-name" class="text-xl font-extrabold text-slate-900 tracking-tight">
                                {{ auth()->user()->name }}
                            </h3>
                            <p id="profile-card-email" class="text-xs font-semibold text-slate-500 mt-1">
                                {{ auth()->user()->email }}
                            </p>
                        </div>
                    </div>

                </div>
            </div>

        </section>

    </main>
@endsection

@push('scripts')
    <script>
        // Data for Role Switcher (Supports Sapras and PKL/BKK for Lomba versatility)
        const roleData = {
            sapras: {
                roleName: 'ADMIN SAPRAS',
                userName: 'Teguh',
                userEmail: 'AdminSapras@smk2nkra.sch.id',
                categoryLabel: 'Admin Sapras',
                menus: [
                    { id: 'fasilitas', name: 'Fasilitas', icon: 'fa-building' },
                    { id: 'paket', name: 'Paket Peminjaman', icon: 'fa-box' },
                    { id: 'persetujuan', name: 'Persetujuan 1', icon: 'fa-cart-shopping' },
                    { id: 'laporan', name: 'Laporan Operasional', icon: 'fa-chart-column' },
                    { id: 'profil', name: 'Profil', icon: 'fa-user-group' },
                ],
                stats: [
                    { label: 'PEMINJAMAN TERVERIFIKASI', value: '3' },
                    { label: 'JUMLAH PAKET PEMINJAMAN', value: '5' },
                    { label: 'JUMLAH FASILITAS', value: '4' }
                ],
                tableTitle: 'LAPORAN OPERASIONAL',
                headers: ['ID', 'Email Instansi', 'Paket Peminjaman', 'Metode'],
                rows: [
                    ['ORD-001', 'Sekretariat@kemenkeu.go.id', 'Unggulan', 'Transfer'],
                    ['ORD-002', 'P3k@smk2nkra.sch.id', 'Standar 2', 'Transfer'],
                    ['ORD-003', 'Humas@smk2nkra.sch.id', 'Standar 1', 'Transfer']
                ]
            },
            pkl_bkk: {
                roleName: 'ADMIN PKL & BKK',
                userName: 'Sri Astuti, S.Pd',
                userEmail: 'BkkPkl@smk2nkra.sch.id',
                categoryLabel: 'PKL & BKK',
                menus: [
                    { id: 'dudi', name: 'Data DUDI', icon: 'fa-handshake' },
                    { id: 'loker', name: 'Lowongan Kerja', icon: 'fa-briefcase' },
                    { id: 'pkl', name: 'Data PKL', icon: 'fa-graduation-cap' },
                    { id: 'jurnal', name: 'Jurnal Siswa', icon: 'fa-book-open' },
                    { id: 'profil', name: 'Profil', icon: 'fa-user-group' },
                ],
                stats: [
                    { label: 'PERUSAHAAN MITRA', value: '42' },
                    { label: 'LOKER AKTIF BKK', value: '12' },
                    { label: 'SISWA PKL AKTIF', value: '286' }
                ],
                tableTitle: 'DAFTAR DUDI / MITRA TERBARU',
                headers: ['ID', 'Nama Perusahaan', 'Bidang Industri', 'Kuota PKL'],
                rows: [
                    ['DUDI-01', 'PT Telekomunikasi Indonesia', 'Jaringan & IT', '12 Siswa'],
                    ['DUDI-02', 'Nasmoco Karanganyar', 'Otomotif & TKR', '8 Siswa'],
                    ['DUDI-03', 'Software House Solo', 'RPL & Software', '15 Siswa']
                ]
            }
        };

        // Switch role dynamically
        function switchRole(roleKey) {
            const data = roleData[roleKey];
            if (!data) return;

            // Update Header & Profile
            document.getElementById('breadcrumb-role').innerText = data.roleName;
            document.getElementById('header-user-name').innerText = data.userName;
            document.getElementById('header-user-role').innerText = data.roleName.toLowerCase();
            document.getElementById('profile-card-name').innerText = data.userName;
            document.getElementById('profile-card-email').innerText = data.userEmail;
            document.getElementById('role-category-label').innerText = data.categoryLabel;

            // Update Stats
            document.getElementById('stat-1-label').innerText = data.stats[0].label;
            document.getElementById('stat-1-value').innerText = data.stats[0].value;
            document.getElementById('stat-2-label').innerText = data.stats[1].label;
            document.getElementById('stat-2-value').innerText = data.stats[1].value;
            document.getElementById('stat-3-label').innerText = data.stats[2].label;
            document.getElementById('stat-3-value').innerText = data.stats[2].value;

            // Update Menus
            const sidebarContainer = document.getElementById('sidebar-dynamic-menus');
            sidebarContainer.innerHTML = '';
            data.menus.forEach(menu => {
                const a = document.createElement('a');
                a.href = '#';
                a.onclick = () => setActiveMenu(menu.id);
                a.className = 'menu-item flex items-center space-x-3.5 px-5 py-2.5 rounded-xl text-white/90 hover:bg-white/10 font-normal transition-all duration-200';
                a.innerHTML = `
                    <i class="fa-solid ${menu.icon} text-base w-5 text-center"></i>
                    <span class="text-sm">${menu.name}</span>
                `;
                sidebarContainer.appendChild(a);
            });

            // Update Table Title & Headers
            document.getElementById('table-card-title').innerText = data.tableTitle;
            const thContainer = document.getElementById('table-headers');
            thContainer.innerHTML = data.headers.map(h => `<th class="py-3 px-2">${h}</th>`).join('');

            // Update Table Rows
            const tbody = document.getElementById('table-body');
            tbody.innerHTML = data.rows.map(row => `
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="py-3.5 px-2 font-bold text-slate-800">${row[0]}</td>
                    <td class="py-3.5 px-2 text-slate-600">${row[1]}</td>
                    <td class="py-3.5 px-2">${row[2]}</td>
                    <td class="py-3.5 px-2">${row[3]}</td>
                </tr>
            `).join('');
        }

        // Set active menu visual state
        function setActiveMenu(menuId) {
            document.getElementById('breadcrumb-page').innerText = menuId.charAt(0).toUpperCase() + menuId.slice(1);
        }
    </script>
@endpush
