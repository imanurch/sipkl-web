<div class="card">
    <div class="card-icon {{ $class ?? '' }}">
        {{ $slot }}
        {{-- @if($icon == '' && $class == 'bg-icon-success')
            <svg class="" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                <path d="M16.6667 5L7.50001 14.1667L3.33334 10" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        @elseif($icon == 'bg-icon-error')
            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                <path d="M17.5 7L7.5 17M7.5 7L17.5 17" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        @elseif($icon == 'bg-icon-warning') --}}
    </div>
    <div>
        <h6 class="card-title">{{ $title }}</h6>
        <p class="card-data">{{ $data ?? '-' }}</p>
    </div>
</div>