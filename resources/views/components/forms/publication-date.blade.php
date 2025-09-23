@props([
    'name',
    ])

@php
    $idForStatus = \Illuminate\Support\Str::camel($name) . 'Status';
    $idForText = \Illuminate\Support\Str::camel($name) . 'Text';
    $idForControls = \Illuminate\Support\Str::camel($name) . 'Controls';
    $toggleButton = $idForControls . 'Toggle'
@endphp


<div class="form-group">
    <div id="{{ $idForStatus }}" class="@error($name) d-none @enderror">
        <x-forms.label for="{{ $idForText }}">
            {{ __('admin/question/model.publication_date') }}
        </x-forms.label>
        <a href="#" id="{{ $toggleButton }}">{{ __('general.edit') }}</a>

        <div id="{{ $idForText }}" class="form-control-plaintext">
            {{ empty(old($name)) ? __('admin/question/model.publish_immediately') : __('admin/question/model.publish_on', ['datetime' => old($name)]) }}
        </div>
    </div>

    <div id="{{ $idForControls }}" class="@unless($errors->has($name)) d-none @endunless">
        <x-forms.date-time-picker
                name="{{ $name }}"
                :label="__('admin/question/model.publication_date')"
                :placeholder="__('admin/question/model.publication_date_placeholder')"
                :help="__('admin/question/model.publication_date_help')"/>
    </div>
</div>

@push('scripts')
    <script>
        $(function () {
            $("{{ '#' . $toggleButton }}").click(function () {
                $("{{ '#' . $idForStatus }}").addClass("d-none");
                $("{{ '#' . $idForControls }}").removeClass("d-none");
            });
        });
    </script>
@endpush
