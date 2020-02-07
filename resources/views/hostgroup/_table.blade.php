{{-- Styles --}}
@push('styles')
    <link rel="stylesheet" type="text/css"
          href="{{ asset('vendor/AdminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
@endpush

<table id="hostgroups-table" class="table table-bordered table-hover">
    <thead>
    <tr>
    <tr>
        <th>@lang('hostgroup/table.name')</th>
        <th>@lang('hostgroup/table.description')</th>
        <th>@lang('hostgroup/table.hosts')</th>
        <th>@lang('hostgroup/table.rules')</th>
        <th>@lang('hostgroup/table.actions')</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th>@lang('hostgroup/table.name')</th>
        <th>@lang('hostgroup/table.description')</th>
        <th>@lang('hostgroup/table.hosts')</th>
        <th>@lang('hostgroup/table.rules')</th>
        <th>@lang('hostgroup/table.actions')</th>
    </tr>
    </tfoot>
</table>

{{-- Scripts --}}
@push('scripts')
    <script src="{{ asset('vendor/AdminLTE/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/AdminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(function () {
            $('#hostgroups-table').DataTable({
                "ajax": "{{ route('hostgroups.data') }}",
                "columns": [
                    {data: "name"},
                    {data: "description"},
                    {data: "hosts", "searchable": false},
                    {data: "rules", "searchable": false},
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
