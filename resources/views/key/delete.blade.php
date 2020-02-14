@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('key/title.key_delete'))

{{-- Content Header --}}
@section('header')
    @lang('key/title.key_delete') <small>{{ $key->name }}</small>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('keys.index') }}">
            @lang('site.keys')
        </a>
    </li>
    <li class="breadcrumb-item active">
        @lang('key/title.key_delete')
    </li>
@endsection

{{-- Content --}}
@section('content')
    <!-- Notifications -->
    @include('partials.notifications')
    <!-- ./ notifications -->

    {{-- Delete User Form --}}
    {!! Form::open(['route' => ['keys.destroy', $key->id], 'method' => 'delete', ]) !!}
    @include('key._details', ['action' => 'delete'])
    {!! Form::close() !!}
@endsection
