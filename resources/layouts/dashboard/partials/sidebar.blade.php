@props(['items'])

@foreach ($items as $item)
    @if (isset($item->children) && count($item->children) > 0)
        @include('layouts.dashboard.partials.dropdown', ['item' => $item])
    @else
        @include('layouts.dashboard.partials.item', ['item' => $item])
    @endif
@endforeach
