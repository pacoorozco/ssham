@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('user/title.user_update'))

{{-- Content Header --}}
@section('header')
    <i class="nav-icon fa fa-users"></i> @lang('user/title.user_update')
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
            <!-- Card -->
            <div class="card">
                <x-form :action="route('users.update', $user)" method="PUT">

                <div class="card-body">
                    <div class="form-row">
                        <!-- left column -->
                        <div class="col-md-6">

                            <fieldset>
                                <legend>@lang('user/title.personal_information_section')</legend>

                                <!-- username -->
                                <x-form-input name="username" :label="__('user/model.username')" :value="$user->username" readonly>
                                    @slot('help')
                                        <small class="form-text text-muted">
                                            @lang('user/messages.username_help')
                                        </small>
                                    @endslot
                                </x-form-input>
                                <!-- ./ username -->

                                <!-- email -->
                                <x-form-input name="email" type="email" :label="__('user/model.email')" :default="$user->email" required autofocus/>
                                <!-- ./ email -->

                                <!-- role -->
                                <x-form-select name="role" :label="__('user/model.role')" :options="collect(\App\Enums\Roles::cases())->mapWithKeys(fn($case) => [$case->value => $case->name])" :default="$user->role->value" required/>
                                <!-- ./ role -->
                            </fieldset>

                            <!-- enabled -->
                            <fieldset class="form-group">
                                <div class="row">
                                    <legend class="col-form-label col-sm-2 pt-0">
                                        <strong>@lang('user/model.enabled')</strong>
                                    </legend>
                                    <div class="col-sm-10">
                                        @can('delete', $user)
                                            <x-form-group name="enabled">
                                                <x-form-radio name="enabled" value="1" :label="__('general.active')" :bind="$user"/>
                                                <x-form-radio name="enabled" value="0" :label="__('general.blocked')" :bind="$user"/>
                                            </x-form-group>
                                        @else
                                            <small class="form-text text-muted">
                                                @lang('user/messages.edit_status_avoided')
                                            </small>
                                            <x-form-radio name="enabled" type="hidden" :default="$user->enabled"/>
                                        @endcan
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
                                <small class="form-text text-muted">@lang('user/messages.edit_password_help')</small>

                            @if (Auth::id() === $user->id)
                                <!-- current password -->
                                    <x-form-input name="current_password" type="password" :label="__('user/model.current_password')"/>
                                    <!-- ./ current_password -->
                            @endif

                            <!-- password -->
                                <x-form-input name="password" type="password" :label="__('user/model.password')"/>
                                  <!-- ./ password -->

                                <!-- password_confirmation -->
                                <x-form-input name="password_confirmation" type="password" :label="__('user/model.password_confirmation')"/>
                                <!-- ./ password_confirmation -->
                            </fieldset>
                            <!-- ./ about the user -->

                            <fieldset>
                                <legend>@lang('user/title.status_section')</legend>

                                <!-- created at -->
                                <div class="row">
                                    <div class="col-3">
                                        <strong>@lang('user/model.created_at')</strong>
                                    </div>
                                    <div class="col-9">
                                        {{ $user->present()->createdAtForHumans() }} ({{ $user->present()->created_at }})
                                    </div>
                                </div>
                                <!-- ./ created at -->

                                <!-- enabled -->
                                <div class="row">
                                    <div class="col-3">
                                        <strong>@lang('user/model.enabled')</strong>
                                    </div>
                                    <div class="col-9">
                                        {{ $user->present()->enabledAsBadge() }}
                                    </div>
                                </div>
                                <!-- ./ enabled -->

                                <!-- authentication -->
                                <div class="row">
                                    <div class="col-3">
                                        <strong>@lang('user/model.authentication')</strong>
                                    </div>
                                    <div class="col-9">
                                        {{ $user->present()->authenticationAsBadge() }}
                                    </div>
                                </div>
                                <!-- ./ authentication -->
                            </fieldset>


                            <fieldset class="mt-3">
                                <legend>@lang('user/messages.danger_zone_section')</legend>

                                @can('delete', $user)
                                    <ul class="list-group border border-danger">
                                        <li class="list-group-item">
                                            <strong>@lang('user/messages.delete_button')</strong>
                                            <button type="button" class="btn btn-outline-danger btn-sm float-right"
                                                    data-toggle="modal"
                                                    data-target="#confirmationModal">
                                                @lang('user/messages.delete_button')
                                            </button>
                                            <p>@lang('user/messages.delete_help')</p>
                                        </li>
                                    </ul>
                                @else
                                    <p class="from-text text-muted">@lang('user/messages.delete_avoided')</p>
                                @endcan
                            </fieldset>

                        </div>
                        <!-- ./right column -->

                    </div>
                </div>
                <div class="card-footer">
                    <!-- Form Actions -->
                    <x-form-submit class="btn-success">
                        @lang('general.update')
                    </x-form-submit>

                    <a href="{{ route('users.index') }}" class="btn btn-link" role="button">
                        @lang('general.cancel')
                    </a>
                    <!-- ./ form actions -->
                </div>

                </x-form>
            </div>
        </div>
        <!-- ./ card -->
    </div>

    @can('delete', $user)
        <!-- confirmation modal -->
        <x-modals.confirmation
            action="{{ route('users.destroy', $user) }}"
            confirmationText="{{ $user->username }}"
            buttonText="{{ __('user/messages.delete_confirmation_button') }}">

            <div class="alert alert-warning" role="alert">
                @lang('user/messages.delete_confirmation_warning', ['username' => $user->username])
            </div>
        </x-modals.confirmation>
        <!-- ./ confirmation modal -->
    @endcan
@endsection
