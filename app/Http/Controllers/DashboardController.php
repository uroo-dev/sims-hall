<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\PaketPeminjaman;
use App\Models\PaymentConfiguration;
use App\Models\Peminjaman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard sesuai role user.
     */
    public function index(Request $request): View|RedirectResponse
    {
        if ($request->user()?->role === 'pelanggan') {
            return redirect()->route('customer.dashboard');
        }

        if ($request->user()?->role === 'kepala_sekolah') {
            return redirect()->route('kepala-sekolah.dashboard');
        }

        Peminjaman::syncExpiredDeadlines();

        $isSuperAdmin = in_array($request->user()?->role, ['super_admin', 'super_duper_admin'], true);
        $paymentConfig = $isSuperAdmin ? PaymentConfiguration::current() : null;

        $peminjamanTerverifikasiCount = Peminjaman::whereIn('status', ['approved_1', 'approved_final'])->count();
        $paketCount = PaketPeminjaman::count();
        $facilityCount = Facility::count();

        // Ambil data operasional peminjaman terbaru
        $recentPeminjamans = Peminjaman::with([
            'paketPeminjaman',
            'pembayaran.details',
        ])->latest()->take(5)->get();

        return view('Admin.dashboard', compact(
            'paymentConfig',
            'isSuperAdmin',
            'peminjamanTerverifikasiCount',
            'paketCount',
            'facilityCount',
            'recentPeminjamans'
        ));
    }
}
