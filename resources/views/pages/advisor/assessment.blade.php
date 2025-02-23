@extends('layouts.app')

@section('page-title', 'Penilaian PKL')
@section('profil', 'Advisor')
@section('content')

    <div class="layout-card">
        <x-card title="Sudah Dinilai" data="{{ $countAssessed ?? '' }}" class="bg-icon-success">
            <svg class="" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                fill="none">
                <path d="M16.6667 5L7.50001 14.1667L3.33334 10" stroke="white" stroke-width="3" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </x-card>
        <x-card title="Belum Dinilai" data="{{ $countNotAssessed ?? '' }}" class="bg-icon-error">
            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                <path d="M17.5 7L7.5 17M7.5 7L17.5 17" stroke="white" stroke-width="3" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </x-card>
    </div>

    <x-guide guideTitle="Penilaian">
        <li>Penilaian hanya dapat dilakukan jika
            <ul class="list-decimal ps-6">
                <li class="text-xs-semibold">Logbook mingguan telah disetujui guru</li>
                <li class="text-xs-semibold">Siswa telah mengunggah laporan akhir</li>
            </ul>
        </li>
        <li>Penilaian didasarkan pada performa kinerja siswa selama PKL dan kualitas laporan akhir</li>
    </x-guide>

    <div x-data="{ modalAction: null }">
        <x-table.table>
            <x-slot name="tableTitle">Penilaian PKL</x-slot>
            <x-slot name="filterActionForm">assessment</x-slot>
            {{-- toolbar --}}
            <x-slot name="filter">
                <div class="flex w-full space-x-2">
                    <div class="space-y-1 w-full">
                        <span class="text-xs text-neutral-400 w-32">Search</span>
                        <x-table.search value="{{ $filters['search'] ?? '' }}"></x-table.search>
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
                <th>NAMA</th>
                <th>INDUSTRI</th>
                <th>LOGBOOK</th>
                <th>LAPORAN AKHIR</th>
                <th>NILAI</th>
                <th>AKSI</th>
            </x-slot>
            <x-slot name="tBody">
                {{-- {{ dd($data) }} --}}
                @foreach ($data as $dt)
                    <tr>
                        <td class="text-center">{{ $data->firstItem() + $loop->index }}</td>
                        <td>{{ $dt->student->name }}</td>
                        <td>{{ $dt->internship->industry->name }}</td>
                        <x-table.status_table status="{{ $dt->isCompleteLogbook }}"></x-table.status_table>
                        @if ($dt->final_report != null)
                            <x-table.action_btn_table
                                href="{{ route('advisor.assessment.download.finalReport', ['filename' => $dt->final_report]) }}"
                                name="Lihat"></x-table.action_btn_table>
                        @else
                            <td>Belum Tersedia</td>
                        @endif
                        <td class="text-center">{{ $dt->advisor_score ?? 'Belum Dinilai' }}</td>
                        @if ($dt->advisor_score)
                            <td>
                                <button
                                    @click="setFormAction('isEdit', {{ $dt->id }});modalAction='isEdit';dataId={{ $dt->toJson() }}"
                                    class="btn btn-xs btn-warning-fill">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        viewBox="0 0 18 18" fill="none">
                                        <path
                                            d="M13.5 7.49998L10.5 4.49998M1.87494 16.125L4.41321 15.843C4.72333 15.8085 4.87839 15.7913 5.02332 15.7443C5.15191 15.7027 5.27427 15.6439 5.3871 15.5695C5.51428 15.4856 5.6246 15.3753 5.84523 15.1547L15.75 5.24998C16.5784 4.42156 16.5784 3.07841 15.75 2.24998C14.9215 1.42156 13.5784 1.42156 12.75 2.24998L2.84524 12.1547C2.6246 12.3753 2.51428 12.4856 2.43042 12.6128C2.35601 12.7256 2.2972 12.848 2.25557 12.9766C2.20866 13.1215 2.19143 13.2766 2.15697 13.5867L1.87494 16.125Z"
                                            stroke="" stroke-width="0.933333" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                    <span class="min-w-max">Edit Nilai</span>
                                </button>
                            </td>
                        @else
                            @if ($dt->isCompleteLogbook == 'Lengkap' && $dt->isCompleteFinalReport == 'Lengkap')
                                <td>
                                    <button
                                        @click="setFormAction('isEdit', {{ $dt->id }});modalAction='isEdit';dataId={{ $dt->toJson() }}"
                                        class="btn btn-xs btn-success-fill">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="14"
                                            viewBox="0 0 15 14" fill="none">
                                            <path d="M7.49984 2.91669V11.0834M3.4165 7.00002H11.5832" stroke="white"
                                                stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <span class="min-w-max">Input Nilai</span>
                                    </button>
                                </td>
                            @else
                                <td class="text-center">Luaran Belum Lengkap</td>
                            @endif
                        @endif

                    </tr>
                @endforeach
            </x-slot>
            {{-- pagination --}}
            <x-slot name="pagination">{{ $data->links() }}</x-slot>
        </x-table.table>
        <div x-show="modalAction != null" class="form-modal">
            <x-form>
                <x-slot name="formTitle">Form Penilaian</x-slot>
                <x-slot name="formBody">
                    <div class="input-group">
                        <label class="input-label" for="">Nama</label>
                        <input name="name" class="input" type="text" disabled
                            :value="modalAction != null ? dataId.student.name : ''">
                    </div>
                    <div class="input-group">
                        <label class="input-label" for="">Jurusan</label>
                        <input name="department" class="input" type="text" disabled
                            :value="modalAction != null ? dataId.student.department.name : ''">
                    </div>
                    <div class="input-group">
                        <label class="input-label" for="">Lokasi PKL</label>
                        <input name="industry" class="input" type="text" disabled
                            :value="modalAction != null ? dataId.internship.industry.name : ''">
                    </div>
                    <div class="input-group">
                        <label class="input-label" for="">Nilai</label>
                        <input name="advisor_score" class="input" type="text" :disabled="isShow"
                            :value="modalAction != null ? dataId.advisor_score : ''" required>
                    </div>
                </x-slot>
            </x-form>
        </div>
    </div>

    {{-- empty state --}}
    @if (count($data) == 0)
        <x-not_found_empty_state>
            <x-slot name="desc">Belum ada penilaian yang harus diberikan</x-slot>
        </x-not_found_empty_state>
    @endif

    {{-- script Modal Action --}}
    <script>
        function setFormAction(modalAction, id = null) {
            const form = document.getElementById('modalForm');
            if (modalAction === 'isEdit' && id) {
                form.action = `{{ route('advisor.assessment.update', ':id') }}`.replace(':id', id);
            }
        }
    </script>
@endsection
