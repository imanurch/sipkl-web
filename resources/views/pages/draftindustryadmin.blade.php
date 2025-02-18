<div>
    batas --------------------------------
</div>

{{-- unconfirmed --}}
<div x-data="{ modalConfirm: null, id: null }">
    <x-table.table>
        <x-slot name="tableTitle">Pengajuan Industri Baru</x-slot>
        <x-slot name="filterActionForm">industryManagement</x-slot>
        <x-slot name="filter">
            <div class="flex w-full space-x-2">
                <div class="space-y-1 w-full">
                    <span class="text-xs text-neutral-400 w-32">Search</span>
                    <x-table.search value="{{ $filters['search'] ?? '' }}"
                        name="unconfirmedSearchKeyword"></x-table.search>
                </div>
            </div>
        </x-slot>
        <x-slot name="tHeader">
            <th>NO</th>
            <th>NAMA</th>
            <th>ALAMAT</th>
            <th>EMAIL</th>
            <th>NO TELP</th>
            <th>AKSI</th>
        </x-slot>
        <x-slot name="tBody">
            @foreach ($unconfirmedIndustryData as $dt)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $dt->name }}</td>
                    <td class="min-w-64">{{ $dt->address }}</td>
                    <td>{{ $dt->email }}</td>
                    <td>{{ $dt->phone_num }}</td>
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
</div>

{{-- partner --}}
<div x-data="{ modalAction: null, option: false, selected: 'Pilih Opsi' }">
    <x-table.table>
        <x-slot name="tableTitle">Industri Mitra</x-slot>
        <x-slot name="filterActionForm">industryManagement</x-slot>
        <x-slot name="btnAdd">
            <x-table.add_data></x-table.add_data>
        </x-slot>
        <x-slot name="filter">
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
            <th>NO</th>
            <th>NAMA</th>
            <th>ALAMAT</th>
            <th>EMAIL</th>
            <th>NO TELP</th>
            <th>STATUS</th>
            <th>AKSI</th>
        </x-slot>
        <x-slot name="tBody">
            @foreach ($partnerIndustryData as $dt)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $dt->name }}</td>
                    <td class="min-w-64">{{ $dt->address }}</td>
                    <td>{{ $dt->email }}</td>
                    <td>{{ $dt->phone_num }}</td>
                    <x-table.status_table status="{{ $dt->status }}"></x-table.status_table>
                    <x-table.action_table detail="hidden" btnInput="hidden" :data="$dt"></x-table.action_table>
                </tr>
            @endforeach
        </x-slot>
        {{-- pagination --}}
        <x-slot name="pagination">{{ $partnerIndustryData->links() }}</x-slot>
    </x-table.table>
    {{-- form --}}
    <div x-show="modalAction != null" class="form-modal">
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
                        :value="modalAction == 'isEdit' || modalAction=='isDelete' ? dataId.address : ''" required>
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
                        :value="modalAction == 'isEdit' || modalAction=='isDelete' ? dataId.phone_num : ''" required>
                </div>
            </x-slot>
        </x-form>
    </div>
</div>

{{-- rejected --}}
<div>
    <x-table.table>
        <x-slot name="tableTitle">Riwayat Pengajuan Industri Ditolak</x-slot>
        <x-slot name="filterActionForm">industryManagement</x-slot>
        <x-slot name="filter">
            <div class="flex w-full space-x-2">
                <div class="space-y-1 w-full">
                    <span class="text-xs text-neutral-400 w-32">Search</span>
                    <x-table.search value="{{ $filters['search'] ?? '' }}"
                        name="rejectedSearchKeyword"></x-table.search>
                </div>
            </div>
        </x-slot>
        <x-slot name="tHeader">
            <th>NO</th>
            <th>NAMA</th>
            <th>ALAMAT</th>
            <th>EMAIL</th>
            <th>NO TELP</th>
            <th>AKSI</th>
        </x-slot>
        <x-slot name="tBody">
            @foreach ($rejectedIndustryData as $dt)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $dt->name }}</td>
                    <td class="min-w-64">{{ $dt->address }}</td>
                    <td>{{ $dt->email }}</td>
                    <td>{{ $dt->phone_num }}</td>
                    <td class="text-center">
                        <a href="" class="btn btn-xs btn-warning-fill min-w-max">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                viewBox="0 0 18 18" fill="none">
                                <path
                                    d="M13.5 7.49998L10.5 4.49998M1.87494 16.125L4.41321 15.843C4.72333 15.8085 4.87839 15.7913 5.02332 15.7443C5.15191 15.7027 5.27427 15.6439 5.3871 15.5695C5.51428 15.4856 5.6246 15.3753 5.84523 15.1547L15.75 5.24998C16.5784 4.42156 16.5784 3.07841 15.75 2.24998C14.9215 1.42156 13.5784 1.42156 12.75 2.24998L2.84524 12.1547C2.6246 12.3753 2.51428 12.4856 2.43042 12.6128C2.35601 12.7256 2.2972 12.848 2.25557 12.9766C2.20866 13.1215 2.19143 13.2766 2.15697 13.5867L1.87494 16.125Z"
                                    stroke="" stroke-width="0.933333" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            <span class="">Edit</span>
                        </a>
                    </td>
                </tr>
            @endforeach
        </x-slot>
        {{-- pagination --}}
        <x-slot name="pagination">{{ $rejectedIndustryData->links() }}</x-slot>
    </x-table.table>
</div>