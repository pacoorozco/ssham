@extends('layouts.master')

{{-- Styles --}}
@section('styles')
    {!! HTML::style('//cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.css') !!}
@stop

{{-- Web site Title --}}
@section('title')
    @lang('hostgroup/title.host_group_management')
@stop

{{-- Content Header --}}
@section('header')
    <h1> 
        @lang('hostgroup/title.host_group_management') <small>@lang('hostgroup/title.host_group_management_subtitle')</small>
    </h1>
@stop

{{-- Breadcrumbs --}}
@section('breadcrumbs')
<li>
    <i class="fa fa-tasks"></i>
    <a href="{!! route('hostgroups.index') !!}">
        @lang('site.host_groups')
    </a>
</li>
<li class="active">
    @lang('hostgroup/title.host_group_management')
</li>
@stop

{{-- Content --}}
@section('content')

<!-- Notifications -->
@include('partials.notifications')
<!-- ./ notifications -->

<!-- actions -->
<div class="row">
    <div class="col-md-12 space20">
        <a class="btn btn-green add-row" href="{!! route('hostgroups.create') !!}">
            <i class="fa fa-plus"></i> @lang('hostgroup/title.create_a_new_host_group')
        </a>
    </div>
</div>
<!-- /.actions -->

<div class="row">
    <div class="col-xs-12">
        <table id="hostgroups" class="table table-striped table-bordered table-hover table-full-width">
        <thead>
            <tr>
                <th class="col-md-4">@lang('hostgroup/table.name')</th>
                <th class="col-md-5">@lang('hostgroup/table.description')</th>
                <th class="col-md-1">@lang('hostgroup/table.hosts')</th>
                <th class="col-md-2">@lang('hostgroup/table.actions')</th>
            </tr>
        </thead>
        </table>
    </div>
</div>

@stop

{{-- Scripts --}}
@section('scripts')
{!! HTML::script('//cdn.datatables.net/1.10.7/js/jquery.dataTables.js') !!}
{!! HTML::script('//cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.js') !!}
<script>
$(document).ready(function() {
    oTable = $('#hostgroups').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.7/i18n/@lang('site.datatables_lang').json"
        },
        "ajax": "{!! route('hostgroups.data') !!}",
        "columns": [
            {data: "name"},
            {data: "description"},
            {data: "hosts", "searchable": false},
            {data: "actions", "orderable": false, "searchable": false}
        ]
    });
});
</script>
@stop