<div class="card">
    <div class="card-header">
        <h2 class="card-title">
            {{ $keygroup->present()->name }}
        </h2>
    </div>

    <div class="card-body">
        <div class="row">
            <!-- left column -->
            <div class="col-md-6">

                <h3>@lang('keygroup/messages.basic_information_section')</h3>

                <dl class="row">
                    <!-- name -->
                    <dt class="col-sm-3">
                        <strong>@lang('keygroup/model.name')</strong>
                    </dt>
                    <dd class="col-sm-9">
                        {{ $keygroup->present()->name }}
                    </dd>
                    <!-- ./ name -->

                    <!-- description -->
                    <dt class="col-sm-3">
                        <strong>@lang('keygroup/model.description')</strong>
                    </dt>
                    <dd class="col-sm-9">
                        {{ $keygroup->present()->description }}
                    </dd>
                    <!-- ./ description -->
                </dl>

                <h3>Rules</h3>

                <!-- rules -->
                <dl class="row">
                    <dt class="col-sm-3">
                        <strong>@lang('keygroup/table.rules')</strong>
                    </dt>
                    <dd class="col-sm-9">
                        {{ $keygroup->present()->rulesCount() }}
                    </dd>
                </dl>
                <!-- ./ rules -->

            </div>
            <!-- ./ left column -->
            <!-- right column -->
            <div class="col-md-6">

                <h3>@lang('keygroup/messages.group_members_section')</h3>

                <!-- keys -->
                <dl class="row">

                    <dt class="col-sm-3">
                        <strong>@lang('keygroup/model.keys')</strong>
                    </dt>
                    <dd class="col-sm-9">
                        <p>{{ $keygroup->keys->count() }} @lang('key/model.item')</p>
                        <ul class="list-inline">
                            @foreach($keygroup->keys as $key)
                                <li class="list-inline-item">
                                    <a href="{{ route('keys.show', $key->id) }}">
                                        {{ $key->username }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </dd>
                </dl>
                <!-- ./ keys -->

                <fieldset class="mt-3">
                    <legend>@lang('keygroup/messages.danger_zone_section')</legend>

                    @can('delete', $keygroup)
                    <ul class="list-group border border-danger">
                        <li class="list-group-item">
                            <strong>@lang('keygroup/messages.delete_button')</strong>
                            <button type="button" class="btn btn-outline-danger btn-sm float-right"
                                    data-toggle="modal"
                                    data-target="#confirmationModal">
                                @lang('keygroup/messages.delete_button')
                            </button>
                            <p>@lang('keygroup/messages.delete_help')</p>
                        </li>
                    </ul>
                    @else
                        <p class="text-muted">@lang('keygroup/messages.delete_avoided')</p>
                    @endcan
                </fieldset>

            </div>
            <!-- ./ right column -->
        </div>
    </div>

    <div class="card-footer">
        <a href="{{ route('keygroups.edit', $keygroup) }}" class="btn btn-primary @cannot('update', $keygroup) disabled @endcannot" role="button">
            @lang('general.edit')
        </a>
        <a href="{{ route('keygroups.index') }}" class="btn btn-link" role="button">
            @lang('general.cancel')
        </a>
    </div>
</div>
<!-- ./ card -->

<!-- confirmation modal -->
<x-modals.confirmation
    action="{{ route('keygroups.destroy', $keygroup) }}"
    confirmationText="{{ $keygroup->name }}"
    buttonText="{{ __('keygroup/messages.delete_confirmation_button') }}">

    <div class="alert alert-warning" role="alert">
        @lang('keygroup/messages.delete_confirmation_warning', ['name' => $keygroup->name])
    </div>
</x-modals.confirmation>
<!-- ./ confirmation modal -->


