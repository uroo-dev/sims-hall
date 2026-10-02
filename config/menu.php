<?php

/*
|--------------------------------------------------------------------------
| Menu Sidebar Admin
|--------------------------------------------------------------------------
|
| Setiap modul dideklarasikan sekali di sini, lalu otomatis difilter
| berdasarkan role user yang sedang login. Sidebar hanya menampilkan
| modul yang boleh diakses oleh role tersebut.
|
| Kolom "route" boleh berupa:
|   - string  : nama route yang sama untuk semua role
|   - array   : pemetaan role => nama route, dengan kunci "default" sebagai
|                fallback. Dipakai untuk Dashboard yang berbeda per role.
|
*/

return [

    /*
    |----------------------------------------------------------------------
    | Modul yang tersedia
    |----------------------------------------------------------------------
    */
    'modules' => [

        [
            'key' => 'dashboard',
            'label' => 'Dashboard',
            'icon' => 'fa-table-cells-large',
            'roles' => ['admin_aula', 'admin_master', 'admin_kesiswaan', 'admin_produk', 'admin_produk_unggulan', 'admin_ppdb', 'admin_pklbkk', 'super_admin', 'super_duper_admin', 'bkk'],
            // Role bkk / admin_pklbkk masuk ke dashboard BKK, bukan dashboard utama.
            'route' => [
                'bkk' => 'pkl.dashboard',
                'admin_pklbkk' => 'pkl.dashboard',
                'default' => 'dashboard',
            ],
        ],

        [
            'key' => 'master',
            'label' => 'Data Master Sekolah',
            'icon' => 'fa-graduation-cap',
            'roles' => ['admin_master', 'super_admin', 'super_duper_admin'],
            'children' => [
                ['label' => 'Dashboard Sekolah', 'icon' => 'fa-comment-dots', 'route' => 'datamaster.index'],
                ['label' => 'Data Sekolah', 'icon' => 'fa-school', 'route' => 'datamaster.sekolah.edit'],
                ['label' => 'Users', 'icon' => 'fa-users-gear', 'route' => 'datamaster.users'],
                ['label' => 'Data Guru', 'icon' => 'fa-chalkboard-user', 'route' => 'datamaster.guru.index'],
                ['label' => 'Data Siswa', 'icon' => 'fa-user-graduate', 'route' => 'datamaster.siswa.index'],
            ],
        ],

        [
            'key' => 'aula',
            'label' => 'Peminjaman Aula',
            'icon' => 'fa-building-columns',
            'roles' => ['admin_aula', 'super_admin', 'super_duper_admin'],
            'children' => [
                ['label' => 'Dashboard Aula', 'icon' => 'fa-gauge-high', 'route' => null],
                ['label' => 'Konfigurasi Aula', 'icon' => 'fa-hotel', 'route' => 'admin.aula.index'],
                ['label' => 'Fasilitas', 'icon' => 'fa-box', 'route' => 'admin.fasilitas.index'],
                ['label' => 'Paket Peminjaman', 'icon' => 'fa-boxes-packing', 'route' => 'admin.paket.index'],
                ['label' => 'Persetujuan 1', 'icon' => 'fa-cart-shopping', 'route' => 'admin.peminjaman.index'],
                ['label' => 'Laporan Operasional', 'icon' => 'fa-chart-simple', 'route' => 'admin.laporan.index'],
            ],
        ],

        [
            'key' => 'kepsek',
            'label' => 'Kepala Sekolah',
            'icon' => 'fa-user-tie',
            'roles' => ['super_admin', 'super_duper_admin', 'kepala_sekolah'],
            'children' => [
                ['label' => 'Dashboard KS', 'icon' => 'fa-gauge-high', 'route' => null],
                ['label' => 'Persetujuan 2', 'icon' => 'fa-cart-shopping', 'route' => null],
                ['label' => 'Laporan Operasional', 'icon' => 'fa-chart-simple', 'route' => null],
            ],
        ],

        [
            'key' => 'kesiswaan',
            'label' => 'Kesiswaan',
            'icon' => 'fa-users',
            'roles' => ['admin_kesiswaan', 'super_admin', 'super_duper_admin'],
            'children' => [
                ['label' => 'Dashboard K', 'icon' => 'fa-gauge-high', 'route' => null],
                ['label' => 'Data Prestasi', 'icon' => 'fa-trophy', 'route' => null],
                ['label' => 'Data Ekstrakulikuler', 'icon' => 'fa-icons', 'route' => null],
                ['label' => 'Data Tata Tertib', 'icon' => 'fa-book-bookmark', 'route' => null],
            ],
        ],

        [
            'key' => 'produk',
            'label' => 'Produk Unggulan',
            'icon' => 'fa-store',
            'roles' => ['admin_produk', 'admin_produk_unggulan', 'super_admin', 'super_duper_admin'],
            'children' => [
                ['label' => 'Dashboard PU', 'icon' => 'fa-gauge-high', 'route' => null],
                ['label' => 'Data Produk', 'icon' => 'fa-basket-shopping', 'route' => null],
            ],
        ],

        /*
        |----------------------------------------------------------------------
        | Modul PKL & BKK - modul untuk role `bkk` dan `admin_pklbkk`
        |----------------------------------------------------------------------
        */
        [
            'key' => 'pklbkk',
            'label' => 'PKL & BKK',
            'icon' => 'fa-briefcase',
            'roles' => ['bkk', 'admin_pklbkk', 'super_admin', 'super_duper_admin'],
            'children' => [
                [
                    'label' => 'Dashboard BKK',
                    'icon' => 'fa-chart-pie',
                    'route' => 'pkl.dashboard',
                ],
                [
                    'label' => 'Data DUDI',
                    'icon' => 'fa-building',
                    'route' => 'pkl.dudi.index',
                ],
                [
                    'label' => 'Lowongan Kerja',
                    'icon' => 'fa-laptop-code',
                    'route' => 'pkl.lowongan.index',
                ],
                [
                    'label' => 'Data Siswa PKL',
                    'icon' => 'fa-user-graduate',
                    'route' => 'pkl.siswa.index',
                ],
                [
                    'label' => 'Data Penempatan',
                    'icon' => 'fa-address-card',
                    'route' => 'pkl.index',
                ],
                [
                    'label' => 'Buat Pengajuan',
                    'icon' => 'fa-file-signature',
                    'route' => 'pkl.create',
                ],
            ],
        ],

        [
            'key' => 'ppdb',
            'label' => 'PPDB',
            'icon' => 'fa-user-plus',
            'roles' => ['admin_ppdb', 'super_admin', 'super_duper_admin'],
            'children' => [
                ['label' => 'Dashboard PPDB', 'icon' => 'fa-gauge-high', 'route' => null],
                ['label' => 'Informasi & Persyaratan', 'icon' => 'fa-file-lines', 'route' => null],
            ],
        ],

    ],
];
