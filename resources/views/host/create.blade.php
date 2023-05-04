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

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    <!-- Card -->
    <div class="card">
        <x-form :action="route('hosts.store')">

            <div class="card-body">
                <div class="form-row">
                    <!-- left column -->
                    <div class="col-md-6">

                        <fieldset>
                            <legend>@lang('host/title.host_information_section')</legend>
                            <!-- hostname -->
                            <x-form-input name="hostname" :label="__('host/model.hostname')" required autofocus/>
                            <!-- ./ hostname -->

                            <!-- username -->
                            <x-form-input name="username" :label="__('host/model.username')" required value="root"/>
                            <!-- ./ username -->
                        </fieldset>

                        <!-- enabled -->
                        <fieldset class="form-group row">
                            <legend class="col-form-label col-sm-2 float-sm-left pt-0">
                                <strong>@lang('host/model.enabled')</strong>
                            </legend>
                            <div class="col-sm-10">
                                <x-form-group name="enabled">
                                    <x-form-radio name="enabled" value="1" :label="__('general.enabled')" default/>
                                    <x-form-radio name="enabled" value="0" :label="__('general.disabled')"/>
                                </x-form-group>
                            </div>
                        </fieldset>
                        <!-- ./ enabled -->

                        <fieldset>
                            <legend>@lang('host/title.advanced_config_section')</legend>

                            <!-- port -->
                            <div class="card form-group">
                                <div class="card-header">
                                    <label for="port">@lang('host/model.port')</label>
                                    <div class="form-check float-right">
                                        <input class="form-check-input" type="checkbox" id="custom-port-check"
                                               @if($errors->has('port') || old('port')) checked @endif>
                                        <label for="custom-port-check" class="form-check-label">@lang('host/messages.custom-port-check')</label>
                                    </div>
                                    <small class="form-text text-muted">
                                        {!! __('host/messages.custom-port-help', ['url' => route('settings.index'), 'default-value' => setting()->get('ssh_port')]) !!}
                                    </small>

                                </div>
                                <div class="collapse" id="custom-port-card">
                                    <div class="card-body">
                                        <x-form-input name="port" id="port" type="number" required disabled/>
                                    </div>
                                </div>
                            </div>
                            <!-- ./ port -->

                            <!-- authorized_keys_file -->
                            <div class="card form-group">
                                <div class="card-header">
                                    <label for="path">@lang('host/model.authorized_keys_file')</label>
                                    <div class="form-check float-right">
                                        <input class="form-check-input" type="checkbox" id="custom-path-check"
                                               @if(old('authorized_keys_file')) checked @endif>
                                        <label for="custom-path-check" class="form-check-label">@lang('host/messages.custom-path-check')</label>
                                    </div>
                                    <small class="form-text text-muted">
                                        {!! __('host/messages.custom-path-help', ['url' => route('settings.index'), 'default-value' => setting()->get('authorized_keys')]) !!}
                                    </small>
                                </div>
                                <div class="collapse" id="custom-path-card">
                                    <div class="card-body">
                                        <x-form-input name="authorized_keys_file" id="path" required disabled/>
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
                            <x-form-select name="groups[]" :label="__('host/model.groups')" :options="$groups" multiple class="search-select">
                                @slot('help')
                                    <small class="form-text text-muted">
                                        @lang('host/messages.groups_help')
                                    </small>
                                @endslot
                            </x-form-select>
                            <!-- ./ host groups -->
                        </fieldset>
                    </div>

                </div>
            </div>
            <div class="card-footer">
                <!-- Form Actions -->
                <x-form-submit class="btn-success">
                    @lang('general.create')
                </x-form-submit>

                <a href="{{ route('hosts.index') }}" class="btn btn-link" role="button">
                    {{ __('general.cancel') }}
                </a>
                <!-- ./ form actions -->
            </div>

        </x-form>
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
