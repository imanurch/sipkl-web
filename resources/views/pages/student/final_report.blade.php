@extends('layouts.app')

@section('page-title', 'Laporan Akhir')
@section('profil', 'Student')
@section('content')

<x-guide guideTitle="Unggah Laporan Akhir">
    <li>1</li>
    <li>2</li>
</x-guide>

<div class="space-y-4">
    <div>
        <button class="btn btn-xs btn-default-outline">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                <path d="M1.41174 7.41586C1.3323 7.29007 1.29257 7.22717 1.27034 7.13016C1.25364 7.0573 1.25364 6.94238 1.27034 6.86951C1.29257 6.7725 1.3323 6.70961 1.41174 6.58382C2.06823 5.54433 4.02232 2.9165 7.00024 2.9165C9.97815 2.9165 11.9322 5.54433 12.5887 6.58382C12.6682 6.70961 12.7079 6.7725 12.7301 6.86951C12.7468 6.94238 12.7468 7.0573 12.7301 7.13016C12.7079 7.22717 12.6682 7.29007 12.5887 7.41586C11.9322 8.45535 9.97815 11.0832 7.00024 11.0832C4.02232 11.0832 2.06823 8.45535 1.41174 7.41586Z" stroke="#175CD3" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M7.00024 8.74984C7.96673 8.74984 8.75024 7.96634 8.75024 6.99984C8.75024 6.03334 7.96673 5.24984 7.00024 5.24984C6.03374 5.24984 5.25024 6.03334 5.25024 6.99984C5.25024 7.96634 6.03374 8.74984 7.00024 8.74984Z" stroke="#175CD3" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            <span>Lihat Laporan</span>
        </button>
        <button class="btn btn-xs btn-default-fill">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                  <path d="M12.25 8.75V9.45C12.25 10.4301 12.25 10.9201 12.0593 11.2945C11.8915 11.6238 11.6238 11.8915 11.2945 12.0593C10.9201 12.25 10.4301 12.25 9.45 12.25H4.55C3.56991 12.25 3.07986 12.25 2.70552 12.0593C2.37623 11.8915 2.10852 11.6238 1.94074 11.2945C1.75 10.9201 1.75 10.4301 1.75 9.45V8.75M9.91667 4.66667L7 1.75M7 1.75L4.08333 4.66667M7 1.75V8.75" stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            <span>Unggah Laporan</span>
        </button>
    </div>
    <div class="space-y-3">
        <div class="flex place-items-center text-xs-reguler">
            <h6 class="w-28">Tanggal Unggah</h6>
            <div>
                <span>:</span>
            </div>
        </div>
        <div class="flex place-items-center text-xs-reguler">
            <h6 class="w-28">Tanggal Unggah</h6>
            <div>
                <span>:</span>
            </div>
        </div>
    </div>
</div>



@endsection