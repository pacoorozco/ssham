@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	@lang('host/title.host_delete')
@endsection

{{-- Content Header --}}
@section('header')
<h1>
    @lang('host/title.host_delete') <small>{{ $host->getFullHostname() }}</small>
</h1>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
<li>
    <i class="clip-screen"></i>
    <a href="{!! route('hosts.index') !!}">
        {{ __('site.hosts') }}
    </a>
</li>
<li class="active">
    @lang('host/title.host_delete')
</li>
@endsection

{{-- Content --}}
@section('content')

<!-- Notifications -->
@include('partials.notifications')
<!-- ./ notifications -->

{{-- Delete User Form --}}
{!! Form::open(array('route' => array('hosts.destroy', $host->id), 'method' => 'delete', )) !!}
@include('host._details', ['action' => 'delete'])
{!! Form::close() !!}

@endsection
