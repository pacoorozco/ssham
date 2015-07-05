@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	{!! Lang::get('host/title.create_a_new_host') !!}
@stop

{{-- Content Header --}}
@section('header')
<h1>
    {!! Lang::get('host/title.create_a_new_host') !!} <small>add a new host</small>
</h1>
@stop

{{-- Breadcrumbs --}}
@section('breadcrumbs')
<li>
    <i class="clip-bubbles-3"></i>
    <a href="{!! route('hosts.index') !!}">
        {!! Lang::get('site.hosts') !!}
    </a>
</li>
<li class="active">
    {!! Lang::get('host/title.create_a_new_host') !!}
</li>
@stop


{{-- Content --}}
@section('content')

<!-- Notifications -->
@include('partials.notifications')
<!-- ./ notifications -->

{!! Form::open(['route' => 'hosts.store', 'method' => 'post']) !!}
@include('host._form')
{!! Form::close() !!}

@stop