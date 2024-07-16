@props(['user', 'size' => 'sm'])

<span @style('background-image: url(' . $user->profile_picture . ')') {{ $attributes->merge(['class' => 'avatar avatar-' . $size]) }}>
    {{ $user->profile_picture ? '' : mb_substr($user->name, 0, 1) }}
</span>
