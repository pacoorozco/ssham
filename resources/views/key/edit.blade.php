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
        {!! Form::model($key, ['route' => ['keys.update', $key->id], 'method' => 'put']) !!}

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
                        <div class="form-group">
                            {!! Form::label('username', __('key/model.username')) !!}
                            {!! Form::text('username', $key->username, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                            <span class="form-text text-muted">@lang('key/messages.username_help')</span>
                            @error('username')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- ./ username -->
                    </fieldset>

                    <!-- enabled -->
                    <fieldset class="form-group">
                        <div class="row">
                            <legend class="col-form-label col-sm-2 pt-0">
                                <strong>@lang('key/model.enabled')</strong>
                            </legend>
                            <div class="col-sm-10">
                                <div class="form-check">
                                    {!! Form::radio('enabled', 0, null, array('class' => 'form-check-input')) !!}
                                    {!! Form::label('enabled', __('general.disabled'), array('class' => 'form-check-label')) !!}
                                </div>
                                <div class="form-check">
                                    {!! Form::radio('enabled', 1, null, array('class' => 'form-check-input')) !!}
                                    {!! Form::label('enabled', __('general.enabled'), array('class' => 'form-check-label')) !!}
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <!-- ./ enabled -->

                    <!-- SSH public key -->
                    <fieldset>
                        <legend>@lang('key/title.public_key_section')</legend>

                        <div class="form-group">
                            <!-- maintain RSA key -->
                            <div class="form-check">
                                {!! Form::radio('operation', \App\Enums\KeyOperation::NOOP_OPERATION, true, array('class' => 'form-check-input', 'id' => 'maintain_public_key', 'checked' => 'checked')) !!}
                                {!! Form::label('maintain_public_key', __('key/messages.maintain_public_key'), array('class' => 'form-check-label')) !!}
                                <div id="maintain_public_key_form">
                                    <p>
                                        <b>@lang('key/model.fingerprint')</b>: {{ $key->fingerprint }}
                                        <a data-toggle="collapse" href="#collapsePublicKey" aria-expanded="false"
                                           aria-controls="collapsePublicKey">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </p>
                                    <div class="collapse" id="collapsePublicKey">
                                        <pre class="key-code">{{ $key->public }}</pre>
                                    </div>
                                </div>
                            </div>
                            <!-- ./ maintain RSA key -->
                            <!-- create RSA key -->
                            <div class="form-check">
                                {!! Form::radio('operation', \App\Enums\KeyOperation::CREATE_OPERATION, false, array('class' => 'form-check-input', 'id' => 'create_public_key')) !!}
                                {!! Form::label('create_public_key', __('key/messages.create_public_key'), array('class' => 'form-check-label')) !!}
                                <div id="create_public_key_form">
                                    <p class="form-text text-muted">@lang('key/messages.create_public_key_help') @lang('key/messages.change_public_key_help_notice')</p>
                                </div>
                            </div>
                            <!-- ./ create RSA key -->

                            <!-- import public_key -->
                            <div class="form-check">
                                {!! Form::radio('operation', \App\Enums\KeyOperation::IMPORT_OPERATION, false, array('class' => 'form-check-input', 'id' => 'import_public_key')) !!}
                                {!! Form::label('import_public_key', __('key/messages.import_public_key'), array('class' => 'form-check-label')) !!}
                                <div id="import_public_key_form">
                                    {!! Form::textarea('public_key', null, array('class' => 'form-control' . ($errors->has('public_key') ? ' is-invalid' : ''), 'id' => 'public_key', 'rows' => '5', 'required' => 'required', 'placeholder' => __('key/messages.import_public_key_help'))) !!}
                                    <span class="form-text text-muted">
                                            @lang('key/messages.change_public_key_help_notice')
                                        </span>
                                    @error('public_key')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <!-- ./ import public_key -->

                        </div>
                    </fieldset>
                    <!-- ./ SSH public key -->

                </div>
                <!-- ./ left column -->

                <!-- right column -->
                <div class="col-md-6">

                    <fieldset>
                        <legend>@lang('key/title.membership_section')</legend>

                        <!-- key's groups -->
                        <div class="form-group">
                            {!! Form::label('groups[]', __('key/model.groups')) !!}
                            {!! Form::select('groups[]', $groups, $key->groups->pluck('id'), array('multiple' => 'multiple', 'class' => 'form-control search-select')) !!}

                            <small class="form-text text-muted">
                                {{ __('key/messages.groups_help') }}
                            </small>
                        </div>
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
                    </fieldset>

                </div>
                <!-- ./right column -->

            </div>
        </div>
        <div class="card-footer">
            <!-- Form Actions -->
            {!! Form::button(__('general.update'), array('type' => 'submit', 'class' => 'btn btn-success')) !!}
            <a href="{{ route('keys.index') }}" class="btn btn-link" role="button">
                {{ __('general.cancel') }}
            </a>
            <!-- ./ form actions -->
        </div>

        {!! Form::close() !!}
    </div>
    <!-- ./ card -->

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
