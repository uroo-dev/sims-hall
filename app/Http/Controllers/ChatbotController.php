<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChatbotSendRequest;
use App\Services\GeminiResult;
use App\Services\GeminiService;
use App\Services\SchoolKnowledgeService;
use Illuminate\Http\JsonResponse;

/**
 * Controller chatbot "Nanya AI".
 *
 * Alur: validasi & sanitasi input -> pencarian konteks resmi dari
 * database (RAG sederhana) -> panggil Gemini lewat backend ->
 * bila Gemini gagal, jawab deterministik dari database -> kembalikan
 * JSON {answer, timestamp}.
 *
 * Endpoint ini tidak pernah mengembalikan 5xx karena gangguan Gemini
 * maupun database: dua-duanya punya jalur cadangan yang ramah pengguna.
 */
class ChatbotController extends Controller
{
    /**
     * Batas jumlah giliran yang dikirim ke Gemini.
     */
    private const MAKSIMAL_GILIRAN = 10;

    public function __construct(
        private readonly GeminiService $gemini,
        private readonly SchoolKnowledgeService $knowledge,
    ) {}

    /**
     * Endpoint POST /chatbot/send - terima pertanyaan, kembalikan jawaban AI.
     */
    public function send(ChatbotSendRequest $request): JsonResponse
    {
        $question = mb_substr(trim((string) $request->validated('message')), 0, 800);

        // cariDokumenAman() tidak melempar error walau tabel belum ada
        // maupun database mati, sehingga request tidak berakhir sebagai HTTP 500.
        $dokumen = $this->knowledge->cariDokumenAman($question);
        $context = $this->knowledge->buildContext($question, $dokumen);

        $contents = $this->buildContents(
            $request->validated('history') ?? [],
            $this->buildPrompt($question, $context),
        );

        $result = $this->gemini->ask($this->knowledge->systemInstruction(), $contents);

        $answer = $result->successful()
            ? (string) $result->text
            : $this->jawabanCadangan($question, $dokumen, $result);

        return response()->json([
            'answer' => $answer,
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Susun contents Gemini: giliran wajib bergantian user/model.
     *
     * Gemini menolak dua giliran dengan role sama
     * (INVALID_ARGUMENT: contents must alternate). Frontend kita bisa
     * mengirim history yang role-nya beruntun - misalnya jawaban model
     * yang gagal dibuang sehingga menyisakan dua pesan "user" - sehingga
     * history harus dinormalisasi ulang sebelum dikirim.
     *
     * @param  array<int, mixed>  $history
     * @return array<int, array{role: string, parts: array<int, array{text: string}>}>
     */
    private function buildContents(array $history, string $latestQuestion): array
    {
        $turns = $this->sanitizeHistory($history);

        // Percakapan harus dimulai dari user, bukan model.
        while ($turns !== [] && $turns[0]['role'] === 'model') {
            array_shift($turns);
        }

        // Gabungkan giliran beruntun yang role-nya sama.
        $merged = [];
        foreach ($turns as $turn) {
            $lastIndex = array_key_last($merged);

            if ($lastIndex !== null && $merged[$lastIndex]['role'] === $turn['role']) {
                $merged[$lastIndex]['parts'][0]['text'] .= "\n\n".$turn['parts'][0]['text'];

                continue;
            }

            $merged[] = $turn;
        }

        // Batasi giliran agar payload tidak membengkak.
        if (count($merged) > self::MAKSIMAL_GILIRAN) {
            $merged = array_slice($merged, -self::MAKSIMAL_GILIRAN);

            // Potongan bisa jadi diawali model; buang lagi.
            while ($merged !== [] && $merged[0]['role'] === 'model') {
                array_shift($merged);
            }
        }

        $lastIndex = array_key_last($merged);

        // Pertanyaan terkini masuk ke giliran user terakhir supaya role tetap
        // bergantian; kalau tidak ada giliran user, tambahkan yang baru.
        if ($lastIndex !== null && $merged[$lastIndex]['role'] === 'user') {
            $merged[$lastIndex]['parts'][0]['text'] .= "\n\n".$latestQuestion;
        } else {
            $merged[] = [
                'role' => 'user',
                'parts' => [['text' => $latestQuestion]],
            ];
        }

        return $merged;
    }

    /**
     * Jawaban cadangan saat Gemini tidak dapat dihubungi.
     *
     * Prioritas: data resmi di database. Hanya bila tidak ada data relevan
     * (atau database juga bermasalah) memakai pesan ramah dari
     * GeminiResult, tanpa detail teknis provider.
     *
     * @param  array<string, mixed>  $dokumen
     */
    private function jawabanCadangan(string $question, array $dokumen, GeminiResult $result): string
    {
        $dariDatabase = $this->knowledge->buildFallbackAnswer($question, $dokumen);

        return $dariDatabase !== '' ? $dariDatabase : $result->userMessage();
    }

    /**
     * Gabungkan pertanyaan pengguna dengan konteks resmi dari database.
     *
     * Delimiter "===" dibuang dari pertanyaan pengguna. Tanpa ini visitor bisa
     * menyisipkan blok "=== DATA RESMI SEKOLAH ===" palsu berisi biaya atau
     * jadwal palsu yang nanti diperlakukan AI sebagai data resmi.
     */
    private function buildPrompt(string $question, string $context): string
    {
        $question = str_replace('=', '', $question);

        return $context."\n\n=== PERTANYAAN PENGGUNA ===\n".$question;
    }

    /**
     * Bersihkan riwayat percakapan kiriman frontend.
     *
     * Hanya role user|model yang diterima (sudah dijamin validator),
     * jumlah pesan dibatasi maksimal 10, dan tiap teks dibatasi 800
     * karakter. System instruction tidak pernah bisa dikirim dari sini.
     *
     * @param  array<int, mixed>  $history
     * @return array<int, array{role: string, parts: array<int, array{text: string}>}>
     */
    private function sanitizeHistory(array $history): array
    {
        $sanitized = [];

        foreach (array_slice($history, -self::MAKSIMAL_GILIRAN) as $message) {
            if (! is_array($message)) {
                continue;
            }

            $role = $message['role'] ?? null;
            if (! in_array($role, ['user', 'model'], true)) {
                continue;
            }

            $texts = [];
            foreach ((array) ($message['parts'] ?? []) as $part) {
                $text = is_array($part) ? ($part['text'] ?? null) : null;
                if (is_string($text) && trim($text) !== '') {
                    $texts[] = mb_substr(trim($text), 0, 800);
                }
            }

            if ($texts !== []) {
                $sanitized[] = [
                    'role' => $role,
                    'parts' => [['text' => implode("\n", $texts)]],
                ];
            }
        }

        return $sanitized;
    }
}
