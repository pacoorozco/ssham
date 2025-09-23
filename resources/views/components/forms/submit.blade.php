@props([
    'type' => 'success',
    'value'
    ])


<button type="submit" {{ $attributes->merge(['class' => 'btn btn-'.$type]) }}>
    {{ $value ?? $slot}}
</button>

