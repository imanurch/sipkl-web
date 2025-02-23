@extends('layouts.app')

@section('page-title', 'Peserta PKL')
@section('profil', 'Advisor')
@section('content')

    <x-table.table>
        <x-slot name="tableTitle">Data Peserta PKL</x-slot>
        <x-slot name="filterActionForm">intern</x-slot>
        {{-- toolbar --}}
        <x-slot name="filter">
            <div class="flex w-full space-x-2">
                <div class="space-y-1 w-full">
                    <span class="text-xs text-neutral-400 w-32">Search</span>
                    <x-table.search value="{{ $filters['search'] ?? '' }}"></x-table.search>
                </div>
            </div>
            <div class="flex w-full space-x-2">
                <div class="space-y-1 w-full">
                    <span class="text-xs text-neutral-400 w-32">Batch</span>
                    <x-table.select_option_filter optionName="batch"
                        defaultSelected="{{ $filters['batch_id'] != '' ? $filters['batch_id'] : 'Semua batch' }}">
                        <x-slot name="option">
                            @foreach ($batchData as $dt)
                                <li class="option-filter-toolbar-table"
                                    @click="option=false;selected='{{ $dt->name }}';valueSelected='{{ $dt->id }}'">
                                    {{ $dt->name }}</li>
                            @endforeach
                        </x-slot>
                    </x-table.select_option_filter>
                </div>
            </div>
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
                    <td>{{ $dt->gender == 'men' ? 'Laki-Laki' : 'Perempuan' }}</td>
                    <td>{{ $dt->phone_num }}</td>
                    <td>
                        @foreach ($dt->groupMember as $member)
                            @if ($member->group->internship)
                                {{ $member->group->internship->industry->name }}
                            @endif
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </x-slot>
        {{-- pagination --}}
        <x-slot name="pagination">{{ $data->links() }}</x-slot>
    </x-table.table>

    {{-- empty state --}}
    @if (count($data) == 0)
        <x-not_found_empty_state>
            <x-slot name="desc">Belum ada siswa bimbingan PKL</x-slot>
        </x-not_found_empty_state>
    @endif

@endsection
