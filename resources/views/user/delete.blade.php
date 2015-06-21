@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	{!! Lang::get('user/title.user_delete') !!} :: @parent
@stop

{{-- Content Header --}}
@section('header')
<h1>
    {!! Lang::get('user/title.user_delete') !!} <small>{{ $user->name }}</small>
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
    {!! Lang::get('user/title.user_delete') !!}
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