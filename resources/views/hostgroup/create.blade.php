@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	{!! trans('hostgroup/title.create_a_new_host_group') !!}
@stop

{{-- Content Header --}}
@section('header')
<h1>
    {!! trans('hostgroup/title.create_a_new_host_group') !!} <small>{!! trans('hostgroup/title.create_a_new_host_group_subtitle') !!}</small>
</h1>
@stop

{{-- Breadcrumbs --}}
@section('breadcrumbs')
<li>
    <i class="fa fa-tasks"></i>
    <a href="{!! route('hostgroups.index') !!}">
        {!! trans('site.host_groups') !!}
    </a>
</li>
<li class="active">
    {!! trans('hostgroup/title.create_a_new_host_group') !!}
</li>
@stop


{{-- Content --}}
@section('content')

<!-- Notifications -->
@include('partials.notifications')
<!-- ./ notifications -->

{!! Form::open(['route' => 'hostgroups.store', 'method' => 'post']) !!}
@include('hostgroup._form')
{!! Form::close() !!}

@stop