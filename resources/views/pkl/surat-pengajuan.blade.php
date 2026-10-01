<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Surat Pengajuan PKL {{ $surat->nomor_surat }}</title>
    <style>
        @page {
            margin: 25mm 20mm 20mm 20mm;
            size: A4 portrait;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11.5pt;
            line-height: 1.55;
            color: #000;
        }

        .kop {
            width: 100%;
            border-bottom: 3px solid #000;
            padding-bottom: 8px;
            margin-bottom: 18px;
        }

        .kop table {
            width: 100%;
            border-collapse: collapse;
        }

        .kop td {
            vertical-align: middle;
        }

        .kop .logo {
            width: 72px;
        }

        .kop .lembaga {
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.35;
            font-size: 11.5pt;
        }

        .kop .lembaga small {
            display: block;
            font-size: 8.5pt;
            font-weight: normal;
            text-transform: none;
        }

        .judul {
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 13pt;
            margin: 14px 0 4px;
            text-decoration: underline;
        }

        .nomor {
            text-align: right;
            font-size: 11pt;
            margin-bottom: 12px;
        }

        .kop-tujuan {
            margin: 10px 0 14px;
        }

        .kop-tujuan td {
            padding: 1px 0;
        }

        .isi {
            text-align: justify;
            margin-bottom: 10px;
        }

        .indent {
            text-indent: 40px;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0 8px;
            font-size: 10.5pt;
        }

        table.data th,
        table.data td {
            border: 1px solid #000;
            padding: 5px 7px;
            vertical-align: top;
        }

        table.data th {
            background: #e8e8e8;
            text-align: center;
            font-weight: bold;
        }

        table.data td.c {
            text-align: center;
        }

        .detail {
            margin-top: 14px;
        }

        .detail table {
            width: 100%;
            border-collapse: collapse;
        }

        .detail td {
            padding: 1.5px 0;
        }

        .ttd {
            width: 100%;
            margin-top: 26px;
        }

        .ttd td {
            vertical-align: top;
            text-align: center;
        }

        .ttd .ruang {
            height: 68px;
        }

        .nama {
            font-weight: bold;
            text-decoration: underline;
        }

        .muted {
            font-size: 9.5pt;
        }
    </style>
</head>

<body>

    <div class="kop">
        <table>
            <tr>
                <td class="logo">
                    <img src="{{ asset('assets/logosmkk.png') }}" alt="Logo" style="width:66px">
                </td>
                <td class="lembaga">
                    Pemerintah Kabupaten Karanganyar
                    <small>Dinas Pendidikan dan Kebudayaan</small>
                    <small>Sekolah Menengah Kejuruan Negeri 2 Karanganyar</small>
                    <small>Dietrich Scholtze</small>
                    <small style="text-transform:none">Jl. Prof. Dr. Soetomo, Tepas, Argapura, Karanganyar</small>
                </td>
                <td style="width:72px"></td>
            </tr>
        </table>
    </div>

    <div class="nomor">
        Nomor : {{ $surat->nomor_surat }}<br>
        Lampiran : -
    </div>

    <div class="kop-tujuan">
        <table>
            <tr>
                <td style="width:80px">Kepada</td>
                <td style="width:10px">:</td>
                <td>
                    Yth. {{ $surat->dudi->kontak_person ?? 'Bapak/Ibu Pengusaha' }}<br>
                    {{ $surat->dudi->nama_dudi }}<br>
                    {{ $surat->dudi->alamat }}<br>
                    {{ $surat->dudi->kota }}
                </td>
            </tr>
        </table>
    </div>

    <div class="isi indent">
        Perihal: <strong>Permohonan Penempatan Peserta PKL</strong>
    </div>

    <div class="isi indent">
        Sehubungan dengan rencana kegiatan Praktik Kerja Lapangan (PKL) bagi siswa kelas XII
        SMK Negeri 2 Karanganyar, kami bermaksud meminta kerja sama dan izin penempatan peserta PKL
        pada perusahaan yang terhormat Saudara/i, dengan data peserta sebagai berikut:
    </div>

    <table class="data">
        <thead>
            <tr>
                <th style="width:32px">No</th>
                <th>Nama Siswa</th>
                <th style="width:110px">Kelas / Jurusan</th>
                <th style="width:150px">Bidang Usaha DUDI</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($siswas as $index => $siswa)
                <tr>
                    <td class="c">{{ $index + 1 }}</td>
                    <td>{{ $siswa->nama }}</td>
                    <td class="c">{{ $siswa->kelas }} / {{ $siswa->jurusan }}</td>
                    <td>{{ $surat->dudi->bidang_usaha }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="detail">
        <table>
            <tr>
                <td style="width:180px">Mulai PKL</td>
                <td style="width:8px">:</td>
                <td>{{ $tanggalIndonesia($surat->tgl_mulai_pkl) }}</td>
            </tr>
            <tr>
                <td>Selesai PKL</td>
                <td>:</td>
                <td>{{ $tanggalIndonesia($surat->tgl_selesai_pkl) }}</td>
            </tr>
            @if ($guru)
                <tr>
                    <td>Guru Pembimbing</td>
                    <td>:</td>
                    <td>
                        {{ $guru->nama }}
                        @if ($guru->nip)
                            &mdash; NIP. {{ $guru->nip }}
                        @endif
                    </td>
                </tr>
            @endif
        </table>
    </div>

    <div class="isi indent" style="margin-top:12px">
        Berdasarkan hal tersebut, kami memohon kesediaan Saudara/i untuk menerima dan membimbing peserta PKL
        tersebut dengan penuh tanggung jawab. Atas kerja sama dan perhatian baik yang diberikan, kami ucapkan
        terima kasih.
    </div>

    <div class="isi indent">
        {{ $namaSekolah }}
    </div>

    <table class="ttd">
        <tr>
            <td style="width:50%"></td>
            <td style="width:50%">
                {{ $namaKota }}, {{ $tanggalIndonesia($surat->tanggal_surat) }}<br>
                {{ $jabatan }}<br>
                <span class="ruang" style="display:block"></span>
                <span class="nama">{{ $penandaTangan['nama'] ?? '-' }}</span><br>
                @if (! empty($penandaTangan['nip']))
                    <span class="muted">NIP. {{ $penandaTangan['nip'] }}</span>
                @endif
            </td>
        </tr>
    </table>

</body>

</html>
