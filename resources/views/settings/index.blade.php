@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('site.settings'))

{{-- Content Header --}}
@section('header')
    <i class="fa fa-cog"></i> @lang('settings/title.title')
    <small class="text-muted">@lang('settings/title.subtitle')</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('site.settings')
    </li>
@endsection

{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    <div class="card">

        <div class="card-header">
            <a href="{{ route('settings.edit') }}"
               class="btn btn-primary @cannot(\App\Enums\Permissions::EditSettings->value) disabled @endcannot" role="button">
                <i class="fa fa-edit"></i> {{ __('settings/messages.edit_button') }}
            </a>
        </div>
        <div class="card-body">

            <!-- panel-ssh-settings -->

            <h2 id="panel-ssh-settings">@lang('settings/title.ssh_settings')</h2>
            <div class="border bg-light p-2">
                <h3>@lang('settings/title.ssh_credentials_section')</h3>
                <div class="row">

                    <!-- private_key -->
                    <div class="col-sm-3">
                        <strong>@lang('settings/model.private_key')</strong><br/>
                        <small class="text-muted">@lang('settings/model.private_key_help')</small>
                    </div>
                    <div class="col-sm-9">
                        {{ __('settings/messages.private_key_can_not_be_shown') }}
                    </div>
                    <!-- ./ private_key -->

                    <!-- public_key -->
                    <div class="col-sm-3">
                        <strong>@lang('settings/model.public_key')</strong><br>
                        <small class="text-muted">@lang('settings/model.public_key_help')</small>
                    </div>
                    <div class="col-sm-9">
                        <pre class="key-code">{{ $settings['public_key'] }}</pre>
                    </div>
                    <!-- ./ public_key -->
                </div>

                <h3>@lang('settings/title.ssh_options_section')</h3>
                <div class="row">

                    <!-- SSH connect timeout -->
                    <div class="col-sm-3">
                        <strong>@lang('settings/model.ssh_timeout')</strong><br>
                        <small class="text-muted">@lang('settings/model.ssh_timeout_help')</small>
                    </div>
                    <div class="col-sm-9">
                        {{ $settings['ssh_timeout'] }}
                    </div>
                    <!-- ./ SSH connect timeout -->

                </div>
            </div>

            <!-- panel-defaults -->
            <h2 id="panel-defaults" class="mt-4">@lang('settings/title.defaults')</h2>
            <div class="border bg-light p-2">
                <h3>@lang('settings/title.remote_paths_section')</h3>
                <div class="row">

                    <!-- cmd_remote_updater -->
                    <div class="col-sm-3">
                        <strong>@lang('settings/model.cmd_remote_updater')</strong><br>
                        <small class="text-muted">@lang('settings/model.cmd_remote_updater_help')</small>
                    </div>
                    <div class="col-sm-9">
                        {{ $settings['cmd_remote_updater'] }}
                    </div>
                    <!-- ./ cmd_remote_updater -->

                    <!-- authorized_keys -->
                    <div class="col-sm-3">
                        <strong>@lang('settings/model.authorized_keys')</strong><br>
                        <small class="text-muted">@lang('settings/model.authorized_keys_help')</small>
                    </div>
                    <div class="col-sm-9">
                        {{ $settings['authorized_keys'] }}
                    </div>
                    <!-- ./ authorized_keys -->

                    <!-- SSH port -->
                    <div class="col-sm-3">
                        <strong>@lang('settings/model.ssh_port')</strong><br>
                        <small class="text-muted">@lang('settings/model.ssh_port_help')</small>
                    </div>
                    <div class="col-sm-9">
                        {{ $settings['ssh_port'] }}
                    </div>
                    <!-- ./ SSH port -->
                </div>

                <h3>@lang('settings/title.audit_section')</h3>
                <div class="row">
                    <!-- Audit Log retention -->
                    <div class="col-sm-3">
                        <strong>@lang('settings/model.audit_log_retention_days')</strong><br>
                        <small class="text-muted">@lang('settings/model.audit_log_retention_days_help')</small>
                    </div>
                    <div class="col-sm-9">
                        {{ $settings['audit_log_retention_days'] }}
                    </div>
                    <!-- ./ Audit Log retention -->
                </div>
            </div>

            <!-- panel-advanced-settings -->
            <h2 id="panel-advanced-settings" class="mt-4">@lang('settings/title.mixed_mode_section')</h2>
            <div class="border bg-light p-2">
                <div class="row">

                    <!-- mixed_mode -->
                    <div class="col-sm-3">
                        <strong>@lang('settings/model.mixed_mode')</strong><br>
                        <small class="text-muted">@lang('settings/model.mixed_mode_help')</small>
                    </div>
                    <div class="col-sm-9">
                        {{ $settings['mixed_mode'] }}
                    </div>
                    <!-- ./ mixed_mode -->

                @if($settings['mixed_mode'])
                    <!-- ssham_file -->
                        <div class="col-sm-3">
                            <strong>@lang('settings/model.ssham_file')</strong><br>
                            <small class="text-muted">@lang('settings/model.ssham_file_help')</small>
                        </div>
                        <div class="col-sm-9">
                            {{ $settings['ssham_file'] }}
                        </div>
                        <!-- ./ ssham_file -->


                        <!-- non_ssham_file -->
                        <div class="col-sm-3">
                            <strong>@lang('settings/model.non_ssham_file')</strong><br>
                            <small class="text-muted">@lang('settings/model.non_ssham_file_help')</small>
                        </div>
                        <div class="col-sm-9">
                            {{ $settings['non_ssham_file'] }}
                        </div>
                        <!-- ./ non_ssham_file -->

                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

