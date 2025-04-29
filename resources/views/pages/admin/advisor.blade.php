@extends('layouts.app')

@section('page-title', 'Guru Pembimbing')
@section('profil', 'Admin')
@section('content')

    {{-- card --}}
    <div class="layout-card">
        <x-card title="Aktif" data="{{ $activeAdvisor }}" class="bg-icon-success">
            <svg class="" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                fill="none">
                <path d="M16.6667 5L7.50001 14.1667L3.33334 10" stroke="white" stroke-width="3" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </x-card>
        <x-card title="Tidak Aktif" data="{{ $inactiveAdvisor }}" class="bg-icon-neutral">
            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                <path d="M17.5 7L7.5 17M7.5 7L17.5 17" stroke="white" stroke-width="3" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </x-card>
    </div>

    <div x-data="{
        modalAction: null,
        option: false,
        selected: 'Pilih Opsi',
        exportModal: false,
        positionOption: false,
        positionSelected: 'Pilih Opsi',
        positionSelectedValue: '',
        levelOption: false,
        levelSelected: 'Pilih Opsi',
        levelSelectedValue: '',
        departmentOption: false,
        departmentSelected: 'Pilih Opsi',
        departmentSelectedValue: ''
    }">
        <x-table.table>
            <x-slot name="tableTitle">Data Guru Pembimbing</x-slot>
            <x-slot name="filterActionForm">advisorManagement</x-slot>
            <x-slot name="btnAdd">
                <x-table.import></x-table.import>
                {{-- <a href="{{ route('admin.advisorManagement.export') }}" class="btn btn-default-fill btn-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14"
                        fill="none">
                        <path d="M7.00002 2.91675V11.0834M2.91669 7.00008H11.0834" stroke-width="1.6" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    <span class="p-0 m-0">Ekspor</span>
                </a> --}}
                <div>
                    <button @click="exportModal=true" class="btn btn-default-fill btn-xs h-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14"
                            fill="none">
                            <path d="M7.00002 2.91675V11.0834M2.91669 7.00008H11.0834" stroke-width="1.6"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span class="p-0 m-0">Ekspor</span>
                    </button>

                    <div x-show="exportModal" class="form-modal">
                        <form class="form" action="{{ route('admin.advisorManagement.export') }}" method="POST"
                            @click.away="generateDocumentModal=false">
                            <div class="form-header">
                                @csrf
                                <h3>Ekspor Data Pembimbing</h3>
                                <svg @click="exportModal=false,selected='Pilih Opsi'" class="cursor-pointer"
                                    xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28"
                                    fill="none">
                                    <path d="M19.8333 8.16675L8.16663 19.8334M8.16663 8.16675L19.8333 19.8334"
                                        stroke="#525A6A" stroke-width="1.03704" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div class="form-body">
                                <div class="input-group">
                                    <label class="input-label" for="">Pilih Data</label>
                                    <div>
                                        <button @click.prevent="option=!option" class="input input-select w-full"
                                            :disabled="isDelete" required>
                                            <span x-text="selected"
                                                :class="selected == 'Pilih Opsi' ? 'text-neutral-300' : 'text-neutral-800'"></span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 20 20" fill="none">
                                                <path d="M5 7.5L10 12.5L15 7.5" stroke="#667085" stroke-width="0.933333"
                                                    stroke-linecap="round" stroke-linejoin="round" :hidden="isDelete" />
                                            </svg>
                                        </button>
                                        <input type="hidden" name="data_type" x-model="selected">
                                        <div x-show="option" @click.away="option=false" class="bg-neutral-0">
                                            <ul class="border border-brand-600 rounded py-2 mt-2 max-h-32 overflow-auto">
                                                <li @click.prevent="option=false;selected='Semua'"
                                                    class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">
                                                    Semua</li>
                                                <li @click.prevent="option=false;selected='Pembimbing Aktif'"
                                                    class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">
                                                    Pembimbing Aktif</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-footer">
                                <button @click.prevent="exportModal=false" class="btn btn-sm"
                                    :class="modalAction == 'isView' ? 'btn-success-fill' : 'btn-error-fill'">
                                    <span>Batalkan</span>
                                </button>
                                <button type="submit" class="btn btn-success-fill btn-sm">Ekspor</button>
                            </div>
                        </form>
                    </div>
                </div>
                <x-table.add_data></x-table.add_data>
            </x-slot>
            <x-slot name="filter">
                <div class="space-y-1 w-full">
                    <span class="text-xs text-neutral-400 w-32">Search</span>
                    <x-table.search value="{{ $filters['search'] ?? '' }}"></x-table.search>
                </div>
                <div class="space-y-1 w-full">
                    <span class="text-xs text-neutral-400 w-32">Jurusan</span>
                    <x-table.select_option_filter optionName="department"
                        defaultSelected="{{ $filters['department'] != '' ? $filters['department'] : 'Semua Jurusan' }}">
                        <x-slot name="option">
                            @foreach ($departmentData as $dt)
                                <li class="option-filter-toolbar-table"
                                    @click="option=false;selected='{{ $dt->name }}';valueSelected='{{ $dt->name }}'">
                                    {{ $dt->name }}</li>
                            @endforeach
                        </x-slot>
                    </x-table.select_option_filter>
                </div>
                <div class="space-y-1 w-full">
                    <span class="text-xs text-neutral-400 w-32">Status Aktif</span>
                    <x-table.select_option_filter optionName="status"
                        defaultSelected="{{ $filters['status'] != '' ? $filters['status'] : 'Semua Status' }}">
                        <x-slot name="option">
                            <li class="option-filter-toolbar-table"
                                @click="option=false;selected='Aktif';valueSelected='active'">Aktif</li>
                            <li class="option-filter-toolbar-table"
                                @click="option=false;selected='Non Aktif';valueSelected='inactive'">Non Aktif</li>
                        </x-slot>
                    </x-table.select_option_filter>
                </div>
            </x-slot>
            {{-- table --}}
            <x-slot name="tHeader">
                <th>No</th>
                <th>Nama</th>
                <th>NIP</th>
                <th>Jabatan</th>
                <th>Pangkat/Golongan</th>
                <th>Jurusan</th>
                <th>Username</th>
                <th>Email</th>
                <th>No Telp</th>
                <th>Status</th>
                <th>Aksi</th>
            </x-slot>
            <x-slot name="tBody">
                @foreach ($data as $dt)
                    <tr>
                        <td class="text-center">{{ $data->firstItem() + $loop->index }}</td>
                        <td class="left whitespace-nowrap">{{ $dt->name ?? '' }}</td>
                        <td>{{ $dt->nip ?? '' }}</td>
                        <td>{{ $dt->advisorPosition->name ?? '' }}</td>
                        <td>{{ $dt->advisorLevel->name ?? '' }}</td>
                        <td>{{ $dt->department->name ?? '' }}</td>
                        <td class="left whitespace-nowrap">{{ $dt->user->username ?? '' }}</td>
                        <td>{{ $dt->user->email ?? '' }}</td>
                        <td>{{ $dt->phone_num ?? '' }}</td>
                        <x-table.status_table status="{{ $dt->status }}"></x-table.status_table>
                        <x-table.action_table detail="hidden" btnInput="hidden" :data="$dt"></x-table.action_table>
                    </tr>
                @endforeach
            </x-slot>
            {{-- pagination --}}
            <x-slot name="pagination">{{ $data->links() }}</x-slot>
        </x-table.table>

        {{-- form --}}
        <div x-show="modalAction != null && modalAction != 'isImport'" class="form-modal">
            <x-form>
                <x-slot name="formTitle">Data Guru Pembimbing</x-slot>
                <x-slot name="formBody">
                    <input type="" name="user_id"
                        :value="modalAction == 'isEdit' || modalAction=='isDelete' ? dataId.user_id : ''" hidden>
                    <div class="input-group">
                        <label class="input-label" for="">Nama</label>
                        <input name="name" class="input" type="text" placeholder="Masukkan Nama"
                            :disabled="modalAction == 'isDelete'"
                            :value="modalAction == 'isEdit' || modalAction=='isDelete' ? dataId.name : ''" required>
                    </div>
                    <div class="input-group">
                        <label class="input-label" for="">NIP</label>
                        <input name="nip" class="input" type="text" placeholder="Masukkan NIP"
                            :disabled="modalAction == 'isDelete'"
                            :value="modalAction == 'isEdit' || modalAction=='isDelete' ? dataId.nip : ''" required>
                        <small x-show="modalAction != 'isDelete'" class="text-xs text-error-500">*NIP 18 karakter</small>
                    </div>
                    <div class="input-group">
                        <label class="input-label" for="">Posisi</label>
                        <input type="hidden" name="position_id" x-model="positionSelectedValue">
                        <div x-show="modalAction!='isDelete'">
                            <button @click.prevent="positionOption=!positionOption" class="input input-select w-full"
                                :disabled="modalAction == 'isDelete'" required>
                                <span x-text="positionSelected" class="text-neutral-800"></span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 20 20" fill="none">
                                    <path d="M5 7.5L10 12.5L15 7.5" stroke="#667085" stroke-width="0.933333"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        :hidden="modalAction == 'isDelete'" />
                                </svg>
                            </button>
                            <div x-show="positionOption" @click.away="positionOption=false">
                                <ul class="border border-brand-600 rounded py-2 my-2 max-h-32 overflow-auto">
                                    @foreach ($positionData as $dt)
                                        <li @click="positionOption=false;positionSelected='{{ $dt->name }}';positionSelectedValue='{{ $dt->id }}'"
                                            class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">
                                            {{ $dt->name }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div x-show="modalAction=='isDelete'">
                            <input class="input w-full" type="text"
                                :value="modalAction == 'isDelete' ? dataId.advisor_position.name : ''" disabled>
                        </div>
                    </div>
                    <div class="input-group">
                        <label class="input-label" for="">Pangkat/Golongan</label>
                        <input type="hidden" name="level_id" x-model="levelSelectedValue">
                        <div x-show="modalAction!='isDelete'">
                            <button @click.prevent="levelOption=!levelOption" class="input input-select w-full"
                                :disabled="modalAction == 'isDelete'" required>
                                <span x-text="levelSelected" class="text-neutral-800"></span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 20 20" fill="none">
                                    <path d="M5 7.5L10 12.5L15 7.5" stroke="#667085" stroke-width="0.933333"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        :hidden="modalAction == 'isDelete'" />
                                </svg>
                            </button>
                            <div x-show="levelOption" @click.away="levelOption=false">
                                <ul class="border border-brand-600 rounded py-2 my-2 max-h-32 overflow-auto">
                                    @foreach ($levelData as $dt)
                                        <li @click="levelOption=false;levelSelected='{{ $dt->name }}';levelSelectedValue='{{ $dt->id }}'"
                                            class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">
                                            {{ $dt->name }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div x-show="modalAction=='isDelete'">
                            <input class="input w-full" type="text"
                                :value="modalAction == 'isDelete' ? dataId.advisor_level.name : ''" disabled>
                        </div>
                    </div>
                    <div class="input-group">
                        <label class="input-label" for="">Jurusan</label>
                        <input type="hidden" name="department_id" x-model="departmentSelectedValue">
                        <div x-show="modalAction!='isDelete'">
                            <button @click.prevent="departmentOption=!departmentOption" class="input input-select w-full"
                                :disabled="modalAction == 'isDelete'" required>
                                <span x-text="departmentSelected" class="text-neutral-800"></span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 20 20" fill="none">
                                    <path d="M5 7.5L10 12.5L15 7.5" stroke="#667085" stroke-width="0.933333"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        :hidden="modalAction == 'isDelete'" />
                                </svg>
                            </button>
                            <div x-show="departmentOption" @click.away="departmentOption=false">
                                <ul class="border border-brand-600 rounded py-2 my-2 max-h-32 overflow-auto">
                                    @foreach ($departmentData as $dt)
                                        <li @click="departmentOption=false;departmentSelected='{{ $dt->name }}';departmentSelectedValue='{{ $dt->id }}'"
                                            class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">
                                            {{ $dt->name }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div x-show="modalAction=='isDelete'">
                            <input class="input w-full" type="text"
                                :value="modalAction == 'isDelete' ? dataId.department.name : ''" disabled>
                        </div>
                    </div>
                    <div class="input-group">
                        <label class="input-label" for="">Username</label>
                        <input name="username" class="input" type="text" placeholder="Masukkan Username"
                            :disabled="modalAction == 'isDelete'"
                            :value="modalAction == 'isEdit' || modalAction=='isDelete' ? dataId.user.username : ''"
                            required>
                    </div>
                    <div class="input-group">
                        <label class="input-label" for="">Email</label>
                        <input name="email" class="input" type="email" placeholder="Masukkan Email"
                            :disabled="modalAction == 'isDelete'"
                            :value="modalAction == 'isEdit' || modalAction=='isDelete' ? dataId.user.email : ''" required>
                    </div>
                    <div class="input-group">
                        <label class="input-label" for="">Nomor Telepon</label>
                        <input name="phone_num" class="input" type="text" placeholder="Masukkan Nomor Telepon"
                            :disabled="modalAction == 'isDelete'"
                            :value="modalAction == 'isEdit' || modalAction=='isDelete' ? dataId.phone_num : ''" required>
                    </div>
                    <div x-show="modalAction != 'isDelete'" class="input-group">
                        <label class="input-label" for="">Kata Sandi</label>
                        <input name="password" class="input" type="password" placeholder="Masukkan Kata Sandi">
                        <small class="text-xs text-error-500">*Kata sandi 8-12 karakter</small>
                    </div>
                    <div x-show="modalAction != 'isDelete'" class="input-group">
                        <label class="input-label" for="">Ulangi Kata
                            Sandi</label>
                        <input name="check_password" class="input" type="password" placeholder="Ulangi Kata Sandi">
                        <small class="text-xs text-error-500">*Harus sama dengan kata sandi</small>
                    </div>
                </x-slot>
            </x-form>
        </div>

        {{-- import form --}}
        <div x-show="modalAction=='isImport'" class="form-modal">
            <x-form action="{{ route('admin.advisorManagement.import') }}">
                <x-slot name="formTitle">Impor Data Guru</x-slot>
                <x-slot name="formBody">
                    <div class="input-group">
                        <label class="input-label" for="">Unggah File (Format file: Excel)</label>
                        <input class="input" type="file" name="import_file" id="" required>
                    </div>
                    <div class="flex place-items-center">
                        <span class="text-xs-reguler">Template Impor Data :</span>
                        <a href="{{ route('admin.advisorManagement.downloadTemplateFile') }}"
                            class="btn btn-xs btn-default-clear">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14"
                                fill="none">
                                <path
                                    d="M12.25 8.75V9.45C12.25 10.4301 12.25 10.9201 12.0593 11.2945C11.8915 11.6238 11.6238 11.8915 11.2945 12.0593C10.9201 12.25 10.4301 12.25 9.45 12.25H4.55C3.56991 12.25 3.07986 12.25 2.70552 12.0593C2.37623 11.8915 2.10852 11.6238 1.94074 11.2945C1.75 10.9201 1.75 10.4301 1.75 9.45V8.75M9.91667 5.83333L7 8.75M7 8.75L4.08333 5.83333M7 8.75V1.75"
                                    stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span>Unduh Template Impor</span>
                        </a>
                    </div>
                </x-slot>
            </x-form>
        </div>

        {{-- empty state --}}
        @if (count($data) == 0)
            <x-not_found_empty_state>
                <x-slot name="desc">Tambah data guru terlebih dahulu ya!</x-slot>
                <x-slot name="cta">
                    <button @click="modalAction='isAdd'" class="btn btn-xs btn-default-fill">Tambah Data</button>
                </x-slot>
            </x-not_found_empty_state>
        @endif
    </div>

    {{-- script Modal Action --}}
    <script>
        function setFormAction(modalAction, id = null) {
            const form = document.getElementById('modalForm');
            if (modalAction === 'isAdd') {
                form.action = "{{ route('admin.advisorManagement.store') }}";
            } else if (modalAction === 'isEdit' && id) {
                form.action = `{{ route('admin.advisorManagement.update', ':id') }}`.replace(':id', id);
            } else if (modalAction === 'isDelete' && id) {
                form.action = `{{ route('admin.advisorManagement.destroy', ':id') }}`.replace(':id', id);
            }
        }
    </script>

@endsection
