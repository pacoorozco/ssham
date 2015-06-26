@extends('layouts.master')

{{-- Styles --}}
@section('styles')
    {!! HTML::style('//cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.css') !!}
@stop

{{-- Web site Title --}}
@section('title')
    {!! Lang::get('hostgroup/title.hostgroup_management') !!} :: @parent
@stop

{{-- Content Header --}}
@section('header')
    <h1> 
        {!! Lang::get('hostgroup/title.hostgroup_management') !!} <small>create and edit hostgroups</small>
    </h1>
@stop

{{-- Breadcrumbs --}}
@section('breadcrumbs')
<li>
    <i class="clip-bubbles-3"></i>
    <a href="{!! route('hostgroups.index') !!}">
        {!! Lang::get('site.hostgroups') !!}
    </a>
</li>
<li class="active">
    {!! Lang::get('hostgroup/title.hostgroup_management') !!}
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
            <i class="fa fa-plus"></i> {!! Lang::get('hostgroup/title.create_a_new_hostgroup') !!}
        </a>
    </div>
</div>
<!-- /.actions -->

<div class="row">
    <div class="col-xs-12">
        <table id="hostgroups" class="table table-striped table-bordered table-hover table-full-width">
        <thead>
            <tr>
                <th class="col-md-4">{!! Lang::get('hostgroup/table.name') !!}</th>
                <th class="col-md-5">{!! Lang::get('hostgroup/table.description') !!}</th>
                <th class="col-md-1">{!! Lang::get('hostgroup/table.hosts') !!}</th>
                <th class="col-md-2">{!! Lang::get('hostgroup/table.actions') !!}</th>
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
            "url": "//cdn.datatables.net/plug-ins/1.10.7/i18n/{!! Lang::get('site.datatables_lang') !!}.json"
        },
        "processing": true,
        "serverSide": true,
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