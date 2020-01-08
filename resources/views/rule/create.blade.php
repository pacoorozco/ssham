@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('rule/title.create_a_new_rule'))

{{-- Content Header --}}
@section('header')
    @lang('rule/title.create_a_new_rule')
    <small class="text-muted">@lang('rule/title.create_a_new_rule_subtitle')</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('rules.index') }}">
            @lang('site.rules')
        </a>
    </li>
    <li class="breadcrumb-item active">
        @lang('rule/title.create_a_new_rule')
    </li>
@endsection


{{-- Content --}}
@section('content')
    <div class="container-fluid">

        <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

        <div class="card">
            {!! Form::open(['route' => 'rules.store', 'method' => 'post']) !!}

            <div class="card-body">
                <div class="form-row">
                    <!-- left column -->
                    <div class="col-md-6">

                        <!-- description -->
                        <div class="form-group">
                            {!! Form::label('description', __('rule/model.description')) !!}
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

                        <!-- usergroup -->
                        <div class="form-group">
                            {!! Form::label('usergroup', __('rule/model.user_group')) !!}
                            {!! Form::select('usergroup', $usergroups, null, array('class' => 'form-control search-select' . ($errors->has('usergroup') ? ' is-invalid' : ''))) !!}
                            @error('usergroup')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- ./ usergroup -->

                        <!-- action -->
                        <div class="form-group">
                            {!! Form::label('action', __('rule/model.action')) !!}
                            {!! Form::select('action', array('allow' => __('rule/model.action_allow'), 'deny' => __('rule/model.action_deny')), null, array('class' => 'form-control' . ($errors->has('action') ? ' is-invalid' : ''))) !!}
                            @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- ./ action -->

                        <!-- hostgroup -->
                        <div class="form-group">
                            {!! Form::label('hostgroup', __('rule/model.host_group')) !!}
                            {!! Form::select('hostgroup', $hostgroups, null, array('class' => 'form-control search-select' . ($errors->has('hostgroup') ? ' is-invalid' : ''))) !!}
                            @error('hostgroup')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- ./ hostgroup -->

                    </div>
                    <!-- ./ right column -->

                </div>
            </div>

            <div class="card-footer">
                <!-- Form Actions -->
                <a href="{{ route('rules.index') }}" class="btn btn-primary" role="button">
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
            language: "@lang('site.language_short')",
        });
    </script>
@endpush
