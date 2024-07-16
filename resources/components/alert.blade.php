@props(['title', 'description' => null, 'type' => 'success', 'icon' => null, 'dismissable' => false])

<div {{ $attributes->merge(['class' => 'alert alert-' . $type . ($dismissable ? ' alert-dismissible' : '')]) }} role="alert">
    <div class="d-flex">
        @if ($icon)
            <x-components::icon :icon="$icon" class="icon alert-icon" />
        @endif

        <div>
            @if ($description)
                <h4 class="alert-title">{{ $title }}</h4>
                <div class="text-secondary">{{ $description }}</div>
            @else
                {{ $title }}
            @endif
        </div>
    </div>

    @if ($dismissable)
        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
    @endif
</div>
