@extends('layouts.master')

{{-- Web site Title --}}
@section('title',  __('user/title.user_management'))

{{-- Content Header --}}
@section('header')
    <i class="nav-icon fa fa-users"></i> @lang('user/title.user_management')
    <small class="text-muted">@lang('user/title.user_management_subtitle')</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('user/title.user_management')
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
            <a class="btn btn-success" href="{{ route('users.create') }}" role="button">
                <i class="fa fa-plus"></i> @lang('user/title.create_a_new_user')
            </a>
            <!-- /.actions -->
        </div>
        <div class="card-body">
            @include('user._table')
        </div>
    </div>
@endsection
