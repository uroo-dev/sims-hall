<?php

namespace App\Services;

use App\Support\GeminiEndpoint;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Layanan integrasi Google Gemini API.
 *
 * Semua pemanggilan Gemini dilakukan dari backend Laravel memakai
 * Http Facade. API key hanya dibaca dari config('services.gemini.key')
 * yang bersumber dari file .env, sehingga tidak pernah bocor ke
 * JavaScript, Blade, HTML, maupun repository.
 *
 * Ketahanan terhadap gangguan Gemini:
 * - Retry exponential backoff + jitter untuk error transien (408/429/5xx),
 *   sesuai anjuran resmi Google. Error permanen (400/401/403) tidak diulang.
 * - Rantai model cadangan: pindah ke model berikutnya hanya pada 404/503.
 */
class GeminiService
{
    /**
     * Batas keras jumlah panggilan HTTP per satu permintaan pengguna.
     *
     * Tanpa batas ini, rantai model x retry bisa memblokir satu PHP-FPM worker
     * selama puluhan detik dan menguras kuota saat Gemini sedang padat.
     */
    private const MAKSIMAL_PANGGILAN = 5;

    /**
     * Status yang layak dicoba ulang pada model yang sama (transien).
     *
     * 503 sengaja TIDAK ada di sini: overload bersifat per-model, jadi lebih
     * cepat pindah model daripada mengulang model yang sama tiga kali.
     */
    private const STATUS_TRANSIEN = [408, 429, 500, 502, 504];

    /**
     * Status yang layak pindah ke model berikutnya tanpa retry.
     */
    private const STATUS_GANTI_MODEL = [404, 503];

    /**
     * Kirim percakapan ke Gemini dan kembalikan hasilnya.
     *
     * @param  string  $systemInstruction  Instruksi sistem (perilaku AI).
     * @param  array<int, array{role: string, parts: array<int, array{text: string}>}>  $contents  Riwayat percakapan (role: user|model).
     */
    public function ask(string $systemInstruction, array $contents): GeminiResult
    {
        $apiKey = config('services.gemini.key');

        if (blank($apiKey)) {
            Log::error('Gemini API key belum dikonfigurasi di .env (GEMINI_API_KEY).');

            return new GeminiResult(null, 'no_key');
        }

        $endpointError = $this->endpointIsTrusted();

        if ($endpointError !== null) {
            Log::critical('Endpoint Gemini ditolak, API key tidak dikirim.', [
                'alasan' => $endpointError,
            ]);

            return new GeminiResult(null, 'misconfigured');
        }

        $models = $this->models();

        // Semua id model dikonfigurasi tidak valid. Lebih baik jatuh ke
        // fallback database daripada diam-diam memakai model lain.
        if ($models === []) {
            Log::critical('Tidak ada id model Gemini yang valid pada konfigurasi.', [
                'dikonfigurasi' => config('services.gemini.models'),
            ]);

            return new GeminiResult(null, 'misconfigured');
        }

        $deadline = $this->deadline();
        $panggilan = 0;
        $lastStatus = null;
        $lastReason = 'failed';

        foreach ($models as $index => $model) {
            if ($index > 0 && $this->kehabisanWaktu($deadline)) {
                Log::warning('Budget waktu habis, berhenti mencoba model tambahan.', ['sudah' => $panggilan]);

                break;
            }

            $result = $this->callModel($apiKey, $model, $systemInstruction, $contents, $deadline, $panggilan);

            $panggilan += $result->attempts;

            if ($result->result->successful()) {
                return $result->result;
            }

            $lastStatus = $result->result->status;
            $lastReason = $result->result->reason;

            $isLastModel = $index === array_key_last($models);

            if (! in_array($lastStatus, self::STATUS_GANTI_MODEL, true) || $isLastModel) {
                return $result->result;
            }

            if ($panggilan >= self::MAKSIMAL_PANGGILAN) {
                Log::warning('Batas jumlah panggilan tercapai, berhenti failover.', ['panggilan' => $panggilan]);

                break;
            }

            Log::warning('Pindah ke model Gemini berikutnya.', [
                'model_gagal' => $model,
                'model_baru' => $models[$index + 1],
                'status' => $lastStatus,
            ]);
        }

        return new GeminiResult(null, $lastReason, $lastStatus);
    }

    /**
     * Pastikan endpoint tujuan adalah host Google tepercaya sebelum key dikirim.
     *
     * Logikanya dipusatkan di GeminiEndpoint supaya command chatbot:diagnose
     * memakai pemeriksaan yang persis sama dan tidak bisa melewatinya.
     *
     * @return string|null Alasan penolakan, atau null bila aman.
     */
    private function endpointIsTrusted(): ?string
    {
        return GeminiEndpoint::problem(config('services.gemini.endpoint'));
    }

    /**
     * Batas waktu total untuk satu percobaan menjawab, dalam milidetik.
     */
    private function deadline(): int
    {
        return hrtime(true) + (max(3, (int) config('services.gemini.budget_seconds', 20)) * 1_000_000_000);
    }

    private function kehabisanWaktu(int $deadline): bool
    {
        return hrtime(true) >= $deadline;
    }

    /**
     * Daftar model yang akan dicoba, urut dari yang paling preferred.
     *
     * Id model disaring ketat karena masuk ke path URL; karakter di luar
     * [a-z0-9._-] ditolak supaya tidak bisa mengubah path endpoint.
     *
     * Mengembalikan array kosong bila tidak ada yang valid; pemanggil yang
     * memutuskan, bukan method ini.
     *
     * @return array<int, string>
     */
    private function models(): array
    {
        $models = array_values(array_filter(
            (array) config('services.gemini.models', []),
            fn ($model) => is_string($model) && preg_match('/^[A-Za-z0-9._-]+$/', trim($model)) === 1,
        ));

        return $models;
    }

    /**
     * Panggil satu model, lengkap dengan percobaan ulang exponential backoff.
     *
     * @param  array<int, array{role: string, parts: array<int, array{text: string}>}>  $contents
     */
    private function callModel(
        string $apiKey,
        string $model,
        string $systemInstruction,
        array $contents,
        int $deadline,
        int $panggilanSekarang,
    ): GeminiAttempt {
        $url = sprintf(
            '%s/models/%s:generateContent',
            rtrim((string) config('services.gemini.endpoint'), '/'),
            $model,
        );

        $payload = [
            'system_instruction' => ['parts' => [['text' => $systemInstruction]]],
            'contents' => $contents,
            'generationConfig' => $this->generationConfig(),
        ];

        // Batas keras: jumlah config, sisa budget global, dan sisa budget
        // panggilan. Tidak ada jalur yang bisa melebihi MAKSIMAL_PANGGILAN.
        $tries = min(
            max(1, (int) config('services.gemini.retry_times', 3)),
            self::MAKSIMAL_PANGGILAN - $panggilanSekarang,
        );

        $attempts = 0;

        for ($attempt = 1; $attempt <= $tries; $attempt++) {
            if ($attempt > 1 && $this->kehabisanWaktu($deadline)) {
                Log::warning('Budget waktu habis, berhenti mencoba ulang.', ['model' => $model]);

                return new GeminiAttempt(new GeminiResult(null, 'failed', null, $model), $attempts);
            }

            $attempts++;

            try {
                $response = Http::withHeaders([
                    'x-goog-api-key' => $apiKey,
                    'Content-Type' => 'application/json',
                ])
                    ->connectTimeout((int) config('services.gemini.connect_timeout', 5))
                    ->timeout($this->requestTimeout())
                    // Redirect dimatikan: Guzzle mempertahankan header
                    // x-goog-api-key saat mengikuti redirect, sehingga key bisa
                    // bocor ke host lain.
                    ->withOptions(['allow_redirects' => false])
                    ->post($url, $payload);
            } catch (ConnectionException $e) {
                Log::warning('Koneksi ke Gemini API gagal.', [
                    'model' => $model,
                    'percobaan' => "{$attempt}/{$tries}",
                    'message' => $this->redact($e->getMessage()),
                ]);

                if ($attempt < $tries) {
                    $this->sleepBackoff($attempt);

                    continue;
                }

                return new GeminiAttempt(new GeminiResult(null, 'failed', null, $model), $attempts);
            } catch (\Throwable $e) {
                // Jaringan/HTTP yang tidak terduga tidak boleh membuat
                // endpoint publik mengembalikan 500.
                Log::error('Kesalahan tak terduga saat memanggil Gemini.', [
                    'model' => $model,
                    'message' => $this->redact($e->getMessage()),
                ]);

                return new GeminiAttempt(new GeminiResult(null, 'failed', null, $model), $attempts);
            }

            $result = $this->interpret($response, $model);

            if ($result->successful()) {
                return new GeminiAttempt($result, $attempts);
            }

            $status = $response->status();

            if (! in_array($status, self::STATUS_TRANSIEN, true) || $attempt === $tries) {
                return new GeminiAttempt($result, $attempts);
            }

            Log::warning('Gemini API error transien, mencoba ulang.', [
                'model' => $model,
                'status' => $status,
                'percobaan' => "{$attempt}/{$tries}",
            ]);

            $this->sleepBackoff($attempt);
        }

        return new GeminiAttempt(new GeminiResult(null, 'failed', null, $model), $attempts);
    }

    /**
     * Timeout per permintaan, dibatasi agar env kosong tidak jadi tak terbatas.
     */
    private function requestTimeout(): int
    {
        return max(1, min(60, (int) config('services.gemini.timeout', 20)));
    }

    /**
     * @return array<string, mixed>
     */
    private function generationConfig(): array
    {
        return [
            'temperature' => (float) config('services.gemini.temperature', 0.4),
            // Token thinking ikut dihitung dari maxOutputTokens, jadi nilai
            // ini tidak boleh kecil.
            'maxOutputTokens' => max(2048, (int) config('services.gemini.max_output_tokens', 2048)),
            'thinkingConfig' => [
                'thinkingLevel' => (string) config('services.gemini.thinking_level', 'low'),
            ],
        ];
    }

    /**
     * Terjemahkan respons HTTP Gemini menjadi GeminiResult.
     */
    private function interpret(Response $response, string $model): GeminiResult
    {
        $status = $response->status();

        if ($response->successful()) {
            $json = $response->json();
            $answer = $this->extractAnswer($json);

            if (blank($answer)) {
                $finishReason = (string) data_get($json, 'candidates.0.finishReason', '');
                $blockReason = data_get($json, 'promptFeedback.blockReason');

                Log::warning('Gemini tidak mengembalikan teks jawaban.', [
                    'model' => $model,
                    'finish_reason' => $finishReason,
                    'block_reason' => $blockReason,
                    'total_tokens' => data_get($json, 'usageMetadata.totalTokenCount'),
                    'thought_tokens' => data_get($json, 'usageMetadata.thoughtsTokenCount'),
                ]);

                // MAX_TOKENS berarti kuota token habis dipakai thinking, bukan penolakan
                // safety. Bedakan supaya pesan fallback tepat sasaran.
                $reason = $finishReason === 'MAX_TOKENS' ? 'truncated' : 'blocked';

                return new GeminiResult(null, $reason, $status, $model);
            }

            return new GeminiResult($answer, 'ok', $status, $model);
        }

        Log::error('Gemini API mengembalikan respons gagal.', [
            'model' => $model,
            'status' => $status,
            'message' => $this->redact($response->json('error.message') ?? Str::limit($response->body(), 300)),
        ]);

        $reason = match (true) {
            $status === 429 => 'rate_limit',
            $status === 503 => 'overloaded',
            default => 'failed',
        };

        return new GeminiResult(null, $reason, $status, $model);
    }

    /**
     * Tunggu dengan backoff eksponensial + jitter acak.
     *
     * Jitter mencegah semua pengguna mencoba ulang pada detik yang sama.
     */
    private function sleepBackoff(int $attempt): void
    {
        // Dibatasi 2 detik: jeda panjang memblokir worker PHP sehingga
        // visitor lain ikut menunggu, bukan hanya satu permintaan ini.
        $base = max(0, min(2000, (int) config('services.gemini.retry_sleep_ms', 500)));
        $delay = (int) ($base * (2 ** ($attempt - 1)));
        $jitter = random_int(50, 150) / 100;

        usleep((int) (min(2000, $delay * $jitter) * 1000));
    }

    /**
     * Hapus API key dari teks sebelum masuk log.
     *
     * Pesan error provider sangat jarang memantulkan key, tapi log adalah
     * tempat yang paling sering bocor dan paling sulit dipurge.
     */
    private function redact(mixed $text): string
    {
        $value = is_scalar($text) ? (string) $text : '';
        $key = (string) config('services.gemini.key');

        if ($key === '' || $value === '') {
            return $value;
        }

        return str_replace($key, '[REDACTED]', $value);
    }

    /**
     * Gabungkan seluruh potongan teks dari respons Gemini.
     *
     * @param  array<string, mixed>|null  $json
     */
    private function extractAnswer(mixed $json): string
    {
        $parts = data_get((array) $json, 'candidates.0.content.parts', []);

        $texts = [];
        foreach ((array) $parts as $part) {
            $text = data_get($part, 'text');
            if (is_string($text) && $text !== '') {
                $texts[] = $text;
            }
        }

        return trim(implode('', $texts));
    }
}
