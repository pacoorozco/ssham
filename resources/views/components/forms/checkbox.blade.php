@props([
    'name',
    'value',
    'label',
    'id',
    'checked' => false,
    'disabled' => false
    ])

@php
    $id = $id ?? \Illuminate\Support\Str::camel($name.$value);
@endphp

<div {{ $attributes->class(['checkbox', 'disabled' => $disabled]) }}>
    <input type="checkbox"
           name="{{ $name }}"
           id="{{ $id }}"
           value="{{ $value }}"
        @checked($checked)
    >
    <label for="{{ $id }}">{{ $label }}</label>
    <x-forms.error name="{{ $name }}"></x-forms.error>
</div>
