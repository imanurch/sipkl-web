<div class="card">
    <div class="card-icon {{ $class ?? '' }}">
        {{ $slot }}
    </div>
    <div>
        <h6 class="card-title">{{ $title }}</h6>
        <p class="card-data">{{ $data ?? '-' }}</p>
    </div>
</div>