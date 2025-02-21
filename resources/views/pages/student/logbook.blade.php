@extends('layouts.app')

@section('page-title', 'Logbook')
@section('profil', 'Student')
@section('content')

    <x-guide guideTitle="Pengisian Logbook">
        <li>Isi logbook sesuai periode mingguan yang telah ditentukan</li>
        <li>Status Logbook
            <ul class="list-decimal ps-6">
                <li><span class="text-xs-semibold">Belum Diisi</span> : Logbook belum diisi</li>
                <li><span class="text-xs-semibold">Menunggu Konfirmasi</span> : Menunggu persetujuan dosen pembimbing</li>
                <li><span class="text-xs-semibold">Perlu Revisi</span> : Harus diperbaiki sesuai arahan dosen pembimbing</li>
                <li><span class="text-xs-semibold">Disetujui</span> : Logbook telah disetujui</li>
            </ul>
        </li>
        <li>Logbook harus diisi dan disetujui dosen pembimbing sebagai syarat penilaian PKL</li>
    </x-guide>

    @if (count($data) > 0)
        <div class="flex justify-between place-items-center">
            <h6 class="text-sm-semibold">Logbook Peserta PKL</h6>
            <button class="btn btn-xs btn-default-fill">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                    <path
                        d="M12.25 8.75V9.45C12.25 10.4301 12.25 10.9201 12.0593 11.2945C11.8915 11.6238 11.6238 11.8915 11.2945 12.0593C10.9201 12.25 10.4301 12.25 9.45 12.25H4.55C3.56991 12.25 3.07986 12.25 2.70552 12.0593C2.37623 11.8915 2.10852 11.6238 1.94074 11.2945C1.75 10.9201 1.75 10.4301 1.75 9.45V8.75M9.91667 5.83333L7 8.75M7 8.75L4.08333 5.83333M7 8.75V1.75"
                        stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span>Unduh Logbook</span>
            </button>
        </div>
    @endif

    <div x-data="{ modalAction: null, id: null }">
        <div class="space-y-4">
            @foreach ($data as $month => $logs)
                <x-logbook.content_group_logbook month="{{ $month }}">
                    <x-slot name="logbookContent">
                        <div class="flex space-x-2">
                            @foreach ($logs as $log)
                                <x-logbook.card_logbook :data="$log" :week="$loop->iteration"></x-logbook.card_logbook>
                            @endforeach
                        </div>
                    </x-slot>
                </x-logbook.content_group_logbook>
            @endforeach
        </div>

        <div x-show="modalAction != null" class="form-modal">
            <form :action="`{{ route('student.logbook.update', ['id' => '__ID__']) }}`.replace('__ID__', id)" class="form"
                method="POST" id="modalForm" @click.away="modalAction=null">
                <div class="form-header">
                    @csrf
                    @method('PATCH')
                    <h3><span x-text="modalAction=='isAdd' ? 'Tambah' : 'Ubah'"></span> Logbook</h3>
                    <svg @click="modalAction=null,selected='Pilih Opsi'" class="cursor-pointer"
                        xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28" fill="none">
                        <path d="M19.8333 8.16675L8.16663 19.8334M8.16663 8.16675L19.8333 19.8334" stroke="#525A6A"
                            stroke-width="1.03704" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div class="form-body">
                    {{-- <input type="text" :value="modalAction != null ? id : ''"> --}}
                    <div class="input-group">
                        <label class="input-label" for="">Tanggal</label>
                        <input class="input" type="text"
                            :value="modalAction != null ? dataId.start_date + ' s/d ' + dataId.end_date : ''" disabled>
                    </div>
                    <div class="input-group">
                        <label class="input-label" for="">Kegiatan</label>
                        <textarea class="input" name="activities" id="" rows="8"
                            :value="modalAction == 'isEdit' ? dataId.activities : ''" required></textarea>
                    </div>
                    <div x-show="modalAction == 'isEdit'" class="input-group">
                        <label class="input-label" for="">Komentar Guru Pembimbing</label>
                        <textarea class="input" name="feedback" id="" rows="8"
                            :value="modalAction == 'isEdit' ? dataId.feedback : ''" required></textarea>
                    </div>
                </div>
                <div class="form-footer">
                    <button @click="modalAction=null" class="btn btn-error-fill btn-sm">
                        <span>Batalkan</span>
                    </button>
                    <button type="submit" class="btn btn-success-fill btn-sm">
                        <span x-text="modalAction=='isAdd' ? 'Tambah' : 'Ubah'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
