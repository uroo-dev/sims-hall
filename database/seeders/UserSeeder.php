<?php

namespace Database\Seeders;

use App\Models\Fitur;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['username' => 'root'],
            [
                'name' => 'super admin',
                'email' => 'super@gmail.com',
                'role' => 'super_admin',
                'password' => '1234',
            ]
        );

        User::firstOrCreate(
            ['username' => 'uroo'],
            [
                'name' => 'Admin BKK & PKL',
                'email' => 'pklbkk@smk2nkra.sch.id',
                'role' => 'bkk',
                'password' => '1234',
            ]
        );

        User::firstOrCreate(
            ['username' => 'bkk'],
            [
                'name' => 'Operator BKK',
                'email' => 'bkk@smk2nkra.sch.id',
                'role' => 'bkk',
                'password' => '1234',
            ]
        );

        $datamaster = User::firstOrCreate(
            ['username' => 'dafin'],
            [
                'name' => 'Admin Data Master Sekolah',
                'email' => 'datamastersekolah@smk2nkra.sch.id',
                'role' => 'admin',
                'password' => '1234',
            ]
        );

        // Memberi akun ini akses modul Data Master Sekolah lewat tabel `fiturs`.
        Fitur::firstOrCreate(
            ['user_id' => $datamaster->id],
            ['nama_fitur' => 'master']
        );
    }
}
