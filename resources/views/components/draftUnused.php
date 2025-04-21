@extends('layouts.app')

@section('content')
{{-- button --}}
<div class="flex space-x-2">
    <button class="btn btn-default-fill btn-xs">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
            <path d="M7.87464 10.1251L15.7496 2.25013M7.97033 10.3712L9.94141 15.4397C10.1151 15.8862 10.2019 16.1094 10.327 16.1746C10.4354 16.2311 10.5646 16.2312 10.6731 16.1748C10.7983 16.1098 10.8854 15.8866 11.0596 15.4403L16.0023 2.77453C16.1595 2.37164 16.2381 2.1702 16.1951 2.04148C16.1578 1.92969 16.0701 1.84197 15.9583 1.80462C15.8296 1.76162 15.6281 1.84023 15.2252 1.99746L2.55943 6.94021C2.11313 7.11438 1.88997 7.20146 1.82494 7.32664C1.76857 7.43516 1.76864 7.56434 1.82515 7.67279C1.89033 7.7979 2.11358 7.88472 2.56009 8.05836L7.62859 10.0294C7.71923 10.0647 7.76455 10.0823 7.80271 10.1095C7.83653 10.1337 7.86611 10.1632 7.89024 10.1971C7.91746 10.2352 7.93508 10.2805 7.97033 10.3712Z" stroke="" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        <span>button</span>
    </button>
    <button class="btn btn-default-outline btn-xs">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
            <path d="M7.87464 10.1251L15.7496 2.25013M7.97033 10.3712L9.94141 15.4397C10.1151 15.8862 10.2019 16.1094 10.327 16.1746C10.4354 16.2311 10.5646 16.2312 10.6731 16.1748C10.7983 16.1098 10.8854 15.8866 11.0596 15.4403L16.0023 2.77453C16.1595 2.37164 16.2381 2.1702 16.1951 2.04148C16.1578 1.92969 16.0701 1.84197 15.9583 1.80462C15.8296 1.76162 15.6281 1.84023 15.2252 1.99746L2.55943 6.94021C2.11313 7.11438 1.88997 7.20146 1.82494 7.32664C1.76857 7.43516 1.76864 7.56434 1.82515 7.67279C1.89033 7.7979 2.11358 7.88472 2.56009 8.05836L7.62859 10.0294C7.71923 10.0647 7.76455 10.0823 7.80271 10.1095C7.83653 10.1337 7.86611 10.1632 7.89024 10.1971C7.91746 10.2352 7.93508 10.2805 7.97033 10.3712Z" stroke="" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <span>button</span>
    </button>
    <button class="btn btn-default-clear btn-xs">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
            <path d="M7.87464 10.1251L15.7496 2.25013M7.97033 10.3712L9.94141 15.4397C10.1151 15.8862 10.2019 16.1094 10.327 16.1746C10.4354 16.2311 10.5646 16.2312 10.6731 16.1748C10.7983 16.1098 10.8854 15.8866 11.0596 15.4403L16.0023 2.77453C16.1595 2.37164 16.2381 2.1702 16.1951 2.04148C16.1578 1.92969 16.0701 1.84197 15.9583 1.80462C15.8296 1.76162 15.6281 1.84023 15.2252 1.99746L2.55943 6.94021C2.11313 7.11438 1.88997 7.20146 1.82494 7.32664C1.76857 7.43516 1.76864 7.56434 1.82515 7.67279C1.89033 7.7979 2.11358 7.88472 2.56009 8.05836L7.62859 10.0294C7.71923 10.0647 7.76455 10.0823 7.80271 10.1095C7.83653 10.1337 7.86611 10.1632 7.89024 10.1971C7.91746 10.2352 7.93508 10.2805 7.97033 10.3712Z" stroke="" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <span>button</span>
    </button>
    <button class="btn btn-success-fill btn-xs">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
            <path d="M7.87464 10.1251L15.7496 2.25013M7.97033 10.3712L9.94141 15.4397C10.1151 15.8862 10.2019 16.1094 10.327 16.1746C10.4354 16.2311 10.5646 16.2312 10.6731 16.1748C10.7983 16.1098 10.8854 15.8866 11.0596 15.4403L16.0023 2.77453C16.1595 2.37164 16.2381 2.1702 16.1951 2.04148C16.1578 1.92969 16.0701 1.84197 15.9583 1.80462C15.8296 1.76162 15.6281 1.84023 15.2252 1.99746L2.55943 6.94021C2.11313 7.11438 1.88997 7.20146 1.82494 7.32664C1.76857 7.43516 1.76864 7.56434 1.82515 7.67279C1.89033 7.7979 2.11358 7.88472 2.56009 8.05836L7.62859 10.0294C7.71923 10.0647 7.76455 10.0823 7.80271 10.1095C7.83653 10.1337 7.86611 10.1632 7.89024 10.1971C7.91746 10.2352 7.93508 10.2805 7.97033 10.3712Z" stroke="" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        <span>button</span>
    </button>
    <button class="btn btn-warning-fill btn-xs">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
            <path d="M7.87464 10.1251L15.7496 2.25013M7.97033 10.3712L9.94141 15.4397C10.1151 15.8862 10.2019 16.1094 10.327 16.1746C10.4354 16.2311 10.5646 16.2312 10.6731 16.1748C10.7983 16.1098 10.8854 15.8866 11.0596 15.4403L16.0023 2.77453C16.1595 2.37164 16.2381 2.1702 16.1951 2.04148C16.1578 1.92969 16.0701 1.84197 15.9583 1.80462C15.8296 1.76162 15.6281 1.84023 15.2252 1.99746L2.55943 6.94021C2.11313 7.11438 1.88997 7.20146 1.82494 7.32664C1.76857 7.43516 1.76864 7.56434 1.82515 7.67279C1.89033 7.7979 2.11358 7.88472 2.56009 8.05836L7.62859 10.0294C7.71923 10.0647 7.76455 10.0823 7.80271 10.1095C7.83653 10.1337 7.86611 10.1632 7.89024 10.1971C7.91746 10.2352 7.93508 10.2805 7.97033 10.3712Z" stroke="" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        <span>button</span>
    </button>
    <button class="btn btn-error-fill btn-xs">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
            <path d="M7.87464 10.1251L15.7496 2.25013M7.97033 10.3712L9.94141 15.4397C10.1151 15.8862 10.2019 16.1094 10.327 16.1746C10.4354 16.2311 10.5646 16.2312 10.6731 16.1748C10.7983 16.1098 10.8854 15.8866 11.0596 15.4403L16.0023 2.77453C16.1595 2.37164 16.2381 2.1702 16.1951 2.04148C16.1578 1.92969 16.0701 1.84197 15.9583 1.80462C15.8296 1.76162 15.6281 1.84023 15.2252 1.99746L2.55943 6.94021C2.11313 7.11438 1.88997 7.20146 1.82494 7.32664C1.76857 7.43516 1.76864 7.56434 1.82515 7.67279C1.89033 7.7979 2.11358 7.88472 2.56009 8.05836L7.62859 10.0294C7.71923 10.0647 7.76455 10.0823 7.80271 10.1095C7.83653 10.1337 7.86611 10.1632 7.89024 10.1971C7.91746 10.2352 7.93508 10.2805 7.97033 10.3712Z" stroke="" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        <span>button</span>
    </button>
</div>

{{-- tag status --}}
<div class="flex space-x-4">
    <div class="tag-status">
        <div class="tag-status-icon bg-icon-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="14" viewBox="0 0 15 14" fill="none">
                <path d="M12.1667 3.5L5.75001 9.91667L2.83334 7" stroke="" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>                        
        </div>
        <span>Disetujui 2</span>
    </div>
    <div class="tag-status">
        <div class="tag-status-icon bg-icon-warning">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="14" viewBox="0 0 15 14" fill="none">
                <path d="M12.1667 3.5L5.75001 9.91667L2.83334 7" stroke="" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>                        
        </div>
        <span>Pending</span>
    </div>
    <div class="tag-status">
        <div class="tag-status-icon bg-icon-error">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="14" viewBox="0 0 15 14" fill="none">
                <path d="M12.1667 3.5L5.75001 9.91667L2.83334 7" stroke="" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>                        
        </div>
        <span>Ditolak</span>
    </div>
    <div class="tag-status">
        <div class="tag-status-icon bg-icon-neutral">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="14" viewBox="0 0 15 14" fill="none">
                <path d="M12.1667 3.5L5.75001 9.91667L2.83334 7" stroke="" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>                        
        </div>
        <span>Belum Diisi</span>
    </div>
</div>

{{-- card --}}
<div class="layout-card">
    <div class="card">
        <div class="card-icon bg-icon-default">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                <path d="M7.87464 10.1251L15.7496 2.25013M7.97033 10.3712L9.94141 15.4397C10.1151 15.8862 10.2019 16.1094 10.327 16.1746C10.4354 16.2311 10.5646 16.2312 10.6731 16.1748C10.7983 16.1098 10.8854 15.8866 11.0596 15.4403L16.0023 2.77453C16.1595 2.37164 16.2381 2.1702 16.1951 2.04148C16.1578 1.92969 16.0701 1.84197 15.9583 1.80462C15.8296 1.76162 15.6281 1.84023 15.2252 1.99746L2.55943 6.94021C2.11313 7.11438 1.88997 7.20146 1.82494 7.32664C1.76857 7.43516 1.76864 7.56434 1.82515 7.67279C1.89033 7.7979 2.11358 7.88472 2.56009 8.05836L7.62859 10.0294C7.71923 10.0647 7.76455 10.0823 7.80271 10.1095C7.83653 10.1337 7.86611 10.1632 7.89024 10.1971C7.91746 10.2352 7.93508 10.2805 7.97033 10.3712Z" stroke="" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div>
            <h6 class="card-title">Default</h6>
            <p class="card-data">120</p>
        </div>
    </div>
    <div class="card">
        <div class="card-icon bg-icon-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                <path d="M7.87464 10.1251L15.7496 2.25013M7.97033 10.3712L9.94141 15.4397C10.1151 15.8862 10.2019 16.1094 10.327 16.1746C10.4354 16.2311 10.5646 16.2312 10.6731 16.1748C10.7983 16.1098 10.8854 15.8866 11.0596 15.4403L16.0023 2.77453C16.1595 2.37164 16.2381 2.1702 16.1951 2.04148C16.1578 1.92969 16.0701 1.84197 15.9583 1.80462C15.8296 1.76162 15.6281 1.84023 15.2252 1.99746L2.55943 6.94021C2.11313 7.11438 1.88997 7.20146 1.82494 7.32664C1.76857 7.43516 1.76864 7.56434 1.82515 7.67279C1.89033 7.7979 2.11358 7.88472 2.56009 8.05836L7.62859 10.0294C7.71923 10.0647 7.76455 10.0823 7.80271 10.1095C7.83653 10.1337 7.86611 10.1632 7.89024 10.1971C7.91746 10.2352 7.93508 10.2805 7.97033 10.3712Z" stroke="" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div>
            <h6 class="card-title">Success</h6>
            <p class="card-data">120</p>
        </div>
    </div>
    <div class="card">
        <div class="card-icon bg-icon-warning">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                <path d="M7.87464 10.1251L15.7496 2.25013M7.97033 10.3712L9.94141 15.4397C10.1151 15.8862 10.2019 16.1094 10.327 16.1746C10.4354 16.2311 10.5646 16.2312 10.6731 16.1748C10.7983 16.1098 10.8854 15.8866 11.0596 15.4403L16.0023 2.77453C16.1595 2.37164 16.2381 2.1702 16.1951 2.04148C16.1578 1.92969 16.0701 1.84197 15.9583 1.80462C15.8296 1.76162 15.6281 1.84023 15.2252 1.99746L2.55943 6.94021C2.11313 7.11438 1.88997 7.20146 1.82494 7.32664C1.76857 7.43516 1.76864 7.56434 1.82515 7.67279C1.89033 7.7979 2.11358 7.88472 2.56009 8.05836L7.62859 10.0294C7.71923 10.0647 7.76455 10.0823 7.80271 10.1095C7.83653 10.1337 7.86611 10.1632 7.89024 10.1971C7.91746 10.2352 7.93508 10.2805 7.97033 10.3712Z" stroke="" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div>
            <h6 class="card-title">Warning</h6>
            <p class="card-data">120</p>
        </div>
    </div>
    <div class="card">
        <div class="card-icon bg-icon-error">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                <path d="M7.87464 10.1251L15.7496 2.25013M7.97033 10.3712L9.94141 15.4397C10.1151 15.8862 10.2019 16.1094 10.327 16.1746C10.4354 16.2311 10.5646 16.2312 10.6731 16.1748C10.7983 16.1098 10.8854 15.8866 11.0596 15.4403L16.0023 2.77453C16.1595 2.37164 16.2381 2.1702 16.1951 2.04148C16.1578 1.92969 16.0701 1.84197 15.9583 1.80462C15.8296 1.76162 15.6281 1.84023 15.2252 1.99746L2.55943 6.94021C2.11313 7.11438 1.88997 7.20146 1.82494 7.32664C1.76857 7.43516 1.76864 7.56434 1.82515 7.67279C1.89033 7.7979 2.11358 7.88472 2.56009 8.05836L7.62859 10.0294C7.71923 10.0647 7.76455 10.0823 7.80271 10.1095C7.83653 10.1337 7.86611 10.1632 7.89024 10.1971C7.91746 10.2352 7.93508 10.2805 7.97033 10.3712Z" stroke="" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div>
            <h6 class="card-title">Error</h6>
            <p class="card-data">120</p>
        </div>
    </div>
</div>

{{-- guide --}}
<div class="guide border border-neutral-200 rounded py-5 px-8">
    <div class="flex place-items-center space-x-1">
        <h3 class="text-sm-semibold">Panduan Pendaftaran</h3>
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 18 18" fill="none">
            <path d="M9 12V9M9 6H9.0075M16.5 9C16.5 13.1421 13.1421 16.5 9 16.5C4.85786 16.5 1.5 13.1421 1.5 9C1.5 4.85786 4.85786 1.5 9 1.5C13.1421 1.5 16.5 4.85786 16.5 9Z" stroke="#1F2228" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
    </div>
    <ul class="list-disc text-xs-reguler px-5">
        <li>Panduan 1</li>
        <li>Panduan 2</li>
        <li>Panduan 3</li>
    </ul>
</div>

{{-- registration step --}}
<div class="flex space-x-4">
    <div class="registration-step step-active">
        <span >1</span>
        <h6>Pilih Lokasi PKL</h6>
    </div>
    <div class="registration-step step-inactive">
        <span >2</span>
        <h6>Isi Data Anggota Kelompok</h6>
    </div>
    <div class="registration-step step-inactive">
        <span >3</span>
        <h6>Isi Data Pengajuan PKL</h6>
    </div>
    <div class="registration-step step-inactive">
        <span >4</span>
        <h6>Lengkapi Berkas Administrasi</h6>
    </div>
    <div class="registration-step step-inactive">
        <span >5</span>
        <h6>Pendaftaran Diterima</h6>
    </div>
</div>

{{-- table --}}
<div class="border border-neutral-200 rounded p-5">
    <table>
        <thead>
            <tr class="t-header-row">
                <th class="t-header-cell">Header 1</th>
                <th class="t-header-cell">Header 2</th>
                <th class="t-header-cell">Header 2</th>
                <th class="t-header-cell">Header 2</th>
            </tr>
        </thead>
        <tbody>
            <tr class="t-body-row">
                <td class="t-body-cell">Cell 1</td>
                <td class="t-body-cell text-center">Cell 1</td>
                <td class="t-body-cell justify-center flex place-items-center">
                    <div class="tag-status-icon bg-icon-success">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="14" viewBox="0 0 15 14" fill="none">
                            <path d="M12.1667 3.5L5.75001 9.91667L2.83334 7" stroke="" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>                        
                    </div>
                    <span>Cell 2</span>
                </td>
                <td class="t-body-cell text-center">Cell 1</td>
            </tr>
        </tbody>
    </table>
</div>

{{-- form --}}
{{-- <div class="form">
    <div class="form-header">
        <h3>Title</h3>
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28" fill="none">
            <path d="M19.8333 8.16675L8.16663 19.8334M8.16663 8.16675L19.8333 19.8334" stroke="#525A6A" stroke-width="1.03704" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
    </div>
    <div class="form-body">
        <div class="input-group">
            <label class="input-label" for="">Label</label>
            <input class="input" type="text" placeholder="Input Text">    
        </div>
        <div class="input-group">
            <label class="input-label" for="">Label</label>
            <textarea class="input" name="" id="" cols="30" rows="5" placeholder="Input Text"></textarea>    
        </div>
        <div class="input-group">
            <label class="input-label" for="">Label</label>
            <div x-data="{option: false}">
                <button @click="option=!option" class="input input-select">
                    <span>Select</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M5 7.5L10 12.5L15 7.5" stroke="#667085" stroke-width="0.933333" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                </button>
                <div x-show="option" style="display: none;">
                    <ul class="border border-brand-600 rounded py-2 my-2 max-h-32 overflow-auto">
                        <li class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">Option 1</li>
                        <li class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">Option 2</li>
                        <li class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">Option 3</li>
                        <li class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">Option 4</li>
                        <li class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">Option 5</li>
                        <li class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">Option 6</li>
                        <li class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">Option 7</li>
                        <li class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">Option 8</li>
                        <li class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">Option 9</li>
                        <li class="text-xs-reguler px-4 py-2 hover:bg-brand-600 hover:text-neutral-0 hover:text-xs-medium">Option 10</li>
                    </ul>
                </div>
            </div>
        </div> 

        <div class="input-group">
            <label class="input-label" for="">Label</label>
            <button class="btn btn-default-fill btn-xs">                
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">                    
                    <path d="M12.25 8.75V9.45C12.25 10.4301 12.25 10.9201 12.0593 11.2945C11.8915 11.6238 11.6238 11.8915 11.2945 12.0593C10.9201 12.25 10.4301 12.25 9.45 12.25H4.55C3.56991 12.25 3.07986 12.25 2.70552 12.0593C2.37623 11.8915 2.10852 11.6238 1.94074 11.2945C1.75 10.9201 1.75 10.4301 1.75 9.45V8.75M9.91667 4.66667L7 1.75M7 1.75L4.08333 4.66667M7 1.75V8.75" stroke="white" stroke-width="1.03704" stroke-linecap="round" stroke-linejoin="round"/>                   
                </svg>
                <span>Unggah</span>
            </button>
        </div>    
        <div class="input-group">
            <label class="input-label" for="">Label</label>
            <button class="btn btn-default-fill btn-xs">                
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                    <path d="M12.25 8.75V9.45C12.25 10.4301 12.25 10.9201 12.0593 11.2945C11.8915 11.6238 11.6238 11.8915 11.2945 12.0593C10.9201 12.25 10.4301 12.25 9.45 12.25H4.55C3.56991 12.25 3.07986 12.25 2.70552 12.0593C2.37623 11.8915 2.10852 11.6238 1.94074 11.2945C1.75 10.9201 1.75 10.4301 1.75 9.45V8.75M9.91667 5.83333L7 8.75M7 8.75L4.08333 5.83333M7 8.75V1.75" stroke="" stroke-width="1.03704" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>Unduh</span>
            </button>
        </div>     
        <div class="input-group">
            <label class="input-label" for="">Label</label>
            <div class="space-y-2">
                <div class="checkbox-option">
                    <input class="checkbox" type="checkbox">
                    <span>Option 1</span>
                </div>
                <div class="checkbox-option">
                    <input class="checkbox" type="checkbox">
                    <span>Option 2</span>
                </div>
                <div class="checkbox-option">
                    <input class="checkbox" type="checkbox">
                    <span>Option 3</span>
                </div>
            </div>
        </div>            
    </div>
    <div class="form-footer">
        <button class="btn btn-error-fill btn-sm">
            <span>Batalkan</span>
        </button>
        <button class="btn btn-success-fill btn-sm">
            <span>Simpan</span>
        </button>
    </div>
</div> --}}
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

{{-- <div>
    <label id="listbox-label" class="block text-sm/6 font-medium text-gray-900">Assigned to</label>
    <div class="relative mt-2">
      <button type="button" class="grid w-full cursor-default grid-cols-1 rounded-md bg-white py-1.5 pl-3 pr-2 text-left text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" aria-haspopup="listbox" aria-expanded="true" aria-labelledby="listbox-label">
        <span class="col-start-1 row-start-1 flex items-center gap-3 pr-6">
          <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" class="size-5 shrink-0 rounded-full">
          <span class="block truncate">Tom Cook</span>
        </span>
        <svg class="col-start-1 row-start-1 size-5 self-center justify-self-end text-gray-500 sm:size-4" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" data-slot="icon">
          <path fill-rule="evenodd" d="M5.22 10.22a.75.75 0 0 1 1.06 0L8 11.94l1.72-1.72a.75.75 0 1 1 1.06 1.06l-2.25 2.25a.75.75 0 0 1-1.06 0l-2.25-2.25a.75.75 0 0 1 0-1.06ZM10.78 5.78a.75.75 0 0 1-1.06 0L8 4.06 6.28 5.78a.75.75 0 0 1-1.06-1.06l2.25-2.25a.75.75 0 0 1 1.06 0l2.25 2.25a.75.75 0 0 1 0 1.06Z" clip-rule="evenodd" />
        </svg>
      </button>
  
      <!--
        Select popover, show/hide based on select state.
  
        Entering: ""
          From: ""
          To: ""
        Leaving: "transition ease-in duration-100"
          From: "opacity-100"
          To: "opacity-0"
      -->
      <ul class="absolute z-10 mt-1 max-h-56 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm" tabindex="-1" role="listbox" aria-labelledby="listbox-label" aria-activedescendant="listbox-option-3">
        <!--
          Select option, manage highlight styles based on mouseenter/mouseleave and keyboard navigation.
  
          Highlighted: "bg-indigo-600 text-white outline-none", Not Highlighted: "text-gray-900"
        -->
        <li class="relative cursor-default select-none py-2 pl-3 pr-9 text-gray-900" id="listbox-option-0" role="option">
          <div class="flex items-center">
            <img src="https://images.unsplash.com/photo-1491528323818-fdd1faba62cc?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" class="size-5 shrink-0 rounded-full">
            <!-- Selected: "font-semibold", Not Selected: "font-normal" -->
            <span class="ml-3 block truncate font-normal">Wade Cooper</span>
          </div>
  
          <!--
            Checkmark, only display for selected option.
  
            Highlighted: "text-white", Not Highlighted: "text-indigo-600"
          -->
          <span class="absolute inset-y-0 right-0 flex items-center pr-4 text-indigo-600">
            <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
              <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
            </svg>
          </span>
        </li>
  
        <!-- More items... -->
      </ul>
    </div>
  </div>

  <div class="relative inline-block text-left">
    <div>
      <button type="button" class="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50" id="menu-button" aria-expanded="true" aria-haspopup="true">
        Options
        <svg class="-mr-1 size-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
          <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
        </svg>
      </button>
    </div>
  
    <!--
      Dropdown menu, show/hide based on menu state.
  
      Entering: "transition ease-out duration-100"
        From: "transform opacity-0 scale-95"
        To: "transform opacity-100 scale-100"
      Leaving: "transition ease-in duration-75"
        From: "transform opacity-100 scale-100"
        To: "transform opacity-0 scale-95"
    -->
    
    <div class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
      <div class="py-1" role="none">
        <!-- Active: "bg-gray-100 text-gray-900 outline-none", Not Active: "text-gray-700" -->
        <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="menu-item-0">Account settings</a>
        <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="menu-item-1">Support</a>
        <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="menu-item-2">License</a>
        <form method="POST" action="#" role="none">
          <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-gray-700" role="menuitem" tabindex="-1" id="menu-item-3">Sign out</button>
        </form>
      </div>
    </div>
  </div>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
const menuButton = document.getElementById("menu-button");
const dropdownMenu = document.querySelector("[role='menu']");

function toggleDropdown() {
const isExpanded = menuButton.getAttribute("aria-expanded") === "true";
menuButton.setAttribute("aria-expanded", !isExpanded);
dropdownMenu.classList.toggle("hidden");
}

function closeDropdown(event) {
if (
    !menuButton.contains(event.target) &&
    !dropdownMenu.contains(event.target)
) {
    menuButton.setAttribute("aria-expanded", "false");
    dropdownMenu.classList.add("hidden");
}
}

menuButton.addEventListener("click", toggleDropdown);
document.addEventListener("click", closeDropdown);
});
  </script> --}}

@endsection