<?php

namespace App\Services;

/**
 * Hasil satu panggilan ke Gemini API.
 *
 * Memisahkan "jawaban sukses" dari "gagal", supaya pemanggil (controller)
 * bisa otomatis memakai fallback deterministik dari database alih-alih
 * menampilkan pesan permintaan maaf.
 */
final readonly class GeminiResult
{
    /**
     * @param  string|null  $text  Teks jawaban AI, atau null bila gagal.
     * @param  string  $reason  Kode alasan: ok, no_key, misconfigured, rate_limit, overloaded, truncated, blocked, failed.
     * @param  int|null  $status  HTTP status terakhir dari Gemini.
     * @param  string|null  $model  Model yang menghasilkan respons terakhir.
     */
    public function __construct(
        public ?string $text,
        public string $reason,
        public ?int $status = null,
        public ?string $model = null,
    ) {}

    public function successful(): bool
    {
        return $this->text !== null && trim($this->text) !== '';
    }

    /**
     * Pesan ramah untuk pengguna. Tidak pernah memuat detail teknis
     * (nama endpoint, pesan error provider, HTTP status).
     */
    public function userMessage(): string
    {
        return match ($this->reason) {
            'rate_limit' => 'Layanan Nanya AI sedang mencapai batas penggunaan harian. Silakan coba lagi besok, atau hubungi admin sekolah melalui halaman Kontak untuk informasi resmi terbaru.',
            'overloaded' => 'Layanan Nanya AI sedang sangat ramai. Silakan coba lagi dalam beberapa saat, atau hubungi admin sekolah melalui halaman Kontak.',
            'truncated', 'misconfigured', 'no_key' => 'Maaf, layanan Nanya AI sedang tidak dapat dihubungi sementara waktu. Silakan coba beberapa saat lagi, atau hubungi admin sekolah melalui halaman Kontak untuk informasi resmi terbaru.',
            default => 'Maaf, jawaban dari Nanya AI belum tersedia untuk pertanyaan ini. Silakan hubungi admin sekolah melalui halaman Kontak untuk informasi resmi terbaru.',
        };
    }
}
