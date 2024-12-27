@props(['item'])

<li class="ms-2">
    <div class="fs-3 fw-bold my-2">
        @if ($item->icon)
            <span class="nav-link-icon"><i class="{{ $item->icon }}"></i></span>
        @endif

        {{ $item->title }}
    </div>

    <div class="ms-3">
        @foreach ($item->children as $child)
            @include('layouts.dashboard.partials.dropdown-item', ['item' => $child])
        @endforeach
    </div>
</li>
