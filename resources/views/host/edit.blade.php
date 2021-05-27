@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('host/title.host_update'))

{{-- Content Header --}}
@section('header')
    <i class="fa fa-laptop"></i> @lang('host/title.host_update')
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('hosts.index') }}">
            @lang('site.hosts')
        </a>
    </li>
    <li class="breadcrumb-item active">
        @lang('host/title.host_update')
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

            {!! Form::model($host, ['route' => ['hosts.update', $host->id], 'method' => 'put']) !!}

            <div class="card-header @unless($host->enabled) bg-gray-dark @endunless">
                <h2 class="card-title">
                    {{ $host->full_hostname }}
                    @unless($host->enabled)
                        {{ $host->present()->enabledAsBadge() }}
                    @endunless
                </h2>
            </div>

            <div class="card-body">
                <div class="form-row">
                    <!-- left column -->
                    <div class="col-md-6">

                        <fieldset>
                            <legend>@lang('host/title.host_information_section')</legend>
                            <!-- hostname -->
                            <div class="form-group">
                                {!! Form::label('hostname', __('host/model.hostname')) !!}
                                {!! Form::text('hostname', $host->present()->hostname, array('class' => 'form-control', 'disabled' => 'disabled')) !!}
                            </div>
                            <!-- ./ hostname -->

                            <!-- username -->
                            <div class="form-group">
                                {!! Form::label('username', __('host/model.username')) !!}
                                {!! Form::text('username', $host->present()->username, array('class' => 'form-control', 'disabled' => 'disabled')) !!}
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
                                    {!! Form::radio('enabled', 1, null, array('class' => 'form-check-input')) !!}
                                    {!! Form::label('enabled', __('general.enabled'), array('class' => 'form-check-label')) !!}
                                </div>
                                <div class="form-check">
                                    {!! Form::radio('enabled', 0, null, array('class' => 'form-check-input')) !!}
                                    {!! Form::label('enabled', __('general.disabled'), array('class' => 'form-check-label')) !!}
                                </div>
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
                                               @if($errors->has('port') || old('port') || $host->hasCustomPort()) checked @endif>
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
                                               @if(old('authorized_keys_file') || $host->hasCustomAuthorizedKeysFile()) checked @endif>
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
                                {!! Form::select('groups[]', $groups, $host->groups->pluck('id'), array('multiple' => 'multiple', 'class' => 'form-control search-select')) !!}

                                <small class="form-text text-muted">
                                    @lang('host/messages.groups_help')
                                </small>
                            </div>
                            <!-- ./ host groups -->
                        </fieldset>

                        <fieldset>
                            <legend>@lang('host/title.status_section')</legend>

                            <!-- created at -->
                            <div class="row">
                                <div class="col-3">
                                    <strong>@lang('host/model.created_at')</strong>
                                </div>
                                <div class="col-9">
                                    {{ $host->present()->createdAtForHumans() }} ({{ $host->present()->created_at }})
                                </div>
                            </div>
                            <!-- ./ created at -->

                            <!-- enabled -->
                            <div class="row">
                                <div class="col-3">
                                    <strong>@lang('host/model.enabled')</strong>
                                </div>
                                <div class="col-9">
                                    {{ $host->present()->enabledAsBadge() }}
                                </div>
                            </div>
                            <!-- ./ enabled -->

                            <!-- synced -->
                            <div class="row">
                                <div class="col-3">
                                    <strong>@lang('host/model.synced')</strong>
                                </div>
                                <div class="col-9">
                                    {{ $host->present()->pendingSyncAsBadge() }}
                                </div>
                            </div>
                            <!-- ./ synced -->

                            <!-- last_rotation -->
                            <div class="row">
                                <div class="col-3">
                                    <strong>@lang('host/model.last_rotation')</strong>
                                </div>
                                <div class="col-9">
                                    {{ $host->present()->status_code }}
                                    ({{ $host->present()->lastRotationForHumans() }})
                                </div>
                            </div>
                            <!-- ./ synced -->
                        </fieldset>

                        <fieldset class="mt-5">
                            <legend>@lang('host/messages.danger_zone_section')</legend>

                            <ul class="list-group border border-danger">
                                <li class="list-group-item">
                                    <strong>@lang('host/messages.delete_button')</strong>
                                    <button type="button" class="btn btn-outline-danger btn-sm float-right"
                                            data-toggle="modal"
                                            data-target="#confirmationModal">
                                        @lang('host/messages.delete_button')
                                    </button>
                                    <p>@lang('host/messages.delete_host_help')</p>
                                </li>
                            </ul>
                        </fieldset>

                    </div>
                    <!-- ./ right column -->
                </div>
            </div>
            <div class="card-footer">
                <!-- Form Actions -->
                {!! Form::button(__('general.update'), array('type' => 'submit', 'class' => 'btn btn-success')) !!}
                <a href="{{ route('hosts.index') }}" class="btn btn-link" role="button">
                    {{ __('general.cancel') }}
                </a>
                <!-- ./ form actions -->
            </div>

            {!! Form::close() !!}
        </div>
        <!-- ./ card -->

        <!-- confirmation modal -->
        <x-modals.confirmation
            action="{{ route('hosts.destroy', $host) }}"
            confirmationText="{{ $host->hostname }}"
            buttonText="{{ __('host/messages.delete_confirmation_button') }}">

            <div class="alert alert-warning" role="alert">
                @lang('host/messages.delete_confirmation_warning', ['hostname' => $host->hostname])
            </div>

        </x-modals.confirmation>
        <!-- ./ confirmation modal -->

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
