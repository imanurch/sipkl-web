@extends('layouts.app')

@section('page-title', 'Monitoring')
@section('profil', 'Advisor')
@section('content')

    {{-- card --}}
    {{-- <div class="layout-card">
        <x-card title="Perlu Surat" data="{{ $needDocument ?? '' }}" class="bg-icon-success">
            <svg class="" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                fill="none">
                <path d="M16.6667 5L7.50001 14.1667L3.33334 10" stroke="white" stroke-width="3" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </x-card>
    </div> --}}

    <div x-data="{ modalAction: null, option: false, optionInternship: false, selected: 'Pilih Opsi', selectedInternship: 'Pilih Opsi', selectedValueInternship: null, generateDocumentModal: false }">
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
                <th class="text-nowrap">Guru Pembimbing</th>
                <th class="text-nowrap">Industri</th>
                <th>Keterangan</th>
                <th>Dokumen</th>
                <th>Generate</th>
                <th>Aksi</th>
            </x-slot>
            <x-slot name="tBody">
                @foreach ($data as $dt)
                    <tr>
                        <td class="text-center">{{ $loop->iteration ?? '' }}</td>
                        <td>{{ $dt->type ?? '' }}</td>
                        <td class="text-nowrap">{{ $dt->date ?? '' }}</td>
                        <td>{{ $dt->internship->advisor->name ?? '' }}</td>
                        <td>{{ $dt->internship->industry->name ?? '' }}</td>
                        <td class="min-w-44">{{ $dt->note ?? '-' }}</td>
                        <td class="place-items-center">
                            @if (count($dt->monitoringDocument) > 0)
                                <div class="flex space-x-2">
                                    @foreach ($dt->monitoringDocument as $doc)
                                        <x-table.action_btn_table name="{{ $doc->type }}"
                                            href="{{ route('admin.monitoring.downloadFile', ['type' => $doc->type, 'filename' => $doc->url]) }}"></x-table.action_btn_table>
                                    @endforeach
                                </div>
                            @else
                                Belum Tersedia
                            @endif
                        </td>
                        <td class="place-items-center">
                            @if ($dt->internship->advisor)
                                <button @click.prevent="generateDocumentModal=true;dataId={{ $dt }}"
                                    class="btn btn-xs btn-default-outline">Generate</button>
                            @else
                                Pembimbing Belum Diatur
                            @endif
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
                            <button @click.prevent="option=!option" class="input input-select w-full"
                                :disabled="modalAction == 'isDelete'" required>
                                <span x-text="selected" class="text-neutral-800"></span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                    fill="none">
                                    <path d="M5 7.5L10 12.5L15 7.5" stroke="#667085" stroke-width="0.933333"
                                        stroke-linecap="round" stroke-linejoin="round" />
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
                        <input onclick="this.showPicker()" name="date" class="input"
                            :type="modalAction == 'isDelete' ? 'text' : 'date'" placeholder="Masukkan Tanggal"
                            :disabled="modalAction == 'isView' || modalAction == 'isDelete'"
                            :value="modalAction != null ? dataId.date : ''" required>
                    </div>
                    <div class="input-group">
                        <label class="input-label" for="">Industri - Pembimbing</label>
                        {{-- isAdd / isEdit --}}
                        <input type="hidden" name="internship_id" x-model="selectedValueInternship">
                        <div x-show="modalAction == 'isAdd'">
                            <button @click.prevent="optionInternship=!optionInternship" class="input input-select w-full"
                                :disabled="modalAction == 'isDelete'" required>
                                <span x-text="selectedInternship" class="text-neutral-800"></span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                    fill="none">
                                    <path d="M5 7.5L10 12.5L15 7.5" stroke="#667085" stroke-width="0.933333"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                            <div x-show="optionInternship" @click.away="optionInternship=false">
                                <ul class="border border-brand-600 rounded py-2 my-2 max-h-32 overflow-auto">
                                    @foreach ($internshipListData as $dt)
                                        @if ($dt->advisor != null)
                                            <li @click.prevent="optionInternship=false;
                                        selectedInternship='{{ $dt->industry->name ?? '' }} (Pembimbing {{ $dt->advisor->name ?? '' }})';selectedValueInternship='{{ $dt->id }}'"
                                                class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">
                                                {{ $dt->industry->name ?? '' }} (Pembimbing
                                                {{ $dt->advisor->name ?? '' }})</li>
                                        @else
                                            <li class="text-xs-reguler text-neutral-400 px-4 py-2">
                                                {{ $dt->industry->name ?? '' }} (Pembimbing Belum Diatur) </li>
                                        @endif
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
                </x-slot>
            </x-form>
        </div>

        <div x-show="generateDocumentModal" class="form-modal" x-data="{ transportationField: false }">
            {{-- <div class="form-modal"> --}}
            <form class="form" action="{{ route('admin.monitoring.generateSurat') }}" method="POST"
                @click.away="generateDocumentModal=false">
                <div class="form-header">
                    @csrf
                    <h3>Generate Dokumen</h3>
                    <svg @click="generateDocumentModal=false,selected='Pilih Opsi'" class="cursor-pointer"
                        xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28"
                        fill="none">
                        <path d="M19.8333 8.16675L8.16663 19.8334M8.16663 8.16675L19.8333 19.8334" stroke="#525A6A"
                            stroke-width="1.03704" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div class="form-body">
                    <div class="input-group">
                        <input type="hidden" name="monitoring_id" :value="generateDocumentModal ? dataId.id : ''">
                        <label class="input-label" for="">Jenis Dokumen</label>
                        <div>
                            <button @click.prevent="option=!option" class="input input-select w-full"
                                :disabled="modalAction == 'isDelete'" required>
                                <span x-text="selected" class="text-neutral-800"></span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 20 20" fill="none">
                                    <path d="M5 7.5L10 12.5L15 7.5" stroke="#667085" stroke-width="0.933333"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                            <input type="hidden" name="documentGenerateType" id="documentType" x-model="selected">
                            <div x-show="option" @click.away="option=false" class="bg-neutral-0">
                                <ul class="border border-brand-600 rounded py-2 mt-2 max-h-32 overflow-auto">
                                    <li @click.prevent="option=false;selected='Surat Tugas'"
                                        class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">
                                        Surat Tugas</li>
                                    <li @click.prevent="option=false;selected='SPPD';transportationField=true"
                                        class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">
                                        SPPD</li>
                                    <li @click.prevent="option=false;selected='Surat Pengantar'"
                                        class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">
                                        Surat Pengantar</li>
                                    <li @click.prevent="option=false;selected='Surat Penarikan'"
                                        class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">
                                        Surat Penarikan</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="input-group">
                        <label class="input-label" for="">Nomor Surat</label>
                        <input class="input h-full w-full" name="letter_num" type="text"
                            placeholder="Masukkan Nomor Surat" required>
                    </div>
                    <div x-show="transportationField" class="input-group">
                        <label class="input-label" for="">Kendaraan</label>
                        <input class="input h-full w-full" value="Motor" name="transportation" type="text"
                            placeholder="Masukkan Kendaraan" required>
                    </div>
                </div>
                <div class="form-footer">
                    <button @click.prevent="generateDocumentModal=false" class="btn btn-sm"
                        :class="modalAction == 'isView' ? 'btn-success-fill' : 'btn-error-fill'">
                        <span>Batalkan</span>
                    </button>
                    <button type="submit" class="btn btn-success-fill btn-sm">Generate</button>
                </div>
            </form>
        </div>

        {{-- empty state --}}
        @if (count($data) == 0)
            <x-not_found_empty_state>
                <x-slot name="desc">Tambah data monitoring terlebih dahulu ya!</x-slot>
                <x-slot name="cta">
                    <button @click="modalAction='isAdd'" class="btn btn-xs btn-default-fill">Tambah
                        Data</button>
                </x-slot>
            </x-not_found_empty_state>
        @endif
    </div>

    {{-- script Modal Action --}}
    <script>
        function setFormAction(modalAction, id = null) {
            const form = document.getElementById('modalForm');
            if (modalAction === 'isAdd') {
                form.action = "{{ route('admin.monitoring.store') }}";
            } else if (modalAction === 'isEdit' && id) {
                form.action = `{{ route('admin.monitoring.update', ':id') }}`.replace(':id', id);
            } else if (modalAction === 'isDelete' && id) {
                form.action = `{{ route('admin.monitoring.destroy', ':id') }}`.replace(':id', id);
            }
        }
    </script>

    {{-- <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}
@endsection
