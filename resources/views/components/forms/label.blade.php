@props([
    'for',
    'value'
])

<label
    {{ $attributes->only(['class']) }}
    for="{{ $for }}"
>
    {{ $value ?? $slot }}

</label>
