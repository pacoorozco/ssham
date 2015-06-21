@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	{!! Lang::get('usergroup/title.usergroup_delete') !!} :: @parent
@stop

{{-- Content Header --}}
@section('header')
<h1>
    {!! Lang::get('usergroup/title.usergroup_delete') !!} <small>{{ $usergroup->name }}</small>
</h1>
@stop

{{-- Breadcrumbs --}}
@section('breadcrumbs')
<li>
    <i class="clip-bubbles-3"></i>
    <a href="{!! route('usergroups.index') !!}">
        {!! Lang::get('site.usergroups') !!}
    </a>
</li>
<li class="active">
    {!! Lang::get('usergroup/title.usergroup_delete') !!}
</li>
@stop

{{-- Content --}}
@section('content')

<!-- Notifications -->
@include('partials.notifications')
<!-- ./ notifications -->
        
{{-- Delete User Form --}}
{!! Form::open(array('route' => array('usergroups.destroy', $usergroup->id), 'method' => 'delete', )) !!}
@include('usergroup._details', ['action' => 'delete'])
{!! Form::close() !!}

@stop