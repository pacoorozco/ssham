{{-- Styles --}}
@push('styles')
    <link rel="stylesheet" type="text/css"
          href="{{ asset('vendor/AdminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
@endpush

<table id="usergroups-table" class="table table-bordered table-hover">
    <thead>
    <tr>
        <th>@lang('usergroup/table.name')</th>
        <th>@lang('usergroup/table.description')</th>
        <th>@lang('usergroup/table.users')</th>
        <th>@lang('usergroup/table.actions')</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th>@lang('usergroup/table.name')</th>
        <th>@lang('usergroup/table.description')</th>
        <th>@lang('usergroup/table.users')</th>
        <th>@lang('usergroup/table.actions')</th>
    </tr>
    </tfoot>
</table>

{{-- Scripts --}}
@push('scripts')
    <script src="{{ asset('vendor/AdminLTE/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/AdminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(function () {
            $('#usergroups-table').DataTable({
                "ajax": "{{ route('usergroups.data') }}",
                "columns": [
                    {data: "name"},
                    {data: "description"},
                    {data: "users", "searchable": false},
                    {data: "actions", "orderable": false, "searchable": false}
                ],
                "aLengthMenu": [
                    [5, 10, 15, 20, -1],
                    [5, 10, 15, 20, "@lang('general.all')"]
                ],
                // set the initial value
                "iDisplayLength": 10
            });
        });
    </script>
@endpush
