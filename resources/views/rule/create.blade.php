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


    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    <div class="card">
        {!! Form::open(['route' => 'rules.store', 'method' => 'post']) !!}

        <div class="card-body">
            <div class="form-row">

                <!-- source -->
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('source', __('rule/model.source')) !!}
                        {!! Form::select('source', $sources, null, array('placeholder' => '', 'class' => 'form-control search-select' . ($errors->has('source') ? ' is-invalid' : ''))) !!}
                        <small class="form-text text-muted">This is the group of SSH keys which will be have access to
                            the target.</small>
                        @error('source')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <!-- ./ source -->

                <!-- action -->
                <div class="col-md-1">
                    <!-- action -->
                    <div class="form-group">
                        {!! Form::label('action', __('rule/model.action')) !!}
                        {!! Form::select('action', \App\Enums\ControlRuleAction::asSelectArray(), null, array('class' => 'form-control' . ($errors->has('action') ? ' is-invalid' : ''))) !!}
                        @error('description')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <!-- ./ action -->

                <!-- target -->
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('target', __('rule/model.target')) !!}
                        {!! Form::select('target', $targets, null, array('placeholder' => '', 'class' => 'form-control search-select' . ($errors->has('target') ? ' is-invalid' : ''))) !!}
                        <small class="form-text text-muted">This is the group of hosts to which you are granting access
                            to.</small>
                        @error('target')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <!-- ./ target -->

                <!-- description -->
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('name', __('rule/model.name')) !!}
                        {!! Form::text('name', null, array('class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''), 'required' => 'required')) !!}
                        <small class="form-text text-muted">Short description to identify the rule easily.</small>
                        @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <!-- ./ description -->
            </div>
        </div>

        <div class="card-footer">
            <!-- Form Actions -->
            {!! Form::button(__('general.create'), array('type' => 'submit', 'class' => 'btn btn-success')) !!}
            <a href="{{ route('rules.index') }}" class="btn btn-link" role="button">
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
          href="{{ asset('vendor/AdminLTE/plugins/select2/css/select2.min.css') }}">
@endpush

{{-- Scripts --}}
@push('scripts')
    <script src="{{ asset('vendor/AdminLTE/plugins/select2/js/select2.min.js') }}"></script>
    <script>
        $(".search-select").select2({
            placeholder: 'Select a group',
            language: "@lang('site.language_short')",
        });
    </script>
@endpush
