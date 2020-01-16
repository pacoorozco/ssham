@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('host/title.host_show'))

{{-- Content Header --}}
@section('header')
    @lang('host/title.host_show')
    <small class="text-muted">{{ $host->getFullHostname() }}</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('hosts.index') }}">
            @lang('site.hosts')
        </a>
    </li>
    <li class="breadcrumb-item active">
        @lang('host/title.host_show')
    </li>
@endsection

{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    @include('host._details', ['action' => 'show'])

@endsection
