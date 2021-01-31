@extends('layouts.master')

{{-- Web site Title --}}
@section('title',  __('dashboard/messages.title'))

{{-- Content Header --}}
@section('header')
    {{ __('dashboard/messages.title') }}
    <small>{{ __('dashboard/messages.subtitle') }}</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item active">
        {{ __('dashboard/messages.title') }}
    </li>
@endsection

@section('content')
    <div class="container-fluid">

        <!-- start: STATUS BOXES -->
        <div class="row">

            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $key_count }}</h3>
                        <p>{{ __('key/model.item') }}</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-key"></i>
                    </div>
                    <a class="small-box-footer" href="{{ route('keys.index') }}">
                        {{ __('key/title.key_management') }} <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $host_count }}</h3>
                        <p>{{ __('host/model.item') }}</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-laptop"></i>
                    </div>
                    <a class="small-box-footer" href="{{ route('hosts.index') }}">
                        {{ __('host/title.host_management') }} <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $rule_count }}</h3>
                        <p>@lang('rule/model.item')</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-dungeon"></i>
                    </div>
                    <a class="small-box-footer" href="{{ route('rules.index') }}">
                        {{ __('rule/title.rule_management') }} <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $user_count }}</h3>
                        <p>{{ __('user/model.item') }}</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <a class="small-box-footer" href="{{ route('users.index') }}">
                        {{ __('user/title.user_management') }} <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

        </div>
        <!-- end: STATUS BOXES -->

        <!-- start: ACTIVITY LOG -->
        @include('dashboard._latest_events')
        <!-- end: ACTIVITY LOG -->

    </div>
@endsection
