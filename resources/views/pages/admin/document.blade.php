@extends('layouts.app')

@section('page-title', 'Berkas Administrasi')
@section('profil', 'Admin')
@section('content')

    <div class="space-y-6">
        <div x-data="{ batchOption: false, batchSelected: 'Pilih Opsi', batchValueSelected: null }">
            {{-- advisor documents --}}
            <div class="space-y-3">
                <h6 class="text-xs-medium">Cari Berkas Guru Pembimbing</h6>
                <form action="{{ route('admin.document.advisorSearch') }}" method="POST">
                    @csrf
                    <div class="flex space-x-2 place-items-end">
                        <div class="flex space-x-2 w-full max-w-96">
                            <div class="w-full flex space-x-2">
                                <div class="input-group w-full">
                                    <label for="" class="input-label">Batch</label>
                                    <input name="advisor_nip" class="input w-full" type="text" placeholder="NIP">
                                </div>
                            </div>
                            <div class="input-group w-full">
                                <label for="" class="input-label">Batch</label>
                                <input type="text" name="batch" x-model="batchValueSelected" hidden>
                                <div class="relative">
                                    <button @click.prevent="batchOption=!batchOption" class="input input-select w-full">
                                        <span x-text="batchSelected"
                                            :class="batchSelected == 'Pilih Opsi' ? 'text-neutral-300' : 'text-neutral-800'"></span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 20 20" fill="none">
                                            <path d="M5 7.5L10 12.5L15 7.5" stroke="#667085" stroke-width="0.933333"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </button>
                                    <div x-show="batchOption" @click.away="batchOption=false" class="absolute">
                                        <ul class="border border-brand-600 rounded py-2 my-2 max-h-32 overflow-auto">
                                            @foreach ($batchData as $dt)
                                                <li @click.prevent="batchOption=false;batchSelected='{{ $dt->name }} - {{ $dt->year }}';batchValueSelected='{{ $dt->id }}'"
                                                    class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">
                                                    {{ $dt->name }} - {{ $dt->year }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-xs btn-default-fill">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14"
                                fill="none">
                                <path
                                    d="M12.25 12.25L8.75006 8.75M9.91667 5.83333C9.91667 8.0885 8.0885 9.91667 5.83333 9.91667C3.57817 9.91667 1.75 8.0885 1.75 5.83333C1.75 3.57817 3.57817 1.75 5.83333 1.75C8.0885 1.75 9.91667 3.57817 9.91667 5.83333Z"
                                    stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span>Cari</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- <div class="text-xs">
                <p>Nama : {{ $advisorData->name ?? '' }}</p>
                <p>NIM : {{ $advisorData ?? '' }}</p>
                <div>
                    <p>Surat Tugas Pembimbing :</p>
                    @if ($advisorData->advisorDocument != null)
                        <a href="{{ route('advisor.dashboard.downloadSuratTugas', ['filename' => $advisorData->advisorDocument]) }}"
                            class="btn btn-xs btn-default-fill w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14"
                                fill="none">
                                <path
                                    d="M12.25 5.25001L12.25 1.75001M12.25 1.75001H8.74999M12.25 1.75001L7 7M5.83333 1.75H4.55C3.56991 1.75 3.07986 1.75 2.70552 1.94074C2.37623 2.10852 2.10852 2.37623 1.94074 2.70552C1.75 3.07986 1.75 3.56991 1.75 4.55V9.45C1.75 10.4301 1.75 10.9201 1.94074 11.2945C2.10852 11.6238 2.37623 11.8915 2.70552 12.0593C3.07986 12.25 3.56991 12.25 4.55 12.25H9.45C10.4301 12.25 10.9201 12.25 11.2945 12.0593C11.6238 11.8915 11.8915 11.6238 12.0593 11.2945C12.25 10.9201 12.25 10.4301 12.25 9.45V8.16667"
                                    stroke="white" stroke-width="0.93" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span>Lihat</span>
                        </a>
                    @else
                        <span class="text-xs-reguler text-neutral-300 w-full">Belum Tersedia</span>
                    @endif
                </div>
                @if ($advisorData ?? '')
                    @foreach ($advisorData as $dt)
                        <span></span>
                    @endforeach
                @endif
            </div> --}}

        {{-- student documents --}}
        <div class="space-y-3">
            <h6 class="text-xs-medium">Cari Berkas Peserta PKL</h6>
            <form action="">
                <div class="flex space-x-2">
                    <input class="input w-full max-w-96" type="text" placeholder="Nama/NIP">
                    <button class="btn btn-xs btn-default-fill">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14"
                            fill="none">
                            <path
                                d="M12.25 12.25L8.75006 8.75M9.91667 5.83333C9.91667 8.0885 8.0885 9.91667 5.83333 9.91667C3.57817 9.91667 1.75 8.0885 1.75 5.83333C1.75 3.57817 3.57817 1.75 5.83333 1.75C8.0885 1.75 9.91667 3.57817 9.91667 5.83333Z"
                                stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span>Cari</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- export --}}
        <div class="space-y-3" x-data="{ typeOption: false, typeSelected: 'Pilih Opsi' }">
            <h6 class="text-xs-medium" for="">Ekspor Data Pengguna (Admin, Guru, Siswa)</h6>
            <form action="">
                <div class="flex space-x-2">
                    <div class="w-full max-w-96">
                        <button @click.prevent="typeOption=!typeOption" class="input input-select w-full">
                            <span x-text="typeSelected"
                                :class="typeSelected == 'Pilih Opsi' ? 'text-neutral-300' : 'text-neutral-800'"></span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                fill="none">
                                <path d="M5 7.5L10 12.5L15 7.5" stroke="#667085" stroke-width="0.933333"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                        <div x-show="typeOption" @click.away="typeOption=false">
                            <ul class="border border-brand-600 rounded py-2 my-2 max-h-32 overflow-auto">
                                <li @click.prevent="typeOption=false;typeSelected='Semua'"
                                    class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">
                                    Semua</li>
                                <li @click.prevent="typeOption=false;typeSelected='Admin'"
                                    class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">
                                    Admin</li>
                                <li @click.prevent="typeOption=false;typeSelected='Guru Pembimbing'"
                                    class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">
                                    Guru Pembimbing</li>
                                <li @click.prevent="typeOption=false;typeSelected='Peserta Didik'"
                                    class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">
                                    Peserta Didik</li>
                            </ul>
                        </div>
                    </div>
                    <div>
                        <button class="btn btn-xs btn-default-fill">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14"
                                fill="none">
                                <path
                                    d="M12.25 8.75V9.45C12.25 10.4301 12.25 10.9201 12.0593 11.2945C11.8915 11.6238 11.6238 11.8915 11.2945 12.0593C10.9201 12.25 10.4301 12.25 9.45 12.25H4.55C3.56991 12.25 3.07986 12.25 2.70552 12.0593C2.37623 11.8915 2.10852 11.6238 1.94074 11.2945C1.75 10.9201 1.75 10.4301 1.75 9.45V8.75M9.91667 5.83333L7 8.75M7 8.75L4.08333 5.83333M7 8.75V1.75"
                                    stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span>Ekspor</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- export --}}
        <div class="space-y-3" x-data="{ typeOption: false, typeSelected: 'Pilih Opsi', batchOption: false, batchSelected: 'Pilih Opsi' }">
            <h6 class="text-xs-medium" for="">Ekspor Data PKL</h6>
            <form action="">
                <div class="flex space-x-2 place-items-end">
                    <div class="w-full max-w-96 flex space-x-2">
                        <div class="input-group w-full">
                            <label for="" class="input-label">Data</label>
                            <div class="relative">
                                <button @click.prevent="typeOption=!typeOption" class="input input-select w-full">
                                    <span x-text="typeSelected"
                                        :class="typeSelected == 'Pilih Opsi' ? 'text-neutral-300' : 'text-neutral-800'"></span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 20 20" fill="none">
                                        <path d="M5 7.5L10 12.5L15 7.5" stroke="#667085" stroke-width="0.933333"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                                <div x-show="typeOption" @click.away="typeOption=false" class="absolute">
                                    <ul class="border border-brand-600 rounded py-2 my-2 max-h-32 overflow-auto">
                                        <li @click.prevent="typeOption=false;typeSelected='Pendaftaran PKL'"
                                            class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">
                                            Pendaftaran PKL</li>
                                        <li @click.prevent="typeOption=false;typeSelected='Peserta PKL'"
                                            class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">
                                            Peserta PKL</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="input-group w-full">
                            <label for="" class="input-label">Batch</label>
                            <div class="relative">
                                <button @click.prevent="batchOption=!batchOption" class="input input-select w-full">
                                    <span x-text="batchSelected"
                                        :class="batchSelected == 'Pilih Opsi' ? 'text-neutral-300' : 'text-neutral-800'"></span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 20 20" fill="none">
                                        <path d="M5 7.5L10 12.5L15 7.5" stroke="#667085" stroke-width="0.933333"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                                <div x-show="batchOption" @click.away="batchOption=false" class="absolute">
                                    <ul class="border border-brand-600 rounded py-2 my-2 max-h-32 overflow-auto">
                                        <li @click.prevent="batchOption=false;batchSelected='Semua'"
                                            class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">
                                            Semua</li>
                                        @foreach ($batchData as $dt)
                                            <li @click.prevent="batchOption=false;batchSelected='{{ $dt->name }} - {{ $dt->year }}'"
                                                class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">
                                                {{ $dt->name }} - {{ $dt->year }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-xs btn-default-fill h-fit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14"
                            fill="none">
                            <path
                                d="M12.25 8.75V9.45C12.25 10.4301 12.25 10.9201 12.0593 11.2945C11.8915 11.6238 11.6238 11.8915 11.2945 12.0593C10.9201 12.25 10.4301 12.25 9.45 12.25H4.55C3.56991 12.25 3.07986 12.25 2.70552 12.0593C2.37623 11.8915 2.10852 11.6238 1.94074 11.2945C1.75 10.9201 1.75 10.4301 1.75 9.45V8.75M9.91667 5.83333L7 8.75M7 8.75L4.08333 5.83333M7 8.75V1.75"
                                stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span>Ekspor</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
