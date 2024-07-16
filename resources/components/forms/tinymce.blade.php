@props(['title' => null, 'value' => '', 'options' => [], 'required' => false, 'id' => uniqid('tinymce-')])

@if ($title)
    <label for="{{ $id }}" class="form-label">
        {{ $title }}

        @if ($required)
            <span class="text-danger">*</span>
        @endif
    </label>
@endif

<x-components::forms.textarea :value="$value" :id="$id" :autosize="false" :required="false" {{ $attributes->only('name') }} />

@push('scripts')
    <script>
        initTinyMCE('#{{ $id }}', @json($options));
    </script>
@endpush
