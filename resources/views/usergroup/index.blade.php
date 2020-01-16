@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('usergroup/title.user_group_management'))

{{-- Content Header --}}
@section('header')
    @lang('usergroup/title.user_group_management')
    <small class="text-muted">@lang('usergroup/title.user_group_management_subtitle')</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('usergroup/title.user_group_management')
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
                <a class="btn btn-success" href="{{ route('usergroups.create') }}" role="button">
                    <i class="fa fa-plus"></i> @lang('usergroup/title.create_a_new_user_group')
                </a>
                <!-- /.actions -->
            </div>
            <div class="card-body">
                @include('usergroup._table')
            </div>
        </div>
    </div>
@endsection
