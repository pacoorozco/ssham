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
                {!! Form::model($user, ['route' => ['users.update', $user], 'method' => 'put']) !!}

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
                                    <small class="form-text text-muted">@lang('user/messages.username_help')</small>
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
                            <fieldset class="form-group" @cannot('delete', $user) disabled="disabled" @endcannot>
                                <div class="row">
                                    <legend class="col-form-label col-sm-2 pt-0">
                                        <strong>@lang('user/model.enabled')</strong>
                                    </legend>
                                    <div class="col-sm-10">
                                        @can('delete', $user)
                                            <div class="form-check">
                                                {!! Form::radio('enabled', 0, null, array('class' => 'form-check-input')) !!}
                                                {!! Form::label('enabled', __('general.blocked'), array('class' => 'form-check-label')) !!}
                                            </div>
                                            <div class="form-check">
                                                {!! Form::radio('enabled', 1, null, array('class' => 'form-check-input')) !!}
                                                {!! Form::label('enabled', __('general.active'), array('class' => 'form-check-label')) !!}
                                            </div>
                                        @else
                                            <small class="form-text text-muted">
                                                @lang('user/messages.edit_status_avoided')
                                            </small>
                                            {!! Form::hidden('enabled', ($user->enabled) ? 1 : 0) !!}
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

                            <fieldset>
                                <legend>@lang('user/title.status_section')</legend>

                                <!-- created at -->
                                <div class="row">
                                    <div class="col-3">
                                        <strong>@lang('user/model.created_at')</strong>
                                    </div>
                                    <div class="col-9">
                                        {{ $user->present()->createdAtForHumans() }} ({{ $user->present()->created_at }}
                                        )
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
                    {!! Form::button(__('general.update'), array('type' => 'submit', 'class' => 'btn btn-success')) !!}
                    <a href="{{ route('users.index') }}" class="btn btn-link" role="button">
                        @lang('general.cancel')
                    </a>
                    <!-- ./ form actions -->
                </div>

                {!! Form::close() !!}
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
