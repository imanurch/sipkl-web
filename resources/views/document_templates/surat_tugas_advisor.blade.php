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
        <h1 style="font-size:1rem;text-align:center">SURAT PERINTAH TUGAS <br><span style="font-weight: 400">Nomor:
            {{ $letter_num ?? '' }}</span></h1>
    </div>

    <p><span>Dasar:</span> {{ $internship_team_decree ?? '' }}</p>

    <div>
        <p>Memerintahkan <br>Kepada:</p>
        <div>
            <span style="display: inline-block;min-width: 8rem">Nama Guru</span><span style="display: inline-block">:
                {{ $advisor_name ?? '' }}</span>
            <br><span style="display: inline-block;min-width: 8rem">NIP</span><span style="display: inline-block">:
                {{ $advisor_nip ?? '' }}</span>
        </div>
        <p><span>Untuk:</span> {{ $activity ?? '' }} peserta Praktik Kerja Lapangan SMK Negeri 1 Pajangan
            tahun pelajaran {{ $academic_year ?? '' }}.</p>
    </div>

    <div style="float: right">
        <p>Dikeluarkan di Bantul,<br>Pada tanggal: {{ $create_date ?? '#####' }}<br>Kepala SMK Negeri 1 Pajangan,</p>
        <img src="{{ storage_path('app/signatures/' . $principal_signature) }}" alt="" width="100">
        <p>{{ $principal_name ?? '' }}<br>NIP. {{ $principal_nip ?? '' }}</p>
    </div>
</body>

</html>
