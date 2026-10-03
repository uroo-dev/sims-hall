<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Akun admin. Setiap admin bagian memakai username `admin_<bagian>` dan
 * password `password`, sehingga mudah diingat saat login di menu Dashboard.
 */
class UserSeeder extends Seeder
{
    /**
     * Role admin per bagian dan label yang ditampilkan di halaman login.
     *
     * @var array<string, string>
     */
    private const ADMIN_BAGIAN = [
        'admin_aula' => 'Admin Aula',
        'admin_master' => 'Admin Data Master',
        'admin_kesiswaan' => 'Admin Kesiswaan',
        'admin_produk' => 'Admin Produk',
        'admin_produk_unggulan' => 'Admin Produk Unggulan',
        'admin_ppdb' => 'Admin PPDB',
        'admin_pklbkk' => 'Admin PKL BKK',
        'admin_sekolah' => 'Admin Sekolah',
    ];

    public function run(): void
    {
        $this->buatAkun('root', 'Super Admin', 'super_admin');
        $this->buatAkun('super_duper', 'Super Duper Admin', 'super_duper_admin');

        foreach (self::ADMIN_BAGIAN as $bagian => $nama) {
            $this->buatAkun($bagian, $nama, $bagian);
        }

        $this->buatAkun('bkk', 'Bursa Kerja Khusus', 'bkk');
        $this->buatAkun('uroo', 'Bursa Kerja Khusus', 'bkk');
    }

    /**
     * Buat satu akun. Data yang sudah ada hanya diperbarui password, role, dan
     * nama supaya admin tidak terkunci setelah seeding ulang.
     */
    private function buatAkun(string $username, string $nama, string $role): void
    {
        User::updateOrCreate(
            ['username' => $username],
            [
                'name' => $nama,
                'email' => $username.'@sims-hall.test',
                'role' => $role,
                'password' => 'password',
            ]
        );
    }
}
