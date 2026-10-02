<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Identitas Sekolah
    |--------------------------------------------------------------------------
    | Dipakai pada landing page publik (header, footer, peta lokasi).
    */

    'nama' => env('SEKOLAH_NAMA', 'SMK Negeri 2 Karanganyar'),
    'nama_pendek' => env('SEKOLAH_NAMA_PENDEK', 'SMKN 2 Karanganyar'),
    'alamat' => env('SEKOLAH_ALAMAT', 'Jl. Yos Sudarso, Jengglong, Bejen, Kec. Karanganyar, Jawa Tengah 57716'),
    'tentang' => env('SEKOLAH_TENTANG', 'SMK Negeri 2 Karanganyar adalah salah satu Sekolah Menengah Kejuruan favorit di Kabupaten Karanganyar. Serta merupakan sekolah yang berpendidikan karakter, berwawasan, disiplin, tanggung jawab, dan bermoral baik.'),

    'rating' => (float) env('SEKOLAH_RATING', 4.6),
    'jumlah_ulasan' => (int) env('SEKOLAH_JUMLAH_ULASAN', 217),

    'maps_url' => env('SEKOLAH_MAPS_URL', 'https://maps.google.com/?q=SMK+Negeri+2+Karanganyar'),
    'maps_embed_url' => env('SEKOLAH_MAPS_EMBED_URL', 'https://maps.google.com/maps?q=SMK%20Negeri%202%20Karanganyar&t=&z=15&ie=UTF8&iwloc=&output=embed'),

    /*
    |--------------------------------------------------------------------------
    | Mitra DUDI
    |--------------------------------------------------------------------------
    | Belum ada tabel mitra di database, jadi daftar ini masih statis.
    */

    'mitra' => [
        ['nama' => 'EXP', 'keterangan' => null],
        ['nama' => 'MSM', 'keterangan' => 'Solo'],
        ['nama' => 'NASMOCO', 'keterangan' => null],
        ['nama' => 'TOYOTA', 'keterangan' => null],
        ['nama' => 'PT. Yichao Textile Indonesia', 'keterangan' => null],
    ],

];
