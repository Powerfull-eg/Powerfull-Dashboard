@props(['item'])

<li @class(['nav-item', 'active' => $item->active])>
    <a class="nav-link" href="{{ $item->url }}">
        @if ($item->icon)
            <span class="nav-link-icon"><i class="{{ $item->icon }}"></i></span>
        @endif

        <span class="nav-link-title me-2">{{ $item->title }}</span>
    </a>
</li>
