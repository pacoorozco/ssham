@props([
    'label',
    'name',
    'id',
    'value' => '',
    'help' => '',
    'type' => 'text',
    'required' => false,
    'readonly' => false,
    'disabled' => false,
    'prepend' => '',
    'append' => '',
    ])

@php
    $id = $id ?? \Illuminate\Support\Str::camel($name);
    $validationId = $id . 'Feedback';
@endphp

<div class="form-group">
    <x-forms.label for="{{ $id }}">{{ $label }}</x-forms.label>
    <div class="input-group">
        @if($prepend)
            <div class="input-group-prepend">
                <span class="input-group-text">{!! $prepend !!}</span>
            </div>
        @endif
        <input name="{{ $name }}" id="{{ $id }}"
               type="{{ $type }}"
               value="{{ old($name, $value) }}"
               @if($type == 'number')
                   {{ $attributes->only(['min', 'max']) }}
               @endif
               {{ $attributes->class([
                'form-control',
                'is-invalid' => $errors->has($name),
            ]) }}
               @required($required)
               @readonly($readonly)
               @disabled($disabled)
               @error($name) aria-describedby="{{ $validationId }}" @enderror
        />
        @if($append)
            <div class="input-group-append">
                <span class="input-group-text">{!! $append !!}</span>
            </div>
        @endif
    </div>
    @if($help)
        <small class="form-text text-muted">{{ $help }}</small>
    @endif
    <x-forms.error :name="$name" :id="$validationId"></x-forms.error>
</div>
