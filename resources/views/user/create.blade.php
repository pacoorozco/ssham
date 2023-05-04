@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('user/title.create_a_new_user'))

{{-- Content Header --}}
@section('header')
    <i class="nav-icon fa fa-users"></i> @lang('user/title.create_a_new_user')
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

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    <!-- Card -->
    <div class="card">
        <x-form :action="route('users.store')">

        <div class="card-body">
            <div class="form-row">
                <!-- left column -->
                <div class="col-md-6">

                    <fieldset>
                        <legend>@lang('user/title.personal_information_section')</legend>
                        <!-- username -->
                            <x-form-input name="username" :label="__('user/model.username')" required autofocus>
                                @slot('help')
                                    <small class="form-text text-muted">
                                        @lang('user/messages.username_help')
                                    </small>
                                @endslot
                            </x-form-input>
                        <!-- ./ username -->

                        <!-- email -->
                        <x-form-input name="email" type="email" :label="__('user/model.email')" required/>
                        <!-- ./ email -->

                        <!-- role -->
                        <x-form-select name="role" :label="__('user/model.role')" :options="\App\Enums\Roles::asSelectArray()" :default="\App\Enums\Roles::Operator" required/>
                        <!-- ./ role -->
                    </fieldset>

                </div>
                <!-- ./ left column -->
                <!-- right column -->
                <div class="col-md-6">

                    <fieldset>
                        <legend>@lang('user/title.credentials')</legend>

                        <!-- password -->
                        <x-form-input name="password" type="password" :label="__('user/model.password')" required/>
                        <!-- ./ password -->

                        <!-- password_confirmation -->
                        <x-form-input name="password_confirmation" type="password" :label="__('user/model.password_confirmation')" required/>
                        <!-- ./ password_confirmation -->
                    </fieldset>

                </div>
                <!-- ./right column -->

            </div>
        </div>
        <div class="card-footer">
            <!-- Form Actions -->
            <x-form-submit class="btn-success">
                @lang('general.create')
            </x-form-submit>

            <a href="{{ route('users.index') }}" class="btn btn-link" role="button">
                @lang('general.cancel')
            </a>

            <!-- ./ form actions -->
        </div>

        </x-form>
    </div>
    <!-- ./ card -->
@endsection
