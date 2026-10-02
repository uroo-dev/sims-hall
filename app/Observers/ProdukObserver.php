<?php

namespace App\Observers;

use App\Models\Produk;
use Illuminate\Support\Facades\Storage;

class ProdukObserver
{
    /**
     * Tunda penghapusan file sampai transaksi database berhasil commit.
     */
    public bool $afterCommit = true;

    /**
     * Hapus file lama ketika dokumentasi diganti.
     */
    public function updated(Produk $produk): void
    {
        if (! $produk->wasChanged('dokumentasi')) {
            return;
        }

        $this->hapusFile($produk->getOriginal('dokumentasi'));
    }

    /**
     * Hapus dokumentasi ketika produk dihapus.
     */
    public function deleted(Produk $produk): void
    {
        $this->hapusFile($produk->dokumentasi);
    }

    /**
     * Hapus satu file dokumentasi dari disk public bila ada.
     */
    protected function hapusFile(?string $path): void
    {
        if (filled($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
