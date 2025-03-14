<div class="border border-neutral-100">
    <div class="flex flex-col md:flex-row md:justify-between md:place-items-center space-y-3 md:space-y-0 border-b border-neutral-100 py-4 px-4">
        <h3 class="text-sm-semibold">{{ $tableTitle ?? '' }}</h3>
        <div class="flex space-x-1">
            {{-- search --}}
            <form action="{{ $filterActionForm ?? '' }}" method="GET" class="w-full m-0">
                <div class="flex">
                    <div class="relative w-full">
                        <input class="input w-full rounded-e-[0] border-e-0" type="text" placeholder="Cari Disini ..."
                            name="searchKeyword" value="{{ $value ?? '' }}">
                    </div>
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
            <div x-data="{ filter: false }" class="relative space-y-2">
                <button @click.prevent="filter=!filter" class="btn btn-xs btn-default-fill">Filter</button>
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
                        <div class="px-4 gap-2 w-full">
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
