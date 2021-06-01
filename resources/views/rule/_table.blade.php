{{-- Styles --}}
@push('styles')
    <link rel="stylesheet" type="text/css"
          href="{{ asset('vendor/AdminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
@endpush

<table id="rules-table" class="table table-bordered table-hover" data-form="deleteForm">
    <thead>
    <tr>
        <th>#</th>
        <th>@lang('rule/table.source')</th>
        <th>@lang('rule/table.target')</th>
        <th>@lang('rule/table.action')</th>
        <th>@lang('rule/table.name')</th>
        <th>@lang('rule/table.actions')</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th>#</th>
        <th>@lang('rule/table.source')</th>
        <th>@lang('rule/table.target')</th>
        <th>@lang('rule/table.action')</th>
        <th>@lang('rule/table.name')</th>
        <th>@lang('rule/table.actions')</th>
    </tr>
    </tfoot>
</table>

<!-- confirmation modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1"
     aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="#" method="post" id="confirmationForm">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">
                        @lang('rule/messages.confirmation_title')
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning" role="alert">
                        @lang('rule/messages.delete_confirmation_warning')
                    </div>
                    <dl>
                        <dt>@lang('rule/model.source')</dt>
                        <dd id="source-text">N/A</dd>
                        <dt>@lang('rule/model.target')</dt>
                        <dd id="target-text">N/A</dd>
                        <dt>@lang('rule/model.action')</dt>
                        <dd id="action-text">N/A</dd>
                    </dl>
                    <p>
                        @lang('rule/messages.confirmation_help', ['confirmationText' => 'delete rule'])
                    </p>
                    <input id="confirmationInput" type="text" class="form-control" autocomplete="off">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-block btn-outline-danger" id="confirmationButton"
                            disabled="disabled">
                        @lang('rule/messages.delete_button')
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- ./ confirmation modal -->

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
                    {data: "source"},
                    {data: "target"},
                    {data: "action"},
                    {data: "name"},
                    {data: "actions", "orderable": false, "searchable": false}
                ],
                "order": [[3, 'asc'], [1, 'asc']],
                "aLengthMenu": [
                    [5, 10, 15, 20, -1],
                    [5, 10, 15, 20, "@lang('general.all')"]
                ],
                // set the initial value
                "iDisplayLength": 10
            });
        });

        $('#confirmationInput').keyup(function (e) {
            if ($('#confirmationInput').val().trim() === 'delete rule') {
                $('#confirmationButton').removeAttr('disabled');
            } else {
                $('#confirmationButton').attr('disabled', 'true');
            }
        });

        $('#confirmationModal').on('show.bs.modal', function (event) {
            $('#confirmationInput').trigger('focus');

            var button = $(event.relatedTarget);
            var ruleId = button.data('rule-id');
            var source = button.data('rule-source');
            var target = button.data('rule-target');
            var action = button.data('rule-action');
            $('#confirmationForm').attr('action', 'rules/' + ruleId);
            $('#source-text').text(source);
            $('#target-text').text(target);
            $('#action-text').text(action);
        });

    </script>
@endpush
