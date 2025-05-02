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
    </style>
</head>

<body>
    @include('document_templates/kop_surat')

    <div>
        <h1 style="font-size:1rem;text-align:center">SURAT JALAN PRAKTIK KERJA LAPANGAN (PKL)
            </br><span style="font-weight: 400">Nomor : {{ $letter_num ?? '' }}</span></h1>
    </div>
    <div>
        <p>Yang bertanda tangan di bawah ini:</p>
        <span style="display: inline-block;min-width: 8rem">Nama</span><span style="display: inline-block">:
            {{ $principal_name ?? '' }}</span>
        <br><span style="display: inline-block;min-width: 8rem">NIP</span><span style="display: inline-block">:
            {{ $principal_nip ?? '' }}</span>
        <br><span style="display: inline-block;min-width: 8rem">Jabatan</span><span style="display: inline-block">:
            Kepala SMK Negeri 1 Pajangan</span>
    </div>
    <div>
        <p>Menerangkan bahwa:</p>
        <span style="display: inline-block;min-width: 8rem">Nama</span><span style="display: inline-block">:
            {{ $intern_name ?? '' }}</span>
        <br><span style="display: inline-block;min-width: 8rem">NIS</span><span style="display: inline-block">:
            {{ $intern_nis ?? '' }}</span>
        <br><span style="display: inline-block;min-width: 8rem">Kepentingan</span><span style="display: inline-block">:
            Melaksanakan kegiatan Praktik Kerja Lapangan (PKL)</span>
        <br><span style="display: inline-block;min-width: 8rem">Tanggal</span><span style="display: inline-block">:
            {{ $internship_start_date ?? '#####' }} - {{ $internship_end_date ?? '#####' }}</span>
        <br><span style="display: inline-block;min-width: 8rem">Tujuan Lokasi</span><span
            style="display: inline-block">: {{ $industry_name ?? '#####' }}</span>
        <br><span style="display: inline-block;min-width: 8rem">Alamat</span><span style="display: inline-block">:
            {{ $industry_address ?? '#####' }}</span>
        <br><span style="display: inline-block;min-width: 8rem">Jenis Kendaraan</span><span
            style="display: inline-block">: {{ $intern_transport ?? '#####' }}</span>
    </div>
    <div>
        <p>Demikian surat keterangan jalan ini kami buat agar dapat digunakan sebagaimana mestinya.</p>
    </div>

    <div style="float: right">
        <p>Dikeluarkan di Bantul,<br>Pada tanggal: {{ $create_date ?? '#####' }}<br>Kepala SMK Negeri 1 Pajangan,</p>
        <img src="{{ storage_path('app/signatures/' . $principal_signature) }}" alt="" width="100">
        <p>{{ $principal_name ?? '' }}<br>NIP. {{ $principal_nip ?? '' }}</p>
    </div>
</body>

</html>
