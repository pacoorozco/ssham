@extends('layouts.admin')

{{-- Web site Title --}}
@section('title')
    @lang('user/title.create_new')
@endsection

{{-- Content Header --}}
@section('header')
    @lang('user/title.create_new')
    <small>@lang('user/title.create_new_subtitle')</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li>
        <a href="{{ route('users.index') }}">
            <i class="fa fa-user"></i> @lang('site.users')
        </a>
    </li>
    <li class="active">
        @lang('user/title.create_new')
    </li>
@endsection

{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    @include('user/_form')

@endsection
