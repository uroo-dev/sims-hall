<?php

namespace App\Observers;

use App\Models\ProdukUnggulan;
use Illuminate\Support\Facades\Storage;

class ProdukUnggulanObserver
{
    /**
     * Tunda penghapusan file sampai transaksi database berhasil commit.
     */
    public bool $afterCommit = true;

    /**
     * Hapus file dokumentasi yang sudah tidak tercantum lagi setelah disimpan.
     */
    public function updated(ProdukUnggulan $produkUnggulan): void
    {
        if (! $produkUnggulan->wasChanged('dokumentasi')) {
            return;
        }

        $lama = $this->pecah($produkUnggulan->getOriginal('dokumentasi'));
        $baru = $produkUnggulan->dokumentasi_list;

        $this->hapusFile(array_diff($lama, $baru));
    }

    /**
     * Hapus seluruh dokumentasi ketika data direset.
     */
    public function deleted(ProdukUnggulan $produkUnggulan): void
    {
        $this->hapusFile($produkUnggulan->dokumentasi_list);
    }

    /**
     * Pecah kolom dokumentasi yang dipisah koma menjadi list path.
     *
     * @return list<string>
     */
    protected function pecah(?string $dokumentasi): array
    {
        $paths = explode(',', (string) $dokumentasi);

        return array_values(array_filter(array_map('trim', $paths)));
    }

    /**
     * Hapus sekumpulan file dokumentasi dari disk public.
     *
     * @param  iterable<string>  $paths
     */
    protected function hapusFile(iterable $paths): void
    {
        foreach ($paths as $path) {
            if (filled($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }
}
