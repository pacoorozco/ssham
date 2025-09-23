@props([
    'label',
    'name',

    'value' => '',
    'help' => '',
    ])


<div class="form-group mb-3">
    <x-forms.label for="{{ $name }}" value="{{ $label }}"/>
    @if($help)
        <small class="form-text text-muted">{{ $help }}</small>
    @endif

    <div class="fileinput fileinput-new mt-2" data-provides="fileinput">
        @if($value)
            <div class="fileinput-new img-thumbnail">
                <img src="{{ $value }}" alt="default image" style="width: 150px; height: 150px;">
            </div>
        @endif
        <div class="fileinput-preview fileinput-exists img-thumbnail"
             style="max-width: 150px; max-height: 150px;"></div>

        <!-- image buttons -->
        <div class="mt-2">
            <span class="btn btn-outline-secondary btn-sm btn-file">
            <span class="fileinput-new">{{ __('general.pick_image') }}</span>
            <span class="fileinput-exists">{{ __('general.upload_image') }}</span>
            <input type="file"
                   name="image"
                   id="{{ \Illuminate\Support\Str::camel($name) }}"
                   @class([
                'form-control',
                'is-invalid' => $errors->has($name),
            ])
                   @error($name)
                   aria-describedby="validation{{ \Illuminate\Support\Str::studly($name) }}Feedback"
                   @enderror
            >
            </span>

            <a href="#" class="fileinput-exists btn btn-outline-danger btn-sm" data-dismiss="fileinput"
               role="button">
                {{ __('general.delete_image') }}
            </a>
        </div>
        <!-- ./image buttons -->

        <x-forms.error name="{{ $name }}"></x-forms.error>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/jasny-bootstrap/css/jasny-bootstrap.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('vendor/jasny-bootstrap/js/jasny-bootstrap.min.js') }}"></script>
@endpush
