@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	@lang('hostgroup/title.create_a_new_host_group')
@endsection

{{-- Content Header --}}
@section('header')
<h1>
    @lang('hostgroup/title.create_a_new_host_group') !!} <small>{!! trans('hostgroup/title.create_a_new_host_group_subtitle')</small>
</h1>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
<li>
    <i class="fa fa-tasks"></i>
    <a href="{!! route('hostgroups.index') !!}">
        @lang('site.host_groups')
    </a>
</li>
<li class="active">
    @lang('hostgroup/title.create_a_new_host_group')
</li>
@endsection


{{-- Content --}}
@section('content')

<!-- Notifications -->
@include('partials.notifications')
<!-- ./ notifications -->

{!! Form::open(['route' => 'hostgroups.store', 'method' => 'post']) !!}
@include('hostgroup._form')
{!! Form::close() !!}

@endsection
