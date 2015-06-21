@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-sm-4">
        <div class="core-box">
            <div class="heading">
                <i class="clip-user-4 circle-icon circle-green"></i>
                <h2>{!! Lang::get('dashboard.manage_users') !!}</h2>
            </div>
            <div class="content">
                {!! Lang::get('dashboard.manage_users_description') !!}
            </div>
            <a class="view-more" href="{!! route('users.index') !!}">
                {!! Lang::get('button.view_more') !!}
            </a>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="core-box">
            <div class="heading">
                <i class="fa fa-tasks circle-icon circle-teal"></i>
                <h2>{!! Lang::get('dashboard.manage_hosts') !!}</h2>
            </div>
            <div class="content">
                {!! Lang::get('dashboard.manage_hosts_description') !!}
            </div>
            <a class="view-more" href="{!! route('hosts.index') !!}">
                {!! Lang::get('button.view_more') !!}
            </a>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="core-box">
            <div class="heading">
                <i class="clip-database circle-icon circle-bricky"></i>
                <h2>{!! Lang::get('dashboard.manage_accesses') !!}</h2>
            </div>
            <div class="content">
                {!! Lang::get('dashboard.manage_accesses_description') !!}
            </div>
            <a class="view-more" href="#">
                {!! Lang::get('button.view_more') !!}
            </a>
        </div>
    </div>
</div>

@endsection
