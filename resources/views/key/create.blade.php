@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('key/title.create_a_new_key'))

{{-- Content Header --}}
@section('header')
    <i class="nav-icon fa fa-key"></i> @lang('key/title.create_a_new_key')
    <small class="text-muted">@lang('key/title.create_a_new_key_subtitle')</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('keys.index') }}">
            @lang('site.keys')
        </a>
    </li>
    <li class="breadcrumb-item active">
        @lang('key/title.create_a_new_key')
    </li>
@endsection


{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    <!-- Card -->
    <div class="card">
        {!! Form::open(['route' => 'keys.store']) !!}

        <div class="card-body">
            <div class="form-row">
                <!-- left column -->
                <div class="col-md-6">

                    <fieldset>
                        <legend>@lang('key/title.key_identification_section')</legend>
                        <!-- username -->
                        <div class="form-group">
                            {!! Form::label('username', __('key/model.username')) !!}
                            {!! Form::text('username', null, array('class' => 'form-control' . ($errors->has('username') ? ' is-invalid' : ''), 'required' => 'required', 'autofocus' => 'autofocus')) !!}
                            <span class="form-text text-muted">@lang('key/messages.username_help')</span>
                            @error('username')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- ./ username -->
                    </fieldset>

                    <!-- SSH public key -->
                    <fieldset>
                        <legend>@lang('key/title.public_key_section')</legend>
                        <div class="form-group">
                            <!-- create RSA key -->
                            <div class="form-check">
                                {!! Form::radio('operation', \App\Enums\KeyOperation::CREATE_OPERATION, true, array('class' => 'form-check-input', 'id' => 'create_public_key')) !!}
                                {!! Form::label('create_public_key', __('key/messages.create_public_key'), array('class' => 'form-check-label')) !!}
                                <div id="create_public_key_form">
                                        <span class="form-text text-muted">
                                            @lang('key/messages.create_public_key_help')
                                        </span>
                                </div>
                            </div>
                            <!-- ./ create RSA key -->

                            <!-- import public_key -->
                            <div class="form-check">
                                {!! Form::radio('operation', \App\Enums\KeyOperation::IMPORT_OPERATION, false, array('class' => 'form-check-input', 'id' => 'import_public_key')) !!}
                                {!! Form::label('import_public_key', __('key/messages.import_public_key'), array('class' => 'form-check-label')) !!}
                                <div id="import_public_key_form">
                                    {!! Form::textarea('public_key', null, array('class' => 'form-control' . ($errors->has('public_key') ? ' is-invalid' : ''), 'id' => 'public_key', 'rows' => '5', 'placeholder' => __('key/messages.import_public_key_help'), 'required' => 'required')) !!}
                                    @error('public_key')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <!-- ./ import public_key -->
                        </div>
                    </fieldset>
                    <!-- ./ SSH public key -->

                </div>
                <!-- ./ left column -->

                <!-- right column -->
                <div class="col-md-6">

                    <fieldset>
                        <legend>@lang('key/title.membership_section')</legend>

                        <!-- key's groups -->
                        <div class="form-group">
                            {!! Form::label('groups[]', __('key/model.groups')) !!}
                            {!! Form::select('groups[]', $groups, null, array('multiple' => 'multiple', 'class' => 'form-control search-select')) !!}

                            <small class="form-text text-muted">
                                @lang('key/messages.groups_help')
                            </small>
                        </div>
                        <!-- ./ key's groups -->
                    </fieldset>

                </div>
                <!-- ./right column -->

            </div>
        </div>
        <div class="card-footer">
            <!-- Form Actions -->
            {!! Form::button(__('general.create'), array('type' => 'submit', 'class' => 'btn btn-success')) !!}
            <a href="{{ route('keys.index') }}" class="btn btn-link" role="button">
                {{ __('general.cancel') }}
            </a>
            <!-- ./ form actions -->
        </div>

        {!! Form::close() !!}
    </div>
    <!-- ./ card -->
@endsection

{{-- Styles --}}
@push('styles')
    <link rel="stylesheet" type="text/css"
          href="{{ asset('vendor/AdminLTE/plugins/select2/css/select2.min.css') }}">
@endpush

{{-- Scripts --}}
@push('scripts')
    <script src="{{ asset('vendor/AdminLTE/plugins/select2/js/select2.min.js') }}"></script>
    <script>
        $(".search-select").select2({
            placeholder: "@lang('general.filter_box_help')",
            allowClear: true,
            language: "@lang('site.language_short')",
        });


        $(function () {
            var $radioValue = $('input:radio[name=operation]:checked').val();
            switch ($radioValue) {
                case 'import':
                    enablePublicKeyImport();
                    break;
                case 'create':
                default:
                    enablePublicKeyCreation();
            }
        });

        $("#create_public_key").click(function () {
            enablePublicKeyCreation();
        });

        $("#import_public_key").click(function () {
            enablePublicKeyImport();
        });

        function enablePublicKeyCreation() {
            $("#create_public_key_form").removeClass("d-none");
            $("#import_public_key_form").addClass("d-none");
            $("#public_key").prop("disabled", true);
        }

        function enablePublicKeyImport() {
            $("#import_public_key_form").removeClass("d-none");
            $("#create_public_key_form").addClass("d-none");
            $("#public_key").prop("disabled", false);
        }
    </script>
@endpush

