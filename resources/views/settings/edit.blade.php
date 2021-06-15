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
    <li class="breadcrumb-item">
        <a href="{{ route('settings.index') }}">
            @lang('site.settings')
        </a>
    </li>
    <li class="breadcrumb-item active">
        @lang('settings/title.update')
    </li>
@endsection

{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    <div class="card card-tabs">
        {!! Form::open(['route' => ['settings.update'], 'method' => 'put']) !!}

        <div class="card-header p-0 pt-1 boder-bottom-0">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a href="#panel-ssh-settings" id="panel-ssh-settings-tab" class="nav-link active"
                       data-toggle="tab" role="tab" aria-controls="panel-ssh-settings"
                       aria-selected="true">
                        @lang('settings/title.ssh_settings')
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#panel-defaults" id="panel-defaults-tab" class="nav-link"
                       data-toggle="tab" role="tab" aria-controls="panel-defaults"
                       aria-selected="false">
                        @lang('settings/title.defaults')
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

                <!-- panel-defaults -->
                <div class="tab-pane fade" id="panel-defaults" role="tabpanel"
                     aria-labelledby="panel-defaults-tab">

                    <fieldset>
                        <legend>@lang('settings/title.defaults_section')</legend>

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

                        <!-- temp_dir -->
                        <div class="form-group">
                            {!! Form::label('temp_dir', __('settings/model.temp_dir')) !!}
                            {!! Form::text('temp_dir', $settings['temp_dir'], array('class' => 'form-control' . ($errors->has('temp_dir') ? ' is-invalid' : ''), 'required' => 'required')) !!}
                            <span class="form-text text-muted">@lang('settings/model.temp_dir_help')</span>
                            @error('temp_dir'))
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- ./ temp_dir -->

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

                    </fieldset>
                </div>

                <!-- panel-advanced-settings -->
                <div class="tab-pane fade" id="panel-advanced-settings" role="tabpanel"
                     aria-labelledby="panel-advanced-settings-tab">

                    <!-- mixed_mode -->
                    <fieldset class="form-group row">
                        <legend class="col-form-label col-sm-2 float-sm-left pt-0">
                            <strong>@lang('settings/title.mixed_mode_section')</strong>
                        </legend>
                        <div class="col-sm-10">
                            <div class="form-check">
                                {!! Form::radio('mixed_mode', 1, ($settings['mixed_mode'] == 1), array('class' => 'form-check-input', 'id' => 'enable_mixed_mode')) !!}
                                {!! Form::label('mixed_mode', __('general.enabled'), array('class' => 'form-check-label')) !!}
                            </div>
                            <div class="form-check">
                                {!! Form::radio('mixed_mode', 0, ($settings['mixed_mode'] == 0), array('class' => 'form-check-input', 'id' => 'disable_mixed_mode')) !!}
                                {!! Form::label('mixed_mode', __('general.disabled'), array('class' => 'form-check-label')) !!}
                            </div>
                        </div>
                    </fieldset>
                    <!-- ./ mixed_mode -->

                    <fieldset id="mixed_mode_options_form">

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

                    </fieldset>

                </div>

            </div>
        </div>
        <div class="card-footer">
            {!! Form::button(__('general.save'), array('type' => 'submit', 'class' => 'btn btn-success')) !!}
            <a href="{{ route('settings.index') }}" class="btn btn-link" role="button">
                {{ __('general.cancel') }}
            </a>
        </div>

        {!! Form::close() !!}
    </div>

@endsection

@push('scripts')
    <script>
        $(function () {
            showMixedModeOptionsIfNeeded();

            $('#enable_mixed_mode').click(function () {
                $('#mixed_mode_options_form').removeClass("d-none");
            });

            $('#disable_mixed_mode').click(function () {
                $('#mixed_mode_options_form').addClass("d-none");
            });

            function showMixedModeOptionsIfNeeded() {
                var radioValue = $('input:radio[name=mixed_mode]:checked').val();
                if (radioValue === '1') {
                    $('#mixed_mode_options_form').removeClass("d-none");
                } else {
                    $('#mixed_mode_options_form').addClass("d-none");
                }
            }
        });
    </script>
@endpush
