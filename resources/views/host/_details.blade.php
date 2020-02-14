<div class="card">
    <div class="card-header bg-cyan">
        <h3 class="card-title">{{ $host->full_hostname }}  @if(!$host->enabled)<span class="badge badge-secondary">{{ __('general.disabled') }}</span>@endif </h3>
    </div>
    <div class="card-body">

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

        <!-- type -->
        <div class="row">
            <div class="col-2">
                <strong>@lang('host/model.type')</strong>
            </div>
            <div class="col-10">
                {{ $host->type }}
            </div>
        </div>
        <!-- ./ type -->

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

        <!-- groups -->
        <div class="row">
            <div class="col-2">
                <strong>@lang('host/model.groups')</strong>
            </div>
            <div class="col-10">
                <p>{{ $host->groups->count() }} @lang('hostgroup/model.item')</p>
                <ul class="list-inline">
                    @foreach($host->groups as $group)
                        <li class="list-inline-item"><a href="{{ route('hostgroups.show', $group->id) }}">{{ $group->name }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
        <!-- ./ groups -->

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

        <!-- last_rotation -->
        <div class="row">
            <div class="col-2">
                <strong>@lang('host/model.last_rotation')</strong>
            </div>
            <div class="col-10">
                @if (is_null($host->last_rotation))
                    @lang('host/messages.never_rotated')
                @else
                    {{ $host->last_rotation }}
                @endif
            </div>
        </div>
        <!-- ./ synced -->

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
