{{-- <form class="form" method="POST" :action="`${window.location.pathname}${modalAction=='isEdit' ? `-update/${id}` : (modalAction=='isDelete' ? `-delete/${id}` : '.store')}`" > --}}
<form class="form" method="POST" id="modalForm" @click.away="modalAction=null" action="{{ $action ?? '' }}" enctype="multipart/form-data">
    <div class="form-header">
        @csrf
        <template x-if="modalAction=='isEdit'">
            @method('PATCH')
        </template>
        {{-- <template x-if="modalAction=='isEditStatus'">
            @method('GET')
        </template> --}}
        <template x-if="modalAction=='isDelete'">
            @method('DELETE')
        </template>
        <h3><span x-text="modalAction=='isAdd' ? 'Tambah' : (modalAction=='isEdit' ? 'Ubah' : (modalAction=='isDelete' ? 'Hapus' : ''))"></span> {{ $formTitle }}</h3>
        <svg @click="modalAction=null,selected='Pilih Opsi'" class="cursor-pointer" xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28" fill="none">
            <path d="M19.8333 8.16675L8.16663 19.8334M8.16663 8.16675L19.8333 19.8334" stroke="#525A6A" stroke-width="1.03704" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
    </div>
    <div class="form-body">
        {{ $formBody }}           
    </div>
    <div class="form-footer">
        <button @click="modalAction=null" class="btn btn-sm" :class="modalAction=='isView' ? 'btn-success-fill' : 'btn-error-fill'">
            <span x-text="modalAction=='isView' ? 'Kembali' : 'Batalkan'"></span>
        </button>
        <button x-show="modalAction!='isView'" type="submit" class="btn btn-success-fill btn-sm">
            <span x-text="modalAction=='isAdd' ? 'Tambah' : (modalAction=='isEdit' || modalAction=='isEditStatus' ? 'Ubah' : (modalAction=='isDelete' ? 'Hapus' : (modalAction=='isImport' ? 'Impor' : '')))"></span>
        </button>
    </div>
</form>

{{-- :action="isEdit ? '/admin-update' : (isDelete ? '/admin-delete' : '/admin-store')" --}}

    {{-- REAL --}}
    {{-- <div class="form-header">
        @csrf
        <h3><span x-text="isShow ? '' : (isEdit ? 'Ubah' : (isDelete ? 'Hapus' : 'Tambah'))"></span> {{ $formTitle }}</h3>
        <svg @click="modal=false;importModal=false;isShow=false;isEdit=false;isDelete=false" class="cursor-pointer" xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28" fill="none">
            <path d="M19.8333 8.16675L8.16663 19.8334M8.16663 8.16675L19.8333 19.8334" stroke="#525A6A" stroke-width="1.03704" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
    </div>
    <div class="form-body">
        {{ $formBody }}           
    </div>
    <div class="form-footer">
        <button @click="modal=false;importModal=false;isShow=false;isEdit=false;isDelete=false;selected='Pilih Opsi';" class="btn btn-error-fill btn-sm">
            <span>Batalkan</span>
        </button>
        <button type="submit" class="btn btn-success-fill btn-sm">
            <span x-text="isEdit ? 'Ubah' : (isDelete ? 'Hapus' : (isImport ? 'Impor' : 'Simpan'))"></span>
        </button>
    </div> --}}
    {{-- END REAL --}}