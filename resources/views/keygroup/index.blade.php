@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('keygroup/title.key_group_management'))

{{-- Content Header --}}
@section('header')
    <i class="fa fa-briefcase"></i> @lang('keygroup/title.key_group_management')
    <small class="text-muted">@lang('keygroup/title.key_group_management_subtitle')</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('keygroup/title.key_group_management')
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
                <a class="btn btn-success" href="{{ route('keygroups.create') }}" role="button">
                    <i class="fa fa-plus"></i> @lang('keygroup/title.create_a_new_key_group')
                </a>
                <!-- /.actions -->
            </div>
            <div class="card-body">
                @include('keygroup._table')
            </div>
        </div>
@endsection
