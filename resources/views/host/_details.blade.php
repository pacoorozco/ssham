<div class="card">
    <div class="card-header @unless($host->enabled) bg-gray-dark @endunless">
        <h2 class="card-title">
            {{ $host->present()->full_hostname }}
            @unless($host->enabled)
                {{ $host->present()->enabledAsBadge() }}
            @endunless
        </h2>
    </div>

    <div class="card-body">
        <div class="row">
            <!-- left column -->
            <div class="col-md-6">

                <h3>@lang('host/title.host_information_section')</h3>
                <dl class="row">
                    <!-- hostname -->
                    <dt class="col-sm-3">
                        <strong>@lang('host/model.hostname')</strong>
                    </dt>
                    <dd class="col-sm-9">
                        {{ $host->present()->hostname }}
                    </dd>
                    <!-- ./ hostname -->

                    <!-- username -->
                    <dt class="col-sm-3">
                        <strong>@lang('host/model.username')</strong>
                    </dt>
                    <dd class="col-sm-9">
                        {{ $host->present()->username }}
                    </dd>
                </dl>
                <!-- ./ username -->

                <h3>@lang('host/title.advanced_config_section')</h3>
                <dl class="row">
                    <!-- port -->
                    <dt class="col-sm-3">
                        <strong>@lang('host/model.port')</strong>
                    </dt>
                    <dd class="col-sm-9">
                        {{ $host->present()->port }}
                        @unless($host->hasCustomPort())
                            (set by <a href="{{ route('settings.index') }}">settings</a>)
                        @endunless
                    </dd>
                    <!-- ./ port -->

                    <!-- authorized_keys_file -->
                    <dt class="col-sm-3">
                        <strong>@lang('host/model.authorized_keys_file')</strong>
                    </dt>
                    <dd class="col-sm-9">
                        {{ $host->present()->authorized_keys_file }}
                        @unless($host->hasCustomAuthorizedKeysFile())
                            (set by <a href="{{ route('settings.index') }}">settings</a>)
                        @endunless
                    </dd>
                </dl>
                <!-- ./ authorized_keys_file -->
            </div>
            <!-- ./ left column -->
            <!-- right column -->
            <div class="col-md-6">

                <h3>@lang('host/title.membership_section')</h3>
                <!-- groups -->
                <dl class="row">
                    <dt class="col-sm-3">
                        <strong>@lang('host/model.groups')</strong>
                    </dt>
                    <dd class="col-sm-9">
                        @forelse($host->groups as $group)
                            @if ($loop->first)
                                {{ $host->groups->count() }} @lang('hostgroup/model.item')
                                <ul class="list-inline">
                                    @endif

                                    <li class="list-inline-item">
                                        <a href="{{ route('hostgroups.show', $group->id) }}">{{ $group->name }}</a>
                                    </li>

                                    @if ($loop->last)
                                </ul>
                            @endif
                        @empty
                            @lang('host/messages.groups_empty')
                        @endforelse
                    </dd>
                </dl>
                <!-- ./ groups -->

                <h3>@lang('host/title.status_section')</h3>
                <dl class="row">
                    <!-- created at -->
                    <dt class="col-3">
                        <strong>@lang('host/model.created_at')</strong>
                    </dt>
                    <dd class="col-9">
                        {{ $host->present()->createdAtForHumans() }} ({{ $host->present()->created_at }})
                    </dd>
                    <!-- ./ created at -->

                    <!-- enabled -->
                    <dt class="col-3">
                        <strong>@lang('host/model.enabled')</strong>
                    </dt>
                    <dd class="col-9">
                        {{ $host->present()->enabledAsBadge() }}
                    </dd>
                    <!-- ./ enabled -->

                    <!-- synced -->
                    <dt class="col-3">
                        <strong>@lang('host/model.synced')</strong>
                    </dt>
                    <dd class="col-9">
                        {{ $host->present()->pendingSyncAsBadge() }}
                    </dd>
                    <!-- ./ synced -->

                    <!-- last_rotation -->
                    <dt class="col-3">
                        <strong>@lang('host/model.last_rotation')</strong>
                    </dt>
                    <dd class="col-9">
                        {{ $host->present()->status_code }}
                        ({{ $host->present()->lastRotationForHumans() }})
                    </dd>
                    <!-- ./ last_rotation -->
                </dl>

                <h3>@lang('host/messages.danger_zone_section')</h3>

                @can('delete', $host)
                    <ul class="list-group border border-danger">
                        <li class="list-group-item">
                            <strong>@lang('host/messages.delete_button')</strong>
                            <button type="button" class="btn btn-outline-danger btn-sm float-right"
                                    data-toggle="modal"
                                    data-target="#confirmationModal">
                                @lang('host/messages.delete_button')
                            </button>
                            <p>@lang('host/messages.delete_host_help')</p>
                        </li>
                    </ul>
                @else
                    <p class="text-muted">@lang('host/messages.delete_avoided')</p>
                @endcan

            </div>
            <!-- ./ right column -->
        </div>

    </div>
    <div class="card-footer">
        <a href="{{ route('hosts.edit', $host->id) }}" class="btn btn-primary @cannot('update', $host) disabled @endcannot" role="button">
            @lang('general.edit')
        </a>
        <a href="{{ route('hosts.index') }}" class="btn btn-link" role="button">
            @lang('general.cancel')
        </a>
    </div>

    <!-- confirmation modal -->
    <x-modals.confirmation
        action="{{ route('hosts.destroy', $host) }}"
        confirmationText="{{ $host->hostname }}"
        buttonText="{{ __('host/messages.delete_confirmation_button') }}">

        <div class="alert alert-warning" role="alert">
            @lang('host/messages.delete_confirmation_warning', ['hostname' => $host->hostname])
        </div>

    </x-modals.confirmation>
    <!-- ./ confirmation modal -->
</div>
