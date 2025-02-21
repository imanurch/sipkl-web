<div class="space-y-4">
    <h6 class="text-xs-medium">Isi Data Pendaftaran PKL</h6>
    <form action="{{ route('student.registration.step4') }}" method="POST">
        @csrf
        <div class="space-y-4">
            <div class="space-y-4">
                <div class="input-group">
                    <label class="input-label" for="">Lokasi PKL</label>
                    <input name="industry_id" type="hidden" value="{{ $locationInternship->id ?? '' }}">
                    <input class="input" type="text" disabled value="{{ $locationInternship->name ?? '' }}">
                </div>
                <div class="input-group">
                    <label class="input-label" for="">Anggota Kelompok</label>
                    <div class="flex flex-col space-y-2">
                        @foreach ($teamMember as $dt)
                            <input name="student_id[]" type="hidden" value="{{ $dt->id ?? '' }}">
                            <input class="input" type="text" disabled value="{{ $dt->name }}">
                        @endforeach
                    </div>
                </div>
                <div class="input-group">
                    <label class="input-label" for="">Waktu Mulai</label>
                    <input name="start_date" class="input" type="date"
                        onclick="this.showPicker()" required>    
                </div>
                <div class="input-group">
                    <label class="input-label" for="">Waktu Selesai</label>
                    <input name="end_date" class="input" type="date"
                        onclick="this.showPicker()" required>    
                </div> 
            </div>
            <div>
                <button type="submit"
                        class="btn btn-xs btn-success-fill">
                    <span>Selanjutnya</span>
                    </button>
                </div>
            </div>
    </form>
</div>
