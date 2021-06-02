@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('keygroup/title.key_group_update'))

{{-- Content Header --}}
@section('header')
    <i class="fa fa-briefcase"></i> @lang('keygroup/title.key_group_update')
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('keygroups.index') }}">
            @lang('site.key_groups')
        </a>
    </li>
    <li class="breadcrumb-item active">
        @lang('keygroup/title.key_group_update')
    </li>
@endsection

{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    <!-- Card -->
    <div class="card">
        {!! Form::model($keygroup, ['route' => ['keygroups.update', $keygroup], 'method' => 'put']) !!}

        <div class="card-header">
            <h2 class="card-title">
                {{ $keygroup->name }}
            </h2>
        </div>

        <div class="card-body">
            <div class="form-row">
                <!-- left column -->
                <div class="col-md-4">

                    <fieldset>
                        <legend>@lang('keygroup/messages.basic_information_section')</legend>

                        <!-- name -->
                        <div class="form-group">
                            {!! Form::label('name', __('keygroup/model.name')) !!}
                            <small class="form-text text-muted">@lang('keygroup/messages.name_help')</small>
                            {!! Form::text('name', null, array('class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''), 'required' => 'required', 'autofocus' => 'autofocus')) !!}
                            @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- ./ name -->

                        <!-- description -->
                        <div class="form-group">
                            {!! Form::label('description', __('keygroup/model.description')) !!}
                            <small class="form-text text-muted">@lang('keygroup/messages.description_help')</small>
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

                        <!-- key groups -->
                        <div class="form-group">
                            {!! Form::label('keys[]', __('keygroup/model.keys')) !!}
                            {!! Form::select('keys[]', $keys, $keygroup->keys->pluck('id'), array('multiple' => 'multiple', 'class' => 'form-control duallistbox')) !!}
                            <small class="form-text text-muted">@lang('keygroup/messages.group_help')</small>
                        </div>
                        <!-- ./ key groups -->

                    </fieldset>

                    <fieldset class="mt-3">
                        <legend>@lang('keygroup/messages.danger_zone_section')</legend>

                        <ul class="list-group border border-danger">
                            <li class="list-group-item">
                                <strong>@lang('keygroup/messages.delete_button')</strong>
                                <button type="button" class="btn btn-outline-danger btn-sm float-right"
                                        data-toggle="modal"
                                        data-target="#confirmationModal">
                                    @lang('keygroup/messages.delete_button')
                                </button>
                                <p>@lang('keygroup/messages.delete_help')</p>
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
            <a href="{{ route('keygroups.index') }}" class="btn btn-link" role="button">
                @lang('general.cancel')
            </a>
            <!-- ./ form actions -->
        </div>

        {!! Form::close() !!}
    </div>
    <!-- ./ card -->

    <!-- confirmation modal -->
    <x-modals.confirmation
        action="{{ route('keygroups.destroy', $keygroup) }}"
        confirmationText="{{ $keygroup->name }}"
        buttonText="{{ __('keygroup/messages.delete_confirmation_button') }}">

        <div class="alert alert-warning" role="alert">
            @lang('keygroup/messages.delete_confirmation_warning', ['name' => $keygroup->name])
        </div>
    </x-modals.confirmation>
    <!-- ./ confirmation modal -->
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
            nonSelectedListLabel: '{{ __('keygroup/messages.available_keys_section') }}',
            selectedListLabel: '{{ __('keygroup/messages.selected_keys_section') }}',
        });
    </script>
@endpush

