<?php

namespace App\Http\Controllers\Admin\Ppdb;

use App\Http\Controllers\Controller;
use App\Models\Ppdb_informasi;
use App\Models\Ppdb_persyaratan;
use App\Models\Ppdb_tanggal_penting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            'persyaratan' => Ppdb_persyaratan::select(['id', 'syarat'])->get(),
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

    public function persyaratanPost(Request $request)
    {
        $request->validate([
            'syarat' => 'required|string|max:255',
        ]);

        Ppdb_persyaratan::create([
            'syarat' => $request->syarat,
        ]);

        return redirect()
            ->back()
            ->withFragment('section-persyaratan')
            ->with('success', 'Berhasil menambah persyaratan.');
    }

    public function persyaratanDelete($id)
    {
        $syarat = Ppdb_persyaratan::where('id', $id)->first();

        if (! $syarat) {
            return abort('204', 'syarat tidak ditemukan');
        }

        $syarat->delete();

        return redirect()
            ->back()
            ->withFragment('section-persyaratan')
            ->with('success', 'Berhasil menghapus persyaratan.');
    }

    /**
     * Upload atau perbarui file persyaratan PPDB.
     */
    public function persyaratanFileUpload(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'path_file' => ['required', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:10240'],
        ], [
            'path_file.required' => 'File persyaratan wajib diupload.',
            'path_file.file' => 'File persyaratan tidak valid.',
            'path_file.mimes' => 'Format file harus pdf, doc, docx, jpg, jpeg, atau png.',
            'path_file.max' => 'Ukuran file maksimal 10 MB.',
        ]);

        $informasi = Ppdb_informasi::first();

        if ($informasi && $informasi->path_file) {
            if (Storage::disk('public')->exists($informasi->path_file)) {
                Storage::disk('public')->delete($informasi->path_file);
            } elseif (Storage::exists($informasi->path_file)) {
                Storage::delete($informasi->path_file);
            }
        }

        if (! $informasi) {
            $informasi = new Ppdb_informasi;
        }

        $file = $request->file('path_file');
        $path = $file->store('ppdb/persyaratan', 'public');

        $informasi->path_file = $path;
        if (! $informasi->exists) {
            $informasi->judul = $informasi->judul ?? '';
            $informasi->save();
        } else {
            $informasi->save();
        }

        return redirect()
            ->back()
            ->withFragment('section-persyaratan')
            ->with('success', 'File persyaratan berhasil diupload.');
    }

    /**
     * Hapus file persyaratan PPDB.
     */
    public function persyaratanFileDelete(): RedirectResponse
    {
        $informasi = Ppdb_informasi::first();

        if ($informasi && $informasi->path_file) {
            if (Storage::disk('public')->exists($informasi->path_file)) {
                Storage::disk('public')->delete($informasi->path_file);
            } elseif (Storage::exists($informasi->path_file)) {
                Storage::delete($informasi->path_file);
            }
            $informasi->path_file = null;
            $informasi->save();
        }

        return redirect()
            ->back()
            ->withFragment('section-persyaratan')
            ->with('success', 'File persyaratan berhasil dihapus.');
    }
}
