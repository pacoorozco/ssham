{{-- Styles --}}
@push('styles')
    <link rel="stylesheet" type="text/css"
          href="{{ asset('vendor/AdminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
@endpush

<table id="rules-table" class="table table-bordered table-hover" data-form="deleteForm">
    <thead>
    <tr>
        <th>#</th>
        <th>@lang('rule/table.user_group')</th>
        <th>@lang('rule/table.host_group')</th>
        <th>@lang('rule/table.action')</th>
        <th>@lang('rule/table.description')</th>
        <th>@lang('rule/table.actions')</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th>#</th>
        <th>@lang('rule/table.user_group')</th>
        <th>@lang('rule/table.host_group')</th>
        <th>@lang('rule/table.action')</th>
        <th>@lang('rule/table.description')</th>
        <th>@lang('rule/table.actions')</th>
    </tr>
    </tfoot>
</table>

<div class="modal" id="deleteModal" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">@lang('general.delete')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>@lang('rule/messages.confirm_delete')</p>
            </div>
            <div class="modal-footer">
                {!! Form::open(['method' => 'delete', 'class' =>'form-inline', 'id' => 'deleteForm']) !!}
                {!! Form::button('<i class="fa fa-trash"></i> ' . __('general.delete'), ['type' => 'submit', 'class' => 'btn btn-danger']) !!}
                {!! Form::close() !!}
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- Scripts --}}
@push('scripts')
    <script src="{{ asset('vendor/AdminLTE/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/AdminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(function () {
            $('#rules-table').DataTable({
                "ajax": "{{ route('rules.data') }}",
                "columns": [
                    {data: "id", "orderable": false},
                    {data: "keygroup"},
                    {data: "hostgroup"},
                    {data: "action"},
                    {data: "name"},
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

        $(function () {
            $('#deleteModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var rule = button.data('rule-id'); // Extract info from data-* attributes
                var modal = $(this);
                modal.find('.modal-title').text('@lang('rule/messages.confirm_delete_title')' + rule);
                modal.find('.modal-footer #deleteForm').attr('action', 'rules/' + rule);
            })
        });
    </script>
@endpush
