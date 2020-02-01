@extends('layouts.master')

{{-- Web site Title --}}
@section('title',  __('key/title.key_management'))

{{-- Content Header --}}
@section('header')
    @lang('key/title.key_management')
    <small class="text-muted">@lang('key/title.key_management_subtitle')</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('key/title.key_management')
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
                <a class="btn btn-success" href="{{ route('keys.create') }}" role="button">
                    <i class="fa fa-plus"></i> @lang('key/title.create_a_new_key')
                </a>
                <a class="btn btn-primary" href="{{ route('keygroups.index') }}" role="button">
                    <i class="fa fa-keys"></i> @lang('keygroup/title.key_group_management')
                </a>
                <!-- /.actions -->
            </div>
            <div class="card-body">
                @include('key._table')
            </div>
        </div>
    </div>
@endsection
