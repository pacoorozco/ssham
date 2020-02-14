@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('key/title.key_show'))

{{-- Content Header --}}
@section('header')
    @lang('key/title.key_show')
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('keys.index') }}">
            @lang('site.keys')
        </a>
    </li>
    <li class="breadcrumb-item active">
        @lang('key/title.key_show')
    </li>
@endsection

{{-- Content --}}
@section('content')

    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    @include('key._details', ['action' => 'show'])

@endsection
