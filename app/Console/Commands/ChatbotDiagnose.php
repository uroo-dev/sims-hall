<?php

namespace App\Console\Commands;

use App\Services\GeminiService;
use App\Services\SchoolKnowledgeService;
use App\Support\GeminiEndpoint;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

/**
 * Diagnosa cepat integrasi chatbot Gemini.
 *
 * Perintah ini hanya membaca konfigurasi, memanggil API list-model, lalu
 * mencoba satu panggilan kecil. Tujuannya memisahkan penyebab masalah:
 * key salah, kuota habis, model tidak tersedia, atau kode yang bermasalah.
 *
 * API key tidak pernah dicetak ke layar.
 */
class ChatbotDiagnose extends Command
{
    protected $signature = 'chatbot:diagnose
                            {--question : Kirim satu pertanyaan uji ke model pertama}';

    protected $description = 'Periksa konfigurasi dan konektivitas API Gemini untuk chatbot sekolah.';

    public function handle(GeminiService $gemini, SchoolKnowledgeService $knowledge): int
    {
        $this->newLine();
        $this->line('=== Diagnosa Chatbot Gemini ===');
        $this->newLine();

        $problems = 0;

        $problems += $this->checkConfig();
        $problems += $this->checkDatabase($knowledge);
        $problems += $this->checkConnectivity();

        if ($this->option('question')) {
            $problems += $this->checkAnswer($gemini, $knowledge);
        }

        $this->newLine();
        if ($problems === 0) {
            $this->info('Semua pemeriksaan lolos. Chatbot siap dipakai.');
            $this->line('Uji jawaban penuh dengan: php artisan chatbot:diagnose --question');

            return self::SUCCESS;
        }

        $this->error("Ditemukan {$problems} masalah. Lihat rincian di atas dan storage/logs/laravel.log.");

        return self::FAILURE;
    }

    /**
     * Periksa konfigurasi lokal tanpa membocorkan key.
     */
    private function checkConfig(): int
    {
        $this->line('1. Konfigurasi');

        $key = config('services.gemini.key');
        if (blank($key)) {
            $this->error('   GEMINI_API_KEY belum diisi di .env.');

            return 1;
        }

        $this->line('   GEMINI_API_KEY   : terisi (disembunyikan)');
        $this->line('   Model (urutan)   : '.implode(' -> ', (array) config('services.gemini.models')));
        $this->line('   Max output token : '.config('services.gemini.max_output_tokens'));
        $this->line('   Thinking level   : '.config('services.gemini.thinking_level'));
        $this->line('   Retry            : '.config('services.gemini.retry_times').'x, jeda '.config('services.gemini.retry_sleep_ms').'ms');
        $this->newLine();

        return 0;
    }

    /**
     * Pastikan knowledge base bisa dibaca; tanpa ini AI akan mengarang jawaban.
     */
    private function checkDatabase(SchoolKnowledgeService $knowledge): int
    {
        $this->line('2. Knowledge base');

        try {
            $dokumen = $knowledge->cariDokumen('jurusan ppdb');

            $jumlah = collect($dokumen)->flatten()->count();
            $this->line('   Database terhubung, dokumen relevan ditemukan: '.$jumlah);

            if ($jumlah === 0) {
                $this->warn('   Tabel kosong. Jalankan: php artisan db:seed --class=ChatbotKnowledgeSeeder');
            }

            $this->newLine();

            return 0;
        } catch (Throwable $e) {
            $this->error('   Database gagal dibaca: '.Str::limit($e->getMessage(), 200));
            $this->newLine();

            return 1;
        }
    }

    /**
     * Hapus API key dari teks apa pun yang akan dicetak.
     */
    private function redact(mixed $text): string
    {
        $value = is_scalar($text) ? (string) $text : '';
        $key = (string) config('services.gemini.key');

        return $key === '' ? $value : str_replace($key, '[REDACTED]', $value);
    }

    /**
     * Pastikan endpoint Gemini terjangkau dan key dikenali.
     */
    private function checkConnectivity(): int
    {
        $this->line('3. Koneksi ke Gemini API');

        // Guard yang sama persis dengan GeminiService. Tanpa ini command ini
        // justru menjadi jalur bocor key saat endpoint salah ketik di .env.
        $problem = GeminiEndpoint::problem(config('services.gemini.endpoint'));

        if ($problem !== null) {
            $this->error('   Endpoint ditolak ('.$problem.'). API key TIDAK dikirim.');
            $this->line('   Set GEMINI_ENDPOINT ke https://generativelanguage.googleapis.com/v1beta');
            $this->newLine();

            return 1;
        }

        $url = rtrim((string) config('services.gemini.endpoint'), '/').'/models';

        try {
            $response = Http::withHeaders(['x-goog-api-key' => config('services.gemini.key')])
                ->timeout((int) config('services.gemini.timeout', 30))
                ->get($url);
        } catch (Throwable $e) {
            $this->error('   Gagal menghubungi endpoint: '.Str::limit($this->redact($e->getMessage()), 200));
            $this->newLine();

            return 1;
        }

        if ($response->status() === 429) {
            $this->error('   429 Too Many Requests: kuota project sudah habis.');
            $this->line('   Buka https://aistudio.google.com/apikey untuk cek kuota.');
            $this->line('   API key ini memakai satu project Google Cloud. Kuota project bisa');
            $this->line('   dibagi dengan identitas lain (server sekolah, aplikasi lain, collaborator).');
            $this->newLine();

            return 1;
        }

        if (! $response->successful()) {
            $this->error('   Status '.$response->status().': '.Str::limit($this->redact($response->json('error.message')), 200));
            $this->line('   401/403 berarti API key salah atau tidak punya akses ke API.');
            $this->newLine();

            return 1;
        }

        $tersedia = collect($response->json('models', []))
            ->pluck('name')
            ->map(fn ($name) => str_replace('models/', '', (string) $name))
            ->filter(fn ($name) => str_contains($name, 'gemini'))
            ->values();

        $this->line('   Endpoint dapat diakses. Model Gemini tersedia: '.$tersedia->count());

        foreach ((array) config('services.gemini.models') as $model) {
            $ada = $tersedia->contains($model);
            $this->line(($ada ? '   [OK]    ' : '   [X]    ').$model.($ada ? '' : ' (tidak tersedia untuk key ini)'));
        }

        $this->newLine();

        return 0;
    }

    /**
     * Satu panggilan jawaban penuh, memakai jalur kode yang sama dengan produksi.
     */
    private function checkAnswer(GeminiService $gemini, SchoolKnowledgeService $knowledge): int
    {
        $this->line('4. Uji jawaban');

        $pertanyaan = 'Apa saja jurusan yang tersedia?';
        $dokumen = $knowledge->cariDokumenAman($pertanyaan);
        $context = $knowledge->buildContext($pertanyaan, $dokumen);

        $result = $gemini->ask($knowledge->systemInstruction(), [[
            'role' => 'user',
            'parts' => [['text' => $context."\n\n=== PERTANYAAN PENGGUNA ===\n".$pertanyaan]],
        ]]);

        if ($result->successful()) {
            $this->info('   Gemini menjawab ('.$result->model.'): '.Str::limit($result->text, 200));

            return 0;
        }

        $this->error('   Gemini gagal. alasan='.$result->reason.' status='.($result->status ?? 'n/a'));
        $this->line('   Chatbot tetap menjawab dari database. Pesan cadangan: '.$result->userMessage());

        if ($result->reason === 'blocked') {
            $this->line('   Penyebab paling umum: maxOutputTokens habis terpakai thinking.');
            $this->line('   Naikkan GEMINI_MAX_OUTPUT_TOKENS atau turunkan GEMINI_THINKING_LEVEL.');
        }

        if ($result->reason === 'overloaded') {
            $this->line('   Penyebab: model sedang overload/kuota project penuh.');
        }

        return 1;
    }
}
