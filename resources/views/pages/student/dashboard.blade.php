@extends('layouts.app')

@section('page-title', 'Beranda')
@section('profil', 'Student')
@section('content')

    <h6 class="text-md-reguler">Selamat Datang di Sistem Informasi Praktik Kerja Lapangan SMK Negeri 1 Pajangan!</h6>

    {{-- student data --}}
    <div class="border border-neutral-200 rounded py-5 px-8 space-y-3">
        <h2 class="text-xs-semibold pb-2 border-b border-neutral-200">DATA DIRI PESERTA DIDIK</h2>
        <div class="w-full">
            <div class="grid md:grid-cols-2 gap-y-2 gap-x-5">
                <div class="flex space-x-3 place-items-center">
                    <h6 class="text-xs-reguler w-32" for="">Nama Lengkap</h6>
                    <span>:</span>
                    <input class="input w-full" type="text" value="{{ session('user_bio')->name ?? '' }}" disabled>
                </div>
                <div class="flex space-x-3 place-items-center">
                    <h6 class="text-xs-reguler w-32" for="">Jurusan</h6>
                    <span>:</span>
                    <input class="input w-full" type="text" value="{{ session('user_bio')->department->name ?? '' }}"
                        disabled>
                </div>
                <div class="flex space-x-3 place-items-center">
                    <h6 class="text-xs-reguler w-32" for="">NISN</h6>
                    <span>:</span>
                    <input class="input w-full" type="text" value="{{ session('user_bio')->nisn ?? '' }}" disabled>
                </div>
                <div class="flex space-x-3 place-items-center">
                    <h6 class="text-xs-reguler w-32" for="">Nomor Telepon</h6>
                    <span>:</span>
                    <input class="input w-full" type="text" value="{{ session('user_bio')->phone_num ?? '' }}" disabled>
                </div>
                <div class="flex space-x-3 place-items-center">
                    <h6 class="text-xs-reguler w-32" for="">Jenis Kelamin</h6>
                    <span>:</span>
                    <input class="input w-full" type="text"
                        value="{{ session('user_bio')->gender == 'men' ? 'Laki-Laki' : 'Perempuan' }}" disabled>
                </div>
            </div>
        </div>
    </div>

    {{-- internship data --}}
    @if ($internshipData != null)
        <div class="border border-neutral-200 rounded py-5 px-8 space-y-3">
            <h2 class="text-xs-semibold pb-2 border-b border-neutral-200">DATA PKL</h2>
            <div class="w-full">
                <div class="grid md:grid-cols-2 gap-y-2 gap-x-5">
                    <div class="flex space-x-3 place-items-center">
                        <h6 class="text-xs-reguler w-32" for="">Nama Industri</h6>
                        <span>:</span>
                        <input class="input w-full" type="text" value="{{ $internshipData->industry->name ?? '' }}"
                            disabled>
                    </div>
                    <div class="flex space-x-3 place-items-center">
                        <h6 class="text-xs-reguler w-32" for="">Guru Pembimbing</h6>
                        <span>:</span>
                        <input class="input w-full" type="text"
                            value="{{ $internshipData->advisor->name ?? '' }} ({{ $internshipData->advisor->phone_num ?? '' }})"
                            disabled>
                    </div>
                    <div class="flex space-x-3 place-items-center">
                        <h6 class="text-xs-reguler w-32" for="">Alamat</h6>
                        <span>:</span>
                        <input class="input w-full" type="text" value="{{ $internshipData->industry->address ?? '' }}"
                            disabled>
                    </div>
                    <div class="flex space-x-3 place-items-center">
                        <h6 class="text-xs-reguler w-32" for="">Status</h6>
                        <span>:</span>
                        <input class="input w-full" type="text" value="" disabled>
                    </div>
                    <div class="flex space-x-3 place-items-center">
                        <h6 class="text-xs-reguler w-32" for="">Waktu Pelaksanaan</h6>
                        <span>:</span>
                        <input class="input w-full" type="text"
                            value="{{ $internshipData->start_date ?? '' }} s/d {{ $internshipData->end_date ?? '' }}"
                            disabled>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @include('components.contact')
@endsection
