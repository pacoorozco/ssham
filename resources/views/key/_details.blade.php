<div class="card">
    <div class="card-header bg-cyan">
        <h2 class="card-title">{{ $key->username }} @if(!$key->enabled)<span
                class="badge badge-pill badge-secondary">{{ __('general.disabled') }}</span>@endif </h2>
    </div>
    <div class="card-body">

        <!-- username -->
        <div class="row">
            <div class="col-2">
                <strong>@lang('key/model.username')</strong>
            </div>
            <div class="col-10">
                {{ $key->username }}
            </div>
        </div>
        <!-- ./ username -->

    @if (!empty($key->private))
        <!-- private key -->
            <div class="row hide-after-download">
                <div class="col-2">
                    <strong>@lang('key/model.private_key')</strong>
                </div>
                <div class="col-10">
                    <button type="button" class="btn btn-secondary" data-toggle="modal"
                            data-target="#download-private-key">
                        <i class="fa fa-download"></i> @lang('key/messages.private_key_available')
                    </button>
                </div>
            </div>
            <!-- ./ private key -->
    @endif

    <!-- fingerprint -->
        <div class="row">
            <div class="col-2">
                <strong>@lang('key/model.fingerprint')</strong>
            </div>
            <div class="col-10">
                {{ $key->fingerprint }}
            </div>
        </div>
        <!-- ./ fingerprint -->

        <!-- public key -->
        <div class="row">
            <div class="col-2">
                <strong>@lang('key/model.public_key')</strong>
            </div>
            <div class="col-10">
                <pre class="key-code">{{ $key->public }}</pre>
            </div>
        </div>
        <!-- ./ public key -->

        <!-- groups -->
        <div class="row">
            <div class="col-2">
                <strong>@lang('key/model.groups')</strong>
            </div>
            <div class="col-10">
                <ul class="list-inline">
                    @forelse($key->groups as $group)
                        <li class="list-inline-item"><a
                                href="{{ route('keygroups.show', $group->id) }}">{{ $group->name }}</a></li>
                    @empty
                        <li class="list-inline-item">@lang('key/model.no_groups')</li>
                    @endforelse
                </ul>
            </div>
        </div>
        <!-- ./ groups -->

        <!-- enabled -->
        <div class="row">
            <div class="col-2">
                <strong>@lang('key/model.enabled')</strong>
            </div>
            <div class="col-10">
                @if ($key->enabled)
                    <span class="badge badge-pill badge-success">{{ __('general.enabled') }}</span>
                @else
                    <span class="badge badge-pill badge-secondary">{{ __('general.disabled') }}</span>
                @endif
            </div>
        </div>
        <!-- ./ enabled -->

    </div>
    <div class="card-footer">
        <a href="{{ route('keys.index') }}" class="btn btn-primary" role="button">
            <i class="fa fa-arrow-left"></i> @lang('general.back')
        </a>
        @if ($action == 'show')
            <a href="{{ route('keys.edit', $key->id) }}" class="btn btn-primary" role="button">
                <i class="fa fa-pen"></i> @lang('general.edit')
            </a>
            @if (!empty($key->private))
                <button type="button" class="btn btn-secondary hide-after-download" data-toggle="modal"
                        data-target="#download-private-key">
                    <i class="fa fa-download"></i> @lang('key/messages.private_key_available')
                </button>
            @endif
        @else
            <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> @lang('general.delete')</button>
        @endif
    </div>
</div>

@if (!empty($key->private))
    <!-- download private key modal -->
    <div class="modal fade" id="download-private-key">
        <!-- modal-dialog -->
        <div class="modal-dialog">
            <!-- modal-content -->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('key/title.download_private_key')</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="@lang('general.close')">
                        <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    @lang('key/messages.download_private_key_help')
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                        @lang('general.close')
                    </button>
                    <a href="{{ route('keys.download', $key->id) }}" class="btn btn-primary" role="button"
                       id="download-button">
                        <i class="fa fa-download"></i> @lang('key/messages.download_private_key')
                    </a>
                </div>
            </div>
            <!-- ./modal-content -->
        </div>
        <!-- ./modal-dialog -->
    </div>
    <!-- ./download private key modal -->
@endif

@push('scripts')
    <script>
        $('#download-button').click(function () {
            $('.hide-after-download').addClass("d-none");
            $('#download-private-key').modal('hide');
        })
    </script>
@endpush
