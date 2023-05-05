{{-- Styles --}}
@push('styles')
    <link rel="stylesheet" type="text/css"
          href="{{ asset('vendor/AdminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
@endpush

<table id="keys-table" class="table table-bordered table-hover">
    <thead>
    <tr>
        <th>@lang('key/table.name')</th>
        <th>@lang('key/table.fingerprint')</th>
        <th>@lang('key/table.groups')</th>
        <th>@lang('key/table.enabled')</th>
        <th>@lang('key/table.actions')</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th>@lang('key/table.name')</th>
        <th>@lang('key/table.fingerprint')</th>
        <th>@lang('key/table.groups')</th>
        <th>@lang('key/table.enabled')</th>
        <th>@lang('key/table.actions')</th>
    </tr>
    </tfoot>
</table>

{{-- Scripts --}}
@push('scripts')
    <script src="{{ asset('vendor/AdminLTE/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/AdminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(function () {
            $('#keys-table').DataTable({
                "ajax": "{{ route('keys.data') }}",
                "columns": [
                    {data: "name"},
                    {data: "fingerprint", "orderable": false, "searchable": true},
                    {data: "groups", "orderable": false, "searchable": false},
                    {data: "enabled"},
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
