<?php

namespace Database\Seeders;

use App\Models\Produk;
use App\Models\User;
use Database\Seeders\JurusanSeeder;
use Database\Seeders\ProdukSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'username' => 'admin',
            'email' => 'test@example.com',
            'role' => 'admin',
            'password' => '1234'
        ]);

        $this->call([
            UserSeeder::class,
            JurusanSeeder::class,
            ProdukSeeder::class
        ]);
    }
}
