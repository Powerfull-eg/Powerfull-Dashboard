<x-layouts::scaffold {{ $attributes }}>
    {{ $slot }}

    @include('components.google-analytics')
    @include('components.facebook-pixel')
</x-layouts::scaffold>
