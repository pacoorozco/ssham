@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	{!! Lang::get('user/title.user_update') !!}
@stop

{{-- Content Header --}}
@section('header')
<h1>
    {!! Lang::get('user/title.user_update') !!} <small>{{ $user->name }}</small>
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
    {!! Lang::get('user/title.user_update') !!}
</li>
@stop

{{-- Content --}}
@section('content')

<!-- Notifications -->
@include('partials.notifications')
<!-- ./ notifications -->

{!! Form::model($user, ['route' => ['users.update', $user->id], 'method' => 'put']) !!}
@include('user._form')
{!! Form::close() !!}

@stop