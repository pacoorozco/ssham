@props([
    'label',
    'name',
    'id',
    'options' => [],
    'selectedKey' => '',
    'help' => '',
    'required' => false,
    'disabled' => false,
    'hidePlaceholderFromSelection' => false
    ])

@php
    $id = $id ?? \Illuminate\Support\Str::camel($name);
@endphp

<div class="form-group @error($name) has-error @enderror">
    <x-forms.label for="{{ $id }}">{{ $label }}</x-forms.label>
    <select name="{{ $name }}"
            id="{{ $id }}"
        {{ $attributes->merge(['class' => 'form-control'])->only(['class']) }}
        @required($required)
        @disabled($disabled)
    >
        @if($attributes->has('placeholder'))
            <option value=""
                    @if($hidePlaceholderFromSelection) hidden @endif>{{ $attributes->get('placeholder') }}</option>
        @endif
        @foreach($options as $key => $option)
            @if(is_array($option))
                <x-forms.select-optgroup label="{{ $key }}" :options="$option"
                                         selectedKey="{{ old($name, $selectedKey) }}"/>
            @else
                <x-forms.select-option key="{{ $key }}" label="{{ $option }}"
                                       selectedKey="{{ old($name, $selectedKey) }}"/>
            @endif
        @endforeach
    </select>
    @if($disabled)
        <x-forms.input-hidden name="{{ $name }}" value="{{ $selectedKey }}"/>
    @endif
    @if($help)
        <small class="form-text text-muted">{{ $help }}</small>
    @endif
    <x-forms.error :name="$name" :id="$id"/>
</div>
