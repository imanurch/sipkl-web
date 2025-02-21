@extends('layouts.app')

@section('page-title', 'Laporan Akhir')
@section('profil', 'Student')
@section('content')

    <x-guide guideTitle="Unggah Laporan Akhir">
        <li>Unggah laporan akhir dalam <span class="text-xs-semibold">format PDF</span> melalui form yang tersedia</li>
        <li>Jika mengunggah lebih dari 1x, file sebelumnya akan tergantikan dengan yang terakhir diunggah</li>
        <li>Periksa kembali file yang telah diunggah melalui tombol Lihat File</li>
        <li>Laporan Akhir sebagai syarat penilaian PKL</li>
    </x-guide>

    @if ($isIntern == true)
        <div class="space-y-4">
            <form action="{{ route('student.finalReport.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="flex space-x-3 place-items-center">
                    <h6 class="w-28 text-xs-reguler">Unggah File</h6>
                    <div>
                        <input name="laporan_akhir" type="file" class="input">
                    </div>
                    <button type="submit" class="btn btn-xs btn-default-fill">Kirim File</button>
                </div>
            </form>
            @if ($data != null)
                <div class="flex place-items-center space-x-2">
                    <span class="text-xs-reguler">Lihat File (Diunggah pada {{ $data->updated_at }}) :</span>
                    <a href="{{ route('student.finalReport.downloadLaporanAkhir', ['filename' => $data->url]) }}"
                        class="btn btn-xs btn-default-fill">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14"
                            fill="none">
                            <path
                                d="M12.25 5.25001L12.25 1.75001M12.25 1.75001H8.74999M12.25 1.75001L7 7M5.83333 1.75H4.55C3.56991 1.75 3.07986 1.75 2.70552 1.94074C2.37623 2.10852 2.10852 2.37623 1.94074 2.70552C1.75 3.07986 1.75 3.56991 1.75 4.55V9.45C1.75 10.4301 1.75 10.9201 1.94074 11.2945C2.10852 11.6238 2.37623 11.8915 2.70552 12.0593C3.07986 12.25 3.56991 12.25 4.55 12.25H9.45C10.4301 12.25 10.9201 12.25 11.2945 12.0593C11.6238 11.8915 11.8915 11.6238 12.0593 11.2945C12.25 10.9201 12.25 10.4301 12.25 9.45V8.16667"
                                stroke="white" stroke-width="0.93" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span>Lihat Laporan</span>
                    </a>
                </div>
                <div class="flex place-items-center text-xs-reguler">
                    <h6 class="w-28">Status Penilaian :</h6>
                    <div class="tag-status">
                        <div class="tag-status-icon {{ $data->isAssessed == true ? 'bg-icon-success' : 'bg-icon-error' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="14" viewBox="0 0 15 14"
                                fill="none">
                                <path
                                    d="{{ $data->isAssessed == true ? 'M12.1667 3.5L5.75001 9.91667L2.83334 7' : 'M10.4167 4.08325L4.58337 9.91659M4.58337 4.08325L10.4167 9.91659' }}"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <span>{{ $data->isAssessed == true ? 'Sudah Dinilai' : 'Belum Dinilai' }}</span>
                    </div>
                </div>
            @endif
        </div>
    @endif

@endsection
