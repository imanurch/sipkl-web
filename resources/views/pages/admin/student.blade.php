@extends('layouts.app')

@section('page-title', 'Peserta Didik')
@section('profil', 'Admin')
@section('content')

    <div class="layout-card">
        <x-card title="Terdaftar PKL" data="{{ $registeredStudent }}" class="bg-icon-success">
            <svg class="" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                fill="none">
                <path d="M16.6667 5L7.50001 14.1667L3.33334 10" stroke="white" stroke-width="3" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </x-card>
        <x-card title="Tidak Terdaftar" data="{{ $unregisteredStudent }}" class="bg-icon-error">
            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                <path d="M17.5 7L7.5 17M7.5 7L17.5 17" stroke="white" stroke-width="3" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </x-card>
    </div>

    <div x-data="{ modalAction: null, option: false, optionGender: false, selected: 'Pilih Opsi', selectedGender: 'Pilih Opsi' }">
        <x-table.table>
            <x-slot name="tableTitle">Data Peserta Didik</x-slot>
            <x-slot name="filterActionForm">studentManagement</x-slot>
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
                    <span class="text-xs text-neutral-400 w-32">Tahun Ajaran</span>
                    <x-table.select_option_filter optionName="year"
                        defaultSelected="{{ $filters['year'] != '' ? $filters['year'] : 'Semua Tahun' }}">
                        <x-slot name="option">
                            @foreach ($yearData as $dt)
                                <li class="option-filter-toolbar-table"
                                    @click="option=false;selected='{{ $dt->year }}';valueSelected='{{ $dt->year }}'">
                                    {{ $dt->year }}</li>
                            @endforeach
                        </x-slot>
                    </x-table.select_option_filter>
                </div>
                <div class="space-y-1 w-full">
                    <span class="text-xs text-neutral-400 w-32">Status Registrasi</span>
                    <x-table.select_option_filter optionName="status"
                        defaultSelected="{{ $filters['status'] != '' ? $filters['status'] : 'Semua Status' }}">
                        <x-slot name="option">
                            <li class="option-filter-toolbar-table"
                                @click="option=false;selected='Teregistrasi';valueSelected='registered'">Teregistrasi
                            </li>
                            <li class="option-filter-toolbar-table"
                                @click="option=false;selected='Belum Teregistrasi';valueSelected='unregistered'">Belum
                                Teregistrasi</li>
                        </x-slot>
                    </x-table.select_option_filter>
                </div>
            </x-slot>
            <x-slot name="tHeader">
                <th>NO</th>
                <th>NAMA</th>
                <th>NISN</th>
                <th>JENIS KELAMIN</th>
                <th>JURUSAN</th>
                <th>USERNAME</th>
                <th>EMAIL</th>
                <th>NO TELP</th>
                <th>STATUS PKL</th>
                <th>AKSI</th>
            </x-slot>
            <x-slot name="tBody">
                @foreach ($data as $dt)
                    <tr>
                        <td class="text-center">{{ $data->firstItem() + $loop->index }}</td>
                        <td>{{ $dt->name }}</td>
                        <td>{{ $dt->nisn }}</td>
                        <td>{{ $dt->gender == 'men' ? 'Laki-Laki' : 'Perempuan' }}</td>
                        <td>{{ $dt->department->name }}</td>
                        <td>{{ $dt->user->username }}</td>
                        <td>{{ $dt->user->email }}</td>
                        <td>{{ $dt->phone_num }}</td>
                        {{-- <td>{{ $dt->status }}</td>        --}}
                        <x-table.status_table status="{{ $dt->status }}"></x-table.status_table>
                        <x-table.action_table detail="hidden" btnInput="hidden" :data="$dt"></x-table.action_table>
                    </tr>
                @endforeach
            </x-slot>
            {{-- pagination --}}
            <x-slot name="pagination">{{ $data->links() }}</x-slot>
        </x-table.table>

        <div x-show="modalAction != null && modalAction !='isImport'" class="form-modal">
            <x-form>
                <x-slot name="formTitle">Data Siswa</x-slot>
                <x-slot name="formBody">
                    <input type="text" name="user_id"
                        :value="modalAction == 'isEdit' || modalAction=='isDelete' ? dataId.user_id : ''">
                    <div class="input-group">
                        <label class="input-label" for="">Nama</label>
                        <input name="name" class="input" type="text" placeholder="Masukkan Nama"
                            :disabled="modalAction == 'isDelete'"
                            :value="modalAction == 'isEdit' || modalAction=='isDelete' ? dataId.name : ''" required>
                    </div>
                    <div class="input-group">
                        <label class="input-label" for="">NISN</label>
                        <input name="nisn" class="input" type="text" placeholder="Masukkan NISN"
                            :disabled="modalAction == 'isDelete'"
                            :value="modalAction == 'isEdit' || modalAction=='isDelete' ? dataId.nisn : ''" required>
                    </div>
                    <div class="input-group">
                        <label class="input-label" for="">Jenis Kelamin</label>
                        <input type="hidden" name="gender" x-model="selectedGender">
                        <div>
                            <button @click.prevent="optionGender=!optionGender" class="input input-select w-full"
                                :disabled="modalAction == 'isDelete'">
                                <span x-text="selectedGender"
                                    :class="selectedGender == 'Pilih Opsi' ? 'text-neutral-300' : 'text-neutral-800'"></span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                    fill="none">
                                    <path d="M5 7.5L10 12.5L15 7.5" stroke="#667085" stroke-width="0.933333"
                                        stroke-linecap="round" stroke-linejoin="round" :hidden="isDelete" />
                                </svg>
                            </button>
                            <div x-show="optionGender" @click.away="optionGender=false">
                                <ul class="border border-brand-600 rounded py-2 my-2 max-h-32 overflow-auto">
                                    <li @click="optionGender=false;selectedGender='Laki-Laki'"
                                        class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">
                                        Laki-Laki</li>
                                    <li @click="optionGender=false;selectedGender='Perempuan'"
                                        class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">
                                        Perempuan</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="input-group">
                        <label class="input-label" for="">Jurusan</label>
                        <input type="hidden" name="department_id" x-model="selected">
                        <div>
                            <button @click.prevent="option=!option" class="input input-select w-full"
                                :disabled="modalAction == 'isDelete'">
                                <span x-text="selected"
                                    :class="selected == 'Pilih Opsi' ? 'text-neutral-300' : 'text-neutral-800'"></span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 20 20" fill="none">
                                    <path d="M5 7.5L10 12.5L15 7.5" stroke="#667085" stroke-width="0.933333"
                                        stroke-linecap="round" stroke-linejoin="round" :hidden="isDelete" />
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
                        <label class="input-label" for="">Tahun Ajaran</label>
                        <input name="year" class="input" type="text" placeholder="Masukkan Tahun"
                            :disabled="modalAction == 'isDelete'"
                            :value="modalAction == 'isEdit' || modalAction=='isDelete' ? dataId.year : ''" required>
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
                        <input name="email" class="input" type="email" placeholder="MasukkanEmail"
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

        {{-- import form --}}
        <div x-show="modalAction=='isImport'" class="form-modal">
            <x-form action="{{ route('admin.studentManagement.import') }}">
                <x-slot name="formTitle">Impor Data Siswa</x-slot>
                <x-slot name="formBody">
                    <div class="input-group">
                        <label class="input-label" for="">Unggah File</label>
                        <input class="input" type="file" name="import_file" id="" required>
                    </div>
                    <div class="flex place-items-center">
                        <span class="text-xs-reguler">Template Impor Data :</span>
                        <a href="{{ route('admin.studentManagement.downloadTemplateFile') }}" class="btn btn-xs btn-default-clear">
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
                <x-slot name="desc">Tambah data siswa terlebih dahulu ya!</x-slot>
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
                form.action = "{{ route('admin.studentManagement.store') }}";
            } else if (modalAction === 'isEdit' && id) {
                form.action = `{{ route('admin.studentManagement.update', ':id') }}`.replace(':id', id);
            } else if (modalAction === 'isDelete' && id) {
                form.action = `{{ route('admin.studentManagement.destroy', ':id') }}`.replace(':id', id);
            }
        }
    </script>
@endsection
