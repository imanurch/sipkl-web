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

    <div x-data="{ modalAction: null, option: false, selected: 'Pilih Opsi', selectedValue: null, generateDocumentModal: false }">
        <x-table.table>
            <x-slot name="tableTitle">Data Peserta Didik</x-slot>
            <x-slot name="filterActionForm">intern</x-slot>
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
                <th>Surat Jalan</th>
                <th>Aksi</th>
            </x-slot>
            <x-slot name="tBody">
                @foreach ($data as $dt)
                    <tr>
                        <td class="text-center">{{ $data->firstItem() + $loop->index }}</td>
                        <td>{{ $dt->group->name ?? '' }}</td>
                        <td>
                            <ul class="text-left">
                                @foreach ($dt->group->groupMember as $member)
                                    @if ($loop->iteration == '4')
                                        <li>dst</li>
                                        @break

                                    @else
                                        <li class="whitespace-nowrap overflow-hidden text-ellipsis block max-w-40">
                                            {{ $loop->iteration }}. {{ $member->student->name ?? '' }}
                                            ({{ $member->student->department->name ?? '' }})
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </td>
                        <td class="whitespace-nowrap">{{ $dt->start_date }} <br>-
                            <br>{{ $dt->end_date }}
                        </td>
                        <td>
                            @if ($dt->advisor)
                                {{ $dt->advisor->name }}
                            @else
                                <button
                                    @click="setFormAction('isEdit', {{ $dt->id }});modalAction='isEdit';dataId={{ $dt->toJson() }};teamMember({{ $dt->group->groupMember->toJson() }})"
                                    class="btn btn-xs btn-default-fill text-nowrap">Pilih Guru
                                </button>
                            @endif
                        </td>
                        <td class="min-w-48">{{ $dt->industry->name ?? '' }}</td>
                        <td>
                            @if ($dt->surat_jalan)
                                <div class="flex justify-center space-x-2">
                                    @foreach ($dt->internDocument as $doc)
                                        {{-- {{ $doc }} --}}
                                        @if ($doc->type == 'surat jalan')
                                            <x-table.action_btn_table name="{{ $doc->student->name }}"
                                                href="{{ route('admin.intern.downloadFile', ['filename' => $doc->url]) }}"></x-table.action_btn_table>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <button
                                    @click.prevent="generateDocumentModal=true;dataId={{ $dt }};generateSuratJalan({{ $dt->group->groupMember }})"
                                    class="btn btn-xs btn-success-fill min-w-max">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        viewBox="0 0 18 18" fill="none">
                                        <path
                                            d="M7.87464 10.1251L15.7496 2.25013M7.97033 10.3712L9.94141 15.4397C10.1151 15.8862 10.2019 16.1094 10.327 16.1746C10.4354 16.2311 10.5646 16.2312 10.6731 16.1748C10.7983 16.1098 10.8854 15.8866 11.0596 15.4403L16.0023 2.77453C16.1595 2.37164 16.2381 2.1702 16.1951 2.04148C16.1578 1.92969 16.0701 1.84197 15.9583 1.80462C15.8296 1.76162 15.6281 1.84023 15.2252 1.99746L2.55943 6.94021C2.11313 7.11438 1.88997 7.20146 1.82494 7.32664C1.76857 7.43516 1.76864 7.56434 1.82515 7.67279C1.89033 7.7979 2.11358 7.88472 2.56009 8.05836L7.62859 10.0294C7.71923 10.0647 7.76455 10.0823 7.80271 10.1095C7.83653 10.1337 7.86611 10.1632 7.89024 10.1971C7.91746 10.2352 7.93508 10.2805 7.97033 10.3712Z"
                                            stroke="" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <span class="">Generate Dokumen</span>
                                </button>
                            @endif
                        </td>
                        <x-table.action_table :data="$dt" registrationTeamMember=true></x-table.action_table>
                        {{-- teamMember({{ $dt->group->groupMember->toJson() }}) --}}
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
                        <label class="input-label" for="">Anggota Kelompok</label>
                        <div id="memberField" class="space-y-2">
                            {{-- memberField --}}
                        </div>
                    </div>
                    <div class="input-group">
                        <label class="input-label" for="">Waktu</label>
                        <input name="time" class="input" type="text" disabled
                            :value="modalAction != null ? dataId.start_date + ' - ' + dataId.end_date : ''">
                    </div>
                    <div class="input-group">
                        <label class="input-label" for="">Guru Pembimbing</label>
                        <input x-show="modalAction != 'isEdit'" class="input" type="text" disabled
                            :value="modalAction != 'isEdit' ? dataId.advisor.name : ''">
                        <div x-show="modalAction == 'isEdit'">
                            <input name="advisor_id" type="hidden" :value="modalAction == 'isEdit' ? selectedValue : ''">
                            <button @click.prevent="option=!option" class="input input-select w-full">
                                <span x-text="selected"></span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                    fill="none">
                                    <path d="M5 7.5L10 12.5L15 7.5" stroke="#667085" stroke-width="0.933333"
                                        stroke-linecap="round" stroke-linejoin="round" />
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

        {{-- generate doc --}}
        <div x-show="generateDocumentModal" class="form-modal ">
            <form class="form w-[55%]" action="{{ route('admin.intern.generateDocument') }}" method="POST"
                @click.away="generateDocumentModal=false">
                <div class="form-header">
                    @csrf
                    <h3>Generate Dokumen Surat Jalan</h3>
                    <svg @click="generateDocumentModal=false,selected='Pilih Opsi'" class="cursor-pointer"
                        xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28"
                        fill="none">
                        <path d="M19.8333 8.16675L8.16663 19.8334M8.16663 8.16675L19.8333 19.8334" stroke="#525A6A"
                            stroke-width="1.03704" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div class="form-body space-y-0">
                    <input type="hidden" name="internship_id" :value="generateDocumentModal ? dataId.id : ''">
                    <div id="documentGenerateField" class="space-y-4">
                        {{-- documentGenerateField --}}
                    </div>
                </div>
                <div class="form-footer">
                    <button @click.prevent="generateDocumentModal=false" class="btn btn-sm"
                        :class="modalAction == 'isView' ? 'btn-success-fill' : 'btn-error-fill'">
                        <span>Batalkan</span>
                    </button>
                    <button type="submit" class="btn btn-success-fill btn-sm">Generate</button>
                </div>
            </form>
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

    <script>
        function teamMember(dataMember) {
            const container = document.getElementById('memberField');
            container.innerHTML = '';

            dataMember.forEach(function(teamMember, index) {
                const member = document.createElement('input');
                member.value = teamMember.student.name + ' (' + teamMember.student.department.name + '/NIS ' +
                    teamMember.student.nis + ')';
                member.disabled = true;
                member.classList.add('input', 'w-full');

                container.appendChild(member);
            });
        }
    </script>

    <script>
        function generateSuratJalan(dataMember) {
            console.log(dataMember);
            const container = document.getElementById('documentGenerateField');
            container.innerHTML = '';

            dataMember.forEach(function(teamMember, index) {
                const section = document.createElement('div');
                section.classList.add('flex', 'space-x-2');

                const div = document.createElement('div');
                div.classList.add('input-group', 'w-full');

                const labelMember = document.createElement('label');
                labelMember.innerHTML = 'Anggota Kelompok';
                labelMember.classList.add('input-label');

                const member = document.createElement('input');
                member.value = teamMember.student.name;
                member.readOnly = true;
                member.classList.add('input', 'w-full', 'bg-neutral-50');

                const memberId = document.createElement('input');
                memberId.value = teamMember.student.id;
                memberId.name = 'memberId[]';
                memberId.hidden = true;

                const div2 = document.createElement('div');
                div2.classList.add('input-group', 'w-full');

                const labelLetterNum = document.createElement('label');
                labelLetterNum.innerHTML = 'Nomor Surat';
                labelLetterNum.classList.add('input-label');

                const letterNum = document.createElement('input');
                letterNum.name = 'letterNum[]';
                letterNum.placeholder = 'Masukkan Nomor Surat';
                letterNum.classList.add('input', 'w-full');
                letterNum.required = true;

                const div3 = document.createElement('div');
                div3.classList.add('input-group', 'w-full');

                const labelTransportation = document.createElement('label');
                labelTransportation.innerHTML = 'Kendaraan';
                labelTransportation.classList.add('input-label');
                labelTransportation.required = true;

                const transportation = document.createElement('input');
                transportation.name = 'transportation[]';
                transportation.value = 'Motor';
                transportation.classList.add('input', 'w-full');
                transportation.required = true;

                container.appendChild(section);
                section.appendChild(div);
                section.appendChild(div2);
                section.appendChild(div3);
                div.appendChild(labelMember);
                div.appendChild(member);
                div.appendChild(memberId);
                div2.appendChild(labelLetterNum);
                div2.appendChild(letterNum);
                div3.appendChild(labelTransportation);
                div3.appendChild(transportation);
            });
        }
    </script>

@endsection
