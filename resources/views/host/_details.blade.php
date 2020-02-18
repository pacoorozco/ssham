<div class="card">
    <div class="card-header bg-cyan">
        <h3 class="card-title">{{ $host->full_hostname }}  @if(!$host->enabled)<span
                class="badge badge-secondary">{{ __('general.disabled') }}</span>@endif </h3>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- left column -->
            <div class="col-md-6">

                <h4>@lang('host/title.host_information_section')</h4>

                <!-- hostname -->
                <div class="row">
                    <div class="col-2">
                        <strong>@lang('host/model.hostname')</strong>
                    </div>
                    <div class="col-10">
                        {{ $host->hostname }}
                    </div>
                </div>
                <!-- ./ hostname -->

                <!-- username -->
                <div class="row">
                    <div class="col-2">
                        <strong>@lang('host/model.username')</strong>
                    </div>
                    <div class="col-10">
                        {{ $host->username }}
                    </div>
                </div>
                <!-- ./ username -->

                <h4>@lang('host/title.advanced_config_section')</h4>

                <!-- port -->
                <div class="row">
                    <div class="col-2">
                        <strong>@lang('host/model.port')</strong>
                    </div>
                    <div class="col-10">
                        {{ $host->port }}
                    </div>
                </div>
                <!-- ./ port -->

                <!-- authorized_keys_file -->
                <div class="row">
                    <div class="col-2">
                        <strong>@lang('host/model.authorized_keys_file')</strong>
                    </div>
                    <div class="col-10">
                        {{ $host->authorized_keys_file }}
                    </div>
                </div>
                <!-- ./ authorized_keys_file -->
            </div>
            <!-- ./ left column -->
            <!-- right column -->
            <div class="col-md-6">

                <h4>@lang('host/title.membership_section')</h4>

                <!-- groups -->
                <div class="row">
                    <div class="col-2">
                        <strong>@lang('host/model.groups')</strong>
                    </div>
                    <div class="col-10">
                        <p>{{ $host->groups->count() }} @lang('hostgroup/model.item')</p>
                        <ul class="list-inline">
                            @foreach($host->groups as $group)
                                <li class="list-inline-item"><a
                                        href="{{ route('hostgroups.show', $group->id) }}">{{ $group->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <!-- ./ groups -->

                <h4>@lang('host/title.status_section')</h4>

                <!-- enabled -->
                <div class="row">
                    <div class="col-2">
                        <strong>@lang('host/model.enabled')</strong>
                    </div>
                    <div class="col-10">
                        @if ($host->enabled)
                            <span class="badge badge-pill badge-success">{{ __('general.enabled') }}</span>
                        @else
                            <span class="badge badge-pill badge-secondary">{{ __('general.disabled') }}</span>
                        @endif
                    </div>
                </div>
                <!-- ./ enabled -->

                <!-- synced -->
                <div class="row">
                    <div class="col-2">
                        <strong>@lang('host/model.synced')</strong>
                    </div>
                    <div class="col-10">
                        @if ($host->synced)
                            <span class="badge badge-pill badge-success">{{ __('general.yes') }}</span>
                        @else
                            <span class="badge badge-pill badge-secondary">{{ __('general.no') }}</span>
                        @endif
                    </div>
                </div>
                <!-- ./ synced -->

                <!-- created at -->
                <div class="row">
                    <div class="col-2">
                        <strong>@lang('host/model.created_at')</strong>
                    </div>
                    <div class="col-10">
                        {{ $host->created_at }}
                    </div>
                </div>
                <!-- ./ created at -->

                <!-- last_rotation -->
                <div class="row">
                    <div class="col-2">
                        <strong>@lang('host/model.last_rotation')</strong>
                    </div>
                    <div class="col-10">
                        {{ $host->status_code }}
                        @if (is_null($host->last_rotation))
                            ({{ $host->created_at }})
                        @else
                            ({{ $host->last_rotation }})
                        @endif
                    </div>
                </div>
                <!-- ./ synced -->
            </div>
            <!-- ./ right column -->
        </div>

    </div>
    <div class="card-footer">
        <a href="{{ route('hosts.index') }}" class="btn btn-primary" role="button">
            <i class="fa fa-arrow-left"></i> @lang('general.back')
        </a>
        @if ($action == 'show')
            <a href="{{ route('hosts.edit', $host->id) }}" class="btn btn-primary" role="button">
                <i class="fa fa-pen"></i> @lang('general.edit')
            </a>
        @else
            <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> @lang('general.delete')</button>
        @endif
    </div>
</div>
