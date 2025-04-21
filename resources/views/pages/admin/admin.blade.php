@extends('layouts.app')

@section('page-title', 'Administrator PKL')
@section('profil', 'Admin')
@section('content')

    <div x-data="{ modalAction: null }">
        <x-table.table classFilter="hidden">
            <x-slot name="tableTitle">Data Administrator</x-slot>
            <x-slot name="filterActionForm">adminManagement</x-slot>
            <x-slot name="btnAdd">
                <x-table.add_data></x-table.add_data>
            </x-slot>
            {{-- table --}}
            <x-slot name="tHeader">
                <th>No</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Email</th>
                <th>No Telp</th>
                <th>Aksi</th>
            </x-slot>
            <x-slot name="tBody">
                @foreach ($data as $dt)
                    <tr>
                        <td class="text-center">{{ $data->firstItem() + $loop->index }}</td>
                        <td class="left whitespace-nowrap">{{ $dt->name }}</td>
                        <td class="whitespace-nowrap">{{ $dt->user->username }}</td>
                        <td class="whitespace-nowrap">{{ $dt->user->email }}</td>
                        <td>{{ $dt->phone_num }}</td>
                        <x-table.action_table detail="hidden" btnInput="hidden" :data="$dt"></x-table.action_table>
                    </tr>
                @endforeach
            </x-slot>
            {{-- pagination --}}
            <x-slot name="pagination">{{ $data->links() }}</x-slot>
        </x-table.table>
        {{-- form --}}
        <div x-show="modalAction != null" class="form-modal">
            <x-form>
                <x-slot name="formTitle">Data Administrator</x-slot>
                <x-slot name="formBody">
                    <input name="user_id" :value="modalAction == 'isEdit' || modalAction=='isDelete' ? dataId.user_id : ''"
                        hidden>
                    <div class="input-group">
                        <label class="input-label" for="">Nama</label>
                        <input name="name" class="input" type="text" placeholder="Masukkan Nama"
                            :disabled="modalAction == 'isDelete'"
                            :value="modalAction == 'isEdit' || modalAction=='isDelete' ? dataId.name : ''" required>
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
                        <label class="input-label" for="">Ulangi Kata Sandi</label>
                        <input name="check_password" class="input" type="password" placeholder="Ulangi Kata Sandi">
                        <small class="text-xs text-error-500">*Harus sama dengan kata sandi</small>
                    </div>
                </x-slot>
            </x-form>
        </div>
    </div>

    {{-- script Modal Action --}}
    <script>
        function setFormAction(modalAction, id = null) {
            const form = document.getElementById('modalForm');
            if (modalAction === 'isAdd') {
                form.action = "{{ route('admin.adminManagement.store') }}";
            } else if (modalAction === 'isEdit' && id) {
                form.action = `{{ route('admin.adminManagement.update', ':id') }}`.replace(':id', id);
            } else if (modalAction === 'isDelete' && id) {
                form.action = `{{ route('admin.adminManagement.destroy', ':id') }}`.replace(':id', id);
            }
        }
    </script>

@endsection
