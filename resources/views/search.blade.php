@extends('layouts.master')

{{-- Web site Title --}}
@section('title', __('search/messages.title'))

{{-- Content Header --}}
@section('header')
    @lang('search/messages.title')
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('site.search')
    </li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h2>@lang('search/messages.header', ['count' => $count, 'query' => $query])</h2>
            </div>

            <div class="card-body">
                @foreach($searchResults->groupByType() as $type => $modelSearchResults)
                    <h3>{{ ucfirst($type) }}</h3>

                    @foreach($modelSearchResults as $searchResult)
                        <ul>
                            <li><a href="{{ $searchResult->url }}">{{ $searchResult->title }}</a></li>
                        </ul>
                    @endforeach
                @endforeach
            </div>
        </div>
    </div>
@endsection
