@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	{!! Lang::get('hostgroup/title.hostgroup_show') !!} :: @parent
@stop

{{-- Content Header --}}
@section('header')
<h1>
    {!! Lang::get('hostgroup/title.hostgroup_show') !!} <small>{{ $hostgroup->name }}</small>
</h1>
@stop

{{-- Breadcrumbs --}}
@section('breadcrumbs')
<li>
    <i class="clip-bubbles-3"></i>
    <a href="{!! route('hostgroups.index') !!}">
        {!! Lang::get('site.hostgroups') !!}
    </a>
</li>
<li class="active">
    {!! Lang::get('hostgroup/title.hostgroup_show') !!}
</li>
@stop

{{-- Content --}}
@section('content')

<!-- Notifications -->
@include('partials.notifications')
<!-- ./ notifications -->

@include('hostgroup._details', ['action' => 'show'])

@stop