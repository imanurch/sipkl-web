@extends('layouts.app')

@section('page-title', 'Peserta PKL')
@section('profil', 'Admin')
@section('content')

    {{-- card --}}
    <div class="layout-card">
        <x-card title="Peserta PKL" data="{{ $intern ?? '' }}" class="bg-icon-success">
            <svg class="" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                fill="none">
                <path d="M16.6667 5L7.50001 14.1667L3.33334 10" stroke="white" stroke-width="3" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </x-card>
    </div>

    <div x-data="{ modalAction: null, option: false, selected: 'Pilih Opsi', selectedValue: null }">
        <x-table.table>
            <x-slot name="tableTitle">Data Peserta Didik</x-slot>
            <x-slot name="filterActionForm">intern</x-slot>
            {{-- <x-slot name="btnAdd">
            <x-table.add_data></x-table.add_data>
        </x-slot> --}}
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
                <th>Kelompok</th>
                <th>Nama</th>
                <th>Waktu</th>
                <th>Guru Pembimbing</th>
                <th>Lokasi PKL</th>
                <th>Aksi</th>
            </x-slot>
            <x-slot name="tBody">
                @foreach ($data as $dt)
                    <tr>
                        <td class="text-center">{{ $data->firstItem() + $loop->index }}</td>
                        <td>{{ $dt->group->name ?? '' }}</td>
                        <td>
                            <ul>
                                @foreach ($dt->group->groupMember as $member)
                                    <li>{{ $loop->iteration }}. {{ $member->student->name ?? '' }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>{{ $dt->start_date ?? '' }} s/d {{ $dt->end_date ?? '' }}</td>
                        @if ($dt->advisor)
                            <td>{{ $dt->advisor->name }}</td>
                        @else
                            <td class="text-neutral-50">Belum Diatur</td>
                        @endif
                        <td>{{ $dt->industry->name ?? '' }}</td>
                        @php
                            $dt->member = $dt->group->groupMember->pluck('student.name')->toArray();
                        @endphp
                        <x-table.action_table btnInput="hidden" :data="$dt"></x-table.action_table>
                    </tr>
                @endforeach
            </x-slot>
            {{-- pagination --}}
            <x-slot name="pagination">{{ $data->links() }}</x-slot>
        </x-table.table>
        {{-- form --}}
        <div x-show="modalAction != null" class="form-modal">
            <x-form>
                <x-slot name="formTitle">Data Peserta PKL</x-slot>
                <x-slot name="formBody">
                    <div class="input-group">
                        <label class="input-label" for="">Kelompok</label>
                        <input name="name" class="input" type="text" disabled
                            :value="modalAction != null ? dataId.group.name : ''">
                    </div>
                    <div class="input-group">
                        <label class="input-label" for="">Anggota</label>
                        <input name="member" class="input" type="text" disabled
                            :value="modalAction != null ? dataId.member : ''">
                    </div>
                    <div class="input-group">
                        <label class="input-label" for="">Waktu</label>
                        <input name="time" class="input" type="text" disabled
                            :value="modalAction != null ? dataId.start_date + ' s/d ' + dataId.end_date : ''">
                    </div>
                    <div class="input-group">
                        <label class="input-label" for="">Guru Pembimbing</label>
                        <input x-show="modalAction != 'isEdit'" class="input" type="text" disabled
                            :value="modalAction != 'isEdit' ? dataId.advisor.name : ''">
                        <div x-show="modalAction == 'isEdit'">
                            <input name="advisor_id" type="hidden" :value="modalAction == 'isEdit' ? selectedValue : ''">
                            <button @click.prevent="option=!option" class="input input-select w-full">
                                <span
                                    x-text="selected != 'Pilih Opsi' ? selected : (modalAction == 'isEdit' ? dataId.advisor.name : selected)"></span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                    fill="none">
                                    <path d="M5 7.5L10 12.5L15 7.5" stroke="#667085" stroke-width="0.933333"
                                        stroke-linecap="round" stroke-linejoin="round" :hidden="isDelete" />
                                </svg>
                            </button>
                            <div x-show="option" @click.away="option=false">
                                <ul class="border border-brand-600 rounded py-2 my-2 max-h-32 overflow-auto">
                                    @foreach ($advisorListData as $dt)
                                        <li @click.prevent="option=false;selected='{{ $dt->name }}';selectedValue='{{ $dt->id }}'"
                                            class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">
                                            {{ $dt->name }} <br>NIP{{ $dt->nip }} | {{ $dt->department->name }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="input-group">
                        <label class="input-label" for="">Lokasi PKL</label>
                        <input name="industry" class="input" type="text" disabled
                            :value="modalAction != null ? dataId.industry.name : ''" required>
                    </div>
                </x-slot>
            </x-form>
        </div>
    </div>

    {{-- empty state --}}
    @if (count($data) == 0)
        <x-not_found_empty_state>
            <x-slot name="desc">Belum ada siswa yang terdaftar PKL batch ini</x-slot>
        </x-not_found_empty_state>
    @endif

    {{-- script Modal Action --}}
    <script>
        function setFormAction(modalAction, id = null) {
            const form = document.getElementById('modalForm');
            if (modalAction === 'isEdit' && id) {
                form.action = `{{ route('admin.intern.updateAdvisor', ':id') }}`.replace(':id', id);
            } else if (modalAction === 'isDelete' && id) {
                form.action = `{{ route('admin.intern.destroy', ':id') }}`.replace(':id', id);
            }
        }
    </script>

@endsection
