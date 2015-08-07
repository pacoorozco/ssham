@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
    {!! trans('site.settings') !!}
@stop

{{-- Content Header --}}
@section('header')
    <h1>
        {!! trans('site.settings') !!} <small>configure SSHAM</small>
    </h1>
@stop

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li>
        <i class="clip-settings"></i>
        <a href="{!! route('settings.index') !!}">
            {!! trans('site.settings') !!}
        </a>
    </li>
    <li class="active">
        {!! trans('user/title.user_management') !!}
    </li>
@stop

{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    <div class="row">
        <div class="col-xs-12">
            {!! Form::open(['route' => ['settings.update'], 'method' => 'put']) !!}
            @include('settings._form')
            {!! Form::close() !!}
        </div>
    </div>
@stop