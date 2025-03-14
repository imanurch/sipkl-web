<div x-data="{ option: false, selected: '{{ $defaultSelected ?? 'Pilih Opsi' }}', valueSelected: '' }">
    <button @click.prevent="option=!option"
        class="border border-neutral-200 rounded py-2 px-3 text-xs-reguler focus:outline-none focus:border-brand-600 flex justify-between place-items-center w-full">
        <span x-text="selected" class="truncate"
            :class="selected == 'Pilih Opsi' || selected=='{{ $defaultSelected }}' ? 'text-neutral-300' : 'text-neutral-800'"></span>
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
            <path d="M5 7.5L10 12.5L15 7.5" stroke="#667085" stroke-width="0.933333" stroke-linecap="round"
                stroke-linejoin="round" />
        </svg>
    </button>
    <div x-show="option" @click.away="option=false" class="w-full">
        <ul class="border border-brand-600 bg-neutral-0 rounded py-2 my-2 max-h-32 w-full overflow-auto">
            {{ $option ?? '' }}
        </ul>
    </div>
    <input type="hidden" name="{{ $optionName }}" x-model="valueSelected">
</div>
