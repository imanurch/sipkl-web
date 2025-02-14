@extends('layouts.app')

@section('page-title', 'Luaran PKL')
@section('profil', 'Admin')
@section('content')

<div class="layout-card">
    <x-card title="Lengkap" data="{{ $completeOutput ?? '' }}" class="bg-icon-success">
        <svg class="" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
            <path d="M16.6667 5L7.50001 14.1667L3.33334 10" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>  
    </x-card>
    <x-card title="Tidak Lengkap" data="{{ $incompleteOutput ?? '' }}" class="bg-icon-error">
        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
            <path d="M17.5 7L7.5 17M7.5 7L17.5 17" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </x-card>
</div>

<x-table.table>
    <x-slot name="tableTitle">Luaran Peserta PKL</x-slot>    
    <x-slot name="actionForm">output</x-slot>
        <x-slot name="toolbar">
            <x-slot name="filter">
                <x-table.search value="{{ $filters['search'] ?? '' }}" name="searchKeyword"></x-table.search>
                <x-table.select_option_filter optionName="batch" defaultSelected="{{ $filters['batch_id'] != '' ? $filters['batch_id'] : 'Semua batch' }}">
                    <x-slot name="option">
                        @foreach ($batchData as $dt)
                        <li class="option-filter-toolbar-table" @click="option=false;selected='{{ $dt->name }}';valueSelected='{{ $dt->id }}'">{{ $dt->name }}</li>
                        @endforeach
                    </x-slot>
                </x-table.select_option_filter>
            </x-slot>
        </x-slot>
    <x-slot name="tHeader">                    
        <th>NO</th>
        <th>NAMA</th>
        <th>JURUSAN</th>
        <th>LOKASI PKL</th>
        <th>LOGBOOK</th>
        <th>LAPORAN AKHIR</th>
    </x-slot>
    <x-slot name="tBody">
        @foreach ($data as $dt)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>              
                <td>{{ $dt->name }}</td>
                <td>{{ $dt->department->name }}</td>      
                <td>{{ $dt->groupMember }}</td> 
                {{-- <td>{{ $dt->groupMember->group->internship->industry->name }}</td>  --}}
                <x-table.action_btn_table href="{{ route('admin.outputAdmin.logbook', ['batch_id'=> $filters['batch_id'] ?? '', 'id' => $dt->id]) }}" name="Lihat"></x-table.action_btn_table>
                <x-table.action_btn_table href="" name="Lihat"></x-table.action_btn_table>
            </tr>
        @endforeach
    </x-slot>
</x-table.table>        

@endsection