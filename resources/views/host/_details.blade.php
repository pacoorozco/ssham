
<div class="row">
    <div class="col-xs-12">

        <h2>{{ $host->getFullHostname() }}</h2>

        <!-- groups -->
        <strong>{!! trans('host/model.groups') !!}</strong>
        <pre>
            @foreach($host->hostgroups as $group)
                {{ $group->name }}
            @endforeach
        </pre>
        <!-- ./ groups -->

        <!-- enabled -->
        <strong>{!! trans('host/model.enabled') !!}</strong>
        <pre>{{ ($host->enabled) ? trans('general.yes') : trans('general.no') }}</pre>
        <!-- ./ enabled -->

    </div>
</div>

<div class="row">
    <div class="col-xs-12">

        <div class="form-group">
            <div class="controls">
                <a href="{!! route('hosts.index') !!}" class="btn btn-primary">{!! trans('button.back') !!}</a>
                @if ($action == 'show')
                <a href="{!! route('hosts.edit', $host->id) !!}" class="btn btn-primary">{!! trans('button.edit') !!}</a>
                @else
                {!! Form::button(trans('button.delete'), array('type' => 'submit', 'class' => 'btn btn-danger')) !!}
                @endif
            </div>
        </div>

    </div>
</div>
