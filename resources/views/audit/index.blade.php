@extends('layouts.master')


{{-- Web site Title --}}
@section('title',  __('site.audit'))

{{-- Content Header --}}
@section('header')
    <i class="fa fa-binoculars"></i> {{ __('site.audit') }}
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item active">
        {{ __('site.audit') }}
    </li>
@endsection

{{-- Content --}}
@section('content')
    <div class="container-fluid">

        <!-- Notifications -->
        @include('partials.notifications')
        <!-- ./ notifications -->

        <div class="card">
            <div class="card-body">
                @include('audit._table')
            </div>
        </div>
    </div>
@endsection

