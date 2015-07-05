@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	{!! Lang::get('hostgroup/title.host_group_update') !!}
@stop

{{-- Content Header --}}
@section('header')
<h1>
    {!! Lang::get('hostgroup/title.host_group_update') !!} <small>{{ $hostgroup->name }}</small>
</h1>
@stop

{{-- Breadcrumbs --}}
@section('breadcrumbs')
<li>
    <i class="fa fa-tasks"></i>
    <a href="{!! route('hostgroups.index') !!}">
        {!! Lang::get('site.hostgroups') !!}
    </a>
</li>
<li class="active">
    {!! Lang::get('hostgroup/title.host_group_update') !!}
</li>
@stop

{{-- Content --}}
@section('content')

<!-- Notifications -->
@include('partials.notifications')
<!-- ./ notifications -->

{!! Form::model($hostgroup, ['route' => ['hostgroups.update', $hostgroup->id], 'method' => 'put']) !!}
@include('hostgroup._form')
{!! Form::close() !!}

@stop