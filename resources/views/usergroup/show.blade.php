@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	{!! Lang::get('usergroup/title.user_group_show') !!}
@stop

{{-- Content Header --}}
@section('header')
<h1>
    {!! Lang::get('usergroup/title.user_group_show') !!} <small>{{ $usergroup->name }}</small>
</h1>
@stop

{{-- Breadcrumbs --}}
@section('breadcrumbs')
<li>
    <i class="clip-users"></i>
    <a href="{!! route('usergroups.index') !!}">
        {!! Lang::get('site.user_groups') !!}
    </a>
</li>
<li class="active">
    {!! Lang::get('usergroup/title.user_group_show') !!}
</li>
@stop

{{-- Content --}}
@section('content')

<!-- Notifications -->
@include('partials.notifications')
<!-- ./ notifications -->

@include('usergroup._details', ['action' => 'show'])

@stop