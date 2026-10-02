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
     * URLs for documentation
     */
    public function getDokumentasiUrlsAttribute(): array
    {
        $list = $this->dokumentasi_list;
        $defaults = [
            asset('assets/prestasi/banner_terbaru_2.png'),
            asset('assets/prestasi/banner_terbaru_1.png'),
        ];

        $urls = [];
        for ($i = 0; $i < 2; $i++) {
            if (isset($list[$i]) && ! blank($list[$i])) {
                $path = $list[$i];
                if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                    $urls[$i] = $path;
                } elseif (str_starts_with($path, 'assets/')) {
                    $urls[$i] = asset($path);
                } else {
                    $urls[$i] = Storage::disk('public')->url($path);
                }
            } else {
                $urls[$i] = $defaults[$i];
            }
        }

        return $urls;
    }
}
