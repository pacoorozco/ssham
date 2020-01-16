@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('hostgroup/title.create_a_new_host_group'))

{{-- Content Header --}}
@section('header')
    @lang('hostgroup/title.create_a_new_host_group')
    <small class="text-muted">@lang('hostgroup/title.create_a_new_host_group_subtitle')</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('hostgroups.index') }}">
            @lang('site.host_groups')
        </a>
    </li>
    <li class="breadcrumb-item active">
        @lang('hostgroup/title.create_a_new_host_group')
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
            {!! Form::open(['route' => 'hostgroups.store', 'method' => 'post']) !!}
            <div class="card-body">
                <div class="form-row">
                    <!-- left column -->
                    <div class="col-md-6">

                        <!-- name -->
                        <div class="form-group">
                            {!! Form::label('name', __('hostgroup/model.name')) !!}
                            {!! Form::text('name', null, array('class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''), 'required' => 'required', 'autofocus' => 'autofocus')) !!}
                            @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror

                        </div>
                        <!-- ./ name -->

                        <!-- description -->
                        <div class="form-group">
                            {!! Form::label('description', __('hostgroup/model.description')) !!}
                            {!! Form::textarea('description', null, array('class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : ''))) !!}
                            @error('description'))
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- ./ description -->
                    </div>
                    <!-- ./ left column -->

                    <!-- right column -->
                    <div class="col-md-6">
                        <!-- host's groups -->
                        <div class="form-group">
                            {!! Form::label('hosts[]', __('hostgroup/model.hosts')) !!}
                            {!! Form::select('hosts[]', $hosts, null, array('multiple' => 'multiple', 'class' => 'form-control search-select')) !!}
                        </div>
                        <!-- ./ host's groups -->
                    </div>
                    <!-- ./right column -->
                </div>
            </div>
            <div class="card-footer">
                <!-- Form Actions -->
                <a href="{{ route('hostgroups.index') }}" class="btn btn-primary" role="button">
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

