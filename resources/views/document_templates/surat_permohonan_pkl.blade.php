<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

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

    .page-break {
        page-break-before: always;
    }

    .align-right {
        text-align: right;
    }

    .float-right {
        float: right;
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

<body>
    {{-- page 1 --}}
    <div>
        @include('document_templates/kop_surat')
        <div>
            <p class="align-right">Bantul, {{ $create_date ?? '' }}</p>
        </div>
        <div>
            <span style="display: inline-block;min-width: 4rem">No</span><span style="display: inline-block">:
                {{ $letter_num ?? '' }}</span>
            <br><span style="display: inline-block;min-width: 4rem">Hal</span><span style="display: inline-block">:
                Permohonan Tempat Praktik Kerja
                Lapangan (PKL)</span>
            <br><span style="display: inline-block;min-width: 4rem">Lamp.</span><span style="display: inline-block">: 2
                eks</span>
        </div>
        <div>
            <p>Yth. Kepala {{ $industry_name ?? '' }} <br>di {{ $industry_address ?? '' }}</br>
        </div>
        <div>
            <p>Dengan hormat,</p>
            <p>Disampaikan kepada Bapak/ Ibu/ Saudara Pimpinan, bahwa dalam rangka pelaksanaan Praktik Kerja Lapangan
                (PKL) sesuai dengan kurikulum yang berlaku guna memantapkan kemampuan, mendapatkan kompetensi/
                subkompetensi, dan memberikan pengalaman pada situasi kerja yang sesungguhnya di instansi/ perusahaan
                bagi
                siswa kami, maka dengan ini kami memohon kepada Bapak/ Ibu/ Saudara Pimpinan/ Direktur dari Dunia Usaha/
                Dunia Industri (DU/DI) supaya siswa kami dapat melaksanakan PKL di instansi yang Bapak/ Ibu/ Saudara
                pimpin
                pada tahun pelajaran {{ $academic_year ?? '#####' }}.</p>
            <p>Pelaksanaan PKL pada bulan {{ $internship_start_month ?? '#####' }} s.d.
                {{ $internship_end_month ?? '#####' }}
                {{ $internship_year ?? '#####' }}. Pembimbing direncanakan dan diharapkan
                dari guru SMK Negeri 1 Pajangan-Bantul dan instruktur dari perusahaan/ instansi yang Bapak/ Ibu/ Saudara
                pimpin.
            </p>
            <p>Demikian permohonan ini kami sampaikan. Atas kesempatan yang diberikan serta kerja sama yang baik, kami
                ucapkan terima kasih.</p>
        </div>
        <div class="float-right">
            <p style="margin: 0">Hormat kami,<br>Kepala SMK Negeri 1 Pajangan</p>
            <img src="{{ public_path('storage/signatures/' . $principal_signature) }}" alt="" width="100">
            <p style="margin: 0">{{ $principal_name ?? '' }}<br>NIP. {{ $principal_nip ?? '' }}</p>
        </div>
    </div>

    {{-- page 2 --}}
    <div class="page-break">
        <div>
            <span style="display: inline-block;min-width: 6rem">Lampiran</span><span style="display: inline-block">:
                Surat Kepala SMK Negeri 1
                Pajangan</span>
            <br><span style="display: inline-block;min-width: 6rem">No</span><span style="display: inline-block">:
                {{ $letter_num ?? '' }}</span>
            <br><span style="display: inline-block;min-width: 6rem">Tanggal</span><span>:
                {{ $create_date ?? '' }}</span>
            <br><span style="display: inline-block;min-width: 6rem">Hal</span><span style="display: inline-block">:
                Permohonan Tempat Praktik Kerja
                Lapangan (PKL)</span>
        </div>
        <hr style="margin: 0">
        <div>
            <h1 style="font-size:1rem;text-align:center">DAFTAR USULAN PESERTA PRAKTIK KERJA LAPANGAN (PKL)
                </br>SMK NEGERI 1 PAJANGAN
        </div>
        <div>
            <p>DUDI: {{ $industry_name ?? '#####' }}</p>
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Kompetensi Keahlian</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($intern_group_data as $dt)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            {{-- harusnya NIS bukan NISN --}}
                            <td>{{ $dt->student->nisn }}</td>
                            <td style="text-align: left; width:10rem;">{{ $dt->student->name }}</td>
                            <td>XI</td>
                            <td style="text-align: left; width:16rem;">
                                {{ $dt->student->department->name == 'RPL' ? 'Rekayasa Perangkat Lunak' : ($dt->student->department->name == 'DPIB' ? 'Desain Pemodelan dan Informasi Bangunan' : 'Kriya Kayu dan Rotan') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="float-right">
            <p style="margin: 0">Hormat kami,<br>Kepala SMK Negeri 1 Pajangan</p>
            <img src="{{ public_path('storage/signatures/' . $principal_signature) }}" alt="" width="100">
            <p style="margin: 0">{{ $principal_name ?? '' }}<br>NIP. {{ $principal_nip ?? '' }}</p>
        </div>
    </div>

    {{-- page 3 --}}
    <div class="page-break">
        <div>
            <p>Yth. {{ $industry_name ?? '' }} <br>di {{ $industry_address ?? '' }}</br>
        </div>
        <div>
            <p>Dengan hormat,</p>
            <p> Menanggapi surat Saudara Nomor: {{ $letter_num ?? '#####' }} tanggal {{ $create_date ?? '#####' }}
                perihal sebagaimana
                pokok surat, dengan ini
                kami beritahukan bahwa kami <span style="font-weight: 500">menerima/ tidak menerima</span> siswa Bapak
                untuk melaksanakan PKL sesuai jadwal bulan {{ $internship_start_month ?? '#####' }}
                s.d.{{ $internship_end_month ?? '#####' }}
                {{ $internship_year ?? '#####' }}, yaitu atas nama:
            </p>

            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Kompetensi Keahlian</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($intern_group_data as $dt)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            {{-- harusnya NIS bukan NISN --}}
                            <td>{{ $dt->student->nisn }}</td>
                            <td style="text-align: left; width:10rem;">{{ $dt->student->name }}</td>
                            <td>XI</td>
                            <td style="text-align: left; width:16rem;">
                                {{ $dt->student->department->name == 'RPL' ? 'Rekayasa Perangkat Lunak' : ($dt->student->department->name == 'DPIB' ? 'Desain Pemodelan dan Informasi Bangunan' : 'Kriya Kayu dan Rotan') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <p>Demikian untuk menjadikan maklum. Atas perhatian Bapak, kami ucapkan terima kasih.</p>
        </div>
        <div class="float-right">
            <p style="margin-bottom: 5rem">_____________, ____________________</p>
            <p>................................</p>
        </div>
        <div style="margin-top:12rem">
            <p>Catatan:
                <br>- Lembar ini wajib dikembalikan kepada Kepala Program Keahlian masing-masing.
            </p>
        </div>
    </div>
</body>

</html>
