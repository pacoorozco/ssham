@props([
    'name',
    'value',
    'label',
    'id',
    'help' => '',
    'checked' => false,
    'disabled' => false
    ])

@php
    $id = $id ?? \Illuminate\Support\Str::camel($name.$value);
@endphp

<div {{ $attributes->class(['form-check']) }}>
    <input type="radio"
           name="{{ $name }}"
           id="{{ $id }}"
           value="{{ $value }}"
           @class([
    'form-check-input',
    'is-invalid' => $errors->has($name),
])
           @checked($checked)
           @disabled($disabled)
           @error($name)
           aria-describedby="{{ 'validation' . $name . 'Feedback' }}"
        @enderror
    />
    <label for="{{ $id }}" class="form-check-label">
        {{ $label }}
        @if($help)
            <small class="text-muted">
                {{ $help }}
            </small>
        @endif
    </label>
    <x-forms.error name="{{ $name }}"></x-forms.error>
</div>

