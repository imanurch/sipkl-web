@extends('layouts.app')

@section('page-title', 'Pendaftaran PKL')
@section('profil', 'Student')
@section('content')

    <x-guide guideTitle="Pendaftaran">
        <li>Ikuti langkah pendaftaran sesuai petunjuk yang tertera.</li>
        <li>Hanya <span class="text-xs-semibold">satu perwakilan kelompok yang perlu mendaftar</span> dan mengisi data
            anggota kelompok.</li>
        <li>Jika lokasi PKL belum tersedia, ajukan lokasi baru dengan klik "<span class="text-xs-semibold">Pengajuan Lokasi
                Baru</span>".</li>
    </x-guide>

    <div class="space-y-4">
        {{-- tabs --}}
        <div class="flex w-full justify-between border-b border-neutral-100">
            <div>
                <h6 class="py-3 text-sm-medium">Pendaftaran PKL</h6>
            </div>
            <div class="flex">
                <a href="{{ route('student.registration') }}"
                    class="text-sm-medium text-neutral-400 py-3 px-6 hover:text-neutral-700 {{ !Route::is('student.registration.history')
                        ? 'text-brand-500 border-b border-brand-500 hover:text-brand-500'
                        : '' }}">Form
                    Registrasi</a>
                <a href="{{ route('student.registration.history') }}"
                    class="text-sm-medium text-neutral-400 py-3 px-6 hover:text-neutral-700 {{ Route::is('student.registration.history')
                        ? 'text-brand-500 border-b border-brand-500 hover:text-brand-500'
                        : '' }}">Riwayat</a>
            </div>
        </div>

        {{-- registration --}}
        <div class="space-y-4 {{ Route::is('student.registration.history') ? 'hidden' : '' }}">
            {{-- <h6 class="text-md-semibold">Alur Pendaftaran PKL</h6> --}}
            <div class="space-y-4">
                <div class="border-b border-neutral-50">
                    <div class="md:relative space-y-2 md:space-y-0 my-6 md:my-16 md:mx-12">
                        <div class="h-[1px] md:bg-neutral-50">
                            <div
                                class="h-[1px] md:bg-brand-800 {{ Route::is('student.registration.step2') ? 'w-[25%]' : (Route::is('student.registration.step3') ? 'w-[50%]' : (Route::is('student.registration.step4') ? 'w-[80%]' : (Route::is('student.registration.step5') ? 'w-[100%]' : 'w-0'))) }}">
                            </div>
                        </div>
                        <div
                            class="flex space-x-2 md:space-x-0 md:inline md:absolute translate-y-[-30%] top-0 start-[-5%] text-center space-y-3">
                            <button
                                class="text-neutral-0 py-2 px-4 rounded-full shadow-md {{ session('batch_id') != null || !Route::is('student.registration') ? 'bg-brand-800' : 'bg-neutral-200' }}">1</button>
                            <h6
                                class="text-xs {{ session('batch_id') != null || !Route::is('student.registration') ? 'text-brand-600' : '' }}">
                                Pilih Lokasi PKL</h6>
                        </div>
                        <div
                            class="flex space-x-2 md:space-x-0 md:inline md:absolute translate-y-[-30%] top-0 start-[20%] text-center space-y-3">
                            <button
                                class="text-neutral-0 py-2 px-4 rounded-full shadow-md {{ !Route::is('student.registration') ? 'bg-brand-800' : 'bg-neutral-200' }}">2</button>
                            <h6
                                class="text-xs {{ !Route::is('student.registration') && !Route::is('student.registration.step2') ? 'text-brand-600' : '' }}">
                                Isi Data Anggota</h6>
                        </div>
                        <div
                            class="flex space-x-2 md:space-x-0 md:inline md:absolute translate-y-[-30%] top-0 start-[45%] text-center space-y-3">
                            <button
                                class="text-neutral-0 py-2 px-4 rounded-full shadow-md {{ !Route::is('student.registration') && !Route::is('student.registration.step2') ? 'bg-brand-800' : 'bg-neutral-200' }}">3</button>
                            <h6
                                class="text-xs {{ !Route::is('student.registration') && !Route::is('student.registration.step2') ? 'text-brand-600' : '' }}">
                                Isi Data Pendaftaran</h6>
                        </div>
                        <div
                            class="flex space-x-2 md:space-x-0 md:inline md:absolute translate-y-[-30%] top-0 start-[70%] text-center space-y-3">
                            <button
                                class="text-neutral-0 py-2 px-4 rounded-full shadow-md {{ Route::is('student.registration.step4') || Route::is('student.registration.step5') ? 'bg-brand-800' : 'bg-neutral-200' }}">4</button>
                            <h6
                                class="text-xs {{ Route::is('student.registration.step4') || Route::is('student.registration.step5') ? 'text-brand-600' : '' }}">
                                Lengkapi Berkas Pendaftaran</h6>
                        </div>
                        <div
                            class="flex space-x-2 md:space-x-0 md:inline md:absolute translate-y-[-30%] top-0 start-[95%] text-center space-y-3">
                            <button
                                class="text-neutral-0 py-2 px-4 rounded-full shadow-md {{ Route::is('student.registration.step5') ? 'bg-brand-800' : 'bg-neutral-200' }}">5</button>
                            <h6
                                class="text-xs min-w-max {{ Route::is('student.registration.step5') ? 'text-brand-600' : '' }}">
                                Hasil Pendaftaran</h6>
                        </div>
                    </div>
                </div>

                {{-- <div class="border-b border-neutral-50">
                    <div class="relative my-16 mx-12">
                        <div class="h-[1px] bg-neutral-50">
                            <div
                                class="h-[1px] bg-brand-800 {{ Route::is('student.registration.step2') ? 'w-[25%]' : (Route::is('student.registration.step3') ? 'w-[50%]' : (Route::is('student.registration.step4') ? 'w-[80%]' : (Route::is('student.registration.step5') ? 'w-[100%]' : 'w-0'))) }}">
                            </div>
                        </div>
                        <div class="absolute translate-y-[-30%] top-0 start-[-5%] text-center space-y-3">
                            <button
                                class="text-neutral-0 py-2 px-4 rounded-full shadow-md {{ session('user_bio') != null || !Route::is('student.registration') ? 'bg-brand-800' : 'bg-neutral-200' }}">1</button>
                            <h6
                                class="text-xs {{ session('batch_id') != null || !Route::is('student.registration') ? 'text-brand-600' : '' }}">
                                Pilih Lokasi PKL</h6>
                        </div>
                        <div class="absolute translate-y-[-30%] top-0 start-[20%] text-center space-y-3">
                            <button
                                class="text-neutral-0 py-2 px-4 rounded-full shadow-md {{ !Route::is('student.registration') ? 'bg-brand-800' : 'bg-neutral-200' }}">2</button>
                            <h6
                                class="text-xs {{ !Route::is('student.registration') && !Route::is('student.registration.step2') ? 'text-brand-600' : '' }}">
                                Isi Data Anggota</h6>
                        </div>
                        <div class="absolute translate-y-[-30%] top-0 start-[45%] text-center space-y-3">
                            <button
                                class="text-neutral-0 py-2 px-4 rounded-full shadow-md {{ !Route::is('student.registration') && !Route::is('student.registration.step2') ? 'bg-brand-800' : 'bg-neutral-200' }}">3</button>
                            <h6
                                class="text-xs {{ !Route::is('student.registration') && !Route::is('student.registration.step2') ? 'text-brand-600' : '' }}">
                                Isi Data Pendaftaran</h6>
                        </div>
                        <div class="absolute translate-y-[-30%] top-0 start-[70%] text-center space-y-3">
                            <button
                                class="text-neutral-0 py-2 px-4 rounded-full shadow-md {{ Route::is('student.registration.step4') || Route::is('student.registration.step5') ? 'bg-brand-800' : 'bg-neutral-200' }}">4</button>
                            <h6
                                class="text-xs {{ Route::is('student.registration.step4') || Route::is('student.registration.step5') ? 'text-brand-600' : '' }}">
                                Lengkapi Berkas Pendaftaran</h6>
                        </div>
                        <div class="absolute translate-y-[-30%] top-0 start-[95%] text-center space-y-3">
                            <button
                                class="text-neutral-0 py-2 px-4 rounded-full shadow-md {{ Route::is('student.registration.step5') ? 'bg-brand-800' : 'bg-neutral-200' }}">5</button>
                            <h6
                                class="text-xs min-w-max {{ Route::is('student.registration.step5') ? 'text-brand-600' : '' }}">
                                Hasil Pendaftaran</h6>
                        </div>
                    </div>
                </div> --}}

                {{-- form --}}
                @if (session('batch_id') != null)
                    <div x-data="{ currentStep: 1 }">
                        {{-- step 1 --}}
                        @if (Route::is('student.registration'))
                            <div x-show="currentStep===1">
                                <x-registration_step.step1 :industryData="$industryData" :industryRequestData="$industryRequestData"></x-registration_step.step1>
                            </div>
                        @endif

                        {{-- step pengajuan industry --}}
                        <div x-show="currentStep==='newIndustry'">
                            <x-registration_step.newIndustry></x-registration_step.newIndustry>
                        </div>

                        {{-- step 2 --}}
                        @if (Route::is('student.registration.step2'))
                            <div>
                                <x-registration_step.step2 :studentListData="$studentListData"></x-registration_step.step2>
                            </div>
                        @endif

                        {{-- step 3 --}}
                        @if (Route::is('student.registration.step3'))
                            <div>
                                <x-registration_step.step3 :locationInternship="$locationInternship" :teamMember="$teamMember"></x-registration_step.step3>
                            </div>
                        @endif

                        {{-- step 4 --}}
                        @if (Route::is('student.registration.step4'))
                            <div>
                                <x-registration_step.step4 :registrationData="$registrationData"></x-registration_step.step4>
                            </div>
                        @endif

                        {{-- step 5 --}}
                        @if (Route::is('student.registration.step5'))
                            <div>
                                <x-registration_step.step5 :registrationData="$registrationData"></x-registration_step.step5>
                            </div>
                        @endif

                    </div>
                @else
                    <x-not_found_empty_state>
                        <x-slot name="title">Pendaftaran tidak tersedia</x-slot>
                        <x-slot name="desc">Saat ini bukan periode pendaftaran PKL. Hubungi admin PKL untuk info lebih
                            lanjut
                            ya!</x-slot>
                    </x-not_found_empty_state>
                @endif
            </div>
        </div>

        {{-- history --}}
        <div class="{{ !Route::is('student.registration.history') ? 'hidden' : '' }}">
            @if ($historyData ?? '')
                @if (count($historyData) > 0)
                    <div class="border border-neutral-100">
                        <div>
                            <div class="border-b border-neutral-100 py-4 px-4">
                                <h6 class="text-xs-medium">Riwayat Registrasi PKL</h6>
                            </div>

                            <div class="px-4 py-4 space-y-3">
                                <div class="border border-neutral-50 rounded overflow-x-auto overflow-hidden">
                                    <table class="table">
                                        <thead>
                                            <th>NO</th>
                                            <th>KELOMPOK</th>
                                            <th>NAMA</th>
                                            <th>WAKTU</th>
                                            <th>GURU PEMBIMBING</th>
                                            <th>LOKASI PKL</th>
                                            <th>STATUS</th>
                                        </thead>
                                        <tbody>
                                            {{-- @if ($historyData ?? '') --}}
                                            @foreach ($historyData as $dt)
                                                <tr>
                                                    <td class="text-center">{{ $historyData->firstItem() + $loop->index }}
                                                    </td>
                                                    <td>{{ $dt->group->name }}</td>
                                                    <td>
                                                        <ul>
                                                            @foreach ($dt->group->groupMember as $member)
                                                                <li>{{ $loop->iteration }}.
                                                                    {{ $member->student->name ?? '' }}
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </td>
                                                    <td>{{ $dt->start_date ?? '' }} s/d {{ $dt->end_date ?? '' }}</td>
                                                    @if ($dt->advisor)
                                                        <td>{{ $dt->advisor->name }}</td>
                                                    @else
                                                        <td class="text-neutral-50">Belum Tersedia</td>
                                                    @endif
                                                    <td>{{ $dt->industry->name ?? '' }}</td>
                                                    <x-table.status_table :status="$dt->status"></x-table.status_table>
                                                </tr>
                                            @endforeach
                                            {{-- @endif --}}
                                        </tbody>
                                        {{-- pagination --}}
                                        {{-- <x-slot name="pagination">{{ $industryRequestData->links() }}</x-slot> --}}
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <x-not_found_empty_state>
                        <x-slot name="title">Riwayat Pendaftaran tidak ditemukan</x-slot>
                        <x-slot name="desc">Kamu dapat melihat riwayat pendaftaran yang kamu lakukan pada halaman
                            ini.</x-slot>
                    </x-not_found_empty_state>
                @endif
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection
