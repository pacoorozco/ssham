@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('user/title.create_a_new_user'))

{{-- Content Header --}}
@section('header')
    @lang('user/title.create_a_new_user')
    <small class="text-muted">@lang('user/title.create_a_new_user_subtitle')</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('users.index') }}">
            @lang('site.users')
        </a>
    </li>
    <li class="breadcrumb-item active">
        @lang('user/title.create_a_new_user')
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
            {!! Form::open(['route' => 'users.store']) !!}

            <div class="card-body">
                <div class="form-row">
                    <!-- left column -->
                    <div class="col-md-6">

                        <fieldset>
                            <legend>@lang('user/title.personal_information_section')</legend>
                            <!-- username -->
                            <div class="form-group">
                                {!! Form::label('username', __('user/model.username')) !!}
                                {!! Form::text('username', null, array('class' => 'form-control' . ($errors->has('username') ? ' is-invalid' : ''), 'required' => 'required', 'autofocus' => 'autofocus')) !!}
                                <span class="form-text text-muted">@lang('user/messages.username_help')</span>
                                @error('username')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- ./ username -->

                            <!-- email -->
                            <div class="form-group">
                                {!! Form::label('email', __('user/model.email')) !!}

                                {!! Form::email('email', null, array('class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : ''), 'required' => 'required')) !!}

                                @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- ./ email -->
                        </fieldset>

                        <fieldset>
                            <legend>@lang('user/title.about_the_user_section')</legend>

                            <!-- password -->
                            <div class="form-group">
                                {!! Form::label('password', __('user/model.password')) !!}
                                {!! Form::password('password', array('class' => 'form-control' . ($errors->has('password') ? ' is-invalid' : ''), 'required' => 'required')) !!}
                                @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- ./ password -->

                            <!-- password_confirmation -->
                            <div class="form-group">
                                {!! Form::label('password_confirmation', __('user/model.password_confirmation')) !!}
                                {!! Form::password('password_confirmation', array('class' => 'form-control' . ($errors->has('password_confirmation') ? ' is-invalid' : ''), 'required' => 'required')) !!}
                                @error('password_confirmation')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- ./ password_confirmation -->
                        </fieldset>

                    </div>
                    <!-- ./ left column -->

                    <!-- right column -->
                    <div class="col-md-6">
                    </div>
                    <!-- ./right column -->

                </div>
            </div>
            <div class="card-footer">
                <!-- Form Actions -->
                <a href="{{ route('users.index') }}" class="btn btn-primary" role="button">
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
