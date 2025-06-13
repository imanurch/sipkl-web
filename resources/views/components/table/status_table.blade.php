<td class="justify-items-center">
    <div class="tag-status">
        <div
            class="tag-status-icon {{ $status == 'Non Aktif' || $status == 'Belum Terdaftar' || $status == 'Tidak Lengkap' || $status == 'Tidak Lulus' || $status == 'Belum Dikonfirmasi' || $status == 'Ditolak' ? 'bg-icon-error' : ($status == 'Perlu Konfirmasi' ? 'bg-icon-warning' : 'bg-icon-success') }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="14" viewBox="0 0 15 14" fill="none">
                <path
                    d="{{ $status == 'Non Aktif' || $status == 'Belum Terdaftar' || $status == 'Tidak Lengkap' || $status == 'Tidak Lulus' || $status == 'Belum Dikonfirmasi' || $status == 'Ditolak' || $status == 'Perlu Konfirmasi' ? 'M10.4167 4.08325L4.58337 9.91659M4.58337 4.08325L10.4167 9.91659' : 'M12.1667 3.5L5.75001 9.91667L2.83334 7' }}"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
        <span class="whitespace-nowrap">{{ $status }}</span>
    </div>
</td>
