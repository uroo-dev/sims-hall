<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Tata Tertib - {{ $tataTertib->judul }}</title>
    <style>
        @page {
            margin: 15mm 15mm 15mm 15mm;
            size: a4 portrait;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10pt;
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
            font-size: 14pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            margin: 0 0 4px 0;
            letter-spacing: 0.5px;
        }

        .document-title .nomor-dokumen {
            font-size: 9pt;
            color: #64748b;
            margin: 0;
        }

        .content-box {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 18px 22px;
            margin-bottom: 30px;
        }

        .section-header {
            font-size: 11pt;
            font-weight: bold;
            color: #0066C4;
            margin-bottom: 12px;
            border-bottom: 1.5px solid #e2e8f0;
            padding-bottom: 6px;
        }

        .content-text {
            font-size: 10pt;
            color: #334155;
            line-height: 1.6;
            white-space: pre-line;
            text-align: justify;
        }

        .signature-table {
            width: 100%;
            margin-top: 40px;
            border-collapse: collapse;
        }

        .signature-cell {
            width: 50%;
            vertical-align: top;
            text-align: center;
            font-size: 9.5pt;
        }

        .signature-space {
            height: 65px;
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

    <!-- JUDUL DOKUMEN -->
    <div class="document-title">
        <h2>PERATURAN & TATA TERTIB SEKOLAH</h2>
        <p class="nomor-dokumen">Nomor: 421.5 / KES / {{ date('Y') }} / {{ sprintf('%03d', $tataTertib->tata_tertibID) }}</p>
    </div>

    <!-- ISI TATA TERTIB -->
    <div class="content-box">
        <div class="section-header">
            TENTANG: {{ strtoupper($tataTertib->judul) }}
        </div>
        <div class="content-text">
{{ $tataTertib->deskripsi ?: 'Peraturan kedisiplinan dan norma perilaku siswa di lingkungan SMK Negeri 2 Karanganyar.' }}
        </div>
    </div>

    <!-- TANDA TANGAN PENGESAHAN -->
    <table class="signature-table">
        <tr>
            <td class="signature-cell">
                Mengetahui,<br>
                <strong>Kepala SMK Negeri 2 Karanganyar</strong>
                <div class="signature-space"></div>
                <div class="signature-name">Drs. Sugiyarso, M.Pd.</div>
                <div class="signature-nip">NIP. 19680512 199403 1 008</div>
            </td>
            <td class="signature-cell">
                Karanganyar, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                <strong>Waka Kesiswaan</strong>
                <div class="signature-space"></div>
                <div class="signature-name">Waka Bidang Kesiswaan</div>
                <div class="signature-nip">SMK Negeri 2 Karanganyar</div>
            </td>
        </tr>
    </table>

    <div class="footer-note">
        Dokumen resmi Tata Tertib Siswa SMK Negeri 2 Karanganyar &bull; Dicetak otomatis melalui Sistem SIMS Kesiswaan pada {{ date('d/m/Y H:i') }} WIB
    </div>

</body>
</html>
