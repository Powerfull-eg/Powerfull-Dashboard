@props(['items'])

@foreach ($items as $item)
    @if (isset($item->children) && count($item->children) > 0)
    <div>
        @include('layouts.dashboard.partials.dropdown', ['item' => $item])
    </div>
    @else
        <div class="fw-bold fs-3">
            @include('layouts.dashboard.partials.item', ['item' => $item])
        </div>   
    @endif
@endforeach
