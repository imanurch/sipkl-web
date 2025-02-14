@extends('layouts.app')

@section('page-title', 'Peserta PKL')
@section('profil', 'Advisor')
@section('content')

<x-table.table>
    <x-slot name="tableTitle">Data Peserta PKL</x-slot>      
    <x-slot name="actionForm">intern</x-slot>
    {{-- toolbar --}}
    <x-slot name="toolbar">
        <x-slot name="filter">
            <x-table.search value="{{ $filters['search'] ?? '' }}"></x-table.search>
            <x-table.select_option_filter optionName="batch" defaultSelected="{{ $filters['batch_id'] != '' ? $filters['batch_id'] : 'Semua batch' }}">
                <x-slot name="option">
                    @foreach ($batchData as $dt)
                    <li class="option-filter-toolbar-table" @click="option=false;selected='{{ $dt->name }}';valueSelected='{{ $dt->id }}'">{{ $dt->name }}</li>
                    @endforeach
                </x-slot>
            </x-table.select_option_filter>
        </x-slot>
    </x-slot>
    {{-- table --}}
    <x-slot name="tHeader">                    
        <th>NO</th>
        <th>NAMA</th>
        <th>JURUSAN</th>
        <th>NISN</th>
        <th>JENIS KELAMIN</th>
        <th>NOMOR TELEPON</th>
        <th>LOKASI PKL</th>
    </x-slot>
    <x-slot name="tBody">
        @foreach ($data as $dt)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>              
                <td>{{ $dt->name }}</td>
                <td>{{ $dt->department->name }}</td>      
                <td>{{ $dt->nisn }}</td>
                <td>{{ $dt->gender }}</td>
                <td>{{ $dt->phone_num }}</td>
                <td>{{ $dt->groupMember->group->internship->industry->name }}</td>
            </tr>
        @endforeach
    </x-slot>
    {{-- pagination --}}
    <x-slot name="pagination">{{ $data->links() }}</x-slot>
</x-table.table>

@endsection