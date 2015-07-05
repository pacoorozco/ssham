@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	{!! Lang::get('host/title.host_show') !!}
@stop

{{-- Content Header --}}
@section('header')
<h1>
    {!! Lang::get('host/title.host_show') !!} <small>{{ $host->hostname }}</small>
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
    {!! Lang::get('host/title.host_show') !!}
</li>
@stop

{{-- Content --}}
@section('content')

<!-- Notifications -->
@include('partials.notifications')
<!-- ./ notifications -->

@include('host._details', ['action' => 'show'])

@stop