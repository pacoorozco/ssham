@props([
    'label',
    'options' => [],
    'selectedKey' => ''
])

<optgroup label="{{ $label }}">
    @foreach($options as $key => $option)
        <x-forms.select-option :key="$key" :label="$option"
            :selectedKey="$selectedKey"/>
    @endforeach
</optgroup>
