@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	{!! Lang::get('usergroup/title.create_a_new_usergroup') !!}
@stop

{{-- Content Header --}}
@section('header')
<h1>
    {!! Lang::get('usergroup/title.create_a_new_usergroup') !!} <small>add a new usergroup</small>
</h1>
@stop

{{-- Breadcrumbs --}}
@section('breadcrumbs')
<li>
    <i class="clip-bubbles-3"></i>
    <a href="{!! route('usergroups.index') !!}">
        {!! Lang::get('site.usergroups') !!}
    </a>
</li>
<li class="active">
    {!! Lang::get('usergroup/title.create_a_new_usergroup') !!}
</li>
@stop


{{-- Content --}}
@section('content')

<!-- Notifications -->
@include('partials.notifications')
<!-- ./ notifications -->

{!! Form::open(['route' => 'usergroups.store', 'method' => 'post']) !!}
@include('usergroup._form')
{!! Form::close() !!}

@stop