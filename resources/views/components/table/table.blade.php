<div class="space-y-3">
    <div class="space-y-2">
        <div class="space-y-2 sm:space-y-0 sm:flex sm:justify-between sm:place-items-center">
            <h3 class="text-sm-semibold">{{ $tableTitle ?? ''}}</h3>
            <div class="flex space-x-1">
                {{ $btnAdd ?? '' }}
            </div>
        </div>
        <form action="{{ $filterActionForm ?? '' }}" method="GET">
            <div class="w-full flex flex-col sm:flex-row gap-3 place-items-end px-4 py-3 border border-neutral-100 rounded">
                <div class="flex flex-col sm:flex-row gap-2 w-full">
                    {{ $filter ?? '' }}
                </div>
                <div class="flex space-x-2">
                    <button type="submit" class="btn btn-success-fill btn-xs">
                        <span>Terapkan</span>
                    </button>
                    <a href="{{ $filterActionForm ?? '' }}" class="btn btn-error-fill btn-xs">
                        <span>Hapus</span>
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{ $addition ?? '' }}

    {{-- table --}}
    <div class="border border-neutral-200 rounded px-5">
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
