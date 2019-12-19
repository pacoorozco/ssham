@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	@lang('hostgroup/title.host_group_update')
@endsection

{{-- Content Header --}}
@section('header')
<h1>
    @lang('hostgroup/title.host_group_update') <small>{{ $hostgroup->name }}</small>
</h1>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
<li>
    <i class="fa fa-tasks"></i>
    <a href="{!! route('hostgroups.index') !!}">
        {{ __('site.hostgroups') }}
    </a>
</li>
<li class="active">
    @lang('hostgroup/title.host_group_update')
</li>
@endsection

{{-- Content --}}
@section('content')

<!-- Notifications -->
@include('partials.notifications')
<!-- ./ notifications -->

{!! Form::model($hostgroup, ['route' => ['hostgroups.update', $hostgroup->id], 'method' => 'put']) !!}
@include('hostgroup._form')
{!! Form::close() !!}

@endsection
