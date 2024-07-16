@props(['title' => null, 'id' => uniqid('date-'), 'options' => [], 'format' => 'YYYY-MM-DD', 'minDate' => null, 'maxDate' => null])

@php
    $name = $attributes->get('name') ?? false;
    $required = $attributes->get('required') ?? false;
@endphp

@if ($title)
    <label for="{{ $id }}" class="form-label">
        {{ $title }}

        @if ($required)
            <span class="text-danger">*</span>
        @endif
    </label>
@endif

<div class="col input-icon">
    <input id="{{ $id }}" {{ $attributes->merge(['class' => 'form-control']) }} />

    <span class="input-icon-addon">
        <x-components::icon icon="ti ti-calendar" />
    </span>

    @if ($name)
        <x-components::forms.invalid-feedback :name="$name" />
    @endif
</div>

@push('scripts')
    <script>
        initLitepicker('#{{ $id }}', {
            format: '{{ $format }}',
            minDate: '{{ $minDate }}',
            maxDate: '{{ $maxDate }}',
            ...@json($options),
        });
    </script>
@endpush
