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
    <div class="container-fluid">

        <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

        <!-- Card -->
        <div class="card">
            {!! Form::open(['route' => 'hosts.store', 'method' => 'post']) !!}

            <div class="card-body">
                <div class="form-row">
                    <!-- left column -->
                    <div class="col-md-6">

                        <fieldset>
                            <legend>@lang('host/title.host_information_section')</legend>
                            <!-- hostname -->
                            <div class="form-group">
                                {!! Form::label('hostname', __('host/model.hostname')) !!}
                                {!! Form::text('hostname', null, array('class' => 'form-control' . ($errors->has('hostname') ? ' is-invalid' : ''), 'required' => 'required', 'autofocus' => 'autofocus')) !!}
                                @error('hostname')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror

                            </div>
                            <!-- ./ hostname -->

                            <!-- username -->
                            <div class="form-group">
                                {!! Form::label('username', __('host/model.username')) !!}
                                {!! Form::text('username', 'root', array('class' => 'form-control' . ($errors->has('username') ? ' is-invalid' : ''), 'required' => 'required')) !!}
                                @error('username')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- ./ username -->
                        </fieldset>

                        <fieldset>
                            <legend>@lang('host/title.advanced_config_section')</legend>
                            <!-- port -->
                            <div class="form-group">
                                {!! Form::label('port', __('host/model.port')) !!}
                                {!! Form::number('port', setting('ssh_port'), array('class' => 'form-control' . ($errors->has('port') ? ' is-invalid' : ''), 'required' => 'required')) !!}
                                @error('port')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- ./ port -->

                            <!-- authorized_keys_file -->
                            <div class="form-group">
                                {!! Form::label('authorized_keys_file', __('host/model.authorized_keys_file')) !!}
                                {!! Form::text('authorized_keys_file', setting('authorized_keys'), array('class' => 'form-control' . ($errors->has('authorized_keys_file') ? ' is-invalid' : ''), 'required' => 'required')) !!}
                                @error('authorized_keys_file')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
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
                                {!! Form::select('groups[]', $groups, null, array('multiple' => 'multiple', 'class' => 'form-control search-select')) !!}
                            </div>
                            <!-- ./ host groups -->
                        </fieldset>
                    </div>

                </div>
            </div>
            <div class="card-footer">
                <!-- Form Actions -->
                <a href="{{ route('hosts.index') }}" class="btn btn-primary" role="button">
                    <i class="fa fa-arrow-left"></i> {{ __('general.back') }}
                </a>
            {!! Form::button('<i class="fa fa-save"></i> ' . __('general.save'), array('type' => 'submit', 'class' => 'btn btn-success')) !!}
            <!-- ./ form actions -->
            </div>

            {!! Form::close() !!}
        </div>
        <!-- ./ card -->
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
        $(".search-select").select2({
            placeholder: "@lang('host/messages.groups_help')",
            allowClear: true,
            language: "@lang('site.language_short')",
        });
    </script>
@endpush
