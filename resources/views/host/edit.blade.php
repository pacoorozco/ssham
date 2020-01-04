@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('host/title.host_update'))

{{-- Content Header --}}
@section('header')
    @lang('host/title.host_update')
    <small class="text-muted">{{ $host->getFullHostname() }}</small>
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
    <div class="container-fluid">

        <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

        <!-- Card -->
        <div class="card">

            {!! Form::model($host, ['route' => ['hosts.update', $host->id], 'method' => 'put']) !!}

            <div class="card-body">
                <div class="form-row">
                    <!-- left column -->
                    <div class="col-md-6">
                        <!-- hostname -->
                        <div class="form-group">
                            {!! Form::label('hostname', __('host/model.full_hostname')) !!}
                            {!! Form::text('hostname', $host->getFullHostname(), array('class' => 'form-control', 'disabled' => 'disabled')) !!}
                        </div>
                        <!-- ./ hostname -->

                        <!-- enabled -->
                        <fieldset class="form-group">
                            <div class="row">
                                <legend class="col-form-label col-sm-2 pt-0"><strong>@lang('host/model.enabled')</strong>
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
                    </div>
                    <!-- ./ left column -->

                    <!-- right column -->
                    <div class="col-md-6">
                        <!-- host groups -->
                        <div class="form-group">
                            {!! Form::label('groups[]', __('host/model.groups')) !!}
                            {!! Form::select('groups[]', $groups, $host->hostgroups->pluck('id'), array('multiple' => 'multiple', 'class' => 'form-control search-select')) !!}
                        </div>
                        <!-- ./ host groups -->
                    </div>
                    <!-- ./ right column -->
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
            placeholder: "@lang('user/messages.groups_help')",
            allowClear: true,
            language: "@lang('site.language_short')",
        });
    </script>
@endpush
