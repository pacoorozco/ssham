@props([
    'id',
    'name',
     'value'
     ])

@php
    $id = $id ?? \Illuminate\Support\Str::camel($name);
@endphp

<input id="{{ $id }}" name="{{ $name }}" type="hidden" value="{{ $value }}">
