<?php

namespace Database\Seeders;

use App\Models\Fitur;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminAula extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['username' => 'admin_aula'],
            [
                'name' => 'Admin Aula',
                'email' => 'admin_aula@example.com',
                'role' => 'admin',
                'password' => 'password',
            ]
        );

        Fitur::firstOrCreate(
            ['user_id' => $user->id],
            [
                'nama_fitur' => 'aula',
            ]
        );
    }
}
