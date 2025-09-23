@props([
'title' => __('components/modals/confirmation.title'),
'action',
'confirmationText',
'buttonText',
])

<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog"
     aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <x-forms.form action="{{ $action }}" method="delete">

                <div class="modal-header">
                    <h4 class="modal-title" id="confirmationModalLabel">
                        {{ $title }}
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ $slot }}
                    <p>
                        {{ __('components/modals/confirmation.help', ['confirmationText' => $confirmationText]) }}
                    </p>
                    <input id="confirmationInput" type="text" class="form-control" autocomplete="off">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-block btn-danger" id="confirmationButton"
                            disabled="disabled">
                        {{ $buttonText }}
                    </button>
                </div>

            </x-forms.form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $('#confirmationInput').keyup(function () {
            if ($('#confirmationInput').val().trim() === '{{ $confirmationText }}') {
                $('#confirmationButton').removeAttr('disabled');
            } else {
                $('#confirmationButton').attr('disabled', 'true');
            }
        });
        $('#confirmationModal').on('shown.bs.modal', function () {
            $('#confirmationInput').trigger('focus')
        })
    </script>
@endpush

