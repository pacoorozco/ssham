@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	{{ trans('host/title.host_show') }}
@stop

{{-- Content Header --}}
@section('header')
<h1>
    {{ trans('host/title.host_show') }} <small>{{ $host->getFullHostname() }}</small>
</h1>
@stop

{{-- Breadcrumbs --}}
@section('breadcrumbs')
<li>
    <i class="clip-screen"></i>
    <a href="{!! route('hosts.index') !!}">
        {{ trans('site.hosts') }}
    </a>
</li>
<li class="active">
    {{ trans('host/title.host_show') }}
</li>
@stop

{{-- Content --}}
@section('content')

<!-- Notifications -->
@include('partials.notifications')
<!-- ./ notifications -->

@include('host._details', ['action' => 'show'])

@stop