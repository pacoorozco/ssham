@extends('layouts.master')

{{-- Styles --}}
@section('styles')
{!! HTML::style('//cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.css') !!}
@stop

{{-- Web site Title --}}
@section('title')
	{{ $title }} :: @parent
@stop

{{-- Content Header --}}
@section('header')
    <h1> 
        {{ $title }} <small>create and edit users</small>
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
@include('partials/notifications')
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
        <table id="users" class="table table-striped table-bordered table-hover table-full-width">
        <thead>
            <tr>
                <th class="col-md-4">{!! Lang::get('user/table.name') !!}</th>
                <th class="col-md-1">{!! Lang::get('user/table.type') !!}</th>
                <th class="col-md-4">{!! Lang::get('user/table.fingerprint') !!}</th>
                <th class="col-md-1">{!! Lang::get('user/table.active') !!}</th>
                <th class="col-md-2">{!! Lang::get('user/table.actions') !!}</th>
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
    oTable = $('#users').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.7/i18n/{!! Lang::get('site.datatables_lang') !!}.json"
        },
        "processing": true,
        "serverSide": true,
        "ajax": "{!! route('users.data') !!}",
        "columns": [
            {data: "name"},
            {data: "type"},
            {data: "fingerprint", "orderable": false},
            {data: "active"},
            {data: "actions", "orderable": false, "searchable": false}
        ]
    });
});
</script>
@stop