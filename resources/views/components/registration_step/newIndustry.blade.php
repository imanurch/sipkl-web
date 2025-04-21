<div class="space-y-3">
    <h6 class="text-xs-medium">Ajukan Lokasi PKL Baru</h6>
    <form action="{{ route('student.registration.industry.request') }}" method="POST">
        @csrf
        <div class="space-y-4">
            <div class="space-y-3">
                <div class="input-group">
                    <label class="input-label" for="">Nama Industri</label>
                    <input name="name" class="input" type="text" placeholder="Masukkan Nama" required>
                </div>
                <div class="input-group">
                    <label class="input-label" for="">Alamat Industri</label>
                    <input name="address" class="input" type="text" placeholder="Masukkan Alamat" required>
                </div>
                <div class="input-group">
                    <label class="input-label" for="">Email Industri</label>
                    <input name="email" class="input" type="text" placeholder="Masukkan Email" required>
                </div>
                <div class="input-group">
                    <label class="input-label" for="">Nomor Telepon Industri</label>
                    <input name="phone_num" class="input" type="text" placeholder="Masukkan Nomor Telepon" required>
                </div>
                <div class="input-group">
                    <label class="input-label" for="">Nama Pimpinan</label>
                    <input name="leader_name" class="input" type="text" placeholder="Masukkan Alamat" required>
                </div>
            </div>

            <div>
                <button @click="currentStep=1" class="btn btn-xs btn-success-outline">
                    <span>Kembali</span>
                </button>
                <button type="submit" class="btn btn-xs btn-success-fill">
                    <span>Simpan Pengajuan</span>
                </button>
            </div>
        </div>
    </form>
</div>
