<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\PaketPeminjaman;
use App\Models\PaymentConfiguration;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Dispatch user ke dashboard / panel masing-masing sesuai role.
     */
    public function dispatch(Request $request): RedirectResponse
    {
        return redirect($request->user()->dashboardRoute());
    }

    /**
     * Tampilkan dashboard peminjaman aula (khusus admin aula & super admin, dilindungi middleware).
     */
    public function index(Request $request): View
    {
        Peminjaman::syncExpiredDeadlines();

        $isSuperAdmin = in_array($request->user()?->role, ['super_admin', 'super_duper_admin'], true);
        $paymentConfig = $isSuperAdmin ? PaymentConfiguration::current() : null;

        $peminjamanTerverifikasiCount = Peminjaman::whereIn('status', ['approved_1', 'approved_final'])->count();
        $paketCount = PaketPeminjaman::count();
        $facilityCount = Facility::count();

        // Data Kalender Ketersediaan Aula (Sama seperti panel customer)
        $year = (int) $request->input('year', now()->year);
        $month = (int) $request->input('month', now()->month);
        $calendarDate = Carbon::createFromDate($year, $month, 1);
        $startOfMonth = $calendarDate->copy()->startOfMonth();
        $endOfMonth = $calendarDate->copy()->endOfMonth();

        // Ambil peminjaman yang SUDAH DISETUJUI (approved_1 atau approved_final) untuk menandai tanggal terpakai
        $allPeminjamans = Peminjaman::with('paketPeminjaman')
            ->where(function ($q) {
                $q->whereIn('status', ['approved_1', 'approved_final'])
                    ->orWhereHas('persetujuans', function ($sq) {
                        $sq->where('status', 'approved');
                    });
            })
            ->whereNotIn('status', ['rejected', 'cancelled'])
            ->where('tanggal_mulai', '<=', $endOfMonth)
            ->where('tanggal_selesai', '>=', $startOfMonth)
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
                        'paket' => $item->nama_paket ?? ($item->paketPeminjaman?->nama_paket ?? 'Paket Aula'),
                        'jam_mulai' => $startDate->format('H:i'),
                        'jam_selesai' => $endDate->format('H:i'),
                        'status' => $item->status,
                        'url' => route('admin.peminjaman.show', $item->id),
                    ];
                }
                $current->addDay();
            }
        }

        // Ambil data operasional peminjaman terbaru
        $recentPeminjamans = Peminjaman::with([
            'paketPeminjaman',
            'pembayaran.details',
        ])->latest()->take(5)->get();

        // Hitung statistik peminjaman per paket untuk grafik batang Chart.js
        $paketStats = PaketPeminjaman::withCount('peminjamans')->get();
        $paketChartLabels = $paketStats->pluck('nama_paket')->toArray();
        $paketChartData = $paketStats->pluck('peminjamans_count')->toArray();

        $customCount = Peminjaman::where('is_custom', true)->count();
        if ($customCount > 0 || empty($paketChartLabels)) {
            $paketChartLabels[] = 'Kustom / Mandiri';
            $paketChartData[] = $customCount;
        }

        return view('Admin.peminjaman.dashboard', compact(
            'paymentConfig',
            'isSuperAdmin',
            'peminjamanTerverifikasiCount',
            'paketCount',
            'facilityCount',
            'recentPeminjamans',
            'calendarDate',
            'bookedDays',
            'paketChartLabels',
            'paketChartData'
        ));
    }
}
