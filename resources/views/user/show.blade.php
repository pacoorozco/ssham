@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	@lang('user/title.user_show')
@endsection

{{-- Content Header --}}
@section('header')
<h1>
    @lang('user/title.user_show') <small>{{ $user->username }}</small>
</h1>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
<li>
    <i class="clip-user"></i>
    <a href="{!! route('users.index') !!}">
        {{ __('site.users') }}
    </a>
</li>
<li class="active">
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
