@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('rule/title.rule_management'))

{{-- Content Header --}}
@section('header')
    @lang('rule/title.rule_management')
    <small class="text-muted">@lang('rule/title.rule_management_subtitle')</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('rule/title.rule_management')
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
            <a class="btn btn-success" href="{{ route('rules.create') }}" role="button">
                <i class="fa fa-plus"></i> @lang('rule/title.create_a_new_rule')
            </a>
            <!-- /.actions -->
        </div>
        <div class="card-body">
            @include('rule._table')
        </div>
    </div>
@endsection
