@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('hostgroup/title.host_group_delete'))

{{-- Content Header --}}
@section('header')
    @lang('hostgroup/title.host_group_delete')
    <small class="text-muted">{{ $hostgroup->name }}</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('hostgroups.index') }}">
            @lang('site.host_groups')
        </a>
    </li>
    <li class="breadcrumb-item active">
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
