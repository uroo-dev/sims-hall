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
            'roles' => ['admin', 'super_admin', 'super_duper_admin', 'bkk'],
            // Role bkk masuk ke dashboard BKK, bukan dashboard utama.
            'route' => [
                'bkk' => 'pkl.dashboard',
                'default' => 'dashboard',
            ],
        ],

        [
            'key' => 'master',
            'label' => 'Data Master Sekolah',
            'icon' => 'fa-graduation-cap',
            'roles' => ['admin', 'super_admin', 'super_duper_admin'],
            'children' => [
                ['label' => 'Dashboard Sekolah', 'icon' => 'fa-comment-dots', 'route' => null],
                ['label' => 'Data Sekolah', 'icon' => 'fa-school', 'route' => null],
                ['label' => 'Users', 'icon' => 'fa-users-gear', 'route' => null],
            ],
        ],

        [
            'key' => 'aula',
            'label' => 'Peminjaman Aula',
            'icon' => 'fa-building-columns',
            'roles' => ['admin', 'super_admin', 'super_duper_admin'],
            'children' => [
                ['label' => 'Dashboard Aula', 'icon' => 'fa-gauge-high', 'route' => null],
                ['label' => 'Fasilitas', 'icon' => 'fa-box', 'route' => null],
                ['label' => 'Paket Peminjaman', 'icon' => 'fa-boxes-packing', 'route' => null],
                ['label' => 'Persetujuan 1', 'icon' => 'fa-cart-shopping', 'route' => null],
                ['label' => 'Laporan Operasional', 'icon' => 'fa-chart-simple', 'route' => null],
            ],
        ],

        [
            'key' => 'kepsek',
            'label' => 'Kepala Sekolah',
            'icon' => 'fa-user-tie',
            'roles' => ['admin', 'super_admin', 'super_duper_admin', 'kepala_sekolah'],
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
            'roles' => ['admin', 'super_admin', 'super_duper_admin'],
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
            'roles' => ['admin', 'super_admin', 'super_duper_admin'],
            'children' => [
                ['label' => 'Dashboard PU', 'icon' => 'fa-gauge-high', 'route' => null],
                ['label' => 'Data Produk', 'icon' => 'fa-basket-shopping', 'route' => null],
            ],
        ],

        /*
        |----------------------------------------------------------------------
        | Modul PKL & BKK - satu-satunya modul untuk role `bkk`
        |----------------------------------------------------------------------
        */
        [
            'key' => 'pklbkk',
            'label' => 'PKL & BKK',
            'icon' => 'fa-briefcase',
            'roles' => ['bkk', 'admin', 'super_admin', 'super_duper_admin'],
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
            'roles' => ['admin', 'super_admin', 'super_duper_admin'],
            'children' => [
                ['label' => 'Dashboard PPDB', 'icon' => 'fa-gauge-high', 'route' => null],
                ['label' => 'Informasi & Persyaratan', 'icon' => 'fa-file-lines', 'route' => null],
            ],
        ],

    ],
];
