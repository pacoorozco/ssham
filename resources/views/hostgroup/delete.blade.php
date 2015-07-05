@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	{!! Lang::get('hostgroup/title.host_group_delete') !!}
@stop

{{-- Content Header --}}
@section('header')
<h1>
    {!! Lang::get('hostgroup/title.host_group_delete') !!} <small>{{ $hostgroup->name }}</small>
</h1>
@stop

{{-- Breadcrumbs --}}
@section('breadcrumbs')
<li>
    <i class="fa fa-tasks"></i>
    <a href="{!! route('hostgroups.index') !!}">
        {!! Lang::get('site.host_groups') !!}
    </a>
</li>
<li class="active">
    {!! Lang::get('hostgroup/title.host_group_delete') !!}
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