<div x-data="{panduan:false}" class="space-y-2">
    <button @click="panduan=!panduan" class="btn btn-xs btn-default-outline">
        <svg class="stroke-brand-800" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 18 18" fill="none">
            <path
                d="M9 12V9M9 6H9.0075M16.5 9C16.5 13.1421 13.1421 16.5 9 16.5C4.85786 16.5 1.5 13.1421 1.5 9C1.5 4.85786 4.85786 1.5 9 1.5C13.1421 1.5 16.5 4.85786 16.5 9Z"
                stroke-width="1" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        <span>Lihat Panduan</span>
    </button>
    <div x-show="panduan" class="guide border border-neutral-100 rounded py-5 px-8 space-y-2">
        <div class="flex place-items-center space-x-1">
            <h3 class="text-xs-semibold">Panduan {{ $guideTitle }}</h3>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 18 18" fill="none">
                <path
                    d="M9 12V9M9 6H9.0075M16.5 9C16.5 13.1421 13.1421 16.5 9 16.5C4.85786 16.5 1.5 13.1421 1.5 9C1.5 4.85786 4.85786 1.5 9 1.5C13.1421 1.5 16.5 4.85786 16.5 9Z"
                    stroke="#1F2228" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
        <ul class="list-disc text-xs-reguler px-5 space-y-1">
            {{ $slot }}
        </ul>
    </div>
</div>

{{-- <div class="guide border border-neutral-100 rounded py-5 px-8 space-y-2">
    <div class="flex place-items-center space-x-1">
        <h3 class="text-xs-semibold">Panduan {{ $guideTitle }}</h3>
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 18 18" fill="none">
            <path
                d="M9 12V9M9 6H9.0075M16.5 9C16.5 13.1421 13.1421 16.5 9 16.5C4.85786 16.5 1.5 13.1421 1.5 9C1.5 4.85786 4.85786 1.5 9 1.5C13.1421 1.5 16.5 4.85786 16.5 9Z"
                stroke="#1F2228" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </div>
    <ul class="list-disc text-xs-reguler px-5 space-y-1">
        {{ $slot }}
    </ul>
</div> --}}
