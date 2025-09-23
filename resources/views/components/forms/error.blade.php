@props([
    'name',
    'id',
    ])

@php
    $id = $id ?? \Illuminate\Support\Str::camel($name) . 'Feedback';
@endphp

@error($name)
<span id="{{ $id }}" {{ $attributes->class(['error invalid-feedback']) }}>
    {{ $message }}
</span>
@enderror
