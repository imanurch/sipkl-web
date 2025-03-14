<div class="card">
    {{-- <div class="card-icon {{ $class ?? '' }}">
        {{ $slot }}
    </div> --}}
    <div>
        {{-- <h6 class="text-xs px-2 py-1 text-success-500">{{ $title }}</h6> --}}
        <h6 class="card-title">{{ $title }}</h6>
        <p class="card-data">{{ $data ?? '-' }}</p>
    </div>
</div>