<div class="space-y-2">
    <h6 class="text-xs-medium">{{ $period }}</h6>

    @if ($data->activities)
        @if ($data->status == '1')
            <div class="space-y-2">
                <div class="tag-status">
                    <div class="tag-status-icon bg-icon-success">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="14" viewBox="0 0 15 14"
                            fill="none">
                            <path d="M12.1667 3.5L5.75001 9.91667L2.83334 7" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                    <span>Disetujui</span>
                </div>
                <textarea class="input w-full" name="" id="" rows="4" disabled>{{ $content }}</textarea>
            </div>
        @else
            <div x-data="{ modalAction: null, currentId: null }" class="space-y-2">
                @if ($data->status == '2')
                    <div class="tag-status">
                        <div class="tag-status-icon bg-icon-warning">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14"
                                fill="none">
                                <path
                                    d="M10.4999 5.83331L8.1666 3.49997M1.45825 12.5416L3.43247 12.3223C3.67367 12.2955 3.79427 12.2821 3.907 12.2456C4.007 12.2132 4.10218 12.1675 4.18994 12.1096C4.28885 12.0444 4.37465 11.9586 4.54626 11.787L12.2499 4.08331C12.8943 3.43898 12.8943 2.39431 12.2499 1.74997C11.6056 1.10564 10.5609 1.10564 9.9166 1.74997L2.21293 9.45363C2.04132 9.62524 1.95552 9.71104 1.89029 9.80996C1.83242 9.89771 1.78668 9.99289 1.7543 10.0929C1.71781 10.2056 1.70441 10.3262 1.67761 10.5674L1.45825 12.5416Z"
                                    stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <span>Direvisi</span>
                    </div>
                @endif
                <textarea class="input w-full" name="" id="" rows="4" disabled>{{ $content }}</textarea>
                <div class="space-x-1">
                    <button @click="modalAction='accept';currentId='{{ $data->id }}'"
                        class="btn btn-xs btn-success-fill">
                        <span>Setujui</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14"
                            fill="none">
                            <path d="M11.6666 3.5L5.24992 9.91667L2.33325 7" stroke="white" stroke-width="1.6"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <button @click="modalAction='revise';currentId='{{ $data->id }}'"
                        class="btn btn-xs btn-warning-fill">
                        <span>Revisi</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14"
                            fill="none">
                            <path
                                d="M10.4999 5.83331L8.1666 3.49997M1.45825 12.5416L3.43247 12.3223C3.67367 12.2955 3.79427 12.2821 3.907 12.2456C4.007 12.2132 4.10218 12.1675 4.18994 12.1096C4.28885 12.0444 4.37465 11.9586 4.54626 11.787L12.2499 4.08331C12.8943 3.43898 12.8943 2.39431 12.2499 1.74997C11.6056 1.10564 10.5609 1.10564 9.9166 1.74997L2.21293 9.45363C2.04132 9.62524 1.95552 9.71104 1.89029 9.80996C1.83242 9.89771 1.78668 9.99289 1.7543 10.0929C1.71781 10.2056 1.70441 10.3262 1.67761 10.5674L1.45825 12.5416Z"
                                stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>

                {{-- modal --}}
                <div x-show="modalAction != null" class="form-modal">
                    <form method="POST" id="modalForm" class="form"
                        :action="`{{ route('advisor.logbook.detail.confirm', ['logbookId' => '__ID__', 'status' => '__STATUS__']) }}`
                        .replace('__ID__', currentId)
                            .replace('__STATUS__', modalAction)">
                        <div class="form-header">
                            @csrf
                            @method('PATCH')
                            <h6 class="">Konfirmasi Logbook Siswa</h6>
                            <svg @click="modalAction=null" class="cursor-pointer" xmlns="http://www.w3.org/2000/svg"
                                width="28" height="28" viewBox="0 0 28 28" fill="none">
                                <path d="M19.8333 8.16675L8.16663 19.8334M8.16663 8.16675L19.8333 19.8334"
                                    stroke="#525A6A" stroke-width="1.03704" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div class="form-body">
                            <div class="input-group">
                                <label class="input-label" for="">Komentar (Opsional)</label>
                                <textarea class="input" name="feedback" id=""></textarea>
                            </div>
                        </div>
                        <div class="form-footer"
                            :class="modalAction == 'accept' ? 'border-success-400' : 'border-error-400'">
                            <button @click="modalAction=null" class="btn btn-xs"
                                :class="modalAction == 'accept' ? 'btn-success-outline' : 'btn-error-outline'">Batalkan</button>
                            <button type="submit" class="btn btn-xs"
                                :class="modalAction == 'accept' ? 'btn-success-fill' : 'btn-error-fill'"
                                x-text="modalAction == 'accept' ? 'Setujui' : 'Revisi'"></button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @else
        <input type="text" class="input w-full text-neutral-300" value="Belum Diisi" disabled>
    @endif
</div>
