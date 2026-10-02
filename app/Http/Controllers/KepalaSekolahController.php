<?php

namespace App\Http\Controllers;

use App\Models\PaymentConfiguration;
use App\Models\Peminjaman;
use App\Models\Persetujuan;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class KepalaSekolahController extends Controller
{
    /**
     * Tampilkan dashboard utama Kepala Sekolah.
     */
    public function dashboard(Request $request): View
    {
        Peminjaman::syncExpiredDeadlines();

        $stats = [
            'pending_final' => Peminjaman::where('status', 'approved_1')->count(),
            'approved_final' => Peminjaman::where('status', 'approved_final')->count(),
            'rejected' => Peminjaman::where('status', 'rejected')->count(),
            'total' => Peminjaman::count(),
        ];

        // Daftar peminjaman yang paling mendesak butuh persetujuan final
        $pendingPeminjamans = Peminjaman::with(['paketPeminjaman', 'pembayaran.details', 'persetujuans'])
            ->where('status', 'approved_1')
            ->orderBy('tanggal_mulai', 'asc')
            ->limit(10)
            ->get();

        // Agenda peminjaman aula mendatang yang sudah disetujui final
        $upcomingAgendas = Peminjaman::with(['paketPeminjaman', 'pembayaran'])
            ->where('status', 'approved_final')
            ->where('tanggal_selesai', '>=', now()->startOfDay())
            ->orderBy('tanggal_mulai', 'asc')
            ->limit(6)
            ->get();

        return view('Admin.kepalaSekolah.dashboard', compact('stats', 'pendingPeminjamans', 'upcomingAgendas'));
    }

    /**
     * Tampilkan daftar peminjaman aula untuk Kepala Sekolah.
     */
    public function index(Request $request): View
    {
        Peminjaman::syncExpiredDeadlines();

        $query = Peminjaman::with(['paketPeminjaman', 'pembayaran.details', 'persetujuans']);

        // Filter status tab
        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        // Pencarian nama instansi / nama pemohon / nomor invoice
        if ($request->filled('search')) {
            $search = trim((string) $request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('email_instansi', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%")
                    ->orWhereHas('pembayaran', function ($sq) use ($search) {
                        $sq->where('kode_pembayaran', 'like', "%{$search}%");
                    });
            });
        }

        // Filter tanggal pelaksanaan
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_mulai', '>=', $request->query('tanggal_dari'));
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_mulai', '<=', $request->query('tanggal_sampai'));
        }

        // Prioritaskan yang butuh approval final ('approved_1') di bagian atas
        $peminjamans = $query
            ->orderByRaw("CASE WHEN status = 'approved_1' THEN 0 WHEN status = 'approved_final' THEN 1 WHEN status = 'pending' THEN 2 ELSE 3 END")
            ->orderBy('tanggal_mulai', 'desc')
            ->paginate(12)
            ->withQueryString();

        $stats = [
            'all' => Peminjaman::count(),
            'pending_final' => Peminjaman::where('status', 'approved_1')->count(),
            'approved_final' => Peminjaman::where('status', 'approved_final')->count(),
            'rejected' => Peminjaman::where('status', 'rejected')->count(),
        ];

        return view('Admin.kepalaSekolah.index', compact('peminjamans', 'stats'));
    }

    /**
     * Ekspor Daftar Peminjaman Aula ke format PDF (Kepala Sekolah) dengan filter tanggal (default: Bulan Ini).
     */
    public function exportPdf(Request $request): Response
    {
        Peminjaman::syncExpiredDeadlines();

        $defaultDari = Carbon::now()->startOfMonth()->toDateString();
        $defaultSampai = Carbon::now()->endOfMonth()->toDateString();

        $tanggalDari = $request->query('all_dates') === '1' ? null : ($request->query('tanggal_dari') ?: $defaultDari);
        $tanggalSampai = $request->query('all_dates') === '1' ? null : ($request->query('tanggal_sampai') ?: $defaultSampai);

        $query = Peminjaman::with(['paketPeminjaman', 'pembayaran.details', 'persetujuans']);

        if ($tanggalDari && $tanggalSampai) {
            $query->where(function ($q) use ($tanggalDari, $tanggalSampai) {
                $q->where(function ($sub) use ($tanggalDari, $tanggalSampai) {
                    $sub->whereDate('tanggal_mulai', '<=', $tanggalSampai)
                        ->whereDate('tanggal_selesai', '>=', $tanggalDari);
                })->orWhere(function ($sub) use ($tanggalDari, $tanggalSampai) {
                    $sub->whereDate('created_at', '>=', $tanggalDari)
                        ->whereDate('created_at', '<=', $tanggalSampai);
                });
            });
        } elseif ($tanggalDari) {
            $query->where(function ($q) use ($tanggalDari) {
                $q->whereDate('tanggal_selesai', '>=', $tanggalDari)
                    ->orWhereDate('created_at', '>=', $tanggalDari);
            });
        } elseif ($tanggalSampai) {
            $query->where(function ($q) use ($tanggalSampai) {
                $q->whereDate('tanggal_mulai', '<=', $tanggalSampai)
                    ->orWhereDate('created_at', '<=', $tanggalSampai);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('email_instansi', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%")
                    ->orWhereHas('pembayaran', function ($sq) use ($search) {
                        $sq->where('kode_pembayaran', 'like', "%{$search}%");
                    });
            });
        }

        $peminjamans = $query->orderBy('tanggal_mulai', 'asc')->get();

        $stats = [
            'total' => $peminjamans->count(),
            'approved' => $peminjamans->whereIn('status', ['approved_1', 'approved_final'])->count(),
            'pending' => $peminjamans->where('status', 'pending')->count(),
            'rejected' => $peminjamans->where('status', 'rejected')->count(),
            'total_tagihan' => (float) $peminjamans->sum(fn ($p) => (float) ($p->pembayaran?->total_tagihan ?? 0)),
            'total_terbayar' => (float) $peminjamans->sum(fn ($p) => (float) ($p->pembayaran?->total_terbayar ?? 0)),
            'total_sisa_tagihan' => (float) $peminjamans->sum(fn ($p) => (float) ($p->pembayaran?->sisa_tagihan ?? 0)),
        ];

        $kepalaSekolah = $request->user()?->role === 'kepala_sekolah' ? $request->user() : User::where('role', 'kepala_sekolah')->first();
        $petugasAdmin = User::where('role', 'admin_aula')->first() ?? User::whereIn('role', ['admin', 'super_admin', 'super_duper_admin'])->first() ?? $request->user();
        $paymentConfig = PaymentConfiguration::current();

        $logoBase64 = null;
        $logoPath = public_path('assets/logosmkk.png');
        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/png;base64,'.base64_encode($logoData);
        }

        $pdf = Pdf::loadView('Admin.peminjaman.pdf', compact(
            'peminjamans',
            'stats',
            'tanggalDari',
            'tanggalSampai',
            'kepalaSekolah',
            'petugasAdmin',
            'paymentConfig',
            'logoBase64'
        ))->setPaper('a4', 'portrait');

        $filename = 'Daftar-Peminjaman-Aula-Kepsek-'.Carbon::now()->format('Ymd-His').'.pdf';

        if ($request->query('stream') === '1') {
            return $pdf->stream($filename);
        }

        return $pdf->download($filename);
    }

    /**
     * Halaman detail peminjaman aula untuk review dan persetujuan final Kepala Sekolah.
     */
    public function show(Peminjaman $peminjaman): View
    {
        Peminjaman::syncExpiredDeadlines();

        $peminjaman->load([
            'paketPeminjaman.facilities',
            'pembayaran.details' => function ($q) {
                $q->orderBy('created_at', 'desc');
            },
            'persetujuans.approver',
        ]);

        $approvalAdmin = $peminjaman->persetujuans->where('level', 'admin')->sortByDesc('created_at')->first();
        $approvalPimpinan = $peminjaman->persetujuans->where('level', 'pimpinan')->sortByDesc('created_at')->first();

        // Cek potensi konflik jadwal dengan peminjaman lain yang sudah disetujui final
        $conflictingPeminjaman = Peminjaman::where('id', '!=', $peminjaman->id)
            ->where('status', 'approved_final')
            ->where(function ($query) use ($peminjaman) {
                $query->where('tanggal_mulai', '<', $peminjaman->tanggal_selesai)
                    ->where('tanggal_selesai', '>', $peminjaman->tanggal_mulai);
            })
            ->first();

        return view('Admin.kepalaSekolah.show', compact('peminjaman', 'approvalAdmin', 'approvalPimpinan', 'conflictingPeminjaman'));
    }

    /**
     * Berikan persetujuan final peminjaman aula oleh Kepala Sekolah.
     */
    public function approve(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        if ($request->user()?->isSuperAdmin() || $request->user()?->role !== 'kepala_sekolah') {
            abort(403, 'Akses ditolak: Persetujuan final hanya dapat diproses oleh Kepala Sekolah.');
        }

        // Pastikan status peminjaman sudah disetujui admin sarpras (tahap awal)
        if ($peminjaman->status !== 'approved_1') {
            if ($peminjaman->status === 'approved_final') {
                return back()->with('error', 'Permohonan peminjaman ini telah disetujui secara final sebelumnya.');
            }

            return back()->with('error', 'Permohonan peminjaman aula belum diverifikasi dan disetujui oleh admin sarpras.');
        }

        // Cek kembali bentrok jadwal dengan peminjaman lain yang sudah disetujui final
        $conflict = Peminjaman::where('id', '!=', $peminjaman->id)
            ->where('status', 'approved_final')
            ->where(function ($query) use ($peminjaman) {
                $query->where('tanggal_mulai', '<', $peminjaman->tanggal_selesai)
                    ->where('tanggal_selesai', '>', $peminjaman->tanggal_mulai);
            })
            ->first();

        if ($conflict) {
            $start = Carbon::parse($conflict->tanggal_mulai)->translatedFormat('d M Y H:i');
            $end = Carbon::parse($conflict->tanggal_selesai)->translatedFormat('d M Y H:i');

            return back()->with('error', "Persetujuan final gagal: Jadwal bentrok dengan peminjaman resmi (#{$conflict->id} - {$conflict->nama} pada {$start} s/d {$end} WIB).");
        }

        DB::transaction(function () use ($request, $peminjaman) {
            // Update status menjadi approved_final
            $peminjaman->update(['status' => 'approved_final']);

            // Catat persetujuan level pimpinan / kepala sekolah
            Persetujuan::create([
                'peminjaman_id' => $peminjaman->id,
                'approver_id' => auth()->id(),
                'level' => 'pimpinan',
                'status' => 'approved',
                'catatan_approval' => $request->input('catatan_approval', 'Pengajuan peminjaman aula resmi disetujui final oleh Kepala Sekolah.'),
                'tanggal_proses' => now(),
            ]);
        });

        return redirect()->route('kepala-sekolah.peminjaman.show', $peminjaman->id)
            ->with('success', "Permohonan peminjaman aula #{$peminjaman->id} oleh {$peminjaman->nama} berhasil disetujui secara FINAL!");
    }

    /**
     * Tolak peminjaman aula oleh Kepala Sekolah.
     */
    public function reject(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        if ($request->user()?->isSuperAdmin() || $request->user()?->role !== 'kepala_sekolah') {
            abort(403, 'Akses ditolak: Penolakan final hanya dapat diproses oleh Kepala Sekolah.');
        }

        $validated = $request->validate([
            'alasan_penolakan' => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'alasan_penolakan.required' => 'Keterangan atau alasan penolakan permohonan wajib diisi.',
            'alasan_penolakan.min' => 'Alasan penolakan minimal 5 karakter.',
        ]);

        DB::transaction(function () use ($peminjaman, $validated) {
            // Ubah status peminjaman menjadi rejected
            $peminjaman->update(['status' => 'rejected']);

            // Catat riwayat penolakan pimpinan
            Persetujuan::create([
                'peminjaman_id' => $peminjaman->id,
                'approver_id' => auth()->id(),
                'level' => 'pimpinan',
                'status' => 'rejected',
                'catatan_approval' => $validated['alasan_penolakan'],
                'tanggal_proses' => now(),
            ]);

            $pembayaran = $peminjaman->pembayaran;
            if ($pembayaran) {
                // Hitung apakah pemohon telah membayar dan diverifikasi valid
                $nominalTerbayar = (float) $pembayaran->total_terbayar;
                if ($nominalTerbayar <= 0) {
                    $nominalTerbayar = (float) $pembayaran->details()
                        ->where('status', 'verified')
                        ->where('tipe_pembayaran', '!=', 'refund')
                        ->sum('jumlah_bayar');
                }

                if ($nominalTerbayar > 0) {
                    $pembayaran->update([
                        'status_pembayaran' => 'refund_pending',
                        'total_refund' => $nominalTerbayar,
                        'sisa_tagihan' => 0,
                        'catatan' => 'Permohonan ditolak oleh Kepala Sekolah. Alasan: '.$validated['alasan_penolakan'],
                    ]);

                    $pembayaran->details()->firstOrCreate(
                        [
                            'pembayaran_id' => $pembayaran->id,
                            'tipe_pembayaran' => 'refund',
                        ],
                        [
                            'kode_transaksi' => 'TRX-RFD-'.date('Ym').'-'.str_pad((string) $pembayaran->id, 3, '0', STR_PAD_LEFT),
                            'jumlah_bayar' => $nominalTerbayar,
                            'metode' => 'transfer',
                            'status' => 'pending',
                            'catatan' => 'Menunggu pemohon melengkapi data rekening pengembalian dana (refund).',
                        ]
                    );
                }
            }
        });

        return redirect()->route('kepala-sekolah.peminjaman.show', $peminjaman->id)
            ->with('success', "Permohonan peminjaman aula #{$peminjaman->id} telah ditolak oleh Kepala Sekolah.");
    }
}
