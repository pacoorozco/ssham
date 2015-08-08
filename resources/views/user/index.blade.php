@extends('layouts.master')

{{-- Styles --}}
@section('styles')
    {!! HTML::style('//cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.css') !!}
@endsection

{{-- Web site Title --}}
@section('title')
    @lang('user/title.user_management')
@endsection

{{-- Content Header --}}
@section('header')
    <h1> 
        @lang('user/title.user_management') <small>@lang('user/title.user_management_subtitle')</small>
    </h1>
@endsection

{{-- Breadcrumbs --}}
@section('breadcrumbs')
<li>
    <i class="clip-user"></i>
    <a href="{!! route('users.index') !!}">
        @lang('site.users')
    </a>
</li>
<li class="active">
    @lang('user/title.user_management')
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
        <a class="btn btn-green add-row" href="{!! route('users.create') !!}">
            <i class="fa fa-plus"></i> @lang('user/title.create_a_new_user')
        </a>
        <a class="btn btn-blue add-row" href="{!! route('usergroups.index') !!}">
            <i class="clip-users"></i> @lang('usergroup/title.user_group_management')
        </a>
    </div>
</div>
<!-- /.actions -->

<div class="row">
    <div class="col-xs-12">
        <table id="users" class="table table-striped table-bordered table-hover table-full-width">
        <thead>
            <tr>
                <th class="col-md-4">@lang('user/table.username')</th>
                <th class="col-md-5">@lang('user/table.fingerprint')</th>
                <th class="col-md-1">@lang('user/table.groups')</th>
                <th class="col-md-2">@lang('user/table.actions')</th>
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
<script>
$(document).ready(function() {
    oTable = $('#users').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.7/i18n/@lang('site.language').json"
        },
        "ajax": "{!! route('users.data') !!}",
        "columns": [
            {data: "username"},
            {data: "fingerprint", "orderable": false, "searchable": true},
            {data: "groups", "orderable": false, "searchable": false},
            {data: "actions", "orderable": false, "searchable": false}
        ]
    });
});

$('#flash-overlay-modal').modal();

</script>
@endsection
