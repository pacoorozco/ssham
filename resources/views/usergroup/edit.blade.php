@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	@lang('usergroup/title.user_group_update')
@endsection

{{-- Content Header --}}
@section('header')
<h1>
    @lang('usergroup/title.user_group_update') <small>{{ $usergroup->name }}</small>
</h1>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
<li>
    <i class="clip-users"></i>
    <a href="{!! route('usergroups.index') !!}">
        @lang('site.user_groups')
    </a>
</li>
<li class="active">
    @lang('usergroup/title.user_group_update')
</li>
@endsection

{{-- Content --}}
@section('content')

<!-- Notifications -->
@include('partials.notifications')
<!-- ./ notifications -->

{!! Form::model($usergroup, ['route' => ['usergroups.update', $usergroup->id], 'method' => 'put']) !!}
@include('usergroup._form')
{!! Form::close() !!}

@endsection
