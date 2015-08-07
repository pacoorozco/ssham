@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	{{ trans('usergroup/title.user_group_update') }}
@stop

{{-- Content Header --}}
@section('header')
<h1>
    {{ trans('usergroup/title.user_group_update') }} <small>{{ $usergroup->name }}</small>
</h1>
@stop

{{-- Breadcrumbs --}}
@section('breadcrumbs')
<li>
    <i class="clip-users"></i>
    <a href="{!! route('usergroups.index') !!}">
        {{ trans('site.user_groups') }}
    </a>
</li>
<li class="active">
    {{ trans('usergroup/title.user_group_update') }}
</li>
@stop

{{-- Content --}}
@section('content')

<!-- Notifications -->
@include('partials.notifications')
<!-- ./ notifications -->

{!! Form::model($usergroup, ['route' => ['usergroups.update', $usergroup->id], 'method' => 'put']) !!}
@include('usergroup._form')
{!! Form::close() !!}

@stop