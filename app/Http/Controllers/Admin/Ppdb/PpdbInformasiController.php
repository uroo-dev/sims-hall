<?php

namespace App\Http\Controllers\Admin\Ppdb;

use App\Http\Controllers\Controller;
use App\Models\Ppdb_informasi;
use App\Models\Ppdb_tanggal_penting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PpdbInformasiController extends Controller
{
    /**
     * Tampilkan halaman informasi PPDB.
     */
    public function index(): View
    {
        return view('Admin.ppdb.informasi', [
            'informasi' => Ppdb_informasi::first(),
            'agendas' => Ppdb_tanggal_penting::orderBy('tanggal_mulai')->get(),
        ]);
    }

    /**
     * Simpan atau perbarui data informasi PPDB.
     */
    public function informasiUpdate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:100'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ], [
            'judul.required' => 'Judul wajib diisi.',
            'judul.max' => 'Judul maksimal 100 karakter.',
            'keterangan.max' => 'Keterangan maksimal 255 karakter.',
        ]);

        $informasi = Ppdb_informasi::first();

        $informasi ??= new Ppdb_informasi;
        $informasi->fill($validated)->save();

        return redirect()
            ->back()
            ->with('success', 'Data informasi PPDB berhasil disimpan.');
    }

    /**
     * Simpan agenda tanggal penting baru.
     */
    public function tanggalPentingPost(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_agenda' => ['required', 'string', 'max:100'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'keterangan' => ['required', 'string', 'max:150'],
        ], [
            'nama_agenda.required' => 'Nama agenda wajib diisi.',
            'nama_agenda.max' => 'Nama agenda maksimal 100 karakter.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_mulai.date' => 'Tanggal mulai tidak valid.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.date' => 'Tanggal selesai tidak valid.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama dengan atau setelah tanggal mulai.',
            'keterangan.required' => 'Keterangan wajib diisi.',
            'keterangan.max' => 'Keterangan maksimal 150 karakter.',
        ]);

        Ppdb_tanggal_penting::create($validated);

        return redirect()
            ->back()
            ->with('success', 'Agenda berhasil ditambahkan.');
    }

    /**
     * Perbarui agenda tanggal penting.
     */
    public function tanggalPentingUpdate(Request $request, Ppdb_tanggal_penting $agenda): RedirectResponse
    {
        $validated = $request->validate([
            'nama_agenda' => ['required', 'string', 'max:100'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'keterangan' => ['required', 'string', 'max:150'],
        ], [
            'nama_agenda.required' => 'Nama agenda wajib diisi.',
            'nama_agenda.max' => 'Nama agenda maksimal 100 karakter.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_mulai.date' => 'Tanggal mulai tidak valid.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.date' => 'Tanggal selesai tidak valid.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama dengan atau setelah tanggal mulai.',
            'keterangan.required' => 'Keterangan wajib diisi.',
            'keterangan.max' => 'Keterangan maksimal 150 karakter.',
        ]);

        $agenda->update($validated);

        return redirect()
            ->back()
            ->with('success', 'Agenda berhasil diperbarui.');
    }

    /**
     * Hapus agenda tanggal penting.
     */
    public function tanggalPentingDelete(Ppdb_tanggal_penting $agenda): RedirectResponse
    {
        $agenda->delete();

        return redirect()
            ->back()
            ->with('success', 'Agenda berhasil dihapus.');
    }
}
