<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Buku Saku Tata Tertib Siswa - SMKN 2 Karanganyar</title>
    <style>
        @page {
            margin: 15mm 15mm 15mm 15mm;
            size: a4 portrait;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 9.5pt;
            line-height: 1.5;
            color: #1e293b;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }

        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
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
            font-size: 10pt;
            font-weight: bold;
            color: #0f172a;
            margin: 0;
            text-transform: uppercase;
        }

        .kop-instansi-2 {
            font-size: 13pt;
            font-weight: bold;
            color: #0066C4;
            letter-spacing: 0.5px;
            margin: 2px 0;
            text-transform: uppercase;
        }

        .kop-alamat {
            font-size: 8pt;
            color: #475569;
            margin: 0;
            line-height: 1.3;
        }

        .kop-divider {
            border: none;
            border-top: 2.5px solid #0f172a;
            border-bottom: 0.8px solid #0f172a;
            height: 3px;
            margin: 6px 0 20px 0;
        }

        .document-title {
            text-align: center;
            margin-bottom: 25px;
        }

        .document-title h2 {
            font-size: 15pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            margin: 0 0 4px 0;
            letter-spacing: 0.5px;
        }

        .document-title .sub-title {
            font-size: 10pt;
            color: #0066C4;
            font-weight: bold;
            margin: 0 0 4px 0;
            text-transform: uppercase;
        }

        .document-title .nomor-dokumen {
            font-size: 8.5pt;
            color: #64748b;
            margin: 0;
        }

        .intro-box {
            background-color: #f8fafc;
            border-left: 4px solid #0066C4;
            padding: 10px 14px;
            font-size: 9pt;
            color: #475569;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .rule-card {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            margin-bottom: 14px;
            page-break-inside: avoid;
        }

        .rule-header {
            background-color: #f1f5f9;
            padding: 8px 12px;
            font-weight: bold;
            color: #0f172a;
            font-size: 10pt;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
        }

        .rule-body {
            padding: 10px 14px;
            font-size: 9.5pt;
            color: #334155;
            line-height: 1.6;
            text-align: left;
        }

        .rule-item {
            margin-bottom: 7px;
            text-align: left;
            line-height: 1.5;
        }

        .rule-item:last-child {
            margin-bottom: 0;
        }

        .signature-table {
            width: 100%;
            margin-top: 35px;
            border-collapse: collapse;
            page-break-inside: avoid;
        }

        .signature-cell {
            width: 50%;
            vertical-align: top;
            text-align: center;
            font-size: 9.5pt;
        }

        .signature-space {
            height: 60px;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
            color: #0f172a;
        }

        .signature-nip {
            font-size: 8.5pt;
            color: #64748b;
        }

        .footer-note {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 8pt;
            color: #94a3b8;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            padding-top: 6px;
        }
    </style>
</head>
<body>

    <!-- KOP SURAT RESMI -->
    <table class="kop-table">
        <tr>
            <td class="kop-logo">
                @if (file_exists(public_path('assets/logosmkk.png')))
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/logosmkk.png'))) }}" alt="Logo">
                @endif
            </td>
            <td class="kop-text">
                <div class="kop-instansi-1">Pemerintah Provinsi Jawa Tengah &bull; Dinas Pendidikan dan Kebudayaan</div>
                <div class="kop-instansi-2">SMK Negeri 2 Karanganyar</div>
                <div class="kop-alamat">
                    Jl. Yos Sudarso, Jengglong, Bejen, Kec. Karanganyar, Kab. Karanganyar, Jawa Tengah 57716<br>
                    Website: smkn2kra.sch.id &bull; E-mail: info@smkn2kra.sch.id
                </div>
            </td>
        </tr>
    </table>
    <div class="kop-divider"></div>

    <!-- JUDUL BUKU SAKU -->
    <div class="document-title">
        <div class="sub-title">Panduan Resmi Kesiswaan</div>
        <h2>BUKU SAKU TATA TERTIB & NORMA SEKOLAH</h2>
        <p class="nomor-dokumen">Edisi Tahun Ajaran {{ date('Y') }}/{{ date('Y') + 1 }} &bull; SMK Negeri 2 Karanganyar</p>
    </div>

    <div class="intro-box">
        <strong>Ketentuan Umum:</strong> Buku saku ini memuat seluruh aturan, tata tertib, hak, serta kewajiban siswa di lingkungan SMK Negeri 2 Karanganyar guna menciptakan lingkungan belajar yang kondusif, berkarakter, dan berdaya saing industri. Seluruh siswa wajib mematuhi ketentuan yang tertulis di bawah ini.
    </div>

    <!-- DAFTAR SEMUA TATA TERTIB -->
    @php
        $groupedTartib = $tataTertibs->groupBy('judul');
        $pasal = 1;
    @endphp

    @foreach ($groupedTartib as $judul => $items)
        <div class="rule-card">
            <div class="rule-header">
                PASAL {{ $pasal++ }}: {{ strtoupper($judul) }}
            </div>
            <div class="rule-body">
                @foreach ($items as $item)
                    <div class="rule-item">
                        {!! nl2br(e($item->deskripsi ?: 'Peraturan kedisiplinan dan norma perilaku siswa di lingkungan SMK Negeri 2 Karanganyar.')) !!}
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    <!-- TANDA TANGAN PENGESAHAN -->
    <table class="signature-table">
        <tr>
            <td class="signature-cell">
                Mengetahui,<br>
                <strong>Kepala SMK Negeri 2 Karanganyar</strong>
                <div class="signature-space"></div>
                <div class="signature-name">{{ $namaKepsek ?? 'Drs. Sugiyarso, M.Pd.' }}</div>
                <div class="signature-nip">NIP. 19680512 199403 1 008</div>
            </td>
            <td class="signature-cell">
                Karanganyar, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                <strong>Waka Kesiswaan</strong>
                <div class="signature-space"></div>
                <div class="signature-name">{{ $namaWaka ?? 'Waka Bidang Kesiswaan' }}</div>
                <div class="signature-nip">SMK Negeri 2 Karanganyar</div>
            </td>
        </tr>
    </table>

    <div class="footer-note">
        Buku Saku Tata Tertib Siswa &bull; SMK Negeri 2 Karanganyar &bull; Dicetak otomatis melalui Sistem SIMS Kesiswaan
    </div>

</body>
</html>
