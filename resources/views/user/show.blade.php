@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	@lang('user/title.user_show')
@stop

{{-- Content Header --}}
@section('header')
<h1>
    @lang('user/title.user_show') <small>{{ $user->username }}</small>
</h1>
@stop

{{-- Breadcrumbs --}}
@section('breadcrumbs')
<li>
    <i class="clip-user"></i>
    <a href="{!! route('users.index') !!}">
        @lang('site.users')
    </a>
</li>
<li class="active">
    @lang('user/title.user_show')
</li>
@stop

{{-- Content --}}
@section('content')

<!-- Notifications -->
@include('partials.notifications')
<!-- ./ notifications -->

@include('user._details', ['action' => 'show'])

@stop