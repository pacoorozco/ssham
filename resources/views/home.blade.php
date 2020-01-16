@extends('layouts.master')

{{-- Web site Title --}}
@section('title',  __('dashboard/messages.title'))

{{-- Content Header --}}
@section('header')
    @lang('dashboard/messages.title')
    <small>@lang('dashboard/messages.subtitle')</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('dashboard/messages.title')
    </li>
@endsection

@section('content')
    <div class="container-fluid">

        <!-- start: STATUS BOXES -->
        <div class="row">

            <div class="col-lg-4 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $user_count }}</h3>
                        <p>@lang('site.users')</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-md-contacts"></i>
                    </div>
                    <a class="small-box-footer" href="{{ route('users.index') }}">
                        {{ __('user/title.user_management') }} <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-4 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $host_count }}</h3>
                        <p>@lang('site.hosts')</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-md-desktop"></i>
                    </div>
                    <a class="small-box-footer" href="{{ route('hosts.index') }}">
                        {{ __('host/title.host_management') }} <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-4 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $rule_count }}</h3>
                        <p>@lang('site.rules')</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-md-lock"></i>
                    </div>
                    <a class="small-box-footer" href="{{ route('rules.index') }}">
                        {{ __('rule/title.rule_management') }} <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

        </div>
        <!-- end: STATUS BOXES -->

    </div>
@endsection



