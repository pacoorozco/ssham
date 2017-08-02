{{-- Styles --}}
@push('styles')
{!! HTML::style('themes/AdminLTE/plugins/datatables/dataTables.bootstrap.css') !!}
@endpush

<table id="users-table" class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>{{ trans('user/table.username') }}</th>
        <th>{{ trans('user/table.name') }}</th>
        <th>{{ trans('user/table.fingerprint') }}</th>
        <th>{{ trans('general.actions') }}</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th>{{ trans('user/table.username') }}</th>
        <th>{{ trans('user/table.name') }}</th>
        <th>{{ trans('user/table.fingerprint') }}</th>
        <th>{{ trans('general.actions') }}</th>
    </tr>
    </tfoot>
</table>

{{-- Scripts --}}
@push('scripts')
{!! HTML::script('themes/AdminLTE/plugins/datatables/jquery.dataTables.min.js') !!}
{!! HTML::script('themes/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js') !!}

<script>
    $(function () {
        $('#users-table').DataTable({
            "ajax": "{{ route('users.data') }}",
            "columns": [
                {data: "username"},
                {data: "name"},
                {data: "fingerprint", "orderable": false, "searchable": true},
                {data: "actions", "orderable": false, "searchable": false}
            ],
            "order": [[1, 'asc'], [0, 'asc']],
            "aLengthMenu": [
                [5, 10, 15, 20, -1],
                [5, 10, 15, 20, "{{ trans('general.all') }}"]
            ],
            // set the initial value
            "iDisplayLength": 10,
            "autoWidth" : false
        });
    });
</script>
@endpush
