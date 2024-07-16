@props(['name'])

@php
    $key = $name;

    // If the name ends with [], it's an array
    // so we need to remove the brackets
    if (str_ends_with($name, '[]')) {
        $key = substr($name, 0, -2);
    }

    // Replace the brackets with dots
    // so we can get the error bag
    $name = str_replace(['[', ']'], ['.', ''], $name);
@endphp

@error($key)
    <div class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('[name="{{ $name }}"]').addClass('is-invalid');
            })
        </script>
    @endpush
@enderror
