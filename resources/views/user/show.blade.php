@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	{!! Lang::get('user/title.user_show') !!} :: @parent
@stop

{{-- Content Header --}}
@section('header')
<h1>
    {!! Lang::get('user/title.user_show') !!} <small>{{ $user->name }}</small>
</h1>
@stop

{{-- Breadcrumbs --}}
@section('breadcrumbs')
<li>
    <i class="clip-user"></i>
    <a href="{!! route('users.index') !!}">
        {!! Lang::get('site.users') !!}
    </a>
</li>
<li class="active">
    {!! Lang::get('user/title.user_show') !!}
</li>
@stop

{{-- Content --}}
@section('content')

<!-- Notifications -->
@include('partials.notifications')
<!-- ./ notifications -->

@include('user._details', ['action' => 'show'])

@stop