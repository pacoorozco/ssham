@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	@lang('usergroup/title.create_a_new_user_group')
@stop

{{-- Content Header --}}
@section('header')
<h1>
    @lang('usergroup/title.create_a_new_user_group') !!} <small>{!! trans('usergroup/title.create_a_new_user_group_subtitle')</small>
</h1>
@stop

{{-- Breadcrumbs --}}
@section('breadcrumbs')
<li>
    <i class="clip-users"></i>
    <a href="{!! route('usergroups.index') !!}">
        @lang('site.user_groups')
    </a>
</li>
<li class="active">
    @lang('usergroup/title.create_a_new_user_group')
</li>
@stop


{{-- Content --}}
@section('content')

<!-- Notifications -->
@include('partials.notifications')
<!-- ./ notifications -->

{!! Form::open(['route' => 'usergroups.store', 'method' => 'post']) !!}
@include('usergroup._form')
{!! Form::close() !!}

@stop