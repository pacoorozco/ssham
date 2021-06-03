@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('user/personal_access_token.title'))

{{-- Content Header --}}
@section('header')
    <i class="nav-icon fa fa-users"></i> @lang('user/personal_access_token.title')
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('users.index') }}">
            @lang('site.users')
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('users.edit', $user) }}">
            @lang('user/title.user_update')
        </a>
    </li>
    <li class="breadcrumb-item active">
        @lang('user/personal_access_token.title')
    </li>
@endsection

@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    <div class="row">
        <!-- User edit sidebar -->
        <div class="col-md-2">
            @include('user._settings_menu')
        </div>
        <!-- ./ User edit sidebar -->

        <div class="col-md-10">

            <!-- card -->
            <div class="card card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        @lang('user/personal_access_token.generate_button')
                    </h3>
                </div>
                <div class="card-body">
                    <p>@lang('user/personal_access_token.help')</p>

                {!! Form::open(['route' => ['users.tokens.store', $user]]) !!}

                <!-- name -->
                    <div class="form-group">
                        {!! Form::label('name', __('user/personal_access_token.name_input')) !!}
                        {!! Form::text('name', null, array('class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''), 'required' => 'required', 'autofocus' => 'autofocus')) !!}
                        <small class="form-text text-muted">
                            @lang('user/personal_access_token.name_help')
                        </small>
                        @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- ./ name -->

                    {!! Form::button(__('user/personal_access_token.generate_button'), array('type' => 'submit', 'class' => 'btn btn-success')) !!}
                    <a href="{{ route('users.tokens.index', $user) }}" class="btn btn-link" role="button">
                        @lang('general.cancel')
                    </a>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection
