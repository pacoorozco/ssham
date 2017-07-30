@extends('layouts.admin')

{{-- Web site Title --}}
@section('title')
    {{ trans('user/title.user_management') }}
@endsection

{{-- Content Header --}}
@section('header')
    {{ trans('user/title.user_management') }}
    <small>{{ trans('user/title.user_management_subtitle') }}</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li>
        <a href="{{ route('home') }}">
            <i class="fa fa-dashboard"></i> {{ trans('site.dashboard') }}
        </a>
    </li>
    <li class="active">
        {{ trans('site.users') }}
    </li>
@endsection


{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    <!-- actions -->
    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('users.create') }}">
                <button type="button" class="btn btn-success margin-bottom">
                    <i class="fa fa-plus"></i> {{ trans('user/title.create_new') }}
                </button>
            </a>
            <a href="{{ route('usergroups.index') }}">
                <button type="button" class="btn btn-primary margin-bottom">
                    <i class="fa fa-users"></i> {{ trans('usergroup/title.user_group_management') }}
                </button>
            </a>
        </div>
    </div>
    <!-- /.actions -->
    <div class="box">
        <div class="box-body">
            @include('user._table')
        </div>
    </div>
@endsection
