@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('usergroup/title.user_group_show'))

{{-- Content Header --}}
@section('header')
    @lang('usergroup/title.user_group_show')
    <small class="text-muted">{{ $usergroup->name }}</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('usergroups.index') }}">
            @lang('site.user_groups')
        </a>
    </li>
    <li class="breadcrumb-item active">
        @lang('usergroup/title.user_group_show')
    </li>
@endsection

{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    @include('usergroup._details', ['action' => 'show'])

@endsection
