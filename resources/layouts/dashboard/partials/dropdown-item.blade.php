@props(['item'])

<a @class(['dropdown-item', 'active' => $item->active]) href="{{ $item->url }}">
    {{ $item->title }}
</a>
