@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('hostgroup/title.host_group_show'))

{{-- Content Header --}}
@section('header')
    @lang('hostgroup/title.host_group_show')
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
        @lang('hostgroup/title.host_group_show')
    </li>
@endsection

{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    @include('hostgroup._details', ['action' => 'show'])

@endsection
