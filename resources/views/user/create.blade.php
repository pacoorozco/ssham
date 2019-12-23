@extends('layouts.master')

{{-- Web site Title --}}
@section('title',  __('user/title.create_a_new_user'))

{{-- Content Header --}}
@section('header')
    {{ __('user/title.create_a_new_user') }}
    <small>{{ __('user/title.create_a_new_user_subtitle') }}</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('users.index') }}">
            @lang('site.users')
        </a>
    </li>
    <li class="breadcrumb-item active">
        {{ __('user/title.create_a_new_user') }}
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
            <form method="post" action="{{ route('users.store') }}">
                @csrf

                @include('user._form')
            </form>
        </div>
        <!-- ./ card -->
    </div>
@endsection

