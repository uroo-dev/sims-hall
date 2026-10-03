<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Kesiswaan extends Model
{
    use HasFactory;

    protected $table = 'kesiswaan';

    protected $primaryKey = 'kesiswaanID';

    protected $fillable = [
        'judul',
        'deskripsi',
        'dokumentasi',
    ];

    /**
     * Singleton row settings
     */
    public static function current(): self
    {
        $kesiswaan = static::query()->first();
        if (! $kesiswaan) {
            $kesiswaan = static::create([
                'judul' => 'Kesiswaan SMKN 2 Karanganyar',
                'deskripsi' => 'Membangun karakter unggul melalui integrasi nilai moral dan penguasaan teknologi. Kami berdedikasi untuk membina potensi setiap siswa dalam lingkungan yang inklusif, inovatif, dan disiplin.',
                'dokumentasi' => null,
            ]);
        }

        return $kesiswaan;
    }

    /**
     * Array of documentation image paths
     */
    public function getDokumentasiListAttribute(): array
    {
        if (blank($this->dokumentasi)) {
            return [];
        }
        $decoded = json_decode($this->dokumentasi, true);
        if (is_array($decoded)) {
            return $decoded;
        }

        return array_filter(array_map('trim', explode(',', $this->dokumentasi)));
    }

    /**
     * URLs untuk foto dokumentasi kesiswaan.
     *
     * Hanya berisi entri yang benar-benar diisi admin. Versi lama mengunduh
     * `assets/prestasi/banner_terbaru_*.png` sebagai cadangan, sehingga halaman
     * publik menampilkan foto prestasi sebagai dokumentasi kesiswaan walaupun
     * belum ada data — sekarang array-nya bisa kosong dan pemanggil yang
     * menentukan tampil-tidaknya kolase.
     *
     * @return list<string>
     */
    public function getDokumentasiUrlsAttribute(): array
    {
        $urls = [];

        foreach ($this->dokumentasi_list as $path) {
            if (blank($path)) {
                continue;
            }

            if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                $urls[] = $path;
            } elseif (str_starts_with($path, 'assets/')) {
                $urls[] = asset($path);
            } else {
                $urls[] = Storage::disk('public')->url($path);
            }
        }

        return $urls;
    }
}
