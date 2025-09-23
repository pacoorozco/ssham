<div class="form-group" id="{{ $name }}-selector">
    <x-forms.label for="{{ \Illuminate\Support\Str::camel($name) }}" value="{{ $label }}"/>

    <select
        multiple="multiple"
        id="{{ \Illuminate\Support\Str::camel($name) }}"
        name="{{ $name . '[]' }}"
        data-placeholder="{{ $placeholder }}"
        @class([
                'form-control',
                'is-invalid' => $errors->has($name),
            ])
    >
        @foreach($availableTags as $tag)
            <option value="{{ $tag }}" @selected($isSelected($tag))>{{ $tag }}</option>
        @endforeach
    </select>

    @if($help)
        <small class="form-text text-muted">{{ $help }}</small>
    @endif
</div>

@pushOnce('styles')
    <link rel="stylesheet" href="{{ asset('vendor/AdminLTE/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/AdminLTE/plugins/select2/select2-bootstrap4.min.css') }}">
@endPushOnce

@pushOnce('scripts')
    <script src="{{ asset('vendor/AdminLTE/plugins/select2/js/select2.full.min.js') }}"></script>

    <script>
        const selectedTags = [
                @foreach($selectedTags as $tag)
            {
                id: '{{ $tag }}',
                text: '{{ $tag }}',
                selected: true
            },
            @endforeach
        ];

        $(function () {
            $("#{{ $name }}").select2({
                theme: 'bootstrap4',
                tags: true,
                tokenSeparators: [',', ' '],
                allowClear: true,
                width: "100%",
                data: selectedTags,
            });
        });
    </script>
@endPushOnce
