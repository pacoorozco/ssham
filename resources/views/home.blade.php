@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
    @lang('dashboard/messages.title')
@endsection

{{-- Content Header --}}
@section('header')
    <h1>
        @lang('dashboard/messages.title')
        <small>@lang('dashboard/messages.subtitle')</small>
    </h1>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li>
        <i class="clip-home-3"></i>
        <a href="{!! route('home') !!}">
            @lang('site.home')
        </a>
    </li>
    <li class="active">
        @lang('dashboard/messages.title')
    </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-4">
            <div class="core-box">
                <div class="heading">
                    <i class="clip-user-4 circle-icon circle-green"></i>

                    <h2>@lang('dashboard/messages.manage_users')</h2>
                </div>
                <div class="content">
                    @lang('dashboard/messages.manage_users_description')
                </div>
                <a class="view-more" href="{!! route('users.index') !!}">
                    @lang('general.view_more')  <i class="clip-arrow-right-2"></i>
                </a>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="core-box">
                <div class="heading">
                    <i class="fa fa-tasks circle-icon circle-teal"></i>

                    <h2>@lang('dashboard/messages.manage_hosts')</h2>
                </div>
                <div class="content">
                    @lang('dashboard/messages.manage_hosts_description')
                </div>
                <a class="view-more" href="{!! route('hosts.index') !!}">
                    @lang('general.view_more')  <i class="clip-arrow-right-2"></i>
                </a>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="core-box">
                <div class="heading">
                    <i class="clip-database circle-icon circle-bricky"></i>

                    <h2>@lang('dashboard/messages.manage_accesses')</h2>
                </div>
                <div class="content">
                    @lang('dashboard/messages.manage_accesses_description')
                </div>
                <a class="view-more" href="{!! route('rules.index') !!}">
                    @lang('general.view_more')  <i class="clip-arrow-right-2"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="panel-body">
            Hosts status
            <div class="flot-small-container">
                <div id="placeholder" class="flot-placeholder"></div>
            </div>
        </div>


    </div>
@endsection

{{-- Scripts --}}
@section('scripts')
    {!! HTML::script(asset('plugins/flot/jquery.flot.min.js')) !!}
    {!! HTML::script(asset('plugins/flot/jquery.flot.pie.min.js')) !!}

    <script>
        $(function() {

            var data = [
                { label: "Synced",  data: {{ $synced }} },
                { label: "Unsynced",  data: {{ $unsynced }} }
            ];


                $.plot("#placeholder", data, {
                    series: {
                        pie: {
                            show: true,
                        }
                    },
                    legend: {
                        show: false
                    }
                });

        });

    </script>
    @endsection