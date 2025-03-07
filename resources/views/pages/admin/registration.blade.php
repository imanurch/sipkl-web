@extends('layouts.app')

@section('page-title', 'Pendaftaran PKL')
@section('profil', 'Admin')
@section('content')

    <div class="layout-card">
        <x-card title="Pendaftaran" data="{{ $unconfirmedRegistration ?? '' }}" class="bg-icon-warning">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path
                    d="M10 8.50224C10.1762 8.00136 10.524 7.57901 10.9817 7.30998C11.4395 7.04095 11.9777 6.9426 12.501 7.03237C13.0243 7.12213 13.499 7.39421 13.8409 7.80041C14.1829 8.20661 14.37 8.72072 14.3692 9.25168C14.3692 10.7506 12.1209 11.5 12.1209 11.5M12.1499 14.5H12.1599M9.9 19.2L11.36 21.1467C11.5771 21.4362 11.6857 21.5809 11.8188 21.6327C11.9353 21.678 12.0647 21.678 12.1812 21.6327C12.3143 21.5809 12.4229 21.4362 12.64 21.1467L14.1 19.2C14.3931 18.8091 14.5397 18.6137 14.7185 18.4645C14.9569 18.2656 15.2383 18.1248 15.5405 18.0535C15.7671 18 16.0114 18 16.5 18C17.8978 18 18.5967 18 19.1481 17.7716C19.8831 17.4672 20.4672 16.8831 20.7716 16.1481C21 15.5967 21 14.8978 21 13.5V7.8C21 6.11984 21 5.27976 20.673 4.63803C20.3854 4.07354 19.9265 3.6146 19.362 3.32698C18.7202 3 17.8802 3 16.2 3H7.8C6.11984 3 5.27976 3 4.63803 3.32698C4.07354 3.6146 3.6146 4.07354 3.32698 4.63803C3 5.27976 3 6.11984 3 7.8V13.5C3 14.8978 3 15.5967 3.22836 16.1481C3.53284 16.8831 4.11687 17.4672 4.85195 17.7716C5.40326 18 6.10218 18 7.5 18C7.98858 18 8.23287 18 8.45951 18.0535C8.76169 18.1248 9.04312 18.2656 9.2815 18.4645C9.46028 18.6137 9.60685 18.8091 9.9 19.2Z"
                    stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </x-card>
        <x-card title="Diterima" data="{{ $acceptedRegistration ?? '' }}" class="bg-icon-success">
            <svg class="" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                fill="none">
                <path d="M16.6667 5L7.50001 14.1667L3.33334 10" stroke="white" stroke-width="3" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </x-card>
        <x-card title="Ditolak" data="{{ $rejectedRegistration ?? '' }}" class="bg-icon-error">
            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                <path d="M17.5 7L7.5 17M7.5 7L17.5 17" stroke="white" stroke-width="3" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </x-card>
    </div>

    <div x-data="{ modalAction: null, modalConfirm: null, id: null, dataId: [], option: false, selected: 'Pilih Opsi', valueSelected: null }">
        <x-table.table>
            <x-slot name="tableTitle">Pendaftaran PKL</x-slot>
            <x-slot name="filterActionForm">registration</x-slot>
            {{-- <x-slot name="btnAdd">
                <x-table.add_data></x-table.add_data>
            </x-slot> --}}
            <x-slot name="filter">
                <div class="flex w-full space-x-2">
                    <div class="space-y-1 w-full">
                        <span class="text-xs text-neutral-400 w-32">Search</span>
                        <x-table.search value="{{ $filters['search'] ?? '' }}"></x-table.search>
                    </div>
                </div>
                <div class="flex w-full space-x-2">
                    <div class="space-y-1 w-full">
                        <span class="text-xs text-neutral-400 w-32">Status Registrasi</span>
                        <x-table.select_option_filter optionName="status"
                            defaultSelected="{{ $filters['status'] != '' ? $filters['status'] : 'Semua Status' }}">
                            <x-slot name="option">
                                <li class="option-filter-toolbar-table"
                                    @click="option=false;selected='Belum Dikonfirmasi';valueSelected='unconfirmed'">Belum
                                    Dikonfirmasi</li>
                                <li class="option-filter-toolbar-table"
                                    @click="option=false;selected='Diterima';valueSelected='accepted'">Diterima</li>
                                <li class="option-filter-toolbar-table"
                                    @click="option=false;selected='Ditolak';valueSelected='rejected'">Ditolak</li>
                            </x-slot>
                        </x-table.select_option_filter>
                    </div>
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
                <th>NO</th>
                <th>KELOMPOK</th>
                <th>ANGGOTA</th>
                <th>WAKTU</th>
                <th>LOKASI PKL</th>
                <th>FILE PENGANTAR</th>
                <th>FILE BUKTI DITERIMA</th>
                <th>FILE TERIMA KASIH</th>
                <th>STATUS</th>
                <th>KONFIRMASI</th>
                <th>AKSI</th>
            </x-slot>
            <x-slot name="tBody">
                @foreach ($data as $dt)
                    {{-- {{ dd($dt) }} --}}
                    <tr>
                        {{-- <td>{{ $dt->id }}</td> --}}
                        <td class="text-center">{{ $data->firstItem() + $loop->index }}</td>
                        <td>{{ $dt->group->name }}</td>
                        <td>
                            <ul>
                                @foreach ($dt->group->groupMember as $member)
                                    <li>{{ $loop->iteration }}. {{ $member->student->name ?? '' }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>{{ $dt->start_date }} s/d {{ $dt->end_date }}</td>
                        <td>{{ $dt->industry->name ?? '' }}</td>

                        {{-- surat pengantar --}}
                        @if ($dt->surat_pengantar != null)
                            <x-table.action_btn_table name="Lihat File"
                                href="{{ route('admin.registration.download.file', ['type' => 'suratPengantar', 'filename' => $dt->surat_pengantar]) }}"></x-table.action_btn_table>
                        @else
                            <td class="text-center">
                                <a href="{{ route('admin.registration.generateSuratPengantar', ['registrationId' => $dt->id]) }}"
                                    class="btn btn-xs btn-success-fill min-w-max">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        viewBox="0 0 18 18" fill="none">
                                        <path
                                            d="M7.87464 10.1251L15.7496 2.25013M7.97033 10.3712L9.94141 15.4397C10.1151 15.8862 10.2019 16.1094 10.327 16.1746C10.4354 16.2311 10.5646 16.2312 10.6731 16.1748C10.7983 16.1098 10.8854 15.8866 11.0596 15.4403L16.0023 2.77453C16.1595 2.37164 16.2381 2.1702 16.1951 2.04148C16.1578 1.92969 16.0701 1.84197 15.9583 1.80462C15.8296 1.76162 15.6281 1.84023 15.2252 1.99746L2.55943 6.94021C2.11313 7.11438 1.88997 7.20146 1.82494 7.32664C1.76857 7.43516 1.76864 7.56434 1.82515 7.67279C1.89033 7.7979 2.11358 7.88472 2.56009 8.05836L7.62859 10.0294C7.71923 10.0647 7.76455 10.0823 7.80271 10.1095C7.83653 10.1337 7.86611 10.1632 7.89024 10.1971C7.91746 10.2352 7.93508 10.2805 7.97033 10.3712Z"
                                            stroke="" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <span class="">Kirim File</span>
                                </a>
                            </td>
                        @endif

                        {{-- surat balasan --}}
                        @if ($dt->surat_balasan != null)
                            <x-table.action_btn_table name="Lihat File"
                                href="{{ route('admin.registration.download.file', ['type' => 'suratBalasan', 'filename' => $dt->surat_balasan]) }}"></x-table.action_btn_table>
                        @else
                            <td class="text-center">Belum Tersedia</td>
                        @endif

                        {{-- ucapan terima kasih --}}
                        @if ($dt->ucapan_terima_kasih != null)
                            <x-table.action_btn_table name="Lihat File"
                                href="{{ route('admin.registration.download.file', ['type' => 'ucapanTerimaKasih', 'filename' => $dt->ucapan_terima_kasih]) }}"></x-table.action_btn_table>
                        @else
                            <td class="text-center">Fitur Belum Tersedia</td>
                        @endif

                        {{-- status --}}
                        <x-table.status_table :status="$dt->status"></x-table.status_table>

                        @php
                            $dt->member = $dt->group->groupMember->pluck('student.name')->toArray();
                        @endphp

                        {{-- confirm --}}
                        @if ($dt->status == '0' && $dt->surat_balasan != null)
                            <x-table.action_confirm_table :id="$dt->id"></x-table.action_confirm_table>
                        @elseif($dt->status != '0' && $dt->surat_balasan != null)
                            <td class="text-center">
                                <button
                                    @click="setFormAction('isEditStatus', {{ $dt->id }});modalAction='isEditStatus';dataId={{ $dt->toJson() }}"
                                    class="btn btn-xs btn-warning-fill min-w-max">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        viewBox="0 0 18 18" fill="none">
                                        <path
                                            d="M13.5 7.49998L10.5 4.49998M1.87494 16.125L4.41321 15.843C4.72333 15.8085 4.87839 15.7913 5.02332 15.7443C5.15191 15.7027 5.27427 15.6439 5.3871 15.5695C5.51428 15.4856 5.6246 15.3753 5.84523 15.1547L15.75 5.24998C16.5784 4.42156 16.5784 3.07841 15.75 2.24998C14.9215 1.42156 13.5784 1.42156 12.75 2.24998L2.84524 12.1547C2.6246 12.3753 2.51428 12.4856 2.43042 12.6128C2.35601 12.7256 2.2972 12.848 2.25557 12.9766C2.20866 13.1215 2.19143 13.2766 2.15697 13.5867L1.87494 16.125Z"
                                            stroke="" stroke-width="0.933333" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                    <span class="">Edit Status</span>
                                </button>
                            </td>
                        @else
                            <td class="text-center">Belum Tersedia</td>
                        @endif

                        {{-- action --}}
                        <x-table.action_table edit="hidden" btnInput="hidden" :data="$dt"></x-table.action_table>
                    </tr>
                @endforeach
            </x-slot>
            {{-- pagination --}}
            <x-slot name="pagination">{{ $data->links() }}</x-slot>
        </x-table.table>

        {{-- form --}}
        <div x-show="modalAction!==null" class="form-modal">
            <x-form>
                <x-slot name="formTitle">Data Registrasi</x-slot>
                <x-slot name="formBody">
                    <div class="input-group">
                        <label class="input-label" for="">Nama Kelompok</label>
                        <input name="group" class="input" type="text" disabled
                            :value="modalAction != null ? dataId.group.name : ''">
                    </div>
                    <div class="input-group">
                        <label class="input-label" for="">Anggota Kelompok</label>
                        <input name="member" class="input" type="text" disabled
                            :value="modalAction != null ? dataId.member : ''">
                    </div>
                    <div class="input-group">
                        <label class="input-label" for="">Waktu</label>
                        <input name="time" class="input" type="text" disabled
                            :value="modalAction != null ? dataId.start_date + ' s/d ' + dataId.end_date : ''">
                    </div>
                    <div class="input-group">
                        <label class="input-label" for="">Lokasi PKL</label>
                        <input name="industry" class="input" type="text" disabled
                            :value="modalAction != null ? dataId.industry.name : ''">
                    </div>
                    <div x-show="modalAction=='isEditStatus'" class="input-group">
                        <label class="input-label" for="">Status</label>
                        <input type="hidden" name="status" x-model="valueSelected">
                        <div>
                            <button @click.prevent="option=!option" class="input input-select w-full"
                                :disabled="modalAction == 'isDelete'" required>
                                <span x-text="selected" class="text-neutral-800"></span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 20 20" fill="none">
                                    <path d="M5 7.5L10 12.5L15 7.5" stroke="#667085" stroke-width="0.933333"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        :hidden="modalAction == 'isDelete'" />
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
                </x-slot>
            </x-form>
        </div>

        {{-- modal --}}
        {{-- accept modal confirm --}}
        <div x-show="modalConfirm=='accept'" class="confirm-modal">
            <div @click.away="modalConfirm = null"
                class="bg-neutral-0 border rounded justify-center w-80 border-success-400">
                <h6 class="text-xs-reguler py-5 px-6 text-center max-w-72">Apakah Anda Yakin
                    Ingin <span class="text-xs-medium text-success-800">Menerima Registrasi PKL
                        Ini?</span>
                </h6>
                <div class="flex justify-center py-3 space-x-4 border-t border-success-400">
                    <button @click="modalConfirm=null" class="btn btn-xs btn-success-outline">Tidak</button>
                    <a :href="`{{ route('admin.registration.status.confirm', ['registrationId' => 'id', 'status' => 'accept']) }}`
                    .replace('id', id)"
                        class="btn btn-xs btn-success-fill">Ya</a>
                </div>
            </div>
        </div>

        {{-- reject modal confirm --}}
        <div x-show="modalConfirm=='reject'" class="confirm-modal">
            <div @click.away="modalConfirm = null"
                class="bg-neutral-0 border rounded justify-center w-80 border-error-400">
                <h6 class="text-xs-reguler py-5 px-6 text-center max-w-72">Apakah Anda Yakin
                    Ingin <span class="text-xs-medium text-error-800">Menolak Registrasi PKL
                        Ini?</span>
                </h6>
                <div class="flex justify-center py-3 space-x-4 border-t border-error-400">
                    <button @click="modalConfirm=null" class="btn btn-xs btn-error-outline">Tidak</button>
                    <a :href="`{{ route('admin.registration.status.confirm', ['registrationId' => 'id', 'status' => 'reject']) }}`
                    .replace('id', id)"
                        class="btn btn-xs btn-error-fill">Ya</a>
                </div>
            </div>
        </div>
    </div>

    {{-- empty state --}}
    @if (count($data) == 0)
        <x-not_found_empty_state>
            <x-slot name="desc">Belum ada registrasi PKL yang harus dikonfirmasi</x-slot>
        </x-not_found_empty_state>
    @endif

@endsection

{{-- script Modal Action --}}
<script>
    function setFormAction(modalAction, id = null) {
        const form = document.getElementById('modalForm');
        // const status = document.getElementById('status').value;
        if (modalAction === 'isDelete' && id) {
            form.action = `{{ route('admin.registration.destroy', ':id') }}`.replace(':id', id);
        } else if (modalAction === 'isEditStatus' && id) {
            form.action = `{{ route('admin.registration.update.status', ':id') }}`.replace(':id', id);
        }
        // else if (modalAction === 'isEditStatus' && id) {
        //     form.action = `{{ route('admin.registration.status.confirm', [':id', ':status']) }}`.replace(':id', id).replace(':status', status);
        //     // route('admin.registration.status.confirm', ['registrationId' => 'id', 'status' => 'reject']) 
        // }
        // else if (modalAction === 'isEditStatus' && id) {
        //     let status = document.getElementById("status").value;

        //     // Laravel membuat URL dengan placeholder yang bisa diubah di JavaScript
        //     let url = @json(route('admin.registration.status.confirm', ['registrationId' => '__id__', 'status' => '__status__']));

        //     // Gantikan placeholder dengan nilai sebenarnya
        //     url = url.replace('__id__', id).replace('__status__', status);

        //     // Redirect ke URL yang telah diperbarui
        //     window.location.href = url;
        // }
 
    }
</script>
