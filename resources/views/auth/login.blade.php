@extends('layouts.login')

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/AdminLTE/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@endpush

{{-- Content --}}
@section('content')
    <p class="login-box-msg">{{ __('auth.sign_instructions') }}</p>

    {!! Form::open(['route' => 'login', 'class' => 'form-login']) !!}

    <fieldset>
        <div class="input-group mb-3">
            {!! Form::text('email', null, array(
                     'class' => 'form-control',
                     'placeholder' => __('auth.email'),
                     'autofocus' => 'autofocus'
                     )) !!}
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
        </div>

        <div class="input-group mb-3">
            {!! Form::password('password', array(
                        'class' => 'form-control',
                        'placeholder' => __('auth.password')
                        )) !!}
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-8">
                <div class="icheck-primary">
                    {!! Form::checkbox('remember', '1', false, array('id' => 'remember')) !!}
                    <label for="remember">
                        {{ __('auth.remember_me') }}
                    </label>
                </div>
            </div>
            <!-- /.col -->
            <div class="col-4">
                <button type="submit" class="btn btn-primary btn-block">{{ __('general.submit') }}</button>
            </div>
            <!-- /.col -->
        </div>
    </fieldset>

    {!! Form::close() !!}

    <p class="mb-1">
        <a href="{{ route('password.request') }}">{{ __('auth.forgot_password') }}</a>
    </p>
@endsection
