<td>
    <div class="flex justify-items-center justify-center space-x-2">
        <button @click="modalConfirm='accept';id='{{ $id }}'" class="bg-icon-success rounded-full p-2 stroke-neutral-0 w-fit">
            <svg class="size-3" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"
                fill="none">
                <path d="M12.9719 4.16846L5.94737 11.193L2.75439 8.00003" stroke="white" stroke-width="2.18947"
                    stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </button>
        <button @click="modalConfirm='reject';id='{{ $id }}'" class="bg-icon-error rounded-full p-2 stroke-neutral-0 w-fit">
            <svg class="size-3" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"
                fill="none">
                <path d="M11.3298 4.80713L4.94385 11.1931M4.94385 4.80713L11.3298 11.1931" stroke="white"
                    stroke-width="2.18947" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </button>
    </div>
</td>
