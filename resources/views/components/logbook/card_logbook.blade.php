<div class="flex place-items-center border border-neutral-200 rounded py-4 px-6 justify-between">
    <div class="space-y-2">
        <div class="tag-status">
            <div
                class="tag-status-icon {{ $data->status == 1 ? 'bg-icon-success' : ($data->status == 2 ? 'bg-icon-warning' : ($data->status == 0 && $data->activities != null ? 'bg-icon-neutral' : 'bg-icon-error')) }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="14" viewBox="0 0 15 14" fill="none">
                    <path
                        d="{{ $data->status == 1
                            ? 'M12.1667 3.5L5.75001 9.91667L2.83334 7'
                            : ($data->status == 2
                                ? 'M10.4999 5.83331L8.1666 3.49997M1.45825 12.5416L3.43247 12.3223C3.67367 12.2955 3.79427 12.2821 3.907 12.2456C4.007 12.2132 4.10218 12.1675 4.18994 12.1096C4.28885 12.0444 4.37465 11.9586 4.54626 11.787L12.2499 4.08331C12.8943 3.43898 12.8943 2.39431 12.2499 1.74997C11.6056 1.10564 10.5609 1.10564 9.9166 1.74997L2.21293 9.45363C2.04132 9.62524 1.95552 9.71104 1.89029 9.80996C1.83242 9.89771 1.78668 9.99289 1.7543 10.0929C1.71781 10.2056 1.70441 10.3262 1.67761 10.5674L1.45825 12.5416Z'
                                : 'M10.4167 4.08325L4.58337 9.91659M4.58337 4.08325L10.4167 9.91659') }}"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
            <span>{{ $data->status == 1 ? 'Disetujui' : ($data->status == 2 ? 'Perlu Revisi' : ($data->status == 0 && $data->activities != null ? 'Menunggu Konfirmasi' : 'Belum Diisi')) }}</span>
        </div>
        <div>
            <h6 class="text-xs-medium">Minggu Ke- {{ $week }}</h6>
            <span class="text-xs-reguler">{{ $data->start_date }} - {{ $data->end_date }}</span>
        </div>
    </div>
    <svg @click="modalAction='{{ $data->status == '1' ? 'isView' : ($data->activities != null ? 'isEdit' : 'isAdd') }}';dataId={{ $data->toJson() }};id='{{ $data->id }}'"
        class="cursor-pointer" xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24"
        fill="none">
        <path d="M9.5 18L15.5 12L9.5 6" stroke="#3D4350" stroke-width="1.33333" stroke-linecap="round"
            stroke-linejoin="round" />
    </svg>
</div>
