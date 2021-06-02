<div class="card">
    <div class="card-header">
        <h2 class="card-title">
            {{ $hostgroup->present()->name }}
        </h2>
    </div>

    <div class="card-body">
        <div class="row">
            <!-- left column -->
            <div class="col-md-6">

                <h3>@lang('hostgroup/messages.basic_information_section')</h3>

                <dl class="row">
                    <!-- name -->
                    <dt class="col-sm-3">
                        <strong>@lang('hostgroup/model.name')</strong>
                    </dt>
                    <dd class="col-sm-9">
                        {{ $hostgroup->present()->name }}
                    </dd>
                    <!-- ./ name -->

                    <!-- description -->
                    <dt class="col-sm-3">
                        <strong>@lang('hostgroup/model.description')</strong>
                    </dt>
                    <dd class="col-sm-9">
                        {{ $hostgroup->present()->description }}
                    </dd>
                    <!-- ./ description -->
                </dl>

                <h3>Rules</h3>

                <!-- rules -->
                <dl class="row">
                    <dt class="col-sm-3">
                        <strong>@lang('hostgroup/table.rules')</strong>
                    </dt>
                    <dd class="col-sm-9">
                        {{ $hostgroup->present()->rulesCount() }}
                    </dd>
                </dl>
                <!-- ./ rules -->

            </div>
            <!-- ./ left column -->
            <!-- right column -->
            <div class="col-md-6">

                <h3>@lang('hostgroup/messages.group_members_section')</h3>

                <!-- groups -->
                <dl class="row">
                    <dt class="col-sm-3">
                        <strong>@lang('hostgroup/model.hosts')</strong>
                    </dt>
                    <dd class="col-sm-9">
                        <p>{{ $hostgroup->hosts->count() }} @lang('host/model.item')</p>
                        <ul class="list-inline">
                            @foreach($hostgroup->hosts as $host)
                                <li class="list-inline-item"><a
                                        href="{{ route('hosts.show', $host->id) }}">{{ $host->hostname }}</a></li>
                            @endforeach
                        </ul>
                    </dd>
                </dl>
                <!-- ./ groups -->

                <h3 class="mt-3">
                    @lang('hostgroup/messages.danger_zone_section')
                </h3>

                <ul class="list-group border border-danger">
                    <li class="list-group-item">
                        <strong>@lang('hostgroup/messages.delete_button')</strong>
                        <button type="button" class="btn btn-outline-danger btn-sm float-right"
                                data-toggle="modal"
                                data-target="#confirmationModal">
                            @lang('hostgroup/messages.delete_button')
                        </button>
                        <p>@lang('hostgroup/messages.delete_help')</p>
                    </li>
                </ul>

            </div>
            <!-- ./ right column -->
        </div>
    </div>

    <div class="card-footer">
        <a href="{{ route('hostgroups.edit', $hostgroup->id) }}" class="btn btn-primary" role="button">
            @lang('general.edit')
        </a>
        <a href="{{ route('hostgroups.index') }}" class="btn btn-link" role="button">
            @lang('general.cancel')
        </a>
    </div>
</div>

<!-- confirmation modal -->
<x-modals.confirmation
    action="{{ route('hostgroups.destroy', $hostgroup) }}"
    confirmationText="{{ $hostgroup->name }}"
    buttonText="{{ __('hostgroup/messages.delete_confirmation_button') }}">

    <div class="alert alert-warning" role="alert">
        @lang('hostgroup/messages.delete_confirmation_warning', ['name' => $hostgroup->name])
    </div>
</x-modals.confirmation>
<!-- ./ confirmation modal -->


