<div class="border border-neutral-100">
    <div
        class="flex flex-col md:flex-row md:justify-between md:place-items-center space-y-3 md:space-y-0 border-b border-neutral-100 py-4 px-4">
        <h3 class="text-sm-semibold">{{ $tableTitle ?? '' }}</h3>
        <div class="flex flex-wrap gap-1">
            {{-- search --}}
            <form action="{{ $filterActionForm ?? '' }}" method="GET" class="m-0">
                <div class="flex">
                    <div class="relative">
                        <input class="input w-full rounded-e-[0] border-e-0" type="text" placeholder="Cari Disini ..."
                            name="{{ $mainSearchName ?? 'searchKeyword' }}" value="{{ $value ?? '' }}">
                    </div>
                    {{ $mainSearchAddition ?? '' }}
                    <button class="btn btn-xs btn-default-fill rounded-s-[0]">
                        <svg class="" width="18" height="18" viewBox="0 0 28 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <circle cx="11.5" cy="11.5" r=" 9.5" stroke="" stroke-width="2" />
                            <path d="M18.5 18.5L22 22" stroke="" stroke-width="2" stroke-linecap="round" />
                        </svg>
                    </button>
                </div>
            </form>
            {{-- filter --}}
            <div x-data="{ filter: false }" class="relative space-y-2 {{ $classFilter ?? '' }}">
                <button @click.prevent="filter=!filter" class="btn btn-xs btn-default-fill h-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14"
                        fill="none">
                        <path
                            d="M1.97508 3.30567C1.53389 2.81257 1.31329 2.56602 1.30496 2.35648C1.29774 2.17446 1.37596 1.9995 1.51643 1.88352C1.67814 1.75 2.00897 1.75 2.67064 1.75H11.329C11.9907 1.75 12.3215 1.75 12.4832 1.88352C12.6237 1.9995 12.7019 2.17446 12.6947 2.35648C12.6864 2.56602 12.4658 2.81257 12.0246 3.30568L8.69594 7.02592C8.60799 7.12421 8.56402 7.17336 8.53266 7.22929C8.50485 7.2789 8.48445 7.33231 8.47209 7.38782C8.45816 7.45042 8.45816 7.51636 8.45816 7.64826V10.7674C8.45816 10.8815 8.45816 10.9385 8.43977 10.9878C8.42351 11.0314 8.39707 11.0704 8.36265 11.1017C8.32369 11.1371 8.27073 11.1583 8.16481 11.2007L6.18148 11.994C5.96708 12.0798 5.85988 12.1226 5.77382 12.1048C5.69857 12.0891 5.63253 12.0444 5.59006 11.9804C5.5415 11.9071 5.5415 11.7916 5.5415 11.5607V7.64826C5.5415 7.51636 5.5415 7.45042 5.52757 7.38782C5.51521 7.33231 5.49481 7.2789 5.467 7.22929C5.43565 7.17336 5.39167 7.12421 5.30372 7.02592L1.97508 3.30567Z"
                            stroke="" stroke-width="0.933333" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span>Filter</span>
                </button>
                <form x-show="filter" @click.away="filter=false" action="{{ $filterActionForm ?? '' }}" method="GET"
                    class="absolute bg-neutral-0 end-0 shadow-lg">
                    <div class="w-64 space-y-2 border border-neutral-100 rounded">
                        <div class="flex justify-between place-items-center border-b border-neutral-200 py-3 px-4">
                            <h6 class="text-xs-medium">Filter</h6>
                            <svg class="size-5 cursor-pointer" @click="filter=false" class="cursor-pointer"
                                xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28"
                                fill="none">
                                <path d="M19.8333 8.16675L8.16663 19.8334M8.16663 8.16675L19.8333 19.8334"
                                    stroke="#525A6A" stroke-width="1.03704" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div class="px-4 space-y-2 w-full">
                            {{ $filter ?? '' }}
                        </div>
                        <div class="flex justify-end space-x-2 border-t border-neutral-200 py-3 px-4">
                            <a href="{{ $filterActionForm ?? '' }}" class="btn btn-error-fill btn-xs">
                                <span>Hapus Filter</span>
                            </a>
                            <button type="submit" class="btn btn-success-fill btn-xs">
                                <span>Terapkan</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            {{-- add & import --}}
            {{ $btnAdd ?? '' }}
        </div>
    </div>
    <div class="px-4 py-4 space-y-3">
        {{ $addition ?? '' }}

        {{-- table --}}
        <div class="border border-neutral-50 rounded">
            <div class="overflow-x-auto overflow-hidden">
                <table class="table">
                    <thead>
                        <tr>
                            {{ $tHeader ?? '' }}
                        </tr>
                    </thead>
                    <tbody>
                        {{ $tBody ?? '' }}
                    </tbody>
                </table>
            </div>
        </div>
        {{-- pagination --}}
        {{ $pagination ?? '' }}
    </div>
</div>
