@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	@lang('user/title.user_update')
@endsection

{{-- Content Header --}}
@section('header')
<h1>
    @lang('user/title.user_update') <small>{{ $user->username }}</small>
</h1>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
<li>
    <i class="clip-user"></i>
    <a href="{!! route('users.index') !!}">
        @lang('site.users')
    </a>
</li>
<li class="active">
    @lang('user/title.user_update')
</li>
@endsection

{{-- Content --}}
@section('content')

<!-- Notifications -->
@include('partials.notifications')
<!-- ./ notifications -->

{!! Form::model($user, ['route' => ['users.update', $user->id], 'method' => 'put']) !!}
@include('user._form')
{!! Form::close() !!}

@endsection
