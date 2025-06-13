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
    </style>
</head>

<body>
    <div>
        @include('document_templates/kop_surat')
        <div>
            <p style="text-align: right">Bantul, {{ $create_date ?? '' }}</p>
        </div>
        <div>
            <span style="display: inline-block;min-width: 4rem">No</span><span style="display: inline-block">:
                {{ $letter_num ?? '' }}</span>
            <br><span style="display: inline-block;min-width: 4rem">Hal</span><span style="display: inline-block">:
                Penarikan Peserta Praktik Kerja Lapangan (PKL)</span>
            <br><span style="display: inline-block;min-width: 4rem">Lamp.</span><span style="display: inline-block">:
                -</span>
        </div>
        <div>
            <p>Yth. Pimpinan {{ $industry_name ?? '' }} <br>di {{ $industry_address ?? '' }}</br>
        </div>
        <div>
            <p>Dengan hormat,</p>
            <p>Sesuai dengan surat kesediaan penerimaan peserta Praktik Kerja Lapangan (PKL) yang
                telah
                disepakati bersama, maka dengan ini kami tarik kembali peserta didik SMK Negeri 1 Pajangan yang telah
                selesai melaksanakan kegiatan PKL tahun pelajaran {{ $academic_year ?? '' }} di
                perusahaan atau instansi yang
                Bapak/Ibu pimpin.</p>
            <p>Selanjutnya, kami sampaikan penghargaan dan terima kasih atas kesempatan yang telah diberikan kepada
                peserta didik kami untuk dapat melaksanakan kegiatan PKL, sehingga program Praktik Kerja Lapangan (PKL)
                dapat terlaksana sebagaimana mestinya. Apabila selama pelaksanaan kegiatan PKL terdapat hal-hal yang
                kurang berkenan, kami mohon maaf yang sebesar-besarnya.</p>
            <p>Dengan ini pula kami memohon kerja sama yang telah terjalin dengan baik dapat terus berlangsung, sehingga
                untuk periode yang akan datang peserta didik kami dapat melaksanakan kegiatan PKL di perusahaan atau
                instansi yang Bapak/Ibu pimpin.</p>
        </div>

        <div>
            <h1 style="font-size:1rem;text-align:center">Kompetensi Keahlian:
                </br>{{ $department == 'RPL' ? 'Rekayasa Perangkat Lunak' : ($department == 'DPIB' ? 'Desain Pemodelan dan Informasi Bangunan' : 'Kreatif Kriya Kayu dan Rotan') }}
        </div>

        <div>
            <br><span style="display: inline-block;min-width: 4rem">Nama Peserta PKL</span><span
                style="display: inline-block">:</span>

            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($intern_group_data as $dt)
                        <tr>
                            <td style="width:4rem;">{{ $loop->iteration }}</td>
                            <td style="text-align: left; width:22rem;">{{ $dt->name }}</td>
                            <td style="width:8rem;">XI {{ $dt->department->name }} </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <p>Demikian surat penarikan peserta Praktik Kerja Lapangan (PKL) ini kami sampaikan. Atas kerja sama
                Bapak/Ibu, kami mengucapkan terima kasih.</p>
        </div>

        <div style="float: right">
            <p>Hormat kami,<br>Kepala SMK Negeri 1 Pajangan,</p>
            <img src="{{ storage_path('app/signatures/' . $principal_signature) }}" alt="" width="100">
            <p>{{ $principal_name ?? '' }}<br>NIP. {{ $principal_nip ?? '' }}</p>
        </div>
    </div>
</body>

</html>
