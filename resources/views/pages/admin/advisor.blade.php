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

    <div x-data="{ modalAction: null, option: false, selected: 'Pilih Opsi' }">
        <x-table.table>
            <x-slot name="tableTitle">Data Guru Pembimbing</x-slot>
            <x-slot name="filterActionForm">advisorManagement</x-slot>
            <x-slot name="btnAdd">
                <x-table.import></x-table.import>
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
                <th>NO</th>
                <th>NAMA</th>
                <th>NIP</th>
                <th>JURUSAN</th>
                <th>USERNAME</th>
                <th>EMAIL</th>
                <th>NO TELP</th>
                <th>STATUS</th>
                <th>AKSI</th>
            </x-slot>
            <x-slot name="tBody">
                @foreach ($data as $dt)
                    <tr>
                        <td class="text-center">{{ $data->firstItem() + $loop->index }}</td>
                        <td>{{ $dt->name }}</td>
                        <td>{{ $dt->nip }}</td>
                        <td>{{ $dt->department->name }}</td>
                        <td>{{ $dt->user->username }}</td>
                        <td>{{ $dt->user->email }}</td>
                        <td>{{ $dt->phone_num }}</td>
                        <x-table.status_table status="{{ $dt->status }}"></x-table.status_table>
                        <x-table.action_table detail="hidden" btnInput="hidden" :data="$dt"></x-table.action_table>
                    </tr>
                @endforeach
            </x-slot>
            {{-- pagination --}}
            <x-slot name="pagination">{{ $data->links() }}</x-slot>
        </x-table.table>

        {{-- form --}}
        <div x-show="modalAction!=null" class="form-modal">
            <x-form>
                <x-slot name="formTitle">Data Guru Pembimbing</x-slot>
                <x-slot name="formBody">
                    <input type="" name="user_id" :value="modalAction=='isEdit' || modalAction=='isDelete' ? dataId.user_id : ''">
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
                    </div>
                    <div class="input-group">
                        <label class="input-label" for="">Jurusan</label>
                        <input type="hidden" name="department_id" x-model="selected">
                        <div>
                            <button @click.prevent="option=!option" class="input input-select w-full"
                                :disabled="modalAction == 'isDelete'" required>
                                {{-- <span x-text="selected" :class="selected=='Pilih Opsi' ? 'text-neutral-300' : 'text-neutral-800'"></span> --}}
                                <span x-text="selected" class="text-neutral-800"></span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                    fill="none">
                                    <path d="M5 7.5L10 12.5L15 7.5" stroke="#667085" stroke-width="0.933333"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        :hidden="modalAction == 'isDelete'" />
                                </svg>
                            </button>
                            <div x-show="option" @click.away="option=false">
                                <ul class="border border-brand-600 rounded py-2 my-2 max-h-32 overflow-auto">
                                    @foreach ($departmentData as $dt)
                                        <li @click="option=false;selected='{{ $dt->name }}'"
                                            class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">
                                            {{ $dt->name }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="input-group">
                        <label class="input-label" for="">Username</label>
                        <input name="username" class="input" type="text" placeholder="Masukkan Username"
                            :disabled="modalAction == 'isDelete'"
                            :value="modalAction == 'isEdit' || modalAction=='isDelete' ? dataId.user.username : ''" required>
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
                    <div class="input-group" :hidden="modalAction == 'isDelete'">
                        <label class="input-label" for="" :hidden="modalAction == 'isDelete'">Kata Sandi</label>
                        <input name="password" class="input" type="text" placeholder="Masukkan Kata Sandi"
                            :hidden="modalAction == 'isDelete'">
                    </div>
                    <div class="input-group" :hidden="modalAction == 'isDelete'">
                        <label class="input-label" for="" :hidden="modalAction == 'isDelete'">Ulangi Kata
                            Sandi</label>
                        <input name="check_password" class="input" type="text" placeholder="Ulangi Kata Sandi"
                            :hidden="modalAction == 'isDelete'">
                    </div>
                </x-slot>
            </x-form>
        </div>
        {{-- <div x-show="importModal" class="w-full" x-data="dataId:{}">
        <x-form>
            <x-slot name="formTitle">Impor Data Siswa</x-slot>
            <x-slot name="formBody" >
                <div class="input-group">
                    <label class="input-label" for="">Unggah File</label>
                    <button class="btn btn-xs btn-default-fill w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                            <path d="M12.25 8.75V9.45C12.25 10.4301 12.25 10.9201 12.0593 11.2945C11.8915 11.6238 11.6238 11.8915 11.2945 12.0593C10.9201 12.25 10.4301 12.25 9.45 12.25H4.55C3.56991 12.25 3.07986 12.25 2.70552 12.0593C2.37623 11.8915 2.10852 11.6238 1.94074 11.2945C1.75 10.9201 1.75 10.4301 1.75 9.45V8.75M9.91667 4.66667L7 1.75M7 1.75L4.08333 4.66667M7 1.75V8.75" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>Unggah File</span>
                    </button> 
                </div>
                <div class="flex place-items-center">
                    <span class="text-xs-reguler">Template Impor Data :</span>
                    <button class="btn btn-xs btn-default-clear">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                            <path d="M12.25 8.75V9.45C12.25 10.4301 12.25 10.9201 12.0593 11.2945C11.8915 11.6238 11.6238 11.8915 11.2945 12.0593C10.9201 12.25 10.4301 12.25 9.45 12.25H4.55C3.56991 12.25 3.07986 12.25 2.70552 12.0593C2.37623 11.8915 2.10852 11.6238 1.94074 11.2945C1.75 10.9201 1.75 10.4301 1.75 9.45V8.75M9.91667 5.83333L7 8.75M7 8.75L4.08333 5.83333M7 8.75V1.75" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>Unduh Template Impor</span>
                    </button> 
                </div>
            </x-slot>
        </x-form>
    </div> --}}
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
