@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('hostgroup/title.host_group_management'))

{{-- Content Header --}}
@section('header')
    <i class="fa fa-server"></i> @lang('hostgroup/title.host_group_management')
    <small class="text-muted">@lang('hostgroup/title.host_group_management_subtitle')</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('hostgroup/title.host_group_management')
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
            <a class="btn btn-success @cannot('create', \App\Models\Hostgroup::class) disabled @endcannot" href="{{ route('hostgroups.create') }}" role="button">
                <i class="fa fa-plus"></i> @lang('hostgroup/title.create_a_new_host_group')
            </a>
            <!-- /.actions -->
        </div>
        <div class="card-body">
            @include('hostgroup._table')
        </div>
    </div>
@endsection
