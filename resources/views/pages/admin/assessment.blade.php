@extends('layouts.app')

@section('page-title', 'Penilaian PKL')
@section('profil', 'Admin')
@section('content')

{{-- card --}}
<div class="layout-card">
    <x-card title="Belum Dinilai" data="90" class="bg-icon-warning">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
            <path d="M10 8.50224C10.1762 8.00136 10.524 7.57901 10.9817 7.30998C11.4395 7.04095 11.9777 6.9426 12.501 7.03237C13.0243 7.12213 13.499 7.39421 13.8409 7.80041C14.1829 8.20661 14.37 8.72072 14.3692 9.25168C14.3692 10.7506 12.1209 11.5 12.1209 11.5M12.1499 14.5H12.1599M9.9 19.2L11.36 21.1467C11.5771 21.4362 11.6857 21.5809 11.8188 21.6327C11.9353 21.678 12.0647 21.678 12.1812 21.6327C12.3143 21.5809 12.4229 21.4362 12.64 21.1467L14.1 19.2C14.3931 18.8091 14.5397 18.6137 14.7185 18.4645C14.9569 18.2656 15.2383 18.1248 15.5405 18.0535C15.7671 18 16.0114 18 16.5 18C17.8978 18 18.5967 18 19.1481 17.7716C19.8831 17.4672 20.4672 16.8831 20.7716 16.1481C21 15.5967 21 14.8978 21 13.5V7.8C21 6.11984 21 5.27976 20.673 4.63803C20.3854 4.07354 19.9265 3.6146 19.362 3.32698C18.7202 3 17.8802 3 16.2 3H7.8C6.11984 3 5.27976 3 4.63803 3.32698C4.07354 3.6146 3.6146 4.07354 3.32698 4.63803C3 5.27976 3 6.11984 3 7.8V13.5C3 14.8978 3 15.5967 3.22836 16.1481C3.53284 16.8831 4.11687 17.4672 4.85195 17.7716C5.40326 18 6.10218 18 7.5 18C7.98858 18 8.23287 18 8.45951 18.0535C8.76169 18.1248 9.04312 18.2656 9.2815 18.4645C9.46028 18.6137 9.60685 18.8091 9.9 19.2Z" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
    </x-card>
    <x-card title="Lulus" data="90" class="bg-icon-success">
        <svg class="" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
            <path d="M16.6667 5L7.50001 14.1667L3.33334 10" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>  
    </x-card>
    <x-card title="Tidak Lulus" data="90" class="bg-icon-error">
        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
            <path d="M17.5 7L7.5 17M7.5 7L17.5 17" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </x-card>
</div>

<div x-data="{ modalAction: null, option: false, selected:'Pilih Opsi'}" >
    <x-table.table>
        <x-slot name="tableTitle">Data Peserta Didik</x-slot>    
        <x-slot name="actionForm">assessment</x-slot>
        {{-- toolbar --}}
        <x-slot name="toolbar">
            <x-slot name="filter">
                <x-table.search value="{{ $filters['search'] ?? '' }}"></x-table.search>
                <x-table.select_option_filter optionName="batch" defaultSelected="{{ $filters['batch_id'] != '' ? $filters['batch_id'] : 'Semua batch' }}">
                    <x-slot name="option">
                        @foreach ($batchData as $dt)
                        <li class="option-filter-toolbar-table" @click="option=false;selected='{{ $dt->name }}';valueSelected='{{ $dt->id }}'">{{ $dt->name }}</li>
                        @endforeach
                    </x-slot>
                </x-table.select_option_filter>
            </x-slot>
        </x-slot>
        {{-- table --}}
        <x-slot name="tHeader">                    
            <th>NO</th>
            <th>NAMA</th>
            <th>LOKASI PKL</th>
            <th>LUARAN</th>
            <th>NILAI AKHIR</th>
            <th>STATUS</th>
            <th>AKSI</th>
        </x-slot>
        <x-slot name="tBody">
            @foreach ($data as $dt)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>              
                    <td>{{ $dt->name }}</td>
                    <td>{{ $dt->industry }}</td>
                    <td>{{ $dt->output }}</td>        
                    <td>{{ $dt->final_score }}</td> 
                    <x-table.status_table status="0"></x-table.status_table>       
                    <x-table.action_table delete="hidden" :data="$dt"></x-table.action_table>
                </tr>
            @endforeach
        </x-slot>
        {{-- pagination --}}
        <x-slot name="pagination">{{ $data->links() }}</x-slot>
    </x-table.table>
    {{-- form --}}
    <div x-show="modalAction != null" class="w-full" x-data="dataId:{}">
        <x-form>
            <x-slot name="formTitle">Penilaian</x-slot>
            <x-slot name="formBody" >
                <div class="input-group">
                    <label class="input-label" for="">Nama</label>
                    <input name="name" class="input" type="text" disabled :value="modalAction!=null ? dataId.name : ''">    
                </div>
                <div class="input-group">
                    <label class="input-label" for="">Jurusan</label>
                    <input name="department" class="input" type="text" disabled :value="modalAction!=null ? dataId.department : ''">    
                </div>
                <div class="input-group">
                    <label class="input-label" for="">Lokasi PKL</label>
                    <input name="industry" class="input" type="text" disabled :value="modalAction!=null ? dataId.industry">    
                </div>                
                <div class="input-group">
                    <label class="input-label" for="">Nilai Guru Pembimbing</label>
                    <input name="advisor_score" class="input" type="text"  :disabled="modalAction=='isView'" :value="modalAction!=null ? dataId.advisor_score : ''" required>    
                </div>               
                <div class="input-group">
                    <label class="input-label" for="">Nilai Industri</label>
                    <input name="industry_score" class="input" type="text"  :disabled="modalAction=='isView'" :value="modalAction!=null ? dataId.industry_score : ''" required>    
                </div>               
                <div class="input-group">
                    <label class="input-label" for="">Nilai Ujian Akhir PKL</label>
                    <input name="final_test_score" class="input" type="text"  :disabled="modalAction=='isView'" :value="modalAction!=null ? dataId.final_test_score : ''" required>    
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
            form.action = "{{ route('admin.assessment.store') }}";
        } else if (modalAction === 'isEdit' && id) {
            form.action = `{{ route('admin.assessment.update', ':id') }}`.replace(':id', id);
        } else if (modalAction === 'isDelete' && id) {
            form.action = `{{ route('admin.assessment.destroy', ':id') }}`.replace(':id', id);
        }
    }
</script>

@endsection