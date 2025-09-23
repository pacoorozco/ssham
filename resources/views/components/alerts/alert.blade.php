@props([
    'type',
    ])

<div class="alert alert-dismissible alert-{{ $type }} fade show" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <h4 class="alert-heading">
        <i @class([
    'icon',
    'bi bi-check-circle-fill' => $type === 'success',
    'bi bi-exclamation-triangle-fill' => $type === 'warning',
    'bi bi-info-circle-fill' => $type === 'info',
    'bi bi-shield-fill-exclamation' => $type === 'danger',
])></i>
        {{ __('general.' . $type) }}
    </h4>
    {{ $slot }}
</div>
