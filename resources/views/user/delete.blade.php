@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('user/title.user_delete'))

{{-- Content Header --}}
@section('header')
    @lang('user/title.user_delete')
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('users.index') }}">
            @lang('site.users')
        </a>
    </li>
    <li class="breadcrumb-item active">
        @lang('user/title.user_delete')
    </li>
@endsection

{{-- Content --}}
@section('content')
    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    {{-- Delete User Form --}}
    {!! Form::open(['route' => ['users.destroy', $user->id], 'method' => 'delete', ]) !!}
    @include('user._details', ['action' => 'delete'])
    {!! Form::close() !!}
@endsection
