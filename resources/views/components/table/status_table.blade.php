<td class="justify-items-center">
    <div class="tag-status">
        <div class="tag-status-icon {{ $status == 'Non Aktif' || $status == 'Belum Terdaftar' || $status == 'Tidak Lengkap' || $status == 'Tidak Lulus' ? 'bg-icon-error' : 'bg-icon-success' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="14" viewBox="0 0 15 14" fill="none">
                <path d="{{$status == 'Non Aktif' || $status == 'Belum Terdaftar' || $status == 'Tidak Lengkap' || $status == 'Tidak Lulus' ? 'M10.4167 4.08325L4.58337 9.91659M4.58337 4.08325L10.4167 9.91659' : 'M12.1667 3.5L5.75001 9.91667L2.83334 7' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>                      
        </div>
        <span>{{$status}}</span>
    </div>
</td>

{{-- <td class="justify-items-center">
    <div class="tag-status">
        <div class="tag-status-icon {{ $status == 0 ? 'bg-icon-error' : 'bg-icon-success' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="14" viewBox="0 0 15 14" fill="none">
                <path d="{{$status == 0 ? 'M10.4167 4.08325L4.58337 9.91659M4.58337 4.08325L10.4167 9.91659' : 'M12.1667 3.5L5.75001 9.91667L2.83334 7' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>                      
        </div>
        <span>{{$statusName[$status]}}</span>
    </div>
</td> --}}