<?php

namespace App\Http\Controllers;

use App\Models\DetailPembayaran;
use App\Models\Facility;
use App\Models\PaketPeminjaman;
use App\Models\PaymentConfiguration;
use App\Models\Pembayaran;
use App\Models\Peminjaman;
use App\Models\Persetujuan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $startOfMonth = $calendarDate->copy()->startOfMonth();
        $endOfMonth = $calendarDate->copy()->endOfMonth();

        // Ambil HANYA peminjaman yang SUDAH DISETUJUI (approved_1 atau approved_final) untuk menandai tanggal terpakai
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
                        'paket' => $item->paketPeminjaman?->nama_paket ?? 'Paket Aula',
                        'jam_mulai' => $startDate->format('H:i'),
                        'jam_selesai' => $endDate->format('H:i'),
                        'status' => $item->status,
                    ];
                }
                $current->addDay();
            }
        }

        return view('Admin.peminjaman.customerPanel.dashboard', compact(
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

        return view('Admin.peminjaman.customerPanel.paket', compact(
            'user',
            'pakets',
            'paketUnggulan',
            'paketLainnya'
        ));
    }

    /**
     * Halaman Formulir Pengajuan Peminjaman Aula:
     * - Eager load facilities & details untuk paket terpilih (ramah N+1 query)
     * - Form isian: Paket, Nama Pemohon, Email Instansi, Jadwal Mulai & Selesai, Catatan, dan Surat Pengantar
     */
    public function peminjamanCreate(Request $request): View
    {
        $user = $this->getCurrentUser();
        $selectedPaketId = $request->query('paket_id');

        // Eager load facilities & details agar bebas N+1 query
        $pakets = PaketPeminjaman::with(['facilities', 'details'])
            ->orderBy('harga', 'asc')
            ->get();

        $selectedPaket = $pakets->firstWhere('id', (int) $selectedPaketId) ?? $pakets->first();
        $paymentConfig = PaymentConfiguration::current();

        $approvedBookings = Peminjaman::query()
            ->where(function ($q) {
                $q->whereIn('status', ['approved_1', 'approved_final'])
                    ->orWhereHas('persetujuans', function ($sq) {
                        $sq->where('status', 'approved');
                    });
            })
            ->whereNotIn('status', ['rejected', 'cancelled'])
            ->where('tanggal_selesai', '>=', now())
            ->orderBy('tanggal_mulai', 'asc')
            ->take(6)
            ->get();

        $facilities = Facility::orderBy('judul', 'asc')->get();
        $isCustom = $request->query('custom') == '1' || $request->query('paket_id') === 'custom';

        return view('Admin.peminjaman.customerPanel.pengajuan', compact(
            'user',
            'pakets',
            'selectedPaket',
            'paymentConfig',
            'approvedBookings',
            'facilities',
            'isCustom'
        ));
    }

    /**
     * Simpan pengajuan peminjaman aula baru:
     * - Validasi form berdasarkan skema migrasi peminjamans
     * - Cek ketersediaan jadwal aula agar tidak bentrok
     * - Upload surat pengantar (jika ada)
     * - Inisialisasi tagihan pembayaran & tenggat jatuh tempo DP
     */
    public function peminjamanStore(Request $request): RedirectResponse
    {
        $user = $this->getCurrentUser();
        $isCustom = $request->boolean('is_custom') || $request->input('is_custom') === '1' || $request->input('paket_peminjaman_id') === 'custom';

        $rules = [
            'nama' => ['required', 'string', 'max:150'],
            'email_instansi' => ['required', 'email', 'max:150'],
            'tanggal_mulai' => ['required', 'date', 'after_or_equal:today'],
            'tanggal_selesai' => ['required', 'date', 'after:tanggal_mulai'],
            'catatan' => ['nullable', 'string', 'max:2000'],
            'surat_pengantar' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:5120'],
        ];

        if ($isCustom) {
            $rules['facility_ids'] = ['required', 'array', 'min:1'];
            $rules['facility_ids.*'] = ['exists:facilities,id'];
            $rules['paket_peminjaman_id'] = ['nullable'];
        } else {
            $rules['paket_peminjaman_id'] = ['required', 'exists:paket_peminjamans,id'];
            $rules['facility_ids'] = ['nullable', 'array'];
        }

        $validated = $request->validate($rules, [
            'paket_peminjaman_id.required' => 'Pilih salah satu paket peminjaman aula.',
            'paket_peminjaman_id.exists' => 'Paket peminjaman yang dipilih tidak valid.',
            'facility_ids.required' => 'Pilih minimal satu fasilitas aula untuk paket custom.',
            'facility_ids.min' => 'Pilih minimal satu fasilitas aula untuk paket custom.',
            'facility_ids.*.exists' => 'Fasilitas aula yang dipilih tidak valid.',
            'nama.required' => 'Nama pemohon atau penanggung jawab wajib diisi.',
            'email_instansi.required' => 'Email instansi pemohon wajib diisi.',
            'tanggal_mulai.required' => 'Waktu mulai peminjaman aula wajib diisi.',
            'tanggal_mulai.after_or_equal' => 'Waktu mulai tidak boleh mendahului hari ini.',
            'tanggal_selesai.required' => 'Waktu selesai peminjaman aula wajib diisi.',
            'tanggal_selesai.after' => 'Waktu selesai harus setelah waktu mulai peminjaman.',
            'surat_pengantar.mimes' => 'Format berkas surat pengantar harus PDF, JPG, PNG, atau DOC/DOCX.',
            'surat_pengantar.max' => 'Ukuran berkas surat pengantar maksimal 5MB.',
        ]);

        $start = Carbon::parse($validated['tanggal_mulai']);
        $end = Carbon::parse($validated['tanggal_selesai']);

        // Validasi selisih waktu dinamis peminjaman sesuai konfigurasi (minimal_hari_booking)
        $config = PaymentConfiguration::current();
        $minHari = (int) ($config->minimal_hari_booking ?? 3);
        $earliestAllowedDate = now()->startOfDay()->addDays($minHari);

        if ($start->lt($earliestAllowedDate)) {
            return back()->withInput()->withErrors([
                'tanggal_mulai' => "Pemesanan aula minimal dilakukan {$minHari} hari sebelum tanggal pelaksanaan acara. Tanggal peminjaman paling awal yang dapat dipilih adalah ".$earliestAllowedDate->translatedFormat('d F Y').'.',
            ]);
        }

        // Pastikan waktu mulai peminjaman lebih besar dari batas waktu pelunasan sebelum hari H
        if ($start->copy()->subHours((int) $config->jatuh_tempo_pelunasan_jam)->lte(now())) {
            return back()->withInput()->withErrors([
                'tanggal_mulai' => "Waktu mulai peminjaman harus lebih dari {$config->jatuh_tempo_pelunasan_jam} jam dari sekarang untuk memenuhi tenggat waktu pembayaran final.",
            ]);
        }

        // Cek apakah ada jadwal aula yang bentrok dengan peminjaman yang sudah disetujui (approved)
        $conflictingPeminjaman = Peminjaman::query()
            ->where(function ($q) {
                $q->whereIn('status', ['approved_1', 'approved_final'])
                    ->orWhereHas('persetujuans', function ($sq) {
                        $sq->where('status', 'approved');
                    });
            })
            ->whereNotIn('status', ['rejected', 'cancelled'])
            ->where(function ($query) use ($start, $end) {
                $query->where('tanggal_mulai', '<', $end)
                    ->where('tanggal_selesai', '>', $start);
            })
            ->first();

        if ($conflictingPeminjaman) {
            $conflictStart = Carbon::parse($conflictingPeminjaman->tanggal_mulai)->translatedFormat('d F Y H:i');
            $conflictEnd = Carbon::parse($conflictingPeminjaman->tanggal_selesai)->translatedFormat('d F Y H:i');

            return back()->withInput()->withErrors([
                'tanggal_mulai' => "Pengajuan gagal: Jadwal aula bentrok dengan peminjaman yang sudah disetujui ({$conflictStart} s/d {$conflictEnd} WIB). Silakan pilih jadwal lain.",
            ]);
        }

        $suratPath = null;
        if ($request->hasFile('surat_pengantar')) {
            $suratPath = $request->file('surat_pengantar')->store('surat_pengantar', 'public');
        }

        $pembayaran = DB::transaction(function () use ($validated, $suratPath, $isCustom, $config) {
            $jatuhTempoPelunasan = Carbon::parse($validated['tanggal_mulai'])->subHours((int) $config->jatuh_tempo_pelunasan_jam);

            if ($isCustom) {
                $peminjaman = Peminjaman::create([
                    'paket_peminjaman_id' => null,
                    'is_custom' => true,
                    'harga_custom' => null,
                    'nama' => $validated['nama'],
                    'email_instansi' => $validated['email_instansi'],
                    'tanggal_mulai' => $validated['tanggal_mulai'],
                    'tanggal_selesai' => $validated['tanggal_selesai'],
                    'catatan' => $validated['catatan'] ?? null,
                    'surat_pengantar' => $suratPath,
                    'status' => 'pending',
                ]);

                if (! empty($validated['facility_ids'])) {
                    $peminjaman->facilities()->sync($validated['facility_ids']);
                }

                return Pembayaran::create([
                    'peminjaman_id' => $peminjaman->id,
                    'kode_pembayaran' => 'INV-'.date('Ym').'-'.str_pad((string) $peminjaman->id, 4, '0', STR_PAD_LEFT),
                    'total_tagihan' => 0,
                    'total_terbayar' => 0,
                    'total_refund' => 0,
                    'sisa_tagihan' => 0,
                    'status_pembayaran' => 'pending',
                    'jatuh_tempo_dp' => null,
                    'jatuh_tempo_pelunasan' => $jatuhTempoPelunasan,
                    'catatan' => 'Pengajuan Paket Custom - Menunggu verifikasi fasilitas & penetapan harga oleh Admin Aula',
                ]);
            }

            $paket = PaketPeminjaman::findOrFail($validated['paket_peminjaman_id']);

            $peminjaman = Peminjaman::create([
                'paket_peminjaman_id' => $paket->id,
                'is_custom' => false,
                'harga_custom' => null,
                'nama' => $validated['nama'],
                'email_instansi' => $validated['email_instansi'],
                'tanggal_mulai' => $validated['tanggal_mulai'],
                'tanggal_selesai' => $validated['tanggal_selesai'],
                'catatan' => $validated['catatan'] ?? null,
                'surat_pengantar' => $suratPath,
                'status' => 'pending',
            ]);

            $jatuhTempoDp = now()->addHours($config->jatuh_tempo_dp_jam);

            return Pembayaran::create([
                'peminjaman_id' => $peminjaman->id,
                'kode_pembayaran' => 'INV-'.date('Ym').'-'.str_pad((string) $peminjaman->id, 4, '0', STR_PAD_LEFT),
                'total_tagihan' => $paket->harga,
                'total_terbayar' => 0,
                'total_refund' => 0,
                'sisa_tagihan' => $paket->harga,
                'status_pembayaran' => 'pending',
                'jatuh_tempo_dp' => $jatuhTempoDp,
                'jatuh_tempo_pelunasan' => $jatuhTempoPelunasan,
                'catatan' => 'Tagihan sewa paket aula '.($paket->nama_paket ?: ucfirst($paket->kategori)),
            ]);
        });

        $successMsg = $isCustom
            ? 'Formulir pengajuan peminjaman aula dengan Paket Custom berhasil diajukan! Admin Aula akan segera memverifikasi fasilitas dan menetapkan harga yang harus dibayar.'
            : 'Formulir pengajuan peminjaman aula berhasil diajukan. Silakan lakukan pembayaran uang muka (DP) atau pembayaran lunas.';

        return redirect()->route('customer.pembayaran.show', $pembayaran->id)
            ->with('success', $successMsg);
    }

    /**
     * Halaman Pembayaran Tagihan Aula:
     * - Countdown waktu tenggang pembayaran DP real-time
     * - Informasi lengkap rekening bank resmi sekolah & QRIS
     * - Pilihan pembayaran: Bayar DP atau Langsung Lunas
     * - Formulir unggah bukti transfer
     * - Eager loaded: bebas N+1 query
     */
    public function pembayaranShow(Pembayaran $pembayaran): View
    {
        Peminjaman::syncExpiredDeadlines();
        $pembayaran->refresh();

        $user = $this->getCurrentUser();

        // Keamanan akses: pastikan peminjam hanya melihat tagihannya sendiri (kecuali admin/super admin)
        if ($user && $user->role !== 'admin' && ! $user->isSuperAdmin()) {
            if ($pembayaran->peminjaman && $pembayaran->peminjaman->email_instansi !== $user->email && $pembayaran->peminjaman->nama !== $user->name) {
                abort(403, 'Anda tidak memiliki hak akses untuk melihat tagihan pembayaran ini.');
            }
        }

        // Eager load seluruh relasi terkait (mencegah N+1 query)
        $pembayaran->load([
            'peminjaman.paketPeminjaman.facilities',
            'peminjaman.facilities',
            'details.diverifikasiOleh',
        ]);

        $config = PaymentConfiguration::current();
        $isCustom = (bool) $pembayaran->peminjaman?->is_custom;
        $paket = $pembayaran->peminjaman?->paketPeminjaman;
        
        $nominalDp = 0;
        if ($isCustom) {
            $nominalDp = (float) $pembayaran->total_tagihan > 0 ? ((float) $pembayaran->total_tagihan * 0.3) : 0;
        } else {
            $nominalDp = ($paket && $paket->harga_dp > 0) ? (float) $paket->harga_dp : ((float) $pembayaran->total_tagihan * 0.3);
        }
        $refundDetail = $pembayaran->details->firstWhere('tipe_pembayaran', 'refund');

        // Pastikan sisa_tagihan bernilai 0 jika peminjaman ditolak, dibatalkan, atau berstatus refund
        if (in_array($pembayaran->status_pembayaran, ['refund_pending', 'refunded', 'rejected', 'hangus']) || in_array($pembayaran->peminjaman?->status, ['rejected', 'cancelled'])) {
            if ($pembayaran->sisa_tagihan > 0) {
                $pembayaran->update(['sisa_tagihan' => 0]);
                $pembayaran->refresh();
            }
        }

        return view('Admin.peminjaman.customerPanel.pembayaran', compact(
            'user',
            'pembayaran',
            'config',
            'paket',
            'nominalDp',
            'refundDetail'
        ));
    }

    /**
     * Kirim bukti pembayaran (DP / Lunas Langsung / Pelunasan):
     */
    public function pembayaranBayar(Request $request, Pembayaran $pembayaran): RedirectResponse
    {
        $user = $this->getCurrentUser();

        if ($user && $user->role !== 'admin' && ! $user->isSuperAdmin()) {
            if ($pembayaran->peminjaman && $pembayaran->peminjaman->email_instansi !== $user->email && $pembayaran->peminjaman->nama !== $user->name) {
                abort(403, 'Akses ditolak.');
            }
        }

        if ($pembayaran->peminjaman?->is_custom && (float) $pembayaran->total_tagihan <= 0) {
            return back()->with('error', 'Tagihan untuk paket custom ini belum ditetapkan oleh Admin Aula. Silakan tunggu hingga admin memverifikasi fasilitas dan menetapkan harga.');
        }

        $validated = $request->validate([
            'tipe_pembayaran' => ['required', 'in:dp,lunas_langsung,pelunasan'],
            'bank_tujuan' => ['required', 'string', 'max:100'],
            'bank_pengirim' => ['required', 'string', 'max:100'],
            'norek_pengirim' => ['required', 'string', 'max:100'],
            'atas_nama_pengirim' => ['required', 'string', 'max:150'],
            'jumlah_bayar' => ['required', 'numeric', 'min:1000'],
            'tanggal_bayar' => ['required', 'date'],
            'bukti_pembayaran' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ], [
            'tipe_pembayaran.required' => 'Pilih jenis pembayaran (DP atau Lunas Langsung).',
            'bank_tujuan.required' => 'Pilih rekening tujuan transfer sekolah.',
            'bank_pengirim.required' => 'Nama bank asal pengirim wajib diisi.',
            'norek_pengirim.required' => 'Nomor rekening pengirim wajib diisi.',
            'atas_nama_pengirim.required' => 'Nama pemilik rekening pengirim wajib diisi.',
            'jumlah_bayar.required' => 'Nominal transfer wajib diisi.',
            'bukti_pembayaran.required' => 'Unggah berkas bukti transfer atau struk pembayaran.',
            'bukti_pembayaran.image' => 'Berkas bukti transfer harus berupa file gambar.',
            'bukti_pembayaran.mimes' => 'Format berkas bukti transfer harus berupa gambar (JPG, JPEG, PNG, atau WEBP).',
            'bukti_pembayaran.max' => 'Ukuran berkas bukti transfer maksimal 3MB.',
        ]);

        $buktiPath = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');

        DB::transaction(function () use ($pembayaran, $validated, $buktiPath) {
            $prefix = match ($validated['tipe_pembayaran']) {
                'dp' => 'DP',
                'pelunasan' => 'PLN',
                'lunas_langsung' => 'LNS',
                default => 'TRX',
            };

            $detailCount = $pembayaran->details()->count() + 1;
            $kodeTransaksi = 'TRX-'.$prefix.'-'.date('Ym').'-'.str_pad((string) $detailCount, 3, '0', STR_PAD_LEFT);

            // Norek tujuan otomatis dicocokkan
            $norekTujuan = null;
            $config = PaymentConfiguration::current();
            if ($validated['bank_tujuan'] === $config->bank_utama) {
                $norekTujuan = $config->norek_utama;
            } elseif ($validated['bank_tujuan'] === $config->bank_alternatif_1) {
                $norekTujuan = $config->norek_alternatif_1;
            } elseif ($validated['bank_tujuan'] === $config->bank_alternatif_2) {
                $norekTujuan = $config->norek_alternatif_2;
            } elseif (str_contains(strtolower($validated['bank_tujuan']), 'qris')) {
                $norekTujuan = $config->qris_merchant;
            }

            DetailPembayaran::create([
                'pembayaran_id' => $pembayaran->id,
                'kode_transaksi' => $kodeTransaksi,
                'tipe_pembayaran' => $validated['tipe_pembayaran'],
                'jumlah_bayar' => $validated['jumlah_bayar'],
                'metode' => str_contains(strtolower($validated['bank_tujuan']), 'cash') ? 'cash' : 'transfer',
                'bank_tujuan' => $validated['bank_tujuan'],
                'norek_tujuan' => $norekTujuan,
                'bank_pengirim' => $validated['bank_pengirim'],
                'norek_pengirim' => $validated['norek_pengirim'],
                'atas_nama_pengirim' => $validated['atas_nama_pengirim'],
                'bukti_pembayaran' => $buktiPath,
                'tanggal_bayar' => $validated['tanggal_bayar'],
                'status' => 'pending',
                'catatan' => $validated['catatan'] ?? null,
            ]);

            // Jika peminjaman masih draft, ubah status ke pending
            if ($pembayaran->peminjaman && $pembayaran->peminjaman->status === 'draft') {
                $pembayaran->peminjaman->update(['status' => 'pending']);
            }
        });

        return redirect()->route('customer.pembayaran.show', $pembayaran->id)
            ->with('success', 'Bukti pembayaran berhasil diunggah! Pihak sekolah akan segera memverifikasi transaksi Anda.');
    }

    /**
     * Halaman Daftar Peminjaman / Cek Peminjaman:
     * - Riwayat peminjaman milik user
     * - Bebas N+1 query dengan pre-loading facilities & details
     */
    public function riwayat(): View
    {
        Peminjaman::syncExpiredDeadlines();

        $user = $this->getCurrentUser();

        $query = Peminjaman::with([
            'paketPeminjaman.facilities',
            'facilities',
            'persetujuans.approver',
            'pembayaran.details.diverifikasiOleh',
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
        $completedPembayarans = [];

        foreach ($peminjamans as $pem) {
            if ($pem->pembayaran) {
                if ($pem->pembayaran->status_pembayaran !== 'lunas' && $pem->pembayaran->status_pembayaran !== 'free') {
                    $hasUnpaid = true;
                    $activePembayarans[] = $pem->pembayaran;
                } else {
                    $completedPembayarans[] = $pem->pembayaran;
                }
            }
        }

        $config = PaymentConfiguration::current();

        return view('Admin.peminjaman.customerPanel.riwayat', compact(
            'user',
            'peminjamans',
            'hasUnpaid',
            'activePembayarans',
            'completedPembayarans',
            'config'
        ));
    }

    /**
     * Pemohon Mengisi Rekening untuk Pengembalian Dana (Refund):
     * Kolom pada tabel detail_pembayarans digunakan untuk mencatat rekening pemohon.
     */
    public function simpanRekeningRefund(Request $request, Pembayaran $pembayaran): RedirectResponse
    {
        $user = $this->getCurrentUser();

        if ($user && $user->role !== 'admin' && ! $user->isSuperAdmin()) {
            if ($pembayaran->peminjaman && $pembayaran->peminjaman->email_instansi !== $user->email && $pembayaran->peminjaman->nama !== $user->name) {
                abort(403, 'Akses ditolak.');
            }
        }

        $validated = $request->validate([
            'bank_tujuan' => ['required', 'string', 'max:100'],
            'norek_tujuan' => ['required', 'string', 'max:100'],
            'atas_nama_pengirim' => ['required', 'string', 'max:150'],
        ], [
            'bank_tujuan.required' => 'Nama bank tujuan pengembalian dana wajib diisi.',
            'norek_tujuan.required' => 'Nomor rekening pengembalian dana wajib diisi.',
            'atas_nama_pengirim.required' => 'Nama lengkap pemilik rekening wajib diisi.',
        ]);

        $detailRefund = $pembayaran->details()->firstOrNew(['tipe_pembayaran' => 'refund']);
        $detailRefund->kode_transaksi = $detailRefund->kode_transaksi ?: 'TRX-RFD-'.date('Ym').'-'.str_pad((string) $pembayaran->id, 3, '0', STR_PAD_LEFT);
        $detailRefund->jumlah_bayar = $pembayaran->total_refund ?: $pembayaran->total_terbayar;
        $detailRefund->metode = 'transfer';
        $detailRefund->bank_tujuan = $validated['bank_tujuan'];
        $detailRefund->norek_tujuan = $validated['norek_tujuan'];
        $detailRefund->atas_nama_pengirim = $validated['atas_nama_pengirim'];
        $detailRefund->status = 'pending';
        $detailRefund->catatan = 'Data rekening refund telah dilengkapi pemohon. Menunggu admin mentransfer dana pengembalian.';
        $detailRefund->save();

        return redirect()->route('customer.pembayaran.show', $pembayaran->id)
            ->with('success', 'Data rekening pengembalian dana berhasil disimpan! Pihak sekolah akan segera memproses transfer pengembalian dana.');
    }

    /**
     * Pemohon Mengonfirmasi Penerimaan Dana Refund:
     * Mengubah status pembayaran menjadi refunded dan detail refund menjadi verified.
     */
    public function konfirmasiRefund(Request $request, Pembayaran $pembayaran): RedirectResponse
    {
        $user = $this->getCurrentUser();

        if ($user && $user->role !== 'admin' && ! $user->isSuperAdmin()) {
            if ($pembayaran->peminjaman && $pembayaran->peminjaman->email_instansi !== $user->email && $pembayaran->peminjaman->nama !== $user->name) {
                abort(403, 'Akses ditolak.');
            }
        }

        $detailRefund = $pembayaran->details()->where('tipe_pembayaran', 'refund')->first();
        if ($detailRefund) {
            $detailRefund->update([
                'status' => 'verified',
                'diverifikasi_pada' => now(),
            ]);
        }

        $pembayaran->update([
            'status_pembayaran' => 'refunded',
            'catatan' => trim(($pembayaran->catatan ? $pembayaran->catatan.' | ' : '').'Dana refund telah dikonfirmasi diterima oleh pemohon pada '.now()->format('d/m/Y H:i').' WIB.'),
        ]);

        return redirect()->route('customer.pembayaran.show', $pembayaran->id)
            ->with('success', 'Terima kasih atas konfirmasi Anda. Pengembalian dana telah selesai (Status: Refunded).');
    }

    /**
     * Pemohon Membatalkan Pengajuan Peminjaman Aula:
     * - Pelanggan dapat membatalkan pengajuan kapan saja (selama acara belum selesai).
     * - Namun, jika pembatalan dilakukan melebihi batas offset cancelation (H-offset),
     *   dana pembayaran tidak akan dapat direfund (status pembayaran hangus).
     * - Jika pembatalan dilakukan sebelum atau tepat pada batas offset cancelation,
     *   dana pembayaran yang telah diverifikasi dapat direfund (status refund_pending).
     */
    public function peminjamanCancel(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        $user = $this->getCurrentUser();

        // 1. Validasi hak akses kepemilikan data peminjaman
        if ($user && $user->role !== 'admin' && ! $user->isSuperAdmin()) {
            if ($peminjaman->email_instansi !== $user->email && $peminjaman->nama !== $user->name) {
                abort(403, 'Akses ditolak: Anda tidak memiliki izin untuk membatalkan pengajuan ini.');
            }
        }

        // 2. Validasi status peminjaman saat ini
        if (in_array($peminjaman->status, ['cancelled', 'rejected'])) {
            return back()->with('error', 'Pengajuan peminjaman ini sudah dibatalkan atau ditolak sebelumnya.');
        }

        // 3. Validasi apakah masih dapat dibatalkan (acara belum selesai)
        if (! $peminjaman->canBeCancelled()) {
            return back()->with('error', 'Pengajuan peminjaman aula tidak dapat dibatalkan karena waktu pelaksanaan acara telah selesai.');
        }

        $request->validate([
            'alasan_pembatalan' => ['nullable', 'string', 'max:1000'],
        ]);

        $alasan = $request->input('alasan_pembatalan', 'Pengajuan dibatalkan oleh pemohon.');

        $statusMsg = DB::transaction(function () use ($peminjaman, $alasan) {
            $isEligibleRefund = $peminjaman->isEligibleForRefund();
            $hMin = $peminjaman->hari_maksimal_cancel;

            // Update status peminjaman menjadi cancelled
            $peminjaman->update([
                'status' => 'cancelled',
                'catatan' => $alasan,
            ]);

            // Catat riwayat persetujuan
            Persetujuan::create([
                'peminjaman_id' => $peminjaman->id,
                'approver_id' => auth()->id(),
                'level' => 'admin',
                'status' => 'rejected',
                'catatan_approval' => 'Pengajuan dibatalkan oleh pemohon.'.($isEligibleRefund ? " (Dalam batas refund H-{$hMin})" : " (Melebihi offset pembatalan H-{$hMin}, dana tidak dapat direfund)").'. Alasan: '.$alasan,
                'tanggal_proses' => now(),
            ]);

            $pembayaran = $peminjaman->pembayaran;
            if (! $pembayaran) {
                return 'Pengajuan peminjaman aula berhasil dibatalkan.';
            }

            // Hitung nominal pembayaran yang SUDAH diverifikasi benar (valid) oleh admin
            $nominalTerverifikasi = (float) $pembayaran->total_terbayar;
            if ($nominalTerverifikasi <= 0) {
                $nominalTerverifikasi = (float) $pembayaran->details()
                    ->where('status', 'verified')
                    ->where('tipe_pembayaran', '!=', 'refund')
                    ->sum('jumlah_bayar');
            }

            // Cek apakah ada bukti pembayaran yang masih pending verifikasi admin
            $hasPendingPayment = $pembayaran->details()
                ->where('status', 'pending')
                ->where('tipe_pembayaran', '!=', 'refund')
                ->exists();

            // KASUS 1: Pembatalan MELEBIHI batas offset cancelation -> DANA TIDAK DAPAT DIREUND (HANGUS)
            if (! $isEligibleRefund) {
                $pembayaran->update([
                    'status_pembayaran' => 'hangus',
                    'total_refund' => 0,
                    'sisa_tagihan' => 0,
                    'catatan' => 'Pengajuan dibatalkan oleh pemohon melebihi batas offset pembatalan (H-'.$hMin.'). Pembayaran sebesar Rp '.number_format($nominalTerverifikasi, 0, ',', '.').' tidak dapat dikembalikan (dana hangus). Alasan: '.$alasan,
                ]);

                if ($nominalTerverifikasi > 0) {
                    return 'Pengajuan peminjaman aula berhasil dibatalkan. Namun, karena pembatalan dilakukan melebihi batas toleransi pembatalan (maksimal H-'.$hMin.'), dana pembayaran sebesar Rp '.number_format($nominalTerverifikasi, 0, ',', '.').' tidak dapat dikembalikan (hangus).';
                }

                return 'Pengajuan peminjaman aula berhasil dibatalkan. Karena pembatalan melebihi batas toleransi H-'.$hMin.', status tagihan dihentikan (hangus).';
            }

            // KASUS 2: Pembatalan DALAM BATAS offset cancelation -> BERHAK REFUND
            if ($nominalTerverifikasi > 0) {
                // Pembayaran SUDAH terverifikasi valid -> lanjut ke alur refund pending
                $pembayaran->update([
                    'status_pembayaran' => 'refund_pending',
                    'total_refund' => $nominalTerverifikasi,
                    'sisa_tagihan' => 0,
                    'catatan' => 'Pengajuan dibatalkan oleh pemohon dalam batas H-'.$hMin.'. Alasan: '.$alasan.'. Pembayaran terverifikasi sebesar Rp '.number_format($nominalTerverifikasi, 0, ',', '.').' akan dikembalikan (refund) oleh admin.',
                ]);

                // Siapkan data transaksi refund
                $pembayaran->details()->firstOrCreate(
                    [
                        'pembayaran_id' => $pembayaran->id,
                        'tipe_pembayaran' => 'refund',
                    ],
                    [
                        'kode_transaksi' => 'TRX-RFD-'.date('Ym').'-'.str_pad((string) $pembayaran->id, 3, '0', STR_PAD_LEFT),
                        'jumlah_bayar' => $nominalTerverifikasi,
                        'metode' => 'transfer',
                        'status' => 'pending',
                        'catatan' => 'Menunggu pemohon melengkapi data rekening pengembalian dana (refund).',
                    ]
                );

                return 'Pengajuan peminjaman berhasil dibatalkan. Karena pembayaran telah diverifikasi valid sebesar Rp '.number_format($nominalTerverifikasi, 0, ',', '.').' dan dilakukan dalam batas waktu H-'.$hMin.', status pembayaran menjadi Refund Pending. Silakan lengkapi nomor rekening pengembalian dana agar admin dapat mentransfer refund.';
            } elseif ($hasPendingPayment) {
                // Ada bukti pembayaran yang belum diverifikasi oleh admin
                $pembayaran->update([
                    'sisa_tagihan' => 0,
                    'catatan' => 'Pengajuan dibatalkan oleh pemohon dalam batas H-'.$hMin.'. Alasan: '.$alasan.'. Bukti pembayaran sedang menunggu verifikasi admin sebelum proses refund dapat diproses.',
                ]);

                return 'Pengajuan peminjaman aula berhasil dibatalkan. Bukti pembayaran Anda sedang menunggu verifikasi admin. Jika pembayaran diverifikasi valid, dana akan dikembalikan (refund) oleh admin.';
            } else {
                // Belum ada pembayaran
                $pembayaran->update([
                    'status_pembayaran' => 'rejected',
                    'total_refund' => 0,
                    'sisa_tagihan' => 0,
                    'catatan' => 'Pengajuan dibatalkan oleh pemohon sebelum pembayaran dilakukan. Alasan: '.$alasan,
                ]);

                return 'Pengajuan peminjaman aula berhasil dibatalkan.';
            }
        });

        return back()->with('success', $statusMsg);
    }

    /**
     * Halaman Profil Peminjam / User
     */
    public function profil(): View
    {
        $user = $this->getCurrentUser();

        return view('Admin.peminjaman.customerPanel.profil', compact('user'));
    }
}
