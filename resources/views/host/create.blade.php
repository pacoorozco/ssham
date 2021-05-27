@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('host/title.create_a_new_host'))

{{-- Content Header --}}
@section('header')
    <i class="fa fa-laptop"></i> @lang('host/title.create_a_new_host')
    <small class="text-muted">@lang('host/title.create_a_new_host_subtitle')</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('hosts.index') }}">
            @lang('site.hosts')
        </a>
    </li>
    <li class="breadcrumb-item active">
        @lang('host/title.create_a_new_host')
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
            {!! Form::open(['route' => 'hosts.store', 'method' => 'post']) !!}

            <div class="card-body">
                <div class="form-row">
                    <!-- left column -->
                    <div class="col-md-6">

                        <fieldset>
                            <legend>@lang('host/title.host_information_section')</legend>
                            <!-- hostname -->
                            <div class="form-group">
                                {!! Form::label('hostname', __('host/model.hostname')) !!}
                                {!! Form::text('hostname', null, array('class' => 'form-control' . ($errors->has('hostname') ? ' is-invalid' : ''), 'required' => 'required', 'autofocus' => 'autofocus')) !!}
                                @error('hostname')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror

                            </div>
                            <!-- ./ hostname -->

                            <!-- username -->
                            <div class="form-group">
                                {!! Form::label('username', __('host/model.username')) !!}
                                {!! Form::text('username', 'root', array('class' => 'form-control' . ($errors->has('username') ? ' is-invalid' : ''), 'required' => 'required')) !!}
                                @error('username')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- ./ username -->
                        </fieldset>

                        <!-- enabled -->
                        <fieldset class="form-group row">
                            <legend class="col-form-label col-sm-2 float-sm-left pt-0">
                                <strong>@lang('host/model.enabled')</strong>
                            </legend>
                            <div class="col-sm-10">
                                <div class="form-check">
                                    {!! Form::radio('enabled', 1, true, array('class' => 'form-check-input')) !!}
                                    {!! Form::label('enabled', __('general.enabled'), array('class' => 'form-check-label')) !!}
                                </div>
                                <div class="form-check">
                                    {!! Form::radio('enabled', 0, false, array('class' => 'form-check-input')) !!}
                                    {!! Form::label('enabled', __('general.disabled'), array('class' => 'form-check-label')) !!}
                                </div>
                                @error('enabled')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </fieldset>
                        <!-- ./ enabled -->

                        <fieldset>
                            <legend>@lang('host/title.advanced_config_section')</legend>

                            <!-- port -->
                            <div class="card form-group">
                                <div class="card-header">
                                    {!! Form::label('port', __('host/model.port')) !!}
                                    <div class="form-check float-right">
                                        <input class="form-check-input" type="checkbox" id="custom-port-check"
                                               @if($errors->has('port') || old('port')) checked @endif>
                                        <label class="form-check-label">@lang('host/messages.custom-port-check')</label>
                                    </div>
                                    <small class="form-text text-muted">
                                        {!! __('host/messages.custom-port-help', ['url' => route('settings.index'), 'default-value' => setting()->get('ssh_port')]) !!}
                                    </small>

                                </div>
                                <div class="collapse" id="custom-port-card">
                                    <div class="card-body">
                                        {!! Form::number('port', null, array('id' => 'port', 'class' => 'form-control' . ($errors->has('port') ? ' is-invalid' : ''), 'required' => 'required', 'disabled' => 'disabled')) !!}
                                        @error('port')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <!-- ./ port -->

                            <!-- authorized_keys_file -->
                            <div class="card form-group">
                                <div class="card-header">
                                    {!! Form::label('path', __('host/model.authorized_keys_file')) !!}
                                    <div class="form-check float-right">
                                        <input class="form-check-input" type="checkbox" id="custom-path-check"
                                               @if(old('authorized_keys_file')) checked @endif>
                                        <label class="form-check-label">@lang('host/messages.custom-path-check')</label>
                                    </div>
                                    <small class="form-text text-muted">
                                        {!! __('host/messages.custom-path-help', ['url' => route('settings.index'), 'default-value' => setting()->get('authorized_keys')]) !!}
                                    </small>
                                </div>
                                <div class="collapse" id="custom-path-card">
                                    <div class="card-body">
                                        {!! Form::text('authorized_keys_file', null, array('id' => 'path', 'class' => 'form-control' . ($errors->has('authorized_keys_file') ? ' is-invalid' : ''), 'required' => 'required', 'disabled' => 'disabled')) !!}
                                        @error('authorized_keys_file')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <!-- ./ authorized_keys_file -->
                        </fieldset>
                    </div>
                    <!-- ./ left column -->

                    <!-- right column -->
                    <div class="col-md-6">

                        <fieldset>
                            <legend>@lang('host/title.membership_section')</legend>

                            <!-- host groups -->
                            <div class="form-group">
                                {!! Form::label('groups[]', __('host/model.groups')) !!}
                                {!! Form::select('groups[]', $groups, null, array('multiple' => 'multiple', 'class' => 'form-control search-select')) !!}

                                <small class="form-text text-muted">
                                    @lang('host/messages.groups_help')
                                </small>
                            </div>
                            <!-- ./ host groups -->
                        </fieldset>
                    </div>

                </div>
            </div>
            <div class="card-footer">
                <!-- Form Actions -->
                {!! Form::button(__('general.create'), array('type' => 'submit', 'class' => 'btn btn-success')) !!}
                <a href="{{ route('hosts.index') }}" class="btn btn-link" role="button">
                    {{ __('general.cancel') }}
                </a>
                <!-- ./ form actions -->
            </div>

            {!! Form::close() !!}
        </div>
        <!-- ./ card -->
    </div>
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
        $('.search-select').select2({
            placeholder: "@lang('general.filter_box_help')",
            allowClear: true,
            language: "@lang('site.language_short')",
        });

        $(function () {
            if ($('#custom-port-check').prop('checked') === true) {
                enableCustomPort();
            } else {
                disableCustomPort();
            }
            if ($('#custom-path-check').prop('checked') === true) {
                enableCustomPath();
            } else {
                disableCustomPath();
            }
        });

        $('#custom-port-check').click(function () {
            if ($(this).prop("checked") === true) {
                enableCustomPort();
            } else if ($(this).prop("checked") === false) {
                disableCustomPort();
            }
        });

        function enableCustomPort() {
            $('#custom-port-card').collapse('show');
            $('#port').prop('disabled', false);
        }

        function disableCustomPort() {
            $('#custom-port-card').collapse('hide');
            $('#port').prop('disabled', true);
        }

        $('#custom-path-check').click(function () {
            if ($(this).prop("checked") === true) {
                enableCustomPath();
            } else if ($(this).prop("checked") === false) {
                disableCustomPath();
            }
        });

        function enableCustomPath() {
            $('#custom-path-card').collapse('show');
            $('#path').prop('disabled', false);
        }

        function disableCustomPath() {
            $('#custom-path-card').collapse('hide');
            $('#path').prop('disabled', true);
        }
    </script>
@endpush
