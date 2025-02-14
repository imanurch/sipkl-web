<x-form>
    <x-slot name="formTitle">Data Administrator</x-slot>
    <x-slot name="formBody" >
        <div class="input-group">
            <label class="input-label" for="">Username</label>
            <input name="username" class="input" type="text" placeholder="Masukkan Username" :disabled="modalAction=='isDelete'" :value="modalAction=='isEdit' || modalAction=='isDelete' ? dataId.username : ''" required>    
            {{-- <input name="username" class="input" type="text" placeholder="Masukkan Username" :disabled="modalAction=='isDelete'" value="{{ session('modalData')->username }}" required>     --}}
        </div>
        <div class="input-group">
            <label class="input-label" for="">Email</label>
            <input name="email" class="input" type="email" placeholder="Masukkan Email" :disabled="modalAction=='isDelete'" :value="modalAction=='isEdit' || modalAction=='isDelete' ? dataId.email : ''" required>    
            {{-- <input name="email" class="input" type="email" placeholder="Masukkan Email" :disabled="modalAction=='isDelete'" :value="modalAction=='isEdit' || modalAction=='isDelete' ? modalData.email : ''" required>     --}}
        </div>
        <div class="input-group">
            <label class="input-label" for="">Nomor Telepon</label>
            <input name="phone_num" class="input" type="text" placeholder="Masukkan Nomor Telepon" :disabled="modalAction=='isDelete'" :value="modalAction=='isEdit' || modalAction=='isDelete' ? dataId.phone_num : ''" required>    
        </div>
        <div class="input-group">
            <label class="input-label" for="" :hidden="modalAction=='isDelete'">Kata Sandi</label>
            <input name="password" class="input" type="text" placeholder="Masukkan Kata Sandi" :hidden="modalAction=='isDelete'">    
        </div>
        <div class="input-group">
            <label class="input-label" for="" :hidden="modalAction=='isDelete'">Ulangi Kata Sandi</label>
            <input name="check_password" class="input" type="text" placeholder="Ulangi Kata Sandi" :hidden="modalAction=='isDelete'">    
        </div>
    </x-slot>
</x-form>