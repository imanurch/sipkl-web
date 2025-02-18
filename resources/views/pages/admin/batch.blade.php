@extends('layouts.app')

@section('page-title', 'Batch PKL')
@section('profil', 'Admin')
@section('content')

<div x-data="{ modalAction:null }">
    <x-table.table>
        <x-slot name="tableTitle">Batch PKL</x-slot>    
        <x-slot name="filterActionForm">batchManagement</x-slot>
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
            <th>TAHUN</th>
            <th>STATUS</th>
            <th>AKSI</th>
        </x-slot>
        <x-slot name="tBody">
            @foreach ($data as $dt)
                <tr>
                    <td class="text-center">{{ $data->firstItem() + $loop->index }}</td>
                    <td>{{ $dt->name }}</td>
                    <td>{{ $dt->year }}</td>     
                    <x-table.status_table :status="$dt->status"></x-table.status_table>  
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
            <x-slot name="formTitle">Batch PKL</x-slot>
            <x-slot name="formBody" >
                <div class="input-group">
                    <label class="input-label" for="">Nama</label>
                    <input name="name" class="input" type="text" placeholder="Masukkan Nama" :disabled="modalAction=='isDelete'" :value="modalAction=='isEdit' || modalAction=='isDelete' ? dataId.name : ''" required>
                </div>
                <div class="input-group">
                    <label class="input-label" for="">Tahun</label>
                    <input name="year" class="input" type="text" placeholder="Masukkan Tahun" :disabled="modalAction=='isDelete'" :value="modalAction=='isEdit' || modalAction=='isDelete' ? dataId.year : ''" required>
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
            form.action = "{{ route('admin.batchManagement.store') }}";
        } else if (modalAction === 'isEdit' && id) {
            form.action = `{{ route('admin.batchManagement.update', ':id') }}`.replace(':id', id);
        } else if (modalAction === 'isDelete' && id) {
            form.action = `{{ route('admin.batchManagement.destroy', ':id') }}`.replace(':id', id);
        }
    }
</script>

@endsection