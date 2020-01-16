@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	@lang('usergroup/title.user_group_delete')
@endsection

{{-- Content Header --}}
@section('header')
<h1>
    @lang('usergroup/title.user_group_delete') <small>{{ $usergroup->name }}</small>
</h1>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('usergroups.index') }}">
            @lang('site.user_groups')
        </a>
    </li>
    <li class="breadcrumb-item active">
    @lang('usergroup/title.user_group_delete')
</li>
@endsection

{{-- Content --}}
@section('content')

<!-- Notifications -->
@include('partials.notifications')
<!-- ./ notifications -->

{{-- Delete User Form --}}
{!! Form::open(array('route' => array('usergroups.destroy', $usergroup->id), 'method' => 'delete', )) !!}
@include('usergroup._details', ['action' => 'delete'])
{!! Form::close() !!}

@endsection
