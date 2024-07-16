@php
    foreach (['success', 'error', 'warning', 'info'] as $status) {
        if (session()->has($status)) {
            $color = $status == 'error' ? 'danger' : $status;
            break;
        }
    }
@endphp

@if (session()->has($status))
    <div {{ $attributes->merge(['class' => 'alert alert-dismissible alert-' . $color]) }}>
        @if (is_array(session($status)))
            <ul class="mb-0">
                @foreach (session($status) as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        @else
            {{ session($status) }}
        @endif

        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
    </div>
@endif
