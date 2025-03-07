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
        <h6 class="text-md-semibold">Alur Pendaftaran PKL</h6>
        <div class="space-y-4">
            {{-- card --}}
            {{-- <div class="flex space-x-2 border-b border-neutral-200 pb-4">
                <div class="flex space-x-2 place-items-end p-6 rounded w-fit border border-brand-600 text-brand-800">
                    <span class="display-lg-semibold">1</span>
                    <h6 class="text-xs-reguler w-16">Pilih Lokasi PKL</h6>
                </div>
                <div
                    class="flex space-x-2 place-items-end p-6 rounded w-fit {{ !Route::is('student.registration') ? 'border border-brand-600 text-brand-800' : 'bg-neutral-50 text-neutral-0' }}">
                    <span class="display-lg-semibold">2</span>
                    <h6 class="text-xs-reguler w-16">Isi Data Anggota Kelompok</h6>
                </div>
                <div
                    class="flex space-x-2 place-items-end p-6 rounded w-fit {{ !Route::is('student.registration') && !Route::is('student.registration.step2') ? 'border border-brand-600 text-brand-800' : 'bg-neutral-50 text-neutral-0' }}">
                    <span class="display-lg-semibold">3</span>
                    <h6 class="text-xs-reguler w-16">Isi Data Pendaftaran PKL</h6>
                </div>
                <div
                    class="flex space-x-2 place-items-end p-6 rounded w-fit {{ Route::is('student.registration.step4') || Route::is('student.registration.step5') ? 'border border-brand-600 text-brand-800' : 'bg-neutral-50 text-neutral-0' }}">
                    <span class="display-lg-semibold">4</span>
                    <h6 class="text-xs-reguler w-16">Lengkapi Berkas Pendaftaran</h6>
                </div>
                <div
                    class="flex space-x-2 place-items-end p-6 rounded w-fit {{ Route::is('student.registration.step5') ? 'border border-brand-600 text-brand-800' : 'bg-neutral-50 text-neutral-0' }}">
                    <span class="display-lg-semibold">5</span>
                    <h6 class="text-xs-reguler w-16">Hasil Pendaftaran</h6>
                </div>
            </div> --}}

            {{-- <div class="border-b border-neutral-50">
                <div class="relative my-12 mx-24">
                    <div class="h-[1px] bg-neutral-50">
                        <div class=""></div>
                    </div>
                    <button
                        class="bg-brand-800 text-neutral-0 py-2 px-4 rounded-full shadow-md absolute translate-y-[-50%] start-[0%]">1</button>
                    <button
                        class="bg-neutral-200 text-neutral-0 py-2 px-4 rounded-full shadow-md absolute translate-y-[-50%] start-[25%]">2</button>
                    <button
                        class="bg-neutral-200 text-neutral-0 py-2 px-4 rounded-full shadow-md absolute translate-y-[-50%] start-[50%]">3</button>
                    <button
                        class="bg-neutral-200 text-neutral-0 py-2 px-4 rounded-full shadow-md absolute translate-y-[-50%] start-[75%]">4</button>
                    <button
                        class="bg-neutral-200 text-neutral-0 py-2 px-4 rounded-full shadow-md absolute translate-y-[-50%] start-[100%]">5</button>
                </div>
            </div> --}}

            <div class="border-b border-neutral-50">
                <div class="relative my-16 mx-12">
                    <div class="h-[1px] bg-neutral-50">
                        <div
                            class="h-[1px] bg-brand-800 {{ Route::is('student.registration.step2') ? 'w-[25%]' : (Route::is('student.registration.step3') ? 'w-[50%]' : (Route::is('student.registration.step4') ? 'w-[80%]' : (Route::is('student.registration.step5') ? 'w-[100%]' : 'w-0'))) }}">
                        </div>
                    </div>
                    <div class="absolute translate-y-[-30%] top-0 start-[-5%] text-center space-y-3">
                        <button
                            class="text-neutral-0 py-2 px-4 rounded-full shadow-md {{ $activeBatch != null ? 'bg-brand-800' : 'bg-neutral-200' }}">1</button>
                        <h6 class="text-xs {{ $activeBatch != null ? 'text-brand-600' : '' }}">Pilih Lokasi PKL</h6>
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
                        <h6 class="text-xs min-w-max {{ Route::is('student.registration.step5') ? 'text-brand-600' : '' }}">
                            Hasil Pendaftaran</h6>
                    </div>
                </div>
            </div>



            {{-- form --}}
            @if ($activeBatch != null)
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
                    <x-slot name="desc">Saat ini bukan periode pendaftaran PKL. Hubungi admin PKL untuk info lebih lanjut
                        ya!</x-slot>
                </x-not_found_empty_state>
            @endif
        </div>
    </div>


    {{-- <div class="space-y-4">
    <h6 class="text-md-semibold">Alur Pendaftaran PKL</h6>
    <div x-data="{ step1:true, newIndustry:false, step2:false, step3:false, step4:false, step5:false }" class="space-y-4">
        <div class="flex space-x-2 border-b border-neutral-200 pb-4">
            <div class="flex space-x-2 place-items-end p-6 rounded w-fit" :class="step1 || newIndustry ? 'border border-brand-600 text-brand-800' : 'bg-neutral-50 text-neutral-0'">
                <span class="display-lg-semibold">1</span>
                <h6 class="text-xs-reguler w-16">Pilih Lokasi PKL</h6>
            </div>
            <div class="flex space-x-2 place-items-end p-6 rounded w-fit" :class="step2 ? 'border border-brand-600 text-brand-800' : 'bg-neutral-50 text-neutral-0'">
                <span class="display-lg-semibold">2</span>
                <h6 class="text-xs-reguler w-16">Isi Data Anggota Kelompok</h6>
            </div>
            <div class="flex space-x-2 place-items-end p-6 rounded w-fit" :class="step3 ? 'border border-brand-600 text-brand-800' : 'bg-neutral-50 text-neutral-0'">
                <span class="display-lg-semibold">3</span>
                <h6 class="text-xs-reguler w-16">Isi Data Pendaftaran PKL</h6>
            </div>
            <div class="flex space-x-2 place-items-end p-6 rounded w-fit" :class="step4 ? 'border border-brand-600 text-brand-800' : 'bg-neutral-50 text-neutral-0'">
                <span class="display-lg-semibold">4</span>
                <h6 class="text-xs-reguler w-16">Lengkapi Berkas Pendaftaran</h6>
            </div>
            <div class="flex space-x-2 place-items-end p-6 rounded w-fit" :class="step5 ? 'border border-brand-600 text-brand-800' : 'bg-neutral-50 text-neutral-0'">
                <span class="display-lg-semibold">5</span>
                <h6 class="text-xs-reguler w-16">Hasil Pendaftaran</h6>
            </div>
        </div>
        <div x-data="{currentStep:1}" class="space-y-3">
            <h6 class="text-xs-medium" x-text="currentStep===1 ? 'Pilih Lokasi PKL' : (currentStep===2 ? 'Isi Data Anggota Kelompok' : (currentStep===3 ? 'Isi Data Pengajuan PKL' : (currentStep===4 ? 'Lengkapi Berkas Administrasi' : 'Hasil Pendaftaran')))"></h6>
               
            <div x-show="currentStep===1">
                <x-registration_step.step1></x-registration_step.step1>
            </div>          
            
            
            <div x-show="currentStep==='newIndustry'">
                <x-registration_step.newIndustry></x-registration_step.newIndustry>
            </div>   

            
            <div x-show="currentStep===2">
                <x-registration_step.step2></x-registration_step.step2>
            </div> 
            
            
            <div x-show="currentStep===3">
                <x-registration_step.step3></x-registration_step.step3>
            </div> 
            
            
            <div x-show="currentStep===4">
                <x-registration_step.step4></x-registration_step.step4>
            </div> 
            
        </div>
    </div>
</div> --}}

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection


{{-- card --}}
{{-- <div class="flex space-x-2 border-b border-neutral-200 pb-4">
    <div class="flex space-x-2 place-items-end p-6 rounded w-fit" :class="step1 || newIndustry ? 'border border-brand-600 text-brand-800' : 'bg-neutral-50 text-neutral-0'">
        <span class="display-lg-semibold">1</span>
        <h6 class="text-xs-reguler w-16">Pilih Lokasi PKL</h6>
    </div>
    <div class="flex space-x-2 place-items-end p-6 rounded w-fit" :class="step2 ? 'border border-brand-600 text-brand-800' : 'bg-neutral-50 text-neutral-0'">
        <span class="display-lg-semibold">2</span>
        <h6 class="text-xs-reguler w-16">Isi Data Anggota Kelompok</h6>
    </div>
    <div class="flex space-x-2 place-items-end p-6 rounded w-fit" :class="step3 ? 'border border-brand-600 text-brand-800' : 'bg-neutral-50 text-neutral-0'">
        <span class="display-lg-semibold">3</span>
        <h6 class="text-xs-reguler w-16">Isi Data Pendaftaran PKL</h6>
    </div>
    <div class="flex space-x-2 place-items-end p-6 rounded w-fit" :class="step4 ? 'border border-brand-600 text-brand-800' : 'bg-neutral-50 text-neutral-0'">
        <span class="display-lg-semibold">4</span>
        <h6 class="text-xs-reguler w-16">Lengkapi Berkas Pendaftaran</h6>
    </div>
    <div class="flex space-x-2 place-items-end p-6 rounded w-fit" :class="step5 ? 'border border-brand-600 text-brand-800' : 'bg-neutral-50 text-neutral-0'">
        <span class="display-lg-semibold">5</span>
        <h6 class="text-xs-reguler w-16">Hasil Pendaftaran</h6>
    </div>
</div> --}}
