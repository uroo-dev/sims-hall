<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Daftar Peminjaman Aula - SMKN 2 Karanganyar</title>
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
            padding-right: 75px;
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

        /* TABEL UTAMA */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7pt;
            margin-bottom: 12px;
        }

        .data-table th {
            background-color: #0060ac;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
            padding: 4px 3px;
            border: 1px solid #004f8f;
            vertical-align: middle;
        }

        .data-table td {
            padding: 3.5px 3px;
            border: 1px solid #cbd5e1;
            vertical-align: middle;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
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

        .badge-approved-final { background-color: #dcfce7; color: #15803d; border: 0.5px solid #86efac; }
        .badge-approved-1 { background-color: #fef3c7; color: #b45309; border: 0.5px solid #fcd34d; }
        .badge-pending { background-color: #e0f2fe; color: #0369a1; border: 0.5px solid #7dd3fc; }
        .badge-rejected { background-color: #fee2e2; color: #b91c1c; border: 0.5px solid #fca5a5; }

        .badge-lunas { background-color: #dcfce7; color: #15803d; border: 0.5px solid #86efac; }
        .badge-partial { background-color: #fef3c7; color: #b45309; border: 0.5px solid #fcd34d; }
        .badge-unpaid { background-color: #f1f5f9; color: #475569; border: 0.5px solid #cbd5e1; }
        .badge-refund { background-color: #f3e8ff; color: #7e22ce; border: 0.5px solid #d8b4fe; }

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

    <!-- 1. KOP SURAT RESMI -->
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
        <h1 class="doc-title">Laporan Rekapitulasi Daftar Peminjaman Aula</h1>
        <p class="doc-subtitle">
            @if ($tanggalDari && $tanggalSampai)
                Periode Tanggal: {{ \Carbon\Carbon::parse($tanggalDari)->locale('id')->isoFormat('D MMMM Y') }} s/d {{ \Carbon\Carbon::parse($tanggalSampai)->locale('id')->isoFormat('D MMMM Y') }}
                @if ($tanggalDari === now()->startOfMonth()->toDateString() && $tanggalSampai === now()->endOfMonth()->toDateString())
                    <strong>(Bulan Ini)</strong>
                @endif
            @elseif ($tanggalDari)
                Mulai Tanggal: {{ \Carbon\Carbon::parse($tanggalDari)->locale('id')->isoFormat('D MMMM Y') }}
            @elseif ($tanggalSampai)
                Hingga Tanggal: {{ \Carbon\Carbon::parse($tanggalSampai)->locale('id')->isoFormat('D MMMM Y') }}
            @else
                Periode: Keseluruhan Riwayat Peminjaman (Semua Waktu)
            @endif
        </p>
    </div>

    <!-- 3. METADATA PENCETAKAN -->
    <table class="meta-table">
        <tr>
            <td class="text-left" style="width: 50%;">
                <strong>Nomor Dokumen:</strong> REG-AULA/{{ date('Ym') }}/{{ str_pad((string) count($peminjamans), 4, '0', STR_PAD_LEFT) }}
            </td>
            <td class="text-right" style="width: 50%;">
                <strong>Waktu Cetak:</strong> {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y, HH:mm') }} WIB &nbsp;|&nbsp; 
                <strong>Operator:</strong> {{ $petugasAdmin->name ?? 'Admin Sistem' }} ({{ ucwords(str_replace('_', ' ', $petugasAdmin->role ?? 'Admin')) }})
            </td>
        </tr>
    </table>

    <!-- 4. TABEL DAFTAR PEMINJAMAN -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 18px;">No</th>
                <th style="width: 62px;">No. Invoice</th>
                <th style="width: 95px;">Pemohon & Instansi</th>
                <th style="width: 68px;">Paket Aula</th>
                <th style="width: 68px;">Jadwal Pelaksanaan</th>
                <th style="width: 48px;">Status</th>
                <th style="width: 52px;">Tagihan</th>
                <th style="width: 52px;">Terbayar</th>
                <th style="width: 52px;">Sisa Tagihan</th>
                <th style="width: 45px;">Status Bayar</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($peminjamans as $index => $item)
                @php
                    $pembayaran = $item->pembayaran;
                    $tagihan = (float) ($pembayaran->total_tagihan ?? 0);
                    $terbayar = (float) ($pembayaran->total_terbayar ?? 0);
                    $sisa = (float) ($pembayaran->sisa_tagihan ?? 0);
                    $statusBayar = $pembayaran->status_pembayaran ?? 'pending';
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center font-bold" style="color: #004f8f; font-size: 7.2pt;">
                        {{ $pembayaran->kode_pembayaran ?? ('INV-' . str_pad($item->id, 5, '0', STR_PAD_LEFT)) }}
                    </td>
                    <td>
                        <div class="font-bold" style="color: #0f172a;">{{ $item->nama }}</div>
                        <div style="font-size: 6.8pt; color: #64748b;">{{ $item->email_instansi }}</div>
                    </td>
                    <td>
                        <div class="font-bold">{{ $item->paketPeminjaman->nama_paket ?? '-' }}</div>
                        <div style="font-size: 6.8pt; color: #64748b;">{{ ucwords($item->paketPeminjaman->kategori ?? '-') }}</div>
                    </td>
                    <td class="text-center" style="font-size: 7pt;">
                        <div>{{ $item->tanggal_mulai ? $item->tanggal_mulai->format('d/m/Y H:i') : '-' }}</div>
                        <div style="color: #64748b;">s/d {{ $item->tanggal_selesai ? $item->tanggal_selesai->format('d/m/Y H:i') : '-' }}</div>
                    </td>
                    <td class="text-center">
                        @if ($item->status === 'approved_final')
                            <span class="badge badge-approved-final">Disetujui Final</span>
                        @elseif ($item->status === 'approved_1')
                            <span class="badge badge-approved-1">Disetujui Admin</span>
                        @elseif ($item->status === 'pending')
                            <span class="badge badge-pending">Menunggu</span>
                        @elseif ($item->status === 'rejected')
                            <span class="badge badge-rejected">Ditolak</span>
                        @else
                            <span class="badge badge-pending">{{ strtoupper($item->status) }}</span>
                        @endif
                    </td>
                    <td class="text-right">Rp {{ number_format($tagihan, 0, ',', '.') }}</td>
                    <td class="text-right font-bold" style="color: #0060ac;">
                        Rp {{ number_format($terbayar, 0, ',', '.') }}
                    </td>
                    <td class="text-right font-bold" style="color: {{ $sisa > 0 ? '#d97706' : '#15803d' }};">
                        {{ $sisa > 0 ? 'Rp ' . number_format($sisa, 0, ',', '.') : 'LUNAS' }}
                    </td>
                    <td class="text-center">
                        @if ($statusBayar === 'lunas')
                            <span class="badge badge-lunas">LUNAS</span>
                        @elseif ($statusBayar === 'partial')
                            <span class="badge badge-partial">DP / CICIL</span>
                        @elseif ($statusBayar === 'pending')
                            <span class="badge badge-unpaid">BELUM BAYAR</span>
                        @elseif ($statusBayar === 'refunded' || $statusBayar === 'refund_pending')
                            <span class="badge badge-refund">REFUND</span>
                        @else
                            <span class="badge badge-rejected">{{ strtoupper($statusBayar) }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center" style="padding: 16px; color: #64748b;">
                        <em>Tidak ada data permohonan peminjaman aula pada periode yang dipilih.</em>
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if (count($peminjamans) > 0)
            <tfoot>
                <tr class="total-row">
                    <td colspan="6" class="text-center">TOTAL KESELURUHAN ({{ count($peminjamans) }} PERMOHONAN)</td>
                    <td class="text-right font-bold">Rp {{ number_format($stats['total_tagihan'], 0, ',', '.') }}</td>
                    <td class="text-right font-bold" style="color: #004f8f;">Rp {{ number_format($stats['total_terbayar'], 0, ',', '.') }}</td>
                    <td class="text-right font-bold" style="color: #d97706;">Rp {{ number_format($stats['total_sisa_tagihan'], 0, ',', '.') }}</td>
                    <td class="text-center">-</td>
                </tr>
            </tfoot>
        @endif
    </table>

    <!-- 6. LEMBAR PENGESAHAN -->
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
        Dokumen ini diterbitkan secara otomatis dan sah melalui Sistem Informasi Manajemen Sarana dan Prasarana (SIMS Sarpras) Aula SMK Negeri 2 Karanganyar.
    </div>

</body>
</html>
