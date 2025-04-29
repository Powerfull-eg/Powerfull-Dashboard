@props(['title' => null, 'id' => uniqid('checkbox-'),'onchange' => null, 'checked' => false])

@php
    $name = $attributes->get('name') ?? false;
    $required = $attributes->get('required') ?? false;
@endphp
    <div class="d-flex align-items-center gap-2">

        @if ($title)
        <span>{{ $title }}</span>
        @endif
        
        @if ($required)
        <span class="text-danger">*</span>
        @endif
        
        <label class="switch">
            <input type="checkbox" name="{{ $name }}" id="{{ $id }}" onchange="{{ $onchange }} " {{ $checked ? 'checked' : '' }} class="form-check-input">
            <span class="slider round"></span>
        </label>
    </div>

@if ($name)
    <x-components::forms.invalid-feedback :name="$name" />
@endif

@push('styles')
<style>
    /* The switch - the box around the slider */
    label.switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
    }

    /* Hide default HTML checkbox */
    label.switch input {
    opacity: 0;
    width: 0;
    height: 0;
    }

    /* The slider */
    label.switch span.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: .4s;
    transition: .4s;
    }

    label.switch span.slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
    }

    label.switch input[type=checkbox]:checked + span.slider {
    background-color: #2196F3;
    }

    label.switch input[type=checkbox]:focus + span.slider {
    box-shadow: 0 0 1px #2196F3;
    }

    label.switch input[type=checkbox]:checked + span.slider:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
    }

    /* Rounded sliders */
    label.switch span.slider.round {
    border-radius: 34px;
    }

    label.switch span.slider.round:before {
    border-radius: 50%;
    }
</style>
@endpush

@push('scripts')
<script>
    const checkbox = document.querySelector('#{{ $id }}');

    checkbox.addEventListener('change', () => {
        checkbox.checked ? checkbox.value = 1 : checkbox.value = 0;
    });
</script>
@endpush