<div class="space-y-3" x-data={logbookContent:true}>
    <div class="flex space-x-3 place-items-center">
        <span class="text-xs-reguler">Bulan {{ $month }}</span>
        <svg class="cursor-pointer" @click="logbookContent=!logbookContent" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
            <path :d="logbookContent ? 'M4 6L8 10L12 6' : 'M6 12L10 8L6 4'" stroke="#175CD3" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </div>
    <div x-show="logbookContent" class="space-y-6">
        {{ $logbookContent ?? ''}}
    </div>
</div>