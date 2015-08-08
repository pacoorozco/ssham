@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	@lang('hostgroup/title.host_group_show')
@stop

{{-- Content Header --}}
@section('header')
<h1>
    @lang('hostgroup/title.host_group_show') <small>{{ $hostgroup->name }}</small>
</h1>
@stop

{{-- Breadcrumbs --}}
@section('breadcrumbs')
<li>
    <i class="fa fa-tasks"></i>
    <a href="{!! route('hostgroups.index') !!}">
        @lang('site.hostgroups')
    </a>
</li>
<li class="active">
    @lang('hostgroup/title.host_group_show')
</li>
@stop

{{-- Content --}}
@section('content')

<!-- Notifications -->
@include('partials.notifications')
<!-- ./ notifications -->

@include('hostgroup._details', ['action' => 'show'])

@stop