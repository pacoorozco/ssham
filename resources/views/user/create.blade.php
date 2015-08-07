@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	{!! Lang::get('user/title.create_a_new_user') !!}
@stop

{{-- Content Header --}}
@section('header')
<h1>
    {!! Lang::get('user/title.create_a_new_user') !!} <small>{!! Lang::get('user/title.create_a_new_user_subtitle') !!}</small>
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
