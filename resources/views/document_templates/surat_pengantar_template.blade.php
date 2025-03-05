<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- @vite('resources/css/app.css') --}}
    <link rel="stylesheet" href="{{ public_path('build/assets/app.css') }}">
    <title>SIPKL</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- <style>
        .header-container {
            display: flex;
            align-items: center;
            gap: 1rem;
            /* Setara dengan space-x-4 */
        }

        .logo {
            width: 6rem;
            /* Setara dengan w-24 (96px) */
        }

        .header-text {
            text-align: center;
            font-weight: bold;
            font-size: 1rem;
            /* Setara dengan text-sm */
            line-height: 1.4;
        }

        .header-subtext {
            text-align: center;
            font-size: 1rem;
            /* Setara dengan text-xs */
            margin-top: 0.25rem;
            /* Memberikan sedikit jarak atas */
        }

        .content-container {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            /* Setara dengan space-y-6 */
        }

        .text-small {
            font-size: 1rem;
            /* Setara dengan text-sm */
        }

        .text-right {
            text-align: right;
        }

        .info-container {
            display: flex;
            align-items: center;
        }

        .info-label {
            min-width: 4rem;
            /* Setara dengan min-w-16 */
            font-weight: bold;
        }

        .info-value {
            flex: 1;
        }

        .paragraph {
            text-align: justify;
            line-height: 1.5;
        }

        .table-container {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .table-container th,
        .table-container td {
            border: 1px solid black;
            padding: 0.5rem 1rem;
            text-align: left;
        }
    </style> --}}
</head>

<body>
    {{-- <div class="m-12">
        <div class="header-container">
            <div class="logo">
                <img src="{{ url('storage/images/logoDinasDIY.png') }}" alt=" ">
            </div>
            <div>
                <h1 class="header-text">PEMERINTAH DAERAH ISTIMEWA YOGYAKARTA
                    <br>DINAS PENDIDIKAN, PEMUDA, DAN OLAHRAGA
                    <br>BALAI PENDIDIKAN MENENGAH KAB. BANTUL
                    <br>SMKN 1 PAJANGAN
                </h1>
                <h6 class="header-subtext">Alamat: Pajangan, Triwidadi, Pajangan, Bantul Kode Pos: 56751
                    Telepon:
                    <br>0812#### Faksimile: smkn1pajanganbantul@gmail.com
                    <br>Laman: www.smkn1pajangan.sch.id
                </h6>
            </div>
            <div class="logo">
                <img src="{{ url('storage/images/logo.png') }}" alt=" ">
            </div>
        </div>

        <hr style="margin: 1rem 0rem">

        <div class="content-container">
            <div class="text-small">
                <h6 class="text-right">Bantul, 2 Februari 2025</h6>
                <div class="info-container">
                    <h6 class="info-label">Nomor</h6>
                    <span class="info-value">: B/400.3.8.10</span>
                </div>
                <div class="info-container">
                    <h6 class="info-label">Lamp</h6>
                    <span class="info-value">: </span>
                </div>
                <div class="info-container">
                    <h6 class="info-label">Hal.</h6>
                    <span class="info-value">: Permohonan Praktik Kerja Industri</span>
                </div>
            </div>

            <div>
                <h5 class="text-small">Kepada:
                    <br>Yth. PT Abadi Jaya
                    <br>Jl.Gajah Mada No.5, Yogyakarta
                </h5>
            </div>

            <div class="text-small">
                <p class="paragraph">Dalam kurikulum Sekolah Menengah Kejuruan (SMK) sangat diperlukan kerja sama
                    antara sekolah dengan
                    Dunia
                    Industri. Maka, dunia industri merupakan salah satu institusi pasangan yang nantinya diharapkan
                    sebagai
                    tempat Praktek Kerja Industri para siswa SMK.
                    <br>Kami mohon kesediaan Bapak/Ibu/Sdr. untuk dapat menerima siswa/siswi SMKN 1 Pajangan untuk dapat
                    melaksanakan Praktek Kerja Industri di Instansi yang anda pimpin untuk tahun pembelajaran 2025/2026.
                </p>
                <p>Waktu Pelaksanaan : <span>1 Juli 2025 - 2 Juli 2025</span> </p>
                <p>Adapun siswa/siswi kami tersebut adalah:</p>
                <table class="table-container">
                    <thead>
                        <tr>
                            <th>NIS</th>
                            <th>NAMA SISWA</th>
                            <th>JURUSAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2502</td>
                            <td>Angga</td>
                            <td>Rekayasa Perangkat Lunak</td>
                        </tr>
                        <tr>
                            <td>2503</td>
                            <td>Dwi</td>
                            <td>Rekayasa Perangkat Lunak</td>
                        </tr>
                    </tbody>
                </table>
                <p class="text-small">Demikian surat permohonan ini dan terima kasih atas kerja samanya.</p>
            </div>
        </div>
    </div> --}}


    <div class="m-12">
        <div class="flex place-items-center space-x-4">
            <div class="w-24">
                <img src="{{ url('storage/images/logoDinasDIY.png') }}" alt=" ">
            </div>
            <div class="space-y-2">
                <h1 class="text-sm-bold text-center">PEMERINTAH DAERAH ISTIMEWA YOGYAKARTA
                    <br>DINAS PENDIDIKAN, PEMUDA, DAN OLAHRAGA
                    <br>BALAI PENDIDIKAN MENENGAH KAB. BANTUL
                    <br>SMKN 1 PAJANGAN
                </h1>
                <p>aksara jawa????</p>
                <h6 class="text-xs-medium text-center">Alamat: Pajangan, Triwidadi, Pajangan, Bantul Kode Pos: 56751
                    Telepon:
                    <br>0812#### Faksimile: smkn1pajanganbantul@gmail.com
                    <br>Laman: www.smkn1pajangan.sch.id
                </h6>
            </div>
            <div class="w-24">
                <img src="{{ url('storage/images/logo.png') }}" alt=" ">
            </div>
        </div>

        <hr class="my-4">

        <div class="space-y-6">
            <div class="text-sm">
                <h6 class="flex justify-end">Bantul, 2 Februari 2025</h6>
                <div class="flex">
                    <h6 class="min-w-16">Nomor</h6>
                    <span>: B/400.3.8.10</span>
                </div>
                <div class="flex">
                    <h6 class="min-w-16">Lamp</h6>
                    <span>: </span>
                </div>
                <div class="flex">
                    <h6 class="min-w-16">Hal.</h6>
                    <span>: Permohonan Praktik Kerja Industri</span>
                </div>
            </div>

            <div>
                <h5 class="text-sm">Kepada:
                    <br>Yth. PT Abadi Jaya
                    <br>Jl.Gajah Mada No.5, Yogyakarta
                </h5>
            </div>

            <div class="text-sm space-y-3">
                <p class="text-justify">Dalam kurikulum Sekolah Menengah Kejuruan (SMK) sangat diperlukan kerja sama
                    antara sekolah dengan
                    Dunia
                    Industri. Maka, dunia industri merupakan salah satu institusi pasangan yang nantinya diharapkan
                    sebagai
                    tempat Praktek Kerja Industri para siswa SMK.
                    <br>Kami mohon kesediaan Bapak/Ibu/Sdr. untuk dapat menerima siswa/siswi SMKN 1 Pajangan untuk dapat
                    melaksanakan Praktek Kerja Industri di Instansi yang anda pimpin untuk tahun pembelajaran 2025/2026.
                </p>
                <p>Waktu Pelaksanaan : <span>1 Juli 2025 - 2 Juli 2025</span> </p>
                <p>Adapun siswa/siswi kami tersebut adalah:</p>
                <table class="text-sm">
                    <thead>
                        <tr>
                            <th class="border py-2 px-4">NIS</th>
                            <th class="border py-2 px-4">NAMA SISWA</th>
                            <th class="border py-2 px-4">JURUSAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border py-2 px-4">2502</td>
                            <td class="border py-2 px-4">Angga</td>
                            <td class="border py-2 px-4">Rekayasa Perangkat Lunak</td>
                        </tr>
                        <tr>
                            <td class="border py-2 px-4">2503</td>
                            <td class="border py-2 px-4">Dwi</td>
                            <td class="border py-2 px-4">Rekayasa Perangkat Lunak</td>
                        </tr>
                    </tbody>
                </table>
                <p class="text-sm">Demikian surat permohonan ini dan terima kasih atas kerja samanya.</p>
            </div>
        </div>
    </div>
</body>

</html>

{{-- <h1>{{ $title ?? '' }}</h1>
<p>Tanggal: {{ $date ?? '' }}</p>
<p>Ini adalah contoh dokumen PDF yang dihasilkan dari Laravel.</p> --}}
