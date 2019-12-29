{{-- Create / Edit User Form --}}

<div class="card-body">
    <div class="form-row">
        <!-- left column -->
        <div class="col-md-6">

            <fieldset>
                <legend>@lang('user/model.personal_information')</legend>
                <!-- username -->
                <div class="form-group">
                    {!! Form::label('username', __('user/model.username')) !!}
                    @if (isset($user))
                        {!! Form::text('username', null, array('class' => 'form-control', 'disabled' => 'disabled')) !!}
                    @else
                        {!! Form::text('username', null, array('class' => 'form-control' . ($errors->has('username') ? ' is-invalid' : ''), 'required' => 'required', 'autofocus' => 'autofocus')) !!}
                    @endif
                    @error('username')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <!-- ./ username -->

                <!-- email -->
                <div class="form-group">
                    {!! Form::label('email', __('user/model.email')) !!}
                    {!! Form::email('email', null, array('class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : ''), 'required' => 'required')) !!}
                    @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <!-- ./ email -->
            </fieldset>

            <fieldset>
                <legend>@lang('user/model.credentials')</legend>
                <!-- password -->
                <div class="form-group">
                    {!! Form::label('password', __('user/model.password')) !!}
                    {!! Form::password('password', array('class' => 'form-control' . ($errors->has('password') ? ' is-invalid' : ''))) !!}
                    @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <!-- ./ password -->

                <!-- password_confirmation -->
                <div class="form-group">
                    {!! Form::label('password_confirmation', __('user/model.password_confirmation')) !!}
                    {!! Form::password('password_confirmation', array('class' => 'form-control' . ($errors->has('password_confirmation') ? ' is-invalid' : ''))) !!}
                    @error('password_confirmation')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <!-- ./ password_confirmation -->
            </fieldset>

        </div>
        <!-- ./ left column -->

        <!-- right column -->
        <div class="col-md-6">

            <!-- user's groups -->
            <fieldset>
                <legend>@lang('user/model.membership')</legend>
                <div class="form-group">
                    {!! Form::label('groups[]', __('user/model.groups')) !!}
                    <div class="controls">
                        @if (isset($user))
                            {!! Form::select('groups[]', $groups, $user->usergroups->lists('id')->all(), array('multiple' => 'multiple', 'class' => 'form-control search-select')) !!}
                        @else
                            {!! Form::select('groups[]', $groups, null, array('multiple' => 'multiple', 'class' => 'form-control search-select')) !!}
                        @endif
                        @error('groups[]'))
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <!-- ./ user's groups -->
            </fieldset>

            <fieldset>
                <legend>@lang('user/model.public_key')'</legend>
                <!-- SSH public key -->
                <div class="form-group">
                    <!-- create RSA key -->
                    <div class="form-check">
                        @if (isset($user))
                            {!! Form::radio('create_rsa_key', '1', false, array('class' => 'form-check-input', 'id' => 'create_rsa_key')) !!}
                            {!! Form::label('create_rsa_key', __('user/messages.public_key'), array('class' => 'form-check-label')) !!}
                            <span class="form-text text-muted">@lang('user/messages.create_rsa_key_help')</span>
                            <span class="form-text text-muted">@lang('user/messages.create_rsa_key_help_notice')</span>
                        @else
                            {!! Form::radio('public_key', 'create', true, array('class' => 'form-check-input', 'id' => 'create_public_key')) !!}
                            {!! Form::label('create_public_key', __('user/messages.create_public_key'), array('class' => 'form-check-label')) !!}
                            <div id="create_public_key_form">
                                <span class="form-text text-muted">@lang('user/messages.create_public_key_help')</span>
                            </div>
                        @endif
                    </div>
                    <!-- ./ create RSA key -->

                    <!-- import / edit public_key -->
                    <div class="form-check">
                        {!! Form::radio('public_key', 'import', false, array('class' => 'form-check-input', 'id' => 'import_public_key')) !!}
                        {!! Form::label('import_public_key', __('user/messages.import_public_key'), array('class' => 'form-check-label')) !!}
                        <div id="import_public_key_form">
                            {!! Form::textarea('public_key_input', null, array('class' => 'form-control' . ($errors->has('public_key_input') ? ' is-invalid' : ''), 'id' => 'public_key_input', 'rows' => '5')) !!}
                            <span class="form-text text-muted">@lang('user/messages.import_public_key_help')</span>
                        </div>
                        @error('public_key_input'))
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- ./ import / edit public_key -->
                </div>
                <!-- ./ SSH public key -->
            </fieldset>


        @if (isset($user))
            <!-- administrator role -->
                <div class="form-group">
                    {!! Form::label('is_admin', __('user/model.is_admin')) !!}
                    {!! Form::select('is_admin', array('1' => __('general.yes'), '0' => __('general.no')), ($user->hasRole('admin') ? '1' : '0'), array('class' => 'form-control', 'disabled' => 'disabled')) !!}

                    @error('is_admin'))
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <!-- ./ administrator role -->
        @endif

        @if (isset($user))
            <!-- enabled -->
                <div class="form-group">
                    {!! Form::label('enabled', __('user/model.enabled')) !!}
                    {!! Form::select('enabled', array('1' => __('general.yes'), '0' => __('general.no')), null, array('class' => 'form-control')) !!}
                    @error('enabled'))
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <!-- ./ enabled -->
            @endif

        </div>
        <!-- ./right column -->

    </div>
</div>
<div class="card-footer">
    <!-- Form Actions -->
    <div class="form-group">
        {!! Form::button('<i class="fa fa-floppy-o"></i> ' . __('general.save'), array('type' => 'submit', 'class' => 'btn btn-success')) !!}
    </div>
    <!-- ./ form actions -->
</div>

{{-- Styles --}}
@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/AdminLTE/plugins/select2/css/select2.min.css') }}">
@endpush

{{-- Scripts --}}
@push('scripts')
    <script src="{{ asset('vendor/AdminLTE/plugins/select2/js/select2.min.js') }}"></script>
    <script>
        $(".search-select").select2({
            placeholder: "@lang('user/messages.groups_help')",
            allowClear: true,
            language: "@lang('site.language_short')"
        });


        $(function () {
            disablePublicKeyImport()
            var $radios = $('input:radio[id=import_public_key]');
            if ($radios.is(':checked') === true) {
                enablePublicKeyImport()
            }
        });

        $("#create_public_key").click(function () {
            disablePublicKeyImport()
        });

        $("#import_public_key").click(function () {
            enablePublicKeyImport()
        });

        function disablePublicKeyImport(){
            $("#create_public_key_form").removeClass("d-none");
            $("#import_public_key_form").addClass("d-none");
            $("#public_public_input").attr("disabled");
        }

        function enablePublicKeyImport(){
            $("#import_public_key_form").removeClass("d-none");
            $("#create_public_key_form").addClass("d-none");
            $("#public_public_input").removeAttr("disabled");
        }
    </script>
@endpush


