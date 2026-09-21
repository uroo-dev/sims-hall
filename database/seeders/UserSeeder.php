<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            'username' => 'root',
            'role' => 'super_admin',
            'name' => 'super admin',
            'email' => 'super@gmail.com',
            'password' => bcrypt('1234'),
        ]);
    }
}
