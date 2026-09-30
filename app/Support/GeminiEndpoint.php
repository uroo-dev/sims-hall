<?php

namespace App\Support;

/**
 * Penjaga endpoint Gemini.
 *
 * API key dikirim lewat header x-goog-api-key, jadi endpoint yang salah ketik
 * di .env akan berarti key produksi terkirim ke host lain. Karena itu endpoint
 * wajib https dan host-nya harus ada di allowlist, dan redirect dimatikan.
 *
 * Dipakai bersama oleh GeminiService dan command chatbot:diagnose supaya
 * tidak ada jalur yang bisa melewati pemeriksaan ini.
 */
final class GeminiEndpoint
{
    /**
     * @var array<int, string>
     */
    private const HOST_DIJARKAN = ['generativelanguage.googleapis.com'];

    /**
     * Alasan penolakan endpoint, atau null bila aman dipakai.
     */
    public static function problem(mixed $endpoint): ?string
    {
        if (! is_string($endpoint) || $endpoint === '') {
            return 'endpoint kosong atau bukan string';
        }

        $parts = parse_url($endpoint);

        if ($parts === false) {
            return 'endpoint tidak bisa diurai';
        }

        if (strtolower((string) ($parts['scheme'] ?? '')) !== 'https') {
            return 'endpoint wajib memakai https';
        }

        if (! in_array(strtolower((string) ($parts['host'] ?? '')), self::HOST_DIJARKAN, true)) {
            return 'host endpoint tidak ada di allowlist';
        }

        if (($parts['user'] ?? null) !== null) {
            return 'endpoint tidak boleh memuat kredensial userinfo';
        }

        return null;
    }

    /**
     * true bila endpoint aman dipakai.
     */
    public static function isTrusted(mixed $endpoint): bool
    {
        return self::problem($endpoint) === null;
    }
}
