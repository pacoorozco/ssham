{{-- Styles --}}
@push('styles')
    <link rel="stylesheet" type="text/css"
          href="{{ asset('vendor/AdminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
@endpush

<table id="activities-table" class="table table-bordered table-hover">
    <thead>
    <tr>
        <th>{{ __('activity/model.operation') }}</th>
        <th>{{ __('activity/model.status') }}</th>
        <th>{{ __('activity/model.time') }}</th>
        <th>{{ __('activity/model.timestamp') }}</th>
        <th>{{ __('activity/model.causer') }}</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th>{{ __('activity/model.operation') }}</th>
        <th>{{ __('activity/model.status') }}</th>
        <th>{{ __('activity/model.time') }}</th>
        <th>{{ __('activity/model.timestamp') }}</th>
        <th>{{ __('activity/model.causer') }}</th>
    </tr>
    </tfoot>
</table>

{{-- Scripts --}}
@push('scripts')
    <script src="{{ asset('vendor/AdminLTE/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/AdminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(function () {
            $('#activities-table').DataTable({
                "ajax": "{{ route('audit.data') }}",
                "columns": [
                    {data: "description"},
                    {data: "status"},
                    {data: "time"},
                    {data: "timestamp"},
                    {data: "causer"},
                ],
                // set order by 'timestamp'
                "order": [[ 3, "desc" ]],
                "aLengthMenu": [
                    [5, 10, 15, 20, -1],
                    [5, 10, 15, 20, "{{ __('general.all') }}"]
                ],
                // set the initial value
                "iDisplayLength": 15
            });
        });
    </script>
@endpush
