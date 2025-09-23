@props([
    'method' => 'POST',
    'action',
    'hasFiles' => false,
])

@php
    $method = strtoupper($method)
@endphp

<form
        method="{{ $method !== 'GET' ? 'POST' : 'GET' }}"
        action="{{ $action }}"
        {!! $hasFiles ? 'enctype="multipart/form-data"' : '' !!}
        {{ $attributes->except(['method', 'action']) }}
>
    @unless(in_array($method, ['HEAD', 'GET', 'OPTIONS']))
        @csrf
    @endunless

    @if(in_array($method, ['PUT', 'PATCH', 'DELETE']))
        @method($method)
    @endif

    {{ $slot }}
</form>
