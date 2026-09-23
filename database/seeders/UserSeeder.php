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

        $pklBkk = User::firstOrCreate(
            ['username' => 'uroo'],
            [
                'name' => 'Admin BKK & PKL',
                'email' => 'pklbkk@smk2nkra.sch.id',
                'role' => 'admin',
                'password' => '1234',
            ]
        );

        Fitur::firstOrCreate(
            ['user_id' => $pklBkk->id],
            ['nama_fitur' => 'pklbkk']
        );
    }
}
