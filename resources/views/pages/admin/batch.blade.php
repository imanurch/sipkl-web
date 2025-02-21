@extends('layouts.app')

@section('page-title', 'Batch PKL')
@section('profil', 'Admin')
@section('content')

    <div x-data="{ modalAction: null }">
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
            {{-- setting batch status --}}
            <x-slot name="addition">
                <div x-data="{ modalActiveBatch: false, option: false, selected: 'Pilih Opsi', selectedValue: '' }">
                    <div class="flex justify-between place-items-center border border-brand-500 rounded-sm py-2 px-4">
                        <div>
                            <span class="text-xs">Batch Yang Sedang Aktif : </span>
                            @if ($data->active_batch != null)
                                <span class="text-xs-semibold">{{ $data->active_batch->name }}
                                    ({{ $data->active_batch->year }})</span>
                            @else
                                <span class="text-xs text-neutral-200">Tidak ada batch yang aktif</span>
                            @endif
                        </div>
                        <div class="flex space-x-2">
                            <button @click="modalActiveBatch=true" class="btn btn-xs btn-default-fill">Atur</button>
                            <a href="{{ route('admin.batchManagement.updateActiveBatch',['id'=>'nonaktif']) }}" class="btn btn-xs btn-default-outline">Hapus</a>
                        </div>
                    </div>
                    {{-- modal active batch --}}
                    <div x-show="modalActiveBatch">
                        <form
                            :action="`{{ route('admin.batchManagement.updateActiveBatch', ['id' => '__ID__']) }}`
                            .replace('__ID__', selectedValue)"
                            method="GET" class="form">
                            <div class="form-header">
                                @csrf
                                {{-- @method('PATCH') --}}
                                <h6>Atur Batch Aktif</h6>
                                <svg @click="modalActiveBatch=false;selected='Pilih Opsi'" class="cursor-pointer"
                                    xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28"
                                    fill="none">
                                    <path d="M19.8333 8.16675L8.16663 19.8334M8.16663 8.16675L19.8333 19.8334"
                                        stroke="#525A6A" stroke-width="1.03704" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div class="form-body">
                                <div class="input-group">
                                    <label class="input-label" for="">Pilih Batch</label>
                                    <button @click.prevent="option=!option" class="input input-select w-full" required>
                                        <span x-text="selected" class="text-neutral-800"></span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 20 20" fill="none">
                                            <path d="M5 7.5L10 12.5L15 7.5" stroke="#667085" stroke-width="0.933333"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </button>
                                    <div x-show="option" @click.away="option=false">
                                        <ul class="border border-brand-600 rounded py-2 my-2 max-h-32 overflow-auto">
                                            @foreach ($data as $dt)
                                                <li @click="option=false;selected='{{ $dt->name }} - {{ $dt->year }}';selectedValue='{{ $dt->id }}'"
                                                    class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">
                                                    {{ $dt->name }} - {{ $dt->year }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="form-footer">
                                <button @click="modalActiveBatch=false;selected='Pilih Opsi'"
                                    class="btn btn-xs btn-error-fill">Kembali</button>
                                <button type="submit" class="btn btn-xs btn-success-fill">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </x-slot>
            {{-- table --}}
            <x-slot name="tHeader">
                <th>NO</th>
                <th>NAMA</th>
                <th>TAHUN</th>
                <th>AKSI</th>
            </x-slot>
            <x-slot name="tBody">
                @foreach ($data as $dt)
                    <tr>
                        <td class="text-center">{{ $data->firstItem() + $loop->index }}</td>
                        <td>{{ $dt->name }}</td>
                        <td class="text-center">{{ $dt->year }}</td>
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
                <x-slot name="formBody">
                    <div class="input-group">
                        <label class="input-label" for="">Nama</label>
                        <input name="name" class="input" type="text" placeholder="Masukkan Nama"
                            :disabled="modalAction == 'isDelete'"
                            :value="modalAction == 'isEdit' || modalAction=='isDelete' ? dataId.name : ''" required>
                    </div>
                    <div class="input-group">
                        <label class="input-label" for="">Tahun</label>
                        <input name="year" class="input" type="text" placeholder="Masukkan Tahun"
                            :disabled="modalAction == 'isDelete'"
                            :value="modalAction == 'isEdit' || modalAction=='isDelete' ? dataId.year : ''" required>
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
