@extends('layouts.login')

@push('styles')
    <link rel="stylesheet" type="text/css"
          href="{{ asset('vendor/AdminLTE/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@endpush

{{-- Content --}}
@section('content')
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">{{ __('auth.sign_instructions') }}</p>

            <form class="form-login" action="{{ route('login') }}" method="post">
                @csrf
                <fieldset>
                    <div class="input-group mb-3">
                        <input type="text" name="username" value="{{ old('username') }}"
                               class="form-control @error('username') is-invalid @enderror"
                               placeholder="@lang('auth.username')"
                               required autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                        @error('username')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="@lang('auth.password')" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input id="remember" name="remember" type="checkbox" value="1">
                                <label for="remember">@lang('auth.remember_me')</label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">@lang('general.submit')</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </fieldset>
            </form>

            <p class="mb-1">
                <a href="{{ route('password.request') }}">{{ __('auth.forgot_password') }}</a>
            </p>
        </div>
    </div>
@endsection
