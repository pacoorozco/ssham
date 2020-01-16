<div class="card">
    <div class="card-header">
        <h2>{{ $hostgroup->name }}</h2>
    </div>
    <div class="card-body">
        <!-- description -->
        <div class="row">
            <div class="col-2">
                <strong>@lang('hostgroup/model.description')</strong>
            </div>
            <div class="col-10">
                {{ $hostgroup->description }}
            </div>
        </div>
        <!-- ./ description -->

        <!-- groups -->
        <div class="row">
            <div class="col-2">
                <strong>@lang('hostgroup/model.hosts')</strong>
            </div>
            <div class="col-10">
                <ul>
                    @forelse($hostgroup->hosts as $host)
                        <li>{{ $host->hostname }}</li>
                    @empty
                        <li>@lang('hostgroup/model.no_hosts')</li>
                    @endforelse
                </ul>
            </div>
        </div>
        <!-- ./ groups -->

    </div>
</div>

</div>
<div class="card-footer">
    <a href="{{ route('hostgroups.index') }}" class="btn btn-primary" role="button">
        <i class="fa fa-arrow-left"></i> @lang('general.back')
    </a>
    @if ($action == 'show')
        <a href="{{ route('hostgroups.edit', $hostgroup->id) }}" class="btn btn-primary" role="button">
            <i class="fa fa-pen"></i> @lang('general.edit')
        </a>
    @else
        {!! Form::button('<i class="fa fa-trash"></i> ' . __('general.delete'), array('type' => 'submit', 'class' => 'btn btn-danger')) !!}
    @endif
</div>


