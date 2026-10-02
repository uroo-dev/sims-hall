<?php

namespace Database\Seeders;

use App\Models\User;
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
            'name' => 'Admin Aula',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'role' => 'admin_aula',
        ]);

        $this->call([
            UserSeeder::class,
            AdminAula::class,
            FasilitasSeeder::class,
            PaketPeminjamanSeeder::class,
            PaymentConfigurationSeeder::class,
            BkkSeeder::class,
            ChatbotKnowledgeSeeder::class,
            DataMasterSeeder::class,
            JurusanSeeder::class,
            ProdukSeeder::class,
            ProdukUnggulanSeeder::class,
        ]);
    }
}
