@extends('layouts.app')

@section('page-title', 'Logbook Bimbingan')
@section('profil', 'Advisor')
@section('content')

<div class="layout-card">
    <x-card title="Disetujui" data="90" class="bg-icon-success">
        <svg class="" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
            <path d="M16.6667 5L7.50001 14.1667L3.33334 10" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>   
    </x-card>
    <x-card title="Revisi" data="90" class="bg-icon-warning">
        <svg class="" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
            <path d="M16.6667 5L7.50001 14.1667L3.33334 10" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>   
    </x-card>
    <x-card title="Belum Disetujui" data="90" class="bg-icon-error">
        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
            <path d="M17.5 7L7.5 17M7.5 7L17.5 17" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </x-card>
</div>

<x-guide guideTitle="Bimbingan">
    <li>Guide 1</li>
    <li>Guide 2</li>
</x-guide>

<x-table>
    <x-slot name="tableTitle">Jurnal Mingguan Peserta PKL</x-slot>    
    <x-slot name="filter">
        <x-table.filter></x-table.filter>
    </x-slot>
    <x-slot name="tHeader">                    
        <th>NO</th>
        <th>NAMA</th>
        <th>JURUSAN</th>
        <th>INDUSTRI</th>
        <th>JURNAL</th>
        {{-- <th>AKSI</th> --}}
    </x-slot>
    <x-slot name="tBody">
        @foreach ($data as $dt)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>              
                <td>{{ $dt->name }}</td>
                <td>{{ $dt->department->name }}</td>        
                <td>{{ $dt->industry }}</td>
                <x-action_btn_table name="Lihat"></x-action_btn_table>
                {{-- <x-action_table detail="hidden" :data="$dt"></x-action_table> --}}
            </tr>
        @endforeach
    </x-slot>
</x-table>

@endsection