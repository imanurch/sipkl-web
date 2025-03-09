@extends('layouts.app')

@section('page-title', 'Beranda')
@section('profil', 'Advisor')
@section('content')

    <div class="layout-card">
        <x-card title="Bimbingan PKL" data="{{ $mentee ?? '' }}" class="bg-icon-default">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                <path
                    d="M18.3334 17.5V15.8333C18.3334 14.2801 17.2711 12.9751 15.8334 12.605M12.9167 2.7423C14.1383 3.23679 15.0001 4.43443 15.0001 5.83333C15.0001 7.23224 14.1383 8.42988 12.9167 8.92437M14.1667 17.5C14.1667 15.9469 14.1667 15.1703 13.913 14.5577C13.5747 13.741 12.9258 13.092 12.109 12.7537C11.4965 12.5 10.7199 12.5 9.16675 12.5H6.66675C5.11361 12.5 4.33704 12.5 3.72447 12.7537C2.90771 13.092 2.2588 13.741 1.92048 14.5577C1.66675 15.1703 1.66675 15.9469 1.66675 17.5M11.2501 5.83333C11.2501 7.67428 9.7577 9.16667 7.91675 9.16667C6.0758 9.16667 4.58341 7.67428 4.58341 5.83333C4.58341 3.99238 6.0758 2.5 7.91675 2.5C9.7577 2.5 11.2501 3.99238 11.2501 5.83333Z"
                    stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </x-card>
        <x-card title="Industri PKL" data="{{ $industry ?? '' }}" class="bg-icon-default">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                <path
                    d="M10.8334 9.16667H14.8334C15.7668 9.16667 16.2335 9.16667 16.5901 9.34832C16.9037 9.50811 17.1586 9.76308 17.3184 10.0767C17.5001 10.4332 17.5001 10.8999 17.5001 11.8333V17.5M10.8334 17.5V5.16667C10.8334 4.23325 10.8334 3.76654 10.6518 3.41002C10.492 3.09641 10.237 2.84144 9.9234 2.68166C9.56688 2.5 9.10017 2.5 8.16675 2.5H5.16675C4.23333 2.5 3.76662 2.5 3.4101 2.68166C3.09649 2.84144 2.84153 3.09641 2.68174 3.41002C2.50008 3.76654 2.50008 4.23325 2.50008 5.16667V17.5M18.3334 17.5H1.66675M5.41675 5.83333H7.91675M5.41675 9.16667H7.91675M5.41675 12.5H7.91675"
                    stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </x-card>
    </div>

    <div class="border border-neutral-200 rounded py-5 px-8 space-y-3">
        <h2 class="text-xs-semibold pb-2 border-b border-neutral-200">Data Diri Guru Pembimbing PKL</h2>
        <div class="w-full">
            <div class="grid md:grid-cols-2 gap-y-2 gap-x-5">
                <div class="flex space-x-3 place-items-center">
                    <h6 class="text-xs-reguler w-32" for="">Nama Lengkap</h6>
                    <span>:</span>
                    <input class="input w-full" type="text" value="{{ $data->name }}" disabled>
                </div>
                <div class="flex space-x-3 place-items-center">
                    <h6 class="text-xs-reguler w-32" for="">Email</h6>
                    <span>:</span>
                    <input class="input w-full" type="text" value="{{ $data->user->email }}" disabled>
                </div>
                <div class="flex space-x-3 place-items-center">
                    <h6 class="text-xs-reguler w-32" for="">NIP</h6>
                    <span>:</span>
                    <input class="input w-full" type="text" value="{{ $data->nip }}" disabled>
                </div>
                <div class="flex space-x-3 place-items-center">
                    <h6 class="text-xs-reguler w-32" for="">Nomor Telepon</h6>
                    <span>:</span>
                    <input class="input w-full" type="text" value="{{ $data->phone_num }}" disabled>
                </div>
                <div class="flex space-x-3 place-items-center">
                    <h6 class="text-xs-reguler w-32" for="">Jurusan</h6>
                    <span>:</span>
                    <input class="input w-full" type="text" value="{{ $data->department->name }}" disabled>
                </div>
                <div class="flex space-x-3 place-items-center">
                    <h6 class="text-xs-reguler w-32" for="">Surat Tugas</h6>
                    <span>:</span>
                    @if ($data->surat_tugas != null)
                        <a href="{{ route('advisor.dashboard.downloadSuratTugas', ['filename' => $data->surat_tugas->surat_tugas]) }}"
                            class="btn btn-xs btn-default-fill w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14"
                                fill="none">
                                <path
                                    d="M12.25 5.25001L12.25 1.75001M12.25 1.75001H8.74999M12.25 1.75001L7 7M5.83333 1.75H4.55C3.56991 1.75 3.07986 1.75 2.70552 1.94074C2.37623 2.10852 2.10852 2.37623 1.94074 2.70552C1.75 3.07986 1.75 3.56991 1.75 4.55V9.45C1.75 10.4301 1.75 10.9201 1.94074 11.2945C2.10852 11.6238 2.37623 11.8915 2.70552 12.0593C3.07986 12.25 3.56991 12.25 4.55 12.25H9.45C10.4301 12.25 10.9201 12.25 11.2945 12.0593C11.6238 11.8915 11.8915 11.6238 12.0593 11.2945C12.25 10.9201 12.25 10.4301 12.25 9.45V8.16667"
                                    stroke="white" stroke-width="0.93" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span>Lihat</span>
                        </a>
                    @else
                        <span class="text-xs-reguler text-neutral-300 w-full">Belum Tersedia</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('components.contact')
@endsection
