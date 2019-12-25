@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('user/title.user_show'))

{{-- Content Header --}}
@section('header')
    @lang('user/title.user_show') <small>{{ $user->username }}</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('users.index') }}">
            @lang('site.users')
        </a>
    </li>
    <li class="breadcrumb-item active"><
        @lang('user/title.user_show')
    </li>
@endsection

{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    @include('user._details', ['action' => 'show'])

@endsection
