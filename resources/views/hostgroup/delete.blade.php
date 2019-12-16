@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	@lang('hostgroup/title.host_group_delete')
@endsection

{{-- Content Header --}}
@section('header')
<h1>
    @lang('hostgroup/title.host_group_delete') <small>{{ $hostgroup->name }}</small>
</h1>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
<li>
    <i class="fa fa-tasks"></i>
    <a href="{!! route('hostgroups.index') !!}">
        @lang('site.host_groups')
    </a>
</li>
<li class="active">
    @lang('hostgroup/title.host_group_delete')
</li>
@endsection

{{-- Content --}}
@section('content')

<!-- Notifications -->
@include('partials.notifications')
<!-- ./ notifications -->
        
{{-- Delete User Form --}}
{!! Form::open(array('route' => array('hostgroups.destroy', $hostgroup->id), 'method' => 'delete', )) !!}
@include('hostgroup._details', ['action' => 'delete'])
{!! Form::close() !!}

@endsection
