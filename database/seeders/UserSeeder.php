<?php

namespace Database\Seeders;

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
            ['username' => 'admin_produk'],
            [
                'name' => 'produk',
                'email' => 'produk@gmail.com',
                'role' => 'admin_produk',
                'password' => '1234',
            ]
        );
        User::firstOrCreate(
            ['username' => 'dwika'],
            [
                'name' => 'dwika',
                'email' => 'dwika@gmail.com',
                'role' => 'pelanggan',
                'password' => '1234',
            ]
        );
        User::firstOrCreate(
            ['username' => 'kepsek'],
            [
                'name' => 'Kepala Sekolah',
                'email' => 'kepsek@smk2nkra.sch.id',
                'role' => 'kepala_sekolah',
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

        User::firstOrCreate(
            ['username' => 'dafin'],
            [
                'name' => 'Admin Data Master Sekolah',
                'email' => 'datamastersekolah@smk2nkra.sch.id',
                'role' => 'admin_master',
                'password' => '1234',
            ]
        );

        User::firstOrCreate(
            ['username' => 'admin_sekolah'],
            [
                'name' => 'Admin Sekolah',
                'email' => 'adminsekolah@smk2nkra.sch.id',
                'role' => 'admin_sekolah',
                'password' => '1234',
            ]
        );
    }
}
