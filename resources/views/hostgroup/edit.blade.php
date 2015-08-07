@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	{{ trans('hostgroup/title.host_group_update') }}
@stop

{{-- Content Header --}}
@section('header')
<h1>
    {{ trans('hostgroup/title.host_group_update') }} <small>{{ $hostgroup->name }}</small>
</h1>
@stop

{{-- Breadcrumbs --}}
@section('breadcrumbs')
<li>
    <i class="fa fa-tasks"></i>
    <a href="{!! route('hostgroups.index') !!}">
        {{ trans('site.hostgroups') }}
    </a>
</li>
<li class="active">
    {{ trans('hostgroup/title.host_group_update') }}
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