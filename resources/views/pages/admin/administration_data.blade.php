@extends('layouts.app')

@section('page-title', 'Data Administrasi')
@section('profil', 'Admin')
@section('content')

    <div class="space-y-6">
        <div>
            <div class="flex justify-between">
                <form action="{{ route('admin.administrationData.update') }}" method="POST" id="form" class="w-full"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4 border border-neutral-100">
                        <div class="border-b border-neutral-100 py-4 px-4 space-y-2">
                            <h6 class="text-sm-medium">Data Sekolah</h6>
                            <p class="text-xs-reguler">Data dibutuhkan untuk kebutuhan generate dokumen persuratan</p>
                        </div>
                        <div class="px-4 pb-4 space-y-4">
                            <div class="space-y-3">
                                <div class="flex space-x-3 text-xs-reguler place-items-center">
                                    <h6 class="min-w-56">Nama</h6>
                                    <span>:</span>
                                    <span>{{ $schoolProfile->name ?? 'ss' }}</span>
                                </div>
                                <div class="flex space-x-3 text-xs-reguler place-items-center">
                                    <h6 class="min-w-56">Alamat</h6>
                                    <span>:</span>
                                    <span>{{ $schoolProfile->address ?? '' }}</span>
                                </div>
                                <div class="flex space-x-3 text-xs-reguler place-items-center">
                                    <h6 class="min-w-56">Email</h6>
                                    <span>:</span>
                                    <input class="inputField w-full" name="email"
                                        value="{{ $schoolProfile->email ?? '' }}" disabled>
                                </div>
                                <div class="flex space-x-3 text-xs-reguler place-items-center">
                                    <h6 class="min-w-56">Nomor Telepon</h6>
                                    <span>:</span>
                                    <input class="inputField w-full" name="phone_num"
                                        value="{{ $schoolProfile->phone_num ?? '' }}" disabled>
                                </div>
                                <div class="flex space-x-3 text-xs-reguler place-items-center">
                                    <h6 class="min-w-56">Website</h6>
                                    <span>:</span>
                                    <input class="inputField w-full" name="website"
                                        value="{{ $schoolProfile->website ?? '' }}" disabled>
                                </div>
                                <div class="flex space-x-3 text-xs-reguler place-items-center">
                                    <h6 class="min-w-56">Nama Kepala Sekolah</h6>
                                    <span>:</span>
                                    <input class="inputField w-full" name="principal_name"
                                        value="{{ $schoolProfile->principal_name ?? '' }}" disabled>
                                </div>
                                <div class="flex space-x-3 text-xs-reguler place-items-center">
                                    <h6 class="min-w-56">NIP Kepala Sekolah</h6>
                                    <span>:</span>
                                    <input class="inputField w-full" name="principal_nip"
                                        value="{{ $schoolProfile->principal_nip ?? '' }}" disabled>
                                </div>
                                <div class="flex space-x-3 text-xs-reguler place-items-center">
                                    <h6 class="min-w-56">Tanda Tangan Kepala Sekolah</h6>
                                    <span>:</span>
                                    @if ($schoolProfile->principal_signature != null)
                                        <a href="{{ route('admin.administrationData.downloadFile', ['filename' => $schoolProfile->principal_signature]) }}"
                                            target="_blank" class="documentViewBtn btn btn-xs btn-default-fill">Lihat
                                            File</a>
                                    @endif
                                    <input class="fileInputField hidden" name="principal_signature" type="file">
                                </div>
                                <div class="flex space-x-3 text-xs-reguler place-items-center">
                                    <h6 class="min-w-56">Cap Sekolah</h6>
                                    <span>:</span>
                                    @if ($schoolProfile->school_stamp != null)
                                        <a href="{{ route('admin.administrationData.downloadFile', ['filename' => $schoolProfile->school_stamp]) }}"
                                            target="_blank" class="documentViewBtn btn btn-xs btn-default-fill">Lihat
                                            File</a>
                                    @endif
                                    <input class="fileInputField hidden" name="school_stamp" type="file">
                                </div>
                                <div class="flex space-x-3 text-xs-reguler place-items-center">
                                    <h6 class="min-w-56">SK Pembentukan Tim POKJA PKL</h6>
                                    <span>:</span>
                                    <textarea class="inputField w-full resize-none" name="internship_team_decree" disabled>{{ $schoolProfile->internship_team_decree ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="py-3 px-6 border-t border-neutral-150 flex justify-end space-x-2">
                            <button id="btnEditCancel" @click.prevent="editDataCancel()"
                                class="btn btn-xs btn-success-outline hidden">Batal</button>
                            <button id="btnEdit" @click.prevent="editData()" class="btn btn-xs btn-default-fill">Ubah
                                Data</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function editData() {
                const btnEdit = document.getElementById('btnEdit');
                const btnEditCancel = document.getElementById('btnEditCancel');
                const inputs = document.querySelectorAll('.inputField');
                const fileInputs = document.querySelectorAll('.fileInputField');
                const documentViewBtn = document.querySelectorAll('.documentViewBtn');
                if (btnEdit.innerText == 'Ubah Data') {
                    btnEdit.innerText = 'Simpan';
                    btnEdit.classList.add('btn-success-fill');
                    btnEditCancel.classList.remove('hidden');
                    inputs.forEach(input => {
                        input.disabled = false;
                        input.classList.add('input');
                    });
                    fileInputs.forEach(input => {
                        input.classList.remove('hidden');
                    })
                    documentViewBtn.forEach(btn => {
                        btn.classList.add('hidden');
                    })
                } else if (btnEdit.innerText == 'Simpan') {
                    btnEditCancel.classList.add('hidden');
                    form.submit();
                }
            }

            function editDataCancel() {
                const btnEdit = document.getElementById('btnEdit');
                const btnEditCancel = document.getElementById('btnEditCancel');
                const inputs = document.querySelectorAll('.inputField');
                const fileInputs = document.querySelectorAll('.fileInputField');
                const documentViewBtn = document.querySelectorAll('.documentViewBtn');

                btnEdit.innerText = 'Ubah Data';
                btnEdit.classList.remove('btn-success-fill');
                btnEditCancel.classList.add('hidden');
                inputs.forEach(input => {
                    input.disabled = true;
                    input.classList.remove('input');
                });
                fileInputs.forEach(input => {
                    input.classList.add('hidden');
                })
                documentViewBtn.forEach(btn => {
                    btn.classList.remove('hidden');
                })

            }
        </script>
    </div>

@endsection
