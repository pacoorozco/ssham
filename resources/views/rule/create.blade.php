@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	@lang('rule/title.create_a_new_rule')
@endsection

{{-- Content Header --}}
@section('header')
<h1>
    @lang('rule/title.create_a_new_rule') <small>@lang('rule/title.create_a_new_rule_subtitle')</small>
</h1>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
<li>
    <i class="clip-database"></i>
    <a href="{!! route('rules.index') !!}">
        @lang('site.rules')
    </a>
</li>
<li class="active">
    @lang('rule/title.create_a_new_rule')
</li>
@endsection


{{-- Content --}}
@section('content')
<!-- Notifications -->
@include('partials.notifications')
<!-- ./ notifications -->

{!! Form::open(['route' => 'rules.store', 'method' => 'post']) !!}
@include('rule._form')
{!! Form::close() !!}
@endsection

