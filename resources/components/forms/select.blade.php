@props(['title' => null, 'options' => [], 'selected' => null, 'tom' => true, 'id' => uniqid('select-'), 'placeholder' => null])

@php
    $name = $attributes->get('name') ?? false;
    $required = $attributes->get('required') ?? false;

    if ($name && str_ends_with($name, '[]') && $attributes->has('multiple') === false) {
        $attributes = $attributes->merge(['multiple' => true]);
    }
@endphp

@if ($title)
    <label for="{{ $id }}" class="form-label">
        {{ $title }}

        @if ($required)
            <span class="text-danger">*</span>
        @endif
    </label>
@endif

<select id="{{ $id }}" {{ $attributes->merge(['class' => 'form-select']) }}>

    @foreach ($options as $key => $value)
        <option value="{{ $key }}" @selected($key == $selected)>{{ $value }}</option>
    @endforeach

    {{ $slot }}
</select>

@if ($name)
    <x-components::forms.invalid-feedback :name="$name" />
@endif

@pushIf($tom, 'scripts')
    <script>
        (() => {
            const instance = new TomSelect('#{{ $id }}', {
                create: false,
                dropdownParent: 'body',
                copyClassesToDropdown: false,
                placeholder: '{{ $placeholder ?? __('Select an option') }}',
            });

            $('#{{ $id }}').data('tom', instance);
        })();
    </script>
@endPushIf
