<?php

namespace App\Http\Controllers\Admin\Ppdb;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use App\Models\Ppdb_informasi;
use App\Models\Ppdb_jalur;
use App\Models\Ppdb_jurusan;
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
        $jurusans = Ppdb_jurusan::with('jurusan')->orderBy('id')->get();
        $jalurs = Ppdb_jalur::orderBy('id')->get();
        $daftarJurusan = Jurusan::orderBy('nama')->get();

        return view('Admin.ppdb.informasi', [
            'informasi' => Ppdb_informasi::first(),
            'agendas' => Ppdb_tanggal_penting::orderBy('tanggal_mulai')->get(),
            'persyaratan' => Ppdb_persyaratan::select(['id', 'syarat'])->get(),
            'jurusans' => $jurusans,
            'jalurs' => $jalurs,
            'totalDayaTampung' => $jurusans->sum('daya_tampung'),
            'totalPercentase' => $jalurs->sum('percentase'),
            'daftarJurusan' => $daftarJurusan,
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

    /**
     * Upload atau perbarui file hasil seleksi PPDB.
     */
    public function hasilSeleksiFileUpload(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'path_file_hasil' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
        ], [
            'path_file_hasil.required' => 'File hasil seleksi wajib diupload.',
            'path_file_hasil.file' => 'File hasil seleksi tidak valid.',
            'path_file_hasil.mimes' => 'Format file harus pdf, doc, atau docx.',
            'path_file_hasil.max' => 'Ukuran file maksimal 10 MB.',
        ]);

        $informasi = Ppdb_informasi::first();

        if ($informasi && $informasi->path_file_hasil) {
            if (Storage::disk('public')->exists($informasi->path_file_hasil)) {
                Storage::disk('public')->delete($informasi->path_file_hasil);
            } elseif (Storage::exists($informasi->path_file_hasil)) {
                Storage::delete($informasi->path_file_hasil);
            }
        }

        if (! $informasi) {
            $informasi = new Ppdb_informasi;
        }

        $file = $request->file('path_file_hasil');
        $path = $file->store('ppdb/hasil-seleksi', 'public');

        $informasi->path_file_hasil = $path;
        if (! $informasi->exists) {
            $informasi->judul = $informasi->judul ?? '';
            $informasi->save();
        } else {
            $informasi->save();
        }

        return redirect()
            ->back()
            ->withFragment('section-hasil-seleksi')
            ->with('success', 'File hasil seleksi berhasil diupload.');
    }

    /**
     * Hapus file hasil seleksi PPDB.
     */
    public function hasilSeleksiFileDelete(): RedirectResponse
    {
        $informasi = Ppdb_informasi::first();

        if ($informasi && $informasi->path_file_hasil) {
            if (Storage::disk('public')->exists($informasi->path_file_hasil)) {
                Storage::disk('public')->delete($informasi->path_file_hasil);
            } elseif (Storage::exists($informasi->path_file_hasil)) {
                Storage::delete($informasi->path_file_hasil);
            }
            $informasi->path_file_hasil = null;
            $informasi->save();
        }

        return redirect()
            ->back()
            ->withFragment('section-hasil-seleksi')
            ->with('success', 'File hasil seleksi berhasil dihapus.');
    }

    /**
     * CRUD Jurusan.
     */
    public function jurusanStore(Request $request): RedirectResponse
    {
        if (! $request->filled('jurusan_id') && $request->filled('nama_jurusan')) {
            $found = Jurusan::where('nama', $request->input('nama_jurusan'))->first();
            if ($found) {
                $request->merge(['jurusan_id' => $found->jurusanID]);
            }
        }

        $validated = $request->validate([
            'jurusan_id' => ['required', 'exists:jurusan,jurusanID'],
            'daya_tampung' => ['required', 'integer', 'min:0'],
        ], [
            'jurusan_id.required' => 'Jurusan wajib dipilih.',
            'jurusan_id.exists' => 'Jurusan yang dipilih tidak valid.',
            'daya_tampung.required' => 'Daya tampung wajib diisi.',
            'daya_tampung.integer' => 'Daya tampung harus berupa angka.',
            'daya_tampung.min' => 'Daya tampung minimal 0.',
        ]);

        Ppdb_jurusan::create($validated);

        return redirect()
            ->back()
            ->withFragment('section-jurusan')
            ->with('success', 'Jurusan berhasil ditambahkan.');
    }

    public function jurusanUpdate(Request $request, Ppdb_jurusan $jurusan): RedirectResponse
    {
        if (! $request->filled('jurusan_id') && $request->filled('nama_jurusan')) {
            $found = Jurusan::where('nama', $request->input('nama_jurusan'))->first();
            if ($found) {
                $request->merge(['jurusan_id' => $found->jurusanID]);
            }
        }

        $validated = $request->validate([
            'jurusan_id' => ['required', 'exists:jurusan,jurusanID'],
            'daya_tampung' => ['required', 'integer', 'min:0'],
        ], [
            'jurusan_id.required' => 'Jurusan wajib dipilih.',
            'jurusan_id.exists' => 'Jurusan yang dipilih tidak valid.',
            'daya_tampung.required' => 'Daya tampung wajib diisi.',
            'daya_tampung.integer' => 'Daya tampung harus berupa angka.',
            'daya_tampung.min' => 'Daya tampung minimal 0.',
        ]);

        $jurusan->update($validated);

        return redirect()
            ->back()
            ->withFragment('section-jurusan')
            ->with('success', 'Jurusan berhasil diperbarui.');
    }

    public function jurusanDestroy(Ppdb_jurusan $jurusan): RedirectResponse
    {
        if ($jurusan->img) {
            if (Storage::disk('public')->exists($jurusan->img)) {
                Storage::disk('public')->delete($jurusan->img);
            } elseif (Storage::exists($jurusan->img)) {
                Storage::delete($jurusan->img);
            }
        }
        $jurusan->delete();

        return redirect()
            ->back()
            ->withFragment('section-jurusan')
            ->with('success', 'Jurusan berhasil dihapus.');
    }

    /**
     * Update gambar jurusan.
     */
    public function jurusanImageUpdate(Request $request, Ppdb_jurusan $jurusan): RedirectResponse
    {
        $validated = $request->validate([
            'img' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'img.required' => 'Gambar wajib dipilih.',
            'img.image' => 'File harus berupa gambar.',
            'img.mimes' => 'Format gambar harus JPG, JPEG, PNG, atau WEBP.',
            'img.max' => 'Ukuran gambar maksimal 2 MB.',
        ]);

        if ($jurusan->img) {
            if (Storage::disk('public')->exists($jurusan->img)) {
                Storage::disk('public')->delete($jurusan->img);
            } elseif (Storage::exists($jurusan->img)) {
                Storage::delete($jurusan->img);
            }
        }

        $path = $request->file('img')->store('ppdb/jurusan', 'public');
        $jurusan->img = $path;
        $jurusan->save();

        return redirect()
            ->back()
            ->withFragment('section-gambar-jurusan')
            ->with('success', 'Gambar jurusan berhasil diperbarui.');
    }

    /**
     * Hapus gambar jurusan.
     */
    public function jurusanImageDestroy(Ppdb_jurusan $jurusan): RedirectResponse
    {
        if ($jurusan->img) {
            if (Storage::disk('public')->exists($jurusan->img)) {
                Storage::disk('public')->delete($jurusan->img);
            } elseif (Storage::exists($jurusan->img)) {
                Storage::delete($jurusan->img);
            }
            $jurusan->img = null;
            $jurusan->save();
        }

        return redirect()
            ->back()
            ->withFragment('section-gambar-jurusan')
            ->with('success', 'Gambar jurusan berhasil dihapus.');
    }

    /**
     * CRUD Jalur Seleksi.
     */
    public function jalurStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_jalur' => ['required', 'string', 'max:100'],
            'percentase' => ['required', 'numeric', 'min:0', 'max:100'],
        ], [
            'nama_jalur.required' => 'Nama jalur wajib diisi.',
            'nama_jalur.max' => 'Nama jalur maksimal 100 karakter.',
            'percentase.required' => 'Persentase wajib diisi.',
            'percentase.numeric' => 'Persentase harus berupa angka.',
            'percentase.min' => 'Persentase minimal 0.',
            'percentase.max' => 'Persentase maksimal 100.',
        ]);

        $totalSekarang = Ppdb_jalur::sum('percentase');
        $totalBaru = $totalSekarang + (float) $validated['percentase'];

        if ($totalBaru > 100) {
            return redirect()
                ->back()
                ->withFragment('section-jalur')
                ->withInput()
                ->withErrors([
                    'percentase' => 'Total persentase jalur seleksi tidak boleh melebihi 100%.',
                ]);
        }

        Ppdb_jalur::create($validated);

        return redirect()
            ->back()
            ->withFragment('section-jalur')
            ->with('success', 'Jalur seleksi berhasil ditambahkan.');
    }

    public function jalurUpdate(Request $request, Ppdb_jalur $jalur): RedirectResponse
    {
        $validated = $request->validate([
            'nama_jalur' => ['required', 'string', 'max:100'],
            'percentase' => ['required', 'numeric', 'min:0', 'max:100'],
        ], [
            'nama_jalur.required' => 'Nama jalur wajib diisi.',
            'nama_jalur.max' => 'Nama jalur maksimal 100 karakter.',
            'percentase.required' => 'Persentase wajib diisi.',
            'percentase.numeric' => 'Persentase harus berupa angka.',
            'percentase.min' => 'Persentase minimal 0.',
            'percentase.max' => 'Persentase maksimal 100.',
        ]);

        $totalLain = Ppdb_jalur::where('id', '!=', $jalur->id)->sum('percentase');
        $totalBaru = $totalLain + (float) $validated['percentase'];

        if ($totalBaru > 100) {
            return redirect()
                ->back()
                ->withFragment('section-jalur')
                ->withInput()
                ->withErrors([
                    'percentase' => 'Total persentase jalur seleksi tidak boleh melebihi 100%.',
                ]);
        }

        $jalur->update($validated);

        return redirect()
            ->back()
            ->withFragment('section-jalur')
            ->with('success', 'Jalur seleksi berhasil diperbarui.');
    }

    public function jalurDestroy(Ppdb_jalur $jalur): RedirectResponse
    {
        $jalur->delete();

        return redirect()
            ->back()
            ->withFragment('section-jalur')
            ->with('success', 'Jalur seleksi berhasil dihapus.');
    }
}
