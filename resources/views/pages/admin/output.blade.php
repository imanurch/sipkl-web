@extends('layouts.app')

@section('page-title', 'Luaran PKL')
@section('profil', 'Admin')
@section('content')

    <div class="layout-card">
        <x-card title="Lengkap" data="{{ $completeOutputCount ?? '' }}" class="bg-icon-success">
            <svg class="" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                fill="none">
                <path d="M16.6667 5L7.50001 14.1667L3.33334 10" stroke="white" stroke-width="3" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </x-card>
        <x-card title="Tidak Lengkap" data="{{ $incompleteOutputCount ?? '' }}" class="bg-icon-error">
            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                <path d="M17.5 7L7.5 17M7.5 7L17.5 17" stroke="white" stroke-width="3" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </x-card>
    </div>

    <x-table.table>
        <x-slot name="tableTitle">Luaran Peserta PKL</x-slot>
        <x-slot name="filterActionForm">output</x-slot>
        {{-- toolbar --}}
        <x-slot name="filter">
            <div class="space-y-1 w-full">
                <span class="text-xs text-neutral-400 w-32">Search</span>
                <x-table.search value="{{ $filters['search'] ?? '' }}"></x-table.search>
            </div>
            <div class="space-y-1 w-full">
                <span class="text-xs text-neutral-400 w-32">Search</span>
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
                    <td class="text-center">{{ $data->firstItem() + $loop->index }}</td>
                    <td>{{ $dt->name }}</td>
                    <td>{{ $dt->department->name }}</td>
                    @foreach ($dt->groupMember as $member)
                        @if ($member->group->internship)
                            <td>{{ $member->group->internship->industry->name }}</td>
                            <x-table.action_btn_table
                                href="{{ route('advisor.logbook.detail', ['studentId' => $dt->id ?? '', 'internshipId' => $member->group->internship->id]) }}"
                                name="Lihat"></x-table.action_btn_table>
                        @endif
                    @endforeach
                    @if (!$dt->internDocument->isEmpty())
                        @foreach ($dt->internDocument as $doc)
                            @if ($doc->type == 'laporan akhir')
                                <x-table.action_btn_table
                                    href="{{ route('admin.output.download.finalReport', ['filename' => $doc->url]) }}"
                                    name="Lihat"></x-table.action_btn_table>
                            @else
                                <td>Belum Tersedia</td>
                            @endif
                        @endforeach
                    @else
                        <td>Belum Tersedia</td>
                    @endif
                </tr>
            @endforeach
        </x-slot>
        {{-- pagination --}}
        <x-slot name="pagination">{{ $data->links() }}</x-slot>

    </x-table.table>

@endsection
