@props(['icon'])

@if (str_starts_with($icon, '<'))
    {!! $icon !!}
@else
    <i {{ $attributes->merge(['class' => $icon]) }}></i>
@endif
