<?php

namespace App\Services;

/**
 * Hasil satu percobaan terhadap satu model, lengkap dengan jumlah HTTP call
 * yang terpakai. Jumlah ini dipakai untuk menghitung budget panggilan global
 * supaya rantai model + retry tidak pernah melebihi batas keras.
 */
final readonly class GeminiAttempt
{
    public function __construct(
        public GeminiResult $result,
        public int $attempts,
    ) {}
}
