@extends('layouts.admin')

{{-- Web site Title --}}
@section('title')
    {{ trans('site.dashboard') }} :: @parent
@endsection

{{-- Content Header --}}
@section('header')
    {{ trans('site.dashboard') }}
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="active">
        <i class="fa fa-dashboard"></i> {{ trans('site.dashboard') }}
    </li>
@endsection

@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    <!-- info boxes -->
    <div class="row">
        <!-- users box -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="io ion-ios-person-outline"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">{{ trans('site.users') }}</span>
                    <span class="info-box-number">{{ $data['users'] }}</span>
                </div>
            </div>
        </div>
        <!-- ./ users-box -->
        <!-- user groups box -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="ion ion-ios-people-outline"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">{{ trans('site.user_groups') }}</span>
                    <span class="info-box-number">{{ $data['user_groups'] }}</span>
                </div>
            </div>
        </div>
        <!-- ./ user_groups-box -->

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <!-- hosts box -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fa fa-server"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">{{ trans('site.hosts') }}</span>
                    <span class="info-box-number">{{ $data['hosts'] }}</span>
                </div>
            </div>
        </div>
        <!-- ./ hosts-box -->

        <!-- host groups box -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-server"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">{{ trans('site.host_groups') }}</span>
                    <span class="info-box-number">{{ $data['host_groups'] }}</span>
                </div>
            </div>
        </div>
        <!-- ./ host_groups-box -->
    </div>
    <!-- ./ info boxes -->

    <!-- statistics -->
    <div class="row">
        <!-- monthly records statistics -->
        <div class="col-md-8 col-sm-12 col-xs-12" id="monthly-stats-widget">
            {{-- @include('dashboard._monthly_records_stats') --}}
        </div>

        <!-- record types statistics -->
        <div class="col-md-4 col-sm-12 col-xs-12" id="record-stats-widget">
            {{-- @include('dashboard._record_types_stats') --}}
        </div>
    </div>
    <!-- ./ statistics -->

    <!-- log activity -->
    <div class="row">
        <!-- latest activity widget -->
        <div class="col-md-8 col-sm-12 col-xs-12" id="latest-activity-widget">
            {{-- @include('dashboard._latest_activity') --}}
        </div>
        <!-- ./ latest activity widget -->

        <!-- latest push updates widget -->
        <div class="col-md-4 col-sm-12 col-xs-12" id="latest-jobs-widget">
            {{-- @include('dashboard._latest_jobs') --}}
        </div>
        <!-- ./ latest push updates widget -->
    </div>
    <!-- log activity -->
@endsection
