@props([
    'key',
    'label',
    'selectedKey' => ''
])

<option value="{{ $key }}" @selected($key == $selectedKey)>
    {{ $label }}
</option>
