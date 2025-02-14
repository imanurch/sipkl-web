@extends('layouts.app')

@section('page-title', 'Logbook')
@section('profil', 'Student')
@section('content')

<x-guide guideTitle="Pengisian Logbook">
    <li>1</li>
    <li>2</li>
</x-guide>

<div class="flex justify-between place-items-center">
    <h6 class="text-sm-semibold">Logbook Peserta PKL</h6>
    <button class="btn btn-xs btn-default-fill">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
              <path d="M12.25 8.75V9.45C12.25 10.4301 12.25 10.9201 12.0593 11.2945C11.8915 11.6238 11.6238 11.8915 11.2945 12.0593C10.9201 12.25 10.4301 12.25 9.45 12.25H4.55C3.56991 12.25 3.07986 12.25 2.70552 12.0593C2.37623 11.8915 2.10852 11.6238 1.94074 11.2945C1.75 10.9201 1.75 10.4301 1.75 9.45V8.75M9.91667 5.83333L7 8.75M7 8.75L4.08333 5.83333M7 8.75V1.75" stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        <span>Unduh Logbook</span>
    </button>
</div>

<div x-data="{ modal:false }">
    <div class="space-y-4">
        <x-content_group_logbook month="1">
            <x-slot name="logbookContent">
                <div class="flex space-x-2">
                    <x-card_logbook status="1" :statusName="$statusName"></x-card_logbook>
                    <x-card_logbook status="1" :statusName="$statusName"></x-card_logbook>
                    <x-card_logbook status="1" :statusName="$statusName"></x-card_logbook>
                    <x-card_logbook status="1" :statusName="$statusName"></x-card_logbook>
                </div>
            </x-slot>
        </x-content_group_logbook>
        <x-content_group_logbook month="2">
            <x-slot name="logbookContent">
                <div class="flex space-x-2">
                    <x-card_logbook status="1" :statusName="$statusName"></x-card_logbook>
                    <x-card_logbook status="1" :statusName="$statusName"></x-card_logbook>
                    <x-card_logbook status="1" :statusName="$statusName"></x-card_logbook>
                    <x-card_logbook status="1" :statusName="$statusName"></x-card_logbook>
                </div>
            </x-slot>
        </x-content_group_logbook>
    </div>
    
    <div x-show="modal" class="w-full" x-data="dataId:{}">
        <x-form>
            <x-slot name="formTitle">Isi Logbook</x-slot>
            <x-slot name="formBody" >
                <div class="input-group">
                    <label class="input-label" for="">Tanggal</label>
                    <input name="name" class="input" type="text" disabled>    
                </div>
                <div class="input-group">
                    <label class="input-label" for="">Kegiatan</label>  
                    <textarea class="input" name="content" id="" rows="4" required></textarea>
                </div>
            </x-slot>
        </x-form>
    </div>
</div>

@endsection