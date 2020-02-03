@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('keygroup/title.key_group_update'))

{{-- Content Header --}}
@section('header')
    @lang('keygroup/title.key_group_update')
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
    <div class="container-fluid">

        <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

        <!-- Card -->
        <div class="card">
            {!! Form::model($keygroup, ['route' => ['keygroups.update', $keygroup->id], 'method' => 'put']) !!}
            <div class="card-body">
                <div class="form-row">
                    <!-- left column -->
                    <div class="col-md-6">

                        <!-- name -->
                        <div class="form-group">
                            {!! Form::label('name', __('keygroup/model.name')) !!}
                            {!! Form::text('name', null, array('class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''), 'required' => 'required', 'autofocus' => 'autofocus')) !!}
                            @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror

                        </div>
                        <!-- ./ name -->

                        <!-- description -->
                        <div class="form-group">
                            {!! Form::label('description', __('keygroup/model.description')) !!}
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
                        <!-- key's groups -->
                        <div class="form-group">
                            {!! Form::label('keys[]', __('keygroup/model.keys')) !!}
                            {!! Form::select('keys[]', $keys, $keygroup->keys->pluck('id'), array('multiple' => 'multiple', 'class' => 'form-control search-select')) !!}
                        </div>
                        <!-- ./ key's groups -->
                    </div>
                    <!-- ./right column -->
                </div>
            </div>
            <div class="card-footer">
                <!-- Form Actions -->
                <a href="{{ route('keygroups.index') }}" class="btn btn-primary" role="button">
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
            placeholder: "@lang('key/messages.groups_help')",
            allowClear: true,
            language: "@lang('site.language_short')",
        });
    </script>
@endpush

