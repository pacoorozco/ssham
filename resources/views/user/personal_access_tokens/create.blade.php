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

                    <x-form :action="route('users.tokens.store', $user)">
                        <!-- name -->
                        <x-form-input name="name" :label="__('user/personal_access_token.name_input')" required autofocus>
                            @slot('help')
                                <small class="form-text text-muted">
                                    @lang('user/personal_access_token.name_help')
                                </small>
                            @endslot
                        </x-form-input>
                        <!-- ./ name -->

                        <x-form-submit class="btn-success">
                            @lang('user/personal_access_token.generate_button')
                        </x-form-submit>

                        <a href="{{ route('users.tokens.index', $user) }}" class="btn btn-link" role="button">
                            @lang('general.cancel')
                        </a>
                    </x-form>

                </div>
            </div>
        </div>
    </div>
@endsection
