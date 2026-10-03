<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use Illuminate\Database\Seeder;

class JurusanSeeder extends Seeder
{
    /**
     * Data jurusan SMK Negeri 2 Karanganyar.
     *
     * @var list<array<string, string>>
     */
    protected array $jurusan = [
        [
            'nama' => 'Rekayasa Perangkat Lunak',
            'deskripsi' => 'Karya perangkat lunak, aplikasi, dan sistem digital karya siswa RPL.',
        ],
        [
            'nama' => 'Teknik Ototronik',
            'deskripsi' => 'Karya teknik dan produk elektronik berbasis sensor.',
        ],
        [
            'nama' => 'Teknik Pemesinan',
            'deskripsi' => 'Karya hasil rekayasa mesin dan manufaktur precision.',
        ],
        [
            'nama' => 'Teknik Pembuatan Kain',
            'deskripsi' => 'Karya tekstil, jahit, dan desain motif lokal.',
        ],
        [
            'nama' => 'Desain Komunikasi Visual',
            'deskripsi' => 'Karya grafis, animasi, fotografi, dan komunikasi visual.',
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->jurusan as $jurusan) {
            Jurusan::firstOrCreate(['nama' => $jurusan['nama']], $jurusan);
        }
    }
}
