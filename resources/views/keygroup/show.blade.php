@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('keygroup/title.key_group_show'))

{{-- Content Header --}}
@section('header')
    @lang('keygroup/title.key_group_show')
    <small class="text-muted">{{ $keygroup->name }}</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('keygroups.index') }}">
            @lang('site.key_groups')
        </a>
    </li>
    <li class="breadcrumb-item active">
        @lang('keygroup/title.key_group_show')
    </li>
@endsection

{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    @include('keygroup._details', ['action' => 'show'])

@endsection
