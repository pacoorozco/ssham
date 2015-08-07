@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	{{ trans('host/title.create_a_new_host') }}
@stop

{{-- Content Header --}}
@section('header')
<h1>
    {{ trans('host/title.create_a_new_host') !!} <small>{!! trans('host/title.create_a_new_host_subtitle') }}</small>
</h1>
@stop

{{-- Breadcrumbs --}}
@section('breadcrumbs')
<li>
    <i class="clip-bubbles-3"></i>
    <a href="{!! route('hosts.index') !!}">
        {{ trans('site.hosts') }}
    </a>
</li>
<li class="active">
    {{ trans('host/title.create_a_new_host') }}
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