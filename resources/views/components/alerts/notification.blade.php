@props([
    'id',
    'title' => __('general.notifications.title'),
    'icon' => 'bi bi-megaphone-fill',
    'when' => 'now'
    ])

<div class="alert alert-success alert-dismissible" role="alert">
    <div class="row">
        <div class="col-1 text-center">
            <i class="{{ $icon }}" style="font-size: 3em;"></i>
        </div>
        <div class="col">
            <small class="float-right time">
                <i class="bi bi-clock-fill"></i>
                {{ $when }}
            </small>
            <h5>
                {{ $title }}
            </h5>
            <div>
                <a href="#" class="float-right mark-as-read" data-id="{{ $id }}" data-dismiss="alert">
                    {{ __('general.notifications.mark_as_read') }}
                </a>
                {{ $slot }}
            </div>
        </div>
    </div>
</div>

@pushonce('scripts')
    <script>
        function sendMarkRequest(id = null) {
            return $.ajax({
                url: "{{ route('notifications.read') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'PATCH',
                    id: id
                }
            });
        }

        $(function () {
            $('.mark-as-read').click(function () {
                sendMarkRequest($(this).data('id'));
            });
        });
    </script>
@endpushonce
