@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('host/title.host_delete'))

{{-- Content Header --}}
@section('header')
    @lang('host/title.host_delete')
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
    @lang('host/title.host_delete')
</li>
@endsection

{{-- Content --}}
@section('content')

<!-- Notifications -->
@include('partials.notifications')
<!-- ./ notifications -->

{{-- Delete User Form --}}
{!! Form::open(['route' => ['hosts.destroy', $host->id], 'method' => 'delete', ]) !!}
@include('host._details', ['action' => 'delete'])
{!! Form::close() !!}

@endsection
