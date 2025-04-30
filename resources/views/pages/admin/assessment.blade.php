@extends('layouts.app')

@section('page-title', 'Penilaian PKL')
@section('profil', 'Admin')
@section('content')

    {{-- card --}}
    <div class="layout-card">
        <x-card title="Belum Dinilai" data="{{ $countNotAssessed ?? '' }}" class="bg-icon-warning">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path
                    d="M10 8.50224C10.1762 8.00136 10.524 7.57901 10.9817 7.30998C11.4395 7.04095 11.9777 6.9426 12.501 7.03237C13.0243 7.12213 13.499 7.39421 13.8409 7.80041C14.1829 8.20661 14.37 8.72072 14.3692 9.25168C14.3692 10.7506 12.1209 11.5 12.1209 11.5M12.1499 14.5H12.1599M9.9 19.2L11.36 21.1467C11.5771 21.4362 11.6857 21.5809 11.8188 21.6327C11.9353 21.678 12.0647 21.678 12.1812 21.6327C12.3143 21.5809 12.4229 21.4362 12.64 21.1467L14.1 19.2C14.3931 18.8091 14.5397 18.6137 14.7185 18.4645C14.9569 18.2656 15.2383 18.1248 15.5405 18.0535C15.7671 18 16.0114 18 16.5 18C17.8978 18 18.5967 18 19.1481 17.7716C19.8831 17.4672 20.4672 16.8831 20.7716 16.1481C21 15.5967 21 14.8978 21 13.5V7.8C21 6.11984 21 5.27976 20.673 4.63803C20.3854 4.07354 19.9265 3.6146 19.362 3.32698C18.7202 3 17.8802 3 16.2 3H7.8C6.11984 3 5.27976 3 4.63803 3.32698C4.07354 3.6146 3.6146 4.07354 3.32698 4.63803C3 5.27976 3 6.11984 3 7.8V13.5C3 14.8978 3 15.5967 3.22836 16.1481C3.53284 16.8831 4.11687 17.4672 4.85195 17.7716C5.40326 18 6.10218 18 7.5 18C7.98858 18 8.23287 18 8.45951 18.0535C8.76169 18.1248 9.04312 18.2656 9.2815 18.4645C9.46028 18.6137 9.60685 18.8091 9.9 19.2Z"
                    stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </x-card>
        <x-card title="Lulus" data="{{ $countPass ?? '' }}" class="bg-icon-success">
            <svg class="" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                fill="none">
                <path d="M16.6667 5L7.50001 14.1667L3.33334 10" stroke="white" stroke-width="3" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </x-card>
        <x-card title="Tidak Lulus" data="{{ $countNotPass ?? '' }}" class="bg-icon-error">
            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                <path d="M17.5 7L7.5 17M7.5 7L17.5 17" stroke="white" stroke-width="3" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </x-card>
    </div>

    <div x-data="{ modalAction: null, option: false, selected: 'Pilih Opsi', valueSelected: '', exportModal: false }">
        <x-table.table>
            <x-slot name="tableTitle">Data Peserta Didik</x-slot>
            <x-slot name="filterActionForm">assessment</x-slot>
            <x-slot name="btnAdd">
                <button @click="exportModal=true" class="btn btn-default-fill btn-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14"
                        fill="none">
                        <path d="M7.00002 2.91675V11.0834M2.91669 7.00008H11.0834" stroke-width="1.6" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    <span class="p-0 m-0">Ekspor</span>
                </button>
                <div x-show="exportModal" class="form-modal">
                    <form class="form" action="{{ route('admin.assessment.export') }}" method="POST"
                        @click.away="generateDocumentModal=false">
                        <div class="form-header">
                            @csrf
                            <h3>Ekspor Penilaian Peserta PKL</h3>
                            <svg @click="exportModal=false,selected='Pilih Opsi'" class="cursor-pointer"
                                xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28"
                                fill="none">
                                <path d="M19.8333 8.16675L8.16663 19.8334M8.16663 8.16675L19.8333 19.8334" stroke="#525A6A"
                                    stroke-width="1.03704" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div class="form-body">
                            <div class="input-group">
                                <label class="input-label" for="">Batch</label>
                                <div>
                                    <button @click.prevent="option=!option" class="input input-select w-full" required>
                                        <span x-text="selected"
                                            :class="selected == 'Pilih Opsi' ? 'text-neutral-300' : 'text-neutral-800'"></span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 20 20" fill="none">
                                            <path d="M5 7.5L10 12.5L15 7.5" stroke="#667085" stroke-width="0.933333"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </button>
                                    <input type="hidden" name="batch_id" x-model="valueSelected">
                                    <div x-show="option" @click.away="option=false" class="bg-neutral-0">
                                        <ul class="border border-brand-600 rounded py-2 mt-2 max-h-32 overflow-auto">
                                            @foreach ($batchData as $dt)
                                                <li class="option-filter-toolbar-table"
                                                    @click="option=false;selected='{{ $dt->name }}';valueSelected='{{ $dt->id }}'">
                                                    {{ $dt->name }}</li>
                                            @endforeach
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
            </x-slot>
            <x-slot name="filter">
                <div class="space-y-1 w-full">
                    <span class="text-xs text-neutral-400 w-32">Search</span>
                    <x-table.search value="{{ $filters['search'] ?? '' }}"></x-table.search>
                </div>
                <div class="space-y-1 w-full">
                    <span class="text-xs text-neutral-400 w-32">Search</span>
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
            </x-slot>
            {{-- table --}}
            <x-slot name="tHeader">
                <th>No</th>
                <th>Nama</th>
                <th>Lokasi PKL</th>
                <th>Luaran</th>
                <th>Nilai Akhir</th>
                <th>Status</th>
                <th>Detail</th>
                <th>Aksi</th>
            </x-slot>
            <x-slot name="tBody">
                @foreach ($data as $dt)
                    <tr>
                        <td class="text-center">{{ $data->firstItem() + $loop->index }}</td>
                        <td>{{ $dt->student->name }}</td>
                        <td>{{ $dt->internship->industry->name }}</td>
                        <x-table.status_table status="{{ $dt->isCompleteOutput }}"></x-table.status_table>
                        <td class="text-center">{{ $dt->internship_score ?? 'Nilai Belum Lengkap' }}</td>
                        @if ($dt->internship_status)
                            <x-table.status_table status="{{ $dt->internship_status }}"></x-table.status_table>
                        @else
                            <td class="text-center">Nilai Belum Lengkap</td>
                        @endif
                        <td>
                            <button
                                @click="modalAction='isView';dataId={{ $dt->toJson() }};technicalAssessment({{ $dt->toJson() }})"
                                class="{{ $detail ?? '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                    viewBox="0 0 18 18" fill="none">
                                    <path
                                        d="M1.81497 9.53488C1.71283 9.37315 1.66176 9.29229 1.63317 9.16756C1.6117 9.07387 1.6117 8.92613 1.63317 8.83244C1.66176 8.70771 1.71283 8.62685 1.81497 8.46512C2.65902 7.12863 5.17143 3.75 9.00018 3.75C12.8289 3.75 15.3413 7.12863 16.1854 8.46512C16.2875 8.62685 16.3386 8.70771 16.3672 8.83244C16.3887 8.92613 16.3887 9.07387 16.3672 9.16756C16.3386 9.29229 16.2875 9.37315 16.1854 9.53488C15.3413 10.8714 12.8289 14.25 9.00018 14.25C5.17143 14.25 2.65903 10.8714 1.81497 9.53488Z"
                                        stroke="#079455" stroke-width="0.933333" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M9.00018 11.25C10.2428 11.25 11.2502 10.2426 11.2502 9C11.2502 7.75736 10.2428 6.75 9.00018 6.75C7.75754 6.75 6.75018 7.75736 6.75018 9C6.75018 10.2426 7.75754 11.25 9.00018 11.25Z"
                                        stroke="#079455" stroke-width="0.933333" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </button>
                        </td>
                        @if ($dt->internship_score)
                            <td>
                                <button
                                    @click="setFormAction('isEdit', {{ $dt->id }});modalAction='isEdit';dataId={{ $dt->toJson() }};technicalAssessmentEdit({{ $dt->toJson() }})"
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
                            @if ($dt->isCompleteOutput == 'Lengkap')
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
                <x-slot name="formTitle">Penilaian</x-slot>
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
                    {{-- technical --}}
                    <div x-show="modalAction=='isEdit'" class="space-y-3">
                        <label class="text-xs" for="">Penilaian Aspek Teknis</label>
                        <div class="space-y-1">
                            <div id="technicalAspectFields" class="space-y-2">
                                {{-- technicalAspectFields --}}
                            </div>
                            <button @click.prevent="addTechnicalAspect()" class="btn btn-xs btn-default-fill h-fit">Tambah
                                Aspek</button>
                        </div>
                    </div>
                    <div x-show="modalAction!='isEdit'" class="space-y-3">
                        <label class="text-xs text-neutral-400" for="">Penilaian Aspek Teknis</label>
                        <div id="technicalViewFields" class="space-y-2">
                            {{-- technicalViewFields --}}
                        </div>
                        <div x-show="modalAction != 'isEdit'" class="sm:flex sm:place-items-center sm:space-x-3">
                            <span class="input-label w-36" for="">Rata-Rata</span>
                            <input name="technical_aspect_average" class="input w-full" type="text"
                                :disabled="modalAction == 'isView'"
                                :value="modalAction != null ? dataId.technical_score_average : ''">
                        </div>
                    </div>
                    {{-- non technical --}}
                    <div class="space-y-3">
                        <label class="text-xs text-neutral-400" for="">Penilaian Aspek Non Teknis</label>
                        <div class="input-group">
                            <div class="sm:flex sm:place-items-center sm:space-x-3">
                                <span class="input-label w-36" for="">Kedisiplinan</span>
                                <input name="dicipline" class="input w-full" type="text"
                                    :disabled="modalAction == 'isView'"
                                    :value="modalAction != null ? dataId.Kedisiplinan : ''">
                            </div>
                            <div class="sm:flex sm:place-items-center sm:space-x-3">
                                <span class="input-label w-36" for="">Kerjasama</span>
                                <input name="teamwork" class="input w-full" type="text"
                                    :disabled="modalAction == 'isView'"
                                    :value="modalAction != null ? dataId.Kerja_Sama : ''">
                            </div>
                            <div class="sm:flex sm:place-items-center sm:space-x-3">
                                <span class="input-label w-36" for="">Inisiatif</span>
                                <input name="initiative" class="input w-full" type="text"
                                    :disabled="modalAction == 'isView'"
                                    :value="modalAction != null ? dataId.Inisiatif : ''">
                            </div>
                            <div class="sm:flex sm:place-items-center sm:space-x-3">
                                <span class="input-label w-36" for="">Tanggung Jawab</span>
                                <input name="responsibility" class="input w-full" type="text"
                                    :disabled="modalAction == 'isView'"
                                    :value="modalAction != null ? dataId.Tanggung_Jawab : ''">
                            </div>
                            <div class="sm:flex sm:place-items-center sm:space-x-3">
                                <span class="input-label w-36" for="">Jujur dan Santun</span>
                                <input name="honest" class="input w-full" type="text"
                                    :disabled="modalAction == 'isView'"
                                    :value="modalAction != null ? dataId.Jujur_dan_Santun : ''">
                            </div>
                            <div x-show="modalAction != 'isEdit'" class="sm:flex sm:place-items-center sm:space-x-3">
                                <span class="input-label w-36" for="">Rata-Rata</span>
                                <input name="non_technical_aspect_average" class="input w-full" type="text"
                                    :disabled="modalAction == 'isView'"
                                    :value="modalAction != null ? dataId.non_technical_score_average : ''">
                            </div>
                        </div>
                    </div>
                    {{-- final report --}}
                    <div class="space-y-3">
                        <label class="text-xs text-neutral-400" for="">Penilaian Aspek Laporan PKL</label>
                        <div class="input-group">
                            <div class="sm:flex sm:place-items-center sm:space-x-3">
                                <span class="input-label w-36" for="">Sikap</span>
                                <input name="attitude" class="input w-full" type="text"
                                    :disabled="modalAction == 'isView'" :value="modalAction != null ? dataId.Sikap : ''">
                            </div>
                            <div class="sm:flex sm:place-items-center sm:space-x-3">
                                <span class="input-label w-36" for="">Tata Tulis Laporan</span>
                                <input name="writing" class="input w-full" type="text"
                                    :disabled="modalAction == 'isView'"
                                    :value="modalAction != null ? dataId.Tata_Tulis : ''">
                            </div>
                            <div class="sm:flex sm:place-items-center sm:space-x-3">
                                <span class="input-label w-36" for="">Ketepatan Waktu</span>
                                <input name="on_time" class="input w-full" type="text"
                                    :disabled="modalAction == 'isView'"
                                    :value="modalAction != null ? dataId.Ketepatan_Waktu : ''">
                            </div>
                            <div class="sm:flex sm:place-items-center sm:space-x-3">
                                <span class="input-label w-36" for="">Ketertiban</span>
                                <input name="orderly" class="input w-full" type="text"
                                    :disabled="modalAction == 'isView'"
                                    :value="modalAction != null ? dataId.Ketertiban : ''">
                            </div>
                            <div class="sm:flex sm:place-items-center sm:space-x-3">
                                <span class="input-label w-36" for="">Laporan PKL Keseluruhan</span>
                                <input name="final_report" class="input w-full" type="text"
                                    :disabled="modalAction == 'isView'"
                                    :value="modalAction != null ? dataId.Keseluruhan_Laporan : ''">
                            </div>
                            <div x-show="modalAction != 'isEdit'" class="sm:flex sm:place-items-center sm:space-x-3">
                                <span class="input-label w-36" for="">Rata-Rata</span>
                                <input name="final_report_aspect_average" class="input w-full" type="text"
                                    :disabled="modalAction == 'isView'"
                                    :value="modalAction != null ? dataId.final_report_score_average : ''">
                            </div>
                        </div>
                    </div>
                    {{-- final test --}}
                    <div class="input-group">
                        <label class="input-label" for="">Penilaian Ujian PKL</label>
                        <input name="final_test" class="input" type="text" :disabled="modalAction == 'isView'"
                            :value="modalAction != null ? dataId.test_assessment.score : ''">
                    </div>
                    <div x-show="modalAction != 'isEdit'" class="input-group">
                        <label class="input-label" for="">Nilai Akhir PKL</label>
                        <input name="internship_score" class="input" type="text" :disabled="modalAction == 'isView'"
                            :value="modalAction != null ? dataId.internship_score : ''">
                    </div>
                </x-slot>
            </x-form>
        </div>
    </div>

    {{-- empty state --}}
    @if (count($data) == 0)
        <x-not_found_empty_state>
            <x-slot name="desc">Belum ada penilaian yang perlu diberikan</x-slot>
        </x-not_found_empty_state>
    @endif

    {{-- script Modal Action --}}
    <script>
        function setFormAction(modalAction, id = null) {
            const form = document.getElementById('modalForm');
            if (modalAction === 'isEdit' && id) {
                form.action = `{{ route('admin.assessment.update', ':id') }}`.replace(':id', id);
            }
        }
    </script>

    <script>
        function technicalAssessmentEdit(data) {
            const container = document.getElementById('technicalAspectFields');
            container.innerHTML = '';

            data.technical_aspect.forEach(function(tech, index) {
                const div = document.createElement('div');
                div.classList.add('sm:flex', 'sm:place-items-center', 'sm:space-x-3');

                // Buat input field
                const btnDelete = document.createElement('button');
                btnDelete.innerText = 'Hapus';
                btnDelete.setAttribute('onclick', "removeField(this)");
                btnDelete.classList.add('btn', 'btn-xs', 'btn-error-fill', 'h-fit');

                // Buat input field
                const inputAspect = document.createElement('input');
                inputAspect.type = 'text';
                inputAspect.id = 'tech-' + index;
                inputAspect.name = 'technical_aspect[]';
                inputAspect.value = tech;
                inputAspect.classList.add('input', 'w-2/3');

                // Buat input field
                const inputScore = document.createElement('input');
                inputScore.type = 'text';
                inputScore.id = 'tech-' + index;
                inputScore.name = 'technical_score[]';
                inputScore.value = data.technical_aspect_score[index];
                inputScore.classList.add('input', 'w-1/3');

                div.appendChild(inputAspect);
                div.appendChild(inputScore);
                div.appendChild(btnDelete);

                container.appendChild(div);
            });
        }
    </script>

    <script>
        function addTechnicalAspect() {
            const technicalAspectFields = document.getElementById('technicalAspectFields');
            const newField = document.createElement('div');
            newField.classList.add('flex', 'space-x-2');

            newField.innerHTML = `
                <input name="technical_aspect[]" class="input w-2/3" placeholder="Aspek Teknis" required>
                <input name="technical_score[]" class="input w-1/3" placeholder="Nilai" required>
                <button onclick="removeField(this)" class="btn btn-xs btn-error-fill h-fit">Hapus</button>
            `;

            technicalAspectFields.appendChild(newField);
        }

        function removeField(button) {
            button.parentElement.remove();
        }
    </script>

    <script>
        function technicalAssessment(data) {
            const container = document.getElementById('technicalViewFields');
            container.innerHTML = '';

            data.technical_aspect.forEach(function(tech, index) {
                const div = document.createElement('div');
                div.classList.add('sm:flex', 'sm:place-items-center', 'sm:space-x-3');

                // Buat label
                const label = document.createElement('label');
                label.setAttribute('for', 'tech-' + index);
                label.innerText = tech;
                label.classList.add('input-label', 'w-36');

                // Buat input field
                const input = document.createElement('input');
                input.type = 'text';
                input.id = 'tech-' + index;
                input.name = 'technical_aspect[' + index + ']';
                input.value = data.technical_aspect_score[index];
                input.classList.add('input', 'w-full');
                input.setAttribute('disabled', '');

                div.appendChild(label);
                div.appendChild(input);

                container.appendChild(div);
            });
        }
    </script>

@endsection
