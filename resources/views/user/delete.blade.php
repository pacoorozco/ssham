@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	{!! trans('user/title.user_delete') !!}
@stop

{{-- Content Header --}}
@section('header')
<h1>
    {!! trans('user/title.user_delete') !!} <small>{{ $user->username }}</small>
</h1>
@stop

{{-- Breadcrumbs --}}
@section('breadcrumbs')
<li>
    <i class="clip-user"></i>
    <a href="{!! route('users.index') !!}">
        {!! trans('site.users') !!}
    </a>
</li>
<li class="active">
    {!! trans('user/title.user_delete') !!}
</li>
@stop

{{-- Content --}}
@section('content')

<!-- Notifications -->
@include('partials.notifications')
<!-- ./ notifications -->
        
{{-- Delete User Form --}}
{!! Form::open(array('route' => array('users.destroy', $user->id), 'method' => 'delete', )) !!}
@include('user._details', ['action' => 'delete'])
{!! Form::close() !!}

@stop