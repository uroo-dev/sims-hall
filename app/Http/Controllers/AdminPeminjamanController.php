<?php

namespace App\Http\Controllers;

use App\Models\PaymentConfiguration;
use App\Models\Peminjaman;
use App\Models\Persetujuan;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            'rejected' => Peminjaman::where('status', 'rejected')->count(),
            'refund_pending' => Peminjaman::whereHas('pembayaran', function ($q) {
                $q->where('status_pembayaran', 'refund_pending');
            })->count(),
        ];

        return view('Admin.peminjaman.index', compact('peminjamans', 'stats'));
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
        if (! in_array($peminjaman->status, ['approved_1', 'approved_final', 'rejected'])) {
            $conflictingApproved = Peminjaman::query()
                ->where('id', '!=', $peminjaman->id)
                ->where(function ($q) {
                    $q->whereIn('status', ['approved_1', 'approved_final'])
                        ->orWhereHas('persetujuans', function ($sq) {
                            $sq->where('status', 'approved');
                        });
                })
                ->where('status', '!=', 'rejected')
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
        $request->validate([
            'catatan_approval' => ['nullable', 'string', 'max:1000'],
        ]);

        // Validasi: Cegah approval jika jadwal bentrok dengan peminjaman lain yang sudah disetujui
        $conflictingPeminjaman = Peminjaman::query()
            ->where('id', '!=', $peminjaman->id)
            ->where(function ($q) {
                $q->whereIn('status', ['approved_1', 'approved_final'])
                    ->orWhereHas('persetujuans', function ($sq) {
                        $sq->where('status', 'approved');
                    });
            })
            ->where('status', '!=', 'rejected')
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

            // Jika ada bukti pembayaran yang pending, otomatis verifikasi
            if ($peminjaman->pembayaran) {
                $pendingDetails = $peminjaman->pembayaran->details()
                    ->where('status', 'pending')
                    ->where('tipe_pembayaran', '!=', 'refund')
                    ->get();

                foreach ($pendingDetails as $detail) {
                    $detail->update([
                        'status' => 'verified',
                        'diverifikasi_oleh' => auth()->id(),
                        'diverifikasi_pada' => now(),
                    ]);
                }

                $peminjaman->pembayaran->syncAkumulasiPembayaran();
                $pembayaran = $peminjaman->pembayaran->fresh();

                // Hitung tenggat waktu pembayaran final (pelunasan) setelah approve / pembayaran pertama (DP):
                // Sesuai konfigurasi pembayaran, jatuh tempo pelunasan adalah X jam sebelum hari H (tanggal_mulai)
                if ($pembayaran && ($pembayaran->status_pembayaran === 'partial' || ($pembayaran->sisa_tagihan > 0 && $pembayaran->total_terbayar > 0))) {
                    $config = PaymentConfiguration::current();
                    $tenggatPelunasan = Carbon::parse($peminjaman->tanggal_mulai)->subHours((int) $config->jatuh_tempo_pelunasan_jam);
                    $pembayaran->update([
                        'jatuh_tempo_pelunasan' => $tenggatPelunasan,
                    ]);
                }
            }
        });

        return redirect()->route('admin.peminjaman.show', $peminjaman->id)
            ->with('success', 'Pengajuan peminjaman aula berhasil disetujui (Approved)! Bukti pembayaran yang masuk telah diverifikasi.');
    }

    /**
     * Aksi Reject Pengajuan oleh Admin:
     * - Wajib memberikan keterangan / alasan penolakan
     * - Jika pemohon sudah melakukan pembayaran yang berhasil / ada uang masuk,
     *   maka status pembayaran diubah menjadi refund_pending dan admin diminta merefund nominal tersebut.
     * - Jika belum ada pembayaran atau pembayaran nol, status pembayaran langsung menjadi rejected.
     */
    public function reject(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        $validated = $request->validate([
            'alasan_penolakan' => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'alasan_penolakan.required' => 'Keterangan atau alasan penolakan permohonan wajib diisi oleh admin.',
            'alasan_penolakan.min' => 'Alasan penolakan permohonan minimal 5 karakter.',
        ]);

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

            // Hitung nominal yang sudah dibayar oleh pemohon (uang masuk)
            $nominalTerbayar = (float) $pembayaran->total_terbayar;
            if ($nominalTerbayar <= 0) {
                // Cek apakah ada bukti pembayaran yang masuk (status verified atau pending)
                $nominalTerbayar = (float) $pembayaran->details()
                    ->whereIn('status', ['verified', 'pending'])
                    ->whereIn('tipe_pembayaran', ['dp', 'lunas_langsung', 'pelunasan'])
                    ->sum('jumlah_bayar');
            }

            // Skenario A: Ada pembayaran berhasil / uang masuk -> refund_pending
            if ($nominalTerbayar > 0) {
                $pembayaran->update([
                    'status_pembayaran' => 'refund_pending',
                    'total_refund' => $nominalTerbayar,
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

                return 'Pengajuan berhasil ditolak. Karena pemohon telah membayar sebesar Rp '.number_format($nominalTerbayar, 0, ',', '.').', status pembayaran diubah menjadi Refund Pending. Pemohon diminta mengisi nomor rekening pengembalian dana.';
            }

            // Skenario jika belum ada pembayaran
            $pembayaran->update([
                'status_pembayaran' => 'rejected',
                'catatan' => 'Permohonan ditolak oleh admin. Alasan: '.$validated['alasan_penolakan'],
            ]);

            return 'Pengajuan peminjaman aula berhasil ditolak.';
        });

        return redirect()->route('admin.peminjaman.show', $peminjaman->id)
            ->with('success', $message);
    }

    /**
     * Aksi Reject Pembayaran Deposit (Deposit Gagal / Bukti Palsu):
     * - Admin mengubah status pembayaran menjadi rejected dan wajib mengisi alasan.
     * - Pemohon diminta untuk transfer ulang dengan tenggat waktu baru sesuai konfigurasi pembayaran.
     * - Jika melewati batas waktu tersebut, sistem secara otomatis membatalkan peminjaman menjadi rejected.
     */
    public function rejectPembayaran(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        $validated = $request->validate([
            'alasan_penolakan' => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'alasan_penolakan.required' => 'Keterangan atau alasan penolakan pembayaran wajib diisi oleh admin.',
            'alasan_penolakan.min' => 'Alasan penolakan pembayaran minimal 5 karakter.',
        ]);

        $pembayaran = $peminjaman->pembayaran;
        if (! $pembayaran) {
            return back()->with('error', 'Data tagihan pembayaran peminjaman tidak ditemukan.');
        }

        DB::transaction(function () use ($pembayaran, $validated) {
            // Tolak detail transaksi pembayaran yang aktif / pending
            $detailTerakhir = $pembayaran->details()
                ->whereIn('status', ['pending', 'verified'])
                ->where('tipe_pembayaran', '!=', 'refund')
                ->latest()
                ->first();

            if ($detailTerakhir) {
                $detailTerakhir->update([
                    'status' => 'rejected',
                    'catatan' => $validated['alasan_penolakan'],
                    'diverifikasi_oleh' => auth()->id(),
                    'diverifikasi_pada' => now(),
                ]);
            }

            // Atur batas waktu baru sesuai konfigurasi pembayaran
            $config = PaymentConfiguration::current();
            $jatuhTempoBaru = now()->addHours($config->jatuh_tempo_dp_jam);

            $pembayaran->update([
                'status_pembayaran' => 'rejected',
                'jatuh_tempo_dp' => $jatuhTempoBaru,
                'total_terbayar' => 0,
                'sisa_tagihan' => $pembayaran->total_tagihan,
                'catatan' => 'Bukti pembayaran deposit ditolak admin: '.$validated['alasan_penolakan'].'. Pemohon diminta transfer ulang sebelum '.$jatuhTempoBaru->format('d/m/Y H:i').' WIB.',
            ]);
        });

        return redirect()->route('admin.peminjaman.show', $peminjaman->id)
            ->with('success', 'Bukti pembayaran deposit berhasil ditolak. Status pembayaran diubah menjadi Rejected. Pemohon diberikan tenggat waktu baru untuk mentransfer ulang.');
    }

    /**
     * Aksi Unggah Bukti Transfer Refund oleh Admin:
     * - Admin mentransfer ke rekening pemohon yang telah diisi sebelumnya.
     * - Admin mengunggah struk/bukti transfer pengembalian dana.
     * - Menunggu konfirmasi penerimaan dana dari pemohon untuk mengubah status menjadi refunded.
     */
    public function uploadRefund(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
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
}
