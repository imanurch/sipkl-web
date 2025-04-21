<div class="space-y-3">
    <h6 class="text-xs-medium">Isi Data Anggota Kelompok</h6>
    <form action="{{ route('student.registration.step3') }}" method="POST">
        @csrf
        <div class="space-y-4">
            <div class="input-group">
                <label class="input-label" for="">Pilih Anggota Kelompok yang tersedia</label>
                <div x-data="{ option: false, selected: 'Pilih Opsi', valueSelected: '' }" class="space-y-4">
                    <div class="flex place-items space-x-2 w-96">
                        <div class="w-full">
                            <button @click.prevent="option=!option" class="input input-select w-full"
                                :disabled="isDelete" required>
                                <span x-text="selected"
                                    :class="selected == 'Pilih Opsi' ? 'text-neutral-300' : 'text-neutral-800'"></span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 20 20" fill="none">
                                    <path d="M5 7.5L10 12.5L15 7.5" stroke="#667085" stroke-width="0.933333"
                                        stroke-linecap="round" stroke-linejoin="round" :hidden="isDelete" />
                                </svg>
                            </button>
                            <div x-show="option" @click.away="option=false">
                                <ul class="border border-brand-600 rounded py-2 my-2 max-h-32 overflow-auto">
                                    @foreach ($studentListData as $dt)
                                        <li @click="option=false;selected='{{ $dt->name }} (NIS {{ $dt->nis }})';valueSelected='{{ $dt->id }}'"
                                            class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">
                                            {{ $dt->name }} (NIS {{ $dt->nis }})</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <button x-show="selected!='Pilih Opsi'" @click.prevent="addMember(selected, valueSelected);selected='Pilih Opsi'"
                            class="btn btn-xs btn-default-fill h-fit text-nowrap">Tambah Anggota</button>
                    </div>
                    <div id="memberFields" class="space-y-2">
                        {{-- team member --}}
                    </div>
                    <div class="flex space-x-2">
                        <input type="checkbox" name="teamMember[]" value="" id="doesntHaveGroup">
                        <span class="text-xs">Individu/Tidak memiliki anggota kelompok</span>
                    </div>
                    <p class="text-xs">* Tidak perlu menambah data diri karena sistem secara otomatis mencatat kamu
                        sebagai anggota</p>
                </div>
            </div>
            <div>
                <button type="submit" class="btn btn-xs btn-success-fill">
                    <span>Selanjutnya</span>
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    function addMember(selected, valueSelected) {
        const memberFields = document.getElementById('memberFields');
        const indiv = document.getElementById('doesntHaveGroup');
        const newField = document.createElement('div');
        newField.classList.add('flex', 'space-x-2');

        newField.innerHTML = `
            <input value="${selected}" readonly class="input w-80" disabled>
            <button onclick="removeMember(this)" class="btn btn-xs btn-error-fill h-fit">Hapus</button>
            <input type="hidden" name="teamMember[]" value="${valueSelected}" readonly>
        `;
        indiv.name = '';

        memberFields.appendChild(newField);
    }

    function removeMember(button) {
        button.parentElement.remove();
    }
</script>
