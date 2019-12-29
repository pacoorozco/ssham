@extends('layouts.master')

{{-- Web site Title --}}
@section('title',  __('user/title.create_a_new_user'))

{{-- Content Header --}}
@section('header')
    @lang('user/title.create_a_new_user')
    <small>@lang('user/title.create_a_new_user_subtitle')</small>
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
            @include('user._form')
            {!! Form::close() !!}
        </div>
        <!-- ./ card -->
    </div>
@endsection

