@extends('layouts.app')

@section('page-title', 'Monitoring')
@section('profil', 'Advisor')
@section('content')

    <x-guide guideTitle="Monitoring">
        <li>Klik "<span class="text-xs-semibold">Tambah Data</span>" untuk menambahkan data monitoring</li>
        <li>Setelah data ditambahkan, silakan menunggu dokumen yang akan digenerate oleh admin</li>
        <li>Hubungi admin PKL jika membutuhkan informasi lebih lanjut terkait dokumen</li>
    </x-guide>

    {{-- <div x-data="{ modalAction: null, option: false, optionInternship: false, selected: 'Pilih Opsi', selectedInternship: 'Pilih Opsi' }"> --}}
    <div x-data="{ modalAction: null, option: false, optionInternship: false, selected: 'Pilih Opsi', selectedInternship: 'Pilih Opsi', selectedValueInternship: null }">
        <x-table.table>
            <x-slot name="tableTitle">Data Monitoring</x-slot>
            <x-slot name="filterActionForm">monitoring</x-slot>
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
                <div class="space-y-1 w-full">
                    <span class="text-xs text-neutral-400 w-32">Jenis</span>
                    <x-table.select_option_filter optionName="type"
                        defaultSelected="{{ $filters['type'] != '' ? $filters['type'] : 'Semua Jenis' }}">
                        <x-slot name="option">
                            <li class="option-filter-toolbar-table"
                                @click="option=false;selected='Pelepasan';valueSelected='Pelepasan'">
                                Pelepasan </li>
                            <li class="option-filter-toolbar-table"
                                @click="option=false;selected='Kunjungan';valueSelected='Kunjungan'">
                                Kunjungan </li>
                            <li class="option-filter-toolbar-table"
                                @click="option=false;selected='Penarikan';valueSelected='Penarikan'">
                                Penarikan </li>
                        </x-slot>
                    </x-table.select_option_filter>
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
                <th>Jenis Monitoring</th>
                <th>Waktu</th>
                <th>Kelompok - Industri</th>
                <th>Keterangan</th>
                <th>Dokumen</th>
                <th>Aksi</th>
            </x-slot>
            <x-slot name="tBody">
                @foreach ($data as $dt)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $dt->type }}</td>
                        <td>{{ $dt->date }}</td>
                        <td>{{ $dt->internship->group_id }} - {{ $dt->internship->industry->name }}</td>
                        <td class="">{{ $dt->note ?? '-' }}</td>
                        <td class="place-items-center">
                            <div class="flex space-x-2">
                                @if (count($dt->monitoringDocument) > 0)
                                    @foreach ($dt->monitoringDocument as $doc)
                                        <a href="{{ route('advisor.monitoring.downloadFile', ['type' => $doc->type, 'filename' => $doc->url]) }}"
                                            target="_blank" class="btn btn-xs btn-default-fill">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 14 14" fill="none">
                                                <path
                                                    d="M12.25 5.25001L12.25 1.75001M12.25 1.75001H8.74999M12.25 1.75001L7 7M5.83333 1.75H4.55C3.56991 1.75 3.07986 1.75 2.70552 1.94074C2.37623 2.10852 2.10852 2.37623 1.94074 2.70552C1.75 3.07986 1.75 3.56991 1.75 4.55V9.45C1.75 10.4301 1.75 10.9201 1.94074 11.2945C2.10852 11.6238 2.37623 11.8915 2.70552 12.0593C3.07986 12.25 3.56991 12.25 4.55 12.25H9.45C10.4301 12.25 10.9201 12.25 11.2945 12.0593C11.6238 11.8915 11.8915 11.6238 12.0593 11.2945C12.25 10.9201 12.25 10.4301 12.25 9.45V8.16667"
                                                    stroke="white" stroke-width="0.93" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                            <span class="min-w-max">{{ $doc->type }}</span>
                                        </a>
                                    @endforeach
                                @else
                                    Belum Tersedia
                                @endif
                            </div>
                        </td>
                        <x-table.action_table detail="hidden" :data="$dt"></x-table.action_table>
                    </tr>
                @endforeach
            </x-slot>
            {{-- pagination --}}
            <x-slot name="pagination">{{ $data->links() }}</x-slot>
        </x-table.table>

        <div x-show="modalAction != null" class="form-modal">
            <x-form>
                <x-slot name="formTitle">Monitoring</x-slot>
                <x-slot name="formBody">
                    <div class="input-group">
                        <label class="input-label" for="">Jenis Monitoring</label>
                        {{-- isAdd / isEdit --}}
                        <input type="hidden" name="type" x-model="selected">
                        <div x-show="modalAction == 'isAdd' || modalAction == 'isEdit'">
                            <button @click.prevent="option=!option" class="input input-select w-full" :disabled="isDelete"
                                required>
                                <span x-text="selected"
                                    :class="selected == 'Pilih Opsi' ? 'text-neutral-300' : 'text-neutral-800'"></span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                    fill="none">
                                    <path d="M5 7.5L10 12.5L15 7.5" stroke="#667085" stroke-width="0.933333"
                                        stroke-linecap="round" stroke-linejoin="round" :hidden="isDelete" />
                                </svg>
                            </button>
                            <div x-show="option" @click.away="option=false">
                                <ul class="border border-brand-600 rounded py-2 my-2 max-h-32 overflow-auto">
                                    <li @click.prevent="option=false;selected='Pelepasan'"
                                        class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">
                                        Pelepasan</li>
                                    <li @click.prevent="option=false;selected='Kunjungan'"
                                        class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">
                                        Kunjungan</li>
                                    <li @click.prevent="option=false;selected='Penarikan'"
                                        class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">
                                        Penarikan</li>
                                </ul>
                            </div>
                        </div>
                        {{-- isView / isDelete --}}
                        <input x-show="modalAction == 'isView' || modalAction == 'isDelete'" type="text" class="input"
                            disabled :value="modalAction != null ? dataId.type : ''">
                    </div>
                    <div class="input-group">
                        <label class="input-label" for="">Waktu</label>
                        <input onclick="this.showPicker()" name="date" class="input" type="date"
                            placeholder="Masukkan Tanggal" :disabled="modalAction == 'isView' || modalAction == 'isDelete'"
                            :value="modalAction != null ? dataId.date : ''" required>
                    </div>
                    <div class="input-group">
                        <label class="input-label" for="">Kelompok - Industri</label>
                        {{-- isAdd / isEdit --}}
                        <input type="hidden" name="internship_id" x-model="selectedValueInternship">
                        <div x-show="modalAction == 'isAdd'">
                            <button @click.prevent="optionInternship=!optionInternship" class="input input-select w-full"
                                :disabled="isDelete" required>
                                <span x-text="selectedInternship"
                                    :class="selected == 'Pilih Opsi' ? 'text-neutral-300' : 'text-neutral-800'"></span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 20 20" fill="none">
                                    <path d="M5 7.5L10 12.5L15 7.5" stroke="#667085" stroke-width="0.933333"
                                        stroke-linecap="round" stroke-linejoin="round" :hidden="isDelete" />
                                </svg>
                            </button>
                            <div x-show="optionInternship" @click.away="optionInternship=false">
                                <ul class="border border-brand-600 rounded py-2 my-2 max-h-32 overflow-auto">
                                    @foreach ($internshipListData as $dt)
                                        <li @click.prevent="optionInternship=false;
                                        selectedInternship='{{ $dt->group_id }} - {{ $dt->industry->name }}';selectedValueInternship='{{ $dt->id }}'"
                                            class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">
                                            {{ $dt->group_id }} - {{ $dt->industry->name }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        {{-- isView / isDelete --}}
                        <input x-show="modalAction != 'isAdd'" type="text" class="input" disabled
                            :value="modalAction != null ? dataId.internship.group_id + ' - ' + dataId.internship.industry.name :
                                ''">

                    </div>
                    <div class="input-group">
                        <label class="input-label" for="">Keterangan</label>
                        <input name="note" class="input" type="text" placeholder="Masukkan Keterangan"
                            :disabled="modalAction == 'isView' || modalAction == 'isDelete'"
                            :value="modalAction != null ? dataId.note : ''">
                    </div>
                    {{-- <div class="input-group">
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
                    </div> --}}
                </x-slot>
            </x-form>
        </div>

        {{-- empty state --}}
        @if (count($data) == 0)
            <x-not_found_empty_state>
                <x-slot name="desc">Tambah data monitoring terlebih dahulu ya!</x-slot>
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
                form.action = "{{ route('advisor.monitoring.store') }}";
            } else if (modalAction === 'isEdit' && id) {
                form.action = `{{ route('advisor.monitoring.update', ':id') }}`.replace(':id', id);
            } else if (modalAction === 'isDelete' && id) {
                form.action = `{{ route('advisor.monitoring.destroy', ':id') }}`.replace(':id', id);
            }
        }
    </script>

    {{-- <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}
@endsection
