@extends('layouts.app')

@section('page-title', 'Monitoring')
@section('profil', 'Advisor')
@section('content')

<x-guide guideTitle="Monitoring & Generate Dokumen">
    <li>Guide 1</li>
    <li>Guide 2</li>
</x-guide>

<div x-data="{ modal:false, isEdit:false, isDelete:false, option: false, option2: false, selected:'Pilih Opsi', selected2:'Pilih Opsi'}" >
    <x-table>
        <x-slot name="tableTitle">Data Monitoring</x-slot>
        <x-slot name="filter">
            <x-table.filter></x-table.filter>
            <x-table.add_data></x-table.add_data>
        </x-slot>
        <x-slot name="tHeader">                    
            <th>NO</th>
            <th>JENIS MONITORING</th>
            <th>WAKTU</th>
            <th>KELOMPOK - INDUSTRI</th>
            <th>MATERI</th>
            <th>DOKUMEN</th>
            <th>AKSI</th>
        </x-slot>
        <x-slot name="tBody">
            @foreach ($data as $dt)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>              
                    <td>{{ $dt->type }}</td>
                    <td>{{ $dt->date }}</td>
                    <td>{{ $dt->group }} - {{ $dt->industry }}</td>        
                    <td>{{ $dt->note }}</td> 
                    <td>{{ $dt->document }}</td> 
                    <x-action_table detail="hidden" btnInput="hidden" :data="$dt"></x-action_table>
                </tr>
            @endforeach
        </x-slot>
    </x-table>
    <div x-show="modal" class="w-full" x-data="dataId:{}">
        <x-form>
            <x-slot name="formTitle">Monitoring</x-slot>
            <x-slot name="formBody" >                
                <div class="input-group">
                    <label class="input-label" for="">Jenis Monitoring</label>
                    <div>
                        <button @click="option=!option" class="input input-select w-full" :disabled="isDelete" required>
                            <span x-text="selected" :class="selected=='Pilih Opsi' ? 'text-neutral-300' : 'text-neutral-800'"></span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M5 7.5L10 12.5L15 7.5" stroke="#667085" stroke-width="0.933333" stroke-linecap="round" stroke-linejoin="round" :hidden="isDelete"/>
                                </svg>
                        </button>
                        <div x-show="option" @click.away="option=false">
                            <ul class="border border-brand-600 rounded py-2 my-2 max-h-32 overflow-auto">
                                <li @click="option=false;selected='option1'" class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">Pelepasan Peserta PKL</li>
                                <li @click="option=false;selected='option2'" class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">Kunjungan</li>
                                <li @click="option=false;selected='option3'" class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">Penarikan Peserta PKL</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="input-group">
                    <label class="input-label" for="">Waktu</label>
                    <input name="date" class="input" type="text" placeholder="Masukkan Tanggal" :disabled="isDelete" :value="isEdit || isDelete ? dataId.date : ''" required>    
                </div>                
                <div class="input-group">
                    <label class="input-label" for="">Kelompok - Industri</label>
                    <div>
                        <button @click="option2=!option2" class="input input-select w-full" :disabled="isDelete" required>
                            <span x-text="selected2" :class="selected=='Pilih Opsi' ? 'text-neutral-300' : 'text-neutral-800'"></span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M5 7.5L10 12.5L15 7.5" stroke="#667085" stroke-width="0.933333" stroke-linecap="round" stroke-linejoin="round" :hidden="isDelete"/>
                                </svg>
                        </button>
                        <div x-show="option2" @click.away="option2=false">
                            <ul class="border border-brand-600 rounded py-2 my-2 max-h-32 overflow-auto">
                                <li @click="option2=false;selected2='option21'" class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">Pelepasan Peserta PKL</li>
                                <li @click="option2=false;selected2='option22'" class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">Kunjungan</li>
                                <li @click="option2=false;selected2='option23'" class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">Penarikan Peserta PKL</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="input-group">
                    <label class="input-label" for="">Materi</label>
                    <input name="note" class="input" type="text" placeholder="Masukkan Keterangan" :disabled="isDelete" :value="isEdit || isDelete ? dataId.note : ''" required>    
                </div>
                <div class="input-group">
                    <label class="input-label" for="">Label</label>
                    <div class="space-y-2">
                        <div class="checkbox-option">
                            <input class="checkbox" type="checkbox">
                            <span>Option 1</span>
                        </div>
                        <div class="checkbox-option">
                            <input class="checkbox" type="checkbox">
                            <span>Option 2</span>
                        </div>
                        <div class="checkbox-option">
                            <input class="checkbox" type="checkbox">
                            <span>Option 3</span>
                        </div>
                    </div>
                </div>
            </x-slot>
        </x-form>
    </div>
</div>    

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection