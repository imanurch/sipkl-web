<div class="space-y-12">
    <div class="space-y-3">
        <h6 class="text-xs-medium">Pilih Lokasi PKL</h6>
        <form action="{{ route('student.registration.step2') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div class="input-group">
                    <label class="input-label" for="">Pilih Lokasi PKL yang Tersedia</label>
                    <div x-data="{ option: false, selected: 'Pilih Opsi', valueSelected: '' }">
                        <input type="hidden" name="internshipLocation" x-model="valueSelected">
                        <button @click.prevent="option=!option" class="input input-select w-full" :disabled="isDelete"
                            required>
                            <span x-text="selected"
                                :class="selected == 'Pilih Opsi' ? 'text-neutral-300' : 'text-neutral-800'"></span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                fill="none">
                                <path d="M5 7.5L10 12.5L15 7.5" stroke="#667085" stroke-width="0.933333"
                                    stroke-linecap="round" stroke-linejoin="round" :hidden="isDelete" />
                            </svg>
                        </button>
                        <div x-show="option" @click.away="option=false">
                            <ul class="border border-brand-600 rounded py-2 my-2 max-h-32 overflow-auto">
                                @foreach ($industryData as $dt)
                                    <li @click="option=false;selected='{{ $dt->name }}';valueSelected='{{ $dt->id }}'"
                                        class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">
                                        {{ $dt->name }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <p class="text-xs-reguler"><span class="text-error-600">Tidak Menemukan Lokasi PKL yang dicari?</span>
                    Ajukan industri baru dengan menekan tombol Ajukan Lokasi di bawah ini ya! (Cek pengajuan lokasi agar tidak diajukan kembali)</p>
                <div>
                    <button @click.prevent="currentStep='newIndustry'" class="btn btn-xs btn-success-outline">
                        <span>Ajukan Lokasi Baru</span>
                    </button>
                    <button type="submit" class="btn btn-xs btn-success-fill">
                        <span>Selanjutnya</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    @if ($industryRequestData != null)
        <div>
            <h6 class="text-xs-medium">Pengajuan Lokasi Baru</h6>
            <table class="table">
                <thead>
                    <th>NO</th>
                    <th>NAMA</th>
                    <th>ALAMAT</th>
                    <th>NO TELP</th>
                </thead>
                <tbody>
                    @foreach ($industryRequestData as $dt)
                        <tr>
                            <td class="text-center">{{ $industryRequestData->firstItem() + $loop->index }}</td>
                            <td>{{ $dt->name }}</td>
                            <td>{{ $dt->address }}</td>
                            <td>{{ $dt->phone_num }}</td>
                        </tr>
                    @endforeach
                </tbody>
                {{-- pagination --}}
                <x-slot name="pagination">{{ $industryRequestData->links() }}</x-slot>
            </table>
        </div>
    @endif
</div>
