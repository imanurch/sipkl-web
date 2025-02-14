@extends('layouts.app')

@section('page-title', 'Penilaian PKL')
@section('profil', 'Advisor')
@section('content')

<div class="layout-card">
    <x-card title="Sudah Dinilai" data="90" class="bg-icon-success">
        <svg class="" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
            <path d="M16.6667 5L7.50001 14.1667L3.33334 10" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>   
    </x-card>
    <x-card title="Belum Dinilai" data="90" class="bg-icon-error">
        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
            <path d="M17.5 7L7.5 17M7.5 7L17.5 17" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </x-card>
</div>

<x-guide guideTitle="Penilaian">
    <li>Guide 1</li>
    <li>Guide 2</li>
</x-guide>

<div x-data="{ modal:false }" >
    <x-table>
        <x-slot name="tableTitle">Penilaian PKL</x-slot>    
        <x-slot name="filter">
            <x-table.filter></x-table.filter>
        </x-slot>
        <x-slot name="tHeader">                    
            <th>NO</th>
            <th>NAMA</th>
            <th>INDUSTRI</th>
            <th>LOGBOOK</th>
            <th>LAPORAN AKHIR</th>
            <th>NILAI AKHIR</th>
            <th>AKSI</th>
        </x-slot>
        <x-slot name="tBody">
            @foreach ($data as $dt)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>              
                    <td>{{ $dt->name }}</td>     
                    <td>{{ $dt->industry }}
                    </td><x-status_table status="0" :statusName="$statusName"></x-status_table>                           
                    <x-action_btn_table name="Lihat"></x-action_btn_table>
                    <td>{{ $dt->final_score }}</td>
                    {{-- <x-action_btn_table name="Input Nilai"></x-action_btn_table> --}}
                    <x-action_table detail="hidden" edit="hidden" delete="hidden" :data=$dt></x-action_table>
                    {{-- <td class="text-center">
                        <button @click="modal=true;dataId={{ $dt->toJson() }}" class="btn btn-xs btn-default-fill">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="14" viewBox="0 0 15 14" fill="none">
                                <path d="M7.49984 2.91669V11.0834M3.4165 7.00002H11.5832" stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                              </svg>
                            <span>Input Nilai</span>
                        </button>
                    </td> --}}
                </tr>
            @endforeach
        </x-slot>
    </x-table>
    <div x-show="modal" class="w-full" x-data="dataId:{}">
        <x-form>
            <x-slot name="formTitle">Form Penilaian</x-slot>
            <x-slot name="formBody" >
                <div class="input-group">
                    <label class="input-label" for="">Nama</label>
                    <input name="name" class="input" type="text" disabled :value=" modal ? dataId.name : ''">    
                </div>
                <div class="input-group">
                    <label class="input-label" for="">Jurusan</label>
                    <input name="department" class="input" type="text" disabled :value=" modal ? dataId.department : ''">    
                </div>
                <div class="input-group">
                    <label class="input-label" for="">Lokasi PKL</label>
                    <input name="industry" class="input" type="text" disabled :value=" modal ? dataId.industry">    
                </div>                
                <div class="input-group">
                    <label class="input-label" for="">Nilai</label>
                    <input name="advisor_score" class="input" type="text"  :disabled="isShow" :value="modal ? dataId.advisor_score : ''" required>    
                </div>
            </x-slot>
        </x-form>
    </div>
</div>    

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection