@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	{{ trans('rule/title.create_a_new_rule') }}
@stop

{{-- Content Header --}}
@section('header')
<h1>
    {{ trans('rule/title.create_a_new_rule') !!} <small>{!! trans('rule/title.create_a_new_rule_subtitle') }}</small>
</h1>
@stop

{{-- Breadcrumbs --}}
@section('breadcrumbs')
<li>
    <i class="clip-database"></i>
    <a href="{!! route('rules.index') !!}">
        {{ trans('site.rules') }}
    </a>
</li>
<li class="active">
    {{ trans('rule/title.create_a_new_rule') }}
</li>
@stop


{{-- Content --}}
@section('content')
<!-- Notifications -->
@include('partials.notifications')
<!-- ./ notifications -->

{!! Form::open(['route' => 'rules.store', 'method' => 'post']) !!}
@include('rule._form')
{!! Form::close() !!}
@stop
