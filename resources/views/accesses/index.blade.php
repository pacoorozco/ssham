@extends('layouts.master')

{{-- Styles --}}
@section('styles')
    {!! HTML::style('//cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.css') !!}
@stop

{{-- Web site Title --}}
@section('title')
    {!! Lang::get('permission/title.permission_management') !!} :: @parent
@stop

{{-- Content Header --}}
@section('header')
    <h1> 
        {!! Lang::get('permission/title.permission_management') !!} <small>permissions</small>
    </h1>
@stop

{{-- Breadcrumbs --}}
@section('breadcrumbs')
<li>
    <i class="clip-bubbles-3"></i>
    <a href="{!! route('users.index') !!}">
        {!! Lang::get('site.users') !!}
    </a>
</li>
<li class="active">
    {!! Lang::get('user/title.user_management') !!}
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
        <a class="btn btn-green add-row" href="{!! route('users.create') !!}">
            <i class="fa fa-plus"></i> {!! Lang::get('user/title.create_a_new_user') !!}
        </a>
    </div>
</div>
<!-- /.actions -->

<div class="row">
    <div class="col-xs-12">
        <table id="rules" class="table table-striped table-bordered table-hover table-full-width">
        <thead>
            <tr>
                <th class="col-md-4">{!! Lang::get('rule/table.usergroup') !!}</th>
                <th class="col-md-4">{!! Lang::get('rule/table.hostgroup') !!}</th>
                <th class="col-md-2">{!! Lang::get('rule/table.permission') !!}</th>
                <th class="col-md-2">{!! Lang::get('rule/table.active') !!}</th>
                <th class="col-md-2">{!! Lang::get('rule/table.actions') !!}</th>
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
    oTable = $('#rules').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.7/i18n/{!! Lang::get('site.language') !!}.json"
        },
        "processing": true,
        "serverSide": true,
        "ajax": "{!! route('rule.data') !!}",
        "columns": [
            {data: "usergroup"},
            {data: "hostgroup"},
            {data: "permission"},
            {data: "active"},
            {data: "actions", "orderable": false, "searchable": false}
        ]
    });
});
</script>
@stop