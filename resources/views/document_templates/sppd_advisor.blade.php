<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        @page {
            margin: 1in;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Times New Roman', Times, serif;
            font-size: 1rem;
            text-align: justify;
            line-height: 1.5;
        }

        div {
            margin-bottom: 1rem
        }

        table {
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 0.2rem 0.5rem;
            text-align: center
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>
    {{-- page 1 --}}
    <div>
        @include('document_templates/kop_surat')
        <div>
            <h1 style="font-size:1rem;text-align:center">SURAT PERINTAH PERJALANAN DINAS (SPPD)</h1>
        </div>

        <div>
            <table>
                <tbody>
                    <tr>
                        <td style="width:1.2rem;">1</td>
                        <td style="text-align: left; width:16.5rem;">Pejabat yang berwenang memberi perintah</td>
                        <td style="text-align: left; width:16.5rem;">Kepala Sekolah</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td style="text-align: left">Nama pegawai yang diperintahkan </td>
                        <td style="text-align: left">{{ $advisor_name }}</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td style="text-align: left">
                            <li style="list-style: none">a. Pangkat/golongan</li>
                            <li style="list-style: none">b. Jabatan</li>
                        </td>
                        <td style="text-align: left">
                            <li style="list-style: none">a. {{ $pangkat ?? '' }}/{{ $golongan ?? '' }}</li>
                            <li style="list-style: none">b. {{ $jabatan ?? '' }}'</li>
                        </td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td style="text-align: left">Maksud perjalanan dinas</td>
                        <td style="text-align: left">{{ $activity ?? '##' }} peserta Praktik Kerja Lapangan (PKL)
                            SMK Negeri 1 Pajangan tahun pelajaran {{ $academic_year ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td style="text-align: left">Alat angkutan yang dipergunakan</td>
                        <td style="text-align: left">{{ $kendaraan ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td style="text-align: left">
                            <li style="list-style: none">a. Tempat Berangkat</li>
                            <li style="list-style: none">b. Tempat Tujuan</li>
                        </td>
                        <td style="text-align: left">
                            <li style="list-style: none">a. SMK Negeri 1 Pajangan</li>
                            <li style="list-style: none">b. {{ $industry_name ?? '' }}</li>
                        </td>
                    </tr>
                    <tr>
                        <td>7</td>
                        <td style="text-align: left">
                            <li style="list-style: none">a. Lamanya perjalanan dinas</li>
                            <li style="list-style: none">b. Tanggal Berangkat</li>
                            <li style="list-style: none">c. Tanggal Harus Kembali</li>
                        </td>
                        <td style="text-align: left">
                            <li style="list-style: none">a. 1 hari</li>
                            <li style="list-style: none">b. {{ $monitoring_date ?? '' }}</li>
                            <li style="list-style: none">c. {{ $monitoring_date ?? '' }}</li>
                        </td>
                    </tr>
                    <tr>
                        <td>8</td>
                        <td style="text-align: left">Pengikut</td>
                        <td style="text-align: left">-</td>
                    </tr>
                    <tr>
                        <td>9</td>
                        <td style="text-align: left">
                            Pembebanan anggaran
                            <li style="list-style: none">a. Instansi</li>
                            <li style="list-style: none">b. Mata anggaran</li>
                        </td>
                        <td style="text-align: left">
                            <br>
                            <li style="list-style: none">a. SMK Negeri 1 Pajangan</li>
                            <li style="list-style: none">b. BOS</li>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div>
            <div style="float:left">
                <p>Guru Pembimbing PKL,</p>
                <br>
                <br>
                <p>{{ $advisor_name ?? '' }}<br>NIP. {{ $advisor_nip ?? '' }}</p>
            </div>
            <div style="float:right">
                <p>Kepala SMK Negeri 1 Pajangan,</p>
                <br>
                <br>
                <p>{{ $principal_name ?? '' }}<br>NIP. {{ $principal_nip ?? '' }}</p>
            </div>
        </div>
    </div>

    {{-- page 2 --}}
    <div class="page-break">
        <div style="margin: 0">
            <div style="float: right;margin: 0">
                <span style="display: inline-block;min-width: 7rem">Nomor SPDD</span><span
                    style="display: inline-block">:
                    {{ $letter_num ?? '' }}</span>
                <br><span style="display: inline-block;min-width: 7rem">Berangkat dari</span><span
                    style="display: inline-block">: SMK Negeri 1 Pajangan</span>
                <br><span style="display: inline-block;min-width: 7rem">Pada tanggal</span><span
                    style="display: inline-block">: {{ $monitoring_date ?? '' }}</span>
                <br><span style="display: inline-block;min-width: 7rem">Ke</span><span style="display: inline-block">:
                    {{ $industry_name ?? '' }}</span>

                <div style="float: right;margin: 0">
                    <p style="margin: 0"><br>Kepala SMK Negeri 1 Pajangan,</p>
                    <br>
                    <br>
                    <p style="margin: 0">{{ $principal_name ?? '' }}<br>NIP. {{ $principal_nip ?? '' }}</p>
                </div>
            </div>
        </div>

        <hr style="clear: both">
        <div style="margin: 0">
            <div style="float:left;margin: 0">
                <span style="display: inline-block">II. </span>
                <span style="display: inline-block;min-width: 6rem">Tiba di</span><span style="display: inline-block">:
                    {{ $industry_name ?? '' }}</span>
                <br><span style="display: inline-block;min-width: 6rem">Pada tanggal</span><span
                    style="display: inline-block">: {{ $monitoring_date ?? '' }}</span>
                <br><span style="display: inline-block;min-width: 6rem">Kepala</span></span>
                <br>
                <br>
                <br>
                <br>
                <span style="margin-left: 7rem">........................</span>
            </div>
            <div style="float:right;min-width:18rem;margin: 0">
                <span style="display: inline-block;min-width: 6rem">Berangkat dari</span><span
                    style="display: inline-block">:
                    {{ $industry_name ?? '' }}</span>
                <br><span style="display: inline-block;min-width: 6rem">Ke</span><span style="display: inline-block">:
                    SMK Negeri 1 Pajangan</span>
                <br><span style="display: inline-block;min-width: 6rem">Pada tanggal</span><span
                    style="display: inline-block">: {{ $monitoring_date ?? '' }}</span>
                <br><span style="display: inline-block;min-width: 6rem">Kepala</span></span>
                <br>
                <br>
                <br>
                <span style="margin-left: 7rem">........................</span>
            </div>
        </div>

        <hr style="clear: both">
        <div style="float:right;margin: 0">
            <div style="max-width:20rem;margin:0">IV. Tiba
                kembali di: SMK
                Negeri 1 Pajangan
                <br>Pada tanggal:
                <br>Telah diperiksa dengan keterangan bahwa perjalanan tersebut di atas benar dilakukan atas
                perintahnya dan semata-mata untuk kepentingan jabatan dalam waktu yang sesingkat-singkatnya.
            </div>
            <div style="margin: 0">
                <p>Kepala SMK Negeri 1 Pajangan,</p>
                <br>
                <br>
                <p style="margin: 0">{{ $principal_name ?? '' }}<br>NIP. {{ $principal_nip ?? '' }}</p>
            </div>
        </div>

        <div style="clear: both">
            <span>V. Catatan lain-lain</span>
        </div>
        <div>
            <span>VI. Perhatian</span>
            <p style="margin: 0">
                Pejabat yang berwenang menerbitkan SPPD pegawai yang melakukan perjalanan dinas, para pejabat yang
                mengesahkan tanggal berangkat/ tiba serta bendahara bertanggung jawab berdasarkan peraturan-peraturan
                keuangan negara apabila menderita rugi akibat kesalahan dan kealpaan.
                <br>(angka 8, lampiran surat Menteri Keuangan tanggal 30 April 1974 No. B 269 / MK / I / 4 / d)
            </p>
        </div>
    </div>
</body>

</html>
