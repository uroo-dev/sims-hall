<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Rekapitulasi Pemasukan Aula - SMKN 2 Karanganyar</title>
    <style>
        @page {
            margin: 10mm 10mm 12mm 10mm;
            size: a4 portrait;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 8.5pt;
            line-height: 1.3;
            color: #1e293b;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }

        /* KOP SURAT RESMI */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }

        .kop-logo {
            width: 75px;
            vertical-align: middle;
            text-align: center;
        }

        .kop-logo img {
            width: 65px;
            height: auto;
        }

        .kop-text {
            text-align: center;
            vertical-align: middle;
            padding-right: 75px; /* Menjaga teks tetap sentris terhadap logo di kiri */
        }

        .kop-instansi-1 {
            font-size: 10.5pt;
            font-weight: bold;
            color: #0f172a;
            letter-spacing: 0.5px;
            margin: 0;
            text-transform: uppercase;
        }

        .kop-instansi-2 {
            font-size: 11pt;
            font-weight: bold;
            color: #0f172a;
            letter-spacing: 0.5px;
            margin: 0;
            text-transform: uppercase;
        }

        .kop-sekolah {
            font-size: 14pt;
            font-weight: 800;
            color: #004f8f;
            letter-spacing: 1px;
            margin: 2px 0;
            text-transform: uppercase;
        }

        .kop-alamat {
            font-size: 8pt;
            color: #475569;
            margin: 1px 0;
        }

        .kop-kontak {
            font-size: 7.8pt;
            color: #475569;
            margin: 1px 0;
        }

        /* GARIS GANDA KOP SURAT */
        .kop-line-thick {
            border-top: 2.5px solid #000000;
            margin-top: 4px;
            margin-bottom: 1.5px;
        }

        .kop-line-thin {
            border-top: 1px solid #000000;
            margin-bottom: 12px;
        }

        /* JUDUL DOKUMEN */
        .doc-title-container {
            text-align: center;
            margin-bottom: 12px;
        }

        .doc-title {
            font-size: 12pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 0 3px 0;
        }

        .doc-subtitle {
            font-size: 8.5pt;
            color: #334155;
            font-weight: 500;
            margin: 0;
        }

        /* METADATA CETAK */
        .meta-table {
            width: 100%;
            margin-bottom: 10px;
            font-size: 8pt;
            color: #475569;
            border-collapse: collapse;
        }

        .meta-table td {
            padding: 2px 0;
        }

        /* TABEL UTAMA LAPORAN PEMINJAMAN */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 6.8pt;
            margin-bottom: 12px;
        }

        .data-table th {
            background-color: #0060ac;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
            padding: 4px 2.5px;
            border: 1px solid #004f8f;
            vertical-align: middle;
        }

        .data-table td {
            padding: 3.5px 2.5px;
            border: 1px solid #cbd5e1;
            vertical-align: middle;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .data-table tbody tr:hover {
            background-color: #f1f5f9;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }

        /* BADGE STATUS */
        .badge {
            display: inline-block;
            padding: 1px 3.5px;
            border-radius: 2.5px;
            font-size: 5.8pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-lunas { background-color: #dcfce7; color: #15803d; border: 0.5px solid #86efac; }
        .badge-partial { background-color: #fef3c7; color: #b45309; border: 0.5px solid #fcd34d; }
        .badge-pending { background-color: #e0f2fe; color: #0369a1; border: 0.5px solid #7dd3fc; }
        .badge-refunded { background-color: #f3e8ff; color: #7e22ce; border: 0.5px solid #d8b4fe; }
        .badge-rejected { background-color: #fee2e2; color: #b91c1c; border: 0.5px solid #fca5a5; }

        /* GRAND TOTAL BARIS */
        .total-row {
            background-color: #e2e8f0 !important;
            font-weight: bold;
            font-size: 8pt;
            color: #0f172a;
        }

        .total-row td {
            border-top: 2px solid #64748b;
            border-bottom: 2px solid #64748b;
        }

        /* BAGIAN PENGESAHAN / TANDA TANGAN */
        .signature-table {
            width: 100%;
            margin-top: 15px;
            border-collapse: collapse;
            page-break-inside: avoid;
        }

        .signature-box {
            width: 50%;
            vertical-align: top;
            text-align: center;
            font-size: 8.5pt;
        }

        .signature-space {
            height: 55px;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
            color: #0f172a;
        }

        .signature-nip {
            font-size: 8pt;
            color: #475569;
        }

        /* FOOTER HALAMAN */
        .footer-note {
            margin-top: 12px;
            font-size: 7pt;
            color: #64748b;
            border-top: 1px dashed #cbd5e1;
            padding-top: 4px;
        }
    </style>
</head>
<body>

    <!-- 1. KOP SURAT RESMI PEMERINTAH & SEKOLAH -->
    <table class="kop-table">
        <tr>
            <td class="kop-logo">
                @if ($logoBase64)
                    <img src="{{ $logoBase64 }}" alt="Logo SMKN 2 Karanganyar">
                @endif
            </td>
            <td class="kop-text">
                <div class="kop-instansi-1">Pemerintah Provinsi Jawa Tengah</div>
                <div class="kop-instansi-2">Dinas Pendidikan dan Kebudayaan</div>
                <div class="kop-sekolah">SMK Negeri 2 Karanganyar</div>
                <div class="kop-alamat">Jl. Yos Sudarso, Jettel, Bejen, Kec. Karanganyar, Kab. Karanganyar, Jawa Tengah 57716</div>
                <div class="kop-kontak">Telepon: (0271) 495048 &nbsp;|&nbsp; Pos-el: smkn2kra@yahoo.co.id &nbsp;|&nbsp; Laman: www.smkn2kra.sch.id</div>
            </td>
        </tr>
    </table>

    <div class="kop-line-thick"></div>
    <div class="kop-line-thin"></div>

    <!-- 2. JUDUL DOKUMEN -->
    <div class="doc-title-container">
        <h1 class="doc-title">Laporan Rekapitulasi Pemasukan Peminjaman Aula</h1>
        <p class="doc-subtitle">
            @if ($filter['tanggal_dari'] && $filter['tanggal_sampai'])
                Periode: {{ \Carbon\Carbon::parse($filter['tanggal_dari'])->locale('id')->isoFormat('D MMMM Y') }} s/d {{ \Carbon\Carbon::parse($filter['tanggal_sampai'])->locale('id')->isoFormat('D MMMM Y') }}
            @elseif ($filter['tanggal_dari'])
                Mulai Tanggal: {{ \Carbon\Carbon::parse($filter['tanggal_dari'])->locale('id')->isoFormat('D MMMM Y') }}
            @elseif ($filter['tanggal_sampai'])
                Hingga Tanggal: {{ \Carbon\Carbon::parse($filter['tanggal_sampai'])->locale('id')->isoFormat('D MMMM Y') }}
            @else
                Periode: Keseluruhan Riwayat Transaksi (Semua Waktu)
            @endif
            &nbsp;&bull;&nbsp;
            Kriteria Filter: 
            {{ $filter['status_pembayaran'] !== 'all' ? 'Status: ' . strtoupper($filter['status_pembayaran']) : 'Semua Status' }}
            ({{ $filter['filter_by'] === 'sewa' ? 'Berdasarkan Tanggal Sewa' : ($filter['filter_by'] === 'pengajuan' ? 'Berdasarkan Tanggal Pengajuan' : 'Berdasarkan Tanggal Transaksi') }})
        </p>
    </div>

    <!-- 3. METADATA PENCETAKAN -->
    <table class="meta-table">
        <tr>
            <td class="text-left" style="width: 50%;">
                <strong>Nomor Dokumen:</strong> LAP-AULA/{{ date('Ym') }}/{{ str_pad((string) count($peminjamans), 4, '0', STR_PAD_LEFT) }}
            </td>
            <td class="text-right" style="width: 50%;">
                <strong>Waktu Cetak:</strong> {{ $printedAt }} &nbsp;|&nbsp; 
                <strong>Operator:</strong> {{ $petugasAdmin->name ?? 'Admin Sistem' }} ({{ ucwords(str_replace('_', ' ', $petugasAdmin->role ?? 'Admin')) }})
            </td>
        </tr>
    </table>

    <!-- 4. TABEL DAFTAR DATA PEMINJAMAN & TRANSAKSI -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 18px;">No</th>
                <th style="width: 58px;">No. Invoice</th>
                <th style="width: 82px;">Pemohon & Instansi</th>
                <th style="width: 60px;">Paket Aula</th>
                <th style="width: 60px;">Jadwal Sewa</th>
                <th style="width: 44px;">Status Sewa</th>
                <th style="width: 50px;">Tagihan</th>
                <th style="width: 50px;">Dana Masuk</th>
                <th style="width: 40px;">Refund</th>
                <th style="width: 50px;">Sisa Tagihan</th>
                <th style="width: 44px;">Status Bayar</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($peminjamans as $index => $item)
                @php
                    $pembayaran = $item->pembayaran;
                    $tagihan = (float) ($pembayaran->total_tagihan ?? 0);
                    $terbayar = (float) ($pembayaran->total_terbayar ?? 0);
                    $refund = (float) ($pembayaran->total_refund ?? 0);
                    $sisa = (float) ($pembayaran->sisa_tagihan ?? 0);
                    $status = $pembayaran->status_pembayaran ?? 'pending';
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center font-bold" style="font-size: 7.2pt; color: #004f8f;">
                        {{ $pembayaran->kode_pembayaran ?? '-' }}
                    </td>
                    <td>
                        <div class="font-bold" style="color: #0f172a;">{{ $item->nama }}</div>
                        <div style="font-size: 7pt; color: #64748b;">{{ $item->email_instansi }}</div>
                    </td>
                    <td>
                        <div class="font-bold">{{ $item->paketPeminjaman->nama_paket ?? 'Paket Kustom' }}</div>
                        <div style="font-size: 6.8pt; color: #64748b;">{{ ucwords($item->paketPeminjaman->kategori ?? '-') }}</div>
                    </td>
                    <td class="text-center" style="font-size: 7pt;">
                        <div>{{ $item->tanggal_mulai ? $item->tanggal_mulai->format('d/m/Y H:i') : '-' }}</div>
                        <div style="color: #64748b;">s/d {{ $item->tanggal_selesai ? $item->tanggal_selesai->format('d/m/Y H:i') : '-' }}</div>
                    </td>
                    <td class="text-center">
                        @if ($item->status === 'approved_final')
                            <span class="badge badge-lunas">DISETUJUI</span>
                        @elseif ($item->status === 'approved_1')
                            <span class="badge badge-partial">ADM ACC</span>
                        @elseif ($item->status === 'pending')
                            <span class="badge badge-pending">PENDING</span>
                        @elseif ($item->status === 'rejected')
                            <span class="badge badge-rejected">DITOLAK</span>
                        @else
                            <span class="badge badge-pending">{{ strtoupper($item->status) }}</span>
                        @endif
                    </td>
                    <td class="text-right">Rp {{ number_format($tagihan, 0, ',', '.') }}</td>
                    <td class="text-right font-bold" style="color: #0060ac;">
                        Rp {{ number_format($terbayar, 0, ',', '.') }}
                        @if ($pembayaran && $pembayaran->details->count() > 0)
                            <div style="font-size: 6.2pt; font-weight: normal; color: #475569;">
                                ({{ $pembayaran->details->count() }}x verifikasi)
                            </div>
                        @endif
                    </td>
                    <td class="text-right" style="color: {{ $refund > 0 ? '#b91c1c' : '#94a3b8' }};">
                        {{ $refund > 0 ? 'Rp ' . number_format($refund, 0, ',', '.') : '-' }}
                    </td>
                    <td class="text-right font-bold" style="color: {{ $sisa > 0 ? '#d97706' : '#15803d' }};">
                        {{ $sisa > 0 ? 'Rp ' . number_format($sisa, 0, ',', '.') : 'LUNAS' }}
                    </td>
                    <td class="text-center">
                        @if ($status === 'lunas')
                            <span class="badge badge-lunas">LUNAS</span>
                        @elseif ($status === 'partial')
                            <span class="badge badge-partial">DP / CICIL</span>
                        @elseif ($status === 'pending')
                            <span class="badge badge-pending">PENDING</span>
                        @elseif ($status === 'refunded' || $status === 'refund_pending')
                            <span class="badge badge-refunded">REFUND</span>
                        @else
                            <span class="badge badge-rejected">{{ strtoupper($status) }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center" style="padding: 18px; color: #64748b;">
                        <em>Tidak ada rekaman transaksi peminjaman aula yang sesuai dengan kriteria filter pada periode ini.</em>
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="6" class="text-center">GRAND TOTAL AKUMULASI ({{ count($peminjamans) }} DATA PEMINJAMAN)</td>
                <td class="text-right font-bold">Rp {{ number_format($stats['total_tagihan'], 0, ',', '.') }}</td>
                <td class="text-right font-bold" style="color: #004f8f;">Rp {{ number_format($stats['total_pemasukan_bruto'], 0, ',', '.') }}</td>
                <td class="text-right font-bold" style="color: #b91c1c;">Rp {{ number_format($stats['total_refund'], 0, ',', '.') }}</td>
                <td class="text-right font-bold" style="color: #d97706;">Rp {{ number_format($stats['total_sisa_tagihan'], 0, ',', '.') }}</td>
                <td class="text-center font-bold" style="color: #15803d; font-size: 7.2pt;">
                    NETTO: Rp {{ number_format($stats['total_pemasukan_netto'], 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
    </table>

    <!-- 6. BAGIAN LEMBAR PENGESAHAN & TANDA TANGAN RESMI -->
    <table class="signature-table">
        <tr>
            <td class="signature-box">
                <div>Mengetahui / Memeriksa,</div>
                <div><strong>Pengelola Sarpras / Petugas Aula</strong></div>
                <div class="signature-space"></div>
                <div class="signature-name">{{ $petugasAdmin->name ?? 'Admin Aula' }}</div>
                <div class="signature-nip">Petugas Administrasi SIMS Aula</div>
            </td>
            <td class="signature-box">
                <div>Karanganyar, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }}</div>
                <div><strong>Kepala SMK Negeri 2 Karanganyar</strong></div>
                <div class="signature-space"></div>
                <div class="signature-name">{{ $kepalaSekolah->name ?? 'Drs. Suwarno, M.Pd.' }}</div>
                <div class="signature-nip">NIP. 19680512 199412 1 002</div>
            </td>
        </tr>
    </table>

    <!-- 7. FOOTER INFORMASI DOKUMEN -->
    <div class="footer-note">
        Dokumen ini diterbitkan secara otomatis dan sah melalui Sistem Informasi Manajemen Sarana dan Prasarana (SIMS Sarpras) Aula SMK Negeri 2 Karanganyar. Dicetak pada {{ $printedAt }}.
    </div>

</body>
</html>
