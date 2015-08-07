@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	{!! trans('hostgroup/title.host_group_delete') !!}
@stop

{{-- Content Header --}}
@section('header')
<h1>
    {!! trans('hostgroup/title.host_group_delete') !!} <small>{{ $hostgroup->name }}</small>
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
    {!! trans('hostgroup/title.host_group_delete') !!}
</li>
@stop

{{-- Content --}}
@section('content')

<!-- Notifications -->
@include('partials.notifications')
<!-- ./ notifications -->
        
{{-- Delete User Form --}}
{!! Form::open(array('route' => array('hostgroups.destroy', $hostgroup->id), 'method' => 'delete', )) !!}
@include('hostgroup._details', ['action' => 'delete'])
{!! Form::close() !!}

@stop