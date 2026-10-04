<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\Ekstrakurikuler;
use App\Models\Kesiswaan;
use App\Models\TataTertib;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PublicKesiswaanController extends Controller
{
    /**
     * Tampilkan landing page Kesiswaan
     */
    public function index(): View
    {
        $kesiswaan = Kesiswaan::current();
        $ekstrakurikulers = Ekstrakurikuler::query()->orderBy('ekstrakurikulerID')->get();
        $tataTertibs = TataTertib::query()->orderBy('tata_tertibID')->get();

        // Data prestasi tidak lagi memakai tabel `prestasis`; sebuah prestasi
        // adalah artikel berstatus published yang masuk kategori "Prestasi".
        $prestasies = Artikel::with('kategori')
            ->prestasi()
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->get();

        return view('Public.kesiswaan', [
            'kesiswaan' => $kesiswaan,
            'ekstrakurikulers' => $ekstrakurikulers,
            'tataTertibs' => $tataTertibs,
            'prestasies' => $prestasies,
        ]);
    }

    /**
     * Unduh Buku Saku Tata Tertib Siswa (PDF) - Mencakup seluruh peraturan tata tertib
     */
    public function downloadBukuSakuPdf(Request $request)
    {
        // Generate PDF Buku Saku lengkap dari seluruh aturan tata tertib di database
        $tataTertibs = TataTertib::query()->orderBy('tata_tertibID')->get();

        $sekolah = \App\Models\Sekolah::first();
        $kesiswaanUser = \App\Models\User::where('role', 'admin_kesiswaan')->first();

        $pdf = Pdf::loadView('Admin.kesiswaan.tata_tertib.pdf_buku_saku', [
            'tataTertibs' => $tataTertibs,
            'namaKepsek' => $sekolah?->nama_kepsek ?? 'Drs. Sugiyarso, M.Pd.',
            'namaWaka' => $kesiswaanUser?->name ?? 'Waka Bidang Kesiswaan',
        ]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->setOption('isHtml5ParserEnabled', true);

        $filename = 'Buku-Saku-Tata-Tertib-SMKN2-Karanganyar.pdf';

        if ($request->query('stream') === '1') {
            return $pdf->stream($filename);
        }

        return $pdf->download($filename);
    }

    /**
     * Unduh Tata Tertib Tertentu (PDF)
     */
    public function downloadTataTertibPdf(Request $request, $id)
    {
        $tataTertib = TataTertib::findOrFail($id);

        if ($request->query('source') === 'uploaded' && $tataTertib->file_pdf && Storage::disk('public')->exists($tataTertib->file_pdf)) {
            return Storage::disk('public')->download($tataTertib->file_pdf, Str::slug($tataTertib->judul).'.pdf');
        }

        $pdf = Pdf::loadView('Admin.kesiswaan.tata_tertib.pdf_single', [
            'tataTertib' => $tataTertib,
        ]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->setOption('isHtml5ParserEnabled', true);

        $filename = 'Tata-Tertib-'.Str::slug($tataTertib->judul).'.pdf';

        if ($request->query('stream') === '1') {
            return $pdf->stream($filename);
        }

        return $pdf->download($filename);
    }
}
