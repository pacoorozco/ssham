@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('site.settings'))

{{-- Content Header --}}
@section('header')
    @lang('settings/title.title')
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
    <div class="container-fluid">

        <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

        <div class="card card-tabs">
            {!! Form::open(['route' => ['settings.update'], 'method' => 'put']) !!}

            <div class="card-header">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a href="#panel-ssh-settings" id="panel-ssh-settings-tab" class="nav-link active"
                           data-toggle="tab" role="tab" aria-controls="panel-ssh-settings"
                           aria-selected="true">
                            @lang('settings/title.ssh_settings')
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#panel-advanced-settings" id="panel-advanced-settings-tab" class="nav-link"
                           data-toggle="tab" role="tab" aria-controls="panel-advanced-settings"
                           aria-selected="false">
                            @lang('settings/title.advanced_settings')
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content">

                    <!-- panel-ssh-settings -->
                    <div class="tab-pane fade show active" id="panel-ssh-settings" role="tabpanel"
                         aria-labelledby="panel-ssh-settings-tab">

                        <fieldset>
                            <legend>@lang('settings/title.ssh_credentials_section')</legend>

                            <div class="form-row">
                                <div class="col-6">
                                    <!-- private_key -->
                                    <div class="form-group">
                                        {!! Form::label('private_key', __('settings/model.private_key')) !!}
                                        {!! Form::textarea('private_key', $settings['private_key'], array('class' => 'form-control' . ($errors->has('private_key') ? ' is-invalid' : ''), 'required' => 'required')) !!}
                                        <span
                                            class="form-text text-muted">@lang('settings/model.private_key_help')</span>
                                        @error('private_key'))
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <!-- ./ private_key -->
                                </div>
                                <div class="col-6">
                                    <!-- public_key -->
                                    <div class="form-group">
                                        {!! Form::label('public_key', __('settings/model.public_key')) !!}
                                        {!! Form::textarea('public_key', $settings['public_key'], array('class' => 'form-control' . ($errors->has('public_key') ? ' is-invalid' : ''), 'required' => 'required')) !!}
                                        <span
                                            class="form-text text-muted">@lang('settings/model.public_key_help')</span>
                                        @error('public_key'))
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <!-- ./ public_key -->
                                </div>
                            </div>
                        </fieldset>

                        <fieldset>
                            <legend>@lang('settings/title.ssh_options_section')</legend>

                            <!-- SSH port -->
                            <div class="form-group">
                                {!! Form::label('ssh_port', __('settings/model.ssh_port')) !!}
                                {!! Form::number('ssh_port', $settings['ssh_port'], array('class' => 'form-control' . ($errors->has('ssh_port') ? ' is-invalid' : ''), 'required' => 'required')) !!}
                                <span class="form-text text-muted">@lang('settings/model.ssh_port_help')</span>
                                @error('ssh_port'))
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- ./ SSH port -->

                            <!-- SSH connect timeout -->
                            <div class="form-group">
                                {!! Form::label('ssh_timeout', __('settings/model.ssh_timeout')) !!}
                                {!! Form::number('ssh_timeout', $settings['ssh_timeout'], array('class' => 'form-control' . ($errors->has('ssh_timeout') ? ' is-invalid' : ''), 'required' => 'required')) !!}
                                <span class="form-text text-muted">@lang('settings/model.ssh_timeout_help')</span>
                                @error('ssh_timeout'))
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- ./ SSH connect timeout -->

                        </fieldset>

                    </div>
                    <!-- ./ panel-ssh-settings -->

                    <!-- panel-advanced-settings -->
                    <div class="tab-pane fade" id="panel-advanced-settings" role="tabpanel"
                         aria-labelledby="panel-advanced-settings-tab">

                        <fieldset>
                            <legend>@lang('settings/title.remote_paths_section')</legend>

                            <!-- cmd_remote_updater -->
                            <div class="form-group">
                                {!! Form::label('cmd_remote_updater', __('settings/model.cmd_remote_updater')) !!}
                                {!! Form::text('cmd_remote_updater', $settings['cmd_remote_updater'], array('class' => 'form-control' . ($errors->has('cmd_remote_updater') ? ' is-invalid' : ''), 'required' => 'required')) !!}
                                <span
                                    class="form-text text-muted">@lang('settings/model.cmd_remote_updater_help')</span>
                                @error('cmd_remote_updater'))
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- ./ cmd_remote_updater -->

                            <!-- authorized_keys -->
                            <div class="form-group">
                                {!! Form::label('authorized_keys', __('settings/model.authorized_keys')) !!}
                                {!! Form::text('authorized_keys', $settings['authorized_keys'], array('class' => 'form-control' . ($errors->has('authorized_keys') ? ' is-invalid' : ''), 'required' => 'required')) !!}
                                <span class="form-text text-muted">@lang('settings/model.authorized_keys_help')</span>
                                @error('authorized_keys'))
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- ./ authorized_keys -->
                        </fieldset>

                        <fieldset>
                            <legend>@lang('settings/title.mixed_mode_section')</legend>

                            <!-- mixed_mode -->
                            <div class="form-group">
                                {!! Form::label('mixed_mode', __('settings/model.mixed_mode')) !!}
                                {!! Form::select('mixed_mode', array('1' => __('general.yes'), '0' => __('general.no')), $settings['mixed_mode'], array('class' => 'form-control' . ($errors->has('mixed_mode') ? ' is-invalid' : ''), 'required' => 'required', 'id' => 'mixed_mode_select')) !!}
                                <span class="form-text text-muted">@lang('settings/model.mixed_mode_help')</span>
                                @error('mixed_mode'))
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- ./ mixed_mode -->

                            <div id="mixed_mode_options_form">

                                <!-- ssham_file -->
                                <div class="form-group">
                                    {!! Form::label('ssham_file', __('settings/model.ssham_file')) !!}
                                    {!! Form::text('ssham_file', $settings['ssham_file'], array('class' => 'form-control'. ($errors->has('ssham_file') ? ' is-invalid' : ''), 'required' => 'required')) !!}
                                    <span class="form-text text-muted">@lang('settings/model.ssham_file_help')</span>
                                    @error('ssham_file'))
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- ./ ssham_file -->

                                <!-- non_ssham_file -->
                                <div class="form-group">
                                    {!! Form::label('non_ssham_file', __('settings/model.non_ssham_file')) !!}
                                    {!! Form::text('non_ssham_file', $settings['non_ssham_file'], array('class' => 'form-control' . ($errors->has('non_ssham_file') ? ' is-invalid' : ''), 'required' => 'required')) !!}
                                    <span
                                        class="form-text text-muted">@lang('settings/model.non_ssham_file_help')</span>
                                    @error('non_ssham_file'))
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- ./ non_ssham_file -->

                            </div>
                        </fieldset>

                    </div>
                </div>
            </div>
            <div class="card-footer">
                {!! Form::button('<i class="fa fa-save"></i> ' . __('general.save'), array('type' => 'submit', 'class' => 'btn btn-success')) !!}
            </div>

            {!! Form::close() !!}
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            showMixedModeOptionsIfNeeded();

            $("#mixed_mode_select").change(function () {
                showMixedModeOptionsIfNeeded();
            });

            function showMixedModeOptionsIfNeeded() {
                if ($("#mixed_mode_select").val() === '1') {
                    $("#mixed_mode_options_form").removeClass("d-none");
                } else {
                    $("#mixed_mode_options_form").addClass("d-none");
                }
            }
        });
    </script>
@endpush
