<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dudi;
use App\Models\Lowongan;
use App\Models\PenempatanPkl;
use App\Models\Siswa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * API publik untuk integrasi Modul 5 (Landing Page & Chatbot AI).
 *
 * Seluruh endpoint hanya mengekspos data yang aman untuk publik:
 * - DUDI yang sudah di-ACC BKK (tampil_di_landing = true)
 * - Siswa dengan status penempatan FIX di DUDI tersebut
 * - Lowongan kerja yang masih aktif (is_active + deadline >= today)
 *
 * Endpoint ini sengaja tidak memakai auth agar Landing Page & Chatbot
 * bisa mengambilnya langsung. Bila perlu pembatasan kuota, tambahkan
 * throttle middleware di routes/api.php.
 */
class PklPublicApiController extends Controller
{
    /**
     * GET /api/pkl/dudi
     *
     * Daftar DUDI yang tayang di Landing Page, lengkap dengan jumlah
     * siswa FIX di masing-masing DUDI.
     */
    public function dudi(): JsonResponse
    {
        $dudis = Dudi::forLandingPage()
            ->with('siswaFix')
            ->withCount('penempatanFix')
            ->orderBy('nama_dudi')
            ->get()
            ->map(fn (Dudi $d) => [
                'id' => $d->id,
                'nama_dudi' => $d->nama_dudi,
                'kota' => $d->kota,
                'bidang_usaha' => $d->bidang_usaha,
                'alamat' => $d->alamat,
                'is_mitra_resmi' => $d->is_mitra_resmi,
                'jumlah_siswa' => $d->penempatan_fix_count,
                'siswa' => $d->siswaFix->map(fn (Siswa $s) => [
                    'id' => $s->id,
                    'nama' => $s->nama,
                    'kelas' => $s->kelas,
                    'jurusan' => $s->jurusan,
                ]),
            ]);

        return response()->json([
            'success' => true,
            'data' => $dudis,
            'meta' => ['total' => $dudis->count()],
        ]);
    }

    /**
     * GET /api/pkl/lowongan
     *
     * Daftar lowongan aktif (Career Center). Mendukung filterJurusan & tipe.
     */
    public function lowongan(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'jurusan' => ['nullable', 'string', 'max:191'],
            'tipe' => ['nullable', 'in:'.Lowongan::TIPE_PEKERJAAN.','.Lowongan::TIPE_MAGANG],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
        ]);

        $query = Lowongan::active()->with('dudi:id,nama_dudi,kota');

        if (! empty($validated['jurusan'])) {
            $query->untukJurusan($validated['jurusan']);
        }

        if (! empty($validated['tipe'])) {
            $query->tipe($validated['tipe']);
        }

        $perPage = $validated['per_page'] ?? 20;
        $lowongans = $query->orderBy('deadline')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $lowongans->items(),
            'meta' => [
                'total' => $lowongans->total(),
                'page' => $lowongans->currentPage(),
                'limit' => $lowongans->perPage(),
            ],
        ]);
    }

    /**
     * GET /api/pkl/rekap
     *
     * Ringkasan angka PKL untuk chatbot AI ("Berapa siswa sudah PKL?").
     */
    public function rekap(): JsonResponse
    {
        $total = fn (string $status) => PenempatanPkl::status($status)->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_siswa' => Siswa::count(),
                'siswa_fix' => $total(PenempatanPkl::STATUS_FIX),
                'siswa_menunggu' => $total(PenempatanPkl::STATUS_PENGAJUAN),
                'siswa_ditolak' => $total(PenempatanPkl::STATUS_DITOLAK),
                'siswa_belum_pkl' => Siswa::belumPkl()->count(),
                'total_dudi' => Dudi::count(),
                'dudi_tampil_landing' => Dudi::forLandingPage()->count(),
                'lowongan_aktif' => Lowongan::active()->count(),
            ],
        ]);
    }

    /**
     * GET /api/pkl/siswa/{nis}
     *
     * Status PKL satu siswa (berguna untuk chatbot: "Siswa X sudah PKL?").
     */
    public function siswa(string $nis): JsonResponse
    {
        $siswa = Siswa::with(['penempatanPkls' => fn ($q) => $q->with(['dudi', 'guru', 'suratPengajuan'])])
            ->where('nis', $nis)
            ->first();

        if (! $siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Siswa dengan NIS tersebut tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'nis' => $siswa->nis,
                'nama' => $siswa->nama,
                'kelas' => $siswa->kelas,
                'jurusan' => $siswa->jurusan,
                'sudah_pkl' => $siswa->penempatanFix()->exists(),
                'penempatan' => $siswa->penempatanPkls->map(fn ($p) => [
                    'dudi' => $p->dudi?->nama_dudi,
                    'kota' => $p->dudi?->kota,
                    'guru_pembimbing' => $p->guru?->nama,
                    'status' => $p->status_penempatan,
                    'status_label' => $p->status_label,
                    'tgl_mulai' => optional($p->suratPengajuan?->tgl_mulai_pkl)->toDateString(),
                    'tgl_selesai' => optional($p->suratPengajuan?->tgl_selesai_pkl)->toDateString(),
                ]),
            ],
        ]);
    }
}
