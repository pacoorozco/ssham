@extends('layouts.login')

@section('content')
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">@lang('auth.confirm_password')</p>

            <form action="{{ route('password.confirm') }}" method="POST">
                @csrf
                <div class="input-group mb-3">
                    <input id="password" name="password" type="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="{{ __('auth.password') }}" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    @error('password')
                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">@lang('auth.confirm_password')</button>
                    </div>
                </div>
            </form>
            <p class="mt-3 mb-1">
                <a href="{{ route('login') }}">@lang('auth.login')</a>
            </p>
        </div>
    </div>
@endsection
