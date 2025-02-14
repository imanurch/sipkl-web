@extends('layouts.app')

@section('page-title', 'Berkas Administrasi')
@section('profil', 'Admin')
@section('content')

<div class="space-y-3">
    <h6 class="text-xs-medium">Berkas Guru Pembimbing</h6>
    <div class="flex space-x-2">
        <input class="input" type="text" placeholder="Cari Disini ...">
        <button class="btn btn-xs btn-default-fill">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                <path d="M12.25 12.25L8.75006 8.75M9.91667 5.83333C9.91667 8.0885 8.0885 9.91667 5.83333 9.91667C3.57817 9.91667 1.75 8.0885 1.75 5.83333C1.75 3.57817 3.57817 1.75 5.83333 1.75C8.0885 1.75 9.91667 3.57817 9.91667 5.83333Z" stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>Cari</span>
        </button>
    </div>
</div>

<div class="space-y-3" x-data="{ option: false, selected:'Pilih Opsi' }">
    <h6 class="text-xs-medium" for="">Ekspor Data</h6>
    <div class="flex space-x-2">
        <div>
            <button @click="option=!option" class="input input-select">
                <span x-text="selected" :class="selected=='Pilih Opsi' ? 'text-neutral-300' : 'text-neutral-800'"></span>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M5 7.5L10 12.5L15 7.5" stroke="#667085" stroke-width="0.933333" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
            </button>
            <div x-show="option" @click.away="option=false">
                <ul class="border border-brand-600 rounded py-2 my-2 max-h-32 overflow-auto">
                    <li @click="option=false;selected='option1'" class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">Option 1</li>
                    <li @click="option=false;selected='option2'" class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">Option 2</li>
                    <li @click="option=false;selected='option3'" class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">Option 3</li>
                </ul>
            </div>
        </div>
        <button class="btn btn-xs btn-default-fill">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                <path d="M12.25 8.75V9.45C12.25 10.4301 12.25 10.9201 12.0593 11.2945C11.8915 11.6238 11.6238 11.8915 11.2945 12.0593C10.9201 12.25 10.4301 12.25 9.45 12.25H4.55C3.56991 12.25 3.07986 12.25 2.70552 12.0593C2.37623 11.8915 2.10852 11.6238 1.94074 11.2945C1.75 10.9201 1.75 10.4301 1.75 9.45V8.75M9.91667 5.83333L7 8.75M7 8.75L4.08333 5.83333M7 8.75V1.75" stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>Ekspor</span>
        </button>
    </div>
</div>

@endsection