<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminAula extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['username' => 'admin_aula'],
            [
                'name' => 'Admin Aula',
                'email' => 'admin_aula@example.com',
                'role' => 'admin_aula',
                'password' => 'password',
            ]
        );
    }
}
