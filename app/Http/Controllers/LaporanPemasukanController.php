<?php

namespace App\Http\Controllers;

use App\Models\DetailPembayaran;
use App\Models\PaketPeminjaman;
use App\Models\PaymentConfiguration;
use App\Models\Peminjaman;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LaporanPemasukanController extends Controller
{
    /**
     * Tampilkan halaman utama Laporan Rekapitulasi Pemasukan Aula.
     */
    public function index(Request $request): View
    {
        Peminjaman::syncExpiredDeadlines();

        // Tentukan apakah user mengakses via panel Kepala Sekolah atau Admin Aula
        $isKepalaSekolah = $request->user()?->role === 'kepala_sekolah' || $request->routeIs('kepala-sekolah.*');

        // Parameter filter dan query dasar
        $filter = $this->resolveFilterParameters($request);
        $query = $this->buildFilteredQuery($filter);

        // Ambil data peminjaman terpaginasi untuk tabel
        $peminjamans = (clone $query)
            ->latest('tanggal_mulai')
            ->paginate(15)
            ->withQueryString();

        // Hitung akumulasi statistik ringkasan keuangan
        $stats = $this->calculateFinancialSummary($query);

        // Data visualisasi analitik (grafik bulanan & proporsi paket)
        $chartData = $this->prepareChartData();

        // Daftar paket untuk filter dropdown
        $daftarPaket = PaketPeminjaman::orderBy('nama_paket')->get();

        return view('Admin.laporan.index', compact(
            'peminjamans',
            'stats',
            'chartData',
            'daftarPaket',
            'filter',
            'isKepalaSekolah'
        ));
    }

    /**
     * Ekspor Laporan Rekapitulasi Pemasukan Aula ke format PDF.
     */
    public function exportPdf(Request $request): Response
    {
        Peminjaman::syncExpiredDeadlines();

        $isKepalaSekolah = $request->user()?->role === 'kepala_sekolah' || $request->routeIs('kepala-sekolah.*');
        $filter = $this->resolveFilterParameters($request);
        $query = $this->buildFilteredQuery($filter);

        // Ambil seluruh data sesuai filter tanpa paginasi untuk dicetak ke PDF
        $peminjamans = $query
            ->orderBy('tanggal_mulai', 'asc')
            ->get();

        $stats = $this->calculateFinancialSummary($query);

        // Ambil data penandatangan resmi
        $kepalaSekolah = User::where('role', 'kepala_sekolah')->first();
        $petugasAdmin = $request->user();
        $paymentConfig = PaymentConfiguration::current();

        // Encode logo SMKN 2 Karanganyar ke Base64 agar dapat di-render Dompdf tanpa hambatan file path
        $logoBase64 = null;
        $logoPath = public_path('assets/logosmkk.png');
        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/png;base64,'.base64_encode($logoData);
        }

        $pdfData = [
            'peminjamans' => $peminjamans,
            'stats' => $stats,
            'filter' => $filter,
            'isKepalaSekolah' => $isKepalaSekolah,
            'kepalaSekolah' => $kepalaSekolah,
            'petugasAdmin' => $petugasAdmin,
            'paymentConfig' => $paymentConfig,
            'logoBase64' => $logoBase64,
            'printedAt' => Carbon::now()->locale('id')->isoFormat('D MMMM Y, HH:mm').' WIB',
        ];

        $pdf = Pdf::loadView('Admin.laporan.pdf', $pdfData);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->setOption('isHtml5ParserEnabled', true);

        $filename = 'Laporan-Pemasukan-Aula-SMKN2-KRA-'.Carbon::now()->format('Ymd-His').'.pdf';

        if ($request->query('stream') === '1') {
            return $pdf->stream($filename);
        }

        return $pdf->download($filename);
    }

    /**
     * Resolusi parameter filter dari request (dengan dukungan preset periode).
     */
    private function resolveFilterParameters(Request $request): array
    {
        $preset = $request->query('preset', 'bulan_ini');
        $tanggalDari = $request->query('tanggal_dari');
        $tanggalSampai = $request->query('tanggal_sampai');

        // Jika user memilih preset cepat
        if ($preset && ! $request->has('custom_range')) {
            switch ($preset) {
                case 'hari_ini':
                    $tanggalDari = Carbon::today()->toDateString();
                    $tanggalSampai = Carbon::today()->toDateString();
                    break;
                case 'bulan_ini':
                    $tanggalDari = Carbon::now()->startOfMonth()->toDateString();
                    $tanggalSampai = Carbon::now()->endOfMonth()->toDateString();
                    break;
                case 'bulan_lalu':
                    $tanggalDari = Carbon::now()->subMonth()->startOfMonth()->toDateString();
                    $tanggalSampai = Carbon::now()->subMonth()->endOfMonth()->toDateString();
                    break;
                case 'tahun_ini':
                    $tanggalDari = Carbon::now()->startOfYear()->toDateString();
                    $tanggalSampai = Carbon::now()->endOfYear()->toDateString();
                    break;
                case 'semua':
                    $tanggalDari = null;
                    $tanggalSampai = null;
                    break;
                case 'custom':
                default:
                    // Tetap gunakan tanggal_dari & tanggal_sampai yang diisi
                    break;
            }
        }

        return [
            'preset' => $preset,
            'tanggal_dari' => $tanggalDari,
            'tanggal_sampai' => $tanggalSampai,
            'filter_by' => $request->query('filter_by', 'sewa'), // 'sewa' (jadwal pelaksanaan aula), 'transaksi' (pembayaran), 'pengajuan'
            'status_pembayaran' => $request->query('status_pembayaran', 'all'),
            'paket_id' => $request->query('paket_id', 'all'),
            'search' => trim((string) $request->query('search', '')),
        ];
    }

    /**
     * Bangun query Eloquent Peminjaman dengan seluruh relasi & kriteria filter.
     *
     * @param  array<string, mixed>  $filter
     */
    private function buildFilteredQuery(array $filter): Builder
    {
        $query = Peminjaman::query()->with([
            'paketPeminjaman',
            'pembayaran.details' => function ($q) {
                $q->where('status', 'verified')->orderBy('id');
            },
        ]);

        // 1. Filter Rentang Tanggal
        if (! empty($filter['tanggal_dari']) || ! empty($filter['tanggal_sampai'])) {
            $dari = $filter['tanggal_dari'] ? Carbon::parse($filter['tanggal_dari'])->startOfDay() : null;
            $sampai = $filter['tanggal_sampai'] ? Carbon::parse($filter['tanggal_sampai'])->endOfDay() : null;

            if ($filter['filter_by'] === 'sewa') {
                // Berdasarkan jadwal sewa aula (mencakup peminjaman aktif/berjalan atau diajukan pada tanggal tersebut)
                if ($dari && $sampai) {
                    $query->where(function ($q) use ($dari, $sampai) {
                        $q->where(function ($sub) use ($dari, $sampai) {
                            $sub->whereDate('tanggal_mulai', '<=', $sampai->toDateString())
                                ->whereDate('tanggal_selesai', '>=', $dari->toDateString());
                        })->orWhere(function ($sub) use ($dari, $sampai) {
                            $sub->whereDate('created_at', '>=', $dari->toDateString())
                                ->whereDate('created_at', '<=', $sampai->toDateString());
                        });
                    });
                } elseif ($dari) {
                    $query->where(function ($q) use ($dari) {
                        $q->whereDate('tanggal_selesai', '>=', $dari->toDateString())
                            ->orWhereDate('created_at', '>=', $dari->toDateString());
                    });
                } elseif ($sampai) {
                    $query->where(function ($q) use ($sampai) {
                        $q->whereDate('tanggal_mulai', '<=', $sampai->toDateString())
                            ->orWhereDate('created_at', '<=', $sampai->toDateString());
                    });
                }
            } elseif ($filter['filter_by'] === 'pengajuan') {
                // Berdasarkan waktu permohonan booking dibuat
                if ($dari && $sampai) {
                    $query->whereDate('created_at', '>=', $dari->toDateString())
                        ->whereDate('created_at', '<=', $sampai->toDateString());
                } elseif ($dari) {
                    $query->whereDate('created_at', '>=', $dari->toDateString());
                } elseif ($sampai) {
                    $query->whereDate('created_at', '<=', $sampai->toDateString());
                }
            } else {
                // Berdasarkan tanggal transaksi pembayaran (detail_pembayarans) atau fallback
                $query->where(function ($sub) use ($dari, $sampai) {
                    $sub->whereHas('pembayaran.details', function ($detailQ) use ($dari, $sampai) {
                        $detailQ->where('status', 'verified');
                        if ($dari && $sampai) {
                            $detailQ->whereDate(DB::raw('COALESCE(tanggal_bayar, diverifikasi_pada, created_at)'), '>=', $dari->toDateString())
                                ->whereDate(DB::raw('COALESCE(tanggal_bayar, diverifikasi_pada, created_at)'), '<=', $sampai->toDateString());
                        } elseif ($dari) {
                            $detailQ->whereDate(DB::raw('COALESCE(tanggal_bayar, diverifikasi_pada, created_at)'), '>=', $dari->toDateString());
                        } elseif ($sampai) {
                            $detailQ->whereDate(DB::raw('COALESCE(tanggal_bayar, diverifikasi_pada, created_at)'), '<=', $sampai->toDateString());
                        }
                    })->orWhere(function ($fallbackQ) use ($dari, $sampai) {
                        if ($dari && $sampai) {
                            $fallbackQ->whereDate('created_at', '>=', $dari->toDateString())
                                ->whereDate('created_at', '<=', $sampai->toDateString());
                        } elseif ($dari) {
                            $fallbackQ->whereDate('created_at', '>=', $dari->toDateString());
                        } elseif ($sampai) {
                            $fallbackQ->whereDate('created_at', '<=', $sampai->toDateString());
                        }
                    });
                });
            }
        }

        // 2. Filter Status Pembayaran
        if ($filter['status_pembayaran'] !== 'all' && ! empty($filter['status_pembayaran'])) {
            $status = $filter['status_pembayaran'];
            if ($status === 'refunded') {
                $query->whereHas('pembayaran', function ($q) {
                    $q->whereIn('status_pembayaran', ['refunded', 'refund_pending'])
                        ->orWhere('total_refund', '>', 0);
                });
            } else {
                $query->whereHas('pembayaran', function ($q) use ($status) {
                    $q->where('status_pembayaran', $status);
                });
            }
        }

        // 3. Filter Paket Peminjaman
        if ($filter['paket_id'] !== 'all' && ! empty($filter['paket_id'])) {
            $query->where('paket_peminjaman_id', $filter['paket_id']);
        }

        // 4. Pencarian teks bebas
        if (! empty($filter['search'])) {
            $search = $filter['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('email_instansi', 'like', "%{$search}%")
                    ->orWhere('catatan', 'like', "%{$search}%")
                    ->orWhereHas('pembayaran', function ($pembQ) use ($search) {
                        $pembQ->where('kode_pembayaran', 'like', "%{$search}%");
                    })
                    ->orWhereHas('paketPeminjaman', function ($paketQ) use ($search) {
                        $paketQ->where('nama_paket', 'like', "%{$search}%");
                    });
            });
        }

        return $query;
    }

    /**
     * Hitung ringkasan statistik keuangan berdasarkan query yang aktif.
     */
    private function calculateFinancialSummary(Builder $query): array
    {
        // Ambil data untuk kalkulasi finansial
        $records = (clone $query)->with(['pembayaran.details'])->get();

        $totalPeminjaman = $records->count();
        $totalTagihan = 0.0;
        $totalPemasukanBruto = 0.0;
        $totalRefund = 0.0;
        $totalSisaTagihan = 0.0;

        $countLunas = 0;
        $countPartial = 0;
        $countPending = 0;
        $countRefunded = 0;

        foreach ($records as $peminjaman) {
            $pembayaran = $peminjaman->pembayaran;
            if (! $pembayaran) {
                continue;
            }

            $tagihan = (float) $pembayaran->total_tagihan;
            $terbayar = (float) $pembayaran->total_terbayar;
            $refund = (float) $pembayaran->total_refund;
            $sisa = (float) $pembayaran->sisa_tagihan;

            $totalTagihan += $tagihan;
            $totalPemasukanBruto += $terbayar;
            $totalRefund += $refund;
            $totalSisaTagihan += $sisa;

            if ($pembayaran->status_pembayaran === 'lunas') {
                $countLunas++;
            } elseif ($pembayaran->status_pembayaran === 'partial') {
                $countPartial++;
            } elseif ($pembayaran->status_pembayaran === 'pending') {
                $countPending++;
            }

            if ($pembayaran->status_pembayaran === 'refunded' || $refund > 0) {
                $countRefunded++;
            }
        }

        $totalPemasukanNetto = max(0, $totalPemasukanBruto - $totalRefund);
        $persenLunas = $totalTagihan > 0 ? round(($totalPemasukanNetto / $totalTagihan) * 100, 1) : 0;

        return [
            'total_peminjaman' => $totalPeminjaman,
            'total_tagihan' => $totalTagihan,
            'total_pemasukan_bruto' => $totalPemasukanBruto,
            'total_refund' => $totalRefund,
            'total_pemasukan_netto' => $totalPemasukanNetto,
            'total_sisa_tagihan' => $totalSisaTagihan,
            'count_lunas' => $countLunas,
            'count_partial' => $countPartial,
            'count_pending' => $countPending,
            'count_refunded' => $countRefunded,
            'persen_lunas' => $persenLunas,
        ];
    }

    /**
     * Siapkan data analitik bulanan dan proporsi paket untuk Chart.js (Optimasi 2 query bebas loop).
     */
    private function prepareChartData(): array
    {
        // 1. Data Pemasukan 6 Bulan Terakhir (1 Query tunggal cepat bebas multi-loop)
        $months = [];
        $incomeData = [];
        $refundData = [];
        $buckets = [];

        for ($i = 5; $i >= 0; $i--) {
            $monthDate = Carbon::now()->subMonths($i);
            $ymKey = $monthDate->format('Y-m');
            $months[] = $monthDate->locale('id')->isoFormat('MMM Y');
            $buckets[$ymKey] = ['income' => 0.0, 'refund' => 0.0];
        }

        $startDate = Carbon::now()->subMonths(5)->startOfMonth();
        $details = DetailPembayaran::query()
            ->where('status', 'verified')
            ->where(function ($q) use ($startDate) {
                $q->where('tanggal_bayar', '>=', $startDate)
                    ->orWhere(function ($sub) use ($startDate) {
                        $sub->whereNull('tanggal_bayar')
                            ->where('created_at', '>=', $startDate);
                    });
            })
            ->get(['tipe_pembayaran', 'jumlah_bayar', 'tanggal_bayar', 'diverifikasi_pada', 'created_at']);

        foreach ($details as $detail) {
            $date = $detail->tanggal_bayar ?? $detail->diverifikasi_pada ?? $detail->created_at;
            if (! $date) {
                continue;
            }
            $ym = Carbon::parse($date)->format('Y-m');
            if (isset($buckets[$ym])) {
                $val = (float) $detail->jumlah_bayar;
                if ($detail->tipe_pembayaran === 'refund') {
                    $buckets[$ym]['refund'] += $val;
                } elseif (in_array($detail->tipe_pembayaran, ['dp', 'pelunasan', 'lunas_langsung'], true)) {
                    $buckets[$ym]['income'] += $val;
                }
            }
        }

        foreach ($buckets as $b) {
            $incomeData[] = $b['income'];
            $refundData[] = $b['refund'];
        }

        // 2. Proporsi Pemasukan per Paket Peminjaman (1 Query agregat database)
        $paketStats = DB::table('paket_peminjamans')
            ->leftJoin('peminjamans', 'paket_peminjamans.id', '=', 'peminjamans.paket_peminjaman_id')
            ->leftJoin('pembayarans', 'peminjamans.id', '=', 'pembayarans.peminjaman_id')
            ->select('paket_peminjamans.nama_paket', DB::raw('COALESCE(SUM(pembayarans.total_terbayar), 0) as total_revenue'))
            ->groupBy('paket_peminjamans.id', 'paket_peminjamans.nama_paket')
            ->orderBy('paket_peminjamans.nama_paket')
            ->get();

        $paketLabels = $paketStats->pluck('nama_paket')->all();
        $paketRevenues = $paketStats->pluck('total_revenue')->map(fn ($v) => (float) $v)->all();

        return [
            'monthly' => [
                'labels' => $months,
                'income' => $incomeData,
                'refund' => $refundData,
            ],
            'paket' => [
                'labels' => $paketLabels,
                'revenues' => $paketRevenues,
            ],
        ];
    }
}
