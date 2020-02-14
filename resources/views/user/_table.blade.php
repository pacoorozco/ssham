{{-- Styles --}}
@push('styles')
    <link rel="stylesheet" type="text/css"
          href="{{ asset('vendor/AdminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
@endpush

<table id="users-table" class="table table-bordered table-hover">
    <thead>
    <tr>
        <th>@lang('user/table.username')</th>
        <th>@lang('user/table.email')</th>
        <th>@lang('user/table.actions')</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th>@lang('user/table.username')</th>
        <th>@lang('user/table.email')</th>
        <th>@lang('user/table.actions')</th>
    </tr>
    </tfoot>
</table>

{{-- Scripts --}}
@push('scripts')
    <script src="{{ asset('vendor/AdminLTE/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/AdminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(function () {
            $('#users-table').DataTable({
                "ajax": "{{ route('users.data') }}",
                "columns": [
                    {data: "username"},
                    {data: "email"},
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
