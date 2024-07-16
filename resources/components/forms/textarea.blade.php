@props(['title' => null, 'id' => uniqid('textarea-'), 'autosize' => true])

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

<textarea id="{{ $id }}" @if ($autosize) data-bs-toggle="autosize" @endif
    {{ $attributes->merge(['class' => 'form-control']) }}>{{ $slot }}</textarea>

@if ($name)
    <x-components::forms.invalid-feedback :name="$name" />
@endif
