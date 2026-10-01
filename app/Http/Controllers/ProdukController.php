<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProdukRequest;
use App\Models\Jurusan;
use App\Models\Produk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProdukController extends Controller
{
    /**
     * Tampilkan daftar produk unggulan.
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));

        $produk = Produk::query()
            ->with('jurusan')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('kode_produk', 'like', "%{$search}%")
                        ->orWhere('nama', 'like', "%{$search}%")
                        ->orWhere('deskripsi', 'like', "%{$search}%")
                        ->orWhereHas('jurusan', fn ($jurusan) => $jurusan->where('nama', 'like', "%{$search}%"));
                });
            })
            ->orderByDesc('produkID')
            ->paginate(10)
            ->withQueryString();

        return view('Admin.produk_unggulan.data', [
            'produk' => $produk,
            'jurusan' => Jurusan::query()->orderBy('nama')->get(),
            'search' => $search,
        ]);
    }

    /**
     * Simpan produk unggulan baru.
     */
    public function store(ProdukRequest $request): RedirectResponse
    {
        $data = $request->safe()->only(['nama', 'deskripsi', 'jurusanID']);

        $data['kode_produk'] = $this->kodeBerikutnya();

        if ($request->hasFile('dokumentasi')) {
            $data['dokumentasi'] = $request->file('dokumentasi')->store('produk', 'public');
        }

        Produk::query()->create($data);

        return redirect()
            ->route('produk.index')
            ->with('success', 'Produk unggulan berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit produk unggulan.
     */
    public function edit(Produk $produk): View
    {
        return view('Admin.produk_unggulan.edit', [
            'produk' => $produk,
            'jurusan' => Jurusan::query()->orderBy('nama')->get(),
        ]);
    }

    /**
     * Perbarui produk unggulan.
     */
    public function update(ProdukRequest $request, Produk $produk): RedirectResponse
    {
        $data = $request->safe()->only(['nama', 'deskripsi', 'jurusanID']);

        if ($request->boolean('hapus_dokumentasi')) {
            if ($produk->dokumentasi) {
                Storage::disk('public')->delete($produk->dokumentasi);
            }

            unset($data['dokumentasi']);
            $produk->dokumentasi = null;
        } elseif ($request->hasFile('dokumentasi')) {
            if ($produk->dokumentasi) {
                Storage::disk('public')->delete($produk->dokumentasi);
            }

            $data['dokumentasi'] = $request->file('dokumentasi')->store('produk', 'public');
        }

        $produk->update($data);

        return redirect()
            ->route('produk.index')
            ->with('success', 'Produk unggulan berhasil diperbarui.');
    }

    /**
     * Hapus produk unggulan.
     */
    public function destroy(Produk $produk): RedirectResponse
    {
        if ($produk->dokumentasi) {
            Storage::disk('public')->delete($produk->dokumentasi);
        }

        $produk->delete();

        return redirect()
            ->route('produk.index')
            ->with('success', 'Produk unggulan berhasil dihapus.');
    }

    /**
     * Kode produk berikutnya, contoh: PU-005.
     */
    protected function kodeBerikutnya(): string
    {
        $urutan = ((int) Produk::query()->max('produkID')) + 1;

        return 'PU-'.str_pad((string) $urutan, 3, '0', STR_PAD_LEFT);
    }
}
