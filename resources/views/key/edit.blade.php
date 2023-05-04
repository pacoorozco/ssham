@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('key/title.key_update'))

{{-- Content Header --}}
@section('header')
    <i class="nav-icon fa fa-key"></i> @lang('key/title.key_update')
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

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    <!-- Card -->
    <div class="card">
        <x-form :action="route('keys.update', $key)" method="PUT">

        <div class="card-header @unless($key->enabled) bg-gray-dark @endunless">
            <h2 class="card-title">
                {{ $key->username }}
                @unless($key->enabled)
                    {{ $key->present()->enabledAsBadge() }}
                @endunless
            </h2>
        </div>

        <div class="card-body">
            <div class="form-row">
                <!-- left column -->
                <div class="col-md-6">

                    <fieldset>
                        <legend>@lang('key/title.key_identification_section')</legend>
                        <!-- username -->
                        <x-form-input name="username" :label="__('key/model.username')" :value="$key->username" readonly>
                            @slot('help')
                                <small class="form-text text-muted">
                                    @lang('key/messages.username_help')
                                </small>
                            @endslot
                        </x-form-input>
                        <!-- ./ username -->
                    </fieldset>

                    <!-- enabled -->
                    <fieldset class="form-group">
                        <div class="row">
                            <legend class="col-form-label col-sm-2 pt-0">
                                <strong>@lang('key/model.enabled')</strong>
                            </legend>
                            <div class="col-sm-10">
                                <x-form-group name="enabled">
                                    <x-form-radio name="enabled" value="1" :label="__('general.enabled')" :bind="$key"/>
                                    <x-form-radio name="enabled" value="0" :label="__('general.disabled')" :bind="$key"/>
                                </x-form-group>
                            </div>
                        </div>
                    </fieldset>
                    <!-- ./ enabled -->

                    <!-- SSH public key -->
                    <fieldset>
                        <legend>@lang('key/title.public_key_section')</legend>


                        <x-form-group name="operation">

                            <!-- maintain key option -->
                            <x-form-radio name="operation" id="maintain_public_key" :value="\App\Enums\KeyOperation::NOOP_OPERATION" :label="__('key/messages.maintain_public_key')" default>
                                @slot('help')
                                    <div id="maintain_public_key_form">
                                        <small class="form-text text-muted">
                                            <b>@lang('key/model.fingerprint')</b>: {{ $key->fingerprint }}
                                            <a data-toggle="collapse" href="#collapsePublicKey" aria-expanded="false"
                                               aria-controls="collapsePublicKey">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </small>
                                        <div class="collapse" id="collapsePublicKey">
                                            <pre class="key-code">{{ $key->public }}</pre>
                                        </div>
                                    </div>
                                @endslot
                            </x-form-radio>
                            <!-- ./maintain key option -->

                            <!-- create key option -->
                            <x-form-radio name="operation" id="create_public_key" :value="\App\Enums\KeyOperation::CREATE_OPERATION" :label="__('key/messages.create_public_key')">
                                @slot('help')
                                <div id="create_public_key_form">
                                    <small class="form-text text-muted">@lang('key/messages.create_public_key_help') @lang('key/messages.change_public_key_help_notice')</small>
                                </div>
                                @endslot
                            </x-form-radio>
                            <!-- ./create key option -->

                            <!-- import key option -->
                            <x-form-radio name="operation" id="import_public_key" :value="\App\Enums\KeyOperation::IMPORT_OPERATION" :label="__('key/messages.import_public_key')">
                                @slot('help')
                                    <div id="import_public_key_form">
                                        <x-form-textarea name="public_key" id="public_key" rows="5" :placeholder="__('key/messages.import_public_key_help')" required/>
                                    </div>
                                @endslot
                            </x-form-radio>
                            <!-- ./import key option -->

                        </x-form-group>

                    </fieldset>
                    <!-- ./ SSH public key -->

                </div>
                <!-- ./ left column -->

                <!-- right column -->
                <div class="col-md-6">

                    <fieldset>
                        <legend>@lang('key/title.membership_section')</legend>

                        <!-- key's groups -->
                        <x-form-select name="groups[]" :label="__('key/model.groups')" :options="$groups" :default="$key->groups->pluck('id')" multiple class="search-select">
                            @slot('help')
                                <small class="form-text text-muted">
                                    @lang('key/messages.groups_help')
                                </small>
                            @endslot
                        </x-form-select>
                        <!-- ./ key's groups -->
                    </fieldset>

                    <fieldset>@lang('key/title.status_section')</fieldset>
                    <dl class="row">
                        <!-- created at -->
                        <dt class="col-3">
                            <strong>@lang('key/model.created_at')</strong>
                        </dt>
                        <dd class="col-9">
                            {{ $key->present()->createdAtForHumans() }} ({{ $key->present()->created_at }})
                        </dd>
                        <!-- ./ created at -->

                        <!-- updated at -->
                        <dt class="col-3">
                            <strong>@lang('key/model.updated_at')</strong>
                        </dt>
                        <dd class="col-9">
                            {{ $key->present()->updatedAtForHumans() }} ({{ $key->present()->updated_at }})
                        </dd>
                        <!-- ./ updated at -->

                    </dl>

                    <fieldset class="mt-5">
                        <legend>@lang('key/messages.danger_zone_section')</legend>

                        @can('delete', $key)
                        <ul class="list-group border border-danger">
                            <li class="list-group-item">
                                <strong>@lang('key/messages.delete_button')</strong>
                                <button type="button" class="btn btn-outline-danger btn-sm float-right"
                                        data-toggle="modal"
                                        data-target="#confirmationModal">
                                    @lang('key/messages.delete_button')
                                </button>
                                <p>@lang('key/messages.delete_help')</p>
                            </li>
                        </ul>
                        @else
                            <p class="from-text text-muted">@lang('key/messages.delete_avoided')</p>
                        @endcan
                    </fieldset>

                </div>
                <!-- ./right column -->

            </div>
        </div>
        <div class="card-footer">
            <!-- Form Actions -->
            <x-form-submit class="btn-success">
                @lang('general.update')
            </x-form-submit>

            <a href="{{ route('keys.index') }}" class="btn btn-link" role="button">
                {{ __('general.cancel') }}
            </a>
            <!-- ./ form actions -->
        </div>

        </x-form>
    </div>
    <!-- ./ card -->

    @can('delete', $key)
    <!-- confirmation modal -->
    <x-modals.confirmation
        action="{{ route('keys.destroy', $key) }}"
        confirmationText="{{ $key->username }}"
        buttonText="{{ __('key/messages.delete_confirmation_button') }}">

        <div class="alert alert-warning" role="alert">
            @lang('key/messages.delete_confirmation_warning', ['username' => $key->username])
        </div>

    </x-modals.confirmation>
    <!-- ./ confirmation modal -->
    @endcan

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
            placeholder: "@lang('general.filter_box_help')",
            allowClear: true,
            language: "@lang('site.language_short')"
        });


        $(function () {
            var $radioValue = $('input:radio[name=operation]:checked').val();
            switch ($radioValue) {
                case '{{ \App\Enums\KeyOperation::CREATE_OPERATION }}':
                    enablePublicKeyCreation();
                    break;
                case '{{ \App\Enums\KeyOperation::IMPORT_OPERATION }}':
                    enablePublicKeyImport();
                    break;
                case '{{ \App\Enums\KeyOperation::NOOP_OPERATION }}':
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
            $("#public_key").prop("disabled", true);
        }

        function enablePublicKeyCreation() {
            $("#maintain_public_key_form").addClass("d-none");
            $("#create_public_key_form").removeClass("d-none");
            $("#import_public_key_form").addClass("d-none");
            $("#public_key").prop("disabled", true);
        }

        function enablePublicKeyImport() {
            $("#maintain_public_key_form").addClass("d-none");
            $("#import_public_key_form").removeClass("d-none");
            $("#create_public_key_form").addClass("d-none");
            $("#public_key").prop("disabled", false);
        }
    </script>
@endpush
