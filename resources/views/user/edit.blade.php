@extends('layouts.admin')

{{-- Web site Title --}}
@section('title')
    @lang('user/title.user_edit')
@endsection

{{-- Content Header --}}
@section('header')
    @lang('user/title.user_edit')
    <small>{{ $user->username }}</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li>
        <a href="{{ route('users.index') }}">
            <i class="fa fa-user"></i> @lang('site.users')
        </a>
    </li>
    <li class="active">
        @lang('user/title.user_edit')
    </li>
@endsection

{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    @include('user._form')

@endsection
