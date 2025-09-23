@props([
    'name',
    'value'
    ])

<div class="form-group">

    <div id="visibilityStatus" class="@error($name) d-none @enderror">
        <x-forms.label for="visibility">{{ __('admin/question/model.hidden') }}</x-forms.label>
        <a href="#" id="enableVisibilityControls">{{ __('general.edit') }}</a>
        <div id="visibility" class="form-control-plaintext">
            {{ old($name, $value) ? __('admin/question/model.hidden_yes') : __('admin/question/model.hidden_no') }}
        </div>
    </div>

    <div id="visibilityControls" class="@unless($errors->has($name)) d-none @endunless">
        <x-forms.label for="{{ $name }}">{{ __('admin/question/model.hidden') }}</x-forms.label>
        <div class="form-check">
            <input type="radio"
                   name="{{ $name }}"
                   id="visibilityPublic"
                   value="0"
                   @class([
            'form-check-input',
            'is-invalid' => $errors->has($name),
        ])
                   @checked(old($name, $value) == 0)
                   @error($name)
                   aria-describedby="{{ 'validation' . $name . 'Feedback' }}"
                @enderror
            />
            <label for="visibilityPublic" class="form-check-label">
                {{ __('admin/question/model.hidden_no') }}
            </label>
        </div>

        <div class="form-check">
            <input type="radio"
                   name="{{ $name }}"
                   id="visibilityPrivate"
                   value="1"
                   @class([
            'form-check-input',
            'is-invalid' => $errors->has($name),
        ])
                   @checked(old($name, $value) == 1)
                   @error($name)
                   aria-describedby="{{ 'validation' . $name . 'Feedback' }}"
                @enderror
            />
            <label for="visibilityPublic" class="form-check-label">
                {{ __('admin/question/model.hidden_yes') }}
                <small class="text-muted">
                    {{ __('admin/question/model.hidden_yes_help') }}
                </small>
            </label>
            <x-forms.error name="{{ $name }}" id="{{ 'validation' . $name . 'Feedback' }}"></x-forms.error>
        </div>
    </div>
</div>

{{-- Scripts --}}
@push('scripts')
    <script>
        $(function () {
            $("#enableVisibilityControls").click(function () {
                $("#visibilityStatus").addClass("d-none");
                $("#visibilityControls").removeClass("d-none");
            });
        });
    </script>
@endpush
