@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('user/title.user_update'))

{{-- Content Header --}}
@section('header')
    @lang('user/title.user_update')
    <small class="text-muted">{{ $user->username }}</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('users.index') }}">
            @lang('site.users')
        </a>
    </li>
    <li class="breadcrumb-item active">
        @lang('user/title.user_update')
    </li>
@endsection

{{-- Content --}}
@section('content')
    <div class="container-fluid">

        <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

        <!-- Card -->
        <div class="card">
            {!! Form::model($user, ['route' => ['users.update', $user->id], 'method' => 'put']) !!}

            <div class="card-body">
                <div class="form-row">
                    <!-- left column -->
                    <div class="col-md-6">

                        <fieldset>
                            <legend>@lang('user/title.personal_information_section')</legend>
                            <!-- username -->
                            <div class="form-group">
                                {!! Form::label('username', __('user/model.username')) !!}
                                {!! Form::text('username', $user->username, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                                <span class="form-text text-muted">@lang('user/messages.username_help')</span>
                                @error('username')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- ./ username -->

                            <!-- email -->
                            <div class="form-group">
                                {!! Form::label('email', __('user/model.email')) !!}
                                {!! Form::email('email', $user->email, array('class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : ''), 'required' => 'required', 'autofocus' => 'autofocus')) !!}
                                @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- ./ email -->
                        </fieldset>

                        <!-- enabled -->
                        <fieldset class="form-group">
                            <div class="row">
                                <legend class="col-form-label col-sm-2 pt-0"><strong>@lang('user/model.enabled')</strong>
                                </legend>
                                <div class="col-sm-10">
                                    <div class="form-check">
                                        {!! Form::radio('enabled', 0, null, array('class' => 'form-check-input')) !!}
                                        {!! Form::label('enabled', __('general.blocked'), array('class' => 'form-check-label')) !!}
                                    </div>
                                    <div class="form-check">
                                        {!! Form::radio('enabled', 1, null, array('class' => 'form-check-input')) !!}
                                        {!! Form::label('enabled', __('general.active'), array('class' => 'form-check-label')) !!}
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <!-- ./ enabled -->

                        <!-- about the user -->
                        <fieldset>
                            <legend>@lang('user/title.about_the_user_section')</legend>
                            <p>@lang('user/messages.edit_password_help')</p>
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
                        <!-- ./ about the user -->

                    </div>
                    <!-- ./ left column -->

                    <!-- right column -->
                    <div class="col-md-6">

                        <fieldset>
                            <legend>@lang('user/title.membership_section')</legend>

                            <!-- user's groups -->
                            <div class="form-group">
                                {!! Form::label('groups[]', __('user/model.groups')) !!}
                                <div class="controls">
                                    {!! Form::select('groups[]', $groups, $user->usergroups->pluck('id'), array('multiple' => 'multiple', 'class' => 'form-control search-select')) !!}
                                </div>
                            </div>
                            <!-- ./ user's groups -->

                            <!-- administrator role -->
                            <div class="form-group">
                                {!! Form::label('is_admin', __('user/model.is_admin')) !!}
                                {!! Form::select('is_admin', array('1' => __('general.yes'), '0' => __('general.no')), ($user->hasRole('admin') ? '1' : '0'), array('class' => 'form-control', 'disabled' => 'disabled')) !!}
                                @error('is_admin'))
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- ./ administrator role -->

                        </fieldset>

                        <!-- SSH public key -->
                        <fieldset>
                            <legend>@lang('user/title.public_key_section')</legend>

                            <div class="form-group">
                                <!-- maintain RSA key -->
                                <div class="form-check">
                                    {!! Form::radio('public_key', 'maintain', true, array('class' => 'form-check-input', 'id' => 'maintain_public_key', 'checked' => 'checked')) !!}
                                    {!! Form::label('maintain_public_key', __('user/messages.maintain_public_key'), array('class' => 'form-check-label')) !!}
                                    <div id="maintain_public_key_form">
                                        <p>
                                            <b>@lang('user/model.fingerprint')</b>: {{ $user->fingerprint }}
                                            <a data-toggle="collapse" href="#collapsePublicKey" aria-expanded="false"
                                               aria-controls="collapsePublicKey">
                                                <i class="fa fa-caret-down"></i>
                                            </a>
                                        </p>
                                        <div class="collapse" id="collapsePublicKey">
                                            <pre class="key-code">{{ $user->public_key }}</pre>
                                        </div>
                                    </div>
                                </div>
                                <!-- ./ maintain RSA key -->
                                <!-- create RSA key -->
                                <div class="form-check">
                                    {!! Form::radio('public_key', 'create', false, array('class' => 'form-check-input', 'id' => 'create_public_key')) !!}
                                    {!! Form::label('create_public_key', __('user/messages.create_public_key'), array('class' => 'form-check-label')) !!}
                                    <div id="create_public_key_form">
                                        <p class="form-text text-muted">@lang('user/messages.create_public_key_help') @lang('user/messages.change_public_key_help_notice')</p>
                                    </div>
                                </div>
                                <!-- ./ create RSA key -->

                                <!-- import public_key -->
                                <div class="form-check">
                                    {!! Form::radio('public_key', 'import', false, array('class' => 'form-check-input', 'id' => 'import_public_key')) !!}
                                    {!! Form::label('import_public_key', __('user/messages.import_public_key'), array('class' => 'form-check-label')) !!}
                                    <div id="import_public_key_form">
                                        {!! Form::textarea('public_key_input', null, array('class' => 'form-control' . ($errors->has('public_key_input') ? ' is-invalid' : ''), 'id' => 'public_key_input', 'rows' => '5', 'placeholder' => __('user/messages.import_public_key_help'))) !!}
                                        <span
                                            class="form-text text-muted">@lang('user/messages.change_public_key_help_notice')</span>
                                    </div>
                                    @error('public_key_input')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- ./ import public_key -->

                            </div>
                            <!-- ./ SSH public key -->
                        </fieldset>

                    </div>
                    <!-- ./right column -->

                </div>
            </div>
            <div class="card-footer">
                <!-- Form Actions -->
                <a href="{{ route('users.index') }}" class="btn btn-primary" role="button">
                    <i class="fa fa-arrow-left"></i> {{ __('general.back') }}
                </a>
                {!! Form::button('<i class="fa fa-save"></i> ' . __('general.save'), array('type' => 'submit', 'class' => 'btn btn-success')) !!}
            <!-- ./ form actions -->
            </div>

            {!! Form::close() !!}
        </div>
        <!-- ./ card -->
    </div>
@endsection

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
            var $radioValue = $('input:radio[name=public_key]:checked').val();
            switch ($radioValue) {
                case 'create':
                    enablePublicKeyCreation();
                    break;
                case 'import':
                    enablePublicKeyImport();
                    break;
                case 'maintain':
                default:
                    enablePublicKeyMaintain();
            }
        });

        $("#maintain_public_key").click(function () {
            enablePublicKeyMaintain();
        });

        $("#create_public_key").click(function () {
            enablePublicKeyCreation();
        });

        $("#import_public_key").click(function () {
            enablePublicKeyImport();
        });

        function enablePublicKeyMaintain() {
            $("#maintain_public_key_form").removeClass("d-none");
            $("#create_public_key_form").addClass("d-none");
            $("#import_public_key_form").addClass("d-none");
            $("#public_key_input").prop("disabled", true);
        }

        function enablePublicKeyCreation() {
            $("#maintain_public_key_form").addClass("d-none");
            $("#create_public_key_form").removeClass("d-none");
            $("#import_public_key_form").addClass("d-none");
            $("#public_key_input").prop("disabled", true);
        }

        function enablePublicKeyImport() {
            $("#maintain_public_key_form").addClass("d-none");
            $("#import_public_key_form").removeClass("d-none");
            $("#create_public_key_form").addClass("d-none");
            $("#public_key_input").prop("disabled", false);
        }
    </script>
@endpush
