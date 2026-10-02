<?php

namespace App\Console\Commands;

use App\Models\Jurusan;
use App\Models\Produk;
use App\Models\ProdukUnggulan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class BersihkanDokumentasi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dokumentasi:bersihkan {--hapus : Benar-benar hapus file, bukan hanya menampilkan daftar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cari file dokumentasi di disk public yang tidak lagi dirujuk database';

    /**
     * Folder dokumentasi yang dikelola aplikasi.
     *
     * @var list<string>
     */
    protected array $folder = ['produk', 'produk-unggulan', 'jurusan'];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $disk = Storage::disk('public');

        $terpakai = $this->pathTerpakai();

        $sampah = [];

        foreach ($this->folder as $folder) {
            foreach ($disk->files($folder) as $path) {
                if (! in_array($path, $terpakai, true)) {
                    $sampah[] = $path;
                }
            }
        }

        if ($sampah === []) {
            $this->components->info('Tidak ada file dokumentasi yang menganggur.');

            return self::SUCCESS;
        }

        $this->components->twoColumnDetail('<fg=yellow>File menganggur</>', (string) count($sampah));

        foreach ($sampah as $path) {
            $this->line('  '.$path);
        }

        if (! $this->option('hapus')) {
            $this->newLine();
            $this->components->warn('Jalankan ulang dengan --hapus untuk menghapus file di atas.');

            return self::SUCCESS;
        }

        $disk->delete($sampah);

        $this->newLine();
        $this->components->info(count($sampah).' file dokumentasi berhasil dihapus.');

        return self::SUCCESS;
    }

    /**
     * Seluruh path dokumentasi yang masih dirujuk database.
     *
     * @return list<string>
     */
    protected function pathTerpakai(): array
    {
        $produkUnggulan = ProdukUnggulan::query()
            ->pluck('dokumentasi')
            ->flatMap(fn (?string $dokumentasi): array => explode(',', (string) $dokumentasi));

        return Produk::query()
            ->whereNotNull('dokumentasi')
            ->pluck('dokumentasi')
            ->merge(Jurusan::query()->whereNotNull('dokumentasi')->pluck('dokumentasi'))
            ->merge($produkUnggulan)
            ->map(fn (?string $path): string => trim((string) $path))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }
}
