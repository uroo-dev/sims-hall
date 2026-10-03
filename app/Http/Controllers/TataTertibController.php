<?php

namespace App\Http\Controllers;

use App\Models\TataTertib;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TataTertibController extends Controller
{
    /**
     * Tampilkan daftar tata tertib & form tambah
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));

        $tataTertibs = TataTertib::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                        ->orWhere('deskripsi', 'like', "%{$search}%");
                });
            })
            ->latest('tata_tertibID')
            ->paginate(10)
            ->withQueryString();

        return view('Admin.kesiswaan.tata_tertib.index', [
            'tataTertibs' => $tataTertibs,
            'search' => $search,
        ]);
    }

    /**
     * Simpan data tata tertib baru
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file_pdf' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $data = [
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'] ?? null,
        ];

        if ($request->hasFile('file_pdf')) {
            $data['file_pdf'] = $request->file('file_pdf')->store('tata-tertib', 'public');
        }

        TataTertib::create($data);

        return redirect()
            ->route('admin.kesiswaan.tata-tertib.index')
            ->with('success', 'Tata Tertib berhasil ditambahkan.');
    }

    /**
     * Perbarui data tata tertib
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $tataTertib = TataTertib::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file_pdf' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $data = [
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'] ?? null,
        ];

        if ($request->hasFile('file_pdf')) {
            if ($tataTertib->file_pdf && Storage::disk('public')->exists($tataTertib->file_pdf)) {
                Storage::disk('public')->delete($tataTertib->file_pdf);
            }
            $data['file_pdf'] = $request->file('file_pdf')->store('tata-tertib', 'public');
        }

        $tataTertib->update($data);

        return redirect()
            ->route('admin.kesiswaan.tata-tertib.index')
            ->with('success', 'Tata Tertib berhasil diperbarui.');
    }

    /**
     * Hapus data tata tertib
     */
    public function destroy($id): RedirectResponse
    {
        $tataTertib = TataTertib::findOrFail($id);

        if ($tataTertib->file_pdf && Storage::disk('public')->exists($tataTertib->file_pdf)) {
            Storage::disk('public')->delete($tataTertib->file_pdf);
        }

        $tataTertib->delete();

        return redirect()
            ->route('admin.kesiswaan.tata-tertib.index')
            ->with('success', 'Tata Tertib berhasil dihapus.');
    }

    /**
     * Download / Export Tata Tertib menjadi dokumen PDF resmi
     */
    public function exportPdf(Request $request, $id)
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

    /**
     * Download / Export Seluruh Tata Tertib (Buku Saku) menjadi PDF
     */
    public function exportAllPdf(Request $request)
    {
        $tataTertibs = TataTertib::query()->orderBy('tata_tertibID')->get();

        $pdf = Pdf::loadView('Admin.kesiswaan.tata_tertib.pdf_buku_saku', [
            'tataTertibs' => $tataTertibs,
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
}
