@extends('layouts.master')


{{-- Web site Title --}}
@section('title',  __('host/title.host_management'))

{{-- Content Header --}}
@section('header')
    @lang('host/title.host_management')
    <small>@lang('host/title.host_management_subtitle')</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('host/title.host_management')
    </li>
@endsection

{{-- Content --}}
@section('content')
    <div class="container-fluid">

        <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

        <div class="card">
            <div class="card-header">
                <!-- actions -->
                <a class="btn btn-success" href="{{ route('hosts.create') }}" role="button">
                    <i class="fa fa-plus"></i> @lang('host/title.create_a_new_host')
                </a>
                <a class="btn btn-primary" href="{{ route('hostgroups.index') }}" role="button">
                    <i class="fa fa-users"></i> @lang('hostgroup/title.host_group_management')
                </a>
                <!-- /.actions -->
            </div>
            <div class="card-body">
                @include('host._table')
            </div>
        </div>
    </div>
@endsection

