@extends('layouts.app')

@section('page-title', 'Logbook Bimbingan')
@section('profil', 'Advisor')
@section('content')

    <div class="layout-card">
        <x-card title="Disetujui" data="{{ $acceptedCount ?? '0' }}" class="bg-icon-success">
            <svg class="" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                fill="none">
                <path d="M16.6667 5L7.50001 14.1667L3.33334 10" stroke="white" stroke-width="3" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </x-card>
        <x-card title="Revisi" data="{{ $revisedCount ?? '0' }}" class="bg-icon-warning">
            <svg class="" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                fill="none">
                <path d="M16.6667 5L7.50001 14.1667L3.33334 10" stroke="white" stroke-width="3" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </x-card>
        <x-card title="Belum Disetujui" data="{{ $unconfirmedCount ?? '0' }}" class="bg-icon-error">
            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                <path d="M17.5 7L7.5 17M7.5 7L17.5 17" stroke="white" stroke-width="3" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </x-card>
    </div>

    <x-guide guideTitle="Persetujuan Logbook">
        <li>Guru harus menyetujui logbook mingguan jika sudah sesuai.</li>
        <li>Jika belum sesuai, guru dapat meminta revisi dan memberikan komentar sebagai masukan kepada siswa.</li>
    </x-guide>

    <x-table.table>
        <x-slot name="tableTitle">Logbook Peserta PKL</x-slot>
        <x-slot name="filterActionForm">logbook</x-slot>
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
        <x-slot name="tHeader">
            <th>No</th>
            <th>Nama</th>
            <th>Jurusan</th>
            <th>Industri</th>
            <th>Status</th>
            <th>Detail Logbook</th>
        </x-slot>
        <x-slot name="tBody">
            @foreach ($data as $dt)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $dt->name }}</td>
                    <td>{{ $dt->department->name }}</td>
                    @foreach ($dt->groupMember as $member)
                        @if ($member->group->internship)
                            <td>{{ $member->group->internship->industry->name }}</td>
                            <x-table.status_table status="{{ $dt->status }}"></x-table.status_table>
                            <td>
                                <x-table.action_btn_table
                                href="{{ route('advisor.logbook.detail', ['studentId' => $dt->id ?? '', 'internshipId' => $member->group->internship->id]) }}"
                                name="Lihat"></x-table.action_btn_table>
                            </td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </x-slot>
        {{-- pagination --}}
        <x-slot name="pagination">{{ $data->links() }}</x-slot>
    </x-table.table>

    {{-- empty state --}}
    @if (count($data) == 0)
        <x-not_found_empty_state>
            <x-slot name="desc">Belum ada logbook yang harus dikonfirmasi</x-slot>
        </x-not_found_empty_state>
    @endif

@endsection
