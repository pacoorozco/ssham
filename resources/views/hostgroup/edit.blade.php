@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('hostgroup/title.host_group_update'))

{{-- Content Header --}}
@section('header')
    <i class="fa fa-server"></i> @lang('hostgroup/title.host_group_update')
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('hostgroups.index') }}">
            @lang('site.host_groups')
        </a>
    </li>
    <li class="breadcrumb-item active">
        @lang('hostgroup/title.host_group_update')
    </li>
@endsection

{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    <div class="card">

        {!! Form::model($hostgroup, ['route' => ['hostgroups.update', $hostgroup], 'method' => 'put']) !!}

        <div class="card-header">
            <h2 class="card-title">
                {{ $hostgroup->name }}
            </h2>
        </div>

        <div class="card-body">
            <div class="form-row">
                <!-- left column -->
                <div class="col-md-4">

                    <fieldset>
                        <legend>@lang('hostgroup/messages.basic_information_section')</legend>
                        <!-- name -->
                        <div class="form-group">
                            {!! Form::label('name', __('hostgroup/model.name')) !!}
                            <small class="form-text text-muted">@lang('hostgroup/messages.name_help')</small>
                            {!! Form::text('name', null, array('class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''), 'required' => 'required', 'autofocus' => 'autofocus')) !!}
                            @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror

                        </div>
                        <!-- ./ name -->

                        <!-- description -->
                        <div class="form-group">
                            {!! Form::label('description', __('hostgroup/model.description')) !!}
                            <small class="form-text text-muted">@lang('hostgroup/messages.description_help')</small>
                            {!! Form::textarea('description', null, array('class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : ''))) !!}
                            @error('description'))
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- ./ description -->
                    </fieldset>
                </div>
                <!-- ./ left column -->

                <!-- right column -->
                <div class="col-md-8">
                    <fieldset>
                        <legend>@lang('hostgroup/messages.group_members_section')</legend>
                        <!-- host's groups -->
                        <div class="form-group">
                            {!! Form::label('hosts[]', __('hostgroup/model.hosts')) !!}
                            {!! Form::select('hosts[]', $hosts, $hostgroup->hosts->pluck('id'), array('multiple' => 'multiple', 'class' => 'form-control duallistbox')) !!}
                            <small class="form-text text-muted">@lang('hostgroup/messages.group_help')</small>
                        </div>
                        <!-- ./ host's groups -->
                    </fieldset>

                    <fieldset class="mt-3">
                        <legend>@lang('hostgroup/messages.danger_zone_section')</legend>

                        @can('delete', $hostgroup)
                        <ul class="list-group border border-danger">
                            <li class="list-group-item">
                                <strong>@lang('hostgroup/messages.delete_button')</strong>
                                <button type="button" class="btn btn-outline-danger btn-sm float-right"
                                        data-toggle="modal"
                                        data-target="#confirmationModal">
                                    @lang('hostgroup/messages.delete_button')
                                </button>
                                <p>@lang('hostgroup/messages.delete_help')</p>
                            </li>
                        </ul>
                        @else
                            <p class="from-text text-muted">@lang('hostgroup/messages.delete_avoided')</p>
                        @endcan
                    </fieldset>
                </div>
                <!-- ./right column -->
            </div>
        </div>
        <div class="card-footer">
            <!-- Form Actions -->
            {!! Form::button(__('general.update'), array('type' => 'submit', 'class' => 'btn btn-success')) !!}
            <a href="{{ route('hostgroups.index') }}" class="btn btn-link" role="button">
                @lang('general.cancel')
            </a>
            <!-- ./ form actions -->
        </div>

        {!! Form::close() !!}
    </div>

    @can('delete', $hostgroup)
        <!-- confirmation modal -->
        <x-modals.confirmation
            action="{{ route('hostgroups.destroy', $hostgroup) }}"
            confirmationText="{{ $hostgroup->name }}"
            buttonText="{{ __('hostgroup/messages.delete_confirmation_button') }}">

            <div class="alert alert-warning" role="alert">
                @lang('hostgroup/messages.delete_confirmation_warning', ['name' => $hostgroup->name])
            </div>
        </x-modals.confirmation>
        <!-- ./ confirmation modal -->
    @endcan
@endsection

{{-- Styles --}}
@push('styles')
    <link rel="stylesheet" type="text/css"
          href="{{ asset('vendor/AdminLTE/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css') }}">
@endpush

{{-- Scripts --}}
@push('scripts')
    <script
        src="{{ asset('vendor/AdminLTE/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js') }}"></script>
    <script>
        $('.duallistbox').bootstrapDualListbox({
            nonSelectedListLabel: '{{ __('hostgroup/messages.available_hosts_section') }}',
            selectedListLabel: '{{ __('hostgroup/messages.selected_hosts_section') }}',
        });
    </script>
@endpush

