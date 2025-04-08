@extends('layouts.app')

@section('page-title', 'Pengaturan Akun')
@section('content')

    <div>
        <div class="flex justify-between space-x-4">
            <form action="{{ route('advisor.account.updateAccount') }}" method="POST" id="accountForm" class="w-full"
                enctype="multipart/form-data">
                @csrf
                <div class="space-y-4 border border-neutral-100">
                    <div class="border-b border-neutral-100 py-4 px-4 space-y-2">
                        <h6 class="text-sm-medium">Akun Pengguna</h6>
                    </div>
                    <div class="px-4 pb-4 space-y-4">
                        <div class="space-y-3">
                            <input type="hidden" name="user_id" value="{{ $data->user_id }}">
                            <div class="flex space-x-3 text-xs-reguler place-items-center">
                                <h6 class="min-w-36">Username</h6>
                                <span>:</span>
                                <span>{{ $data->user->username ?? '' }}</span>
                            </div>
                            <div class="flex space-x-3 text-xs-reguler place-items-center">
                                <h6 class="min-w-36">Email</h6>
                                <span>:</span>
                                <span>{{ $data->user->email ?? '' }}</span>
                            </div>
                            <div class="flex space-x-3 text-xs-reguler place-items-center">
                                <h6 class="min-w-36">Password</h6>
                                <span>:</span>
                                <input type="password" placeholder="****" id="inputAccountField" name="password"
                                    class="w-full" disabled required>
                            </div>
                            <div id="checkPassword" class="hidden space-y-4">
                                <div class="flex space-x-3 text-xs-reguler place-items-center">
                                    <h6 class="min-w-36">Password Baru</h6>
                                    <span>:</span>
                                    <input type="password" placeholder="****" name="new_password" class="input w-full"
                                        required>
                                </div>
                                <div class="flex space-x-3 text-xs-reguler place-items-center">
                                    <h6 class="min-w-36">Ulangi Password Baru</h6>
                                    <span>:</span>
                                    <input type="password" placeholder="****" name="check_password" class="input w-full"
                                        required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="py-3 px-6 border-t border-neutral-150 flex justify-end space-x-2">
                        <button id="editAccountCancelBtn" @click.prevent="editAccountCancel()"
                            class="btn btn-xs btn-success-outline hidden">Batal</button>
                        <button id="editAccountBtn" @click.prevent="editAccount()" class="btn btn-xs btn-default-fill">Ubah
                            Data</button>
                    </div>
                </div>
            </form>

            <form action="{{ route('advisor.account.updateProfile') }}" method="POST" id="profileForm" class="w-full"
                enctype="multipart/form-data">
                @csrf
                <div class="space-y-4 border border-neutral-100">
                    <div class="border-b border-neutral-100 py-4 px-4 space-y-2">
                        <h6 class="text-sm-medium">Profil Pengguna</h6>
                    </div>
                    <div class="px-4 pb-4 space-y-4">
                        <div class="space-y-3">
                            <input type="hidden" name="profile_id" value="{{ $data->id }}">
                            <div class="flex space-x-3 text-xs-reguler place-items-center">
                                <h6 class="min-w-36">Nama</h6>
                                <span>:</span>
                                <span>{{ $data->name ?? '' }}</span>
                            </div>
                            <div class="flex space-x-3 text-xs-reguler place-items-center">
                                <h6 class="min-w-36">NIP</h6>
                                <span>:</span>
                                <span>{{ $data->nip ?? '' }}</span>
                            </div>
                            <div class="flex space-x-3 text-xs-reguler place-items-center">
                                <h6 class="min-w-36">Jurusan</h6>
                                <span>:</span>
                                <span>{{ $data->department->name ?? '' }}</span>
                            </div>
                            <div class="flex space-x-3 text-xs-reguler place-items-center">
                                <h6 class="min-w-36">Nomor Telepon</h6>
                                <span>:</span>
                                <input type="text" value="{{ $data->phone_num ?? '' }}" id="inputProfilField"
                                    class="w-full" name="phone_num" required>
                            </div>
                        </div>
                    </div>
                    <div class="py-3 px-6 border-t border-neutral-150 flex justify-end space-x-2">
                        <button id="editProfilCancelBtn" @click.prevent="editProfilCancel()"
                            class="btn btn-xs btn-success-outline hidden">Batal</button>
                        <button id="editProfilBtn" @click.prevent="editProfil()" class="btn btn-xs btn-default-fill">Ubah
                            Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editAccount() {
            const editAccountBtn = document.getElementById('editAccountBtn');
            const editAccountCancelBtn = document.getElementById('editAccountCancelBtn');
            const inputAccountField = document.getElementById('inputAccountField');
            const checkPassword = document.getElementById('checkPassword');
            if (editAccountBtn.innerText == 'Ubah Data') {
                editAccountBtn.innerText = 'Simpan';
                editAccountBtn.classList.add('btn-success-fill');
                editAccountCancelBtn.classList.remove('hidden');

                inputAccountField.value = '';
                inputAccountField.disabled = false;
                inputAccountField.classList.add('input');

                checkPassword.classList.remove('hidden');
            } else if (editAccountBtn.innerText == 'Simpan') {
                // editAccountCancelBtn.classList.add('hidden');
                // checkPassword.classList.add('hidden');
                accountForm.submit();
            }
        }

        function editAccountCancel() {
            const editAccountBtn = document.getElementById('editAccountBtn');
            const editAccountCancelBtn = document.getElementById('editAccountCancelBtn');
            const inputAccountField = document.getElementById('inputAccountField');

            editAccountBtn.innerText = 'Ubah Data';
            editAccountBtn.classList.remove('btn-success-fill');
            editAccountCancelBtn.classList.add('hidden');
            checkPassword.classList.add('hidden');

            inputAccountField.disabled = true;
            inputAccountField.classList.remove('input');
        }
    </script>

    <script>
        function editProfil() {
            const editProfilBtn = document.getElementById('editProfilBtn');
            const editProfilCancelBtn = document.getElementById('editProfilCancelBtn');
            const inputProfilField = document.getElementById('inputProfilField');
            if (editProfilBtn.innerText == 'Ubah Data') {
                editProfilBtn.innerText = 'Simpan';
                editProfilBtn.classList.add('btn-success-fill');
                editProfilCancelBtn.classList.remove('hidden');

                inputProfilField.disabled = false;
                inputProfilField.classList.add('input');
            } else if (editProfilBtn.innerText == 'Simpan') {
                // editProfilCancelBtn.classList.add('hidden');
                profileForm.submit();
            }
        }

        function editProfilCancel() {
            const editProfilBtn = document.getElementById('editProfilBtn');
            const editProfilCancelBtn = document.getElementById('editProfilCancelBtn');
            const inputProfilField = document.getElementById('inputProfilField');

            editProfilBtn.innerText = 'Ubah Data';
            editProfilBtn.classList.remove('btn-success-fill');
            editProfilCancelBtn.classList.add('hidden');

            inputProfilField.disabled = true;
            inputProfilField.value = '{{ $data->phone_num ?? '' }}';
            inputProfilField.classList.remove('input');
        }
    </script>

@endsection
