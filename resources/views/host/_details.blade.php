<div class="card">
    <div class="card-header">
        <h2>{{ $host->getFullHostname() }}  @if(!$host->enabled)<span class="badge badge-secondary">{{ __('general.disabled') }}</span>@endif </h2>
    </div>
    <div class="card-body">

        <!-- groups -->
        <div class="row">
            <div class="col-2">
                <strong>@lang('host/model.groups')</strong>
            </div>
            <div class="col-10">
                @forelse($host->hostgroups as $group)
                    <span class="badge badge-primary">{{ $group->name }}</span>
                @empty
                    @lang('host/model.no_groups')
                @endforelse
            </div>
        </div>
        <!-- ./ groups -->

        <!-- enabled -->
        <div class="row">
            <div class="col-2">
                <strong>@lang('host/model.enabled')</strong>
            </div>
            <div class="col-10">
                {{ ($host->enabled) ? __('general.yes') : __('general.no') }}
            </div>
        </div>
        <!-- ./ enabled -->

    </div>
    <div class="card-footer">
        <a href="{{ route('users.index') }}" class="btn btn-primary" role="button">
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
