@extends('layouts.master')


{{-- Web site Title --}}
@section('title',  __('host/title.host_management'))

{{-- Content Header --}}
@section('header')
    <i class="fa fa-laptop"></i> @lang('host/title.host_management')
    <small class="text-muted">@lang('host/title.host_management_subtitle')</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('host/title.host_management')
    </li>
@endsection

{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    <div class="card">
        <div class="card-header">
            <!-- actions -->
            <a class="btn btn-success @cannot('create', \App\Models\Host::class) disabled @endcannot" href="{{ route('hosts.create') }}" role="button">
                <i class="fa fa-plus"></i> @lang('host/title.create_a_new_host')
            </a>
            <a class="btn btn-primary" href="{{ route('hostgroups.index') }}" role="button">
                <i class="fa fa-server"></i> @lang('hostgroup/title.host_group_management')
            </a>
            <!-- /.actions -->
        </div>
        <div class="card-body">
            @include('host._table')
        </div>
    </div>
@endsection

