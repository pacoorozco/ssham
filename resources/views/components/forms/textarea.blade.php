@props([
    'label',
    'id',
    'name',
    'value' => '',
    'help' => '',
    'required' => false,
    'readonly' => false,
    'disabled' => false
    ])

@php
    $id = $id ?? \Illuminate\Support\Str::camel($name);
@endphp

<div class="form-group @error($name) has-error @enderror">
    <x-forms.label for="{{ $id }}">{{ $label }}</x-forms.label>

    <textarea id="{{ $id }}"
              name="{{ $name }}"
              {{ $attributes->merge(['class' => 'form-control', 'rows' => '3', 'cols' => '50'])->only(['class', 'placeholder', 'rows', 'cols']) }}
                  @required($required)
                  @readonly($readonly)
                  @disabled($disabled)
        >{{ old($name, $value) }}</textarea>
    @if($help)
        <small class="form-text text-muted">{{ $help }}</small>
    @endif
    <x-forms.error :name="$name" :id="$id"/>
</div>
