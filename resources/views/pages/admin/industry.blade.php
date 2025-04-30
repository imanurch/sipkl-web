@extends('layouts.app')

@section('page-title', 'Industri')
@section('profil', 'Admin')
@section('content')

    {{-- card --}}
    <div class="layout-card">
        <x-card title="Pengajuan" data="{{ $unconfirmedIndustry ?? '' }}" class="bg-icon-warning">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path
                    d="M10 8.50224C10.1762 8.00136 10.524 7.57901 10.9817 7.30998C11.4395 7.04095 11.9777 6.9426 12.501 7.03237C13.0243 7.12213 13.499 7.39421 13.8409 7.80041C14.1829 8.20661 14.37 8.72072 14.3692 9.25168C14.3692 10.7506 12.1209 11.5 12.1209 11.5M12.1499 14.5H12.1599M9.9 19.2L11.36 21.1467C11.5771 21.4362 11.6857 21.5809 11.8188 21.6327C11.9353 21.678 12.0647 21.678 12.1812 21.6327C12.3143 21.5809 12.4229 21.4362 12.64 21.1467L14.1 19.2C14.3931 18.8091 14.5397 18.6137 14.7185 18.4645C14.9569 18.2656 15.2383 18.1248 15.5405 18.0535C15.7671 18 16.0114 18 16.5 18C17.8978 18 18.5967 18 19.1481 17.7716C19.8831 17.4672 20.4672 16.8831 20.7716 16.1481C21 15.5967 21 14.8978 21 13.5V7.8C21 6.11984 21 5.27976 20.673 4.63803C20.3854 4.07354 19.9265 3.6146 19.362 3.32698C18.7202 3 17.8802 3 16.2 3H7.8C6.11984 3 5.27976 3 4.63803 3.32698C4.07354 3.6146 3.6146 4.07354 3.32698 4.63803C3 5.27976 3 6.11984 3 7.8V13.5C3 14.8978 3 15.5967 3.22836 16.1481C3.53284 16.8831 4.11687 17.4672 4.85195 17.7716C5.40326 18 6.10218 18 7.5 18C7.98858 18 8.23287 18 8.45951 18.0535C8.76169 18.1248 9.04312 18.2656 9.2815 18.4645C9.46028 18.6137 9.60685 18.8091 9.9 19.2Z"
                    stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </x-card>
        <x-card title="Mitra" data="{{ $partnerIndustry ?? '' }}" class="bg-icon-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                <path
                    d="M5.75008 9.53712V11.8519M5.75008 9.53712V3.98156C5.75008 3.2145 6.38435 2.59267 7.16675 2.59267C7.94915 2.59267 8.58342 3.2145 8.58342 3.98156M5.75008 9.53712C5.75008 8.77006 5.11582 8.14823 4.33341 8.14823C3.55101 8.14823 2.91675 8.77006 2.91675 9.53712V11.389C2.91675 15.2243 6.08806 18.3334 10.0001 18.3334C13.9121 18.3334 17.0834 15.2243 17.0834 11.389V6.75934C17.0834 5.99228 16.4492 5.37045 15.6667 5.37045C14.8843 5.37045 14.2501 5.99228 14.2501 6.75934M8.58342 3.98156V9.07416M8.58342 3.98156V3.05564C8.58342 2.28857 9.21768 1.66675 10.0001 1.66675C10.7825 1.66675 11.4167 2.28857 11.4167 3.05564V3.98156M11.4167 3.98156V9.07416M11.4167 3.98156C11.4167 3.2145 12.051 2.59267 12.8334 2.59267C13.6158 2.59267 14.2501 3.2145 14.2501 3.98156V6.75934M14.2501 6.75934V9.07416"
                    stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </x-card>
        <x-card title="Ditolak" data="{{ $rejectedIndustry ?? '' }}" class="bg-icon-error">
            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                <path d="M17.5 7L7.5 17M7.5 7L17.5 17" stroke="white" stroke-width="3" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </x-card>
        <x-card title="Aktif" data="{{ $activeIndustry ?? '' }}" class="bg-icon-success">
            <svg class="" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                fill="none">
                <path d="M16.6667 5L7.50001 14.1667L3.33334 10" stroke="white" stroke-width="3" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </x-card>
        <x-card title="Non Aktif" data="{{ $inactiveIndustry ?? '' }}" class="bg-icon-error">
            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                <path d="M17.5 7L7.5 17M7.5 7L17.5 17" stroke="white" stroke-width="3" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </x-card>
    </div>

    <div x-data="{ tabs: '{{ $activeTab }}' }" class="space-y-4">
        {{-- tabs --}}
        <div class="flex w-full justify-between border-b border-neutral-100">
            <div class="flex">
                <button @click="window.location.href = '/admin/industryManagement?tab=unconfirmed'" class="text-sm-medium py-3 px-6"
                    :class="tabs == 'unconfirmed' ? 'text-brand-500 border-b border-brand-500 hover:text-brand-500' :
                        'text-neutral-400 hover:text-neutral-700'">Pengajuan</button>
                <button @click="window.location.href = '/admin/industryManagement?tab=partner'" class="text-sm-medium py-3 px-6"
                    :class="tabs == 'partner' ? 'text-brand-500 border-b border-brand-500 hover:text-brand-500' :
                        'text-neutral-400 hover:text-neutral-700'">Mitra</button>
                <button @click="window.location.href = '/admin/industryManagement?tab=rejected'" class="text-sm-medium py-3 px-6"
                    :class="tabs == 'rejected' ? 'text-brand-500 border-b border-brand-500 hover:text-brand-500' :
                        'text-neutral-400 hover:text-neutral-700'">Ditolak</button>
            </div>
        </div>

        {{-- unconfirmed --}}
        <div x-show="tabs=='unconfirmed'" x-data="{ modalConfirm: null, id: null }">
            <x-table.table>
                <x-slot name="tableTitle">Pengajuan Industri Baru</x-slot>
                <x-slot name="filterActionForm">industryManagement?tab=unconfirmed</x-slot>
                <x-slot name="mainSearchName">unconfirmedSearchKeyword</x-slot>
                <x-slot name="mainSearchAddition">
                    <input type="hidden" name="tab" value="unconfirmed">
                </x-slot>
                <x-slot name="filter">
                    <input type="hidden" name="tab" value="unconfirmed">
                    <div class="flex w-full space-x-2">
                        <div class="space-y-1 w-full">
                            <span class="text-xs text-neutral-400 w-32">Search</span>
                            <x-table.search value="{{ $filters['search'] ?? '' }}"
                                name="unconfirmedSearchKeyword"></x-table.search>
                        </div>
                    </div>
                </x-slot>
                <x-slot name="tHeader">
                    <th>No</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Email</th>
                    <th>No Telp</th>
                    <th>Nama Pimpinan</th>
                    <th>Aksi</th>
                </x-slot>
                <x-slot name="tBody">
                    @foreach ($unconfirmedIndustryData as $dt)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="left whitespace-nowrap">{{ $dt->name }}</td>
                            <td class="left min-w-64">{{ $dt->address }}</td>
                            <td>{{ $dt->email }}</td>
                            <td>{{ $dt->phone_num }}</td>
                            <td>{{ $dt->leader_name }}</td>
                            <x-table.action_confirm_table :id="$dt->id"></x-table.action_confirm_table>
                        </tr>
                    @endforeach
                </x-slot>
                {{-- pagination --}}
                <x-slot name="pagination">{{ $unconfirmedIndustryData->links() }}</x-slot>

            </x-table.table>

            {{-- modal --}}
            {{-- accept modal confirm --}}
            <div x-show="modalConfirm=='accept'" class="confirm-modal">
                <div class="place-self-center bg-neutral-0 border rounded justify-center w-80 border-success-400">
                    <h6 class="text-xs-reguler py-5 px-6 text-center max-w-72">Apakah Anda Yakin
                        Ingin <span class="text-xs-medium text-success-800">Menerima Pengajuan Industri
                            Ini?</span>
                    </h6>
                    <div class="flex justify-center py-3 space-x-4 border-t border-success-400">
                        <button @click="modalConfirm=null" class="btn btn-xs btn-success-outline">Tidak</button>
                        <a :href="`{{ route('admin.industryRequest.status.confirm', ['industryId' => 'id', 'status' => 'accept']) }}`
                        .replace('id', id)"
                            class="btn btn-xs btn-success-fill">Ya</a>
                    </div>
                </div>
            </div>

            {{-- reject modal confirm --}}
            <div x-show="modalConfirm=='reject'" class="confirm-modal">
                <div class="place-self-center bg-neutral-0 border rounded justify-center w-80 border-error-400">
                    <h6 class="text-xs-reguler py-5 px-6 text-center max-w-72">Apakah Anda Yakin
                        Ingin <span class="text-xs-medium text-error-800">Menolak Pengajuan Industri
                            Ini?</span>
                    </h6>
                    <div class="flex justify-center py-3 space-x-4 border-t border-error-400">
                        <button @click="modalConfirm=null" class="btn btn-xs btn-error-outline">Tidak</button>
                        <a :href="`{{ route('admin.industryRequest.status.confirm', ['industryId' => 'id', 'status' => 'reject']) }}`
                        .replace('id', id)"
                            class="btn btn-xs btn-error-fill">Ya</a>
                    </div>
                </div>
            </div>

            {{-- empty state --}}
            @if (count($unconfirmedIndustryData) == 0)
                <x-not_found_empty_state>
                    <x-slot name="desc">Belum ada pengajuan industri yang harus dikonfirmasi</x-slot>
                </x-not_found_empty_state>
            @endif
        </div>


        {{-- partner --}}
        <div x-show="tabs=='partner'" x-data="{ modalAction: null, option: false, selected: 'Pilih Opsi', exportModal: false }">
            <x-table.table>
                <x-slot name="tableTitle">Industri Mitra</x-slot>
                <x-slot name="filterActionForm">industryManagement?tab=partner</x-slot>
                <x-slot name="partnerSearchKeyword">partnerSearchKeyword</x-slot>      
                <x-slot name="mainSearchAddition">
                    <input type="hidden" name="tab" value="partner">
                </x-slot>          
                <x-slot name="btnAdd">
                    <x-table.import></x-table.import>
                    <div>
                        <button @click="exportModal=true" class="btn btn-default-fill btn-xs h-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14"
                                fill="none">
                                <path d="M7.00002 2.91675V11.0834M2.91669 7.00008H11.0834" stroke-width="1.6"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span class="p-0 m-0">Ekspor</span>
                        </button>

                        <div x-show="exportModal" class="form-modal">
                            {{-- <div class="form-modal"> --}}
                            <form class="form" action="{{ route('admin.industryManagement.export') }}" method="POST"
                                @click.away="generateDocumentModal=false">
                                <div class="form-header">
                                    @csrf
                                    <h3>Ekspor Data Industri</h3>
                                    <svg @click="exportModal=false,selected='Pilih Opsi'" class="cursor-pointer"
                                        xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                                        viewBox="0 0 28 28" fill="none">
                                        <path d="M19.8333 8.16675L8.16663 19.8334M8.16663 8.16675L19.8333 19.8334"
                                            stroke="#525A6A" stroke-width="1.03704" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div class="form-body">
                                    <div class="input-group">
                                        <label class="input-label" for="">Pilih Data</label>
                                        <div>
                                            <button @click.prevent="option=!option" class="input input-select w-full"
                                                required>
                                                <span x-text="selected"
                                                    :class="selected == 'Pilih Opsi' ? 'text-neutral-300' : 'text-neutral-800'"></span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    viewBox="0 0 20 20" fill="none">
                                                    <path d="M5 7.5L10 12.5L15 7.5" stroke="#667085"
                                                        stroke-width="0.933333" stroke-linecap="round"
                                                        stroke-linejoin="round"/>
                                                </svg>
                                            </button>
                                            <input type="hidden" name="data_type" x-model="selected">
                                            <div x-show="option" @click.away="option=false" class="bg-neutral-0">
                                                <ul
                                                    class="border border-brand-600 rounded py-2 mt-2 max-h-32 overflow-auto">
                                                    <li @click.prevent="option=false;selected='Semua'"
                                                        class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">
                                                        Semua</li>
                                                    <li @click.prevent="option=false;selected='Industri Aktif'"
                                                        class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">
                                                        Industri Aktif</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-footer">
                                    <button @click.prevent="exportModal=false" class="btn btn-sm"
                                        :class="modalAction == 'isView' ? 'btn-success-fill' : 'btn-error-fill'">
                                        <span>Batalkan</span>
                                    </button>
                                    <button type="submit" class="btn btn-success-fill btn-sm">Ekspor</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <x-table.add_data></x-table.add_data>
                </x-slot>
                <x-slot name="filter">
                    <input type="hidden" name="tab" value="partner">
                    <div class="flex w-full space-x-2">
                        <div class="space-y-1 w-full">
                            <span class="text-xs text-neutral-400 w-32">Search</span>
                            <x-table.search value="{{ $filters['search'] ?? '' }}"
                                name="partnerSearchKeyword"></x-table.search>
                        </div>
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
                <x-slot name="tHeader">
                    <th>No</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Email</th>
                    <th>No Telp</th>
                    <th>Nama Pimpinan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </x-slot>
                <x-slot name="tBody">
                    @foreach ($partnerIndustryData as $dt)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="left whitespace-nowrap">{{ $dt->name }}</td>
                            <td class="left min-w-64">{{ $dt->address }}</td>
                            <td>{{ $dt->email }}</td>
                            <td>{{ $dt->phone_num }}</td>
                            <td>{{ $dt->leader_name }}</td>
                            <x-table.status_table status="{{ $dt->status }}"></x-table.status_table>
                            <x-table.action_table detail="hidden" btnInput="hidden"
                                :data="$dt"></x-table.action_table>
                        </tr>
                    @endforeach
                </x-slot>
                {{-- pagination --}}
                <x-slot name="pagination">{{ $partnerIndustryData->links() }}</x-slot>
            </x-table.table>

            {{-- action form --}}
            <div x-show="modalAction != null && modalAction !='isImport'" class="form-modal">
                <x-form>
                    <x-slot name="formTitle">Data Industri</x-slot>
                    <x-slot name="formBody">
                        <div class="input-group">
                            <label class="input-label" for="">Nama</label>
                            <input name="name" class="input" type="text" placeholder="Masukkan Nama"
                                :disabled="modalAction == 'isDelete'"
                                :value="modalAction == 'isEdit' || modalAction=='isDelete' ? dataId.name : ''" required>
                        </div>
                        <div class="input-group">
                            <label class="input-label" for="">Alamat</label>
                            <input name="address" class="input" type="text" placeholder="Masukkan Alamat"
                                :disabled="modalAction == 'isDelete'"
                                :value="modalAction == 'isEdit' || modalAction=='isDelete' ? dataId.address : ''"
                                required>
                        </div>
                        <div class="input-group">
                            <label class="input-label" for="">Email</label>
                            <input name="email" class="input" type="text" placeholder="Masukkan Email"
                                :disabled="modalAction == 'isDelete'"
                                :value="modalAction == 'isEdit' || modalAction=='isDelete' ? dataId.email : ''" required>
                        </div>
                        <div class="input-group">
                            <label class="input-label" for="">Nomor Telepon</label>
                            <input name="phone_num" class="input" type="text" placeholder="Masukkan Nomor Telepon"
                                :disabled="modalAction == 'isDelete'"
                                :value="modalAction == 'isEdit' || modalAction=='isDelete' ? dataId.phone_num : ''"
                                required>
                        </div>
                        <div class="input-group">
                            <label class="input-label" for="">Nama Pimpinan</label>
                            <input name="leader_name" class="input" type="text" placeholder="Masukkan Nama Pimpinan"
                                :disabled="modalAction == 'isDelete'"
                                :value="modalAction == 'isEdit' || modalAction=='isDelete' ? dataId.leader_name : ''"
                                required>
                        </div>
                    </x-slot>
                </x-form>
            </div>

            {{-- import form --}}
            <div x-show="modalAction=='isImport'" class="form-modal">
                <x-form action="{{ route('admin.industryManagement.import') }}">
                    <x-slot name="formTitle">Impor Data Industri</x-slot>
                    <x-slot name="formBody">
                        <div class="input-group">
                            <label class="input-label" for="">Unggah File (Format file: Excel)</label>
                            <input class="input" type="file" name="import_file" id="" required>
                        </div>
                        <div class="flex place-items-center">
                            <span class="text-xs-reguler">Template Impor Data :</span>
                            <a href="{{ route('admin.industryManagement.downloadTemplateFile') }}"
                                class="btn btn-xs btn-default-clear">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                    viewBox="0 0 14 14" fill="none">
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
            @if (count($partnerIndustryData) == 0)
                <x-not_found_empty_state>
                    <x-slot name="desc">Tambah data industri terlebih dahulu ya!</x-slot>
                    <x-slot name="cta">
                        <button @click="modalAction='isAdd'" class="btn btn-xs btn-default-fill">Tambah Data</button>
                    </x-slot>
                </x-not_found_empty_state>
            @endif
        </div>

        {{-- rejected --}}
        <div x-show="tabs=='rejected'" x-data="{ modalEditStatus: false, option: false, selected: 'Pilih Opsi', valueSelected: null }">
            <x-table.table>
                <x-slot name="tableTitle">Riwayat Pengajuan Industri Ditolak</x-slot>
                <x-slot name="filterActionForm">industryManagement?tab=rejected</x-slot>
                <x-slot name="mainSearchName">rejectedSearchKeyword</x-slot>
                <x-slot name="mainSearchAddition">
                    <input type="hidden" name="tab" value="rejected">
                </x-slot>
                <x-slot name="filter">
                    <input type="hidden" name="tab" value="rejected">
                    <div class="flex w-full space-x-2">
                        <div class="space-y-1 w-full">
                            <span class="text-xs text-neutral-400 w-32">Search</span>
                            <x-table.search value="{{ $filters['search'] ?? '' }}"
                                name="rejectedSearchKeyword"></x-table.search>
                        </div>
                    </div>
                </x-slot>
                <x-slot name="tHeader">
                    <th>No</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Email</th>
                    <th>No Telp</th>
                    <th>Nama Pimpinan</th>
                    <th>Aksi</th>
                </x-slot>
                <x-slot name="tBody">
                    @foreach ($rejectedIndustryData as $dt)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="left whitespace-nowrap">{{ $dt->name }}</td>
                            <td class="left min-w-64">{{ $dt->address }}</td>
                            <td>{{ $dt->email }}</td>
                            <td>{{ $dt->phone_num }}</td>
                            <td>{{ $dt->leader_name }}</td>
                            <td class="text-center">
                                <button
                                    @click.prevent="setUpdateRejectedStatusFormAction({{ $dt->id }});modalEditStatus=true;dataId={{ $dt->toJson() }};"
                                    class="btn btn-xs btn-warning-fill min-w-max">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        viewBox="0 0 18 18" fill="none">
                                        <path
                                            d="M13.5 7.49998L10.5 4.49998M1.87494 16.125L4.41321 15.843C4.72333 15.8085 4.87839 15.7913 5.02332 15.7443C5.15191 15.7027 5.27427 15.6439 5.3871 15.5695C5.51428 15.4856 5.6246 15.3753 5.84523 15.1547L15.75 5.24998C16.5784 4.42156 16.5784 3.07841 15.75 2.24998C14.9215 1.42156 13.5784 1.42156 12.75 2.24998L2.84524 12.1547C2.6246 12.3753 2.51428 12.4856 2.43042 12.6128C2.35601 12.7256 2.2972 12.848 2.25557 12.9766C2.20866 13.1215 2.19143 13.2766 2.15697 13.5867L1.87494 16.125Z"
                                            stroke="" stroke-width="0.933333" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                    <span class="">Ubah Status</span>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </x-slot>
                {{-- pagination --}}
                <x-slot name="pagination">{{ $rejectedIndustryData->links() }}</x-slot>
            </x-table.table>

            {{-- action form --}}
            <div x-show="modalEditStatus" class="form-modal">
                <form class="form" method="POST" @click.away="modalAction=null" id="updateRejectedStatusForm"
                    {{-- :action="`{{ route('admin.industryManagement.updateStatusRejectedIndustry', ['industryId' => '__ID__']) }}`
                    .replace('__ID__', id)" --}}>
                    <div class="form-header">
                        @csrf
                        @method('PATCH')
                        <h3>Ubah Status Industri</h3>
                        <svg @click="modalEditStatus=false,selected='Pilih Opsi',valueSelected=''" class="cursor-pointer"
                            xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28"
                            fill="none">
                            <path d="M19.8333 8.16675L8.16663 19.8334M8.16663 8.16675L19.8333 19.8334" stroke="#525A6A"
                                stroke-width="1.03704" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <div class="form-body">
                        {{-- {{ route('admin.industryManagement.updateStatusRejectedIndustry', ['industryId' => 10]) }} --}}
                        <div class="input-group">
                            <label class="input-label" for="">Nama</label>
                            <input name="name" class="input" type="text" placeholder="Masukkan Nama"
                                :value="modalEditStatus ? dataId.name : ''" disabled>
                        </div>
                        <div class="input-group">
                            <label class="input-label" for="">Alamat</label>
                            <input name="address" class="input" type="text" placeholder="Masukkan Alamat"
                                :value="modalEditStatus ? dataId.address : ''" disabled>
                        </div>
                        <div class="input-group">
                            <label class="input-label" for="">Email</label>
                            <input name="email" class="input" type="text" placeholder="Masukkan Email"
                                :value="modalEditStatus ? dataId.email : ''" disabled>
                        </div>
                        <div class="input-group">
                            <label class="input-label" for="">Nomor Telepon</label>
                            <input name="phone_num" class="input" type="text" placeholder="Masukkan Nomor Telepon"
                                :value="modalEditStatus ? dataId.phone_num : ''" disabled>
                        </div>
                        <div class="input-group">
                            <label class="input-label" for="">Nama Pimpinan</label>
                            <input name="leader_name" class="input" type="text" placeholder="Masukkan Nama Pimpinan"
                                :value="modalEditStatus ? dataId.leader_name : ''" disabled>
                        </div>
                        <div class="input-group">
                            <label class="input-label" for="">Status</label>
                            <input type="hidden" name="status" x-model="valueSelected">
                            <div>
                                <button @click.prevent="option=!option" class="input input-select w-full">
                                    <span x-text="selected" class="text-neutral-800"></span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 20 20" fill="none">
                                        <path d="M5 7.5L10 12.5L15 7.5" stroke="#667085" stroke-width="0.933333"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                                <div x-show="option" @click.away="option=false">
                                    <ul class="border border-brand-600 rounded py-2 my-2 max-h-32 overflow-auto">
                                        <li @click="option=false;selected='Diterima';valueSelected='accept'"
                                            class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">
                                            Diterima</li>
                                        <li @click="option=false;selected='Ditolak';valueSelected='reject'"
                                            class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">
                                            Ditolak</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-footer">
                        <button @click.prevent="modalEditStatus=false" class="btn btn-sm btn-error-fill">
                            <span>Batalkan</span>
                        </button>
                        <button type="submit" class="btn btn-success-fill btn-sm">
                            <span>Ubah Status</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- empty state --}}
            @if (count($rejectedIndustryData) == 0)
                <x-not_found_empty_state>
                    <x-slot name="desc">Tidak ada riwayat pengajuan industri yang ditolak</x-slot>
                </x-not_found_empty_state>
            @endif
        </div>

    </div>

    {{-- script Partner Action Modal --}}
    <script>
        function setFormAction(modalAction, id = null) {
            const form = document.getElementById('modalForm');
            if (modalAction === 'isAdd') {
                form.action = "{{ route('admin.industryManagement.store') }}";
            } else if (modalAction === 'isEdit' && id) {
                form.action = `{{ route('admin.industryManagement.update', ':id') }}`.replace(':id', id);
            } else if (modalAction === 'isDelete' && id) {
                form.action = `{{ route('admin.industryManagement.destroy', ':id') }}`.replace(':id', id);
            }
        }
    </script>

    <script>
        function setUpdateRejectedStatusFormAction(id = null) {
            const form = document.getElementById('updateRejectedStatusForm');
            form.action = `{{ route('admin.industryManagement.updateStatusRejectedIndustry', '__ID__') }}`.replace(
                '__ID__', id);
        }
    </script>

@endsection
