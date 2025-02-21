@extends('layouts.app')

@section('page-title', 'Administrator PKL')
@section('profil', 'Admin')
@section('content')

<div x-data="{ modalAction:null }">
    <x-table.table>
        <x-slot name="tableTitle">Data Administrator</x-slot>    
        <x-slot name="filterActionForm">adminManagement</x-slot>
        <x-slot name="btnAdd">
            <x-table.add_data></x-table.add_data>
        </x-slot>
        <x-slot name="filter">
            <div class="flex w-full space-x-2">
                <div class="space-y-1 w-full">
                    <span class="text-xs text-neutral-400 w-32">Search</span>
                    <x-table.search value="{{ $filters['search'] ?? '' }}"></x-table.search>
                </div>
            </div>
        </x-slot>
        {{-- table --}}
        <x-slot name="tHeader">                    
            <th>NO</th>
            <th>NAMA</th>
            <th>USERNAME</th>
            <th>EMAIL</th>
            <th>NO TELP</th>
            <th>AKSI</th>
        </x-slot>
        <x-slot name="tBody">
            @foreach ($data as $dt)
                <tr>
                    <td class="text-center">{{ $data->firstItem() + $loop->index }}</td>               
                    {{-- <td>{{ $dt->id }}</td> --}}
                    <td>{{ $dt->name }}</td>
                    <td>{{ $dt->user->username }}</td>
                    <td>{{ $dt->user->email }}</td>
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
            <x-slot name="formBody" >
                <input type="hidden" name="user_id" :value="modalAction=='isEdit' || modalAction=='isDelete' ? dataId.user_id : ''">
                <div class="input-group">
                    <label class="input-label" for="">Nama</label>
                    <input name="name" class="input" type="text" placeholder="Masukkan Nama" :disabled="modalAction=='isDelete'" :value="modalAction=='isEdit' || modalAction=='isDelete' ? dataId.name : ''" required>
                </div>
                <div class="input-group">
                    <label class="input-label" for="">Username</label>
                    <input name="username" class="input" type="text" placeholder="Masukkan Username" :disabled="modalAction=='isDelete'" :value="modalAction=='isEdit' || modalAction=='isDelete' ? dataId.user.username : ''" required>
                </div>
                <div class="input-group">
                    <label class="input-label" for="">Email</label>
                    <input name="email" class="input" type="email" placeholder="Masukkan Email" :disabled="modalAction=='isDelete'" :value="modalAction=='isEdit' || modalAction=='isDelete' ? dataId.user.email : ''" required>
                </div>
                <div class="input-group">
                    <label class="input-label" for="">Nomor Telepon</label>
                    <input name="phone_num" class="input" type="text" placeholder="Masukkan Nomor Telepon" :disabled="modalAction=='isDelete'" :value="modalAction=='isEdit' || modalAction=='isDelete' ? dataId.phone_num : ''" required>    
                </div>
                <div class="input-group">
                    <label class="input-label" for="" :hidden="modalAction=='isDelete'">Kata Sandi</label>
                    <input name="password" class="input" type="text" placeholder="Masukkan Kata Sandi" :hidden="modalAction=='isDelete'">    
                </div>
                <div class="input-group">
                    <label class="input-label" for="" :hidden="modalAction=='isDelete'">Ulangi Kata Sandi</label>
                    <input name="check_password" class="input" type="text" placeholder="Ulangi Kata Sandi" :hidden="modalAction=='isDelete'">    
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