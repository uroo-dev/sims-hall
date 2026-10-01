<?php

namespace App\Http\Controllers;

use App\Models\DetailPembayaran;
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

class AdminPeminjamanController extends Controller
{
    /**
     * Halaman Daftar Peminjaman Aula untuk Admin:
     * - Menampilkan data peminjaman beserta paket, pembayaran, dan persetujuan
     * - Filter berdasarkan status peminjaman dan status pembayaran
     * - Pencarian berdasarkan nama peminjam, email, atau invoice
     * - Eager loading optimal bebas N+1 query
     */
    public function index(Request $request): View
    {
        // 1. Sinkronisasi peminjaman yang kedaluwarsa secara otomatis
        Peminjaman::syncExpiredDeadlines();

        // 2. Query data dengan eager loading bebas N+1
        $query = Peminjaman::with([
            'paketPeminjaman.facilities',
            'pembayaran.details.diverifikasiOleh',
            'persetujuans.approver',
        ])->latest();

        // Filter status peminjaman (draft, pending, approved_1, approved_final, rejected)
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter status pembayaran (pending, partial, lunas, refund_pending, refunded, rejected, hangus)
        if ($request->filled('status_pembayaran')) {
            $query->whereHas('pembayaran', function ($q) use ($request) {
                $q->where('status_pembayaran', $request->input('status_pembayaran'));
            });
        }

        // Filter rentang tanggal pelaksanaan sewa
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_mulai', '>=', $request->input('tanggal_dari'));
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_mulai', '<=', $request->input('tanggal_sampai'));
        }

        // Pencarian teks (Nama Pemohon, Email Instansi, Kode Pembayaran)
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('email_instansi', 'like', "%{$search}%")
                    ->orWhereHas('pembayaran', function ($sub) use ($search) {
                        $sub->where('kode_pembayaran', 'like', "%{$search}%");
                    });
            });
        }

        $peminjamans = $query->paginate(10)->withQueryString();

        // Ringkasan metrik statistik peminjaman aula
        $stats = [
            'total' => Peminjaman::count(),
            'pending' => Peminjaman::where('status', 'pending')->count(),
            'approved' => Peminjaman::whereIn('status', ['approved_1', 'approved_final'])->count(),
            'rejected' => Peminjaman::whereIn('status', ['rejected', 'cancelled'])->count(),
            'cancelled' => Peminjaman::where('status', 'cancelled')->count(),
            'refund_pending' => Peminjaman::whereHas('pembayaran', function ($q) {
                $q->where('status_pembayaran', 'refund_pending');
            })->count(),
        ];

        return view('Admin.peminjaman.index', compact('peminjamans', 'stats'));
    }

    /**
     * Ekspor Daftar Peminjaman Aula ke format PDF dengan filter tanggal (default: Bulan Ini).
     */
    public function exportPdf(Request $request): Response
    {
        Peminjaman::syncExpiredDeadlines();

        $defaultDari = Carbon::now()->startOfMonth()->toDateString();
        $defaultSampai = Carbon::now()->endOfMonth()->toDateString();

        $tanggalDari = $request->query('all_dates') === '1' ? null : ($request->query('tanggal_dari') ?: $defaultDari);
        $tanggalSampai = $request->query('all_dates') === '1' ? null : ($request->query('tanggal_sampai') ?: $defaultSampai);

        $query = Peminjaman::with([
            'paketPeminjaman.facilities',
            'pembayaran.details.diverifikasiOleh',
            'persetujuans.approver',
        ]);

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
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('status_pembayaran')) {
            $query->whereHas('pembayaran', function ($q) use ($request) {
                $q->where('status_pembayaran', $request->input('status_pembayaran'));
            });
        }

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('email_instansi', 'like', "%{$search}%")
                    ->orWhereHas('pembayaran', function ($sub) use ($search) {
                        $sub->where('kode_pembayaran', 'like', "%{$search}%");
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

        $kepalaSekolah = User::where('role', 'kepala_sekolah')->first();
        $petugasAdmin = $request->user();
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

        $filename = 'Daftar-Peminjaman-Aula-'.Carbon::now()->format('Ymd-His').'.pdf';

        if ($request->query('stream') === '1') {
            return $pdf->stream($filename);
        }

        return $pdf->download($filename);
    }

    /**
     * Halaman Detail Peminjaman Aula untuk Admin:
     * - Rincian identitas pemohon & instansi
     * - Jadwal sewa aula & paket fasilitas
     * - Berkas surat pengantar/pengajuan
     * - Riwayat transaksi pembayaran & bukti transfer
     * - Aksi approve / reject pengajuan dan reject pembayaran deposit
     * - Formulir unggah bukti transfer refund jika status pembayaran refund_pending
     */
    public function show(Peminjaman $peminjaman): View
    {
        Peminjaman::syncExpiredDeadlines();

        // Eager load lengkap bebas N+1 query
        $peminjaman->load([
            'paketPeminjaman.facilities',
            'pembayaran.details.diverifikasiOleh',
            'persetujuans.approver',
        ]);

        $pembayaran = $peminjaman->pembayaran;
        $config = PaymentConfiguration::current();

        // Pastikan batas waktu pembayaran final (pelunasan) terupdate jika DP telah dibayar / disetujui (partial)
        if ($pembayaran && ($pembayaran->status_pembayaran === 'partial' || ($pembayaran->total_terbayar > 0 && $pembayaran->sisa_tagihan > 0))) {
            if ($peminjaman->tanggal_mulai) {
                $tenggatPelunasanSeharusnya = Carbon::parse($peminjaman->tanggal_mulai)->subHours((int) $config->jatuh_tempo_pelunasan_jam);
                if (! $pembayaran->jatuh_tempo_pelunasan || $pembayaran->jatuh_tempo_pelunasan->format('Y-m-d H:i') !== $tenggatPelunasanSeharusnya->format('Y-m-d H:i')) {
                    $pembayaran->update([
                        'jatuh_tempo_pelunasan' => $tenggatPelunasanSeharusnya,
                    ]);
                    $pembayaran->refresh();
                }
            }
        }

        // Ambil transaksi refund jika ada
        $refundDetail = $pembayaran ? $pembayaran->details->firstWhere('tipe_pembayaran', 'refund') : null;

        // Cek apakah jadwal peminjaman bentrok dengan peminjaman lain yang sudah disetujui
        $conflictingApproved = null;
        if (! in_array($peminjaman->status, ['approved_1', 'approved_final', 'rejected', 'cancelled'])) {
            $conflictingApproved = Peminjaman::query()
                ->where('id', '!=', $peminjaman->id)
                ->where(function ($q) {
                    $q->whereIn('status', ['approved_1', 'approved_final'])
                        ->orWhereHas('persetujuans', function ($sq) {
                            $sq->where('status', 'approved');
                        });
                })
                ->whereNotIn('status', ['rejected', 'cancelled'])
                ->where(function ($query) use ($peminjaman) {
                    $query->where('tanggal_mulai', '<', $peminjaman->tanggal_selesai)
                        ->where('tanggal_selesai', '>', $peminjaman->tanggal_mulai);
                })
                ->first();
        }

        return view('Admin.peminjaman.show', compact('peminjaman', 'pembayaran', 'config', 'refundDetail', 'conflictingApproved'));
    }

    /**
     * Aksi Approve Pengajuan oleh Admin Aula:
     * - Memvalidasi ketiadaan jadwal bentrok dengan peminjaman lain yang sudah disetujui
     * - Mengubah status peminjaman menjadi approved_1 (atau approved_final)
     * - Menyimpan riwayat persetujuan admin
     * - Memverifikasi transaksi pembayaran pending (DP/Lunas) jika bukti sudah ada
     */
    public function approve(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        if ($request->user()?->isSuperAdmin()) {
            abort(403, 'Akses ditolak: Super Admin hanya memiliki akses baca pada modul peminjaman.');
        }

        $request->validate([
            'catatan_approval' => ['nullable', 'string', 'max:1000'],
        ]);

        // 1. Validasi: Sebelum approve, admin harus memverifikasi apakah pembayaran sudah benar terlebih dulu
        $hasPendingPayment = $peminjaman->pembayaran && $peminjaman->pembayaran->details()
            ->where('status', 'pending')
            ->where('tipe_pembayaran', '!=', 'refund')
            ->exists();

        if ($hasPendingPayment) {
            return back()->with('error', 'Persetujuan permohonan tidak dapat diproses: Terdapat bukti transfer pembayaran yang belum diverifikasi. Silakan periksa dan verifikasi pembayaran terlebih dahulu.');
        }

        // Cek apakah pembayaran sudah diverifikasi benar (valid)
        $hasVerifiedPayment = $peminjaman->pembayaran && (
            $peminjaman->pembayaran->total_terbayar > 0 ||
            $peminjaman->pembayaran->details()
                ->where('status', 'verified')
                ->where('tipe_pembayaran', '!=', 'refund')
                ->exists()
        );

        if (! $hasVerifiedPayment) {
            return back()->with('error', 'Persetujuan permohonan tidak dapat diproses: Pembayaran belum diverifikasi benar (valid). Pastikan pemohon telah membayar dan bukti pembayaran telah diverifikasi valid sebelum menyetujui peminjaman.');
        }

        // 2. Validasi: Cegah approval jika jadwal bentrok dengan peminjaman lain yang sudah disetujui
        $conflictingPeminjaman = Peminjaman::query()
            ->where('id', '!=', $peminjaman->id)
            ->where(function ($q) {
                $q->whereIn('status', ['approved_1', 'approved_final'])
                    ->orWhereHas('persetujuans', function ($sq) {
                        $sq->where('status', 'approved');
                    });
            })
            ->whereNotIn('status', ['rejected', 'cancelled'])
            ->where(function ($query) use ($peminjaman) {
                $query->where('tanggal_mulai', '<', $peminjaman->tanggal_selesai)
                    ->where('tanggal_selesai', '>', $peminjaman->tanggal_mulai);
            })
            ->first();

        if ($conflictingPeminjaman) {
            $conflictStart = Carbon::parse($conflictingPeminjaman->tanggal_mulai)->translatedFormat('d F Y H:i');
            $conflictEnd = Carbon::parse($conflictingPeminjaman->tanggal_selesai)->translatedFormat('d F Y H:i');

            return back()->with('error', "Persetujuan gagal: Jadwal peminjaman ini bentrok dengan peminjaman lain yang sudah disetujui (#{$conflictingPeminjaman->id} - {$conflictingPeminjaman->nama} pada {$conflictStart} s/d {$conflictEnd} WIB).");
        }

        DB::transaction(function () use ($request, $peminjaman) {
            // Update status peminjaman
            $peminjaman->update(['status' => 'approved_1']);

            // Catat persetujuan level admin
            Persetujuan::create([
                'peminjaman_id' => $peminjaman->id,
                'approver_id' => auth()->id(),
                'level' => 'admin',
                'status' => 'approved',
                'catatan_approval' => $request->input('catatan_approval', 'Pengajuan peminjaman aula disetujui oleh admin.'),
                'tanggal_proses' => now(),
            ]);

            if ($peminjaman->pembayaran) {
                $pembayaran = $peminjaman->pembayaran->fresh();

                // Hitung tenggat waktu pembayaran final (pelunasan) setelah approve / pembayaran pertama (DP):
                // Sesuai konfigurasi pembayaran, jatuh tempo pelunasan adalah X jam sebelum hari H (tanggal_mulai)
                if ($pembayaran && ($pembayaran->status_pembayaran === 'partial' || ($pembayaran->sisa_tagihan > 0 && $pembayaran->total_terbayar > 0))) {
                    $config = PaymentConfiguration::current();
                    $tenggatPelunasan = Carbon::parse($peminjaman->tanggal_mulai)->subHours((int) ($config->jatuh_tempo_pelunasan_jam ?? 24));
                    $pembayaran->update([
                        'jatuh_tempo_pelunasan' => $tenggatPelunasan,
                    ]);
                }
            }
        });

        return redirect()->route('admin.peminjaman.show', $peminjaman->id)
            ->with('success', 'Pengajuan peminjaman aula berhasil disetujui (Approved)!');
    }

    /**
     * Aksi Reject Pengajuan oleh Admin:
     * - Wajib memberikan keterangan / alasan penolakan.
     * - Sebelum reject, admin harus memverifikasi pembayaran terlebih dahulu.
     * - Jika pembayaran sudah diverifikasi benar (valid), maka proses dilanjutkan ke refund.
     * - Jika status pembayaran gagal / ditolak / belum bayar, maka refund TIDAK dilakukan.
     */
    public function reject(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        if ($request->user()?->isSuperAdmin()) {
            abort(403, 'Akses ditolak: Super Admin hanya memiliki akses baca pada modul peminjaman.');
        }

        $validated = $request->validate([
            'alasan_penolakan' => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'alasan_penolakan.required' => 'Keterangan atau alasan penolakan permohonan wajib diisi oleh admin.',
            'alasan_penolakan.min' => 'Alasan penolakan permohonan minimal 5 karakter.',
        ]);

        // Cek apakah ada bukti pembayaran yang masih pending verifikasi
        $hasPendingPayment = $peminjaman->pembayaran && $peminjaman->pembayaran->details()
            ->where('status', 'pending')
            ->where('tipe_pembayaran', '!=', 'refund')
            ->exists();

        if ($hasPendingPayment) {
            return back()->with('error', 'Penolakan permohonan tidak dapat diproses: Terdapat bukti transfer pembayaran yang masih menunggu verifikasi. Harap verifikasi bukti pembayaran terlebih dahulu (apakah pembayaran valid atau ditolak/gagal).');
        }

        $message = DB::transaction(function () use ($peminjaman, $validated) {
            // Ubah status peminjaman menjadi rejected
            $peminjaman->update(['status' => 'rejected']);

            // Catat riwayat penolakan persetujuan
            Persetujuan::create([
                'peminjaman_id' => $peminjaman->id,
                'approver_id' => auth()->id(),
                'level' => 'admin',
                'status' => 'rejected',
                'catatan_approval' => $validated['alasan_penolakan'],
                'tanggal_proses' => now(),
            ]);

            $pembayaran = $peminjaman->pembayaran;
            if (! $pembayaran) {
                return 'Pengajuan peminjaman aula berhasil ditolak.';
            }

            // Cek apakah ada pembayaran yang sudah diverifikasi benar (valid)
            $nominalTerbayar = (float) $pembayaran->total_terbayar;
            if ($nominalTerbayar <= 0) {
                $nominalTerbayar = (float) $pembayaran->details()
                    ->where('status', 'verified')
                    ->where('tipe_pembayaran', '!=', 'refund')
                    ->sum('jumlah_bayar');
            }

            // Skenario A: Pembayaran SUDAH BENAR (verified) -> lanjut ke proses refund
            if ($nominalTerbayar > 0) {
                $pembayaran->update([
                    'status_pembayaran' => 'refund_pending',
                    'total_refund' => $nominalTerbayar,
                    'sisa_tagihan' => 0,
                    'catatan' => 'Permohonan ditolak oleh admin. Alasan: '.$validated['alasan_penolakan'],
                ]);

                // Buat atau persiapkan detail pembayaran refund
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

                return 'Pengajuan berhasil ditolak. Karena pembayaran telah diverifikasi benar sebesar Rp '.number_format($nominalTerbayar, 0, ',', '.').', status pembayaran diubah menjadi Refund Pending untuk melanjutkan proses pengembalian dana. Pemohon diminta mengisi nomor rekening pengembalian dana.';
            }

            // Skenario B: Status pembayaran GAGAL / ditolak / belum bayar -> refund TIDAK dilakukan
            $pembayaran->update([
                'status_pembayaran' => 'rejected',
                'total_refund' => 0,
                'sisa_tagihan' => 0,
                'catatan' => 'Permohonan ditolak oleh admin. Alasan: '.$validated['alasan_penolakan'].'. Status pembayaran gagal/tidak valid sehingga proses refund tidak dilakukan.',
            ]);

            return 'Pengajuan peminjaman aula berhasil ditolak. Karena status pembayaran gagal / ditolak, proses pengembalian dana (refund) tidak dilakukan.';
        });

        return redirect()->route('admin.peminjaman.show', $peminjaman->id)
            ->with('success', $message);
    }

    /**
     * Aksi Verifikasi Bukti Pembayaran Pemohon (Status: Benar / Valid):
     * - Admin memeriksa bukti transfer dan menandai transaksi sebagai verified.
     */
    public function verifikasiPembayaran(Request $request, Peminjaman $peminjaman, ?DetailPembayaran $detail = null): RedirectResponse
    {
        if ($request->user()?->isSuperAdmin()) {
            abort(403, 'Akses ditolak: Super Admin hanya memiliki akses baca pada modul peminjaman.');
        }

        $pembayaran = $peminjaman->pembayaran;
        if (! $pembayaran) {
            return back()->with('error', 'Data tagihan pembayaran peminjaman tidak ditemukan.');
        }

        $targetDetail = $detail;
        if (! $targetDetail && $request->filled('detail_id')) {
            $targetDetail = $pembayaran->details()->where('id', $request->input('detail_id'))->first();
        }
        if (! $targetDetail) {
            $targetDetail = $pembayaran->details()
                ->where('status', 'pending')
                ->where('tipe_pembayaran', '!=', 'refund')
                ->latest()
                ->first();
        }

        if (! $targetDetail) {
            return back()->with('error', 'Tidak ada bukti pembayaran yang memerlukan verifikasi.');
        }

        DB::transaction(function () use ($peminjaman, $pembayaran, $targetDetail, $request) {
            $targetDetail->update([
                'status' => 'verified',
                'diverifikasi_oleh' => auth()->id(),
                'diverifikasi_pada' => now(),
                'catatan' => $request->input('catatan', 'Pembayaran telah diverifikasi benar dan valid oleh admin.'),
            ]);

            $pembayaran->syncAkumulasiPembayaran();
            $pembayaran->refresh();

            // Jika status pembayaran menjadi partial (DP terverifikasi), hitung tenggat waktu pelunasan
            if ($pembayaran->status_pembayaran === 'partial' || ($pembayaran->sisa_tagihan > 0 && $pembayaran->total_terbayar > 0)) {
                $config = PaymentConfiguration::current();
                $tenggatPelunasan = Carbon::parse($peminjaman->tanggal_mulai)->subHours((int) ($config->jatuh_tempo_pelunasan_jam ?? 24));
                $pembayaran->update([
                    'jatuh_tempo_pelunasan' => $tenggatPelunasan,
                ]);
            }

            // Jika permohonan sudah dibatalkan atau ditolak, tapi pembayaran baru diverifikasi valid oleh admin:
            // Sesuai ketentuan: jika pembatalan melebihi offset cancelation atau status pembayaran hangus, dana tidak dapat direfund.
            if (in_array($peminjaman->status, ['cancelled', 'rejected']) && $pembayaran->total_terbayar > 0) {
                if ($pembayaran->status_pembayaran === 'hangus' || ($peminjaman->status === 'cancelled' && ! $peminjaman->isEligibleForRefund())) {
                    $pembayaran->update([
                        'status_pembayaran' => 'hangus',
                        'total_refund' => 0,
                        'sisa_tagihan' => 0,
                        'catatan' => trim(($pembayaran->catatan ? $pembayaran->catatan.' | ' : '').'Pembayaran diverifikasi valid sebesar Rp '.number_format($pembayaran->total_terbayar, 0, ',', '.').', namun tidak dapat direfund karena pembatalan dilakukan melebihi batas offset pembatalan (dana hangus).'),
                    ]);
                } else {
                    $pembayaran->update([
                        'status_pembayaran' => 'refund_pending',
                        'total_refund' => $pembayaran->total_terbayar,
                        'sisa_tagihan' => 0,
                        'catatan' => 'Pembayaran terverifikasi valid sebesar Rp '.number_format($pembayaran->total_terbayar, 0, ',', '.').' setelah permohonan dibatalkan/ditolak. Status dialihkan ke Refund Pending untuk pengembalian dana pemohon.',
                    ]);

                    $pembayaran->details()->firstOrCreate(
                        [
                            'pembayaran_id' => $pembayaran->id,
                            'tipe_pembayaran' => 'refund',
                        ],
                        [
                            'kode_transaksi' => 'TRX-RFD-'.date('Ym').'-'.str_pad((string) $pembayaran->id, 3, '0', STR_PAD_LEFT),
                            'jumlah_bayar' => $pembayaran->total_terbayar,
                            'metode' => 'transfer',
                            'status' => 'pending',
                            'catatan' => 'Menunggu pemohon melengkapi data rekening pengembalian dana (refund).',
                        ]
                    );
                }
            }
        });

        $isCancelledExceeded = $peminjaman->status === 'cancelled' && ! $peminjaman->isEligibleForRefund();
        $successMsg = in_array($peminjaman->status, ['cancelled', 'rejected'])
            ? ($isCancelledExceeded
                ? 'Bukti pembayaran berhasil diverifikasi valid. Karena pembatalan permohonan melebihi offset cancelation, dana pembayaran tidak dapat direfund (status pembayaran hangus).'
                : 'Bukti pembayaran berhasil diverifikasi valid. Karena peminjaman telah dibatalkan/ditolak dalam batas waktu yang ditentukan, status pembayaran dialihkan ke Refund Pending untuk memproses pengembalian dana.')
            : 'Bukti pembayaran berhasil diverifikasi (Status: Benar / Valid). Anda kini dapat melanjutkan proses persetujuan (Approve) atau penolakan (Reject) permohonan.';

        return redirect()->route('admin.peminjaman.show', $peminjaman->id)
            ->with('success', $successMsg);
    }

    /**
     * Aksi Reject Pembayaran Deposit (Deposit Gagal / Bukti Palsu):
     * - Admin mengubah status pembayaran menjadi rejected dan wajib mengisi alasan.
     * - Pemohon diminta untuk transfer ulang dengan tenggat waktu baru sesuai konfigurasi pembayaran.
     * - Jika peminjaman ditolak saat status pembayaran gagal, proses refund tidak dilakukan.
     */
    public function rejectPembayaran(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        if ($request->user()?->isSuperAdmin()) {
            abort(403, 'Akses ditolak: Super Admin hanya memiliki akses baca pada modul peminjaman.');
        }

        $validated = $request->validate([
            'alasan_penolakan' => ['required', 'string', 'min:5', 'max:1000'],
            'detail_id' => ['nullable', 'exists:detail_pembayarans,id'],
        ], [
            'alasan_penolakan.required' => 'Keterangan atau alasan penolakan pembayaran wajib diisi oleh admin.',
            'alasan_penolakan.min' => 'Alasan penolakan pembayaran minimal 5 karakter.',
        ]);

        $pembayaran = $peminjaman->pembayaran;
        if (! $pembayaran) {
            return back()->with('error', 'Data tagihan pembayaran peminjaman tidak ditemukan.');
        }

        DB::transaction(function () use ($pembayaran, $validated, $request) {
            $detailQuery = $pembayaran->details()
                ->whereIn('status', ['pending', 'verified'])
                ->where('tipe_pembayaran', '!=', 'refund');

            if ($request->filled('detail_id')) {
                $detailQuery->where('id', $request->input('detail_id'));
            }

            $detailTerakhir = $detailQuery->latest()->first();

            if ($detailTerakhir) {
                $detailTerakhir->update([
                    'status' => 'rejected',
                    'catatan' => $validated['alasan_penolakan'],
                    'diverifikasi_oleh' => auth()->id(),
                    'diverifikasi_pada' => now(),
                ]);
            }

            // Sinkronisasi akumulasi total terbayar dan status pembayaran berdasarkan transaksi yang verified
            $pembayaran->syncAkumulasiPembayaran();
            $pembayaran->refresh();

            // Atur batas waktu baru sesuai konfigurasi pembayaran
            $config = PaymentConfiguration::current();
            $isDp = ! $detailTerakhir || $detailTerakhir->tipe_pembayaran === 'dp';

            if ($isDp) {
                $jatuhTempoBaru = now()->addHours((int) ($config->jatuh_tempo_dp_jam ?? 24));
                $pembayaran->update([
                    'jatuh_tempo_dp' => $jatuhTempoBaru,
                    'catatan' => 'Bukti pembayaran uang muka (DP) ditolak admin: '.$validated['alasan_penolakan'].'. Pemohon diminta transfer ulang sebelum '.$jatuhTempoBaru->format('d/m/Y H:i').' WIB.',
                ]);
            } else {
                $jatuhTempoPelunasan = $pembayaran->jatuh_tempo_pelunasan;
                if (! $jatuhTempoPelunasan && $peminjaman->tanggal_mulai) {
                    $jatuhTempoPelunasan = Carbon::parse($peminjaman->tanggal_mulai)->subHours((int) ($config->jatuh_tempo_pelunasan_jam ?? 24));
                }
                $pembayaran->update([
                    'catatan' => 'Bukti pembayaran pelunasan ditolak admin: '.$validated['alasan_penolakan'].'. Pemohon diminta transfer ulang sebelum '.($jatuhTempoPelunasan ? $jatuhTempoPelunasan->format('d/m/Y H:i').' WIB' : 'hari H').'.',
                ]);
            }
        });

        return redirect()->route('admin.peminjaman.show', $peminjaman->id)
            ->with('success', 'Bukti pembayaran berhasil ditolak. Status bukti transfer diubah menjadi Ditolak dan pemohon diminta melakukan transfer ulang.');
    }

    /**
     * Aksi Unggah Bukti Transfer Refund oleh Admin:
     * - Admin mentransfer ke rekening pemohon yang telah diisi sebelumnya.
     * - Admin mengunggah struk/bukti transfer pengembalian dana.
     * - Menunggu konfirmasi penerimaan dana dari pemohon untuk mengubah status menjadi refunded.
     */
    public function uploadRefund(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        if ($request->user()?->isSuperAdmin()) {
            abort(403, 'Akses ditolak: Super Admin hanya memiliki akses baca pada modul peminjaman.');
        }

        $validated = $request->validate([
            'bukti_refund' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'bank_pengirim' => ['nullable', 'string', 'max:100'],
            'norek_pengirim' => ['nullable', 'string', 'max:100'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ], [
            'bukti_refund.required' => 'Unggah berkas bukti transfer refund pengembalian dana.',
            'bukti_refund.image' => 'Berkas bukti transfer refund harus berupa file gambar.',
            'bukti_refund.mimes' => 'Format berkas bukti transfer refund harus berupa gambar (JPG, JPEG, PNG, atau WEBP).',
            'bukti_refund.max' => 'Ukuran berkas bukti transfer maksimal 3MB.',
        ]);

        $pembayaran = $peminjaman->pembayaran;
        if (! $pembayaran || $pembayaran->status_pembayaran !== 'refund_pending') {
            return back()->with('error', 'Status tagihan tidak dalam kondisi Refund Pending.');
        }

        $detailRefund = $pembayaran->details()->where('tipe_pembayaran', 'refund')->first();
        if (! $detailRefund) {
            return back()->with('error', 'Data rekening pengembalian dana pemohon belum ditemukan.');
        }

        $filePath = $request->file('bukti_refund')->store('bukti_refund', 'public');

        $detailRefund->update([
            'bukti_pembayaran' => $filePath,
            'bank_pengirim' => $validated['bank_pengirim'] ?? 'Kas/Bank Sekolah',
            'norek_pengirim' => $validated['norek_pengirim'] ?? null,
            'tanggal_bayar' => now(),
            'diverifikasi_oleh' => auth()->id(),
            'diverifikasi_pada' => now(),
            'catatan' => $validated['catatan'] ?? 'Admin telah mentransfer dana refund ke rekening pemohon. Menunggu konfirmasi pemohon.',
        ]);

        return redirect()->route('admin.peminjaman.show', $peminjaman->id)
            ->with('success', 'Bukti transfer pengembalian dana (refund) berhasil diunggah. Menunggu konfirmasi penerimaan dana dari pemohon.');
    }

    /**
     * Aksi Pembatalan Pengajuan oleh Admin Aula:
     * - Admin dapat membatalkan pengajuan atas permintaan pemohon atau alasan operasional.
     * - Mengikuti aturan: jika pembayaran telah diverifikasi valid, maka dialihkan ke refund_pending.
     *   Jika belum diverifikasi atau tidak ada pembayaran, refund tidak diproses.
     */
    public function cancel(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        if ($request->user()?->isSuperAdmin()) {
            abort(403, 'Akses ditolak: Super Admin hanya memiliki akses baca pada modul peminjaman.');
        }

        if (in_array($peminjaman->status, ['cancelled', 'rejected'])) {
            return back()->with('error', 'Pengajuan peminjaman ini sudah dibatalkan atau ditolak sebelumnya.');
        }

        $validated = $request->validate([
            'alasan_pembatalan' => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'alasan_pembatalan.required' => 'Keterangan atau alasan pembatalan pengajuan wajib diisi.',
            'alasan_pembatalan.min' => 'Alasan pembatalan minimal 5 karakter.',
        ]);

        $message = DB::transaction(function () use ($peminjaman, $validated) {
            $peminjaman->update([
                'status' => 'cancelled',
                'catatan' => $validated['alasan_pembatalan'],
            ]);

            Persetujuan::create([
                'peminjaman_id' => $peminjaman->id,
                'approver_id' => auth()->id(),
                'level' => 'admin',
                'status' => 'rejected',
                'catatan_approval' => 'Pengajuan dibatalkan oleh admin. Alasan: '.$validated['alasan_pembatalan'],
                'tanggal_proses' => now(),
            ]);

            $isEligibleRefund = $peminjaman->isEligibleForRefund();
            $hMin = $peminjaman->hari_maksimal_cancel;

            $pembayaran = $peminjaman->pembayaran;
            if (! $pembayaran) {
                return 'Pengajuan peminjaman aula berhasil dibatalkan.';
            }

            // Cek nominal pembayaran yang sudah terverifikasi benar (valid)
            $nominalTerbayar = (float) $pembayaran->total_terbayar;
            if ($nominalTerbayar <= 0) {
                $nominalTerbayar = (float) $pembayaran->details()
                    ->where('status', 'verified')
                    ->where('tipe_pembayaran', '!=', 'refund')
                    ->sum('jumlah_bayar');
            }

            // Jika pembatalan melebihi offset cancelation: dana tidak dapat direfund (hangus)
            if (! $isEligibleRefund) {
                $pembayaran->update([
                    'status_pembayaran' => 'hangus',
                    'total_refund' => 0,
                    'sisa_tagihan' => 0,
                    'catatan' => 'Pengajuan dibatalkan oleh admin melebihi batas offset pembatalan (H-'.$hMin.'). Dana pembayaran tidak dapat direfund (dana hangus). Alasan: '.$validated['alasan_pembatalan'],
                ]);

                return 'Pengajuan berhasil dibatalkan. Karena pembatalan melebihi offset pembatalan (H-'.$hMin.'), dana pembayaran tidak dapat direfund (status: Hangus).';
            }

            // Skenario 1: Pembayaran sudah diverifikasi benar dalam batas toleransi -> lanjut ke refund
            if ($nominalTerbayar > 0) {
                $pembayaran->update([
                    'status_pembayaran' => 'refund_pending',
                    'total_refund' => $nominalTerbayar,
                    'sisa_tagihan' => 0,
                    'catatan' => 'Pengajuan dibatalkan oleh admin dalam batas H-'.$hMin.'. Alasan: '.$validated['alasan_pembatalan'].'. Menunggu refund dana sebesar Rp '.number_format($nominalTerbayar, 0, ',', '.'),
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

                return 'Pengajuan berhasil dibatalkan. Karena pembayaran telah diverifikasi valid sebesar Rp '.number_format($nominalTerbayar, 0, ',', '.').', status pembayaran diubah menjadi Refund Pending untuk melanjutkan pengembalian dana.';
            }

            // Skenario 2: Terdapat bukti pembayaran yang masih pending verifikasi
            $hasPending = $pembayaran->details()
                ->where('status', 'pending')
                ->where('tipe_pembayaran', '!=', 'refund')
                ->exists();

            if ($hasPending) {
                $pembayaran->update([
                    'sisa_tagihan' => 0,
                    'catatan' => 'Pengajuan dibatalkan oleh admin. Alasan: '.$validated['alasan_pembatalan'].'. Terdapat bukti pembayaran pending yang perlu diverifikasi sebelum refund diproses.',
                ]);

                return 'Pengajuan berhasil dibatalkan. Harap verifikasi bukti pembayaran pemohon terlebih dahulu untuk memproses refund jika valid.';
            }

            // Skenario 3: Belum ada pembayaran
            $pembayaran->update([
                'status_pembayaran' => 'rejected',
                'total_refund' => 0,
                'sisa_tagihan' => 0,
                'catatan' => 'Pengajuan dibatalkan oleh admin. Alasan: '.$validated['alasan_pembatalan'],
            ]);

            return 'Pengajuan peminjaman aula berhasil dibatalkan.';
        });

        return redirect()->route('admin.peminjaman.show', $peminjaman->id)
            ->with('success', $message);
    }
}
