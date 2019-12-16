@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	@lang('usergroup/title.user_group_show')
@endsection

{{-- Content Header --}}
@section('header')
<h1>
    @lang('usergroup/title.user_group_show') <small>{{ $usergroup->name }}</small>
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
