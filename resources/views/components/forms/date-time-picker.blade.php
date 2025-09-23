@props([
    'name',
    'id',
    'label',
    'placeholder',
    'value' => '',
    'help' => '',
    'isDisabledTimepicker' => false,
    ])

@php
    $id = $id ?? \Illuminate\Support\Str::camel($name);
    $timePicker = $id . 'TimePicker';
@endphp

<x-forms.label for="{{ $id }}">
    {{ $label }}
</x-forms.label>
<div class="input-group date" id="{{ $timePicker }}" data-target-input="nearest">
    <input name="{{ $name }}" id="{{ $id }}"
           type="text"
           value="{{ old($name, $value) }}"
           @class([
                'form-control',
                'datetimepicker-input',
                'is-invalid' => $errors->has($name),
            ])
           @if($placeholder)
               placeholder="{{ $placeholder }}"
           @endif
    />
    <div class="input-group-append" data-target="#{{ $id }}" data-toggle="datetimepicker">
        <div class="input-group-text"><i class="bi bi-calendar-date"></i></div>
    </div>
</div>
@if($help)
    <small class="form-text text-muted">{{ $help }}</small>
@endif
<x-forms.error :name="$name"/>

@push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/tempus-dominus/css/tempus-dominus.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('vendor/popper-js/umd/popper.min.js') }}"></script>
    <script src="{{ asset('vendor/tempus-dominus/js/tempus-dominus.min.js') }}"></script>

    <script>
        const picker = new tempusDominus.TempusDominus(document.getElementById('{{ $timePicker }}'), {
            display: {
                icons: {
                    type: 'icons',
                    time: 'bi bi-clock',
                    date: 'bi bi-calendar-week',
                    up: 'bi bi-arrow-up',
                    down: 'bi bi-arrow-down',
                    previous: 'bi bi-chevron-left',
                    next: 'bi bi-chevron-right',
                    today: 'bi bi-calendar-check-fill',
                    clear: 'bi bi-trash',
                    close: 'bi bi-x'
                },
                buttons: {
                    today: false,
                    clear: true,
                    close: true
                },
                components: {
                    clock: {{ $isDisabledTimepicker ? "false" : "true" }},
                    hours: {{ $isDisabledTimepicker ? "false" : "true" }},
                    minutes: {{ $isDisabledTimepicker ? "false" : "true" }},
                },
                theme: 'light',
            },
            useCurrent: false,
            localization: {
                format: '{{ $isDisabledTimepicker ? "yyyy-MM-dd" : "yyyy-MM-dd HH:mm" }}',
            },
        });
    </script>
@endpush
