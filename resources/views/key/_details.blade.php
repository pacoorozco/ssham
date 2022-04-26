<div class="card">
    <div class="card-header @unless($key->enabled) bg-gray-dark @endunless">
        <h2 class="card-title">
            {{ $key->present()->username }}
            @unless($key->enabled)
                {{ $key->present()->enabledAsBadge() }}
            @endunless
        </h2>
    </div>

    <div class="card-body">
        <div class="row">
            <!-- left column -->
            <div class="col-md-6">

                <h3>@lang('key/title.key_identification_section')</h3>
                <dl class="row">

                    <!-- username -->
                    <dt class="col-sm-3">
                        <strong>@lang('key/model.username')</strong>
                    </dt>
                    <dd class="col-sm-9">
                        {{ $key->present()->username }}
                    </dd>
                    <!-- ./ username -->

                    <!-- fingerprint -->
                    <dt class="col-sm-3">
                        <strong>@lang('key/model.fingerprint')</strong>
                    </dt>
                    <dd class="col-sm-9">
                        {{ $key->present()->fingerprint }}
                    </dd>
                    <!-- ./ fingerprint -->

                    <!-- public key -->
                    <dt class="col-sm-3">
                        <strong>@lang('key/model.public_key')</strong>
                    </dt>
                    <dd class="col-sm-9">
                        <pre class="key-code">{{ $key->present()->public }}</pre>
                    </dd>
                    <!-- ./ public key -->

                </dl>

            @unless(empty($key->private))
                <!-- private key -->
                    <dl class="row hide-after-download">
                        <dt class="col-sm-3">
                            <strong>@lang('key/model.private_key')</strong>
                        </dt>
                        <dd class="col-sm-9">
                            <button type="button" class="btn btn-secondary" data-toggle="modal"
                                    data-target="#download-private-key">
                                <i class="fa fa-download"></i> @lang('key/messages.private_key_available')
                            </button>
                        </dd>
                    </dl>
                    <!-- ./ private key -->
                @endunless

            </div>
            <!-- ./ left column -->
            <!-- right column -->
            <div class="col-md-6">

                <h3>@lang('key/title.membership_section')</h3>

                <!-- groups -->
                <dl class="row">
                    <dt class="col-sm-3">
                        <strong>@lang('key/model.groups')</strong>
                    </dt>
                    <dd class="col-sm-9">
                        @forelse($key->groups as $group)
                            @if ($loop->first)
                                {{ $key->groups->count() }} @lang('keygroup/model.item')
                                <ul class="list-inline">
                                    @endif

                                    <li class="list-inline-item">
                                        <a href="{{ route('keygroups.show', $group->id) }}">{{ $group->name }}</a>
                                    </li>

                                    @if ($loop->last)
                                </ul>
                            @endif
                        @empty
                            @lang('key/messages.groups_empty')
                        @endforelse
                    </dd>
                </dl>
                <!-- ./ groups -->

                <h3>@lang('key/title.status_section')</h3>
                <dl class="row">
                    <!-- created at -->
                    <dt class="col-3">
                        <strong>@lang('key/model.created_at')</strong>
                    </dt>
                    <dd class="col-9">
                        {{ $key->present()->createdAtForHumans() }} ({{ $key->present()->created_at }})
                    </dd>
                    <!-- ./ created at -->

                    <!-- updated at -->
                    <dt class="col-3">
                        <strong>@lang('key/model.updated_at')</strong>
                    </dt>
                    <dd class="col-9">
                        {{ $key->present()->updatedAtForHumans() }} ({{ $key->present()->updated_at }})
                    </dd>
                    <!-- ./ updated at -->

                    <!-- enabled -->
                    <dt class="col-sm-3">
                        <strong>@lang('key/model.enabled')</strong>
                    </dt>
                    <dd class="col-sm-9">
                        {{ $key->present()->enabledAsBadge() }}
                    </dd>
                    <!-- ./ enabled -->
                </dl>

                <fieldset class="mt-5">
                    <legend>@lang('key/messages.danger_zone_section')</legend>

                    @can('delete', $key)
                        <ul class="list-group border border-danger">
                            <li class="list-group-item">
                                <strong>@lang('key/messages.delete_button')</strong>
                                <button type="button" class="btn btn-outline-danger btn-sm float-right"
                                        data-toggle="modal"
                                        data-target="#confirmationModal">
                                    @lang('key/messages.delete_button')
                                </button>
                                <p>@lang('key/messages.delete_help')</p>
                            </li>
                        </ul>
                    @else
                        <p class="text-muted">@lang('key/messages.delete_avoided')</p>
                    @endcan
                </fieldset>


            </div>
            <!-- ./ right column -->
        </div>

    </div>
    <div class="card-footer">
        <a href="{{ route('keys.edit', $key) }}" class="btn btn-primary @cannot('update', $key) disabled @endcannot"
           role="button">
            @lang('general.edit')
        </a>
        <a href="{{ route('keys.index') }}" class="btn btn-link" role="button">
            @lang('general.cancel')
        </a>
    </div>
</div>
<!-- ./ card -->

@can('delete', $key)
    <!-- confirmation modal -->
    <x-modals.confirmation
        action="{{ route('keys.destroy', $key) }}"
        confirmationText="{{ $key->username }}"
        buttonText="{{ __('key/messages.delete_confirmation_button') }}">

        <div class="alert alert-warning" role="alert">
            @lang('key/messages.delete_confirmation_warning', ['username' => $key->username])
        </div>
    </x-modals.confirmation>
    <!-- ./ confirmation modal -->
@endcan

@unless(empty($key->private))
    <!-- download private key modal -->
    <div class="modal fade" id="download-private-key">
        <!-- modal-dialog -->
        <div class="modal-dialog">
            <!-- modal-content -->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('key/title.download_private_key')</h4>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="@lang('general.close')">
                        <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    @can('update', $key)
                        @lang('key/messages.download_private_key_help')
                    @endcan
                    @cannot('update', $key)
                        @lang('key/messages.download_private_key_forbidden')
                    @endcannot
                </div>
                <div class="modal-footer justify-content-between">
                        <a href="@can('update', $key) {{ route('keys.download', $key) }} @endcan" class="btn btn-primary @cannot('update', $key) disabled @endcannot" role="button"
                           id="download-button">
                            <i class="fa fa-download"></i> @lang('key/messages.download_private_key')
                        </a>
                    <button type="button" class="btn btn-link" data-dismiss="modal">
                        @lang('general.cancel')
                    </button>
                </div>
            </div>
            <!-- ./modal-content -->
        </div>
        <!-- ./modal-dialog -->
    </div>
    <!-- ./download private key modal -->
@endunless

@push('scripts')
    <script>
        $('#download-button').click(function () {
            $('.hide-after-download').addClass("d-none");
            $('#download-private-key').modal('hide');
        })
    </script>
@endpush
