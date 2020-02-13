@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('user/title.user_update'))

{{-- Content Header --}}
@section('header')
    @lang('user/title.user_update')
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('users.index') }}">
            @lang('site.users')
        </a>
    </li>
    <li class="breadcrumb-item active">
        @lang('user/title.user_update')
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
            {!! Form::model($user, ['route' => ['users.update', $user->id], 'method' => 'put']) !!}

            <div class="card-body">
                <div class="form-row">
                    <!-- left column -->
                    <div class="col-md-6">

                        <fieldset>
                            <legend>@lang('user/title.personal_information_section')</legend>
                            <!-- username -->
                            <div class="form-group">
                                {!! Form::label('username', __('user/model.username')) !!}
                                {!! Form::text('username', $user->username, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                                <span class="form-text text-muted">@lang('user/messages.username_help')</span>
                                @error('username')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- ./ username -->

                            <!-- email -->
                            <div class="form-group">
                                {!! Form::label('email', __('user/model.email')) !!}
                                {!! Form::email('email', $user->email, array('class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : ''), 'required' => 'required', 'autofocus' => 'autofocus')) !!}
                                @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- ./ email -->
                        </fieldset>

                        <!-- enabled -->
                        @if (Auth::id() === $user->id)
                            {!! Form::hidden('enabled', ($user->enabled) ? 1 : 0) !!}
                        @endif
                        <fieldset class="form-group" @if (Auth::id() === $user->id) disabled="disabled" @endif>
                            <div class="row">
                                <legend class="col-form-label col-sm-2 pt-0">
                                    <strong>@lang('user/model.enabled')</strong></legend>
                                <div class="col-sm-10">
                                    @if (Auth::id() === $user->id)
                                        <p class="text-muted">@lang('user/messages.edit_your_status_help')</p>
                                    @endif
                                    <div class="form-check">
                                        {!! Form::radio('enabled', 0, null, array('class' => 'form-check-input')) !!}
                                        {!! Form::label('enabled', __('general.blocked'), array('class' => 'form-check-label')) !!}
                                    </div>
                                    <div class="form-check">
                                        {!! Form::radio('enabled', 1, null, array('class' => 'form-check-input')) !!}
                                        {!! Form::label('enabled', __('general.active'), array('class' => 'form-check-label')) !!}
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <!-- ./ enabled -->

                    </div>
                    <!-- ./ left column -->

                    <!-- right column -->
                    <div class="col-md-6">
                        <!-- about the user -->
                        <fieldset>
                            <legend>@lang('user/title.about_the_user_section')</legend>
                            <p>@lang('user/messages.edit_password_help')</p>

                        @if (Auth::id() === $user->id)
                            <!-- current password -->
                                <div class="form-group">
                                    {!! Form::label('current_password', __('user/model.current_password')) !!}
                                    {!! Form::password('current_password', array('class' => 'form-control' . ($errors->has('current_password') ? ' is-invalid' : ''))) !!}
                                    @error('current_password')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- ./ current_password -->
                        @endif

                        <!-- password -->
                            <div class="form-group">
                                {!! Form::label('password', __('user/model.password')) !!}
                                {!! Form::password('password', array('class' => 'form-control' . ($errors->has('password') ? ' is-invalid' : ''))) !!}
                                @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- ./ password -->

                            <!-- password_confirmation -->
                            <div class="form-group">
                                {!! Form::label('password_confirmation', __('user/model.password_confirmation')) !!}
                                {!! Form::password('password_confirmation', array('class' => 'form-control' . ($errors->has('password_confirmation') ? ' is-invalid' : ''))) !!}
                                @error('password_confirmation')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- ./ password_confirmation -->
                        </fieldset>
                        <!-- ./ about the user -->
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
