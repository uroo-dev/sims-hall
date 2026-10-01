<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Google Gemini API (chatbot "Nanya AI")
    |--------------------------------------------------------------------------
    |
    | API key HANYA disimpan di file .env (GEMINI_API_KEY) dan dibaca dari
    | sini. Jangan pernah menaruh key di JavaScript, Blade, HTML, atau repo.
    |
    */

    'gemini' => [
        'key' => env('GEMINI_API_KEY'),

        /*
        * Rantai model, percobaan pertama lebih dulu. Dipisah dengan koma.
        * Dipakai satu per satu hanya bila model sebelumnya 404 (tidak tersedia
        * untuk akun Anda) atau 503 (overload). Model 400/403 TIDAK dipindah,
        * karena itu berarti bug konfigurasi atau key, bukan masalah model.
        */
        'models' => array_values(array_filter(array_map(
            'trim',
            explode(',', (string) env('GEMINI_MODEL', 'gemini-3.6-flash,gemini-3.5-flash-lite,gemini-3.5-flash,gemini-flash-latest'))
        ))),

        'endpoint' => env('GEMINI_ENDPOINT', 'https://generativelanguage.googleapis.com/v1beta'),
        'temperature' => (float) env('GEMINI_TEMPERATURE', 0.4),

        /*
        * WAJIB >= 2048. Model Gemini 3.x memiliki thinking ON secara default
        * (level "medium") dan token thinking dihitung dari maxOutputTokens.
        * Nilai kecil (mis. 600) akan habis terpakai reasoning sehingga
        * respons tiba tanpa teks jawaban sama sekali.
        */
        'max_output_tokens' => (int) env('GEMINI_MAX_OUTPUT_TOKENS', 2048),

        /*
        * Level thinking: low | medium | high.
        * "low" paling cocok untuk chatbot Q&A agar respons cepat & murah.
        * Jangan pakai "minimal" - nilainya tidak didukung Gemini 3.8 Flash.
        */
        'thinking_level' => env('GEMINI_THINKING_LEVEL', 'low'),

        /*
        * Retry exponential backoff + jitter hanya untuk error transien
        * (408/429/5xx), sesuai anjuran resmi Google.
        */
        'retry_times' => min(5, max(1, (int) env('GEMINI_RETRY_TIMES', 3))),
        'retry_sleep_ms' => min(2000, max(0, (int) env('GEMINI_RETRY_SLEEP_MS', 500))),
        'timeout' => min(60, max(1, (int) env('GEMINI_TIMEOUT_SECONDS', 20))),
        'connect_timeout' => min(15, max(1, (int) env('GEMINI_CONNECT_TIMEOUT_SECONDS', 5))),

        /*
        * Budget wall-clock total untuk satu permintaan pengguna, independen
        * dari jumlah model dan retry. Tanpa ini satu PHP-FPM worker bisa
        * terblokir puluhan detik saat Gemini sedang padat.
        */
        'budget_seconds' => min(60, max(3, (int) env('GEMINI_BUDGET_SECONDS', 20))),

        // Nama sekolah fallback bila tabel school_settings kosong/gagal.
        'school_name' => env('GEMINI_SCHOOL_NAME', 'SMK Negeri 2 Karanganyar'),
    ],

];
