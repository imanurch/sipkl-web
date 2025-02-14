<div class="space-y-4">
<h6 class="text-xs-medium">Isi Data Pendaftaran PKL</h6>
<div class="flex space-x-3 text-xs-reguler place-items-center">
<h6 class="w-32">Surat Pengantar PKL</h6>
<span>:</span>
<a href="{{ route('student.registration.download.file', ['filename' => $filename]) }}"
    class="btn btn-xs btn-default-fill">
    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
        <path
            d="M12.25 8.75V9.45C12.25 10.4301 12.25 10.9201 12.0593 11.2945C11.8915 11.6238 11.6238 11.8915 11.2945 12.0593C10.9201 12.25 10.4301 12.25 9.45 12.25H4.55C3.56991 12.25 3.07986 12.25 2.70552 12.0593C2.37623 11.8915 2.10852 11.6238 1.94074 11.2945C1.75 10.9201 1.75 10.4301 1.75 9.45V8.75M9.91667 5.83333L7 8.75M7 8.75L4.08333 5.83333M7 8.75V1.75"
            stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
    </svg>
    <span>Unduh File</span>
</a>
</div>
{{-- <form action="{{ route('student.registration.step5') }}" method="POST">
        @csrf
        <div class="space-y-3">
            <div class="flex space-x-3 text-xs-reguler place-items-center">
                <h6 class="w-32">Status Pendaftaran</h6>
                <span>:</span>
                <div class="tag-status">
                    <div class="tag-status-icon {{ $registrationData->status == '0' ? 'bg-icon-warning' : ($registrationData->status == '1' ? 'bg-icon-success' : 'bg-icon-error')}}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="14" viewBox="0 0 15 14" fill="none">
                            <path d="{{$registrationData->status == '1' ? 'M12.1667 3.5L5.75001 9.91667L2.83334 7' : 'M10.4167 4.08325L4.58337 9.91659M4.58337 4.08325L10.4167 9.91659'}}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>                      
                    </div>
                    <span>{{ $registrationData->status == '0' ? 'Proses Verifikasi' : ($registrationData->status == '1' ? 'Pendaftaran Diterima' : 'Pendaftaran Ditolak')}}</span>
                </div>
            </div>
            <div class="flex space-x-3 text-xs-reguler place-items-center">
                <h6 class="w-32">Lokasi PKL</h6>
                <span>:</span>
                <span>{{ $registrationData->industry->name }}</span>
            </div>
            <div class="flex space-x-3 text-xs-reguler place-items-center">
                <h6 class="w-32">Anggota Kelompok</h6>
                <span>:</span>
                <div>
                    @foreach ($registrationData->group->groupMember as $dt)
                    <div>
                        <span>{{ $dt->name }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="flex space-x-3 text-xs-reguler place-items-center">
                <h6 class="w-32">Waktu Mulai</h6>
                <span>:</span>
                <span>{{ $registrationData->start_date }}</span>
            </div>
            <div class="flex space-x-3 text-xs-reguler place-items-center">
                <h6 class="w-32">Waktu Selesai</h6>
                <span>:</span>
                <span>{{ $registrationData->end_date }}</span>
            </div>
            <div class="flex space-x-3 text-xs-reguler place-items-center">
                <h6 class="w-32">Surat Pengantar PKL</h6>
                <span>:</span>
                <button class="btn btn-xs btn-default-fill">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                        <path d="M12.25 8.75V9.45C12.25 10.4301 12.25 10.9201 12.0593 11.2945C11.8915 11.6238 11.6238 11.8915 11.2945 12.0593C10.9201 12.25 10.4301 12.25 9.45 12.25H4.55C3.56991 12.25 3.07986 12.25 2.70552 12.0593C2.37623 11.8915 2.10852 11.6238 1.94074 11.2945C1.75 10.9201 1.75 10.4301 1.75 9.45V8.75M9.91667 5.83333L7 8.75M7 8.75L4.08333 5.83333M7 8.75V1.75" stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Unduh File</span>
                </button>                                      
            </div>
            <div class="flex space-x-3 text-xs-reguler place-items-center">
                <h6 class="w-32">Surat Balasan Industri (Bukti Diterima)</h6>
                <span>:</span>
                <button class="btn btn-xs btn-default-fill">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                        <path d="M12.25 8.75V9.45C12.25 10.4301 12.25 10.9201 12.0593 11.2945C11.8915 11.6238 11.6238 11.8915 11.2945 12.0593C10.9201 12.25 10.4301 12.25 9.45 12.25H4.55C3.56991 12.25 3.07986 12.25 2.70552 12.0593C2.37623 11.8915 2.10852 11.6238 1.94074 11.2945C1.75 10.9201 1.75 10.4301 1.75 9.45V8.75M9.91667 4.66667L7 1.75M7 1.75L4.08333 4.66667M7 1.75V8.75" stroke="#175CD3" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Unggah File</span>
                </button>                                      
            </div>
        </div> 
        
        <div>
            <button type="submit" class="btn btn-xs btn-success-fill">
                <span>Selanjutnya</span>
            </button>
        </div>
    </form> --}}
</div>
