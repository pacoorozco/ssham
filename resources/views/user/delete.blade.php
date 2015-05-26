@extends('layouts.admin')

{{-- Web site Title --}}
@section('title')
	{{{ $title }}} :: @parent
@stop

{{-- Content Header --}}
@section('header')
<h1>
    {{{ $title }}} <small>{{{ $user->username }}}</small>
</h1>
@stop

{{-- Breadcrumbs --}}
@section('breadcrumbs')
<li>
    <i class="clip-bubbles-3"></i>
    <a href="{{ URL::route('admin.users.index') }}">
        {{ Lang::get('admin/site.users') }}
    </a>
</li>
<li class="active">
    {{ Lang::get('admin/user/title.user_delete') }}
</li>
@stop

{{-- Content --}}
@section('content')

<!-- Notifications -->
@include('notifications')
<!-- ./ notifications -->
        
{{-- Delete User Form --}}
{{ Form::open(array('route' => array('admin.users.destroy', $user->id), 'method' => 'delete', )) }}
@include('admin/user/_details', compact('user'))
{{ Form::close() }}

@stop