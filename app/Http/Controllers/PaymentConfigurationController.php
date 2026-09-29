<?php

namespace App\Http\Controllers;

use App\Models\PaymentConfiguration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PaymentConfigurationController extends Controller
{
    /**
     * Tampilkan halaman formulir konfigurasi pembayaran sekolah.
     */
    public function index(): View
    {
        $config = PaymentConfiguration::current();

        return view('Admin.paymentConfiguration.index', compact('config'));
    }

    /**
     * Simpan perubahan konfigurasi pembayaran sekolah.
     */
    public function update(Request $request): RedirectResponse
    {
        $config = PaymentConfiguration::current();

        $validated = $request->validate([
            // Rekening Utama
            'bank_utama' => ['required', 'string', 'max:100'],
            'norek_utama' => ['required', 'string', 'max:100'],
            'atas_nama_utama' => ['required', 'string', 'max:150'],

            // Rekening Alternatif 1
            'bank_alternatif_1' => ['nullable', 'string', 'max:100'],
            'norek_alternatif_1' => ['nullable', 'string', 'max:100'],
            'atas_nama_alternatif_1' => ['nullable', 'string', 'max:150'],

            // Rekening Alternatif 2
            'bank_alternatif_2' => ['nullable', 'string', 'max:100'],
            'norek_alternatif_2' => ['nullable', 'string', 'max:100'],
            'atas_nama_alternatif_2' => ['nullable', 'string', 'max:150'],

            // QRIS
            'qris_merchant' => ['nullable', 'string', 'max:150'],
            'qris_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'delete_qris' => ['nullable', 'boolean'],

            // Jatuh Tempo Pembayaran (jam)
            'jatuh_tempo_dp_jam' => ['required', 'integer', 'min:1', 'max:720'],
            'jatuh_tempo_pelunasan_jam' => ['required', 'integer', 'min:1', 'max:720'],

            // Keterangan & Status
            'instruksi_pembayaran' => ['nullable', 'string', 'max:2000'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'bank_utama.required' => 'Nama bank rekening utama wajib diisi.',
            'norek_utama.required' => 'Nomor rekening utama wajib diisi.',
            'atas_nama_utama.required' => 'Atas nama rekening utama wajib diisi.',
            'qris_image.image' => 'File QRIS harus berupa gambar.',
            'qris_image.mimes' => 'Format file QRIS yang diizinkan adalah jpeg, png, jpg, webp.',
            'qris_image.max' => 'Ukuran gambar QRIS maksimal 2MB.',
            'jatuh_tempo_dp_jam.required' => 'Batas waktu (jam) transfer deposit wajib diisi.',
            'jatuh_tempo_dp_jam.min' => 'Batas waktu transfer deposit minimal 1 jam.',
            'jatuh_tempo_pelunasan_jam.required' => 'Batas waktu (jam) pelunasan wajib diisi.',
            'jatuh_tempo_pelunasan_jam.min' => 'Batas waktu pelunasan minimal 1 jam.',
        ]);

        $data = [
            'bank_utama' => $validated['bank_utama'],
            'norek_utama' => $validated['norek_utama'],
            'atas_nama_utama' => $validated['atas_nama_utama'],
            'bank_alternatif_1' => $validated['bank_alternatif_1'] ?? null,
            'norek_alternatif_1' => $validated['norek_alternatif_1'] ?? null,
            'atas_nama_alternatif_1' => $validated['atas_nama_alternatif_1'] ?? null,
            'bank_alternatif_2' => $validated['bank_alternatif_2'] ?? null,
            'norek_alternatif_2' => $validated['norek_alternatif_2'] ?? null,
            'atas_nama_alternatif_2' => $validated['atas_nama_alternatif_2'] ?? null,
            'qris_merchant' => $validated['qris_merchant'] ?? null,
            'jatuh_tempo_dp_jam' => (int) $validated['jatuh_tempo_dp_jam'],
            'jatuh_tempo_pelunasan_jam' => (int) $validated['jatuh_tempo_pelunasan_jam'],
            'instruksi_pembayaran' => $validated['instruksi_pembayaran'] ?? null,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : true,
        ];

        // Kelola file QRIS
        if ($request->hasFile('qris_image')) {
            if ($config->qris_image && Storage::disk('public')->exists($config->qris_image)) {
                Storage::disk('public')->delete($config->qris_image);
            }
            $data['qris_image'] = $request->file('qris_image')->store('qris', 'public');
        } elseif ($request->boolean('delete_qris')) {
            if ($config->qris_image && Storage::disk('public')->exists($config->qris_image)) {
                Storage::disk('public')->delete($config->qris_image);
            }
            $data['qris_image'] = null;
        }

        $config->update($data);

        return redirect()->back()->with('success', 'Konfigurasi pembayaran sekolah berhasil disimpan.');
    }
}
