@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	@lang('keygroup/title.key_group_delete')
@endsection

{{-- Content Header --}}
@section('header')
<h1>
    @lang('keygroup/title.key_group_delete') <small>{{ $keygroup->name }}</small>
</h1>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('keygroups.index') }}">
            @lang('site.key_groups')
        </a>
    </li>
    <li class="breadcrumb-item active">
    @lang('keygroup/title.key_group_delete')
</li>
@endsection

{{-- Content --}}
@section('content')

<!-- Notifications -->
@include('partials.notifications')
<!-- ./ notifications -->

{{-- Delete User Form --}}
{!! Form::open(array('route' => array('keygroups.destroy', $keygroup->id), 'method' => 'delete', )) !!}
@include('keygroup._details', ['action' => 'delete'])
{!! Form::close() !!}

@endsection
