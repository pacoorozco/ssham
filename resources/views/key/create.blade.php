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
        <x-form :action="route('keys.store')">

        <div class="card-body">
            <div class="form-row">
                <!-- left column -->
                <div class="col-md-6">

                    <fieldset>
                        <legend>@lang('key/title.key_identification_section')</legend>
                        <!-- name -->
                        <x-form-input name="name" :label="__('key/model.name')" required autofocus>
                            @slot('help')
                                <small class="form-text text-muted">
                                    @lang('key/messages.name_help')
                                </small>
                            @endslot
                        </x-form-input>
                        <!-- ./ name -->
                    </fieldset>

                    <!-- SSH public key -->
                    <fieldset>
                        <legend>@lang('key/title.public_key_section')</legend>

                        <x-form-group name="operation">

                            <!-- create key option -->
                            <x-form-radio name="operation" id="create_public_key" :value="\App\Enums\KeyOperation::CREATE_OPERATION->value" :label="__('key/messages.create_public_key')" default>
                                @slot('help')
                                    <small class="form-text text-muted">
                                        @lang('key/messages.create_public_key_help')
                                    </small>
                                @endslot
                            </x-form-radio>
                            <!-- ./create key option -->

                            <!-- import key option -->
                            <x-form-radio name="operation" id="import_public_key" :value="\App\Enums\KeyOperation::IMPORT_OPERATION->value" :label="__('key/messages.import_public_key')">
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
                        <x-form-select name="groups[]" :label="__('key/model.groups')" :options="$groups" multiple class="search-select">
                            @slot('help')
                                <small class="form-text text-muted">
                                    @lang('key/messages.groups_help')
                                </small>
                            @endslot
                        </x-form-select>
                        <!-- ./ key's groups -->
                    </fieldset>

                </div>
                <!-- ./right column -->

            </div>
        </div>
        <div class="card-footer">
            <!-- Form Actions -->
            <x-form-submit class="btn-success">
                @lang('general.create')
            </x-form-submit>

            <a href="{{ route('keys.index') }}" class="btn btn-link" role="button">
                {{ __('general.cancel') }}
            </a>
            <!-- ./ form actions -->
        </div>

        </x-form>
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

