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

        <x-forms.form :action="route('settings.update')" method="PUT">

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
                                <x-forms.textarea name="private_key" :label="__('settings/model.private_key')" :default="$settings['private_key']" cols="50" rows="10" required>
                                    @slot('help')
                                        <small class="form-text text-muted">
                                            @lang('settings/model.private_key_help')
                                        </small>
                                    @endslot
                                </x-forms.textarea>
                                <!-- ./ private_key -->
                            </div>
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend>@lang('settings/title.ssh_options_section')</legend>

                        <!-- SSH connect timeout -->
                        <x-forms.input name="ssh_timeout" type="number" :label="__('settings/model.ssh_timeout')" required :default="$settings['ssh_timeout']">
                            @slot('help')
                                <small class="form-text text-muted">
                                    @lang('settings/model.ssh_timeout_help')
                                </small>
                            @endslot
                        </x-forms.input>
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
                        <x-forms.input name="cmd_remote_updater" :label="__('settings/model.cmd_remote_updater')" required :default="$settings['cmd_remote_updater']">
                            @slot('help')
                                <small class="form-text text-muted">
                                    @lang('settings/model.cmd_remote_updater_help')
                                </small>
                            @endslot
                        </x-forms.input>
                        <!-- ./ cmd_remote_updater -->

                        <!-- authorized_keys -->
                        <x-forms.input name="authorized_keys" :label="__('settings/model.authorized_keys')" required :default="$settings['authorized_keys']">
                            @slot('help')
                                <small class="form-text text-muted">
                                    @lang('settings/model.authorized_keys_help')
                                </small>
                            @endslot
                        </x-forms.input>
                        <!-- ./ authorized_keys -->

                        <!-- SSH port -->
                        <x-forms.input name="ssh_port" type="number" :label="__('settings/model.ssh_port')" required :default="$settings['ssh_port']">
                            @slot('help')
                                <small class="form-text text-muted">
                                    @lang('settings/model.ssh_port_help')
                                </small>
                            @endslot
                        </x-forms.input>
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
                            <div class="form-group" name="mixed_mode">
                                <x-forms.radio name="mixed_mode" id="enable_mixed_mode" value="1" :label="__('general.enabled')" :checked="($settings['mixed_mode'] == 1)"/>
                                <x-forms.radio name="mixed_mode" id="disable_mixed_mode" value="0" :label="__('general.disabled')" :checked="($settings['mixed_mode'] == 0)"/>
                            </div>
                        </div>
                    </fieldset>
                    <!-- ./ mixed_mode -->

                    <fieldset id="mixed_mode_options_form">

                        <!-- ssham_file -->
                        <x-forms.input name="ssham_file" :label="__('settings/model.ssham_file')" required :default="$settings['ssham_file']">
                            @slot('help')
                                <small class="form-text text-muted">
                                    @lang('settings/model.ssham_file_help')
                                </small>
                            @endslot
                        </x-forms.input>
                        <!-- ./ ssham_file -->

                        <!-- non_ssham_file -->
                        <x-forms.input name="non_ssham_file" :label="__('settings/model.non_ssham_file')" required :default="$settings['non_ssham_file']">
                            @slot('help')
                                <small class="form-text text-muted">
                                    @lang('settings/model.non_ssham_file_help')
                                </small>
                            @endslot
                        </x-forms.input>
                        <!-- ./ non_ssham_file -->

                    </fieldset>

                </div>

            </div>
        </div>
        <div class="card-footer">
            <x-forms.submit class="btn-success">
                @lang('general.save')
            </x-forms.submit>

            <a href="{{ route('settings.index') }}" class="btn btn-link" role="button">
                {{ __('general.cancel') }}
            </a>
        </div>

        </x-forms.form>
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
