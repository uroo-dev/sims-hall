<?php

namespace Database\Seeders\Concerns;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

trait MengunduhFotoTemplate
{
    /**
     * Unduh foto template (Unsplash) lalu kembalikan path di disk public.
     *
     * Foto yang sudah pernah terunduh tidak diambil ulang.
     */
    protected function unduhFoto(string $fotoId, string $folder): ?string
    {
        $disk = Storage::disk('public');
        $tujuan = "{$folder}/{$fotoId}.jpg";

        if ($disk->exists($tujuan)) {
            return $tujuan;
        }

        $url = "https://images.unsplash.com/{$fotoId}?w=800&auto=format&fit=crop&q=80";

        try {
            $response = Http::timeout(20)->get($url);
        } catch (\Throwable $e) {
            $this->command?->warn("Gagal mengunduh {$fotoId}: {$e->getMessage()}");

            return null;
        }

        if (! $response->successful()) {
            $this->command?->warn("Gagal mengunduh {$fotoId}: HTTP {$response->status()}");

            return null;
        }

        $disk->put($tujuan, $response->body());

        return $tujuan;
    }
}
