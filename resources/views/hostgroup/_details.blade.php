<div class="card">
    <div class="card-header bg-cyan">
        <h3 class="card-title">{{ $hostgroup->name }}</h3>
    </div>
    <div class="card-body">
        <!-- name -->
        <div class="row">
            <div class="col-2">
                <strong>@lang('hostgroup/model.name')</strong>
            </div>
            <div class="col-10">
                {{ $hostgroup->name }}
            </div>
        </div>
        <!-- ./ name -->

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

        <!-- hosts -->
        <div class="row">
            <div class="col-2">
                <strong>@lang('hostgroup/model.hosts')</strong>
            </div>
            <div class="col-10">
                <p>{{ $hostgroup->hosts->count() }} @lang('host/model.item')</p>
                <ul class="list-inline">
                    @foreach($hostgroup->hosts as $host)
                        <li class="list-inline-item"><a href="{{ route('hosts.show', $host->id) }}">{{ $host->hostname }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
        <!-- ./ hosts -->

        <!-- rules -->
        <div class="row">
            <div class="col-2">
                <strong>@lang('hostgroup/model.rules')</strong>
            </div>
            <div class="col-10">
                {{ trans_choice('hostgroup/messages.present_in', $hostgroup->getNumberOfRelatedRules(), ['value' => $hostgroup->getNumberOfRelatedRules()]) }}
            </div>
        </div>
        <!-- ./ rules -->

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


