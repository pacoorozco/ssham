@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('keygroup/title.create_a_new_key_group'))

{{-- Content Header --}}
@section('header')
    <i class="fa fa-briefcase"></i> @lang('keygroup/title.create_a_new_key_group')
    <small class="text-muted">@lang('keygroup/title.create_a_new_key_group_subtitle')</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('keygroups.index') }}">
            @lang('site.key_groups')
        </a>
    </li>
    <li class="breadcrumb-item active">
        @lang('keygroup/title.create_a_new_key_group')
    </li>
@endsection


{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    <!-- Card -->
    <div class="card">
        {!! Form::open(['route' => 'keygroups.store', 'method' => 'post']) !!}
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
                        <legend>@lang('keygroup/messages.group_members_section')</legend>
                        <!-- key's groups -->
                        <div class="form-group">
                            {!! Form::label('keys[]', __('keygroup/model.keys')) !!}
                            {!! Form::select('keys[]', $keys, null, array('multiple' => 'multiple', 'class' => 'form-control duallistbox')) !!}
                            <small class="form-text text-muted">@lang('keygroup/messages.group_help')</small>
                        </div>
                        <!-- ./ key's groups -->
                    </fieldset>
                </div>
                <!-- ./right column -->
            </div>
        </div>
        <div class="card-footer">
            <!-- Form Actions -->
            {!! Form::button(__('general.create'), array('type' => 'submit', 'class' => 'btn btn-success')) !!}
            <a href="{{ route('keygroups.index') }}" class="btn btn-link" role="button">
                @lang('general.cancel')
            </a>
            <!-- ./ form actions -->
        </div>

        {!! Form::close() !!}
    </div>
    <!-- ./ card -->
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

