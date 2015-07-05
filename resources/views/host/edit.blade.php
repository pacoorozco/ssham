@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	{!! Lang::get('host/title.host_update') !!}
@stop

{{-- Content Header --}}
@section('header')
<h1>
    {!! Lang::get('host/title.host_update') !!} <small>{{ $host->hostname }}</small>
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
    {!! Lang::get('host/title.host_update') !!}
</li>
@stop

{{-- Content --}}
@section('content')

<!-- Notifications -->
@include('partials.notifications')
<!-- ./ notifications -->

{!! Form::model($host, ['route' => ['hosts.update', $host->id], 'method' => 'put']) !!}
@include('host._form')
{!! Form::close() !!}

@stop