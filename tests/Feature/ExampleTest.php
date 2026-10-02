<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    // Route "/" sekarang dilayani PublicController yang membaca tabel
    // `sekolahs`, `dudis`, `prestasis`, dan `produk_unggulans` untuk mengisi
    // landing page. Tanpa RefreshDatabase, tabel-tabel itu tidak ada dan
    // request berakhir 500.
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
