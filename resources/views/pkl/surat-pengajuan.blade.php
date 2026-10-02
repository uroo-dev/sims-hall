<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Surat Permohonan PKL - {{ $surat->nomor_surat }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 20mm 15mm 20mm;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.35;
            color: #000;
        }

        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2px;
        }

        .kop-table td {
            vertical-align: middle;
        }

        .kop-logo {
            width: 72px;
            text-align: center;
        }

        .kop-logo img {
            max-height: 72px;
            max-width: 68px;
            width: auto;
            height: auto;
        }

        .kop-teks {
            text-align: center;
            padding: 0 4px;
        }

        .kop-header-1 {
            font-size: 13pt;
            font-weight: bold;
            line-height: 1.15;
            letter-spacing: 0.3px;
        }

        .kop-header-2 {
            font-size: 15pt;
            font-weight: bold;
            line-height: 1.15;
            letter-spacing: 0.5px;
        }

        .kop-alamat {
            font-size: 8pt;
            font-weight: normal;
            line-height: 1.25;
            margin-top: 3px;
        }

        .kop-garis {
            border-top: 2.5px solid #000;
            border-bottom: 0.8px solid #000;
            height: 2px;
            margin: 2px 0 12px 0;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11pt;
            margin-bottom: 8px;
        }

        .meta-table td {
            vertical-align: top;
            padding: 1px 0;
        }

        .tujuan-box {
            margin-left: 200px;
            font-size: 11pt;
            line-height: 1.35;
            margin-bottom: 12px;
        }

        .isi-teks {
            font-size: 11pt;
            line-height: 1.35;
            text-align: justify;
            margin-bottom: 8px;
        }

        .paragraf-indent {
            text-indent: 40px;
            margin: 0;
        }

        table.tabel-siswa {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
            margin: 8px 0 10px 0;
        }

        table.tabel-siswa th,
        table.tabel-siswa td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: middle;
        }

        table.tabel-siswa th {
            text-align: center;
            font-weight: bold;
            background-color: #fafafa;
        }

        .text-center {
            text-align: center;
        }

        .ttd-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11pt;
            margin-top: 10px;
        }

        .ttd-table td {
            vertical-align: top;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>

    {{-- ============================================================
         HALAMAN 1: SURAT PERMOHONAN TEMPAT PKL
         ============================================================ --}}
    <table class="kop-table">
        <tr>
            <td class="kop-logo">
                @if ($logoJateng)
                    <img src="{{ $logoJateng }}" alt="Logo Jateng">
                @endif
            </td>
            <td class="kop-teks">
                <div class="kop-header-1">PEMERINTAH PROVINSI JAWA TENGAH</div>
                <div class="kop-header-1">DINAS PENDIDIKAN</div>
                <div class="kop-header-2">SEKOLAH MENENGAH KEJURUAN NEGERI 2</div>
                <div class="kop-header-2">KARANGANYAR</div>
                <div class="kop-alamat">
                    Jalan Yos Sudarso, Kayangan RT.01/RW.04, Bejen, Karanganyar<br>
                    Kode Pos 57716 Telepon 0271- 494549/494335 Faximile 0271-6498171<br>
                    Surat Elektronik sekolah@smkn2kra.sch.id website smkn2kra.sch.id
                </div>
            </td>
            <td class="kop-logo">
                @if ($logoSmkn2)
                    <img src="{{ $logoSmkn2 }}" alt="Logo SMKN 2">
                @endif
            </td>
        </tr>
    </table>
    <div class="kop-garis"></div>

    <table class="meta-table">
        <tr>
            <td style="width: 70px;">Nomor</td>
            <td style="width: 15px;">:</td>
            <td>{{ $surat->nomor_surat }}</td>
        </tr>
        <tr>
            <td>Lamp</td>
            <td>:</td>
            <td>-</td>
        </tr>
        <tr>
            <td>Perihal</td>
            <td>:</td>
            <td><em>Permohonan Tempat Praktik Kerja Lapangan ( PKL )</em></td>
        </tr>
    </table>

    <div class="tujuan-box">
        Kepada<br>
        Yth. Pimpinan/Kepala<br>
        <strong>{{ strtoupper($surat->dudi->nama_dudi) }}</strong><br>
        <strong>{{ $surat->dudi->alamat }}{{ $surat->dudi->kota ? ', ' . $surat->dudi->kota : '' }}</strong>
    </div>

    <div class="isi-teks">
        <p style="margin: 0 0 6px 0;">Dengan hormat,</p>
        <p class="paragraf-indent">
            Untuk memantapkan kemampuan siswa Sekolah Menengah Kejuruan, khususnya di SMK Negeri 2 Karanganyar, siswa diwajibkan mengikuti praktik kerja lapangan. Adapun kegiatan tersebut dilaksanakan selama kurang lebih {{ $durasiBulan }} bulan. Untuk menunjang kegiatan tersebut kami mohon bantuan Bapak/Ibu untuk memberikan kesempatan kepada siswa kami guna melaksanakan praktik kerja lapangan tempat yang Bapak/Ibu pimpin pada tanggal <strong>{{ $tanggalMulai }} s.d. {{ $tanggalSelesai }}</strong>. Adapun data siswa yang kami kirim adalah sebagai berikut :
        </p>
    </div>

    <table class="tabel-siswa">
        <thead>
            <tr>
                <th style="width: 32px;">No</th>
                <th>Nama Siswa</th>
                <th style="width: 75px;">NIS</th>
                <th style="width: 70px;">Kelas</th>
                <th style="width: 180px;">Kompetensi Keahlian</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($siswas as $index => $siswa)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ strtoupper($siswa->nama) }}</td>
                    <td class="text-center">{{ $siswa->nis }}</td>
                    <td class="text-center">{{ strtoupper($siswa->kelas) }}</td>
                    <td>{{ strtoupper($siswa->jurusan) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 10px;">Data siswa belum tersedia.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p class="isi-teks" style="margin: 8px 0 10px 0;">
        Demikian permohonan yang kami buat atas peran serta dan kerjasama yang baik kami mengucapkan terima kasih
    </p>

    <table class="ttd-table">
        <tr>
            <td style="width: 50%;"></td>
            <td style="width: 50%; text-align: left; line-height: 1.25;">
                Karanganyar, {{ $tanggalSurat }}<br>
                Kepala SMK Negeri 2 Karanganyar<br>
                Kabupaten Karanganyar
                <div style="height: 50px;"></div>
                <strong>{{ $namaKepsek }}</strong><br>
                {{ $pangkatKepsek }}<br>
                NIP. {{ $nipKepsek }}
            </td>
        </tr>
    </table>


    {{-- ============================================================
         HALAMAN 2: SURAT BALASAN DARI DU/DI
         ============================================================ --}}
    <div class="page-break"></div>

    <div style="text-align: center; font-size: 12.5pt; font-weight: bold; margin-bottom: 22px;">
        SURAT BALASAN DARI DU/DI
    </div>

    <div style="font-size: 11pt; line-height: 1.35; margin-bottom: 14px;">
        Kepada<br>
        <strong>Yth. KEPALA SMK NEGERI 2 KARANGANYAR</strong><br>
        Jl. Yos Sudarso Jengglong Bejen Telp. (0271) 494549<br>
        Karanganyar
    </div>

    <div class="isi-teks" style="margin-bottom: 10px;">
        <p style="margin: 0 0 6px 0;">Dengan hormat,</p>
        <p style="margin: 0 0 10px 0;">
            Sehubungan dengan Surat Permohonan Tempat Praktik Kerja Lapangan (PKL) dengan {{ $surat->nomor_surat }} tertanggal {{ $tanggalSurat }} maka kami dari pihak <strong>{{ strtoupper($surat->dudi->nama_dudi) }}</strong>, <strong>{{ $surat->dudi->alamat }}{{ $surat->dudi->kota ? ', ' . $surat->dudi->kota : '' }}</strong> menyatakan :
        </p>
        <p style="text-align: center; font-weight: bold; font-size: 11pt; margin: 8px 0;">
            ( Menerima / Tidak Menerima )
        </p>
        <p style="margin: 8px 0 6px 0;">
            Siswa/siswi tersebut untuk melaksanakan PKL pada tanggal <strong>{{ $tanggalMulai }} s.d. {{ $tanggalSelesai }}</strong>, dengan ketentuan siswa/siswi tersebut harus mentaati segala peraturan atau tatatertib yang berlaku di Perusahaan/Bengkel/Instansi.<br>
            Adapun siswa/siswi yang kami terima sebagai berikut:
        </p>
    </div>

    <table class="tabel-siswa">
        <thead>
            <tr>
                <th style="width: 32px;">No</th>
                <th>Nama Siswa</th>
                <th style="width: 75px;">NIS</th>
                <th style="width: 70px;">Kelas</th>
                <th style="width: 180px;">Kompetensi keahlian</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalRows = max(count($siswas), 6);
            @endphp
            @for ($i = 0; $i < $totalRows; $i++)
                @php
                    $s = $siswas[$i] ?? null;
                @endphp
                <tr>
                    <td class="text-center" style="height: 18px;">{{ $i + 1 }}</td>
                    <td>{{ $s ? strtoupper($s->nama) : '' }}</td>
                    <td class="text-center">{{ $s ? $s->nis : '' }}</td>
                    <td class="text-center">{{ $s ? strtoupper($s->kelas) : '' }}</td>
                    <td>{{ $s ? strtoupper($s->jurusan) : '' }}</td>
                </tr>
            @endfor
        </tbody>
    </table>

    <p class="isi-teks" style="margin: 8px 0 14px 0;">
        Demikian surat keterangan ini kami buat semoga dapat dimaklumi dan dapat dipergunakan sebagaimana mestinya.
    </p>

    <table style="width: 100%; border-collapse: collapse; font-size: 10pt; margin-top: 10px;">
        <tr>
            <td style="width: 45%; vertical-align: top; line-height: 1.35;">
                <strong>NB. :</strong><br>
                *) coret yang tidak perlu
            </td>
            <td style="width: 55%; vertical-align: top; text-align: left; line-height: 1.25;">
                &hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;., ......................... {{ $surat->tanggal_surat->year }}<br>
                Pimpinan DU/DI<br>
                &hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;..<br>
                <div style="height: 48px;"></div>
                Tanda tangan,<br>
                nama pemilik DUDI &amp; stempel DUDI<br>
                (&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;.)
            </td>
        </tr>
    </table>

</body>

</html>
