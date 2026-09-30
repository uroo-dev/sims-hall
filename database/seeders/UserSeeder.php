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
    }
}
