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

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    <!-- Card -->
    <div class="card">

        <x-form :action="route('hosts.update', $host)" method="PUT">

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
                        <x-form-input name="hostname" :label="__('host/model.hostname')" :default="$host->present()->hostname" disabled/>
                        <!-- ./ hostname -->

                        <!-- username -->
                        <x-form-input name="username" :label="__('host/model.username')" :default="$host->present()->username" disabled/>
                        <!-- ./ username -->
                    </fieldset>

                    <!-- enabled -->
                    <fieldset class="form-group row">
                        <legend class="col-form-label col-sm-2 float-sm-left pt-0">
                            <strong>@lang('host/model.enabled')</strong>
                        </legend>
                        <div class="col-sm-10">
                            <x-form-group name="enabled">
                                <x-form-radio name="enabled" value="1" :label="__('general.enabled')" :bind="$host"/>
                                <x-form-radio name="enabled" value="0" :label="__('general.disabled')" :bind="$host"/>
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
                                           @if($errors->has('port') || old('port') || $host->hasCustomPort()) checked @endif>
                                    <label for="custom-port-check" class="form-check-label">@lang('host/messages.custom-port-check')</label>
                                </div>
                                <small class="form-text text-muted">
                                    {!! __('host/messages.custom-port-help', ['url' => route('settings.index'), 'default-value' => setting()->get('ssh_port')]) !!}
                                </small>
                            </div>
                            <div class="collapse" id="custom-port-card">
                                <div class="card-body">
                                    <x-form-input name="port" id="port" type="number" required disabled :default="$host->port"/>
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
                                           @if(old('authorized_keys_file') || $host->hasCustomAuthorizedKeysFile()) checked @endif>
                                    <label for="custom-path-check" class="form-check-label">@lang('host/messages.custom-path-check')</label>
                                </div>
                                <small class="form-text text-muted">
                                    {!! __('host/messages.custom-path-help', ['url' => route('settings.index'), 'default-value' => setting()->get('authorized_keys')]) !!}
                                </small>
                            </div>
                            <div class="collapse" id="custom-path-card">
                                <div class="card-body">
                                    <x-form-input name="authorized_keys_file" id="path" required disabled :default="$host->authorized_keys_file"/>
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
                        <x-form-select name="groups[]" :label="__('host/model.groups')" :options="$groups" multiple class="search-select" :default="$host->groups->pluck('id')">
                            @slot('help')
                                <small class="form-text text-muted">
                                    @lang('host/messages.groups_help')
                                </small>
                            @endslot
                        </x-form-select>
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

                        @can('delete', $host)
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
                        @else
                            <p class="from-text text-muted">@lang('host/messages.delete_avoided')</p>
                        @endcan
                    </fieldset>

                </div>
                <!-- ./ right column -->
            </div>
        </div>
        <div class="card-footer">
            <!-- Form Actions -->
            <x-form-submit class="btn-success">
                @lang('general.update')
            </x-form-submit>

            <a href="{{ route('hosts.index') }}" class="btn btn-link" role="button">
                {{ __('general.cancel') }}
            </a>
            <!-- ./ form actions -->
        </div>

        </x-form>
    </div>
    <!-- ./ card -->

    @can('delete', $host)
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
    @endcan

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
