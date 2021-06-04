@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('search/messages.title'))

{{-- Content Header --}}
@section('header')
    <i class="fa fa-search"></i> @lang('search/messages.title')
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('site.search')
    </li>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <!-- start: SEARCH FORM -->
            <x-search.form/>
        <!-- end: SEARCH FORM -->
        </div>
    </div>
@endsection
