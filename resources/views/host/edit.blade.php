@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	@lang('host/title.host_update')
@stop

{{-- Content Header --}}
@section('header')
<h1>
    @lang('host/title.host_update') }} <small>{{ $host->getFullHostname()</small>
</h1>
@stop

{{-- Breadcrumbs --}}
@section('breadcrumbs')
<li>
    <i class="clip-screen"></i>
    <a href="{!! route('hosts.index') !!}">
        @lang('site.hosts')
    </a>
</li>
<li class="active">
    @lang('host/title.host_update')
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