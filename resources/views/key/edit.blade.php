@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('key/title.key_update'))

{{-- Content Header --}}
@section('header')
    @lang('key/title.key_update')
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('keys.index') }}">
            @lang('site.keys')
        </a>
    </li>
    <li class="breadcrumb-item active">
        @lang('key/title.key_update')
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
            {!! Form::model($key, ['route' => ['keys.update', $key->id], 'method' => 'put']) !!}

            <div class="card-body">
                <div class="form-row">
                    <!-- left column -->
                    <div class="col-md-6">

                        <fieldset>
                            <legend>@lang('key/title.key_identification_section')</legend>
                            <!-- username -->
                            <div class="form-group">
                                {!! Form::label('username', __('key/model.username')) !!}
                                {!! Form::text('username', $key->username, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                                <span class="form-text text-muted">@lang('key/messages.username_help')</span>
                                @error('username')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- ./ username -->
                        </fieldset>

                        <!-- enabled -->
                        <fieldset class="form-group">
                            <div class="row">
                                <legend class="col-form-label col-sm-2 pt-0"><strong>@lang('key/model.enabled')</strong>
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
                    </div>
                    <!-- ./ left column -->

                    <!-- right column -->
                    <div class="col-md-6">

                        <fieldset>
                            <legend>@lang('key/title.membership_section')</legend>

                            <!-- key's groups -->
                            <div class="form-group">
                                {!! Form::label('groups[]', __('key/model.groups')) !!}
                                <div class="controls">
                                    {!! Form::select('groups[]', $groups, $key->groups->pluck('id'), array('multiple' => 'multiple', 'class' => 'form-control search-select')) !!}
                                </div>
                            </div>
                            <!-- ./ key's groups -->
                        </fieldset>

                        <!-- SSH public key -->
                        <fieldset>
                            <legend>@lang('key/title.public_key_section')</legend>

                            <div class="form-group">
                                <!-- maintain RSA key -->
                                <div class="form-check">
                                    {!! Form::radio('public_key', 'maintain', true, array('class' => 'form-check-input', 'id' => 'maintain_public_key', 'checked' => 'checked')) !!}
                                    {!! Form::label('maintain_public_key', __('key/messages.maintain_public_key'), array('class' => 'form-check-label')) !!}
                                    <div id="maintain_public_key_form">
                                        <p>
                                            <b>@lang('key/model.fingerprint')</b>: {{ $key->fingerprint }}
                                            <a data-toggle="collapse" href="#collapsePublicKey" aria-expanded="false"
                                               aria-controls="collapsePublicKey">
                                                <i class="fa fa-caret-down"></i>
                                            </a>
                                        </p>
                                        <div class="collapse" id="collapsePublicKey">
                                            <pre class="key-code">{{ $key->public }}</pre>
                                        </div>
                                    </div>
                                </div>
                                <!-- ./ maintain RSA key -->
                                <!-- create RSA key -->
                                <div class="form-check">
                                    {!! Form::radio('public_key', 'create', false, array('class' => 'form-check-input', 'id' => 'create_public_key')) !!}
                                    {!! Form::label('create_public_key', __('key/messages.create_public_key'), array('class' => 'form-check-label')) !!}
                                    <div id="create_public_key_form">
                                        <p class="form-text text-muted">@lang('key/messages.create_public_key_help') @lang('key/messages.change_public_key_help_notice')</p>
                                    </div>
                                </div>
                                <!-- ./ create RSA key -->

                                <!-- import public_key -->
                                <div class="form-check">
                                    {!! Form::radio('public_key', 'import', false, array('class' => 'form-check-input', 'id' => 'import_public_key')) !!}
                                    {!! Form::label('import_public_key', __('key/messages.import_public_key'), array('class' => 'form-check-label')) !!}
                                    <div id="import_public_key_form">
                                        {!! Form::textarea('public_key_input', null, array('class' => 'form-control' . ($errors->has('public_key_input') ? ' is-invalid' : ''), 'id' => 'public_key_input', 'rows' => '5', 'placeholder' => __('key/messages.import_public_key_help'))) !!}
                                        <span
                                            class="form-text text-muted">@lang('key/messages.change_public_key_help_notice')</span>
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
                <a href="{{ route('keys.index') }}" class="btn btn-primary" role="button">
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
            placeholder: "@lang('key/messages.groups_help')",
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
