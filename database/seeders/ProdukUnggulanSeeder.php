<?php

namespace Database\Seeders;

use App\Models\ProdukUnggulan;
use Database\Seeders\Concerns\MengunduhFotoTemplate;
use Illuminate\Database\Seeder;

class ProdukUnggulanSeeder extends Seeder
{
    use MengunduhFotoTemplate;

    /**
     * Teks landing page, diambil dari resources/views/template/produk_unggulan.html.
     *
     * @var array<string, string>
     */
    protected array $pengaturan = [
        'judul' => 'Produk Unggulan SMKN 2 Karanganyar',
        'deskripsi' => 'Di SMKN 2 Karanganyar, kami tidak hanya mendidik, tetapi juga mencetak inovator. Melalui kurikulum berbasis industri dan fasilitas laboratorium terkini, siswa kami menghasilkan karya-karya nyata yang kompetitif, presisi, dan siap menjawab tantangan pasar global.',
    ];

    /**
     * Dokumentasi hero, 4 foto jurusan pada template.
     *
     * Urutan mengikuti jurusanID: RPL, Ototronik, Permesinan, Teknik Pembuatan Kain.
     *
     * @var list<string>
     */
    protected array $dokumentasi = [
        'photo-1531403009284-440f080d1e12',
        'photo-1486262715619-67b85e0b08d3',
        'photo-1581091226825-a6a2a5aee158',
        'photo-1528459801416-a9e53bbf4e17',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = $this->pengaturan;
        $data['dokumentasi'] = implode(',', $this->salinDokumentasi());

        $produkUnggulan = ProdukUnggulan::query()->first();

        if ($produkUnggulan) {
            $produkUnggulan->update($data);

            return;
        }

        ProdukUnggulan::query()->create($data);
    }

    /**
     * Unduh seluruh foto hero, lalu kembalikan path tersimpannya.
     *
     * @return list<string>
     */
    protected function salinDokumentasi(): array
    {
        $paths = [];

        foreach ($this->dokumentasi as $fotoId) {
            if ($path = $this->unduhFoto($fotoId, 'produk-unggulan')) {
                $paths[] = $path;
            }
        }

        return $paths;
    }
}
