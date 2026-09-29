<?php

namespace App\Http\Controllers;

use App\Models\PaketPeminjaman;
use App\Models\Peminjaman;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CustomerPanelController extends Controller
{
    /**
     * Dapatkan user yang sedang aktif atau fallback user peminjam untuk preview.
     */
    private function getCurrentUser(): ?User
    {
        return Auth::user();
    }

    /**
     * Halaman Dashboard Customer Panel:
     * - Row 1: Card peminjaman terverifikasi milik user, peminjaman tertolak, dan total paket
     * - Row 2: Kalender ketersediaan aula (kolom tanggal_mulai & tanggal_selesai) & Profil user
     */
    public function dashboard(Request $request): View
    {
        $user = $this->getCurrentUser();

        // 1. Data Statistik Milik User
        $userQuery = Peminjaman::query();
        if ($user) {
            $userQuery->where(function ($q) use ($user) {
                $q->where('email_instansi', $user->email)
                    ->orWhere('nama', $user->name);
            });
        }

        $peminjamanTerverifikasi = (clone $userQuery)
            ->whereIn('status', ['approved_1', 'approved_final'])
            ->count();

        $peminjamanTertolak = (clone $userQuery)
            ->where('status', 'rejected')
            ->count();

        $jumlahPaket = PaketPeminjaman::count();

        // 2. Data Kalender Ketersediaan Aula
        // Default gunakan bulan saat ini atau query param (?month=6&year=2026)
        $year = (int) $request->input('year', now()->year);
        $month = (int) $request->input('month', now()->month);
        $calendarDate = Carbon::createFromDate($year, $month, 1);

        // Ambil semua peminjaman yang tidak ditolak untuk penandaan kalender
        $allPeminjamans = Peminjaman::with('paketPeminjaman')
            ->where('status', '!=', 'rejected')
            ->whereYear('tanggal_mulai', $year)
            ->whereMonth('tanggal_mulai', $month)
            ->get();

        // Peta hari yang terisi (booked) pada bulan ini
        $bookedDays = [];
        foreach ($allPeminjamans as $item) {
            $startDate = Carbon::parse($item->tanggal_mulai);
            $endDate = Carbon::parse($item->tanggal_selesai);

            $current = $startDate->copy()->startOfDay();
            $end = $endDate->copy()->startOfDay();

            while ($current->lte($end)) {
                if ($current->month === $month && $current->year === $year) {
                    $dayNum = $current->day;
                    $bookedDays[$dayNum][] = [
                        'id' => $item->id,
                        'nama' => $item->nama,
                        'paket' => $item->paketPeminjaman?->nama_paket ?? 'Paket Aula',
                        'jam_mulai' => $startDate->format('H:i'),
                        'jam_selesai' => $endDate->format('H:i'),
                        'status' => $item->status,
                    ];
                }
                $current->addDay();
            }
        }

        return view('Admin.customerPanel.dashboard', compact(
            'user',
            'peminjamanTerverifikasi',
            'peminjamanTertolak',
            'jumlahPaket',
            'calendarDate',
            'bookedDays'
        ));
    }

    /**
     * Halaman Paket Peminjaman:
     * - Daftar paket peminjaman dari tabel paket_peminjamans dan relasi fasilitasnya
     * - Card peminjaman dengan layout sesuai desain & tombol untuk melihat detail paket
     */
    public function paket(): View
    {
        $user = $this->getCurrentUser();

        // Ambil seluruh paket peminjaman beserta fasilitasnya
        $pakets = PaketPeminjaman::with(['facilities', 'details'])->get();

        // Cari paket spesifik berdasarkan kategori untuk tampilan terstruktur
        $paketUnggulan = $pakets->firstWhere('kategori', 'unggulan') ?? $pakets->first();
        $paketLainnya = $paketUnggulan ? $pakets->where('id', '!=', $paketUnggulan->id) : collect();

        return view('Admin.customerPanel.paket', compact(
            'user',
            'pakets',
            'paketUnggulan',
            'paketLainnya'
        ));
    }

    /**
     * Halaman Daftar Peminjaman / Cek Peminjaman:
     * - Riwayat peminjaman milik user
     * - Status verifikasi Tahap 1, Tahap 2, dan Status Pembayaran
     * - Card Pembayaran
     */
    public function riwayat(): View
    {
        $user = $this->getCurrentUser();

        $query = Peminjaman::with([
            'paketPeminjaman',
            'persetujuans.approver',
            'pembayaran.details',
        ])->latest();

        if ($user && $user->role !== 'admin' && ! $user->isSuperAdmin()) {
            $query->where(function ($q) use ($user) {
                $q->where('email_instansi', $user->email)
                    ->orWhere('nama', $user->name);
            });
        }

        $peminjamans = $query->get();

        // Cek status akumulasi pembayaran
        $hasUnpaid = false;
        $activePembayarans = [];

        foreach ($peminjamans as $pem) {
            if ($pem->pembayaran) {
                if ($pem->pembayaran->status_pembayaran !== 'lunas' && $pem->pembayaran->status_pembayaran !== 'free') {
                    $hasUnpaid = true;
                    $activePembayarans[] = $pem->pembayaran;
                }
            }
        }

        return view('Admin.customerPanel.riwayat', compact(
            'user',
            'peminjamans',
            'hasUnpaid',
            'activePembayarans'
        ));
    }

    /**
     * Halaman Profil Peminjam / User
     */
    public function profil(): View
    {
        $user = $this->getCurrentUser();

        return view('Admin.customerPanel.profil', compact('user'));
    }
}
