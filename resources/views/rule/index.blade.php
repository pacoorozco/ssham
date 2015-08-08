@extends('layouts.master')

@section('meta')
<meta name="csrf-token" content="{!! csrf_token() !!}" />
<meta name="csrf-param" content="_token" />
@endsection

{{-- Styles --}}
@section('styles')
    {!! HTML::style('//cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.css') !!}
@endsection

{{-- Web site Title --}}
@section('title')
    @lang('rule/title.rule_management')
@endsection

{{-- Content Header --}}
@section('header')
    <h1> 
        @lang('rule/title.rule_management') <small>@lang('rule/title.rule_management_subtitle')</small>
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
    @lang('rule/title.rule_management')
</li>
@endsection

{{-- Content --}}
@section('content')

<!-- Notifications -->
@include('partials.notifications')
<!-- ./ notifications -->

<!-- actions -->
<div class="row">
    <div class="col-md-12 space20">
        <a class="btn btn-green add-row" href="{!! route('rules.create') !!}">
            <i class="fa fa-plus"></i> @lang('rule/title.create_a_new_rule')
        </a>
    </div>
</div>
<!-- /.actions -->

<div class="row">
    <div class="col-xs-12">
        <table id="rules" class="table table-striped table-bordered table-hover table-full-width">
        <thead>
            <tr>
                <th class="col-md-4">@lang('rule/table.user_group')</th>
                <th class="col-md-4">@lang('rule/table.host_group')</th>
                <th class="col-md-2">@lang('rule/table.action')</th>
                <th class="col-md-2">@lang('rule/table.enabled')</th>
                <th class="col-md-2">@lang('rule/table.actions')</th>
            </tr>
        </thead>
        </table>
    </div>
</div>

@endsection

{{-- Scripts --}}
@section('scripts')
{!! HTML::script('//cdn.datatables.net/1.10.7/js/jquery.dataTables.js') !!}
{!! HTML::script('//cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.js') !!}
{!! HTML::script('js/jquery-ujs/rails.js') !!}
<script>
$(document).ready(function() {
    oTable = $('#rules').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.7/i18n/@lang('site.language').json"
        },
        "ajax": "{!! route('rules.data') !!}",
        "columns": [
            {data: "usergroup"},
            {data: "hostgroup"},
            {data: "action"},
            {data: "enabled"},
            {data: "actions", "orderable": false, "searchable": false}
        ]
    });
});
</script>
@endsection