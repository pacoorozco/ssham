@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('user/title.user_update'))

{{-- Content Header --}}
@section('header')
    @lang('user/title.user_update') <small>{{ $user->username }}</small>
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
            <form method="post" action="{{ route('users.update', $user->id) }}">
                @method('put')
                @csrf

                @include('user._form')
            </form>
        </div>
        <!-- ./ card -->
    </div>
@endsection
