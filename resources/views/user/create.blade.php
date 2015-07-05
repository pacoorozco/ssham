@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	{!! Lang::get('user/title.create_a_new_user') !!}
@stop

{{-- Content Header --}}
@section('header')
<h1>
    {!! Lang::get('user/title.create_a_new_user') !!} <small>add a new user</small>
</h1>
@stop

{{-- Breadcrumbs --}}
@section('breadcrumbs')
<li>
    <i class="clip-bubbles-3"></i>
    <a href="{!! route('users.index') !!}">
        {!! Lang::get('site.users') !!}
    </a>
</li>
<li class="active">
    {!! Lang::get('user/title.create_a_new_user') !!}
</li>
@stop


{{-- Content --}}
@section('content')
<!-- Notifications -->
@include('partials.notifications')
<!-- ./ notifications -->

{!! Form::open(['route' => 'users.store', 'method' => 'post']) !!}
@include('user._form')
{!! Form::close() !!}
@stop
