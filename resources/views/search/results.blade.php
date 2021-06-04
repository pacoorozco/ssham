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
    <!-- card -->
    <div class="card">

        <div class="card-header">
            <x-search.form searchString="{{ $searchString }}"/>
        </div>

        <div class="card-body">

            <h3>@lang('search/messages.results_section')</h3>
            <p class="text-muted">@lang('search/messages.showing_all_results', ['searchString' => $searchString])</p>

            @forelse($searchResults->groupByType() as $type => $modelSearchResults)
                <h4>{{ $type }}</h4>
                <ul>
                    @foreach($modelSearchResults as $searchResult)
                        <li>
                            <a href="{{ $searchResult->url }}">
                                {{ $searchResult->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @empty
                <p>
                    @lang('search/messages.no_results')
                </p>
            @endforelse
        </div>

    </div>
    <!-- ./ card -->
@endsection
